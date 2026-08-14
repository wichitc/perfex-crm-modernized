<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_154 extends App_module_migration
{
    public function up()
    {
        
    	add_option('allow_vendor_add_edit_delete_purchase_invoice', 1);

        add_option('allow_vendor_add_edit_delete_purchase_quotation', 1);
    }

}