<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'acc_project_budgets.id as id',
    db_prefix() . 'projects.name as project_name',
    'CONCAT(' . db_prefix() . 'staff.firstname, " ", ' . db_prefix() . 'staff.lastname) as owner_name',
    db_prefix() . 'acc_project_budgets.status as status',
    db_prefix() . 'acc_project_budgets.project_id as project_id',
    db_prefix() . 'acc_project_budgets.description as description',
    db_prefix() . 'acc_project_budgets.start_date as start_date',
    db_prefix() . 'acc_project_budgets.end_date as end_date',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'acc_project_budgets';

$join = [
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'acc_project_budgets.project_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'acc_project_budgets.owner_id',
];

$where = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'acc_project_budgets.id as budget_id',
]);
$output  = $result['output'];
$rResult = $result['rResult'];
$currency = get_base_currency();
$currency_name = $currency ? $currency->name : '';

foreach ($rResult as $aRow) {
    $row = [];
    
    // Project Name with Link
    $projectOutput = '<a href="' . admin_url('projects/view/' . $aRow['project_id']) . '"><strong>' . html_escape($aRow['project_name']) . '</strong></a>';
    $projectOutput .= '<p class="text-muted mtop5 no-mbot">' . html_escape($aRow['description']) . '</p>';
    $row[] = $projectOutput;
    
    // Project Manager
    $row[] = html_escape($aRow['owner_name']);
    
    // Start Date
    $row[] = !empty($aRow['start_date']) ? _d($aRow['start_date']) : '';
    
    // End Date
    $row[] = !empty($aRow['end_date']) ? _d($aRow['end_date']) : '';
    
    // Summary values
    $summary = $this->ci->accounting_model->get_project_budget_summary($aRow['project_id'], $aRow['id']);
    
    // Allocated
    $row[] = '<span class="bold text-primary">' . app_format_money($summary['allocated'], $currency_name) . '</span>';
    
    // Spent
    $row[] = '<span class="bold text-warning">' . app_format_money($summary['spent'], $currency_name) . '</span>';
    
    // Remaining
    $remaining_class = $summary['remaining'] >= 0 ? 'text-success' : 'text-danger';
    $row[] = '<span class="bold ' . $remaining_class . '">' . app_format_money($summary['remaining'], $currency_name) . '</span>';
    
    // Status
    $status_class = $aRow['status'] == 'approved' ? 'success' : 'default';
    $row[] = '<span class="label label-' . $status_class . '">' . html_escape(_l('acc_' . $aRow['status'])) . '</span>';
    
    // Options
    $options = '';
    if (has_permission('acc_project_budgets', '', 'view') || is_admin()) {
        $options .= icon_btn(admin_url('accounting/project_budget_detail/' . $aRow['id']), 'fa fa-eye', 'btn-info', ['title' => _l('acc_view_details')]);
    }
    if (has_permission('acc_project_budgets', '', 'edit') || is_admin()) {
        $options .= icon_btn(admin_url('accounting/project_budget/' . $aRow['id']), 'fa fa-pencil-square', 'btn-default');
    }
    if (has_permission('acc_project_budgets', '', 'delete') || is_admin()) {
        $options .= icon_btn(admin_url('accounting/delete_project_budget/' . $aRow['id']), 'fa fa-remove', 'btn-danger _delete');
    }
    $row[] = $options;
    
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
