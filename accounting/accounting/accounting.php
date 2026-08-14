<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Accounting and Bookkeeping
Description: Accounting is the process of recording and tracking financial statements to see the financial health of an entity.
Version: 1.4.5
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('ACCOUNTING_MODULE_NAME', 'accounting');
define('ACCOUTING_MODULE_UPLOAD_FOLDER', module_dir_path(ACCOUNTING_MODULE_NAME, 'uploads'));
define('ACCOUTING_IMPORT_ITEM_ERROR', 'modules/accounting/uploads/import_item_error/');
define('ACCOUTING_ERROR', FCPATH);
define('ACCOUTING_EXPORT_XLSX', 'modules/accounting/uploads/export_xlsx/');
define('ACCOUTING_PATH', 'modules/accounting/uploads/');

hooks()->add_action('app_admin_head', 'accounting_add_head_component');
hooks()->add_action('app_admin_footer', 'accounting_load_js');
hooks()->add_action('admin_init', 'accounting_module_init_menu_items');
hooks()->add_action('admin_init', 'accounting_permissions');

//add class 
hooks()->add_action('after_wh_goods_receipt_added', 'acc_goods_receipt_class_save');
hooks()->add_action('after_wh_goods_receipt_updated', 'acc_goods_receipt_class_save');

hooks()->add_action('after_wh_goods_delivery_added', 'acc_goods_delivery_class_save');
hooks()->add_action('after_wh_goods_delivery_updated', 'acc_goods_delivery_class_save');

hooks()->add_action('after_wh_loss_adjustment_added', 'acc_loss_adjustment_class_save');
hooks()->add_action('after_wh_loss_adjustment_updated', 'acc_loss_adjustment_class_save');

hooks()->add_action('hr_payroll_after_payslip_added', 'acc_payslip_class_save');
hooks()->add_action('hr_payroll_after_payslip_updated', 'acc_payslip_class_save');

hooks()->add_action('after_pur_debit_note_added', 'acc_debit_note_class_save');
hooks()->add_action('after_pur_debit_note_updated', 'acc_debit_note_class_save');

hooks()->add_action('after_invoice_added', 'acc_invoice_class_save');
hooks()->add_action('after_invoice_updated', 'acc_invoice_class_save');
hooks()->add_action('invoice_updated', 'acc_invoice_class_save');

hooks()->add_action('after_credit_note_added', 'acc_credit_note_class_save');
hooks()->add_action('after_credit_note_updated', 'acc_credit_note_class_save');
hooks()->add_action('credit_note_created', 'acc_credit_note_class_save');
hooks()->add_action('credit_note_updated', 'acc_credit_note_class_save');

hooks()->add_action('expense_created', 'acc_expense_class_save');
hooks()->add_action('expense_updated', 'acc_expense_class_save');
hooks()->add_action('after_expense_added', 'acc_expense_class_save');
hooks()->add_action('after_expense_updated', 'acc_expense_class_save');

hooks()->add_action('after_purchase_order_add', 'acc_purchase_order_class_save');
hooks()->add_action('after_pur_order_updated', 'acc_purchase_order_class_save');

hooks()->add_action('after_pur_invoice_added', 'acc_purchase_invoice_class_save');
hooks()->add_action('after_pur_invoice_updated', 'acc_purchase_invoice_class_save');

hooks()->add_action('after_omni_sale_order_return_added', 'acc_omni_sale_order_return_class_save');
hooks()->add_action('after_omni_sale_order_return_updated', 'acc_omni_sale_order_return_class_save');

// Fixed Equipment class save hooks
hooks()->add_action('after_fe_asset_added', 'acc_fe_asset_class_save');
hooks()->add_action('after_fe_asset_updated', 'acc_fe_asset_class_save');
hooks()->add_action('after_fe_asset_updated_v2', 'acc_fe_asset_class_save');
hooks()->add_action('after_fe_license_added', 'acc_fe_asset_class_save');
hooks()->add_action('after_fe_license_updated', 'acc_fe_asset_class_save');
hooks()->add_action('after_fe_consumable_added', 'acc_fe_asset_class_save');
hooks()->add_action('after_fe_consumable_updated', 'acc_fe_asset_class_save');
hooks()->add_action('after_fe_component_added', 'acc_fe_asset_class_save');
hooks()->add_action('after_fe_component_updated', 'acc_fe_asset_class_save');

hooks()->add_action('after_fe_maintenance_added', 'acc_fe_maintenance_class_save');
hooks()->add_action('after_fe_maintenance_updated', 'acc_fe_maintenance_class_save');

// invoice
hooks()->add_action('after_invoice_added', 'acc_automatic_invoice_conversion');
hooks()->add_action('invoice_updated', 'acc_automatic_invoice_conversion');
hooks()->add_action('before_invoice_deleted', 'acc_delete_invoice_convert');
hooks()->add_action('invoice_status_changed', 'acc_invoice_status_changed');
hooks()->add_action('invoice_marked_as_cancelled', 'acc_delete_invoice_convert');

// payment
hooks()->add_action('after_payment_added', 'acc_automatic_payment_conversion');
hooks()->add_action('after_payment_updated', 'acc_automatic_payment_conversion');
hooks()->add_action('before_payment_deleted', 'acc_delete_payment_convert');

// expense
hooks()->add_action('after_expense_added', 'acc_automatic_expense_conversion');
hooks()->add_action('after_expense_imported', 'acc_automatic_expense_conversion');
hooks()->add_action('after_recurring_expense_added', 'acc_automatic_expense_conversion');
hooks()->add_action('expense_updated', 'acc_automatic_expense_conversion');
hooks()->add_action('after_expense_deleted', 'acc_delete_expense_convert');
hooks()->add_action('before_expense_form_name','init_acc_class_option');

// credit note
hooks()->add_filter('credits_applied', 'acc_automatic_credit_note_apply_conversion');
hooks()->add_filter('after_applied_credit_deleted', 'acc_delete_applied_credit_convert');
hooks()->add_filter('credit_note_refund_created', 'acc_automatic_credit_note_refund_conversion');
hooks()->add_filter('credit_note_refund_updated', 'acc_automatic_credit_note_refund_conversion');
hooks()->add_filter('credit_note_refund_deleted', 'acc_delete_credit_note_refund_convert');

hooks()->add_filter('credit_note_status_changed', 'acc_credit_note_status_changed', 10, 2);
hooks()->add_filter('before_credit_note_deleted', 'acc_delete_credit_note_convert');
hooks()->add_filter('after_create_credit_note', 'acc_automatic_credit_note_conversion');
hooks()->add_filter('after_update_credit_note', 'acc_automatic_credit_note_conversion');


// payslip
hooks()->add_action('before_payslip_deleted', 'acc_delete_payslip_convert');
hooks()->add_action('after_update_payslip_status', 'acc_update_payslip_status', 10, 2);

// inventory
hooks()->add_action('after_wh_goods_receipt_added', 'acc_automatic_wh_goods_receipt_convert');
hooks()->add_action('after_wh_goods_receipt_updated', 'acc_automatic_wh_goods_receipt_convert');
hooks()->add_action('after_wh_goods_receipt_approve', 'acc_automatic_wh_goods_receipt_convert');
hooks()->add_action('before_goods_receipt_deleted', 'acc_delete_stock_import_convert');

hooks()->add_action('after_wh_goods_delivery_added', 'acc_automatic_wh_goods_delivery_convert');
hooks()->add_action('after_wh_goods_delivery_updated', 'acc_automatic_wh_goods_delivery_convert');
hooks()->add_action('after_wh_goods_delivery_approve', 'acc_automatic_wh_goods_delivery_convert');
hooks()->add_action('before_goods_delivery_deleted', 'acc_delete_stock_export_convert');

hooks()->add_action('after_wh_loss_adjustment_added', 'acc_automatic_wh_loss_adjustment_convert');
hooks()->add_action('after_wh_loss_adjustment_updated', 'acc_automatic_wh_loss_adjustment_convert');
hooks()->add_action('after_wh_loss_adjustment_approve', 'acc_automatic_wh_loss_adjustment_convert');
hooks()->add_action('before_loss_adjustment_deleted', 'acc_delete_loss_adjustment_convert');

hooks()->add_action('after_receiving_or_exporting_return_order_approved', 'exporting_return_order_approved');

// purchase
hooks()->add_action('after_purchase_order_add', 'acc_automatic_pur_order_convert');
hooks()->add_action('after_purchase_order_approve', 'acc_automatic_pur_order_convert');
hooks()->add_action('before_pur_order_deleted', 'acc_delete_pur_order_convert');
hooks()->add_action('pur_after_expense_converted', 'acc_delete_expense_convert');

hooks()->add_action('after_payment_pur_invoice_added', 'acc_automatic_pur_invoice_payment_convert');
hooks()->add_action('after_purchase_payment_approve', 'acc_automatic_pur_invoice_payment_convert');
hooks()->add_action('after_payment_pur_invoice_deleted', 'acc_delete_pur_invoice_payment_convert');


hooks()->add_action('after_pur_invoice_added', 'acc_automatic_pur_invoice_convert');
hooks()->add_action('after_pur_invoice_updated', 'acc_automatic_pur_invoice_convert');
hooks()->add_action('after_pur_invoice_deleted', 'acc_delete_pur_invoice_convert');
hooks()->add_action('after_purchase_invoice_approve', 'acc_automatic_pur_invoice_convert');

hooks()->add_action('after_pur_refund_added', 'acc_automatic_pur_refund_convert');
hooks()->add_action('after_pur_refund_updated', 'acc_automatic_pur_refund_convert');
hooks()->add_action('after_pur_refund_deleted', 'acc_delete_pur_refund_convert');

hooks()->add_action('after_pur_return_order_status_changed', 'acc_automatic_pur_order_return_convert');
hooks()->add_action('before_pur_order_return_deleted', 'acc_delete_pur_order_return_convert');

hooks()->add_action('after_pur_vendor_created', 'acc_pur_vendor_created');
hooks()->add_action('before_pur_vendor_updated', 'acc_pur_vendor_updated', 10, 2);
hooks()->add_action('after_pur_vendor_profile_company_field', 'acc_init_pur_vendor_profile');

// credit note
hooks()->add_filter('after_pur_debits_applied', 'acc_automatic_debit_note_apply_conversion');
hooks()->add_filter('after_pur_applied_debit_deleted', 'acc_delete_applied_debit_convert');

hooks()->add_filter('after_pur_debit_note_refund_created', 'acc_automatic_debit_note_refund_conversion');
hooks()->add_filter('after_pur_debit_note_refund_updated', 'acc_automatic_debit_note_refund_conversion');
hooks()->add_filter('after_pur_debit_note_refund_deleted', 'acc_delete_debit_note_refund_convert');

hooks()->add_filter('after_pur_debit_note_added', 'acc_automatic_debit_note_conversion');
hooks()->add_filter('after_pur_debit_note_updated', 'acc_automatic_debit_note_conversion');
hooks()->add_filter('after_pur_debit_note_deleted', 'acc_delete_debit_note_convert');
hooks()->add_filter('after_pur_debit_note_status_changed', 'acc_debit_note_status_changed', 10, 2);


