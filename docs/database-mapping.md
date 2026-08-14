# Database Schema Translation & Entity Mapping

This document provides a 100% complete field-by-field translation mapping from legacy MySQL Perfex CRM tables to the modernized SQLAlchemy PostgreSQL / SQLite ORM models.

---

## 1. Core CRM Entities

### 1.1 Clients Table (`tblclients` $\rightarrow$ `Client`)
| Legacy MySQL Column | PostgreSQL / SQLAlchemy Column | Data Type | Key / Constraint | Description |
|---|---|---|---|---|
| `userid` | `userid` | Integer | Primary Key (Autoincrement) | Client account ID |
| `company` | `company` | String(191) | Nullable | Corporate company name |
| `vat` | `vat` | String(50) | Nullable | VAT / Tax Registration ID |
| `phonenumber` | `phonenumber` | String(50) | Nullable | Primary contact phone |
| `country` | `country` | Integer | Default 0 | Country ID |
| `city` | `city` | String(100) | Nullable | City name |
| `zip` | `zip` | String(50) | Nullable | Postal code |
| `state` | `state` | String(100) | Nullable | State / Province |
| `address` | `address` | String(191) | Nullable | Physical street address |
| `website` | `website` | String(191) | Nullable | Corporate website URL |
| `active` | `active` | Integer | Default 1 | Account active flag (1/0) |
| `leadid` | `leadid` | Integer | Default 0 | Originating Lead ID |

### 1.2 Contacts Table (`tblcontacts` $\rightarrow$ `Contact`)
| Legacy MySQL Column | PostgreSQL / SQLAlchemy Column | Data Type | Key / Constraint | Description |
|---|---|---|---|---|
| `id` | `id` | Integer | Primary Key (Autoincrement) | Contact ID |
| `userid` | `userid` | Integer | Foreign Key (`tblclients.userid`) | Parent client ID |
| `firstname` | `firstname` | String(191) | Not Null | Contact first name |
| `lastname` | `lastname` | String(191) | Not Null | Contact last name |
| `email` | `email` | String(191) | Not Null, Unique | Email address |
| `phonenumber` | `phonenumber` | String(50) | Nullable | Phone number |
| `title` | `title` | String(100) | Nullable | Job title |
| `is_primary` | `is_primary` | Integer | Default 1 | Primary contact flag |
| `password` | `password` | String(255) | Nullable | Hashed password |

---

## 2. Lead Management Entities (`tblleads` $\rightarrow$ `Lead`)
| Legacy MySQL Column | PostgreSQL / SQLAlchemy Column | Data Type | Key / Constraint | Description |
|---|---|---|---|---|
| `id` | `id` | Integer | Primary Key (Autoincrement) | Lead ID |
| `name` | `name` | String(191) | Not Null | Full lead name |
| `title` | `title` | String(100) | Nullable | Job position |
| `company` | `company` | String(191) | Nullable | Lead company |
| `email` | `email` | String(191) | Nullable | Email address |
| `status` | `status` | Integer | Foreign Key (`tblleads_status.id`) | Lead status ID |
| `source` | `source` | Integer | Foreign Key (`tblleads_sources.id`) | Acquisition source ID |
| `assigned` | `assigned` | Integer | Foreign Key (`tblstaff.staffid`) | Assigned staff ID |
| `lead_value` | `lead_value` | Float | Nullable | Estimated deal value (THB) |
| `client_id` | `client_id` | Integer | Default 0 | Linked converted client ID |
| `lost` | `lost` | Integer | Default 0 | Mark lost flag |
| `junk` | `junk` | Integer | Default 0 | Mark junk flag |

---

## 3. Financial & Invoicing Entities

### 3.1 Invoices Table (`tblinvoices` $\rightarrow$ `Invoice`)
| Legacy MySQL Column | PostgreSQL / SQLAlchemy Column | Data Type | Key / Constraint | Description |
|---|---|---|---|---|
| `id` | `id` | Integer | Primary Key (Autoincrement) | Invoice ID |
| `clientid` | `clientid` | Integer | Foreign Key (`tblclients.userid`) | Client ID |
| `number` | `number` | Integer | Not Null | Invoice sequential number |
| `prefix` | `prefix` | String(50) | Default "INV-2026-" | Document prefix |
| `date` | `date` | Date | Not Null | Invoice issuance date |
| `duedate` | `duedate` | Date | Nullable | Payment due date |
| `subtotal` | `subtotal` | Float | Default 0.0 | Net subtotal amount |
| `total` | `total` | Float | Default 0.0 | Gross invoice total |
| `status` | `status` | Integer | Default 1 | Status (1=Unpaid, 2=Paid, 3=Partial, 4=Overdue) |
| `hash` | `hash` | String(32) | Unique | Security hash token |

### 3.2 Invoice Line Items (`tblitems_in` $\rightarrow$ `InvoiceItem`)
| Legacy MySQL Column | PostgreSQL / SQLAlchemy Column | Data Type | Key / Constraint | Description |
|---|---|---|---|---|
| `id` | `id` | Integer | Primary Key (Autoincrement) | Item ID |
| `rel_id` | `rel_id` | Integer | Foreign Key (`tblinvoices.id`) | Linked Invoice ID |
| `description` | `description` | Text | Not Null | Short item description |
| `long_description` | `long_description` | Text | Nullable | Long item details |
| `qty` | `qty` | Float | Default 1.0 | Quantity |
| `rate` | `rate` | Float | Default 0.0 | Unit rate |

---

## 4. Double-Entry Accounting Entities

### 4.1 Chart of Accounts (`tblacc_accounts` $\rightarrow$ `Account`)
| Legacy MySQL Column | PostgreSQL / SQLAlchemy Column | Data Type | Key / Constraint | Description |
|---|---|---|---|---|
| `id` | `id` | Integer | Primary Key (Autoincrement) | Account ID |
| `name` | `name` | String(191) | Not Null | Account name |
| `number` | `number` | String(50) | Nullable | Account code |
| `account_type_id` | `account_type_id` | Integer | Not Null | 1=Asset, 2=Liability, 3=Equity, 4=Income, 5=Expense |
| `balance` | `balance` | Float | Default 0.0 | Current ledger balance |

### 4.2 Account History Ledger (`tblacc_account_history` $\rightarrow$ `AccountHistory`)
| Legacy MySQL Column | PostgreSQL / SQLAlchemy Column | Data Type | Key / Constraint | Description |
|---|---|---|---|---|
| `id` | `id` | Integer | Primary Key (Autoincrement) | Entry ID |
| `account` | `account` | Integer | Foreign Key (`tblacc_accounts.id`) | Linked Account ID |
| `debit` | `debit` | Float | Default 0.0 | Debit transaction amount |
| `credit` | `credit` | Float | Default 0.0 | Credit transaction amount |
| `rel_id` | `rel_id` | Integer | Not Null | Linked Document ID |
| `rel_type` | `rel_type` | String(50) | Not Null | Document Type (invoice, payment, etc.) |

---

## 5. Warehouse & Stock Entities (`tblwarehouse` $\rightarrow$ `Warehouse`)
| Legacy Column | SQLAlchemy Column | Data Type | Constraint | Description |
|---|---|---|---|---|
| `warehouse_id` | `warehouse_id` | Integer | Primary Key | Depot ID |
| `warehouse_code` | `warehouse_code` | String(100) | Unique | Depot code (e.g. WH-MAIN) |
| `warehouse_name` | `warehouse_name` | String(191) | Not Null | Depot name |
| `warehouse_address`| `warehouse_address` | String(255) | Nullable | Depot address |
