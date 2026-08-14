<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_170 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      if (!$CI->db->field_exists('approval_setting' ,db_prefix() . 'pur_orders')) {
        $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_orders`
          ADD COLUMN `approval_setting` LONGTEXT NULL
          ");
      }

      add_option('pur_department_required_condition', '0');
      add_option('pur_can_select_approvers_on_purchase_order_form', '0');

   }

}