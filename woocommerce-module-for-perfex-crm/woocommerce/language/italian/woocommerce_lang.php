<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

// =====================================================================
// italian translation file.
//
// Currently seeded from `english/woocommerce_lang.php` so every key has
// a fallback (no `[lang_key_missing]` artifacts when an admin switches
// Perfex to italian). Reviewed translations land via T7.2 — until then,
// strings render in English. Update each value below; do not delete or
// rename keys.
// =====================================================================


$lang['woocommerce']    = 'WooCommerce';
$lang['woocommerce_php']    = 'PHP';
$lang['woocommerce_perfex'] = 'Perfex';

// Sidebar menu labels (woocommerce_helper.php). _l() returns an empty
// string when a key is missing, so omitting these silently renders the
// child menu items with no visible name.
$lang['woocommerce_orders']     = 'Orders';
$lang['woocommerce_customers']  = 'Customers';
$lang['woocommerce_products']   = 'Products';
$lang['woocommerce_stores']     = 'Stores';
$lang['woocommerce_logs']       = 'Logs';
$lang['woocommerce_diagnostic'] = 'Diagnostic';

// Store add/edit wizard (T6.3).
$lang['woocommerce_new_store']                  = 'Nuovo negozio';
$lang['woocommerce_edit_store']                 = 'Modifica negozio';
$lang['woocommerce_store']                      = 'Store';
$lang['woocommerce_store_name']                 = 'Store name';
$lang['woocommerce_store_url']                  = 'Store URL';
$lang['woocommerce_store_url_help']             = 'Full URL including https:// — must be reachable from this server.';
$lang['woocommerce_store_not_found']            = 'Store not found.';
$lang['woocommerce_store_wizard_subtitle']      = 'Connect a WooCommerce store to Perfex CRM in four short steps.';
$lang['woocommerce_wizard_steps']               = 'Wizard steps';
$lang['woocommerce_wizard_basics']              = 'Basics';
$lang['woocommerce_wizard_basics_help']         = 'Give the store a name and the public URL.';
$lang['woocommerce_wizard_credentials']         = 'Credentials';
$lang['woocommerce_wizard_credentials_help']    = 'Paste the consumer key + secret from WooCommerce → Settings → Advanced → REST API. Use the "Test Connection" button before continuing.';
$lang['woocommerce_wizard_sync_options']        = 'Sync options';
$lang['woocommerce_wizard_sync_options_help']   = 'Decide what gets converted automatically and how aggressively the cron syncs each tick.';
$lang['woocommerce_wizard_webhooks']            = 'Webhooks';
$lang['woocommerce_wizard_webhooks_help']       = 'Webhooks let WooCommerce notify Perfex the instant something changes — much fresher than the cron poll.';
$lang['woocommerce_wizard_webhooks_after_save'] = 'Webhooks can be generated after the store is saved. Save now, then return to set them up.';
$lang['woocommerce_wizard_next']                = 'Next';
$lang['woocommerce_wizard_back']                = 'Back';
$lang['woocommerce_wizard_create_store']        = 'Create store';
$lang['woocommerce_consumer_key']               = 'Consumer key';
$lang['woocommerce_consumer_secret']            = 'Consumer secret';
$lang['woocommerce_verify_ssl']                 = 'Verify SSL certificate';
$lang['woocommerce_query_auth']                 = 'Send credentials in query string (legacy hosts)';
$lang['woocommerce_test_connection']            = 'Test connection';
$lang['woocommerce_pages_per_tick']             = 'Pages per cron tick';
$lang['woocommerce_pages_per_tick_help']        = '1–50. Higher values catch up faster on busy stores; lower values are gentler on rate limits.';
$lang['woocommerce_auto_convert']               = 'Auto-convert from WooCommerce';
$lang['woocommerce_auto_convert_customer']      = 'Customers → Perfex clients';
$lang['woocommerce_auto_convert_product']       = 'Products → Perfex items';
$lang['woocommerce_auto_convert_order']         = 'Orders → Perfex invoices';
$lang['woocommerce_auto_invoice_statuses']      = 'Order statuses that trigger auto-invoice';
$lang['woocommerce_auto_invoice_statuses_help'] = 'Only orders matching one of these statuses are converted automatically.';
$lang['woocommerce_refresh_queued']             = 'Manual sync queued — runs on the next cron tick.';

