> [!NOTE]
> **Table Dictionary Parts Navigation**:
> [Part 1](16_Table_Dictionary.md) \| [Part 2](16_Table_Dictionary_Part2.md) \| [Part 3](16_Table_Dictionary_Part3.md) \| [Part 4](16_Table_Dictionary_Part4.md) \| [Part 5](16_Table_Dictionary_Part5.md) \| [Part 6](16_Table_Dictionary_Part6.md) \| [Part 7](16_Table_Dictionary_Part7.md) \| [Part 8](16_Table_Dictionary_Part8.md) \| [Part 9](16_Table_Dictionary_Part9.md) \| [Part 10](16_Table_Dictionary_Part10.md) \| [Part 11](16_Table_Dictionary_Part11.md) \| [Part 12](16_Table_Dictionary_Part12.md)

# Table Dictionary

## Purpose
Lists schema fields, data types, primary keys, and constraints for all tables.

## Scope
Comprehensive data dictionary containing all tables found in the parsed database sql.

## Detailed Explanation
This dictionary maps each database table name, its columns, and properties as parsed from `database.sql`.

### Table: tblactivity_log

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `description` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `date` | `datetime` | `NOT NULL` |
| `staffid` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblannouncements

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `announcementid` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `message` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `showtousers` | `int` | `NOT NULL` |
| `showtostaff` | `int` | `NOT NULL` |
| `showname` | `int` | `NOT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |
| `userid` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblclients

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `userid` | `int` | `NOT NULL` |
| `company` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `vat` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `phonenumber` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `country` | `int` | `NOT NULL DEFAULT '0'` |
| `city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `zip` | `varchar(15)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `state` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `address` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `website` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `active` | `int` | `NOT NULL DEFAULT '1'` |
| `leadid` | `int` | `DEFAULT NULL` |
| `billing_street` | `varchar(200)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_state` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_zip` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `billing_country` | `int` | `DEFAULT '0'` |
| `shipping_street` | `varchar(200)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_city` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_state` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_zip` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `shipping_country` | `int` | `DEFAULT '0'` |
| `longitude` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `latitude` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `default_language` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `default_currency` | `int` | `NOT NULL DEFAULT '0'` |
| `show_primary_contact` | `int` | `NOT NULL DEFAULT '0'` |
| `stripe_id` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `registration_confirmed` | `int` | `NOT NULL DEFAULT '1'` |
| `addedfrom` | `int` | `NOT NULL DEFAULT '0'` |

---

### Table: tblconsents

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `action` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `date` | `datetime` | `NOT NULL` |
| `ip` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `contact_id` | `int` | `NOT NULL DEFAULT '0'` |
| `lead_id` | `int` | `NOT NULL DEFAULT '0'` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `opt_in_purpose_description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `purpose_id` | `int` | `NOT NULL` |
| `staff_name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblconsent_purposes

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `date_created` | `datetime` | `NOT NULL` |
| `last_updated` | `datetime` | `DEFAULT NULL` |

---

### Table: tblcontacts

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `userid` | `int` | `NOT NULL` |
| `is_primary` | `int` | `NOT NULL DEFAULT '1'` |
| `firstname` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `lastname` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `phonenumber` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `title` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `password` | `varchar(255)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `new_pass_key` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `new_pass_key_requested` | `datetime` | `DEFAULT NULL` |
| `email_verified_at` | `datetime` | `DEFAULT NULL` |
| `email_verification_key` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `email_verification_sent_at` | `datetime` | `DEFAULT NULL` |
| `last_ip` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `last_login` | `datetime` | `DEFAULT NULL` |
| `last_password_change` | `datetime` | `DEFAULT NULL` |
| `active` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `profile_image` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `direction` | `varchar(3)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `invoice_emails` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `estimate_emails` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `credit_note_emails` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `contract_emails` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `task_emails` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `project_emails` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `ticket_emails` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |

---

### Table: tblcontact_permissions

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `permission_id` | `int` | `NOT NULL` |
| `userid` | `int` | `NOT NULL` |

---

