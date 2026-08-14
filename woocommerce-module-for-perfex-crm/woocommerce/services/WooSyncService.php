<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\CustomersRepository;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\OrdersRepository;
use WooCommerce\Repositories\ProductsRepository;
use WooCommerce\Repositories\StoreDTO;
use WooCommerce\Repositories\StoresRepository;
use WooCommerce\Repositories\SummaryRepository;
use WooCommerce\Services\GuestClientFactory;
use WooCommerce\Services\OrderConverter;
use WooCommerce\Services\PaymentModeService;
use WooCommerce\Services\PerfexClientGateway;
use WooCommerce\Services\PerfexClientResolver;
use WooCommerce\Services\PerfexInvoiceGateway;

/**
 * Concrete implementation of `SyncService` — the cron's per-store
 * worker. Each `check*()` method:
 *  1. Reads the store's cursor (orderPage / productPage / customerPage).
 *  2. Pages through Woo at `per_page=100`, up to `pages_per_tick`
 *     pages on this tick, upserting via the cache repo.
 *  3. Advances the cursor; resets to 1 on an empty page (end of stream).
 *  4. Persists the new cursor on `woocommerce_stores`.
 *
 * No DB or HTTP directly — everything is injected so unit tests run
 * without the SDK or a live MySQL.
 *
 * Spec refs: §12, §12.2.
 */
final class WooSyncService implements SyncService
{
    private const PER_PAGE = 100;

    private ApiClientFactory   $apiClients;
    private OrdersRepository   $ordersRepo;
    private ProductsRepository $productsRepo;
    private CustomersRepository $customersRepo;
    private SummaryRepository  $summaryRepo;
    private StoresRepository   $storesRepo;
    private LogRepository      $log;

    public function __construct(
        ApiClientFactory   $apiClients,
        OrdersRepository   $ordersRepo,
        ProductsRepository $productsRepo,
        CustomersRepository $customersRepo,
        SummaryRepository  $summaryRepo,
        StoresRepository   $storesRepo,
        LogRepository      $log
    ) {
        $this->apiClients    = $apiClients;
        $this->ordersRepo    = $ordersRepo;
        $this->productsRepo  = $productsRepo;
        $this->customersRepo = $customersRepo;
        $this->summaryRepo   = $summaryRepo;
        $this->storesRepo    = $storesRepo;
        $this->log           = $log;
    }

    public function summary(StoreDTO $store): void
    {
        $reports = $this->apiClients->reports($store);

        $this->summaryRepo->saveForStore(
            (int) $store->storeId,
            self::asArray($reports->getTotals('customers')),
            self::asArray($reports->getTotals('orders')),
            self::asArray($reports->getTotals('products')),
        );
    }

    public function checkOrders(StoreDTO $store): void
    {
        $client = $this->apiClients->orders($store);

        $this->paginate(
            store:        $store,
            startPage:    $store->orderPage,
            cursorColumn: 'orderPage',
            fetch:        fn(int $page): array => self::asList($client->getAll([
                'per_page' => self::PER_PAGE,
                'page'     => $page,
            ])),
            upsert:       function (array $row) use ($store): void {
                if (! isset($row['id'])) { return; }
                $storeId = (int) $store->storeId;
                $wooId   = (int) $row['id'];

                $this->ordersRepo->upsertByWooId(
                    $storeId,
                    $wooId,
                    self::orderRowFor($row),
                );

                if ($store->autoConvertOrder) {
                    $this->maybeAutoConvertOrder($store, $wooId, $row);
                }
            },
        );
    }