// manufacturing
hooks()->add_action('manufacturing_order_status_changed', 'acc_automatic_manufacturing_order_conversion');
hooks()->add_action('manufacturing_order_status_changed', 'acc_automatic_manufacturing_order_conversion');
hooks()->add_action('after_manufacturing_order_deleted', 'acc_delete_manufacturing_order_convert');
hooks()->add_action('after_manufacturing_goods_delivery_added', 'acc_automatic_wh_goods_delivery_convert');

// omni sales
hooks()->add_action('after_omni_sales_order_status_changed', 'acc_automatic_omni_sales_return_order_conversion');
hooks()->add_action('after_omni_sales_order_deleted', 'acc_delete_omni_sales_order_convert');

hooks()->add_action('after_omni_sales_refund_added', 'acc_automatic_omni_sales_refund_convert');
hooks()->add_action('after_omni_sales_refund_updated', 'acc_automatic_omni_sales_refund_convert');
hooks()->add_action('after_omni_sales_refund_deleted', 'acc_delete_omni_sales_refund_convert');

// fixed equipment
hooks()->add_action('after_fe_asset_added', 'acc_automatic_fe_asset_convert');
hooks()->add_action('after_fe_asset_updated', 'acc_automatic_fe_asset_convert');
hooks()->add_action('after_fe_asset_updated_v2', 'acc_automatic_fe_asset_convert');
hooks()->add_action('after_fe_asset_deleted', 'acc_delete_fe_asset_convert');

hooks()->add_action('after_fe_license_added', 'acc_automatic_fe_license_convert');
hooks()->add_action('after_fe_license_updated', 'acc_automatic_fe_license_convert');
hooks()->add_action('after_fe_license_deleted', 'acc_delete_fe_license_convert');

hooks()->add_action('after_fe_consumable_added', 'acc_automatic_fe_consumable_convert');
hooks()->add_action('after_fe_consumable_updated', 'acc_automatic_fe_consumable_convert');

hooks()->add_action('after_fe_component_added', 'acc_automatic_fe_component_convert');
hooks()->add_action('after_fe_component_updated', 'acc_automatic_fe_component_convert');

// fixed equipment
hooks()->add_action('after_fe_maintenance_added', 'acc_automatic_fe_maintenance_convert');
hooks()->add_action('after_fe_maintenance_updated', 'acc_automatic_fe_maintenance_convert');
hooks()->add_action('after_fe_maintenance_deleted', 'acc_delete_fe_maintenance_convert');
hooks()->add_action('after_fe_depreciation_added', 'acc_automatic_fe_depreciation_convert');


// customer
hooks()->add_action('before_client_added', 'acc_before_client_added');
hooks()->add_action('after_client_created', 'acc_client_created');
hooks()->add_action('before_client_updated', 'acc_client_updated',10,2);
hooks()->add_action('after_customer_profile_company_field', 'acc_init_client_profile');

//get currency
hooks()->add_action('after_cron_run', 'acc_cronjob_currency_rates');


hooks()->add_action('not_importable_expense_fields', 'acc_not_importable_expenses_fields', 10, 2);

// vendor

define('ACCOUNTING_REVISION', 145);

/**
 * Register activation module hook
 */

register_activation_hook(ACCOUNTING_MODULE_NAME, 'accounting_module_activation_hook');

$CI = &get_instance();

$CI->load->helper(ACCOUNTING_MODULE_NAME . '/Accounting');

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(ACCOUNTING_MODULE_NAME, [ACCOUNTING_MODULE_NAME]);

/**
 * spreadsheet online module activation hook
 */
function accounting_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * init add head component
 */
