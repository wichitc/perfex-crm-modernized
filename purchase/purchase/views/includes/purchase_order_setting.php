<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
  <div class="col-md-12">
    <h4 class="bold"><?php echo _l('pur_general_infor'); ?></h4>
    <hr class="bold mtop5 hr-panel-separator">
  </div>
</div>

<?php echo form_open_multipart(admin_url('purchase/pur_order_setting'),array('id'=>'pur_order_setting-form')); ?>
<div class="col-md-6">
	<?php echo render_input('pur_order_prefix','pur_order_prefix',get_purchase_option('pur_order_prefix')); ?>
</div>

<div class="col-md-6">
  <?php echo render_input('pur_request_prefix','pur_request_prefix',get_purchase_option('pur_request_prefix')); ?>
</div>

<div class="col-md-6">
  <?php echo render_input('pur_inv_prefix','pur_inv_prefix',get_purchase_option('pur_inv_prefix')); ?>
</div>

<div class="col-md-6">
  <?php echo render_input('debit_note_prefix','debit_note_prefix',get_option('debit_note_prefix')); ?>
</div>

<div class="col-md-6">
  <?php echo render_input('next_po_number','next_po_number',get_purchase_option('next_po_number'),'number'); ?>
</div>

<div class="col-md-6">
  <?php echo render_input('next_pr_number','next_pr_number',get_purchase_option('next_pr_number'),'number'); ?>
</div>

<div class="col-md-6">
  <?php echo render_input('pur_invoice_auto_operations_hour','pur_invoice_auto_operations_hour',get_option('pur_invoice_auto_operations_hour'),'number', array('data-toggle'=>'tooltip','data-title'=>_l('hour_of_day_perform_auto_operations_format'),'max'=>23)); ?>
</div>

<div class="col-md-6">
  <?php $templates = [
    ['id' => 'default', 'name' => _l('pur_default_template')],
    ['id' => 'custom_gp_template', 'name' => _l('pur_custom_gp_template')],
  ];
  echo render_select('po_pdf_template', $templates, ['id', 'name'], 'pur_po_pdf_template',get_option('po_pdf_template')); ?>
</div>

<div class="col-md-6">
  <?php echo render_input('pur_receive_notification_before_purchase_invoice_expires_x_days','pur_receive_notification_before_purchase_invoice_expires_x_days',get_option('pur_receive_notification_before_purchase_invoice_expires_x_days'),'number'); ?>
  
</div>

<div class="col-md-6">
  <?php 
  echo render_select('pur_employees_receive_notifications_when_purchase_invoices_expire', $staffs, ['staffid', 'full_name'], 'pur_employees_receive_notifications_when_purchase_invoices_expire',get_option('pur_employees_receive_notifications_when_purchase_invoices_expire')); ?>
</div>

<div class="col-md-6">

  <?php $vendor_notified_staffs = [];
        if(get_option('pur_employees_receive_notifications_when_new_vendor_registers') != ''){
         $vendor_notified_staffs = explode(',',get_option('pur_employees_receive_notifications_when_new_vendor_registers'));
        }
  echo render_select('pur_employees_receive_notifications_when_new_vendor_registers[]', $staffs, ['staffid', 'full_name'], 'pur_employees_receive_notifications_when_new_vendor_registers', $vendor_notified_staffs, ['multiple' => 'true'], [], '', '', false); ?>
</div>

<div class="row">
  <div class="col-md-12">
    <h4 class="bold"><?php echo _l('pur_shipping_infor'); ?></h4>
    <hr class="bold mtop5 hr-panel-separator">
  </div>
</div>

<div class="col-md-6">
  <?php echo render_textarea('pur_company_address','pur_company_address',get_option('pur_company_address'), ['rows' => 7]); ?>

  <?php echo render_input('pur_company_zipcode','pur_company_zipcode',get_option('pur_company_zipcode'),'text'); ?>
</div>

