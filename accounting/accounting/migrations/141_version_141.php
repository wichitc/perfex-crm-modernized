<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_141 extends App_module_migration
{
    public function up()
    {
      $CI = &get_instance();
      if ($CI->db->field_exists('payment_mode_id' ,db_prefix() . 'acc_payment_mode_mappings')) {
        $CI->db->query("ALTER TABLE `" . db_prefix() . "acc_payment_mode_mappings`
          MODIFY COLUMN `payment_mode_id` varchar(40) NOT NULL AFTER `id`;
          ");
      }

      if ($CI->db->field_exists('payment_mode_id' ,db_prefix() . 'acc_expense_category_mapping_details')) {
        $CI->db->query("ALTER TABLE `" . db_prefix() . "acc_expense_category_mapping_details`
          MODIFY COLUMN `payment_mode_id` varchar(40) NOT NULL AFTER `id`;
          ");
      }
    }
}