function accounting_add_head_component() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'admin/accounting') === false)) {
			$out_style = '<style>
	    @font-face {
	      font-family: MicrFont;
	      src: url(\''.site_url("/modules/accounting/assets/plugins/micr-encoding/micrenc.ttf").'\')  format(\'truetype\')
	    }
	    </style>';

	    echo $out_style;

		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/custom.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';

	}
	if (!(strpos($viewuri, 'admin/accounting/new_journal_entry') === false)) {

		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/rp_') === false) || !(strpos($viewuri, 'admin/accounting/report') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/report.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/simple-tree-table/src/simple-tree-table.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/accounts_import') === false) || !(strpos($viewuri, 'admin/accounting/report') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/chart_of_accounts') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/chart_of_accounts.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		

	}
	if (!(strpos($viewuri, 'admin/accounting/reconcile') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/reconcile.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/reconcile_account') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/reconcile_account.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/transaction.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/import_xlsx_banking') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/dashboard') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/dashboard.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, 'admin/accounting/setting') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/setting.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/new_journal_entry') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/new_journal_entry.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/journal_entry') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/manage_journal_entry.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/budget') === false) || !(strpos($viewuri, 'admin/accounting/user_register_view') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';

	}

	if (!(strpos($viewuri, 'admin/accounting/budget_import') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/import_budget.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/project_budget_detail') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/project_budgets.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
}

/**
 * init add footer component
 */
function accounting_load_js() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];

    $CI->load->model('accounting/accounting_model');
    $classes = $CI->accounting_model->get_class();
    $enable_class = get_option('acc_enable_class_tracking');
    
    // Find selected class of current transaction if editing
    $transaction_class = 0;
    if (strpos($viewuri, 'admin/invoices/invoice/') !== false) {
        $parts = explode('admin/invoices/invoice/', $viewuri);
        $id = intval(explode('?', $parts[1])[0]);
        $inv = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'invoices')->row();
        if ($inv) { $transaction_class = $inv->acc_class; }
    } elseif (strpos($viewuri, 'admin/credit_notes/credit_note/') !== false) {
        $parts = explode('admin/credit_notes/credit_note/', $viewuri);
        $id = intval(explode('?', $parts[1])[0]);
        $cn = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'creditnotes')->row();
        if ($cn) { $transaction_class = $cn->acc_class; }
    } elseif (strpos($viewuri, 'admin/expenses/expense/') !== false) {
        $parts = explode('admin/expenses/expense/', $viewuri);
        $id = intval(explode('?', $parts[1])[0]);
        $exp = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'expenses')->row();
        if ($exp) { $transaction_class = $exp->acc_class; }
    } elseif (strpos($viewuri, 'admin/purchase/purchase_order/') !== false || strpos($viewuri, 'admin/purchase/pur_order/') !== false) {
        $parts = explode('admin/purchase/purchase_order/', $viewuri);
        if (count($parts) < 2) { $parts = explode('admin/purchase/pur_order/', $viewuri); }
        $id = intval(explode('?', $parts[1])[0]);
        $po = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'pur_orders')->row();
        if ($po) { $transaction_class = $po->acc_class; }
    } elseif (strpos($viewuri, 'admin/purchase/pur_invoice/') !== false) {
        $parts = explode('admin/purchase/pur_invoice/', $viewuri);
        $id = intval(explode('?', $parts[1])[0]);
        $pi = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'pur_invoices')->row();
        if ($pi) { $transaction_class = $pi->acc_class; }
    } elseif (strpos($viewuri, 'admin/purchase/debit_note/') !== false) {
        $parts = explode('admin/purchase/debit_note/', $viewuri);
        $id = intval(explode('?', $parts[1])[0]);
        $dn = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'pur_debit_notes')->row();
        if ($dn) { $transaction_class = $dn->acc_class; }
    } elseif (strpos($viewuri, 'order_return/') !== false) {
        $parts = explode('order_return/', $viewuri);
        $subparts = explode('/', explode('?', $parts[1])[0]);
        $id = intval(end($subparts));
        $or = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'wh_order_returns')->row();
        if ($or) { $transaction_class = $or->acc_class; }
    } elseif (strpos($viewuri, 'order_manual/') !== false) {
        $parts = explode('order_manual/', $viewuri);
        $subparts = explode('/', explode('?', $parts[1])[0]);
        $id = intval(end($subparts));
        if ($id > 0) {
            $cart = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'cart')->row();
            if ($cart) { $transaction_class = $cart->acc_class; }
        }
    } elseif (strpos($viewuri, 'admin/purchase/payment_invoice/') !== false) {
        $parts = explode('admin/purchase/payment_invoice/', $viewuri);
        $id = intval(explode('?', $parts[1])[0]);
        $pp = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'pur_invoice_payment')->row();
        if ($pp) { $transaction_class = $pp->acc_class; }
    } elseif (strpos($viewuri, 'admin/warehouse/edit_purchase/') !== false || strpos($viewuri, 'admin/warehouse/view_purchase/') !== false) {
        $parts = explode('admin/warehouse/edit_purchase/', $viewuri);
        if (count($parts) < 2) { $parts = explode('admin/warehouse/view_purchase/', $viewuri); }
        $id = intval(explode('?', $parts[1])[0]);
        $gr = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'goods_receipt')->row();
        if ($gr) { $transaction_class = $gr->acc_class; }
    } elseif (strpos($viewuri, 'admin/warehouse/edit_delivery/') !== false) {
        $parts = explode('admin/warehouse/edit_delivery/', $viewuri);
        $id = intval(explode('?', $parts[1])[0]);
        $gd = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'goods_delivery')->row();
        if ($gd) { $transaction_class = $gd->acc_class; }
    } elseif (strpos($viewuri, 'admin/warehouse/view_lost_adjustment/') !== false || strpos($viewuri, 'admin/warehouse/add_loss_adjustment/') !== false) {
        $parts = explode('admin/warehouse/view_lost_adjustment/', $viewuri);
        if (count($parts) < 2) { $parts = explode('admin/warehouse/add_loss_adjustment/', $viewuri); }
        $id = intval(explode('?', $parts[1])[0]);
        if ($id > 0) {
            $la = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'wh_loss_adjustment')->row();
            if ($la) { $transaction_class = $la->acc_class; }
        }
    } elseif (strpos($viewuri, 'admin/manufacturing/view_manufacturing_order/') !== false) {
        $parts = explode('admin/manufacturing/view_manufacturing_order/', $viewuri);
        $id = intval(explode('?', $parts[1])[0]);
        $mo = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'mrp_manufacturing_orders')->row();
        if ($mo) { $transaction_class = $mo->acc_class; }
    } elseif (strpos($viewuri, 'admin/manufacturing/add_edit_manufacturing_order') !== false) {
        $parts = explode('admin/manufacturing/add_edit_manufacturing_order/', $viewuri);
        if (count($parts) > 1) {
            $id = intval(explode('?', $parts[1])[0]);
            $mo = $CI->db->select('acc_class')->where('id', $id)->get(db_prefix() . 'mrp_manufacturing_orders')->row();
            if ($mo) { $transaction_class = $mo->acc_class; }
        }
    }

    if (!function_exists('get_base_currency')) {
        $CI->load->helper('sales');
    }
    $base_currency = get_base_currency();
    $acc_decimal_separator = ($base_currency) ? $base_currency->decimal_separator : get_option('decimal_separator');
    $acc_thousand_separator = ($base_currency) ? $base_currency->thousand_separator : get_option('thousand_separator');
    $acc_symbol = ($base_currency) ? $base_currency->symbol : "";

    echo '<script>';
    echo 'var acc_decimal_separator = ' . json_encode($acc_decimal_separator) . ';';
    echo 'var acc_thousand_separator = ' . json_encode($acc_thousand_separator) . ';';
    echo 'var acc_enable_class_tracking = ' . json_encode($enable_class) . ';';
    echo 'var acc_classes = ' . json_encode($classes) . ';';
    echo 'var acc_class_lang = ' . json_encode(_l('acc_class')) . ';';
    echo 'var acc_transaction_class = ' . json_encode($transaction_class) . ';';
    echo 'var acc_symbol = ' . json_encode($acc_symbol) . ';';
    echo 'function acc_format_money(total, excludeSymbol) {';
    echo '  if (typeof(accounting) !== "undefined") {';
    echo '    var decimal_sep = (typeof(acc_decimal_separator) !== "undefined") ? acc_decimal_separator : ".";';
    echo '    var thousand_sep = (typeof(acc_thousand_separator) !== "undefined") ? acc_thousand_separator : ",";';
    echo '    var old_decimal = accounting.settings.currency.decimal;';
    echo '    var old_thousand = accounting.settings.currency.thousand;';
    echo '    accounting.settings.currency.decimal = decimal_sep;';
    echo '    accounting.settings.currency.thousand = thousand_sep;';
    echo '    var result;';
    echo '    if (typeof(excludeSymbol) !== "undefined" && excludeSymbol) {';
    echo '      result = accounting.formatMoney(total, { symbol: "" });';
    echo '    } else {';
    echo '      result = accounting.formatMoney(total, { symbol: acc_symbol });';
    echo '    }';
    echo '    accounting.settings.currency.decimal = old_decimal;';
    echo '    accounting.settings.currency.thousand = old_thousand;';
    echo '    return result;';
    echo '  }';
    echo '  total = parseFloat(total) || 0;';
    echo '  var decimal_sep = (typeof(acc_decimal_separator) !== "undefined") ? acc_decimal_separator : ".";';
    echo '  var thousand_sep = (typeof(acc_thousand_separator) !== "undefined") ? acc_thousand_separator : ",";';
    echo '  var parts = total.toFixed(2).split(".");';
    echo '  parts[0] = parts[0].replace(/\\B(?=(\\d{3})+(?!\\d))/g, thousand_sep);';
    echo '  var formatted = parts.join(decimal_sep);';
    echo '  if (typeof(excludeSymbol) !== "undefined" && excludeSymbol) {';
    echo '    return formatted;';
    echo '  }';
    echo '  return (typeof(app) !== "undefined" && app.options && app.options.currency_symbol) ? app.options.currency_symbol + formatted : "" + formatted;';
    echo '}';
    ?>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof acc_enable_class_tracking !== 'undefined' && acc_enable_class_tracking == 1 && typeof acc_classes !== 'undefined' && acc_classes.length > 0) {
            var path = window.location.pathname;
            var is_invoice = path.indexOf('admin/invoices/invoice') !== -1;
            var is_credit_note = path.indexOf('admin/credit_notes/credit_note') !== -1;
            var is_expense = path.indexOf('admin/expenses/expense') !== -1;
            var is_pur_order = path.indexOf('admin/purchase/purchase_order') !== -1 || path.indexOf('admin/purchase/pur_order') !== -1;
            var is_pur_invoice = path.indexOf('admin/purchase/pur_invoice') !== -1;
            var is_pur_payment = path.indexOf('admin/purchase/payment_invoice') !== -1 || path.indexOf('admin/purchase/payment') !== -1;
            var is_goods_receipt = path.indexOf('admin/warehouse/manage_goods_receipt') !== -1 || path.indexOf('admin/warehouse/manage_purchase') !== -1 || path.indexOf('admin/warehouse/edit_purchase') !== -1 || path.indexOf('admin/warehouse/view_purchase') !== -1 || path.indexOf('admin/warehouse/purchase') !== -1;
            var is_goods_delivery = path.indexOf('admin/warehouse/goods_delivery') !== -1 || path.indexOf('admin/warehouse/manage_delivery') !== -1 || path.indexOf('admin/warehouse/edit_delivery') !== -1;
            var is_lost_adjustment = path.indexOf('lost_adjustment') !== -1 || path.indexOf('loss_adjustment') !== -1;
            var is_payslips = path.indexOf('admin/hr_payroll/payslip_manage') !== -1;
            var is_pur_debit_note = path.indexOf('admin/purchase/debit_note') !== -1 || path.indexOf('admin/purchase/debit_notes') !== -1;
            var is_pur_order_return = path.indexOf('order_return') !== -1 || path.indexOf('order_returns') !== -1;
            var is_omni_sales_order = path.indexOf('admin/omni_sales/order_manual') !== -1;
            var is_mrp_manufacturing_order = path.indexOf('admin/manufacturing/add_edit_manufacturing_order') !== -1;
            var is_mrp_view_manufacturing_order = path.indexOf('admin/manufacturing/view_manufacturing_order') !== -1;

            if (is_invoice || is_credit_note || is_expense || is_pur_order || is_pur_invoice || is_pur_payment || is_goods_receipt || is_goods_delivery || is_lost_adjustment || is_pur_debit_note || is_pur_order_return || is_omni_sales_order || is_mrp_manufacturing_order) {
                // Check if Class field is already present
                var interval = setInterval(function() {
                    if (typeof jQuery !== 'undefined' && jQuery('select[name="acc_class"]').length === 0) {
                        var $ = jQuery;
                        var select_html = '<div class="col-md-6 form-group">';
                        select_html += '<label for="acc_class" class="control-label">' + acc_class_lang + '</label>';
                        select_html += '<select name="acc_class" id="acc_class" class="selectpicker" data-width="100%" data-none-selected-text="None">';
                        select_html += '<option value="0">None</option>';
                        $.each(acc_classes, function(i, c) {
                            var selected = (typeof acc_transaction_class !== 'undefined' && acc_transaction_class == c.id) ? 'selected' : '';
                            select_html += '<option value="' + c.id + '" ' + selected + '>' + c.name + '</option>';
                        });
                        select_html += '</select>';
                        select_html += '</div>';

                        // Find a good place to insert it (Checking bottom/late fields first to place at the end)
                        var target = $();
                        var insert_as_row = false;
                        if (is_pur_order_return) {
                            target = $('select[name="return_type"]').closest('.row');
                            if (target.length > 0) {
                                insert_as_row = true;
                            }
                        }
                        if (target.length === 0 && is_omni_sales_order) {
                            target = $('select[name="sale_agent"]').closest('.row');
                            if (target.length > 0) {
                                insert_as_row = true;
                            }
                        }
                        if (target.length === 0 && (is_credit_note || is_invoice)) {
                            target = $('select[name="discount_type"]').closest('.row');
                            if (target.length > 0) {
                                insert_as_row = true;
                            }
                        }
                        if (target.length === 0 && is_lost_adjustment) {
                            target = $('select[name="warehouses"]').closest('.col-md-4, .col-md-6');
                            if (target.length === 0) { target = $('select[name="type"]').closest('.col-md-4, .col-md-6'); }
                        }
                        if (target.length === 0) { target = $('select[name="project"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('select[name="project_id"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('select[name="discount_type"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('select[name="warehouse_id"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('input[name="order_date"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('input[name="date"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('select[name="department"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('select[name="vendor"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('select[name="vendorid"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('select[name="clientid"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('select[name="client_id"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('input[name="pur_order_number"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('input[name="goods_receipt_date"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('input[name="date_c"]').closest('.col-md-6'); }
                        if (target.length === 0) { target = $('.project-ajax-search').closest('.col-md-6'); }
                        if (target.length === 0 && is_mrp_manufacturing_order) {
                            target = $('input[name="routing_id"]').closest('.col-md-6');
                        }
                        
                        // Fallback to first col-md-6 inside the transaction form
                        if (target.length === 0) { target = $('form._transaction_form .col-md-6').first(); }
                        if (target.length === 0) { target = $('form:not(#apply_debits_form) .col-md-6').first(); }

                        if (target.length > 0 && target.closest('#apply_debits_form, #apply_debits, .apply-debits-to-invoice, .apply-debits-from-invoice').length === 0) {
                            if (insert_as_row) {
                                var new_row = $('<div class="row"></div>').append(select_html);
                                new_row.insertAfter(target);
                            } else {
                                var col_class = 'col-md-6';
                                if (target.hasClass('col-md-4')) { col_class = 'col-md-4'; }
                                else if (target.hasClass('col-md-3')) { col_class = 'col-md-3'; }
                                select_html = select_html.replace('col-md-6', col_class);
                                $(select_html).insertAfter(target);
                            }
                            if (typeof $.fn.selectpicker !== 'undefined') {
                                $('#acc_class').selectpicker();
                            }
                            if (typeof init_selectpicker === 'function') {
                                init_selectpicker();
                            }
                            $('#acc_class').selectpicker('refresh');
                            clearInterval(interval);
                        }
                    }
                }, 100);
            } else if (is_payslips) {
                var interval = setInterval(function() {
                    if (typeof jQuery !== 'undefined') {
                        var $ = jQuery;
                        
                        // Handle #add_payslip form
                        var add_form = $('#add_payslip');
                        if (add_form.length > 0 && add_form.find('select[name="acc_class"]').length === 0) {
                            var pdf_template_col = add_form.find('select[name="pdf_template_id"]').closest('.col-md-6');
                            if (pdf_template_col.length > 0) {
                                var select_html = '<div class="col-md-6 form-group">';
                                select_html += '<label for="acc_class" class="control-label">' + acc_class_lang + '</label>';
                                select_html += '<select name="acc_class" class="selectpicker" data-width="100%" data-none-selected-text="None">';
                                select_html += '<option value="0">None</option>';
                                $.each(acc_classes, function(i, c) {
                                    select_html += '<option value="' + c.id + '">' + c.name + '</option>';
                                });
                                select_html += '</select>';
                                select_html += '</div>';
                                $(select_html).insertAfter(pdf_template_col);
                                add_form.find('select[name="acc_class"]').selectpicker('refresh');
                            }
                        }

                        // Handle #edit_payslip form
                        var edit_form = $('#edit_payslip');
                        if (edit_form.length > 0 && edit_form.find('select[name="acc_class"]').length === 0) {
                            var edit_pdf_col = edit_form.find('select[name="pdf_template_id"]').closest('.col-md-6');
                            if (edit_pdf_col.length > 0) {
                                var select_html = '<div class="col-md-6 form-group">';
                                select_html += '<label for="acc_class" class="control-label">' + acc_class_lang + '</label>';
                                select_html += '<select name="acc_class" class="selectpicker" data-width="100%" data-none-selected-text="None">';
                                select_html += '<option value="0">None</option>';
                                $.each(acc_classes, function(i, c) {
                                    var selected = (typeof acc_transaction_class !== 'undefined' && acc_transaction_class == c.id) ? 'selected' : '';
                                    select_html += '<option value="' + c.id + '" ' + selected + '>' + c.name + '</option>';
                                });
                                select_html += '</select>';
                                select_html += '</div>';
                                $(select_html).insertAfter(edit_pdf_col);
                                edit_form.find('select[name="acc_class"]').selectpicker('refresh');
                            }
                        }
                    }
                }, 100);
            }

            if (is_mrp_view_manufacturing_order && typeof acc_transaction_class !== 'undefined' && acc_transaction_class > 0) {
                var view_interval = setInterval(function() {
                    if (typeof jQuery !== 'undefined' && $('.project-overview-class-row').length === 0) {
                        var $ = jQuery;
                        var className = '';
                        $.each(acc_classes, function(i, c) {
                            if (c.id == acc_transaction_class) {
                                className = c.name;
                                return false;
                            }
                        });
                        if (className !== '') {
                            var tableBody = $('table.border.table-striped.table-margintop tbody');
                            if (tableBody.length > 0) {
                                var classRow = '<tr class="project-overview project-overview-class-row">';
                                classRow += '<td class="bold">' + acc_class_lang + '</td>';
                                classRow += '<td>' + className + '</td>';
                                classRow += '</tr>';
                                tableBody.append(classRow);
                                clearInterval(view_interval);
                            }
                        } else {
                            clearInterval(view_interval);
                        }
                    }
                }, 100);
            }

            // Fixed Equipment integration logic
            var is_fe_assets = path.indexOf('admin/fixed_equipment/assets') !== -1;
            var is_fe_licenses = path.indexOf('admin/fixed_equipment/licenses') !== -1;
            var is_fe_components = path.indexOf('admin/fixed_equipment/components') !== -1;
            var is_fe_consumables = path.indexOf('admin/fixed_equipment/consumables') !== -1;
            var is_fe_maintenances = path.indexOf('admin/fixed_equipment/assets_maintenances') !== -1;

            if (is_fe_assets || is_fe_licenses || is_fe_components || is_fe_consumables || is_fe_maintenances) {
                var fe_interval = setInterval(function() {
                    if (typeof jQuery !== 'undefined') {
                        var $ = jQuery;
                        
                        // 1. Assets
                        if (is_fe_assets) {
                            var modal = $('#add_new_assets');
                            if (modal.length > 0 && (modal.hasClass('in') || modal.is(':visible'))) {
                                if (modal.find('select[name="acc_class"]').length === 0) {
                                    var status_field = modal.find('select[name="status"]');
                                    if (status_field.length > 0) {
                                        var status_row = status_field.closest('.row');
                                        var select_html = '<div class="row acc_class_row">';
                                        select_html += '<div class="col-md-12 form-group">';
                                        select_html += '<label for="acc_class" class="control-label">' + acc_class_lang + '</label>';
                                        select_html += '<select name="acc_class" id="acc_class" class="selectpicker" data-width="100%" data-none-selected-text="None">';
                                        select_html += '<option value="0">None</option>';
                                        $.each(acc_classes, function(i, c) {
                                            select_html += '<option value="' + c.id + '">' + c.name + '</option>';
                                        });
                                        select_html += '</select>';
                                        select_html += '</div>';
                                        select_html += '</div>';
                                        
                                        $(select_html).insertAfter(status_row);
                                        var selectpicker_elem = modal.find('select[name="acc_class"]');
                                        selectpicker_elem.selectpicker('refresh');
                                        
                                        var asset_id = modal.find('input[name="id"]').val();
                                        if (asset_id && asset_id > 0) {
                                            $.get(admin_url + 'accounting/get_acc_class_ajax/' + asset_id + '/asset', function(response) {
                                                var data = JSON.parse(response);
                                                if (data && data.acc_class) {
                                                    selectpicker_elem.val(data.acc_class).selectpicker('refresh');
                                                }
                                            });
                                        }
                                    }
                                }
                            }
                        }

                        // 2. Licenses
                        if (is_fe_licenses) {
                            var modal = $('#add_new_licenses');
                            if (modal.length > 0 && (modal.hasClass('in') || modal.is(':visible'))) {
                                if (modal.find('select[name="acc_class"]').length === 0) {
                                    var dep_field = modal.find('select[name="depreciation"]');
                                    if (dep_field.length > 0) {
                                        var dep_row = dep_field.closest('.row');
                                        var select_html = '<div class="row acc_class_row">';
                                        select_html += '<div class="col-md-12 form-group">';
                                        select_html += '<label for="acc_class" class="control-label">' + acc_class_lang + '</label>';
                                        select_html += '<select name="acc_class" id="acc_class" class="selectpicker" data-width="100%" data-none-selected-text="None">';
                                        select_html += '<option value="0">None</option>';
                                        $.each(acc_classes, function(i, c) {
                                            select_html += '<option value="' + c.id + '">' + c.name + '</option>';
                                        });
                                        select_html += '</select>';
                                        select_html += '</div>';
                                        select_html += '</div>';
                                        
                                        $(select_html).insertAfter(dep_row);
                                        var selectpicker_elem = modal.find('select[name="acc_class"]');
                                        selectpicker_elem.selectpicker('refresh');
                                        
                                        var license_id = modal.find('input[name="id"]').val();
                                        if (license_id && license_id > 0) {
                                            $.get(admin_url + 'accounting/get_acc_class_ajax/' + license_id + '/license', function(response) {
                                                var data = JSON.parse(response);
                                                if (data && data.acc_class) {
                                                    selectpicker_elem.val(data.acc_class).selectpicker('refresh');
                                                }
                                            });
                                        }
                                    }
                                }
                            }
                        }

                        // 3. Components
                        if (is_fe_components) {
                            var modal = $('#add_new_components');
                            if (modal.length > 0 && (modal.hasClass('in') || modal.is(':visible'))) {
                                if (modal.find('select[name="acc_class"]').length === 0) {
                                    var cat_field = modal.find('select[name="category_id"]');
                                    if (cat_field.length > 0) {
                                        var cat_row = cat_field.closest('.row');
                                        var select_html = '<div class="row acc_class_row">';
                                        select_html += '<div class="col-md-12 form-group">';
                                        select_html += '<label for="acc_class" class="control-label">' + acc_class_lang + '</label>';
                                        select_html += '<select name="acc_class" id="acc_class" class="selectpicker" data-width="100%" data-none-selected-text="None">';
                                        select_html += '<option value="0">None</option>';
                                        $.each(acc_classes, function(i, c) {
                                            select_html += '<option value="' + c.id + '">' + c.name + '</option>';
                                        });
                                        select_html += '</select>';
                                        select_html += '</div>';
                                        select_html += '</div>';
                                        
                                        $(select_html).insertAfter(cat_row);
                                        var selectpicker_elem = modal.find('select[name="acc_class"]');
                                        selectpicker_elem.selectpicker('refresh');
                                        
                                        var component_id = modal.find('input[name="id"]').val();
                                        if (component_id && component_id > 0) {
                                            $.get(admin_url + 'accounting/get_acc_class_ajax/' + component_id + '/component', function(response) {
                                                var data = JSON.parse(response);
                                                if (data && data.acc_class) {
                                                    selectpicker_elem.val(data.acc_class).selectpicker('refresh');
                                                }
                                            });
                                        }
                                    }
                                }
                            }
                        }

                        // 4. Consumables
                        if (is_fe_consumables) {
                            var modal = $('#add_new_consumables');
                            if (modal.length > 0 && (modal.hasClass('in') || modal.is(':visible'))) {
                                if (modal.find('select[name="acc_class"]').length === 0) {
                                    var cat_field = modal.find('select[name="category_id"]');
                                    if (cat_field.length > 0) {
                                        var cat_row = cat_field.closest('.row');
                                        var select_html = '<div class="row acc_class_row">';
                                        select_html += '<div class="col-md-12 form-group">';
                                        select_html += '<label for="acc_class" class="control-label">' + acc_class_lang + '</label>';
                                        select_html += '<select name="acc_class" id="acc_class" class="selectpicker" data-width="100%" data-none-selected-text="None">';
                                        select_html += '<option value="0">None</option>';
                                        $.each(acc_classes, function(i, c) {
                                            select_html += '<option value="' + c.id + '">' + c.name + '</option>';
                                        });
                                        select_html += '</select>';
                                        select_html += '</div>';
                                        select_html += '</div>';
                                        
                                        $(select_html).insertAfter(cat_row);
                                        var selectpicker_elem = modal.find('select[name="acc_class"]');
                                        selectpicker_elem.selectpicker('refresh');
                                        
                                        var consumable_id = modal.find('input[name="id"]').val();
                                        if (consumable_id && consumable_id > 0) {
                                            $.get(admin_url + 'accounting/get_acc_class_ajax/' + consumable_id + '/consumable', function(response) {
                                                var data = JSON.parse(response);
                                                if (data && data.acc_class) {
                                                    selectpicker_elem.val(data.acc_class).selectpicker('refresh');
                                                }
                                            });
                                        }
                                    }
                                }
                            }
                        }

                        // 5. Maintenances
                        if (is_fe_maintenances) {
                            var modal = $('#add_new_assets_maintenances');
                            if (modal.length > 0 && (modal.hasClass('in') || modal.is(':visible'))) {
                                if (modal.find('select[name="acc_class"]').length === 0) {
                                    var maint_field = modal.find('select[name="maintenance_type"]');
                                    if (maint_field.length > 0) {
                                        var maint_row = maint_field.closest('.row');
                                        var select_html = '<div class="row acc_class_row">';
                                        select_html += '<div class="col-md-12 form-group">';
                                        select_html += '<label for="acc_class" class="control-label">' + acc_class_lang + '</label>';
                                        select_html += '<select name="acc_class" id="acc_class" class="selectpicker" data-width="100%" data-none-selected-text="None">';
                                        select_html += '<option value="0">None</option>';
                                        $.each(acc_classes, function(i, c) {
                                            select_html += '<option value="' + c.id + '">' + c.name + '</option>';
                                        });
                                        select_html += '</select>';
                                        select_html += '</div>';
                                        select_html += '</div>';
                                        
                                        $(select_html).insertAfter(maint_row);
                                        var selectpicker_elem = modal.find('select[name="acc_class"]');
                                        selectpicker_elem.selectpicker('refresh');
                                        
                                        var maintenance_id = modal.find('input[name="id"]').val();
                                        if (maintenance_id && maintenance_id > 0) {
                                            $.get(admin_url + 'accounting/get_acc_class_ajax/' + maintenance_id + '/maintenance', function(response) {
                                                var data = JSON.parse(response);
                                                if (data && data.acc_class) {
                                                     selectpicker_elem.val(data.acc_class).selectpicker('refresh');
                                                }
                                            });
                                        }
                                    }
                                }
                            }
                        }
                    }
                }, 200);
            }
        }

        // Project Budget Category dropdown insertion and enforcement logic
        var path = window.location.pathname;
        var is_expense = <?php echo (get_option('acc_enforce_expense') == '1') ? 'true' : 'false'; ?> && path.indexOf('admin/expenses/expense') !== -1;
        var is_pur_order = <?php echo (get_option('acc_enforce_purchase_order') == '1') ? 'true' : 'false'; ?> && (path.indexOf('admin/purchase/purchase_order') !== -1 || path.indexOf('admin/purchase/pur_order') !== -1);

        if (is_expense || is_pur_order) {
            <?php
            $budget_categories = $CI->db->get(db_prefix() . 'acc_project_budget_categories')->result_array();
            $expense_id = (strpos($viewuri, 'admin/expenses/expense/') !== false) ? intval(explode('?', explode('admin/expenses/expense/', $viewuri)[1])[0]) : 0;
            $po_id = 0;
            if (strpos($viewuri, 'admin/purchase/pur_order/') !== false) {
                $po_id = intval(explode('?', explode('admin/purchase/pur_order/', $viewuri)[1])[0]);
            }
            ?>
            var budget_categories = <?php echo json_encode($budget_categories); ?>;
            var expense_id = <?php echo $expense_id; ?>;
            var po_id = <?php echo $po_id; ?>;
            
            var interval_budget = setInterval(function() {
                if (typeof jQuery !== 'undefined') {
                    var $ = jQuery;
                    if ($('#acc_budget_category_id').length === 0) {
                        var project_select = is_expense ? $('select[name="project_id"]') : $('select[name="project"]');
                        if (project_select.length > 0) {
                            var project_col = project_select.closest('.form-group, .col-md-6');
                            if (project_col.length > 0) {
                                var container_class = is_expense ? 'form-group select-placeholder' : 'col-md-6 form-group';
                                var select_html = '<div class="' + container_class + '" id="acc_budget_cat_container">';
                                select_html += '<label for="acc_budget_category_id" class="control-label">Project Budget Category</label>';
                                select_html += '<select name="acc_budget_category_id" id="acc_budget_category_id" class="selectpicker" data-width="100%" data-none-selected-text="Select Budget Category">';
                                select_html += '<option value=""></option>';
                                $.each(budget_categories, function(i, cat) {
                                    select_html += '<option value="' + cat.id + '">' + cat.name + '</option>';
                                });
                                select_html += '</select>';
                                select_html += '</div>';

                                $(select_html).insertAfter(project_col);
                                $('<style>#acc_budget_cat_container .bootstrap-select.open { position: relative !important; } #acc_budget_alert { position: static !important; }</style>').appendTo('head');
                                $('#acc_budget_category_id').selectpicker();

                                // Fetch existing mapping if editing
                                var current_rel_id = is_expense ? expense_id : po_id;
                                var current_rel_type = is_expense ? 'expense' : 'po';
                                if (current_rel_id > 0) {
                                    $.get(admin_url + 'accounting/get_budget_mapping_ajax/' + current_rel_id + '/' + current_rel_type, function(response) {
                                        if (response) {
                                            var data = JSON.parse(response);
                                            if (data && data.category_id) {
                                                $('#acc_budget_category_id').val(data.category_id).selectpicker('refresh');
                                                acc_check_budget_limits();
                                            }
                                        }
                                    });
                                }

                                clearInterval(interval_budget);
                            }
                        }
                    }
                }
            }, 100);

            function acc_check_budget_limits() {
                if (typeof jQuery === 'undefined') return;
                var $ = jQuery;
                
                var project_id = 0;
                if (is_expense) {
                    project_id = $('select[name="project_id"]').val();
                } else if (is_pur_order) {
                    project_id = $('select[name="project"]').val();
                }
                
                var category_id = $('#acc_budget_category_id').val();
                
                var amount = 0;
                if (is_expense) {
                    amount = parseFloat($('input[name="amount"]').val()) || 0;
                } else if (is_pur_order) {
                    amount = parseFloat($('input[name="grand_total"]').val()) || parseFloat($('.wh-total').text().replace(/[^0-9.-]+/g,"")) || 0;
                    var currency_rate = parseFloat($('input[name="currency_rate"]').val()) || 1;
                    if (currency_rate > 0) {
                        amount = amount / currency_rate;
                    }
                }
                
                if (!project_id || !category_id || amount <= 0) {
                    $('#acc_budget_alert').remove();
                    $('button[type="submit"], button.save-form, button.sub-btn, .transaction-submit, .save-and-send').prop('disabled', false);
                    return;
                }
                
                var exclude_id = is_expense ? expense_id : po_id;
                var type = is_expense ? 'expense' : 'po';
                
                var date = '';
                if (is_expense) {
                    date = $('input[name="date"]').val();
                } else if (is_pur_order) {
                    date = $('input[name="order_date"]').val() || $('input[name="date"]').val();
                }

                $.post(admin_url + 'accounting/check_budget_ajax', {
                    project_id: project_id,
                    category_id: category_id,
                    amount: amount,
                    exclude_id: exclude_id,
                    type: type,
                    date: date
                }, function(response) {
                    var data = JSON.parse(response);
                    $('#acc_budget_alert').remove();
                    
                    if (data.exceeded) {
                        var alert_class = 'alert-warning';
                        if (data.enforcement === 'disable') {
                            alert_class = 'alert-danger';
                            $('button[type="submit"], button.save-form, button.sub-btn, .transaction-submit, .save-and-send').prop('disabled', true);
                        } else {
                            $('button[type="submit"], button.save-form, button.sub-btn, .transaction-submit, .save-and-send').prop('disabled', false);
                        }
                        
                        var alert_html = '<div id="acc_budget_alert" class="alert ' + alert_class + ' col-md-12" style="margin-top: 15px; position: static !important;">';
                        alert_html += '<strong>Project Budget:</strong> ' + data.message;
                        alert_html += '</div>';
                        
                        $('#acc_budget_cat_container').append(alert_html);
                    } else {
                        $('button[type="submit"], button.save-form, button.sub-btn, .transaction-submit, .save-and-send').prop('disabled', false);
                    }
                });
            }
 
            if (typeof jQuery !== 'undefined') {
                var $ = jQuery;
                $(document).on('change', 'select[name="project_id"], select[name="project"], #acc_budget_category_id, input[name="date"], input[name="order_date"]', function() {
                    acc_check_budget_limits();
                });
                
                $(document).on('keyup change', 'input[name="amount"], input[name="grand_total"], input[name="currency_rate"], input.quantity, input.rate, input[name="shipping_fee"], input[name="order_discount"]', function() {
                    acc_check_budget_limits();
                });

                setInterval(function() {
                    if ($('#acc_budget_category_id').length > 0) {
                        acc_check_budget_limits();
                    }
                }, 2000);
            }
        }
    });
    <?php
    echo '</script>';

	if (!(strpos($viewuri, 'admin/accounting') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/tinymce_init.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/banking?group=banking_register') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/banking/banking_register.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/banking?group=posted_bank_transactions') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/banking/posted_bank_transactions.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/banking?group=reconcile_bank_account&bank_account=') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/banking/reconcile_bank_account_detail.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/banking?group=reconcile_bank_account') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/banking/reconcile_bank_account.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/banking?group=banking_feeds') === false)) {
		echo '<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/banking/plaid_new_transaction.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/banking?group=plaid_new_transaction') === false)) {
		echo '<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/banking/plaid_new_transaction.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=banking') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/banking.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=sales') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/sales.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=expenses') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/expenses.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=payslips') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/payslips.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=purchase') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/purchase_order.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=warehouse') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/warehouse.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=stock_export') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/stock_export.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/setting?group=general') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/general.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/setting?group=mapping_setup') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/automatic_conversion.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/setting?group=banking_rules') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/banking_rules.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/setting?group=account_type_details') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/account_type_details.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/new_rule') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/new_rule.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/journal_entry') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/journal_entry/manage.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, 'admin/accounting/new_journal_entry') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/reconcile') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/reconcile/reconcile.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if(!(strpos($viewuri,'admin/accounting/rp_') === false) || !(strpos($viewuri,'admin/accounting/report') === false)){
        echo '<script src="'.module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/simple-tree-table/src/jquery-simple-tree-table.js').'?v=' . ACCOUNTING_REVISION.'"></script>';
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/report/jspdf.min.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
        
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/report/html2pdf.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/report/tableHTMLExport.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/report/main.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
    }

	if (!(strpos($viewuri, '/admin/accounting/dashboard') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/budget') === false) || !(strpos($viewuri, 'admin/accounting/user_register_view') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=manufacturing') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/manufacturing.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=omni_sales') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/omni_sales.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if(!(strpos($viewuri, 'admin/accounting/checks') === false) || !(strpos($viewuri, 'admin/accounting/check') === false)){
        echo '<script src="' . base_url('assets/plugins/signature-pad/signature_pad.min.js') . '"></script>';
    }

	if (!(strpos($viewuri, 'admin/accounting/configure_checks') === false)) {
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/bill/configure_checks.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/accounting/transaction?group=fixed_equipment') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/fixed_equipment.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/vendors') === false)) {
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/vendors/vendor_manage.js') .'?v=' . ACCOUNTING_REVISION.'"></script>';
    }

    if (!(strpos($viewuri, 'admin/accounting/setting?group=income_statement_modification') === false)) {
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/income_statement_modification.js') .'?v=' . ACCOUNTING_REVISION.'"></script>';
    }

    if(!(strpos($viewuri, '/admin/accounting/setting?group=currency_rates') === false)){
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/currency_rate.js') .'?v=' . ACCOUNTING_REVISION.'"></script>';
    }

    if(!(strpos($viewuri, '/admin/accounting/setting?group=class') === false)){
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/class.js') .'?v=' . ACCOUNTING_REVISION.'"></script>';
    }

    if(!(strpos($viewuri, '/admin/accounting/setting?group=budget_categories') === false)){
        echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/budget_categories.js') .'?v=' . ACCOUNTING_REVISION.'"></script>';
    }

	if (!(strpos($viewuri, 'admin/accounting/add_claim') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/claims/claim.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/view_claim') === false)) {
		echo '<script src="' . base_url('assets/plugins/signature-pad/signature_pad.min.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/claims/detail.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/project_budget_detail') === false)) {
		echo '<script src="' . base_url('assets/plugins/signature-pad/signature_pad.min.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/project_budgets/detail.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/add_imprest') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/imprests/imprest.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/retire_imprest') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/imprests/retire.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/view_imprest') === false)) {
		echo '<script src="' . base_url('assets/plugins/signature-pad/signature_pad.min.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/imprests/detail.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}
}

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function accounting_module_init_menu_items() {
	$CI = &get_instance();

	if (has_permission('accounting_dashboard', '', 'view') || has_permission('accounting_transaction', '', 'view') || has_permission('accounting_journal_entry', '', 'view') || has_permission('accounting_transfer', '', 'view') || has_permission('accounting_chart_of_accounts', '', 'view') || has_permission('accounting_reconcile', '', 'view') || has_permission('accounting_report', '', 'view') || has_permission('accounting_setting', '', 'view') || has_permission('acc_project_budgets', '', 'view') || has_permission('acc_imprests', '', 'view') || has_permission('acc_claims', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('accounting', [
			'name' => _l('als_accounting'),
			'icon' => 'fa fa-usd',
			'position' => 5,
		]);

		if (has_permission('accounting_dashboard', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_dashboard',
				'name' => _l('dashboard'),
				'icon' => 'fa fa-home',
				'href' => admin_url('accounting/dashboard'),
				'position' => 1,
			]);
		}

		if (has_permission('accounting_banking', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_banking',
				'name' => _l('banking'),
				'icon' => 'fa fa-university',
				'href' => admin_url('accounting/banking?group=bank_accounts'),
				'position' => 2,
			]);
		}

		if (has_permission('accounting_transaction', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_transaction',
				'name' => _l('transaction'),
				'icon' => 'fa fa-file',
				'href' => admin_url('accounting/transaction?group=sales'),
				'position' => 2,
			]);
		}

		if (has_permission('accounting_registers', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_registers',
				'name' => _l('registers'),
				'icon' => 'fa fa-list',
				'href' => admin_url('accounting/registers'),
				'position' => 2,
			]);
		}

		if (has_permission('accounting_bills', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_bills',
				'name' => _l('bills'),
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/bills'),
				'position' => 3,
			]);
		}

		// Checks Menu
		if (has_permission('accounting_checks', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_checks',
				'name' => _l('acc_checks'),
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/checks'),
				'position' => 3,
			]);
		}


		if (has_permission('accounting_journal_entry', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_journal_entry',
				'name' => _l('journal_entry'),
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/journal_entry'),
				'position' => 3,
			]);
		}

		if (has_permission('accounting_transfer', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_transfer',
				'name' => _l('acc_transfer'),
				'icon' => 'fa fa-exchange',
				'href' => admin_url('accounting/transfer'),
				'position' => 4,
			]);
		}

		if (has_permission('accounting_chart_of_accounts', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_chart_of_accounts',
				'name' => _l('chart_of_accounts'),
				'icon' => 'fa fa-list-ol',
				'href' => admin_url('accounting/chart_of_accounts'),
				'position' => 5,
			]);
		}

		if (has_permission('accounting_reconcile', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_reconcile',
				'name' => _l('reconcile'),
				'icon' => 'fa fa-sliders',
				'href' => admin_url('accounting/reconcile'),
				'position' => 6,
			]);
		}

		if (has_permission('accounting_budget', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_budget',
				'name' => _l('budget'),
				'icon' => 'fa fa-exchange',
				'href' => admin_url('accounting/budget'),
				'position' => 7,
			]);
		}

		if(!acc_get_status_modules('purchase')){
			if (has_permission('accounting_vendor', '', 'view')) {
				$CI->app_menu->add_sidebar_children_item('accounting', [
					'slug' => 'accounting_vendor',
					'name' => _l('vendors'),
					'icon' => 'fa fa-users',
					'href' => admin_url('accounting/vendors'),
					'position' => 8,
				]);
			}
        }

		if (has_permission('acc_project_budgets', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'acc_project_budgets',
				'name' => _l('project_budgets'),
				'icon' => 'fa fa-briefcase',
				'href' => admin_url('accounting/project_budgets'),
				'position' => 8,
			]);
		}

		if (has_permission('acc_imprests', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'acc_imprests',
				'name' => _l('imprests'),
				'icon' => 'fa fa-credit-card',
				'href' => admin_url('accounting/imprests'),
				'position' => 8,
			]);
		}

		if (has_permission('acc_claims', '', 'view') || is_admin()) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'acc_claims',
				'name' => _l('claims'),
				'icon' => 'fa fa-file-text',
				'href' => admin_url('accounting/claims'),
				'position' => 8,
			]);
		}

		if (has_permission('accounting_report', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_report',
				'name' => _l('reports'),
				'icon' => 'fa fa-area-chart',
				'href' => admin_url('accounting/report'),
				'position' => 8,
			]);
		}
		
		if (has_permission('accounting_setting', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_setting',
				'name' => _l('setting'),
				'icon' => 'fa fa-cog',
				'href' => admin_url('accounting/setting?group=general'),
				'position' => 9,
			]);
		}
	}
}

