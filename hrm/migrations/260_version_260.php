<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 2.6.0
 * Adds tables for: Performance, Learning, Engagement, Documents, Helpdesk, Assets,
 * Onboarding, Layoff, Policies, Dependants, Training, Job Descriptions
 * Uses if-not-exists for safe upgrades from any previous version.
 */

class Migration_Version_260 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // Timesheet
        if (!$CI->db->table_exists(db_prefix() . 'hrm_timesheet')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_timesheet` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `date_work` date NOT NULL,
                `value` text NULL,
                `type` varchar(45) NULL,
                `add_from` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Job Description Groups
        if (!$CI->db->table_exists(db_prefix() . 'hrm_job_description_groups')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_job_description_groups` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if ($CI->db->table_exists(db_prefix() . 'job_position')) {
            if (!$CI->db->field_exists('job_description_group_id', db_prefix() . 'job_position')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'job_position` ADD COLUMN `job_description_group_id` int(11) NULL');
            }
            if (!$CI->db->field_exists('duties_responsibilities', db_prefix() . 'job_position')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'job_position` ADD COLUMN `duties_responsibilities` LONGTEXT NULL');
            }
        }

        // Dependants
        if (!$CI->db->table_exists(db_prefix() . 'hrm_dependants')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_dependants` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `full_name` varchar(255) NOT NULL,
                `relationship` varchar(100) NULL,
                `date_of_birth` date NULL,
                `id_number` varchar(100) NULL,
                `notes` text NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Training Types
        if (!$CI->db->table_exists(db_prefix() . 'hrm_training_types')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_training_types` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Staff Trainings
        if (!$CI->db->table_exists(db_prefix() . 'hrm_staff_trainings')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_staff_trainings` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `training_type_id` int(11) NULL,
                `training_name` varchar(255) NULL,
                `completed_date` date NULL,
                `certificate_number` varchar(255) NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Onboarding Templates
        if (!$CI->db->table_exists(db_prefix() . 'hrm_onboarding_templates')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_onboarding_templates` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text NULL,
                `checklist_items` LONGTEXT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Onboarding Records
        if (!$CI->db->table_exists(db_prefix() . 'hrm_onboarding_records')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_onboarding_records` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `template_id` int(11) NULL,
                `status` varchar(50) NULL,
                `started_date` date NULL,
                `completed_date` date NULL,
                `checklist_data` LONGTEXT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Layoff Checklist
        if (!$CI->db->table_exists(db_prefix() . 'hrm_layoff_checklist')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_layoff_checklist` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text NULL,
                `sort_order` int(11) DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Layoff Records
        if (!$CI->db->table_exists(db_prefix() . 'hrm_layoff_records')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_layoff_records` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `layoff_date` date NULL,
                `reason` text NULL,
                `checklist_completed` LONGTEXT NULL,
                `notes` text NULL,
                `added_from` int(11) NULL,
                `date_added` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Policies
        if (!$CI->db->table_exists(db_prefix() . 'hrm_policies')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_policies` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `content` LONGTEXT NULL,
                `category` varchar(100) NULL,
                `is_faq` tinyint(1) DEFAULT 0,
                `sort_order` int(11) DEFAULT 0,
                `date_added` datetime NULL,
                `added_by` int(11) NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Contract Templates
        if (!$CI->db->table_exists(db_prefix() . 'hrm_contract_templates')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_contract_templates` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `contract_type_id` int(11) NULL,
                `content` LONGTEXT NULL,
                `merge_fields` LONGTEXT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // HR Assets
        if (!$CI->db->table_exists(db_prefix() . 'hrm_assets')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_assets` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `asset_code` varchar(100) NULL,
                `category` varchar(100) NULL,
                `assigned_to` int(11) NULL,
                `assigned_date` date NULL,
                `condition` varchar(50) NULL,
                `notes` text NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // HR Helpdesk
        if (!$CI->db->table_exists(db_prefix() . 'hrm_helpdesk_tickets')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_helpdesk_tickets` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `subject` varchar(255) NOT NULL,
                `message` text NULL,
                `category` varchar(100) NULL,
                `status` varchar(50) DEFAULT 'open',
                `assigned_to` int(11) NULL,
                `date_added` datetime NULL,
                `date_updated` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Performance Reviews
        if (!$CI->db->table_exists(db_prefix() . 'hrm_performance_reviews')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_performance_reviews` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `reviewer_id` int(11) NULL,
                `review_period` varchar(50) NULL,
                `review_date` date NULL,
                `rating` decimal(3,2) NULL,
                `notes` text NULL,
                `goals` LONGTEXT NULL,
                `date_added` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Performance Goals
        if (!$CI->db->table_exists(db_prefix() . 'hrm_performance_goals')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_performance_goals` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `title` varchar(255) NOT NULL,
                `description` text NULL,
                `target_date` date NULL,
                `status` varchar(50) DEFAULT 'pending',
                `progress` int(11) DEFAULT 0,
                `review_id` int(11) NULL,
                `date_added` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Learning Courses
        if (!$CI->db->table_exists(db_prefix() . 'hrm_learning_courses')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_learning_courses` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text NULL,
                `category` varchar(100) NULL,
                `duration_hours` int(11) NULL,
                `date_added` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Staff Courses
        if (!$CI->db->table_exists(db_prefix() . 'hrm_staff_courses')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_staff_courses` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `course_id` int(11) NOT NULL,
                `status` varchar(50) DEFAULT 'enrolled',
                `completed_date` date NULL,
                `certificate` varchar(255) NULL,
                `date_added` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Engagement Surveys
        if (!$CI->db->table_exists(db_prefix() . 'hrm_engagement_surveys')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_engagement_surveys` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `questions` LONGTEXT NULL,
                `date_from` date NULL,
                `date_to` date NULL,
                `date_added` datetime NULL,
                `added_by` int(11) NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // Survey Responses
        if (!$CI->db->table_exists(db_prefix() . 'hrm_survey_responses')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_survey_responses` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `survey_id` int(11) NOT NULL,
                `staff_id` int(11) NOT NULL,
                `responses` LONGTEXT NULL,
                `date_submitted` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // HR Documents
        if (!$CI->db->table_exists(db_prefix() . 'hrm_documents')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_documents` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `description` text NULL,
                `category` varchar(100) NULL,
                `file_name` varchar(255) NULL,
                `file_path` varchar(500) NULL,
                `date_added` datetime NULL,
                `added_by` int(11) NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        // 1:1 Notes
        if (!$CI->db->table_exists(db_prefix() . 'hrm_one_on_one_notes')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_one_on_one_notes` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `manager_id` int(11) NOT NULL,
                `meeting_date` date NULL,
                `notes` text NULL,
                `action_items` text NULL,
                `date_added` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }
    }
}
