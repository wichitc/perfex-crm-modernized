<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Predefined field mappings for WooCommerce to Perfex CRM integration
 * These mappings can be manually overridden by users through the field mapping interface
 */

return [
    'customer' => [
        [
            'wc_field' => 'billing_first_name',
            'perfex_field' => 'firstname',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_last_name',
            'perfex_field' => 'lastname',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'email',
            'perfex_field' => 'email',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_company',
            'perfex_field' => 'company',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_phone',
            'perfex_field' => 'phonenumber',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_address_1',
            'perfex_field' => 'address',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_city',
            'perfex_field' => 'city',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_state',
            'perfex_field' => 'state',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_postcode',
            'perfex_field' => 'zip',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_country',
            'perfex_field' => 'country',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ]
    ],
    
    'contact' => [
        [
            'wc_field' => 'billing_first_name',
            'perfex_field' => 'firstname',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_last_name',
            'perfex_field' => 'lastname',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'email',
            'perfex_field' => 'email',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'billing_phone',
            'perfex_field' => 'phonenumber',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'role',
            'perfex_field' => 'title',
            'is_required' => 0,
            'default_value' => 'Customer',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'is_paying_customer',
            'perfex_field' => 'invoice_emails',
            'is_required' => 0,
            'default_value' => '1',
            'is_predefined' => 1
        ]
    ],
    
    'product' => [
        [
            'wc_field' => 'name',
            'perfex_field' => 'description',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'sku',
            'perfex_field' => 'long_description',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'price',
            'perfex_field' => 'rate',
            'is_required' => 1,
            'default_value' => '0.00',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'tax_class',
            'perfex_field' => 'tax',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'categories',
            'perfex_field' => 'group_id',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ]
    ],
    
    'order' => [
        [
            'wc_field' => 'number',
            'perfex_field' => 'number',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'date_created',
            'perfex_field' => 'date',
            'is_required' => 1,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'date_created',
            'perfex_field' => 'duedate',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'total',
            'perfex_field' => 'total',
            'is_required' => 1,
            'default_value' => '0.00',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'total_tax',
            'perfex_field' => 'total_tax',
            'is_required' => 0,
            'default_value' => '0.00',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'status',
            'perfex_field' => 'status',
            'is_required' => 1,
            'default_value' => '1',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'currency',
            'perfex_field' => 'currency',
            'is_required' => 0,
            'default_value' => 'USD',
            'is_predefined' => 1
        ],
        [
            'wc_field' => 'customer_note',
            'perfex_field' => 'adminnote',
            'is_required' => 0,
            'default_value' => '',
            'is_predefined' => 1
        ]
    ]
];