> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary

> [!NOTE]
> This is Part 4 of the document. [Back to Part 1](16_Table_Dictionary.md) | [Go to Part 5](16_Table_Dictionary_Part5.md)

### Table: tblproject_members

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL` |
| `staff_id` | `int` | `NOT NULL` |

---

### Table: tblproject_notes

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL` |
| `title` | `varchar(255)` | `COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `content` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `staff_id` | `int` | `NOT NULL` |
| `dateadded` | `datetime` | `NOT NULL DEFAULT CURRENT_TIMESTAMP` |

---

### Table: tblproject_settings

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL` |
| `name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `value` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblproposals

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `subject` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `content` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `addedfrom` | `int` | `NOT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `total` | `decimal(15` | `,2) DEFAULT NULL` |
| `subtotal` | `decimal(15` | `,2) NOT NULL` |
| `total_tax` | `decimal(15` | `,2) NOT NULL DEFAULT '0.00'` |
| `adjustment` | `decimal(15` | `,2) DEFAULT NULL` |
| `discount_percent` | `decimal(15` | `,2) NOT NULL` |
| `discount_total` | `decimal(15` | `,2) NOT NULL` |
| `discount_type` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `show_quantity_as` | `int` | `NOT NULL DEFAULT '1'` |
| `currency` | `int` | `NOT NULL` |
| `open_till` | `date` | `DEFAULT NULL` |
| `date` | `date` | `NOT NULL` |
| `rel_id` | `int` | `DEFAULT NULL` |
| `rel_type` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `assigned` | `int` | `DEFAULT NULL` |
| `hash` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `proposal_to` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `project_id` | `int` | `DEFAULT NULL` |
| `country` | `int` | `NOT NULL DEFAULT '0'` |
| `zip` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `state` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `address` | `varchar(200)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `email` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `phone` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `allow_comments` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `status` | `int` | `NOT NULL` |
| `estimate_id` | `int` | `DEFAULT NULL` |
| `invoice_id` | `int` | `DEFAULT NULL` |
| `date_converted` | `datetime` | `DEFAULT NULL` |
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

### Table: tblproposal_comments

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `content` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `proposalid` | `int` | `NOT NULL` |
| `staffid` | `int` | `NOT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |

---

### Table: tblrelated_items

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `item_id` | `int` | `NOT NULL` |

---

### Table: tblreminders

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `date` | `datetime` | `NOT NULL` |
| `isnotified` | `int` | `NOT NULL DEFAULT '0'` |
| `rel_id` | `int` | `NOT NULL` |
| `staff` | `int` | `NOT NULL` |
| `rel_type` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `notify_by_email` | `int` | `NOT NULL DEFAULT '1'` |
| `creator` | `int` | `NOT NULL` |

---

### Table: tblroles

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `roleid` | `int` | `NOT NULL` |
| `name` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `permissions` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblsales_activity

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `additional_data` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `staffid` | `varchar(11)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `full_name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `date` | `datetime` | `NOT NULL` |

---

### Table: tblscheduled_emails

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(15)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `scheduled_at` | `datetime` | `NOT NULL` |
| `contacts` | `varchar(197)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `cc` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `attach_pdf` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `template` | `varchar(197)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblservices

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `serviceid` | `int` | `NOT NULL` |
| `name` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblsessions

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `varchar(128)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `ip_address` | `varchar(45)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `timestamp` | `int` | `UNSIGNED NOT NULL DEFAULT '0'` |
| `data` | `blob` | `NOT NULL` |

---

### Table: tblshared_customer_files

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `file_id` | `int` | `NOT NULL` |
| `contact_id` | `int` | `NOT NULL` |

---