    /**
     * Convert a Woo order into a Perfex invoice when:
     *   - the store has `auto_convert_order = 1`
     *   - the order's status appears in `auto_invoice_statuses`
     *     (comma-separated list on the store row)
     *   - the cache row isn't already linked to an invoice
     *
     * Mirrors `Woocommerce_invoice::create_invoice` minus the HTTP +
     * permission layer. Idempotent — OrderConverter::convert returns
     * the existing invoice id when one already exists for the woo
     * order, so concurrent ticks don't create duplicates.
     *
     * @param array<string, mixed> $live  Decoded Woo order payload.
     */
    private function maybeAutoConvertOrder(StoreDTO $store, int $wooId, array $live): void
    {
        // Status gate: only orders matching one of the configured
        // auto-invoice statuses are eligible. Empty string in the
        // setting means "every status" — that mirrors the legacy
        // behaviour, but most tenants will pin to e.g. "completed".
        $statusList = trim((string) ($store->autoInvoiceStatuses ?? ''));
        if ($statusList !== '') {
            $allowed = array_filter(array_map('trim', explode(',', $statusList)));
            $status  = (string) ($live['status'] ?? '');
            if (! in_array($status, $allowed, true)) {
                return;
            }
        }

        $cache = $this->ordersRepo->findByWooId((int) $store->storeId, $wooId);
        if (is_array($cache) && ! empty($cache['invoice_id'])) {
            return; // already linked
        }

        $db      = $this->ordersRepo->db();
        $storeId = (int) $store->storeId;

        // Build the converter graph the same way Woocommerce_invoice
        // does in its buildOrderConverter() — just inlined here so
        // the cron stays self-contained.
        $custRepo = new \WooCommerce\Repositories\CustomerFieldMappingRepository($db);
        $prodRepo = new \WooCommerce\Repositories\ProductFieldMappingRepository($db);
        $ordRepo  = new \WooCommerce\Repositories\OrderFieldMappingRepository($db);
        $presets  = (new \WooCommerce\Libraries\PresetLoader($custRepo, $prodRepo, $ordRepo))
            ->readPresets();
        $resolver = new \WooCommerce\Libraries\MappingResolver(
            $presets, $custRepo, $prodRepo, $ordRepo
        );
        $transformer    = new \WooCommerce\Libraries\WooToPerfexTransformer($this->log);
        $paymentMode    = new PaymentModeService($db);
        $clientGateway  = new PerfexClientGateway($db);
        $invoiceGateway = new PerfexInvoiceGateway($db);
        $clientResolver = new PerfexClientResolver($db);
        $guestFactory   = new GuestClientFactory($clientGateway, $this->log);

        $converter = new OrderConverter(
            $invoiceGateway,
            $guestFactory,
            $resolver,
            $transformer,
            $paymentMode,
            $this->log,
            $clientResolver,
        );

        try {
            $result = $converter->convert($live, $store);
        } catch (\Throwable $e) {
            // OrderConverter already logs the failure with full context;
            // we just bail and let the next cron tick (or the manual
            // Convert button) try again.
            return;
        }

        $invoiceId = (int) ($result['invoice_id'] ?? 0);
        if ($invoiceId <= 0) {
            return;
        }

        $this->ordersRepo->upsertByWooId($storeId, $wooId, [
            'invoice_id'     => $invoiceId,
            'last_synced_at' => date('Y-m-d H:i:s'),
        ]);

        $this->log->write(
            LogRepository::LEVEL_INFO,
            'auto_convert_order.linked',
            [
                'woo_order_id' => $wooId,
                'invoice_id'   => $invoiceId,
                'reused'       => (bool) ($result['link_existing'] ?? false),
            ],
            $storeId,
        );
    }

    public function checkProducts(StoreDTO $store): void
    {
        $client = $this->apiClients->products($store);

        $this->paginate(
            store:        $store,
            startPage:    $store->productPage,
            cursorColumn: 'productPage',
            fetch:        fn(int $page): array => self::asList($client->getAll([
                'per_page' => self::PER_PAGE,
                'page'     => $page,
            ])),
            upsert:       function (array $row) use ($store): void {
                if (! isset($row['id'])) { return; }
                $storeId = (int) $store->storeId;
                $wooId   = (int) $row['id'];

                $this->productsRepo->upsertByWooId(
                    $storeId,
                    $wooId,
                    self::productRowFor($row),
                );

                // Auto-convert hook: when the store has the flag on,
                // newly-cached unlinked products land in tblitems via
                // the same path the manual "Add as Sales Item" button
                // uses. Idempotent — short-circuits on existing
                // itemid, so re-syncs don't duplicate.
                if ($store->autoConvertProduct) {
                    $this->maybeAutoLinkProduct($storeId, $wooId, $row);
                }
            },
        );
    }

