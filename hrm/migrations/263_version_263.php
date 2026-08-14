<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration 263: version 2.6.3
 * Adds:
 *   - hrm_insurance_category           custom, user-defined insurance categories (Life, Dental, Vision, ...)
 *   - hrm_deduction_type               custom deduction types (Cash advance, Loan, Uniform, ...)
 *   - hrm_staff_deduction              per-employee deduction / cash-advance ledger (recoverable from salary)
 *   - hrm_staff_deduction_collection   individual collection events against a staff deduction
 *   - hrm_thirteenth_month             13th month salary / year-end bonus records
 * All guarded with table_exists so upgrades from any prior version are safe.
 */

class Migration_Version_263 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $charset = $CI->db->char_set;

        if (!$CI->db->table_exists(db_prefix() . 'hrm_insurance_category')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_insurance_category` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(191) NOT NULL,
                `company_percent` decimal(6,3) NOT NULL DEFAULT 0,
                `staff_percent` decimal(6,3) NOT NULL DEFAULT 0,
                `description` text NULL,
                `active` tinyint(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'hrm_deduction_type')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_deduction_type` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(191) NOT NULL,
                `calc_type` varchar(20) NOT NULL DEFAULT 'fixed',
                `amount` decimal(15,2) NOT NULL DEFAULT 0,
                `taxable` tinyint(1) NOT NULL DEFAULT 0,
                `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
                `description` text NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'hrm_staff_deduction')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_staff_deduction` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `deduction_type_id` int(11) NULL,
                `title` varchar(191) NULL,
                `total_amount` decimal(15,2) NOT NULL DEFAULT 0,
                `installment_amount` decimal(15,2) NOT NULL DEFAULT 0,
                `collect_type` varchar(20) NOT NULL DEFAULT 'one_time',
                `start_month` date NULL,
                `collected_amount` decimal(15,2) NOT NULL DEFAULT 0,
                `status` varchar(20) NOT NULL DEFAULT 'active',
                `auto_collect` tinyint(1) NOT NULL DEFAULT 1,
                `notes` text NULL,
                `date_created` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'hrm_staff_deduction_collection')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_staff_deduction_collection` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `deduction_id` int(11) NOT NULL,
                `amount` decimal(15,2) NOT NULL DEFAULT 0,
                `collected_date` date NULL,
                `period` varchar(20) NULL,
                `notes` varchar(255) NULL,
                `add_from` int(11) NULL,
                `date_created` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'hrm_thirteenth_month')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "hrm_thirteenth_month` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `year` int(11) NOT NULL,
                `base_amount` decimal(15,2) NOT NULL DEFAULT 0,
                `months_worked` decimal(5,2) NOT NULL DEFAULT 12,
                `computed_amount` decimal(15,2) NOT NULL DEFAULT 0,
                `status` varchar(20) NOT NULL DEFAULT 'draft',
                `notes` text NULL,
                `date_created` datetime NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ';');
        }
    }
}
