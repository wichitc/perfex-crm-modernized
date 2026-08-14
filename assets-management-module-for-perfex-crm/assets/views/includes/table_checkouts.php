<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

$aColumns = [
    db_prefix() . 'asset_checkouts.id',
    db_prefix() . 'assets.assets_name',
    db_prefix() . 'asset_checkouts.checked_out_to',
    db_prefix() . 'asset_checkouts.quantity',
    db_prefix() . 'asset_checkouts.checkout_date',
    db_prefix() . 'asset_checkouts.expected_return_date',
    db_prefix() . 'asset_checkouts.checkout_condition',
];

$sIndexColumn = db_prefix() . 'asset_checkouts.id';
$sTable = db_prefix() . 'asset_checkouts';

// Recipients can be staff, customers or contacts, whose id spaces overlap, so
// the name is resolved per-row by type (below) rather than via a blind staff JOIN.
$join = [
    'LEFT JOIN ' . db_prefix() . 'assets ON ' . db_prefix() . 'assets.id = ' . db_prefix() . 'asset_checkouts.asset_id',
];

$where = [];

// Get status from query string or POST
$filterStatus = $CI->input->get('status');
if (empty($filterStatus)) {
    $filterStatus = $CI->input->post('status');
}

// Apply status filter with AND prefix (Perfex standard)
if (!empty($filterStatus)) {
    $where[] = 'AND ' . db_prefix() . 'asset_checkouts.status = "' . $CI->db->escape_str($filterStatus) . '"';
}

$additionalSelect = [
    db_prefix() . 'asset_checkouts.asset_id',
    db_prefix() . 'asset_checkouts.checked_out_to_type',
    db_prefix() . 'asset_checkouts.actual_return_date',
    db_prefix() . 'asset_checkouts.checkin_condition',
    db_prefix() . 'asset_checkouts.status',
    db_prefix() . 'asset_checkouts.checkout_notes',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $checkoutId = $aRow[db_prefix() . 'asset_checkouts.id'];
    $assetName = $aRow[db_prefix() . 'assets.assets_name'] ?? _l('unknown');
    $assetId = $aRow['asset_id'] ?? '';
    $checkedOutTo = $aRow[db_prefix() . 'asset_checkouts.checked_out_to'] ?? '';
    $checkedOutToType = $aRow['checked_out_to_type'] ?? 'staff';
    $staffName = $checkedOutTo ? get_asset_recipient_name($checkedOutTo, $checkedOutToType) : _l('unknown');
    $quantity = $aRow[db_prefix() . 'asset_checkouts.quantity'] ?? '1';
    $checkoutDate = $aRow[db_prefix() . 'asset_checkouts.checkout_date'] ?? '';
    $expectedReturn = $aRow[db_prefix() . 'asset_checkouts.expected_return_date'] ?? '';
    $actualReturn = $aRow['actual_return_date'] ?? '';
    $checkoutCond = $aRow[db_prefix() . 'asset_checkouts.checkout_condition'] ?? 'good';
    $checkinCond = $aRow['checkin_condition'] ?? '';
    $rowStatus = $aRow['status'] ?? '';
    $notes = $aRow['checkout_notes'] ?? '';
    
    // Column 1: Asset
    $row[] = '<a href="' . admin_url('assets/manage_assets/' . $assetId) . '">' . htmlspecialchars($assetName) . '</a>';
    // Column 2: Checked out to
    $row[] = htmlspecialchars($staffName ?: _l('unknown'));
    // Column 3: Quantity
    $row[] = $quantity;
    // Column 4: Checkout date
    $row[] = $checkoutDate ? _dt($checkoutDate) : '-';
    
    // Column 5 & 6 depend on status
    $actions = '';
    if ($rowStatus == 'returned') {
        // Column 5: Return date
        $row[] = $actualReturn ? _dt($actualReturn) : '-';
        // Column 6: Return condition
        $row[] = $checkinCond ? _l($checkinCond) : '-';
    } else {
        // Column 5: Expected return
        $row[] = $expectedReturn ? _d($expectedReturn) : '-';
        // Column 6: Checkout condition
        $row[] = $checkoutCond ? _l($checkoutCond) : '-';
        
        // Build action button
        if (($rowStatus == 'checked_out' || $rowStatus == 'overdue') && (has_permission('assets', '', 'edit') || is_admin())) {
            $actions .= '<button type="button" class="btn btn-success btn-xs btn-checkin" style="color:#fff;" data-checkout-id="' . $checkoutId . '"><i class="fa fa-sign-in" style="color:#fff;"></i> ' . _l('checkin') . '</button> ';
        }
    }
    
    // Notes button (if notes exist)
    if (!empty($notes)) {
        $notesEscaped = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');
        $actions .= '<button type="button" class="btn btn-info btn-xs" style="color:#fff;" data-toggle="tooltip" title="' . $notesEscaped . '"><i class="fa fa-sticky-note" style="color:#fff;"></i></button>';
    }
    
    // Column 7: Actions (always add for consistent column count)
    $row[] = $actions;

    $output['aaData'][] = $row;
}
