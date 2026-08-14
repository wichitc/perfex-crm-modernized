<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_179 extends App_module_migration
{
   public function up()
   {
      
      
      create_email_template('Purchase Contract is Signed by Vendor', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">The Purchase Contract Has Been Signed by Vendor.</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {contract_admin_link}
        </span><br /><br />', 'purchase_order', 'Purchase Contract is Signed by Vendor (Sent to staff)', 'pur-contract-is-signed');


      add_option('sms_notification_when_new_vendor_registers', 0 );
      add_option('sms_notification_when_vendor_sign_contract', 0);
   }

}