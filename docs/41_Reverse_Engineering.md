# Reverse Engineering Findings

## Purpose
Documents core structural findings, hidden patterns, and codebase conventions discovered during analysis.

## Scope
Design patterns, hooks architecture, and database integrations.

## Detailed Explanation
### 1. Framework & Architecture Patterns
- **CodeIgniter 3 Base**: Monolithic backend using standard MVC, enhanced by modular extensions (`MX_Controller`).
- **Hook/Observer Pattern**: Global actions and filters managed by the `App_Hooks` library class are used by modules to inject UI menu items, database migrations, and settings fields.
- **Double Entry Posting Engine**: Accounting module hooks into payments and invoice events to parse parameters and insert debit/credit rows to the ledger automatically.

### 2. Coding and Naming Standards
- CodeIgniter naming: Controllers begin with uppercase, Models end with `_model.php`.
- Database naming: Prefix `tbl` is followed by module prefix (e.g. `tblacc_`, `tblwh_`, `tblrec_`).

## References
- [System Architecture](01_System_Architecture.md)
- [Project Structure](03_Project_Structure.md)
