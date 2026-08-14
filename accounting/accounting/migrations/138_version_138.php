<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_138 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        
        if (!$CI->db->field_exists('currency_display_name' ,db_prefix() . 'acc_checks')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "acc_checks`
            ADD COLUMN `currency_display_name` VARCHAR(255) NULL
            ");
        }
    }
}
