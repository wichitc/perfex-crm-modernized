<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.4
 * - Ensures saved_filters, industry_options, client_portal options exist
 */

class Migration_Version_104 extends App_module_migration
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
    }
}
