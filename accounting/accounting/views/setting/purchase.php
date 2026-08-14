<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
  
  $acc_pur_order_automatic_conversion = get_option('acc_pur_order_automatic_conversion');
  $acc_pur_order_payment_account = get_option('acc_pur_order_payment_account');
  $acc_pur_order_deposit_to = get_option('acc_pur_order_deposit_to');

  $acc_pur_invoice_automatic_conversion = get_option('acc_pur_invoice_automatic_conversion');
  $acc_pur_invoice_payment_account = get_option('acc_pur_invoice_payment_account');
  $acc_pur_invoice_deposit_to = get_option('acc_pur_invoice_deposit_to');

  $acc_pur_payment_automatic_conversion = get_option('acc_pur_payment_automatic_conversion');
  $acc_pur_payment_payment_account = get_option('acc_pur_payment_payment_account');
  $acc_pur_payment_deposit_to = get_option('acc_pur_payment_deposit_to');

  $acc_pur_order_return_automatic_conversion = get_option('acc_pur_order_return_automatic_conversion');
  $acc_pur_order_return_payment_account = get_option('acc_pur_order_return_payment_account');
  $acc_pur_order_return_deposit_to = get_option('acc_pur_order_return_deposit_to');

  $acc_pur_refund_automatic_conversion = get_option('acc_pur_refund_automatic_conversion');
  $acc_pur_refund_payment_account = get_option('acc_pur_refund_payment_account');
  $acc_pur_refund_deposit_to = get_option('acc_pur_refund_deposit_to');

  $acc_pur_tax_automatic_conversion = get_option('acc_pur_tax_automatic_conversion');
  $acc_pur_tax_payment_account = get_option('acc_pur_tax_payment_account');
  $acc_pur_tax_deposit_to = get_option('acc_pur_tax_deposit_to');

  $acc_pur_order_shipping_payment_account = get_option('acc_pur_order_shipping_payment_account');
  $acc_pur_order_shipping_deposit_to = get_option('acc_pur_order_shipping_deposit_to');

  $acc_pur_invoice_shipping_payment_account = get_option('acc_pur_invoice_shipping_payment_account');
  $acc_pur_invoice_shipping_deposit_to = get_option('acc_pur_invoice_shipping_deposit_to');

  $acc_pur_order_return_fee_payment_account = get_option('acc_pur_order_return_fee_payment_account');
  $acc_pur_order_return_fee_deposit_to = get_option('acc_pur_order_return_fee_deposit_to');

  $acc_pur_order_discount_payment_account = get_option('acc_pur_order_discount_payment_account');
  $acc_pur_order_discount_deposit_to = get_option('acc_pur_order_discount_deposit_to');

  $acc_pur_invoice_discount_payment_account = get_option('acc_pur_invoice_discount_payment_account');
  $acc_pur_invoice_discount_deposit_to = get_option('acc_pur_invoice_discount_deposit_to');

  $acc_pur_order_return_discount_payment_account = get_option('acc_pur_order_return_discount_payment_account');
  $acc_pur_order_return_discount_deposit_to = get_option('acc_pur_order_return_discount_deposit_to');


  $acc_debit_note_mapping_mode = get_option('acc_debit_note_mapping_mode');

  $acc_debit_note_automatic_conversion = get_option('acc_debit_note_automatic_conversion');
  $acc_debit_note_payment_account = get_option('acc_debit_note_payment_account');
  $acc_debit_note_deposit_to = get_option('acc_debit_note_deposit_to');

  $acc_debit_note_refund_automatic_conversion = get_option('acc_debit_note_refund_automatic_conversion');
  $acc_debit_note_refund_payment_account = get_option('acc_debit_note_refund_payment_account');
  $acc_debit_note_refund_deposit_to = get_option('acc_debit_note_refund_deposit_to');
 ?>


