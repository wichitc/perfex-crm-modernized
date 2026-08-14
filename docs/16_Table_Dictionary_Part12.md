> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary - Part 12

> [!NOTE]
> This is Part 12 of the document. [Back to Part 1](16_Table_Dictionary.md)

### Table: tblweb_to_recruitment

**Module**: Recruitment

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `campaign_code` | `varchar(200)` | `NULL` |
| `campaign_name` | `varchar(200)` | `NULL` |
| `cp_proposal` | `text` | `NULL` |
| `cp_position` | `int(11)` | `NULL` |
| `cp_department` | `int(11)` | `NULL` |
| `cp_amount_recruiment` | `int(11)` | `NULL` |
| `cp_form_work` | `varchar(45)` | `NULL` |
| `cp_workplace` | `varchar(255)` | `NULL` |
| `cp_salary_from` | `DECIMAL(15,0` | `) NULL` |
| `cp_salary_to` | `DECIMAL(15,0` | `) NULL` |
| `cp_from_date` | `date` | `NULL` |
| `cp_to_date` | `date` | `NULL` |
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
| `cp_add_from` | `int(11)` | `NULL` |
| `cp_date_add` | `date` | `NULL` |
| `cp_status` | `int(11)` | `NULL` |
| `nation` | `varchar(15)` | `None` |
| `nationality` | `varchar(15)` | `None` |
| `religion` | `varchar(15)` | `None` |
| `marital_status` | `varchar(15)` | `None` |
| `birthplace` | `varchar(200)` | `None` |
| `home_town` | `varchar(200)` | `None` |
| `resident` | `varchar(200)` | `None` |
| `current_accommodation` | `varchar(200)` | `None` |
| `cp_desired_salary` | `varchar(10)` | `NULL` |
| `specialized` | `varchar(100)` | `None` |
| `training_form` | `varchar(50)` | `None` |
| `training_places` | `varchar(50)` | `None` |
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

### Table: tblwh_activity_log

**Module**: Warehouse (Inventory)

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

### Table: tblwh_approval_details

**Module**: Warehouse (Inventory)

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

### Table: tblwh_approval_setting

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT` | `NOT NULL AUTO_INCREMENT` |
| `name` | `VARCHAR(255)` | `NOT NULL` |
| `related` | `VARCHAR(255)` | `NOT NULL` |
| `setting` | `LONGTEXT` | `NOT NULL` |

---

### Table: tblwh_brand

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `name` | `text` | `NULL` |

---

### Table: tblwh_custom_fields

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `custom_fields_id` | `int` | `NULL` |
| `warehouse_id` | `text` | `NULL` |

---

### Table: tblwh_goods_delivery_activity_log

**Module**: Warehouse (Inventory)

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

### Table: tblwh_inventory_serial_numbers

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `commodity_id` | `INT(11)` | `NOT NULL` |
| `warehouse_id` | `INT(11)` | `NULL` |
| `inventory_manage_id` | `INT(11)` | `NULL` |
| `serial_number` | `VARCHAR(255)` | `NULL` |
| `is_used` | `VARCHAR(20)` | `NULL DEFAULT 'no'` |

---

### Table: tblwh_loss_adjustment

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `type` | `varchar(15)` | `NULL` |
| `addfrom` | `int(11)` | `NULL` |
| `reason` | `LONGTEXT` | `NULL` |
| `time` | `datetime` | `NULL` |
| `date_create` | `date` | `NOT NULL` |
| `status` | `int` | `NOT NULL` |

---

### Table: tblwh_loss_adjustment_detail

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `items` | `int(11)` | `NULL` |
| `unit` | `int(11)` | `NULL` |
| `current_number` | `int(15)` | `NULL` |
| `updates_number` | `int(15)` | `NULL` |
| `loss_adjustment` | `INT(11)` | `NULL` |

---

### Table: tblwh_model

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `name` | `text` | `NULL` |
| `brand_id` | `int(11)` | `NOT NULL` |

---

### Table: tblwh_omni_shipments

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `cart_id` | `INT(11)` | `NULL` |
| `shipment_number` | `VARCHAR(100)` | `NULL` |
| `planned_shipping_date` | `DATETIME` | `NULL` |
| `shipment_status` | `VARCHAR(50)` | `NULL` |
| `datecreated` | `DATETIME` | `NULL` |

---

### Table: tblwh_order_return_details

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `order_return_id` | `INT(11)` | `NOT NULL` |
| `rel_type_detail_id` | `INT(11)` | `NULL` |
| `commodity_code` | `INT(11)` | `NULL` |
| `commodity_name` | `TEXT` | `NULL` |
| `quantity` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `unit_id` | `INT(11)` | `NULL` |
| `unit_price` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `sub_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `tax_id` | `TEXT` | `NULL` |
| `tax_rate` | `TEXT` | `NULL` |
| `tax_name` | `TEXT` | `NULL` |
| `total_amount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `total_after_discount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `reason_return` | `VARCHAR(200)` | `NULL` |

