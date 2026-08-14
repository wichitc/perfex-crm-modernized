<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_157 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();

      if ($CI->db->field_exists('approval_status' ,db_prefix() . 'pur_invoices')) { 
        
        $CI->db->update(db_prefix().'pur_invoices', ['approval_status' => 2]);
      }
   }

}