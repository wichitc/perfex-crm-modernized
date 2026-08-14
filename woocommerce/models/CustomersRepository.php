<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

class CustomersRepository extends WooCacheRepository
{
    public function __construct(object $db, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, 'woocommerce_customers', 'woo_customer_id', $tablePrefix);
    }
}
