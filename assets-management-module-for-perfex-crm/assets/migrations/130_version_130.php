<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_130 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // Allow assets to be allocated / revoked to customers and contacts,
        // not just staff. A discriminator column tells the UI and reports how
        // to resolve `acction_to`. Existing rows default to 'staff', so every
        // historical allocation keeps rendering exactly as before.
        if (!$CI->db->field_exists('acction_to_type', db_prefix() . 'assets_acction_1')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'assets_acction_1`
                ADD `acction_to_type` ENUM("staff", "client", "contact") NOT NULL DEFAULT "staff" AFTER `acction_to`');
        }
    }
}