/**
 * Init accounting module permissions in setup in admin_init hook
 */
function accounting_permissions() {
	$permission_prefix = 'Accounting - ';

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
	];
	register_staff_capabilities('accounting_dashboard', $capabilities, _l('accounting_dashboard'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_banking', $capabilities, _l('accounting_banking'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_transaction', $capabilities, _l('accounting_transaction'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('acc_project_budgets', $capabilities, _l('accounting_project_budgets'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('acc_imprests', $capabilities, _l('accounting_imprests'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('acc_claims', $capabilities, _l('accounting_claims'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_registers', $capabilities, _l('accounting_registers'));
	
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_bills', $capabilities, _l('accounting_bills'));

	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_checks', $capabilities, _l('accounting_checks'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_journal_entry', $capabilities, _l('accounting_journal_entry'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_transfer', $capabilities, _l('accounting_transfer'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_chart_of_accounts', $capabilities, _l('accounting_chart_of_accounts'));
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
	];
	register_staff_capabilities('accounting_reconcile', $capabilities, _l('accounting_reconcile'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_budget', $capabilities, _l('accounting_budget'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_vendor', $capabilities, _l('accounting_vendors'));


	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
	];
	register_staff_capabilities('accounting_report', $capabilities, _l('accounting_report'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_setting', $capabilities, _l('accounting_setting'));
}

function accounting_preactivate($module_name){
    if ($module_name['system_name'] == ACCOUNTING_MODULE_NAME) {             
        require_once 'libraries/gtsslib.php';
        $accounting_api = new AccountingLic();
        $accounting_gtssres = $accounting_api->verify_license();          
        if(!$accounting_gtssres || ($accounting_gtssres && isset($accounting_gtssres['status']) && !$accounting_gtssres['status'])){
             $CI = & get_instance();
            $data['submit_url'] = $module_name['system_name'].'/gtsverify/activate'; 
            $data['original_url'] = admin_url('modules/activate/'.ACCOUNTING_MODULE_NAME); 
            $data['module_name'] = ACCOUNTING_MODULE_NAME; 
            $data['title'] = "Module License Activation"; 
            echo $CI->load->view($module_name['system_name'].'/activate', $data, true);
            exit();
        }        
    }
}

function accounting_predeactivate($module_name){
    if ($module_name['system_name'] == ACCOUNTING_MODULE_NAME) {
        require_once 'libraries/gtsslib.php';
        $accounting_api = new AccountingLic();
        $accounting_api->deactivate_license();
    }
}

function acc_automatic_invoice_conversion($data) {
	if ($data) {
		if (get_option('acc_invoice_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			if(isset($data['id'])){
				$CI->accounting_model->automatic_invoice_conversion($data['id']);
			}else{
				$CI->accounting_model->automatic_invoice_conversion($data);
			}
		}

	}

	return $data;
}

function acc_automatic_payment_conversion($data) {
	if ($data) {
		if (get_option('acc_payment_automatic_conversion') == 1 || get_option('acc_active_payment_mode_mapping') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			if(isset($data['id'])){
				$CI->accounting_model->automatic_payment_conversion($data['id']);
			}else{
				$CI->accounting_model->automatic_payment_conversion($data);
			}
		}

	}

	return $data;
}

function acc_automatic_expense_conversion($data) {
	if ($data) {
		if (get_option('acc_expense_automatic_conversion') == 1 || get_option('acc_active_expense_category_mapping') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			if(isset($data['id'])){
				$CI->accounting_model->automatic_expense_conversion($data['id']);
			}else{
				$CI->accounting_model->automatic_expense_conversion($data);
			}
		}

	}
	return $data;
}

function acc_delete_invoice_convert($invoice_id) {
	if ($invoice_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_invoice_convert($invoice_id);

	}

	return $invoice_id;
}

function acc_delete_payment_convert($data) {
	if ($data['paymentid']) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($data['paymentid'], 'payment');
	}

	return $data;
}

function acc_delete_expense_convert($expense_id) {
	if ($expense_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($expense_id, 'expense');
	}

	return $expense_id;
}

function acc_invoice_status_changed($data) {
	$CI = &get_instance();
	$CI->load->model('accounting/accounting_model');

	$CI->accounting_model->invoice_status_changed($data);

	return $data;
}

function acc_delete_pur_order_convert($pur_order_id) {
	if ($pur_order_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($pur_order_id, 'purchase_order');
	}

	return $pur_order_id;
}

function acc_delete_payslip_convert($payslip_id) {
	if ($payslip_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($payslip_id, 'payslip');
	}

	return $payslip_id;
}

function acc_delete_stock_export_convert($goods_delivery_id) {
	if ($goods_delivery_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($goods_delivery_id, 'stock_export');
	}

	return $goods_delivery_id;
}

function acc_delete_stock_import_convert($goods_receipt_id) {
	if ($goods_receipt_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($goods_receipt_id, 'stock_import');
	}

	return $goods_receipt_id;
}

function acc_delete_loss_adjustment_convert($loss_adjustment_id) {
	if ($loss_adjustment_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($loss_adjustment_id, 'loss_adjustment');
	}

	return $loss_adjustment_id;
}


function acc_automatic_pur_invoice_payment_convert($id) {
	if ($id) {
		if (get_option('acc_pur_payment_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_purchase_payment_conversion($id);
		}

	}
	return $id;
}

function acc_delete_pur_invoice_payment_convert($pur_invoice_payment_id) {
	if ($pur_invoice_payment_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($pur_invoice_payment_id, 'purchase_payment');
	}

	return $pur_invoice_payment_id;
}

function acc_automatic_credit_note_apply_conversion($data) {
	$acc_credit_note_mapping_mode = get_option('acc_credit_note_mapping_mode');

	if ($acc_credit_note_mapping_mode == 'on_apply') {
		if (get_option('acc_credit_note_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			$CI->accounting_model->automatic_credit_note_apply_conversion($data['credit_id']);
		}
	}

	return $data;
}

function acc_automatic_credit_note_conversion($id) {
	$acc_credit_note_mapping_mode = get_option('acc_credit_note_mapping_mode');

	if ($acc_credit_note_mapping_mode == 'on_create') {
		if (get_option('acc_credit_note_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			$CI->accounting_model->automatic_credit_note_conversion($id);
		}
	}

	return $id;
}

function acc_delete_applied_credit_convert($data) {
	$acc_credit_note_mapping_mode = get_option('acc_credit_note_mapping_mode');

	if ($acc_credit_note_mapping_mode == 'on_apply') {
		if ($data['id']) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			$CI->accounting_model->delete_convert($data['id'], 'credit_note');
		}
	}

	return $data;
}

function acc_delete_credit_note_convert($id) {
	if ($id) {
		$acc_credit_note_mapping_mode = get_option('acc_credit_note_mapping_mode');

		if ($acc_credit_note_mapping_mode == 'on_apply') {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->load->model('credit_notes_model');
			
			$credit_notes = $CI->credit_notes_model->get($id);

			foreach($credit_notes->refunds as $refund){
				$CI->accounting_model->delete_convert($refund['id'], 'credit_note_refund');
			}

			foreach($credit_notes->applied_credits as $applied_credit){
				$CI->accounting_model->delete_convert($applied_credit['id'], 'credit_note');
			}
		}else{
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->delete_convert($id, 'credit_note');
		}
	}

	return $id;
}

function acc_automatic_credit_note_refund_conversion($data) {

	if (get_option('acc_credit_note_refund_automatic_conversion') == 1) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->automatic_credit_note_refund_conversion($data['refund_id']);
	}

	return $data;
}

function acc_delete_credit_note_refund_convert($data) {

	if ($data['refund_id']) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($data['refund_id'], 'credit_note_refund');
	}

	return $data;
}

function acc_automatic_manufacturing_order_conversion($data) {

	if(isset($data['data']['status'])){
		if ($data['data']['status'] == 'done') {
			if (get_option('acc_mrp_manufacturing_order_automatic_conversion') == 1) {
				$CI = &get_instance();
				$CI->load->model('accounting/accounting_model');

				$CI->accounting_model->automatic_manufacturing_order_conversion($data['id']);
			}
		}else{
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			$CI->accounting_model->delete_convert($data['id'], 'manufacturing_order');
		}
	}

	return $data;
}


function acc_automatic_omni_sales_return_order_conversion($data) {
	if($data['data_order']->original_order_id != ''){
		if ($data['data']['status'] == '5') {
			if (get_option('acc_omni_sales_order_return_automatic_conversion') == 1) {
				$CI = &get_instance();
				$CI->load->model('accounting/accounting_model');
				$CI->accounting_model->automatic_omni_sales_return_order_conversion($data['data_order']->id);
			}
		}else{
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			$CI->accounting_model->delete_convert($data['data_order']->id, 'sales_return_order');
		}
	}

	return $data;
}



function acc_delete_manufacturing_order_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'manufacturing_order');
	}

	return $id;
}

function acc_automatic_pur_order_return_convert($data) {
	if ($data['status'] == 'finish') {
		if (get_option('acc_mrp_manufacturing_order_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			$CI->accounting_model->automatic_purchase_order_return_conversion($data['id']);
		}
	}else{
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($data['id'], 'purchase_order_return');
	}

	return $data;
}

function acc_delete_pur_order_return_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'purchase_order_return');
		$CI->accounting_model->delete_pur_refund_convert_by_order($id);

	}

	return $data;
}


function acc_automatic_pur_refund_convert($id) {
	if ($id) {
		if (get_option('acc_pur_refund_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_purchase_refund_conversion($id);
		}

	}
	return $id;
}

function acc_delete_pur_refund_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'purchase_refund');
	}

	return $data;
}


function acc_automatic_pur_order_convert($id) {
	if ($id) {
		if (get_option('acc_pur_order_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_purchase_order_conversion($id);
		}

	}
	return $id;
}


function acc_automatic_pur_invoice_convert($id) {
	if ($id) {
		if (get_option('acc_pur_invoice_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_purchase_invoice_conversion($id);
		}

	}
	return $id;
}


function acc_delete_pur_invoice_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'purchase_invoice');
	}

	return $data;
}


