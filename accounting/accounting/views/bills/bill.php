<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <?php echo form_hidden('type',$type); ?>

            <div class="row">
               <div class="col-md-12" id="small-table">
                  <div class="panel_s">
                     <?php
                     if(isset($bill) && !isset($is_duplicate)){
                       echo form_hidden('is_edit','true');
                    }
                    ?>
                    <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'expense-form','class'=>'dropzone dropzone-manual')) ;?>
                    <div class="panel-body">
                     <div class="clearfix"></div>
                     <h4 class="no-margin"><?php echo $title; ?></h4>
                     <hr class="hr-panel-heading" />
                     <div class="row">
                     <div class="col-md-6">
                       <div class="row">
                        <div class="col-md-12">
                          <div class="form-group select-placeholder"<?php if(isset($bill) && !empty($bill->is_recurring_from)){ ?> data-toggle="tooltip" data-title="<?php echo _l('create_recurring_from_child_error_message', [_l('invoice_lowercase'),_l('invoice_lowercase'), _l('invoice_lowercase')]); ?>"<?php } ?>>
                             <label for="recurring" class="control-label">
                             <?php echo _l('recurring_bill'); ?>
                             </label>
                             <select class="selectpicker"
                             data-width="100%"
                             name="recurring"
                             data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                             <?php
                             // The problem is that this invoice was generated from previous recurring invoice
                             // Then this new invoice you set it as recurring but the next invoice date was still taken from the previous invoice.
                             if(isset($bill) && !empty($bill->acc_is_recurring_from)){echo 'disabled';} ?>
                             >
                                <?php for($i = 0; $i <=12; $i++){ ?>
                                <?php
                                   $selected = '';
                                   if(isset($bill)){
                                     if($bill->acc_custom_recurring == 0){
                                      if($bill->acc_recurring == $i){
                                        $selected = 'selected';
                                      }
                                    }
                                   }
                                   if($i == 0){
                                    $reccuring_string =  _l('invoice_add_edit_recurring_no');
                                   } else if($i == 1){
                                    $reccuring_string = _l('invoice_add_edit_recurring_month',$i);
                                   } else {
                                    $reccuring_string = _l('invoice_add_edit_recurring_months',$i);
                                   }
                                   ?>
                                <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $reccuring_string; ?></option>
                                <?php } ?>
                                <option value="custom" <?php if(isset($bill) && $bill->acc_recurring != 0 && $bill->acc_custom_recurring == 1){echo 'selected';} ?>><?php echo _l('recurring_custom'); ?></option>
                             </select>
                          </div>
                       </div>
                       <div class="recurring_custom <?php if((isset($bill) && $bill->acc_custom_recurring != 1) || (!isset($bill))){echo 'hide';} ?>">
                          <div class="col-md-6">
                             <?php $value = (isset($bill) && $bill->acc_custom_recurring == 1 ? $bill->acc_recurring : 1); ?>
                             <?php echo render_input('repeat_every_custom','',$value,'number',array('min'=>1)); ?>
                          </div>
                          <div class="col-md-6">
                             <select name="repeat_type_custom" id="repeat_type_custom" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value="day" <?php if(isset($bill) && $bill->acc_custom_recurring == 1 && $bill->acc_recurring_type == 'day'){echo 'selected';} ?>><?php echo _l('invoice_recurring_days'); ?></option>
                                <option value="week" <?php if(isset($bill) && $bill->acc_custom_recurring == 1 && $bill->acc_recurring_type == 'week'){echo 'selected';} ?>><?php echo _l('invoice_recurring_weeks'); ?></option>
                                <option value="month" <?php if(isset($bill) && $bill->acc_custom_recurring == 1 && $bill->acc_recurring_type == 'month'){echo 'selected';} ?>><?php echo _l('invoice_recurring_months'); ?></option>
                                <option value="year" <?php if(isset($bill) && $bill->acc_custom_recurring == 1 && $bill->acc_recurring_type == 'year'){echo 'selected';} ?>><?php echo _l('invoice_recurring_years'); ?></option>
                             </select>
                          </div>
                       </div>
                       <div id="cycles_wrapper" class="<?php if(!isset($bill) || (isset($bill) && $bill->acc_recurring == 0)){echo ' hide';}?>">
                          <div class="col-md-12">
                             <?php $value = (isset($bill) ? $bill->acc_cycles : 0); ?>
                             <div class="form-group recurring-cycles">
                               <label for="cycles"><?php echo _l('recurring_total_cycles'); ?>
                                 <?php if(isset($bill) && $bill->acc_total_cycles > 0){
                                   echo '<small>' . _l('cycles_passed', $bill->acc_total_cycles) . '</small>';
                                 }
                                 ?>
                               </label>
                               <div class="input-group">
                                 <input type="number" class="form-control"<?php if($value == 0){echo ' disabled'; } ?> name="cycles" id="cycles" value="<?php echo $value; ?>" <?php if(isset($bill) && $bill->acc_total_cycles > 0){echo 'min="'.($bill->acc_total_cycles).'"';} ?>>
                                 <div class="input-group-addon">
                                   <div class="checkbox">
                                     <input type="checkbox"<?php if($value == 0){echo ' checked';} ?> id="unlimited_cycles">
                                     <label for="unlimited_cycles"><?php echo _l('cycles_infinity'); ?></label>
                                   </div>
                                 </div>
                               </div>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>
                     </div>
                     <?php $card_image = site_url('modules/accounting/assets/images/check_card3.png') ?>
                     <div class="col-md-10 check-card bill-card" style="background: url('<?php echo new_html_entity_decode($card_image); ?>')">

                        <div class="row">
                           <div class="col-md-2">
                              <h3 class="no-margin"><?php echo _l('acc_bill'); ?></h3>
                           </div>

                           <div class="col-md-5">
                           </div>
                           <?php if (get_option('acc_enable_class_tracking') == 1) { ?>
                              <div class="col-md-2">
                                 <label for="class" class="mtop10"><?php echo _l('acc_class'); ?></label>

                              </div>
                              <div class="col-md-3">
                                 <?php $value = (isset($bill) ? $bill->acc_class : '');
                                    echo render_select('acc_class',$classes,array('id','name'),'',$value);  ?>
                              </div>
                              <?php } ?>

                       </div>

                     <?php $vendor =  isset($bill) ? $bill->vendor : '' ;?>

                     <div class="row">
                        <div class="col-md-2">
                           <label for="vendor" class="mtop5"><?php echo _l('acc_vendor'); ?></label>
                        </div>

                        <div class="col-md-5">
                           <?php echo render_select('vendor', $list_vendor, array('userid','company'), '', $vendor); ?>
                        </div>
                        <div class="col-md-2">
                           <label for="date" class="mtop5"><?php echo _l('bill_date'); ?></label>
                        </div>
                        <div class="col-md-3">
                           <?php $value = (isset($bill) ? _d($bill->date) : _d(date('Y-m-d')));
                           $date_attrs = array();
                           if(isset($bill) && $bill->recurring > 0 && $bill->last_recurring_date != null) {
                             $date_attrs['disabled'] = true;
                          }
                          ?>
                          <?php echo render_date_input('date','',$value,$date_attrs);?>
                       </div>

                    </div>
                    <div class="row">
                     <div class="col-md-2">
                        <label for="expense_name" class="mtop5"><i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('expense_name_help'); ?> - <?php echo _l('expense_field_billable_help',_l('expense_name')); ?>"></i><?php echo _l('expense_name'); ?></label>
                        
                     </div>
                     <div class="col-md-5">
                        <?php $value = (isset($bill) ? $bill->expense_name : ''); ?>
                        <?php echo render_input('expense_name','',$value); ?>
                     </div>
                     <div class="col-md-2">
                        <label for="reference_no" class="mtop5"><?php echo _l('expense_add_edit_reference_no'); ?></label>
                     </div>
                     <div class="col-md-3">
                        <?php $value = (isset($bill) ? $bill->reference_no : ''); ?>
                        <?php echo render_input('reference_no','',$value); ?>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-md-2">
                        <label for="note" class="mtop5"><i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('expense_field_billable_help',_l('acc_memo')); ?>"></i><?php echo _l('acc_memo'); ?></label>
                     </div>
                     <div class="col-md-5">
                        <?php $value = (isset($bill) ? $bill->note : ''); ?>
                        <?php echo render_textarea('note','',$value,array('rows'=>1),array()); ?>
                     </div>
                     <div class="col-md-2">
                        <label for="due_date" class="mtop5"><?php echo _l('acc_due_date'); ?></label>

                     </div>
                     <div class="col-md-3">
                        <?php $due_date = (isset($bill) ? _d($bill->due_date) : _d(date('Y-m-d')));
                        echo render_date_input('due_date','',$due_date,$date_attrs); ?>
                     </div>
                  </div>

                  <div class="row">
                        <?php if(isset($bill) && $bill->attachment !== ''){ ?>
                     <div class="col-md-7">
                           <div class="row">
                              <div class="col-md-10">
                                 <i class="<?php echo get_mime_class($bill->filetype); ?>"></i> <a href="<?php echo admin_url('accounting/download_file/bill/'.$bill->id); ?>"><?php echo $bill->attachment; ?></a>
                              </div>
                              <?php if($bill->attachment_added_from == get_staff_user_id() || is_admin()){ ?>
                                 <div class="col-md-2 text-right">
                                    <a href="<?php echo admin_url('accounting/delete_bill_attachment/'.$bill->id); ?>" class="text-danger _delete"><i class="fa fa fa-times"></i></a>
                                 </div>
                              <?php } ?>
                           </div>
                           
                     </div>
                        <?php } ?>

                        <?php if(!isset($bill) || (isset($bill) && $bill->attachment == '')){ ?>
                           <div class="col-md-7">
                              <div id="dropzoneDragArea" class="dz-default dz-message">
                                 <span><?php echo _l('acc_attachment'); ?></span>
                              </div>
                           </div>
                           <div class="col-md-5">
                              <div class="dropzone-previews"></div>
                           </div>
                        <?php } ?>
                    
                  </div>

               </div>

               <div class="col-md-6">
                  
                  <div class="btn-bottom-toolbar text-right">
                   <a href="<?php echo admin_url('accounting/bills'); ?>"class="btn btn-default mright10 ">
                     <?php echo _l('close'); ?>
                  </a>
                  <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
               </div>
            </div>

            <div class="row">
               <div class="col-md-6 hide">
                  <?php $selected = (isset($bill) ? $bill->paymentmode : ''); ?>
                  <?php echo render_select('paymentmode',$payment_modes,array('id','name'),'payment_mode',$selected); ?>
               </div>
               
            </div>
            <div class="col-md-6">
               
               <div class="row hide">
                  <div class="col-md-6">
                     <div class="form-group select-placeholder">
                        <label class="control-label" for="tax"><?php echo _l('tax_1'); ?></label>
                        <select class="selectpicker display-block" data-width="100%" name="tax" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value=""><?php echo _l('no_tax'); ?></option>
                           <?php foreach($taxes as $tax){
                              $selected = '';
                              if(isset($bill)){
                               if($tax['id'] == $bill->tax){
                                $selected = 'selected';
                             }
                          } ?>
                          <option
                          value="<?php echo $tax['id']; ?>"
                          <?php echo $selected; ?>
                          data-percent="<?php echo $tax['taxrate']; ?>"
                          data-subtext="<?php echo $tax['name']; ?>">
                          <?php echo $tax['taxrate']; ?>%
                       </option>
                    <?php } ?>
                 </select>

              </div>
           </div>
           <div class="col-md-6">
            <div class="form-group select-placeholder">
               <label class="control-label" for="tax2"><?php echo _l('tax_2'); ?></label>
               <select class="selectpicker display-block" data-width="100%" name="tax2" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" <?php if(!isset($bill) || isset($bill) && $bill->tax == 0){echo 'disabled';} ?>>
                  <option value=""><?php echo _l('no_tax'); ?></option>
                  <?php foreach($taxes as $tax){
                     $selected = '';
                     if(isset($bill)){
                      if($tax['id'] == $bill->tax2){
                       $selected = 'selected';
                    }
                 } ?>
                 <option
                 value="<?php echo $tax['id']; ?>"
                 <?php echo $selected; ?>
                 data-percent="<?php echo $tax['taxrate']; ?>"
                 data-subtext="<?php echo $tax['name']; ?>">
                 <?php echo $tax['taxrate']; ?>%
              </option>
           <?php } ?>
        </select>
     </div>
  </div>
  <?php if(!isset($bill)) { ?>
     <div class="col-md-12 hide" id="tax_subtract">
      <div class="info-block">
         <div class="checkbox checkbox-primary no-margin">
           <input type="checkbox" id="tax1_included">
           <label for="tax1_included">
            <?php echo _l('subtract_tax_total_from_amount','<span id="tax_subtract_total" class="bold"></span>'); ?>
         </label>
      </div>
      <small class="text-muted">
        <?php echo _l('expense_subtract_info_text'); ?>
     </small>
  </div>
</div>
<?php } ?>
</div>
<div class="clearfix mtop15"></div>

