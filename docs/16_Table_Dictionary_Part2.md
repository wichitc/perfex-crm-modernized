> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary

> [!NOTE]
> This is Part 2 of the document. [Back to Part 1](16_Table_Dictionary.md) | [Go to Part 3](16_Table_Dictionary_Part3.md)

### Table: tblestimates

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `sent` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `datesend` | `datetime` | `DEFAULT NULL` |
| `clientid` | `int` | `NOT NULL` |
| `deleted_customer_name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `project_id` | `int` | `NOT NULL DEFAULT '0'` |
| `number` | `int` | `NOT NULL` |
| `prefix` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `number_format` | `int` | `NOT NULL DEFAULT '0'` |
| `formatted_number` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `hash` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `date` | `date` | `NOT NULL` |
| `expirydate` | `date` | `DEFAULT NULL` |
| `currency` | `int` | `NOT NULL` |
| `subtotal` | `decimal(15` | `,2) NOT NULL` |
| `total_tax` | `decimal(15` | `,2) NOT NULL DEFAULT '0.00'` |
| `total` | `decimal(15` | `,2) NOT NULL` |
| `adjustment` | `decimal(15` | `,2) DEFAULT NULL` |
| `addedfrom` | `int` | `NOT NULL` |
| `status` | `int` | `NOT NULL DEFAULT '1'` |
| `clientnote` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `adminnote` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `discount_percent` | `decimal(15` | `,2) DEFAULT '0.00'` |
| `discount_total` | `decimal(15` | `,2) DEFAULT '0.00'` |
| `discount_type` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `invoiceid` | `int` | `DEFAULT NULL` |
| `invoiced_date` | `datetime` | `DEFAULT NULL` |
| `terms` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `reference_no` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `sale_agent` | `int` | `NOT NULL DEFAULT '0'` |
| `billing_street` | `varchar(200)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_state` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_zip` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_country` | `int` | `DEFAULT NULL` |
| `shipping_street` | `varchar(200)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_state` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_zip` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_country` | `int` | `DEFAULT NULL` |
| `include_shipping` | `tinyint(1)` | `NOT NULL` |
| `show_shipping_on_estimate` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `show_quantity_as` | `int` | `NOT NULL DEFAULT '1'` |
| `pipeline_order` | `int` | `DEFAULT '1'` |
| `is_expiry_notified` | `int` | `NOT NULL DEFAULT '0'` |
| `acceptance_firstname` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `acceptance_lastname` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `acceptance_email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `acceptance_date` | `datetime` | `DEFAULT NULL` |
| `acceptance_ip` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `signature` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `short_link` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblestimate_requests

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `UNSIGNED NOT NULL` |
| `email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `submission` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `last_status_change` | `datetime` | `DEFAULT NULL` |
| `date_estimated` | `datetime` | `DEFAULT NULL` |
| `from_form_id` | `int` | `DEFAULT NULL` |
| `assigned` | `int` | `DEFAULT NULL` |
| `status` | `int` | `DEFAULT NULL` |
| `default_language` | `int` | `NOT NULL` |
| `date_added` | `datetime` | `NOT NULL` |

---

### Table: tblestimate_request_forms

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `UNSIGNED NOT NULL` |
| `form_key` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `type` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `form_data` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `recaptcha` | `int` | `DEFAULT NULL` |
| `status` | `int` | `NOT NULL` |
| `submit_btn_name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `submit_btn_bg_color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#84c529'` |
| `submit_btn_text_color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff'` |
| `success_submit_msg` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `submit_action` | `int` | `DEFAULT '0'` |
| `submit_redirect_url` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `language` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `dateadded` | `datetime` | `DEFAULT NULL` |
| `notify_type` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `notify_ids` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `responsible` | `int` | `DEFAULT NULL` |
| `notify_request_submitted` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tblestimate_request_status

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `UNSIGNED NOT NULL` |
| `name` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `statusorder` | `int` | `DEFAULT NULL` |
| `color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `flag` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblevents

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `eventid` | `int` | `NOT NULL` |
| `title` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `userid` | `int` | `NOT NULL` |
| `start` | `datetime` | `NOT NULL` |
| `end` | `datetime` | `DEFAULT NULL` |
| `public` | `int` | `NOT NULL DEFAULT '0'` |
| `color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `isstartnotified` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `reminder_before` | `int` | `NOT NULL DEFAULT '0'` |
| `reminder_before_type` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblexpenses

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `category` | `int` | `NOT NULL` |
| `currency` | `int` | `NOT NULL` |
| `amount` | `decimal(15` | `,2) NOT NULL` |
| `tax` | `int` | `DEFAULT NULL` |
| `tax2` | `int` | `NOT NULL DEFAULT '0'` |
| `reference_no` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `note` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `expense_name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `clientid` | `int` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL DEFAULT '0'` |
| `billable` | `int` | `DEFAULT '0'` |
| `invoiceid` | `int` | `DEFAULT NULL` |
| `paymentmode` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `date` | `date` | `NOT NULL` |
| `recurring_type` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `repeat_every` | `int` | `DEFAULT NULL` |
| `recurring` | `int` | `NOT NULL DEFAULT '0'` |
| `cycles` | `int` | `NOT NULL DEFAULT '0'` |
| `total_cycles` | `int` | `NOT NULL DEFAULT '0'` |
| `custom_recurring` | `int` | `NOT NULL DEFAULT '0'` |
| `last_recurring_date` | `date` | `DEFAULT NULL` |
| `create_invoice_billable` | `tinyint(1)` | `DEFAULT NULL` |
| `send_invoice_to_customer` | `tinyint(1)` | `NOT NULL` |
| `recurring_from` | `int` | `DEFAULT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |
| `addedfrom` | `int` | `NOT NULL` |

---

### Table: tblexpenses_categories

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblfiles

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `file_name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `filetype` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `visible_to_customer` | `int` | `NOT NULL DEFAULT '0'` |
| `attachment_key` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `external` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `external_link` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `thumbnail_link` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'For external usage'` |
| `staffid` | `int` | `NOT NULL` |
| `contact_id` | `int` | `DEFAULT '0'` |
| `task_comment_id` | `int` | `NOT NULL DEFAULT '0'` |
| `dateadded` | `datetime` | `NOT NULL` |

---

### Table: tblfilters

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `UNSIGNED NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `builder` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `staff_id` | `int` | `UNSIGNED NOT NULL` |
| `identifier` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `is_shared` | `tinyint` | `UNSIGNED NOT NULL DEFAULT '0'` |

