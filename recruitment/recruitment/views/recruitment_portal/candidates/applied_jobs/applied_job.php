<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_customers_portal_head'); ?>

<div class="panel_s section-heading section-invoices">
	<div class="panel-body">
		<div class="col-md-6">

			<h4 class="no-margin section-text"><?php echo _l('re_applied_jobs'); ?></h4>
			
		</div>
	</div>
</div>

<div class="panel_s">
	<div class="panel-body">
		<table class="table dt-table table-invoices" data-order-col="1" data-order-type="desc">
			<thead>
				<tr>
					<th class="th-campaign"><?php echo _l('campaign'); ?></th>
					<th class="th-date_applied"><?php echo _l('date_applied'); ?></th>
					<th class="th-status"><?php echo _l('status'); ?></th>
					<th class="th-status"><?php echo _l('rec_options'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($candidate->applied_jobs as $applied_job) {?>
					<?php if($applied_job['activate'] == '1'){ ?>
						<tr class="project-overview">
							<td><?php
							$cp = get_rec_campaign_hp($applied_job['campaign_id']);
							$datas = '';
							if (isset($cp)) {
								$datas = '<a href="' . site_url('recruitment/recruitment_portal/job_detail/' . $cp->cp_id) . '">' . $cp->campaign_code . ' - ' . $cp->campaign_name . '</a>';
							}
							echo new_html_entity_decode($datas);
						?></td>
						<td><?php echo _d($applied_job['date_created']); ?></td>
						<td>
							<?php 
							echo re_render_status_html($applied_job['id'], 'applied_job', $applied_job['status']);
							?>
						</td>
						<td>
							<a href="<?php echo site_url('recruitment/recruitment_portal/delete_applied_job/'.$applied_job['id']) ?>" class="btn btn-danger btn-icon _delete" data-original-title="<?php echo _l('delete'); ?>" data-toggle="tooltip" data-placement="top">
								<i class="fa fa-remove"></i>
							</a>
						</td>

					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>
</div>
<?php hooks()->do_action('app_customers_portal_footer'); ?>
