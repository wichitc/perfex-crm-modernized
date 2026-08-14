<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_144 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        //Version 1.4.4
        if (!$CI->db->table_exists(db_prefix() . 'acc_class')) {
            $CI->db->query('CREATE TABLE ' . db_prefix() . "acc_class (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `name` TEXT NOT NULL,
              `description` TEXT NULL,
              `addedfrom` INT(11) NULL,
              `dateadded` DATETIME NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        add_option('acc_enable_class_tracking', 0);

        if (!$CI->db->field_exists('acc_class' ,db_prefix() . 'expenses')) {
          $CI->db->query('ALTER TABLE `' . db_prefix() . 'expenses`
            ADD COLUMN `acc_class` INT(11) NOT NULL DEFAULT 0;');
        }

        if (!$CI->db->field_exists('acc_class' ,db_prefix() . 'invoices')) {
          $CI->db->query('ALTER TABLE `' . db_prefix() . 'invoices`
            ADD COLUMN `acc_class` INT(11) NOT NULL DEFAULT 0;');
        }

        if (!$CI->db->field_exists('acc_class' ,db_prefix() . 'acc_checks')) {
          $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_checks`
            ADD COLUMN `acc_class` INT(11) NOT NULL DEFAULT 0;');
        }

        if (!$CI->db->field_exists('acc_class' ,db_prefix() . 'acc_pay_bills')) {
          $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_pay_bills`
            ADD COLUMN `acc_class` INT(11) NOT NULL DEFAULT 0;');
        }

        if (!$CI->db->field_exists('acc_class' ,db_prefix() . 'acc_transfers')) {
          $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_transfers`
            ADD COLUMN `acc_class` INT(11) NOT NULL DEFAULT 0;');
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_orders')) {
          if (!$CI->db->field_exists('acc_class' ,db_prefix() . 'pur_orders')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'pur_orders`
              ADD COLUMN `acc_class` INT(11) NOT NULL DEFAULT 0;');
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_invoices')) {
          if (!$CI->db->field_exists('acc_class' ,db_prefix() . 'pur_invoices')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'pur_invoices`
              ADD COLUMN `acc_class` INT(11) NOT NULL DEFAULT 0;');
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_invoice_payment')) {
          if (!$CI->db->field_exists('acc_class' ,db_prefix() . 'pur_invoice_payment')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'pur_invoice_payment`
              ADD COLUMN `acc_class` INT(11) NOT NULL DEFAULT 0;');
          }
        }

        if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'creditnotes')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "creditnotes`
            ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
            ");
        }

        $count = $CI->db->query('SELECT COUNT(1) as total_records FROM `'.db_prefix() . 'acc_account_history` WHERE rel_type = "credit_note"')->row();
        if ($count->total_records > 0) {
          add_option('acc_credit_note_mapping_mode', 'on_apply');
        }else{
          add_option('acc_credit_note_mapping_mode', 'on_create');
        }

        add_option('acc_debit_note_mapping_mode', 'on_create');

        add_option('acc_debit_note_automatic_conversion', 1);
        add_option('acc_debit_note_refund_automatic_conversion', 1);

        add_option('acc_debit_note_payment_account', 80);
        add_option('acc_debit_note_deposit_to', 87);
        add_option('acc_debit_note_refund_payment_account', 87);
        add_option('acc_debit_note_refund_deposit_to', 80);

        if (!$CI->db->field_exists('debit_note_refund_payment_account' ,db_prefix() . 'acc_payment_mode_mappings')) {
          $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_payment_mode_mappings`
            ADD COLUMN `debit_note_refund_payment_account` INT(11) NOT NULL DEFAULT \'0\',
            ADD COLUMN `debit_note_refund_deposit_to` INT(11) NOT NULL DEFAULT \'0\';');
        }

        if ($CI->db->table_exists(db_prefix() . 'creditnotes')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'creditnotes')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "creditnotes`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_debits')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_debits')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_debits`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_debits_refunds')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_debits_refunds')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_debits_refunds`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_debit_notes')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_debit_notes')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_debit_notes`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if (!$CI->db->table_exists(db_prefix() . 'acc_item_group_automatics')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "acc_item_group_automatics` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `item_group_id` INT(11) NOT NULL,
              `inventory_asset_account` INT(11) NOT NULL DEFAULT 0,
              `income_account` INT(11) NOT NULL DEFAULT 0,
              `expense_account` INT(11) NOT NULL DEFAULT 0,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->field_exists('recurring', db_prefix() . 'expenses')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "expenses`
            ADD COLUMN `recurring` int(11) NOT NULL DEFAULT '0',
            ADD COLUMN `recurring_type` varchar(10) DEFAULT NULL,
            ADD COLUMN `custom_recurring` tinyint(1) NOT NULL DEFAULT '0',
            ADD COLUMN `cycles` int(11) NOT NULL DEFAULT '0',
            ADD COLUMN `total_cycles` int(11) NOT NULL DEFAULT '0',
            ADD COLUMN `last_recurring_date` date DEFAULT NULL
            ");
        }

        if (!$CI->db->field_exists('acc_is_recurring_from', db_prefix() . 'expenses')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "expenses`
            ADD COLUMN `acc_is_recurring_from` int(11) DEFAULT NULL
            ");
        }

        add_option('acc_hide_zero_value_rows', 0);

        if (!$CI->db->field_exists('class', db_prefix() . 'acc_account_history')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_account_history` ADD COLUMN `class` INT(11) NULL DEFAULT 0;');
        }

        $tables_to_add_class = [
            'creditnotes' => 'acc_class',
            'goods_receipt' => 'acc_class',
            'goods_delivery' => 'acc_class',
            'wh_loss_adjustment' => 'acc_class',
            'hrp_payslips' => 'acc_class',
            'pur_orders' => 'acc_class',
            'pur_invoices' => 'acc_class',
            'pur_debit_notes' => 'acc_class',
            'wh_order_returns' => 'acc_class'
        ];

        foreach ($tables_to_add_class as $table => $column) {
            $full_table = db_prefix() . $table;
            if ($CI->db->table_exists($full_table)) {
                if (!$CI->db->field_exists($column, $full_table)) {
                    $CI->db->query('ALTER TABLE `' . $full_table . '` ADD COLUMN `' . $column . '` INT(11) NULL DEFAULT 0;');
                }
            }
        }

        // Version 1.4.5 (merged into 144) - Project Budgeting & Imprest/Claims Tables
        add_option('acc_budget_enforcement', 'disable');
        add_option('acc_budget_approver_id', '');
        add_option('acc_enforce_purchase_order', 0);
        add_option('acc_enforce_expense', 0);
        add_option('acc_enforce_imprest', 0);
        add_option('acc_enforce_claim', 0);

        if (!$CI->db->table_exists(db_prefix() . 'acc_project_budget_categories')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "acc_project_budget_categories` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(255) NOT NULL,
              `created_at` DATETIME NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
            
            // Insert default categories
            $CI->db->query("INSERT INTO `" . db_prefix() . "acc_project_budget_categories` (`name`, `created_at`) VALUES 
                ('Civil Works', NOW()),
                ('Fuel / Transport', NOW()),
                ('Accommodation', NOW());");
        }

        if (!$CI->db->table_exists(db_prefix() . 'acc_project_budgets')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "acc_project_budgets` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `project_id` INT(11) NOT NULL,
              `owner_id` INT(11) NOT NULL,
              `description` TEXT NULL,
              `status` VARCHAR(50) DEFAULT 'draft',
              `created_at` DATETIME NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'acc_project_budget_details')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "acc_project_budget_details` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `budget_id` INT(11) NOT NULL,
              `category_id` INT(11) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'acc_imprest_requests')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "acc_imprest_requests` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `reference_no` VARCHAR(100) NOT NULL,
              `request_date` DATE NOT NULL,
              `project_id` INT(11) NOT NULL,
              `category_id` INT(11) NOT NULL,
              `staff_id` INT(11) NOT NULL,
              `amount_requested` DECIMAL(15,2) NOT NULL,
              `amount_retired` DECIMAL(15,2) DEFAULT '0.00',
              `variance` DECIMAL(15,2) DEFAULT '0.00',
              `payment_method` VARCHAR(100) NOT NULL,
              `description` TEXT NULL,
              `attachment` VARCHAR(255) DEFAULT NULL,
              `status` VARCHAR(50) DEFAULT 'disbursed',
              `debit_account_id` INT(11) DEFAULT NULL,
              `credit_account_id` INT(11) DEFAULT NULL,
              `retire_notes` TEXT NULL,
              `retire_payment_method` VARCHAR(100) NULL,
              `retire_transaction_id` VARCHAR(255) NULL,
              `retire_date` DATE NULL,
              `expense_account_id` INT(11) DEFAULT NULL,
              `cash_bank_account_id` INT(11) DEFAULT NULL,
              `created_by` INT(11) NOT NULL,
              `created_at` DATETIME NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `reference_no` (`reference_no`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'acc_claims')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "acc_claims` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `expense_date` DATE NOT NULL,
              `project_id` INT(11) NOT NULL,
              `category_id` INT(11) NOT NULL,
              `staff_id` INT(11) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL,
              `description` TEXT NULL,
              `status` VARCHAR(50) DEFAULT 'draft',
              `debit_account_id` INT(11) DEFAULT NULL,
              `credit_account_id` INT(11) DEFAULT NULL,
              `created_by` INT(11) NOT NULL,
              `created_at` DATETIME NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'acc_claim_refunds')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "acc_claim_refunds` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `claim_id` INT(11) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL,
              `payment_date` DATE NOT NULL,
              `payment_method` VARCHAR(100) NOT NULL,
              `notes` TEXT NULL,
              `attachment` VARCHAR(255) DEFAULT NULL,
              `debit_account_id` INT(11) DEFAULT NULL,
              `credit_account_id` INT(11) DEFAULT NULL,
              `created_at` DATETIME NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'acc_project_budget_mappings')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "acc_project_budget_mappings` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `rel_id` INT(11) NOT NULL,
              `rel_type` VARCHAR(50) NOT NULL,
              `project_id` INT(11) NOT NULL,
              `category_id` INT(11) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
              PRIMARY KEY (`id`),
              UNIQUE KEY `rel_id_rel_type` (`rel_id`, `rel_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }
        
        if ($CI->db->table_exists(db_prefix() . 'pur_debit_notes')) {
            if (!$CI->db->field_exists('acc_class', db_prefix() . 'pur_debit_notes')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'pur_debit_notes` ADD COLUMN `acc_class` INT(11) NULL DEFAULT 0;');
            }
        }
        if ($CI->db->table_exists(db_prefix() . 'wh_order_returns')) {
            if (!$CI->db->field_exists('acc_class', db_prefix() . 'wh_order_returns')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'wh_order_returns` ADD COLUMN `acc_class` INT(11) NULL DEFAULT 0;');
            }
        }
        if ($CI->db->table_exists(db_prefix() . 'wh_loss_adjustment')) {
            if (!$CI->db->field_exists('acc_class', db_prefix() . 'wh_loss_adjustment')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'wh_loss_adjustment` ADD COLUMN `acc_class` INT(11) NULL DEFAULT 0;');
            }
        }
        if ($CI->db->table_exists(db_prefix() . 'cart')) {
            if (!$CI->db->field_exists('acc_class', db_prefix() . 'cart')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'cart` ADD COLUMN `acc_class` INT(11) NULL DEFAULT 0;');
            }
        }
        if ($CI->db->table_exists(db_prefix() . 'mrp_manufacturing_orders')) {
            if (!$CI->db->field_exists('acc_class', db_prefix() . 'mrp_manufacturing_orders')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'mrp_manufacturing_orders` ADD COLUMN `acc_class` INT(11) NULL DEFAULT 0;');
            }
        }
        if ($CI->db->table_exists(db_prefix() . 'fe_assets')) {
            if (!$CI->db->field_exists('acc_class', db_prefix() . 'fe_assets')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_assets` ADD COLUMN `acc_class` INT(11) NULL DEFAULT 0;');
            }
        }
        if ($CI->db->table_exists(db_prefix() . 'fe_asset_maintenances')) {
            if (!$CI->db->field_exists('acc_class', db_prefix() . 'fe_asset_maintenances')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'fe_asset_maintenances` ADD COLUMN `acc_class` INT(11) NULL DEFAULT 0;');
            }
        }
        if ($CI->db->table_exists(db_prefix() . 'acc_project_budgets')) {
            if (!$CI->db->field_exists('start_date', db_prefix() . 'acc_project_budgets')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_project_budgets` ADD COLUMN `start_date` DATE NULL;');
            }
            if (!$CI->db->field_exists('end_date', db_prefix() . 'acc_project_budgets')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_project_budgets` ADD COLUMN `end_date` DATE NULL;');
            }
        }

        if (!$CI->db->table_exists(db_prefix() . 'acc_approval_setting')) {
            $CI->db->query("CREATE TABLE IF NOT EXISTS `" . db_prefix() . "acc_approval_setting` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(255) NOT NULL,
              `related` VARCHAR(255) NOT NULL,
              `setting` LONGTEXT NOT NULL,
              `approval_type` INT NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        } 
        if (!$CI->db->table_exists(db_prefix() . 'acc_approval_details')) {
            $CI->db->query("CREATE TABLE IF NOT EXISTS `" . db_prefix() . "acc_approval_details` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `rel_id` INT NOT NULL,
              `rel_type` VARCHAR(255) NOT NULL,
              `staffid` TEXT NOT NULL,
              `approve` INT NOT NULL,
              `note` TEXT NULL,
              `date` DATETIME NULL,
              `approve_value` DECIMAL(15,2) DEFAULT '0.00',
              `action` VARCHAR(255) NULL,
              `sender` INT NOT NULL DEFAULT '0',
              `date_send` DATETIME NULL,
              `approve_setting_id` INT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        } 

        // Project Budget transaction approval schema changes.
        if ($CI->db->table_exists(db_prefix() . 'acc_project_budget_mappings')) {
            if (!$CI->db->field_exists('budget_approval_status', db_prefix() . 'acc_project_budget_mappings')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . "acc_project_budget_mappings` ADD COLUMN `budget_approval_status` VARCHAR(30) NOT NULL DEFAULT 'approved';");
            }
            if (!$CI->db->field_exists('budget_approval_note', db_prefix() . 'acc_project_budget_mappings')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_project_budget_mappings` ADD COLUMN `budget_approval_note` TEXT NULL;');
            }
            if (!$CI->db->field_exists('budget_approved_by', db_prefix() . 'acc_project_budget_mappings')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_project_budget_mappings` ADD COLUMN `budget_approved_by` INT(11) NULL;');
            }
            if (!$CI->db->field_exists('budget_approved_at', db_prefix() . 'acc_project_budget_mappings')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'acc_project_budget_mappings` ADD COLUMN `budget_approved_at` DATETIME NULL;');
            }
        }

        if ($CI->db->table_exists(db_prefix() . 'acc_claims') && !$CI->db->field_exists('budget_approval_status', db_prefix() . 'acc_claims')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . "acc_claims` ADD COLUMN `budget_approval_status` VARCHAR(30) NOT NULL DEFAULT 'approved';");
        }

        if ($CI->db->table_exists(db_prefix() . 'acc_imprest_requests') && !$CI->db->field_exists('budget_approval_status', db_prefix() . 'acc_imprest_requests')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . "acc_imprest_requests` ADD COLUMN `budget_approval_status` VARCHAR(30) NOT NULL DEFAULT 'approved';");
        }

        add_option('acc_omni_sales_order_return_fee_payment_account', 1);
        add_option('acc_omni_sales_order_return_fee_deposit_to', 66);
        add_option('acc_omni_sales_order_return_discount_payment_account', 66);
        add_option('acc_omni_sales_order_return_discount_deposit_to', 1);
    }
}
