<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_144 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		
		if (!$CI->db->field_exists('currency' ,db_prefix() . 'goods_receipt')){
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_receipt`
				ADD COLUMN `currency` INT(11) NULL DEFAULT '0',
				ADD COLUMN `currency_exchange_rate` DECIMAL(15,6) NULL DEFAULT '0'
				;");
		}

		add_option('goods_receipt_do_not_convert_to_base_currency', 0, 1);

		if (!$CI->db->field_exists('currency_exchange_rate' ,db_prefix() . 'wh_order_returns')){
			$CI->db->query('ALTER TABLE `' . db_prefix() . "wh_order_returns`
				ADD COLUMN `currency_exchange_rate` DECIMAL(15,6) NULL DEFAULT '1'
				;");
		}
	}
}
