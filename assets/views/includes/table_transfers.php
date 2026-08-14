<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

$aColumns = [
    db_prefix() . 'asset_transfers.id',
    db_prefix() . 'assets.assets_name',
    'from_loc.location',
    'to_loc.location',
    db_prefix() . 'asset_transfers.quantity',
    db_prefix() . 'asset_transfers.transfer_date',
    db_prefix() . 'staff.firstname',
];

$sIndexColumn = db_prefix() . 'asset_transfers.id';
$sTable = db_prefix() . 'asset_transfers';

$join = [
    'LEFT JOIN ' . db_prefix() . 'assets ON ' . db_prefix() . 'assets.id = ' . db_prefix() . 'asset_transfers.asset_id',
    'LEFT JOIN ' . db_prefix() . 'asset_location from_loc ON from_loc.location_id = ' . db_prefix() . 'asset_transfers.from_location',
    'LEFT JOIN ' . db_prefix() . 'asset_location to_loc ON to_loc.location_id = ' . db_prefix() . 'asset_transfers.to_location',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'asset_transfers.transferred_by',
];

$where = [];

// Get status from query string or POST
$filterStatus = $CI->input->get('status');
if (empty($filterStatus)) {
    $filterStatus = $CI->input->post('status');
}

// Apply status filter with AND prefix (Perfex standard)
if (!empty($filterStatus)) {
    $where[] = 'AND ' . db_prefix() . 'asset_transfers.status = "' . $CI->db->escape_str($filterStatus) . '"';
}

$additionalSelect = [
    db_prefix() . 'asset_transfers.asset_id',
    db_prefix() . 'asset_transfers.status',
    db_prefix() . 'asset_transfers.received_by',
    db_prefix() . 'asset_transfers.received_at',
    db_prefix() . 'asset_transfers.reason',
    db_prefix() . 'asset_transfers.notes',
    db_prefix() . 'staff.lastname',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $transferId = $aRow[db_prefix() . 'asset_transfers.id'];
    $assetName = $aRow[db_prefix() . 'assets.assets_name'] ?? _l('unknown');
    $assetId = $aRow['asset_id'] ?? '';
    $fromLoc = $aRow['from_loc.location'] ?? _l('unknown');
    $toLoc = $aRow['to_loc.location'] ?? _l('unknown');
    $quantity = $aRow[db_prefix() . 'asset_transfers.quantity'] ?? '1';
    $transferDate = $aRow[db_prefix() . 'asset_transfers.transfer_date'] ?? '';
    $staffName = trim(($aRow[db_prefix() . 'staff.firstname'] ?? '') . ' ' . ($aRow['lastname'] ?? ''));
    $rowStatus = $aRow['status'] ?? '';
    $receivedBy = $aRow['received_by'] ?? '';
    $receivedAt = $aRow['received_at'] ?? '';
    $reason = $aRow['reason'] ?? '';
    $notes = $aRow['notes'] ?? '';
    
    // Column 1: Asset
    $row[] = '<a href="' . admin_url('assets/manage_assets/' . $assetId) . '">' . htmlspecialchars($assetName) . '</a>';
    // Column 2: From location
    $row[] = htmlspecialchars($fromLoc ?: '-');
    // Column 3: To location
    $row[] = htmlspecialchars($toLoc ?: '-');
    // Column 4: Quantity
    $row[] = $quantity;
    // Column 5: Transfer date
    $row[] = $transferDate ? _dt($transferDate) : '-';
    
    // Columns 6 & 7 depend on status
    if ($rowStatus == 'completed') {
        // Column 6: Received by
        if ($receivedBy) {
            $CI->load->model('staff_model');
            $receiver = $CI->staff_model->get($receivedBy);
            $row[] = $receiver ? $receiver->firstname . ' ' . $receiver->lastname : _l('unknown');
        } else {
            $row[] = '-';
        }
        // Column 7: Received at + Notes button
        $col7 = $receivedAt ? _dt($receivedAt) : '-';
        if (!empty($notes) || !empty($reason)) {
            $noteText = (!empty($reason) ? _l('reason') . ': ' . $reason : '') . (!empty($notes) ? ' | ' . _l('notes') . ': ' . $notes : '');
            $col7 .= ' <button type="button" class="btn btn-info btn-xs" style="color:#fff;" data-toggle="tooltip" title="' . htmlspecialchars($noteText, ENT_QUOTES) . '"><i class="fa fa-sticky-note" style="color:#fff;"></i></button>';
        }
        $row[] = $col7;
    } else {
        // Column 6: Transferred by
        $row[] = htmlspecialchars($staffName ?: _l('unknown'));
        
        // Column 7: Action + Notes
        $actions = '';
        if (($rowStatus == 'pending' || $rowStatus == 'in_transit') && (has_permission('assets', '', 'edit') || is_admin())) {
            $actions .= '<a href="' . admin_url('assets/complete_transfer/' . $transferId) . '" class="btn btn-success btn-xs" style="color:#fff;"><i class="fa fa-check" style="color:#fff;"></i> ' . _l('complete_transfer') . '</a> ';
        }
        if (!empty($notes) || !empty($reason)) {
            $noteText = (!empty($reason) ? _l('reason') . ': ' . $reason : '') . (!empty($notes) ? ' | ' . _l('notes') . ': ' . $notes : '');
            $actions .= '<button type="button" class="btn btn-info btn-xs" style="color:#fff;" data-toggle="tooltip" title="' . htmlspecialchars($noteText, ENT_QUOTES) . '"><i class="fa fa-sticky-note" style="color:#fff;"></i></button>';
        }
        $row[] = $actions;
    }

    $output['aaData'][] = $row;
}
