<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_168 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      if (!$CI->db->field_exists('visible_to_vendor' ,db_prefix() . 'files')) {
        $CI->db->query("ALTER TABLE `" . db_prefix() . "files`
          ADD COLUMN `visible_to_vendor` INT(11) NULL DEFAULT '1'
          ");
      }
   }

}