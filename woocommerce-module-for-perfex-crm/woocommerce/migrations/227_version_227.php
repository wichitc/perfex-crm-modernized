<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_227 extends App_module_migration
{
    public function up()
    {
        $this->ci->load->dbforge();

        // Tables to alter
        $tables = [
            'woocommerce_product_field_mapping',
            'woocommerce_order_field_mapping',
            'woocommerce_customer_field_mapping'
        ];

        foreach ($tables as $table) {
            // Add columns required for predefined/override feature if they don't exist
            if (!$this->ci->db->field_exists('is_predefined', $table)) {
                $this->ci->dbforge->add_column($table, [
                    'is_predefined' => [
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'null' => false,
                        'default' => 0,
                        'after' => 'default_value',
                    ],
                ]);
            }

            if (!$this->ci->db->field_exists('is_overridden', $table)) {
                $this->ci->dbforge->add_column($table, [
                    'is_overridden' => [
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'null' => false,
                        'default' => 0,
                        'after' => 'is_predefined',
                    ],
                ]);
            }

            if (!$this->ci->db->field_exists('original_wc_field', $table)) {
                $this->ci->dbforge->add_column($table, [
                    'original_wc_field' => [
                        'type' => 'VARCHAR',
                        'constraint' => 191,
                        'null' => true,
                        'after' => 'is_overridden',
                    ],
                ]);
            }

            if (!$this->ci->db->field_exists('original_perfex_field', $table)) {
                $this->ci->dbforge->add_column($table, [
                    'original_perfex_field' => [
                        'type' => 'VARCHAR',
                        'constraint' => 191,
                        'null' => true,
                        'after' => 'original_wc_field',
                    ],
                ]);
            }

            // Add helpful indexes where possible
            try {
                $this->ci->db->query("CREATE INDEX idx_{$table}_is_predefined ON {$table} (is_predefined)");
            } catch (Exception $e) {
            }
            try {
                $this->ci->db->query("CREATE INDEX idx_{$table}_is_overridden ON {$table} (is_overridden)");
            } catch (Exception $e) {
            }
        }
    }
}