function acc_delete_omni_sales_order_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'sales_return_order');
	}

	return $id;
}


function acc_automatic_omni_sales_refund_convert($id) {
	if ($id) {
		if (get_option('acc_omni_sales_refund_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_omni_sales_refund_conversion($id);
		}

	}
	return $id;
}

function acc_delete_omni_sales_refund_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'sales_refund');
	}

	return $id;
}


function acc_automatic_wh_goods_receipt_convert($id) {
	if ($id) {
		if (get_option('acc_wh_stock_import_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_stock_import_conversion($id);
		}

	}
	return $id;
}


function acc_automatic_wh_goods_delivery_convert($id) {
	if ($id) {
		if (get_option('acc_wh_stock_export_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_stock_export_conversion($id);
		}

	}
	return $id;
}


function acc_automatic_wh_loss_adjustment_convert($id) {
	if ($id) {
		if (get_option('acc_wh_loss_adjustment_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_loss_adjustment_conversion($id);
		}

	}
	return $id;
}


function acc_automatic_fe_asset_convert($id) {
	if ($id) {
		if (get_option('acc_fe_asset_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_fe_asset_conversion($id);
		}

	}
	return $id;
}

function acc_delete_fe_asset_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'fe_asset');
		$CI->accounting_model->delete_convert($id, 'fe_licenses');
		$CI->accounting_model->delete_convert($id, 'fe_component');
		$CI->accounting_model->delete_convert($id, 'fe_consumable');

		$CI->accounting_model->delete_depreciation_convert_by_asset($id);
	}

	return $id;
}

function acc_automatic_fe_license_convert($id) {
	if ($id) {
		if (get_option('acc_fe_license_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_fe_license_conversion($id);
		}

	}
	return $id;
}

function acc_delete_fe_license_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'fe_license');
	}

	return $id;
}

