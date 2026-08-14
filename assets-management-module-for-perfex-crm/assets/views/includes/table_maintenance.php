<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

$aColumns = [
    db_prefix() . 'asset_maintenance.id',
    db_prefix() . 'assets.assets_name',
    db_prefix() . 'asset_maintenance.maintenance_type',
    db_prefix() . 'asset_maintenance.title',
    db_prefix() . 'asset_maintenance.scheduled_date',
    db_prefix() . 'asset_maintenance.cost',
    db_prefix() . 'asset_maintenance.status',
];

$sIndexColumn = db_prefix() . 'asset_maintenance.id';
$sTable = db_prefix() . 'asset_maintenance';

$join = [
    'LEFT JOIN ' . db_prefix() . 'assets ON ' . db_prefix() . 'assets.id = ' . db_prefix() . 'asset_maintenance.asset_id',
];

$where = [];

// Get status from query string or POST
$filterStatus = $CI->input->get('status');
if (empty($filterStatus)) {
    $filterStatus = $CI->input->post('status');
}

// Apply status filter with AND prefix (Perfex standard)
if (!empty($filterStatus)) {
    $where[] = 'AND ' . db_prefix() . 'asset_maintenance.status = "' . $CI->db->escape_str($filterStatus) . '"';
}

$additionalSelect = [
    db_prefix() . 'asset_maintenance.asset_id',
    db_prefix() . 'asset_maintenance.description',
    db_prefix() . 'asset_maintenance.vendor_name',
    db_prefix() . 'asset_maintenance.vendor_contact',
    db_prefix() . 'asset_maintenance.is_recurring',
    db_prefix() . 'asset_maintenance.recurring_interval',
    db_prefix() . 'asset_maintenance.recurring_unit',
    db_prefix() . 'asset_maintenance.completed_date',
    db_prefix() . 'asset_maintenance.notes',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $maintenanceId = $aRow[db_prefix() . 'asset_maintenance.id'];
    $assetName = $aRow[db_prefix() . 'assets.assets_name'] ?? _l('unknown');
    $assetId = $aRow['asset_id'] ?? '';
    $maintType = $aRow[db_prefix() . 'asset_maintenance.maintenance_type'] ?? '';
    $title = $aRow[db_prefix() . 'asset_maintenance.title'] ?? '';
    $scheduledDate = $aRow[db_prefix() . 'asset_maintenance.scheduled_date'] ?? '';
    $cost = $aRow[db_prefix() . 'asset_maintenance.cost'] ?? 0;
    $rowStatus = $aRow[db_prefix() . 'asset_maintenance.status'] ?? '';
    $description = $aRow['description'] ?? '';
    $vendorName = $aRow['vendor_name'] ?? '';
    $vendorContact = $aRow['vendor_contact'] ?? '';
    $isRecurring = $aRow['is_recurring'] ?? 0;
    $recurInterval = $aRow['recurring_interval'] ?? '';
    $recurUnit = $aRow['recurring_unit'] ?? '';
    $completedDate = $aRow['completed_date'] ?? '';
    $notes = $aRow['notes'] ?? '';
    
    // Column 1: ID
    $row[] = $maintenanceId;
    // Column 2: Asset
    $row[] = '<a href="' . admin_url('assets/manage_assets/' . $assetId) . '">' . htmlspecialchars($assetName) . '</a>';
    // Column 3: Type
    $row[] = $maintType ? _l($maintType) : '-';
    // Column 4: Title
    $row[] = htmlspecialchars($title ?: '-');
    // Column 5: Scheduled date
    $row[] = $scheduledDate ? _d($scheduledDate) : '-';
    // Column 6: Cost
    $row[] = app_format_money($cost, get_base_currency());
    
    // Column 7: Status
    $status_class = [
        'scheduled' => 'default',
        'in_progress' => 'info',
        'completed' => 'success',
        'cancelled' => 'warning',
        'overdue' => 'danger'
    ];
    $row[] = '<span class="label label-' . ($status_class[$rowStatus] ?? 'default') . '">' . ($rowStatus ? _l($rowStatus) : '-') . '</span>';

    // Column 8: Actions
    $actions = '';
    if (has_permission('assets', '', 'edit') || is_admin()) {
        $dataArray = [
            'asset_id' => $assetId,
            'maintenance_type' => $maintType,
            'title' => $title,
            'scheduled_date' => $scheduledDate ? _d($scheduledDate) : '',
            'cost' => $cost,
            'vendor_name' => $vendorName,
            'vendor_contact' => $vendorContact,
            'description' => $description,
            'is_recurring' => $isRecurring,
            'recurring_interval' => $recurInterval,
            'recurring_unit' => $recurUnit,
            'status' => $rowStatus,
            'completed_date' => $completedDate ? _d($completedDate) : '',
            'notes' => $notes,
        ];
        $dataJson = htmlspecialchars(json_encode($dataArray), ENT_QUOTES, 'UTF-8');
        $actions .= '<button type="button" class="btn btn-default btn-xs" data-maintenance-id="' . $maintenanceId . '" data-maintenance="' . $dataJson . '" onclick="editMaintenanceFromBtn(this)"><i class="fa fa-pencil"></i></button> ';
    }
    if (has_permission('assets', '', 'delete') || is_admin()) {
        $actions .= '<a href="' . admin_url('assets/delete_maintenance/' . $maintenanceId) . '" class="btn btn-danger btn-xs _delete"><i class="fa fa-trash" style="color:#fff;"></i></a> ';
    }
    // Notes button
    if (!empty($notes) || !empty($description)) {
        $noteText = (!empty($description) ? _l('description') . ': ' . $description : '') . (!empty($notes) ? ' | ' . _l('notes') . ': ' . $notes : '');
        $actions .= '<button type="button" class="btn btn-info btn-xs" style="color:#fff;" data-toggle="tooltip" title="' . htmlspecialchars($noteText, ENT_QUOTES) . '"><i class="fa fa-sticky-note" style="color:#fff;"></i></button>';
    }
    $row[] = $actions;

    $output['aaData'][] = $row;
}