<?php echo form_open(admin_url('accounting/update_purchase_automatic_conversion'),array('id'=>'werehouse-mapping-setup-form')); ?>
<div class="row">
  <div class="col-md-12">
    <h4><?php echo _l('automatic_conversion'); ?></h4>
    <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('purchase_order') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pur_order_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_pur_order_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pur_order_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pur_order_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pur_order_automatic_conversion == 0){echo 'hide';} ?>" id="div_pur_order_automatic_conversion">
          <div class="col-md-12">
            <h5><?php echo _l('default_for_all_item'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_order_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_order_deposit_to,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-12">
            <h5><?php echo _l('pur_shipping_fee'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_shipping_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_order_shipping_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_shipping_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_order_shipping_deposit_to,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-12">
            <h5><?php echo _l('discount'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_discount_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_order_discount_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_discount_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_order_discount_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('purchase_invoice') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pur_invoice_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_pur_invoice_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pur_invoice_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pur_invoice_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pur_invoice_automatic_conversion == 0){echo 'hide';} ?>" id="div_pur_invoice_automatic_conversion">
          <div class="col-md-12">
            <h5><?php echo _l('default_for_all_item'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_invoice_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_invoice_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_invoice_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_invoice_deposit_to,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-12">
            <h5><?php echo _l('pur_shipping_fee'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_invoice_shipping_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_invoice_shipping_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_invoice_shipping_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_invoice_shipping_deposit_to,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-12">
            <h5><?php echo _l('discount'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_invoice_discount_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_invoice_discount_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_invoice_discount_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_invoice_discount_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('payment') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pur_payment_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_pur_payment_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pur_payment_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pur_payment_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pur_payment_automatic_conversion == 0){echo 'hide';} ?>" id="div_pur_payment_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_pur_payment_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_payment_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_payment_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_payment_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('purchase_order_return') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pur_order_return_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_pur_order_return_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pur_order_return_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pur_order_return_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pur_order_return_automatic_conversion == 0){echo 'hide';} ?>" id="div_pur_order_return_automatic_conversion">
          <div class="col-md-12">
            <h5><?php echo _l('default_for_all_item'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_return_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_order_return_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_return_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_order_return_deposit_to,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-12">
            <h5><?php echo _l('fee_for_return_order'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_return_fee_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_order_return_fee_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_return_fee_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_order_return_fee_deposit_to,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-12">
            <h5><?php echo _l('discount'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_return_discount_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_order_return_discount_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_order_return_discount_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_order_return_discount_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('refund') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pur_refund_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_pur_refund_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pur_refund_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pur_refund_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pur_refund_automatic_conversion == 0){echo 'hide';} ?>" id="div_pur_refund_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_pur_refund_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_refund_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_refund_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_refund_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <h5 class="title mbot5"><?php echo _l('debit_note') ?></h5>
              </div>
            </div>
          </div>
          <div class="col-md-6">
          <?php
              $debit_note_mapping_mode = [
                          1 => ['id' => 'on_create', 'name' => _l('map_debit_note_when_creating_it')],
                          2 => ['id' => 'on_apply', 'name' => _l('map_debit_note_when_applying_it_to_an_invoice')],
                        ];
               echo render_select('acc_debit_note_mapping_mode', $debit_note_mapping_mode, array('id', 'name'), '', $acc_debit_note_mapping_mode, array(), array(), '', '', false); ?>
          </div>
        </div>
        <div class="row">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6 border-right">
                    <h5 class="debit_note_label <?php if($acc_debit_note_mapping_mode == 'on_apply'){echo 'hide';} ?>"><?php echo _l('debit_note'); ?></h5>
                    <h5 class="invoices_debited_label <?php if($acc_debit_note_mapping_mode == 'on_create'){echo 'hide';} ?>"><?php echo _l('invoices_debited'); ?></h5>
                  </div>
                  <div class="col-md-6 mtop5">
                      <div class="onoffswitch">
                          <input type="checkbox" id="acc_debit_note_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_debit_note_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_debit_note_automatic_conversion">
                          <label class="onoffswitch-label" for="acc_debit_note_automatic_conversion"></label>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="<?php if($acc_debit_note_automatic_conversion == 0){echo 'hide';} ?>" id="div_debit_note_automatic_conversion">
            <div class="col-md-6">
              <?php echo render_select('acc_debit_note_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_debit_note_payment_account,array(),array(),'','',false); ?>
            </div>
            <div class="col-md-6">
              <?php echo render_select('acc_debit_note_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_debit_note_deposit_to,array(),array(),'','',false); ?>
            </div>
          </div>
          <div class="row  div_debit_note_refund">
            <div class="col-md-12">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6 border-right">
                    <h5><?php echo _l('refund'); ?></h5>
                  </div>
                  <div class="col-md-6 mtop5">
                      <div class="onoffswitch">
                          <input type="checkbox" id="acc_debit_note_refund_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_debit_note_refund_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_debit_note_refund_automatic_conversion">
                          <label class="onoffswitch-label" for="acc_debit_note_refund_automatic_conversion"></label>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="<?php if($acc_debit_note_refund_automatic_conversion == 0){echo 'hide';} ?>  div_debit_note_refund" id="div_debit_note_refund_automatic_conversion">
            <div class="col-md-6">
              <?php echo render_select('acc_debit_note_refund_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_debit_note_refund_payment_account,array(),array(),'','',false); ?>
            </div>
            <div class="col-md-6">
              <?php echo render_select('acc_debit_note_refund_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_debit_note_refund_deposit_to,array(),array(),'','',false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('tax_default') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pur_tax_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_pur_tax_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pur_tax_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pur_tax_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pur_tax_automatic_conversion == 0){echo 'hide';} ?>" id="div_pur_refund_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_pur_tax_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pur_tax_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pur_tax_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pur_tax_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<hr>
<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
<?php echo form_close(); ?>

