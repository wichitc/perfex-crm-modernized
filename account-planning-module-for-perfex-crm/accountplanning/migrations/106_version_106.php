<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.6
 * - Meeting notes / call logs
 * - Competitor tracking
 * - Client portal request update (uses tasks)
 * - Recurring plan periods (cron)
 */

class Migration_Version_106 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        $CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_meeting_notes` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `accountplanning_id` INT(11) NOT NULL,
            `subject` VARCHAR(255) NULL,
            `notes` TEXT NULL,
            `meeting_date` DATE NULL,
            `datecreated` DATETIME NULL,
            `addedfrom` INT(11) NULL,
            PRIMARY KEY (`id`),
            KEY `accountplanning_id` (`accountplanning_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_competitors` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `accountplanning_id` INT(11) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `threat_level` VARCHAR(50) NULL,
            `notes` TEXT NULL,
            `datecreated` DATETIME NULL,
            PRIMARY KEY (`id`),
            KEY `accountplanning_id` (`accountplanning_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }
}