    /**
     * Insert into `tblitems` and stamp `tblwoocommerce_products.itemid`
     * when the store has `auto_convert_product = 1` and the cache row
     * isn't already linked. Mirrors the manual `link_product`
     * controller flow (services side — no HTTP, no permission gates,
     * no JSON response).
     *
     * @param array<string, mixed> $live The decoded Woo product payload
     */
    private function maybeAutoLinkProduct(int $storeId, int $wooId, array $live): void
    {
        $cache = $this->productsRepo->findByWooId($storeId, $wooId);
        if (! is_array($cache) || ! empty($cache['itemid'])) {
            return; // already linked
        }

        // Talk to the DB through the repo's own driver — keeps the
        // service free of CI super-object knowledge.
        $db = $this->productsRepo->db();

        $description     = (string) ($live['name']             ?? ($cache['name'] ?? ''));
        $longDescription = strip_tags((string) ($live['description'] ?? ''));
        $rate            = (string) ($live['regular_price']    ?? ($cache['price'] ?? '0'));

        $row = [
            'description'      => $description,
            'long_description' => $longDescription,
            'rate'             => is_numeric($rate) ? (float) $rate : 0.0,
            'tax'              => 0,
            'tax2'             => 0,
            'unit'             => '',
            'group_id'         => 0,
        ];
        $row = array_filter(
            $row,
            static fn($_, string $col) => $db->field_exists($col, db_prefix() . 'items'),
            ARRAY_FILTER_USE_BOTH
        );

        $db->insert(db_prefix() . 'items', $row);
        $itemId = (int) $db->insert_id();
        if ($itemId <= 0) {
            return;
        }

        $this->productsRepo->upsertByWooId($storeId, $wooId, [
            'itemid'         => $itemId,
            'last_synced_at' => date('Y-m-d H:i:s'),
        ]);

        $this->log->write(
            LogRepository::LEVEL_INFO,
            'auto_convert_product.linked',
            [
                'woo_product_id' => $wooId,
                'item_id'        => $itemId,
            ],
            $storeId,
        );
    }

    public function checkCustomers(StoreDTO $store): void
    {
        $client = $this->apiClients->customers($store);

        $this->paginate(
            store:        $store,
            startPage:    $store->customerPage,
            cursorColumn: 'customerPage',
            fetch:        fn(int $page): array => self::asList($client->getAll([
                'per_page' => self::PER_PAGE,
                'page'     => $page,
            ])),
            upsert:       function (array $row) use ($store): void {
                if (! isset($row['id'])) { return; }
                $storeId = (int) $store->storeId;
                $wooId   = (int) $row['id'];

                $this->customersRepo->upsertByWooId(
                    $storeId,
                    $wooId,
                    self::customerRowFor($row),
                );

                if ($store->autoConvertCustomer) {
                    $this->maybeAutoLinkCustomer($storeId, $wooId, $row);
                }
            },
        );
    }

