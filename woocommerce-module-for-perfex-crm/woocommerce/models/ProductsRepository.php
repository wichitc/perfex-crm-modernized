<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

class ProductsRepository extends WooCacheRepository
{
    public function __construct(object $db, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, 'woocommerce_products', 'product_id', $tablePrefix);
    }
}
