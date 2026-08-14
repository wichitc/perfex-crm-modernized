<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-0">
                                <i class="fa fa-wrench"></i> <?php echo _l('maintenance_schedule'); ?>
                            </h4>
                            <?php if (has_permission('assets', '', 'create') || is_admin()): ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#maintenanceModal" onclick="resetMaintenanceForm()">
                                <i class="fa fa-plus"></i> <?php echo _l('schedule_maintenance'); ?>
                            </button>
                            <?php endif; ?>
                        </div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#scheduled" aria-controls="scheduled" role="tab" data-toggle="tab">
                                    <?php echo _l('scheduled'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#in_progress" aria-controls="in_progress" role="tab" data-toggle="tab">
                                    <?php echo _l('in_progress'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#completed" aria-controls="completed" role="tab" data-toggle="tab">
                                    <?php echo _l('completed'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#overdue" aria-controls="overdue" role="tab" data-toggle="tab">
                                    <span class="text-danger"><?php echo _l('overdue'); ?></span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content tw-mt-4">
                            <div role="tabpanel" class="tab-pane active" id="scheduled">
                                <?php
                                $table_data = [
                                    '#',
                                    _l('asset'),
                                    _l('maintenance_type'),
                                    _l('title'),
                                    _l('scheduled_date'),
                                    _l('cost'),
                                    _l('status'),
                                    _l('action')
                                ];
                                render_datatable($table_data, 'maintenance_scheduled');
                                ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="in_progress">
                                <?php render_datatable($table_data, 'maintenance_in_progress'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="completed">
                                <?php render_datatable($table_data, 'maintenance_completed'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="overdue">
                                <?php render_datatable($table_data, 'maintenance_overdue'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(admin_url('assets/add_maintenance'), ['id' => 'maintenanceForm']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="maintenanceModalTitle"><?php echo _l('schedule_maintenance'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="maintenance_id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asset_id"><?php echo _l('asset'); ?> <span class="text-danger">*</span></label>
                            <select name="asset_id" id="asset_id" class="selectpicker" data-live-search="true" data-width="100%" required>
                                <option value=""><?php echo _l('select_asset'); ?></option>
                                <?php foreach ($assets as $asset): ?>
                                <option value="<?php echo $asset['id']; ?>"><?php echo htmlspecialchars($asset['assets_code'] . ' - ' . $asset['assets_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="maintenance_type"><?php echo _l('maintenance_type'); ?> <span class="text-danger">*</span></label>
                            <select name="maintenance_type" id="maintenance_type" class="selectpicker" data-width="100%" required>
                                <option value="preventive"><?php echo _l('preventive'); ?></option>
                                <option value="corrective"><?php echo _l('corrective'); ?></option>
                                <option value="inspection"><?php echo _l('inspection'); ?></option>
                                <option value="calibration"><?php echo _l('calibration'); ?></option>
                                <option value="cleaning"><?php echo _l('cleaning'); ?></option>
                                <option value="other"><?php echo _l('other'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('title', 'title', '', 'text', ['required' => true]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_date_input('scheduled_date', 'scheduled_date', '', ['required' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input('cost', 'estimated_cost', '', 'text', ['data-type' => 'currency']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_input('vendor_name', 'vendor_name'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input('vendor_contact', 'vendor_contact'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_textarea('description', 'description'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="is_recurring" id="is_recurring" value="1">
                            <label for="is_recurring"><?php echo _l('is_recurring'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="row recurring-options" style="display:none;">
                    <div class="col-md-6">
                        <?php echo render_input('recurring_interval', 'recurring_interval', '', 'number'); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recurring_unit"><?php echo _l('recurring_unit'); ?></label>
                            <select name="recurring_unit" id="recurring_unit" class="selectpicker" data-width="100%">
                                <option value="days"><?php echo _l('days'); ?></option>
                                <option value="weeks"><?php echo _l('weeks'); ?></option>
                                <option value="months" selected><?php echo _l('months'); ?></option>
                                <option value="years"><?php echo _l('years'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row status-section" style="display:none;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status"><?php echo _l('status'); ?></label>
                            <select name="status" id="maintenance_status" class="selectpicker" data-width="100%">
                                <option value="scheduled"><?php echo _l('scheduled'); ?></option>
                                <option value="in_progress"><?php echo _l('in_progress'); ?></option>
                                <option value="completed"><?php echo _l('completed'); ?></option>
                                <option value="cancelled"><?php echo _l('cancelled'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 completed-date-section" style="display:none;">
                        <?php echo render_date_input('completed_date', 'completed_date'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_textarea('notes', 'notes'); ?>
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

<?php init_tail(); ?>
<script>
$(function() {
    appValidateForm($('#maintenanceForm'), {
        asset_id: 'required',
        title: 'required',
        scheduled_date: 'required',
        maintenance_type: 'required'
    });

    $('#is_recurring').on('change', function() {
        $('.recurring-options').toggle(this.checked);
    });

    $('#maintenance_status').on('change', function() {
        $('.completed-date-section').toggle($(this).val() === 'completed');
    });

    // Initialize DataTables via AJAX - status passed via query parameter
    initDataTable('.table-maintenance_scheduled', admin_url + 'assets/table_maintenance?status=scheduled', [], [], undefined, [4, 'asc']);
    initDataTable('.table-maintenance_in_progress', admin_url + 'assets/table_maintenance?status=in_progress', [], [], undefined, [4, 'asc']);
    initDataTable('.table-maintenance_completed', admin_url + 'assets/table_maintenance?status=completed', [], [], undefined, [4, 'desc']);
    initDataTable('.table-maintenance_overdue', admin_url + 'assets/table_maintenance?status=overdue', [], [], undefined, [4, 'asc']);
});

function resetMaintenanceForm() {
    $('#maintenanceForm')[0].reset();
    $('#maintenanceForm').attr('action', admin_url + 'assets/add_maintenance');
    $('#maintenance_id').val('');
    $('#maintenanceModalTitle').text('<?php echo _l('schedule_maintenance'); ?>');
    $('.status-section').hide();
    $('.recurring-options').hide();
    $('.completed-date-section').hide();
    $('#maintenanceModal .selectpicker').selectpicker('refresh');
}

function editMaintenanceFromBtn(btn) {
    var id = $(btn).data('maintenance-id');
    var data = $(btn).data('maintenance');
    
    // If data is a string (not parsed), try to parse it
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error('Failed to parse maintenance data:', e);
            return;
        }
    }
    
    editMaintenance(id, data);
}

function editMaintenance(id, data) {
    $('#maintenanceForm').attr('action', admin_url + 'assets/update_maintenance/' + id);
    $('#maintenance_id').val(id);
    $('#maintenanceModalTitle').text('<?php echo _l('edit_maintenance'); ?>');
    
    // Set all form values
    $('#asset_id').val(data.asset_id || '').selectpicker('refresh');
    $('#maintenance_type').val(data.maintenance_type || '').selectpicker('refresh');
    $('#title').val(data.title || '');
    $('input[name="scheduled_date"]').val(data.scheduled_date || '');
    $('input[name="cost"]').val(data.cost || '');
    $('input[name="vendor_name"]').val(data.vendor_name || '');
    $('input[name="vendor_contact"]').val(data.vendor_contact || '');
    $('textarea[name="description"]').val(data.description || '');
    $('textarea[name="notes"]').val(data.notes || '');
    
    if (data.is_recurring == 1) {
        $('#is_recurring').prop('checked', true).trigger('change');
        $('input[name="recurring_interval"]').val(data.recurring_interval || '');
        $('#recurring_unit').val(data.recurring_unit || '').selectpicker('refresh');
    } else {
        $('#is_recurring').prop('checked', false);
        $('.recurring-options').hide();
    }
    
    $('.status-section').show();
    $('#maintenance_status').val(data.status || 'scheduled').selectpicker('refresh').trigger('change');
    if (data.completed_date) {
        $('input[name="completed_date"]').val(data.completed_date);
    }
    
    $('#maintenanceModal').modal('show');
}
</script>
</body>
</html>
