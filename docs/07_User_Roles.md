# User Roles

## Purpose
Lists system actors, roles, and access scopes within Perfex CRM.

## Scope
Internal staff roles, customer portal contacts, suppliers, candidates, and administrative profiles.

## Detailed Explanation
Perfex CRM uses a Role-Based Access Control (RBAC) model. Permissions are assigned to Staff members directly or via Roles.

### Roles Matrix
| Role / Actor | Context | Primary Permissions |
| --- | --- | --- |
| **Administrator** | Core CRM Admin | Full database access, system configurations, module activations, cron management, audit log viewing. |
| **Staff Member** | Core CRM Back-office | CRUD permissions on Leads, Projects, Tasks, and Support Tickets based on department assignment. |
| **Accountant** | Accounting Module | View Chart of Accounts, create manual Journal Entries, run Bank Reconciliations, view Financial Reports. |
| **HR Manager** | HRM Module | Edit staff files, approve work shifts, review attendance logs, process leave requests. |
| **Recruiting Manager** | Recruitment Module | Post new job campaigns, coordinate candidate interviews, evaluate applicants. |
| **Warehouse Manager** | Warehouse Module | Track stock levels, approve goods receipts/delivery vouchers, record losses, trigger shipments. |
| **Customer Contact** | Client Portal | View invoices, pay online, accept estimates/proposals, submit support tickets. |
| **Vendor / Supplier** | Purchase Portal | View purchase orders, submit invoices, register items. |
| **Candidate** | Recruitment Portal | Search job postings, apply for roles, check interview schedules. |

## References
- [Authorization](25_Authorization.md)
- [Authentication](24_Authentication.md)
