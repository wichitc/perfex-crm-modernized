<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * T6.4 — field-name dictionary used by the Field Mappings editor.
 *
 * Returns label-keyed arrays for the typeahead dropdowns. Carried over
 * from the legacy `Customer/Product/Order_field_mapping_model::get_default_*`
 * methods, simplified into pure functions and a single namespace.
 *
 * Perfex custom fields are merged in dynamically via `get_custom_fields()`
 * so admins who add a custom field on clients/items can immediately map
 * a Woo source to it without a code change.
 */

if (! function_exists('woo_default_wc_fields')) {
    /**
     * @return array<string, string>  field key => display label
     */
    function woo_default_wc_fields(string $entity): array
    {
        return match ($entity) {
            'customer', 'contact' => [
                'id'                  => 'Customer ID',
                'email'               => 'Email',
                'first_name'          => 'First Name',
                'last_name'           => 'Last Name',
                'role'                => 'Customer Role',
                'username'            => 'Username',
                'is_paying_customer'  => 'Is Paying Customer',
                'avatar_url'          => 'Avatar URL',
                'date_created'        => 'Date Created',
                'date_modified'       => 'Date Modified',
                'billing_first_name'  => 'Billing First Name',
                'billing_last_name'   => 'Billing Last Name',
                'billing_company'     => 'Billing Company',
                'billing_address_1'   => 'Billing Address 1',
                'billing_address_2'   => 'Billing Address 2',
                'billing_city'        => 'Billing City',
                'billing_state'       => 'Billing State',
                'billing_postcode'    => 'Billing Postcode',
                'billing_country'     => 'Billing Country',
                'billing_email'       => 'Billing Email',
                'billing_phone'       => 'Billing Phone',
                'shipping_first_name' => 'Shipping First Name',
                'shipping_last_name'  => 'Shipping Last Name',
                'shipping_company'    => 'Shipping Company',
                'shipping_address_1'  => 'Shipping Address 1',
                'shipping_address_2'  => 'Shipping Address 2',
                'shipping_city'       => 'Shipping City',
                'shipping_state'      => 'Shipping State',
                'shipping_postcode'   => 'Shipping Postcode',
                'shipping_country'    => 'Shipping Country',
            ],
            'product' => [
                'name'              => 'Product Name',
                'slug'              => 'Slug',
                'type'              => 'Type',
                'status'            => 'Status',
                'featured'          => 'Featured',
                'description'       => 'Description',
                'short_description' => 'Short Description',
                'sku'               => 'SKU',
                'price'             => 'Price',
                'regular_price'     => 'Regular Price',
                'sale_price'        => 'Sale Price',
                'on_sale'           => 'On Sale',
                'total_sales'       => 'Total Sales',
                'manage_stock'      => 'Manage Stock',
                'stock_quantity'    => 'Stock Quantity',
                'stock_status'      => 'Stock Status',
                'weight'            => 'Weight',
                'categories'        => 'Categories',
                'tags'              => 'Tags',
                'images'            => 'Images',
                'attributes'        => 'Attributes',
                'parent_id'         => 'Parent ID',
                'date_created'      => 'Date Created',
                'date_modified'     => 'Date Modified',
            ],
            'order' => [
                'id'                  => 'Order ID',
                'number'              => 'Order Number',
                'status'              => 'Status',
                'currency'            => 'Currency',
                'total'               => 'Total',
                'subtotal'            => 'Subtotal',
                'total_tax'           => 'Total Tax',
                'shipping_total'      => 'Shipping Total',
                'discount_total'      => 'Discount Total',
                'customer_id'         => 'Customer ID',
                'customer_note'       => 'Customer Note',
                'payment_method'      => 'Payment Method',
                'payment_method_title' => 'Payment Method Title',
                'transaction_id'      => 'Transaction ID',
                'date_paid'           => 'Date Paid',
                'date_completed'      => 'Date Completed',
                'date_created'        => 'Date Created',
                'date_modified'       => 'Date Modified',
                'billing_first_name'  => 'Billing First Name',
                'billing_last_name'   => 'Billing Last Name',
                'billing_company'     => 'Billing Company',
                'billing_address_1'   => 'Billing Address 1',
                'billing_city'        => 'Billing City',
                'billing_postcode'    => 'Billing Postcode',
                'billing_country'     => 'Billing Country',
                'billing_email'       => 'Billing Email',
                'billing_phone'       => 'Billing Phone',
                'shipping_first_name' => 'Shipping First Name',
                'shipping_last_name'  => 'Shipping Last Name',
                'shipping_address_1'  => 'Shipping Address 1',
                'shipping_city'       => 'Shipping City',
                'shipping_postcode'   => 'Shipping Postcode',
                'shipping_country'    => 'Shipping Country',
            ],
            default => [],
        };
    }
}

if (! function_exists('woo_default_perfex_fields')) {
    /**
     * @return array<string, string>  field key => display label
     */
    function woo_default_perfex_fields(string $entity): array
    {
        $base = match ($entity) {
            'customer', 'contact' => [
                'firstname'        => 'First Name',
                'lastname'         => 'Last Name',
                'company'          => 'Company',
                'email'            => 'Email',
                'phonenumber'      => 'Phone',
                'address'          => 'Address',
                'city'             => 'City',
                'state'            => 'State',
                'zip'              => 'Zip',
                'country'          => 'Country',
                'billing_street'   => 'Billing Street',
                'billing_city'     => 'Billing City',
                'billing_state'    => 'Billing State',
                'billing_zip'      => 'Billing Zip',
                'billing_country'  => 'Billing Country',
                'shipping_street'  => 'Shipping Street',
                'shipping_city'    => 'Shipping City',
                'shipping_state'   => 'Shipping State',
                'shipping_zip'     => 'Shipping Zip',
                'shipping_country' => 'Shipping Country',
            ],
            'product' => [
                'description'       => 'Description',
                'long_description'  => 'Long Description',
                'rate'              => 'Rate',
                'sku'               => 'SKU',
                'unit'              => 'Unit',
                'group_id'          => 'Group',
                'tax'               => 'Tax 1',
                'tax2'              => 'Tax 2',
            ],
            'order' => [
                'number'            => 'Invoice Number',
                'date'              => 'Invoice Date',
                'duedate'           => 'Due Date',
                'currency'          => 'Currency',
                'total'             => 'Total',
                'subtotal'          => 'Subtotal',
                'total_tax'         => 'Total Tax',
                'discount_total'    => 'Discount Total',
                'adminnote'         => 'Admin Note',
                'clientnote'        => 'Client Note',
                'terms'             => 'Terms',
                'sale_agent'        => 'Sale Agent',
                'reference_no'      => 'Reference Number',
            ],
            default => [],
        };

        $cfMap = [
            'customer' => 'customers',
            'contact'  => 'contacts',
            'product'  => 'items',
            'order'    => 'invoice',
        ];
        $cfGroup = $cfMap[$entity] ?? null;
        if ($cfGroup !== null && function_exists('get_custom_fields')) {
            foreach (get_custom_fields($cfGroup) as $field) {
                if (! empty($field['slug'])) {
                    $base[(string) $field['slug']] = $field['name'] . ' (Custom)';
                }
            }
        }

        return $base;
    }
}
