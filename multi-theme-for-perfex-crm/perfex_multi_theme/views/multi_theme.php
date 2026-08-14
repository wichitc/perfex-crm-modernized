<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="multi_theme" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open_multipart(admin_url('perfex_multi_theme/main/theme'), ['id' => 'multi-theme-form', ]); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
               <span class="edit-title"><?php echo _l('edit_multi_theme'); ?></span>
               <span class="add-title"><?php echo _l('new_multi_theme'); ?></span>
            </h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div id="additional"></div>
                  <?php echo render_input('theme_name', 'pmt_theme_name'); ?>
                  <?php echo render_color_picker('theme_color', _l('pmt_theme_color')); ?>
                  <?php echo render_input('bakground_image', 'pmt_theme_back_img', '', 'file'); ?>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#multi-theme-form'), {
      theme_name: 'required',
      theme_color: 'required'
    });
    $('#multi_theme').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#multi_theme input[name="theme_name"]').val('');
        $('#multi_theme input[name="theme_color"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});


// Edit status function which init the data to the modal
function edit_theme(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#multi_theme input[name="theme_name"]').val($(invoker).data('theme_name'));
    $('#multi_theme .colorpicker-input').colorpicker('setValue', $(invoker).data('theme_color'));
    $('#multi_theme').modal('show');
    $('.add-title').addClass('hide');
}

// function manage_theme_setting(form) {
// form.submit();
//    //  var data = $(form).serialize();
//    //  var url = form.action;
//    //  $.post(url, data).done(function (response) {
//    //      window.location.reload();
//    //  });
//    //  return false;
// }
</script>
