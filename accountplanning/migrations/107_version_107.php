<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.7
 * - Client portal: request update
 */

class Migration_Version_107 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        $CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_update_requests` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `accountplanning_id` INT(11) NOT NULL,
            `contact_id` INT(11) NULL,
            `dateadded` DATETIME NULL,
            `status` VARCHAR(50) NULL DEFAULT "pending",
            PRIMARY KEY (`id`),
            KEY `accountplanning_id` (`accountplanning_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }
}