---

### Table: tblfilter_defaults

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `filter_id` | `int` | `UNSIGNED NOT NULL` |
| `staff_id` | `int` | `NOT NULL` |
| `identifier` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `view` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblform_questions

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `questionid` | `int` | `NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `question` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `required` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `question_order` | `int` | `NOT NULL` |

---

### Table: tblform_question_box

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `boxid` | `int` | `NOT NULL` |
| `boxtype` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `questionid` | `int` | `NOT NULL` |

---

### Table: tblform_question_box_description

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `questionboxdescriptionid` | `int` | `NOT NULL` |
| `description` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `boxid` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `questionid` | `int` | `NOT NULL` |

---

### Table: tblform_results

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `resultid` | `int` | `NOT NULL` |
| `boxid` | `int` | `NOT NULL` |
| `boxdescriptionid` | `int` | `DEFAULT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `questionid` | `int` | `NOT NULL` |
| `answer` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `resultsetid` | `int` | `NOT NULL` |

---

### Table: tblgdpr_requests

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `clientid` | `int` | `NOT NULL DEFAULT '0'` |
| `contact_id` | `int` | `NOT NULL DEFAULT '0'` |
| `lead_id` | `int` | `NOT NULL DEFAULT '0'` |
| `request_type` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `status` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `request_date` | `datetime` | `NOT NULL` |
| `request_from` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblinvoicepaymentrecords

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `invoiceid` | `int` | `NOT NULL` |
| `amount` | `decimal(15` | `,2) NOT NULL` |
| `paymentmode` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `paymentmethod` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `date` | `date` | `NOT NULL` |
| `daterecorded` | `datetime` | `NOT NULL` |
| `note` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `transactionid` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblinvoices

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `sent` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `datesend` | `datetime` | `DEFAULT NULL` |
| `clientid` | `int` | `NOT NULL` |
| `deleted_customer_name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `number` | `int` | `NOT NULL` |
| `prefix` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `number_format` | `int` | `NOT NULL DEFAULT '0'` |
| `formatted_number` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `date` | `date` | `NOT NULL` |
| `duedate` | `date` | `DEFAULT NULL` |
| `currency` | `int` | `NOT NULL` |
| `subtotal` | `decimal(15` | `,2) NOT NULL` |
| `total_tax` | `decimal(15` | `,2) NOT NULL DEFAULT '0.00'` |
| `total` | `decimal(15` | `,2) NOT NULL` |
| `adjustment` | `decimal(15` | `,2) DEFAULT NULL` |
| `addedfrom` | `int` | `DEFAULT NULL` |
| `hash` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `status` | `int` | `DEFAULT '1'` |
| `clientnote` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `adminnote` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `last_overdue_reminder` | `date` | `DEFAULT NULL` |
| `last_due_reminder` | `date` | `DEFAULT NULL` |
| `cancel_overdue_reminders` | `int` | `NOT NULL DEFAULT '0'` |
| `allowed_payment_modes` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `token` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `discount_percent` | `decimal(15` | `,2) DEFAULT '0.00'` |
| `discount_total` | `decimal(15` | `,2) DEFAULT '0.00'` |
| `discount_type` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `recurring` | `int` | `NOT NULL DEFAULT '0'` |
| `recurring_type` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `custom_recurring` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `cycles` | `int` | `NOT NULL DEFAULT '0'` |
| `total_cycles` | `int` | `NOT NULL DEFAULT '0'` |
| `is_recurring_from` | `int` | `DEFAULT NULL` |
| `last_recurring_date` | `date` | `DEFAULT NULL` |
| `terms` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `sale_agent` | `int` | `NOT NULL DEFAULT '0'` |
| `billing_street` | `varchar(200)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_state` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_zip` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_country` | `int` | `DEFAULT NULL` |
| `shipping_street` | `varchar(200)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_state` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_zip` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_country` | `int` | `DEFAULT NULL` |
| `include_shipping` | `tinyint(1)` | `NOT NULL` |
| `show_shipping_on_invoice` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `show_quantity_as` | `int` | `NOT NULL DEFAULT '1'` |
| `project_id` | `int` | `DEFAULT '0'` |
| `subscription_id` | `int` | `NOT NULL DEFAULT '0'` |
| `short_link` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblitemable

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(15)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `long_description` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `qty` | `decimal(15` | `,2) NOT NULL` |
| `rate` | `decimal(15` | `,2) NOT NULL` |
| `unit` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `is_optional` | `tinyint` | `NOT NULL DEFAULT '0'` |
| `is_selected` | `tinyint` | `NOT NULL DEFAULT '1'` |
| `item_order` | `int` | `DEFAULT NULL` |

---

### Table: tblitems

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `description` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `long_description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `rate` | `decimal(15` | `,2) NOT NULL` |
| `tax` | `int` | `DEFAULT NULL` |
| `tax2` | `int` | `DEFAULT NULL` |
| `unit` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `group_id` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tblitems_groups

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblitem_tax

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `itemid` | `int` | `NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `taxrate` | `decimal(15` | `,2) NOT NULL` |
| `taxname` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblknowedge_base_article_feedback

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `articleanswerid` | `int` | `NOT NULL` |
| `articleid` | `int` | `NOT NULL` |
| `answer` | `int` | `NOT NULL` |
| `ip` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `date` | `datetime` | `NOT NULL` |

---
