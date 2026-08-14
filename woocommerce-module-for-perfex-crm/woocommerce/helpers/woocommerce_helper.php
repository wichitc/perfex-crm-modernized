<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Bootstrap helpers — staff capabilities, sidebar menu, and the
 * spec §7.3 cleanup hooks that null out cross-table linkage when
 * the linked row is removed elsewhere in Perfex.
 *
 * Registered from woocommerce.php via hooks()->add_action.
 */

if (! function_exists('woo_csrf_input')) {
    /**
     * Emit Perfex's CSRF hidden input for raw `<form method="post">`
     * blocks that don't go through `form_open()`. CodeIgniter's CSRF
     * guard rejects requests missing the token with HTTP 419 — see
     * https://help.perfexcrm.com/working-with-forms/.
     *
     * Idempotent: the same token is valid for the entire session
     * (`csrf_regenerate = false` in `application/config/config.php`),
     * so dropping multiple calls into one page is safe.
     */
    function woo_csrf_input(): void
    {
        $ci = &get_instance();
        if (! isset($ci->security)) {
            return;
        }
        echo '<input type="hidden" name="'
            . html_escape($ci->security->get_csrf_token_name())
            . '" value="'
            . html_escape($ci->security->get_csrf_hash())
            . '">';
    }
}

if (! function_exists('woo_invoice_preview_woo_badge')) {
    /**
     * Render a "From WooCommerce" pill in the invoice preview tab strip
     * when the invoice carries a `wco_id`. Hooked onto Perfex's
     * `after_admin_invoice_preview_template_tab_menu_last_item` action
     * (fires inside `<ul class="nav nav-tabs">` after the toggle-view
     * separator, so the pill sits inline with the tabs).
     *
     * Reads the linked Woo order id straight from the invoice object —
     * no extra query unless we resolve the store id for the deep-link,
     * and even that's a single indexed lookup.
     */
    function woo_invoice_preview_woo_badge(mixed $invoice = null): void
    {
        if (! is_object($invoice) || empty($invoice->wco_id)) {
            return;
        }

        $wooOrderId = (int) $invoice->wco_id;
        $storeId    = isset($invoice->store_id) ? (int) $invoice->store_id : 0;

        $href = $storeId > 0
            ? admin_url('woocommerce/woocommerce/order/' . $storeId . '/' . $wooOrderId)
            : admin_url('woocommerce/orders');

        echo '<li role="presentation" class="tab-separator" '
            . 'data-toggle="tooltip" data-title="' . html_escape(_l('woocommerce_invoice_from_woo_tooltip')) . '">'
            . '<a href="' . html_escape($href) . '" target="_blank" rel="noopener" '
            . 'class="tw-text-purple-700 tw-font-medium">'
            . '<i class="fa fa-shopping-bag mright3" aria-hidden="true"></i>'
            . html_escape(_l('woocommerce_invoice_from_woo_label', '#' . $wooOrderId))
            . '</a>'
            . '</li>';
    }
}

if (! function_exists('woo_register_tables')) {
    /**
     * Register the module's `App_table` definitions on `admin_init`.
     * Each table is paired with a view file under `views/tables/`
     * which is included by `App_table::init()` to call
     * `outputUsing(...)->setRules(...)`. This is the same shape as
     * Perfex core's `application/helpers/table_helper.php`.
     *
     * Hooked from `woocommerce.php`.
     */
    function woo_register_tables(): void
    {
        if (! class_exists('App_table')) {
            return;
        }

        $tablesDir = realpath(__DIR__ . '/../views/tables') ?: (__DIR__ . '/../views/tables');

        App_table::register(
            App_table::new('woo_products', $tablesDir . DIRECTORY_SEPARATOR . 'products')
        );
        App_table::register(
            App_table::new('woo_orders', $tablesDir . DIRECTORY_SEPARATOR . 'orders')
        );
        App_table::register(
            App_table::new('woo_customers', $tablesDir . DIRECTORY_SEPARATOR . 'customers')
        );
        App_table::register(
            App_table::new('woo_logs', $tablesDir . DIRECTORY_SEPARATOR . 'logs')
        );
    }
}

if (! function_exists('woo_permissions')) {
    /**
     * Capability set Perfex offers under "Staff → Permissions →
     * WooCommerce". Names match non-negotiable #4 / spec §9.2 and
     * the has_permission gates on every state-changing endpoint.
     */
    function woo_permissions(): void
    {
        $capabilities = [
            'capabilities' => [
                'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
                'create' => _l('permission_create'),
                'edit'   => _l('permission_edit'),
                'delete' => _l('permission_delete'),
            ],
        ];
        register_staff_capabilities(WOOCOMMERCE_MODULE_NAME, $capabilities, _l('woocommerce'));
    }
}

