<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_158 extends App_module_migration
{
   public function up()
   {
      create_email_template('Purchase Quotation Created/Updated (Sent to staff)', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">The Purchase Quotation {pq_number} has a new change.</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {admin_quotation_link}
        </span><br /><br />', 'purchase_order', 'Purchase Quotation Created/Updated (Sent to staff)', 'purchase-quotation-created-updated');


      create_email_template('Purchase Invoice Created/Updated (Sent to staff)', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">The Purchase Invoice {invoice_number} has a new change.</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {invoice_link}
        </span><br /><br />', 'purchase_order', 'Purchase Invoice Created/Updated (Sent to staff)', 'purchase-invoice-created-updated');

      create_email_template('New Paymemt Approved(Sent to vendor)', '<span style=\"font-size: 12pt;\"> Hello !. </span><br /><br /><span style=\"font-size: 12pt;\">The Payment of Purchase Invoice {invoice_number} has been approved</span><br /><br /><span style=\"font-size: 12pt;\"><br />Please click on the link to view information: {vendor_invoice_link}
        </span><br /><br />', 'purchase_order', 'New Paymemt Approved(Sent to vendor)', 'new-payment-approved');
   }

}