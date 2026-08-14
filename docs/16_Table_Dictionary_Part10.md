> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary - Part 10

> [!NOTE]
> This is Part 10 of the document. [Back to Part 1](16_Table_Dictionary.md)

### Table: tblprovince_city

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `province_code` | `varchar(45)` | `NOT NULL` |
| `province_name` | `VARCHAR(200)` | `NOT NULL` |

---

### Table: tblpur_activity_log

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `rel_id` | `INT(11)` | `NOT NULL` |
| `rel_type` | `VARCHAR(45)` | `NOT NULL` |
| `staffid` | `INT(11)` | `NULL` |
| `date` | `DATETIME` | `NULL` |
| `note` | `TEXT` | `NULL` |

---

### Table: tblpur_approval_details

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `rel_id` | `INT(11)` | `NOT NULL` |
| `rel_type` | `VARCHAR(45)` | `NOT NULL` |
| `staffid` | `VARCHAR(45)` | `NULL` |
| `approve` | `VARCHAR(45)` | `NULL` |
| `note` | `TEXT` | `NULL` |
| `date` | `DATETIME` | `NULL` |
| `approve_action` | `VARCHAR(255)` | `NULL` |
| `reject_action` | `VARCHAR(255)` | `NULL` |
| `approve_value` | `VARCHAR(255)` | `NULL` |
| `reject_value` | `VARCHAR(255)` | `NULL` |
| `staff_approve` | `INT(11)` | `NULL` |
| `action` | `VARCHAR(45)` | `NULL` |

---

### Table: tblpur_approval_setting

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `related` | `VARCHAR(255)` | `NOT NULL` |
| `setting` | `LONGTEXT` | `NOT NULL` |

---

### Table: tblpur_comments

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `content` | `MEDIUMTEXT` | `NULL` |
| `rel_type` | `VARCHAR(50)` | `NOT NULL` |
| `rel_id` | `INT(11)` | `NULL` |
| `staffid` | `INT(11)` | `NOT NULL` |
| `dateadded` | `DATETIME` | `NOT NULL` |

---

