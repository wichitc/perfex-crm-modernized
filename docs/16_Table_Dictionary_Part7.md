> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary - Part 7

> [!NOTE]
> This is Part 7 of the document. [Back to Part 1](16_Table_Dictionary.md)

### Table: tblacc_project_budgets

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `project_id` | `INT(11)` | `NOT NULL` |
| `owner_id` | `INT(11)` | `NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `status` | `VARCHAR(50)` | `DEFAULT 'draft'` |
| `created_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblacc_reconciles

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `account` | `INT(11)` | `NOT NULL` |
| `beginning_balance` | `DECIMAL(15,2` | `) NOT NULL` |
| `ending_balance` | `DECIMAL(15,2` | `) NOT NULL` |
| `ending_date` | `DATE` | `NOT NULL` |
| `expense_date` | `DATE` | `NULL` |
| `service_charge` | `DECIMAL(15,2` | `) NULL` |
| `expense_account` | `INT(11)` | `NULL` |
| `income_date` | `DATE` | `NULL` |
| `interest_earned` | `DECIMAL(15,2` | `) NULL` |
| `income_account` | `INT(11)` | `NULL` |

---

### Table: tblacc_tax_mappings

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `tax_id` | `INT(11)` | `NOT NULL` |
| `payment_account` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `deposit_to` | `INT(11)` | `NOT NULL DEFAULT 0` |

---

### Table: tblacc_transaction_bankings

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `date` | `DATE` | `NOT NULL` |
| `withdrawals` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `deposits` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `payee` | `VARCHAR(255)` | `NULL` |
| `description` | `TEXT` | `NULL` |
| `datecreated` | `DATETIME` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |

---

### Table: tblacc_transfers

**Module**: Accounting

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `transfer_funds_from` | `INT(11)` | `NOT NULL` |
| `transfer_funds_to` | `INT(11)` | `NOT NULL` |
| `transfer_amount` | `DECIMAL(15,2` | `) NULL` |
| `date` | `VARCHAR(45)` | `NULL` |
| `description` | `TEXT` | `NULL` |
| `datecreated` | `DATETIME` | `NULL` |
| `addedfrom` | `INT(11)` | `NULL` |

---

### Table: tblallowance_type

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `type_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `type_name` | `varchar(200)` | `NOT NULL` |
| `allowance_val` | `decimal(15,2` | `) NOT NULL` |
| `taxable` | `boolean` | `NOT NULL` |

---

### Table: tblapi_idempotency_keys

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `idem_key` | `VARCHAR(191)` | `NOT NULL` |
| `api_key` | `VARCHAR(255)` | `NOT NULL DEFAULT ''` |
| `method` | `VARCHAR(10)` | `NOT NULL DEFAULT 'post'` |
| `endpoint` | `VARCHAR(255)` | `NOT NULL DEFAULT ''` |
| `response_code` | `INT(11)` | `NOT NULL DEFAULT 200` |
| `response_body` | `LONGTEXT` | `NULL` |
| `created_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblapi_limit

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `uri` | `VARCHAR(255)` | `NOT NULL` |
| `class` | `VARCHAR(255)` | `NOT NULL` |
| `method` | `VARCHAR(255)` | `NOT NULL` |
| `ip_address` | `VARCHAR(45)` | `NOT NULL` |
| `time` | `INT(11)` | `NOT NULL` |

---

### Table: tblapi_security_log

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `event_type` | `VARCHAR(50)` | `NOT NULL COMMENT "sql_injection_attempt` |
| `severity` | `ENUM(` | `"low", "medium", "high", "critical") NOT NULL DEFAULT "medium"` |
| `ip_address` | `VARCHAR(45)` | `NOT NULL` |
| `user_api_id` | `INT(11)` | `UNSIGNED NULL COMMENT "Reference to user_api.id if authenticated"` |
| `endpoint` | `VARCHAR(255)` | `NULL COMMENT "API endpoint accessed"` |
| `request_method` | `VARCHAR(10)` | `NULL COMMENT "GET` |
| `user_agent` | `TEXT` | `NULL` |
| `payload` | `TEXT` | `NULL COMMENT "Suspicious payload or relevant data"` |
| `created_at` | `DATETIME` | `NOT NULL` |
| `FOREIGN` | `KEY` | `(`user_api_id`) REFERENCES `user_api`(`id`) ON DELETE SET NULL ON UPDATE CASCADE` |

---

