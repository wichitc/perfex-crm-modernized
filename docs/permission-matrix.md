# Permission Matrix - Perfex CRM

## Role-Based Access Control (RBAC) Permission Matrix

| Feature / Module | Permission Code | Super Admin | Staff / Manager | Support Agent | Client User |
| :--- | :--- | :---: | :---: | :---: | :---: |
| Dashboard Stats | `DASHBOARD_VIEW` | ✓ | ✓ | ✓ | - |
| View Clients | `CUSTOMERS_VIEW` | ✓ | ✓ | - | - |
| Create/Edit Clients | `CUSTOMERS_EDIT` | ✓ | ✓ | - | - |
| View Invoices | `INVOICES_VIEW` | ✓ | ✓ | - | Own Invoices |
| Issue Invoice | `INVOICES_CREATE` | ✓ | ✓ | - | - |
| Record Payment | `INVOICES_PAYMENT` | ✓ | ✓ | - | Own Pay |
| View Leads | `LEADS_VIEW` | ✓ | Assigned | - | - |
| Convert Lead | `LEADS_CONVERT` | ✓ | Assigned | - | - |
| View Accounting | `ACCOUNTING_VIEW` | ✓ | Finance Role | - | - |
| View Warehouse | `WAREHOUSE_VIEW` | ✓ | Inventory Role | - | - |
| View Purchase Orders| `PURCHASE_VIEW` | ✓ | Procurement Role | - | - |
| WooCommerce Sync | `WOOCOMMERCE_VIEW` | ✓ | - | - | - |
| Support Desk Queue | `TICKETS_VIEW` | ✓ | ✓ | ✓ | Own Tickets |
| System Settings | `SETTINGS_VIEW` | ✓ | - | - | - |
