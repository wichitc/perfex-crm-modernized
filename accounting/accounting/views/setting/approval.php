<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="_buttons">
	<a href="#" class="btn btn-info pull-left" onclick="new_approval_setting(); return false;"><?php echo _l('new_approval_setting'); ?></a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
	<thead>
		<th><?php echo _l('id'); ?></th>
		<th><?php echo _l('name'); ?></th>
		<th><?php echo _l('related'); ?></th>
		<th><?php echo _l('options'); ?></th>
	</thead>
	<tbody>
	<?php foreach($approval_setting as $value){ ?>
		<tr>
		   <td><?php echo html_escape($value['id']); ?></td>
		   <td><?php echo html_escape($value['name']); ?></td>
		   <td><?php echo _l($value['related']); ?></td>
		   <td>
		     <a href="#" onclick="edit_approval_setting(this,<?php echo html_escape($value['id']); ?>); return false" data-name="<?php echo html_escape($value['name']); ?>" data-related="<?php echo html_escape($value['related']); ?>" data-approval_type="<?php echo html_escape($value['approval_type']); ?>" data-setting='<?php echo html_escape($value['setting']); ?>' class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
		      <a href="<?php echo admin_url('accounting/delete_approval_setting/'.$value['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
		   </td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php
$hr_record_status = 0; 
if(get_status_modules_pur('hr_profile') == true){
	$hr_record_status = 1;
} ?>

<div class="modal fade" id="approval_setting_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog withd_1k" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<span class="edit-title"><?php echo _l('edit_approval_setting'); ?></span>
					<span class="add-title"><?php echo _l('new_approval_setting'); ?></span>
				</h4>
			</div>
			<?php echo form_open('accounting/approval_setting',array('id'=>'approval-setting-form')); ?>
			<?php echo form_hidden('approval_setting_id'); ?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<?php echo render_input('name','subject','','text'); ?>
						<?php $related = [ 
								0 => ['id' => 'claim', 'name' => _l('claims')],
								1 => ['id' => 'project_budget', 'name' => _l('project_budgets')],
								2 => ['id' => 'imprest', 'name' => _l('imprests')],
							]; ?>
						<?php echo render_select('related',$related,array('id','name'),'task_single_related'); ?>

						<div class="checkbox checkbox-primary">
					        <input type="checkbox" id="approval_type" name="approval_type" value="approval_type">
					        <label for="approval_type"><?php echo _l('only_1_person_needs_to_approve_the_transaction'); ?>

					        <a href="#" class="pull-right display-block input_method">&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" title="" data-original-title="<?php echo _l('approval_type_tooltip'); ?>"></i></a>
					        </label>
					    </div>

					    <hr>
					
					    <h4 >
							<span class=""><?php echo _l('pur_approvers'); ?></span>
						</h4>
				
						<div class="list_approve">
							<div id="item_approve">
                                <div class="col-md-11" style="padding-left: 0px; padding-right: 0px;">
                                <div class="col-md-<?php if($hr_record_status == 1){ echo '4'; }else{ echo '1'; } ?> <?php if($hr_record_status == 0){ echo 'hide'; } ?>">
                                	<div class="select-placeholder form-group">
		                                <label for="approver[0]"><?php echo _l('approver'); ?></label>
		                            <select name="approver[0]" id="approver[0]" class="selectpicker approver_class" data-id="0" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" required>
		                                <option value=""></option>
		                                <option value="direct_manager"><?php echo _l('direct_manager'); ?></option>
		                                <option value="head_of_department"><?php echo _l('department_manager'); ?></option>
		                                <option value="staff" <?php if($hr_record_status == 0){ echo 'selected'; } ?> ><?php echo _l('staff'); ?></option>
		                            </select>
		                           </div> 
                          		</div>
                          		<div class="col-md-<?php if($hr_record_status == 1){ echo '4'; }else{ echo '8'; } ?> <?php if($hr_record_status == 1){ echo 'hide'; } ?>" id="is_staff_0">
                                	<div class="select-placeholder form-group">
		                                <label for="staff[0]"><?php echo _l('staff'); ?></label>
		                            <select name="staff[0]" id="staff[0]" class="selectpicker" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true">
		                                <option value=""></option>
		                                <?php foreach($staffs as $val){
		                                 $selected = '';
		                                  ?>
		                              <option value="<?php echo html_escape($val['staffid']); ?>">
		                                 <?php echo html_escape($val['firstname'] . ' ' . $val['lastname']); ?>
		                              </option>
		                              <?php } ?>
		                            </select>
		                           </div> 
                          		</div>
                          		<div class="col-md-4">
                                	<div class="select-placeholder form-group">
		                                <label for="action[0]"><?php echo _l('action'); ?></label>
		                            <select name="action[0]" id="action[0]" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true">
		                                <option value="approve" selected><?php echo _l('approve'); ?></option>
		                                <option value="sign"><?php echo _l('sign'); ?></option>
		                            </select>
		                           </div> 
                          		</div>
                          		</div>
                                <div class="col-md-1 btn_apr">
                                <span class="pull-bot">
                                    <button name="add" class="btn new_vendor_requests btn-success" data-ticket="true" type="button" style="margin-top: 25px;"><i class="fa fa-plus"></i></button>
                                    </span>
                               </div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<script>
var addMoreVendorsInputKey = 1;
document.addEventListener("DOMContentLoaded", function() {
  "use strict";

  addMoreVendorsInputKey = jQuery('.list_approve select[name*="approver"]').length || 1;

  jQuery("body").on('click', '.new_vendor_requests', function() {
      if (jQuery(this).hasClass('disabled')) { return false; }
      
      var newattachment = jQuery('.list_approve').find('#item_approve').eq(0).clone().appendTo('.list_approve');
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
      
      // Update inline style for spacing if needed
      newattachment.find('button[name="add"]').css('margin-top', '25px');
      
      addMoreVendorsInputKey++;
  });

  jQuery("body").on('click', '.remove_vendor_requests', function() {
      jQuery(this).parents('#item_approve').remove();
  });

  jQuery("body").on('change', '.approver_class' , function() {
      var length = jQuery('.list_approve select[name*="approver"]').length;
      for (let i = 0; i < length; i++) {
          if (jQuery('select[name="approver['+i+']"]').val() == 'staff') {
              jQuery('#is_staff_' + i).removeClass('hide');
              jQuery('select[name="staff['+ i +']"]').attr('required', 'required');
          } else {
              jQuery('#is_staff_' + i).addClass('hide');
              jQuery('select[name="staff['+ i +']"]').removeAttr('required');
          }
      }
  });
});

function edit_approval_setting(invoker, id) {
  "use strict";
  appValidateForm(jQuery('#approval-setting-form'), {name: 'required', related: 'required'});

  var name = jQuery(invoker).data('name');
  var related = jQuery(invoker).data('related');
  var approval_type = jQuery(invoker).data('approval_type');
  
  jQuery('input[name="approval_setting_id"]').val(id);
  jQuery('#approval_setting_modal input[name="name"]').val(name);
  jQuery('select[name="related"]').val(related).change();
  
  if (approval_type == 1) {
      jQuery('#approval_type').prop('checked', true);
  } else {
      jQuery('#approval_type').prop('checked', false);
  }
  
  jQuery.post(admin_url + 'accounting/get_html_approval_setting/' + id).done(function(response) {
      response = JSON.parse(response);
      jQuery('.list_approve').html('');
      jQuery('.list_approve').append(response.html);
      init_selectpicker();
  });
  
  jQuery('#approval_setting_modal').modal('show');
  jQuery('#approval_setting_modal .add-title').addClass('hide');
  jQuery('#approval_setting_modal .edit-title').removeClass('hide');
}

function new_approval_setting() {
  "use strict";
  appValidateForm(jQuery('#approval-setting-form'), {name: 'required', related: 'required'});

  jQuery('input[name="approval_setting_id"]').val('');
  jQuery('#approval_setting_modal input[name="name"]').val('');
  jQuery('select[name="related"]').val('claim').change();
  jQuery('#approval_type').prop('checked', false);
  
  jQuery.post(admin_url + 'accounting/get_html_approval_setting').done(function(response) {
      response = JSON.parse(response);
      jQuery('.list_approve').html('');
      jQuery('.list_approve').append(response.html);
      init_selectpicker();
  });

  jQuery('#approval_setting_modal').modal('show');
  jQuery('#approval_setting_modal .add-title').removeClass('hide');
  jQuery('#approval_setting_modal .edit-title').addClass('hide');
}
</script>
