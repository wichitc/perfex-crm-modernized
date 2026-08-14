<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_148 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		// 1.4.8
		// 27_09_2024
		
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
	}
}
