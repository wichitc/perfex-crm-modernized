<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('ap_meeting_notes')); ?></h4>
<div class="clearfix"></div>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<div class="row mbot15">
    <div class="col-md-12">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ap_meeting_note_modal"><i class="fa fa-plus"></i> <?php echo _l('ap_add_meeting_note'); ?></button>
    </div>
</div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo _l('ap_meeting_date'); ?></th>
                <th><?php echo _l('subject'); ?></th>
                <th><?php echo _l('ap_notes'); ?></th>
                <?php if (has_permission('accountplanning', '', 'edit')) { ?><th width="60"></th><?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($meeting_notes)) { foreach ($meeting_notes as $mn) { ?>
            <tr>
                <td><?php echo isset($mn['meeting_date']) ? _d($mn['meeting_date']) : '-'; ?></td>
                <td><?php echo htmlspecialchars($mn['subject'] ?? ''); ?></td>
                <td><?php echo nl2br(htmlspecialchars(substr($mn['notes'] ?? '', 0, 200))); ?><?php echo strlen($mn['notes'] ?? '') > 200 ? '...' : ''; ?></td>
                <?php if (has_permission('accountplanning', '', 'edit')) { ?>
                <td class="ap-actions-cell">
                    <button type="button" class="btn btn-default btn-icon ap-edit-meeting-note" data-id="<?php echo (int)$mn['id']; ?>"><i class="fa fa-pencil"></i></button>
                    <a href="<?php echo admin_url('accountplanning/delete_meeting_note/' . $mn['id'] . '/' . $account->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-trash"></i></a>
                </td>
                <?php } ?>
            </tr>
            <?php } } else { ?>
            <tr><td colspan="4" class="text-muted"><?php echo _l('ap_no_meeting_notes'); ?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php if (has_permission('accountplanning', '', 'edit')) { ?>
<div class="modal fade" id="ap_meeting_note_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open(admin_url('accountplanning/add_meeting_note/' . $account->id), ['id' => 'ap_meeting_note_form']); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="ap_meeting_note_modal_title"><?php echo _l('ap_add_meeting_note'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="edit_id" id="ap_meeting_note_edit_id" value="">
                <div class="form-group">
                    <label><?php echo _l('ap_meeting_date'); ?></label>
                    <input type="text" name="meeting_date" id="ap_meeting_note_date" class="form-control datepicker" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label><?php echo _l('subject'); ?></label>
                    <input type="text" name="subject" id="ap_meeting_note_subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><?php echo _l('ap_notes'); ?></label>
                    <textarea name="notes" id="ap_meeting_note_notes" class="form-control" rows="5"></textarea>
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
var ap_meeting_notes_data = <?php echo json_encode(array_map(function($mn){ return ['id'=>$mn['id'],'meeting_date'=>$mn['meeting_date']??'','subject'=>$mn['subject']??'','notes'=>$mn['notes']??'']; }, $meeting_notes ?? [])); ?>;
$(function(){
    $('#ap_meeting_note_modal').on('show.bs.modal', function(e){
        var btn = e.relatedTarget ? $(e.relatedTarget) : $();
        if (btn.length && btn.hasClass('ap-edit-meeting-note')) {
            var d = (ap_meeting_notes_data || []).find(function(x){ return x.id == btn.data('id'); });
            if (d) {
                $('#ap_meeting_note_modal_title').text('<?php echo _l('ap_edit_meeting_note'); ?>');
                $('#ap_meeting_note_edit_id').val(d.id);
                $('#ap_meeting_note_date').val(d.meeting_date);
                $('#ap_meeting_note_subject').val(d.subject);
                $('#ap_meeting_note_notes').val(d.notes);
                $('#ap_meeting_note_form').attr('action', '<?php echo admin_url('accountplanning/update_meeting_note/'); ?>' + d.id + '/<?php echo (int)$account->id; ?>');
            }
        } else {
            $('#ap_meeting_note_modal_title').text('<?php echo _l('ap_add_meeting_note'); ?>');
            $('#ap_meeting_note_edit_id').val('');
            $('#ap_meeting_note_date').val('<?php echo date('Y-m-d'); ?>');
            $('#ap_meeting_note_subject').val('');
            $('#ap_meeting_note_notes').val('');
            $('#ap_meeting_note_form').attr('action', '<?php echo admin_url('accountplanning/add_meeting_note/' . (int)$account->id); ?>');
        }
        if (typeof init_datepicker === 'function') init_datepicker($('#ap_meeting_note_modal'));
    });
    $('.ap-edit-meeting-note').on('click', function(){ $('#ap_meeting_note_modal').modal('show', {relatedTarget: this}); });
});
</script>
<?php } ?>