    /**
     * Create (or find + link) a Perfex client for a Woo customer when
     * the store has `auto_convert_customer = 1` and the cache row
     * isn't already linked. Mirrors `Woocommerce_invoice::import_customer`
     * minus the HTTP layer.
     *
     * @param array<string, mixed> $live Decoded Woo customer payload.
     */
    private function maybeAutoLinkCustomer(int $storeId, int $wooId, array $live): void
    {
        $cache = $this->customersRepo->findByWooId($storeId, $wooId);
        if (! is_array($cache) || ! empty($cache['userid'])) {
            return; // already linked
        }

        // Project the Woo customer onto the order-shaped payload that
        // GuestClientFactory expects — it reads $payload['billing'].
        $billing = isset($live['billing']) && is_array($live['billing']) ? $live['billing'] : [];
        if (empty($billing['email']) && ! empty($live['email'])) {
            $billing['email'] = (string) $live['email'];
        }
        if (empty($billing['first_name']) && ! empty($live['first_name'])) {
            $billing['first_name'] = (string) $live['first_name'];
        }
        if (empty($billing['last_name']) && ! empty($live['last_name'])) {
            $billing['last_name'] = (string) $live['last_name'];
        }

        $db       = $this->customersRepo->db();
        $gateway  = new PerfexClientGateway($db);
        $factory  = new GuestClientFactory($gateway, $this->log);

        try {
            $clientId = $factory->findOrCreate(['billing' => $billing], $storeId);
        } catch (\Throwable $e) {
            $this->log->write(
                LogRepository::LEVEL_ERROR,
                'auto_convert_customer.failed',
                [
                    'woo_customer_id' => $wooId,
                    'message'         => $e->getMessage(),
                ],
                $storeId,
            );
            return;
        }

        // Stamp woo_id + store_id on tblclients so the next
        // ClientResolver lookup matches by that key (mirrors the
        // manual import flow's stamping). Then mirror the Perfex
        // client id back onto the Woo cache row so the customers list
        // flips to "linked".
        $db
            ->where('userid', $clientId)
            ->update(db_prefix() . 'clients', [
                'woo_id'   => $wooId,
                'store_id' => $storeId,
            ]);

        $this->customersRepo->upsertByWooId($storeId, $wooId, [
            'userid'         => $clientId,
            'last_synced_at' => date('Y-m-d H:i:s'),
        ]);

        $this->log->write(
            LogRepository::LEVEL_INFO,
            'auto_convert_customer.linked',
            [
                'woo_customer_id' => $wooId,
                'client_id'       => $clientId,
            ],
            $storeId,
        );
    }

    /**
     * Pagination loop shared by all three resource methods.
     *
     * @param callable(int): list<array<string, mixed>> $fetch
     * @param callable(array<string, mixed>): void $upsert
     */
    private function paginate(
        StoreDTO $store,
        int $startPage,
        string $cursorColumn,
        callable $fetch,
        callable $upsert
    ): void {
        $page    = max(1, $startPage);
        $remaining = max(1, $store->pagesPerTick);
        $newCursor = $page;

        while ($remaining-- > 0) {
            $rows = $fetch($page);

            if ($rows === []) {
                // End of stream — reset to page 1 so the next tick
                // starts fresh and re-syncs any deletes/updates.
                $newCursor = 1;
                break;
            }

            foreach ($rows as $row) {
                $upsert($row);
            }

            // Short page = last page; advance to next tick's start (1)
            // so we don't endlessly re-fetch the tail.
            if (count($rows) < self::PER_PAGE) {
                $newCursor = 1;
                break;
            }

            $newCursor = $page + 1;
            $page++;
        }

        if ($newCursor !== $startPage) {
            $this->storesRepo->updateCursors(
                (int) $store->storeId,
                orderPage:    $cursorColumn === 'orderPage'    ? $newCursor : null,
                productPage:  $cursorColumn === 'productPage'  ? $newCursor : null,
                customerPage: $cursorColumn === 'customerPage' ? $newCursor : null,
            );
        }
    }

    /**
     * @param mixed $maybeArrayOrObject
     * @return array<string, mixed>
     */
    private static function asArray(mixed $maybeArrayOrObject): array
    {
        if (is_array($maybeArrayOrObject)) {
            return $maybeArrayOrObject;
        }
        if (is_object($maybeArrayOrObject)) {
            return json_decode(json_encode($maybeArrayOrObject) ?: '{}', true) ?: [];
        }
        return [];
    }

