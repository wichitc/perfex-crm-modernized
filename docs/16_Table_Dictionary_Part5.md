> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary

> [!NOTE]
> This is Part 5 of the document. [Back to Part 1](16_Table_Dictionary.md) | 

### Table: tbltaxes

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `taxrate` | `decimal(15` | `,2) NOT NULL` |

---

### Table: tbltemplates

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `UNSIGNED NOT NULL` |
| `name` | `varchar(255)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `type` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `addedfrom` | `int` | `NOT NULL` |
| `content` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `content_type` | `varchar(20)` | `COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'html'` |

---

### Table: tbltickets

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `ticketid` | `int` | `NOT NULL` |
| `adminreplying` | `int` | `NOT NULL DEFAULT '0'` |
| `userid` | `int` | `NOT NULL` |
| `contactid` | `int` | `NOT NULL DEFAULT '0'` |
| `merged_ticket_id` | `int` | `DEFAULT NULL` |
| `email` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `name` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `department` | `int` | `NOT NULL` |
| `priority` | `int` | `NOT NULL` |
| `status` | `int` | `NOT NULL` |
| `service` | `int` | `DEFAULT NULL` |
| `ticketkey` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `subject` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `message` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `admin` | `int` | `DEFAULT NULL` |
| `date` | `datetime` | `NOT NULL` |
| `project_id` | `int` | `NOT NULL DEFAULT '0'` |
| `lastreply` | `datetime` | `DEFAULT NULL` |
| `clientread` | `int` | `NOT NULL DEFAULT '0'` |
| `adminread` | `int` | `NOT NULL DEFAULT '0'` |
| `assigned` | `int` | `NOT NULL DEFAULT '0'` |
| `staff_id_replying` | `int` | `DEFAULT NULL` |
| `cc` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tbltickets_pipe_log

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `date` | `datetime` | `NOT NULL` |
| `email_to` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `subject` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `message` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `status` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tbltickets_predefined_replies

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `message` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tbltickets_priorities

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `priorityid` | `int` | `NOT NULL` |
| `name` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tbltickets_status

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `ticketstatusid` | `int` | `NOT NULL` |
| `name` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `isdefault` | `int` | `NOT NULL DEFAULT '0'` |
| `statuscolor` | `varchar(7)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `statusorder` | `int` | `DEFAULT NULL` |

---

### Table: tblticket_attachments

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `ticketid` | `int` | `NOT NULL` |
| `replyid` | `int` | `DEFAULT NULL` |
| `file_name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `filetype` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |

---

### Table: tblticket_replies

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `ticketid` | `int` | `NOT NULL` |
| `userid` | `int` | `DEFAULT NULL` |
| `contactid` | `int` | `NOT NULL DEFAULT '0'` |
| `name` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `email` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `date` | `datetime` | `NOT NULL` |
| `message` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `attachment` | `int` | `DEFAULT NULL` |
| `admin` | `int` | `DEFAULT NULL` |

---

### Table: tbltodos

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `todoid` | `int` | `NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `staffid` | `int` | `NOT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |
| `finished` | `tinyint(1)` | `NOT NULL` |
| `datefinished` | `datetime` | `DEFAULT NULL` |
| `item_order` | `int` | `DEFAULT NULL` |

---

### Table: tbltracked_mails

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `uid` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `date` | `datetime` | `NOT NULL` |
| `email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `opened` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `date_opened` | `datetime` | `DEFAULT NULL` |
| `subject` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tbltwocheckout_log

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `UNSIGNED NOT NULL` |
| `reference` | `varchar(64)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `invoice_id` | `int` | `NOT NULL` |
| `amount` | `varchar(25)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `created_at` | `datetime` | `NOT NULL` |
| `attempt_reference` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tbluser_auto_login

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `key_id` | `varchar(64)` | `COLLATE utf8mb4_unicode_ci NOT NULL` |
| `user_id` | `int` | `NOT NULL` |
| `user_agent` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `last_ip` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `last_login` | `timestamp` | `NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |
| `staff` | `int` | `NOT NULL` |

---

### Table: tbluser_meta

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `umeta_id` | `bigint` | `UNSIGNED NOT NULL` |
| `staff_id` | `bigint` | `UNSIGNED NOT NULL DEFAULT '0'` |
| `client_id` | `bigint` | `UNSIGNED NOT NULL DEFAULT '0'` |
| `contact_id` | `bigint` | `UNSIGNED NOT NULL DEFAULT '0'` |
| `meta_key` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `meta_value` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblvault

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `customer_id` | `int` | `NOT NULL` |
| `server_address` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `port` | `int` | `DEFAULT NULL` |
| `username` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `password` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `creator` | `int` | `NOT NULL` |
| `creator_name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `visibility` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `share_in_projects` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `last_updated` | `datetime` | `DEFAULT NULL` |
| `last_updated_from` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `date_created` | `datetime` | `NOT NULL` |

---

### Table: tblviews_tracking

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `rel_id` | `int` | `NOT NULL` |
| `rel_type` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `date` | `datetime` | `NOT NULL` |
| `view_ip` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblweb_to_lead

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `form_key` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `lead_source` | `int` | `NOT NULL` |
| `lead_status` | `int` | `NOT NULL` |
| `notify_lead_imported` | `int` | `NOT NULL DEFAULT '1'` |
| `notify_type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `notify_ids` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `responsible` | `int` | `NOT NULL DEFAULT '0'` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `form_data` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `recaptcha` | `int` | `NOT NULL DEFAULT '0'` |
| `submit_btn_name` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `submit_btn_text_color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff'` |
| `submit_btn_bg_color` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#84c529'` |
| `success_submit_msg` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `submit_action` | `int` | `DEFAULT '0'` |
| `lead_name_prefix` | `varchar(255)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `submit_redirect_url` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `language` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `allow_duplicate` | `int` | `NOT NULL DEFAULT '1'` |
| `mark_public` | `int` | `NOT NULL DEFAULT '0'` |
| `track_duplicate_field` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `track_duplicate_field_and` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `create_task_on_duplicate` | `int` | `NOT NULL DEFAULT '0'` |
| `dateadded` | `datetime` | `NOT NULL` |

---

