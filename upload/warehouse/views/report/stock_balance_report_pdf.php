<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Background color
$dimensions    = $this->getPageDimensions();
$font_size     = get_option('pdf_font_size');
$pdf_font_size = ($font_size + 1);
$table_font_size = 'font-size:'.$pdf_font_size.'px;';

$arr_inventory_manage = $stock_balance['arr_inventory_manage'];
$warehouse_ids = $stock_balance['warehouse_ids'];
$inventory_manage = $stock_balance['inventory_manage'];
$serial_numbers   = $stock_balance['serial_numbers'];

if (isset($arr_inventory_manage) && count($arr_inventory_manage) > 0) {
	$grand_total_qty  = 0;
	$grand_total_cost = 0;
	$total_inventory_manage = 0;
	$get_base_currency =  get_base_currency();
	if($get_base_currency){
		$base_currency_id = $get_base_currency->id;
	}else{
		$base_currency_id = 0;
	}


	foreach ($arr_inventory_manage as $iv_key => $inventory_manages) {
		$iv_key_explode = explode('_', $iv_key);
		$warehouse_id   = isset($iv_key_explode[0]) ? $iv_key_explode[0] : 0;
		$warehouse_name = isset($warehouse_ids[$warehouse_id]) ? $warehouse_ids[$warehouse_id] : '';
		
		$total_inventory_manage++;

		if (isset($inventory_manages) && count($inventory_manages) > 0) {
			$items = '';

			$items .= '<table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop"  cellpadding="2" >
			<thead style="' . $table_font_size . '">';
			$items .= '
			<tr style="font-size: '.$pdf_font_size.'px">
			<td align="left" width="20%"><span>'._l('clients_invoice_dt_date') .': '.date('d/m/Y H:i') .'</span></td>
			<td align="left" width="80%"><span>'. _l('warehouse_filter') .': '. $warehouse_name .'</span></td>
			<td align="right" width="0%"></td>
			</tr>';
			$items .= '<tr height="30" style="color:black;' . $table_font_size . '; ">';
			$items .= '
			<th width="7%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" ><strong>' . _l('wh_item_code') . '</strong></th>
			<th width="15%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('description') . '</strong></th>
			<th width="8%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_item_type') . '</strong></th>
			<th width="7%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_group') . '</strong></th>
			<th width="7%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('expense_dt_table_heading_category') . '</strong></th>
			<th width="9%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_batch_no') . '</strong></th>
			<th width="10%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_serial_hashtag') . '</strong></th>
			<th width="7%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('expiry_date') . '</strong></th>
			<th width="6%" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_uom') . '</strong></th>
			<th width="6%" align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_bal_qty') . '</strong></th>
			<th width="9%" align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_unit_cost') . '</strong></th>
			<th width="9%" align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_total_cost') . '</strong></th>
			</tr>
			</thead>
			<tbody class="tbody-main"  style="' . $table_font_size . '" >';

			foreach ($inventory_manages as $commodity_id => $commodity_value) { 
				$total_qty      = 0;
				$total_cost     = 0;

					// render item table start
				foreach ($commodity_value as $key => $inventory_value) {
					$order_number     = $key + 1;
					$total_serial_qty = 0;

					if($key == 0){ 
						$items .= '<tr style="' . $table_font_size . '">';
					// $items .= '<td align="center" width="4%" class="hide">' . $order_number . '</td>';
						$items .= '<td align="left" width="7%"><span style="font-weight:bold;">' . $inventory_value['commodity_code'] . '</span></td>';
						$items .= '<td align="left" width="15%"><span style="font-weight:bold;">' . $inventory_value['description'] . '</span></td>';
						$items .= '<td align="left" width="8%"><span style="font-weight:bold;">' . $inventory_value['commondity_name'] . '</span></td>';
						$items .= '<td align="left" width="7%"><span style="font-weight:bold;">' . $inventory_value['name'] . '</span></td>';
						$items .= '<td align="left" width="7%"><span style="font-weight:bold;">' . $inventory_value['sub_group_name'] . '</span></td>';
						$items .= '<td align="left" width="9%"></td>';
						$items .= '<td align="left" width="10%"></td>';
						$items .= '<td align="left" width="7%"></td>';
						$items .= '<td align="left" width="6%"><span style="font-weight:bold;">' . $inventory_value['unit_name'] . '</span></td>';
						$items .= '<td align="right" width="6%"></td>';
						$items .= '<td align="right" width="9%"></td>';
						$items .= '<td align="right" width="9%"></td>';
						$items .= '</tr>';
					}

					if (isset($serial_numbers[$inventory_value['id']]) && count($serial_numbers[$inventory_value['id']]) > 0) {
						foreach ($serial_numbers[$inventory_value['id']] as $serial_key => $serial_number) {

							$serial_number_total_cost = (float) $inventory_value['purchase_price'] * 1;
							$total_serial_qty++;

							$items .= '<tr style="' . $table_font_size . '">';
							$items .= '<td colspan="5"></td>';
							$items .= '<td><span style="font-weight:bold;">' . $inventory_value['lot_number'] . '</span></td>';
							$items .= '<td>' . $serial_number['serial_number'] . '</td>';
							$items .= '<td><span style="font-weight:bold;">' . $inventory_value['expiry_date'] . '</span></td>';
							$items .= '<td></td>';
							$items .= '<td align="right">1</td>';
							$items .= '<td align="right">' . app_format_money((float) $inventory_value['purchase_price'], $base_currency_id) . '</td>';
							$items .= '<td align="right">' . app_format_money($serial_number_total_cost, $base_currency_id) . '</td>';
							$items .= '</tr>';
						}
					}

					if ((float) $inventory_value['inventory_number'] > (float) $total_serial_qty) {

						$no_serial_number_qty        = (float) $inventory_value['inventory_number'] - (float) $total_serial_qty;
						$no_serial_number_total_cost = (float) $inventory_value['purchase_price'] * (float) $no_serial_number_qty;

						$items .= '<tr style="' . $table_font_size . '">';
						$items .= '<td colspan="5"></td>';
						$items .= '<td><span style="font-weight:bold;">' . $inventory_value['lot_number'] . '</span></td>';
						$items .= '<td>' . _l('wh_no_serial_number') . '</td>';
						$items .= '<td><span style="font-weight:bold;">' . $inventory_value['expiry_date'] . '</span></td>';
						$items .= '<td></td>';
						$items .= '<td align="right">' . app_format_number($no_serial_number_qty, '') . '</td>';
						$items .= '<td align="right">' . app_format_money((float) $inventory_value['purchase_price'], $base_currency_id) . '</td>';
						$items .= '<td align="right">' . app_format_money($no_serial_number_total_cost, $base_currency_id) . '</td>';
						$items .= '</tr>';
					}

					$total_qty += (float) $inventory_value['inventory_number'];
					$total_cost += (float) $inventory_value['purchase_price'] * (float) $inventory_value['inventory_number'];
				}

				$grand_total_qty += (float) $total_qty;
				$grand_total_cost += (float) $total_cost;

				$items .= '<tr style="' . $table_font_size . '">';
				$items .= '<th colspan="8" class="text-right bold" align="right"><span style="font-weight:bold;">' . _l('total') . ' : </span></th>';
				$items .= '<th colspan="1" class="bold"></th>';
				$items .= '<th colspan="1" class="bold" align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($total_qty, '') . '</span></th>';
				$items .= '<th colspan="1" class="bold"></th>';
				$items .= '<th colspan="1" class="bold" align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_money($total_cost, $base_currency_id) . '</span></th>';
				$items .= '</tr>';

			}
			$items .= '</tbody>
			</table>';
			if($total_inventory_manage < count($arr_inventory_manage) ){
				$items .= '<br pagebreak="true"/>';
			}

			$tblhtml = $items;
			$pdf->writeHTML($tblhtml, true, false, false, false, '');
		}

		if(count($arr_inventory_manage) == $total_inventory_manage){
			$items = '<table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop"  >
			';
			$items  .= '<tbody class="tbody-main"  style="' . $table_font_size . '" >';
			$items .= '<tr style="' . $table_font_size . '">';
			$items .= '<th colspan="8" class="text-right bold" align="right"  width="70%"><span style="font-weight:bold;">' . _l('wh_grand_total') . ' : </span></th>';
			$items .= '<th colspan="1" class="bold" width="6%"></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="6%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_number($grand_total_qty, '') . '</span></th>';
			$items .= '<th colspan="1" class="bold" width="9%"></th>';
			$items .= '<th colspan="1" class="bold" align="right" width="9%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><span style="font-weight:bold;">' . app_format_money($grand_total_cost, $base_currency_id) . '</span></th>';
			$items .= '</tr>';
			$items .= '</tbody>
			</table>';
			$tblhtml = $items;
			$pdf->writeHTML($tblhtml, true, false, false, false, '');

		}
	}
}
