<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration 262: Wire contract templates into staff_contract.
 *
 * Adds the columns required to:
 *   - link a contract to the template it was generated from
 *   - persist the rendered (merge-fields-resolved) HTML body of the contract
 *   - capture the employee's e-signature, when, and from which IP
 */

class Migration_Version_262 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $table = db_prefix() . 'staff_contract';

        if (!$CI->db->table_exists($table)) {
            return;
        }

        if (!$CI->db->field_exists('template_id', $table)) {
            $CI->db->query('ALTER TABLE `' . $table . '` ADD COLUMN `template_id` INT(11) NULL AFTER `staff_role`');
        }

        if (!$CI->db->field_exists('body_content', $table)) {
            $CI->db->query('ALTER TABLE `' . $table . '` ADD COLUMN `body_content` LONGTEXT NULL AFTER `template_id`');
        }

        if (!$CI->db->field_exists('signed_at', $table)) {
            $CI->db->query('ALTER TABLE `' . $table . '` ADD COLUMN `signed_at` DATETIME NULL AFTER `body_content`');
        }

        if (!$CI->db->field_exists('signature_image', $table)) {
            $CI->db->query('ALTER TABLE `' . $table . '` ADD COLUMN `signature_image` LONGTEXT NULL AFTER `signed_at`');
        }

        if (!$CI->db->field_exists('signed_ip', $table)) {
            $CI->db->query('ALTER TABLE `' . $table . '` ADD COLUMN `signed_ip` VARCHAR(45) NULL AFTER `signature_image`');
        }
    }
}