### Table: tblpur_contacts

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `userid` | `int(11)` | `NOT NULL` |
| `is_primary` | `int(11)` | `NOT NULL DEFAULT '1'` |
| `firstname` | `varchar(191)` | `NOT NULL` |
| `lastname` | `VARCHAR(191)` | `NOT NULL` |
| `email` | `varchar(100)` | `NOT NULL` |
| `phonenumber` | `varchar(100)` | `NOT NULL` |
| `title` | `varchar(100)` | `NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `password` | `varchar(255)` | `NULL` |
| `new_pass_key` | `varchar(32)` | `NULL` |
| `new_pass_key_requested` | `datetime` | `NULL` |
| `email_verified_at` | `datetime` | `NULL` |
| `email_verification_key` | `varchar(32)` | `NULL` |
| `email_verification_sent_at` | `DATETIME` | `NULL` |
| `last_ip` | `varchar(40)` | `NULL` |
| `last_login` | `DATETIME` | `NULL` |
| `last_password_change` | `DATETIME` | `NULL` |
| `active` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |
| `profile_image` | `varchar(191)` | `NULL` |
| `direction` | `varchar(3)` | `NULL` |
| `invoice_emails` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |
| `estimate_emails` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |
| `credit_note_emails` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |
| `contract_emails` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |
| `task_emails` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |
| `project_emails` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |
| `ticket_emails` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |

---

### Table: tblpur_contracts

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `contract_number` | `varchar(200)` | `NOT NULL` |
| `contract_name` | `varchar(200)` | `NOT NULL` |
| `content` | `LONGTEXT` | `NULL` |
| `vendor` | `INT(11)` | `NOT NULL` |
| `pur_order` | `INT(11)` | `NOT NULL` |
| `contract_value` | `DECIMAL(15,0` | `) NOT NULL` |
| `start_date` | `date` | `NOT NULL` |
| `end_date` | `date` | `NULL` |
| `buyer` | `INT(11)` | `NULL` |
| `add_from` | `INT(11)` | `NOT NULL` |
| `signed` | `INT(32)` | `NOT NULL DEFAULT '0'` |
| `note` | `LONGTEXT` | `NULL` |

---

### Table: tblpur_debit_notes

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `vendorid` | `INT(11)` | `NULL` |
| `deleted_vendor_name` | `VARCHAR(100)` | `NULL` |
| `number` | `INT(11)` | `NULL` |
| `prefix` | `varchar(50)` | `NULL` |
| `number_format` | `INT(11)` | `NULL` |
| `datecreated` | `datetime` | `NULL` |
| `date` | `date` | `NULL` |
| `adminnote` | `text` | `NULL` |
| `terms` | `text` | `NULL` |
| `vendornote` | `text` | `NULL` |
| `currency` | `INT(11)` | `NULL` |
| `subtotal` | `decimal(15,2` | `) NULL` |
| `total_tax` | `decimal(15,2` | `) NULL` |
| `total` | `decimal(15,2` | `) NULL` |
| `adjustment` | `decimal(15,2` | `) NULL` |
| `addedfrom` | `int(11)` | `NULL` |
| `status` | `int(11)` | `NULL` |
| `discount_percent` | `decimal(15,2` | `) NULL` |
| `discount_total` | `decimal(15,2` | `) NULL` |
| `discount_type` | `varchar(30)` | `NULL` |
| `billing_street` | `varchar(200)` | `NULL` |
| `billing_city` | `varchar(100)` | `NULL` |
| `billing_state` | `varchar(100)` | `NULL` |
| `billing_zip` | `varchar(100)` | `NULL` |
| `billing_country` | `int(11)` | `NULL` |
| `shipping_street` | `varchar(200)` | `NULL` |
| `shipping_city` | `varchar(100)` | `NULL` |
| `shipping_state` | `varchar(100)` | `NULL` |
| `shipping_zip` | `varchar(100)` | `NULL` |
| `shipping_country` | `int(11)` | `NULL` |
| `include_shipping` | `tinyint(1)` | `NULL` |
| `show_shipping_on_debit_note` | `tinyint(1)` | `NULL` |
| `show_quantity_as` | `int(11)` | `NULL` |
| `reference_no` | `varchar(100)` | `NULL` |

---

### Table: tblpur_debits

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `invoice_id` | `INT(11)` | `NULL` |
| `debit_id` | `INT(11)` | `NULL` |
| `staff_id` | `INT(11)` | `NULL` |
| `date_applied` | `datetime` | `NULL` |
| `date` | `date` | `NULL` |
| `amount` | `decimal(15,2` | `) NULL` |

---

### Table: tblpur_debits_refunds

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `debit_note_id` | `INT(11)` | `NULL` |
| `staff_id` | `INT(11)` | `NULL` |
| `refunded_on` | `date` | `NULL` |
| `payment_mode` | `varchar(40)` | `NULL` |
| `note` | `text` | `NULL` |
| `amount` | `decimal(15,2` | `) NULL` |
| `created_at` | `datetime` | `NULL` |

---

### Table: tblpur_estimate_detail

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `pur_estimate` | `INT(11)` | `NOT NULL` |
| `item_code` | `VARCHAR(100)` | `NOT NULL` |
| `unit_id` | `INT(11)` | `NULL` |
| `unit_price` | `DECIMAL(15,0` | `) NULL` |
| `quantity` | `int(11)` | `NOT NULL` |
| `into_money` | `DECIMAL(15,0` | `) NULL` |
| `tax` | `text` | `NULL` |
| `total` | `DECIMAL(15,0` | `) NULL` |

---

### Table: tblpur_estimates

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `sent` | `TINYINT(1)` | `NOT NULL DEFAULT '0'` |
| `datesend` | `DATETIME` | `NULL` |
| `vendor` | `INT(11)` | `NOT NULL` |
| `deleted_vendor_name` | `VARCHAR(100)` | `NULL` |
| `pur_request` | `INT(11)` | `NOT NULL` |
| `number` | `INT(11)` | `NOT NULL` |
| `prefix` | `varchar(50)` | `NULL` |
| `number_format` | `INT(11)` | `NOT NULL DEFAULT '0'` |
| `hash` | `VARCHAR(32)` | `NULL` |
| `datecreated` | `DATETIME` | `NOT NULL` |
| `date` | `DATE` | `NOT NULL` |
| `expirydate` | `DATE` | `NULL` |
| `currency` | `INT(11)` | `NOT NULL` |
| `subtotal` | `DECIMAL(15,2` | `) NOT NULL` |
| `total_tax` | `DECIMAL(15,2` | `) NOT NULL` |
| `total` | `DECIMAL(15,2` | `) NOT NULL` |
| `adjustment` | `DECIMAL(15,2` | `) NULL` |
| `addedfrom` | `INT(11)` | `NOT NULL` |
| `status` | `INT(11)` | `NOT NULL DEFAULT '1'` |
| `vendornote` | `TEXT` | `NULL` |
| `adminnote` | `TEXT` | `NULL` |
| `discount_percent` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount_type` | `VARCHAR(30)` | `NULL` |
| `invoiceid` | `INT(11)` | `NULL` |
| `invoiced_date` | `DATETIME` | `NULL` |
| `terms` | `TEXT` | `NULL` |
| `reference_no` | `VARCHAR(100)` | `NULL` |
| `buyer` | `INT(11)` | `NOT NULL DEFAULT '0'` |
| `billing_street` | `VARCHAR(200)` | `NULL` |
| `billing_city` | `VARCHAR(100)` | `NULL` |
| `billing_state` | `VARCHAR(100)` | `NULL` |
| `billing_zip` | `VARCHAR(100)` | `NULL` |
| `billing_country` | `INT(11)` | `NULL` |
| `shipping_street` | `VARCHAR(200)` | `NULL` |
| `shipping_city` | `VARCHAR(100)` | `NULL` |
| `shipping_state` | `VARCHAR(100)` | `NULL` |
| `shipping_zip` | `VARCHAR(100)` | `NULL` |
| `shipping_country` | `INT(11)` | `NULL` |
| `include_shipping` | `TINYINT(1)` | `NOT NULL` |
| `show_shipping_on_estimate` | `TINYINT(1)` | `NOT NULL DEFAULT '1'` |
| `show_quantity_as` | `INT(11)` | `NOT NULL DEFAULT '1'` |
| `pipeline_order` | `INT(11)` | `NOT NULL DEFAULT '0'` |
| `is_expiry_notified` | `INT(11)` | `NOT NULL DEFAULT '0'` |
| `acceptance_firstname` | `VARCHAR(50)` | `NULL` |
| `acceptance_lastname` | `VARCHAR(50)` | `NULL` |
| `acceptance_email` | `VARCHAR(100)` | `NULL` |
| `acceptance_date` | `DATETIME` | `NULL` |
| `acceptance_ip` | `VARCHAR(40)` | `NULL` |
| `signature` | `VARCHAR(40)` | `NULL` |

