<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_159 extends App_module_migration
{
   public function up()
   {

    $CI = &get_instance();
      // 1: only need 1 person approve
      // 0: Need all approval
      if (!$CI->db->field_exists('approval_type' ,db_prefix() . 'pur_approval_setting')) { 
        $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_approval_setting`
          ADD COLUMN `approval_type` INT(11) NULL DEFAULT '0'
        ;");
      }
   }

}