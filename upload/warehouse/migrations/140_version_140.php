<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_140 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		add_option('wh_serial_number_as_mandatory', 0, 1);
		add_option('next_serial_number', 1, 1);
		add_option('serial_number_format', 1, 1);
	}
}