// Webhook panel (T6.10).
$lang['woocommerce_webhooks']                   = 'Webhooks';
$lang['woocommerce_webhooks_for_store']         = 'Webhooks — %s';
$lang['woocommerce_webhook_panel_help']         = 'Webhooks let WooCommerce notify Perfex the instant something changes. Each topic posts to this Perfex install with the store\'s shared secret.';
$lang['woocommerce_webhook_panel_empty']        = 'No webhooks registered yet — pick the topics you want and click "Generate".';
$lang['woocommerce_webhook_topics']             = 'Topics';
$lang['woocommerce_webhook_topic_orders']       = 'Orders (created / updated / deleted)';
$lang['woocommerce_webhook_topic_products']     = 'Products (created / updated / deleted)';
$lang['woocommerce_webhook_topic_customers']    = 'Customers (created / updated / deleted)';
$lang['woocommerce_generate_webhooks']          = 'Generate';
$lang['woocommerce_validate_webhooks']          = 'Validate';
$lang['woocommerce_webhook_topic']              = 'Topic';
$lang['woocommerce_webhook_deliveries']         = 'Deliveries';
$lang['woocommerce_webhook_last_delivery']      = 'Last delivery';
$lang['woocommerce_webhook_sig_ok_fail']        = 'Sig OK / Fail';

// Field mappings editor (T6.4).
$lang['woocommerce_field_mappings']             = 'Field mappings';
$lang['woocommerce_field_mappings_subtitle']    = 'Map WooCommerce fields to Perfex fields for %s.';
$lang['woocommerce_tab_customer']               = 'Customer';
$lang['woocommerce_tab_product']                = 'Product';
$lang['woocommerce_tab_order']                  = 'Order';
$lang['woocommerce_wc_field']                   = 'WooCommerce field';
$lang['woocommerce_perfex_field']               = 'Perfex field';
$lang['woocommerce_required']                   = 'Required';
$lang['woocommerce_default_value']              = 'Default';
$lang['woocommerce_mapping_predefined']         = 'Predefined';
$lang['woocommerce_mapping_overridden']         = 'Overridden';
$lang['woocommerce_mapping_custom']             = 'Custom';
$lang['woocommerce_mapping_add']                = 'Add Mapping';
$lang['woocommerce_mapping_load_preset']        = 'Load Preset';
$lang['woocommerce_mapping_preflight']          = 'Pre-flight Check';
$lang['woocommerce_mapping_reset_tab']          = 'Reset Tab';
$lang['woocommerce_mapping_empty']              = 'No mappings yet — click "Load Preset" or "Add Mapping" to get started.';

// Product edit modal (T6.8).
$lang['woocommerce_edit_product']               = 'Edit Product';
$lang['woocommerce_product_name']               = 'Name';
$lang['woocommerce_product_sku']                = 'SKU';
$lang['woocommerce_regular_price']              = 'Regular price';
$lang['woocommerce_sale_price']                 = 'Sale price';
$lang['woocommerce_image_url']                  = 'Image URL';
$lang['woocommerce_stock']                      = 'Stock';
$lang['woocommerce_manage_stock']               = 'Manage stock at product level';
$lang['woocommerce_stock_quantity']             = 'Stock quantity';
$lang['woocommerce_stock_status']               = 'Stock status';
$lang['woocommerce_product_modal_remote_note']  = 'Saving sends the changes to WooCommerce. Local cache will refresh on the next sync tick.';

