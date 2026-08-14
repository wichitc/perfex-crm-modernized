<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * CSRF exclusion list for the WooCommerce module.
 *
 * Each entry is a regex anchored with `^...$` and matched (case-insensitive)
 * against `$this->uri->uri_string()` by `App_Security::csrf_verify()`. Use
 * standard PCRE patterns — NOT CodeIgniter's `(:any)` route placeholder,
 * which evaluates to the literal string ":any" inside a regex group.
 *
 * Two categories are exempted:
 *
 * 1. The public webhook receiver. Authenticated by HMAC, not by Perfex
 *    session — there's no logged-in staff to mint a CSRF token.
 *
 * 2. Admin-side AJAX-only endpoints called from `assets/js/*.js`. Per the
 *    Perfex CRM forms guide (https://help.perfexcrm.com/working-with-forms/)
 *    AJAX routes are excluded from CSRF and instead rely on:
 *      - `has_permission(...)` capability checks at the start of every
 *        endpoint
 *      - the same-origin cookie policy (we POST from the admin page)
 *      - the `X-Requested-With: XMLHttpRequest` header WooFetch sends
 *
 *    Browser-rendered `<form method="post">` blocks (setup wizard,
 *    store-switcher, order detail's status/delete forms, customer
 *    import) still go through CSRF — they all carry a hidden token via
 *    `woo_csrf_input()` (see `helpers/woocommerce_helper.php`).
 */
return [
    // Public webhook receiver — HMAC-authenticated, no Perfex session.
    // Tolerates the legacy `admin/` prefix so any webhooks already
    // registered in WooCommerce against the older URL keep delivering
    // until the admin re-runs Webhooks → Generate.
    '(admin/)?woocommerce/webhook/index/\d+',

    // Stores — store add/edit wizard support endpoints (T6.3, T6.10).
    'admin/woocommerce/stores/credentials_test',
    'admin/woocommerce/stores/save',
    'admin/woocommerce/stores/webhooks_generate/\d+',
    'admin/woocommerce/stores/webhooks_delete/\d+',

    // Stores — field-mapping editor endpoints (T6.4).
    'admin/woocommerce/stores/(add_mapping|delete_mapping|load_preset|reset_tab|preflight|override_mapping|revert_mapping)/(customer|contact|product|order)/\d+',

    // Woocommerce — product edit modal save (T6.8).
    'admin/woocommerce/woocommerce/update_product/\d+/\d+',

    // Woocommerce — manual product → Perfex item linking (T5.x / T6.7 step 3).
    'admin/woocommerce/woocommerce/link_product/\d+/\d+',

    // Woocommerce — orders list bulk actions.
    'admin/woocommerce/woocommerce/(bulk_convert|bulk_mark_completed)',

    // Woocommerce — customers list bulk import.
    'admin/woocommerce/woocommerce/bulk_import_customer',
];