### Table: tblcontracts

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `content` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `description` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `subject` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `client` | `int` | `NOT NULL` |
| `datestart` | `date` | `DEFAULT NULL` |
| `dateend` | `date` | `DEFAULT NULL` |
| `contract_type` | `int` | `DEFAULT NULL` |
| `project_id` | `int` | `DEFAULT NULL` |
| `addedfrom` | `int` | `NOT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |
| `isexpirynotified` | `int` | `NOT NULL DEFAULT '0'` |
| `contract_value` | `decimal(15` | `,2) DEFAULT NULL` |
| `trash` | `tinyint(1)` | `DEFAULT '0'` |
| `not_visible_to_client` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `hash` | `varchar(32)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `signed` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `signature` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `marked_as_signed` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `acceptance_firstname` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `acceptance_lastname` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `acceptance_email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `acceptance_date` | `datetime` | `DEFAULT NULL` |
| `acceptance_ip` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `short_link` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `last_sent_at` | `datetime` | `DEFAULT NULL` |
| `contacts_sent_to` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `last_sign_reminder_at` | `datetime` | `DEFAULT NULL` |

---

### Table: tblcontracts_types

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblcontract_comments

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `content` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `contract_id` | `int` | `NOT NULL` |
| `staffid` | `int` | `NOT NULL` |
| `dateadded` | `datetime` | `NOT NULL` |

---

### Table: tblcontract_renewals

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `contractid` | `int` | `NOT NULL` |
| `old_start_date` | `date` | `NOT NULL` |
| `new_start_date` | `date` | `NOT NULL` |
| `old_end_date` | `date` | `DEFAULT NULL` |
| `new_end_date` | `date` | `DEFAULT NULL` |
| `old_value` | `decimal(15` | `,2) DEFAULT NULL` |
| `new_value` | `decimal(15` | `,2) DEFAULT NULL` |
| `date_renewed` | `datetime` | `NOT NULL` |
| `renewed_by` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `renewed_by_staff_id` | `int` | `NOT NULL DEFAULT '0'` |
| `is_on_old_expiry_notified` | `int` | `DEFAULT '0'` |

---

### Table: tblcountries

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `country_id` | `int` | `NOT NULL` |
| `iso2` | `char(2)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `short_name` | `varchar(80)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''` |
| `long_name` | `varchar(80)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''` |
| `iso3` | `char(3)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `numcode` | `varchar(6)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `un_member` | `varchar(12)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `calling_code` | `varchar(8)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `cctld` | `varchar(5)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblcreditnotes

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `clientid` | `int` | `NOT NULL` |
| `deleted_customer_name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `number` | `int` | `NOT NULL` |
| `prefix` | `varchar(50)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `number_format` | `int` | `NOT NULL DEFAULT '1'` |
| `formatted_number` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `datecreated` | `datetime` | `NOT NULL` |
| `date` | `date` | `NOT NULL` |
| `adminnote` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `terms` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `clientnote` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `currency` | `int` | `NOT NULL` |
| `subtotal` | `decimal(15` | `,2) NOT NULL` |
| `total_tax` | `decimal(15` | `,2) NOT NULL DEFAULT '0.00'` |
| `total` | `decimal(15` | `,2) NOT NULL` |
| `adjustment` | `decimal(15` | `,2) DEFAULT NULL` |
| `addedfrom` | `int` | `DEFAULT NULL` |
| `status` | `int` | `DEFAULT '1'` |
| `project_id` | `int` | `NOT NULL DEFAULT '0'` |
| `discount_percent` | `decimal(15` | `,2) DEFAULT '0.00'` |
| `discount_total` | `decimal(15` | `,2) DEFAULT '0.00'` |
| `discount_type` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
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
| `show_shipping_on_credit_note` | `tinyint(1)` | `NOT NULL DEFAULT '1'` |
| `show_quantity_as` | `int` | `NOT NULL DEFAULT '1'` |
| `reference_no` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |

---

### Table: tblcreditnote_refunds

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `credit_note_id` | `int` | `NOT NULL` |
| `staff_id` | `int` | `NOT NULL` |
| `refunded_on` | `date` | `NOT NULL` |
| `payment_mode` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `note` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `amount` | `decimal(15` | `,2) NOT NULL` |
| `created_at` | `datetime` | `DEFAULT NULL` |

---

### Table: tblcredits

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `invoice_id` | `int` | `NOT NULL` |
| `credit_id` | `int` | `NOT NULL` |
| `staff_id` | `int` | `NOT NULL` |
| `date` | `date` | `NOT NULL` |
| `date_applied` | `datetime` | `NOT NULL` |
| `amount` | `decimal(15` | `,2) NOT NULL` |

---

### Table: tblcurrencies

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `symbol` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `decimal_separator` | `varchar(5)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `thousand_separator` | `varchar(5)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `placement` | `varchar(10)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `isdefault` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |

---

### Table: tblcustomers_groups

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `name` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblcustomer_admins

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `staff_id` | `int` | `NOT NULL` |
| `customer_id` | `int` | `NOT NULL` |
| `date_assigned` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tblcustomer_groups

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `groupid` | `int` | `NOT NULL` |
| `customer_id` | `int` | `NOT NULL` |

---

### Table: tblcustomfields

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `fieldto` | `varchar(30)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `name` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `slug` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `required` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `type` | `varchar(20)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `options` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `display_inline` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `field_order` | `int` | `DEFAULT '0'` |
| `active` | `int` | `NOT NULL DEFAULT '1'` |
| `show_on_pdf` | `int` | `NOT NULL DEFAULT '0'` |
| `show_on_ticket_form` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `only_admin` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `show_on_table` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `show_on_client_portal` | `int` | `NOT NULL DEFAULT '0'` |
| `disalow_client_to_edit` | `int` | `NOT NULL DEFAULT '0'` |
| `bs_column` | `int` | `NOT NULL DEFAULT '12'` |
| `default_value` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |

---

### Table: tblcustomfieldsvalues

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `id` | `int` | `NOT NULL` |
| `relid` | `int` | `NOT NULL` |
| `fieldid` | `int` | `NOT NULL` |
| `fieldto` | `varchar(15)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `value` | `mediumtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |

---

### Table: tbldepartments

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `departmentid` | `int` | `NOT NULL` |
| `name` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `imap_username` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `email` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `email_from_header` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |
| `host` | `varchar(150)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `password` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `encryption` | `varchar(3)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `folder` | `varchar(191)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INBOX'` |
| `delete_after_import` | `int` | `NOT NULL DEFAULT '0'` |
| `calendar_id` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` |
| `hidefromclient` | `tinyint(1)` | `NOT NULL DEFAULT '0'` |

---

### Table: tbldismissed_announcements

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `dismissedannouncementid` | `int` | `NOT NULL` |
| `announcementid` | `int` | `NOT NULL` |
| `staff` | `int` | `NOT NULL` |
| `userid` | `int` | `NOT NULL` |

---

### Table: tblemailtemplates

#### Columns:
| Name | Type | Extra Properties |
| --- | --- | --- |
| `emailtemplateid` | `int` | `NOT NULL` |
| `type` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `slug` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `language` | `varchar(40)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `name` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `subject` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `message` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `fromname` | `longtext` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL` |
| `fromemail` | `varchar(100)` | `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL` |
| `plaintext` | `int` | `NOT NULL DEFAULT '0'` |
| `active` | `tinyint` | `NOT NULL DEFAULT '0'` |
| `order` | `int` | `NOT NULL` |

---