    /**
     * @param mixed $maybeList
     * @return list<array<string, mixed>>
     */
    private static function asList(mixed $maybeList): array
    {
        if (! is_array($maybeList)) {
            $maybeList = is_object($maybeList) ? (array) $maybeList : [];
        }

        $out = [];
        foreach ($maybeList as $item) {
            if (is_object($item)) {
                $item = json_decode(json_encode($item) ?: '{}', true);
            }
            if (is_array($item)) {
                $out[] = $item;
            }
        }
        return $out;
    }

    /**
     * Project the WC order payload onto the cache row shape.
     * @param array<string, mixed> $woo
     * @return array<string, mixed>
     */
    private static function orderRowFor(array $woo): array
    {
        $billing = is_array($woo['billing'] ?? null) ? $woo['billing'] : [];

        return [
            'order_number'  => (string) ($woo['number']   ?? $woo['id'] ?? ''),
            'customer_id'   => (int)    ($woo['customer_id'] ?? 0),
            'address'       => self::renderAddress($billing),
            'phone'         => (string) ($billing['phone'] ?? ''),
            'status'        => (string) ($woo['status']   ?? ''),
            'currency'      => (string) ($woo['currency'] ?? ''),
            'date_created'  => (string) ($woo['date_created']  ?? ''),
            'date_modified' => (string) ($woo['date_modified'] ?? ''),
            'total'         => (string) ($woo['total']    ?? '0'),
        ];
    }

    /**
     * @param array<string, mixed> $woo
     * @return array<string, mixed>
     */
    private static function productRowFor(array $woo): array
    {
        $images = is_array($woo['images'] ?? null) ? $woo['images'] : [];
        $picture = '';
        if (isset($images[0]) && is_array($images[0]) && isset($images[0]['src'])) {
            $picture = (string) $images[0]['src'];
        }
        $categories = is_array($woo['categories'] ?? null) ? $woo['categories'] : [];
        $catNames = [];
        foreach ($categories as $c) {
            if (is_array($c) && isset($c['name'])) {
                $catNames[] = (string) $c['name'];
            }
        }

        return [
            'name'          => (string) ($woo['name']      ?? ''),
            'permalink'     => (string) ($woo['permalink'] ?? ''),
            'type'          => (string) ($woo['type']      ?? ''),
            'status'        => (string) ($woo['status']    ?? ''),
            'sku'           => (string) ($woo['sku']       ?? ''),
            'price'         => (string) ($woo['price']     ?? ''),
            'sales'         => (string) ($woo['total_sales'] ?? '0'),
            'picture'       => $picture,
            'category'      => implode(', ', $catNames),
            'date_created'  => (string) ($woo['date_created']  ?? ''),
            'date_modified' => (string) ($woo['date_modified'] ?? ''),
        ];
    }

    /**
     * @param array<string, mixed> $woo
     * @return array<string, mixed>
     */
    private static function customerRowFor(array $woo): array
    {
        $billing = is_array($woo['billing'] ?? null) ? $woo['billing'] : [];

        return [
            'email'      => (string) ($woo['email']      ?? ''),
            'first_name' => (string) ($woo['first_name'] ?? $billing['first_name'] ?? ''),
            'last_name'  => (string) ($woo['last_name']  ?? $billing['last_name']  ?? ''),
            'phone'      => (string) ($billing['phone']  ?? ''),
            'role'       => (string) ($woo['role']       ?? ''),
            'username'   => (string) ($woo['username']   ?? ''),
            'avatar_url' => (string) ($woo['avatar_url'] ?? ''),
        ];
    }

    /**
     * @param array<string, mixed> $billing
     */
    private static function renderAddress(array $billing): string
    {
        $parts = [];
        foreach (['address_1', 'address_2', 'city', 'state', 'postcode', 'country'] as $k) {
            if (! empty($billing[$k])) {
                $parts[] = (string) $billing[$k];
            }
        }
        return implode(', ', $parts);
    }
}
