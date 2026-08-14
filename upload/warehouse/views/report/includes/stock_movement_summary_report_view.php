	<?php
	if (isset($goods_transaction_details) && count($goods_transaction_details) > 0) {

		$b_f_grandtotal     = 0;
		$gr_grandtotal      = 0;
		$pi_grandtotal      = 0;
		$cp_grandtotal      = 0;
		$pr_grandtotal      = 0;
		$grt_grandtotal     = 0;
		$do_grandtotal      = 0;
		$si_grandtotal      = 0;
		$cs_grandtotal      = 0;
		$drt_grandtotal     = 0;
		$srt_grandtotal     = 0;
		$br_grandtotal      = 0;
		$stf_grandtotal     = 0;
		$adj_grandtotal     = 0;
		$rec_grandtotal     = 0;
		$iss_grandtotal     = 0;
		$bal_qty_grandtotal = 0;
		$transaction_index  = 0;
		foreach ($goods_transaction_details as $main_warehouse_id => $item_by_warehouse) { ?>
			<?php	$warehouse_name = isset($warehouse_ids[$main_warehouse_id]) ? $warehouse_ids[$main_warehouse_id] : ''; ?>
			<?php if(count($item_by_warehouse) > 0){

				?>
				<tr>
					<th colspan="27" class="text-left bold"><?php echo _l('warehouse_filter'); ?>:<?php echo html_entity_decode($warehouse_name); ?> </th>
				</tr>
			<?php } ?>
			<?php 
			$total_item_by_warehouse = 0;
			$b_f_total     = 0;
			$gr_total      = 0;
			$pi_total      = 0;
			$cp_total      = 0;
			$pr_total      = 0;
			$grt_total     = 0;
			$do_total      = 0;
			$si_total      = 0;
			$cs_total      = 0;
			$drt_total     = 0;
			$srt_total     = 0;
			$br_total      = 0;
			$stf_total     = 0;
			$adj_total     = 0;
			$rec_total     = 0;
			$iss_total     = 0;
			$bal_qty_total = 0;
			?>

			<?php foreach ($item_by_warehouse as $key => $transaction_detail) {
				$total_item_by_warehouse ++;
				$total_serial_qty = 0;

				$b_f_subtotal     = 0;
				$gr_subtotal      = 0;
				$pi_subtotal      = 0;
				$cp_subtotal      = 0;
				$pr_subtotal      = 0;
				$grt_subtotal     = 0;
				$do_subtotal      = 0;
				$si_subtotal      = 0;
				$cs_subtotal      = 0;
				$drt_subtotal     = 0;
				$srt_subtotal     = 0;
				$br_subtotal      = 0;
				$stf_subtotal     = 0;
				$adj_subtotal     = 0;
				$rec_subtotal     = 0;
				$iss_subtotal     = 0;
				$bal_qty_subtotal = 0;
				$exist_data       = false;

				?>

				<?php if (isset($import_openings[$main_warehouse_id][$transaction_detail['commodity_id']]) && count($import_openings[$main_warehouse_id][$transaction_detail['commodity_id']]) > 0) { ?>
					<?php foreach ($import_openings[$main_warehouse_id][$transaction_detail['commodity_id']] as $lot_date => $import_opening) { ?>
						<?php
						$lot_number   = '';
						$expiry_date  = '';
						$arr_lot_date = explode('_', $lot_date);
						if (isset($arr_lot_date[0]) && $arr_lot_date[0] != 'XXX') {
							$lot_number = $arr_lot_date[0];
						}
						if (isset($arr_lot_date[1]) && $arr_lot_date[1] != 'XXX') {
							$expiry_date = $arr_lot_date[1];
						}

						$warehouse_id = '';
						if (isset($arr_lot_date[3]) && $arr_lot_date[3] != 'XXX') {
							$warehouse_id = $arr_lot_date[3];
						}

						if ($transaction_detail['warehouse_id'] != $warehouse_id) {
							continue;
						}
						$warehouse_name = isset($warehouse_ids[$warehouse_id]) ? $warehouse_ids[$warehouse_id] : '';

						?>

						<?php if ( ! $exist_data) {?>
							<?php 
							$transaction_index++;
							$order_number     = $transaction_index;
							?>
							<tr>
								<td class="bold"><?php echo html_entity_decode($order_number); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['commodity_code'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['description'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['commondity_name'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['name'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['sub_group_name'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['unit_name'] ?? ''); ?></td>
								<td class="bold" colspan="20"></td>
							</tr>
							<?php $exist_data = true; ?>
						<?php } ?>

						<tr>
							<td colspan="1"></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><?php echo html_entity_decode($import_opening['lot_number'] ?? ''); ?></td>
							<td><?php echo html_entity_decode($import_opening['serial_number'] ?? ''); ?></td>
							<td><?php echo html_entity_decode($import_opening['expiry_date'] ?? ''); ?></td>
							<td><?php echo html_entity_decode($import_opening['quantity'] ?? ''); ?></td>
							<?php

							$b_f = $import_opening['quantity'];
							$gr  = 0;
							if (isset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][1])) {
								$gr = $import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][1]['quantity'];
								unset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][1]);
							}

							$pi  = 0;
							$cp  = 0;
							$pr  = 0;
							$grt = 0;
							$do  = 0;
							if (isset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][2])) {
								$do = 0 - $export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][2]['quantity'];
								unset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][2]);
							}

							$si  = 0;
							$cs  = 0;
							$drt = 0;
							$srt = 0;
							$br  = 0;
							$stf = 0;
							if (isset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4])) {
								$stf = $import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4]['quantity'];
								unset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4]);
							}

							$adj = 0;
							if (isset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3])) {
								$adj = $import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3]['quantity'];
								unset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3]);
							}

							$rec = 0;
							if (isset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4])) {
								$rec = 0 - $export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4]['quantity'];
								unset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4]);
							}

							$iss = 0;
							if (isset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3])) {
								$iss = 0 - $export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3]['quantity'];
								unset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3]);
							}

							$bal_qty = $b_f + $gr + $pi + $cp + $pr + $grt + $do + $si + $cs + $drt + $srt + $br + $stf + $adj + $rec + $iss;

							$b_f_subtotal += $b_f;
							$gr_subtotal += $gr;
							$pi_subtotal += $pi;
							$cp_subtotal += $cp;
							$pr_subtotal += $pr;
							$grt_subtotal += $grt;
							$do_subtotal += $do;
							$si_subtotal += $si;
							$cs_subtotal += $cs;
							$drt_subtotal += $drt;
							$srt_subtotal += $srt;
							$br_subtotal += $br;
							$stf_subtotal += $stf;
							$adj_subtotal += $adj;
							$rec_subtotal += $rec;
							$iss_subtotal += $iss;
							$bal_qty_subtotal += $bal_qty;
							?>
							<td><?php echo app_format_number($gr, ''); ?></td>
							<td><?php echo app_format_number($pi, ''); ?></td>
							<td><?php echo app_format_number($cp, ''); ?></td>
							<td><?php echo app_format_number($pr, ''); ?></td>
							<td><?php echo app_format_number($grt, ''); ?></td>

							<td><?php echo app_format_number($do, ''); ?></td>
							<td><?php echo app_format_number($si, ''); ?></td>
							<td><?php echo app_format_number($cs, ''); ?></td>
							<td><?php echo app_format_number($drt, ''); ?></td>
							<td><?php echo app_format_number($srt, ''); ?></td>
							<td><?php echo app_format_number($br, ''); ?></td>

							<td><?php echo app_format_number($stf, ''); ?></td>
							<td><?php echo app_format_number($adj, ''); ?></td>
							<td><?php echo app_format_number($rec, ''); ?></td>
							<td><?php echo app_format_number($iss, ''); ?></td>

							<td><?php echo app_format_number($bal_qty, ''); ?></td>
						</tr>

					<?php } ?>
				<?php } ?>

				<!-- item period -->

				<?php if (isset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']]) && count($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']]) > 0) {?>

					<?php foreach ($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']] as $lot_date => $import_period_details) {?>
						<?php
						$lot_number   = '';
						$expiry_date  = '';
						$arr_lot_date = explode('_', $lot_date);
						if (isset($arr_lot_date[0]) && $arr_lot_date[0] != 'XXX') {
							$lot_number = $arr_lot_date[0];
						}
						if (isset($arr_lot_date[1]) && $arr_lot_date[1] != 'XXX') {
							$expiry_date = $arr_lot_date[1];
						}

						$warehouse_id = '';
						if (isset($arr_lot_date[3]) && $arr_lot_date[3] != 'XXX') {
							$warehouse_id = $arr_lot_date[3];
						}

						if ($transaction_detail['warehouse_id'] != $warehouse_id) {
							continue;
						}
						$warehouse_name = isset($warehouse_ids[$warehouse_id]) ? $warehouse_ids[$warehouse_id] : '';
						?>

						<?php if ( ! $exist_data) {?>
							<?php 
							$transaction_index++;
							$order_number     = $transaction_index;
							?>
							<tr>
								<td class="bold"><?php echo html_entity_decode($order_number); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['commodity_code'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['description'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['commondity_name'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['name'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['sub_group_name'] ?? ''); ?></td>
								<td class="bold"><?php echo html_entity_decode($transaction_detail['unit_name'] ?? ''); ?></td>
								<td class="bold" colspan="20"></td>
							</tr>
							<?php $exist_data = true; ?>
						<?php } ?>

						<?php foreach ($import_period_details as $key => $import_opening) {?>
							<tr>
								<td colspan="1"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo html_entity_decode($import_opening['lot_number'] ?? ''); ?></td>
								<td><?php echo html_entity_decode($import_opening['serial_number'] ?? ''); ?></td>
								<td><?php echo html_entity_decode($import_opening['expiry_date'] ?? ''); ?></td>
								<?php
								$b_f = 0;
								?>
								<td><?php echo html_entity_decode($b_f); ?></td>
								<?php
								$gr = 0;
								if (isset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][1])) {
									$gr = $import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][1]['quantity'];
									unset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][1]);
								}

								$pi  = 0;
								$cp  = 0;
								$pr  = 0;
								$grt = 0;
								$do  = 0;
								if (isset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][2])) {
									$do = 0 - $export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][2]['quantity'];
									unset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][2]);
								}

								$si  = 0;
								$cs  = 0;
								$drt = 0;
								$srt = 0;
								$br  = 0;
								$stf = 0;
								if (isset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4])) {
									$stf = $import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4]['quantity'];
									unset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4]);
								}

								$adj = 0;
								if (isset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3])) {
									$adj = $import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3]['quantity'];
									unset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3]);
								}

								$rec = 0;
								if (isset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4])) {
									$rec = 0 - $export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4]['quantity'];
									unset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][4]);
								}

								$iss = 0;
								if (isset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3])) {
									$iss = 0 - $export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3]['quantity'];
									unset($export_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']][$lot_date][3]);
								}

								$bal_qty = $b_f + $gr + $pi + $cp + $pr + $grt + $do + $si + $cs + $drt + $srt + $br + $stf + $adj + $rec + $iss;

								$b_f_subtotal += $b_f;
								$gr_subtotal += $gr;
								$pi_subtotal += $pi;
								$cp_subtotal += $cp;
								$pr_subtotal += $pr;
								$grt_subtotal += $grt;
								$do_subtotal += $do;
								$si_subtotal += $si;
								$cs_subtotal += $cs;
								$drt_subtotal += $drt;
								$srt_subtotal += $srt;
								$br_subtotal += $br;
								$stf_subtotal += $stf;
								$adj_subtotal += $adj;
								$rec_subtotal += $rec;
								$iss_subtotal += $iss;
								$bal_qty_subtotal += $bal_qty;
								?>
								<td><?php echo app_format_number($gr, ''); ?></td>
								<td><?php echo app_format_number($pi, ''); ?></td>
								<td><?php echo app_format_number($cp, ''); ?></td>
								<td><?php echo app_format_number($pr, ''); ?></td>
								<td><?php echo app_format_number($grt, ''); ?></td>

								<td><?php echo app_format_number($do, ''); ?></td>
								<td><?php echo app_format_number($si, ''); ?></td>
								<td><?php echo app_format_number($cs, ''); ?></td>
								<td><?php echo app_format_number($drt, ''); ?></td>
								<td><?php echo app_format_number($srt, ''); ?></td>
								<td><?php echo app_format_number($br, ''); ?></td>

								<td><?php echo app_format_number($stf, ''); ?></td>
								<td><?php echo app_format_number($adj, ''); ?></td>
								<td><?php echo app_format_number($rec, ''); ?></td>
								<td><?php echo app_format_number($iss, ''); ?></td>

								<td><?php echo app_format_number($bal_qty, ''); ?></td>
							</tr>

						<?php }?>
					<?php }?>
				<?php }?>

				<?php
				$b_f_grandtotal += (float) $b_f_subtotal;
				$gr_grandtotal += (float) $gr_subtotal;
				$pi_grandtotal += (float) $pi_subtotal;
				$cp_grandtotal += (float) $cp_subtotal;
				$pr_grandtotal += (float) $pr_subtotal;
				$grt_grandtotal += (float) $grt_subtotal;
				$do_grandtotal += (float) $do_subtotal;
				$si_grandtotal += (float) $si_subtotal;
				$cs_grandtotal += (float) $cs_subtotal;
				$drt_grandtotal += (float) $drt_subtotal;
				$srt_grandtotal += (float) $srt_subtotal;
				$br_grandtotal += (float) $br_subtotal;
				$stf_grandtotal += (float) $stf_subtotal;
				$adj_grandtotal += (float) $adj_subtotal;
				$rec_grandtotal += (float) $rec_subtotal;
				$iss_grandtotal += (float) $iss_subtotal;
				$bal_qty_grandtotal += (float) $bal_qty_subtotal;

				$b_f_total     += (float) $b_f_subtotal;;
				$gr_total      += (float) $gr_subtotal;;
				$pi_total      += (float) $pi_subtotal;;
				$cp_total      += (float) $cp_subtotal;;
				$pr_total      += (float) $pr_subtotal;;
				$grt_total     += (float) $grt_subtotal;;
				$do_total      += (float) $do_subtotal;;
				$si_total      += (float) $si_subtotal;;
				$cs_total      += (float) $cs_subtotal;;
				$drt_total     += (float) $drt_subtotal;;
				$srt_total     += (float) $srt_subtotal;;
				$br_total      += (float) $br_subtotal;;
				$stf_total     += (float) $stf_subtotal;;
				$adj_total     += (float) $adj_subtotal;;
				$rec_total     += (float) $rec_subtotal;;
				$iss_total     += (float) $iss_subtotal;;
				$bal_qty_total += (float) $bal_qty_subtotal;;

				?>

				<?php if($exist_data){ ?>
					<tr>
						<th colspan="10" class="text-right bold"><?php echo _l('wh_sub_total') ?> : </th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($b_f_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($gr_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($pi_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($cp_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($pr_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($grt_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($do_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($si_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($cs_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($drt_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($srt_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($br_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($stf_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($adj_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($rec_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($iss_subtotal, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($bal_qty_subtotal, '')); ?></th>
					</tr>
				<?php } ?>

				<?php if(count($item_by_warehouse) == $total_item_by_warehouse){ ?>
					<tr>
						<th colspan="10" class="text-right bold"><?php echo _l('total') ?> : </th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($b_f_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($gr_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($pi_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($cp_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($pr_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($grt_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($do_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($si_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($cs_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($drt_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($srt_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($br_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($stf_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($adj_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($rec_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($iss_total, '') ?? ''); ?></th>
						<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($bal_qty_total, '')); ?></th>
					</tr>
				<?php } ?>


			<?php } ?>
		<?php } ?>
			<tr>
				<th colspan="10" class="text-right bold"><?php echo _l('wh_grand_total') ?> : </th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($b_f_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($gr_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($pi_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($cp_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($pr_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($grt_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($do_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($si_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($cs_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($drt_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($srt_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($br_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($stf_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($adj_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($rec_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($iss_grandtotal, '') ?? ''); ?></th>
				<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($bal_qty_grandtotal, '') ?? ''); ?></th>
			</tr>
	<?php } ?>
