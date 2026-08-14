<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('ap_competitors')); ?></h4>
<div class="clearfix"></div>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<div class="row mbot15">
    <div class="col-md-12">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ap_competitor_modal"><i class="fa fa-plus"></i> <?php echo _l('ap_add_competitor'); ?></button>
    </div>
</div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo _l('ap_competitor_name'); ?></th>
                <th><?php echo _l('ap_threat_level'); ?></th>
                <th><?php echo _l('ap_notes'); ?></th>
                <?php if (has_permission('accountplanning', '', 'edit')) { ?><th width="60"></th><?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($competitors)) { foreach ($competitors as $c) { ?>
            <tr>
                <td><?php echo htmlspecialchars($c['name'] ?? ''); ?></td>
                <td><span class="label label-<?php echo ($c['threat_level'] ?? '') == 'High' ? 'danger' : (($c['threat_level'] ?? '') == 'Medium' ? 'warning' : 'default'); ?>"><?php echo htmlspecialchars($c['threat_level'] ?? '-'); ?></span></td>
                <td><?php echo nl2br(htmlspecialchars(substr($c['notes'] ?? '', 0, 150))); ?><?php echo strlen($c['notes'] ?? '') > 150 ? '...' : ''; ?></td>
                <?php if (has_permission('accountplanning', '', 'edit')) { ?>
                <td class="ap-actions-cell">
                    <button type="button" class="btn btn-default btn-icon ap-edit-competitor" data-id="<?php echo (int)$c['id']; ?>"><i class="fa fa-pencil"></i></button>
                    <a href="<?php echo admin_url('accountplanning/delete_competitor/' . $c['id'] . '/' . $account->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-trash"></i></a>
                </td>
                <?php } ?>
            </tr>
            <?php } } else { ?>
            <tr><td colspan="4" class="text-muted"><?php echo _l('ap_no_competitors'); ?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<div class="modal fade" id="ap_competitor_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open(admin_url('accountplanning/add_competitor/' . $account->id), ['id' => 'ap_competitor_form']); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="ap_competitor_modal_title"><?php echo _l('ap_add_competitor'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label><?php echo _l('ap_competitor_name'); ?></label>
                    <input type="text" name="name" id="ap_competitor_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><?php echo _l('ap_threat_level'); ?></label>
                    <select name="threat_level" id="ap_competitor_threat_level" class="form-control">
                        <option value="">-</option>
                        <option value="Low"><?php echo _l('ap_threat_low'); ?></option>
                        <option value="Medium"><?php echo _l('ap_threat_medium'); ?></option>
                        <option value="High"><?php echo _l('ap_threat_high'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo _l('ap_notes'); ?></label>
                    <textarea name="notes" id="ap_competitor_notes" class="form-control" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
var ap_competitors_data = <?php echo json_encode(array_map(function($c){ return ['id'=>$c['id'],'name'=>$c['name']??'','threat_level'=>$c['threat_level']??'','notes'=>$c['notes']??'']; }, $competitors ?? [])); ?>;
$(function(){
    $('#ap_competitor_modal').on('show.bs.modal', function(e){
        var btn = e.relatedTarget ? $(e.relatedTarget) : $();
        if (btn.length && btn.hasClass('ap-edit-competitor')) {
            var d = (ap_competitors_data || []).find(function(x){ return x.id == btn.data('id'); });
            if (d) {
                $('#ap_competitor_modal_title').text('<?php echo _l('ap_edit_competitor'); ?>');
                $('#ap_competitor_name').val(d.name);
                $('#ap_competitor_threat_level').val(d.threat_level);
                $('#ap_competitor_notes').val(d.notes);
                $('#ap_competitor_form').attr('action', '<?php echo admin_url('accountplanning/update_competitor/'); ?>' + d.id + '/<?php echo (int)$account->id; ?>');
            }
        } else {
            $('#ap_competitor_modal_title').text('<?php echo _l('ap_add_competitor'); ?>');
            $('#ap_competitor_name').val('');
            $('#ap_competitor_threat_level').val('');
            $('#ap_competitor_notes').val('');
            $('#ap_competitor_form').attr('action', '<?php echo admin_url('accountplanning/add_competitor/' . (int)$account->id); ?>');
        }
    });
    $('.ap-edit-competitor').on('click', function(){ $('#ap_competitor_modal').modal('show', {relatedTarget: this}); });
});
</script>
<?php } ?>
