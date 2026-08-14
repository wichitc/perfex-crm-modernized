<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_149 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		// 1.4.9
		// 16_10_2024
		add_option('packing_list_pdf_display_only_item_name', 0, 1);
		
	}
}