function acc_automatic_fe_component_convert($id) {
	if ($id) {
		if (get_option('acc_fe_component_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_fe_component_conversion($id);
		}

	}
	return $id;
}

function acc_automatic_fe_consumable_convert($id) {
	if ($id) {
		if (get_option('acc_fe_consumable_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_fe_consumable_conversion($id);
		}

	}
	return $id;
}

function acc_automatic_fe_maintenance_convert($id) {
	if ($id) {
		if (get_option('acc_fe_maintenance_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_fe_maintenance_conversion($id);
		}

	}
	return $id;
}

function acc_automatic_fe_depreciation_convert($id) {
	if ($id) {
		if (get_option('acc_fe_depreciation_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_fe_depreciation_conversion($id);
		}

	}
	return $id;
}


function acc_delete_fe_maintenance_convert($id) {
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'fe_maintenance');
	}

	return $id;
}

function acc_before_client_added($data) {
	if (isset($data['balance'])) {
        $data['balance'] = str_replace(',', '', $data['balance']);
        if($data['balance'] != '' && $data['balance'] > 0){
            if($data['balance_as_of'] != ''){
                $data['balance_as_of'] = to_sql_date($data['balance_as_of']);
            }else{
                $data['balance_as_of'] = date('Y-m-d');
            }
        }else{
            unset($data['balance']);
            unset($data['balance_as_of']);
        }
    }
    
	return $data;
}

function acc_client_created($data) {
	$CI = &get_instance();
	$CI->load->model('accounting/accounting_model');

	$CI->accounting_model->acc_client_created($data);

	return $data;
}

function acc_client_updated($data, $id) {
	$CI = &get_instance();
	$CI->load->model('accounting/accounting_model');

	if (isset($data['balance'])) {
        $data['balance'] = str_replace(',', '', $data['balance']);
        if($data['balance'] != '' && $data['balance'] > 0){
            if($data['balance_as_of'] != ''){
                $data['balance_as_of'] = to_sql_date($data['balance_as_of']);
            }else{
                $data['balance_as_of'] = date('Y-m-d');
            }
        }else{
            unset($data['balance']);
            unset($data['balance_as_of']);
        }
    }

	$CI->accounting_model->acc_client_updated($data, $id);

	return $data;
}

function acc_init_client_profile($client = ''){
	$balance = $client ? $client->balance : '';
	$balance_as_of = $client ? _d($client->balance_as_of) : '';
	$attr = [];
	$date_attr = [];
	$attr['data-type'] = 'currency';

	if($client && $client->balance != null){
		// $attr['disabled'] = 'true';
		// $date_attr['disabled'] = 'true';
	}

    $option = '<div class="row">
          <div class="col-md-6">
          '. render_input('balance', 'balance', $balance, 'text', $attr) .'
          </div>
          <div class="col-md-6">
          '. render_date_input('balance_as_of', 'as_of', $balance_as_of, $date_attr) .'
          </div>
        </div>';
    echo html_entity_decode($option);
}

function acc_pur_vendor_created($data) {
	$CI = &get_instance();
	$CI->load->model('accounting/accounting_model');

	$CI->accounting_model->acc_pur_vendor_created($data);

	return $data;
}

function acc_pur_vendor_updated($data, $id) {
	$CI = &get_instance();
	$CI->load->model('accounting/accounting_model');

	// $CI->accounting_model->acc_pur_vendor_updated($data, $id);

	return $data;
}


function acc_init_pur_vendor_profile($vendor = ''){
	$balance = $vendor ? $vendor->balance : '';
	$balance_as_of = $vendor ? _d($vendor->balance_as_of) : '';
	$attr = [];
	$date_attr = [];
	$attr['data-type'] = 'currency';

	if($vendor && $vendor->balance != null){
		// $attr['disabled'] = 'true';
		// $date_attr['disabled'] = 'true';
	}

    $option = '<div class="row">
          <div class="col-md-6">
          '. render_input('balance', 'balance', $balance, 'text', $attr) .'
          </div>
          <div class="col-md-6">
          '. render_date_input('balance_as_of', 'as_of', $balance_as_of, $date_attr) .'
          </div>
        </div>';
    echo html_entity_decode($option);
}

function exporting_return_order_approved($id) {
	if ($id) {
		if (get_option('acc_wh_stock_import_return_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('warehouse/warehouse_model');
			$CI->load->model('warehouse/warehouse_model');
			$order_return = $CI->warehouse_model->get_order_return($id);

			if($order_return){
				$CI->load->model('accounting/accounting_model');
				$CI->accounting_model->automatic_stock_import_conversion($order_return->receipt_delivery_id);
			}
		}
	}
	return $id;
}

/**
 * get currency rates
 *
 */
function acc_cronjob_currency_rates($manually) {
    $CI = &get_instance();
    $CI->load->model('accounting/accounting_model');
    if (date('G') == '16' && get_option('cr_automatically_get_currency_rate') == 1) {
        if(date('Y-m-d') != get_option('cur_date_cronjob_currency_rates')){
            $CI->accounting_model->cronjob_currency_rates($manually);
        }
    }

    $CI->accounting_model->recurring_journal_entry();
    $CI->accounting_model->recurring_bills();
}

function acc_update_payslip_status($id, $status) {
	$CI = &get_instance();
	$CI->load->model('accounting/accounting_model');
	if ($status == 'payslip_closing') {
		$CI->accounting_model->automatic_payslip_conversion($id);
	}else{
		$CI->accounting_model->delete_convert($id, 'payslip');
	}

	return true;
}


/**
 * Initializes the vendor option.
 *
 * @param      string  $expense  The expense
 */
function init_acc_class_option($expense = ''){
    $CI = &get_instance();
    $CI->load->model('accounting/accounting_model');
	$classes = $CI->accounting_model->get_class();

    $value = (isset($expense) ? $expense->acc_class : '');
    echo render_select('acc_class',$classes,array('id','name'),'acc_class',$value);
}



function acc_automatic_debit_note_apply_conversion($id) {
	if (get_option('acc_debit_note_automatic_conversion') == 1) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');
		$acc_debit_note_mapping_mode = get_option('acc_debit_note_mapping_mode');
  	
		if ($acc_debit_note_mapping_mode == 'on_apply') {
			$CI->accounting_model->automatic_debit_note_apply_conversion($id);
		}
	}

	return $id;
}

function acc_automatic_debit_note_conversion($id) {
	if (get_option('acc_debit_note_automatic_conversion') == 1) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');
		$acc_debit_note_mapping_mode = get_option('acc_debit_note_mapping_mode');
  	
		if ($acc_debit_note_mapping_mode == 'on_create') {
			$CI->accounting_model->automatic_debit_note_conversion($id);
		}
	}

	return $id;
}

function acc_delete_applied_debit_convert($id) {
	if ($id) {
		$CI = &get_instance();
  		$acc_debit_note_mapping_mode = get_option('acc_debit_note_mapping_mode');

		if ($acc_debit_note_mapping_mode == 'on_apply') {
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->delete_convert($id, 'debit_note');
		}
	}

	return $id;
}

function acc_delete_debit_note_convert($id) {
	if ($id) {
  		$acc_debit_note_mapping_mode = get_option('acc_debit_note_mapping_mode');
		
		if ($acc_debit_note_mapping_mode == 'on_create') {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->delete_convert($id, 'debit_note');
		}else{
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->load->model('purchase/purchase_model');

			$debit_notes = $CI->purchase_model->get_debit_note($id);

			foreach($debit_notes->refunds as $refund){
				$CI->accounting_model->delete_convert($refund['id'], 'debit_note_refund');
			}

			foreach($debit_notes->applied_debits as $applied_credit){
				$CI->accounting_model->delete_convert($applied_credit['id'], 'debit_note');
			}
		}
	}

	return $id;
}

function acc_automatic_debit_note_refund_conversion($id) {

	if (get_option('acc_debit_note_refund_automatic_conversion') == 1) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->automatic_debit_note_refund_conversion($id);
	}

	return $id;
}

