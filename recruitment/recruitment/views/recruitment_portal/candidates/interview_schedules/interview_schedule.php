<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_customers_portal_head'); ?>

<div class="panel_s section-heading section-invoices">
	<div class="panel-body">
		<div class="col-md-6">

			<h4 class="no-margin section-text"><?php echo _l('rec_interview_schedules'); ?></h4>
			
		</div>
	</div>
</div>

<div class="panel_s">
	<div class="panel-body">
		<table class="table dt-table table-invoices" data-order-col="1" data-order-type="desc">
			<thead>
				<tr>
					<th class="hide"><?php echo _l('add_from'); ?></th>
					<th><?php echo _l('interview_schedules_name'); ?></th>
					<th><?php echo _l('rec_time'); ?></th>
					<th><?php echo _l('interview_day'); ?></th>
					<th><?php echo _l('recruitment_campaign'); ?></th>
					<th><?php echo _l('interviewer'); ?></th>
					<th><?php echo _l('date_add'); ?></th>
					<th><?php echo _l('interview_location'); ?></th>
					<th><?php echo _l('status'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($list_interview as $li) {
					?>
					<tr>
						<td class="hide">
							<?php
							$_data = '<a href="#">' . staff_profile_image($li['added_from'], [
								'staff-profile-image-small',
							]) . '</a>';
							$_data .= ' <a href="#">' . get_staff_full_name($li['added_from']) . '</a>';
							echo new_html_entity_decode($_data);
							?>
						</td>
						<td><?php echo new_html_entity_decode($li['is_name']) ?></td>
						<td><?php echo new_html_entity_decode($li['from_time'] . ' - ' . $li['to_time']); ?></td>
						<td><?php echo _d($li['interview_day']); ?></td>
						<td><?php
						$cp = get_rec_campaign_hp($li['campaign']);
						if ($li['campaign'] != '' && $li['campaign'] != 0) {
							if (isset($cp)) {
								$_data = $cp->campaign_code . ' - ' . $cp->campaign_name;
							} else {
								$_data = '';
							}
						} else {
							$_data = '';

						}

						echo new_html_entity_decode($_data);
						?>

					</td>
					<td>
						<?php
						$inv = new_explode(',', $li['interviewer']);
						$ata = '';
						foreach ($inv as $iv) {
							$ata .= '<a href="#">' . staff_profile_image($iv, [
								'staff-profile-image-small mright5',
							], 'small', [
								'data-toggle' => 'tooltip',
								'data-title' => get_staff_full_name($iv),
							]) . '</a>';
						}
						echo new_html_entity_decode($ata);
						?>
					</td>
					<td><?php echo _d($li['added_date']); ?></td>
					<td><?php echo new_html_entity_decode($li['interview_location']); ?></td>
					<td>
						<?php 
						echo re_render_status_html($li['in_id'], 'interview', $li['status']);
						?>
					</td>
				</tr>
			<?php }?>
		</tbody>
	</table>
</div>
</div>
<?php hooks()->do_action('app_customers_portal_footer'); ?>