### Table: tblapi_usage_logs

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `user_api_id` | `INT(11)` | `UNSIGNED NOT NULL` |
| `api_key` | `VARCHAR(255)` | `NOT NULL` |
| `endpoint` | `VARCHAR(255)` | `NOT NULL` |
| `response_code` | `INT(11)` | `NOT NULL` |
| `response_time` | `DECIMAL(10,4` | `) NOT NULL DEFAULT 0.0000` |
| `timestamp` | `INT(11)` | `NOT NULL` |
| `ip_address` | `VARCHAR(45)` | `NOT NULL` |
| `user_agent` | `TEXT` | `NULL` |
| `rate_limit_checked` | `TINYINT(1)` | `NOT NULL DEFAULT 1` |
| `rate_limit_type` | `VARCHAR(50)` | `NULL` |
| `rate_limit_limit` | `INT(11)` | `NULL` |
| `rate_limit_current` | `INT(11)` | `NULL` |
| `rate_limit_exceeded` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |

---

### Table: tblapi_webhook_logs

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `webhook_id` | `INT(11)` | `UNSIGNED NOT NULL` |
| `event` | `VARCHAR(100)` | `NOT NULL` |
| `url` | `TEXT` | `NOT NULL` |
| `payload` | `LONGTEXT` | `NOT NULL COMMENT "JSON payload sent"` |
| `response_code` | `INT(11)` | `DEFAULT NULL` |
| `response_body` | `TEXT` | `DEFAULT NULL` |
| `error_message` | `TEXT` | `DEFAULT NULL` |
| `attempt_number` | `INT(11)` | `DEFAULT 1` |
| `status` | `ENUM(` | `"pending", "success", "failed") DEFAULT "pending"` |
| `triggered_at` | `DATETIME` | `DEFAULT CURRENT_TIMESTAMP` |

---

### Table: tblapi_webhook_queue

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `BIGINT` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `webhook_id` | `INT(11)` | `UNSIGNED NOT NULL` |
| `event` | `VARCHAR(100)` | `NOT NULL` |
| `payload` | `LONGTEXT` | `NOT NULL` |
| `status` | `ENUM(` | `'pending','processing','delivered','failed') NOT NULL DEFAULT 'pending'` |
| `attempts` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `max_attempts` | `INT(11)` | `NOT NULL DEFAULT 3` |
| `next_attempt_at` | `DATETIME` | `NOT NULL` |
| `claim_token` | `VARCHAR(64)` | `DEFAULT NULL` |
| `claimed_at` | `DATETIME` | `DEFAULT NULL` |
| `created_at` | `DATETIME` | `NOT NULL` |
| `completed_at` | `DATETIME` | `DEFAULT NULL` |
| `last_error` | `TEXT` | `DEFAULT NULL` |

---

### Table: tblapi_webhooks

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `url` | `TEXT` | `NOT NULL` |
| `events` | `TEXT` | `NOT NULL COMMENT "Comma-separated list of events"` |
| `secret` | `VARCHAR(255)` | `DEFAULT NULL COMMENT "Secret for webhook signature"` |
| `active` | `TINYINT(1)` | `DEFAULT 1` |
| `headers` | `TEXT` | `DEFAULT NULL COMMENT "JSON object of custom headers"` |
| `timeout` | `INT(11)` | `DEFAULT 30 COMMENT "Request timeout in seconds"` |
| `retry_count` | `INT(11)` | `DEFAULT 3 COMMENT "Number of retries on failure"` |
| `last_triggered` | `DATETIME` | `DEFAULT NULL` |
| `success_count` | `INT(11)` | `DEFAULT 0` |
| `failure_count` | `INT(11)` | `DEFAULT 0` |
| `date_created` | `DATETIME` | `DEFAULT CURRENT_TIMESTAMP` |
| `date_updated` | `DATETIME` | `DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |

---

### Table: tblasset_audit_log

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NULL` |
| `action` | `VARCHAR(50)` | `NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `old_values` | `TEXT` | `NULL` |
| `new_values` | `TEXT` | `NULL` |
| `performed_by` | `INT(11)` | `NULL` |
| `ip_address` | `VARCHAR(45)` | `NULL` |
| `user_agent` | `VARCHAR(255)` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |

---

### Table: tblasset_checkouts

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NOT NULL` |
| `checked_out_to` | `INT(11)` | `NOT NULL` |
| `checked_out_to_type` | `ENUM(` | `"staff", "client", "contact") DEFAULT "staff"` |
| `checkout_date` | `DATETIME` | `NOT NULL` |
| `expected_return_date` | `DATE` | `NULL` |
| `actual_return_date` | `DATETIME` | `NULL` |
| `checkout_notes` | `TEXT` | `NULL` |
| `checkin_notes` | `TEXT` | `NULL` |
| `checkout_condition` | `VARCHAR(50)` | `DEFAULT "good"` |
| `checkin_condition` | `VARCHAR(50)` | `NULL` |
| `checkout_by` | `INT(11)` | `NOT NULL` |
| `checkin_by` | `INT(11)` | `NULL` |
| `quantity` | `INT(11)` | `DEFAULT 1` |
| `status` | `ENUM(` | `"checked_out", "returned", "overdue") DEFAULT "checked_out"` |
| `project_id` | `INT(11)` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |
| `updated_at` | `DATETIME` | `NULL` |

