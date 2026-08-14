	<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<h5 class="no-margin font-bold h5-color"><?php echo _l('recruitment_proposal') ?></h5>
		<hr class="hr-color">
	</div>
</div>
<div class="form-group">
	<div class="checkbox checkbox-primary no-mtop">
		<input onchange="recruitment_campaign_setting(this); return false" type="checkbox" id="recruitment_create_campaign_with_plan" name="purchase_setting[recruitment_create_campaign_with_plan]" <?php if(get_recruitment_option('recruitment_create_campaign_with_plan') == 1 ){ echo 'checked';} ?> value="recruitment_create_campaign_with_plan">
		<label for="recruitment_create_campaign_with_plan"><?php echo _l('create_recruitment_campaign_not_create_plan'); ?>

		<a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('recruitment_campaign_setting_tooltip'); ?>"></i>
		</a>
	</label>
</div>
</div>

<div class="row">
	<div class="col-md-12">
		<h5 class="no-margin font-bold h5-color"><?php echo _l('recruitment_campaign') ?></h5>
		<hr class="hr-color">
	</div>
</div>
<div class="form-group">
	<div class="checkbox checkbox-primary no-mtop">
		<input onchange="recruitment_campaign_setting(this); return false" type="checkbox" id="display_quantity_to_be_recruited" name="purchase_setting[display_quantity_to_be_recruited]" <?php if(get_recruitment_option('display_quantity_to_be_recruited') == 1 ){ echo 'checked';} ?> value="display_quantity_to_be_recruited">
		<label for="display_quantity_to_be_recruited"><?php echo _l('Display_the_Quantity_to_be_recruited_on_the_Recruitment_Portal'); ?>

		<a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('Display_the_Quantity_to_be_recruited_on_the_Recruitment_Portal'); ?>"></i>
		</a>
	</label>
</div>
</div>

<div class="row">
	<div class="col-md-12">
		<h5 class="no-margin font-bold h5-color"><?php echo _l('new_candidate_lable') ?></h5>
		<hr class="hr-color">
	</div>
</div>
<div class="form-group">
	<div class="checkbox checkbox-primary no-mtop">
		<input onchange="recruitment_campaign_setting(this); return false" type="checkbox" id="send_email_welcome_for_new_candidate" name="purchase_setting[send_email_welcome_for_new_candidate]" <?php if(get_recruitment_option('send_email_welcome_for_new_candidate') == 1 ){ echo 'checked';} ?> value="send_email_welcome_for_new_candidate">
		<label for="send_email_welcome_for_new_candidate"><?php echo _l('send_email_welcome_for_new_candidate_label'); ?>
	</label>
</div>
</div>



<?php echo form_open_multipart(admin_url('recruitment/prefix_number'),array('class'=>'prefix_number','autocomplete'=>'off')); ?>
<div class="row">
	<div class="col-md-12">
		<h5 class="no-margin font-bold h5-color"><?php echo _l('candidate_code') ?></h5>
		<hr class="hr-color">
	</div>
</div>

<div class="form-group">
	<label><?php echo _l('re_candidate_code_prefix'); ?></label>
	<div  class="form-group" app-field-wrapper="candidate_code_prefix">
		<input type="text" id="candidate_code_prefix" name="candidate_code_prefix" class="form-control" value="<?php echo get_option('candidate_code_prefix'); ?>"></div>
	</div>

	<div class="form-group">
		<label><?php echo _l('re_candidate_code_number'); ?></label>
		<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('re_next_number_tooltip'); ?>"></i>
		<div  class="form-group" app-field-wrapper="candidate_code_number">
			<input type="number" min="0" id="candidate_code_number" name="candidate_code_number" class="form-control" value="<?php echo get_option('candidate_code_number'); ?>">
		</div>

	</div>

	<div class="modal-footer">
		<?php if(has_permission('hrm_setting', '', 'create') || has_permission('hrm_setting', '', 'edit') ){ ?>
			<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
		<?php } ?>
	</div>
	<?php echo form_close(); ?>



<div class="clearfix"></div>


