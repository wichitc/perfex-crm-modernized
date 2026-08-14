<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="#" onclick="new_staff_deduction(); return false;" class="btn btn-info pull-left display-block">
                                <?php echo _l('add'); ?> <?php echo _l('staff_deduction'); ?>
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <p class="text-muted"><?php echo _l('staff_deduction_help'); ?></p>
                        <table class="table dt-table">
                            <thead>
                                <th><?php echo _l('id'); ?></th>
                                <th><?php echo _l('staff'); ?></th>
                                <th><?php echo _l('deduction_type'); ?></th>
                                <th><?php echo _l('title'); ?></th>
                                <th><?php echo _l('deduction_total'); ?></th>
                                <th><?php echo _l('deduction_collected'); ?></th>
                                <th><?php echo _l('deduction_balance'); ?></th>
                                <th><?php echo _l('deduction_collect_type'); ?></th>
                                <th><?php echo _l('status'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                $currency_symbol = isset($base_currency->symbol) ? $base_currency->symbol : '';
                                foreach ((array) $deductions as $d) {
                                    $balance = (float) $d['total_amount'] - (float) $d['collected_amount'];
                                ?>
                                <tr>
                                    <td><?php echo (int) $d['id']; ?></td>
                                    <td><?php echo htmlspecialchars($d['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($d['type_name'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($d['title'] ?? ''); ?></td>
                                    <td><?php echo app_format_money($d['total_amount'], $currency_symbol); ?></td>
                                    <td><?php echo app_format_money($d['collected_amount'], $currency_symbol); ?></td>
                                    <td><strong><?php echo app_format_money($balance, $currency_symbol); ?></strong></td>
                                    <td><?php echo $d['collect_type'] === 'installment' ? _l('deduction_installment') : _l('deduction_one_time'); ?></td>
                                    <td>
                                        <?php $labels = ['active' => 'label-info', 'completed' => 'label-success', 'cancelled' => 'label-default']; ?>
                                        <span class="label <?php echo $labels[$d['status']] ?? 'label-default'; ?>"><?php echo _l('deduction_status_' . $d['status']); ?></span>
                                    </td>
                                    <td>
                                        <?php if ($d['status'] === 'active' && $balance > 0) { ?>
                                        <a href="#" onclick='collect_deduction(<?php echo (int) $d['id']; ?>, <?php echo json_encode($d['staff_name']); ?>, <?php echo (float) $d['installment_amount']; ?>, <?php echo $balance; ?>); return false' class="btn btn-success btn-icon" title="<?php echo _l('deduction_collect_now'); ?>"><i class="fa fa-money"></i></a>
                                        <?php } ?>
                                        <a href="#" onclick='edit_staff_deduction(this); return false'
                                           data-id="<?php echo (int) $d['id']; ?>"
                                           data-staff_id="<?php echo (int) $d['staff_id']; ?>"
                                           data-deduction_type_id="<?php echo (int) ($d['deduction_type_id'] ?? 0); ?>"
                                           data-title="<?php echo htmlspecialchars($d['title'] ?? ''); ?>"
                                           data-total_amount="<?php echo htmlspecialchars($d['total_amount']); ?>"
                                           data-installment_amount="<?php echo htmlspecialchars($d['installment_amount']); ?>"
                                           data-collect_type="<?php echo htmlspecialchars($d['collect_type']); ?>"
                                           data-auto_collect="<?php echo (int) $d['auto_collect']; ?>"
                                           data-notes="<?php echo htmlspecialchars($d['notes'] ?? ''); ?>"
                                           class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
                                        <a href="<?php echo admin_url('hrm/delete_staff_deduction/' . (int) $d['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add / edit deduction modal -->
<div class="modal fade" id="staff_deduction_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/deductions')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('staff_deduction'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="staff_id" class="control-label"><?php echo _l('staff'); ?></label>
                    <select name="staff_id" id="deduction_staff_id" class="selectpicker" data-live-search="true" data-width="100%">
                        <option value=""></option>
                        <?php foreach ((array) $staff as $s) { ?>
                        <option value="<?php echo (int) $s['staffid']; ?>"><?php echo htmlspecialchars($s['firstname'] . ' ' . ($s['lastname'] ?? '')); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="deduction_type_id" class="control-label"><?php echo _l('deduction_type'); ?></label>
                    <select name="deduction_type_id" id="deduction_type_id_sel" class="selectpicker" data-width="100%">
                        <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                        <?php foreach ((array) $deduction_type as $t) { ?>
                        <option value="<?php echo (int) $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php echo render_input('title', 'title'); ?>
                <div class="row">
                    <div class="col-md-6"><?php echo render_input('total_amount', 'deduction_total', '', 'number', ['step' => 'any']); ?></div>
                    <div class="col-md-6"><?php echo render_input('installment_amount', 'deduction_installment_amount', '', 'number', ['step' => 'any']); ?></div>
                </div>
                <div class="form-group">
                    <label for="collect_type" class="control-label"><?php echo _l('deduction_collect_type'); ?></label>
                    <select name="collect_type" id="deduction_collect_type" class="form-control">
                        <option value="one_time"><?php echo _l('deduction_one_time'); ?></option>
                        <option value="installment"><?php echo _l('deduction_installment'); ?></option>
                    </select>
                </div>
                <?php echo render_date_input('start_month', 'deduction_start_month'); ?>
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="auto_collect" id="deduction_auto_collect" value="1" checked>
                    <label for="deduction_auto_collect"><?php echo _l('deduction_auto_collect'); ?></label>
                </div>
                <?php echo render_textarea('notes', 'notes'); ?>
                <input type="hidden" name="id" id="staff_deduction_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Collect modal -->
<div class="modal fade" id="collect_deduction_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/collect_deduction')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('deduction_collect_now'); ?> - <span id="collect_staff_name"></span></h4>
            </div>
            <div class="modal-body">
                <p class="text-muted"><?php echo _l('deduction_outstanding_balance'); ?>: <strong id="collect_balance_label"></strong></p>
                <?php echo render_input('amount', 'amount', '', 'number', ['step' => 'any']); ?>
                <?php echo render_input('period', 'deduction_period', '', 'text', ['placeholder' => 'e.g. 2026-07']); ?>
                <?php echo render_date_input('collected_date', 'date'); ?>
                <?php echo render_textarea('notes', 'notes'); ?>
                <input type="hidden" name="deduction_id" id="collect_deduction_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-success"><?php echo _l('deduction_collect_now'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
function new_staff_deduction() {
    var $m = $('#staff_deduction_modal');
    $m.find('#staff_deduction_id').val('');
    $m.find('#deduction_staff_id').val('').selectpicker('refresh');
    $m.find('#deduction_type_id_sel').val('').selectpicker('refresh');
    $m.find('input[name="title"]').val('');
    $m.find('input[name="total_amount"]').val('');
    $m.find('input[name="installment_amount"]').val('');
    $m.find('#deduction_collect_type').val('one_time');
    $m.find('input[name="start_month"]').val('');
    $m.find('#deduction_auto_collect').prop('checked', true);
    $m.find('textarea[name="notes"]').val('');
    $m.modal('show');
}
function edit_staff_deduction(el) {
    var $m = $('#staff_deduction_modal');
    var $e = $(el);
    $m.find('#staff_deduction_id').val($e.data('id'));
    $m.find('#deduction_staff_id').val($e.data('staff_id')).selectpicker('refresh');
    $m.find('#deduction_type_id_sel').val($e.data('deduction_type_id') || '').selectpicker('refresh');
    $m.find('input[name="title"]').val($e.data('title'));
    $m.find('input[name="total_amount"]').val($e.data('total_amount'));
    $m.find('input[name="installment_amount"]').val($e.data('installment_amount'));
    $m.find('#deduction_collect_type').val($e.data('collect_type') || 'one_time');
    $m.find('#deduction_auto_collect').prop('checked', parseInt($e.data('auto_collect'), 10) === 1);
    $m.find('textarea[name="notes"]').val($e.data('notes') || '');
    $m.modal('show');
}
function collect_deduction(id, staffName, installment, balance) {
    var $m = $('#collect_deduction_modal');
    $m.find('#collect_deduction_id').val(id);
    $m.find('#collect_staff_name').text(staffName);
    $m.find('#collect_balance_label').text(balance);
    var suggested = installment > 0 ? Math.min(installment, balance) : balance;
    $m.find('input[name="amount"]').val(suggested);
    $m.find('input[name="period"]').val('');
    $m.find('textarea[name="notes"]').val('');
    $m.modal('show');
}
</script>
</body>
</html>
