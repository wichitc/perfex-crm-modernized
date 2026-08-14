<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Libraries\CredentialCipher;
use WooCommerce\Libraries\JobQueue;
use WooCommerce\Repositories\JobsRepository;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\OrdersRepository;
use WooCommerce\Repositories\StoresRepository;
use WooCommerce\Services\DefaultApiClientFactory;

/**
 * General WooCommerce admin controller. v3 ships only the order
 * status-update + delete endpoints (T5.6, T5.7); the rest of the
 * screen lives in Phase 6.
 *
 * Every state-changing endpoint runs has_permission('woocommerce',
 * '', 'edit') or 'delete' per non-negotiable #4 (spec §9.2).
 *
 * @property CI_Input            $input
 * @property CI_Output           $output
 * @property CI_DB_query_builder|CI_DB_mysqli_driver $db
 */
class Woocommerce extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (function_exists('staff_logged_in') && ! staff_logged_in()) {
            redirect(admin_url('authentication/admin'));
        }
    }

    /**
     * Update the status of a Woo order. POST { order_id, status }.
     */
    /**
     * Orders list (T6.5). Page shell + Perfex `<app-filters>` Vue
     * widget; rows come over AJAX from `orders_table()`.
     */
    public function orders(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
        $storesRepo = new \WooCommerce\Repositories\StoresRepository($this->db, $cipher);

        $this->load->view('orders', [
            'title'           => _l('woocommerce_orders') . ' | ' . _l('woocommerce'),
            'stores'          => $storesRepo->listStores(),
            'active_store_id' => $this->resolveActiveStoreId($storesRepo->listStores()),
            'table'           => App_table::find('woo_orders'),
        ]);
    }

    /**
     * AJAX endpoint for the orders DataTable.
     */
    public function orders_table(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            ajax_access_denied();
            return;
        }
        if (! $this->input->is_ajax_request()) {
            show_404();
            return;
        }
        App_table::find('woo_orders')->output();
    }

    /**
     * @param list<\WooCommerce\Repositories\StoreDTO> $stores
     */
    private function resolveActiveStoreId(array $stores): ?int
    {
        $staffId = function_exists('get_staff_user_id') ? (int) get_staff_user_id() : 0;
        if ($staffId > 0) {
            $row = $this->db->select('store_id')
                ->where('staffid', $staffId)
                ->limit(1)
                ->get(db_prefix() . 'staff')
                ->row_array();
            if (is_array($row) && ! empty($row['store_id'])) {
                return (int) $row['store_id'];
            }
        }
        return $stores === [] ? null : (int) $stores[0]->storeId;
    }

    /**
     * Customers list (T6.9). Page shell + Vue <app-filters>; rows
     * come over AJAX from `customers_table()`.
     */
    public function customers(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
        $storesRepo = new \WooCommerce\Repositories\StoresRepository($this->db, $cipher);

        $stores        = $storesRepo->listStores();
        $activeStoreId = $this->resolveActiveStoreId($stores);

        $this->load->view('customers', [
            'title'           => _l('woocommerce_customers') . ' | ' . _l('woocommerce'),
            'stores'          => $stores,
            'active_store_id' => $activeStoreId,
            'table'           => App_table::find('woo_customers'),
        ]);
    }

    public function customers_table(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            ajax_access_denied();
            return;
        }
        if (! $this->input->is_ajax_request()) {
            show_404();
            return;
        }
        App_table::find('woo_customers')->output();
    }

    /**
     * Customer detail (T6.9).
     */
    public function customer(int $storeId = 0, int $wooCustomerId = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        if ($storeId <= 0 || $wooCustomerId <= 0) {
            show_404();
            return;
        }

        $cacheRow = (new \WooCommerce\Repositories\CustomersRepository($this->db))
            ->findByWooId($storeId, $wooCustomerId);

        // Recent orders for this customer in the cache.
        $recentOrders = $this->db
            ->where('store_id', $storeId)
            ->where('customer_id', $wooCustomerId)
            ->order_by('date_created', 'DESC')
            ->limit(10)
            ->get(db_prefix() . 'woocommerce_orders')
            ->result_array();

        // Linked Perfex client, if any.
        $linkedClient = null;
        if (is_array($cacheRow) && ! empty($cacheRow['userid'])) {
            $linkedClient = $this->db
                ->where('userid', (int) $cacheRow['userid'])
                ->limit(1)
                ->get(db_prefix() . 'clients')
                ->row_array() ?: null;
        }

        $custName = trim((string) ($cacheRow['first_name'] ?? '') . ' ' . ($cacheRow['last_name'] ?? ''));
        $titleSubject = $custName !== ''
            ? $custName
            : ((string) ($cacheRow['email'] ?? '') !== ''
                ? (string) $cacheRow['email']
                : '#' . $wooCustomerId);

        $this->load->view('customer_view', [
            'title'          => $titleSubject . ' | ' . _l('woocommerce_customers') . ' | ' . _l('woocommerce'),
            'store_id'       => $storeId,
            'cache_row'      => $cacheRow,
            'recent_orders'  => $recentOrders ?: [],
            'linked_client'  => $linkedClient,
        ]);
    }

    /**
     * Products list (T6.7). Page shell + Perfex `<app-filters>` Vue
     * widget; rows come over AJAX from `products_table()`.
     */
    public function products(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
        $storesRepo = new \WooCommerce\Repositories\StoresRepository($this->db, $cipher);

        $stores        = $storesRepo->listStores();
        $activeStoreId = $this->resolveActiveStoreId($stores);

        $this->load->view('products', [
            'title'           => _l('woocommerce_products') . ' | ' . _l('woocommerce'),
            'stores'          => $stores,
            'active_store_id' => $activeStoreId,
            'table'           => App_table::find('woo_products'),
        ]);
    }

    /**
     * AJAX endpoint for the products DataTable. Routes through
     * `App_table::find('woo_products')->output()` which runs the
     * `outputUsing()` closure registered in `views/tables/products.php`
     * with the saved-filter rules already wired in.
     */
    public function products_table(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            ajax_access_denied();
            return;
        }
        if (! $this->input->is_ajax_request()) {
            show_404();
            return;
        }

        App_table::find('woo_products')->output();
    }

    /**
     * GET /admin/woocommerce/woocommerce/product_modal/{storeId}/{wooId}
     * (T6.8). Pulls the product live from Woo so the form sees the
     * current authoritative values for fields the cache doesn't carry
     * (description, regular_price, sale_price, stock_*). Falls back to
     * the cache row only if the live fetch fails.
     */
    public function product_modal(int $storeId = 0, int $wooId = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            access_denied('woocommerce');
            return;
        }
        if ($storeId <= 0 || $wooId <= 0) {
            show_404();
            return;
        }

        $repo  = new \WooCommerce\Repositories\ProductsRepository($this->db);
        $cache = $repo->findByWooId($storeId, $wooId);
        if (! is_array($cache)) {
            show_404();
            return;
        }

        // Try to fetch live — Woo carries description, regular_price,
        // sale_price, manage_stock, stock_quantity, stock_status which
        // our cache schema doesn't track. If the API call fails (no
        // creds, store offline) we fall through to the cache row only.
        $live = null;
        try {
            $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
            $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
            $store  = (new \WooCommerce\Repositories\StoresRepository($this->db, $cipher))
                ->findStore($storeId);
            $logRepo = new \WooCommerce\Repositories\LogRepository($this->db);
            $resp = (new \WooCommerce\Services\DefaultApiClientFactory($logRepo))
                ->products($store)
                ->getByWooId($wooId);
            $live = is_object($resp) ? (array) $resp : (array) $resp;
        } catch (\Throwable $e) {
            // Soft-fail; the modal still works against cache values.
            $live = null;
        }

        // Project a unified `$product` array for the view: live values
        // first, cache values as fallback.
        $imagesProp = $live['images'] ?? [];
        $firstImage = '';
        if (is_array($imagesProp) && isset($imagesProp[0])) {
            $img = $imagesProp[0];
            $img = is_object($img) ? (array) $img : (array) $img;
            $firstImage = (string) ($img['src'] ?? '');
        }

        $product = [
            'name'           => (string) ($live['name']           ?? $cache['name']    ?? ''),
            'sku'            => (string) ($live['sku']            ?? $cache['sku']     ?? ''),
            'price'          => (string) ($live['regular_price']  ?? $cache['price']   ?? ''),
            'sale_price'     => (string) ($live['sale_price']     ?? ''),
            'status'         => (string) ($live['status']         ?? $cache['status']  ?? 'publish'),
            'description'    => (string) ($live['description']    ?? ''),
            'manage_stock'   => (bool)   ($live['manage_stock']   ?? false),
            'stock_quantity' => $live['stock_quantity'] !== null ? (string) ($live['stock_quantity'] ?? '') : '',
            'stock_status'   => (string) ($live['stock_status']   ?? 'instock'),
            'picture'        => $firstImage !== '' ? $firstImage : (string) ($cache['picture'] ?? ''),
        ];

        $this->load->view('item_modal', [
            'store_id' => $storeId,
            'woo_id'   => $wooId,
            'product'  => $product,
        ]);
    }

    /**
     * POST /admin/woocommerce/woocommerce/update_product/{storeId}/{wooId}
     * (T6.8). Pushes the form to Woo and refreshes the cache row from
     * the response so the list view shows the new values without
     * waiting for the next cron tick.
     */
    public function update_product(int $storeId = 0, int $wooId = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            $this->jsonResponse(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        if ($storeId <= 0 || $wooId <= 0) {
            $this->jsonResponse(400, ['success' => false, 'error' => 'missing_ids']);
            return;
        }

        // Validate the payload here too — JS validation is UX, not security.
        $name         = trim((string) $this->input->post('name'));
        $regularPrice = trim((string) $this->input->post('regular_price'));
        $salePrice    = trim((string) $this->input->post('sale_price'));
        $sku          = trim((string) $this->input->post('sku'));

        if ($name === '' || $sku === '') {
            $this->jsonResponse(400, ['success' => false, 'error' => 'missing_required']);
            return;
        }
        if ($regularPrice !== '' && ! is_numeric($regularPrice)) {
            $this->jsonResponse(400, ['success' => false, 'error' => 'price_not_numeric']);
            return;
        }
        if ($salePrice !== '' && ! is_numeric($salePrice)) {
            $this->jsonResponse(400, ['success' => false, 'error' => 'sale_not_numeric']);
            return;
        }
        if ($salePrice !== '' && $regularPrice !== '' && (float) $salePrice >= (float) $regularPrice) {
            $this->jsonResponse(400, ['success' => false, 'error' => 'sale_not_less_than_regular']);
            return;
        }

        $appKey  = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher  = new \WooCommerce\Libraries\CredentialCipher($appKey);
        $stores  = new \WooCommerce\Repositories\StoresRepository($this->db, $cipher);

        try {
            $store = $stores->findStore($storeId);
        } catch (\Throwable $e) {
            $this->jsonResponse(404, ['success' => false, 'error' => 'store_not_found']);
            return;
        }

        // Build the Woo REST payload. We always send every field the
        // form binds — including `false` and empty strings — because
        // the REST API uses absent fields to mean "leave unchanged"
        // and present-but-empty to mean "clear it". Filtering empties
        // here would silently drop user-cleared fields (description,
        // sale_price etc.) and they'd never persist.
        $manageStock = (bool) $this->input->post('manage_stock');
        $payload = [
            'name'           => $name,
            'sku'            => $sku,
            'regular_price'  => $regularPrice,
            'sale_price'     => $salePrice,
            'status'         => (string) ($this->input->post('status') ?: 'publish'),
            'description'    => (string) $this->input->post('description'),
            'manage_stock'   => $manageStock,
            'stock_quantity' => $manageStock && $this->input->post('stock_quantity') !== ''
                ? (int) $this->input->post('stock_quantity')
                : null,
            'stock_status'   => (string) ($this->input->post('stock_status') ?: 'instock'),
        ];
        if ($manageStock === false) {
            // Leave stock_quantity null so Woo accepts the unmanaged state.
            unset($payload['stock_quantity']);
        }
        if ($this->input->post('image_url')) {
            $payload['images'] = [['src' => (string) $this->input->post('image_url')]];
        }

        $logRepo = new \WooCommerce\Repositories\LogRepository($this->db);
        $factory = new \WooCommerce\Services\DefaultApiClientFactory($logRepo);
        try {
            $resp = $factory->products($store)->update($wooId, $payload);
        } catch (\Throwable $e) {
            $this->jsonResponse(502, ['success' => false, 'error' => 'remote_failure', 'detail' => $e->getMessage()]);
            return;
        }

        // Mirror the change into the local cache so the products list
        // shows the new values on reload — without waiting for the
        // next cron tick to re-sync. We project Woo's response shape
        // back onto our cache columns; missing fields fall back to the
        // values we just sent. The cache row's `last_synced_at` is
        // bumped so the diagnostic page reflects the manual write.
        $respArr = is_object($resp) ? (array) $resp : (array) $resp;

        $firstImage = '';
        $imagesProp = $respArr['images'] ?? [];
        if (is_array($imagesProp) && isset($imagesProp[0])) {
            $img = $imagesProp[0];
            $img = is_object($img) ? (array) $img : (array) $img;
            $firstImage = (string) ($img['src'] ?? '');
        }
        if ($firstImage === '' && $this->input->post('image_url')) {
            $firstImage = (string) $this->input->post('image_url');
        }

        $cacheRow = [
            'name'          => (string) ($respArr['name']  ?? $name),
            'sku'           => (string) ($respArr['sku']   ?? $sku),
            'price'         => (string) ($respArr['price'] ?? $regularPrice),
            'status'        => (string) ($respArr['status'] ?? $payload['status']),
            'type'          => (string) ($respArr['type'] ?? ''),
            'sales'         => (string) ($respArr['total_sales'] ?? '0'),
            'permalink'     => (string) ($respArr['permalink'] ?? ''),
            'picture'       => $firstImage,
            'date_modified' => date('Y-m-d H:i:s'),
            'last_synced_at'=> date('Y-m-d H:i:s'),
        ];
        // Drop empty values so we don't overwrite cache fields we
        // don't have a fresh value for. Every value is already cast to
        // string above, so a single `!== ''` check is sufficient.
        $cacheRow = array_filter($cacheRow, static fn(string $v): bool => $v !== '');

        (new \WooCommerce\Repositories\ProductsRepository($this->db))
            ->upsertByWooId($storeId, $wooId, $cacheRow);

        $this->jsonResponse(200, ['success' => true, 'product' => $resp]);
    }

    /**
     * POST /admin/woocommerce/woocommerce/link_product/{storeId}/{wooId}
     *
     * Manual product → Perfex sales item conversion (the missing half of
     * T5.x: orders + customers had explicit endpoints, products didn't).
     * Creates a row in `tblitems` from the Woo product (live fetch with
     * cache fallback) and stamps the new item id on the cache row's
     * `itemid` column so the products list flips to "linked" without a
     * resync.
     *
     * Idempotent: if `itemid` is already set on the cache, returns the
     * existing link instead of creating a second item.
     */
    public function link_product(int $storeId = 0, int $wooId = 0): void
    {
        if (! has_permission('woocommerce', '', 'create')) {
            $this->jsonResponse(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        if ($storeId <= 0 || $wooId <= 0) {
            $this->jsonResponse(400, ['success' => false, 'error' => 'missing_ids']);
            return;
        }

        $repo  = new \WooCommerce\Repositories\ProductsRepository($this->db);
        $cache = $repo->findByWooId($storeId, $wooId);
        if (! is_array($cache)) {
            $this->jsonResponse(404, ['success' => false, 'error' => 'product_not_found']);
            return;
        }

        // Already linked? Short-circuit. Idempotent re-clicks just
        // bounce the admin to the existing Perfex item.
        if (! empty($cache['itemid'])) {
            $this->jsonResponse(200, [
                'success' => true,
                'item_id' => (int) $cache['itemid'],
                'reused'  => true,
            ]);
            return;
        }

        // Live fetch — cache lacks description / long_description, so
        // we always try to enrich. Soft-fail to cache values if the
        // remote is offline.
        $description     = (string) ($cache['name'] ?? '');
        $longDescription = '';
        $rate            = (string) ($cache['price'] ?? '0');

        try {
            $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
            $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
            $store  = (new \WooCommerce\Repositories\StoresRepository($this->db, $cipher))
                ->findStore($storeId);
            $logRepo = new \WooCommerce\Repositories\LogRepository($this->db);
            $resp    = (new \WooCommerce\Services\DefaultApiClientFactory($logRepo))
                ->products($store)->getByWooId($wooId);
            $live = is_object($resp) ? (array) $resp : (array) $resp;

            $description     = (string) ($live['name']             ?? $description);
            $longDescription = strip_tags((string) ($live['description'] ?? ''));
            $rate            = (string) ($live['regular_price']    ?? $rate);
        } catch (\Throwable $e) {
            // soft-fail
        }

        // tblitems row. Columns: description (NOT NULL), long_description,
        // rate (DECIMAL), tax/tax2 (FK to taxes; 0 = none), unit, group_id.
        // Stick to the columns Perfex itself sets in Items_model::add — any
        // extra defensive keys (e.g. custom_field_relid) error out on
        // installs that don't carry them, since CodeIgniter's QB doesn't
        // filter unknown columns and MySQL throws "Unknown column" hard.
        $row = [
            'description'      => $description,
            'long_description' => $longDescription,
            'rate'             => is_numeric($rate) ? (float) $rate : 0.0,
            'tax'              => 0,
            'tax2'             => 0,
            'unit'             => '',
            'group_id'         => 0,
        ];

        // Filter to columns that actually exist on this tenant's tblitems
        // — Perfex versions diverge here. `field_exists()` is cheap (one
        // INFORMATION_SCHEMA hit, cached by CI for the request).
        $row = array_filter(
            $row,
            fn($_, $col) => $this->db->field_exists($col, db_prefix() . 'items'),
            ARRAY_FILTER_USE_BOTH
        );

        $this->db->insert(db_prefix() . 'items', $row);
        $itemId = (int) $this->db->insert_id();

        if ($itemId <= 0) {
            $this->jsonResponse(500, ['success' => false, 'error' => 'item_insert_failed']);
            return;
        }

        // Stamp the cache row so the products list flips to "linked"
        // immediately and the next cron tick doesn't re-create a duplicate.
        $repo->upsertByWooId($storeId, $wooId, [
            'itemid'         => $itemId,
            'last_synced_at' => date('Y-m-d H:i:s'),
        ]);

        $this->jsonResponse(200, [
            'success' => true,
            'item_id' => $itemId,
            'reused'  => false,
        ]);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function jsonResponse(int $status, array $payload): void
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output((string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Order detail (T6.6). Fetches the live order from Woo (so line
     * items are visible) and joins it with the cached invoice
     * linkage + store context.
     */
    public function order(int $storeId = 0, int $orderId = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        if ($storeId <= 0 || $orderId <= 0) {
            show_404();
            return;
        }

        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
        $storesRepo = new \WooCommerce\Repositories\StoresRepository($this->db, $cipher);

        try {
            $store = $storesRepo->findStore($storeId);
        } catch (\Throwable $e) {
            show_404();
            return;
        }

        $cacheRow = (new \WooCommerce\Repositories\OrdersRepository($this->db))
            ->findByWooId($storeId, $orderId);

        $log = new \WooCommerce\Repositories\LogRepository($this->db);
        $api = (new \WooCommerce\Services\DefaultApiClientFactory($log))->orders($store);

        $live = null;
        $apiError = null;
        try {
            $rawLive = $api->getByWooId($orderId);
            // Deep-convert: the SDK returns stdClass with nested
            // stdClass objects (line items, billing, shipping). The
            // shallow `(array) $obj` cast leaves the nested ones
            // unchanged, so the view chokes when it does
            // `$line['image']['src']`. Round-trip through JSON to
            // get a recursively-arrayified payload.
            $live = json_decode(json_encode($rawLive), true);
            if (! is_array($live)) {
                $live = is_array($rawLive) ? $rawLive : (array) $rawLive;
            }
        } catch (\Throwable $e) {
            $apiError = $e->getMessage();
        }

        $orderNumber = (string) (($live['number'] ?? null) ?: ($cacheRow['order_number'] ?? ''));
        if ($orderNumber === '') {
            $orderNumber = '#' . $orderId;
        }

        $this->load->view('order', [
            'title'      => sprintf(_l('woocommerce_order_n'), $orderNumber)
                            . ' | ' . _l('woocommerce_orders')
                            . ' | ' . _l('woocommerce'),
            'store'      => $store,
            'cache_row'  => $cacheRow,
            'live'       => $live,
            'api_error'  => $apiError,
        ]);
    }

    public function update_woo(int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            $this->respondOrRedirect(false, 'forbidden', null, 403);
            return;
        }

        $orderId = (int) $this->input->post('order_id');
        $status  = (string) $this->input->post('status');

        if ($orderId <= 0 || $status === '') {
            $this->respondOrRedirect(false, 'missing_order_id_or_status', null, 400);
            return;
        }

        try {
            $store = $this->storesRepo()->findStore($storeId);
        } catch (\Throwable $e) {
            $this->respondOrRedirect(false, 'store_not_found', null, 404);
            return;
        }

        $api = (new DefaultApiClientFactory(new LogRepository($this->db)))->orders($store);

        try {
            $api->updateStatus($orderId, $status);
        } catch (\Throwable $e) {
            $this->respondOrRedirect(
                false,
                'woo_update_failed',
                admin_url('woocommerce/woocommerce/order/' . $storeId . '/' . $orderId),
                502,
                ['message' => $e->getMessage()]
            );
            return;
        }

        // Refresh the cache row's status so the admin UI reflects it.
        $this->db
            ->where('store_id', $storeId)
            ->where('order_id', $orderId)
            ->update(db_prefix() . 'woocommerce_orders', [
                'status'         => $status,
                'last_synced_at' => date('Y-m-d H:i:s'),
            ]);

        // If the new status is in the store's auto-invoice-statuses,
        // queue a payment-record job (handler lands in Phase 5
        // post-T5.5 wiring; for now JobQueue handles it gracefully).
        if (self::isAutoInvoiceStatus($store->autoInvoiceStatuses, $status)) {
            $jobs = new JobQueue(new JobsRepository($this->db), new LogRepository($this->db));
            $jobs->enqueue('convert.order_to_invoice', $storeId, [
                'woo_order_id' => $orderId,
                'origin'       => 'manual_status_change',
            ]);
        }

        if (function_exists('log_activity')) {
            log_activity("WooCommerce: store=$storeId order=$orderId status set to $status");
        }

        $this->respondOrRedirect(
            true,
            sprintf(_l('woocommerce_status_updated_to'), $status),
            admin_url('woocommerce/woocommerce/order/' . $storeId . '/' . $orderId),
            200,
            ['status' => $status]
        );
    }

    /**
     * Branch on is_ajax_request: AJAX callers get JSON, browser-form
     * callers get a flash + redirect to a sensible page. Keeps the
     * AJAX UX (POST → reload via JS) and the plain-form UX (POST →
     * 302 with banner) both clean without two parallel handlers.
     *
     * @param array<string, mixed> $extra extra fields merged into the JSON response
     */
    private function respondOrRedirect(bool $success, string $message, ?string $redirectUrl, int $httpCode = 200, array $extra = []): void
    {
        if ($this->input->is_ajax_request()) {
            $this->respondJson(
                $httpCode,
                array_merge(['success' => $success, 'error' => $success ? null : $message], $extra)
            );
            return;
        }

        if (function_exists('set_alert')) {
            set_alert($success ? 'success' : 'danger', $message);
        }
        redirect($redirectUrl ?? ($_SERVER['HTTP_REFERER'] ?? admin_url('woocommerce/orders')));
    }

    /**
     * Delete a Woo order on the remote and drop the cache row.
     * Linked invoice (if any) is preserved with wco_id = NULL.
     */
    public function delete(int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'delete')) {
            $this->respondOrRedirect(false, 'forbidden', null, 403);
            return;
        }

        $orderId = (int) $this->input->post('order_id');
        if ($orderId <= 0) {
            $this->respondOrRedirect(false, 'missing_order_id', null, 400);
            return;
        }

        try {
            $store = $this->storesRepo()->findStore($storeId);
        } catch (\Throwable $e) {
            $this->respondOrRedirect(false, 'store_not_found', null, 404);
            return;
        }

        $api = (new DefaultApiClientFactory(new LogRepository($this->db)))->orders($store);

        try {
            $api->delete($orderId, force: true);
        } catch (\Throwable $e) {
            $this->respondOrRedirect(
                false,
                'woo_delete_failed',
                admin_url('woocommerce/woocommerce/order/' . $storeId . '/' . $orderId),
                502,
                ['message' => $e->getMessage()]
            );
            return;
        }

        // Drop cache row.
        $this->db
            ->where('store_id', $storeId)
            ->where('order_id', $orderId)
            ->delete(db_prefix() . 'woocommerce_orders');

        // Preserve the linked invoice (accounting history) but null
        // the wco_id so the row no longer claims a Woo provenance.
        $this->db
            ->where('store_id', $storeId)
            ->where('wco_id',   $orderId)
            ->update(db_prefix() . 'invoices', [
                'wco_id' => null,
            ]);

        if (function_exists('log_activity')) {
            log_activity("WooCommerce: store=$storeId order=$orderId deleted");
        }

        $this->respondOrRedirect(
            true,
            _l('woocommerce_order_deleted'),
            admin_url('woocommerce/orders'),
            200
        );
    }

    /**
     * POST /admin/woocommerce/woocommerce/bulk_convert
     * Body: id (cache row id). One row at a time so the JS layer can
     * report per-row success/fail without locking up the request on a
     * batch transaction. Skips rows that already carry an invoice_id.
     */
    public function bulk_convert(): void
    {
        if (! has_permission('woocommerce', '', 'create')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }

        $cacheId = (int) $this->input->post('id');
        if ($cacheId <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_id']);
            return;
        }

        $row = $this->cacheOrderRow($cacheId);
        if ($row === null) {
            $this->respondJson(404, ['success' => false, 'error' => 'order_not_found']);
            return;
        }
        if ((int) ($row['invoice_id'] ?? 0) > 0) {
            // Already converted — nothing to do, treat as success so the
            // batch counter doesn't double-count selected rows.
            $this->respondJson(200, ['success' => true, 'skipped' => true, 'invoice_id' => (int) $row['invoice_id']]);
            return;
        }

        try {
            $store        = $this->storesRepo()->findStore((int) $row['store_id']);
            $logRepo      = new LogRepository($this->db);
            $factory      = new DefaultApiClientFactory($logRepo);
            $orderResp    = $factory->orders($store)->getByWooId((int) $row['order_id']);
            $orderPayload = is_object($orderResp) ? (array) $orderResp : (array) $orderResp;

            $custRepo = new \WooCommerce\Repositories\CustomerFieldMappingRepository($this->db);
            $prodRepo = new \WooCommerce\Repositories\ProductFieldMappingRepository($this->db);
            $ordRepo  = new \WooCommerce\Repositories\OrderFieldMappingRepository($this->db);
            $presets  = (new \WooCommerce\Libraries\PresetLoader($custRepo, $prodRepo, $ordRepo))->readPresets();
            $resolver = new \WooCommerce\Libraries\MappingResolver(
                $presets, $custRepo, $prodRepo, $ordRepo
            );

            $converter = new \WooCommerce\Services\OrderConverter(
                new \WooCommerce\Services\PerfexInvoiceGateway($this->db),
                new \WooCommerce\Services\GuestClientFactory(
                    new \WooCommerce\Services\PerfexClientGateway($this->db),
                    $logRepo,
                ),
                $resolver,
                new \WooCommerce\Libraries\WooToPerfexTransformer($logRepo),
                new \WooCommerce\Services\PaymentModeService($this->db),
                $logRepo,
                new \WooCommerce\Services\PerfexClientResolver($this->db),
            );

            $result = $converter->convert($orderPayload, $store);
        } catch (\Throwable $e) {
            $this->respondJson(500, ['success' => false, 'error' => 'convert_failed', 'detail' => $e->getMessage()]);
            return;
        }

        $invoiceId = (int) ($result['invoice_id'] ?? 0);
        if ($invoiceId > 0) {
            (new \WooCommerce\Repositories\OrdersRepository($this->db))
                ->upsertByWooId((int) $row['store_id'], (int) $row['order_id'], [
                    'invoice_id'     => $invoiceId,
                    'last_synced_at' => date('Y-m-d H:i:s'),
                ]);
        }

        $this->respondJson(200, ['success' => true, 'invoice_id' => $invoiceId]);
    }

    /**
     * POST /admin/woocommerce/woocommerce/bulk_mark_completed
     * Body: id (cache row id). Pushes a 'completed' status to Woo and
     * mirrors it back to the cache row. Skips rows already 'completed'.
     */
    public function bulk_mark_completed(): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }

        $cacheId = (int) $this->input->post('id');
        if ($cacheId <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_id']);
            return;
        }

        $row = $this->cacheOrderRow($cacheId);
        if ($row === null) {
            $this->respondJson(404, ['success' => false, 'error' => 'order_not_found']);
            return;
        }
        if ((string) ($row['status'] ?? '') === 'completed') {
            $this->respondJson(200, ['success' => true, 'skipped' => true]);
            return;
        }

        try {
            $store = $this->storesRepo()->findStore((int) $row['store_id']);
            $api   = (new DefaultApiClientFactory(new LogRepository($this->db)))->orders($store);
            $api->updateStatus((int) $row['order_id'], 'completed');
        } catch (\Throwable $e) {
            $this->respondJson(502, ['success' => false, 'error' => 'remote_failure', 'detail' => $e->getMessage()]);
            return;
        }

        $this->db
            ->where('store_id', (int) $row['store_id'])
            ->where('order_id', (int) $row['order_id'])
            ->update(db_prefix() . 'woocommerce_orders', [
                'status'         => 'completed',
                'last_synced_at' => date('Y-m-d H:i:s'),
            ]);

        $this->respondJson(200, ['success' => true]);
    }

    /**
     * POST /admin/woocommerce/woocommerce/bulk_import_customer
     * Body: id (cache row id from `tblwoocommerce_customers`).
     *
     * Re-uses the same GuestClientFactory + PerfexClientGateway path as
     * `Woocommerce_invoice::import_customer`, but returns JSON so the
     * customers-list bulk toolbar can sequence imports without page
     * reloads. Already-linked rows return success-skipped so a stray
     * selection doesn't poison the batch counter.
     */
    public function bulk_import_customer(): void
    {
        if (! has_permission('woocommerce', '', 'create')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }

        $cacheId = (int) $this->input->post('id');
        if ($cacheId <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_id']);
            return;
        }

        $row = $this->db
            ->select('id, store_id, woo_customer_id, userid')
            ->where('id', $cacheId)
            ->limit(1)
            ->get(db_prefix() . 'woocommerce_customers')
            ->row_array();
        if ($row === []) {
            $this->respondJson(404, ['success' => false, 'error' => 'customer_not_found']);
            return;
        }
        if ((int) ($row['userid'] ?? 0) > 0) {
            $this->respondJson(200, ['success' => true, 'skipped' => true, 'client_id' => (int) $row['userid']]);
            return;
        }

        $storeId   = (int) $row['store_id'];
        $wooCustId = (int) $row['woo_customer_id'];

        try {
            $store    = $this->storesRepo()->findStore($storeId);
            $logRepo  = new LogRepository($this->db);
            $factory  = new DefaultApiClientFactory($logRepo);
            $custResp = $factory->customers($store)->getByWooId($wooCustId);
            $customer = is_object($custResp) ? (array) $custResp : (array) $custResp;

            $billing = isset($customer['billing']) ? (array) $customer['billing'] : [];
            if (empty($billing['email']) && ! empty($customer['email'])) {
                $billing['email'] = (string) $customer['email'];
            }
            if (empty($billing['first_name']) && ! empty($customer['first_name'])) {
                $billing['first_name'] = (string) $customer['first_name'];
            }
            if (empty($billing['last_name']) && ! empty($customer['last_name'])) {
                $billing['last_name'] = (string) $customer['last_name'];
            }

            $clientGateway = new \WooCommerce\Services\PerfexClientGateway($this->db);
            $guestSvc      = new \WooCommerce\Services\GuestClientFactory($clientGateway, $logRepo);
            $clientId      = $guestSvc->findOrCreate(['billing' => $billing], $storeId);

            $this->db
                ->where('userid', $clientId)
                ->update(db_prefix() . 'clients', [
                    'woo_id'   => $wooCustId,
                    'store_id' => $storeId,
                ]);

            $this->db
                ->where('id', $cacheId)
                ->update(db_prefix() . 'woocommerce_customers', [
                    'userid'         => $clientId,
                    'last_synced_at' => date('Y-m-d H:i:s'),
                ]);
        } catch (\Throwable $e) {
            $this->respondJson(500, ['success' => false, 'error' => 'import_failed', 'detail' => $e->getMessage()]);
            return;
        }

        $this->respondJson(200, ['success' => true, 'client_id' => $clientId]);
    }

    /**
     * @return array<string,mixed>|null
     */
    private function cacheOrderRow(int $cacheId): ?array
    {
        $row = $this->db
            ->select('id, store_id, order_id, status, invoice_id')
            ->where('id', $cacheId)
            ->limit(1)
            ->get(db_prefix() . 'woocommerce_orders')
            ->row_array();

        return $row === [] ? null : $row;
    }

    private function storesRepo(): StoresRepository
    {
        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : '';
        return new StoresRepository($this->db, new CredentialCipher($appKey));
    }

    private static function isAutoInvoiceStatus(?string $csv, string $status): bool
    {
        if ($csv === null || $csv === '') {
            return false;
        }
        $statuses = array_map('trim', explode(',', $csv));
        return in_array($status, $statuses, true);
    }

    /** @param array<string, mixed> $payload */
    private function respondJson(int $status, array $payload): void
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output((string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
