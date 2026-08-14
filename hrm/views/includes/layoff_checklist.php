<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="#" onclick="new_layoff_checklist(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add'); ?> <?php echo _l('layoff_checklist'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('name'); ?></th>
    <th><?php echo _l('description'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
 <tbody>
    <?php foreach((array)$layoff_checklist as $c){ ?>
    <tr>
       <td><?php echo htmlspecialchars($c['id']); ?></td>
       <td><?php echo htmlspecialchars($c['name']); ?></td>
       <td><?php echo htmlspecialchars($c['description'] ?? ''); ?></td>
       <td>
         <a href="#" onclick="edit_layoff_checklist(this,<?php echo (int)$c['id']; ?>); return false" data-name="<?php echo htmlspecialchars($c['name']); ?>" data-description="<?php echo htmlspecialchars($c['description'] ?? ''); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
          <a href="<?php echo admin_url('hrm/delete_layoff_checklist/'.(int)$c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>       
<div class="modal fade" id="layoff_checklist_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/layoff_checklist')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('layoff_checklist'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name"><?php echo _l('name'); ?></label>
                    <input type="text" name="name" id="layoff_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description"><?php echo _l('description'); ?></label>
                    <textarea name="description" id="layoff_description" class="form-control" rows="3"></textarea>
                </div>
                <input type="hidden" name="id" id="layoff_id">
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
function new_layoff_checklist() { $('#layoff_id').val(''); $('#layoff_name').val(''); $('#layoff_description').val(''); $('#layoff_checklist_modal').modal('show'); }
function edit_layoff_checklist(el, id) { $('#layoff_id').val(id); $('#layoff_name').val($(el).data('name')); $('#layoff_description').val($(el).data('description')||''); $('#layoff_checklist_modal').modal('show'); }
</script>
