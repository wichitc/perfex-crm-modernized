<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="bold"><?php echo _l('ap_templates'); ?></h4>
                <a href="<?php echo admin_url('accountplanning'); ?>" class="btn btn-default mright5"><?php echo _l('back'); ?></a>
                <?php if (has_permission('accountplanning', '', 'create')) { ?>
                <button type="button" class="btn btn-info" onclick="ap_edit_template(0);"><i class="fa fa-plus"></i> <?php echo _l('new'); ?></button>
                <?php } ?>
                <div class="panel_s mtop15">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th><?php echo _l('name'); ?></th>
                                    <th><?php echo _l('subject'); ?></th>
                                    <th class="ap-template-actions-col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($templates as $t) { ?>
                                <tr>
                                    <td><?php echo $t['id']; ?></td>
                                    <td><?php echo htmlspecialchars($t['name']); ?></td>
                                    <td><?php echo htmlspecialchars($t['subject']); ?></td>
                                    <td class="ap-template-actions">
                                        <button type="button" class="btn btn-default btn-sm" onclick="ap_edit_template(<?php echo (int)$t['id']; ?>);"><i class="fa fa-edit"></i></button>
                                        <a href="<?php echo admin_url('accountplanning/create_from_template/' . $t['id']); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> <?php echo _l('ap_create_from_template'); ?></a>
                                        <?php if (has_permission('accountplanning', '', 'delete')) { ?>
                                        <a href="<?php echo admin_url('accountplanning/delete_template/' . $t['id']); ?>" class="btn btn-danger btn-sm _delete"><i class="fa fa-remove"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if (empty($templates)) { ?>
                                <tr><td colspan="4" class="text-muted"><?php echo _l('ap_no_templates'); ?></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ap_template_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open(admin_url('accountplanning/save_template'), ['id' => 'ap_template_form']); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('ap_templates'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="ap_template_id" value="">
                <?php echo render_input('name', 'name', '', 'text', ['required' => true]); ?>
                <?php echo render_input('subject', 'subject', ''); ?>
                <?php echo render_textarea('vision', 'vision', ''); ?>
                <?php echo render_textarea('mission', 'mission', ''); ?>
                <?php echo render_textarea('objectives', 'objectives', ''); ?>
                <?php echo render_textarea('threat', 'threat', ''); ?>
                <?php echo render_textarea('opportunity', 'opportunity', ''); ?>
                <?php echo render_textarea('criteria_to_success', 'criteria_to_success', ''); ?>
                <?php echo render_textarea('constraints', 'constraints', ''); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
function ap_edit_template(id) {
    $('#ap_template_id').val(id || '');
    if (id) {
        $.get(admin_url + 'accountplanning/get_template/' + id).done(function(t) {
            if (t) {
                $('input[name="name"]').val(t.name);
                $('input[name="subject"]').val(t.subject);
                $('textarea[name="vision"]').val(t.vision);
                $('textarea[name="mission"]').val(t.mission);
                $('textarea[name="objectives"]').val(t.objectives);
                $('textarea[name="threat"]').val(t.threat);
                $('textarea[name="opportunity"]').val(t.opportunity);
                $('textarea[name="criteria_to_success"]').val(t.criteria_to_success);
                $('textarea[name="constraints"]').val(t.constraints);
            }
        });
    } else {
        $('#ap_template_form')[0].reset();
        $('#ap_template_id').val('');
    }
    $('#ap_template_modal').modal('show');
}
</script>
</body>
</html>
