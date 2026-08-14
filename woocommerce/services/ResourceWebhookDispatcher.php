<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\CustomersRepository;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\OrdersRepository;
use WooCommerce\Repositories\ProductsRepository;
use WooCommerce\Repositories\StoreDTO;

/**
 * Concrete `WebhookDispatcher` per spec §13.3. Routes a verified
 * payload to one of three resource handlers:
 *
 *   - order.{created|updated}  → OrdersRepository::upsertByWooId,
 *                                 then queue convert.order_to_invoice
 *                                 if store->autoConvertOrder=1.
 *   - product.{created|updated} → ProductsRepository::upsertByWooId,
 *                                 then queue link.product_to_item
 *                                 if store->autoConvertProduct=1.
 *   - customer.{created|updated} → CustomersRepository::upsertByWooId,
 *                                 then queue convert.customer_to_client
 *                                 if store->autoConvertCustomer=1.
 *   - <resource>.deleted        → flip is_deleted=1, stamp deleted_at;
 *                                 do NOT drop the row (auditors want
 *                                 the history).
 */
final class ResourceWebhookDispatcher implements WebhookDispatcher
{
    public function __construct(
        private OrdersRepository    $ordersRepo,
        private ProductsRepository  $productsRepo,
        private CustomersRepository $customersRepo,
        private JobEnqueuer         $jobs,
        private LogRepository       $log,
    ) {
    }

    public function dispatch(StoreDTO $store, string $topic, array $payload, string $correlationId): void
    {
        [$resource, $event] = self::parseTopic($topic);
        $storeId = (int) $store->storeId;

        match ($resource) {
            'order'    => $this->handleOrder(   $store, $event, $payload, $correlationId),
            'product'  => $this->handleProduct( $store, $event, $payload, $correlationId),
            'customer' => $this->handleCustomer($store, $event, $payload, $correlationId),
            default    => $this->log->write(
                LogRepository::LEVEL_WARN,
                'webhook.unknown_resource',
                ['topic' => $topic, 'resource' => $resource],
                $storeId,
                $correlationId,
            ),
        };
    }