if (! function_exists('woocommerce_init_menu_items')) {
    /**
     * Sidebar menu — collapsible parent + children for every screen
     * v3 ships. Children are gated on the staff's view permission;
     * Stores + Logs + Diagnostic are admin-only because they expose
     * cross-tenant data + secrets metadata.
     */
    function woocommerce_init_menu_items(): void
    {
        if (staff_cant('view', WOOCOMMERCE_MODULE_NAME)) {
            return;
        }

        $CI = &get_instance();
        $CI->app_menu->add_sidebar_menu_item('woocommerce-menu', [
            'name'     => 'WooCommerce',
            'collapse' => true,
            'position' => 11,
            'icon'     => 'fa fa-shopping-bag',
        ]);

        $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
            'slug'     => 'woo-orders',
            'name'     => _l('woocommerce_orders'),
            'href'     => admin_url('woocommerce/orders'),
            'icon'     => 'fa fa-list-alt',
            'position' => 11,
        ]);
        $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
            'slug'     => 'woo-customers',
            'name'     => _l('woocommerce_customers'),
            'href'     => admin_url('woocommerce/customers'),
            'icon'     => 'fa fa-users',
            'position' => 13,
        ]);
        $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
            'slug'     => 'woo-products',
            'name'     => _l('woocommerce_products'),
            'href'     => admin_url('woocommerce/products'),
            'icon'     => 'fa fa-cubes',
            'position' => 16,
        ]);

        if (function_exists('is_admin') && is_admin()) {
            $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
                'slug'     => 'woo-stores',
                'name'     => _l('woocommerce_stores'),
                'href'     => admin_url('woocommerce/stores'),
                'icon'     => 'fa fa-shopping-cart',
                'position' => 18,
            ]);
            $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
                'slug'     => 'woo-logs',
                'name'     => _l('woocommerce_logs'),
                'href'     => admin_url('woocommerce/logs'),
                'icon'     => 'fa fa-list',
                'position' => 20,
            ]);
            $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
                'slug'     => 'woo-diagnostic',
                'name'     => _l('woocommerce_diagnostic'),
                'href'     => admin_url('woocommerce/diagnostic'),
                'icon'     => 'fa fa-stethoscope',
                'position' => 22,
            ]);
        }
    }
}

// ---------------------------------------------------------------------------
// Spec §7.3 cleanup hooks. Called by Perfex core; we null out our
// cross-table linkage so a deleted invoice / client / item / payment
// doesn't leave orphan refs in our cache tables.
// ---------------------------------------------------------------------------

if (! function_exists('woo_after_invoice_deleted')) {
    /** @param mixed $invoiceId */
    function woo_after_invoice_deleted($invoiceId): void
    {
        /** @var CI_DB_query_builder $db */
        $db = get_instance()->db;
        $db->where('invoice_id', (int) $invoiceId)
            ->update(db_prefix() . 'woocommerce_orders', ['invoice_id' => null]);
    }
}

if (! function_exists('woo_after_client_deleted')) {
    /** @param mixed $clientId */
    function woo_after_client_deleted($clientId): void
    {
        /** @var CI_DB_query_builder $db */
        $db = get_instance()->db;
        $db->where('userid', (int) $clientId)
            ->update(db_prefix() . 'woocommerce_customers', ['userid' => null]);
    }
}

if (! function_exists('woo_after_item_deleted')) {
    /** @param mixed $itemId */
    function woo_after_item_deleted($itemId): void
    {
        /** @var CI_DB_query_builder $db */
        $db = get_instance()->db;
        $db->where('itemid', (int) $itemId)
            ->update(db_prefix() . 'woocommerce_products', ['itemid' => null]);
    }
}

if (! function_exists('woo_after_payment_deleted')) {
    /**
     * §7.3 addition for v3: when a payment is deleted, the linked
     * invoice may need to flip back from "paid" to a not-paid status.
     * For now we just log so support can spot it; the auto re-flow
     * lands with PaymentRecorder's reverse-record method (T8.x).
     *
     * @param mixed $paymentId
     */
    function woo_after_payment_deleted($paymentId): void
    {
        if (function_exists('log_activity')) {
            log_activity('WooCommerce: payment ' . (int) $paymentId . ' deleted; linked Woo invoice may need re-record on next tick.');
        }
    }
}
