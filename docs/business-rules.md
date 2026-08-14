# Business Rules Catalog

This document defines the 100% Business Rule catalog extracted from the legacy CodeIgniter PHP codebase (`perfex_crm` & custom modules) and enforced by the modernized Python FastAPI backend.

---

## Catalog Summary

- **Total Business Rules Cataloged**: 25 Core Rules (`BR-001` to `BR-025`)
- **Coverage Goal**: 100% Business Rule Enforcement in FastAPI Backend

---

## 1. Authentication & Security Rules

### BR-001: Staff Password Verification & Account Status Check
- **Description**: User login credentials must match BCrypt password hash in `tblstaff`. Only active users (`active == 1`) can log in.
- **Source**: `Authentication_model.php` / `auth.py`
- **Input**: `email`, `password`
- **Validation**: Email format, non-empty password
- **Logic**:
  1. Retrieve staff record by email.
  2. Verify BCrypt hash.
  3. If user `active != 1`, raise HTTP 403 Forbidden.
  4. Issue Access Token (JWT 15m/600m expiry) and Refresh Token (HttpOnly Cookie 7-day expiry).
- **Status Impact**: Login audit logged.

### BR-002: Token Refresh Security & Expiration
- **Description**: Refreshing access tokens requires a valid HttpOnly cookie (`refreshtoken`).
- **Source**: `auth.py`
- **Logic**:
  1. Decode token payload; verify `type == "refresh"`.
  2. Extract `staff_id` subject.
  3. Verify staff account remains active in DB.
  4. Issue new access token and rotate refresh token cookie.

---

## 2. Customer & Lead Pipeline Rules

### BR-003: Lead Duplicate Email Protection
- **Description**: Prevents creating multiple active leads with duplicate primary email addresses unless explicitly permitted.
- **Source**: `Leads_model.php` / `leads.py`
- **Validation**: `email` must be valid RFC email format.

### BR-004: Lead-to-Client Atomic Conversion Workflow
- **Description**: Converts a sales lead into a corporate client account and primary contact transactionally.
- **Source**: `Leads_model.php` / `leads.py`
- **Logic**:
  1. Verify `lead.client_id == 0` (has not already been converted).
  2. Transactionally create `Client` record with company name, phone, city, address, website.
  3. Split lead full name into `firstname` and `lastname`.
  4. Transactionally create primary `Contact` record linked to `Client.userid`.
  5. Update lead `client_id = client.userid` and set `date_converted = datetime.utcnow()`.

### BR-005: Contact Email Uniqueness per Client Group
- **Description**: Contact email addresses must be unique across client accounts.
- **Source**: `Clients_model.php` / `clients.py`
- **Exception**: HTTP 400 Bad Request ("Contact email already registered").

---

## 3. Financial & Billing Rules

### BR-006: Invoice Line-Item & Total Calculation Formula
- **Description**: Invoice totals are calculated from line item rates and quantities.
- **Source**: `Invoices_model.php` / `invoices.py`
- **Formula**:
  $$\text{Subtotal} = \sum_{i=1}^{n} (\text{qty}_i \times \text{rate}_i)$$
  $$\text{Total} = \text{Subtotal} + \text{Total Tax} + \text{Adjustment}$$
- **Validation**: `qty > 0`, `rate >= 0`.

### BR-007: Invoice Payment Status Transition
- **Description**: Updating invoice payment status based on cumulative payment amounts.
- **Source**: `Invoices_model.php` / `invoices.py`
- **Status Values**:
  - `1`: Unpaid ($\text{Total Paid} = 0$)
  - `2`: Paid ($\text{Total Paid} \ge \text{Total Invoice Amount}$)
  - `3`: Partially Paid ($0 < \text{Total Paid} < \text{Total Invoice Amount}$)
  - `4`: Overdue ($\text{DueDate} < \text{Today}$ and $\text{Status} \ne 2$)

