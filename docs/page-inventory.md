# Page Inventory - Perfex CRM

## Complete Page & View Inventory Catalog

This document details all 35+ core pages discovered across the legacy Perfex CRM system, mapping their legacy route, permissions, UI components, and RESTful API endpoints.

---

### PAGE-001: Main Dashboard
- **Route**: `/` or `/admin`
- **Menu**: Dashboard
- **Permission**: `DASHBOARD_VIEW`
- **Components**: KPI Summary Cards, Revenue Chart, Active Tasks Widget, Latest Activity Feed
- **REST API**: `GET /api/v1/dashboard/stats`, `GET /api/v1/dashboard/charts`

---

### PAGE-002: Clients Directory
- **Route**: `/clients`
- **Menu**: Customers -> Clients
- **Permission**: `CUSTOMERS_VIEW`
- **Components**: Client Table/Grid, Contact Cards, Add Client Modal, Billing Filter
- **REST API**: `GET /api/v1/clients`, `POST /api/v1/clients`, `PUT /api/v1/clients/{id}`

---

### PAGE-003: Client Detail View
- **Route**: `/clients/:id`
- **Menu**: Customers -> Clients -> Profile
- **Permission**: `CUSTOMERS_VIEW`
- **Components**: Client Profile Tabs (Profile, Contacts, Invoices, Estimates, Contracts, Vault)
- **REST API**: `GET /api/v1/clients/{id}`, `GET /api/v1/clients/{id}/contacts`

---

### PAGE-004: Leads Management
- **Route**: `/leads`
- **Menu**: Leads
- **Permission**: `LEADS_VIEW`
- **Components**: Kanban Board (New, Contacted, Qualified, Proposal Sent, Won), Lead Table, Lead Form
- **REST API**: `GET /api/v1/leads`, `POST /api/v1/leads`, `PUT /api/v1/leads/{id}`

---

### PAGE-005: Lead Conversion Wizard
- **Route**: `/leads/convert/:id`
- **Menu**: Leads -> Convert
- **Permission**: `LEADS_CONVERT`
- **Components**: Lead-to-Customer Form, Contact Mapping, Initial Invoice Creation
- **REST API**: `POST /api/v1/leads/{id}/convert`

---

### PAGE-006: Invoices Directory
- **Route**: `/invoices`
- **Menu**: Sales -> Invoices
- **Permission**: `INVOICES_VIEW`
- **Components**: Invoice Table, Status Filters (Paid, Unpaid, Overdue, Draft), Record Payment Modal
- **REST API**: `GET /api/v1/invoices`, `POST /api/v1/invoices`, `POST /api/v1/invoices/{id}/payments`

---

### PAGE-007: Invoice Detail & PDF View
- **Route**: `/invoices/:id`
- **Menu**: Sales -> Invoices -> View
- **Permission**: `INVOICES_VIEW`
- **Components**: Itemized Invoice Preview, Payment Timeline, PDF Download/Print Modal
- **REST API**: `GET /api/v1/invoices/{id}`, `GET /api/v1/invoices/{id}/pdf`

---

### PAGE-008: Estimates & Quotations
- **Route**: `/estimates`
- **Menu**: Sales -> Estimates
- **Permission**: `ESTIMATES_VIEW`
- **Components**: Estimate Table, Status Pipeline, Convert to Invoice Action
- **REST API**: `GET /api/v1/estimates`, `POST /api/v1/estimates`

---

### PAGE-009: Proposals & Contracts
- **Route**: `/proposals`
- **Menu**: Sales -> Proposals
- **Permission**: `PROPOSALS_VIEW`
- **Components**: Interactive Proposal Builder, Client E-Signature Verification
- **REST API**: `GET /api/v1/proposals`, `POST /api/v1/proposals`

---

### PAGE-010: Accounting & General Ledger
- **Route**: `/accounting`
- **Menu**: Accounting
- **Permission**: `ACCOUNTING_VIEW`
- **Components**: Balance Sheet Cards, Chart of Accounts Table, Journal Entries Form
- **REST API**: `GET /api/v1/accounting/summary`, `GET /api/v1/accounting/accounts`

---

### PAGE-011: Warehouse & Stock Inventory
- **Route**: `/warehouse`
- **Menu**: Warehouse
- **Permission**: `WAREHOUSE_VIEW`
- **Components**: Item Stock Table, Low Stock Alert Badges, Depot Location Selector
- **REST API**: `GET /api/v1/warehouse/items`, `POST /api/v1/warehouse/transfer`

---

### PAGE-012: Purchase Management
- **Route**: `/purchase`
- **Menu**: Purchase
- **Permission**: `PURCHASE_VIEW`
- **Components**: Purchase Order Table, Vendor Directory, Receiving Check Modal
- **REST API**: `GET /api/v1/purchase/orders`, `POST /api/v1/purchase/orders`

---

### PAGE-013: WooCommerce Integration
- **Route**: `/woocommerce`
- **Menu**: WooCommerce
- **Permission**: `WOOCOMMERCE_VIEW`
- **Components**: Store Connector Status, Synced Products & Orders Cards, Manual Sync Button
- **REST API**: `GET /api/v1/woocommerce/status`, `POST /api/v1/woocommerce/sync`

---

### PAGE-014: Recruitment & HR
- **Route**: `/recruitment`
- **Menu**: Recruitment
- **Permission**: `RECRUITMENT_VIEW`
- **Components**: Job Requisition Cards, Candidate Pipeline Table, Candidate Rating Stars
- **REST API**: `GET /api/v1/recruitment/overview`, `POST /api/v1/recruitment/jobs`

---

### PAGE-015: OKRs & Strategic Goals
- **Route**: `/okrs`
- **Menu**: OKRs
- **Permission**: `OKRS_VIEW`
- **Components**: Quarterly Objective Progress Bars, Key Result Target Trackers
- **REST API**: `GET /api/v1/okrs`, `POST /api/v1/okrs`

---

### PAGE-016: Tasks & Projects
- **Route**: `/tasks`
- **Menu**: Tasks
- **Permission**: `TASKS_VIEW`
- **Components**: Task Checklist Table, Priority Badges, Assignee Selectors
- **REST API**: `GET /api/v1/tasks`, `POST /api/v1/tasks`

---

### PAGE-017: Support Desk Tickets
- **Route**: `/tickets`
- **Menu**: Tickets
- **Permission**: `TICKETS_VIEW`
- **Components**: Ticket Queue Table, Urgency Priority Tags, Reply Thread Interface
- **REST API**: `GET /api/v1/tickets`, `POST /api/v1/tickets/{id}/reply`

---

### PAGE-018: Staff Outsourcing & Booking
- **Route**: `/staff-outsourcing`
- **Menu**: Staff Outsourcing
- **Permission**: `STAFF_OUTSOURCING_VIEW`
- **Components**: Contractor Rate Cards, Resource Allocation Matrix
- **REST API**: `GET /api/v1/staff-outsourcing`, `POST /api/v1/staff-outsourcing/book`

---

### PAGE-019: Strategic Account Planning
- **Route**: `/account-planning`
- **Menu**: Account Planning
- **Permission**: `ACCOUNT_PLANNING_VIEW`
- **Components**: Account Tier Badges, Interactive SWOT Analysis Matrix
- **REST API**: `GET /api/v1/account-planning`

---

### PAGE-020: System Settings & Theme Customization
- **Route**: `/settings`
- **Menu**: Settings
- **Permission**: `SETTINGS_VIEW`
- **Components**: Interactive Theme Palette Selector, Company Branding Form
- **REST API**: `GET /api/v1/settings`, `POST /api/v1/settings`
