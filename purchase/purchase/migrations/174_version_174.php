<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_174 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      
      if ($CI->db->field_exists('sale_invoice' ,db_prefix() . 'pur_orders')) { 
        $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_orders`
            CHANGE COLUMN `sale_invoice` `sale_invoice` TEXT NULL DEFAULT NULL 
        ;");
      }

      add_option('po_show_custom_field_on_pdf', '1');
   }

}