<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-4 col-md-offset-4 text-center mbot15">
	<h1 class="text-uppercase register-heading"><?php echo _l('clients_register_heading'); ?></h1>
</div>
<div class="col-md-10 col-md-offset-1">
	<?php echo form_open('recruitment/authentication_candidate/register', ['id'=>'register-form']); ?>
	<div class="panel_s">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<h4 class="bold register-contact-info-heading"><?php echo _l('candidate_register_contact_info'); ?></h4>
					<div class="form-group register-firstname-group">
						<label class="control-label" for="candidate_name"><?php echo _l('clients_firstname'); ?></label>
						<input type="text" class="form-control" name="candidate_name" id="candidate_name" value="<?php echo set_value('candidate_name'); ?>">
						<?php echo form_error('candidate_name'); ?>
					</div>

					<div class="form-group register-lastname-group">
						<label class="control-label" for="last_name"><?php echo _l('clients_lastname'); ?></label>
						<input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>">
						<?php echo form_error('last_name'); ?>
					</div>
					
					<div class="form-group register-email-group">
						<label class="control-label" for="email"><?php echo _l('clients_email'); ?></label>
						<input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>">
						<?php echo form_error('email'); ?>
					</div>
					
					<div class="form-group register-contact-phone-group">
						<label class="control-label" for="phonenumber"><?php echo _l('clients_phone'); ?></label>
						<input type="text" class="form-control" name="phonenumber" id="phonenumber" value="<?php echo set_value('contact_phonenumber'); ?>">
					</div>
					
					<div class="form-group register-password-group">
						<label class="control-label" for="password"><?php echo _l('clients_register_password'); ?></label>
						<input type="password" class="form-control" name="password" id="password">
						<?php echo form_error('password'); ?>
					</div>
					<div class="form-group register-password-repeat-group">
						<label class="control-label" for="passwordr"><?php echo _l('clients_register_password_repeat'); ?></label>
						<input type="password" class="form-control" name="passwordr" id="passwordr">
						<?php echo form_error('passwordr'); ?>
					</div>

				</div>

			</div>

			<div class="row">
				<div class="col-md-12 text-center">
					<div class="form-group">
						<button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info"><?php echo _l('clients_register_string'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php echo form_close(); ?>
</div>
