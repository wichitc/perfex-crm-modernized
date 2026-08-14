<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_155 extends App_module_migration
{
    public function up()
    {
       add_option('automatically_create_a_purchase_order_when_the_quotation_is_approved', 0);
    }

}