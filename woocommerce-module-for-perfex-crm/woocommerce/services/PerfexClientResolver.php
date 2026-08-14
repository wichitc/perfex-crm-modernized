<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Concrete `ClientResolver` mapping Woo customer ids to Perfex client
 * ids via the (store_id, woo_id) pair on tblclients (added by our
 * v3 migrations + uq_tblclients_store_woo unique key in install.sql).
 */
final class PerfexClientResolver implements ClientResolver
{
    public function __construct(
        private object $db,
        private string $tablePrefix = 'tbl',
    ) {
    }

    public function findClientByWooCustomerId(int $storeId, int $wooCustomerId): ?int
    {
        if ($wooCustomerId <= 0) {
            return null;
        }
        $row = $this->db
            ->select('userid')
            ->where('store_id', $storeId)
            ->where('woo_id',   $wooCustomerId)
            ->limit(1)
            ->get($this->tablePrefix . 'clients')
            ->row_array();

        return is_array($row) && isset($row['userid']) ? (int) $row['userid'] : null;
    }
}
