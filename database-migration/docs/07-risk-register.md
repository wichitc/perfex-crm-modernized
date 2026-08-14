# Phase 3: Risk Register & Mitigation Strategy

| Risk ID | Category | Description | Likelihood | Impact | Mitigation Strategy | Status |
|---|---|---|---|---|---|---|
| R-01 | Data Integrity | Auto-increment sequence desynchronization after data load | Low | High | Run `SELECT setval(pg_get_serial_sequence('tblname', 'col'), MAX(col)) FROM tblname;` post data import | Resolved |
| R-02 | Character Encoding | Thai / multi-byte character corruption during data import | Low | High | Enforce UTF-8 client encoding (`SET client_encoding = 'UTF8'`) | Resolved |
| R-03 | Performance | Missing index leading to sequential scans on large tables | Low | Medium | Apply index DDL script `006_indexes.sql` and run `EXPLAIN ANALYZE` | Resolved |
