<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_152 extends App_module_migration
{
    public function up()
    {
        create_email_template('Purchase Order Discuss (Sent to Vendor)', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">The Purchase Order {po_number} has a new discussion.</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {po_vendor_link}
          </span><br /><br />', 'purchase_order', 'Purchase Order Discuss (Sent to Vendor)', 'purchase-order-discuss-to-vendor');

        create_email_template('Purchase Order Discuss (Sent to staff)', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">The Purchase Order {po_number} has a new discussion.</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {po_admin_link}
          </span><br /><br />', 'purchase_order', 'Purchase Order Discuss (Sent to Staff)', 'purchase-order-discuss-to-staff');
    	
    }

}