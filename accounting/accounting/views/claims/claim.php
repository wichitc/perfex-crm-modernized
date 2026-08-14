<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'claim-form']); ?>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="project_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_project'); ?></label>
                                <select name="project_id" id="project_id" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('acc_select_project'); ?>" required>
                                    <option value=""></option>
                                    <?php foreach ($projects as $project) { ?>
                                    <option value="<?php echo html_escape($project['id']); ?>" <?php echo (isset($claim) && $claim->project_id == $project['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($project['name']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="category_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_budget_category'); ?></label>
                                <select name="category_id" id="category_id" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('acc_select_category'); ?>" required>
                                    <option value=""></option>
                                    <?php foreach ($categories as $cat) { ?>
                                    <option value="<?php echo html_escape($cat['id']); ?>" <?php echo (isset($claim) && $claim->category_id == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($cat['name']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="staff_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_staff_member_claimant'); ?></label>
                                <select name="staff_id" id="staff_id" class="selectpicker" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('acc_select_staff'); ?>" required>
                                    <option value=""></option>
                                    <?php foreach ($staff as $member) { ?>
                                    <option value="<?php echo html_escape($member['staffid']); ?>" <?php echo (isset($claim) && $claim->staff_id == $member['staffid']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($member['firstname'] . ' ' . $member['lastname']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <?php 
                                    $expense_date = isset($claim) ? _d($claim->expense_date) : _d(date('Y-m-d'));
                                    echo render_date_input('expense_date', '<span class="text-danger">* </span>' . _l('acc_expense_date'), $expense_date, array('required' => true)); 
                                ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="amount" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_claim_amount'); ?></label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="<?php echo isset($claim) ? html_escape($claim->amount) : ''; ?>" required>
                            </div>
                        </div>

                        <hr class="hr-panel-heading" />
                        <h5 class="bold"><?php echo _l('acc_double_entry_clearance_details'); ?></h5>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="debit_account_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_debit_expense_account'); ?></label>
                                <select name="debit_account_id" id="debit_account_id" class="selectpicker" data-width="100%" data-live-search="true" required>
                                    <option value=""></option>
                                    <?php foreach ($accounts as $acc) { 
                                        $account_name = $acc['name'];
                                        if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                            $account_name = $acc['number'] . ' - ' . $account_name;
                                        }
                                    ?>
                                    <option value="<?php echo $acc['id']; ?>" <?php echo (isset($claim) ? ($claim->debit_account_id == $acc['id']) : (strpos(strtolower($acc['name']), 'chi phí') !== false || strpos(strtolower($acc['name']), 'expense') !== false || strpos(strtolower($acc['name']), '642') !== false || strpos(strtolower($acc['name']), '627') !== false)) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($account_name); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="credit_account_id" class="control-label"><span class="text-danger">* </span><?php echo _l('acc_credit_account_payable_staff'); ?></label>
                                <select name="credit_account_id" id="credit_account_id" class="selectpicker" data-width="100%" data-live-search="true" required>
                                    <option value=""></option>
                                    <?php foreach ($accounts as $acc) { 
                                        $account_name = $acc['name'];
                                        if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                            $account_name = $acc['number'] . ' - ' . $account_name;
                                        }
                                    ?>
                                    <option value="<?php echo $acc['id']; ?>" <?php echo (isset($claim) ? ($claim->credit_account_id == $acc['id']) : (strpos(strtolower($acc['name']), 'phải trả') !== false || strpos(strtolower($acc['name']), 'payable') !== false || strpos(strtolower($acc['name']), '338') !== false || strpos(strtolower($acc['name']), '334') !== false)) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($account_name); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="control-label"><?php echo _l('acc_description_purpose'); ?></label>
                            <textarea name="description" id="description" class="form-control" rows="4"><?php echo isset($claim) ? html_escape($claim->description) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="file" class="control-label"><?php echo _l('acc_upload_attachments_multiple'); ?></label>
                            <input type="file" name="file[]" id="file" class="form-control" multiple>
                        </div>

                        <div id="acc_budget_alert_container"></div>

                        <div class="btn-bottom-toolbar text-right">
                            <a href="<?php echo admin_url('accounting/claims'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
                            <button type="submit" class="btn btn-primary" id="submit-btn"><?php echo isset($claim) ? _l('acc_save_claim') : _l('acc_submit_claim'); ?></button>
                        </div>
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
