> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary - Part 9

> [!NOTE]
> This is Part 9 of the document. [Back to Part 1](16_Table_Dictionary.md)

### Table: tblhrm_staff_courses

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `course_id` | `int(11)` | `NOT NULL` |
| `status` | `varchar(50)` | `DEFAULT 'enrolled'` |
| `completed_date` | `date` | `NULL` |
| `certificate` | `varchar(255)` | `NULL` |
| `date_added` | `datetime` | `NULL` |

---

### Table: tblhrm_staff_deduction

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `deduction_type_id` | `int(11)` | `NULL` |
| `title` | `varchar(191)` | `NULL` |
| `total_amount` | `decimal(15,2` | `) NOT NULL DEFAULT 0` |
| `installment_amount` | `decimal(15,2` | `) NOT NULL DEFAULT 0` |
| `collect_type` | `varchar(20)` | `NOT NULL DEFAULT 'one_time'` |
| `start_month` | `date` | `NULL` |
| `collected_amount` | `decimal(15,2` | `) NOT NULL DEFAULT 0` |
| `status` | `varchar(20)` | `NOT NULL DEFAULT 'active'` |
| `auto_collect` | `tinyint(1)` | `NOT NULL DEFAULT 1` |
| `notes` | `text` | `NULL` |
| `date_created` | `datetime` | `NULL` |

---

### Table: tblhrm_staff_deduction_collection

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `deduction_id` | `int(11)` | `NOT NULL` |
| `amount` | `decimal(15,2` | `) NOT NULL DEFAULT 0` |
| `collected_date` | `date` | `NULL` |
| `period` | `varchar(20)` | `NULL` |
| `notes` | `varchar(255)` | `NULL` |
| `add_from` | `int(11)` | `NULL` |
| `date_created` | `datetime` | `NULL` |

---

### Table: tblhrm_staff_trainings

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `training_type_id` | `int(11)` | `NULL` |
| `training_name` | `varchar(255)` | `NULL` |
| `completed_date` | `date` | `NULL` |
| `certificate_number` | `varchar(100)` | `NULL` |
| `notes` | `text` | `NULL` |

---

### Table: tblhrm_survey_responses

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `survey_id` | `int(11)` | `NOT NULL` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `responses` | `LONGTEXT` | `NULL` |
| `date_submitted` | `datetime` | `NULL` |

---

### Table: tblhrm_thirteenth_month

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `year` | `int(11)` | `NOT NULL` |
| `base_amount` | `decimal(15,2` | `) NOT NULL DEFAULT 0` |
| `months_worked` | `decimal(5,2` | `) NOT NULL DEFAULT 12` |
| `computed_amount` | `decimal(15,2` | `) NOT NULL DEFAULT 0` |
| `status` | `varchar(20)` | `NOT NULL DEFAULT 'draft'` |
| `notes` | `text` | `NULL` |
| `date_created` | `datetime` | `NULL` |

---

### Table: tblhrm_timesheet

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `NOT NULL` |
| `date_work` | `date` | `NOT NULL` |
| `value` | `text` | `NULL` |
| `type` | `varchar(45)` | `NULL` |
| `add_from` | `int(11)` | `NOT NULL` |

---

### Table: tblhrm_training_types

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `varchar(255)` | `NOT NULL` |

---

### Table: tblif

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `url` | `VARCHAR(255)` | `NOT NULL` |
| `key` | `VARCHAR(255)` | `NOT NULL` |
| `secret` | `VARCHAR(255)` | `NOT NULL` |
| `productPage` | `INT(5)` | `DEFAULT 1` |
| `orderPage` | `INT(5)` | `DEFAULT 1` |
| `customerPage` | `INT(5)` | `DEFAULT 1` |
| `date_created` | `DATETIME` | `NOT NULL` |
| `query_auth` | `INT` | `DEFAULT 1` |
| `auto_convert_customer` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `auto_convert_product` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `auto_convert_order` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `auto_invoice_statuses` | `TEXT` | `NULL` |
| `verify_ssl` | `TINYINT(1)` | `NOT NULL DEFAULT 1` |
| `webhook_secret` | `VARCHAR(255)` | `NULL` |
| `pages_per_tick` | `TINYINT(3)` | `NOT NULL DEFAULT 3` |
| `is_active` | `TINYINT(1)` | `NOT NULL DEFAULT 1` |
| `date_modified` | `DATETIME` | `NULL` |
| `woocommerce_payment_mode_id` | `INT` | `NULL` |

