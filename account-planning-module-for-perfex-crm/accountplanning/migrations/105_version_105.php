<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.5
 * - Plan approval workflow (approved_by, approved_date)
 * - Goals & KPIs table
 * - Webhooks table
 */

class Migration_Version_105 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        if (!$CI->db->field_exists('approved_by', db_prefix() . 'accountplanning')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'accountplanning` ADD COLUMN `approved_by` INT(11) NULL AFTER `status`, ADD COLUMN `approved_date` DATETIME NULL AFTER `approved_by`');
        }

        $CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_goals` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `accountplanning_id` INT(11) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `target` DECIMAL(15,2) NULL,
            `actual` DECIMAL(15,2) NULL,
            `unit` VARCHAR(50) NULL DEFAULT "number",
            `due_date` DATE NULL,
            `datecreated` DATETIME NULL,
            PRIMARY KEY (`id`),
            KEY `accountplanning_id` (`accountplanning_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_webhooks` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `url` VARCHAR(500) NOT NULL,
            `events` TEXT NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            `datecreated` DATETIME NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }
}
