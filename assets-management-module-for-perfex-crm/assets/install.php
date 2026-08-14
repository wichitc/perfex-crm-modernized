<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Assets Management Module - Installation Script
 * Version 1.2.0
 * 
 * This file handles fresh installations.
 * For upgrades, see migrations/120_version_120.php
 */

// ============================================
// CORE TABLES
// ============================================

if (!$CI->db->table_exists(db_prefix().'assets_group')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'assets_group` (
        `group_id` INT(11) NOT NULL AUTO_INCREMENT,
        `group_name` VARCHAR(100) NOT NULL,
        PRIMARY KEY (`group_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

if (!$CI->db->table_exists(db_prefix().'asset_unit')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_unit` (
        `unit_id` INT(11) NOT NULL AUTO_INCREMENT,
        `unit_name` VARCHAR(100) NOT NULL,
        PRIMARY KEY (`unit_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

if (!$CI->db->table_exists(db_prefix().'asset_location')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_location` (
        `location_id` INT(11) NOT NULL AUTO_INCREMENT,
        `location` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`location_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

if (!$CI->db->table_exists(db_prefix().'assets')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'assets` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `assets_code` VARCHAR(100) NOT NULL,
        `assets_name` VARCHAR(255) NOT NULL,
        `amount` INT(11) NOT NULL DEFAULT 1,
        `unit` INT(11) NOT NULL,
        `series` VARCHAR(200) NULL,
        `asset_group` INT(11) NULL,
        `asset_location` INT(11) NULL,
        `department` INT(11) NULL,
        `date_buy` DATE NOT NULL,
        `warranty_period` INT(11) NOT NULL DEFAULT 0,
        `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0,
        `depreciation` INT(11) NOT NULL DEFAULT 0,
        `supplier_name` VARCHAR(255) NULL,
        `supplier_address` VARCHAR(255) NULL,
        `supplier_phone` VARCHAR(50) NULL,
        `description` TEXT NULL,
        `belongs_to` TEXT NULL,
        `visible_to_client` TINYINT(1) NOT NULL DEFAULT 0,
        `asset_image` VARCHAR(200) NULL,
        `barcode` VARCHAR(100) NULL,
        `qr_code` VARCHAR(100) NULL,
        `manufacturer` VARCHAR(255) NULL,
        `model_number` VARCHAR(100) NULL,
        `expected_end_of_life` DATE NULL,
        `insurance_policy` VARCHAR(100) NULL,
        `insurance_expiry` DATE NULL,
        `last_maintenance_date` DATE NULL,
        `next_maintenance_date` DATE NULL,
        `maintenance_interval_days` INT(11) NULL DEFAULT 0,
        `status` INT(11) NOT NULL DEFAULT 1,
        `total_allocation` INT(11) NULL DEFAULT 0,
        `total_lost` INT(11) NULL DEFAULT 0,
        `total_liquidation` INT(11) NULL DEFAULT 0,
        `total_damages` INT(11) NULL DEFAULT 0,
        `total_warranty` INT(11) NULL DEFAULT 0,
        `created_at` DATETIME NULL,
        `updated_at` DATETIME NULL,
        `created_by` INT(11) NULL,
        `updated_by` INT(11) NULL,
        PRIMARY KEY (`id`),
        KEY `status` (`status`),
        KEY `asset_group` (`asset_group`),
        KEY `department` (`department`),
        KEY `asset_location` (`asset_location`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

if (!$CI->db->table_exists(db_prefix().'assets_acction_1')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'assets_acction_1` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `acction_code` VARCHAR(100) NOT NULL,
        `assets` INT(11) NOT NULL,
        `acction_from` INT(11) NOT NULL,
        `acction_to` INT(11) NOT NULL,
        `acction_to_type` ENUM("staff", "client", "contact") NOT NULL DEFAULT "staff",
        `amount` INT(11) NOT NULL,
        `time_acction` DATETIME NOT NULL,
        `asset_location` VARCHAR(255) NULL,
        `acction_location` VARCHAR(255) NOT NULL,
        `acction_reason` TEXT NULL,
        `type` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `assets` (`assets`),
        KEY `type` (`type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

if (!$CI->db->table_exists(db_prefix().'assets_acction_2')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'assets_acction_2` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `acction_code` VARCHAR(100) NOT NULL,
        `assets` INT(11) NOT NULL,
        `acction_from` INT(11) NOT NULL,
        `amount` INT(11) NOT NULL,
        `cost` DECIMAL(15,2) NULL,
        `time_acction` DATETIME NOT NULL,
        `description` TEXT NULL,
        `type` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `assets` (`assets`),
        KEY `type` (`type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

if (!$CI->db->table_exists(db_prefix().'inventory_history')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'inventory_history` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `assets` INT(11) NOT NULL,
        `date_time` DATETIME NOT NULL,
        `acction` VARCHAR(50) NOT NULL,
        `inventory_begin` INT(11) NULL,
        `inventory_end` INT(11) NOT NULL,
        `cost` DECIMAL(15,2) NULL,
        PRIMARY KEY (`id`),
        KEY `assets` (`assets`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// MAINTENANCE SCHEDULING TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_maintenance')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_maintenance` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NOT NULL,
        `maintenance_type` VARCHAR(50) NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `description` TEXT NULL,
        `scheduled_date` DATE NOT NULL,
        `completed_date` DATE NULL,
        `cost` DECIMAL(15,2) NULL DEFAULT 0,
        `performed_by` INT(11) NULL,
        `vendor_name` VARCHAR(255) NULL,
        `vendor_contact` VARCHAR(100) NULL,
        `status` ENUM("scheduled", "in_progress", "completed", "cancelled", "overdue") DEFAULT "scheduled",
        `is_recurring` TINYINT(1) DEFAULT 0,
        `recurring_interval` INT(11) NULL,
        `recurring_unit` ENUM("days", "weeks", "months", "years") NULL,
        `notes` TEXT NULL,
        `attachments` TEXT NULL,
        `created_at` DATETIME NULL,
        `updated_at` DATETIME NULL,
        `created_by` INT(11) NULL,
        PRIMARY KEY (`id`),
        KEY `asset_id` (`asset_id`),
        KEY `status` (`status`),
        KEY `scheduled_date` (`scheduled_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// CHECK-IN/CHECK-OUT TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_checkouts')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_checkouts` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NOT NULL,
        `checked_out_to` INT(11) NOT NULL,
        `checked_out_to_type` ENUM("staff", "client", "contact") DEFAULT "staff",
        `checkout_date` DATETIME NOT NULL,
        `expected_return_date` DATE NULL,
        `actual_return_date` DATETIME NULL,
        `checkout_notes` TEXT NULL,
        `checkin_notes` TEXT NULL,
        `checkout_condition` VARCHAR(50) DEFAULT "good",
        `checkin_condition` VARCHAR(50) NULL,
        `checkout_by` INT(11) NOT NULL,
        `checkin_by` INT(11) NULL,
        `quantity` INT(11) DEFAULT 1,
        `status` ENUM("checked_out", "returned", "overdue") DEFAULT "checked_out",
        `project_id` INT(11) NULL,
        `created_at` DATETIME NULL,
        `updated_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        KEY `asset_id` (`asset_id`),
        KEY `checked_out_to` (`checked_out_to`),
        KEY `status` (`status`),
        KEY `expected_return_date` (`expected_return_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// RESERVATIONS TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_reservations')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_reservations` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NOT NULL,
        `reserved_by` INT(11) NOT NULL,
        `reserved_by_type` ENUM("staff", "client", "contact") DEFAULT "staff",
        `reservation_start` DATETIME NOT NULL,
        `reservation_end` DATETIME NOT NULL,
        `purpose` TEXT NULL,
        `status` ENUM("pending", "approved", "rejected", "cancelled", "completed") DEFAULT "pending",
        `approved_by` INT(11) NULL,
        `approved_at` DATETIME NULL,
        `quantity` INT(11) DEFAULT 1,
        `project_id` INT(11) NULL,
        `notes` TEXT NULL,
        `created_at` DATETIME NULL,
        `updated_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        KEY `asset_id` (`asset_id`),
        KEY `reserved_by` (`reserved_by`),
        KEY `status` (`status`),
        KEY `reservation_start` (`reservation_start`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// CUSTOM FIELDS TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_custom_fields')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_custom_fields` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `field_name` VARCHAR(100) NOT NULL,
        `field_slug` VARCHAR(100) NOT NULL,
        `field_type` ENUM("text", "textarea", "number", "date", "select", "checkbox", "url") DEFAULT "text",
        `field_options` TEXT NULL,
        `required` TINYINT(1) DEFAULT 0,
        `show_on_table` TINYINT(1) DEFAULT 0,
        `field_order` INT(11) DEFAULT 0,
        `active` TINYINT(1) DEFAULT 1,
        `applies_to_groups` TEXT NULL,
        `created_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `field_slug` (`field_slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// CUSTOM FIELD VALUES TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_custom_field_values')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_custom_field_values` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NOT NULL,
        `field_id` INT(11) NOT NULL,
        `field_value` TEXT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `asset_field` (`asset_id`, `field_id`),
        KEY `asset_id` (`asset_id`),
        KEY `field_id` (`field_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// WEBHOOKS TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_webhooks')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_webhooks` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL,
        `url` VARCHAR(500) NOT NULL,
        `secret_key` VARCHAR(255) NULL,
        `events` TEXT NOT NULL,
        `active` TINYINT(1) DEFAULT 1,
        `headers` TEXT NULL,
        `created_at` DATETIME NULL,
        `updated_at` DATETIME NULL,
        `last_triggered` DATETIME NULL,
        `last_response_code` INT(11) NULL,
        `failure_count` INT(11) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// WEBHOOK LOGS TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_webhook_logs')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_webhook_logs` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `webhook_id` INT(11) NOT NULL,
        `event` VARCHAR(100) NOT NULL,
        `payload` TEXT NULL,
        `response_code` INT(11) NULL,
        `response_body` TEXT NULL,
        `created_at` DATETIME NULL,
        `execution_time` FLOAT NULL,
        PRIMARY KEY (`id`),
        KEY `webhook_id` (`webhook_id`),
        KEY `created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// NOTIFICATIONS TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_notifications')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_notifications` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `type` VARCHAR(50) NOT NULL,
        `asset_id` INT(11) NULL,
        `staff_id` INT(11) NULL,
        `title` VARCHAR(255) NOT NULL,
        `message` TEXT NULL,
        `link` VARCHAR(500) NULL,
        `is_read` TINYINT(1) DEFAULT 0,
        `read_at` DATETIME NULL,
        `created_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        KEY `staff_id` (`staff_id`),
        KEY `is_read` (`is_read`),
        KEY `type` (`type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// NOTIFICATION SETTINGS TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_notification_settings')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_notification_settings` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `notification_type` VARCHAR(50) NOT NULL,
        `enabled` TINYINT(1) DEFAULT 1,
        `email_enabled` TINYINT(1) DEFAULT 1,
        `days_before` INT(11) DEFAULT 7,
        `recipients` TEXT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `notification_type` (`notification_type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

    // Insert default notification settings
    $CI->db->query('INSERT INTO `'.db_prefix().'asset_notification_settings` 
        (`notification_type`, `enabled`, `email_enabled`, `days_before`) VALUES
        ("warranty_expiry", 1, 1, 30),
        ("insurance_expiry", 1, 1, 30),
        ("maintenance_due", 1, 1, 7),
        ("checkout_overdue", 1, 1, 1),
        ("reservation_reminder", 1, 1, 1),
        ("low_stock", 1, 1, 0),
        ("asset_end_of_life", 1, 1, 30)');
}

// ============================================
// AUDIT LOG TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_audit_log')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_audit_log` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NULL,
        `action` VARCHAR(50) NOT NULL,
        `description` TEXT NULL,
        `old_values` TEXT NULL,
        `new_values` TEXT NULL,
        `performed_by` INT(11) NULL,
        `ip_address` VARCHAR(45) NULL,
        `user_agent` VARCHAR(255) NULL,
        `created_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        KEY `asset_id` (`asset_id`),
        KEY `action` (`action`),
        KEY `performed_by` (`performed_by`),
        KEY `created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// ASSET TRANSFERS TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_transfers')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_transfers` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NOT NULL,
        `from_location` INT(11) NULL,
        `to_location` INT(11) NOT NULL,
        `from_department` INT(11) NULL,
        `to_department` INT(11) NULL,
        `quantity` INT(11) DEFAULT 1,
        `transfer_date` DATETIME NOT NULL,
        `reason` TEXT NULL,
        `transferred_by` INT(11) NOT NULL,
        `received_by` INT(11) NULL,
        `received_at` DATETIME NULL,
        `status` ENUM("pending", "in_transit", "completed", "cancelled") DEFAULT "pending",
        `notes` TEXT NULL,
        `created_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        KEY `asset_id` (`asset_id`),
        KEY `status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// ASSET - PROJECT RELATIONSHIP TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_project_rel')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_project_rel` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NOT NULL,
        `project_id` INT(11) NOT NULL,
        `assigned_date` DATE NULL,
        `removed_date` DATE NULL,
        `quantity` INT(11) DEFAULT 1,
        `notes` TEXT NULL,
        `created_at` DATETIME NULL,
        `created_by` INT(11) NULL,
        PRIMARY KEY (`id`),
        KEY `asset_id` (`asset_id`),
        KEY `project_id` (`project_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// ASSET EXPENSES TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_expenses')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_expenses` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NOT NULL,
        `expense_id` INT(11) NULL,
        `expense_type` VARCHAR(50) NOT NULL,
        `amount` DECIMAL(15,2) NOT NULL,
        `date` DATE NOT NULL,
        `description` TEXT NULL,
        `receipt` VARCHAR(255) NULL,
        `created_at` DATETIME NULL,
        `created_by` INT(11) NULL,
        PRIMARY KEY (`id`),
        KEY `asset_id` (`asset_id`),
        KEY `expense_id` (`expense_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// ASSET DEPRECIATION SCHEDULE TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_depreciation_schedule')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_depreciation_schedule` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asset_id` INT(11) NOT NULL,
        `period_start` DATE NOT NULL,
        `period_end` DATE NOT NULL,
        `opening_value` DECIMAL(15,2) NOT NULL,
        `depreciation_amount` DECIMAL(15,2) NOT NULL,
        `closing_value` DECIMAL(15,2) NOT NULL,
        `created_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        KEY `asset_id` (`asset_id`),
        KEY `period_start` (`period_start`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// IMPORT HISTORY TABLE
// ============================================
if (!$CI->db->table_exists(db_prefix().'asset_import_history')) {
    $CI->db->query('CREATE TABLE `'.db_prefix().'asset_import_history` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `filename` VARCHAR(255) NOT NULL,
        `total_rows` INT(11) DEFAULT 0,
        `imported_rows` INT(11) DEFAULT 0,
        `failed_rows` INT(11) DEFAULT 0,
        `errors` TEXT NULL,
        `imported_by` INT(11) NOT NULL,
        `created_at` DATETIME NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ============================================
// UPGRADE CHECKS FOR EXISTING INSTALLATIONS
// These handle cases where install.php runs on an existing database
// ============================================

// Add new fields to assets table if missing (for upgrades)
if ($CI->db->table_exists(db_prefix().'assets')) {
    // Fix supplier_phone column type
    if ($CI->db->field_exists('supplier_phone', db_prefix().'assets')) {
        $field_info = $CI->db->query("SHOW COLUMNS FROM `".db_prefix()."assets` WHERE Field = 'supplier_phone'")->row();
        if ($field_info && strpos(strtolower($field_info->Type), 'int') !== false) {
            $CI->db->query('ALTER TABLE `'.db_prefix().'assets` MODIFY `supplier_phone` VARCHAR(50) NULL');
        }
    }

    // Add v1.1.0 fields
    if (!$CI->db->field_exists('belongs_to', db_prefix().'assets')) {
        $CI->db->query('ALTER TABLE `'.db_prefix().'assets`
            ADD `belongs_to` TEXT NULL DEFAULT NULL AFTER `description`,
            ADD `visible_to_client` TINYINT(1) NOT NULL DEFAULT 0 AFTER `belongs_to`,
            ADD `asset_image` VARCHAR(200) NULL DEFAULT NULL AFTER `visible_to_client`');
    }

    // Add v1.2.0 fields
    if (!$CI->db->field_exists('barcode', db_prefix().'assets')) {
        $CI->db->query('ALTER TABLE `'.db_prefix().'assets` 
            ADD `barcode` VARCHAR(100) NULL AFTER `asset_image`,
            ADD `qr_code` VARCHAR(100) NULL AFTER `barcode`,
            ADD `manufacturer` VARCHAR(255) NULL AFTER `qr_code`,
            ADD `model_number` VARCHAR(100) NULL AFTER `manufacturer`,
            ADD `expected_end_of_life` DATE NULL AFTER `model_number`,
            ADD `insurance_policy` VARCHAR(100) NULL AFTER `expected_end_of_life`,
            ADD `insurance_expiry` DATE NULL AFTER `insurance_policy`,
            ADD `last_maintenance_date` DATE NULL AFTER `insurance_expiry`,
            ADD `next_maintenance_date` DATE NULL AFTER `last_maintenance_date`,
            ADD `maintenance_interval_days` INT(11) NULL DEFAULT 0 AFTER `next_maintenance_date`');
    }

    if (!$CI->db->field_exists('created_at', db_prefix().'assets')) {
        $CI->db->query('ALTER TABLE `'.db_prefix().'assets` 
            ADD `created_at` DATETIME NULL,
            ADD `updated_at` DATETIME NULL,
            ADD `created_by` INT(11) NULL,
            ADD `updated_by` INT(11) NULL');
    }
}
