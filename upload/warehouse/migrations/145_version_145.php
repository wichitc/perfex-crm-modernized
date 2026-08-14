<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_145 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		if (!$CI->db->field_exists('approval_type' ,db_prefix() . 'wh_approval_setting')){
			$CI->db->query('ALTER TABLE `' . db_prefix() . "wh_approval_setting`
				ADD COLUMN `approval_type` INT(11) NULL DEFAULT '0' COMMENT '0: All  1: only one'
				;");
		}

	}
}