---

### Table: tblinsurance_type

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `from_month` | `date` | `NOT NULL` |
| `social_company` | `VARCHAR(15)` | `NULL` |
| `social_staff` | `VARCHAR(15)` | `NULL` |
| `labor_accident_company` | `VARCHAR(15)` | `NULL` |
| `labor_accident_staff` | `VARCHAR(15)` | `NULL` |
| `health_company` | `VARCHAR(15)` | `NULL` |
| `health_staff` | `VARCHAR(15)` | `NULL` |
| `unemployment_company` | `VARCHAR(15)` | `NULL` |
| `unemployment_staff` | `VARCHAR(15)` | `NULL` |

---

### Table: tblinternal_delivery_note

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `internal_delivery_name` | `text` | `NULL` |
| `description` | `text` | `NULL` |
| `staff_id` | `int(11)` | `NULL` |
| `date_c` | `date` | `NULL` |
| `date_add` | `date` | `NULL` |
| `internal_delivery_code` | `varchar(100)` | `NULL` |
| `approval` | `INT(11)` | `NULL DEFAULT 0 COMMENT 'status approval '` |
| `addedfrom` | `INT(11)` | `null` |
| `total_amount` | `decimal(15,2` | `) null` |
| `datecreated` | `datetime` | `null` |

---

### Table: tblinternal_delivery_note_detail

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `internal_delivery_id` | `int(11)` | `NOT NULL` |
| `commodity_code` | `varchar(100)` | `NULL` |
| `from_stock_name` | `text` | `NULL` |
| `to_stock_name` | `text` | `NULL` |
| `unit_id` | `text` | `NULL` |
| `available_quantity` | `text` | `NULL` |
| `quantities` | `text` | `NULL` |
| `unit_price` | `varchar(100)` | `NULL` |
| `into_money` | `varchar(100)` | `NULL` |
| `note` | `text` | `NULL` |

---

### Table: tblinventory_commodity_min

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `commodity_id` | `int(11)` | `NOT NULL` |
| `commodity_code` | `varchar(100)` | `NULL` |
| `commodity_name` | `varchar(100)` | `NULL` |
| `inventory_number_min` | `varchar(100)` | `NULL` |

---

### Table: tblinventory_history

**Module**: Assets Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `assets` | `INT(11)` | `NOT NULL` |
| `date_time` | `DATETIME` | `NOT NULL` |
| `acction` | `VARCHAR(50)` | `NOT NULL` |
| `inventory_begin` | `INT(11)` | `NULL` |
| `inventory_end` | `INT(11)` | `NOT NULL` |
| `cost` | `DECIMAL(15,2` | `) NULL` |

---

### Table: tblinventory_manage

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `warehouse_id` | `int(11)` | `NOT NULL` |
| `commodity_id` | `int(11)` | `NOT NULL` |
| `inventory_number` | `varchar(100)` | `NULL` |

---

### Table: tblitems_of_vendor

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `vendor_id` | `INT(11)` | `NOT NULL` |
| `description` | `TEXT` | `NOT NULL` |
| `long_description` | `TEXT` | `NULL` |
| `rate` | `DECIMAL(15,2` | `) NULL` |
| `tax` | `int(11)` | `NULL` |
| `tax2` | `int(11)` | `NULL` |
| `unit` | `varchar(40)` | `NULL` |
| `group_id` | `int(11)` | `NOT NULL` |
| `commodity_code` | `varchar(100)` | `NOT NULL` |
| `commodity_barcode` | `TEXT` | `NULL` |
| `unit_id` | `int(11)` | `NULL` |
| `sku_code` | `VARCHAR(200)` | `NULL` |
| `sku_name` | `VARCHAR(200)` | `NULL` |
| `sub_group` | `VARCHAR(200)` | `NULL` |
| `active` | `INT(11)` | `NULL` |
| `parent` | `INT(11)` | `NULL` |
| `attributes` | `LONGTEXT` | `NULL` |
| `parent_attributes` | `LONGTEXT` | `NULL` |
| `commodity_type` | `INT(11)` | `NULL` |
| `origin` | `VARCHAR(100)` | `NULL` |
| `commodity_name` | `VARCHAR(200)` | `NOT NULL` |
| `series_id` | `TEXT` | `NULL` |
| `long_descriptions` | `LONGTEXT` | `NULL` |

