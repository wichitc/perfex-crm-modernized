<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row section-heading section-profile">
	<div class="col-md-8">
		<?php echo form_open_multipart('recruitment/recruitment_portal/profile',array('autocomplete'=>'off')); ?>
		<?php echo form_hidden('profile',true); ?>
		<div class="panel_s">
			<div class="panel-body">
				<h4 class="no-margin section-text"><?php echo _l('clients_profile_heading'); ?></h4>
			</div>
		</div>
		<?php hooks()->do_action('before_client_profile_form_loaded'); ?>
		<div class="panel_s">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<h4 class="text-danger"><?php echo _l('file_campaign'); ?></h4>
						<?php echo render_input('file', '', '', 'file') ?>

						<div class="row">
							<div id="contract_attachments" class="col-md-12">
								<?php if(isset($csv)){ ?>

									<?php
									$data = '<div class="row" id="attachment_file">';
									foreach($csv as $attachment) {
										$href_url = site_url('modules/hr_profile/uploads/contracts/'.$attachment['rel_id'].'/'.$attachment['file_name']).'" download';
										if(!empty($attachment['external'])){
											$href_url = $attachment['external_link'];
										}
										$data .= '<div class="display-block contract-attachment-wrapper">';
										$data .= '<div class="col-md-10">';
										$data .= '<div class="col-md-1 mr-5 hide">';
										$data .= '<a name="preview-btn" onclick="preview_candidate_btn(this); return false;" rel_id = "'.$attachment['rel_id'].'" id = "'.$attachment['id'].'" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left" data-toggle="tooltip" title data-original-title="'._l("preview_file").'">';
										$data .= '<i class="fa fa-eye"></i>'; 
										$data .= '</a>';
										$data .= '</div>';
										$data .= '<div class=col-md-9>';
										$data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
										$data .= '<a href="'.$href_url.'>'.$attachment['file_name'].'</a>';
										$data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
										$data .= '</div>';
										$data .= '</div>';
										$data .= '<div class="col-md-2 text-right">';
										$data .= '<a href="'.site_url('recruitment/recruitment_portal/remove_candidate_cv/'.$attachment['id']) .'" class="text-danger" ><i class="fa fa fa-times"></i></a>';
										$data .= '</div>';
										$data .= '<div class="clearfix"></div><hr/>';
										$data .= '</div>';
									}
									$data .= '</div>';
									echo new_html_entity_decode($data);
									?>
								<?php } ?>
								<!-- check if edit contract => display attachment file end-->

							</div>

							<div id="contract_file_data"></div>
						</div>

						<h4 class="text-danger"><?php echo _l('re_contact_information'); ?></h4>
						<div class="form-group">
							<?php if($candidate->avar == NULL){ ?>
								<div class="form-group profile-image-upload-group">
									<label for="profile_image" class="profile-image"><?php echo _l('client_profile_image'); ?></label>
									<input type="file" name="cd_avar" class="form-control" id="cd_avar">
								</div>
							<?php } ?>
							<?php if($candidate->avar != NULL){ ?>
								<div class="form-group profile-image-group">
									<div class="row">
										<div class="col-md-9">
											<?php echo candidate_profile_image(get_candidate_id(),[
												'client-profile-image-thumb',
											], 'small', ['data-toggle' => 'tooltip', 'data-title' => get_candidate_name(get_candidate_id()), 'data-placement' => 'bottom' ]); ?>

										</div>
										<div class="col-md-3 text-right">
											<a href="<?php echo site_url('recruitment/recruitment_portal/remove_profile_image/'.$candidate->avar->id); ?>"><i class="fa fa-remove text-danger"></i></a>
										</div>
									</div>
								</div>
							<?php } ?>

						</div>
						<div class="form-group profile-firstname-group">
							<label for="candidate_name"><?php echo _l('clients_firstname'); ?></label>
							<input type="text" class="form-control" name="candidate_name" id="candidate_name" value="<?php echo set_value('candidate_name',$candidate->candidate_name); ?>">
							<?php echo form_error('candidate_name'); ?>
						</div>
						<div class="form-group profile-lastname-group">
							<label for="lastname"><?php echo _l('clients_lastname'); ?></label>
							<input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo set_value('last_name',$candidate->last_name); ?>">
							<?php echo form_error('last_name'); ?>
						</div>
						
						<div class="form-group profile-email-group">
							<label for="email"><?php echo _l('clients_email'); ?></label>
							<input type="email" name="email" class="form-control" id="email" value="<?php echo new_html_entity_decode($candidate->email); ?>" disabled>
							<?php echo form_error('email'); ?>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group profile-phone-group">
									<label for="phonenumber"><?php echo _l('clients_phone'); ?></label>
							<input type="email" name="email" class="form-control" id="email" value="<?php echo new_html_entity_decode($candidate->email; ?>" disabled>
									<input type="text" class="form-control" name="phonenumber" id="phonenumber" value="<?php echo new_html_entity_decode($candidate->phonenumber); ?>">
								</div>
							</div>
							<div class="col-md-6">
								<?php $alternate_contact_number = (isset($candidate) ? $candidate->alternate_contact_number : '');
								echo render_input('alternate_contact_number', 'alternate_contact_number', $alternate_contact_number);?>
							</div>
						</div>


						<div class="row">
							<div class="col-md-6">
								<?php $birthday = (isset($candidate) ? _d($candidate->birthday) : '');
								echo render_date_input('birthday', 'birthday', $birthday)?>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="gender"><?php echo _l('gender'); ?></label>
									<select name="gender" id="gender" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
										<option value=""></option>
										<option value="male" <?php if (isset($candidate) && $candidate->gender == 'male') {echo 'selected';}?>><?php echo _l('male'); ?></option>
										<option value="female" <?php if (isset($candidate) && $candidate->gender == 'female') {echo 'selected';}?>><?php echo _l('female'); ?></option>
									</select>
									<br><br>
								</div>
							</div>
							<div class="col-md-12">
								<?php $arrAtt = array();
								$arrAtt['data-type'] = 'currency';
								$desired_salary = (isset($candidate) ? app_format_money((float)$candidate->desired_salary, '') : '');
								?>

								<div class="form-group">
									<label><?php echo _l('desired_salary'); ?></label>
									<div class="input-group">
										<input type="text" class="form-control text-right" name="desired_salary" value="<?php echo new_html_entity_decode($desired_salary) ?>" data-type="currency">

										<div class="input-group-addon">
											<div class="dropdown">
												<span class="discount-type-selected">
													<?php echo new_html_entity_decode($base_currency->name) ;?>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion" id="accordionExample">
							<div class="card mbot20">
								<div class="card-header" id="headingOne">
									<h4 data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="cursor_pointer text-danger">
										<?php echo _l('re_summary'); ?><span class="caret pull-right"></span>
									</h4>
								</div>

								<div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
									<div class="card-body">
										<?php $introduce_yourself = (isset($candidate) ? $candidate->introduce_yourself : '');
										$rows=[];
										$rows['rows'] = 6;
										echo render_textarea('introduce_yourself', 'introduce_yourself', $introduce_yourself, $rows)?>
									</div>
								</div>
							</div>
							
							<div class="card mbot20">
								<div class="card-header" id="headingThree">
									<h4  data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="cursor_pointer text-danger">
										<?php echo _l('re_work_experience'); ?><span class="caret pull-right"></span>
									</h4>
								</div>
								<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
									<div class="card-body ">
										<div class="work_experience">
											<?php if (isset($candidate) && count($candidate->work_experience) > 0) {
												foreach ($candidate->work_experience as $key => $val) {
													?>
													<div class="row mbot20" id="work_experience-item">
														<div class="col-md-3">
															<?php $from_date = _d($val['from_date']);
															echo render_date_input('from_date[' . $key . ']', 'from_date', $from_date);?>
														</div>

														<div class="col-md-3">
															<?php $to_date = _d($val['to_date']);
															echo render_date_input('to_date[' . $key . ']', 'to_date', $to_date);?>
														</div>

														<div class="col-md-3">
															<?php $company = $val['company'];
															echo render_input('company[' . $key . ']', 'company', $company)?>
														</div>

														<div class="col-md-3">
															<?php $position = $val['position'];
															echo render_input('position[' . $key . ']', 'position', $position)?>
														</div>

														<div class="col-md-3">
															<?php $contact_person = $val['contact_person'];
															echo render_input('contact_person[' . $key . ']', 'contact_person', $contact_person)?>
														</div>
														<div class="col-md-3">
															<?php $salary = $val['salary'];
															echo render_input('salary[' . $key . ']', 'salary', $salary)?>
														</div>

														<div class="col-md-6">
															<?php $reason_quitwork = $val['reason_quitwork'];
															echo render_input('reason_quitwork[' . $key . ']', 'reason_quitwork', $reason_quitwork)?>
														</div>

														<div class="col-md-12">
															<?php $job_description = $val['job_description'];
															echo render_textarea('job_description[' . $key . ']', 'job_description', $job_description)?>
														</div>

														<?php if ($key == 0) {?>
															<div class="col-md-12 line-height-content">
																<span class="input-group-btn pull-bot">
																	<button name="add" class="btn new_work_experience btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
																</span>
															</div>
														<?php } else {?>
															<div class="col-md-12 line-height-content">
																<span class="input-group-btn pull-bot">
																	<button name="add" class="btn remove_work_experience btn-danger border-radius-4" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
																</span>
															</div>
														<?php }?>
													</div>

												<?php }} else {?>
													<div class="row mbot20" id="work_experience-item">
														<div class="col-md-3">
															<?php echo render_date_input('from_date[0]', 'from_date', ''); ?>
														</div>

														<div class="col-md-3">
															<?php echo render_date_input('to_date[0]', 'to_date', ''); ?>
														</div>

														<div class="col-md-3">
															<?php echo render_input('company[0]', 'company') ?>
														</div>

														<div class="col-md-3">
															<?php echo render_input('position[0]', 'position') ?>
														</div>

														<div class="col-md-3">
															<?php echo render_input('contact_person[0]', 'contact_person') ?>
														</div>
														<div class="col-md-3">
															<?php echo render_input('salary[0]', 'salary') ?>
														</div>

														<div class="col-md-6">
															<?php echo render_input('reason_quitwork[0]', 'reason_quitwork') ?>
														</div>

														<div class="col-md-12">

															<p class=""><?php echo _l('job_description'); ?></p>
															<?php echo render_textarea('job_description[0]','',''); ?>


														</div>

														<div class="col-md-12 line-height-content">
															<span class="input-group-btn pull-bot">
																<button name="add" class="btn new_work_experience btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
															</span>
														</div>

													</div>
												<?php }?>
											</div>
										</div>
									</div>
								</div>
								<div class="card mbot20">
									<div class="card-header" id="headingFour">
										<h4 data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" class="cursor_pointer text-danger">
											<?php echo _l('re_skills'); ?><span class="caret pull-right"></span>
										</h4>
									</div>
									<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
										<div class="card-body">

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<select name="skill[]" id="skill" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<?php if(isset($candidate->skill)){ $skill_id = new_explode(',', $candidate->skill);} ; ?>

															<?php foreach($skills as $dpkey =>  $skill){ ?>
																<option value="<?php echo new_html_entity_decode($skill['id']); ?>"  <?php if(isset($skill_id) && in_array($skill['id'], $skill_id) == true ){echo 'selected';} ?>> <?php echo new_html_entity_decode($skill['skill_name']); ?></option>                  
															<?php }?>
														</select>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<select name="year_experience" id="year_experience" data-live-search="true" class="selectpicker" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

															<?php foreach(rec_year_experience() as $key =>  $year_experience){ ?>
																<option value="<?php echo new_html_entity_decode($year_experience['value']); ?>"  <?php if(isset($candidate) && $candidate->year_experience == $year_experience['value'] ){echo 'selected';} ?>> <?php echo new_html_entity_decode($year_experience['label']); ?></option>                  
															<?php }?>
														</select>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
								<div class="card mbot20">
									<div class="card-header" id="headingFive">
										<h4 data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" class="cursor_pointer text-danger">
											<?php echo _l('employment_literacy'); ?><span class="caret pull-right"></span>
										</h4>
									</div>
									<div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
										<div class="card-body">
											<div class="list_literacy">
												<?php if (isset($candidate) && count($candidate->literacy) > 0) {
													foreach ($candidate->literacy as $key => $val) {
														?>
														<div class="row mbot20" id="literacy-item">
															<div class="col-md-6">
																<?php $literacy_from_date = _d($val['literacy_from_date']);
																echo render_date_input('literacy_from_date[' . $key . ']', 'from_date', $literacy_from_date);?>
															</div>

															<div class="col-md-6">
																<?php $literacy_to_date = _d($val['literacy_to_date']);
																echo render_date_input('literacy_to_date[' . $key . ']', 'to_date', $literacy_to_date);?>
															</div>

															<div class="col-md-6">
																<?php $diploma = $val['diploma'];
																echo render_input('diploma[' . $key . ']', 'diploma', $diploma)?>
															</div>

															<div class="col-md-6">
																<?php $training_places = $val['training_places'];
																echo render_input('training_places[' . $key . ']', 'training_places', $training_places)?>
															</div>

															<div class="col-md-6">
																<?php $specialized = $val['specialized'];
																echo render_input('specialized[' . $key . ']', 'specialized', $specialized)?>
															</div>
															<div class="col-md-6">
																<?php $training_form = $val['training_form'];
																echo render_input('training_form[' . $key . ']', 'training_form', $training_form)?>
															</div>
															<?php if ($key == 0) {?>
																<div class="col-md-12 line-height-content">
																	<span class="input-group-btn pull-bot">
																		<button name="add" class="btn new_literacy btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
																	</span>
																</div>
															<?php } else {?>
																<div class="col-md-12 line-height-content">
																	<span class="input-group-btn pull-bot">
																		<button name="add" class="btn remove_literacy btn-danger border-radius-4" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
																	</span>
																</div>
															<?php }?>


														</div>
													<?php }} else {?>
														<div class="row mbot20" id="literacy-item">
															<div class="col-md-6">
																<?php echo render_date_input('literacy_from_date[0]', 'from_date', ''); ?>
															</div>

															<div class="col-md-6">
																<?php echo render_date_input('literacy_to_date[0]', 'to_date', ''); ?>
															</div>

															<div class="col-md-6">
																<?php echo render_input('diploma[0]', 'diploma') ?>
															</div>

															<div class="col-md-6">
																<?php echo render_input('training_places[0]', 'training_places') ?>
															</div>

															<div class="col-md-6">
																<?php echo render_input('specialized[0]', 'specialized') ?>
															</div>
															<div class="col-md-6">
																<?php echo render_input('training_form[0]', 'training_form') ?>
															</div>

															<div class="col-md-12 line-height-content">
																<span class="input-group-btn pull-bot">
																	<button name="add" class="btn new_literacy btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
																</span>
															</div>

														</div>
													<?php }?>
												</div>
											</div>
										</div>
									</div>

									<div class="card mbot20">
										<div class="card-header" id="headingTwo">
											<h4 data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="cursor_pointer text-danger">
												<?php echo _l('re_other_information'); ?><span class="caret pull-right"></span>
											</h4>
										</div>
										<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
											<div class="card-body">
												<div class="row">
													<div class="col-md-12">
														<?php 
														$rows1=[];
														$rows1['rows'] = 1;
														?>
														<?php $birthplace = (isset($candidate) ? $candidate->birthplace : '');
														echo render_textarea('birthplace', 'birthplace', $birthplace, $rows1)?>
													</div>

													<div class="col-md-12">
														<?php $home_town = (isset($candidate) ? $candidate->home_town : '');
														echo render_textarea('home_town', 'home_town', $home_town, $rows1)?>
													</div>

													<div class="col-md-4">
														<?php $identification = (isset($candidate) ? $candidate->identification : '');
														echo render_input('identification', 'identification', $identification);?>
													</div>
													<div class="col-md-4">
														<?php $days_for_identity = (isset($candidate) ? _d($candidate->days_for_identity) : '');
														echo render_date_input('days_for_identity', 'days_for_identity', $days_for_identity);?>
													</div>
													<div class="col-md-4">
														<?php $place_of_issue = (isset($candidate) ? $candidate->place_of_issue : '');
														echo render_input('place_of_issue', 'place_of_issue', $place_of_issue);?>
													</div>

													<div class="col-md-4">
														<label for="marital_status" class="control-label"><?php echo _l('marital_status'); ?></label>
														<select name="marital_status" class="selectpicker" id="marital_status" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="<?php echo 'single'; ?>" <?php if (isset($candidate) && $candidate->marital_status == 'single') {echo 'selected';}?> ><?php echo _l('single'); ?></option>
															<option value="<?php echo 'married'; ?>" <?php if (isset($candidate) && $candidate->marital_status == 'married') {echo 'selected';}?>  ><?php echo _l('married'); ?></option>
														</select>
													</div>
													<div class="col-md-4">
														<?php $nationality = (isset($candidate) ? $candidate->nationality : '');
														if(isset($candidate) && is_numeric($candidate->nationality)){
															$nationality = get_country_name($candidate->nationality);
														}

														echo render_input('nationality', 'nationality', $nationality);?>
													</div>
													<div class="col-md-4">
														<?php $nation = (isset($candidate) ? $candidate->nation : '');
														echo render_input('nation', 'nation', $nation);?>
													</div>
													<div class="col-md-4">
														<?php $religion = (isset($candidate) ? $candidate->religion : '');
														echo render_input('religion', 'religion', $religion);?>
													</div>
													<div class="col-md-4">
														<?php $height = (isset($candidate) ? $candidate->height : '');
														echo render_input('height', 'height', $height);?>
													</div>
													<div class="col-md-4">
														<?php $weight = (isset($candidate) ? $candidate->weight : '');
														echo render_input('weight', 'weight', $weight);?>
													</div>

													<?php 

													$rows1=[];
													$rows1['rows'] = 1;
													?>

													<div class="col-md-12">
														<?php $interests = (isset($candidate) ? $candidate->interests : '');
														echo render_textarea('interests', 'interests', $interests, $rows1)?>
													</div>

													<div class="col-md-12">
														<?php $skype = (isset($candidate) ? $candidate->skype : '');
														echo render_input('skype', 'skype', $skype);?>
													</div>
													<div class="col-md-12">
														<?php $facebook = (isset($candidate) ? $candidate->facebook : '');
														echo render_input('facebook', 'facebook', $facebook);?>
													</div>

													<div class="col-md-12">
														<?php $linkedin = (isset($candidate) ? $candidate->linkedin : '');
														echo render_input('linkedin', 'linkedin', $linkedin);?>
													</div>

													<div class="col-md-12">
														<?php $resident = (isset($candidate) ? $candidate->resident : '');
														echo render_textarea('resident', 'resident', $resident, $rows1)?>
													</div>
													<div class="col-md-12">
														<?php $current_accommodation = (isset($candidate) ? $candidate->current_accommodation : '');
														echo render_textarea('current_accommodation', 'current_accommodation', $current_accommodation, $rows1)?>

													</div>
												</div>
											</div>
										</div>
									</div>

								</div>


							</div>
							<div class="row p15 contact-profile-save-section">
								<div class="col-md-12 text-right mtop20">
									<div class="form-group">
										<button type="submit" class="btn btn-info contact-profile-save"><?php echo _l('clients_edit_profile_update_btn'); ?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
			<div class="col-md-4 contact-profile-change-password-section">
				<div class="panel_s section-heading section-change-password">
					<div class="panel-body">
						<h4 class="no-margin section-text"><?php echo _l('clients_edit_profile_change_password_heading'); ?></h4>
					</div>
				</div>
				<div class="panel_s">
					<div class="panel-body">
						<?php echo form_open('recruitment/recruitment_portal/profile'); ?>
						<?php echo form_hidden('change_password',true); ?>
						<div class="form-group">
							<label for="oldpassword"><?php echo _l('clients_edit_profile_old_password'); ?></label>
							<input type="password" class="form-control" name="oldpassword" id="oldpassword">
							<?php echo form_error('oldpassword'); ?>
						</div>
						<div class="form-group">
							<label for="newpassword"><?php echo _l('clients_edit_profile_new_password'); ?></label>
							<input type="password" class="form-control" name="newpassword" id="newpassword">
							<?php echo form_error('newpassword'); ?>
						</div>
						<div class="form-group">
							<label for="newpasswordr"><?php echo _l('clients_edit_profile_new_password_repeat'); ?></label>
							<input type="password" class="form-control" name="newpasswordr" id="newpasswordr">
							<?php echo form_error('newpasswordr'); ?>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-info btn-block"><?php echo _l('clients_edit_profile_change_password_btn'); ?></button>
						</div>
						<?php echo form_close(); ?>
					</div>
					<?php if($candidate->last_password_change !== NULL){ ?>
						<div class="panel-footer last-password-change">
							<?php echo _l('clients_profile_last_changed_password',time_ago($candidate->last_password_change)); ?>
						</div>
					<?php } ?>
				</div>
				<?php hooks()->do_action('after_candidate_profile_password_form_loaded'); ?>
			</div>

		</div>
		<?php hooks()->do_action('app_customers_portal_footer'); ?>

		<?php require 'modules/recruitment/assets/js/recruitment_portals/candidates/profiles/candidate_profile_js.php';?>

		<style type="text/css">
		.cursor_pointer {cursor: pointer; cursor: hand;}
	</style>