---

### Table: tblwh_order_returns

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `rel_id` | `INT(11)` | `NULL` |
| `rel_type` | `VARCHAR(50)` | `NOT NULL COMMENT'manual` |
| `return_type` | `VARCHAR(50)` | `NULL COMMENT'manual` |
| `company_id` | `INT(11)` | `NULL` |
| `company_name` | `VARCHAR(500)` | `NULL` |
| `email` | `VARCHAR(100)` | `NULL` |
| `phonenumber` | `VARCHAR(20)` | `NULL` |
| `order_number` | `VARCHAR(500)` | `NULL` |
| `order_date` | `DATETIME` | `NULL` |
| `number_of_item` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `order_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `order_return_number` | `VARCHAR(200)` | `NULL` |
| `order_return_name` | `VARCHAR(500)` | `NULL` |
| `fee_return_order` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `refund_loyaty_point` | `INT(11)` | `NULL DEFAULT '0'` |
| `subtotal` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `total_amount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `additional_discount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `adjustment_amount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `total_after_discount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `return_policies_information` | `TEXT` | `NULL` |
| `admin_note` | `TEXT` | `NULL` |
| `approval` | `INT(11)` | `NULL DEFAULT 0` |
| `datecreated` | `DATETIME` | `NULL` |
| `staff_id` | `INT(11)` | `NULL` |
| `receipt_delivery_id` | `INT(1)` | `NULL DEFAULT 0` |

---

### Table: tblwh_order_returns_refunds

**Module**: Purchase Management

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `order_return_id` | `INT(11)` | `NULL` |
| `staff_id` | `INT(11)` | `NULL` |
| `refunded_on` | `date` | `NULL` |
| `payment_mode` | `varchar(40)` | `NULL` |
| `note` | `text` | `NULL` |
| `amount` | `decimal(15,2` | `) NULL` |
| `created_at` | `datetime` | `NULL` |

---

### Table: tblwh_packing_list_details

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `packing_list_id` | `INT(11)` | `NOT NULL` |
| `delivery_detail_id` | `INT(11)` | `NULL` |
| `commodity_code` | `INT(11)` | `NULL` |
| `commodity_name` | `TEXT` | `NULL` |
| `quantity` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `unit_id` | `INT(11)` | `NULL` |
| `unit_price` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `sub_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `tax_id` | `TEXT` | `NULL` |
| `tax_rate` | `TEXT` | `NULL` |
| `tax_name` | `TEXT` | `NULL` |
| `total_amount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `total_after_discount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |

---

### Table: tblwh_packing_lists

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `NOT NULL AUTO_INCREMENT` |
| `delivery_note_id` | `INT(11)` | `NULL` |
| `packing_list_number` | `VARCHAR(100)` | `NULL` |
| `packing_list_name` | `VARCHAR(200)` | `NULL` |
| `width` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `height` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `lenght` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `weight` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `volume` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `clientid` | `INT(11)` | `NULL` |
| `subtotal` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `total_amount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `discount_total` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `additional_discount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `total_after_discount` | `DECIMAL(15,2` | `) NULL DEFAULT '0.00'` |
| `billing_street` | `varchar(200)` | `DEFAULT NULL` |
| `billing_city` | `varchar(100)` | `DEFAULT NULL` |
| `billing_state` | `varchar(100)` | `DEFAULT NULL` |
| `billing_zip` | `varchar(100)` | `DEFAULT NULL` |
| `billing_country` | `int(11)` | `DEFAULT NULL` |
| `shipping_street` | `varchar(200)` | `DEFAULT NULL` |
| `shipping_city` | `varchar(100)` | `DEFAULT NULL` |
| `shipping_state` | `varchar(100)` | `DEFAULT NULL` |
| `shipping_zip` | `varchar(100)` | `DEFAULT NULL` |
| `shipping_country` | `int(11)` | `DEFAULT NULL` |
| `client_note` | `TEXT` | `NULL` |
| `admin_note` | `TEXT` | `NULL` |
| `approval` | `INT(11)` | `NULL DEFAULT 0` |
| `datecreated` | `DATETIME` | `NULL` |
| `staff_id` | `INT(11)` | `NULL` |

---

### Table: tblwh_series

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `name` | `text` | `NULL` |
| `model_id` | `int(11)` | `NOT NULL` |

---