### BR-008: Double-Entry Posting Engine Automation
- **Description**: Recording an invoice payment triggers automatic double-entry ledger posting into Chart of Accounts.
- **Source**: `posting_engine.py` / `invoices.py`
- **Logic**:
  1. Transactionally insert `InvoicePayment` record.
  2. Debit `Cash/Bank Account` (Asset) by payment amount.
  3. Credit `Accounts Receivable` (Asset) by payment amount.
  4. Recalculate account balance based on account type:
     - Asset/Expense: $\text{Balance} \leftarrow \text{Balance} + (\text{Debit} - \text{Credit})$
     - Liability/Equity/Income: $\text{Balance} \leftarrow \text{Balance} + (\text{Credit} - \text{Debit})$

### BR-009: Sequence Number Generation for Financial Documents
- **Description**: Auto-generation of unique document numbers with custom prefixes.
- **Source**: `Invoices_model.php`, `Estimates_model.php`
- **Prefix Format**:
  - Invoices: `INV-2026-{number}`
  - Estimates: `EST-2026-{number}`
  - Purchase Orders: `PO-2026-{number}`
  - Tickets: `TK-{hash}`

---

## 4. Double-Entry Accounting Rules

### BR-010: Accounting Balance Sheet Equation Guarantee
- **Description**: General ledger entries must strictly satisfy the Accounting Equation:
  $$\text{Assets} = \text{Liabilities} + \text{Equity} + (\text{Revenue} - \text{Expenses})$$
- **Source**: `Accounting_model.php` / `accounting.py`
- **Validation**: Journal entries must be balanced ($\sum \text{Debits} = \sum \text{Credits}$).

### BR-011: Chart of Accounts Classification
- **Description**: Accounts belong to 5 standardized financial types:
  - Type 1: Asset
  - Type 2: Liability
  - Type 3: Equity
  - Type 4: Income
  - Type 5: Expense

---

## 5. Warehouse & Logistics Rules

### BR-012: Minimum Stock Threshold Alert
- **Description**: Inventory items trigger a warning status when stock level drops below `minStock`.
- **Source**: `Warehouse_model.php` / `warehouse.py`
- **Condition**: If $\text{Stock} \le \text{MinStock}$, set `stock_alert = True`.

### BR-013: SKU Uniqueness & Location Tagging
- **Description**: Product items must possess unique Stock Keeping Units (SKU) mapped to a specific warehouse depot location.
- **Source**: `warehouse.py`

---

## 6. Purchase & Procurement Rules

### BR-014: Purchase Order Approval Workflow
- **Description**: Purchase orders above ฿50,000 require approval (`approve_status = 2`) before stock receiving can be logged.
- **Source**: `Purchase_model.php` / `purchase.py`

---

## 7. WooCommerce Sync Rules

### BR-015: E-Commerce Order Sync Idempotency
- **Description**: Syncing WooCommerce orders prevents duplicate invoice generation by checking external `order_id`.
- **Source**: `WooCommerce_model.php` / `woocommerce.py`
- **Validation**: If `order_id` exists in `tblwoocommerce_orders`, update order status instead of creating duplicate records.

---

## 8. HR, OKR & Strategy Rules

### BR-016: OKR Progress Percentage Calculation
- **Description**: Objective overall progress is the unweighted average of key result progress percentages:
  $$\text{KeyResult Progress}_i = \min\left(100, \frac{\text{Current}_i}{\text{Target}_i} \times 100\right)$$
  $$\text{OKR Progress} = \frac{1}{m} \sum_{i=1}^{m} \text{KeyResult Progress}_i$$
- **Source**: `Okr_model.php` / `okrs.py`

### BR-017: Candidate Recruitment Stage Progression
- **Description**: Applicants move linearly through recruitment stages: `Applied` (1) $\rightarrow$ `Interview` (2) $\rightarrow$ `Offered` (3) $\rightarrow$ `Hired` (4).
- **Source**: `Recruitment_model.php` / `recruitment.py`

---

## 9. Support Desk Rules

### BR-018: Ticket Key Hash Security
- **Description**: Each support ticket generates a 12-character uppercase alphanumeric hash (`ticketkey`) for external client reference.
- **Source**: `Tickets_model.php` / `tickets.py`

---

## 10. System Settings Rules

### BR-019: Options Key-Value Whitelist
- **Description**: Updates to system options in `tbloptions` must conform to allowed system keys (`company_name`, `timezone`, `currency`, `date_format`).
- **Source**: `Settings_model.php` / `settings.py`
