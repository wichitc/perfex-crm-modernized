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
                                <i class="fa fa-exchange"></i> <?php echo _l('asset_checkouts'); ?>
                            </h4>
                            <?php if (has_permission('assets', '', 'create') || is_admin()): ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkoutModal">
                                <i class="fa fa-sign-out"></i> <?php echo _l('checkout_asset'); ?>
                            </button>
                            <?php endif; ?>
                        </div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#checked_out" aria-controls="checked_out" role="tab" data-toggle="tab">
                                    <?php echo _l('checked_out'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#overdue" aria-controls="overdue" role="tab" data-toggle="tab">
                                    <span class="text-danger"><?php echo _l('overdue'); ?></span>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#returned" aria-controls="returned" role="tab" data-toggle="tab">
                                    <?php echo _l('returned'); ?>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content tw-mt-4">
                            <div role="tabpanel" class="tab-pane active" id="checked_out">
                                <?php
                                $table_data = [
                                    _l('asset'),
                                    _l('checked_out_to'),
                                    _l('quantity'),
                                    _l('checkout_date'),
                                    _l('expected_return'),
                                    _l('condition'),
                                    _l('action')
                                ];
                                render_datatable($table_data, 'checkouts_table_active');
                                ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="overdue">
                                <?php render_datatable($table_data, 'checkouts_table_overdue'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="returned">
                                <?php 
                                $table_data_returned = [
                                    _l('asset'),
                                    _l('checked_out_to'),
                                    _l('quantity'),
                                    _l('checkout_date'),
                                    _l('return_date'),
                                    _l('condition_returned')
                                ];
                                render_datatable($table_data_returned, 'checkouts_table_returned'); 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('assets/checkout_asset'), ['id' => 'checkoutForm']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('checkout_asset'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="asset_id"><?php echo _l('asset'); ?> <span class="text-danger">*</span></label>
                    <select name="asset_id" id="checkout_asset_id" class="selectpicker" data-live-search="true" data-width="100%" required>
                        <option value=""><?php echo _l('select_asset'); ?></option>
                        <?php foreach ($assets as $asset): 
                            $available = $asset['amount'] - $asset['total_allocation'];
                        ?>
                        <option value="<?php echo $asset['id']; ?>" data-available="<?php echo $available; ?>">
                            <?php echo htmlspecialchars($asset['assets_code'] . ' - ' . $asset['assets_name'] . ' (' . $available . ' ' . _l('available') . ')'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="checked_out_to"><?php echo _l('checkout_to'); ?> <span class="text-danger">*</span></label>
                    <?php echo render_asset_recipient_select('checked_out_to'); ?>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_input('quantity', 'quantity', '1', 'number', ['min' => 1, 'required' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_date_input('expected_return_date', 'expected_return_date'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="checkout_condition"><?php echo _l('condition'); ?></label>
                    <select name="checkout_condition" id="checkout_condition" class="selectpicker" data-width="100%">
                        <option value="excellent"><?php echo _l('excellent'); ?></option>
                        <option value="good" selected><?php echo _l('good'); ?></option>
                        <option value="fair"><?php echo _l('fair'); ?></option>
                        <option value="poor"><?php echo _l('poor'); ?></option>
                    </select>
                </div>
                <?php echo render_textarea('checkout_notes', 'notes'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('checkout'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Checkin Modal -->
<div class="modal fade" id="checkinModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open('', ['id' => 'checkinForm']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('checkin_asset'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="checkin_condition"><?php echo _l('return_condition'); ?></label>
                    <select name="checkin_condition" id="checkin_condition" class="selectpicker" data-width="100%">
                        <option value="excellent"><?php echo _l('excellent'); ?></option>
                        <option value="good" selected><?php echo _l('good'); ?></option>
                        <option value="fair"><?php echo _l('fair'); ?></option>
                        <option value="poor"><?php echo _l('poor'); ?></option>
                        <option value="damaged"><?php echo _l('damaged'); ?></option>
                    </select>
                </div>
                <?php echo render_textarea('checkin_notes', 'notes'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-success"><?php echo _l('checkin'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
    appValidateForm($('#checkoutForm'), {
        asset_id: 'required',
        checked_out_to: 'required',
        quantity: 'required'
    });

    // Active checkouts table - status passed via query parameter
    initDataTable('.table-checkouts_table_active', admin_url + 'assets/table_checkouts?status=checked_out', [], [], undefined, [3, 'desc']);

    // Overdue table - status passed via query parameter
    initDataTable('.table-checkouts_table_overdue', admin_url + 'assets/table_checkouts?status=overdue', [], [], undefined, [3, 'desc']);

    // Returned table - status passed via query parameter
    initDataTable('.table-checkouts_table_returned', admin_url + 'assets/table_checkouts?status=returned', [], [], undefined, [3, 'desc']);

    // Validate quantity against available
    $('#checkout_asset_id').on('change', function() {
        var available = $(this).find(':selected').data('available') || 1;
        $('input[name="quantity"]').attr('max', available);
    });
    
    // Event delegation for check-in button
    $(document).on('click', '.btn-checkin', function(e) {
        e.preventDefault();
        var checkoutId = $(this).data('checkout-id');
        showCheckinModal(checkoutId);
    });
});

function showCheckinModal(checkoutId) {
    $('#checkinForm').attr('action', admin_url + 'assets/checkin_asset/' + checkoutId);
    $('#checkinModal').modal('show');
}

// Refresh all tables on page
function refreshCheckoutTables() {
    try {
        if ($.fn.DataTable.isDataTable('.table-checkouts_table_active')) {
            $('.table-checkouts_table_active').DataTable().ajax.reload(null, false);
        }
        if ($.fn.DataTable.isDataTable('.table-checkouts_table_overdue')) {
            $('.table-checkouts_table_overdue').DataTable().ajax.reload(null, false);
        }
        if ($.fn.DataTable.isDataTable('.table-checkouts_table_returned')) {
            $('.table-checkouts_table_returned').DataTable().ajax.reload(null, false);
        }
    } catch(e) {
        // If refresh fails, reload the page
        location.reload();
    }
}

// Handle form submission via AJAX for better UX
$('#checkinForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    $.post(form.attr('action'), form.serialize(), function(response) {
        $('#checkinModal').modal('hide');
        alert_float('success', '<?php echo _l('asset_checked_in_successfully'); ?>');
        refreshCheckoutTables();
    }).fail(function() {
        alert_float('danger', '<?php echo _l('error_occurred'); ?>');
    });
});
</script>
</body>
</html>