---

### Table: tblasset_custom_field_values

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NOT NULL` |
| `field_id` | `INT(11)` | `NOT NULL` |
| `field_value` | `TEXT` | `NULL` |

---

### Table: tblasset_custom_fields

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `field_name` | `VARCHAR(100)` | `NOT NULL` |
| `field_slug` | `VARCHAR(100)` | `NOT NULL` |
| `field_type` | `ENUM(` | `"text", "textarea", "number", "date", "select", "checkbox", "url") DEFAULT "text"` |
| `field_options` | `TEXT` | `NULL` |
| `required` | `TINYINT(1)` | `DEFAULT 0` |
| `show_on_table` | `TINYINT(1)` | `DEFAULT 0` |
| `field_order` | `INT(11)` | `DEFAULT 0` |
| `active` | `TINYINT(1)` | `DEFAULT 1` |
| `applies_to_groups` | `TEXT` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |

---

### Table: tblasset_depreciation_schedule

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NOT NULL` |
| `period_start` | `DATE` | `NOT NULL` |
| `period_end` | `DATE` | `NOT NULL` |
| `opening_value` | `DECIMAL(15,2` | `) NOT NULL` |
| `depreciation_amount` | `DECIMAL(15,2` | `) NOT NULL` |
| `closing_value` | `DECIMAL(15,2` | `) NOT NULL` |
| `created_at` | `DATETIME` | `NULL` |

---

### Table: tblasset_expenses

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NOT NULL` |
| `expense_id` | `INT(11)` | `NULL` |
| `expense_type` | `VARCHAR(50)` | `NOT NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL` |
| `date` | `DATE` | `NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `receipt` | `VARCHAR(255)` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |
| `created_by` | `INT(11)` | `NULL` |

---

### Table: tblasset_import_history

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `filename` | `VARCHAR(255)` | `NOT NULL` |
| `total_rows` | `INT(11)` | `DEFAULT 0` |
| `imported_rows` | `INT(11)` | `DEFAULT 0` |
| `failed_rows` | `INT(11)` | `DEFAULT 0` |
| `errors` | `TEXT` | `NULL` |
| `imported_by` | `INT(11)` | `NOT NULL` |
| `created_at` | `DATETIME` | `NULL` |

---

