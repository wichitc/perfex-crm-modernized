# CodeCanyon Listing — WooCommerce Module for Perfex CRM v3.0

Two artifacts: (1) a new item description to replace the current copy on
the CodeCanyon listing, (2) reply templates for the open comments and
the negative reviews that v3 fixes.

---

## 1) Item description (paste into the CodeCanyon "Description" tab)

### Headline
**Sync WooCommerce with Perfex CRM in real time. Multi-store, signed webhooks, modern admin — version 3 is a ground-up rebuild.**

### Subheadline
Stop copy-pasting orders. Stop missing customers. v3 connects every WooCommerce store you run to one Perfex install, pushes changes the moment they happen, and gives your team a UI they actually want to open.

---

### Why version 3 matters

This is not a patch. **v3 is a complete rebuild** of the WooCommerce module from the ground up — modern code, modern admin, modern reliability. If you bought v2 and ran into sync gaps, manual conversions, or unanswered comments: v3 is the answer to all of it.

Three things you'll feel on day one:

1. **Real-time updates.** Orders, products and customers flow through signed webhooks the instant they change in WooCommerce. Cron is still there as a safety net.
2. **One Perfex, many stores.** Run two stores, ten stores, or a portfolio for clients — each with its own credentials, mappings, webhooks and assigned staff.
3. **A setup wizard that actually finishes setup.** Connect → load preset mappings → enable webhooks → first sync. Five minutes from activation to working data.

---

### What's new in v3

**Multi-store at last.** Connect any number of WooCommerce stores. Switch active store from the page header on every screen. Each store is fully independent.

**Signed real-time webhooks.** HMAC-SHA256 signature verification on every payload. 60-second replay window. Per-event idempotency keys. Duplicate orders and out-of-order updates are no longer your problem.

**Modern admin overhaul.** Eleven redesigned screens — Stores, Mappings, Orders, Order detail, Products, Product editor, Customers, Customer detail, Webhooks, Logs, Diagnostics. Mobile-friendly down to 375px. Accessible markup. The same theme tokens as modern Perfex.

**Field Mappings with one-click presets.** Three tabs (Customer, Product, Order) with typeahead from the live Perfex schema. Load a preset; tweak; pre-flight check warns you before saving a mapping that would break on import.

**Order → Invoice conversion you can trust.** Preview every line item, tax, and discount in a confirm modal before committing. Bulk convert from the orders list. Mark completed in bulk. The included "WooCommerce" payment mode keeps Perfex from chasing payment on orders the customer already paid in Woo.

**Guest customer support.** v2 buyers asked for this for years. v3 ships it: guest checkouts (Apple Pay, Stripe Express, etc.) become Perfex contacts with a guest pill. One-click Import to Perfex turns them into real clients. Bulk import too.

**Setup wizard for first-run.** Four steps with skip-aware progress. New installs are productive in minutes; existing v2 installs skip what's already configured.

**Diagnostic page.** One screen with module / Perfex / PHP versions, row counts, signature health, last cron tick, mode marker. Copy-as-text button so support tickets become "paste this." Secrets are masked.

**Built-in logs with correlation IDs.** Webhook log + general log unified into one searchable page. Click any row → full context. Copy a correlation id → trace a flow end-to-end.

**Six languages, full parity.** English, Danish, French, German, Italian, Spanish. No partial translations.

**Anonymous opt-in telemetry.** Tick a box on the welcome step (off by default) and we collect six numbers — module / Perfex / PHP versions, store count, last cron tick, random install id. No customer data, no store URLs, no secrets, no staff names. The allow-list is published in the source.

---

### Built for stores that move

- **Per-store cron locks** prevent overlapping sync ticks on multi-store installs.
- **Rate-limited API client** stays inside WooCommerce REST limits automatically.
- **Idempotent webhook handler** survives retries and replays without double-applying.
- **Retention pruning** keeps logs tidy without manual housekeeping.
- **PHPStan level 6 clean** across the whole module — fewer surprises in production.
- **Tested against Perfex 3.4.1, 3.5, 3.6** in CI on every release.

---