---

### Table: tblpur_faf_requests

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `vendor_id` | `INT(11)` | `NULL` |
| `reference_number` | `TEXT` | `NULL` |
| `genrated_po_number` | `TEXT` | `NULL` |
| `amount_request` | `decimal(15,2` | `) NULL` |
| `department` | `INT(11)` | `NULL` |
| `requestor` | `INT(11)` | `NULL` |
| `summary` | `TEXT` | `NULL` |
| `approval_setting` | `LONGTEXT` | `NULL` |
| `approve_status` | `INT(1)` | `NOT NULL DEFAULT '1'` |
| `requestor_signed_at` | `datetime` | `NULL` |
| `created_at` | `datetime` | `NULL` |
| `created_by` | `INT(11)` | `NULL` |

---

### Table: tblpur_invoice_details

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `pur_invoice` | `INT(11)` | `NOT NULL` |
| `item_code` | `VARCHAR(100)` | `NULL` |
| `description` | `TEXT` | `NULL` |
| `unit_id` | `INT(11)` | `NULL` |
| `unit_price` | `DECIMAL(15,2` | `) NULL` |
| `quantity` | `DECIMAL(15,2` | `) NULL` |
| `into_money` | `DECIMAL(15,2` | `) NULL` |
| `tax` | `TEXT` | `NULL` |
| `total` | `DECIMAL(15,2` | `) NULL` |
| `discount_percent` | `DECIMAL(15,2` | `) NULL` |
| `discount_money` | `DECIMAL(15,2` | `) NULL` |
| `total_money` | `DECIMAL(15,2` | `) NULL` |
| `tax_value` | `DECIMAL(15,2` | `) NULL` |
| `tax_rate` | `TEXT` | `NULL` |
| `tax_name` | `TEXT` | `NULL` |
| `item_name` | `TEXT` | `NULL` |