---

### Table: tbljob_industry

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `industry_name` | `varchar(200)` | `NOT NULL` |
| `industry_description` | `text` | `NULL` |

---

### Table: tbljob_position

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `position_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `position_name` | `varchar(200)` | `NOT NULL` |

---

### Table: tblmanage_leave

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `leave_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `id_staff` | `int(11)` | `NOT NULL` |
| `leave_date` | `int(11)` | `NULL` |
| `leave_year` | `int(11)` | `NULL` |
| `accumulated_leave` | `int(11)` | `NULL` |
| `seniority_leave` | `int(11)` | `NULL` |
| `borrow_leave` | `int(11)` | `NULL` |
| `actual_leave` | `int(11)` | `NULL` |
| `expected_leave` | `int(11)` | `NULL` |

---

### Table: tblmulti_theme_setup

**Module**: Multi Theme

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `theme_name` | `varchar(100)` | `NOT NULL` |
| `theme_name_slug` | `varchar(100)` | `NOT NULL` |
| `bakground_image` | `varchar(200)` | `DEFAULT NULL` |
| `theme_color` | `varchar(100)` | `DEFAULT NULL` |
| `is_default` | `int(4)` | `DEFAULT NULL` |

---

### Table: tblokr_approval_details

**Module**: OKRs

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
| `sender` | `INT(11)` | `NULL` |
| `date_send` | `DATETIME` | `NULL` |
| `notification_recipient` | `LONGTEXT` | `NULL` |
| `approval_deadline` | `DATE` | `NULL` |

---

### Table: tblokr_approval_setting

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `department` | `VARCHAR(255)` | `NOT NULL` |
| `okrs` | `VARCHAR(255)` | `NOT NULL` |
| `setting` | `LONGTEXT` | `NOT NULL` |
| `choose_when_approving` | `INT` | `NOT NULL DEFAULT 0` |
| `notification_recipient` | `LONGTEXT` | `NULL` |
| `number_day_approval` | `INT(11)` | `NULL` |

---

### Table: tblokr_setting_category

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `category` | `text` | `NOT NULL` |

---

### Table: tblokr_setting_circulation

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `name_circulation` | `varchar(150)` | `NOT NULL` |
| `from_date` | `date` | `NOT NULL` |
| `to_date` | `date` | `NOT NULL` |

---

### Table: tblokr_setting_evaluation_criteria

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `group_criteria` | `int(11)` | `NOT NULL` |
| `name` | `varchar(250)` | `NOT NULL` |
| `scores` | `int(250)` | `NOT NULL` |

---

### Table: tblokr_setting_question

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `question` | `text` | `NOT NULL` |

---

### Table: tblokr_setting_unit

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `unit` | `text` | `NOT NULL` |

---

### Table: tblokrs

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `name` | `TEXT` | `NOT NULL` |
| `circulation` | `int(11)` | `NOT NULL` |
| `okr_superior` | `text` | `NULL` |
| `your_target` | `varchar(250)` | `NOT NULL` |
| `okr_cross` | `text` | `NULL` |
| `display` | `int(11)` | `NULL` |
| `creator` | `int(11)` | `NOT NULL` |
| `datecreator` | `datetime` | `NOT NULL` |

---