function acc_delete_debit_note_refund_convert($id) {
  	
	if ($id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($id, 'debit_note_refund');
	}

	return $id;
}

function acc_credit_note_status_changed($id, $data) {
	$acc_credit_note_mapping_mode = get_option('acc_credit_note_mapping_mode');

	if ($acc_credit_note_mapping_mode == 'on_create') {
		if (get_option('acc_credit_note_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			if ($data['status'] == 3) {
				$CI->accounting_model->delete_convert($id, 'credit_note');
			}elseif($data['status'] == 1){
				$CI->accounting_model->automatic_credit_note_conversion($id);
			}
		}
	}

	return $id;
}

function acc_debit_note_status_changed($id, $data) {
	$acc_debit_note_mapping_mode = get_option('acc_debit_note_mapping_mode');

	if ($acc_debit_note_mapping_mode == 'on_create') {
		if (get_option('acc_debit_note_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			if ($data['status'] == 3) {
				$CI->accounting_model->delete_convert($id, 'debit_note');
			}elseif($data['status'] == 1){
				$CI->accounting_model->automatic_debit_note_conversion($id);
			}
		}
	}

	return $id;
}

function acc_not_importable_expenses_fields($data)
{
    $data[] = 'vendor';
    $data[] = 'due_date';
    $data[] = 'date_paid';
    $data[] = 'status';
    $data[] = 'is_bill';
    $data[] = 'reason_for_void';
    $data[] = 'voided';
    $data[] = 'approved';
    $data[] = 'acc_mapping';
    $data[] = 'acc_class';
    $data[] = 'recurring';
    $data[] = 'recurring_type';
    $data[] = 'custom_recurring';
    $data[] = 'cycles';
    $data[] = 'total_cycles';
    $data[] = 'last_recurring_date';
    $data[] = 'acc_is_recurring_from';

    return $data;
}


