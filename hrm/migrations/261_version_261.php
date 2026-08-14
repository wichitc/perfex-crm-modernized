<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration 261: Add description column to hrm_onboarding_templates if missing
 * For installations that used install.php schema without description
 */

class Migration_Version_261 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        if ($CI->db->table_exists(db_prefix() . 'hrm_onboarding_templates')) {
            if (!$CI->db->field_exists('description', db_prefix() . 'hrm_onboarding_templates')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'hrm_onboarding_templates` ADD COLUMN `description` text NULL AFTER `name`');
            }
        }
    }
}
