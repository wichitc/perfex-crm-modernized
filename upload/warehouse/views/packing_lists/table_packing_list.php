<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	db_prefix() . 'wh_packing_lists.id',
	'packing_list_number',
	'clientid',
	'width',
	'volume',
	'total_amount',
	'discount_total',
	'total_after_discount',
	'datecreated',
	'approval',
	'delivery_status',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'wh_packing_lists';
$join = [];

$where = [];

if ($this->ci->input->post('from_date')) {
	array_push($where, "AND date_format(datecreated, '%Y-%m-%d') >= '" . date('Y-m-d', strtotime(to_sql_date($this->ci->input->post('from_date')))) . "'");
}
if ($this->ci->input->post('to_date')) {
	array_push($where, "AND date_format(datecreated, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime(to_sql_date($this->ci->input->post('to_date')))) . "'");
}
if ($this->ci->input->post('staff_id') && $this->ci->input->post('staff_id') != '') {
	array_push($where, 'AND staff_id IN (' . implode(', ', $this->ci->input->post('staff_id')) . ')');
}

if ($this->ci->input->post('status_id') && $this->ci->input->post('status_id') != '') {
	$status_arr = $this->ci->input->post('status_id');
	if (in_array(5, $this->ci->input->post('status_id'))) {
		$status_arr[] = 0;
	}
	array_push($where, 'AND approval IN (' . implode(', ', $status_arr) . ')');

}

if ($this->ci->input->post('delivery_id') && $this->ci->input->post('delivery_id') != '') {
	array_push($where, 'AND delivery_note_id IN (' . implode(', ', $this->ci->input->post('delivery_id')) . ')');
}

if (!has_permission('wh_packing_list', '', 'view')) {
	array_push($where, 'AND (' . db_prefix() . 'wh_packing_lists.staff_id=' . get_staff_user_id() . ')');
}

$custom_fields = get_custom_fields('iv_packing_list', [
	'show_on_table' => 1,
]);


foreach ($custom_fields as $key => $field) {
	$selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

	array_push($customFieldsColumns, $selectAs);
	array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
	array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'wh_packing_lists.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="iv_packing_list" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
	@$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'wh_packing_lists.id', 'packing_list_name', 'width', 'height', 'lenght', 'volume', 'additional_discount']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	for ($i = 0; $i < count($aColumns); $i++) {
		if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
			$_data = $aRow[strafter($aColumns[$i], 'as ')];
		} else {
			$_data = $aRow[$aColumns[$i]];
		}

		if ($aColumns[$i] == db_prefix() . 'wh_packing_lists.id') {
			$_data = $aRow[db_prefix() . 'wh_packing_lists.id'];
		} elseif ($aColumns[$i] == 'packing_list_number') {

			$name = '<a href="' . admin_url('warehouse/view_packing_list/' . $aRow['id']) . '" onclick="init_packing_list(' . $aRow['id'] . '); return false;">' . $aRow['packing_list_number'] . ' - ' . $aRow['packing_list_name'] . '</a>';

			$name .= '<div class="row-options">';
			$name .= '<a href="' . admin_url('warehouse/manage_packing_list/' . $aRow['id']) . '" >' . _l('view') . '</a>';

			if ((has_permission('wh_packing_list', '', 'edit') || is_admin()) && ($aRow['approval'] == 0)) {
				$name .= ' | <a href="' . admin_url('warehouse/packing_list/' . $aRow['id']) . '" >' . _l('edit') . '</a>';
			}

			if ((has_permission('wh_packing_list', '', 'delete') || is_admin()) && ($aRow['approval'] == 0)) {
				$name .= ' | <a href="' . admin_url('warehouse/delete_packing_list/' . $aRow['id']) . '" class="text-danger _delete" >' . _l('delete') . '</a>';
			}

			$name .= '</div>';

			$_data = $name;

		} elseif ($aColumns[$i] == 'clientid') {
			$_data = get_company_name($aRow['clientid']);
		} elseif ($aColumns[$i] == 'width') {
			$_data = $aRow['width'] . ' x ' . $aRow['height'] . ' x ' . $aRow['lenght'];
		} elseif ($aColumns[$i] == 'volume') {
			$_data = app_format_money($aRow['volume'], '');
		} elseif ($aColumns[$i] == 'total_amount') {
			$_data = app_format_money($aRow['total_amount'], '');
		} elseif ($aColumns[$i] == 'discount_total') {
			$_data = app_format_money($aRow['discount_total'] + $aRow['additional_discount'], '');
		} elseif ($aColumns[$i] == 'total_after_discount') {
			$_data = app_format_money($aRow['total_after_discount'], '');
		} elseif ($aColumns[$i] == 'datecreated') {
			$_data = _dt($aRow['datecreated']);

		} elseif ($aColumns[$i] == 'approval') {
			$approve_data = '';
			if ($aRow['approval'] == 1) {
				$approve_data = '<span class="label label-tag tag-id-1 label-tab1"><span class="tag">' . _l('approved') . '</span><span class="hide">, </span></span>&nbsp';
			} elseif ($aRow['approval'] == 0) {
				$approve_data = '<span class="label label-tag tag-id-1 label-tab2"><span class="tag">' . _l('not_yet_approve') . '</span><span class="hide">, </span></span>&nbsp';
			} elseif ($aRow['approval'] == -1) {
				$approve_data = '<span class="label label-tag tag-id-1 label-tab3"><span class="tag">' . _l('reject') . '</span><span class="hide">, </span></span>&nbsp';
			}

			$_data = $approve_data;
		} elseif ($aColumns[$i] == 'delivery_status') {

			$_data = render_delivery_status_html($aRow['id'], 'packing_list', $aRow['delivery_status']);
		}

		$row[] = $_data;
	}
	$output['aaData'][] = $row;

}
