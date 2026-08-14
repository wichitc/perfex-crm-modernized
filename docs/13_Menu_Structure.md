# Menu Structure

## Purpose
Details sidebar hierarchy and settings menus, including module hooks-injected configurations.

## Scope
Main navigation bar, Settings submenu, and User Profile widgets.

## Detailed Explanation
Sidebar menus are registered during the `admin_init` action hook. Modules intercept this action to inject their menus.

### Nested Sidebar Menu Outline
1. **Dashboard**
2. **Customers**
3. **Sales**
   - Proposals
   - Estimates
   - Invoices
   - Payments
   - Credit Notes
   - Items
4. **Recruitment** (Recruitment Module Hook)
   - Job Position
   - Campaigns
   - Candidates
   - Rec Portal
5. **HRM** (HRM Module Hook)
   - Staff Directory
   - Work Shift
   - Attendance
   - Leaves
   - Payroll
6. **Warehouse** (Warehouse Module Hook)
   - Commodity List
   - Warehouse Stock
   - Goods Receipt
   - Goods Delivery
   - Shipments
7. **Purchase** (Purchase Module Hook)
   - Suppliers
   - Purchase Request
   - Purchase Order
   - Purchase Invoice
8. **Accounting** (Accounting Module Hook)
   - Chart of Accounts
   - Journal Entries
   - Reconcile
   - Financial Reports
9. **OKRs** (OKRs Module Hook)
   - OKRs Board
   - Settings
10. **WooCommerce** (WooCommerce Module Hook)
    - Stores
    - Synced Products
    - Synced Orders
    - Connection Logs

## References
- [UI Analysis](11_UI_Analysis.md)
- [Screen Flow](12_Screen_Flow.md)
