<script>

(function($) {
"use strict";

init_pur_ajax_search('vendors', '#vendor_id');



$('select[name="department"]').on('change', function(){

	var department = $(this).val();
	var requestor = '<?php echo (isset($faf) ? $faf->requestor : ''); ?>';

		$.post(admin_url+'purchase/requestor_list_by_department/'+department).done(function(response) {
			response = JSON.parse(response);

			$('select[name="requestor"]').html(response.html);
			$('select[name="requestor"]').selectpicker('refresh');

			$('select[name="requestor"]').val(requestor).change();

		});
	

});


<?php if(isset($faf)){ ?>

	$('select[name="department"]').val('<?php echo pur_html_entity_decode($faf->department); ?>').change();	
  $.post(admin_url + 'purchase/get_html_approval_setting_for_faf/'+ <?php echo pur_html_entity_decode($faf->id); ?>).done(function(response) {
     response = JSON.parse(response);

      $('.list_approve').html('');
      $('.list_approve').append(response);
  init_selectpicker();

  });
 <?php } ?>


var addMoreVendorsInputKey = $('.list_approve select[name*="approver"]').length;
 $("body").on('click', '.new_vendor_requests', function() {
   if ($(this).hasClass('disabled')) { return false; }
  
     addMoreVendorsInputKey = $('.list_approve select[name*="approver"]').length;
    var newattachment = $('.list_approve').find('#item_approve').eq(0).clone().appendTo('.list_approve');
    newattachment.find('button[data-toggle="dropdown"]').remove();
    newattachment.find('select').selectpicker('refresh');

    newattachment.find('button[data-id="approver[0]"]').attr('data-id', 'approver[' + addMoreVendorsInputKey + ']');
    newattachment.find('label[for="approver[0]"]').attr('for', 'approver[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[name="approver[0]"]').attr('name', 'approver[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[id="approver[0]"]').attr('id', 'approver[' + addMoreVendorsInputKey + ']').selectpicker('refresh');
    newattachment.find('select[data-id="0"]').attr('data-id', addMoreVendorsInputKey);

    newattachment.find('button[data-id="staff[0]"]').attr('data-id', 'staff[' + addMoreVendorsInputKey + ']');
    newattachment.find('label[for="staff[0]"]').attr('for', 'staff[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[name="staff[0]"]').attr('name', 'staff[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[id="staff[0]"]').attr('id', 'staff[' + addMoreVendorsInputKey + ']').selectpicker('refresh');

    newattachment.find('button[data-id="action[0]"]').attr('data-id', 'action[' + addMoreVendorsInputKey + ']');
    newattachment.find('label[for="action[0]"]').attr('for', 'action[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[name="action[0]"]').attr('name', 'action[' + addMoreVendorsInputKey + ']');
    newattachment.find('select[id="action[0]"]').attr('id', 'action[' + addMoreVendorsInputKey + ']').selectpicker('refresh');

    newattachment.find('#is_staff_0').attr('id', 'is_staff_' + addMoreVendorsInputKey);
    newattachment.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    newattachment.find('button[name="add"]').removeClass('new_vendor_requests').addClass('remove_vendor_requests').removeClass('btn-success').addClass('btn-danger');

    $('select[name="approver[' + addMoreVendorsInputKey + ']"]').on('change', function(){
      let index = $(this).attr('data-id');
      if($(this).val() == 'staff'){
        $('#is_staff_' + index).removeClass('hide');
        $('select[name="staff['+ index +']"').attr('required', 'required');
      }else{
        $('#is_staff_' + index).addClass('hide');
        $('select[name="staff['+ index +']"').removeAttr('required');
      }
    });

    addMoreVendorsInputKey++;

});
$("body").on('click', '.remove_vendor_requests', function() {
    $(this).parents('#item_approve').remove();
});

$('body').on('change', '.approver_class' , function(){
    addMoreVendorsInputKey = $('.list_approve select[name*="approver"]').length;

    for(let i = 0; i < addMoreVendorsInputKey; i++){
      if($('select[name="approver['+i+']"]').val() == 'staff'){
        $('#is_staff_' + i).removeClass('hide');
        $('select[name="staff['+ i +']"').attr('required', 'required');
    }else{
        $('#is_staff_' + i).addClass('hide');
        $('select[name="staff['+ i +']"').removeAttr('required');
    }
    }
});

})(jQuery);
	


//preview faf_request attachment
function preview_faf_request_btn(invoker){
  "use strict"; 
    var id = $(invoker).attr('id');
    var rel_id = $(invoker).attr('rel_id');
    view_faf_request_file(id, rel_id);
}

function view_faf_request_file(id, rel_id) {
  "use strict"; 
      $('#faf_request_file_data').empty();
      $("#faf_request_file_data").load(admin_url + 'purchase/file_faf_request/' + id + '/' + rel_id, function(response, status, xhr) {
          if (status == "error") {
              alert_float('danger', xhr.statusText);
          }
      });
}
function close_modal_preview(){
  "use strict"; 
 $('._project_file').modal('hide');
}

function delete_faf_request_attachment(id) {
  "use strict"; 
    if (confirm_delete()) {
        requestGet('purchase/delete_faf_request_attachment/' + id).done(function(success) {
            
                $("#faf_request_pv_file").find('[data-attachment-id="' + id + '"]').remove();
            
        }).fail(function(error) {
            alert_float('danger', error.responseText);
        });
    }
  }	

</script>