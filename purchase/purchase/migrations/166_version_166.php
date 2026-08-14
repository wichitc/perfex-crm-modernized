<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_166 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      add_option('po_pdf_template', 'default');

      if (!$CI->db->field_exists('inco_term' ,db_prefix() . 'pur_orders')) {
        $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_orders`
          ADD COLUMN `inco_term` TEXT NULL
          ");
      }
   }

}