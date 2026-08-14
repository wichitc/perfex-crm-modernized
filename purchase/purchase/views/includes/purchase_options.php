<div class="col-md-12">

  <div class="col-md-6">
   
      <div class="checkbox checkbox-primary">
        <input onchange="purchase_order_setting(this); return false" type="checkbox" id="purchase_order_setting" name="purchase_setting[purchase_order_setting]" <?php if(get_purchase_option('purchase_order_setting') == 1 ){ echo 'checked';} ?> value="purchase_order_setting">
        <label for="purchase_order_setting"><?php echo _l('create_purchase_order_non_create_purchase_request_quotation'); ?>

        <a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('purchase_order_tooltip'); ?>"></i></a>
        </label>
      </div>

   
      <div class="checkbox checkbox-primary">
        <input onchange="item_by_vendor(this); return false" type="checkbox" id="item_by_vendor" name="purchase_setting[item_by_vendor]" <?php if(get_purchase_option('item_by_vendor') == 1 ){ echo 'checked';} ?> value="item_by_vendor">
        <label for="item_by_vendor"><?php echo _l('load_item_by_vendor'); ?>

        </label>
      </div>


    
      <div class="checkbox checkbox-primary">
        <input onchange="po_only_prefix_and_number(this); return false" type="checkbox" id="po_only_prefix_and_number" name="purchase_setting[po_only_prefix_and_number]" <?php if(get_option('po_only_prefix_and_number') == 1 ){ echo 'checked';} ?> value="po_only_prefix_and_number">
        <label for="po_only_prefix_and_number"><?php echo _l('po_only_prefix_and_number'); ?>

        </label>
      </div>


      <div class="checkbox checkbox-primary">
        <input onchange="allow_vendors_to_register(this); return false" type="checkbox" id="allow_vendors_to_register" name="purchase_setting[allow_vendors_to_register]" <?php if(get_option('allow_vendors_to_register') == 1 ){ echo 'checked';} ?> value="allow_vendors_to_register">
        <label for="allow_vendors_to_register"><?php echo _l('allow_vendors_to_register'); ?>

        </label>
      </div>

      <div class="checkbox checkbox-primary">
    <input onchange="allow_vendor_add_edit_delete_purchase_invoice(this); return false" type="checkbox" id="allow_vendor_add_edit_delete_purchase_invoice" name="purchase_setting[allow_vendor_add_edit_delete_purchase_invoice]" <?php if(get_option('allow_vendor_add_edit_delete_purchase_invoice') == 1 ){ echo 'checked';} ?> value="allow_vendor_add_edit_delete_purchase_invoice">
    <label for="allow_vendor_add_edit_delete_purchase_invoice"><?php echo _l('allow_vendor_add_edit_delete_purchase_invoice'); ?>

    </label>
  </div>

    <div class="checkbox checkbox-primary">
      <input onchange="automatically_create_po(this); return false" type="checkbox" id="automatically_create_a_purchase_order_when_the_quotation_is_approved" name="purchase_setting[automatically_create_a_purchase_order_when_the_quotation_is_approved]" <?php if(get_option('automatically_create_a_purchase_order_when_the_quotation_is_approved') == 1 ){ echo 'checked';} ?> value="automatically_create_a_purchase_order_when_the_quotation_is_approved">
      <label for="automatically_create_a_purchase_order_when_the_quotation_is_approved"><?php echo _l('automatically_create_a_purchase_order_when_the_quotation_is_approved'); ?>

      <a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('automatically_create_po_tooltip'); ?>"></i></a>
      </label>
    </div>

    <div class="checkbox checkbox-primary">
        <input onchange="allow_upload_esign_for_approve_type(this); return false" type="checkbox" id="allow_upload_esign_for_approve_type" name="purchase_setting[allow_upload_esign_for_approve_type]" <?php if(get_option('allow_upload_esign_for_approve_type') == 1 ){ echo 'checked';} ?> value="allow_upload_esign_for_approve_type">
        <label for="allow_upload_esign_for_approve_type"><?php echo _l('allow_upload_esign_for_approve_type'); ?>

        </label>
      </div>

      <div class="checkbox checkbox-primary">
        <input onchange="can_edit_po_number(this); return false" type="checkbox" id="can_edit_po_number" name="purchase_setting[can_edit_po_number]" <?php if(get_option('can_edit_po_number') == 1 ){ echo 'checked';} ?> value="can_edit_po_number">
        <label for="can_edit_po_number"><?php echo _l('can_edit_po_number'); ?>

        </label>
      </div>

      <div class="checkbox checkbox-primary">
        <input onchange="pur_can_select_approvers_on_purchase_order_form(this); return false" type="checkbox" id="pur_can_select_approvers_on_purchase_order_form" name="purchase_setting[pur_can_select_approvers_on_purchase_order_form]" <?php if(get_option('pur_can_select_approvers_on_purchase_order_form') == 1 ){ echo 'checked';} ?> value="pur_can_select_approvers_on_purchase_order_form">
        <label for="pur_can_select_approvers_on_purchase_order_form"><?php echo _l('pur_can_select_approvers_on_purchase_order_form'); ?>

        </label>
      </div>


      <div class="checkbox checkbox-primary">
        <input onchange="pur_can_select_approvers_on_faf_form(this); return false" type="checkbox" id="pur_can_select_approvers_on_faf_form" name="purchase_setting[pur_can_select_approvers_on_faf_form]" <?php if(get_option('pur_can_select_approvers_on_faf_form') == 1 ){ echo 'checked';} ?> value="pur_can_select_approvers_on_faf_form">
        <label for="pur_can_select_approvers_on_faf_form"><?php echo _l('pur_can_select_approvers_on_faf_form'); ?>

        </label>
      </div>
      
      <div class="checkbox checkbox-primary">
        <input onchange="po_show_custom_field_on_pdf(this); return false" type="checkbox" id="po_show_custom_field_on_pdf" name="purchase_setting[po_show_custom_field_on_pdf]" <?php if(get_option('po_show_custom_field_on_pdf') == 1 ){ echo 'checked';} ?> value="po_show_custom_field_on_pdf">
        <label for="po_show_custom_field_on_pdf"><?php echo _l('po_show_custom_field_on_pdf'); ?>

        </label>
      </div>


      <div class="checkbox checkbox-primary">
        <input onchange="sms_option(this); return false" type="checkbox" id="sms_notification_when_new_vendor_registers" name="purchase_setting[sms_notification_when_new_vendor_registers]" <?php if(get_option('sms_notification_when_new_vendor_registers') == 1 ){ echo 'checked';} ?> value="sms_notification_when_new_vendor_registers">
        <label for="sms_notification_when_new_vendor_registers"><?php echo _l('sms_notification_when_new_vendor_registers'); ?>

        </label>
      </div>
      
