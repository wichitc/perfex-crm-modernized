<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_139 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        
        add_option('acc_check_company_logo');
         
        if (!$CI->db->field_exists('include_company_logo' ,db_prefix() . 'acc_checks')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "acc_checks`
            ADD COLUMN `include_company_logo` TINYINT(1) NOT NULL DEFAULT 0
            ");
        }
    }
}