function acc_invoice_class_save($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'invoices', ['acc_class' => $class_id]);
    }
}
function acc_credit_note_class_save($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'creditnotes', ['acc_class' => $class_id]);
    }
}
function acc_expense_class_save($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'expenses', ['acc_class' => $class_id]);
    }
}
function acc_purchase_order_class_save($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'pur_orders', ['acc_class' => $class_id]);
    }
}
function acc_purchase_invoice_class_save($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'pur_invoices', ['acc_class' => $class_id]);
    }
}



function acc_goods_receipt_class_save($id) {
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'goods_receipt', ['acc_class' => $class_id]);
    }
}

function acc_goods_delivery_class_save($id) {
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'goods_delivery', ['acc_class' => $class_id]);
    }
}

function acc_loss_adjustment_class_save($id) {
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'wh_loss_adjustment', ['acc_class' => $class_id]);
    }
}

function acc_payslip_class_save($id) {
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'hrp_payslips', ['acc_class' => $class_id]);
    }
}

function acc_debit_note_class_save($id) {
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'pur_debit_notes', ['acc_class' => $class_id]);
    }
}

function acc_omni_sale_order_return_class_save($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'cart', ['acc_class' => $class_id]);
    }
}

function acc_fe_asset_class_save($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'fe_assets', ['acc_class' => $class_id]);
    }
}

function acc_fe_maintenance_class_save($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI = &get_instance();
    if ($CI->input->post('acc_class') !== null) {
        $class_id = intval($CI->input->post('acc_class'));
        $CI->db->where('id', $id)->update(db_prefix() . 'fe_asset_maintenances', ['acc_class' => $class_id]);
    }
}

hooks()->add_action('after_expense_added', 'acc_save_expense_budget_mapping');
hooks()->add_action('expense_updated', 'acc_save_expense_budget_mapping');
hooks()->add_action('after_purchase_order_add', 'acc_save_po_budget_mapping');
hooks()->add_action('purchase_order_updated', 'acc_save_po_budget_mapping');
hooks()->add_action('after_purchase_order_updated', 'acc_save_po_budget_mapping');
hooks()->add_action('after_pur_order_updated', 'acc_save_po_budget_mapping');

function acc_prepare_budget_enforcement_for_post($project_id, $category_id, $amount, $exclude_id = null, $type = 'expense', $date = null) {
    $CI =& get_instance();
    $CI->load->model('accounting/accounting_model');
    return $CI->accounting_model->get_project_budget_enforcement_result($project_id, $category_id, $amount, $exclude_id, $type, $date);
}

function acc_handle_budget_notify_if_needed($result, $project_id, $message, $link = '') {
    if (!empty($result['exceeded']) && isset($result['check']['enforcement']) && $result['check']['enforcement'] == 'notify') {
        $CI =& get_instance();
        $CI->load->model('accounting/accounting_model');
        $CI->accounting_model->notify_project_budget_manager($project_id, $message, $link);
    }
}

function acc_save_expense_budget_mapping($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI =& get_instance();
    if ($CI->input->post('acc_budget_category_id') !== null) {
        $category_id = intval($CI->input->post('acc_budget_category_id'));
        $expense = $CI->db->select('project_id, amount, date')->where('id', $id)->get(db_prefix() . 'expenses')->row();
        if ($expense && $expense->project_id > 0 && $category_id > 0) {
            $CI->load->model('accounting/accounting_model');
            $result = $CI->accounting_model->get_project_budget_enforcement_result($expense->project_id, $category_id, $expense->amount, $id, 'expense', $expense->date);
            $mapping_amount = !empty($result['check']['has_budget']) ? $expense->amount : 0;
            $old_mapping = $CI->db->where('rel_type', 'expense')->where('rel_id', $id)->get(db_prefix() . 'acc_project_budget_mappings')->row();
            $CI->accounting_model->update_project_budget_mapping($id, 'expense', $expense->project_id, $category_id, $mapping_amount, $result['status']);
            if ($result['status'] == 'pending' && (!$old_mapping || $old_mapping->budget_approval_status != 'pending')) {
                $CI->accounting_model->send_budget_approval_required_notifications('expense', $id);
            }
            acc_handle_budget_notify_if_needed($result, $expense->project_id, 'Project Budget warning: Expense EXP-' . $id . ' exceeds remaining budget.', 'expenses/list_expenses/' . $id);
        }
    }
}

function acc_save_po_budget_mapping($id) {
    if (is_array($id)) { $id = isset($id['id']) ? $id['id'] : 0; }
    if (!$id) { return; }
    $CI =& get_instance();
    if ($CI->input->post('acc_budget_category_id') !== null) {
        $category_id = intval($CI->input->post('acc_budget_category_id'));
        $po = $CI->db->select('project, total, order_date, pur_order_number, currency_rate')->where('id', $id)->get(db_prefix() . 'pur_orders')->row();
        if ($po && $po->project > 0 && $category_id > 0) {
            $CI->load->model('accounting/accounting_model');
            $po_base_amount = floatval($po->currency_rate) > 0 ? floatval($po->total) / floatval($po->currency_rate) : floatval($po->total);
            $result = $CI->accounting_model->get_project_budget_enforcement_result($po->project, $category_id, $po_base_amount, $id, 'po', $po->order_date);
            $mapping_amount = !empty($result['check']['has_budget']) ? $po->total : 0;
            $old_mapping = $CI->db->where('rel_type', 'po')->where('rel_id', $id)->get(db_prefix() . 'acc_project_budget_mappings')->row();
            $CI->accounting_model->update_project_budget_mapping($id, 'po', $po->project, $category_id, $mapping_amount, $result['status']);
            if ($result['status'] == 'pending' && (!$old_mapping || $old_mapping->budget_approval_status != 'pending')) {
                $CI->accounting_model->send_budget_approval_required_notifications('po', $id);
            }
            $budget_detail_link = $CI->accounting_model->get_project_budget_detail_link($po->project, $po->order_date);
            acc_handle_budget_notify_if_needed($result, $po->project, 'Project Budget warning: Purchase Order ' . $po->pur_order_number . ' exceeds remaining budget.', $budget_detail_link != '' ? $budget_detail_link : 'purchase/purchase_order/' . $id);
        }
    }
}

hooks()->add_filter('before_expense_added', 'acc_before_expense_save_filter');
hooks()->add_filter('before_expense_updated', 'acc_before_expense_save_filter');

function acc_before_expense_save_filter($data) {
    if (get_option('acc_enforce_expense') == '1' && isset($data['acc_budget_category_id']) && intval($data['acc_budget_category_id']) > 0 && !empty($data['project_id'])) {
        $CI =& get_instance();
        $expense_id = $CI->uri->segment(4) ? intval($CI->uri->segment(4)) : null;
        $result = acc_prepare_budget_enforcement_for_post(
            intval($data['project_id']),
            intval($data['acc_budget_category_id']),
            isset($data['amount']) ? floatval($data['amount']) : 0,
            $expense_id,
            'expense',
            isset($data['date']) ? $data['date'] : null
        );

        if ($result['blocked']) {
            set_alert('danger', 'Cannot save Expense: Project Budget exceeded. Remaining budget: ' . app_format_number($result['check']['remaining']));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    if (isset($data['acc_budget_category_id'])) {
        unset($data['acc_budget_category_id']);
    }
    return $data;
}

