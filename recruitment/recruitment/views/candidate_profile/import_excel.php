<?php defined('BASEPATH') or exit('No direct script access allowed'); 
?>
<?php 
$file_header = array();
$file_header[] = _l('ID');
$file_header[] = _l('candidate_code');
$file_header[] = _l('first_name');
$file_header[] = _l('last_name');
$file_header[] = _l('email');
$file_header[] = _l('phone');
$file_header[] = _l('alternate_contact_number');
$file_header[] = _l('resident');
$file_header[] = _l('current_accommodation');
$file_header[] = _l('re_candidate_status');

$file_header[] = _l('skype');
$file_header[] = _l('facebook');
$file_header[] = _l('linkedin');
$file_header[] = _l('birthday');
$file_header[] = _l('gender');
$file_header[] = _l('desired_salary');
$file_header[] = _l('birthplace');
$file_header[] = _l('home_town');
$file_header[] = _l('identification');
$file_header[] = _l('days_for_identity');
$file_header[] = _l('place_of_issue');
$file_header[] = _l('marital_status');
$file_header[] = _l('nationality');
$file_header[] = _l('nation');
$file_header[] = _l('religion');
$file_header[] = _l('height');
$file_header[] = _l('weight');
$file_header[] = _l('introduce_yourself');
$file_header[] = _l('skill_name');
$file_header[] = _l('experience');
$file_header[] = _l('interests');

?>

<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<h4><?php echo _l('re_import_candidates') ?></h4>
						<div id ="dowload_file_sample">


						</div>

						<?php if(!isset($simulate)) { ?>

							<ul>
								<li class="text-danger">1. <?php echo _l('rec_import_excel_required'); ?></li>
								<li class="text-danger">2. <?php echo _l('rec_import_excel_candidate_status'); ?></li>
								<li class="text-danger">3. <?php echo _l('rec_import_excel_gender'); ?></li>
								<li class="text-danger">4. <?php echo _l('rec_import_excel_marital_status'); ?></li>
								<li class="text-danger">5. <?php echo _l('rec_import_excel_skill'); ?></li>
								<li class="text-danger">6. <?php echo _l('rec_import_excel_seniority'); ?></li>
								<!-- <li class="text-danger">7. <?php echo _l('rec_import_excel_CV'); ?></li> -->
							</ul>

							<div class="table-responsive no-dt">
								<table class="table table-hover table-bordered">
									<thead>
										<tr>
											<?php
											$total_fields = 0;
											
											for($i=0;$i<count($file_header);$i++){
												if($i == 4||$i == 2||$i == 3){
													?>
													<th class="bold"><span class="text-danger">*</span> <?php echo new_html_entity_decode($file_header[$i]) ?> </th>
													<?php 
												} else {
													?>
													<th class="bold"><?php echo new_html_entity_decode($file_header[$i]) ?> </th>
													
													<?php

												} 
												$total_fields++;
											}

											?>

										</tr>
									</thead>
									<tbody>
										<?php for($i = 0; $i<1;$i++){
											echo '<tr>';
											for($x = 0; $x<count($file_header);$x++){
												echo '<td>- </td>';
											}
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
							<hr>

						<?php } ?>
						
						<div class="row">
							<div class="col-md-4">
								<?php echo form_open_multipart(admin_url('hrm/import_job_p_excel'),array('id'=>'import_form')) ;?>
								<?php echo form_hidden('leads_import','true'); ?>
								<?php echo render_input('file_csv','choose_excel_file','','file'); ?> 

								<div class="form-group">
									<a href="<?php echo admin_url('recruitment/candidate_profile'); ?>" class="btn btn-default"><?php echo _l('close'); ?></a>
									<button id="uploadfile" type="button" class="btn btn-info import" onclick="return uploadfilecsv(this);" ><?php echo _l('rec_import'); ?></button>
								</div>
								<?php echo form_close(); ?>
							</div>
							<div class="col-md-8">
								<div class="form-group" id="file_upload_response">
									
								</div>
								
							</div>
						</div>
						
					</div>
				</div>
			</div>

			<!-- box loading -->
			<div id="box-loading">

			</div>

		</div>
	</div>
</div>
<?php init_tail(); ?>

<?php require 'modules/recruitment/assets/js/candidates/import_excel_js.php';?>
</body>
</html>
