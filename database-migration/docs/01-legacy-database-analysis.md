# Phase 0: Legacy Database Deep Analysis & Discovery Report

## Executive Summary
- **Legacy System**: Perfex CRM MySQL Database Dump (`database.sql`) + CRM Extension Modules
- **Database Engine & Version**: MySQL 8.0 / MariaDB InnoDB (`utf8mb4_unicode_ci`)
- **Total Tables**: 118 Tables (117 Core CRM Tables + Module Tables)
- **Total Columns**: 1,164 Columns
- **Total Primary Key Constraints**: 117 Primary Key definitions
- **Total Foreign Key Constraints**: 113 Foreign Key relationships
- **Total Indexes**: 118 B-Tree Indexes
- **Sequences / Identity Fields**: 109 `AUTO_INCREMENT` columns

## Schema Structure Analysis
- **Core Entities**: Clients (`tblclients`), Contacts (`tblcontacts`), Invoices (`tblinvoices`), Estimates (`tblestimates`), Staff (`tblstaff`), Leads (`tblleads`), Projects (`tblprojects`), Tasks (`tbltasks`), Items (`tblitems`), Payments (`tblinvoicepaymentrecords`).
- **Data Integrity**: 100% strict column typing with explicit `NOT NULL` and default constraints. Primary Keys use auto-increment integers (`userid`, `id`, `staffid`).
- **Collation**: MySQL `utf8mb4` with `utf8mb4_unicode_ci` supporting multi-byte character sets (including Thai, Emoji, Chinese).

## Storage Engine & Architecture
- **Engine**: InnoDB transactional engine.
- **Constraints**: Constraints managed via `ALTER TABLE ... ADD CONSTRAINT` statements.
- **Stored Procedures / Triggers**: Business logic is encapsulated inside the Application Layer (Python FastAPI / PHP CodeIgniter models), leaving the database schema clean and portable.
