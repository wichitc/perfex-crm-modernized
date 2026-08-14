> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary - Part 8

> [!NOTE]
> This is Part 8 of the document. [Back to Part 1](16_Table_Dictionary.md)

### Table: tblbooking_follower

**Module**: Staff Outsourcing

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `follower_id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `booking` | `INT(11)` | `NOT NULL` |
| `follower` | `INT(11)` | `NOT NULL` |

---

### Table: tblcd_care

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `candidate` | `int(11)` | `NOT NULL` |
| `care_time` | `datetime` | `NOT NULL` |
| `care_result` | `text` | `NOT NULL` |
| `description` | `text` | `NULL` |
| `add_from` | `int(11)` | `NOT NULL` |
| `add_time` | `datetime` | `NULL` |
| `type` | `varchar(45)` | `NOT NULL` |

---

### Table: tblcd_family_infor

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `fi_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `candidate` | `int(11)` | `NOT NULL` |
| `relationship` | `varchar(100)` | `NULL` |
| `name` | `varchar(200)` | `NULL` |
| `fi_birthday` | `date` | `NULL` |
| `job` | `varchar(200)` | `NULL` |
| `address` | `varchar(200)` | `NULL` |
| `phone` | `int(15)` | `NULL` |

---

### Table: tblcd_interview

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `in_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `candidate` | `int(11)` | `NOT NULL` |
| `interview` | `int(11)` | `NOT NULL` |

---

### Table: tblcd_literacy

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `li_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `candidate` | `int(11)` | `NOT NULL` |
| `literacy_from_date` | `date` | `NULL` |
| `literacy_to_date` | `date` | `NULL` |
| `diploma` | `varchar(200)` | `NULL` |
| `training_places` | `varchar(200)` | `NULL` |
| `specialized` | `varchar(200)` | `NULL` |
| `training_form` | `varchar(200)` | `NULL` |

---

### Table: tblcd_skill

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `candidate` | `int(11)` | `NOT NULL` |
| `skill_name` | `text` | `NULL` |
| `skill_description` | `text` | `NULL` |

---

### Table: tblcd_work_experience

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `we_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `candidate` | `int(11)` | `NOT NULL` |
| `from_date` | `date` | `NULL` |
| `to_date` | `date` | `NULL` |
| `company` | `varchar(200)` | `NULL` |
| `position` | `varchar(200)` | `NULL` |
| `contact_person` | `varchar(200)` | `NULL` |
| `salary` | `varchar(200)` | `NULL` |
| `reason_quitwork` | `varchar(200)` | `NULL` |
| `job_description` | `varchar(200)` | `NULL` |

---

### Table: tblcurrency_rate_logs

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `from_currency_id` | `int(11)` | `NULL` |
| `from_currency_name` | `VARCHAR(100)` | `NULL` |
| `from_currency_rate` | `decimal(15,6` | `) NOT NULL DEFAULT '0.000000'` |
| `to_currency_id` | `int(11)` | `NULL` |
| `to_currency_name` | `VARCHAR(100)` | `NULL` |
| `to_currency_rate` | `decimal(15,6` | `) NOT NULL DEFAULT '0.000000'` |
| `date` | `DATE` | `NULL` |

---

### Table: tblcurrency_rates

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `from_currency_id` | `int(11)` | `NULL` |
| `from_currency_name` | `VARCHAR(100)` | `NULL` |
| `from_currency_rate` | `decimal(15,6` | `) NOT NULL DEFAULT '0.000000'` |
| `to_currency_id` | `int(11)` | `NULL` |
| `to_currency_name` | `VARCHAR(100)` | `NULL` |
| `to_currency_rate` | `decimal(15,6` | `) NOT NULL DEFAULT '0.000000'` |
| `date_updated` | `datetime` | `NOT NULL DEFAULT CURRENT_TIMESTAMP` |

---

### Table: tblday_off

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `off_reason` | `varchar(255)` | `NOT NULL` |
| `off_type` | `varchar(100)` | `NOT NULL` |
| `break_date` | `date` | `NOT NULL` |
| `timekeeping` | `varchar(45)` | `NULL` |
| `department` | `int(11)` | `NULL DEFAULT '0'` |
| `position` | `int(11)` | `NULL DEFAULT '0'` |
| `add_from` | `int(11)` | `NOT NULL` |

---

### Table: tblgoods_delivery

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `rel_type` | `int(11)` | `NULL COMMENT 'type goods delivery'` |
| `rel_document` | `int(11)` | `NULL COMMENT 'document id of goods delivery'` |
| `customer_code` | `text` | `NULL` |
| `customer_name` | `varchar(100)` | `NULL` |
| `to_` | `varchar(100)` | `NULL` |
| `address` | `varchar(100)` | `NULL` |
| `description` | `text` | `NULL COMMENT 'the reason delivery'` |
| `staff_id` | `int(11)` | `NULL COMMENT 'salesman'` |
| `date_c` | `date` | `NULL` |
| `date_add` | `date` | `NULL` |
| `goods_delivery_code` | `varchar(100)` | `NULL COMMENT 'số chứng từ xuất kho'` |
| `approval` | `INT(11)` | `NULL DEFAULT 0 COMMENT 'status approval '` |
| `addedfrom` | `INT(11)` | `None` |

---

### Table: tblgoods_delivery_detail

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `goods_delivery_id` | `int(11)` | `NOT NULL` |
| `commodity_code` | `varchar(100)` | `NULL` |
| `commodity_name` | `text` | `NULL` |
| `warehouse_id` | `text` | `NULL` |
| `unit_id` | `text` | `NULL` |
| `quantities` | `text` | `NULL` |
| `unit_price` | `varchar(100)` | `NULL` |
| `note` | `text` | `NULL` |

---

### Table: tblgoods_delivery_invoices_pr_orders

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `rel_id` | `int(11)` | `NULL COMMENT 'goods_delivery_id'` |
| `rel_type` | `int(11)` | `NULL COMMENT 'invoice_id or purchase order id'` |
| `type` | `varchar(100)` | `NULL COMMENT'invoice` |

