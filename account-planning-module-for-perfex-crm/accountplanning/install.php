<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Options
add_option('accountplanning_enabled', 1);
if (!option_exists('accountplanning_reminder_days')) {
    add_option('accountplanning_reminder_days', 3);
}
if (!option_exists('accountplanning_reminders_enabled')) {
    add_option('accountplanning_reminders_enabled', 1);
}
if (!option_exists('accountplanning_industry_options')) {
    add_option('accountplanning_industry_options', '');
}
if (!option_exists('accountplanning_client_portal_enabled')) {
    add_option('accountplanning_client_portal_enabled', '1');
}

// Main accountplanning table
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `client_id` INT(11) NOT NULL,
    `vision` VARCHAR(255) NULL,
    `mission` VARCHAR(255) NULL,
    `lead_generation` VARCHAR(45) NULL,
    `current_service_know_pmax` VARCHAR(45) NULL,
    `current_service_facebook` VARCHAR(45) NULL,
    `current_service_sem` VARCHAR(45) NULL,
    `objectives` VARCHAR(255) NULL,
    `threat` VARCHAR(255) NULL,
    `opportunity` VARCHAR(255) NULL,
    `criteria_to_success` VARCHAR(255) NULL,
    `constraints` VARCHAR(255) NULL,
    `data_tree` LONGTEXT NULL,
    `latest_update` DATE NULL,
    `new_update` DATE NULL,
    `product` VARCHAR(255) NULL,
    `sale_channel_online` VARCHAR(255) NULL,
    `sale_channel_offline` VARCHAR(255) NULL,
    `industry` VARCHAR(255) NULL,
    `revenue_next_year` VARCHAR(255) NULL,
    `wallet_share` VARCHAR(255) NULL,
    `client_status` VARCHAR(255) NULL,
    `bcg_model` VARCHAR(255) NULL,
    `margin` VARCHAR(255) NULL,
    `subject` VARCHAR(255) NULL,
    `date` DATE NULL,
    `status` VARCHAR(50) NULL DEFAULT "draft",
    `approved_by` INT(11) NULL,
    `approved_date` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_client_date_status` (`client_id`, `date`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Templates
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_templates` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NULL,
    `vision` TEXT NULL,
    `mission` TEXT NULL,
    `objectives` TEXT NULL,
    `threat` TEXT NULL,
    `opportunity` TEXT NULL,
    `criteria_to_success` TEXT NULL,
    `constraints` TEXT NULL,
    `data_tree` LONGTEXT NULL,
    `datecreated` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Relations (project/invoice/estimate links)
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_relations` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `rel_type` VARCHAR(50) NOT NULL,
    `rel_id` INT(11) NOT NULL,
    `dateadded` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `accountplanning_id` (`accountplanning_id`),
    KEY `rel_type_rel_id` (`rel_type`, `rel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Saved filters
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_saved_filters` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `staffid` INT(11) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `filters` TEXT NULL,
    `datecreated` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `staffid` (`staffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Marketing activities
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_marketing_activities` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `item` VARCHAR(255) NULL,
    `reference` VARCHAR(255) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Service ability offering
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_service_ability_offering` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `service` VARCHAR(255) NULL,
    `potential` VARCHAR(255) NULL,
    `scale` VARCHAR(255) NULL,
    `convert` VARCHAR(255) NULL,
    `prioritization` VARCHAR(255) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Current service
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_current_service` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `name` VARCHAR(255) NULL,
    `potential` VARCHAR(255) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Objectives
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_objective` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `datecreated` DATE NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Items
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_items` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `objective_id` INT(11) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `datecreated` DATE NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Tasks
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_task` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `items_id` INT(11) NOT NULL,
    `accountplanning_id` INT(11) NULL,
    `action_needed` VARCHAR(255) NOT NULL,
    `prioritization` VARCHAR(255) NULL,
    `pic` VARCHAR(255) NULL,
    `deadline` DATE NULL,
    `status` VARCHAR(255) NULL,
    `objective` VARCHAR(255) NULL,
    `item` VARCHAR(255) NULL,
    `convert_to_task` VARCHAR(255) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Financial
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_financial` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `year` VARCHAR(45) NULL,
    `revenue` VARCHAR(255) NULL,
    `sales_spent` VARCHAR(255) NULL,
    `traffic` VARCHAR(255) NULL,
    `loss` VARCHAR(255) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Team
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_team` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `rel_id` VARCHAR(45) NOT NULL,
    `rel_type` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Goals & KPIs
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_goals` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `target` DECIMAL(15,2) NULL,
    `actual` DECIMAL(15,2) NULL,
    `unit` VARCHAR(50) NULL DEFAULT "number",
    `due_date` DATE NULL,
    `datecreated` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `accountplanning_id` (`accountplanning_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Webhooks
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_webhooks` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `url` VARCHAR(500) NOT NULL,
    `events` TEXT NULL,
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `datecreated` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Meeting notes
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_meeting_notes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `subject` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `meeting_date` DATE NULL,
    `datecreated` DATETIME NULL,
    `addedfrom` INT(11) NULL,
    PRIMARY KEY (`id`),
    KEY `accountplanning_id` (`accountplanning_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Competitors
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_competitors` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `threat_level` VARCHAR(50) NULL,
    `notes` TEXT NULL,
    `datecreated` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `accountplanning_id` (`accountplanning_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Update requests (client portal)
$CI->db->query('CREATE TABLE IF NOT EXISTS `' . db_prefix() . 'accountplanning_update_requests` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `accountplanning_id` INT(11) NOT NULL,
    `contact_id` INT(11) NULL,
    `dateadded` DATETIME NULL,
    `status` VARCHAR(50) NULL DEFAULT "pending",
    PRIMARY KEY (`id`),
    KEY `accountplanning_id` (`accountplanning_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

// Fix project_settings.available_features when stored as "0"/"1" (causes unserialize error in project view)
$tbl_ps = db_prefix() . 'project_settings';
if ($CI->db->table_exists($tbl_ps)) {
    $tab_settings = [];
    if (function_exists('get_project_tabs_admin')) {
        $tabs = get_project_tabs_admin();
        foreach ($tabs as $tab) {
            if (isset($tab['collapse']) && !empty($tab['children'])) {
                foreach ($tab['children'] as $d) {
                    $tab_settings[$d['slug']] = 1;
                }
            } elseif (!empty($tab['slug'])) {
                $tab_settings[$tab['slug']] = 1;
            }
        }
    }
    if (empty($tab_settings)) {
        $tab_settings = ['project_overview' => 1, 'project_tasks' => 1, 'project_milestones' => 1];
    }
    $valid_value = serialize($tab_settings);
    $CI->db->where('name', 'available_features');
    $rows = $CI->db->get($tbl_ps)->result_array();
    foreach ($rows as $row) {
        $val = $row['value'] ?? '';
        $needs_fix = (strlen($val) < 10);
        if (!$needs_fix) {
            $un = @unserialize($val);
            $needs_fix = ($un === false && $val !== serialize(false)) || !is_array($un);
        }
        if ($needs_fix) {
            $CI->db->where('id', $row['id']);
            $CI->db->update($tbl_ps, ['value' => $valid_value]);
        }
    }
}
