<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
  $acc_first_month_of_financial_year = get_option('acc_first_month_of_financial_year');
  $acc_first_month_of_tax_year = get_option('acc_first_month_of_tax_year');
  $acc_accounting_method = get_option('acc_accounting_method');
  $acc_close_the_books = get_option('acc_close_the_books');
  $acc_closing_date = get_option('acc_closing_date');
  $acc_allow_changes_after_viewing = get_option('acc_allow_changes_after_viewing');
  $acc_close_book_password = get_option('acc_close_book_password');
  $acc_close_book_passwordr = get_option('acc_close_book_passwordr');
  $acc_enable_account_numbers = get_option('acc_enable_account_numbers');
  $acc_show_account_numbers = get_option('acc_show_account_numbers');
  $acc_automatic_conversion = get_option('acc_automatic_conversion');

  $acc_invoice_payment_account = get_option('acc_invoice_payment_account');
  $acc_invoice_deposit_to = get_option('acc_invoice_deposit_to');
  $acc_payment_payment_account = get_option('acc_payment_payment_account');
  $acc_payment_deposit_to = get_option('acc_payment_deposit_to');
  $acc_expense_payment_account = get_option('acc_expense_payment_account');
  $acc_expense_deposit_to = get_option('acc_expense_deposit_to');
  
  $acc_enable_all_time_filter = get_option('acc_enable_all_time_filter');
  $acc_mapping_label_type = get_option('acc_mapping_label_type');
  $acc_enable_class_tracking = get_option('acc_enable_class_tracking');
  $acc_hide_zero_value_rows = get_option('acc_hide_zero_value_rows');
 ?>
<?php echo form_open(admin_url('accounting/reset_data')); ?>
<div class="row mbot10">
    <div class="col-md-12">
      <button type="submit" class="btn btn-info _delete"><?php echo _l('reset_data'); ?></button> <label class="text-danger"><?php echo _l('accounting_reset_button_tooltip'); ?></label>
  </div>
</div>
<hr>
<?php echo form_close(); ?>
<?php echo form_open(admin_url('accounting/remove_invalid_entries')); ?>
<div class="row mbot10">
    <div class="col-md-12">
      <button type="submit" class="btn btn-info _delete"><?php echo _l('remove_invalid_entries'); ?></button> <label class="text-danger"><?php echo _l('accounting_remove_invalid_entries_tooltip'); ?></label>
  </div>
</div>
<hr>
<?php echo form_close(); ?>

