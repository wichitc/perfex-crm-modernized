> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary - Part 11

> [!NOTE]
> This is Part 11 of the document. [Back to Part 1](16_Table_Dictionary.md)

### Table: tblrec_job_position

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `position_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `position_name` | `varchar(200)` | `NOT NULL` |
| `position_description` | `text` | `NULL` |

---

### Table: tblrec_list_criteria

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `evaluation_form` | `int(11)` | `NOT NULL` |
| `group_criteria` | `int(11)` | `NOT NULL` |
| `evaluation_criteria` | `int(11)` | `NOT NULL` |
| `percent` | `float` | `NOT NULL` |

---

### Table: tblrec_notifications

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `isread` | `int(11)` | `NOT NULL DEFAULT '0'` |
| `isread_inline` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `date` | `datetime` | `NOT NULL` |
| `description` | `text` | `NOT NULL` |
| `fromuserid` | `int(11)` | `NOT NULL` |
| `fromclientid` | `int(11)` | `NOT NULL DEFAULT '0'` |
| `from_fullname` | `varchar(100)` | `NOT NULL` |
| `touserid` | `int(11)` | `NOT NULL` |
| `fromcompany` | `int(11)` | `DEFAULT NULL` |
| `link` | `mediumtext` | `None` |
| `additional_data` | `text` | `None` |

---

### Table: tblrec_proposal

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `proposal_name` | `varchar(200)` | `NOT NULL` |
| `position` | `int(11)` | `NOT NULL` |
| `department` | `int(11)` | `NULL` |
| `amount_recruiment` | `int(11)` | `NULL` |
| `form_work` | `varchar(45)` | `NULL` |
| `workplace` | `varchar(255)` | `NULL` |
| `salary_from` | `DECIMAL(15,0` | `) NULL` |
| `salary_to` | `DECIMAL(15,0` | `) NULL` |
| `from_date` | `date` | `NULL` |
| `to_date` | `date` | `NOT NULL` |
| `reason_recruitment` | `text` | `NULL` |
| `job_description` | `text` | `NULL` |
| `approver` | `int(11)` | `NOT NULL` |
| `ages_from` | `int(11)` | `NULL` |
| `ages_to` | `int(11)` | `NULL` |
| `gender` | `varchar(10)` | `NULL` |
| `height` | `float` | `NULL` |
| `weight` | `float` | `NULL` |
| `literacy` | `varchar(200)` | `NULL` |
| `experience` | `varchar(200)` | `NULL` |
| `add_from` | `int(11)` | `NOT NULL` |
| `date_add` | `date` | `NOT NULL` |
| `status` | `int(11)` | `NOT NULL` |

---

### Table: tblrec_set_transfer_record

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `set_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `send_to` | `varchar(45)` | `NOT NULL` |
| `email_to` | `text` | `NULL` |
| `add_from` | `int(11)` | `NOT NULL` |
| `add_date` | `date` | `NOT NULL` |
| `subject` | `text` | `NOT NULL` |
| `content` | `text` | `NULL` |

---

### Table: tblrec_skill

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `skill_name` | `text` | `NULL` |

---

