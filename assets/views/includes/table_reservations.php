<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

$aColumns = [
    db_prefix() . 'asset_reservations.id',
    db_prefix() . 'assets.assets_name',
    db_prefix() . 'staff.firstname',
    db_prefix() . 'asset_reservations.quantity',
    db_prefix() . 'asset_reservations.reservation_start',
    db_prefix() . 'asset_reservations.reservation_end',
    db_prefix() . 'asset_reservations.status',
];

$sIndexColumn = db_prefix() . 'asset_reservations.id';
$sTable = db_prefix() . 'asset_reservations';

$join = [
    'LEFT JOIN ' . db_prefix() . 'assets ON ' . db_prefix() . 'assets.id = ' . db_prefix() . 'asset_reservations.asset_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'asset_reservations.reserved_by',
];

$where = [];

// Get status from query string or POST
$filterStatus = $CI->input->get('status');
if (empty($filterStatus)) {
    $filterStatus = $CI->input->post('status');
}

// Apply status filter with AND prefix (Perfex standard)
if (!empty($filterStatus)) {
    $where[] = 'AND ' . db_prefix() . 'asset_reservations.status = "' . $CI->db->escape_str($filterStatus) . '"';
}

$additionalSelect = [
    db_prefix() . 'asset_reservations.asset_id',
    db_prefix() . 'asset_reservations.reserved_by',
    db_prefix() . 'asset_reservations.purpose',
    db_prefix() . 'asset_reservations.notes',
    db_prefix() . 'staff.lastname',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $reservationId = $aRow[db_prefix() . 'asset_reservations.id'];
    $assetName = $aRow[db_prefix() . 'assets.assets_name'] ?? _l('unknown');
    $assetId = $aRow['asset_id'] ?? '';
    $staffName = trim(($aRow[db_prefix() . 'staff.firstname'] ?? '') . ' ' . ($aRow['lastname'] ?? ''));
    $quantity = $aRow[db_prefix() . 'asset_reservations.quantity'] ?? '1';
    $resStart = $aRow[db_prefix() . 'asset_reservations.reservation_start'] ?? '';
    $resEnd = $aRow[db_prefix() . 'asset_reservations.reservation_end'] ?? '';
    $rowStatus = $aRow[db_prefix() . 'asset_reservations.status'] ?? '';
    $purpose = $aRow['purpose'] ?? '';
    $notes = $aRow['notes'] ?? '';
    
    // Column 1: Asset
    $row[] = '<a href="' . admin_url('assets/manage_assets/' . $assetId) . '">' . htmlspecialchars($assetName) . '</a>';
    // Column 2: Reserved by
    $row[] = htmlspecialchars($staffName ?: _l('unknown'));
    // Column 3: Quantity
    $row[] = $quantity;
    // Column 4: Start date
    $row[] = $resStart ? _dt($resStart) : '-';
    // Column 5: End date
    $row[] = $resEnd ? _dt($resEnd) : '-';
    
    // Column 6: Status
    $status_class = [
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'cancelled' => 'default',
        'completed' => 'info'
    ];
    $row[] = '<span class="label label-' . ($status_class[$rowStatus] ?? 'default') . '">' . ($rowStatus ? _l($rowStatus) : '-') . '</span>';

    // Column 7: Actions
    $actions = '';
    if ($rowStatus == 'pending' && (has_permission('assets', '', 'edit') || is_admin())) {
        $actions .= '<a href="' . admin_url('assets/approve_reservation/' . $reservationId) . '" class="btn btn-success btn-xs" style="color:#fff;"><i class="fa fa-check" style="color:#fff;"></i> ' . _l('approve') . '</a> ';
        $actions .= '<button type="button" class="btn btn-danger btn-xs" style="color:#fff;" onclick="showRejectModal(' . $reservationId . ')"><i class="fa fa-times" style="color:#fff;"></i> ' . _l('reject') . '</button> ';
    }
    if (has_permission('assets', '', 'delete') || is_admin()) {
        $actions .= '<a href="' . admin_url('assets/delete_reservation/' . $reservationId) . '" class="btn btn-default btn-xs _delete"><i class="fa fa-trash"></i></a> ';
    }
    // Notes button
    if (!empty($notes) || !empty($purpose)) {
        $noteText = (!empty($purpose) ? _l('purpose') . ': ' . $purpose : '') . (!empty($notes) ? ' | ' . _l('notes') . ': ' . $notes : '');
        $actions .= '<button type="button" class="btn btn-info btn-xs" style="color:#fff;" data-toggle="tooltip" title="' . htmlspecialchars($noteText, ENT_QUOTES) . '"><i class="fa fa-sticky-note" style="color:#fff;"></i></button>';
    }
    $row[] = $actions;

    $output['aaData'][] = $row;
}
