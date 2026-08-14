<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons mbot15">
                            <?php if (has_permission('acc_claims', '', 'create') || is_admin()) { ?>
                            <a href="<?php echo admin_url('accounting/add_claim'); ?>" class="btn btn-primary pull-left display-block">
                                <i class="fa fa-plus-circle"></i> <?php echo _l('acc_log_new_claim'); ?>
                            </a>
                            <?php } ?>
                            <div class="clearfix"></div>
                        </div>

                        <!-- Filters -->
                        <div class="row mbot15">
                            <div class="col-md-3">
                                <?php echo render_select('filter_project_id', $projects, array('id', 'name'), 'project'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_select('filter_category_id', $categories, array('id', 'name'), 'budget_category'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_select('filter_staff_id', $staff, array('staffid', array('firstname', 'lastname')), 'staff', '', ['data-live-search' => 'true']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php 
                                $statuses = [
                                    ['id' => 'draft', 'name' => _l('acc_draft')],
                                    ['id' => 'pending_approval', 'name' => _l('acc_pending_approval')],
                                    ['id' => 'approved', 'name' => _l('acc_approved')],
                                    ['id' => 'rejected', 'name' => _l('acc_rejected')],
                                    ['id' => 'paid', 'name' => _l('acc_paid')],
                                ];
                                echo render_select('filter_status', $statuses, array('id', 'name'), 'status'); 
                                ?>
                            </div>
                        </div>
                        <hr />
                        
                        <div class="table-responsive">
                            <?php
                            $table_data = [
                                _l('date'),
                                _l('project'),
                                _l('acc_budget_category'),
                                _l('staff'),
                                _l('acc_amount_claimed'),
                                _l('acc_refunded'),
                                _l('status'),
                                _l('description'),
                                _l('options'),
                            ];
                            render_datatable($table_data, 'claims');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refund-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('acc_pay_refund_reimburse_staff'); ?></h4>
            </div>
            <?php echo form_open(admin_url('accounting/add_claim_refund'), ['id' => 'refund-form']); ?>
            <input type="hidden" name="claim_id" id="claim_id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="refund_amount" class="control-label"><?php echo _l('acc_refund_amount'); ?></label>
                    <input type="number" step="0.01" name="amount" id="refund_amount" class="form-control" required>
                    <p class="help-block" id="max_refund_info"></p>
                </div>
                
                <?php echo render_date_input('payment_date', 'acc_payment_date', _d(date('Y-m-d')), array('required' => true)); ?>
                
                <div class="form-group">
                    <label for="payment_method" class="control-label"><?php echo _l('acc_payment_method'); ?></label>
                    <select name="payment_method" id="payment_method" class="selectpicker" data-width="100%">
                        <option value="Cash"><?php echo _l('acc_cash'); ?></option>
                        <option value="Bank Transfer"><?php echo _l('acc_bank_transfer'); ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="credit_account_id" class="control-label"><?php echo _l('acc_payment_source_credit_cash_bank'); ?></label>
                    <select name="credit_account_id" id="credit_account_id" class="selectpicker" data-width="100%" data-live-search="true" required>
                        <option value=""></option>
                        <?php 
                        $accounts = $this->accounting_model->get_accounts();
                        foreach ($accounts as $acc) { 
                            $account_name = $acc['name'];
                            if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                $account_name = $acc['number'] . ' - ' . $account_name;
                            }
                        ?>
                        <option value="<?php echo $acc['id']; ?>" <?php echo (strpos(strtolower($acc['name']), 'tiền mặt') !== false || strpos(strtolower($acc['name']), 'cash') !== false || strpos(strtolower($acc['name']), '111') !== false) ? 'selected' : ''; ?>>
                            <?php echo html_escape($account_name); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes" class="control-label"><?php echo _l('acc_payment_notes_description'); ?></label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('acc_process_payment'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function(){
        var ClaimsServerParams = {
            'project_id': '[name="filter_project_id"]',
            'category_id': '[name="filter_category_id"]',
            'staff_id': '[name="filter_staff_id"]',
            'status': '[name="filter_status"]',
        };
        initDataTable('.table-claims', admin_url + 'accounting/claims_table', [8], [8], ClaimsServerParams, [0, 'desc']);

        $('select[name="filter_project_id"], select[name="filter_category_id"], select[name="filter_staff_id"], select[name="filter_status"]').on('change', function() {
            $('.table-claims').DataTable().ajax.reload();
        });
        
        $('body').on('click', '.btn-refund', function() {
            var claimId = $(this).data('id');
            var remaining = parseFloat($(this).data('remaining')) || 0;
            
            $('#claim_id').val(claimId);
            $('#refund_amount').val(remaining.toFixed(2));
            $('#refund_amount').attr('max', remaining);
            $('#max_refund_info').text('<?php echo _l('acc_maximum_allowed_refund'); ?>: ' + remaining.toFixed(2));
            
            $('#refund-modal').modal('show');
        });
    });
</script>
</body>
</html>
