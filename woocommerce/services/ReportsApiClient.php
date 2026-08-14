<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * `reports/{resource}/totals` endpoints — used by the cron's per-store
 * summary pass that drives the Stores list overview cards.
 */
class ReportsApiClient extends BaseApiClient
{
    /**
     * @param string $resource one of: orders, products, customers, reviews, coupons
     */
    public function getTotals(string $resource): mixed
    {
        return $this->makeRequest('GET', "reports/$resource/totals");
    }
}
