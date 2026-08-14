<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

/**
 * `tblwoocommerce_summary` — one row per store. The three JSON columns
 * hold the totals report for customers / orders / products. Surfaced
 * on the Stores list overview cards.
 */
class SummaryRepository extends BaseRepository
{
    public function __construct(object $db, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, $tablePrefix . 'woocommerce_summary');
    }

    /**
     * Idempotent per-store upsert: replace the existing row or insert
     * a new one. Each section is JSON-encoded.
     *
     * @param array<int, mixed>|array<string, mixed> $customers
     * @param array<int, mixed>|array<string, mixed> $orders
     * @param array<int, mixed>|array<string, mixed> $products
     */
    public function saveForStore(int $storeId, array $customers, array $orders, array $products): void
    {
        $row = [
            'store_id'  => $storeId,
            'customers' => json_encode($customers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'orders'    => json_encode($orders,    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'products'  => json_encode($products,  JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ];

        $existing = $this->db->select('id')
            ->where('store_id', $storeId)
            ->limit(1)
            ->get($this->table)
            ->row_array();

        if (is_array($existing) && isset($existing['id'])) {
            $this->db->where('id', (int) $existing['id'])->update($this->table, $row);
        } else {
            $this->db->insert($this->table, $row);
        }
    }
}
