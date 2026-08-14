<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

class OrderFieldMappingRepository extends FieldMappingRepository
{
    public function __construct(object $db, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, 'woocommerce_order_field_mapping', $tablePrefix);
    }
}
