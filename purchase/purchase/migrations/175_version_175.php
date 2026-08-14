<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_175 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      
      add_option('pur_receive_notification_before_purchase_invoice_expires_x_days', '7');
      add_option('pur_employees_receive_notifications_when_purchase_invoices_expire', '');


      create_email_template('Purchase Invoice About to Expire', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">The Purchase Invoice {invoice_number}  about to expire.</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {invoice_link}
        </span><br /><br />', 'purchase_order', 'Purchase Invoice About to Expire (Sent to staff)', 'purchase-invoice-about-to-expire');

      if (!$CI->db->field_exists('expire_notify' ,db_prefix() . 'pur_invoices')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_invoices`
            ADD COLUMN `expire_notify` tinyint(1) NOT NULL DEFAULT '0'
            ");
      }
      
   }

}