---

### Table: tblpur_order_detail

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `pur_order` | `INT(11)` | `NOT NULL` |
| `item_code` | `VARCHAR(100)` | `NOT NULL` |
| `unit_id` | `INT(11)` | `NULL` |
| `unit_price` | `DECIMAL(15,0` | `) NULL` |
| `quantity` | `int(11)` | `NOT NULL` |
| `into_money` | `DECIMAL(15,0` | `) NULL` |
| `tax` | `text` | `NULL` |
| `total` | `DECIMAL(15,0` | `) NULL` |
| `discount_money` | `DECIMAL(15,0` | `) NULL` |
| `total_money` | `DECIMAL(15,0` | `) NULL` |

---

### Table: tblpur_order_payment

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `pur_order` | `int(11)` | `NOT NULL` |
| `amount` | `DECIMAL(15,2` | `) NOT NULL` |
| `paymentmode` | `LONGTEXT` | `NULL` |
| `date` | `DATE` | `NOT NULL` |
| `daterecorded` | `DATETIME` | `NOT NULL` |
| `note` | `TEXT` | `NOT NULL` |
| `transactionid` | `MEDIUMTEXT` | `NULL` |

---

### Table: tblpur_orders

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `pur_order_name` | `varchar(100)` | `NOT NULL` |
| `vendor` | `INT(11)` | `NOT NULL` |
| `estimate` | `INT(11)` | `NOT NULL` |
| `pur_order_number` | `VARCHAR(30)` | `NOT NULL` |
| `order_date` | `date` | `NOT NULL` |
| `status` | `INT(32)` | `NOT NULL DEFAULT '1'` |
| `approve_status` | `INT(32)` | `NOT NULL DEFAULT '1'` |
| `datecreated` | `DATETIME` | `NOT NULL` |
| `days_owed` | `INT(11)` | `NOT NULL` |
| `delivery_date` | `DATE` | `NULL` |
| `subtotal` | `DECIMAL(15,2` | `) NOT NULL` |
| `total_tax` | `DECIMAL(15,2` | `) NOT NULL` |
| `total` | `DECIMAL(15,2` | `) NOT NULL` |
| `addedfrom` | `INT(11)` | `NOT NULL` |
| `vendornote` | `TEXT` | `NULL` |
| `terms` | `TEXT` | `NULL` |
| `discount_percent` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount_type` | `VARCHAR(30)` | `NULL` |
| `buyer` | `INT(11)` | `NOT NULL DEFAULT '0'` |
| `status_goods` | `INT(11)` | `NOT NULL DEFAULT '0'` |

---

### Table: tblpur_request

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `pur_rq_code` | `VARCHAR(45)` | `NOT NULL` |
| `pur_rq_name` | `VARCHAR(100)` | `NOT NULL` |
| `rq_description` | `TEXT` | `NULL` |
| `requester` | `INT(11)` | `NOT NULL` |
| `department` | `INT(11)` | `NOT NULL` |
| `request_date` | `DATETIME` | `NOT NULL` |
| `status` | `INT(11)` | `NULL` |
| `status_goods` | `INT(11)` | `NOT NULL DEFAULT "0"` |

---

### Table: tblpur_request_detail

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `prd_id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `pur_request` | `INT(11)` | `NOT NULL` |
| `item_code` | `VARCHAR(100)` | `NOT NULL` |
| `unit_id` | `INT(11)` | `NULL` |
| `unit_price` | `DECIMAL(15,0` | `) NULL` |
| `quantity` | `int(11)` | `NOT NULL` |
| `into_money` | `DECIMAL(15,0` | `) NULL` |
| `inventory_quantity` | `int(11)` | `NOT NULL DEFAULT "0"` |

