# Legacy System Inventory - Perfex CRM (CodeIgniter 3 Engine)

## System Overview
- **Framework**: CodeIgniter 3.x
- **Language**: PHP 7.4 / 8.1
- **Database Engine**: MySQL / MariaDB (InnoDB, utf8mb4)
- **Primary Modules**: CRM Core, Accounting, HRM, Purchase Management, Warehouse / Inventory, WooCommerce Sync, Recruitment, OKRs, Staff Outsourcing, Account Planning.

---

## 1. Core Controllers (PHP CodeIgniter)
- `application/controllers/admin/Clients.php` — Customer & Contact Management
- `application/controllers/admin/Leads.php` — Lead Pipeline, Kanban, Conversion
- `application/controllers/admin/Invoices.php` — Invoice Billing & Recurring Invoices
- `application/controllers/admin/Estimates.php` — Estimates & Proposals
- `application/controllers/admin/Tasks.php` — Task Management & Timesheets
- `application/controllers/admin/Tickets.php` — Support Ticket Desk
- `application/controllers/admin/Projects.php` — Project & Milestone Tracking
- `application/controllers/admin/Settings.php` — Global System Configuration
- `accounting/controllers/Accounting.php` — Chart of Accounts & Financial Ledger
- `human-resources-management/controllers/Hrm.php` — Employee Management & Payroll
- `purchase/controllers/Purchase.php` — Purchase Orders & Supplier Quotes
- `inventory-management/controllers/Warehouse.php` — Stock Items & Warehouses
- `woocommerce/controllers/Woocommerce.php` — E-Commerce Order & Product Sync
- `recruitment/controllers/Recruitment.php` — Hiring Campaigns & Candidates
- `okrs/controllers/Okrs.php` — Objectives & Key Results
- `staff-outsourcing/controllers/Staff_outsourcing.php` — Resource Booking
- `account-planning/controllers/Account_planning.php` — Strategic Account SWOT

---

## 2. Core Models
- `Clients_model.php` — Client CRUD & Contact Handling
- `Leads_model.php` — Lead Pipelines, Kanban Movement, Client Conversion
- `Invoices_model.php` — Invoicing, Item Calculations, Payment Processing
- `Estimates_model.php` — Estimate Generation & Conversion
- `Tasks_model.php` — Task Assignment & Status Updates
- `Tickets_model.php` — Support Ticketing Routing & Priority
- `Accounting_model.php` — Journal Posting & Account Balances
- `Hrm_model.php` — Staff Records & Department Allocation
- `Purchase_model.php` — Purchase Order Workflows
- `Warehouse_model.php` — Stock Adjustment & Movement Logs

---

## 3. Database Schema Tables
- `tblclients`, `tblcontacts` — Customers & Sub-contacts
- `tblleads`, `tblleads_status`, `tblleads_sources` — Lead Pipeline
- `tblinvoices`, `tblinvoicepaymentrecords`, `tblitemable` — Billing & Line Items
- `tblestimates`, `tblestimate_items` — Proposals
- `tbltasks`, `tbltask_assigned` — Tasks & Projects
- `tbltickets`, `tblticket_replies` — Support System
- `tblacc_accounts`, `tblacc_journal_entries` — Accounting Ledger
- `tblstaff`, `tbldepartments` — Human Resources
- `tblpur_orders`, `tblpur_vendors` — Purchasing
- `tblwh_items`, `tblwh_warehouses` — Warehouse Items

---

## 4. UI / View Architecture
- PHP Views with Bootstrap 3/4 layout templates.
- jQuery, DataTables, AdminLTE elements, FontAwesome, Ajax form submissions.
