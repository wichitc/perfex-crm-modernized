<?php init_head();?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12" id="small-table">
				<div class="panel_s">
					<div class="panel-body">
						<?php echo form_hidden('interview_id', $interview_id); ?>
						<div class="row">
							<div class="col-md-11">
								<h4 class="no-margin font-bold"><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo _l($title); ?></h4>
							</div>
							<div class="col-md-1 pull-right">
								<a href="#" class="btn btn-default pull-right btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view_interview_schedules('.interview_sm','#interview_sm_view'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
							</div>
						</div>
						<hr />
						<div class="row">
							<div class="col-md-12">
								<?php if (has_permission('recruitment', '', 'create') || is_admin()) {?>
									<a href="#" onclick="new_interview_schedule(); return false;" class="btn btn-info pull-left display-block"><?php echo _l('new_interview_schedule'); ?></a>
								<?php }?>

								<?php if (has_permission('recruitment', '', 'view') || is_admin()) {?>
									<a href="<?php echo admin_url('recruitment/calendar_interview_schedule'); ?>" class="btn btn-default pull-left display-block mleft5"><?php echo _l('calendar_view'); ?></a>
								<?php }?>
							</div>
						</div>

						<br>

						<div class="row">
							<div class="col-md-3"> <?php echo render_date_input('cp_from_date_filter', '', $from_date_filter
							, ['placeholder' => _l('from_date')]); ?></div>
							<div class="col-md-3"> <?php echo render_date_input('cp_to_date_filter', '', '', ['placeholder' => _l('to_date')]); ?></div>
							<?php if(is_admin()){ ?>
								<div class="col-md-3">
									<div class="form-group">
										<select name="cp_manager_filter[]" id="cp_manager_filter" class="selectpicker" multiple="true" data-actions-box="true" data-live-search="true" multiple="true" data-width="100%" data-none-selected-text="<?php echo _l('interviewer'); ?>">

											<?php foreach ($staffs as $s) {?>
												<option value="<?php echo new_html_entity_decode($s['staffid']); ?>"><?php echo new_html_entity_decode($s['firstname'] . ' ' . $s['lastname']); ?></option>
											<?php }?>
										</select>
									</div>
								</div>
							<?php } ?>
						</div>

						<br>
						<?php 
						$table_data = array(

							_l('interview_schedules_name'),
							_l('rec_time'),
							_l('interview_day'),
							_l('recruitment_campaign'),
							_l('candidate'),
							_l('interviewer'),
							_l('date_add'),
							_l('add_from'),
							_l('send_notify'),
						);
						$cf = get_custom_fields('interview',array('show_on_table'=>1));
						foreach($cf as $custom_field) {
							array_push($table_data,$custom_field['name']);
						}
						?>
						<?php render_datatable($table_data, 'table_interview', ['interview_sm' => 'interview_sm']);?>
					</div>
				</div>
			</div>
			<div class="col-md-7 small-table-right-col">
				<div id="interview_sm_view" class="hide">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="interview_schedules_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<?php echo form_open_multipart(admin_url('recruitment/interview_schedules'), array('id' => 'interview_schedule-form')); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="add-title"><?php echo _l('new_interview_schedule'); ?></span>
					<span class="edit-title"><?php echo _l('edit_interview_schedule'); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div id="additional_interview"></div>
					<div class="col-md-12">
						<h5 class="bold"><?php echo _l('general_infor') ?></h5>
						<hr class="margin-top-10"/>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="campaign"><?php echo _l('recruitment_campaign'); ?></label>
							<select onchange="campaign_change(); return false;" name="campaign" id="campaign" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
								<option value=""></option>
								<?php foreach ($rec_campaigns as $s) {?>
									<option value="<?php echo new_html_entity_decode($s['cp_id']); ?>" <?php if (isset($candidate) && $s['cp_id'] == $candidate->rec_campaign) {echo 'selected';}?>><?php echo new_html_entity_decode($s['campaign_code'] . ' - ' . $s['campaign_name']); ?></option>
								<?php }?>
							</select>
						</div>

					</div>
					<div class="col-md-4">
						<?php echo render_input('is_name', 'interview_schedules_name') ?>

					</div>
					<div class="col-md-4">

						<div class="form-group">
							<label for="position"><?php echo _l('position'); ?></label>
							<select name="position" id="position" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
								<option value=""></option>
								<?php foreach ($positions as $p) {?>
									<option value="<?php echo new_html_entity_decode($p['position_id']); ?>"><?php echo new_html_entity_decode($p['position_name']); ?></option>
								<?php }?>

							</select>
						</div>

					</div>

					<div class="col-md-4">
						<?php echo render_date_input('interview_day', 'interview_day'); ?>
					</div>
					<div class="col-md-4">
						<?php echo render_input('from_time', 'from_time', '', 'time'); ?>

					</div>

					<div class="col-md-4">
						<?php echo render_input('to_time', 'to_time', '', 'time'); ?>

					</div>
					<div class="col-md-12">
						
						<?php echo render_input('interview_location', 'interview_location', ''); ?>
					</div>

					<div class="col-md-12 form-group">
						<label for="interviewer"><span class="text-danger">* </span><?php echo _l('interviewer'); ?></label>
						<select name="interviewer[]" id="interviewer" class="selectpicker" multiple="true" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" required>

							<?php foreach ($staffs as $s) {?>
								<option value="<?php echo new_html_entity_decode($s['staffid']); ?>"><?php echo new_html_entity_decode($s['firstname'] . ' ' . $s['lastname']); ?></option>
							<?php }?>
						</select>
						<br><br>
					</div>
					<!-- render custom fields -->
                    <div class="col-md-12" id="custom_fields_items">
                      <?php echo render_custom_fields('interview'); ?>
                    </div>

					<div class="col-md-12">
						<h5 class="bold"><?php echo _l('list_of_candidates_participating'); ?></h5>
						<hr class="margin-top-10"/>
					</div>

					<div class="col-md-12">
						<div id="example"></div>
					</div>

					<div class="col-md-4"> <label for="candidate[0]"><span class="text-danger">* </span><?php echo _l('candidate'); ?></label> </div>
					<div class="col-md-3"> <label for="email"><?php echo _l('email').'/'._l('phonenumber'); ?></label> </div>
					<div class="col-md-4"> <label for="phonenumber"><?php echo _l('from_time').'/'._l('to_time'); ?></label> </div>

					<div class="list_candidates">

						<div class="row col-md-12" id="candidates-item">
							<div class="col-md-4 form-group">
								<select name="candidate[0]" onchange="candidate_infor_change(this); return false;" id="candidate[0]" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" required>
									<option value=""></option>
									<?php foreach ($candidates as $s) {?>
										<?php echo var_dump($candidates); ?>
										<option value="<?php echo new_html_entity_decode($s['id']); ?>"><?php echo new_html_entity_decode($s['candidate_code'] . ' ' . $s['candidate_name'] . ' ' . $s['last_name']); ?></option>
									<?php }?>
								</select>
							</div>

							<div class="col-md-4">

								<input type="text" disabled="true" name="email[0]" id="email[0]" class="form-control" />
							</div>

							<div class="col-md-3">
								<input type="text" disabled="true" name="phonenumber[0]" id="phonenumber[0]" class="form-control" />
							</div>
							<div class="col-md-4">
								<?php echo render_input('cd_from_time', 'from_time', '', 'time'); ?>
							</div>

							<div class="col-md-4">
								<?php echo render_input('cd_to_time', 'to_time', '', 'time'); ?>
							</div>

							<div class="col-md-1 lightheight-34-nowrap">
								<span class="input-group-btn pull-bot">
									<button name="add" class="btn new_candidates btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
								</span>
							</div>

						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="
				" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button id="sm_btn" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail();?>

</body>
</html>