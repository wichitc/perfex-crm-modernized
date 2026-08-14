<?php echo form_hidden('_attachment_sale_id',$intv_sch->id); ?>

<div class="panel_s">
	<div class="panel-body">
		
		<div class="horizontal-scrollable-tabs preview-tabs-top">
			<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
			<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
			<div class="horizontal-tabs">
				<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
					<li role="presentation" class="active">
						<a href="#tab_estimate" aria-controls="tab_estimate" role="tab" data-toggle="tab">
							<?php echo _l('general_infor'); ?>
						</a>
					</li>  

					<li role="presentation" >
						<a href="#tab_activilog" class="tab_activilog" aria-controls="tab_activilog" role="tab" data-toggle="tab">
							<?php echo _l('rec_interview_comments'); ?>
						</a>
					</li>

				</ul>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane ptop10 active" id="tab_estimate">

				<div class="col-md-12">
					<table class="table border table-striped margin-top-0">
						<tbody>
							<tr class="project-overview">
								<td class="bold" width="30%"><?php echo _l('interview_schedules_name'); ?></td>
								<td><?php echo new_html_entity_decode($intv_sch->is_name); ?></td>
							</tr>
							<tr class="project-overview">
								<td class="bold" width="30%"><?php echo _l('rec_time'); ?></td>
								<?php
								$from_hours_format = '';
								$to_hours_format = '';

								$from_hours = _dt($intv_sch->from_hours);
								$from_hours = explode(" ", $from_hours);
								foreach ($from_hours as $key => $value) {
									if ($key != 0) {
										$from_hours_format .= $value;
									}
								}

								$to_hours = _dt($intv_sch->to_hours);
								$to_hours = explode(" ", $to_hours);
								foreach ($to_hours as $key => $value) {
									if ($key != 0) {
										$to_hours_format .= $value;
									}
								}

								?>
								<td><?php echo new_html_entity_decode($from_hours_format . ' - ' . $to_hours_format); ?></td>
							</tr>
							<tr class="project-overview">
								<td class="bold"><?php echo _l('interview_day'); ?></td>
								<td><?php echo _d($intv_sch->interview_day); ?></td>
							</tr>
							<tr class="project-overview">
								<td class="bold"><?php echo _l('recruitment_campaign'); ?></td>
								<td><?php $cp = get_rec_campaign_hp($intv_sch->campaign);
								if (isset($cp)) {
									$_data = $cp->campaign_code . ' - ' . $cp->campaign_name;
								} else {
									$_data = '';
								}
								echo new_html_entity_decode($_data);?></td>
							</tr>

							<tr class="project-overview">
								<td class="bold"><?php echo _l('date_add'); ?></td>
								<td>
									<?php echo _d($intv_sch->added_date); ?>
								</td>
							</tr>

							<tr class="project-overview">
								<td class="bold"><?php echo _l('interviewer'); ?></td>
								<td><?php
								$inv = new_explode(',', $intv_sch->interviewer);
								$ata = '';
								foreach ($inv as $iv) {
									$ata .= '<a href="' . admin_url('staff/profile/' . $iv) . '">' . staff_profile_image($iv, [
										'staff-profile-image-small mright5',
									], 'small', [
										'data-toggle' => 'tooltip',
										'data-title' => get_staff_full_name($iv),
									]) . '</a>';
								}
								echo new_html_entity_decode($ata);
							?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12">
				<div id="p_custom_fields_items">
					<?php echo render_custom_fields('interview', $intv_sch->id); ?>
				</div>
			</div>


			<div class="col-md-12">
				<h4 class="isp-general-infor"><?php echo _l('list_of_candidates_participating') ?></h4>
				<hr class="isp-general-infor-hr" />
			</div>
			<?php foreach ($intv_sch->list_candidate as $cd) {?>
				<div class="col-md-6">
					<div class="col-md-12">
						<div class="row">
							<div class="thumbnail">
								<div class="caption" onclick="location.href='<?php echo admin_url('recruitment/candidate/' . $cd['candidate']) ?>'">

									<h4 id="thumbnail-label"><?php echo candidate_profile_image($cd['candidate'], ['staff-profile-image-small mright5'], 'small') . ' #' . $cd['candidate_code'] . ' - ' . $cd['candidate_name'] . ' ' . $cd['last_name']; ?></h4>

									<p><?php echo _l('email') . ': ' . $cd['email']; ?></p>

									<div class="thumbnail-description smaller"><?php echo _l('phonenumber') . ': ' . $cd['phonenumber']; ?></div>

								</div>
							</div>
						</div>
					</div>
				</div>
			<?php }?>

		</div>

		<div role="tabpanel" class="tab-pane" id="tab_activilog">

			<div class="panel_s no-shadow">
				<div class="activity-feed">
					<?php foreach($activity_log as $log){ ?>
						<div class="feed-item">
							<div class="date">
								<span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($log['date']); ?>">
									<?php echo time_ago($log['date']); ?>
								</span>
								<?php if($log['staffid'] == get_staff_user_id() || is_admin() || has_permission('recruitment','','delete')){ ?>
									<a href="#" class="pull-right text-danger" onclick="delete_wh_activitylog(this,<?php echo new_html_entity_decode($log['id']); ?>);return false;"><i class="fa fa fa-times"></i></a>
								<?php } ?>
							</div>
							<div class="text">
								<?php if($log['staffid'] != 0){ ?>
									<a href="<?php echo admin_url('profile/'.$log["staffid"]); ?>">
										<?php echo staff_profile_image($log['staffid'],array('staff-profile-xs-image pull-left mright5'));
										?>
									</a>
									<?php
								}
								$additional_data = '';
								if(!empty($log['additional_data'])){
									$additional_data = unserialize($log['additional_data']);
									echo ($log['staffid'] == 0) ? _l($log['description'],$additional_data) : $log['full_name'] .' - '._l($log['description'],$additional_data);
								} else {
									echo new_html_entity_decode($log['full_name']) . ' - ';
									echo _l($log['description']);
								}
								?>
							</div>

						</div>
					<?php } ?>
				</div>
				<div class="col-md-12">
					<?php echo render_textarea('wh_activity_textarea','','',array('placeholder'=>_l('rec_interview_comments')),array(),'mtop15'); ?>
					<div class="text-right">
						<button id="wh_enter_activity" class="btn btn-info"><?php echo _l('submit'); ?></button>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>

		</div>

	</div>

</div>
<?php require 'modules/recruitment/assets/js/interview_schedules/view_interview_schedule_js.php';?>