// First-run setup wizard (T6.13).
$lang['woocommerce_setup']                          = 'Setup';
$lang['woocommerce_setup_title']                    = 'Welcome to WooCommerce for Perfex';
$lang['woocommerce_setup_subtitle']                 = 'A short, four-step setup gets your first store ready in a few minutes.';
$lang['woocommerce_setup_step_welcome']             = 'Welcome';
$lang['woocommerce_setup_step_connect']             = 'Connect store';
$lang['woocommerce_setup_step_mappings']            = 'Field mappings';
$lang['woocommerce_setup_step_webhooks']            = 'Webhooks';
$lang['woocommerce_setup_welcome_title']            = 'Let\'s get your WooCommerce store talking to Perfex.';
$lang['woocommerce_setup_welcome_body']             = 'In the next steps you will:';
$lang['woocommerce_setup_welcome_b1']               = 'Connect a store with a WooCommerce REST API key.';
$lang['woocommerce_setup_welcome_b2']               = 'Load curated field mappings so customers, products, and orders import sensibly out-of-the-box.';
$lang['woocommerce_setup_welcome_b3']               = 'Generate webhooks so changes in WooCommerce reach Perfex instantly.';
$lang['woocommerce_setup_lets_start']               = 'Let\'s go';
$lang['woocommerce_setup_connect_title']            = 'Connect your first store';
$lang['woocommerce_setup_connect_body']             = 'Have your WooCommerce admin handy. You\'ll need a Consumer Key + Secret with read/write access from WooCommerce → Settings → Advanced → REST API.';
$lang['woocommerce_setup_open_store_wizard']        = 'Open store wizard';
$lang['woocommerce_setup_store_already_connected']  = 'Store connected — continue to mappings.';
$lang['woocommerce_setup_skip']                     = 'Skip';
$lang['woocommerce_setup_mappings_title']           = 'Load preset field mappings';
$lang['woocommerce_setup_mappings_body']            = 'These are sensible defaults curated for typical WooCommerce stores. You can tweak any of them later under Stores → Field Mappings.';
$lang['woocommerce_setup_load_presets']             = 'Load preset mappings';
$lang['woocommerce_setup_no_store_yet']             = 'Connect a store first — presets need to know which store to apply to.';
$lang['woocommerce_setup_presets_loaded']           = 'Loaded %d preset mapping rows.';
$lang['woocommerce_setup_webhooks_title']           = 'Generate webhooks';
$lang['woocommerce_setup_webhooks_body']            = 'Webhooks let WooCommerce notify Perfex the instant something changes — much fresher than polling. We\'ll open the panel where you can pick which topics to subscribe to.';
$lang['woocommerce_setup_open_webhook_panel']       = 'Open webhook panel';
$lang['woocommerce_setup_finish']                   = 'Finish setup';

// Pre-flight messages (T0.6). %1$s = required version, %2$s = detected version.
$lang['woocommerce_preflight_php_too_old']    = 'WooCommerce module requires PHP %1$s or newer. Your PHP is %2$s. Please upgrade PHP first.';
$lang['woocommerce_preflight_perfex_too_old'] = 'WooCommerce module requires Perfex CRM %1$s or newer. Your Perfex is %2$s. Please upgrade Perfex first.';

// Post-install banner (T1.5).
$lang['woocommerce_post_install_regenerate_webhooks'] = 'Action required: a webhook signing secret has been generated for one or more stores. Regenerate the WooCommerce webhooks so they sign new deliveries with the new secret.';

// ----------------------------------------------------------------------
// Bulk-added strings — surfaced when running the missing-key audit
// against `views/`, `controllers/`, and `helpers/`. Translations may be
// re-flowed in Phase 7 (T7.2). Keep alphabetical within each section
// for easy diffing.
// ----------------------------------------------------------------------

// Status / store-status pills
$lang['woocommerce_active']                     = 'Active';
$lang['woocommerce_active_store']               = 'Active store';
$lang['woocommerce_inactive']                   = 'Inactive';
$lang['woocommerce_completed']                  = 'Completed';
$lang['woocommerce_created']                    = 'Created';
$lang['woocommerce_deleted']                    = 'Deleted';
$lang['woocommerce_failed']                     = 'Failed';
$lang['woocommerce_paid']                       = 'Paid';
$lang['woocommerce_processed_ok']               = 'Processed';
$lang['woocommerce_signature_ok']               = 'Signature OK';
$lang['woocommerce_set']                        = 'Set';
$lang['woocommerce_not_set']                    = 'Not set';
$lang['woocommerce_unknown']                    = 'Unknown';
$lang['woocommerce_disabled_for_self_signed']   = 'SSL verification disabled (self-signed)';
$lang['woocommerce_store_status_healthy']       = 'Healthy';
$lang['woocommerce_store_status_no_webhooks']   = 'No webhooks';
$lang['woocommerce_store_status_inactive']      = 'Inactive';

