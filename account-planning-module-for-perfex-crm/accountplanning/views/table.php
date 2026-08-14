<?php

defined('BASEPATH') or exit('No direct script access allowed');

$hasPermissionDelete = has_permission('accountplanning', '', 'delete');

$custom_fields = get_table_custom_fields('accountplanning');
$customFieldsColumns = [];
$this->ci->db->query("SET sql_mode = ''");
$sTable       = db_prefix() . 'accountplanning';
$clientsTbl   = db_prefix() . 'clients';
$aColumns = [
    '1',
    'id',
    'subject',
    $clientsTbl . '.company as company',
    'date',
    'status',
    'objectives',
    'revenue_next_year',
    'wallet_share',
    'client_status',
    'bcg_model',
    'margin',
];
$join = [
    'LEFT JOIN ' . $clientsTbl . ' ON ' . $clientsTbl . '.userid=' . $sTable . '.client_id'
];
foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    $customFieldsColumns[] = $selectAs;
    $aColumns[] = 'ctable_' . $key . '.value as ' . $selectAs;
    $join[] = 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . $sTable . '.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="accountplanning" AND ctable_' . $key . '.fieldid=' . (int)$field['id'];
}

$sIndexColumn = 'id';
$where        = [];

if(isset($client_id)){
    array_push($where, 'AND ' . $sTable . '.client_id=' . (int)$client_id);
}
$ci = $this->ci;
if ($ci->input->get('filter_status')) {
    array_push($where, 'AND ' . $sTable . '.status="' . $ci->db->escape_str($ci->input->get('filter_status')) . '"');
}
if ($ci->input->get('filter_client_id')) {
    array_push($where, 'AND ' . $sTable . '.client_id=' . (int)$ci->input->get('filter_client_id'));
}
if ($ci->input->get('filter_bcg_model')) {
    array_push($where, 'AND ' . $sTable . '.bcg_model="' . $ci->db->escape_str($ci->input->get('filter_bcg_model')) . '"');
}
if ($ci->input->get('filter_client_status')) {
    array_push($where, 'AND ' . $sTable . '.client_status="' . $ci->db->escape_str($ci->input->get('filter_client_status')) . '"');
}
if ($ci->input->get('filter_date_from')) {
    array_push($where, 'AND ' . $sTable . '.date>="' . $ci->db->escape_str($ci->input->get('filter_date_from')) . '"');
}
if ($ci->input->get('filter_date_to')) {
    array_push($where, 'AND ' . $sTable . '.date<="' . $ci->db->escape_str($ci->input->get('filter_date_to')) . '"');
}
if ($ci->input->get('filter_search')) {
    $q = $ci->db->escape('%' . $ci->input->get('filter_search') . '%');
    array_push($where, 'AND (' . $sTable . '.subject LIKE ' . $q . ' OR ' . $sTable . '.objectives LIKE ' . $q . ' OR ' . $sTable . '.vision LIKE ' . $q . ' OR ' . $sTable . '.mission LIKE ' . $q . ' OR ' . $clientsTbl . '.company LIKE ' . $q . ')');
}
if ($ci->input->get('filter_pic')) {
    $pic = (int) $ci->input->get('filter_pic');
    if ($pic > 0) {
        $picEsc = $ci->db->escape_str($pic);
        array_push($where, 'AND EXISTS (SELECT 1 FROM ' . db_prefix() . 'accountplanning_task t WHERE t.accountplanning_id = ' . $sTable . '.id AND (t.pic = "' . $picEsc . '" OR t.pic LIKE "' . $picEsc . '|%" OR t.pic LIKE "%|' . $picEsc . '|%" OR t.pic LIKE "%|' . $picEsc . '"))');
    }
}
if ($ci->input->get('filter_has_invoice') === '1') {
    array_push($where, 'AND EXISTS (SELECT 1 FROM ' . db_prefix() . 'accountplanning_relations ar WHERE ar.accountplanning_id = ' . $sTable . '.id AND ar.rel_type = "invoice")');
}
if ($ci->input->get('filter_has_proposal') === '1') {
    array_push($where, 'AND EXISTS (SELECT 1 FROM ' . db_prefix() . 'accountplanning_relations ar WHERE ar.accountplanning_id = ' . $sTable . '.id AND ar.rel_type = "proposal")');
}
if ($ci->input->get('filter_overdue') === '1') {
    array_push($where, 'AND EXISTS (SELECT 1 FROM ' . db_prefix() . 'accountplanning_task t WHERE t.accountplanning_id = ' . $sTable . '.id AND t.deadline < "' . date('Y-m-d') . '" AND t.status != "Complete" AND (t.convert_to_task = 0 OR t.convert_to_task IS NULL))');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,['client_id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // Bulk actions
    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    // User id
    $row[] = $aRow['id'];

    // Company
    $company  = $aRow['subject'];
    $isPerson = false;

    if ($company == '') {
        $company  = _l('accountplanning');
        $isPerson = true;
    }

    $url = admin_url('accountplanning/view/' . $aRow['id']);

    $company = '<a href="' . $url . '">' . $company . '</a>';

    $company .= '<div class="row-options">';
    $company .= '<a href="' . $url . '">' . _l('view') . '</a>';
    $url_new = 'accountplanning/delete/' . $aRow['id'];
    $company .= ' | <a href="#" onclick="copy_accountplanning(' . (int)$aRow['id'] . ',' . (int)$aRow['client_id'] . ',\'' . htmlspecialchars($aRow['company'], ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($aRow['subject'], ENT_QUOTES, 'UTF-8') . '\');return false;">'._l('copy_accountplanning').'</a>';
    if ($hasPermissionDelete) {
        $company .= ' | <a href="' . admin_url($url_new) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $company .= '</div>';
    $row[] = $company;
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['client_id']) . '">' . $aRow['company'] .'</a>';
    if($aRow['date'] != ''){
        $row[] = date('F - Y', strtotime($aRow['date']));
    }else {
        $row[] =  _d($aRow['date']);
    }
    $status_label = isset($aRow['status']) && $aRow['status'] ? _l('ap_status_' . $aRow['status']) : _l('ap_status_draft');
    $row[] = $status_label;

    $row[] = $aRow['objectives'];
	$basecur = get_base_currency();
    $row[] = app_format_money($aRow['revenue_next_year'],$basecur);
    if($aRow['margin']){
        $row[] = $aRow['margin'].' %';

    }else{
        $row[] = $aRow['margin'];
    }
    if($aRow['wallet_share']){
        $row[] = $aRow['wallet_share'].' %';

    }else{
        $row[] = $aRow['wallet_share'];
    }
    $client_status = '';
    if($aRow['client_status'] == 'Red'){
        $client_status = '<label class="text-danger">'.$aRow['client_status'].'</label>';
    }elseif($aRow['client_status'] == 'Yellow'){
        $client_status = '<label class="text-warning">'.$aRow['client_status'].'</label>';
    }elseif ($aRow['client_status'] == 'Green') {
        $client_status = '<label class="text-success">'.$aRow['client_status'].'</label>';
    }
    $row[] = $client_status;
    $row[] = $aRow['bcg_model'];

    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $output['aaData'][] = $row;
}
