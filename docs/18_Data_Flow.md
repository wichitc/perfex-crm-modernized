# Data Flow

## Purpose
Visualizes the flow of data packets (leads, invoices, purchases, stock) through the CRM and modules.

## Scope
Input channels, storage nodes, processing operations, and output delivery.

## Detailed Explanation
Data enters the system via external interfaces (Web-to-Lead forms, REST APIs, WooCommerce Webhooks) or manual user forms, goes through validation middleware, is processed by controllers and services, committed to the InnoDB database, and outputs via file rendering (PDF) or email/SMS dispatch.

### Main Data Pipelines
1. **Sales Lead Sync**: Web-to-lead Form -> CSRF checks -> Controller -> Save to `tblleads` -> Update Kanban -> Send Staff Notification.
2. **Invoicing Journal Mapping**: Client accepts Invoice -> Stripe Payment -> Create Payment Record -> Trigger Accounting Hook -> Accounting Model creates Debit/Credit logs in `tblacc_account_history`.
3. **Goods Delivery Pipeline**: Project finishes -> Trigger Invoice -> Generate Goods Delivery Voucher -> Deduct stock count from Warehouse -> Trigger shipment details -> Output packing list.

## References
- [Business Workflow](09_Business_Workflow.md)
- [System Workflow](10_System_Workflow.md)
