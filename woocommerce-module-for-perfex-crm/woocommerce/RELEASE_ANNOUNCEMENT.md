# WooCommerce Module v3.0 for Perfex CRM

Excerpt: A rewrite with multi-store support, signed real-time webhooks, a new admin, and a first-run setup wizard.

Date: 2026-04-29
Version: 3.0.0

## TL;DR

- Connect multiple WooCommerce stores to one Perfex CRM (new).
- New admin, with a 4-step setup wizard, mobile-friendly screens, and per-store quick actions.
- Signed, idempotent webhooks with replay protection. Duplicate orders and out-of-order updates no longer slip through.
- Field Mappings with one-click presets for Customers, Products, and Orders.
- Order to Invoice conversion with a confirm preview, plus a "WooCommerce" payment mode so paid orders don't get re-billed in Perfex.
- Diagnostic page so support tickets become "paste this screenshot".
- Six languages: English, Danish, French, German, Italian, Spanish.
- Anonymous, opt-in telemetry. Off by default.

## Why this release matters

v3 is a rewrite. The module now supports more than one store, the UI is built for the current Perfex theme, webhooks are signed and de-duplicated, and the setup wizard gets you to a first sync in a single sitting. v2 settings carry forward: field mappings, cron schedule, and store credentials all keep working after upgrade.

## Highlights

1. Multi-store (new)
	- One Perfex install can sync any number of WooCommerce stores.
	- Switch active store from the page header on every list screen.
	- Each store keeps its own credentials, mappings, webhooks, and assigned staff.

2. New admin
	- Eleven redesigned screens: Stores, Mappings, Orders, Order detail, Products, Product editor, Customers, Customer detail, Webhooks, Logs, Diagnostics.
	- 4-step setup wizard on first run; skip-aware so you can come back later.
	- Tokenised CSS, accessible markup, works at 375 px.

3. Signed webhooks with replay protection (improved)
	- HMAC-SHA256 signature verification on every payload.
	- 60-second replay window plus per-event idempotency so retries can't double-apply.
	- Per-topic toggles for orders, products, and customers; one-click generate and validate.

4. Field mappings with presets (improved)
	- Three tabs (Customer, Product, Order), each with typeahead from the live Perfex schema.
	- One-click presets for the standard fields. Tweak per row, override, or revert.
	- Pre-flight check warns before you save a mapping that would break on import.

5. Order to invoice conversion (new)
	- Convert from the order detail page; confirm modal previews the line items first.
	- Bulk convert and bulk mark-completed on the orders list.
	- "WooCommerce" payment mode marks orders already paid in Woo so Perfex doesn't request payment again.

6. Customers (improved)
	- Recent-orders timeline on every customer detail page.
	- One-click Import to Perfex (creates a guest client). Bulk import too.
	- Guest pill so support can tell at a glance which contacts came from a checkout vs. a registered account.

7. Products (improved)
	- Add as Sales Item to link a Woo product to a Perfex item.
	- Inline price and SKU validation in the editor; image preview from URL paste.
	- Filters: status, type, linked-only, low-stock.

8. Diagnostic page (new)
	- One screen with module / Perfex / PHP versions, row counts, signature health, last cron tick, and mode marker.
	- Copy-as-text button, so support tickets become "paste this".
	- Secrets are masked so the page is safe to share.

9. Reliability (improved)
	- Per-store cron locks prevent overlapping ticks.
	- Webhook log and general log unified into one searchable Logs page with correlation IDs.
	- Retention prunes old log rows automatically.

10. Anonymous telemetry (new, opt-in)
	- Six fields only: module / Perfex / PHP versions, store count, last cron tick, and a random install id.
	- No customer data, no store URLs, no secrets, no staff names.
	- Off by default. Turn on from the welcome step of the setup wizard.

## Compatibility

- Perfex CRM 3.4.1 or later (tested on 3.4.1, 3.5, 3.6).
- WooCommerce 5.x or later.
- PHP 8.0 or later.

## Migration from v2.x

- Activate v3 in place. The included migrations seed any missing fields, add the WooCommerce payment mode, and back-fill webhook secrets where missing.
- All v2 settings carry forward: field mappings, cron schedule, store credentials.
- Allow an extra few seconds on first activation while migrations run. The activity log shows progress.

## Quick start (fresh install, about 5 minutes)

1. Activate the module. The setup wizard opens automatically.
2. Step 1 (Welcome): optionally tick "Send anonymous telemetry".
3. Step 2 (Connect your store): paste the WooCommerce URL, consumer key, and consumer secret. Click Test Connection, then Save.
4. Step 3 (Mappings): click "Load Presets" to seed the defaults for Customers, Products, and Orders.
5. Step 4 (Webhooks): tick the topics you want, click Generate, then paste the URL into WooCommerce » Settings » Advanced » Webhooks.
6. Done. Open the Orders list and refresh to see your first sync.

## Upgrade guide (from v2.x)

1. Back up your database.
2. Update the module folder.
3. Reactivate. The wizard skips steps that already have data.
4. Open Diagnostics and confirm signature health is green.
5. Run a small manual sync (10 products, 5 orders) and review Logs.

## FAQs

Q: Will my v2 mappings carry over?
A: Yes. v3 reads the same mapping table; presets are additive, not destructive.

Q: Webhooks vs cron, which should I use?
A: Both. Webhooks deliver updates in near real time; cron is a safety net for anything missed during downtime.

Q: Do I have to opt in to telemetry?
A: No. It is off by default. You can toggle it from the wizard or by clearing `woocommerce_telemetry_opt_in` in `tbloptions`.

Q: Can I sync from multiple stores at once?
A: Yes. v3 supports any number of stores, each with independent credentials, mappings, and assignees.

Q: What if a webhook arrives twice?
A: The dedup window discards repeats by signature and delivery id. Cron also re-checks idempotency keys before persisting.

## Where to learn more

- Module documentation: bundled PDF (`Documentation - woocommerce perfex crm module.pdf`).
- API hooks: `modules/woocommerce/docs/hooks.md`.
- Diagnostic and telemetry details: in-product Diagnostic page.
- Security audit summary: `modules/woocommerce/docs/security_audit.md`.

## Support

Open a ticket with:

- Perfex CRM version.
- WooCommerce version.
- Module version (the Diagnostic page shows it).
- The Diagnostic page "Copy as text" output.
- Relevant log lines (Logs page, click row, copy correlation id).

## Thank you

v3 was shaped by the support tickets, comments and feedback from store owners who told us what was slow, broken, or missing in v2. Keep it coming.