    /** @param array<string, mixed> $payload */
    private function handleOrder(StoreDTO $store, string $event, array $payload, string $correlationId): void
    {
        $wooId = (int) ($payload['id'] ?? 0);
        if ($wooId <= 0) {
            $this->logBadPayload('order', $event, $store, $correlationId);
            return;
        }

        $storeId = (int) $store->storeId;

        if ($event === 'deleted') {
            $this->ordersRepo->update($this->ordersRepoIdFor($store, $wooId, 'order_id'), [
                'is_deleted' => 1,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            return;
        }

        // created or updated → upsert. Use the same projection
        // WooSyncService uses so cron + webhook converge to the same row shape.
        $this->ordersRepo->upsertByWooId($storeId, $wooId, $this->orderRowFor($payload));

        if ($store->autoConvertOrder) {
            $this->jobs->enqueue(JobEnqueuer::JOB_CONVERT_ORDER_TO_INVOICE, $storeId, [
                'woo_order_id'   => $wooId,
                'correlation_id' => $correlationId,
            ]);
        }
    }

    /** @param array<string, mixed> $payload */
    private function handleProduct(StoreDTO $store, string $event, array $payload, string $correlationId): void
    {
        $wooId = (int) ($payload['id'] ?? 0);
        if ($wooId <= 0) {
            $this->logBadPayload('product', $event, $store, $correlationId);
            return;
        }

        $storeId = (int) $store->storeId;

        if ($event === 'deleted') {
            $this->productsRepo->update($this->productsRepoIdFor($store, $wooId, 'product_id'), [
                'is_deleted' => 1,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            return;
        }

        $this->productsRepo->upsertByWooId($storeId, $wooId, $this->productRowFor($payload));

        if ($store->autoConvertProduct) {
            $this->jobs->enqueue(JobEnqueuer::JOB_LINK_PRODUCT_TO_ITEM, $storeId, [
                'woo_product_id' => $wooId,
                'correlation_id' => $correlationId,
            ]);
        }
    }

    /** @param array<string, mixed> $payload */
    private function handleCustomer(StoreDTO $store, string $event, array $payload, string $correlationId): void
    {
        $wooId = (int) ($payload['id'] ?? 0);
        if ($wooId <= 0) {
            $this->logBadPayload('customer', $event, $store, $correlationId);
            return;
        }

        $storeId = (int) $store->storeId;

        if ($event === 'deleted') {
            $this->customersRepo->update($this->customersRepoIdFor($store, $wooId, 'woo_customer_id'), [
                'is_deleted' => 1,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            return;
        }

        $this->customersRepo->upsertByWooId($storeId, $wooId, $this->customerRowFor($payload));

        if ($store->autoConvertCustomer) {
            $this->jobs->enqueue(JobEnqueuer::JOB_CONVERT_CUSTOMER_TO_CLIENT, $storeId, [
                'woo_customer_id' => $wooId,
                'correlation_id'  => $correlationId,
            ]);
        }
    }

    /** @return array{0:string, 1:string} */
    private static function parseTopic(string $topic): array
    {
        $parts = explode('.', $topic, 2);
        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    private function logBadPayload(string $resource, string $event, StoreDTO $store, string $correlationId): void
    {
        $this->log->write(
            LogRepository::LEVEL_WARN,
            'webhook.payload_missing_id',
            ['resource' => $resource, 'event' => $event],
            (int) $store->storeId,
            $correlationId,
        );
    }

    /**
     * Look up the cache row's primary key id given a (storeId, wooId)
     * pair. Used by delete handlers since BaseRepository::update needs
     * a primary-key value, not the (store_id, woo_id) tuple.
     */
    private function ordersRepoIdFor(StoreDTO $store, int $wooId, string $wooIdColumn): int
    {
        $row = $this->ordersRepo->findByWooId((int) $store->storeId, $wooId);
        return is_array($row) && isset($row['id']) ? (int) $row['id'] : 0;
    }

    private function productsRepoIdFor(StoreDTO $store, int $wooId, string $wooIdColumn): int
    {
        $row = $this->productsRepo->findByWooId((int) $store->storeId, $wooId);
        return is_array($row) && isset($row['id']) ? (int) $row['id'] : 0;
    }

    private function customersRepoIdFor(StoreDTO $store, int $wooId, string $wooIdColumn): int
    {
        $row = $this->customersRepo->findByWooId((int) $store->storeId, $wooId);
        return is_array($row) && isset($row['id']) ? (int) $row['id'] : 0;
    }

    /**
     * @param array<string, mixed> $woo
     * @return array<string, mixed>
     */
    private static function orderRowFor(array $woo): array
    {
        $billing = is_array($woo['billing'] ?? null) ? $woo['billing'] : [];

        return [
            'order_number'  => (string) ($woo['number'] ?? $woo['id'] ?? ''),
            'customer_id'   => (int)    ($woo['customer_id'] ?? 0),
            'address'       => self::renderAddress($billing),
            'phone'         => (string) ($billing['phone'] ?? ''),
            'status'        => (string) ($woo['status'] ?? ''),
            'currency'      => (string) ($woo['currency'] ?? ''),
            'date_created'  => (string) ($woo['date_created']  ?? ''),
            'date_modified' => (string) ($woo['date_modified'] ?? ''),
            'total'         => (string) ($woo['total'] ?? '0'),
        ];
    }

    /**
     * @param array<string, mixed> $woo
     * @return array<string, mixed>
     */
    private static function productRowFor(array $woo): array
    {
        $images = is_array($woo['images'] ?? null) ? $woo['images'] : [];
        $picture = isset($images[0]['src']) ? (string) $images[0]['src'] : '';
        $cats = is_array($woo['categories'] ?? null) ? $woo['categories'] : [];
        $catNames = [];
        foreach ($cats as $c) {
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

    /** @param array<string, mixed> $billing */
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
