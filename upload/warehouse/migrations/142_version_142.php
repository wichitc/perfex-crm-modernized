<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_142 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		add_option('update_inventory_number', 0, 1);
	}
}