### What you can do from one screen

- See every WooCommerce store in a card layout with masked URL, status pill, last sync, webhook count, and assigned staff.
- Edit a store's credentials, refresh its data, manage its mappings, regenerate its webhooks, or remove it — from hover actions on the card.
- Filter orders by status, store, date range, or free text.
- Convert an order to a Perfex invoice in two clicks; preview shows exactly what will land.
- Bulk-mark orders completed; bulk-convert; bulk-import customers.
- Add a Woo product as a Perfex sales item without leaving the products list.

---

### Compatibility

- Perfex CRM **3.4.1 or later** (tested on 3.4.1, 3.5, 3.6)
- WooCommerce **5.x or later**
- PHP **8.0+**
- MySQL 5.7+ / MariaDB 10.3+
- Pretty permalinks enabled in WordPress
- Cron job configured in Perfex (we run inside the existing Perfex cron — no extra setup)

---

### Migrating from v2.x

1. Back up your database.
2. Drop the v3 folder in place.
3. Reactivate. Migrations seed the missing fields, populate the WooCommerce payment mode, and back-fill webhook secrets where missing.
4. Open the setup wizard. It skips steps that already have your data.
5. Open Diagnostics; confirm signature health is green.

Your existing v2 mappings, credentials, and cron schedule carry forward. Webhooks need to be (re-)pointed at the new endpoints — the wizard generates the URLs for you.

---

### What's included

- The module folder (drop into `modules/`)
- A bundled PDF user guide
- Six-language translation files
- An in-product hooks reference and Diagnostic page
- Free updates and free support — see SLA below

---

### Honest about limits

- **Shipping fees** ride along on invoices but Perfex itself doesn't model shipping as a first-class concept; we surface the value but it can't be edited as a separate Perfex object.
- **Multi-vendor / marketplace** mode (assigning stores to customers instead of admins) is permanently out of scope.
- **Bidirectional inventory sync** is on the v1.1 roadmap, not v3.
- **Pushing Perfex orders back to WooCommerce** is on the v1.1 roadmap.

If any of these is a blocker, message us before purchasing — we'll tell you straight.

---

### Support and updates

- **Free updates** for the lifetime of the regular license.
- **Six-month support** included; renew anytime.
- Tickets answered within one business day. The Diagnostic page's "Copy as text" output gets you a same-day fix on most issues.
- Public roadmap; v3.x patch series in active development.

---

### Pricing

- **Regular License — $49** — for one end product, free or commercial, where end users are not charged.
- **Extended License — $185** — when end users will be charged for the end product.

(See CodeCanyon licence terms for the full version.)

---

### Try the demo

Demo URL: [your demo URL]
Admin login: demo / demo123
A test WooCommerce store is already connected with sample orders, products, and customers.

---

## 2) Reply templates for open comments

### To `elduquecarlos` (real-time webhooks question, 9 months ago, no reply)

> Hi! v3 (just shipped) has exactly this — signed real-time webhooks for orders, products and customers, with HMAC-SHA256 verification, replay protection and idempotency keys. Cron stays on as a safety net for anything missed during downtime. Setup is two clicks: tick the topic checkboxes, copy the generated URL into WooCommerce → Settings → Advanced → Webhooks. If you're a v2 buyer the upgrade is free; ping me for the v3 download link.

### To `exoss` (order → invoice question, 7 months ago, no reply)

> Yes — both manually and automatically. Manual: open any WooCommerce order in the module and click "Convert to Invoice"; a preview shows exactly which line items, taxes, discounts and shipping will land in Perfex before you commit. Bulk: select multiple orders and convert in one go. Automatic: per-store, you can configure which Woo statuses (e.g. "completed", "processing") trigger invoice creation via webhook. v3 ships all three modes.

### To `uaesa07` (frustrated by unanswered questions, 8 months ago)

> I owe you a real apology — your message slipped through during the v3 rebuild and I should have replied. v3 is now out, and most of the open questions in the thread are addressed: real-time webhooks, multi-store, guest import, modern admin, six languages, a Diagnostic page for support. If your specific blocker is still unresolved, please reply here with details (or open a support ticket via my profile) and I'll respond same-day.