</div>
<div class="horizontal-scrollable-tabs preview-tabs-top mtop25">
<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
  <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
  <div class="horizontal-tabs">
    <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
        <li role="presentation" class="active">
           <a href="#expenses" aria-controls="expenses" role="tab" id="tab_out_of_stock" data-toggle="tab">
              <?php echo _l('expenses') ?>
           </a>
        </li>
        <li role="presentation">
           <a href="#items" aria-controls="items" role="tab" id="tab_out_of_stock" data-toggle="tab">
              <?php echo _l('items') ?>
           </a>
        </li>
    </ul>
    </div>
</div>
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="expenses">
   <table id="bill-debit-account" class="table invoice-mapping-table items">
      <thead>
       
         <tr>
          <th width="50%"><?php echo _l('debit_account'); ?></th>
          <th width="40%"><?php echo _l('amount'); ?></th>
          <th width="10%"></th>
       </tr>
    </thead>
    <?php if(isset($bill) && !empty($bill->debit_account)){
      $i               = 0;
      foreach($bill->debit_account as $debit_account){ 
       ?>
       <tr class="bill-debit-account-<?php echo new_html_entity_decode($i); ?> template_children">
         <td>
            <?php echo render_select('debit_account['.$i.']', $list_debit_account, array('id','name'), '',$debit_account['account'], array()); ?>
         </td>
         <td>
            <?php echo render_input('debit_amount['.$i.']', '',acc_format_number($debit_account['amount']),'text', array('data-type' => 'currency')); ?>
         </td>
         <td>
            <button name="add_template" class="btn <?php if($i == 0){ echo 'new_debit_template btn-success'; }else{ echo 'remove_debit_template btn-danger';} ?>" data-ticket="true" type="button"><i class="fa <?php if($i == 0){ echo 'fa-plus'; }else{ echo 'fa-minus';} ?>"></i></button>
         </td>
      </tr>
      <?php 
      $i++;
   }
}else{
   ?>
   <tr class="bill-debit-account-0 bill-item-id-0 template_children">
      <td>
         <?php echo render_select('debit_account[0]', $list_debit_account, array('id','name'), '',$acc_bill_deposit_to, array()); ?>
      </td>
      <td>
         <?php echo render_input('debit_amount[0]', '','','text', array('data-type' => 'currency')); ?>
      </td>
      <td>
         <button name="add_template" class="btn new_debit_template btn-success" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
      </td>
   </tr>
<?php } ?>
</table>
<table id="bill-credit-account" class="table invoice-mapping-table items">
   <thead>
      <tr>
       <th width="50%"><?php echo _l('credit_account'); ?></th>
       <th width="40%"><?php echo _l('amount'); ?></th>
       <th width="10%"><i class="fa fa-cog"></i></th>
    </tr>
 </thead>
 <tbody id="body-bill-credit-account">
   
  <?php if(isset($bill) && !empty($bill->credit_account)){
   $i               = 0;
   foreach($bill->credit_account as $credit_account){ 
    ?>
    <tr class="bill-credit-account-<?php echo new_html_entity_decode($i); ?> template_children">
      <td>
         <?php echo render_select('credit_account['.$i.']', $list_credit_account, array('id','name'), '',$credit_account['account'], array()); ?>
      </td>
      <td>
         <?php echo render_input('credit_amount['.$i.']', '',acc_format_number($credit_account['amount']),'text', array('data-type' => 'currency')); ?>
      </td>
      <td>
         <button name="add_template" class="btn <?php if($i == 0){ echo 'new_credit_template btn-success'; }else{ echo 'remove_template btn-danger';} ?>" data-ticket="true" type="button"><i class="fa <?php if($i == 0){ echo 'fa-plus'; }else{ echo 'fa-minus';} ?>"></i></button>
      </td>
   </tr>
   <?php 
   $i++;
}
}else{
   ?>
   <tr class="bill-credit-account-0 bill-item-id-0 template_children">
      <td>
         <?php echo render_select('credit_account[0]', $list_credit_account, array('id','name'), '',$acc_bill_deposit_to, array()); ?>
      </td>
      <td>
         <?php echo render_input('credit_amount[0]', '','','text', array('data-type' => 'currency')); ?>
      </td>
      <td>
         <button name="add_template" class="btn new_credit_template btn-success" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
      </td>
   </tr>
<?php } ?>
</tbody>
</table>
</div>
  <div role="tabpanel" class="tab-pane" id="items">
   <table id="bill-item-list" class="table invoice-mapping-table items">
      <thead>
       
         <tr>
          <th width="30%"><?php echo _l('acc_item'); ?></th>
          <th width="30%"><?php echo _l('description'); ?></th>
          <th width="10%"><?php echo _l('qty'); ?></th>
          <th width="10%"><?php echo _l('cost'); ?></th>
          <th width="10%"><?php echo _l('amount'); ?></th>
          <th width="10%"></th>
       </tr>
    </thead>
    <?php if(isset($bill) && count($bill->bill_items) > 0){
      $i               = 0;
      foreach($bill->bill_items as $bill_item){ 
       ?>
       <tr class="bill-item-list-<?php echo new_html_entity_decode($i); ?> template_children" data-index="<?php echo new_html_entity_decode($i); ?>">
         <td>
            <?php echo render_select('item_id['.$i.']', $items, array('id','description'), '',$bill_item['item_id'], array('onchange' => 'bill_item_change(); return false;')); ?>
         </td>
         <td>
            <?php echo render_input('item_description['.$i.']', '', $bill_item['description']); ?>
         </td>
         <td>
            <?php echo render_input('item_qty['.$i.']', '',number_format($bill_item['qty'],2),'number', array('onchange' => 'bill_item_qty_change(this); return false;')); ?>
         </td>
         <td>
            <?php echo render_input('item_cost['.$i.']', '',acc_format_number($bill_item['cost']),'text', array('data-type' => 'currency', 'onchange' => 'bill_item_cost_change(this); return false;')); ?>
         </td>
         <td>
            <?php echo render_input('item_amount['.$i.']', '',acc_format_number($bill_item['amount']),'text', array('readonly' => true, 'data-type' => 'currency')); ?>
         </td>
         <td>
            <button name="add_template" class="btn <?php if($i == 0){ echo 'new_item_template btn-success'; }else{ echo 'remove_item_template btn-danger';} ?>" data-ticket="true" type="button"><i class="fa <?php if($i == 0){ echo 'fa-plus'; }else{ echo 'fa-minus';} ?>"></i></button>
         </td>
      </tr>
      <?php 
      $i++;
   }
}else{
   ?>
   <tr class="bill-item-list-0 bill-item-id-0 template_children" data-index="0">
      <td>
            <?php echo render_select('item_id[0]', $items, array('id','description'), '','', array('onchange' => 'bill_item_change(this); return false;')); ?>
         </td>
         <td>
            <?php echo render_input('item_description[0]', '', ''); ?>
         </td>
         <td>
            <?php echo render_input('item_qty[0]', '','','number', array('onchange' => 'bill_item_qty_change(this); return false;')); ?>
         </td>
         <td>
            <?php echo render_input('item_cost[0]', '','','text', array('data-type' => 'currency', 'onchange' => 'bill_item_cost_change(this); return false;')); ?>
         </td>
         <td>
            <?php echo render_input('item_amount[0]', '','','text', array('readonly' => true, 'data-type' => 'currency')); ?>
         </td>
      <td>
         <button name="add_template" class="btn new_item_template btn-success" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
      </td>
   </tr>
<?php } ?>
</table>
</div>

<div class="col-md-5 col-md-offset-7">
   <table class="table text-right bold">
      <tbody>
         <tr id="tr_maximum_amount" class="hide">
            <td><span class="bold"><?php echo _l('maximum_amount'); ?></span>
            </td>
            <td id="bill-maximum-amount" class="text-danger">
            </td>
         </tr>
         <tr>
            <td><span class="bold"><?php echo _l('invoice_total'); ?></span>
            </td>
            <?php $value = (isset($bill) ? $bill->amount : 0); ?>
            <?php echo form_hidden('amount', $value); ?>
            <td id="bill-total" class="text-danger">
               <?php echo app_format_money($value, $currency->name); ?>
            </td>
         </tr>
      </tbody>
   </table>
</div>
</div>
</div>
</div>
<div class="col-md-7 small-table-right-col">
   <div id="bill_div" class="hide">
   </div>
</div>
</div>
</div>
<?php echo form_close(); ?>
<div class="btn-bottom-pusher"></div>

</div>
</div>
</div>

<?php $this->load->view('admin/expenses/expense_category'); ?>
<?php init_tail(); ?>
<?php require 'modules/accounting/assets/js/bill/bill_js.php';?>

</body>
</html>
