<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_226 extends App_module_migration
{
    public function up()
    {
        $this->ci->load->dbforge();

        // Create order field mapping table
        $this->ci->db->query(
            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "woocommerce_order_field_mapping (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `store_id` int(10) UNSIGNED NOT NULL,
                `wc_field` varchar(191) NOT NULL,
                `perfex_field` varchar(191) NOT NULL,
                `is_required` tinyint(1) DEFAULT 0,
                `default_value` varchar(191) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_store_id` (`store_id`),
                KEY `idx_wc_field` (`wc_field`),
                KEY `idx_perfex_field` (`perfex_field`),
                CONSTRAINT `fk_order_field_mapping_store` 
                    FOREIGN KEY (`store_id`) 
                    REFERENCES " . db_prefix() . "woocommerce_stores(`store_id`) 
                    ON DELETE CASCADE
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
    }
}