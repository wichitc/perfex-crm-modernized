<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_173 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      
      if ($CI->db->field_exists('pur_order_name' ,db_prefix() . 'pur_orders')) {
        $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_orders`
          CHANGE COLUMN `pur_order_name` `pur_order_name` TEXT NULL ;
          ");
      }

      add_option('pur_order_project_required_condition', '0');
   }

}