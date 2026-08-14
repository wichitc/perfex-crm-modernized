<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_156 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();

      if (!$CI->db->field_exists('approval_status' ,db_prefix() . 'pur_invoices')) { 
        $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_invoices`
          ADD COLUMN `approval_status` INT(11) NULL
        ;");
      }
   }

}