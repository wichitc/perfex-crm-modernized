<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('ap_relations')); ?></h4>
<div class="clearfix"></div>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<?php $CI = &get_instance(); $has_relations = $CI->db->table_exists(db_prefix() . 'projects') || $CI->db->table_exists(db_prefix() . 'invoices') || $CI->db->table_exists(db_prefix() . 'estimates') || $CI->db->table_exists(db_prefix() . 'proposals'); ?>
<div class="row mbot15">
    <div class="col-md-12">
        <div class="btn-group">
            <?php if ($has_relations) { ?>
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?php echo _l('ap_add_relation'); ?> <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <?php if ($this->db->table_exists(db_prefix() . 'projects')) { ?>
                <li><a href="#" onclick="ap_add_relation_modal('project'); return false;"><i class="fa fa-folder"></i> <?php echo _l('project'); ?></a></li>
                <?php } ?>
                <?php if ($CI->db->table_exists(db_prefix() . 'invoices')) { ?>
                <li><a href="#" onclick="ap_add_relation_modal('invoice'); return false;"><i class="fa fa-file-text-o"></i> <?php echo _l('invoice'); ?></a></li>
                <?php } ?>
                <?php if ($CI->db->table_exists(db_prefix() . 'estimates')) { ?>
                <li><a href="#" onclick="ap_add_relation_modal('estimate'); return false;"><i class="fa fa-file-text"></i> <?php echo _l('estimate'); ?></a></li>
                <?php } ?>
                <?php if ($CI->db->table_exists(db_prefix() . 'proposals')) { ?>
                <li><a href="#" onclick="ap_add_relation_modal('proposal'); return false;"><i class="fa fa-file-o"></i> <?php echo _l('proposal'); ?></a></li>
                <?php } ?>
            </ul>
            <?php } ?>
            <?php if ($CI->db->table_exists(db_prefix() . 'projects')) { ?>
            <a href="<?php echo admin_url('accountplanning/quick_create_project/' . $account->id); ?>" class="btn btn-info"><i class="fa fa-plus"></i> <?php echo _l('ap_quick_create_project'); ?></a>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo _l('ap_relation_type'); ?></th>
                <th><?php echo _l('ap_relation_name'); ?></th>
                <?php if (has_permission('accountplanning', '', 'edit')) { ?><th width="80"></th><?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($relations)) { foreach ($relations as $rel) { ?>
            <tr>
                <td><?php echo ucfirst($rel['rel_type']); ?></td>
                <td><a href="<?php echo htmlspecialchars($rel['url']); ?>" target="_blank"><?php echo htmlspecialchars($rel['name']); ?></a></td>
                <?php if (has_permission('accountplanning', '', 'edit')) { ?>
                <td><a href="<?php echo admin_url('accountplanning/delete_relation/' . $rel['id'] . '/' . $account->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a></td>
                <?php } ?>
            </tr>
            <?php } } else { ?>
            <tr><td colspan="3" class="text-muted"><?php echo _l('ap_no_relations'); ?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php if (isset($project_tasks) && isset($project_milestones) && (!empty($project_tasks) || !empty($project_milestones))) { ?>
<div class="mtop15">
    <h5 class="bold"><?php echo _l('ap_linked_project_tasks'); ?></h5>
    <?php if (!empty($project_tasks)) { ?>
    <table class="table table-striped table-condensed">
        <thead><tr><th><?php echo _l('task'); ?></th><th><?php echo _l('deadline'); ?></th><th><?php echo _l('status'); ?></th><th width="60"></th></tr></thead>
        <tbody>
        <?php foreach ($project_tasks as $pt) { ?>
        <tr>
            <td><?php echo htmlspecialchars($pt['name']); ?></td>
            <td><?php echo isset($pt['duedate']) ? _d($pt['duedate']) : '-'; ?></td>
            <td><?php echo htmlspecialchars($pt['status_name'] ?? 'N/A'); ?></td>
            <td><a href="<?php echo htmlspecialchars($pt['url'] ?? '#'); ?>" class="btn btn-default btn-xs" target="_blank"><i class="fa fa-external-link"></i></a></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } ?>
    <?php if (!empty($project_milestones)) { ?>
    <h6 class="bold mtop15"><?php echo _l('ap_project_milestones'); ?></h6>
    <table class="table table-striped table-condensed">
        <thead><tr><th><?php echo _l('ap_milestone_name'); ?></th><th><?php echo _l('deadline'); ?></th></tr></thead>
        <tbody>
        <?php foreach ($project_milestones as $pm) { ?>
        <tr>
            <td><?php echo htmlspecialchars($pm['name'] ?? ''); ?></td>
            <td><?php echo isset($pm['due_date']) ? _d($pm['due_date']) : '-'; ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</div>
<?php } ?>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<div class="modal fade" id="ap_add_relation_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('ap_add_relation'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ap_rel_type" value="">
                <div class="form-group">
                    <label id="ap_rel_type_label"></label>
                    <select id="ap_rel_id" class="form-control selectpicker" data-live-search="true" data-width="100%"></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="button" class="btn btn-info" onclick="ap_save_relation(); return false;"><?php echo _l('submit'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>
var ap_account_id = <?php echo (int)$account->id; ?>;
function ap_add_relation_modal(type) {
    $('#ap_rel_type').val(type);
    $('#ap_rel_type_label').text(type.charAt(0).toUpperCase() + type.slice(1));
    $('#ap_rel_id').empty();
    var url = admin_url + 'accountplanning/get_relation_options/' + type + '/' + ap_account_id;
    $.getJSON(url).done(function(data) {
        var opts = data.options || [];
        $.each(opts, function(i, o) {
            $('#ap_rel_id').append($('<option>').val(o.id).text(o.name));
        });
        $('#ap_rel_id').selectpicker('refresh');
    });
    $('#ap_add_relation_modal').modal('show');
}
function ap_save_relation() {
    var type = $('#ap_rel_type').val();
    var rel_id = $('#ap_rel_id').val();
    if (!rel_id) { alert_float('warning', '<?php echo _l('ap_select_relation'); ?>'); return; }
    $.post(admin_url + 'accountplanning/add_relation', { accountplanning_id: ap_account_id, rel_type: type, rel_id: rel_id }).done(function(r) {
        if (r.success) {
            alert_float('success', r.message);
            window.location.reload();
        } else {
            alert_float('danger', r.message || '<?php echo _l('something_went_wrong'); ?>');
        }
    }).fail(function() { alert_float('danger', '<?php echo _l('something_went_wrong'); ?>'); });
}
</script>
<?php } ?>
