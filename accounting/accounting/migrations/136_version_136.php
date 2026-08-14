<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_136 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'invoices')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "invoices`
            ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
            ");
        }

        if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'invoicepaymentrecords')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "invoicepaymentrecords`
            ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
            ");
        }

        if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'expenses')) {
          $CI->db->query("ALTER TABLE `" . db_prefix() . "expenses`
            ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
            ");
        }

        if ($CI->db->table_exists(db_prefix() . 'omni_refunds')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'omni_refunds')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "omni_refunds`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_orders')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_orders')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_orders`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_invoices')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_invoices')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_invoices`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_invoice_payment')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'pur_invoice_payment')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "pur_invoice_payment`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'wh_order_returns')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'wh_order_returns')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "wh_order_returns`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'wh_order_returns_refunds')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'wh_order_returns_refunds')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "wh_order_returns_refunds`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'credits')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'credits')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "credits`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'creditnote_refunds')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'creditnote_refunds')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "creditnote_refunds`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'hrp_payslips')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'hrp_payslips')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "hrp_payslips`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'goods_receipt')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'goods_receipt')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "goods_receipt`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'goods_delivery')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'goods_delivery')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "goods_delivery`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'wh_loss_adjustment')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'wh_loss_adjustment')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "wh_loss_adjustment`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'items')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'items')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "items`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }
        if ($CI->db->table_exists(db_prefix() . 'mrp_manufacturing_orders')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'mrp_manufacturing_orders')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "mrp_manufacturing_orders`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }
        if ($CI->db->table_exists(db_prefix() . 'fe_assets')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'fe_assets')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "fe_assets`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }
        if ($CI->db->table_exists(db_prefix() . 'fe_asset_maintenances')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'fe_asset_maintenances')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "fe_asset_maintenances`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }
        if ($CI->db->table_exists(db_prefix() . 'fe_depreciation_items')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'fe_depreciation_items')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "fe_depreciation_items`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'cart')) {
          if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'cart')) {
            $CI->db->query("ALTER TABLE `" . db_prefix() . "cart`
              ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
              ");
          }
        }

        
        add_option('acc_upgrade_invoice_mapping', 0);
        if (get_option('acc_upgrade_invoice_mapping') == 0) {
          $CI->db->query('UPDATE '.db_prefix().'invoices INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'invoices.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "invoice" SET acc_mapping = 1');
          update_option('acc_upgrade_invoice_mapping', 1);
        }

        add_option('acc_upgrade_expense_mapping', 0);
        if (get_option('acc_upgrade_expense_mapping') == 0) {
          $CI->db->query('UPDATE '.db_prefix().'expenses INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'expenses.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "expense" SET acc_mapping = 1');
          update_option('acc_upgrade_expense_mapping', 1);
        }

        add_option('acc_upgrade_payment_mapping', 0);
        if (get_option('acc_upgrade_payment_mapping') == 0) {
          $CI->db->query('UPDATE '.db_prefix().'invoicepaymentrecords INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'invoicepaymentrecords.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "payment" SET acc_mapping = 1');
          update_option('acc_upgrade_payment_mapping', 1);
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_orders')) {
          add_option('acc_upgrade_purchase_order_mapping', 0);
          if (get_option('acc_upgrade_purchase_order_mapping') == 0) {
          $CI->db->query('UPDATE '.db_prefix().'pur_orders INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'pur_orders.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "purchase_order" SET acc_mapping = 1');
            update_option('acc_upgrade_purchase_order_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_invoices')) {
          add_option('acc_upgrade_purchase_invoice_mapping', 0);
          if (get_option('acc_upgrade_purchase_invoice_mapping') == 0) {
          $CI->db->query('UPDATE '.db_prefix().'pur_invoices INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'pur_invoices.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "purchase_invoice" SET acc_mapping = 1');
            update_option('acc_upgrade_purchase_invoice_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'pur_invoice_payment')) {
          add_option('acc_upgrade_purchase_payment_mapping', 0);
          if (get_option('acc_upgrade_purchase_payment_mapping') == 0) {
          $CI->db->query('UPDATE '.db_prefix().'pur_invoice_payment INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'pur_invoice_payment.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "purchase_payment" SET acc_mapping = 1');
            update_option('acc_upgrade_purchase_payment_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'wh_order_returns')) {
          add_option('acc_upgrade_purchase_order_return_mapping', 0);
          if (get_option('acc_upgrade_purchase_order_return_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'wh_order_returns INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'wh_order_returns.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "purchase_order_return" SET acc_mapping = 1');
            update_option('acc_upgrade_purchase_order_return_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'wh_order_returns_refunds')) {
          add_option('acc_upgrade_purchase_refund_mapping', 0);
          if (get_option('acc_upgrade_purchase_refund_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'wh_order_returns_refunds INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'wh_order_returns_refunds.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "purchase_refund" SET acc_mapping = 1');
            update_option('acc_upgrade_purchase_refund_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'credits')) {
          add_option('acc_upgrade_credit_note_mapping', 0);
          if (get_option('acc_upgrade_credit_note_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'credits INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'credits.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "credit_note" SET acc_mapping = 1');
            update_option('acc_upgrade_credit_note_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'creditnote_refunds')) {
          add_option('acc_upgrade_credit_note_refund_mapping', 0);
          if (get_option('acc_upgrade_credit_note_refund_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'creditnote_refunds INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'creditnote_refunds.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "credit_note_refund" SET acc_mapping = 1');
            update_option('acc_upgrade_credit_note_refund_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'hrp_payslips')) {
          add_option('acc_upgrade_hrp_payslip_mapping', 0);
          if (get_option('acc_upgrade_hrp_payslip_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'hrp_payslips INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'hrp_payslips.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "payslip" SET acc_mapping = 1');
            update_option('acc_upgrade_hrp_payslip_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'goods_receipt')) {
          add_option('acc_upgrade_stock_import_mapping', 0);
          if (get_option('acc_upgrade_stock_import_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'goods_receipt INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'goods_receipt.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "stock_import" SET acc_mapping = 1');
            update_option('acc_upgrade_stock_import_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'goods_delivery')) {
          add_option('acc_upgrade_stock_export_mapping', 0);
          if (get_option('acc_upgrade_stock_export_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'goods_delivery INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'goods_delivery.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "stock_export" SET acc_mapping = 1');
            update_option('acc_upgrade_stock_export_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'wh_loss_adjustment')) {
          add_option('acc_upgrade_loss_adjustment_mapping', 0);
          if (get_option('acc_upgrade_loss_adjustment_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'wh_loss_adjustment INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'wh_loss_adjustment.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "loss_adjustment" SET acc_mapping = 1');
            update_option('acc_upgrade_loss_adjustment_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'items')) {
          add_option('acc_upgrade_opening_stock_mapping', 0);
          if (get_option('acc_upgrade_opening_stock_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'items INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'items.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "opening_stock" SET acc_mapping = 1');
            update_option('acc_upgrade_opening_stock_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'mrp_manufacturing_orders')) {
          add_option('acc_upgrade_manufacturing_order_mapping', 0);
          if (get_option('acc_upgrade_manufacturing_order_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'mrp_manufacturing_orders INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'mrp_manufacturing_orders.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "manufacturing_order" SET acc_mapping = 1');
            update_option('acc_upgrade_manufacturing_order_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'fe_assets')) {
          add_option('acc_upgrade_fe_asset_mapping', 0);
          if (get_option('acc_upgrade_fe_asset_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'fe_assets INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'fe_assets.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "fe_asset" SET acc_mapping = 1');
            update_option('acc_upgrade_fe_asset_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'fe_asset_maintenances')) {
          add_option('acc_upgrade_fe_maintenance_mapping', 0);
          if (get_option('acc_upgrade_fe_maintenance_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'fe_asset_maintenances INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'fe_asset_maintenances.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "fe_maintenance" SET acc_mapping = 1');
            update_option('acc_upgrade_fe_maintenance_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'fe_depreciation_items')) {
          add_option('acc_upgrade_fe_depreciation_mapping', 0);
          if (get_option('acc_upgrade_fe_depreciation_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'fe_depreciation_items INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'fe_depreciation_items.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "fe_depreciation" SET acc_mapping = 1');
            update_option('acc_upgrade_fe_depreciation_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'cart')) {
          add_option('acc_upgrade_sales_return_order_mapping', 0);
          if (get_option('acc_upgrade_sales_return_order_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'cart INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'cart.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "sales_return_order" SET acc_mapping = 1');
            update_option('acc_upgrade_sales_return_order_mapping', 1);
          }
        }

        if ($CI->db->table_exists(db_prefix() . 'omni_refunds')) {
          add_option('acc_upgrade_sales_refund_mapping', 0);
          if (get_option('acc_upgrade_sales_refund_mapping') == 0) {
            $CI->db->query('UPDATE '.db_prefix().'omni_refunds INNER JOIN '.db_prefix().'acc_account_history ON '.db_prefix().'omni_refunds.id = '.db_prefix().'acc_account_history.rel_id AND '.db_prefix().'acc_account_history.rel_type = "sales_refund" SET acc_mapping = 1');
            update_option('acc_upgrade_sales_refund_mapping', 1);
          }
        }
    }
}
