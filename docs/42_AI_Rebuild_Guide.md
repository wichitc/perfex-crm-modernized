# AI Rebuild Guide

## Purpose
Provides a comprehensive roadmap for another AI developer agent to recreate the CRM and its 10 custom modules from scratch.

## Scope
Build phases, database loading sequence, and complexity matrices.

## Detailed Explanation
To rebuild this software from scratch, follow these instructions:

### Recommended Build Path
1. **Database Schema Initialization**: Run database schema creation (`database.sql`). Create the core tables first.
2. **Setup Base Framework & Auth**: Initialize framework routing, CSRF protection, and user/staff login models.
3. **Core CRM CRUD**: Implement Leads, Customers, Contacts, Projects, Tasks, and Support Tickets.
4. **Action/Filter Hook System**: Implement the Hooks manager to allow dynamic additions of settings, templates, and menu routes.
5. **Phase-in Modules**:
   - **Accounting**: Chart of Accounts -> Ledgers -> Automatic double-entry journaling via Invoice/Payment hooks.
   - **Warehouse & Purchase**: Commodity tables -> Goods Receipts -> Goods Delivery -> PO tracking -> Vendor management.
   - **HRM & Recruitment**: Staff directory -> shift mappings -> job campaigns -> applicant evaluation grids.
   - **WooCommerce Integration**: Sync scheduler -> order processing webhook -> mapping repository.
   - **REST API**: API Key auth middleware -> batch endpoints.

### Milestones & Complexity Matrix
| Phase | Components | Est. Complexity | Dependencies |
| --- | --- | --- | --- |
| 1 | DB & Core Framework Setup | Low | None |
| 2 | Auth, User roles, Permissions | Medium | Phase 1 |
| 3 | CRM Entities (Leads, Clients, Tasks) | Medium | Phase 2 |
| 4 | Hooks/Events Architecture | High | Phase 2 |
| 5 | Invoicing & Payments | High | Phase 3 |
| 6 | Accounting Module | Very High | Phase 4, 5 |
| 7 | Warehouse & Purchase Modules | High | Phase 5 |
| 8 | HRM & Recruitment Modules | High | Phase 2 |
| 9 | WooCommerce Sync | High | Phase 5 |
| 10 | REST API | Medium | Phase 2 |

## References
- [System Architecture](01_System_Architecture.md)
- [Table Dictionary](16_Table_Dictionary.md)
