<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_162 extends App_module_migration
{
   public function up()
   {
     $CI = &get_instance();
     if (!$CI->db->field_exists('code_prefix' ,db_prefix() . 'pur_vendor_cate')) { 
        $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_vendor_cate`
            ADD COLUMN `code_prefix` TEXT  NULL
        ;");
      }

      if (!$CI->db->field_exists('vendor_code_prefix' ,db_prefix() . 'pur_vendor')) { 
        $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_vendor`
            ADD COLUMN `vendor_code_prefix` TEXT  NULL
        ;");
      }

      add_option('allow_upload_esign_for_approve_type', 0);
   }

}