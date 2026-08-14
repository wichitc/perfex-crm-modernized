# Database Analysis

## Purpose
Analyzes the database setup, naming conventions, index optimization, and structural rules.

## Scope
Details general constraints, engine configurations, and relational structures.

## Detailed Explanation
### Database Specifications
- **Database Engine**: InnoDB. Supports transactions, foreign key constraints, and row-level locking.
- **Collation**: `utf8mb4_unicode_ci` / `utf8mb4_general_ci` (provides full unicode support for multi-language entries).
- **Table Naming Convention**: Tables are prefixed with the standard configuration prefix (default is `tbl`).
- **Module Prefixes**:
  - `tblacc_`: Accounting module tables (68 tables)
  - `tblhrm_` / `tblwork_`: HRM module tables (54 tables)
  - `tblrec_`: Recruitment module tables (26 tables)
  - `tblpur_`: Purchase management tables (39 tables)
  - `tblgoods_` / `tblwh_` / `tblware_`: Warehouse inventory tables (46 tables)
  - `tblokr_`: OKRs module tables (13 tables)
  - `tblwoocommerce_`: WooCommerce module tables (13 tables)
  - `tblapi_`: REST API logs and limits (9 tables)

### Naming Conventions
- **Primary Keys**: Typically `id`, or specific compound IDs (e.g. `announcementid` in `tblannouncements`, `staffid` in `tblstaff`).
- **Foreign Keys**: Reference other primary keys (e.g. `clientid` in `tblinvoices` maps to `userid` in `tblclients`).

## References
- [Table Dictionary](16_Table_Dictionary.md)
- [ER Diagram](17_ER_Diagram.md)