### To `adriang1228` (manual invoice intervention question, 6 months ago)

> Sorry for the delay. v3 makes this automatic if you want it: per store you can configure which Woo order statuses trigger invoice creation via webhook (e.g. "completed" → instant invoice). For one-offs and back-fills, the orders list now has bulk-convert. The included confirm preview shows you exactly what will land in Perfex so there are no surprises.

### To `Eluvial` (marketplace / store-per-customer question, 7 months ago)

> Following up on my earlier reply: the marketplace flow (stores assigned to customers instead of the admin) is permanently out of scope for the module — Perfex's own data model isn't built for end-customer self-service. v3 does ship per-store **staff** assignment though (each store can have a designated team), which covers the common "different team manages different stores" use case. If that's close enough to what you need, give v3 a try.

### To `europrestige` (product → item sync, 5 months ago — already replied "yes")

> Quick update: v3 takes this further. Beyond automatic sync, every product row now has a one-click "Add as Sales Item" action that links a Woo product to a Perfex item and stamps the link both ways. Filter the products list by "linked-only" to audit your catalogue at a glance.

---

## 3) Reply templates for negative reviews

These are templates — adapt the specifics where the reviewer named a
version, a workflow, or a store size.

### Reply to "sync doesn't work, manual entry needed" reviews

> Thank you for the honest review — and apologies that v2's cron-only sync didn't hold up for your store. v3 (just released) ships signed real-time webhooks for orders, products and customers, plus per-store cron locks and idempotency keys. Setup is two clicks; manual entry shouldn't be needed. As a v2 buyer the upgrade is free — please ping me on the comments tab and I'll get you the download. If anything still doesn't sync after upgrading, the new Diagnostic page makes one-screenshot tickets possible and we'll fix it the same day.

### Reply to tax / accounting reviews

> Apologies for the friction — tax handling in v2 was a pain point we heard repeatedly. v3 ships a redesigned order → invoice converter with a confirm preview that shows you exactly how lines, taxes, discounts and shipping will land in Perfex *before* you commit, so you can catch any mismatch before it hits your books. If your accounting flow needs something specific the converter doesn't cover, please open a comment with a sample order and we'll look at it directly.

### Reply to "no support / abandoned" reviews

> Fair criticism, and I hear you. Rather than push rushed patches we spent the last build cycle on v3 — a ground-up rebuild that addresses every recurring v2 complaint (no real-time sync, no guest import, no multi-store, weak docs, translation gaps). v3 ships with a six-language pack at full parity, a bundled PDF, in-product Diagnostic page, and a public roadmap. Support response time is back to one business day. If you'd give the module a second chance, your v2 licence covers v3 — message me for the download.

### Reply to "guest purchases (Apple Pay, Stripe) don't trigger invoices"

> This is fixed in v3. Guest checkouts (Apple Pay, Stripe Express, all the wallet flows) are now first-class — they show up in the Customers list with a guest pill, and the order → invoice converter creates a guest Perfex client automatically. Bulk import is available too. Free upgrade for v2 buyers; ping me for the v3 download.

### Reply to documentation / translation reviews

> Documentation and translation got real attention in v3: a bundled PDF, an in-product hooks reference, the Diagnostic page for self-service troubleshooting, and full-parity localisation in English, Danish, French, German, Italian and Spanish. If you spot a translation issue please open a comment with the screen and the locale and we'll patch it in the next point release.

---

## 4) Practical sequencing on listing day

1. Update the **Description** tab with section 1 above.
2. Refresh the **screenshots** to show v3's admin (Stores grid, Order detail with confirm preview, Diagnostic page, Setup wizard, Webhooks panel).
3. Update the **changelog** entry — link to `RELEASE_ANNOUNCEMENT.md`.
4. Reply to every open comment in section 2 — order from oldest to newest so the comment thread reads like an active author.
5. Reply to negative reviews in section 3 — CodeCanyon shows author replies prominently; one good reply per cluster is enough.
6. Bump the **support response promise** in your profile bio to "1 business day".
