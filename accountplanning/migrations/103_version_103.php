<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.3
 * - Saved filter presets
 * - Industry options
 * - Client portal setting
 * - DB indexes for performance
 */

class Migration_Version_103 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        $CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_saved_filters` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `staffid` INT(11) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `filters` TEXT NULL,
            `datecreated` DATETIME NULL,
            PRIMARY KEY (`id`),
            KEY `staffid` (`staffid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

        if (!option_exists('accountplanning_industry_options')) {
            add_option('accountplanning_industry_options', '');
        }
        if (!option_exists('accountplanning_client_portal_enabled')) {
            add_option('accountplanning_client_portal_enabled', '0');
        }

        $tbl = db_prefix() . 'accountplanning';
        if ($CI->db->query("SHOW INDEX FROM `{$tbl}` WHERE Key_name = 'idx_client_date_status'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$tbl}` ADD INDEX `idx_client_date_status` (`client_id`, `date`, `status`)");
        }
    }
}
