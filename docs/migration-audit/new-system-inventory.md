# Target System Inventory - NOVIXA CRM (Next.js 16 + FastAPI + PostgreSQL)

## System Overview
- **Frontend Stack**: Next.js 16.2 (App Router), React 19, Tailwind CSS v4, Lucide Icons, React Query (@tanstack/react-query), Axios API Client.
- **Backend Stack**: Python 3.13, FastAPI 0.115, SQLAlchemy 2.0 (Async Session), Alembic Migrations, Pydantic v2 Schemas, Celery Workers, Redis.
- **Database Engine**: PostgreSQL 16 (Relational Async Connection Pool).

---

## 1. Frontend App Routes & Components (`frontend/src/app/(dashboard)/`)
- `app/(dashboard)/page.tsx` — Dashboard Analytics & Quick Actions
- `app/(dashboard)/clients/page.tsx` — Client Management & Contact Cards
- `app/(dashboard)/leads/page.tsx` — Lead Kanban & Table Views, Conversion Modal
- `app/(dashboard)/invoices/page.tsx` — Invoicing, Billing & Payment Modal
- `app/(dashboard)/estimates/page.tsx` — Proposal & Estimate Management
- `app/(dashboard)/accounting/page.tsx` — Financial Ledger & Balance Sheet
- `app/(dashboard)/warehouse/page.tsx` — Stock Items & Warehouse Locations
- `app/(dashboard)/purchase/page.tsx` — Purchase Orders & Vendor Tracking
- `app/(dashboard)/woocommerce/page.tsx` — E-Commerce Store Sync & Activity Logs
- `app/(dashboard)/hr/page.tsx` — HRM Staff Directory & Department Statistics
- `app/(dashboard)/recruitment/page.tsx` — Job Openings & Candidate Scoring
- `app/(dashboard)/okrs/page.tsx` — Objective Goals & Key Results Breakdown
- `app/(dashboard)/tasks/page.tsx` — Task Board, Priority Badges & Status Toggles
- `app/(dashboard)/tickets/page.tsx` — Support Ticket Help Desk
- `app/(dashboard)/staff-outsourcing/page.tsx` — Resource Booking & Contractor Allocation
- `app/(dashboard)/account-planning/page.tsx` — Strategic Account Tier & SWOT Matrix
- `app/(dashboard)/settings/page.tsx` — Multi-Theme (6 Colors) & Localization Switcher (TH/EN)

---

## 2. Backend FastAPI Routers (`backend/app/api/v1/`)
- `auth.py` — JWT Login, Refresh, Password Hashing
- `clients.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Clients & Contacts
- `leads.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE`, `POST /convert` for Leads
- `invoices.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE`, `POST /payments` for Invoices
- `estimates.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Estimates
- `accounting.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Accounts & Entries
- `warehouse.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Stock Items
- `purchase.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Purchase Orders
- `woocommerce.py` — Full CRUD `GET`, `POST /sync`, `PUT`, `DELETE` for Store Sync
- `hr.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Staff Employees
- `recruitment.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Recruitment Campaigns
- `okrs.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Objectives & Key Results
- `tasks.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Tasks
- `tickets.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Tickets
- `staff_outsourcing.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Contractors
- `account_planning.py` — Full CRUD `GET`, `POST`, `PUT`, `DELETE` for Account Plans
- `settings.py` — Configuration Storage & Retrieval

---

## 3. PostgreSQL Database Tables (`database-migration/`)
- `tblclients`, `tblcontacts`
- `tblleads`, `tblleads_status`, `tblleads_sources`
- `tblinvoices`, `tblinvoicepaymentrecords`, `tblitemable`
- `tblestimates`, `tblestimate_items`
- `tbltasks`, `tbltask_assigned`
- `tbltickets`, `tblticket_replies`
- `tblacc_accounts`, `tblacc_journal_entries`
- `tblstaff`, `tbldepartments`
- `tblpur_orders`, `tblpur_vendors`
- `tblwh_items`, `tblwh_warehouses`
