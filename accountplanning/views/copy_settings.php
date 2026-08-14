<!-- Copy Project -->
<div class="modal fade" id="copy_accountplanning" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('accountplanning/copy/'.(isset($project) ? $project->id : '')),array('id'=>'copy_form','data-copy-url'=>admin_url('accountplanning/copy/'))); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <?php echo htmlspecialchars(_l('copy_accountplanning')); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('subject', 'launching_subject'); ?>
                        <?php 
                      $date = getdate();
                      $date_1 = mktime(0, 0, 0, $date['mon'], 1, $date['year']);
                      $value = date('d/m/Y', $date_1);
                      ?>
                      <?php 
                      echo render_select('date', $month,array('id','name'), 'time', $value); ?>
                        <div class="form-group select-placeholder" id="rel_id_wrapper">
                        <label for="client_id" class="control-label"><span class="text-danger">* </span><?php echo htmlspecialchars(_l('client')); ?></label>
                        <select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo htmlspecialchars(_l('dropdown_non_selected_tex')); ?>" required></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></button>
                <button type="submit" data-form="#copy_form" autocomplete="off" data-loading-text="<?php echo htmlspecialchars(_l('wait_text')); ?>"  class="btn btn-info"><?php echo htmlspecialchars(_l('copy_accountplanning')); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Copy Project end -->
<script>
// Copy project modal and set url if ID is passed manually eq from project list area
function copy_accountplanning(id, client_id, name = '', subject = '') {
    $('#copy_accountplanning').modal('show');

    if (typeof(id) != 'undefined') {
        $('#copy_form').attr('action', $('#copy_form').data('copy-url') + id);
    }
    $('input[name="subject"]').val(subject);

    $('select[name="client_id"]').append('<option value="'+client_id+'">'+name+'</option>');
    $('select[name="client_id"]').selectpicker('val',client_id).change();
    $('select[name="client_id"]').selectpicker('refresh');
}

</script>