### Table: tblrequest

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(45)` | `NOT NULL` |
| `request_type_id` | `VARCHAR(45)` | `NULL` |
| `date_create` | `DATETIME` | `NOT NULL` |
| `approval_deadline` | `DATETIME` | `NULL` |
| `addedfrom` | `INT(11)` | `None` |
| `status` | `VARCHAR(45)` | `None` |
| `code` | `VARCHAR(255)` | `NULL DEFAULT ""` |
| `description` | `MEDIUMTEXT` | `NULL` |

---

### Table: tblrequest_approval_details

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `request_id` | `INT(11)` | `NOT NULL` |
| `staffid` | `VARCHAR(255)` | `NOT NULL` |
| `approve` | `VARCHAR(45)` | `NOT NULL` |
| `note` | `TEXT` | `NULL` |
| `date` | `DATETIME` | `NULL DEFAULT NULL` |
| `approve_action` | `VARCHAR(255)` | `NULL` |
| `reject_action` | `VARCHAR(255)` | `NULL` |
| `approve_value` | `VARCHAR(255)` | `NULL` |
| `reject_value` | `VARCHAR(255)` | `NULL` |
| `staff_approve` | `INT(11)` | `NULL DEFAULT 0` |

---

### Table: tblrequest_files

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `request_id` | `INT(11)` | `NOT NULL` |
| `file_name` | `VARCHAR(255)` | `NOT NULL` |
| `filetype` | `VARCHAR(255)` | `NOT NULL` |
| `dateadded` | `DATETIME` | `NOT NULL` |

---

### Table: tblrequest_follow

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `request_id` | `VARCHAR(45)` | `NULL` |
| `staffid` | `int(11)` | `NOT NULL` |

---

### Table: tblrequest_form

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `request_id` | `INT(11)` | `NOT NULL` |
| `name` | `VARCHAR(45)` | `NOT NULL` |
| `type` | `VARCHAR(45)` | `NOT NULL` |
| `value` | `VARCHAR(255)` | `NOT NULL` |

---

### Table: tblrequest_log

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `request_id` | `VARCHAR(45)` | `NULL` |
| `staffid` | `int(11)` | `NOT NULL` |
| `date` | `DATETIME` | `NULL DEFAULT NULL` |
| `note` | `TEXT` | `NULL` |

---

### Table: tblrequest_related

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `request_id` | `INT(11)` | `NOT NULL` |
| `rel_type` | `VARCHAR(45)` | `NOT NULL` |
| `rel_id` | `VARCHAR(45)` | `NOT NULL` |

---

### Table: tblrequest_type

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(45)` | `NOT NULL` |
| `maximum_number_day` | `VARCHAR(45)` | `NULL` |
| `description` | `MEDIUMTEXT` | `NULL` |
| `data_chart` | `LONGTEXT` | `NOT NULL` |
| `active` | `VARCHAR(45)` | `NOT NULL DEFAULT "1"` |

---

### Table: tblrequest_type_form

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `request_type_id` | `INT(11)` | `NOT NULL` |
| `name` | `VARCHAR(45)` | `NOT NULL` |
| `type` | `VARCHAR(45)` | `NOT NULL` |

---

### Table: tblrequest_type_workflow

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `request_type_id` | `VARCHAR(45)` | `NOT NULL` |
| `staffid` | `VARCHAR(255)` | `NOT NULL` |
| `approve_action` | `VARCHAR(255)` | `NULL` |
| `reject_action` | `VARCHAR(255)` | `NULL` |
| `approve_value` | `VARCHAR(255)` | `NULL` |
| `reject_value` | `VARCHAR(255)` | `NULL` |

---

### Table: tblresource

**Module**: Staff Outsourcing

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `resource_name` | `VARCHAR(100)` | `NOT NULL` |
| `resource_group` | `INT(11)` | `NOT NULL` |
| `approved` | `INT(11)` | `NOT NULL` |
| `manager` | `INT(11)` | `NULL` |
| `color` | `VARCHAR(255)` | `NULL` |
| `description` | `TEXT` | `NULL` |
| `status` | `VARCHAR(50)` | `NOT NULL` |

---

### Table: tblresource_group

**Module**: Staff Outsourcing

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `group_name` | `VARCHAR(100)` | `NOT NULL` |
| `icon` | `VARCHAR(100)` | `NOT NULL` |
| `description` | `TEXT` | `NULL` |
| `creator` | `INT(11)` | `NOT NULL` |
| `date_create` | `DATE` | `NOT NULL` |

---

