<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="#" onclick="new_department(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add'); ?> <?php echo _l('department'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('name'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
 <tbody>
    <?php foreach ((array) $departments as $d) {
        $d = (array) $d;
        ?>
    <tr>
       <td><?php echo htmlspecialchars($d['departmentid'] ?? $d['id'] ?? ''); ?></td>
       <td><?php echo htmlspecialchars($d['name'] ?? ''); ?></td>
       <td>
         <a href="#" onclick="edit_department(this,<?php echo (int)($d['departmentid'] ?? $d['id'] ?? 0); ?>); return false" data-name="<?php echo htmlspecialchars($d['name'] ?? ''); ?>" data-parent="<?php echo (int)($d['parent_id'] ?? 0); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
          <a href="<?php echo admin_url('hrm/delete_department/'.(int)($d['departmentid'] ?? $d['id'] ?? 0)); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>
<div class="modal fade" id="department_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/department')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('department'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name"><?php echo _l('name'); ?></label>
                    <input type="text" name="name" id="department_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="parent_id"><?php echo _l('parent'); ?></label>
                    <select name="parent_id" id="department_parent" class="form-control selectpicker">
                        <option value="0"><?php echo _l('dropdown_non_selected_tex'); ?></option>
                        <?php foreach ((array) $departments as $p) {
                            $p = (array) $p;
                            $pid = $p['departmentid'] ?? $p['id'] ?? 0;
                            if (empty($pid)) continue;
                            ?>
                        <option value="<?php echo (int)$pid; ?>"><?php echo htmlspecialchars($p['name'] ?? ''); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" name="id" id="department_id">
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
function new_department() { $('#department_id').val(''); $('#department_name').val(''); $('#department_parent').val('0'); $('#department_parent option').prop('disabled', false); if (typeof $.fn.selectpicker !== 'undefined') { $('#department_parent').selectpicker('refresh'); } $('#department_modal').modal('show'); }
function edit_department(el, id) { $('#department_id').val(id); $('#department_name').val($(el).data('name')); $('#department_parent').val($(el).data('parent')||'0'); $('#department_parent option').prop('disabled', false); $('#department_parent option[value="'+id+'"]').prop('disabled', true); if (typeof $.fn.selectpicker !== 'undefined') { $('#department_parent').selectpicker('refresh'); } $('#department_modal').modal('show'); $('#department_modal').one('hidden.bs.modal', function(){ $('#department_parent option').prop('disabled', false); if (typeof $.fn.selectpicker !== 'undefined') { $('#department_parent').selectpicker('refresh'); } }); }
</script>
