# Functional Requirements

## Purpose
Defines the functional specifications of each primary module within the application.

## Scope
Detailed listing of features across Core CRM, Accounting, HRM, Recruitment, Warehouse, Purchase, OKRs, WooCommerce, REST API, and Staff Outsourcing.

## Detailed Explanation

### 1. Customers & Contacts Management
- Create corporate clients with multiple individual contacts.
- Assign client portal permissions (allow contact to view invoices, write tickets, view proposals).

### 2. Lead Management
- Import leads via CSV or capture through Web-to-Lead forms.
- Dynamic drag-and-drop lead kanban boards.

### 3. Invoices & Billing
- Automated PDF invoice creation via TCPDF.
- Payment gateway integration (PayPal, Stripe, Mollie, Authorize.net).
- Send invoice emails with payment links.

### 4. Accounting Module
- **Chart of Accounts**: Tree structure mapping of Assets, Liabilities, Equity, Income, and Expenses.
- **Double Entry Journal**: Automatically creates debit/credit records upon core invoicing, payments, and expenses actions.
- **Bank Reconciliation**: Reconcile bank statements with system accounts.
- **Financial Reports**: Profit and Loss statement, Balance Sheet, Trial Balance.

### 5. HRM Module
- Manage staff records, contracts, work shifts, and insurance.
- Record daily attendance and process leave applications.

### 6. Recruitment Module
- Create job postings and embed web recruitment application forms.
- Manage applicant pipelines, interviews, evaluation sheets, and convert to staff.

### 7. Warehouse Module
- Stock logs, goods receipts (inward inventory), goods delivery (outward inventory).
- Loss and adjustment recording, packing lists, and inventory warnings.

### 8. Purchase Management Module
- Manage vendors, RFQs, purchase orders, purchase invoices, and vendor payments.

### 9. OKRs Module
- Create Objectives and map key results with progress weights and scores.

### 10. WooCommerce Integration
- Synchronize products, customers, and orders from multiple WooCommerce stores.
- Create CRM invoices automatically when WooCommerce orders reach specific statuses.

## References
- [Business Requirements](04_Business_Requirements.md)
- [UI Analysis](11_UI_Analysis.md)
