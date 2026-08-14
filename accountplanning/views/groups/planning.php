<?php
$checkfolder = FCPATH . 'assets/plugins/tinymce/plugins/leaui_mindmap';
$srcloc = module_dir_path('accountplanning', 'assets/plugins/tinymce/plugins/leaui_mindmap');
$destloc = FCPATH . 'assets/plugins/tinymce/plugins/';
if (!is_dir($checkfolder) && is_dir($srcloc)) {
    if (!is_dir($destloc)) {
        @mkdir($destloc, 0755, true);
    }
    @mkdir($checkfolder, 0755, true);
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcloc, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $item) {
        $subpath = $iterator->getSubPathname();
        $target = $checkfolder . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $subpath);
        if ($item->isDir()) {
            @mkdir($target, 0755, true);
        } else {
            @copy($item->getPathname(), $target);
        }
    }
}
?>
<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('planning')); ?></h4>
<div class="clearfix"></div>
<?php echo form_open_multipart(admin_url('accountplanning/update_planning/'.$account->id), array('id' => 'service-ability-offering-form')); ?>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<div class="btn-bottom-toolbar btn-toolbar-container-out text-right ap-calc100-20left">
    <button class="btn btn-info only-save planning-form-submiter">
        <?php echo htmlspecialchars(_l('submit')); ?>
    </button>
</div>
<?php } ?>
<div class="row">
    <?php $value = (isset($account->subject) ? $account->subject : '') ?>
    <?php echo render_input('subject', 'subject', $value, 'text', array(), array(), 'col-md-6'); ?>
    <?php $value = (isset($account->date) ? _d($account->date) : '') ?>
    <?php echo render_select('date', $month, array('id', 'name'), 'time', $value, array(), array(), 'col-md-6'); ?>
</div>
<div class="row">
    <?php
    $status_options = [['id' => 'draft', 'name' => _l('ap_status_draft')], ['id' => 'in_progress', 'name' => _l('ap_status_in_progress')], ['id' => 'review', 'name' => _l('ap_status_review')], ['id' => 'completed', 'name' => _l('ap_status_completed')], ['id' => 'archived', 'name' => _l('ap_status_archived')]];
    $value = (isset($account->status) ? $account->status : 'draft');
    echo render_select('status', $status_options, array('id', 'name'), 'plan_status', $value, array(), array(), 'col-md-6');
    ?>
</div>
<?php if (isset($health_score)) { ?>
<div class="alert alert-info mbot15">
    <strong><?php echo _l('ap_health_score'); ?>:</strong> <?php echo $health_score; ?>%
</div>
<?php } ?>
<?php if (isset($account->approved_by) && $account->approved_by && isset($account->approved_date) && $account->approved_date) { ?>
<p class="text-muted mbot15"><?php echo _l('ap_approved_by'); ?>: <?php echo get_staff_full_name($account->approved_by); ?> - <?php echo _dt($account->approved_date); ?></p>
<?php } ?>
<h4 class="bold"><?php echo htmlspecialchars(_l('planning_a')); ?></h4>
<?php $value = (isset($account->objectives) ? $account->objectives : '') ?>
<?php echo render_textarea('objectives', '', $value, array(), array(), '', 'ap-tinymce'); ?>
<div class="row">
    <?php $value = (isset($account->revenue_next_year) ? app_format_number($account->revenue_next_year) : '') ?>
    <?php echo render_input('revenue_next_year', _l('revenue_next_year', '($)'), $value, 'text', array('data-type' => 'currency'), array(), 'col-md-6'); ?>
    <?php $value = (isset($account->margin) ? $account->margin : '') ?>
    <?php echo render_input('margin', _l('margin', '(%)'), $value, 'number', array(), array(), 'col-md-6'); ?>
    <?php $value = (isset($account->wallet_share) ? $account->wallet_share : '') ?>
    <?php echo render_input('wallet_share', _l('wallet_share', '(%)'), $value, 'number', array(), array(), 'col-md-6'); ?>
    <?php
    $bcg_model = array(
        '1' => array('id' => 'Question marks', 'name' => 'Question marks'),
        '2' => array('id' => 'Stars', 'name' => 'Stars'),
        '3' => array('id' => 'Dogs', 'name' => 'Dogs'),
        '4' => array('id' => 'Cash cows', 'name' => 'Cash cows'),
    );
    ?>
    <?php
    $value = (isset($account->client_status) ? $account->client_status : '');
    if ($value == 'Green') {
        $color = '#84C529';
    } elseif ($value == 'Red') {
        $color = '#fc2d42';
    } elseif ($value == 'Yellow') {
        $color = '#FF0';
    } else {
        $color = '#fc2d42';
    }
    ?>
    <div class="form-group select-placeholder col-md-6">
        <label class="control-label">
            <?php echo htmlspecialchars(_l('client_status')); ?>:
            <div id="client_status_color" class="calendar-cpicker cpicker cpicker-big" style="float: right; background: <?php echo htmlspecialchars($color); ?>;"></div>
        </label>
        <select class="selectpicker display-block mbot15" name="client_status" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('dropdown_non_selected_tex')); ?>">
            <option value="Red" class="text-danger" <?php if (isset($account) && $account->client_status == "Red") { echo 'selected'; } ?>>Red</option>
            <option value="Yellow" class="text-warning" <?php if (isset($account) && $account->client_status == "Yellow") { echo 'selected'; } ?>>Yellow</option>
            <option value="Green" class="text-success" <?php if (isset($account) && $account->client_status == "Green") { echo 'selected'; } ?>>Green</option>
        </select>
    </div>
    <?php $value = (isset($account->bcg_model) ? $account->bcg_model : '') ?>
    <?php echo render_select('bcg_model', $bcg_model, array('id', 'name'), 'bcg_model', $value, array(), array(), 'col-md-6'); ?>
