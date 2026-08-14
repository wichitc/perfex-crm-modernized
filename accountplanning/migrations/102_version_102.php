<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.2
 * - Plan status (Draft, In Progress, Review, Completed, Archived)
 * - Industry field for account plans
 * - Plan templates tables
 * - Project/invoice/estimate/proposal links
 * - Reminder settings
 * - Custom fields registration
 * - fields_helper.php mindmap patch (one-time)
 */

class Migration_Version_102 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // Plan status
        if (!$CI->db->field_exists('status', db_prefix() . 'accountplanning')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'accountplanning` ADD COLUMN `status` VARCHAR(50) NULL DEFAULT "draft" AFTER `date`');
        }

        // Industry field
        if (!$CI->db->field_exists('industry', db_prefix() . 'accountplanning')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'accountplanning` ADD COLUMN `industry` VARCHAR(255) NULL AFTER `sale_channel_offline`');
        }

        // Plan templates
        $CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_templates` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `subject` VARCHAR(255) NULL,
            `vision` TEXT NULL,
            `mission` TEXT NULL,
            `objectives` TEXT NULL,
            `threat` TEXT NULL,
            `opportunity` TEXT NULL,
            `criteria_to_success` TEXT NULL,
            `constraints` TEXT NULL,
            `data_tree` LONGTEXT NULL,
            `datecreated` DATETIME NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

        // Project links
        $CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_relations` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `accountplanning_id` INT(11) NOT NULL,
            `rel_type` VARCHAR(50) NOT NULL,
            `rel_id` INT(11) NOT NULL,
            `dateadded` DATETIME NULL,
            PRIMARY KEY (`id`),
            KEY `accountplanning_id` (`accountplanning_id`),
            KEY `rel_type_rel_id` (`rel_type`, `rel_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

        // Reminder settings
        if (!option_exists('accountplanning_reminder_days')) {
            add_option('accountplanning_reminder_days', 3);
        }
        if (!option_exists('accountplanning_reminders_enabled')) {
            add_option('accountplanning_reminders_enabled', 1);
        }

        // fields_helper.php mindmap patch (one-time, idempotent)
        $helper_path = FCPATH . 'application/helpers/fields_helper.php';
        if (file_exists($helper_path)) {
            $content = file_get_contents($helper_path);
            $search = "if (strpos(\$textarea_class, 'tinymce') !== false)";
            $replace = "if (strpos(\$textarea_class, 'tinymce') !== false && strpos(\$textarea_class, 'mindmap') == false )";
            if (strpos($content, $replace) === false && strpos($content, $search) !== false) {
                $content = str_replace($search, $replace, $content);
                file_put_contents($helper_path, $content);
            }
        }
    }
}