### Table: tblsalary_form

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `form_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `form_name` | `varchar(200)` | `NOT NULL` |
| `salary_val` | `decimal(15,2` | `) NOT NULL` |
| `tax` | `boolean` | `NOT NULL` |

---

### Table: tblstaff_contract

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id_contract` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `contract_code` | `varchar(15)` | `NOT NULL` |
| `name_contract` | `int(11)` | `NOT NULL` |
| `staff` | `int(11)` | `NOT NULL` |
| `contract_form` | `varchar(191)` | `NULL` |
| `start_valid` | `date` | `NULL` |
| `end_valid` | `date` | `NULL` |
| `contract_status` | `varchar(100)` | `NULL` |
| `salary_form` | `int(11)` | `NULL` |
| `allowance_type` | `varchar(11)` | `NULL` |
| `sign_day` | `date` | `NULL` |

---

### Table: tblstaff_contract_detail

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `contract_detail_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `staff_contract_id` | `int(11)` | `UNSIGNED NOT NULL` |
| `since_date` | `date` | `NULL` |
| `contract_note` | `varchar(100)` | `NULL` |
| `contract_salary_expense` | `longtext` | `NULL` |
| `contract_allowance_expense` | `longtext` | `NULL` |

---

### Table: tblstaff_contracttype

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id_contracttype` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `name_contracttype` | `varchar(200)` | `NOT NULL` |
| `contracttype` | `varchar(200)` | `NOT NULL` |
| `duration` | `int(11)` | `NULL` |
| `unit` | `varchar(20)` | `NULL` |
| `insurance` | `boolean` | `NOT NULL` |

---

### Table: tblstaff_insurance

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `insurance_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `staff_id` | `int(11)` | `UNSIGNED NOT NULL` |
| `insurance_book_num` | `varchar(100)` | `NULL` |
| `health_insurance_num` | `varchar(100)` | `NULL` |
| `city_code` | `varchar(100)` | `NULL` |
| `registration_medical` | `varchar(100)` | `NULL` |

---

### Table: tblstaff_insurance_history

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `insurance_id` | `int(11)` | `UNSIGNED NOT NULL` |
| `staff_id` | `int(11)` | `UNSIGNED NULL` |
| `from_month` | `date` | `NULL` |
| `formality` | `varchar(50)` | `NULL` |
| `reason` | `varchar(50)` | `NULL` |
| `premium_rates` | `varchar(100)` | `NULL` |
| `payment_company` | `varchar(100)` | `NULL` |
| `payment_worker` | `varchar(100)` | `NULL` |

---

### Table: tblstock_take

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `description` | `text` | `NULL COMMENT 'the reason stock take'` |
| `warehouse_id` | `int(11)` | `NULL` |
| `date_stock_take` | `date` | `NULL` |
| `stock_take_code` | `varchar(100)` | `NULL COMMENT 'số kiểm kê kho'` |
| `date_add` | `date` | `NULL` |
| `hour_add` | `date` | `NULL` |
| `staff_id` | `varchar(100)` | `NULL` |
| `approval` | `INT(11)` | `NULL DEFAULT 0 COMMENT 'status approval '` |
| `addedfrom` | `INT(11)` | `None` |

---

### Table: tblstock_take_detail

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `stock_take_id` | `int(11)` | `NOT NULL` |
| `commodity_code` | `varchar(100)` | `NULL` |
| `commodity_name` | `text` | `NULL` |
| `unit_id` | `text` | `NULL` |
| `unit_price` | `varchar(100)` | `NULL` |
| `quantity_stock_take` | `varchar(100)` | `NULL` |
| `quantity_accounting_book` | `varchar(100)` | `NULL` |
| `quantity_change` | `varchar(100)` | `NULL` |
| `handling` | `text` | `NULL` |
| `reason` | `text` | `NULL` |
| `approval` | `INT(11)` | `NULL DEFAULT 0 COMMENT 'status approval '` |

---

### Table: tbluser_api

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `user` | `VARCHAR(50)` | `NOT NULL` |
| `name` | `VARCHAR(50)` | `NOT NULL` |
| `token` | `VARCHAR(255)` | `NOT NULL` |
| `expiration_date` | `DATETIME` | `NULL` |
| `permission_enable` | `TINYINT(4)` | `NOT NULL DEFAULT 0` |
| `request_limit` | `INT(11)` | `NOT NULL DEFAULT 1000` |
| `time_window` | `INT(11)` | `NOT NULL DEFAULT 3600` |
| `burst_limit` | `INT(11)` | `NOT NULL DEFAULT 0` |
| `quota_active` | `TINYINT(1)` | `NOT NULL DEFAULT 1` |
| `quota_created_at` | `DATETIME` | `NULL` |
| `quota_updated_at` | `DATETIME` | `NULL` |

---

### Table: tbluser_api_permissions

**Module**: REST API

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `api_id` | `INT(11)` | `UNSIGNED NOT NULL` |
| `feature` | `VARCHAR(50)` | `NOT NULL` |
| `capability` | `VARCHAR(50)` | `NOT NULL` |

---

### Table: tblware_body_type

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `body_type_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `body_code` | `varchar(100)` | `NULL` |
| `body_name` | `text` | `NULL` |
| `order` | `int(10)` | `NULL` |
| `display` | `int(1)` | `NULL COMMENT 'display 1: display (yes) 0: not displayed (no)'` |
| `note` | `text` | `NULL` |