---

### Table: tblgoods_receipt

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `supplier_code` | `varchar(100)` | `NULL` |
| `supplier_name` | `text` | `NULL` |
| `deliver_name` | `text` | `NULL` |
| `buyer_id` | `int(11)` | `NULL` |
| `description` | `text` | `NULL` |
| `pr_order_id` | `int(11)` | `NULL COMMENT 'code puchase request agree'` |
| `date_c` | `date` | `NULL` |
| `date_add` | `date` | `NULL` |
| `goods_receipt_code` | `varchar(100)` | `NULL` |
| `total_tax_money` | `varchar(100)` | `NULL` |
| `total_goods_money` | `varchar(100)` | `NULL` |
| `value_of_inventory` | `varchar(100)` | `NULL` |
| `total_money` | `varchar(100)` | `NULL COMMENT 'total_money = total_tax_money +total_goods_money '` |

---

### Table: tblgoods_receipt_detail

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `goods_receipt_id` | `int(11)` | `NOT NULL` |
| `commodity_code` | `varchar(100)` | `NULL` |
| `commodity_name` | `text` | `NULL` |
| `warehouse_id` | `text` | `NULL` |
| `unit_id` | `text` | `NULL` |
| `quantities` | `text` | `NULL` |
| `unit_price` | `varchar(100)` | `NULL` |
| `tax` | `varchar(100)` | `NULL` |
| `tax_money` | `varchar(100)` | `NULL` |
| `goods_money` | `varchar(100)` | `NULL` |
| `note` | `text` | `NULL` |

---

### Table: tblgoods_transaction_detail

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `goods_receipt_id` | `int(11)` | `NULL COMMENT 'id_goods_receipt_id or goods_delivery_id'` |
| `goods_id` | `int(11)` | `NOT NULL COMMENT ' is id commodity'` |
| `quantity` | `varchar(100)` | `NULL` |
| `date_add` | `DATETIME` | `NULL` |
| `commodity_id` | `int(11)` | `NOT NULL` |
| `warehouse_id` | `int(11)` | `NOT NULL` |
| `note` | `text` | `null` |
| `status` | `int(2)` | `NULL COMMENT '1:Goods receipt note 2:Goods delivery note'` |

---

### Table: tblhrm_assets

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(255)` | `NOT NULL` |
| `asset_code` | `varchar(100)` | `NULL` |
| `category` | `varchar(100)` | `NULL` |
| `assigned_to` | `int(11)` | `NULL` |
| `assigned_date` | `date` | `NULL` |
| `condition` | `varchar(50)` | `NULL` |
| `notes` | `text` | `NULL` |

---

### Table: tblhrm_contract_templates

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(255)` | `NOT NULL` |
| `contract_type_id` | `int(11)` | `NULL` |
| `content` | `LONGTEXT` | `NULL` |
| `merge_fields` | `LONGTEXT` | `NULL` |

---

