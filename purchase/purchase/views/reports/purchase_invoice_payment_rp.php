<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="list_purchase_inv_payment_report" class="hide">
   <div class="row">
      <div class="col-md-4">
         <div class="form-group">
            
         </div>
      </div>
      <div class="clearfix"></div>
   </div>
<table class="table table-purchase-inv-payment-report scroll-responsive">
   <thead>
      <tr>
         <th><?php echo _l('invoice_no'); ?></th>
         <th><?php echo _l('payments_table_mode_heading'); ?></th>
         <th><?php echo _l('payment_transaction_id'); ?></th>
         <th><?php echo _l('payments_table_date_heading'); ?></th>
         <th><?php echo _l('approval_status'); ?></th>
         <th><?php echo _l('payments_table_amount_heading'); ?></th>
      </tr>
   </thead>
   <tbody></tbody>
   <tfoot>
      <tr>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td class="total"></td>
      </tr>
   </tfoot>
</table>
</div>