### Table: tblokrs_checkin

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `okrs_id` | `int(11)` | `NOT NULL` |
| `main_results` | `text` | `NOT NULL` |
| `target` | `text` | `NOT NULL` |
| `achieved` | `text` | `NOT NULL` |
| `progress` | `float(2,2` | `) not null default 0.00` |
| `confidence_level` | `int(11)` | `not null default 1` |
| `unit` | `text` | `NOT NULL` |
| `answer` | `text` | `NOT NULL` |
| `evaluation_criteria` | `int(11)` | `NULL` |
| `comment` | `text` | `NULL` |
| `type` | `int(11)` | `NULL` |
| `recently_checkin` | `date` | `NULL` |
| `upcoming_checkin` | `date` | `NULL` |
| `editor` | `int(11)` | `NOT NULL` |
| `created_date` | `datetime` | `NOT NULL` |

---

### Table: tblokrs_checkin_log

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `okrs_id` | `int(11)` | `NOT NULL` |
| `main_results` | `text` | `NOT NULL` |
| `key_results_id` | `int(11)` | `NOT NULL` |
| `target` | `text` | `NOT NULL` |
| `achieved` | `text` | `NOT NULL` |
| `progress` | `float(2,2` | `) not null default 0.00` |
| `confidence_level` | `int(11)` | `not null default 1` |
| `unit` | `text` | `NOT NULL` |
| `answer` | `text` | `NOT NULL` |
| `evaluation_criteria` | `int(11)` | `NULL` |
| `comment` | `text` | `NULL` |
| `type` | `int(11)` | `NULL` |
| `recently_checkin` | `date` | `NULL` |
| `upcoming_checkin` | `date` | `NULL` |
| `editor` | `int(11)` | `NOT NULL` |
| `created_date` | `datetime` | `NOT NULL` |

---

### Table: tblokrs_key_result

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `okrs_id` | `int(11)` | `NOT NULL` |
| `main_results` | `text` | `NOT NULL` |
| `target` | `text` | `NOT NULL` |
| `departments` | `int(11)` | `NULL` |
| `plan` | `text` | `NOT NULL` |
| `results` | `text` | `NOT NULL` |
| `unit` | `text` | `NOT NULL` |
| `datecreator` | `datetime` | `NOT NULL` |

---

### Table: tblokrs_key_result_log

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `okrs_id` | `int(11)` | `NOT NULL` |
| `main_results` | `text` | `NOT NULL` |
| `target` | `text` | `NOT NULL` |
| `plan` | `text` | `NOT NULL` |
| `results` | `text` | `NOT NULL` |
| `unit` | `text` | `NOT NULL` |
| `editor` | `int(11)` | `NOT NULL` |
| `date_edit` | `datetime` | `NOT NULL` |

---

### Table: tblokrs_log

**Module**: OKRs

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `name` | `TEXT` | `NOT NULL` |
| `circulation` | `int(11)` | `NOT NULL` |
| `okr_superior` | `text` | `NULL` |
| `your_target` | `varchar(250)` | `NOT NULL` |
| `okr_cross` | `text` | `NULL` |
| `display` | `int(11)` | `NULL` |
| `editor` | `int(11)` | `NOT NULL` |
| `date_edit` | `datetime` | `NOT NULL` |

---

### Table: tblpayroll_table

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `payroll_month` | `date` | `NOT NULL` |
| `payroll_type` | `int(11)` | `UNSIGNED NULL` |
| `template_data` | `longtext` | `NULL` |
| `status` | `int(11)` | `UNSIGNED NULL DEFAULT 0 COMMENT '1:đã chốt 0:chưa chốt'` |

---

### Table: tblpayroll_type

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `payroll_type_name` | `varchar(100)` | `NOT NULL` |
| `department_id` | `longtext` | `NULL` |
| `role_id` | `longtext` | `NULL` |
| `position_id` | `longtext` | `NULL` |
| `salary_form_id` | `int(11)` | `UNSIGNED NULL COMMENT '1:Chính 2:Phụ cấp'` |
| `manager_id` | `int(11)` | `UNSIGNED NULL` |
| `follower_id` | `int(11)` | `UNSIGNED NULL` |
| `template` | `longtext` | `NULL` |

---

