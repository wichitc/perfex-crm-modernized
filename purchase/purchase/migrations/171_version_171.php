<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_171 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      
      add_option('pur_can_select_approvers_on_faf_form', '0');

      if (!$CI->db->table_exists(db_prefix() . 'pur_faf_requests')) {
          $CI->db->query('CREATE TABLE `' . db_prefix() . "pur_faf_requests` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `vendor_id` INT(11) NULL,
            `reference_number` TEXT NULL,
            `genrated_po_number` TEXT NULL,
            `amount_request` decimal(15,2) NULL,
            `department` INT(11) NULL,
            `requestor` INT(11) NULL,
            `summary` TEXT NULL,
            `approval_setting` LONGTEXT NULL,
            `approve_status` INT(1) NOT NULL DEFAULT '1',
            `requestor_signed_at` datetime NULL,
            `created_at` datetime NULL,
            `created_by` INT(11) NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
      }


   }

}