// Generic UI verbs / pagination / filter
$lang['woocommerce_all']                        = 'All';
$lang['woocommerce_all_statuses']               = 'All statuses';
$lang['woocommerce_apply']                      = 'Apply';
$lang['woocommerce_clear']                      = 'Clear';
$lang['woocommerce_search']                     = 'Search';
$lang['woocommerce_select_all']                 = 'Select all';
$lang['woocommerce_switch']                     = 'Switch';
$lang['woocommerce_page_of']                    = 'Page %d of %d';
$lang['woocommerce_showing_n_of_total']         = 'Showing %d of %d';
$lang['woocommerce_imported_only']              = 'Imported only';
$lang['woocommerce_linked_only']                = 'Linked only';
$lang['woocommerce_low_stock']                  = 'Low stock';
$lang['woocommerce_breadcrumbs']                = 'Breadcrumbs';

// Bulk + actions
$lang['woocommerce_bulk_action']                = 'Bulk action';
$lang['woocommerce_bulk_convert']               = 'Convert to invoices';
$lang['woocommerce_bulk_mark_completed']        = 'Mark as completed';
$lang['woocommerce_bulk_select']                = 'Select rows for bulk actions';
$lang['woocommerce_convert']                    = 'Convert';
$lang['woocommerce_convert_to_invoice']         = 'Convert to invoice';
$lang['woocommerce_update_status']              = 'Update status';
$lang['woocommerce_delete_on_woo']              = 'Delete on WooCommerce';
$lang['woocommerce_confirm_delete_order']       = 'Delete this order on WooCommerce? This cannot be undone.';
$lang['woocommerce_refresh']                    = 'Refresh';
$lang['woocommerce_import']                     = 'Import';
$lang['woocommerce_import_to_perfex']           = 'Import to Perfex';
$lang['woocommerce_import_explainer']           = 'Creates a Perfex client linked to this WooCommerce customer.';

// Order detail
$lang['woocommerce_order_n']                    = 'Order #%s';
$lang['woocommerce_order_number']               = 'Order number';
$lang['woocommerce_order_live_unavailable']     = 'Live order data unavailable; showing cached values.';
$lang['woocommerce_select_order']               = 'Select order';
$lang['woocommerce_line_items']                 = 'Line items';
$lang['woocommerce_no_line_items']              = 'No line items.';
$lang['woocommerce_qty']                        = 'Qty';
$lang['woocommerce_price']                      = 'Price';
$lang['woocommerce_total']                      = 'Total';
$lang['woocommerce_grand_total']                = 'Grand total';
$lang['woocommerce_tax_total']                  = 'Tax total';
$lang['woocommerce_shipping']                   = 'Shipping';
$lang['woocommerce_billing']                    = 'Billing';
$lang['woocommerce_same_as_billing']            = 'Same as billing';
$lang['woocommerce_fee']                        = 'Fee';
$lang['woocommerce_payment_method']             = 'Payment method';
$lang['woocommerce_timeline']                   = 'Timeline';
$lang['woocommerce_recent_orders']              = 'Recent orders';
$lang['woocommerce_no_orders_for_customer']     = 'No orders yet for this customer.';
$lang['woocommerce_view_invoice']               = 'View invoice';
$lang['woocommerce_invoice']                    = 'Invoice';
$lang['woocommerce_other']                      = 'Other';
$lang['woocommerce_danger_zone']                = 'Danger zone';
$lang['woocommerce_conversion']                 = 'Conversion';

