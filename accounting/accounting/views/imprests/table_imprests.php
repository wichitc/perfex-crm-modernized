<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'acc_imprest_requests.id as id',
    db_prefix() . 'acc_imprest_requests.reference_no as reference_no',
    db_prefix() . 'projects.name as project_name',
    db_prefix() . 'acc_project_budget_categories.name as category_name',
    db_prefix() . 'acc_imprest_requests.request_date as request_date',
    'CONCAT(' . db_prefix() . 'staff.firstname, " ", ' . db_prefix() . 'staff.lastname) as staff_name',
    db_prefix() . 'acc_imprest_requests.amount_requested as amount_requested',
    db_prefix() . 'acc_imprest_requests.amount_retired as amount_retired',
    db_prefix() . 'acc_imprest_requests.variance as variance',
    db_prefix() . 'acc_imprest_requests.status as status',
    db_prefix() . 'acc_imprest_requests.project_id as project_id',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'acc_imprest_requests';

$join = [
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'acc_imprest_requests.project_id',
    'LEFT JOIN ' . db_prefix() . 'acc_project_budget_categories ON ' . db_prefix() . 'acc_project_budget_categories.id = ' . db_prefix() . 'acc_imprest_requests.category_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'acc_imprest_requests.staff_id',
];

$where = [];

$project_id = $this->ci->input->post('project_id');
$category_id = $this->ci->input->post('category_id');
$staff_id = $this->ci->input->post('staff_id');
$status = $this->ci->input->post('status');

if (!empty($project_id)) {
    $where[] = 'AND ' . db_prefix() . 'acc_imprest_requests.project_id = ' . intval($project_id);
}
if (!empty($category_id)) {
    $where[] = 'AND ' . db_prefix() . 'acc_imprest_requests.category_id = ' . intval($category_id);
}
if (!empty($staff_id)) {
    $where[] = 'AND ' . db_prefix() . 'acc_imprest_requests.staff_id = ' . intval($staff_id);
}
if (!empty($status)) {
    $where[] = 'AND ' . db_prefix() . 'acc_imprest_requests.status = ' . $this->ci->db->escape($status);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];
$currency = get_base_currency();
$currency_name = $currency ? $currency->name : '';

foreach ($rResult as $aRow) {
    $row = [];
    
    // Ref No
    $row[] = '<a href="' . admin_url('accounting/view_imprest/' . $aRow['id']) . '"><strong>' . html_escape($aRow['reference_no']) . '</strong></a>';
    
    // Project Link
    $row[] = '<a href="' . admin_url('projects/view/' . $aRow['project_id']) . '"><strong>' . html_escape($aRow['project_name']) . '</strong></a>';
    
    // Budget Category
    $row[] = html_escape($aRow['category_name']);
    
    // Date
    $row[] = _d($aRow['request_date']);
    
    // Staff
    $row[] = html_escape($aRow['staff_name']);
    
    // Requested
    $row[] = '<span class="bold text-primary">' . app_format_money($aRow['amount_requested'], $currency_name) . '</span>';
    
    // Retired
    $row[] = $aRow['amount_retired'] > 0 ? '<span class="bold text-warning">' . app_format_money($aRow['amount_retired'], $currency_name) . '</span>' : '-';
    
    // Variance
    $variance = floatval($aRow['variance']);
    $variance_class = 'text-primary';
    if ($variance > 0) $variance_class = 'text-success'; // Under-spent
    if ($variance < 0) $variance_class = 'text-danger'; // Over-spent
    $row[] = $aRow['amount_retired'] > 0 ? '<span class="bold ' . $variance_class . '">' . app_format_money($variance, $currency_name) . '</span>' : '-';
    
    // Status
    $status_class = 'default';
    if ($aRow['status'] == 'draft') $status_class = 'default';
    if ($aRow['status'] == 'pending_approval') $status_class = 'info';
    if ($aRow['status'] == 'rejected') $status_class = 'danger';
    if ($aRow['status'] == 'disbursed') $status_class = 'warning';
    if ($aRow['status'] == 'completed') $status_class = 'success';
    if ($aRow['status'] == 'pending_refund') $status_class = 'warning';
    if ($aRow['status'] == 'pending_payment') $status_class = 'warning';
    $row[] = '<span class="label label-' . $status_class . '">' . html_escape(_l('acc_' . $aRow['status'])) . '</span>';
    
    // Options
    $options = '<a href="' . admin_url('accounting/view_imprest/' . $aRow['id']) . '" class="btn btn-default btn-xs mright5 mbot5"><i class="fa fa-eye"></i> ' . _l('view') . '</a>';
    if (has_permission('acc_imprests', '', 'edit') || is_admin()) {
        if ($aRow['status'] == 'draft' || $aRow['status'] == 'pending_approval' || $aRow['status'] == 'rejected') {
            $options .= '<a href="' . admin_url('accounting/edit_imprest/' . $aRow['id']) . '" class="btn btn-default btn-xs mright5 mbot5"><i class="fa fa-pencil-square-o"></i> ' . _l('acc_edit_request') . '</a>';
        }
        if ($aRow['status'] == 'disbursed') {
            $options .= '<a href="' . admin_url('accounting/retire_imprest/' . $aRow['id']) . '" class="btn btn-success btn-xs mright5 mbot5"><i class="fa fa-check-circle"></i> ' . _l('acc_retire_cash') . '</a>';
        }
        if (in_array($aRow['status'], ['completed', 'pending_refund', 'pending_payment'])) {
            $options .= '<a href="' . admin_url('accounting/retire_imprest/' . $aRow['id']) . '" class="btn btn-warning btn-xs mright5 mbot5"><i class="fa fa-pencil-square-o"></i> ' . _l('acc_edit_retirement') . '</a>';
        }
    }
    if (has_permission('acc_imprests', '', 'delete') || is_admin()) {
        if (in_array($aRow['status'], ['completed', 'pending_refund', 'pending_payment'])) {
            $options .= '<a href="' . admin_url('accounting/delete_imprest_retirement/' . $aRow['id']) . '" class="btn btn-danger btn-xs mright5 _delete mbot5"><i class="fa fa-trash"></i> ' . _l('acc_delete_retirement') . '</a>';
        }
        $options .= '<a href="' . admin_url('accounting/delete_imprest/' . $aRow['id']) . '" class="btn btn-danger btn-icon _delete mbot5"><i class="fa fa-remove"></i></a>';
    }
    $row[] = $options;
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
