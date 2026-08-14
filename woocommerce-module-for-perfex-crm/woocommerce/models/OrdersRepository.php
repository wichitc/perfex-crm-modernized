<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

class OrdersRepository extends WooCacheRepository
{
    public function __construct(object $db, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, 'woocommerce_orders', 'order_id', $tablePrefix);
    }
}
