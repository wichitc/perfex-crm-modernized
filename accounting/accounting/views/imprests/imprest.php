<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$selected_payment_method = !empty($imprest) ? $imprest->payment_method : '';
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
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'imprest-form']); ?>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="project_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_project'); ?></label>
                                <select name="project_id" id="project_id" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('acc_select_project'); ?>" required>
                                    <option value=""></option>
                                    <?php foreach ($projects as $project) { ?>
                                    <option value="<?php echo html_escape($project['id']); ?>" <?php echo (!empty($imprest) && $imprest->project_id == $project['id']) ? 'selected' : ''; ?>><?php echo html_escape($project['name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="category_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_budget_category'); ?></label>
                                <select name="category_id" id="category_id" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('acc_select_category'); ?>" required>
                                    <option value=""></option>
                                    <?php foreach ($categories as $cat) { ?>
                                    <option value="<?php echo html_escape($cat['id']); ?>" <?php echo (!empty($imprest) && $imprest->category_id == $cat['id']) ? 'selected' : ''; ?>><?php echo html_escape($cat['name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="staff_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_staff_member'); ?></label>
                                <select name="staff_id" id="staff_id" class="selectpicker" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('acc_select_staff'); ?>" required>
                                    <option value=""></option>
                                    <?php foreach ($staff as $member) { ?>
                                    <option value="<?php echo html_escape($member['staffid']); ?>" <?php echo (!empty($imprest) && $imprest->staff_id == $member['staffid']) ? 'selected' : ''; ?>><?php echo html_escape($member['firstname'] . ' ' . $member['lastname']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <?php 
                                    $request_date = !empty($imprest) ? _d($imprest->request_date) : _d(date('Y-m-d'));
                                    echo render_date_input('request_date', '<span class="text-danger">* </span>' . _l('acc_request_date'), $request_date, array('required' => true)); 
                                ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="amount_requested" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_amount_requested'); ?></label>
                                <input type="number" step="0.01" name="amount_requested" id="amount_requested" class="form-control" value="<?php echo !empty($imprest) ? html_escape($imprest->amount_requested) : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="payment_method" class="control-label"><?php echo _l('acc_payment_method'); ?></label>
                                <select name="payment_method" id="payment_method" class="selectpicker" data-width="100%">
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
                        </div>

                        <hr class="hr-panel-heading" />
                        <h5 class="bold"><?php echo _l('acc_double_entry_accounting_details'); ?></h5>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="debit_account_id" class="control-label"><span class="text-danger">* </span><?php echo _l('debit_account_staff_receivable'); ?></label>
                                <select name="debit_account_id" id="debit_account_id" class="selectpicker" data-width="100%" data-live-search="true" required>
                                    <option value=""></option>
                                    <?php foreach ($accounts as $acc) { 
                                        $account_name = $acc['name'];
                                        if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                            $account_name = $acc['number'] . ' - ' . $account_name;
                                        }
                                        $selected = '';
                                        if (!empty($imprest)) {
                                            if ($imprest->debit_account_id == $acc['id']) $selected = 'selected';
                                        } else {
                                            if (strpos(strtolower($acc['name']), 'tạm ứng') !== false || strpos(strtolower($acc['name']), 'receivable') !== false) {
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
                            
                            <div class="col-md-6 form-group">
                                <label for="credit_account_id" class="control-label"><span class="text-danger">* </span><?php echo _l('credit_account_cash_bank'); ?></label>
                                <select name="credit_account_id" id="credit_account_id" class="selectpicker" data-width="100%" data-live-search="true" required>
                                    <option value=""></option>
                                    <?php foreach ($accounts as $acc) { 
                                        $account_name = $acc['name'];
                                        if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                            $account_name = $acc['number'] . ' - ' . $account_name;
                                        }
                                        $selected = '';
                                        if (!empty($imprest)) {
                                            if ($imprest->credit_account_id == $acc['id']) $selected = 'selected';
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

                        <div class="form-group">
                            <label for="description" class="control-label"><?php echo _l('acc_purpose_description'); ?></label>
                            <textarea name="description" id="description" class="form-control" rows="4"><?php echo !empty($imprest) ? html_escape($imprest->description) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="file" class="control-label"><?php echo _l('acc_upload_attachments_multiple'); ?></label>
                            <input type="file" name="file[]" id="file" class="form-control" multiple>
                        </div>

                        <div id="acc_budget_alert_container"></div>

                        <div class="btn-bottom-toolbar text-right">
                            <a href="<?php echo admin_url('accounting/imprests'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
                            <button type="submit" class="btn btn-primary" id="submit-btn"><?php echo !empty($imprest) ? _l('acc_save_request') : _l('acc_submit_request'); ?></button>
                        </div>
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