// Customer / Guest
$lang['woocommerce_customer']                   = 'Customer';
$lang['woocommerce_customer_not_found']         = 'Customer not found';
$lang['woocommerce_customer_not_found_body']    = 'No WooCommerce customer matches that ID. It may have been deleted remotely.';
$lang['woocommerce_guest']                      = 'Guest';
$lang['woocommerce_guest_customer']             = 'Guest customer';
$lang['woocommerce_guest_customer_explainer']   = 'A placeholder Perfex client will be created on conversion.';
$lang['woocommerce_perfex_client']              = 'Perfex client';
$lang['woocommerce_open_perfex_client']         = 'Open Perfex client';
$lang['woocommerce_role']                       = 'Role';
$lang['woocommerce_email']                      = 'Email';
$lang['woocommerce_phone']                      = 'Phone';
$lang['woocommerce_username']                   = 'Username';
$lang['woocommerce_search_customers_placeholder'] = 'Search customers (name, email, phone)';
$lang['woocommerce_customers_subtitle']         = 'Browse WooCommerce customers and their Perfex links.';
$lang['woocommerce_customers_empty_title']      = 'No customers yet';
$lang['woocommerce_customers_empty_body']       = 'Customers appear here once a sync tick brings them in.';

// Orders list
$lang['woocommerce_orders_subtitle']            = 'WooCommerce orders, filterable by status, date and store.';
$lang['woocommerce_orders_empty_title']         = 'No orders yet';
$lang['woocommerce_orders_empty_body']          = 'Orders show up here as soon as a sync tick imports them.';
$lang['woocommerce_orders_empty_filtered_body'] = 'No orders match the current filters.';
$lang['woocommerce_search_orders_placeholder']  = 'Search orders (number, customer, email)';

// Products list
$lang['woocommerce_products_subtitle']          = 'WooCommerce products with stock indicators and Perfex linkage.';
$lang['woocommerce_products_empty_title']       = 'No products yet';
$lang['woocommerce_products_empty_body']        = 'Sync at least one store to see products here.';
$lang['woocommerce_search_products_placeholder'] = 'Search products (name or SKU)';
$lang['woocommerce_type']                       = 'Type';
$lang['woocommerce_sales']                      = 'Sales';
$lang['woocommerce_linked']                     = 'Linked';
$lang['woocommerce_not_linked']                 = 'Not linked';
$lang['woocommerce_linked_to_item']             = 'Linked to Perfex item';
$lang['woocommerce_name']                       = 'Name';
$lang['woocommerce_item']                       = 'Item';
$lang['woocommerce_product_status_']            = 'Status';
$lang['woocommerce_status_']                    = 'Status';
$lang['woocommerce_stock_']                     = 'Stock status';

// Stores list
$lang['woocommerce_stores_subtitle']            = 'Connected WooCommerce stores syncing into Perfex.';
$lang['woocommerce_stores_empty_title']         = 'No stores connected yet';
$lang['woocommerce_stores_empty_body']          = 'Connect your first WooCommerce store to start syncing.';
$lang['woocommerce_assigned_staff']             = 'Assigned staff';
$lang['woocommerce_assigned_staff_help']        = 'Pick the team members who should see this store in their dashboard. Leave empty to share with everyone.';
$lang['woocommerce_assigned_staff_none']        = 'No staff assigned';
$lang['woocommerce_bulk_selected']              = 'selected';
$lang['woocommerce_bulk_convert']               = 'Convert to invoices';
$lang['woocommerce_bulk_mark_completed']        = 'Mark as completed';
$lang['woocommerce_bulk_clear']                 = 'Clear selection';
$lang['woocommerce_bulk_import_customers']      = 'Import to Perfex';
$lang['woocommerce_last_synced']                = 'Last synced';
$lang['woocommerce_never_synced']               = 'Never synced';
$lang['woocommerce_connect_first_store']        = 'Connect first store';
$lang['woocommerce_store_id']                   = 'Store ID';

// Logs
$lang['woocommerce_logs_subtitle']              = 'Filterable union of woocommerce_log + woocommerce_webhook_log.';
$lang['woocommerce_logs_empty_title']           = 'No logs in this range';
$lang['woocommerce_logs_empty_body']            = 'Try widening the date range or clearing filters.';
$lang['woocommerce_log_context']                = 'Context';
$lang['woocommerce_log_rows']                   = 'Log rows';
$lang['woocommerce_webhook']                    = 'Webhook';
$lang['woocommerce_webhook_log_rows']           = 'Webhook log rows';
$lang['woocommerce_event']                      = 'Event';
$lang['woocommerce_level']                      = 'Level';
$lang['woocommerce_correlation_id']             = 'Correlation ID';
$lang['woocommerce_view_context']               = 'View context';
$lang['woocommerce_when']                       = 'When';
$lang['woocommerce_until']                      = 'Until';
$lang['woocommerce_since']                      = 'Since';
$lang['woocommerce_received']                   = 'Received';
$lang['woocommerce_woo_id']                     = 'Woo ID';
$lang['woocommerce_click_to_copy']              = 'Click to copy';
$lang['woocommerce_copied']                     = 'Copied';

