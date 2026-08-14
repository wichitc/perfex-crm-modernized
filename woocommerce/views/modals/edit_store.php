<?php
/**
 * Edit-store wizard wrapper. Same shape as new_store.php — both files
 * exist to mirror the BUILD_PLAN T6.3 file list; the form itself lives
 * in `_store_form.php` and renders identically for create + edit.
 */
defined('BASEPATH') or exit('No direct script access allowed');

$staffOptions     = $staffOptions     ?? [];
$assignedStaffIds = $assignedStaffIds ?? [];

$this->load->view('modals/_store_form', [
    'store'            => $store ?? null,
    'staffOptions'     => $staffOptions,
    'assignedStaffIds' => $assignedStaffIds,
]);
