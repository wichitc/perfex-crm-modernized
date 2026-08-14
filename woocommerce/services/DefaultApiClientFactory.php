<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\StoreDTO;

final class DefaultApiClientFactory implements ApiClientFactory
{
    private LogRepository $log;

    public function __construct(LogRepository $log)
    {
        $this->log = $log;
    }

    public function orders(StoreDTO $store): OrderApiClient
    {
        return new OrderApiClient($store, $this->log);
    }

    public function products(StoreDTO $store): ProductApiClient
    {
        return new ProductApiClient($store, $this->log);
    }

    public function customers(StoreDTO $store): CustomerApiClient
    {
        return new CustomerApiClient($store, $this->log);
    }

    public function reports(StoreDTO $store): ReportsApiClient
    {
        return new ReportsApiClient($store, $this->log);
    }

    public function webhooks(StoreDTO $store): WebhooksApiClient
    {
        return new WebhooksApiClient($store, $this->log);
    }
}
