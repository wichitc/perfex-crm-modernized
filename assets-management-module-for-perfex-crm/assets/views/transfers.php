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
                                <i class="fa fa-truck"></i> <?php echo _l('asset_transfers'); ?>
                            </h4>
                            <?php if (has_permission('assets', '', 'create') || is_admin()): ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#transferModal">
                                <i class="fa fa-plus"></i> <?php echo _l('new_transfer'); ?>
                            </button>
                            <?php endif; ?>
                        </div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#pending_transfers" role="tab" data-toggle="tab"><?php echo _l('pending'); ?></a>
                            </li>
                            <li role="presentation">
                                <a href="#in_transit" role="tab" data-toggle="tab"><?php echo _l('in_transit'); ?></a>
                            </li>
                            <li role="presentation">
                                <a href="#completed_transfers" role="tab" data-toggle="tab"><?php echo _l('completed'); ?></a>
                            </li>
                        </ul>

                        <div class="tab-content tw-mt-4">
                            <div role="tabpanel" class="tab-pane active" id="pending_transfers">
                                <?php
                                $table_data = [
                                    _l('asset'),
                                    _l('from_location'),
                                    _l('to_location'),
                                    _l('quantity'),
                                    _l('transfer_date'),
                                    _l('transferred_by'),
                                    _l('action')
                                ];
                                render_datatable($table_data, 'transfers_pending');
                                ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="in_transit">
                                <?php render_datatable($table_data, 'transfers_transit'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="completed_transfers">
                                <?php 
                                $table_data_completed = [
                                    _l('asset'),
                                    _l('from_location'),
                                    _l('to_location'),
                                    _l('quantity'),
                                    _l('transfer_date'),
                                    _l('received_by'),
                                    _l('received_at')
                                ];
                                render_datatable($table_data_completed, 'transfers_completed'); 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('assets/create_transfer'), ['id' => 'transferForm']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('new_transfer'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="asset_id"><?php echo _l('asset'); ?> <span class="text-danger">*</span></label>
                    <select name="asset_id" id="transfer_asset_id" class="selectpicker" data-live-search="true" data-width="100%" required>
                        <option value=""><?php echo _l('select_asset'); ?></option>
                        <?php foreach ($assets as $asset): ?>
                        <option value="<?php echo $asset['id']; ?>" data-location="<?php echo $asset['asset_location']; ?>" data-department="<?php echo $asset['department']; ?>">
                            <?php echo htmlspecialchars($asset['assets_code'] . ' - ' . $asset['assets_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo _l('from_location'); ?></label>
                            <select name="from_location" id="from_location" class="selectpicker" data-width="100%" disabled>
                                <option value=""><?php echo _l('current_location'); ?></option>
                                <?php foreach ($locations as $loc): ?>
                                <option value="<?php echo $loc['location_id']; ?>"><?php echo htmlspecialchars($loc['location']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo _l('to_location'); ?> <span class="text-danger">*</span></label>
                            <select name="to_location" id="to_location" class="selectpicker" data-width="100%" required>
                                <option value=""><?php echo _l('select_location'); ?></option>
                                <?php foreach ($locations as $loc): ?>
                                <option value="<?php echo $loc['location_id']; ?>"><?php echo htmlspecialchars($loc['location']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo _l('from_department'); ?></label>
                            <select name="from_department" id="from_department" class="selectpicker" data-width="100%" disabled>
                                <option value=""><?php echo _l('current_department'); ?></option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['departmentid']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo _l('to_department'); ?></label>
                            <select name="to_department" id="to_department" class="selectpicker" data-width="100%">
                                <option value=""><?php echo _l('no_change'); ?></option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['departmentid']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_input('quantity', 'quantity', '1', 'number', ['min' => 1, 'required' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_datetime_input('transfer_date', 'transfer_date', date('Y-m-d H:i'), ['required' => true]); ?>
                    </div>
                </div>
                <?php echo render_textarea('reason', 'reason'); ?>
                <?php echo render_textarea('notes', 'notes'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('initiate_transfer'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
    appValidateForm($('#transferForm'), {
        asset_id: 'required',
        to_location: 'required',
        quantity: 'required',
        transfer_date: 'required'
    });

    // Auto-fill current location/department when asset selected
    $('#transfer_asset_id').on('change', function() {
        var $selected = $(this).find(':selected');
        var location = $selected.data('location');
        var department = $selected.data('department');
        
        $('#from_location').val(location).selectpicker('refresh');
        $('#from_department').val(department).selectpicker('refresh');
    });

    // Initialize tables - status passed via query parameter
    initDataTable('.table-transfers_pending', admin_url + 'assets/table_transfers?status=pending', [], [], undefined, [4, 'desc']);
    initDataTable('.table-transfers_transit', admin_url + 'assets/table_transfers?status=in_transit', [], [], undefined, [4, 'desc']);
    initDataTable('.table-transfers_completed', admin_url + 'assets/table_transfers?status=completed', [], [], undefined, [4, 'desc']);
});
</script>
</body>
</html>
