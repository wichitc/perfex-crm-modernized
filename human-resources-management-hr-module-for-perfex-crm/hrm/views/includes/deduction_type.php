<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="#" onclick="new_deduction_type(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add'); ?> <?php echo _l('deduction_type'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<p class="text-muted"><?php echo _l('deduction_type_help'); ?></p>
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('name'); ?></th>
    <th><?php echo _l('deduction_calc_type'); ?></th>
    <th><?php echo _l('amount'); ?></th>
    <th><?php echo _l('taxable'); ?></th>
    <th><?php echo _l('deduction_recurring'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
 <tbody>
    <?php foreach ((array) $deduction_type as $c) { ?>
    <tr>
       <td><?php echo (int) $c['id']; ?></td>
       <td><?php echo htmlspecialchars($c['name']); ?></td>
       <td><?php echo $c['calc_type'] === 'percent' ? _l('deduction_percent') : _l('deduction_fixed'); ?></td>
       <td><?php echo $c['calc_type'] === 'percent' ? htmlspecialchars($c['amount']) . ' %' : app_format_money($c['amount'], $base_currency->symbol); ?></td>
       <td><?php echo !empty($c['taxable']) ? _l('yes') : _l('no'); ?></td>
       <td><?php echo !empty($c['is_recurring']) ? _l('yes') : _l('no'); ?></td>
       <td>
         <a href="#" onclick="edit_deduction_type(this,<?php echo (int) $c['id']; ?>); return false"
            data-name="<?php echo htmlspecialchars($c['name']); ?>"
            data-calc_type="<?php echo htmlspecialchars($c['calc_type']); ?>"
            data-amount="<?php echo htmlspecialchars($c['amount']); ?>"
            data-taxable="<?php echo (int) $c['taxable']; ?>"
            data-is_recurring="<?php echo (int) $c['is_recurring']; ?>"
            data-description="<?php echo htmlspecialchars($c['description'] ?? ''); ?>"
            class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
         <a href="<?php echo admin_url('hrm/delete_deduction_type/' . (int) $c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>

<div class="modal fade" id="deduction_type_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/deduction_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('deduction_type'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo render_input('name', 'name'); ?>
                <div class="form-group">
                    <label for="calc_type" class="control-label"><?php echo _l('deduction_calc_type'); ?></label>
                    <select name="calc_type" id="deduction_calc_type" class="form-control">
                        <option value="fixed"><?php echo _l('deduction_fixed'); ?></option>
                        <option value="percent"><?php echo _l('deduction_percent'); ?></option>
                    </select>
                </div>
                <?php echo render_input('amount', 'amount', '', 'number', ['step' => 'any']); ?>
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="taxable" id="deduction_taxable" value="1">
                    <label for="deduction_taxable"><?php echo _l('taxable'); ?></label>
                </div>
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="is_recurring" id="deduction_is_recurring" value="1">
                    <label for="deduction_is_recurring"><?php echo _l('deduction_recurring'); ?></label>
                </div>
                <?php echo render_textarea('description', 'description'); ?>
                <input type="hidden" name="id" id="deduction_type_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
</div>
<script>
function new_deduction_type() {
    var $m = $('#deduction_type_modal');
    $m.find('#deduction_type_id').val('');
    $m.find('input[name="name"]').val('');
    $m.find('#deduction_calc_type').val('fixed');
    $m.find('input[name="amount"]').val('');
    $m.find('#deduction_taxable').prop('checked', false);
    $m.find('#deduction_is_recurring').prop('checked', false);
    $m.find('textarea[name="description"]').val('');
    $m.modal('show');
}
function edit_deduction_type(el, id) {
    var $m = $('#deduction_type_modal');
    $m.find('#deduction_type_id').val(id);
    $m.find('input[name="name"]').val($(el).data('name'));
    $m.find('#deduction_calc_type').val($(el).data('calc_type') || 'fixed');
    $m.find('input[name="amount"]').val($(el).data('amount'));
    $m.find('#deduction_taxable').prop('checked', parseInt($(el).data('taxable'), 10) === 1);
    $m.find('#deduction_is_recurring').prop('checked', parseInt($(el).data('is_recurring'), 10) === 1);
    $m.find('textarea[name="description"]').val($(el).data('description') || '');
    $m.modal('show');
}
</script>
