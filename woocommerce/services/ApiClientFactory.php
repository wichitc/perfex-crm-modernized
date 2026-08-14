<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\StoreDTO;

/**
 * Per-store API client constructor. Lifted out of `WooSyncService` so
 * the sync service can be unit-tested without the WC SDK. Production
 * uses `DefaultApiClientFactory`; tests pass an anonymous instance
 * that returns mocks.
 */
interface ApiClientFactory
{
    public function orders(StoreDTO $store): OrderApiClient;

    public function products(StoreDTO $store): ProductApiClient;

    public function customers(StoreDTO $store): CustomerApiClient;

    public function reports(StoreDTO $store): ReportsApiClient;

    public function webhooks(StoreDTO $store): WebhooksApiClient;
}
