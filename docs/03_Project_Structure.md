# Project Structure

## Purpose
Documents the directory hierarchy and logical module placements of Perfex CRM and its 10 custom modules.

## Scope
Root layout, application layout, and extension module directories.

## Detailed Explanation
### Core Folder Structure
```
perfex_crm/
├── application/
│   ├── config/          # Configurations
│   ├── controllers/     # HTTP Routing handlers
│   ├── helpers/         # Custom PHP helpers
│   ├── libraries/       # core system components
│   ├── models/          # Database queries and repositories
│   └── views/           # UI templates
├── assets/              # Core CSS, JS, and image assets
├── install/             # Database initialization (database.sql)
├── modules/             # Default modules (backup, surveys, goals, etc.)
└── system/              # CodeIgniter 3 framework core files
```

### Custom Modules Folder Structure (Workspace Root)
1. **Accounting**: `accounting/accounting/`
2. **HRM**: `hrm/`
3. **Recruitment**: `recruitment/recruitment/`
4. **Warehouse (Inventory)**: `upload/warehouse/`
5. **Purchase Management**: `purchase/purchase/`
6. **Staff Outsourcing**: `staff_outsourcing/resourcebooking/`
7. **OKRs**: `okrs/okr/`
8. **WooCommerce**: `woocommerce/`
9. **REST API**: `api/`
10. **Multi Theme**: `perfex_multi_theme/`

Each module replicates the CodeIgniter directory structure containing:
- `controllers/`
- `models/`
- `views/`
- `helpers/`
- `libraries/`
- `install.php` / `install.sql` (Activation script)

## References
- [System Architecture](01_System_Architecture.md)
- [Technology Stack](02_Technology_Stack.md)
