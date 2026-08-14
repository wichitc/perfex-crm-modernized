> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary - Part 6

> [!NOTE]
> This is Part 6 of the document. [Back to Part 1](16_Table_Dictionary.md)

### Table: tbl_multi_theme

**Module**: Multi Theme

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `theme_css` | `varchar(45)` | `DEFAULT NULL` |
| `added_at` | `TIMESTAMP` | `on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP` |

---

### Table: tblacc_account_history

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `account` | `INT(11)` | `NOT NULL` |
| `debit` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `credit` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `description` | `TEXT` | `NULL` |
| `rel_id` | `INT(11)` | `NULL` |
| `rel_type` | `VARCHAR(45)` | `NULL` |
| `datecreated` | `DATETIME` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |
| `customer` | `INT(11)` | `NULL` |

---

### Table: tblacc_account_type_details

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `account_type_id` | `INT(11)` | `NOT NULL` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `note` | `TEXT` | `NULL` |
| `statement_of_cash_flows` | `VARCHAR(255)` | `NULL` |

---

### Table: tblacc_accounts

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `key_name` | `VARCHAR(255)` | `NULL` |
| `number` | `VARCHAR(45)` | `NULL` |
| `parent_account` | `INT(11)` | `NULL` |
| `account_type_id` | `INT(11)` | `NOT NULL` |
| `account_detail_type_id` | `INT(11)` | `NOT NULL` |
| `balance` | `DECIMAL(15,2` | `) NULL` |
| `balance_as_of` | `DATE` | `NULL` |
| `description` | `TEXT` | `NULL` |

---

### Table: tblacc_approval_details

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT` | `NOT NULL AUTO_INCREMENT` |
| `rel_id` | `INT` | `NOT NULL` |
| `rel_type` | `VARCHAR(255)` | `NOT NULL` |
| `staffid` | `TEXT` | `NOT NULL` |
| `approve` | `INT` | `NOT NULL` |
| `note` | `TEXT` | `NULL` |
| `date` | `DATETIME` | `NULL` |
| `approve_value` | `DECIMAL(15,2` | `) DEFAULT '0.00'` |
| `action` | `VARCHAR(255)` | `NULL` |
| `sender` | `INT` | `NOT NULL DEFAULT '0'` |
| `date_send` | `DATETIME` | `NULL` |
| `approve_setting_id` | `INT` | `NULL` |

---

### Table: tblacc_approval_setting

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `related` | `VARCHAR(255)` | `NOT NULL` |
| `setting` | `LONGTEXT` | `NOT NULL` |
| `approval_type` | `INT` | `NOT NULL DEFAULT '0'` |

---

### Table: tblacc_bank_reconciles

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `account` | `INT(11)` | `NOT NULL` |
| `opening_balance` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `beginning_balance` | `DECIMAL(15,2` | `) NOT NULL` |
| `ending_balance` | `DECIMAL(15,2` | `) NOT NULL` |
| `ending_date` | `DATE` | `NOT NULL` |
| `finish` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `debits_for_period` | `DECIMAL(15,2` | `) NOT NULL` |
| `credits_for_period` | `DECIMAL(15,2` | `) NOT NULL` |
| `dateadded` | `DATETIME` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |

---

### Table: tblacc_banking_rule_details

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `rule_id` | `INT(11)` | `NOT NULL` |
| `type` | `VARCHAR(45)` | `NULL` |
| `subtype` | `VARCHAR(45)` | `NULL` |
| `text` | `VARCHAR(255)` | `NULL` |

---

### Table: tblacc_banking_rules

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `transaction` | `VARCHAR(45)` | `NULL` |
| `following` | `VARCHAR(45)` | `NULL` |
| `then` | `VARCHAR(45)` | `NULL` |
| `payment_account` | `INT(11)` | `NULL` |
| `deposit_to` | `INT(11)` | `NULL` |

---

### Table: tblacc_bill_mappings

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `bill_id` | `INT(11)` | `NULL` |
| `type` | `VARCHAR(25)` | `NULL` |
| `account` | `INT(11)` | `NULL` |
| `amount` | `DECIMAL(15,2` | `) NULL` |

---

### Table: tblacc_budget_details

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `budget_id` | `INT(11)` | `NOT NULL` |
| `month` | `INT(11)` | `NOT NULL` |
| `year` | `INT(11)` | `NOT NULL` |
| `account` | `INT(11)` | `NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |

---

### Table: tblacc_budgets

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `year` | `INT(11)` | `NOT NULL` |
| `name` | `VARCHAR(200)` | `NULL` |
| `type` | `VARCHAR(45)` | `NULL` |
| `data_source` | `VARCHAR(45)` | `NULL` |

---

### Table: tblacc_check_details

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `check_id` | `INT(11)` | `NULL` |
| `bill` | `INT(11)` | `NULL` |

---

### Table: tblacc_checks

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `rel_id` | `INT(11)` | `NULL` |
| `rel_type` | `VARCHAR(25)` | `NULL` |
| `amount` | `DECIMAL(15,2` | `) NULL` |
| `date` | `DATE` | `NULL` |
| `memo` | `VARCHAR(255)` | `NULL` |
| `dateadded` | `datetime` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |

---

### Table: tblacc_checks_printed

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `check_id` | `INT(11)` | `NULL` |
| `bank_account` | `INT(11)` | `NULL` |
| `first_check_number` | `INT(11)` | `NULL` |
| `printed_at` | `DATETIME` | `NULL` |
| `printed_by` | `INT(11)` | `NULL` |

---

### Table: tblacc_claim_refunds

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `claim_id` | `INT(11)` | `NOT NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL` |
| `payment_date` | `DATE` | `NOT NULL` |
| `payment_method` | `VARCHAR(100)` | `NOT NULL` |
| `notes` | `TEXT` | `NULL` |
| `attachment` | `VARCHAR(255)` | `DEFAULT NULL` |
| `debit_account_id` | `INT(11)` | `DEFAULT NULL` |
| `credit_account_id` | `INT(11)` | `DEFAULT NULL` |
| `created_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblacc_claims

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `expense_date` | `DATE` | `NOT NULL` |
| `project_id` | `INT(11)` | `NOT NULL` |
| `category_id` | `INT(11)` | `NOT NULL` |
| `staff_id` | `INT(11)` | `NOT NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `status` | `VARCHAR(50)` | `DEFAULT 'draft'` |
| `debit_account_id` | `INT(11)` | `DEFAULT NULL` |
| `credit_account_id` | `INT(11)` | `DEFAULT NULL` |
| `created_by` | `INT(11)` | `NOT NULL` |
| `created_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblacc_class

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `TEXT` | `NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |
| `dateadded` | `DATETIME` | `NOT NULL` |

---

### Table: tblacc_expense_category_mapping_details

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `category_mapping_id` | `INT(11)` | `NOT NULL` |
| `payment_mode_id` | `INT(11)` | `NOT NULL` |
| `payment_account` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `deposit_to` | `INT(11)` | `NOT NULL DEFAULT 0` |

---

### Table: tblacc_expense_category_mappings

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `category_id` | `INT(11)` | `NOT NULL` |
| `payment_account` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `deposit_to` | `INT(11)` | `NOT NULL DEFAULT 0` |

---

### Table: tblacc_imprest_requests

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `reference_no` | `VARCHAR(100)` | `NOT NULL` |
| `request_date` | `DATE` | `NOT NULL` |
| `project_id` | `INT(11)` | `NOT NULL` |
| `category_id` | `INT(11)` | `NOT NULL` |
| `staff_id` | `INT(11)` | `NOT NULL` |
| `amount_requested` | `DECIMAL(15,2` | `) NOT NULL` |
| `amount_retired` | `DECIMAL(15,2` | `) DEFAULT '0.00'` |
| `variance` | `DECIMAL(15,2` | `) DEFAULT '0.00'` |
| `payment_method` | `VARCHAR(100)` | `NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `attachment` | `VARCHAR(255)` | `DEFAULT NULL` |
| `status` | `VARCHAR(50)` | `DEFAULT 'draft'` |
| `debit_account_id` | `INT(11)` | `DEFAULT NULL` |
| `credit_account_id` | `INT(11)` | `DEFAULT NULL` |
| `retire_notes` | `TEXT` | `NULL` |
| `retire_payment_method` | `VARCHAR(100)` | `NULL` |
| `retire_transaction_id` | `VARCHAR(255)` | `NULL` |
| `retire_date` | `DATE` | `NULL` |
| `expense_account_id` | `INT(11)` | `DEFAULT NULL` |
| `cash_bank_account_id` | `INT(11)` | `DEFAULT NULL` |
| `created_by` | `INT(11)` | `NOT NULL` |
| `created_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblacc_income_statement_modifications

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `TEXT` | `NOT NULL` |
| `account` | `INT(11)` | `NULL` |
| `active` | `INT(11)` | `NOT NULL DEFAULT 1` |
| `account_type` | `INT(11)` | `NULL` |
| `options` | `TEXT` | `None` |
| `dateadded` | `DATETIME` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |

---

### Table: tblacc_item_automatics

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `item_id` | `INT(11)` | `NOT NULL` |
| `inventory_asset_account` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `income_account` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `expense_account` | `INT(11)` | `NOT NULL DEFAULT 0` |

---

### Table: tblacc_item_group_automatics

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `item_group_id` | `INT(11)` | `NOT NULL` |
| `inventory_asset_account` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `income_account` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `expense_account` | `INT(11)` | `NOT NULL DEFAULT 0` |

---

### Table: tblacc_journal_entries

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `number` | `VARCHAR(45)` | `NULL` |
| `description` | `TEXT` | `NULL` |
| `journal_date` | `DATE` | `NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `datecreated` | `DATETIME` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |

---

### Table: tblacc_matched_transactions

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `account_history_id` | `INT(11)` | `NULL` |
| `history_amount` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `rel_id` | `INT(11)` | `NULL` |
| `rel_type` | `VARCHAR(255)` | `NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `company` | `int(11)` | `DEFAULT NULL` |

---

### Table: tblacc_pay_bill_details

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `pay_bill` | `INT(11)` | `NULL` |
| `bill_id` | `INT(11)` | `NULL` |

---

### Table: tblacc_pay_bill_item_paid

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `pay_bill_id` | `int(11)` | `NOT NULL DEFAULT 0` |
| `item_id` | `INT(11)` | `NULL` |
| `item_name` | `VARCHAR(255)` | `NULL` |
| `item_amount` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `amount_paid` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |

---

### Table: tblacc_pay_bills

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `expense` | `int(11)` | `NULL` |
| `amount` | `DECIMAL(15,2` | `) NULL` |
| `reference_no` | `varchar(100)` | `NULL` |
| `date` | `date` | `NULL` |
| `dateadded` | `datetime` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |
| `company` | `INT(11)` | `NULL` |
| `account_debit` | `INT(11)` | `NULL` |
| `account_credit` | `INT(11)` | `NULL` |

---

### Table: tblacc_payment_mode_mappings

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `payment_mode_id` | `INT(11)` | `NOT NULL` |
| `payment_account` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `deposit_to` | `INT(11)` | `NOT NULL DEFAULT 0` |

---

### Table: tblacc_plaid_transaction_logs

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `bank_id` | `int(11)` | `DEFAULT NULL` |
| `last_updated` | `date` | `DEFAULT NULL` |
| `transaction_count` | `int(11)` | `DEFAULT NULL` |
| `created_at` | `datetime` | `DEFAULT NULL` |
| `addedFrom` | `int(11)` | `DEFAULT NULL` |
| `company` | `int(11)` | `DEFAULT NULL` |
| `status` | `int(11)` | `NOT NULL DEFAULT 0` |

---

### Table: tblacc_print_later

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `rel_id` | `INT(11)` | `NULL` |
| `rel_type` | `VARCHAR(45)` | `NULL` |
| `account` | `INT(11)` | `NOT NULL DEFAULT 0` |

---

### Table: tblacc_project_budget_categories

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `created_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblacc_project_budget_details

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `budget_id` | `INT(11)` | `NOT NULL` |
| `category_id` | `INT(11)` | `NOT NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL DEFAULT '0.00'` |

---

### Table: tblacc_project_budget_mappings

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `rel_id` | `INT(11)` | `NOT NULL` |
| `rel_type` | `VARCHAR(50)` | `NOT NULL` |
| `project_id` | `INT(11)` | `NOT NULL` |
| `category_id` | `INT(11)` | `NOT NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL DEFAULT '0.00'` |

---