---

### Table: tblware_color

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `color_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `color_code` | `varchar(100)` | `NULL` |
| `color_name` | `varchar(100)` | `NULL` |
| `color_hex` | `text` | `NULL` |
| `order` | `int(10)` | `NULL` |
| `display` | `int(1)` | `NULL COMMENT 'display 1: display (yes) 0: not displayed (no)'` |
| `note` | `text` | `NULL` |

---

### Table: tblware_commodity_type

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `commodity_type_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `commondity_code` | `varchar(100)` | `NULL` |
| `commondity_name` | `text` | `NULL` |
| `order` | `int(10)` | `NULL` |
| `display` | `int(1)` | `NULL COMMENT 'display 1: display (yes) 0: not displayed (no)'` |
| `note` | `text` | `NULL` |

---

### Table: tblware_size_type

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `size_type_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `size_code` | `varchar(100)` | `NULL` |
| `size_name` | `text` | `NULL` |
| `size_symbol` | `text` | `NULL` |
| `order` | `int(10)` | `NULL` |
| `display` | `int(1)` | `NULL COMMENT 'display 1: display (yes) 0: not displayed (no)'` |
| `note` | `text` | `NULL` |

---

### Table: tblware_style_type

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `style_type_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `style_code` | `varchar(100)` | `NULL` |
| `style_barcode` | `text` | `NULL` |
| `style_name` | `text` | `NULL` |
| `order` | `int(10)` | `NULL` |
| `display` | `int(1)` | `NULL COMMENT 'display 1: display (yes) 0: not displayed (no)'` |
| `note` | `text` | `NULL` |

---

### Table: tblware_unit_type

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `unit_type_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `unit_code` | `varchar(100)` | `NULL` |
| `unit_name` | `text` | `NULL` |
| `unit_symbol` | `text` | `NULL` |
| `order` | `int(10)` | `NULL` |
| `display` | `int(1)` | `NULL COMMENT 'display 1: display (yes) 0: not displayed (no)'` |
| `note` | `text` | `NULL` |

---

### Table: tblwarehouse

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `warehouse_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `warehouse_code` | `varchar(100)` | `NULL` |
| `warehouse_name` | `text` | `NULL` |
| `warehouse_address` | `text` | `NULL` |
| `order` | `int(10)` | `NULL` |
| `display` | `int(1)` | `NULL COMMENT 'display 1: display (yes) 0: not displayed (no)'` |
| `note` | `text` | `NULL` |

---