// Diagnostic
$lang['woocommerce_diagnostic_subtitle']        = 'Single-screen support snapshot.';
$lang['woocommerce_diag_explainer']             = 'Paste this into your support ticket so we can reproduce your environment quickly.';
$lang['woocommerce_what_support_will_see']      = 'What support will see';
$lang['woocommerce_environment']                = 'Environment';
$lang['woocommerce_module_version']             = 'Module version';
$lang['woocommerce_app_enc_key']                = 'APP_ENC_KEY';
$lang['woocommerce_data_volumes']               = 'Data volumes';
$lang['woocommerce_jobs_pending']               = 'Jobs pending';
$lang['woocommerce_jobs_quarantined']           = 'Jobs quarantined';
$lang['woocommerce_last_cron_tick']             = 'Last cron tick';
$lang['woocommerce_payment_mode_marker']        = 'Payment-mode marker';
$lang['woocommerce_payment_mode_marker_explainer'] = 'Auto-tag for invoices/payments imported from WooCommerce. Should stay disabled.';
$lang['woocommerce_generated_at']               = 'Generated at';
$lang['woocommerce_copy_as_text']               = 'Copy as text';
$lang['woocommerce_copy_manually']              = 'Copy manually';

// Sync from english/woocommerce_lang.php — keys added after the T7.2 seed.
$lang['woocommerce_convert_success'] = 'Order converted to invoice #%s.';
$lang['woocommerce_import_failed']   = 'Import failed: %s';
$lang['woocommerce_import_success']  = 'Customer imported into Perfex.';
$lang['woocommerce_telemetry_explainer'] = 'Helps us measure time-to-first-sync. We collect only: module / Perfex / PHP versions, store count, last cron tick, and a random install id. No customer data, no store URLs, no secrets. Off by default.';
$lang['woocommerce_telemetry_label']     = 'Send anonymous setup telemetry to Boxvibe (optional)';

// Sync from english/ — keys added after the previous T7.2 batch.
$lang['woocommerce_add_as_item']        = 'Add as Sales Item';
$lang['woocommerce_convert_confirm']    = 'Convert now';
$lang['woocommerce_convert_modal_help'] = 'A Perfex invoice will be created from this WooCommerce order. Review the projected outcome below — confirm to write to the database.';
$lang['woocommerce_convert_show_raw']   = 'Show raw preview JSON';
$lang['woocommerce_invoice_from_woo_label']   = 'WooCommerce %s';
$lang['woocommerce_invoice_from_woo_tooltip'] = 'This invoice was converted from a WooCommerce order. Click to open the source order.';
$lang['woocommerce_link_to_perfex_item'] = 'Create a Perfex sales item from this WooCommerce product';
$lang['woocommerce_loading_preview']    = 'Building preview…';
$lang['woocommerce_mapping_revert']             = 'Revert';
$lang['woocommerce_order_already_converted']      = 'Already converted to invoice %s';
$lang['woocommerce_order_already_converted_body'] = 'Use the action drawer on the right to open the linked invoice.';
$lang['woocommerce_order_deleted']     = 'Order deleted on WooCommerce.';
$lang['woocommerce_preview_unavailable'] = 'Preview unavailable. The convert action will still proceed if you confirm.';
$lang['woocommerce_status_cancelled']           = 'Cancelled';
$lang['woocommerce_status_completed']           = 'Completed';
$lang['woocommerce_status_failed']              = 'Failed';
$lang['woocommerce_status_on-hold']             = 'On hold';
$lang['woocommerce_status_pending']             = 'Pending payment';
$lang['woocommerce_status_processing']          = 'Processing';
$lang['woocommerce_status_refunded']            = 'Refunded';
$lang['woocommerce_status_updated_to'] = 'Order status updated to %s.';