### Table: tblasset_location

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `location_id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `location` | `VARCHAR(255)` | `NOT NULL` |

---

### Table: tblasset_maintenance

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NOT NULL` |
| `maintenance_type` | `VARCHAR(50)` | `NOT NULL` |
| `title` | `VARCHAR(255)` | `NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `scheduled_date` | `DATE` | `NOT NULL` |
| `completed_date` | `DATE` | `NULL` |
| `cost` | `DECIMAL(15,2` | `) NULL DEFAULT 0` |
| `performed_by` | `INT(11)` | `NULL` |
| `vendor_name` | `VARCHAR(255)` | `NULL` |
| `vendor_contact` | `VARCHAR(100)` | `NULL` |
| `status` | `ENUM(` | `"scheduled", "in_progress", "completed", "cancelled", "overdue") DEFAULT "scheduled"` |
| `is_recurring` | `TINYINT(1)` | `DEFAULT 0` |
| `recurring_interval` | `INT(11)` | `NULL` |
| `recurring_unit` | `ENUM(` | `"days", "weeks", "months", "years") NULL` |
| `notes` | `TEXT` | `NULL` |
| `attachments` | `TEXT` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |
| `updated_at` | `DATETIME` | `NULL` |
| `created_by` | `INT(11)` | `NULL` |

---

### Table: tblasset_notification_settings

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `notification_type` | `VARCHAR(50)` | `NOT NULL` |
| `enabled` | `TINYINT(1)` | `DEFAULT 1` |
| `email_enabled` | `TINYINT(1)` | `DEFAULT 1` |
| `days_before` | `INT(11)` | `DEFAULT 7` |
| `recipients` | `TEXT` | `NULL` |

---

### Table: tblasset_notifications

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `type` | `VARCHAR(50)` | `NOT NULL` |
| `asset_id` | `INT(11)` | `NULL` |
| `staff_id` | `INT(11)` | `NULL` |
| `title` | `VARCHAR(255)` | `NOT NULL` |
| `message` | `TEXT` | `NULL` |
| `link` | `VARCHAR(500)` | `NULL` |
| `is_read` | `TINYINT(1)` | `DEFAULT 0` |
| `read_at` | `DATETIME` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |

---

### Table: tblasset_project_rel

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NOT NULL` |
| `project_id` | `INT(11)` | `NOT NULL` |
| `assigned_date` | `DATE` | `NULL` |
| `removed_date` | `DATE` | `NULL` |
| `quantity` | `INT(11)` | `DEFAULT 1` |
| `notes` | `TEXT` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |
| `created_by` | `INT(11)` | `NULL` |

---

### Table: tblasset_reservations

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NOT NULL` |
| `reserved_by` | `INT(11)` | `NOT NULL` |
| `reserved_by_type` | `ENUM(` | `"staff", "client", "contact") DEFAULT "staff"` |
| `reservation_start` | `DATETIME` | `NOT NULL` |
| `reservation_end` | `DATETIME` | `NOT NULL` |
| `purpose` | `TEXT` | `NULL` |
| `status` | `ENUM(` | `"pending", "approved", "rejected", "cancelled", "completed") DEFAULT "pending"` |
| `approved_by` | `INT(11)` | `NULL` |
| `approved_at` | `DATETIME` | `NULL` |
| `quantity` | `INT(11)` | `DEFAULT 1` |
| `project_id` | `INT(11)` | `NULL` |
| `notes` | `TEXT` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |
| `updated_at` | `DATETIME` | `NULL` |

---

### Table: tblasset_transfers

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `asset_id` | `INT(11)` | `NOT NULL` |
| `from_location` | `INT(11)` | `NULL` |
| `to_location` | `INT(11)` | `NOT NULL` |
| `from_department` | `INT(11)` | `NULL` |
| `to_department` | `INT(11)` | `NULL` |
| `quantity` | `INT(11)` | `DEFAULT 1` |
| `transfer_date` | `DATETIME` | `NOT NULL` |
| `reason` | `TEXT` | `NULL` |
| `transferred_by` | `INT(11)` | `NOT NULL` |
| `received_by` | `INT(11)` | `NULL` |
| `received_at` | `DATETIME` | `NULL` |
| `status` | `ENUM(` | `"pending", "in_transit", "completed", "cancelled") DEFAULT "pending"` |
| `notes` | `TEXT` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |

---

### Table: tblasset_unit

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `unit_id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `unit_name` | `VARCHAR(100)` | `NOT NULL` |

---

### Table: tblasset_webhook_logs

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `webhook_id` | `INT(11)` | `NOT NULL` |
| `event` | `VARCHAR(100)` | `NOT NULL` |
| `payload` | `TEXT` | `NULL` |
| `response_code` | `INT(11)` | `NULL` |
| `response_body` | `TEXT` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |
| `execution_time` | `FLOAT` | `NULL` |

---

### Table: tblasset_webhooks

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `url` | `VARCHAR(500)` | `NOT NULL` |
| `secret_key` | `VARCHAR(255)` | `NULL` |
| `events` | `TEXT` | `NOT NULL` |
| `active` | `TINYINT(1)` | `DEFAULT 1` |
| `headers` | `TEXT` | `NULL` |
| `created_at` | `DATETIME` | `NULL` |
| `updated_at` | `DATETIME` | `NULL` |
| `last_triggered` | `DATETIME` | `NULL` |
| `last_response_code` | `INT(11)` | `NULL` |
| `failure_count` | `INT(11)` | `DEFAULT 0` |

---

