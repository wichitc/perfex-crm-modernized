<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Tiny seam for "is this Woo customer linked to a Perfex client?".
 * Production wires a callback into Perfex's `tblclients` query;
 * tests pass a fixed map.
 */
interface ClientResolver
{
    /**
     * @return int|null the Perfex client id, or null if no link exists
     */
    public function findClientByWooCustomerId(int $storeId, int $wooCustomerId): ?int;
}
