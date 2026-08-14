<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Background color
$dimensions    = $this->getPageDimensions();
$font_size     = get_option('pdf_font_size');
$pdf_font_size = ($font_size + 0);
$table_font_size = 'font-size:'.$pdf_font_size.'px;';

$goods_transaction_details = $movement_summary['goods_transaction_details'];
$import_openings = $movement_summary['import_openings'];
$import_period_openings = $movement_summary['import_period_openings'];
$export_period_openings = $movement_summary['export_period_openings'];
$warehouse_ids = $movement_summary['warehouse_ids'];
$from_date = $movement_summary['from_date'];
$to_date = $movement_summary['to_date'];

// render item table start
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
	$exist_data		  = false;
	$old_warehouse_id = 0;
	$total_warehouse = 0;
	$items           = '';

	foreach ($goods_transaction_details as $main_warehouse_id => $item_by_warehouse) {
		$total_row = 0;
		$total_warehouse ++;
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


		$warehouse_name = isset($warehouse_ids[$main_warehouse_id]) ? $warehouse_ids[$main_warehouse_id] : '';
		foreach ($item_by_warehouse as $key => $transaction_detail) {
			$total_item_by_warehouse++;
			// table detail
			if($transaction_index == 0 || ($old_warehouse_id != $main_warehouse_id)){
				$total_row += 3;
				$old_warehouse_id = $main_warehouse_id;

				// header
				$items .= '<table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop" cellpadding="2" >
				<thead style="' . $table_font_size . '">';
				$items .= '
				<tr style="font-size: '.$pdf_font_size.'px">
				<td align="left" width="20%"><span>'._l('from_date') .': '.$from_date .'</span></td>
				<td align="center" width="20%"><span>'. _l('to_date') .': '. $to_date .'</span></td>
				<td align="right" width="0%"></td>
				</tr>';

				$items .= '
				<tr style="font-size: '.$pdf_font_size.'px">
				<td align="left" width="80%"><span>'. _l('warehouse_filter') .': '. $warehouse_name .'</span></td>
				<td align="left" width="20%"></td>
				<td align="right" width="0%"></td>
				</tr>';

				$items .= '<tr height="30" style="color:black;' . $table_font_size . '; ">';
				$items .= '
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="5%"><strong style="vertical-align:middle;">' . _l('wh_item_code') . '</strong></th>
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="16%"><strong>' . _l('description') . '</strong></th>
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="6%"><strong>' . _l('wh_item_type') . '</strong></th>
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="5%"><strong>' . _l('wh_group') . '</strong></th>
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="5%"><strong>' . _l('expense_dt_table_heading_category') . '</strong></th>
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="4%"><strong>' . _l('wh_uom') . '</strong></th>
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="6%"><strong>' . _l('wh_batch_no') . '</strong></th>
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="6%"><strong>' . _l('wh_serial_hashtag') . '</strong></th>
				<th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="4%"><strong>' . _l('expiry_date') . '</strong></th>
				<th rowspan="2" align="center" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="4%"><strong>' . _l('wh_b_f') . '</strong></th>
				<th colspan="5" align="center" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="12%"><strong>' . _l('wh_als_purchase') . '</strong></th>
				<th colspan="6" align="center" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="12%"><strong>' . _l('als_sales') . '</strong></th>
				<th colspan="4" align="center" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="10%"><strong>' . _l('wh_als_inventory') . '</strong></th>
				<th rowspan="2" align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="5%"><strong>' . _l('wh_bal_qty') . '</strong></th>

				</tr>

				<tr style="color:black;' . $table_font_size . '; ">
				<td align="right" width="3.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_gr') . '</strong></td>
				<td align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_pi') . '</strong></td>
				<td align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_cp') . '</strong></td>
				<td align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_pr') . '</strong></td>
				<td align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_grt') . '</strong></td>
				<td align="right" width="3%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_do') . '</strong></td>
				<td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_si') . '</strong></td>
				<td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_cs') . '</strong></td>
				<td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_drt') . '</strong></td>
				<td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_srt') . '</strong></td>
				<td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_br') . '</strong></td>
				<td align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_stf') . '</strong></td>
				<td align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_adj') . '</strong></td>
				<td align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_rec') . '</strong></td>
				<td align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_iss') . '</strong></td>
				</tr>
				</thead>
				<tbody class="tbody-main"  style="' . $table_font_size . '" >';

			}
			

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
			$exist_data		  = false;


			if (isset($import_openings[$main_warehouse_id][$transaction_detail['commodity_id']]) && count($import_openings[$main_warehouse_id][$transaction_detail['commodity_id']]) > 0) {
				foreach ($import_openings[$main_warehouse_id][$transaction_detail['commodity_id']] as $lot_date => $import_opening) {

					$lot_number   = '';
					$expiry_date  = '';
					$arr_lot_date = explode('_', $lot_date);
					if (isset($arr_lot_date[0]) && $arr_lot_date[0] != 'XXX') {
						$lot_number = $arr_lot_date[0];
					}
					if (isset($arr_lot_date[1]) && $arr_lot_date[1] != 'XXX') {
						$expiry_date = $arr_lot_date[1];
					}

					$warehouse_id  = '';
					if (isset($arr_lot_date[3]) && $arr_lot_date[3] != 'XXX') {
						$warehouse_id = $arr_lot_date[3];
					}

					if($transaction_detail['warehouse_id'] != $warehouse_id){
						continue;
					}

					if(!$exist_data){
						$transaction_index++;
						$order_number     = $transaction_index;

						$items .= '<tr style="' . $table_font_size . '">';
						$items .= '<td align="left" width="5%"><span style="font-weight:bold;">' . $transaction_detail['commodity_code'] . '</span></td>';
						$items .= '<td align="left" width="16%"><span style="font-weight:bold;">' . $transaction_detail['description'] . '</span></td>';
						$items .= '<td align="left" width="6%"><span style="font-weight:bold;">' . $transaction_detail['commondity_name'] . '</span></td>';
						$items .= '<td align="left" width="5%"><span style="font-weight:bold;">' . $transaction_detail['name'] . '</span></td>';
						$items .= '<td align="left" width="5%"><span style="font-weight:bold;">' . $transaction_detail['sub_group_name'] . '</span></td>';
						$items .= '<td align="left" width="4%"><span style="font-weight:bold;">' . $transaction_detail['unit_name'] . '</span></td>';
						$items .= '<td align="left" width="6%"></td>';
						$items .= '<td align="left" width="6%"></td>';
						$items .= '<td align="left" width="4%"></td>';
						$items .= '<td align="left" width="4%"></td>';
						$items .= '<td align="left" width="39%" colspan="16"></td>';
						$items .= '</tr>';

						$total_row ++;
						if($total_row % 49 == 0){

							$new_header = $this->new_header($warehouse_name, $from_date, $to_date);
							$items .= $new_header['close_table'];

							$tblhtml = $items;
							$pdf->writeHTML($tblhtml, true, false, false, false, '');

							$items = $new_header['start_table'];
							$total_row = 3;
						}

						$exist_data		  = true;
					}

					$items .= '<tr style="' . $table_font_size . '">';
					$items .= '<td width="5%"></td>';
					$items .= '<td width="16%"></td>';
					$items .= '<td width="6%"></td>';
					$items .= '<td width="5%"></td>';
					$items .= '<td width="5%"></td>';
					$items .= '<td width="4%"></td>';
					$items .= '<td width="6%">' . $import_opening['lot_number'] . '</td>';
					$items .= '<td width="6%">' . $import_opening['serial_number'] . '</td>';
					$items .= '<td width="4%">' . $import_opening['expiry_date'] . '</td>';
					$items .= '<td align="right" width="4%">' . $import_opening['quantity'] . '</td>';

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

					$items .= '<td align="right" width="3.2%">'. app_format_number($gr, '') .'</td>';
					$items .= '<td align="right" width="2.2%">'. app_format_number($pi, '') .'</td>';
					$items .= '<td align="right" width="2.2%">'. app_format_number($cp, '') .'</td>';
					$items .= '<td align="right" width="2.2%">'. app_format_number($pr, '') .'</td>';
					$items .= '<td align="right" width="2.2%">'. app_format_number($grt, '') .'</td>';

					$items .= '<td align="right" width="3%">'. app_format_number($do, '') .'</td>';
					$items .= '<td align="right" width="1.8%">'. app_format_number($si, '') .'</td>';
					$items .= '<td align="right" width="1.8%">'. app_format_number($cs, '') .'</td>';
					$items .= '<td align="right" width="1.8%">'. app_format_number($drt, '') .'</td>';
					$items .= '<td align="right" width="1.8%">'. app_format_number($srt, '') .'</td>';
					$items .= '<td align="right" width="1.8%">'. app_format_number($br, '') .'</td>';

					$items .= '<td align="right" width="2.5%">'. app_format_number($stf, '') .'</td>';
					$items .= '<td align="right" width="2.5%">'. app_format_number($adj, '') .'</td>';
					$items .= '<td align="right" width="2.5%">'. app_format_number($rec, '') .'</td>';
					$items .= '<td align="right" width="2.5%">'. app_format_number($iss, '') .'</td>';

					$items .= '<td align="right" width="5%">'. app_format_number($bal_qty, '') .'</td>';
					$items .= '</tr>';

					$total_row ++;
					if($total_row % 49 == 0){
						$new_header = $this->new_header($warehouse_name, $from_date, $to_date);
						$items .= $new_header['close_table'];

						$tblhtml = $items;
						$pdf->writeHTML($tblhtml, true, false, false, false, '');

						$items = $new_header['start_table'];

						$total_row = 3;
					}
					
				}
			}


			if (isset($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']]) && count($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']]) > 0) {
				foreach ($import_period_openings[$main_warehouse_id][$transaction_detail['commodity_id']] as $lot_date => $import_period_details) {
					$lot_number   = '';
					$expiry_date  = '';
					$arr_lot_date = explode('_', $lot_date);
					if (isset($arr_lot_date[0]) && $arr_lot_date[0] != 'XXX') {
						$lot_number = $arr_lot_date[0];
					}
					if (isset($arr_lot_date[1]) && $arr_lot_date[1] != 'XXX') {
						$expiry_date = $arr_lot_date[1];
					}

					$warehouse_id  = '';
					if (isset($arr_lot_date[3]) && $arr_lot_date[3] != 'XXX') {
						$warehouse_id = $arr_lot_date[3];
					}

					if($transaction_detail['warehouse_id'] != $warehouse_id){
						continue;
					}


					if(!$exist_data){
						$transaction_index++;
						$order_number     = $transaction_index;

						$items .= '<tr style="' . $table_font_size . '">';
						$items .= '<td align="left" width="5%"><span style="font-weight:bold;">' . $transaction_detail['commodity_code'] . '</span></td>';
						$items .= '<td align="left" width="16%"><span style="font-weight:bold;">' . $transaction_detail['description'] . '</span></td>';
						$items .= '<td align="left" width="6%"><span style="font-weight:bold;">' . $transaction_detail['commondity_name'] . '</span></td>';
						$items .= '<td align="left" width="5%"><span style="font-weight:bold;">' . $transaction_detail['name'] . '</span></td>';
						$items .= '<td align="left" width="5%"><span style="font-weight:bold;">' . $transaction_detail['sub_group_name'] . '</span></td>';
						$items .= '<td align="left" width="4%"><span style="font-weight:bold;">' . $transaction_detail['unit_name'] . '</span></td>';
						$items .= '<td align="left" width="6%"></td>';
						$items .= '<td align="left" width="6%"></td>';
						$items .= '<td align="left" width="4%"></td>';
						$items .= '<td align="left" width="4%"></td>';
						$items .= '<td align="left" width="39%" colspan="16"></td>';
						$items .= '</tr>';

						$total_row ++;
						if($total_row % 49 == 0){

							$new_header = $this->new_header($warehouse_name, $from_date, $to_date);
							$items .= $new_header['close_table'];

							$tblhtml = $items;
							$pdf->writeHTML($tblhtml, true, false, false, false, '');

							$items = $new_header['start_table'];
							$total_row = 3;

						}

						$exist_data		  = true;
					}

					foreach ($import_period_details as $key => $import_period_opening) {

						$items .= '<tr style="' . $table_font_size . '">';
					// $items .= '<td colspan="1"></td>';
						$items .= '<td colspan="1" width="5%"></td>';
						$items .= '<td colspan="1" width="16%"></td>';
						$items .= '<td colspan="1" width="6%"></td>';
						$items .= '<td colspan="1" width="5%"></td>';
						$items .= '<td colspan="1" width="5%"></td>';
						$items .= '<td colspan="1" width="4%"></td>';
						$items .= '<td colspan="1" width="6%">'. $import_period_opening['lot_number'] .'</td>';
						$items .= '<td colspan="1" width="6%">'. $import_period_opening['serial_number'] .'</td>';
						$items .= '<td colspan="1" width="4%">'. $import_period_opening['expiry_date'] .'</td>';

						$b_f = 0;
						$items .= '<td colspan="1" align="right" width="4%">'. $b_f .'</td>';

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

						$items .= '<td align="right" width="3.2%">'. app_format_number($gr, '') .'</td>';
						$items .= '<td align="right" width="2.2%">'. app_format_number($pi, '') .'</td>';
						$items .= '<td align="right" width="2.2%">'. app_format_number($cp, '') .'</td>';
						$items .= '<td align="right" width="2.2%">'. app_format_number($pr, '') .'</td>';
						$items .= '<td align="right" width="2.2%">'. app_format_number($grt, '') .'</td>';

						$items .= '<td align="right" width="3%">'. app_format_number($do, '') .'</td>';
						$items .= '<td align="right" width="1.8%">'. app_format_number($si, '') .'</td>';
						$items .= '<td align="right" width="1.8%">'. app_format_number($cs, '') .'</td>';
						$items .= '<td align="right" width="1.8%">'. app_format_number($drt, '') .'</td>';
						$items .= '<td align="right" width="1.8%">'. app_format_number($srt, '') .'</td>';
						$items .= '<td align="right" width="1.8%">'. app_format_number($br, '') .'</td>';

						$items .= '<td align="right" width="2.5%">'. app_format_number($stf, '') .'</td>';
						$items .= '<td align="right" width="2.5%">'. app_format_number($adj, '') .'</td>';
						$items .= '<td align="right" width="2.5%">'. app_format_number($rec, '') .'</td>';
						$items .= '<td align="right" width="2.5%">'. app_format_number($iss, '') .'</td>';

						$items .= '<td align="right" width="5%">'. app_format_number($bal_qty, '') .'</td>';

						$items .= '</tr>';

						$total_row ++;
						if($total_row % 49 == 0){

							$new_header = $this->new_header($warehouse_name, $from_date, $to_date);
							$items .= $new_header['close_table'];

							$tblhtml = $items;
							$pdf->writeHTML($tblhtml, true, false, false, false, '');

							$items = $new_header['start_table'];
							$total_row = 3;

						}

					}
				}
			}

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

			$b_f_total += (float) $b_f_subtotal;
			$gr_total += (float) $gr_subtotal;
			$pi_total += (float) $pi_subtotal;
			$cp_total += (float) $cp_subtotal;
			$pr_total += (float) $pr_subtotal;
			$grt_total += (float) $grt_subtotal;
			$do_total += (float) $do_subtotal;
			$si_total += (float) $si_subtotal;
			$cs_total += (float) $cs_subtotal;
			$drt_total += (float) $drt_subtotal;
			$srt_total += (float) $srt_subtotal;
			$br_total += (float) $br_subtotal;
			$stf_total += (float) $stf_subtotal;
			$adj_total += (float) $adj_subtotal;
			$rec_total += (float) $rec_subtotal;
			$iss_total += (float) $iss_subtotal;
			$bal_qty_total += (float) $bal_qty_subtotal;

			if($exist_data){

				$items .= '<tr style="' . $table_font_size . '">';
				$items .= '<td colspan="8" width="48%"></td>';
				$items .= '<td colspan="1" class="text-right bold" align="left" width="9%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . _l('wh_sub_total') . ' : </span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="4%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($b_f_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="3.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($gr_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($pi_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($cp_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($pr_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($grt_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="3%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($do_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($si_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($cs_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($drt_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($srt_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($br_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($stf_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($adj_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($rec_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($iss_subtotal, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($bal_qty_subtotal, '') . '</span></td>';
				$items .= '</tr>';

				// subtotal
				$total_row ++;
				if($total_row % 49 == 0){

					$new_header = $this->new_header($warehouse_name, $from_date, $to_date);
					$items .= $new_header['close_table'];

					$tblhtml = $items;
					$pdf->writeHTML($tblhtml, true, false, false, false, '');

					$items = $new_header['start_table'];
					$total_row = 3;

				}
			}

			if(count($item_by_warehouse) == $total_item_by_warehouse){
				$items .= '<tr style="' . $table_font_size . '">';
				$items .= '<td colspan="26" width="100%"></td>';
				$items .= '</tr>';

				$items .= '<tr style="' . $table_font_size . '">';
				$items .= '<td colspan="8" width="48%"></td>';
				$items .= '<td colspan="1" class="text-right bold" align="left" width="9%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . _l('total') . ' : </span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="4%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($b_f_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="3.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($gr_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($pi_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($cp_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($pr_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($grt_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="3%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($do_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($si_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($cs_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($drt_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($srt_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($br_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($stf_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($adj_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($rec_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($iss_total, '') . '</span></td>';
				$items .= '<td colspan="1" class="bold" align="right" width="5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($bal_qty_total, '') . '</span></td>';
				$items .= '</tr>';

				//total
				$total_row ++;
				if($total_row % 49 == 0){

					$new_header = $this->new_header($warehouse_name, $from_date, $to_date);
					$items .= $new_header['close_table'];

					$tblhtml = $items;
					$pdf->writeHTML($tblhtml, true, false, false, false, '');

					$items = $new_header['start_table'];
					$total_row = 3;
				}
			}
		}

		if($total_warehouse == count($goods_transaction_details)){
			if($total_row % 49 > 0 && $items != ''){
				$items .= '</tbody>
				</table>';
			}

			$items .= '<table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop" cellpadding="2" >
			';
			$items  .= '<tbody class="tbody-main"  style="' . $table_font_size . '" >';
			$items .= '<tr style="' . $table_font_size . '">';
			$items .= '<td colspan="26" width="100%"></td>';
			$items .= '</tr>';
			$items .= '<tr style="' . $table_font_size . '">';
			$items .= '<th colspan="8" class="text-right bold" width="48%" ></th>';
			$items .= '<th colspan="1" class="text-right bold" align="left" width="9%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . _l('wh_grand_total') . ' : </span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="4%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($b_f_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="3.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($gr_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($pi_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($cp_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($pr_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($grt_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="3%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($do_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($si_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($cs_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($drt_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($srt_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($br_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($stf_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($adj_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($rec_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="2.5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($iss_grandtotal, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="5%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($bal_qty_grandtotal, '') . '</span></th>';
			$items .= '</tr>';
			$items .= '</tbody>
			</table>';
			$tblhtml = $items;

			$pdf->writeHTML($tblhtml, true, false, false, false, '');
		}

		
		if($total_warehouse < count($goods_transaction_details)){
			$pagebreak = '<br pagebreak="true"/>';

			$new_header = $this->new_header($warehouse_name, $from_date, $to_date);
			$items .= $new_header['close_table'];
			$items .= $pagebreak;

			$tblhtml = $items;

			$pdf->writeHTML($tblhtml, true, false, false, false, '');
			$items = '';
			$total_row = 0;
		}
	}

}
