<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_165 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      if ($CI->db->table_exists(db_prefix() . 'pur_orders')) {
        if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_orders')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_orders`
            ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
            ");
        }
      }

      if ($CI->db->table_exists(db_prefix() . 'pur_invoices')) {
        if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_invoices')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_invoices`
            ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
            ");
        }
      }

      if ($CI->db->table_exists(db_prefix() . 'pur_invoice_payment')) {
        if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_invoice_payment')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_invoice_payment`
            ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
            ");
        }
      }

      if ($CI->db->table_exists(db_prefix() . 'wh_order_returns_refunds')) {
        if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'wh_order_returns_refunds')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "wh_order_returns_refunds`
            ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
            ");
        }
      }
   }

}