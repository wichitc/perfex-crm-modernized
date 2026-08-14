<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_169 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      if (!$CI->db->field_exists('quote_number_code' ,db_prefix() . 'pur_orders')) {
        $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_orders`
          ADD COLUMN `quote_number_code` TEXT NULL
          ");
      }

   }

}