<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_150 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		// 1.5.0
		if ($CI->db->field_exists('sub_total' ,db_prefix() . 'goods_delivery')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_delivery`
				CHANGE COLUMN `sub_total` `sub_total` DECIMAL(20,2) NULL DEFAULT '0.00' ;");
		}
	}
}
