<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_141 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		if ($CI->db->field_exists('serial_number' ,db_prefix() . 'goods_receipt_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_receipt_detail`
				CHANGE COLUMN `serial_number` `serial_number` LONGTEXT NULL
				;");
		}

		if ($CI->db->field_exists('serial_number' ,db_prefix() . 'goods_delivery_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_delivery_detail`
				CHANGE COLUMN `serial_number` `serial_number` LONGTEXT NULL
				;");
		}
		if ($CI->db->field_exists('serial_number' ,db_prefix() . 'internal_delivery_note_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "internal_delivery_note_detail`
				CHANGE COLUMN `serial_number` `serial_number` LONGTEXT NULL
				;");
		}

		if ($CI->db->field_exists('serial_number' ,db_prefix() . 'wh_loss_adjustment_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "wh_loss_adjustment_detail`
				CHANGE COLUMN `serial_number` `serial_number` LONGTEXT NULL
				;");
		}
		if ($CI->db->field_exists('serial_number' ,db_prefix() . 'wh_packing_list_details')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "wh_packing_list_details`
				CHANGE COLUMN `serial_number` `serial_number` LONGTEXT NULL
				;");
		}

		if ($CI->db->field_exists('serial_number' ,db_prefix() . 'goods_transaction_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_transaction_detail`
				CHANGE COLUMN `serial_number` `serial_number` LONGTEXT NULL
				;");
		}
	}
}