---

### Table: tblpur_unit

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `unit_id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `unit_name` | `VARCHAR(100)` | `NOT NULL` |

---

### Table: tblpur_vendor

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `userid` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `company` | `varchar(200)` | `NULL` |
| `vat` | `varchar(200)` | `NULL` |
| `phonenumber` | `varchar(30)` | `NULL` |
| `country` | `int(11)` | `NOT NULL DEFAULT '0'` |
| `city` | `varchar(100)` | `NULL` |
| `zip` | `varchar(15)` | `NULL` |
| `state` | `varchar(50)` | `NULL` |
| `address` | `varchar(100)` | `NULL` |
| `website` | `varchar(150)` | `NULL` |
| `datecreated` | `DATETIME` | `NOT NULL` |
| `active` | `INT(11)` | `NOT NULL DEFAULT '1'` |
| `leadid` | `INT(11)` | `NULL` |
| `billing_street` | `varchar(200)` | `NULL` |
| `billing_city` | `varchar(100)` | `NULL` |
| `billing_state` | `varchar(100)` | `NULL` |
| `billing_zip` | `varchar(100)` | `NULL` |
| `billing_country` | `int(11)` | `NULL DEFAULT '0'` |
| `shipping_street` | `varchar(200)` | `NULL` |
| `shipping_city` | `varchar(100)` | `NULL` |
| `shipping_state` | `varchar(100)` | `NULL` |
| `shipping_zip` | `varchar(100)` | `NULL` |
| `shipping_country` | `int(11)` | `NULL DEFAULT '0'` |
| `longitude` | `varchar(191)` | `NULL` |
| `latitude` | `varchar(191)` | `NULL` |
| `default_language` | `varchar(40)` | `NULL` |
| `default_currency` | `INT(11)` | `NOT NULL DEFAULT '0'` |
| `show_primary_contact` | `INT(11)` | `NOT NULL DEFAULT '0'` |
| `stripe_id` | `varchar(40)` | `NULL` |
| `registration_confirmed` | `INT(11)` | `NOT NULL DEFAULT '1'` |
| `addedfrom` | `INT(11)` | `NOT NULL DEFAULT '0'` |

---

### Table: tblpur_vendor_admin

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `staff_id` | `INT(11)` | `NOT NULL` |
| `vendor_id` | `INT(11)` | `NOT NULL` |
| `date_assigned` | `DATETIME` | `NOT NULL` |

---

### Table: tblpur_vendor_cate

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `category_name` | `VARCHAR(255)` | `NULL` |
| `description` | `text` | `NULL` |

---

### Table: tblpur_vendor_items

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `vendor` | `int(11)` | `NOT NULL` |
| `group_items` | `int(11)` | `NULL` |
| `items` | `int(11)` | `NOT NULL` |
| `add_from` | `int(11)` | `NULL` |
| `datecreate` | `DATE` | `NULL` |

---

### Table: tblpurchase_option

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `option_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `option_name` | `varchar(200)` | `NOT NULL` |
| `option_val` | `longtext` | `NULL` |
| `auto` | `tinyint(1)` | `NULL` |

