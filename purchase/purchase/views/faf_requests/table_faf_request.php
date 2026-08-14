<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'vendor_id',
    'reference_number',
    'genrated_po_number',
    'amount_request',
    'department',
    'requestor',
    'created_at',
    'approve_status',
    'created_by',
    ];

$join = [
    'LEFT JOIN ' . db_prefix() . 'pur_vendor ON ' . db_prefix() . 'pur_vendor.userid = ' . db_prefix() . 'pur_faf_requests.vendor_id',
    'LEFT JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . 'pur_faf_requests.department',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'pur_faf_requests';

$where  = [];

$user = get_staff_user_id();


if(!has_permission('purchase_orders', '', 'view')){
   array_push($where, 'AND (' . db_prefix() . 'pur_faf_requests.created_by = '.get_staff_user_id().' OR ' . db_prefix() . 'pur_faf_requests.requestor = '.get_staff_user_id().' OR ' . db_prefix() . 'pur_faf_requests.vendor_id IN (SELECT vendor_id FROM ' . db_prefix() . 'pur_vendor_admin WHERE staff_id=' . get_staff_user_id() . ') OR '.get_staff_user_id().' IN (SELECT staffid FROM ' . db_prefix() . 'pur_approval_details WHERE ' . db_prefix() . 'pur_approval_details.rel_type = "faf_requests" AND ' . db_prefix() . 'pur_approval_details.rel_id = '.db_prefix().'pur_faf_requests.id))');
}


if ($this->ci->input->post('vendor')
    && count($this->ci->input->post('vendor')) > 0) {
    array_push($where, 'AND vendor_id IN (' . implode(',', $this->ci->input->post('vendor')) . ')');
}

if ($this->ci->input->post('from_date')
    && $this->ci->input->post('from_date') != '') {
    array_push($where, 'AND date(created_at) >= "'.to_sql_date($this->ci->input->post('from_date')).'"');
}

if ($this->ci->input->post('to_date')
    && $this->ci->input->post('to_date') != '') {
    array_push($where, 'AND date(created_at) <= "'.to_sql_date($this->ci->input->post('to_date')).'"');
}


if ($this->ci->input->post('status') && count($this->ci->input->post('status')) > 0) {
    array_push($where, 'AND approve_status IN (' . implode(',', $this->ci->input->post('status')) . ')');
}

if ($this->ci->input->post('department')
    && count($this->ci->input->post('department')) > 0) {
    array_push($where, 'AND department IN (' . implode(',', $this->ci->input->post('department')) . ')');
}

if ($this->ci->input->post('requestor')
    && count($this->ci->input->post('requestor')) > 0) {
    array_push($where, 'AND requestor IN (' . implode(',', $this->ci->input->post('requestor')) . ')');
}




$filter = [];


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [ db_prefix().'pur_faf_requests.id as id', 'company', 'name', 'currency']);

$output  = $result['output'];
$rResult = $result['rResult'];

$this->ci->load->model('purchase/purchase_model');

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = '<a href="'.admin_url('purchase/faf_requests/'.$aRow['id']).'" onclick="init_faf_request(' . $aRow['id'] . '); return false;">'.$aRow['reference_number'].'</a>';


    $row[] = '<a href="'.admin_url('purchase/vendor/'.$aRow['vendor_id']).'">'.$aRow['company'].'</a>';

    $base_currency = get_base_currency_pur();
    if($aRow['currency'] != 0 && $aRow['currency'] != '' && $aRow['currency'] !=' null'){
        $base_currency = pur_get_currency_by_id($aRow['currency']);
    }


    $row[] = app_format_money($aRow['amount_request'], $base_currency->id);

    $row[] = $aRow['genrated_po_number'];

    $row[] = $aRow['name'];

    $row[] = '<a href="'.admin_url('staff/profile/'.$aRow['requestor']).'">'.get_staff_full_name($aRow['requestor']).'</a>';

    $row[] = _dt($aRow['created_at']);

    $row[] = get_status_approve($aRow['approve_status']);


    $options = '';

    $list_approve_status = $this->ci->purchase_model->get_list_approval_details($aRow['id'],'faf_requests');
    if(has_permission('purchase_faf', '', 'edit') && $aRow['approve_status'] != 2 && count($list_approve_status) == 0){
        $options .= '<a href="'.admin_url('purchase/faf_request/'.$aRow['id']).'" class="btn btn-icon btn-warning"><i class="fa fa-pencil"></i><a>';
    }


    if(has_permission('purchase_faf', '', 'delete')){
        $options .= '<a href="'.admin_url('purchase/delete_faf_request/'.$aRow['id']).'" class="btn btn-icon btn-danger _delete"><i class="fa fa-remove"></i><a>';
    }

    $row[] = $options;

    $output['aaData'][] = $row;
}

echo json_encode($output);
die();
