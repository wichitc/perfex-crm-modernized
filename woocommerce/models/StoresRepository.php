<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

use WooCommerce\Libraries\CredentialCipher;

/**
 * Repository for `woocommerce_stores`. Sits over `BaseRepository`'s
 * builder primitives and adds the only thing this table needs that
 * the base doesn't: encrypt-on-write / decrypt-on-read for the
 * consumer key + secret + webhook secret.
 *
 * Callers always work with `StoreDTO` instances; raw row arrays are an
 * implementation detail.
 */
class StoresRepository extends BaseRepository
{
    private CredentialCipher $cipher;

    public function __construct(object $db, CredentialCipher $cipher, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, $tablePrefix . 'woocommerce_stores', 'store_id');
        $this->cipher = $cipher;
    }

    public function findStore(int $id): StoreDTO
    {
        return self::hydrate($this->find($id), $this->cipher);
    }

    /**
     * @return list<StoreDTO>
     */
    public function listStores(bool $activeOnly = false): array
    {
        $criteria = $activeOnly ? ['is_active' => 1] : [];
        $rows     = $this->all($criteria);

        return array_values(array_map(
            fn(array $row): StoreDTO => self::hydrate($row, $this->cipher),
            $rows
        ));
    }

    public function insertStore(StoreDTO $store): int
    {
        return $this->insert($this->serialize($store));
    }

    public function updateStore(int $id, StoreDTO $store): bool
    {
        return $this->update($id, $this->serialize($store));
    }

    /**
     * Cursor-only update. Avoids re-encrypting key/secret/webhook_secret
     * (which serialize() would do every call) — important because the
     * cron writes cursors after every page and we don't want to churn
     * ciphertexts (or rotate IVs) every tick.
     */
    public function updateCursors(int $storeId, ?int $orderPage = null, ?int $productPage = null, ?int $customerPage = null): bool
    {
        $changes = ['date_modified' => date('Y-m-d H:i:s')];
        if ($orderPage    !== null) { $changes['orderPage']    = $orderPage; }
        if ($productPage  !== null) { $changes['productPage']  = $productPage; }
        if ($customerPage !== null) { $changes['customerPage'] = $customerPage; }

        return $this->update($storeId, $changes);
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function hydrate(array $row, CredentialCipher $cipher): StoreDTO
    {
        return new StoreDTO(
            storeId:                  isset($row['store_id'])    ? (int) $row['store_id']    : null,
            name:                     (string) ($row['name']     ?? ''),
            url:                      (string) ($row['url']      ?? ''),
            key:                      $cipher->decrypt((string) ($row['key']    ?? '')),
            secret:                   $cipher->decrypt((string) ($row['secret'] ?? '')),
            webhookSecret:            isset($row['webhook_secret']) ? $cipher->decrypt((string) $row['webhook_secret']) : null,
            verifySsl:                isset($row['verify_ssl'])      ? (bool) $row['verify_ssl']      : true,
            isActive:                 isset($row['is_active'])       ? (bool) $row['is_active']       : true,
            pagesPerTick:             isset($row['pages_per_tick'])  ? (int)  $row['pages_per_tick']  : 3,
            autoConvertCustomer:      (bool) ($row['auto_convert_customer'] ?? false),
            autoConvertProduct:       (bool) ($row['auto_convert_product']  ?? false),
            autoConvertOrder:         (bool) ($row['auto_convert_order']    ?? false),
            autoInvoiceStatuses:      isset($row['auto_invoice_statuses']) ? (string) $row['auto_invoice_statuses'] : null,
            queryAuth:                (bool) ($row['query_auth'] ?? false),
            productPage:              (int)  ($row['productPage']  ?? 1),
            orderPage:                (int)  ($row['orderPage']    ?? 1),
            customerPage:             (int)  ($row['customerPage'] ?? 1),
            woocommercePaymentModeId: isset($row['woocommerce_payment_mode_id']) ? (int) $row['woocommerce_payment_mode_id'] : null,
            dateCreated:              isset($row['date_created'])  ? (string) $row['date_created']  : null,
            dateModified:             isset($row['date_modified']) ? (string) $row['date_modified'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function serialize(StoreDTO $store): array
    {
        $row = [
            'name'                        => $store->name,
            'url'                         => $store->url,
            'key'                         => $this->cipher->encrypt($store->key),
            'secret'                      => $this->cipher->encrypt($store->secret),
            'webhook_secret'              => $store->webhookSecret === null ? null : $this->cipher->encrypt($store->webhookSecret),
            'verify_ssl'                  => $store->verifySsl ? 1 : 0,
            'is_active'                   => $store->isActive ? 1 : 0,
            'pages_per_tick'              => $store->pagesPerTick,
            'auto_convert_customer'       => $store->autoConvertCustomer ? 1 : 0,
            'auto_convert_product'        => $store->autoConvertProduct ? 1 : 0,
            'auto_convert_order'          => $store->autoConvertOrder ? 1 : 0,
            'auto_invoice_statuses'       => $store->autoInvoiceStatuses,
            'query_auth'                  => $store->queryAuth ? 1 : 0,
            'productPage'                 => $store->productPage,
            'orderPage'                   => $store->orderPage,
            'customerPage'                => $store->customerPage,
            'woocommerce_payment_mode_id' => $store->woocommercePaymentModeId,
        ];

        if ($store->dateCreated !== null) {
            $row['date_created'] = $store->dateCreated;
        }
        if ($store->dateModified !== null) {
            $row['date_modified'] = $store->dateModified;
        }

        return $row;
    }
}
