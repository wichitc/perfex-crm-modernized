<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_230 extends App_module_migration
{
    public function up()
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        $table = 'woocommerce_stores';

        // Add auto-convert toggles
        if (!$CI->db->field_exists('auto_convert_customer', $table)) {
            $CI->dbforge->add_column($table, [
                'auto_convert_customer' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => false,
                    'default' => 0,
                    'after' => 'query_auth',
                ],
            ]);
        }
        if (!$CI->db->field_exists('auto_convert_product', $table)) {
            $CI->dbforge->add_column($table, [
                'auto_convert_product' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => false,
                    'default' => 0,
                    'after' => 'auto_convert_customer',
                ],
            ]);
        }
        if (!$CI->db->field_exists('auto_convert_order', $table)) {
            $CI->dbforge->add_column($table, [
                'auto_convert_order' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => false,
                    'default' => 0,
                    'after' => 'auto_convert_product',
                ],
            ]);
        }
        if (!$CI->db->field_exists('auto_invoice_statuses', $table)) {
            $CI->dbforge->add_column($table, [
                'auto_invoice_statuses' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'auto_convert_order',
                ],
            ]);
        }

        // Helpful index
        try {
            $CI->db->query("CREATE INDEX idx_woo_stores_auto_convert ON {$table} (auto_convert_customer, auto_convert_product, auto_convert_order)");
        } catch (Exception $e) {
        }
    }
}
