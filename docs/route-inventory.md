# Route Inventory - Perfex CRM

## Route & Guard Specification Table

This table documents all application routes, URI paths, authentication requirements, authorization guards, and breadcrumb structures.

| Route Path | Page Title | Auth Guard | Permission Required | Breadcrumb Trail |
| :--- | :--- | :---: | :--- | :--- |
| `/login` | Authentication Login | Public | None | Login |
| `/` | Dashboard Overview | Protected | `DASHBOARD_VIEW` | Dashboard |
| `/clients` | Clients Directory | Protected | `CUSTOMERS_VIEW` | Customers > Clients |
| `/clients/:id` | Client Profile | Protected | `CUSTOMERS_VIEW` | Customers > Clients > Profile |
| `/leads` | Leads Pipeline | Protected | `LEADS_VIEW` | Sales > Leads |
| `/invoices` | Invoices & Billing | Protected | `INVOICES_VIEW` | Sales > Invoices |
| `/invoices/:id` | Invoice Details | Protected | `INVOICES_VIEW` | Sales > Invoices > View |
| `/estimates` | Estimates & Proposals | Protected | `ESTIMATES_VIEW` | Sales > Estimates |
| `/proposals` | Contract Proposals | Protected | `PROPOSALS_VIEW` | Sales > Proposals |
| `/accounting` | Accounting & Ledger | Protected | `ACCOUNTING_VIEW` | Finance > Accounting |
| `/warehouse` | Warehouse Stock | Protected | `WAREHOUSE_VIEW` | Inventory > Warehouse |
| `/purchase` | Purchase Orders | Protected | `PURCHASE_VIEW` | Procurement > Purchase |
| `/woocommerce` | Store Sync Connector | Protected | `WOOCOMMERCE_VIEW` | Integrations > WooCommerce |
| `/recruitment` | Recruitment & HR | Protected | `RECRUITMENT_VIEW` | HR > Recruitment |
| `/okrs` | Objectives & Goals | Protected | `OKRS_VIEW` | Strategy > OKRs |
| `/tasks` | Tasks & Projects | Protected | `TASKS_VIEW` | Operations > Tasks |
| `/tickets` | Support Desk Queue | Protected | `TICKETS_VIEW` | Support > Tickets |
| `/staff-outsourcing` | Resource Allocation | Protected | `STAFF_OUTSOURCING_VIEW` | Operations > Staff Outsourcing |
| `/account-planning` | Strategic Account SWOT | Protected | `ACCOUNT_PLANNING_VIEW` | Strategy > Account Planning |
| `/settings` | System & Theme Settings | Protected | `SETTINGS_VIEW` | Setup > Settings |
