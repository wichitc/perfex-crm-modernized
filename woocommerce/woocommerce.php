<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

require_once __DIR__ . '/vendor/autoload.php';

/*
Module Name: WooCommerce module
Description: Sync WooCommerce stores, products, customers, and orders into Perfex CRM. v3 rebuild.
Author: Boxvibe
Author URI: https://support.boxvibe.com/articles?product_id=6
Version: 3.0.0-dev
Requires at least: 3.4.1
*/

const WOOCOMMERCE_MODULE_NAME    = 'woocommerce';
const WOOCOMMERCE_MODULE_VERSION = '3.0.0-dev';

require_once __DIR__ . '/helpers/preflight_helper.php';

$wooPreflight = woocommerce_preflight(
    function_exists('get_app_version') ? (string) get_app_version() : '0.0.0',
    PHP_VERSION_ID,
    PHP_VERSION
);

if (! $wooPreflight['ok']) {
    if (function_exists('log_activity')) {
        log_activity('WooCommerce module pre-flight failed: '
            . woocommerce_preflight_format_message($wooPreflight));
    }
    return;
}

unset($wooPreflight);

register_language_files(WOOCOMMERCE_MODULE_NAME, [WOOCOMMERCE_MODULE_NAME]);

require_once __DIR__ . '/helpers/cron_helper.php';
require_once __DIR__ . '/helpers/woocommerce_helper.php';
require_once __DIR__ . '/helpers/field_dictionary_helper.php';

register_activation_hook(WOOCOMMERCE_MODULE_NAME, static function (): void {
    require_once __DIR__ . '/install.php';
});

register_cron_task('woocommerce_cron');

// Permissions + sidebar menu items. Run on admin_init so
// $CI->app_menu is wired and has_permission() resolves correctly.
// Surface a "From WooCommerce — Order #N" indicator on Perfex's
// invoice preview when the invoice carries `wco_id`. Listener checks
// the value and bails when missing, so non-Woo invoices stay
// untouched.
hooks()->add_action(
    'after_admin_invoice_preview_template_tab_menu_last_item',
    'woo_invoice_preview_woo_badge'
);

hooks()->add_action('admin_init', 'woo_permissions');
hooks()->add_action('admin_init', 'woocommerce_init_menu_items');
hooks()->add_action('admin_init', 'woo_register_tables');

// Cleanup hooks (spec §7.3): null our cross-table refs when their
// target row is deleted elsewhere in Perfex so we don't leave orphan
// references in the cache tables.
hooks()->add_action('after_invoice_deleted', 'woo_after_invoice_deleted');
hooks()->add_action('after_client_deleted',  'woo_after_client_deleted');
hooks()->add_action('item_deleted',          'woo_after_item_deleted');
hooks()->add_action('after_payment_deleted', 'woo_after_payment_deleted');

if (! function_exists('woocommerce_load_assets')) {
    /**
     * Loads the module's compiled CSS into Perfex's admin head. Hooked
     * onto `app_admin_head` so it lands inside <head> on every admin
     * request — same lifecycle hook other modules use for theme CSS.
     */
    function woocommerce_load_assets(): void
    {
        if (! function_exists('module_dir_url')) {
            return;
        }
        echo '<link rel="stylesheet" href="'
            . module_dir_url(WOOCOMMERCE_MODULE_NAME, 'assets/css/woomodule.css')
            . '?v=' . WOOCOMMERCE_MODULE_VERSION
            . '">';
    }
}

hooks()->add_action('app_admin_head', 'woocommerce_load_assets');

if (! function_exists('woocommerce_load_footer_assets')) {
    /**
     * Module-wide JS primitives (T6.15: toasts + inline validation).
     * Loaded into the admin footer so they're available on every admin
     * page — per-screen scripts (`stores.js`, `products.js` etc.) call
     * `window.WooToast` / `window.WooValidate` directly.
     */
    function woocommerce_load_footer_assets(): void
    {
        if (! function_exists('module_dir_url')) {
            return;
        }
        echo '<script src="'
            . module_dir_url(WOOCOMMERCE_MODULE_NAME, 'assets/js/woomodule.js')
            . '?v=' . WOOCOMMERCE_MODULE_VERSION
            . '"></script>';
    }
}
hooks()->add_action('app_admin_footer', 'woocommerce_load_footer_assets');
