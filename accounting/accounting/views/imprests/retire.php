<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$selected_payment_method = !empty($imprest) ? $imprest->retire_payment_method : '';
$currency_name = isset($currency) && $currency ? $currency->name : '';
$variance_amount = floatval($imprest->amount_requested) - floatval($imprest->amount_retired);
$payment_method_options = [];

foreach (($payment_modes ?? []) as $mode) {
    if (!isset($mode['id'], $mode['name'])) {
        continue;
    }

    $payment_method_options[] = [
        'id' => (string) $mode['id'],
        'name' => $mode['name'],
        'selected_by_default' => isset($mode['selected_by_default']) ? $mode['selected_by_default'] : 0,
    ];
}

foreach ([
    ['id' => 'Cash', 'name' => 'Cash'],
    ['id' => 'Bank Transfer', 'name' => 'Bank Transfer'],
    ['id' => 'Cheque', 'name' => 'Cheque'],
    ['id' => 'Other', 'name' => 'Other'],
] as $legacy_mode) {
    $exists = false;
    foreach ($payment_method_options as $mode) {
        if ($mode['id'] === $legacy_mode['id'] || strcasecmp($mode['name'], $legacy_mode['name']) === 0) {
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $payment_method_options[] = $legacy_mode;
    }
}

if ($selected_payment_method !== '') {
    $selected_exists = false;
    foreach ($payment_method_options as $mode) {
        if ($mode['id'] === (string) $selected_payment_method) {
            $selected_exists = true;
            break;
        }
    }

    if (!$selected_exists) {
        array_unshift($payment_method_options, [
            'id' => (string) $selected_payment_method,
            'name' => $selected_payment_method,
        ]);
    }
}
?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo ($imprest->status != 'disbursed') ? 'Edit Imprest Retirement' : html_escape($title); ?> (Ref: <?php echo html_escape($imprest->reference_no); ?>)</h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo form_open_multipart($this->uri->uri_string(), [
                            'id' => 'retire-form',
                            'data-requested' => html_escape($imprest->amount_requested),
                            'data-project-id' => html_escape($imprest->project_id),
                            'data-category-id' => html_escape($imprest->category_id),
                            'data-imprest-id' => html_escape($imprest->id),
                            'data-request-date' => html_escape($imprest->request_date)
                        ]); ?>
                        
                        <div class="row mbot15">
                            <div class="col-md-12">
                                <div class="well">
                                    <p><strong>Project:</strong> <?php echo html_escape($imprest->project_name); ?></p>
                                    <p><strong>Budget Category:</strong> <?php echo html_escape($imprest->category_name); ?></p>
                                    <p><strong>Staff Member:</strong> <?php echo html_escape($imprest->staff_name); ?></p>
                                    <p><strong><?php echo _l('amount_disbursed_imprest'); ?>:</strong> <span class="text-primary bold"><?php echo app_format_money($imprest->amount_requested, $currency_name); ?></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="amount_retired" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_actual_spent_amount_retired'); ?></label>
                                <input type="number" step="0.01" name="amount_retired" id="amount_retired" class="form-control" value="<?php echo !empty($imprest->amount_retired) ? html_escape($imprest->amount_retired) : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="variance_display" class="control-label"><?php echo _l('acc_variance_desc'); ?></label>
                                <input type="text" id="variance_display" class="form-control" value="<?php echo html_escape(number_format($variance_amount, 2, '.', '')); ?>" readonly>
                                <p class="help-block" id="variance_desc"></p>
                            </div>
                        </div>

                        <hr class="hr-panel-heading" />
                        <h5 class="bold"><?php echo _l('acc_double_entry_clearance_details'); ?></h5>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="expense_account_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_debit_expense_account'); ?></label>
                                <select name="expense_account_id" id="expense_account_id" class="selectpicker" data-width="100%" data-live-search="true" required>
                                    <option value=""></option>
                                    <?php foreach ($accounts as $acc) { 
                                        $account_name = $acc['name'];
                                        if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                            $account_name = $acc['number'] . ' - ' . $account_name;
                                        }
                                        $selected = '';
                                        if ($imprest->status != 'disbursed' && !empty($imprest->expense_account_id)) {
                                            if ($imprest->expense_account_id == $acc['id']) $selected = 'selected';
                                        } else {
                                            if (strpos(strtolower($acc['name']), 'chi phí') !== false || strpos(strtolower($acc['name']), 'expense') !== false || strpos(strtolower($acc['name']), '642') !== false || strpos(strtolower($acc['name']), '627') !== false) {
                                                $selected = 'selected';
                                            }
                                        }
                                    ?>
                                    <option value="<?php echo $acc['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo html_escape($account_name); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 form-group" id="refund_account_container" style="display:none;">
                                <label for="cash_bank_account_id" class="control-label" id="cash_bank_label"><span class="text-danger">* </span><?php echo _l('credit_account_cash_bank'); ?></label>
                                <select name="cash_bank_account_id" id="cash_bank_account_id" class="selectpicker" data-width="100%" data-live-search="true">
                                    <option value=""></option>
                                    <?php foreach ($accounts as $acc) { 
                                        $account_name = $acc['name'];
                                        if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                            $account_name = $acc['number'] . ' - ' . $account_name;
                                        }
                                        $selected = '';
                                        if ($imprest->status != 'disbursed' && !empty($imprest->cash_bank_account_id)) {
                                            if ($imprest->cash_bank_account_id == $acc['id']) $selected = 'selected';
                                        } else {
                                            if (strpos(strtolower($acc['name']), 'tiền mặt') !== false || strpos(strtolower($acc['name']), 'cash') !== false || strpos(strtolower($acc['name']), '111') !== false) {
                                                $selected = 'selected';
                                            }
                                        }
                                    ?>
                                    <option value="<?php echo $acc['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo html_escape($account_name); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="payment_method" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_payment_method'); ?></label>
                                <select name="payment_method" id="payment_method" class="selectpicker" data-width="100%" required>
                                    <?php foreach ($payment_method_options as $mode) {
                                        $selected = '';
                                        if ($selected_payment_method !== '') {
                                            $selected = ((string) $selected_payment_method === (string) $mode['id']) ? 'selected' : '';
                                        } elseif (!empty($mode['selected_by_default'])) {
                                            $selected = 'selected';
                                        }
                                    ?>
                                    <option value="<?php echo html_escape($mode['id']); ?>" <?php echo $selected; ?>>
                                        <?php echo html_escape($mode['name']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="transaction_id" class="control-label"><?php echo _l('acc_transaction_id_reference_optional'); ?></label>
                                <input type="text" name="transaction_id" id="transaction_id" class="form-control" value="<?php echo !empty($imprest->retire_transaction_id) ? html_escape($imprest->retire_transaction_id) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file" class="control-label"><?php echo _l('acc_upload_receipts_multiple'); ?></label>
                            <input type="file" name="file[]" id="file" class="form-control" multiple>
                        </div>

                        <div class="form-group">
                            <label for="description" class="control-label"><?php echo _l('acc_retirement_notes_description'); ?></label>
                            <textarea name="description" id="description" class="form-control" rows="4"><?php echo !empty($imprest->retire_notes) ? html_escape($imprest->retire_notes) : ''; ?></textarea>
                        </div>

                        <div id="acc_budget_alert_container"></div>

                        <div class="btn-bottom-toolbar text-right">
                            <a href="<?php echo admin_url('accounting/imprests'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
                            <button type="submit" class="btn btn-success" id="submit-btn"><?php echo ($imprest->status != 'disbursed') ? _l('acc_save_retirement') : _l('acc_retire_cash'); ?></button>
                        </div>
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