### Table: tblspam_filters

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `type` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `rel_type` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `value` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblstaff

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `staffid` | `int` | `NOT NULL` |
| `email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `firstname` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `lastname` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `facebook` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `linkedin` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `phonenumber` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `skype` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `password` | `varchar(250)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `profile_image` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `last_ip` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `last_login` | `datetime` | `DEFAULT NULL` |
| `last_activity` | `datetime` | `DEFAULT NULL` |
| `last_password_change` | `datetime` | `DEFAULT NULL` |
| `new_pass_key` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `new_pass_key_requested` | `datetime` | `DEFAULT NULL` |
| `admin` | `int` | `NOT NULL DEFAULT '0'` |
| `role` | `int` | `DEFAULT NULL` |
| `active` | `int` | `NOT NULL DEFAULT '1'` |
| `default_language` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `direction` | `varchar(3)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `media_path_slug` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `is_not_staff` | `int` | `NOT NULL DEFAULT '0'` |
| `hourly_rate` | `decimal(15` | `,2) NOT NULL DEFAULT '0.00'` |
| `two_factor_auth_enabled` | `tinyint(1)` | `DEFAULT '0'` |
| `two_factor_auth_code` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `two_factor_auth_code_requested` | `datetime` | `DEFAULT NULL` |
| `email_signature` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `google_auth_secret` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblstaff_departments

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `staffdepartmentid` | `int` | `NOT NULL` |
| `staffid` | `int` | `NOT NULL` |
| `departmentid` | `int` | `NOT NULL` |

---

### Table: tblstaff_permissions

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `staff_id` | `int` | `NOT NULL` |
| `feature` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `capability` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblsubscriptions

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `description_in_item` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `clientid` | `int` | `NOT NULL` |
| `date` | `date` | `DEFAULT NULL` |
| `terms` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `currency` | `int` | `NOT NULL` |
| `tax_id` | `int` | `NOT NULL DEFAULT '0'` |
| `stripe_tax_id` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `tax_id_2` | `int` | `NOT NULL DEFAULT '0'` |
| `stripe_tax_id_2` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `stripe_plan_id` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `stripe_subscription_id` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `next_billing_cycle` | `bigint` | `DEFAULT NULL` |
| `ends_at` | `bigint` | `DEFAULT NULL` |
| `status` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `quantity` | `int` | `NOT NULL DEFAULT '1'` |
| `project_id` | `int` | `NOT NULL DEFAULT '0'` |
| `hash` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `created` | `datetime` | `NOT NULL` |
| `created_from` | `int` | `NOT NULL` |
| `date_subscribed` | `datetime` | `DEFAULT NULL` |
| `in_test_environment` | `int` | `DEFAULT NULL` |
| `last_sent_at` | `datetime` | `DEFAULT NULL` |

---

### Table: tbltaggables

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `tag_id` | `int` | `NOT NULL` |
| `tag_order` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tbltags

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tbltasks

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `priority` | `int` | `DEFAULT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |
| `startdate` | `date` | `NOT NULL` |
| `duedate` | `date` | `DEFAULT NULL` |
| `datefinished` | `datetime` | `DEFAULT NULL` |
| `addedfrom` | `int` | `NOT NULL` |
| `is_added_from_contact` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `status` | `int` | `NOT NULL DEFAULT '0'` |
| `recurring_type` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `repeat_every` | `int` | `DEFAULT NULL` |
| `recurring` | `int` | `NOT NULL DEFAULT '0'` |
| `is_recurring_from` | `int` | `DEFAULT NULL` |
| `cycles` | `int` | `NOT NULL DEFAULT '0'` |
| `total_cycles` | `int` | `NOT NULL DEFAULT '0'` |
| `custom_recurring` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `last_recurring_date` | `date` | `DEFAULT NULL` |
| `rel_id` | `int` | `DEFAULT NULL` |
| `rel_type` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `is_public` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `billable` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `billed` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `invoice_id` | `int` | `NOT NULL DEFAULT '0'` |
| `hourly_rate` | `decimal(15` | `,2) NOT NULL DEFAULT '0.00'` |
| `milestone` | `int` | `DEFAULT '0'` |
| `kanban_order` | `int` | `DEFAULT '1'` |
| `milestone_order` | `int` | `NOT NULL DEFAULT '0'` |
| `visible_to_client` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `deadline_notified` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tbltaskstimers

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `task_id` | `int` | `NOT NULL` |
| `start_time` | `varchar(64)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `end_time` | `varchar(64)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `staff_id` | `int` | `NOT NULL` |
| `hourly_rate` | `decimal(15` | `,2) NOT NULL DEFAULT '0.00'` |
| `note` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tbltasks_checklist_templates

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tbltask_assigned

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `staffid` | `int` | `NOT NULL` |
| `taskid` | `int` | `NOT NULL` |
| `assigned_from` | `int` | `NOT NULL DEFAULT '0'` |
| `is_assigned_from_contact` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |

---

### Table: tbltask_checklist_items

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `taskid` | `int` | `NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `finished` | `int` | `NOT NULL DEFAULT '0'` |
| `dateadded` | `datetime` | `NOT NULL` |
| `addedfrom` | `int` | `NOT NULL` |
| `finished_from` | `int` | `DEFAULT '0'` |
| `list_order` | `int` | `NOT NULL DEFAULT '0'` |
| `assigned` | `int` | `DEFAULT NULL` |

---

### Table: tbltask_comments

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `content` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `taskid` | `int` | `NOT NULL` |
| `staffid` | `int` | `NOT NULL` |
| `contact_id` | `int` | `NOT NULL DEFAULT '0'` |
| `file_id` | `int` | `NOT NULL DEFAULT '0'` |
| `dateadded` | `datetime` | `NOT NULL` |

---

### Table: tbltask_followers

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `staffid` | `int` | `NOT NULL` |
| `taskid` | `int` | `NOT NULL` |

---
