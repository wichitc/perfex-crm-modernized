<?php
/**
 * New-store wizard wrapper. Delegates to the shared partial; kept as a
 * separate file to match the BUILD_PLAN T6.3 file list and to give a
 * single deterministic include for the create flow.
 */
defined('BASEPATH') or exit('No direct script access allowed');

$store ??= null;
$staffOptions     = $staffOptions     ?? [];
$assignedStaffIds = $assignedStaffIds ?? [];

$this->load->view('modals/_store_form', [
    'store'            => $store,
    'staffOptions'     => $staffOptions,
    'assignedStaffIds' => $assignedStaffIds,
]);