</div>
<h4 class="bold"><?php echo htmlspecialchars(_l('planning_b')); ?></h4>
<?php $value = (isset($account->threat) ? $account->threat : '') ?>
<?php echo render_textarea('threat', 'threat', $value, array(), array(), '', 'ap-tinymce'); ?>
<?php $value = (isset($account->opportunity) ? $account->opportunity : '') ?>
<?php echo render_textarea('opportunity', 'opportunity', $value, array(), array(), '', 'ap-tinymce'); ?>
<?php $value = (isset($account->criteria_to_success) ? $account->criteria_to_success : '') ?>
<?php echo render_textarea('criteria_to_success', 'criteria_to_success', $value, array(), array(), '', 'ap-tinymce'); ?>
<?php $value = (isset($account->constraints) ? $account->constraints : '') ?>
<?php echo render_textarea('constraints', 'constraints', $value, array(), array(), '', 'ap-tinymce'); ?>
<?php if (isset($goals) && !empty($goals)) { ?>
<h4 class="bold mtop20"><?php echo htmlspecialchars(_l('ap_goals_kpis')); ?></h4>
<table class="table table-striped">
    <thead><tr><th><?php echo _l('ap_goal_name'); ?></th><th><?php echo _l('ap_goal_target'); ?></th><th><?php echo _l('ap_goal_actual'); ?></th><th><?php echo _l('ap_goal_due_date'); ?></th><?php if (has_permission('accountplanning', '', 'edit')) { ?><th></th><?php } ?></tr></thead>
    <tbody>
    <?php foreach ($goals as $g) { ?>
    <tr>
        <td><?php echo htmlspecialchars($g['name']); ?></td>
        <td><?php echo $g['target'] !== null ? app_format_money($g['target'], get_base_currency()) : '-'; ?></td>
        <td><?php echo $g['actual'] !== null ? app_format_money($g['actual'], get_base_currency()) : '-'; ?></td>
        <td><?php echo $g['due_date'] ? _d($g['due_date']) : '-'; ?></td>
        <?php if (has_permission('accountplanning', '', 'edit')) { ?><td><a href="<?php echo admin_url('accountplanning/delete_goal/' . $account->id . '/' . $g['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-trash"></i></a></td><?php } ?>
    </tr>
    <?php } ?>
    </tbody>
</table>
<?php } else { ?>
<p class="text-muted"><?php echo _l('no_results_found'); ?></p>
<?php } ?>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<div class="mtop15">
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#ap_add_goal_modal"><i class="fa fa-plus"></i> <?php echo _l('ap_goals_kpis'); ?></button>
</div>
<?php } ?>
<?php if (has_permission('accountplanning', '', 'edit') && !empty($todo_list)) {
    $has_overdue = false;
    foreach ($todo_list as $t) {
        if (!empty($t['pic']) && ($t['status'] ?? '') != 'Complete') $has_overdue = true;
    }
    if ($has_overdue) { ?>
<div class="mbot15">
    <a href="<?php echo admin_url('accountplanning/notify_assignees/' . $account->id); ?>" class="btn btn-warning btn-sm"><i class="fa fa-envelope"></i> <?php echo _l('ap_notify_assignees'); ?></a>
    <small class="text-muted"><?php echo _l('ap_notify_assignees_help'); ?></small>
</div>
<?php } } ?>
<h4 class="bold"><?php echo htmlspecialchars(_l('planning_c')); ?></h4>
<div id="sample">
    <label class="text-danger ap-danger">
        <?php echo _l('label_mindmap', '<i class="mce-ico mce-i-none ap-minmap-icon"></i>'); ?>
        <?php echo htmlspecialchars(_l('label_mindmap_edit', '<i class="mce-ico mce-i-none ap-minmap-icon"></i>')); ?>
        <button type="button" class="btn btn-default btn-sm pull-right mleft5" onclick="ap_export_mindmap_svg(); return false;"><i class="fa fa-file-image-o"></i> <?php echo _l('ap_export_mindmap_svg'); ?></button>
        <button type="button" class="btn btn-default btn-sm pull-right" onclick="ap_export_mindmap(); return false;"><i class="fa fa-download"></i> <?php echo _l('ap_export_mindmap'); ?></button>
    </label>
    <?php $value = (isset($account->data_tree) ? $account->data_tree : '') ?>
    <?php echo render_textarea('data_tree', '', $value, array(), array(), '', 'ap-tinymce-mindmap'); ?>
