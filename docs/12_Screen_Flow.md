# Screen Flow

## Purpose
Traces navigations between admin views, client portals, and module-specific portals.

## Scope
Path maps for Invoicing, Recruitment, Staff Outsourcing booking, and Warehouse records.

## Detailed Explanation
### Administrative Screen Navigation
1. **Login Screen** -> redirects to **Dashboard**
2. **Dashboard** -> Click **Customers** -> **Customers List** -> Click **Customer details** -> Opens Customer profile with tabs (Contacts, Invoices, Proposals, Tickets, Tasks, Projects).
3. **Dashboard** -> Click **Accounting** -> **Chart of Accounts** -> Click **Reconcile** -> Reconcile screen.
4. **Dashboard** -> Click **Recruitment** -> **Candidates** -> Click **Candidate details** -> Opens evaluation and interview schedule modals.
5. **Dashboard** -> Click **Warehouse** -> **Goods Receipt** -> Click **Add Goods Receipt** -> Voucher wizard.

### Client Portal Screen Navigation
1. **Client Portal Login** -> redirects to **Portal Dashboard**
2. **Portal Dashboard** -> Click **Invoices** -> **Invoices List** -> Click **Invoice** -> HTML invoice view with payment gateway selector button.
3. **Portal Dashboard** -> Click **Projects** -> **Project Details** -> Tasks checklist, Gantt Chart, timesheets view.

## References
- [UI Analysis](11_UI_Analysis.md)
- [Menu Structure](13_Menu_Structure.md)
