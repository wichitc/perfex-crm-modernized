# Use Cases

## Purpose
Exposes core interactions of users with the platform and modules.

## Scope
Leads management, billing pipelines, accounting, and inventory.

## Detailed Explanation

### Use Case 1: Lead Conversion to Client
- **Actor**: Staff Member
- **Preconditions**: Lead exists in the system.
- **Trigger**: Lead reaches "Convert" status.
- **Workflow**:
  1. Staff clicks "Convert".
  2. System prompts for customer creation details.
  3. System creates a row in `tblclients` and `tblcontacts`.
  4. System updates lead status to converted.

### Use Case 2: Double-Entry Invoice Mapping
- **Actor**: System (Cron / Hook Event)
- **Preconditions**: Accounting automatic conversion is enabled.
- **Trigger**: Invoice is created or payment is recorded.
- **Workflow**:
  1. System triggers `after_invoice_added` hook.
  2. Accounting helper checks payment mapping.
  3. System creates debit and credit entries in `tblacc_account_history`.
  4. System updates account balances.

### Use Case 3: Goods Receipt Stock Entry
- **Actor**: Warehouse Manager
- **Preconditions**: Purchase Order exists.
- **Trigger**: Supplier goods arrive at warehouse.
- **Workflow**:
  1. Manager checks goods against purchase order.
  2. Manager creates a "Goods Receipt" record in `tblgoods_receipt`.
  3. System increases stock level in `tblwarehouse_commodity_details`.

## References
- [Business Workflow](09_Business_Workflow.md)
- [Functional Requirements](05_Functional_Requirements.md)