</div>
<br>
<label class="ap-font-500"><?php echo htmlspecialchars(_l('to_do_list')); ?></label>
<br>
<div id="todo_list"></div>
<?php echo form_hidden('todo_list'); ?>
<?php if (count($account->attachments) > 0) { ?>
<div class="clearfix"></div>
<hr />
<p class="bold text-muted"><?php echo htmlspecialchars(_l('ticket_single_attachments')); ?></p>
<?php foreach ($account->attachments as $attachment) { ?>
    <?php
    $attachment_url = site_url('accountplanning/download_file/'.$attachment['attachment_key']);
    if (!empty($attachment['external'])) {
        $attachment_url = $attachment['external_link'];
    }
    ?>
    <div class="mbot15 row inline-block full-width" data-attachment-id="<?php echo htmlspecialchars($attachment['id']); ?>">
        <div class="col-md-8">
            <a name="preview-inv-btn" class="ap-margin-right-5" rel_id="<?php echo htmlspecialchars($attachment['rel_id']); ?>" id="<?php echo htmlspecialchars($attachment['id']); ?>" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left" data-toggle="tooltip" title data-original-title="<?php echo htmlspecialchars(_l('preview_file')); ?>"><i class="fa fa-eye"></i></a>
            <div class="pull-left"><i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i></div>
            <a href="<?php echo htmlspecialchars($attachment_url); ?>" target="_blank"><?php echo htmlspecialchars($attachment['file_name']); ?></a>
            <br />
            <small class="text-muted"><?php echo htmlspecialchars($attachment['filetype']); ?></small>
        </div>
        <div class="col-md-4 text-right">
            <?php if ($attachment['staffid'] == get_staff_user_id() || is_admin() || has_permission('accountplanning', '', 'edit')) { ?>
            <a href="#" class="text-danger" onclick="delete_invoice_attachment(<?php echo htmlspecialchars($attachment['id']); ?>); return false;"><i class="fa fa-times"></i></a>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<?php } ?>
<hr />
<div class="row attachments">
    <div class="attachment">
        <div class="col-md-5 mbot15">
            <div class="form-group">
                <label for="attachment" class="control-label">
                    <?php echo htmlspecialchars(_l('ticket_single_attachments')); ?>
                </label>
                <div class="input-group">
                    <input type="file" extension="<?php echo str_replace('.', '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-success add_more_attachments p8-half" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
</div>
<?php echo form_close(); ?>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<div class="modal fade" id="ap_add_goal_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open(admin_url('accountplanning/add_goal/' . $account->id)); ?>
            <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><?php echo _l('ap_goals_kpis'); ?></h4></div>
            <div class="modal-body">
                <?php echo render_input('name', 'ap_goal_name', '', 'text', ['required' => true]); ?>
                <?php echo render_input('target', 'ap_goal_target', '', 'number'); ?>
                <?php echo render_input('actual', 'ap_goal_actual', '', 'number'); ?>
                <?php echo render_input('due_date', 'ap_goal_due_date', '', 'date'); ?>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button><button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php } ?>
<div id="inv_file_data"></div>
<div class="modal fade" id="new_items" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo htmlspecialchars(_l('new_item')); ?></span>
                    <span class="edit-title"><?php echo htmlspecialchars(_l('edit_item')); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="item_hidden"></div>
                        <?php echo render_select('objective', $objectives, array('id', 'name'), 'objective'); ?>
                        <?php echo render_input('items_name', 'name_api'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></button>
                <a onclick="add_pic()" class="btn btn-success" data-dismiss="modal"><?php echo htmlspecialchars(_l('submit')); ?></a>
            </div>
        </div>
    </div>
</div>
<script>
if (typeof Handsontable !== "undefined") {
    Handsontable.licenseKey = "non-commercial-and-evaluation";
}
var hotElement = document.querySelector('#todo_list');
var hotSettings = {
    data: <?php echo json_encode($todo_list); ?>,
    columns: [
        { data: 'objective', wordWrap: true, type: 'text' },
        { data: 'item', wordWrap: true, type: 'text' },
        { data: 'action_needed', wordWrap: true, type: 'text' },
        { data: 'prioritization', editor: 'select', selectOptions: ['Low', 'Medium', 'High'] },
        {
            data: 'pic',
            renderer: customDropdownRenderer,
            editor: "chosen",
            width: 150,
            chosenOptions: {
                multiple: true,
                data: <?php echo json_encode($staff); ?>
            }
        },
        {
            data: 'deadline',
            type: 'date',
            dateFormat: 'YYYY-MM-DD',
            correctFormat: true,
            datePickerConfig: {
                firstDay: 0,
                showWeekNumber: true,
                numberOfMonths: 3
            }
        },
        { data: 'status', editor: 'select', selectOptions: ['Processing', 'Delay', 'Complete'] },
        { data: 'id', type: 'text' },
        { data: 'button', renderer: "html", readOnly: true }
    ],
    stretchH: 'all',
    autoWrapRow: true,
    rowHeights: 50,
    maxRows: 22,
    rowHeaders: true,
    colWidths: [150, 150, 150, 70, 150, 70, 70, 70, 60],
    colHeaders: [
        '<?php echo htmlspecialchars(_l('objective')); ?>',
        '<?php echo htmlspecialchars(_l('objective_items')); ?>',
        '<?php echo htmlspecialchars(_l('action_needed')); ?>',
        '<?php echo htmlspecialchars(_l('prioritization')); ?>',
        '<?php echo htmlspecialchars(_l('pic')); ?>',
        '<?php echo htmlspecialchars(_l('deadline')); ?>',
        '<?php echo htmlspecialchars(_l('status')); ?>',
        '',
        ''
    ],
    autoColumnSize: true,
    width: '100%',
    height: 500,
    dropdownMenu: true,
    mergeCells: true,
    contextMenu: true,
    manualRowMove: true,
    manualColumnMove: true,
    multiColumnSorting: { indicator: true },
    hiddenColumns: { columns: [7], indicators: true },
    filters: true,
    manualRowResize: true,
    manualColumnResize: true
};
var hot = new Handsontable(hotElement, hotSettings);
function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
    var selectedId;
    var optionsList = cellProperties.chosenOptions.data;
    if (typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
        Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
        return td;
    }
    var values = (value + "").split("|");
    value = [];
    for (var index = 0; index < optionsList.length; index++) {
        if (values.indexOf(optionsList[index].id + "") > -1) {
            selectedId = optionsList[index].id;
            value.push(optionsList[index].label);
        }
    }
    value = value.join(", ");
    Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
    return td;
}
</script>