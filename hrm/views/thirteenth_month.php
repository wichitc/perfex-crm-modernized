<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="_buttons">
                                    <a href="#" onclick="new_thirteenth_month(); return false;" class="btn btn-info mright5"><?php echo _l('add'); ?> <?php echo _l('thirteenth_month'); ?></a>
                                    <a href="#" onclick="$('#generate_modal').modal('show'); return false;" class="btn btn-success"><?php echo _l('thirteenth_month_generate_all'); ?></a>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <form method="get" action="<?php echo admin_url('hrm/thirteenth_month'); ?>" class="form-inline pull-right">
                                    <label class="control-label mright5"><?php echo _l('year'); ?></label>
                                    <input type="number" name="year" value="<?php echo (int) $year; ?>" class="form-control" style="width:110px;" onchange="this.form.submit()">
                                </form>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <p class="text-muted"><?php echo _l('thirteenth_month_help'); ?></p>
                        <?php
                        $currency_symbol = isset($base_currency->symbol) ? $base_currency->symbol : '';
                        $total = 0;
                        foreach ((array) $records as $r) { $total += (float) $r['computed_amount']; }
                        ?>
                        <table class="table dt-table">
                            <thead>
                                <th><?php echo _l('id'); ?></th>
                                <th><?php echo _l('staff'); ?></th>
                                <th><?php echo _l('year'); ?></th>
                                <th><?php echo _l('thirteenth_base_amount'); ?></th>
                                <th><?php echo _l('thirteenth_months_worked'); ?></th>
                                <th><?php echo _l('thirteenth_computed_amount'); ?></th>
                                <th><?php echo _l('status'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ((array) $records as $r) { ?>
                                <tr>
                                    <td><?php echo (int) $r['id']; ?></td>
                                    <td><?php echo htmlspecialchars($r['staff_name']); ?></td>
                                    <td><?php echo (int) $r['year']; ?></td>
                                    <td><?php echo app_format_money($r['base_amount'], $currency_symbol); ?></td>
                                    <td><?php echo htmlspecialchars($r['months_worked']); ?></td>
                                    <td><strong><?php echo app_format_money($r['computed_amount'], $currency_symbol); ?></strong></td>
                                    <td>
                                        <?php $labels = ['draft' => 'label-default', 'approved' => 'label-info', 'paid' => 'label-success']; ?>
                                        <span class="label <?php echo $labels[$r['status']] ?? 'label-default'; ?>"><?php echo _l('thirteenth_status_' . $r['status']); ?></span>
                                    </td>
                                    <td>
                                        <a href="#" onclick='edit_thirteenth_month(this); return false'
                                           data-id="<?php echo (int) $r['id']; ?>"
                                           data-staff_id="<?php echo (int) $r['staff_id']; ?>"
                                           data-year="<?php echo (int) $r['year']; ?>"
                                           data-base_amount="<?php echo htmlspecialchars($r['base_amount']); ?>"
                                           data-months_worked="<?php echo htmlspecialchars($r['months_worked']); ?>"
                                           data-status="<?php echo htmlspecialchars($r['status']); ?>"
                                           data-notes="<?php echo htmlspecialchars($r['notes'] ?? ''); ?>"
                                           class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
                                        <a href="<?php echo admin_url('hrm/delete_thirteenth_month/' . (int) $r['id'] . '?year=' . (int) $year); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <?php if (!empty($records)) { ?>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right"><?php echo _l('total'); ?></th>
                                    <th><?php echo app_format_money($total, $currency_symbol); ?></th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add / edit modal -->
<div class="modal fade" id="thirteenth_month_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/thirteenth_month')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('thirteenth_month_salary'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="staff_id" class="control-label"><?php echo _l('staff'); ?></label>
                    <select name="staff_id" id="thirteenth_staff_id" class="selectpicker" data-live-search="true" data-width="100%">
                        <option value=""></option>
                        <?php foreach ((array) $staff as $s) { ?>
                        <option value="<?php echo (int) $s['staffid']; ?>"><?php echo htmlspecialchars($s['firstname'] . ' ' . ($s['lastname'] ?? '')); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php echo render_input('year', 'year', $year, 'number'); ?>
                <div class="row">
                    <div class="col-md-6"><?php echo render_input('base_amount', 'thirteenth_base_amount', '', 'number', ['step' => 'any']); ?></div>
                    <div class="col-md-6"><?php echo render_input('months_worked', 'thirteenth_months_worked', '12', 'number', ['step' => 'any', 'max' => '12']); ?></div>
                </div>
                <p class="text-muted"><?php echo _l('thirteenth_formula_note'); ?></p>
                <div class="form-group">
                    <label for="status" class="control-label"><?php echo _l('status'); ?></label>
                    <select name="status" id="thirteenth_status" class="form-control">
                        <option value="draft"><?php echo _l('thirteenth_status_draft'); ?></option>
                        <option value="approved"><?php echo _l('thirteenth_status_approved'); ?></option>
                        <option value="paid"><?php echo _l('thirteenth_status_paid'); ?></option>
                    </select>
                </div>
                <?php echo render_textarea('notes', 'notes'); ?>
                <input type="hidden" name="id" id="thirteenth_month_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Generate all modal -->
<div class="modal fade" id="generate_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/generate_thirteenth_month')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('thirteenth_month_generate_all'); ?></h4>
            </div>
            <div class="modal-body">
                <p class="text-muted"><?php echo _l('thirteenth_month_generate_help'); ?></p>
                <?php echo render_input('year', 'year', $year, 'number'); ?>
                <?php echo render_input('default_base', 'thirteenth_default_base', '0', 'number', ['step' => 'any']); ?>
                <?php echo render_input('default_months', 'thirteenth_months_worked', '12', 'number', ['step' => 'any', 'max' => '12']); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-success"><?php echo _l('thirteenth_month_generate_all'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
function new_thirteenth_month() {
    var $m = $('#thirteenth_month_modal');
    $m.find('#thirteenth_month_id').val('');
    $m.find('#thirteenth_staff_id').val('').selectpicker('refresh');
    $m.find('input[name="year"]').val('<?php echo (int) $year; ?>');
    $m.find('input[name="base_amount"]').val('');
    $m.find('input[name="months_worked"]').val('12');
    $m.find('#thirteenth_status').val('draft');
    $m.find('textarea[name="notes"]').val('');
    $m.modal('show');
}
function edit_thirteenth_month(el) {
    var $m = $('#thirteenth_month_modal');
    var $e = $(el);
    $m.find('#thirteenth_month_id').val($e.data('id'));
    $m.find('#thirteenth_staff_id').val($e.data('staff_id')).selectpicker('refresh');
    $m.find('input[name="year"]').val($e.data('year'));
    $m.find('input[name="base_amount"]').val($e.data('base_amount'));
    $m.find('input[name="months_worked"]').val($e.data('months_worked'));
    $m.find('#thirteenth_status').val($e.data('status') || 'draft');
    $m.find('textarea[name="notes"]').val($e.data('notes') || '');
    $m.modal('show');
}
</script>
</body>
</html>
