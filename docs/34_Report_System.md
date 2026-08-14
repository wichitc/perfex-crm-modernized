# Report System

## Purpose
Specifies analytics, dashboard reports, sales graphs, and double-entry accounting reporting engines.

## Scope
Ledger queries, profit/loss sheets, sales reports, and PDF renders.

## Detailed Explanation
### 1. Financial Reports (Accounting Module)
- **Profit and Loss**: Calculates revenues minus expenses over a custom date range by querying `tblacc_account_history`.
- **Balance Sheet**: Details assets, liabilities, and equity balances.
- **Trial Balance**: Validates ledger equilibrium.

### 2. Sales Reports
- Graph displays generated using Chart.js.
- Lists invoices, expenses, payments, and estimates grouped by months/years.

### 3. PDF Reporting
- Supports batch export of invoices and proposals using the TCPDF class library.

## References
- [Functional Requirements](05_Functional_Requirements.md)
- [Technology Stack](02_Technology_Stack.md)
