<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_146 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		add_option('display_product_image_receipt_delivery_pdf', 0, 1);
	}
}
