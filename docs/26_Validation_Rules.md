# Validation Rules

## Purpose
Specifies client-side and server-side forms and field validation rules.

## Scope
Leads inputs, invoice forms, and module entries validations.

## Detailed Explanation
Data integrity is protected by validation boundaries:

### Server-Side Validations
Enforced using CodeIgniter's `form_validation` library.
- **Leads**: Name (required), Email (required|valid_email), Status (required).
- **Invoices**: Client ID (required|integer), Date (required|valid_date), Item Name (required).
- **Journal Entries**: Debit / Credit totals must match.
- **Goods Receipt**: Commodity ID (required), Quantity (required|numeric|greater_than[0]).

### Client-Side Validations
- Integrated inside HTML forms using the jQuery Validation plugin.
- Performs instant UI check before submitting.

## References
- [UI Analysis](11_UI_Analysis.md)
- [Database Analysis](15_Database_Analysis.md)
