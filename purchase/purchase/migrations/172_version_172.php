<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_172 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      if (!$CI->db->field_exists('currency' ,db_prefix() . 'pur_faf_requests')) {
        $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_faf_requests`
          ADD COLUMN `currency` INT(11) NULL
          ");
      }


   }

}