### Table: tblwh_staff_warehouses

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `staff_id` | `INT(11)` | `NULL` |
| `warehouse_id` | `INT(11)` | `NULL` |

---

### Table: tblwh_sub_group

**Module**: Warehouse (Inventory)

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `sub_group_code` | `varchar(100)` | `NULL` |
| `sub_group_name` | `text` | `NULL` |
| `order` | `int(10)` | `NULL` |
| `display` | `int(1)` | `NULL COMMENT 'display 1: display (yes) 0: not displayed (no)'` |
| `note` | `text` | `NULL` |

---

### Table: tblwoocommerce_assigned

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `store_id` | `INT` | `NOT NULL` |
| `staff_id` | `INT` | `NOT NULL` |

---

### Table: tblwoocommerce_customer_field_mapping

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `store_id` | `INT(10)` | `UNSIGNED NOT NULL` |
| `wc_field` | `VARCHAR(191)` | `NOT NULL` |
| `perfex_field` | `VARCHAR(191)` | `NOT NULL` |
| `is_required` | `TINYINT(1)` | `DEFAULT 0` |
| `default_value` | `VARCHAR(191)` | `DEFAULT NULL` |
| `is_predefined` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `is_overridden` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `original_wc_field` | `VARCHAR(191)` | `NULL` |
| `original_perfex_field` | `VARCHAR(191)` | `NULL` |

---

### Table: tblwoocommerce_customers

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `woo_customer_id` | `INT(11)` | `NOT NULL` |
| `userid` | `INT(11)` | `DEFAULT NULL` |
| `email` | `VARCHAR(190)` | `DEFAULT NULL` |
| `first_name` | `VARCHAR(100)` | `DEFAULT NULL` |
| `last_name` | `VARCHAR(100)` | `DEFAULT NULL` |
| `phone` | `VARCHAR(50)` | `DEFAULT NULL` |
| `role` | `VARCHAR(50)` | `DEFAULT NULL` |
| `username` | `VARCHAR(100)` | `DEFAULT NULL` |
| `avatar_url` | `TEXT` | `DEFAULT NULL` |
| `store_id` | `INT(5)` | `DEFAULT NULL` |
| `last_synced_at` | `DATETIME` | `NULL` |
| `is_deleted` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `deleted_at` | `DATETIME` | `NULL` |

---

### Table: tblwoocommerce_jobs

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `type` | `VARCHAR(64)` | `NOT NULL` |
| `store_id` | `INT` | `UNSIGNED NULL` |
| `payload_json` | `MEDIUMTEXT` | `NOT NULL` |
| `status` | `ENUM(` | `'pending','in_progress','done','failed','quarantined') NOT NULL DEFAULT 'pending'` |
| `attempts` | `TINYINT` | `UNSIGNED NOT NULL DEFAULT 0` |
| `max_attempts` | `TINYINT` | `UNSIGNED NOT NULL DEFAULT 5` |
| `last_error` | `TEXT` | `NULL` |
| `scheduled_for` | `DATETIME` | `NOT NULL` |
| `locked_at` | `DATETIME` | `NULL` |
| `last_run_at` | `DATETIME` | `NULL` |
| `created_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblwoocommerce_log

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `BIGINT` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `store_id` | `INT` | `UNSIGNED NULL` |
| `level` | `ENUM(` | `'info','warn','error') NOT NULL DEFAULT 'info'` |
| `event` | `VARCHAR(128)` | `NOT NULL` |
| `context_json` | `MEDIUMTEXT` | `NULL` |
| `correlation_id` | `VARCHAR(64)` | `NULL` |
| `created_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblwoocommerce_order_field_mapping

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `store_id` | `INT(10)` | `UNSIGNED NOT NULL` |
| `wc_field` | `VARCHAR(191)` | `NOT NULL` |
| `perfex_field` | `VARCHAR(191)` | `NOT NULL` |
| `is_required` | `TINYINT(1)` | `DEFAULT 0` |
| `default_value` | `VARCHAR(191)` | `DEFAULT NULL` |
| `is_predefined` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `is_overridden` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `original_wc_field` | `VARCHAR(191)` | `NULL` |
| `original_perfex_field` | `VARCHAR(191)` | `NULL` |

---