### Table: tblassets

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `assets_code` | `VARCHAR(100)` | `NOT NULL` |
| `assets_name` | `VARCHAR(255)` | `NOT NULL` |
| `amount` | `INT(11)` | `NOT NULL DEFAULT 1` |
| `unit` | `INT(11)` | `NOT NULL` |
| `series` | `VARCHAR(200)` | `NULL` |
| `asset_group` | `INT(11)` | `NULL` |
| `asset_location` | `INT(11)` | `NULL` |
| `department` | `INT(11)` | `NULL` |
| `date_buy` | `DATE` | `NOT NULL` |
| `warranty_period` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `unit_price` | `DECIMAL(15,2` | `) NOT NULL DEFAULT 0` |
| `depreciation` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `supplier_name` | `VARCHAR(255)` | `NULL` |
| `supplier_address` | `VARCHAR(255)` | `NULL` |
| `supplier_phone` | `VARCHAR(50)` | `NULL` |
| `description` | `TEXT` | `NULL` |
| `belongs_to` | `TEXT` | `NULL` |
| `visible_to_client` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `asset_image` | `VARCHAR(200)` | `NULL` |
| `barcode` | `VARCHAR(100)` | `NULL` |
| `qr_code` | `VARCHAR(100)` | `NULL` |
| `manufacturer` | `VARCHAR(255)` | `NULL` |
| `model_number` | `VARCHAR(100)` | `NULL` |
| `expected_end_of_life` | `DATE` | `NULL` |
| `insurance_policy` | `VARCHAR(100)` | `NULL` |
| `insurance_expiry` | `DATE` | `NULL` |
| `last_maintenance_date` | `DATE` | `NULL` |
| `next_maintenance_date` | `DATE` | `NULL` |
| `maintenance_interval_days` | `INT(11)` | `NULL DEFAULT 0` |
| `status` | `INT(11)` | `NOT NULL DEFAULT 1` |
| `total_allocation` | `INT(11)` | `NULL DEFAULT 0` |
| `total_lost` | `INT(11)` | `NULL DEFAULT 0` |
| `total_liquidation` | `INT(11)` | `NULL DEFAULT 0` |
| `total_damages` | `INT(11)` | `NULL DEFAULT 0` |
| `total_warranty` | `INT(11)` | `NULL DEFAULT 0` |
| `created_at` | `DATETIME` | `NULL` |
| `updated_at` | `DATETIME` | `NULL` |
| `created_by` | `INT(11)` | `NULL` |
| `updated_by` | `INT(11)` | `NULL` |

---

### Table: tblassets_acction_1

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `acction_code` | `VARCHAR(100)` | `NOT NULL` |
| `assets` | `INT(11)` | `NOT NULL` |
| `acction_from` | `INT(11)` | `NOT NULL` |
| `acction_to` | `INT(11)` | `NOT NULL` |
| `acction_to_type` | `ENUM(` | `"staff", "client", "contact") NOT NULL DEFAULT "staff"` |
| `amount` | `INT(11)` | `NOT NULL` |
| `time_acction` | `DATETIME` | `NOT NULL` |
| `asset_location` | `VARCHAR(255)` | `NULL` |
| `acction_location` | `VARCHAR(255)` | `NOT NULL` |
| `acction_reason` | `TEXT` | `NULL` |
| `type` | `VARCHAR(50)` | `NOT NULL` |

---

### Table: tblassets_acction_2

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `acction_code` | `VARCHAR(100)` | `NOT NULL` |
| `assets` | `INT(11)` | `NOT NULL` |
| `acction_from` | `INT(11)` | `NOT NULL` |
| `amount` | `INT(11)` | `NOT NULL` |
| `cost` | `DECIMAL(15,2` | `) NULL` |
| `time_acction` | `DATETIME` | `NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `type` | `VARCHAR(50)` | `NOT NULL` |

---

### Table: tblassets_group

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `group_id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `group_name` | `VARCHAR(100)` | `NOT NULL` |

---

### Table: tblbooking

**Module**: Staff Outsourcing

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `purpose` | `VARCHAR(255)` | `NOT NULL` |
| `orderer` | `INT(11)` | `NOT NULL` |
| `resource_group` | `INT(11)` | `NOT NULL` |
| `resource` | `INT(11)` | `NOT NULL` |
| `start_time` | `DATETIME` | `NOT NULL` |
| `end_time` | `DATETIME` | `NOT NULL` |
| `status` | `INT(11)` | `NOT NULL DEFAULT "1"` |
| `description` | `TEXT` | `NULL` |

---