<?php echo form_open(admin_url('accounting/update_general_setting'),array('id'=>'general-settings-form')); ?>
<div class="row">
  <div class="col-md-12">
    <div class="col-md-6 row">
      <h5 class="title mbot5"><?php echo _l('als_accounting') ?></h5>
        <div class="row">
          <div class="col-md-12">
          <?php
              $month = [
                          1 => ['id' => 'January', 'name' => _l('January')],
                          2 => ['id' => 'February', 'name' => _l('February')],
                          3 => ['id' => 'March', 'name' => _l('March')],
                          4 => ['id' => 'April', 'name' => _l('April')],
                          5 => ['id' => 'May', 'name' => _l('May')],
                          6 => ['id' => 'June', 'name' => _l('June')],
                          7 => ['id' => 'July', 'name' => _l('July')],
                          8 => ['id' => 'August', 'name' => _l('August')],
                          9 => ['id' => 'September', 'name' => _l('September')],
                          10 => ['id' => 'October', 'name' => _l('October')],
                          11 => ['id' => 'November', 'name' => _l('November')],
                          12 => ['id' => 'December', 'name' => _l('December')],
                         ];
               echo render_select('acc_first_month_of_financial_year', $month, array('id', 'name'), _l('first_month_of_financial_year') .' <i class="fa fa-question-circle" data-toggle="tooltip" data-title="'. _l('first_month_of_financial_year_note').'"></i> ', $acc_first_month_of_financial_year, array(), array(), '', '', false); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
          <?php
              $month_of_tax_year = [
                          1 => ['id' => 'same_as_financial_year', 'name' => _l('same_as_financial_year')],
                          2 => ['id' => 'January', 'name' => _l('January')],
                         ];
               echo render_select('acc_first_month_of_tax_year', $month_of_tax_year, array('id', 'name'), 'first_month_of_tax_year', $acc_first_month_of_tax_year, array(), array(), '', '', false); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
          <?php
              $method = [
                          1 => ['id' => 'cash', 'name' => _l('cash')],
                          2 => ['id' => 'accrual', 'name' => _l('accrual')],
                         ];
               echo render_select('acc_accounting_method', $method, array('id', 'name'), _l('accounting_method') .' <i class="fa fa-question-circle" data-toggle="tooltip" data-title="'. _l('accounting_method_note').'"></i> ', $acc_accounting_method, array(), array(), '', '', false); ?>
              <p><i class="detail_type_note_1"></i></p>
              <p><i class="detail_type_note_2"></i></p>
              <p><i class="detail_type_note_3"></i></p>
          </div>
          <div class="hide">
            <i id="detail_type_note_cash_1"><?php echo _l('cash_method_note_1'); ?></i>
            <i id="detail_type_note_cash_2"><?php echo _l('cash_method_note_2'); ?></i>
            <i id="detail_type_note_cash_3"><?php echo _l('cash_method_note_3'); ?></i>

            <i id="detail_type_note_accrual_1"><?php echo _l('accrual_method_note_1'); ?></i>
            <i id="detail_type_note_accrual_2"><?php echo _l('accrual_method_note_2'); ?></i>
            <i id="detail_type_note_accrual_3"><?php echo _l('accrual_method_note_3'); ?></i>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mtop10 border-right">
            <span><?php echo _l('close_the_books'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('close_the_books_note'); ?>"></i></span>
          </div>
          <div class="col-md-6 mtop10">
              <div class="onoffswitch">
                  <input type="checkbox" id="acc_close_the_books" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_close_the_books == '1'){echo 'checked';} ?>  value="1" name="acc_close_the_books">
                  <label class="onoffswitch-label" for="acc_close_the_books"></label>
              </div>
          </div>
      </div>
      <div id="div_close_the_books" class="mleft25 <?php if($acc_close_the_books != '1'){echo 'hide';} ?>">
          <?php echo render_date_input('acc_closing_date', _l('closing_date'), _d($acc_closing_date)) ?>
          <?php
              $allow_changes_after_viewing = [
                          1 => ['id' => 'allow_changes_after_viewing_a_warning', 'name' => _l('allow_changes_after_viewing_a_warning')],
                         ];
               echo render_select('acc_allow_changes_after_viewing', $allow_changes_after_viewing, array('id', 'name'),'', $acc_allow_changes_after_viewing, array(), array(), '', '', false); ?>
          <div id="div_close_book_password" class="<?php if($acc_allow_changes_after_viewing == 'allow_changes_after_viewing_a_warning'){echo 'hide';} ?>">
            <div class="form-group register-password-group">
                <label class="control-label" for="acc_close_book_password"><?php echo _l('clients_register_password'); ?></label>
                <input type="password" class="form-control" name="acc_close_book_password" id="acc_close_book_password" autocomplete="off" value="<?php echo new_html_entity_decode($acc_close_book_password); ?>">
            </div>
            <div class="form-group register-password-repeat-group">
                <label class="control-label" for="acc_close_book_passwordr"><?php echo _l('clients_register_password_repeat'); ?></label>
                <input type="password" class="form-control" name="acc_close_book_passwordr" id="acc_close_book_passwordr" autocomplete="off" value="<?php echo new_html_entity_decode($acc_close_book_passwordr); ?>">
            </div>
          </div>
      </div>
    </div>
    <div class="col-md-6">
      <h5 class="title mbot5"><?php echo _l('chart_of_accounts') ?></h5>
      <div class="row">
          <div class="col-md-6 mtop10 border-right">
            <span><?php echo _l('enable_account_numbers'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('chart_of_accounts_note'); ?>"></i></span>
          </div>
          <div class="col-md-6 mtop10">
              <div class="onoffswitch">
                  <input type="checkbox" id="acc_enable_account_numbers" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_enable_account_numbers == '1'){echo 'checked';} ?>  value="1" name="acc_enable_account_numbers">
                  <label class="onoffswitch-label" for="acc_enable_account_numbers"></label>
              </div>
          </div>
      </div>
      <div id="div_enable_account_numbers" class="mleft25 <?php if($acc_enable_account_numbers != '1'){echo 'hide';} ?>">
        <div class="form-group">
          <div class="checkbox checkbox-primary">
            <input type="checkbox" name="acc_show_account_numbers" <?php if($acc_show_account_numbers == '1'){echo 'checked';} ?> id="wd_monday" value="1">
            <label for="wd_monday"><?php echo _l('show_account_numbers'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('show_account_numbers_note'); ?>"></i></label>
          </div>
        </div>
      </div>
      <h5 class="title mbot5"><?php echo _l('dashboard') ?></h5>
      <div class="row">
          <div class="col-md-6 mtop10 border-right">
            <span><?php echo _l('enable_all_time_filter'); ?></span>
          </div>
          <div class="col-md-6 mtop10">
              <div class="onoffswitch">
                  <input type="checkbox" id="acc_enable_all_time_filter" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_enable_all_time_filter == '1'){echo 'checked';} ?>  value="1" name="acc_enable_all_time_filter">
                  <label class="onoffswitch-label" for="acc_enable_all_time_filter"></label>
              </div>
          </div>
      </div>
      
      <h5 class="title mbot5"><?php echo _l('reports') ?></h5>
      <div class="row">
          <div class="col-md-6 mtop10 border-right">
            <span><?php echo _l('acc_hide_zero_value_rows'); ?></span>
          </div>
          <div class="col-md-6 mtop10">
              <div class="onoffswitch">
                  <input type="checkbox" id="acc_hide_zero_value_rows" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_hide_zero_value_rows == '1'){echo 'checked';} ?>  value="1" name="acc_hide_zero_value_rows">
                  <label class="onoffswitch-label" for="acc_hide_zero_value_rows"></label>
              </div>
          </div>
      </div>

      <h5 class="title mbot5"><?php echo _l('acc_mapping') ?></h5>
      <?php
      $month = [
                  1 => ['id' => 'payment_deposit', 'name' => _l('payment_deposit')],
                  2 => ['id' => 'credit_debit', 'name' => _l('credit_debit')],
                 ];
       echo render_select('acc_mapping_label_type', $month, array('id', 'name'), _l('label_type'), $acc_mapping_label_type, array(), array(), '', '', false); ?>

       <h5 class="title mbot5"><?php echo _l('class') ?></h5>
      <div class="row">
          <div class="col-md-6 mtop10 border-right">
            <span><?php echo _l('enable_class_tracking'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('enable_class_tracking_note'); ?>"></i></span>
          </div>
          <div class="col-md-6 mtop10">
              <div class="onoffswitch">
                  <input type="checkbox" id="acc_enable_class_tracking" data-perm-id="3" class="onoffswitch-checkbox" <?php if($acc_enable_class_tracking == '1'){echo 'checked';} ?>  value="1" name="acc_enable_class_tracking">
                  <label class="onoffswitch-label" for="acc_enable_class_tracking"></label>
              </div>
          </div>
      </div>
      <h5 class="title mbot5"><?php echo _l('acc_project_budget_control', 'Project Budget & Enforcement'); ?></h5>
      <div class="row">
          <div class="col-md-12">
              <div class="radio radio-primary">
                  <input type="radio" id="acc_budget_enforce_disable" name="acc_budget_enforcement" value="disable" <?php if(get_option('acc_budget_enforcement') == 'disable' || !get_option('acc_budget_enforcement')){echo 'checked';} ?>>
                  <label for="acc_budget_enforce_disable"><?php echo _l('acc_budget_enforce_disable', 'Disable request when budget exceeded (Hard Block)'); ?></label>
              </div>
              <div class="radio radio-primary">
                  <input type="radio" id="acc_budget_enforce_approval" name="acc_budget_enforcement" value="approval" <?php if(get_option('acc_budget_enforcement') == 'approval'){echo 'checked';} ?>>
                  <label for="acc_budget_enforce_approval"><?php echo _l('acc_budget_enforce_approval', 'Allow request but require advanced approval'); ?></label>
              </div>
              <div class="radio radio-primary">
                  <input type="radio" id="acc_budget_enforce_notify" name="acc_budget_enforcement" value="notify" <?php if(get_option('acc_budget_enforcement') == 'notify'){echo 'checked';} ?>>
                  <label for="acc_budget_enforce_notify"><?php echo _l('acc_budget_enforce_notify', 'Only notify (Warning message)'); ?></label>
              </div>
          </div>
      </div>

      <?php
      $CI = &get_instance();
      $CI->load->model('staff_model');
      $staff_members = $CI->staff_model->get('', ['active' => 1]);
      $acc_budget_approver_id = get_option('acc_budget_approver_id');
      ?>
      <div id="div_budget_approver" class="mtop10 <?php if(get_option('acc_budget_enforcement') != 'approval'){echo 'hide';} ?>">
          <?php echo render_select('acc_budget_approver_id', $staff_members, array('staffid', array('firstname', 'lastname')), _l('acc_budget_approver', 'Director / Budget Approver'), $acc_budget_approver_id); ?>
      </div>

      <div class="mtop15">
          <strong><?php echo _l('acc_enforce_applicable_to', 'Enforce Budget Constraints on:'); ?></strong>
          <div class="checkbox checkbox-primary mtop5">
              <input type="checkbox" name="acc_enforce_purchase_order" <?php if(get_option('acc_enforce_purchase_order') == '1'){echo 'checked';} ?> id="acc_enforce_purchase_order" value="1">
              <label for="acc_enforce_purchase_order"><?php echo _l('purchase_order', 'Purchase Order'); ?></label>
          </div>
          <div class="checkbox checkbox-primary">
              <input type="checkbox" name="acc_enforce_expense" <?php if(get_option('acc_enforce_expense') == '1'){echo 'checked';} ?> id="acc_enforce_expense" value="1">
              <label for="acc_enforce_expense"><?php echo _l('acc_expense', 'Expense'); ?></label>
          </div>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var radios = document.querySelectorAll('input[name="acc_budget_enforcement"]');
        var approverDiv = document.getElementById('div_budget_approver');
        radios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'approval') {
                    approverDiv.classList.remove('hide');
                } else {
                    approverDiv.classList.add('hide');
                }
            });
        });
    });
</script>

<hr>
<div class="col-md-12">
  <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
</div>
<?php echo form_close(); ?>
