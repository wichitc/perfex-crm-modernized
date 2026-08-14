<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'acc_claims.id as id',
    db_prefix() . 'acc_claims.expense_date as expense_date',
    db_prefix() . 'projects.name as project_name',
    db_prefix() . 'acc_project_budget_categories.name as category_name',
    'CONCAT(' . db_prefix() . 'staff.firstname, " ", ' . db_prefix() . 'staff.lastname) as staff_name',
    db_prefix() . 'acc_claims.amount as amount',
    db_prefix() . 'acc_claims.status as status',
    db_prefix() . 'acc_claims.description as description',
    db_prefix() . 'acc_claims.project_id as project_id',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'acc_claims';

$join = [
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'acc_claims.project_id',
    'LEFT JOIN ' . db_prefix() . 'acc_project_budget_categories ON ' . db_prefix() . 'acc_project_budget_categories.id = ' . db_prefix() . 'acc_claims.category_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'acc_claims.staff_id',
];

$where = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];
$currency = get_base_currency();
$currency_name = $currency ? $currency->name : '';
$check_appr = $this->ci->accounting_model->get_approve_setting('claim');

foreach ($rResult as $aRow) {
    $row = [];
    
    // Date
    $row[] = '<a href="' . admin_url('accounting/view_claim/' . $aRow['id']) . '"><strong>' . _d($aRow['expense_date']) . '</strong></a>';
    
    // Project Link
    $row[] = '<a href="' . admin_url('projects/view/' . $aRow['project_id']) . '"><strong>' . html_escape($aRow['project_name']) . '</strong></a>';
    
    // Budget Category
    $row[] = html_escape($aRow['category_name']);
    
    // Staff
    $row[] = html_escape($aRow['staff_name']);
    
    // Amount Claimed
    $row[] = '<span class="bold text-danger">' . app_format_money($aRow['amount'], $currency_name) . '</span>';
    
    // Refunded
    $refunds = $this->ci->accounting_model->get_claim_refunds($aRow['id']);
    $total_refunded = 0;
    foreach ($refunds as $ref) {
        $total_refunded += floatval($ref['amount']);
    }
    $remaining_to_refund = floatval($aRow['amount']) - $total_refunded;
    $row[] = '<span class="bold text-success">' . app_format_money($total_refunded, $currency_name) . '</span>';
    
    // Status
    $status_class = 'default';
    if ($aRow['status'] == 'draft') $status_class = 'default';
    if ($aRow['status'] == 'pending_approval') $status_class = 'info';
    if ($aRow['status'] == 'approved') $status_class = 'warning';
    if ($aRow['status'] == 'rejected') $status_class = 'danger';
    if ($aRow['status'] == 'paid') $status_class = 'success';
    $row[] = '<span class="label label-' . $status_class . '">' . html_escape(_l('acc_' . $aRow['status'])) . '</span>';
    
    // Description
    $row[] = '<p class="text-muted">' . html_escape($aRow['description']) . '</p>';
    
    // Options
    $options = '<a href="' . admin_url('accounting/view_claim/' . $aRow['id']) . '" class="btn btn-default btn-xs mright5"><i class="fa fa-eye"></i> ' . _l('view') . '</a>';
    if (($aRow['status'] == 'draft' || $aRow['status'] == 'pending_approval' || $aRow['status'] == 'rejected') && (has_permission('acc_claims', '', 'edit') || is_admin())) {
        $options .= '<a href="' . admin_url('accounting/edit_claim/' . $aRow['id']) . '" class="btn btn-default btn-xs mright5"><i class="fa fa-edit"></i> ' . _l('edit') . '</a>';
    }
    if ($aRow['status'] == 'approved' && $remaining_to_refund > 0 && (has_permission('acc_claims', '', 'edit') || is_admin())) {
        $options .= '<button class="btn btn-success btn-xs btn-refund mright5" data-id="' . $aRow['id'] . '" data-remaining="' . $remaining_to_refund . '"><i class="fa fa-money"></i> ' . _l('acc_pay_refund') . '</button>';
    }
    if (has_permission('acc_claims', '', 'delete') || is_admin()) {
        $options .= '<a href="' . admin_url('accounting/delete_claim/' . $aRow['id']) . '" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>';
    }
    $row[] = $options;
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
