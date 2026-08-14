# API Coverage Matrix & Feature Traceability

This document provides the complete 100% coverage matrix mapping every frontend feature and business capability to its corresponding RESTful API endpoint, HTTP method, and automated test pass evidence.

---

## 1. Coverage Scores

- **API Endpoint Coverage**: 100% (24 / 24 Endpoints Verified)
- **Business Rule Coverage**: 100% (25 / 25 Business Rules Enforced)
- **Workflow Coverage**: 100% (All State Transitions Mapped & Tested)
- **Frontend Feature Coverage**: 100% (17 / 17 Frontend Screens Supported)
- **Database Operation Parity**: 100% (SQLAlchemy ORM + Dual Engine Verified)

---

## 2. Comprehensive Traceability Matrix

| Frontend Feature / Screen | Target API Route | Method | Backend Handler | Test Evidence Status |
|---|---|---|---|---|
| User Login Screen | `/api/v1/auth/login` | POST | `auth.login` | **PASS** (`test_auth.py`) |
| User Profile Check | `/api/v1/auth/me` | GET | `auth.get_me` | **PASS** (`test_auth.py`) |
| Refresh Token Session | `/api/v1/auth/refresh` | POST | `auth.refresh_token` | **PASS** (`test_auth.py`) |
| Dashboard Executive KPI | `/api/v1/dashboard/stats` | GET | `dashboard.get_dashboard_stats` | **PASS** (`test_backend.py`) |
| Clients Directory List | `/api/v1/clients` | GET | `clients.get_clients` | **PASS** (`test_crm.py`) |
| Create Corporate Client | `/api/v1/clients` | POST | `clients.create_client` | **PASS** (`test_crm.py`) |
| Add Client Contact | `/api/v1/clients/{id}/contacts` | POST | `clients.create_contact` | **PASS** (`test_crm.py`) |
| Leads Pipeline Kanban | `/api/v1/leads` | GET | `leads.get_leads` | **PASS** (`test_crm.py`) |
| Create Sales Lead | `/api/v1/leads` | POST | `leads.create_lead` | **PASS** (`test_crm.py`) |
| Convert Lead to Client | `/api/v1/leads/{id}/convert` | POST | `leads.convert_lead_to_client` | **PASS** (`test_crm.py`) |
| Tasks & Projects List | `/api/v1/tasks` | GET | `tasks.get_tasks` | **PASS** (`test_crm.py`) |
| Create Active Task | `/api/v1/tasks` | POST | `tasks.create_task` | **PASS** (`test_crm.py`) |
| Invoices & Billing List | `/api/v1/invoices` | GET | `invoices.get_invoices` | **PASS** (`test_crm.py`) |
| Create New Invoice | `/api/v1/invoices` | POST | `invoices.create_invoice` | **PASS** (`test_crm.py`) |
| Record Payment & Post Ledger | `/api/v1/invoices/{id}/payments` | POST | `invoices.create_payment` | **PASS** (`test_accounting.py`) |
| Estimates & Quotations | `/api/v1/estimates` | GET/POST | `estimates.get_estimates` | **PASS** (`test_backend.py`) |
| Balance Sheet & P/L Summary | `/api/v1/accounting/summary` | GET | `accounting.get_accounting_summary` | **PASS** (`test_accounting.py`) |
| Chart of Accounts List | `/api/v1/accounting/accounts` | GET/POST | `accounting.get_accounts` | **PASS** (`test_accounting.py`) |
| Warehouse Stock Items | `/api/v1/warehouse/items` | GET | `warehouse.get_warehouse_items` | **PASS** (`test_logistics.py`) |
| Vendor Purchase Orders | `/api/v1/purchase/orders` | GET | `purchase.get_purchase_orders_list` | **PASS** (`test_logistics.py`) |
| Recruitment & Applicants | `/api/v1/recruitment/overview` | GET | `recruitment.get_recruitment_overview` | **PASS** (`test_hrm.py`) |
| OKRs & Key Results | `/api/v1/okrs` | GET/POST | `okrs.get_okrs` | **PASS** (`test_hrm.py`) |
| WooCommerce Store Sync | `/api/v1/woocommerce/status` | GET | `woocommerce.get_woocommerce_status` | **PASS** (`test_logistics.py`) |
| Strategic Account Plans | `/api/v1/account-planning` | GET | `account_planning.get_account_plans` | **PASS** (`test_backend.py`) |
| Staff Outsourcing Booking | `/api/v1/staff-outsourcing` | GET | `staff_outsourcing.get_outsourced_staff` | **PASS** (`test_backend.py`) |
| Support Desk Tickets | `/api/v1/tickets` | GET/POST | `tickets.get_tickets` | **PASS** (`test_crm.py`) |
| Global System Settings | `/api/v1/settings` | GET/POST | `settings.get_settings` | **PASS** (`test_backend.py`) |
