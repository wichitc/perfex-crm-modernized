<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="#" onclick="new_training_type(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add'); ?> <?php echo _l('training_types'); ?>
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
    <?php foreach((array)$training_types as $c){ ?>
    <tr>
       <td><?php echo htmlspecialchars($c['id']); ?></td>
       <td><?php echo htmlspecialchars($c['name']); ?></td>
       <td>
         <a href="#" onclick="edit_training_type(this,<?php echo (int)$c['id']; ?>); return false" data-name="<?php echo htmlspecialchars($c['name']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
          <a href="<?php echo admin_url('hrm/delete_training_type/'.(int)$c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>       
<div class="modal fade" id="training_type_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('hrm/training_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('training_types'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name"><?php echo _l('name'); ?></label>
                    <input type="text" name="name" id="training_type_name" class="form-control" required>
                </div>
                <input type="hidden" name="id" id="training_type_id">
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
function new_training_type() { $('#training_type_id').val(''); $('#training_type_name').val(''); $('#training_type_modal').modal('show'); }
function edit_training_type(el, id) { $('#training_type_id').val(id); $('#training_type_name').val($(el).data('name')); $('#training_type_modal').modal('show'); }
</script>
