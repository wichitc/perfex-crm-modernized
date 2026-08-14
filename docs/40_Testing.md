# Testing

## Purpose
Specifies quality control models, database seeds, and test plans.

## Scope
Core CRM validation, WooCommerce synchronization tests, and double-entry accounting reconciliation tests.

## Detailed Explanation
Rebuild tests should include:
1. **Unit Tests**: Form validations (Leads, Invoices, Contracts).
2. **Integration Tests**: WooCommerce mock order webhook receipt and verification of matching invoice insertion.
3. **Database Ledger Audit**: Verify that inserting a payment creates debit and credit rows in `tblacc_account_history` with identical values.

## References
- [Validation Rules](26_Validation_Rules.md)
- [Database Analysis](15_Database_Analysis.md)