### Table: tblhrm_deduction_type

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(191)` | `NOT NULL` |
| `calc_type` | `varchar(20)` | `NOT NULL DEFAULT 'fixed'` |
| `amount` | `decimal(15,2` | `) NOT NULL DEFAULT 0` |
| `taxable` | `tinyint(1)` | `NOT NULL DEFAULT 0` |
| `is_recurring` | `tinyint(1)` | `NOT NULL DEFAULT 0` |
| `description` | `text` | `NULL` |

---

### Table: tblhrm_dependants

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `full_name` | `varchar(255)` | `NOT NULL` |
| `relationship` | `varchar(100)` | `NULL` |
| `date_of_birth` | `date` | `NULL` |
| `id_number` | `varchar(100)` | `NULL` |
| `notes` | `text` | `NULL` |

---

### Table: tblhrm_documents

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `title` | `varchar(255)` | `NOT NULL` |
| `description` | `text` | `NULL` |
| `category` | `varchar(100)` | `NULL` |
| `file_name` | `varchar(255)` | `NULL` |
| `file_path` | `varchar(500)` | `NULL` |
| `date_added` | `datetime` | `NULL` |
| `added_by` | `int(11)` | `NULL` |

---

### Table: tblhrm_engagement_surveys

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `title` | `varchar(255)` | `NOT NULL` |
| `questions` | `LONGTEXT` | `NULL` |
| `date_from` | `date` | `NULL` |
| `date_to` | `date` | `NULL` |
| `date_added` | `datetime` | `NULL` |
| `added_by` | `int(11)` | `NULL` |

---

### Table: tblhrm_helpdesk_tickets

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `subject` | `varchar(255)` | `NOT NULL` |
| `message` | `text` | `NULL` |
| `category` | `varchar(100)` | `NULL` |
| `status` | `varchar(50)` | `DEFAULT 'open'` |
| `assigned_to` | `int(11)` | `NULL` |
| `date_added` | `datetime` | `NULL` |
| `date_updated` | `datetime` | `NULL` |

---

### Table: tblhrm_insurance_category

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(191)` | `NOT NULL` |
| `company_percent` | `decimal(6,3` | `) NOT NULL DEFAULT 0` |
| `staff_percent` | `decimal(6,3` | `) NOT NULL DEFAULT 0` |
| `description` | `text` | `NULL` |
| `active` | `tinyint(1)` | `NOT NULL DEFAULT 1` |

---

### Table: tblhrm_job_description_groups

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(255)` | `NOT NULL` |

---

### Table: tblhrm_layoff_checklist

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(255)` | `NOT NULL` |
| `description` | `text` | `NULL` |
| `sort_order` | `int(11)` | `DEFAULT 0` |

---

### Table: tblhrm_layoff_records

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `layoff_date` | `date` | `NULL` |
| `reason` | `text` | `NULL` |
| `checklist_completed` | `LONGTEXT` | `NULL` |
| `notes` | `text` | `NULL` |
| `added_from` | `int(11)` | `NULL` |
| `date_added` | `datetime` | `NULL` |

---

### Table: tblhrm_learning_courses

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(255)` | `NOT NULL` |
| `description` | `text` | `NULL` |
| `category` | `varchar(100)` | `NULL` |
| `duration_hours` | `int(11)` | `NULL` |
| `date_added` | `datetime` | `NULL` |

---

### Table: tblhrm_onboarding_records

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `template_id` | `int(11)` | `NULL` |
| `status` | `varchar(50)` | `NULL` |
| `started_date` | `date` | `NULL` |
| `completed_date` | `date` | `NULL` |
| `checklist_data` | `LONGTEXT` | `NULL` |

---

### Table: tblhrm_onboarding_templates

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(255)` | `NOT NULL` |
| `description` | `text` | `NULL` |
| `checklist_items` | `LONGTEXT` | `NULL` |
| `department_id` | `int(11)` | `DEFAULT 0` |
| `position_id` | `int(11)` | `DEFAULT 0` |

---

### Table: tblhrm_one_on_one_notes

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `manager_id` | `int(11)` | `NOT NULL` |
| `meeting_date` | `date` | `NULL` |
| `notes` | `text` | `NULL` |
| `action_items` | `text` | `NULL` |
| `date_added` | `datetime` | `NULL` |

---

### Table: tblhrm_option

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `option_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `option_name` | `varchar(200)` | `NOT NULL` |
| `option_val` | `longtext` | `NULL` |
| `auto` | `tinyint(1)` | `NULL` |

---

### Table: tblhrm_performance_goals

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `title` | `varchar(255)` | `NOT NULL` |
| `description` | `text` | `NULL` |
| `target_date` | `date` | `NULL` |
| `status` | `varchar(50)` | `DEFAULT 'pending'` |
| `progress` | `int(11)` | `DEFAULT 0` |
| `review_id` | `int(11)` | `NULL` |
| `date_added` | `datetime` | `NULL` |

---

### Table: tblhrm_performance_reviews

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `reviewer_id` | `int(11)` | `NULL` |
| `review_period` | `varchar(50)` | `NULL` |
| `review_date` | `date` | `NULL` |
| `rating` | `decimal(3,2` | `) NULL` |
| `notes` | `text` | `NULL` |
| `goals` | `LONGTEXT` | `NULL` |
| `date_added` | `datetime` | `NULL` |

---

### Table: tblhrm_policies

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `title` | `varchar(255)` | `NOT NULL` |
| `content` | `LONGTEXT` | `NULL` |
| `category` | `varchar(100)` | `NULL` |
| `is_faq` | `tinyint(1)` | `DEFAULT 0` |
| `sort_order` | `int(11)` | `DEFAULT 0` |
| `date_added` | `datetime` | `NULL` |
| `added_by` | `int(11)` | `NULL` |

---

