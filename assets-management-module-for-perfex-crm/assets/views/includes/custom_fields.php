<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="tw-font-semibold tw-mb-4"><?php echo _l('custom_fields'); ?></h4>

<?php if (has_permission('assets', '', 'create') || is_admin()): ?>
<button type="button" class="btn btn-primary btn-sm tw-mb-4" data-toggle="modal" data-target="#customFieldModal" onclick="resetCustomFieldForm()">
    <i class="fa fa-plus"></i> <?php echo _l('add_new'); ?>
</button>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo _l('field_name'); ?></th>
                <th><?php echo _l('field_type'); ?></th>
                <th><?php echo _l('required'); ?></th>
                <th><?php echo _l('show_on_table'); ?></th>
                <th><?php echo _l('active'); ?></th>
                <th><?php echo _l('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($custom_fields)): ?>
                <?php foreach ($custom_fields as $field): ?>
                <tr>
                    <td><?php echo htmlspecialchars($field['field_name']); ?></td>
                    <td><span class="label label-default"><?php echo _l($field['field_type']); ?></span></td>
                    <td><?php echo $field['required'] ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>'; ?></td>
                    <td><?php echo $field['show_on_table'] ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>'; ?></td>
                    <td><?php echo $field['active'] ? '<span class="label label-success">' . _l('active') . '</span>' : '<span class="label label-default">' . _l('inactive') . '</span>'; ?></td>
                    <td>
                        <?php if (has_permission('assets', '', 'edit') || is_admin()): ?>
                        <button type="button" class="btn btn-default btn-xs" onclick='editCustomField(<?php echo json_encode($field); ?>)'>
                            <i class="fa fa-pencil"></i>
                        </button>
                        <?php endif; ?>
                        <?php if (has_permission('assets', '', 'delete') || is_admin()): ?>
                        <a href="<?php echo admin_url('assets/delete_custom_field/' . $field['id']); ?>" class="btn btn-danger btn-xs _delete">
                            <i class="fa fa-trash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center"><?php echo _l('no_records_found'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Custom Field Modal -->
<div class="modal fade" id="customFieldModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('assets/custom_field'), ['id' => 'customFieldForm']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="customFieldModalTitle"><?php echo _l('custom_field'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="custom_field_id">
                <?php echo render_input('field_name', 'field_name', '', 'text', ['required' => true]); ?>
                <div class="form-group">
                    <label for="field_type"><?php echo _l('field_type'); ?> <span class="text-danger">*</span></label>
                    <select name="field_type" id="field_type" class="selectpicker" data-width="100%" required>
                        <option value="text"><?php echo _l('text'); ?></option>
                        <option value="textarea"><?php echo _l('textarea'); ?></option>
                        <option value="number"><?php echo _l('number'); ?></option>
                        <option value="date"><?php echo _l('date'); ?></option>
                        <option value="select"><?php echo _l('select'); ?></option>
                        <option value="checkbox"><?php echo _l('checkbox'); ?></option>
                        <option value="url"><?php echo _l('url'); ?></option>
                    </select>
                </div>
                <div id="field_options_container" style="display:none;">
                    <?php echo render_textarea('field_options', 'field_options', '', ['placeholder' => 'Option 1|Option 2|Option 3']); ?>
                    <small class="text-muted"><?php echo _l('separate_options_with_pipe'); ?></small>
                </div>
                <div class="form-group">
                    <label for="applies_to_groups"><?php echo _l('applies_to_groups'); ?></label>
                    <select name="applies_to_groups[]" id="applies_to_groups" class="selectpicker" data-width="100%" multiple>
                        <?php foreach ($asset_group as $group): ?>
                        <option value="<?php echo $group['group_id']; ?>"><?php echo htmlspecialchars($group['group_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted"><?php echo _l('leave_empty_for_all'); ?></small>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="required" id="cf_required" value="1">
                            <label for="cf_required"><?php echo _l('required'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="show_on_table" id="cf_show_on_table" value="1">
                            <label for="cf_show_on_table"><?php echo _l('show_on_table'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="active" id="cf_active" value="1" checked>
                            <label for="cf_active"><?php echo _l('active'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
$(function() {
    $('#field_type').on('change', function() {
        var showOptions = $(this).val() === 'select';
        $('#field_options_container').toggle(showOptions);
    });
});

function resetCustomFieldForm() {
    $('#customFieldForm')[0].reset();
    $('#custom_field_id').val('');
    $('#customFieldModalTitle').text('<?php echo _l('custom_field'); ?>');
    $('#field_type').val('text').selectpicker('refresh');
    $('#applies_to_groups').val([]).selectpicker('refresh');
    $('#field_options_container').hide();
}

function editCustomField(field) {
    $('#custom_field_id').val(field.id);
    $('#customFieldModalTitle').text('<?php echo _l('edit'); ?> <?php echo _l('custom_field'); ?>');
    $('input[name="field_name"]').val(field.field_name);
    $('#field_type').val(field.field_type).selectpicker('refresh').trigger('change');
    $('textarea[name="field_options"]').val(field.field_options);
    
    // Handle applies_to_groups - can be JSON array or comma-separated string
    if (field.applies_to_groups) {
        var groups;
        try {
            // Try parsing as JSON first (new format)
            groups = JSON.parse(field.applies_to_groups);
        } catch (e) {
            // Fall back to comma-separated string (legacy format)
            groups = field.applies_to_groups.split(',');
        }
        // Ensure groups is an array
        if (!Array.isArray(groups)) {
            groups = [groups];
        }
        $('#applies_to_groups').val(groups).selectpicker('refresh');
    } else {
        $('#applies_to_groups').val([]).selectpicker('refresh');
    }
    
    $('#cf_required').prop('checked', field.required == 1);
    $('#cf_show_on_table').prop('checked', field.show_on_table == 1);
    $('#cf_active').prop('checked', field.active == 1);
    
    $('#customFieldModal').modal('show');
}
</script>
