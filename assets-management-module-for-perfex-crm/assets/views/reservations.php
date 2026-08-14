<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-0">
                                <i class="fa fa-calendar"></i> <?php echo _l('asset_reservations'); ?>
                            </h4>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reservationModal">
                                <i class="fa fa-plus"></i> <?php echo _l('new_reservation'); ?>
                            </button>
                        </div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#calendar_view" aria-controls="calendar_view" role="tab" data-toggle="tab">
                                    <i class="fa fa-calendar"></i> <?php echo _l('calendar_view'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#pending_tab" aria-controls="pending_tab" role="tab" data-toggle="tab">
                                    <?php echo _l('pending'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#approved_tab" aria-controls="approved_tab" role="tab" data-toggle="tab">
                                    <?php echo _l('approved'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#all_tab" aria-controls="all_tab" role="tab" data-toggle="tab">
                                    <?php echo _l('all'); ?>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content tw-mt-4">
                            <div role="tabpanel" class="tab-pane active" id="calendar_view">
                                <div id="reservationsCalendar" style="min-height: 600px;"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="pending_tab">
                                <?php
                                $table_data = [
                                    _l('asset'),
                                    _l('reserved_by'),
                                    _l('quantity'),
                                    _l('start_date'),
                                    _l('end_date'),
                                    _l('purpose'),
                                    _l('action')
                                ];
                                render_datatable($table_data, 'reservations_pending');
                                ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="approved_tab">
                                <?php render_datatable($table_data, 'reservations_approved'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="all_tab">
                                <?php 
                                $table_data_all = [
                                    _l('asset'),
                                    _l('reserved_by'),
                                    _l('quantity'),
                                    _l('start_date'),
                                    _l('end_date'),
                                    _l('status'),
                                    _l('action')
                                ];
                                render_datatable($table_data_all, 'reservations_all'); 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('assets/create_reservation'), ['id' => 'reservationForm']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('new_reservation'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="asset_id"><?php echo _l('asset'); ?> <span class="text-danger">*</span></label>
                    <select name="asset_id" id="reservation_asset_id" class="selectpicker" data-live-search="true" data-width="100%" required>
                        <option value=""><?php echo _l('select_asset'); ?></option>
                        <?php foreach ($assets as $asset): ?>
                        <option value="<?php echo $asset['id']; ?>">
                            <?php echo htmlspecialchars($asset['assets_code'] . ' - ' . $asset['assets_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_datetime_input('reservation_start', 'start_date', '', ['required' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_datetime_input('reservation_end', 'end_date', '', ['required' => true]); ?>
                    </div>
                </div>
                <?php echo render_input('quantity', 'quantity', '1', 'number', ['min' => 1, 'required' => true]); ?>
                <?php echo render_textarea('purpose', 'purpose'); ?>
                <?php echo render_textarea('notes', 'notes'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Reject Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <?php echo form_open('', ['id' => 'rejectForm']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('reject_reservation'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo render_textarea('reason', 'rejection_reason'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-danger"><?php echo _l('reject'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
$(function() {
    appValidateForm($('#reservationForm'), {
        asset_id: 'required',
        reservation_start: 'required',
        reservation_end: 'required',
        quantity: 'required'
    });

    // Initialize Calendar
    var calendarEl = document.getElementById('reservationsCalendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: admin_url + 'assets/get_reservations_calendar',
        eventClick: function(info) {
            alert('Reservation: ' + info.event.title);
        },
        dateClick: function(info) {
            $('input[name="reservation_start"]').val(info.dateStr + ' 09:00');
            $('input[name="reservation_end"]').val(info.dateStr + ' 17:00');
            $('#reservationModal').modal('show');
        }
    });
    calendar.render();

    // Pending reservations table - status passed via query parameter
    initDataTable('.table-reservations_pending', admin_url + 'assets/table_reservations?status=pending', [], [], undefined, [3, 'asc']);

    // Approved reservations table - status passed via query parameter
    initDataTable('.table-reservations_approved', admin_url + 'assets/table_reservations?status=approved', [], [], undefined, [3, 'asc']);

    // All reservations table - no status filter
    initDataTable('.table-reservations_all', admin_url + 'assets/table_reservations', [], [], undefined, [3, 'desc']);
});

function showRejectModal(id) {
    $('#rejectForm').attr('action', admin_url + 'assets/reject_reservation/' + id);
    $('#rejectModal').modal('show');
}
</script>
</body>
</html>