<div class="col-md-6">
  <div class="row">
    <div class="col-md-12">
      <?php echo render_input('pur_company_city','pur_company_city',get_option('pur_company_city'),'text'); ?>
    </div>
    <div class="col-md-12">
      <?php echo render_input('pur_company_state','pur_company_state',get_option('pur_company_state'),'text'); ?>
    </div>

    <div class="col-md-12">
      <?php echo render_input('pur_company_country_text','pur_company_country_text',get_option('pur_company_country_text'),'text'); ?>
    </div>

    <div class="col-md-12">
      <?php $countries= get_all_countries();
       $pur_company_country_code = get_option('pur_company_country_code');
       $selected = $pur_company_country_code;
       echo render_select('pur_company_country_code',$countries,array( 'country_id',array( 'short_name')), 'pur_company_country_code',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
       ?>

    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <h4 class="bold"><?php echo _l('pur_other_infor'); ?></h4>
    <hr class="bold mtop5 hr-panel-separator">
  </div>
</div>
	
<div class="col-md-12">
  <?php echo render_textarea('terms_and_conditions', 'terms_and_conditions', get_purchase_option('terms_and_conditions'), [], [], '', 'tinymce'); ?>
</div>

<div class="col-md-12">
  <?php echo render_textarea('vendor_note', 'vendor_note', get_purchase_option('vendor_note')); ?>
</div>

<?php if(get_po_logo() == ''){ ?>
  <div class="col-md-6 form-group">
    <label for="po_logo"><?php echo _l('pdf_logo'); ?></label>
    <input type="file" class="form-control" name="po_logo" accept="image/*" data-toggle="tooltip" title="<?php echo _l('settings_general_company_logo_tooltip'); ?>" />
  </div>
<?php } else { ?>
<div class="col-md-5">
  <?php echo get_po_logo(500, "img img-responsive", 'setting'); ?>
</div>
<?php if( is_admin()){ ?>
          <div class="col-md-6 text-left">
            <a href="<?php echo admin_url('purchase/remove_po_logo'); ?>" data-toggle="tooltip" title="<?php echo _l('remove_po_logo'); ?>" class="_delete text-danger"><i class="fa fa-remove"></i></a>
          </div>
        <?php } ?>
<?php } ?>

<div class="row">
  <div class="col-md-12">
    <h4 class="bold"><?php echo _l('vendors_portal'); ?></h4>
    <hr class="bold mtop5 hr-panel-separator">
  </div>
</div>

<div class="col-md-6">

  <label for="purchase_active_vendor_portal"><?php echo _l('vendors_portal').' '; ?><i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo _l('vendors_portal_active_note'); ?>"></i></label>
  <?php $enabled = [
    ['id' => 1, 'name' => _l('pur_enable')],
    ['id' => 2, 'name' => _l('pur_disable')]
  ];
       $purchase_active_vendor_portal = get_option('purchase_active_vendor_portal');
       $selected = $purchase_active_vendor_portal;
       echo render_select('purchase_active_vendor_portal',$enabled,array( 'id','name'), '',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
       ?>
</div>

<div class="col-md-6">
  <?php $statuses= [];
    $statuses = [
      ['id' => 1, 'name' => _l('purchase_draft')],
      ['id' => 2, 'name' => _l('purchase_approved')],
      ['id' => 3, 'name' => _l('pur_rejected')],
      ['id' => 4, 'name' => _l('pur_canceled')]
    ];

   $show_status = get_option('purchase_order_status_can_show_on_vendor_portal');

   $selected = [];
   if($show_status != ''){
    $selected = explode(',', $show_status);
   }

   echo render_select('purchase_order_status_can_show_on_vendor_portal[]',$statuses,array( 'id','name'), 'purchase_order_status_can_show_on_vendor_portal',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'), 'multiple' => true ), [], '', '', false);
   ?>

</div>


<div class="row">
  <div class="col-md-12">
    <hr class="bold mtop5 hr-panel-separator">
  </div>
</div>

	<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
	<?php echo form_close(); ?>

<div class="clearfix"></div>