</div>
<div class="col-md-6">
  <div class="checkbox checkbox-primary">
    <input onchange="show_tax_column(this); return false" type="checkbox" id="show_purchase_tax_column" name="purchase_setting[show_purchase_tax_column]" <?php if(get_option('show_purchase_tax_column') == 1 ){ echo 'checked';} ?> value="show_purchase_tax_column">
    <label for="show_purchase_tax_column"><?php echo _l('show_purchase_tax_column'); ?>

    </label>
  </div>

  <div class="checkbox checkbox-primary">
    <input onchange="send_email_welcome_for_new_contact(this); return false" type="checkbox" id="send_email_welcome_for_new_contact" name="purchase_setting[send_email_welcome_for_new_contact]" <?php if(get_option('send_email_welcome_for_new_contact') == 1 ){ echo 'checked';} ?> value="send_email_welcome_for_new_contact">
    <label for="send_email_welcome_for_new_contact"><?php echo _l('send_email_welcome_for_new_contact'); ?>

    </label>
  </div>

  <div class="checkbox checkbox-primary">
    <input onchange="reset_purchase_order_number_every_month(this); return false" type="checkbox" id="reset_purchase_order_number_every_month" name="purchase_setting[reset_purchase_order_number_every_month]" <?php if(get_option('reset_purchase_order_number_every_month') == 1 ){ echo 'checked';} ?> value="reset_purchase_order_number_every_month">
    <label for="reset_purchase_order_number_every_month"><?php echo _l('reset_purchase_order_number_every_month'); ?>

    </label>
  </div>

  <div class="checkbox checkbox-primary">
    <input onchange="show_vendor_note_on_po_pdf(this); return false" type="checkbox" id="show_vendor_note_on_po_pdf" name="purchase_setting[show_vendor_note_on_po_pdf]" <?php if(get_option('show_vendor_note_on_po_pdf') == 1 ){ echo 'checked';} ?> value="show_vendor_note_on_po_pdf">
    <label for="show_vendor_note_on_po_pdf"><?php echo _l('show_vendor_note_on_po_pdf'); ?>

    </label>
  </div>

  <div class="checkbox checkbox-primary">
    <input onchange="allow_vendor_add_edit_delete_purchase_quotation(this); return false" type="checkbox" id="allow_vendor_add_edit_delete_purchase_quotation" name="purchase_setting[allow_vendor_add_edit_delete_purchase_quotation]" <?php if(get_option('allow_vendor_add_edit_delete_purchase_quotation') == 1 ){ echo 'checked';} ?> value="allow_vendor_add_edit_delete_purchase_quotation">
    <label for="allow_vendor_add_edit_delete_purchase_quotation"><?php echo _l('allow_vendor_add_edit_delete_purchase_quotation'); ?>

    </label>
  </div>

  <div class="checkbox checkbox-primary">
    <input onchange="show_purchase_order_name_on_po_pdf(this); return false" type="checkbox" id="show_purchase_order_name_on_po_pdf" name="purchase_setting[show_purchase_order_name_on_po_pdf]" <?php if(get_option('show_purchase_order_name_on_po_pdf') == 1 ){ echo 'checked';} ?> value="show_purchase_order_name_on_po_pdf">
    <label for="show_purchase_order_name_on_po_pdf"><?php echo _l('show_purchase_order_name_on_po_pdf'); ?>

    </label>
  </div>


  <div class="checkbox checkbox-primary">
    <input onchange="show_term_conditions_on_po_pdf(this); return false" type="checkbox" id="show_term_conditions_on_po_pdf" name="purchase_setting[show_term_conditions_on_po_pdf]" <?php if(get_option('show_term_conditions_on_po_pdf') == 1 ){ echo 'checked';} ?> value="show_term_conditions_on_po_pdf">
    <label for="show_term_conditions_on_po_pdf"><?php echo _l('show_term_conditions_on_po_pdf'); ?>

    </label>
  </div>

  <div class="checkbox checkbox-primary">
        <input onchange="can_edit_pr_number(this); return false" type="checkbox" id="can_edit_pr_number" name="purchase_setting[can_edit_pr_number]" <?php if(get_option('can_edit_pr_number') == 1 ){ echo 'checked';} ?> value="can_edit_pr_number">
        <label for="can_edit_pr_number"><?php echo _l('can_edit_pr_number'); ?>

        </label>
      </div>


      <div class="checkbox checkbox-primary">
        <input onchange="pur_department_required_condition(this); return false" type="checkbox" id="pur_department_required_condition" name="purchase_setting[pur_department_required_condition]" <?php if(get_option('pur_department_required_condition') == 1 ){ echo 'checked';} ?> value="pur_department_required_condition">
        <label for="pur_department_required_condition"><?php echo _l('pur_department_required_condition'); ?>

        </label>
      </div>

    <div class="checkbox checkbox-primary">
        <input onchange="pur_order_project_required_condition(this); return false" type="checkbox" id="pur_order_project_required_condition" name="purchase_setting[pur_order_project_required_condition]" <?php if(get_option('pur_order_project_required_condition') == 1 ){ echo 'checked';} ?> value="pur_order_project_required_condition">
        <label for="pur_order_project_required_condition"><?php echo _l('pur_order_project_required_condition'); ?>

        </label>
      </div>  


    <div class="checkbox checkbox-primary">
        <input onchange="sms_option(this); return false" type="checkbox" id="sms_notification_when_vendor_sign_contract" name="purchase_setting[sms_notification_when_vendor_sign_contract]" <?php if(get_option('sms_notification_when_vendor_sign_contract') == 1 ){ echo 'checked';} ?> value="sms_notification_when_vendor_sign_contract">
        <label for="sms_notification_when_vendor_sign_contract"><?php echo _l('sms_notification_when_vendor_sign_contract'); ?>

        </label>
      </div>  
      
</div>

 

  <?php echo form_open_multipart(admin_url('purchase/reset_data'), array('id'=>'reset_data')); ?>
  <div class="_buttons">
      <?php if (is_admin()) { ?>
          <div class="row">
              <div class="col-md-12">
                  <button type="button" class="btn btn-danger intext-btn" onclick="reset_data(this); return false;" ><?php echo _l('reset_data'); ?></button>
                  <a href="#" class="input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('reset_data_title_pur'); ?>"></i></a>
              </div>
          </div>
      <?php } ?>
  </div>
  <?php echo form_close(); ?>
</div>