---

### Table: tblrec_activity_log

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `rel_id` | `int` | `NULL` |
| `rel_type` | `varchar(100)` | `NULL` |
| `description` | `mediumtext` | `NULL` |
| `additional_data` | `text` | `NULL` |
| `date` | `datetime` | `NULL` |
| `staffid` | `int(11)` | `NULL` |
| `full_name` | `varchar(100)` | `NULL` |

---

### Table: tblrec_applied_jobs

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `candidate_id` | `int` | `NULL` |
| `campaign_id` | `int` | `NULL` |
| `date_created` | `datetime` | `NULL` |
| `status` | `TEXT` | `NULL` |
| `activate` | `TEXT` | `NULL` |

---

### Table: tblrec_campaign

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `cp_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `campaign_code` | `varchar(200)` | `NOT NULL` |
| `campaign_name` | `varchar(200)` | `NOT NULL` |
| `cp_proposal` | `text` | `NULL` |
| `cp_position` | `int(11)` | `NOT NULL` |
| `cp_department` | `int(11)` | `NULL` |
| `cp_amount_recruiment` | `int(11)` | `NULL` |
| `cp_form_work` | `varchar(45)` | `NULL` |
| `cp_workplace` | `varchar(255)` | `NULL` |
| `cp_salary_from` | `DECIMAL(15,0` | `) NULL` |
| `cp_salary_to` | `DECIMAL(15,0` | `) NULL` |
| `cp_from_date` | `date` | `NULL` |
| `cp_to_date` | `date` | `NOT NULL` |
| `cp_reason_recruitment` | `text` | `NULL` |
| `cp_job_description` | `text` | `NULL` |
| `cp_manager` | `text` | `NULL` |
| `cp_follower` | `text` | `NULL` |
| `cp_ages_from` | `int(11)` | `NULL` |
| `cp_ages_to` | `int(11)` | `NULL` |
| `cp_gender` | `varchar(10)` | `NULL` |
| `cp_height` | `float` | `NULL` |
| `cp_weight` | `float` | `NULL` |
| `cp_literacy` | `varchar(200)` | `NULL` |
| `cp_experience` | `varchar(200)` | `NULL` |
| `cp_add_from` | `int(11)` | `NOT NULL` |
| `cp_date_add` | `date` | `NOT NULL` |
| `cp_status` | `int(11)` | `NOT NULL` |

---

### Table: tblrec_campaign_form_web

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `rec_campaign_id` | `int(11)` | `NOT NULL` |
| `form_type` | `int(11)` | `NULL` |
| `lead_source` | `varchar(10)` | `NULL` |
| `lead_status` | `varchar(10)` | `NULL` |
| `notify_ids_staff` | `text` | `NULL` |
| `notify_ids_roles` | `text` | `NULL` |
| `form_key` | `varchar(32)` | `NULL` |
| `notify_lead_imported` | `int(11)` | `NULL DEFAULT '1'` |
| `notify_type` | `varchar(20)` | `DEFAULT NULL` |
| `notify_ids` | `mediumtext` | `None` |
| `responsible` | `int(11)` | `NULL DEFAULT '0'` |
| `name` | `varchar(191)` | `NULL` |
| `form_data` | `mediumtext` | `None` |
| `recaptcha` | `int(11)` | `NULL DEFAULT '0'` |
| `submit_btn_name` | `varchar(40)` | `DEFAULT NULL` |
| `success_submit_msg` | `text` | `None` |
| `language` | `varchar(40)` | `DEFAULT NULL` |
| `allow_duplicate` | `int(11)` | `NULL DEFAULT '1'` |
| `mark_public` | `int(11)` | `NULL DEFAULT '0'` |
| `track_duplicate_field` | `varchar(20)` | `DEFAULT NULL` |
| `track_duplicate_field_and` | `varchar(20)` | `DEFAULT NULL` |
| `create_task_on_duplicate` | `int(11)` | `NULL DEFAULT '0'` |

---

### Table: tblrec_candidate

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `rec_campaign` | `int(11)` | `NULL` |
| `candidate_code` | `varchar(200)` | `NOT NULL` |
| `candidate_name` | `varchar(200)` | `NOT NULL` |
| `birthday` | `date` | `NULL` |
| `gender` | `varchar(11)` | `NULL` |
| `birthplace` | `text` | `NULL` |
| `home_town` | `text` | `NULL` |
| `identification` | `varchar(45)` | `NULL` |
| `days_for_identity` | `date` | `NULL` |
| `place_of_issue` | `varchar(255)` | `NULL` |
| `marital_status` | `varchar(11)` | `NULL` |
| `nationality` | `varchar(100)` | `NULL` |
| `nation` | `varchar(100)` | `NOT NULL` |
| `religion` | `varchar(100)` | `NULL` |
| `height` | `float` | `NULL` |
| `weight` | `float` | `NULL` |
| `introduce_yourself` | `text` | `NULL` |
| `phonenumber` | `int(15)` | `NULL` |
| `email` | `text` | `NULL` |
| `skype` | `text` | `NULL` |
| `facebook` | `text` | `NULL` |
| `resident` | `text` | `NULL` |
| `current_accommodation` | `text` | `NULL` |
| `status` | `int(11)` | `NOT NULL` |

---

### Table: tblrec_cd_evaluation

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `criteria` | `int(11)` | `NOT NULL` |
| `rate_score` | `int(11)` | `NOT NULL` |
| `assessor` | `int(11)` | `NOT NULL` |
| `evaluation_date` | `datetime` | `NOT NULL` |
| `percent` | `int(11)` | `NOT NULL` |
| `candidate` | `int(11)` | `NOT NULL` |
| `feedback` | `TEXT` | `NOT NULL` |
| `group_criteria` | `int(11)` | `NOT NULL` |

---

### Table: tblrec_company

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `company_name` | `varchar(200)` | `NOT NULL` |
| `company_description` | `text` | `NULL` |
| `company_address` | `varchar(200)` | `NULL` |
| `company_industry` | `text` | `NULL` |

---

### Table: tblrec_criteria

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `criteria_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `criteria_type` | `varchar(45)` | `NOT NULL` |
| `criteria_title` | `varchar(200)` | `NOT NULL` |
| `group_criteria` | `int(11)` | `NULL` |
| `description` | `text` | `NULL` |
| `add_from` | `int(11)` | `NOT NULL` |
| `add_date` | `date` | `NULL` |
| `score_des1` | `text` | `NULL` |
| `score_des2` | `text` | `NULL` |
| `score_des3` | `text` | `NULL` |
| `score_des4` | `text` | `NULL` |
| `score_des5` | `text` | `NULL` |

---

### Table: tblrec_evaluation_form

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `form_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `form_name` | `varchar(200)` | `NOT NULL` |
| `position` | `int(11)` | `NULL` |
| `add_from` | `int(11)` | `NOT NULL` |
| `add_date` | `date` | `NULL` |

---

### Table: tblrec_interview

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `campaign` | `int(11)` | `NOT NULL` |
| `is_name` | `varchar(100)` | `NOT NULL` |
| `interview_day` | `varchar(200)` | `NOT NULL` |
| `from_time` | `text` | `NOT NULL` |
| `to_time` | `text` | `NOT NULL` |
| `from_hours` | `datetime` | `NULL` |
| `to_hours` | `datetime` | `NULL` |
| `interviewer` | `text` | `NOT NULL` |
| `added_from` | `int(11)` | `NOT NULL` |
| `added_date` | `date` | `NOT NULL` |

---

