# Integration

## Purpose
Documents WooCommerce store sync mechanics, webhook payloads, and mapping layouts.

## Scope
Products synchronization, customer linkages, order routing, and mapping parameters.

## Detailed Explanation
The WooCommerce integration module bridges online shops with the CRM.

### Sync Workflow
- **Webhook Handlers**: WooCommerce triggers webhooks when orders are created, updated, or deleted.
- **Data Mapping**:
  - WooCommerce order customers are mapped to CRM clients inside `tblwoocommerce_customers`.
  - WooCommerce products map to CRM items inside `tblwoocommerce_products`.
  - Invoices are automatically generated inside the CRM based on settings inside `tblwoocommerce_stores.auto_invoice_statuses`.

## References
- [API Document](14_API_Document.md)
- [External API](37_External_API.md)
