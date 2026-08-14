# Phase 3: PostgreSQL Migration Plan

## Phase Timeline & Milestones
1. **Phase 0 (Discovery)**: Complete schema and dependency discovery.
2. **Phase 1 (Inventory)**: Generate JSON metadata inventories for all 118 tables.
3. **Phase 2 (Mapping)**: Create YAML mappings for tables, columns, and data types.
4. **Phase 3 (Plan & Risk)**: Finalize migration runbook and risk register.
5. **Phase 4 (PostgreSQL Schema)**: Create DDL scripts `001_extensions.sql` through `011_permissions.sql`.
6. **Phase 5 (Data Migration)**: Transform and load initial seed data.
7. **Phase 6 (Validation)**: Verify 100% row count and referential integrity parity.
8. **Phase 7 (Application Modernization)**: Update FastAPI backend configuration for AsyncPG/PostgreSQL.
9. **Phase 8 (Testing)**: Run automated pytest suite.
10. **Phase 10 (Audit)**: Generate final migration scorecard.
