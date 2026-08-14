<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_151 extends App_module_migration
{
    public function up()
    {

    	add_option('purchase_active_vendor_portal', 1);
		add_option('purchase_order_status_can_show_on_vendor_portal', '1,2,3,4');
    }

}