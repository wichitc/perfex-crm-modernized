> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary

> [!NOTE]
> This is Part 3 of the document. [Back to Part 1](16_Table_Dictionary.md) | [Go to Part 4](16_Table_Dictionary_Part4.md)

### Table: tblknowledge_base

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `articleid` | `int` | `NOT NULL` |
| `articlegroup` | `int` | `NOT NULL` |
| `subject` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `slug` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `active` | `tinyint` | `NOT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `article_order` | `int` | `NOT NULL DEFAULT '0'` |
| `staff_article` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tblknowledge_base_groups

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `groupid` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `group_slug` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `description` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `active` | `tinyint` | `NOT NULL` |
| `color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#28B8DA'` |
| `group_order` | `int` | `DEFAULT '0'` |

---

### Table: tblleads

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `hash` | `varchar(65)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `title` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `company` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `country` | `int` | `NOT NULL DEFAULT '0'` |
| `zip` | `varchar(15)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `state` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `address` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `assigned` | `int` | `NOT NULL DEFAULT '0'` |
| `dateadded` | `datetime` | `NOT NULL` |
| `from_form_id` | `int` | `NOT NULL DEFAULT '0'` |
| `status` | `int` | `NOT NULL` |
| `source` | `int` | `NOT NULL` |
| `lastcontact` | `datetime` | `DEFAULT NULL` |
| `dateassigned` | `date` | `DEFAULT NULL` |
| `last_status_change` | `datetime` | `DEFAULT NULL` |
| `addedfrom` | `int` | `NOT NULL` |
| `email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `website` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `leadorder` | `int` | `DEFAULT '1'` |
| `phonenumber` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `date_converted` | `datetime` | `DEFAULT NULL` |
| `lost` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `junk` | `int` | `NOT NULL DEFAULT '0'` |
| `last_lead_status` | `int` | `NOT NULL DEFAULT '0'` |
| `is_imported_from_email_integration` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `email_integration_uid` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `is_public` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `default_language` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `client_id` | `int` | `NOT NULL DEFAULT '0'` |
| `lead_value` | `decimal(15` | `,2) DEFAULT NULL` |

---

### Table: tblleads_email_integration

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL COMMENT 'the ID always must be 1'` |
| `active` | `int` | `NOT NULL` |
| `email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `imap_server` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `password` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `check_every` | `int` | `NOT NULL DEFAULT '5'` |
| `responsible` | `int` | `NOT NULL` |
| `lead_source` | `int` | `NOT NULL` |
| `lead_status` | `int` | `NOT NULL` |
| `encryption` | `varchar(3)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `folder` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `last_run` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `notify_lead_imported` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `notify_lead_contact_more_times` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `notify_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `notify_ids` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `mark_public` | `int` | `NOT NULL DEFAULT '0'` |
| `only_loop_on_unseen_emails` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `delete_after_import` | `int` | `NOT NULL DEFAULT '0'` |
| `create_task_if_customer` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tblleads_sources

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblleads_status

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `statusorder` | `int` | `DEFAULT NULL` |
| `color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#28B8DA'` |
| `isdefault` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tbllead_activity_log

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `leadid` | `int` | `NOT NULL` |
| `description` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `additional_data` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `date` | `datetime` | `NOT NULL` |
| `staffid` | `int` | `NOT NULL` |
| `full_name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `custom_activity` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |

---

### Table: tbllead_integration_emails

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `subject` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `body` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `dateadded` | `datetime` | `NOT NULL` |
| `leadid` | `int` | `NOT NULL` |
| `emailid` | `int` | `NOT NULL` |

---

### Table: tblmail_queue

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `engine` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `email` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `cc` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `bcc` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `message` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `alt_message` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `status` | `enum(` | `'pending','sending','sent','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `date` | `datetime` | `DEFAULT NULL` |
| `headers` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `attachments` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblmigrations

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `version` | `bigint` | `NOT NULL` |

---

### Table: tblmilestones

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `description_visible_to_customer` | `tinyint(1)` | `DEFAULT '0'` |
| `start_date` | `date` | `DEFAULT NULL` |
| `due_date` | `date` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL` |
| `color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `milestone_order` | `int` | `NOT NULL DEFAULT '0'` |
| `datecreated` | `date` | `NOT NULL` |
| `hide_from_customer` | `int` | `DEFAULT '0'` |

---

### Table: tblmodules

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `module_name` | `varchar(55)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `installed_version` | `varchar(11)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `active` | `tinyint(1)` | `NOT NULL` |

---

### Table: tblnewsfeed_comment_likes

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `postid` | `int` | `NOT NULL` |
| `commentid` | `int` | `NOT NULL` |
| `userid` | `int` | `NOT NULL` |
| `dateliked` | `datetime` | `NOT NULL` |

---

### Table: tblnewsfeed_posts

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `postid` | `int` | `NOT NULL` |
| `creator` | `int` | `NOT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `visibility` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `content` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `pinned` | `int` | `NOT NULL` |
| `datepinned` | `datetime` | `DEFAULT NULL` |

---

### Table: tblnewsfeed_post_comments

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `content` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `userid` | `int` | `NOT NULL` |
| `postid` | `int` | `NOT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |

---

### Table: tblnewsfeed_post_likes

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `postid` | `int` | `NOT NULL` |
| `userid` | `int` | `NOT NULL` |
| `dateliked` | `datetime` | `NOT NULL` |

---

### Table: tblnotes

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `date_contacted` | `datetime` | `DEFAULT NULL` |
| `addedfrom` | `int` | `NOT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |

---

### Table: tblnotifications

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `isread` | `int` | `NOT NULL DEFAULT '0'` |
| `isread_inline` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `date` | `datetime` | `NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `fromuserid` | `int` | `NOT NULL` |
| `fromclientid` | `int` | `NOT NULL DEFAULT '0'` |
| `from_fullname` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `touserid` | `int` | `NOT NULL` |
| `fromcompany` | `int` | `DEFAULT NULL` |
| `link` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `additional_data` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tbloptions

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `value` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `autoload` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |

---

### Table: tblpayment_attempts

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `UNSIGNED NOT NULL` |
| `reference` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `invoice_id` | `int` | `NOT NULL` |
| `amount` | `double` | `NOT NULL` |
| `fee` | `double` | `NOT NULL` |
| `payment_gateway` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `created_at` | `datetime` | `NOT NULL` |

---

### Table: tblpayment_modes

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `show_on_pdf` | `int` | `NOT NULL DEFAULT '0'` |
| `invoices_only` | `int` | `NOT NULL DEFAULT '0'` |
| `expenses_only` | `int` | `NOT NULL DEFAULT '0'` |
| `selected_by_default` | `int` | `NOT NULL DEFAULT '1'` |
| `active` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |

---

### Table: tblpinned_projects

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL` |
| `staff_id` | `int` | `NOT NULL` |

---

### Table: tblprojectdiscussioncomments

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `discussion_id` | `int` | `NOT NULL` |
| `discussion_type` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `parent` | `int` | `DEFAULT NULL` |
| `created` | `datetime` | `NOT NULL` |
| `modified` | `datetime` | `DEFAULT NULL` |
| `content` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `staff_id` | `int` | `NOT NULL` |
| `contact_id` | `int` | `DEFAULT '0'` |
| `fullname` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `file_name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `file_mime_type` | `varchar(70)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblprojectdiscussions

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL` |
| `subject` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `show_to_customer` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `datecreated` | `datetime` | `NOT NULL` |
| `last_activity` | `datetime` | `DEFAULT NULL` |
| `staff_id` | `int` | `NOT NULL DEFAULT '0'` |
| `contact_id` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tblprojects

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `status` | `int` | `NOT NULL DEFAULT '0'` |
| `clientid` | `int` | `NOT NULL` |
| `billing_type` | `int` | `NOT NULL` |
| `start_date` | `date` | `NOT NULL` |
| `deadline` | `date` | `DEFAULT NULL` |
| `project_created` | `date` | `NOT NULL` |
| `date_finished` | `datetime` | `DEFAULT NULL` |
| `progress` | `int` | `DEFAULT '0'` |
| `progress_from_tasks` | `int` | `NOT NULL DEFAULT '1'` |
| `project_cost` | `decimal(15` | `,2) DEFAULT NULL` |
| `project_rate_per_hour` | `decimal(15` | `,2) DEFAULT NULL` |
| `estimated_hours` | `decimal(15` | `,2) DEFAULT NULL` |
| `addedfrom` | `int` | `NOT NULL` |
| `contact_notification` | `int` | `DEFAULT '1'` |
| `notify_contacts` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblproject_activity

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL` |
| `staff_id` | `int` | `NOT NULL DEFAULT '0'` |
| `contact_id` | `int` | `NOT NULL DEFAULT '0'` |
| `fullname` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `visible_to_customer` | `int` | `NOT NULL DEFAULT '0'` |
| `description_key` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Language file key'` |
| `additional_data` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `dateadded` | `datetime` | `NOT NULL` |

---

### Table: tblproject_files

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `file_name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `original_file_name` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `subject` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `filetype` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |
| `last_activity` | `datetime` | `DEFAULT NULL` |
| `project_id` | `int` | `NOT NULL` |
| `visible_to_customer` | `tinyint(1)` | `DEFAULT '0'` |
| `staffid` | `int` | `NOT NULL` |
| `contact_id` | `int` | `NOT NULL DEFAULT '0'` |
| `external` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `external_link` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `thumbnail_link` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---
