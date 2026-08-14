<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_163 extends App_module_migration
{
   public function up()
   {

        create_email_template('Purchase Order Confirmed(Sent to staff)', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">The Purchase Order {po_number} has been confirmed by vendor</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {po_admin_link}
  </span><br /><br />', 'purchase_order', 'Purchase Order Confirmed(Sent to staff)', 'purchase-order-confirmed');
        
   }

}