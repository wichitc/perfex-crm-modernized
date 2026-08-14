<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_143 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		
		if (!$CI->db->field_exists('expiry_date', 'wh_packing_list_details')) {
			$CI->db->query('ALTER TABLE `'.db_prefix() . 'wh_packing_list_details` 
				ADD COLUMN `expiry_date` text  NULL
				;');            
		}
		add_option('packing_list_expiry_date', 0, 1);
		add_option('packing_list_pdf_display_expiry_date', 0, 1);
	}
}
