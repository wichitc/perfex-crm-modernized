<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="#" onclick="new_insurance_category(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add'); ?> <?php echo _l('insurance_category'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<p class="text-muted"><?php echo _l('insurance_category_help'); ?></p>
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('name'); ?></th>
    <th><?php echo _l('company'); ?> (%)</th>
    <th><?php echo _l('worker'); ?> (%)</th>
    <th><?php echo _l('description'); ?></th>
    <th><?php echo _l('active'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
 <tbody>
    <?php foreach ((array) $insurance_category as $c) { ?>
    <tr>
       <td><?php echo (int) $c['id']; ?></td>
       <td><?php echo htmlspecialchars($c['name']); ?></td>
       <td><?php echo htmlspecialchars($c['company_percent']); ?></td>
       <td><?php echo htmlspecialchars($c['staff_percent']); ?></td>
       <td><?php echo htmlspecialchars($c['description'] ?? ''); ?></td>
       <td><?php echo !empty($c['active']) ? _l('yes') : _l('no'); ?></td>
       <td>
         <a href="#" onclick="edit_insurance_category(this,<?php echo (int) $c['id']; ?>); return false"
            data-name="<?php echo htmlspecialchars($c['name']); ?>"
            data-company_percent="<?php echo htmlspecialchars($c['company_percent']); ?>"
            data-staff_percent="<?php echo htmlspecialchars($c['staff_percent']); ?>"
            data-description="<?php echo htmlspecialchars($c['description'] ?? ''); ?>"
            data-active="<?php echo (int) ($c['active'] ?? 0); ?>"
            class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
         <a href="<?php echo admin_url('hrm/delete_insurance_category/' . (int) $c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>

<div class="modal fade" id="insurance_category_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/insurance_category')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('insurance_category'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo render_input('name', 'name'); ?>
                <div class="row">
                    <div class="col-md-6"><?php echo render_input('company_percent', 'company', '', 'number', ['step' => 'any', 'placeholder' => '%']); ?></div>
                    <div class="col-md-6"><?php echo render_input('staff_percent', 'worker', '', 'number', ['step' => 'any', 'placeholder' => '%']); ?></div>
                </div>
                <?php echo render_textarea('description', 'description'); ?>
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="active" id="insurance_category_active" value="1" checked>
                    <label for="insurance_category_active"><?php echo _l('active'); ?></label>
                </div>
                <input type="hidden" name="id" id="insurance_category_id">
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
function new_insurance_category() {
    var $m = $('#insurance_category_modal');
    $m.find('#insurance_category_id').val('');
    $m.find('input[name="name"]').val('');
    $m.find('input[name="company_percent"]').val('');
    $m.find('input[name="staff_percent"]').val('');
    $m.find('textarea[name="description"]').val('');
    $m.find('#insurance_category_active').prop('checked', true);
    $m.modal('show');
}
function edit_insurance_category(el, id) {
    var $m = $('#insurance_category_modal');
    $m.find('#insurance_category_id').val(id);
    $m.find('input[name="name"]').val($(el).data('name'));
    $m.find('input[name="company_percent"]').val($(el).data('company_percent'));
    $m.find('input[name="staff_percent"]').val($(el).data('staff_percent'));
    $m.find('textarea[name="description"]').val($(el).data('description') || '');
    $m.find('#insurance_category_active').prop('checked', parseInt($(el).data('active'), 10) === 1);
    $m.modal('show');
}
</script>
