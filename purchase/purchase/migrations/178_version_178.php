<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_178 extends App_module_migration
{
   public function up()
   {
      
      add_option('pur_employees_receive_notifications_when_new_vendor_registers', '');

      create_email_template('New Vendor Registers', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">One new vendor has just registered.</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {vendor_link}
        </span><br /><br />', 'purchase_order', 'New Vendor Registers (Sent to staff)', 'pur-new-vendor-register');
   }

}