### Table: tblwoocommerce_orders

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `order_id` | `INT(11)` | `NOT NULL` |
| `order_number` | `VARCHAR(50)` | `NOT NULL` |
| `customer_id` | `INT(11)` | `NOT NULL` |
| `address` | `TEXT` | `DEFAULT NULL` |
| `phone` | `VARCHAR(50)` | `DEFAULT NULL` |
| `status` | `VARCHAR(100)` | `DEFAULT NULL` |
| `currency` | `VARCHAR(10)` | `DEFAULT NULL` |
| `date_created` | `DATETIME` | `DEFAULT NULL` |
| `date_modified` | `DATETIME` | `DEFAULT NULL` |
| `total` | `VARCHAR(30)` | `DEFAULT NULL` |
| `invoice_id` | `INT(30)` | `DEFAULT NULL` |
| `store_id` | `INT(5)` | `DEFAULT NULL` |
| `last_synced_at` | `DATETIME` | `NULL` |
| `is_deleted` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `deleted_at` | `DATETIME` | `NULL` |

---

### Table: tblwoocommerce_product_field_mapping

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `store_id` | `INT(10)` | `UNSIGNED NOT NULL` |
| `wc_field` | `VARCHAR(191)` | `NOT NULL` |
| `perfex_field` | `VARCHAR(191)` | `NOT NULL` |
| `is_required` | `TINYINT(1)` | `DEFAULT 0` |
| `default_value` | `VARCHAR(191)` | `DEFAULT NULL` |
| `is_predefined` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `is_overridden` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `original_wc_field` | `VARCHAR(191)` | `NULL` |
| `original_perfex_field` | `VARCHAR(191)` | `NULL` |

---

### Table: tblwoocommerce_products

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `product_id` | `INT(11)` | `NOT NULL` |
| `itemid` | `INT(11)` | `DEFAULT NULL` |
| `name` | `VARCHAR(500)` | `DEFAULT NULL` |
| `permalink` | `VARCHAR(500)` | `DEFAULT NULL` |
| `type` | `VARCHAR(50)` | `DEFAULT NULL` |
| `status` | `VARCHAR(50)` | `DEFAULT NULL` |
| `sku` | `VARCHAR(50)` | `DEFAULT NULL` |
| `price` | `VARCHAR(20)` | `DEFAULT NULL` |
| `sales` | `VARCHAR(20)` | `DEFAULT NULL` |
| `picture` | `TEXT` | `DEFAULT NULL` |
| `category` | `TEXT` | `DEFAULT NULL` |
| `date_created` | `DATETIME` | `DEFAULT NULL` |
| `date_modified` | `DATETIME` | `DEFAULT NULL` |
| `store_id` | `INT(5)` | `DEFAULT NULL` |
| `last_synced_at` | `DATETIME` | `NULL` |
| `is_deleted` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `deleted_at` | `DATETIME` | `NULL` |

---

### Table: tblwoocommerce_rate_limit

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `store_id` | `INT` | `UNSIGNED NOT NULL` |
| `tokens` | `DOUBLE` | `NOT NULL DEFAULT 0` |
| `updated_at` | `DATETIME` | `NOT NULL` |

---

### Table: tblwoocommerce_summary

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `store_id` | `INT(5)` | `DEFAULT NULL` |
| `customers` | `TEXT` | `DEFAULT NULL` |
| `orders` | `TEXT` | `DEFAULT NULL` |
| `products` | `TEXT` | `DEFAULT NULL` |

---

### Table: tblwoocommerce_webhook_log

**Module**: WooCommerce

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `INT` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `store_id` | `INT` | `UNSIGNED NOT NULL` |
| `topic` | `VARCHAR(64)` | `NOT NULL` |
| `resource` | `VARCHAR(32)` | `NOT NULL` |
| `woo_id` | `INT` | `UNSIGNED NULL` |
| `delivery_id` | `VARCHAR(64)` | `NOT NULL` |
| `received_at` | `DATETIME` | `NOT NULL` |
| `signature_ok` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `processed` | `TINYINT(1)` | `NOT NULL DEFAULT 0` |
| `error` | `TEXT` | `NULL` |
| `payload_hash` | `CHAR(64)` | `NULL` |

---

### Table: tblwork_shift

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int(11)` | `NOT NULL AUTO_INCREMENT` |
| `shift_code` | `varchar(45)` | `NOT NULL` |
| `shift_name` | `varchar(200)` | `NOT NULL` |
| `shift_type` | `varchar(200)` | `NOT NULL` |
| `department` | `int(11)` | `NULL DEFAULT '0'` |
| `position` | `int(11)` | `NULL DEFAULT '0'` |
| `add_from` | `int(11)` | `NOT NULL` |
| `date_create` | `date` | `NULL` |
| `from_date` | `date` | `NULL` |
| `to_date` | `date` | `NULL` |
| `shifts_detail` | `TEXT` | `NOT NULL` |

---

### Table: tblworkplace

**Module**: HRM

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `workplace_id` | `int(11)` | `UNSIGNED NOT NULL AUTO_INCREMENT` |
| `workplace_name` | `varchar(200)` | `NOT NULL` |

---

