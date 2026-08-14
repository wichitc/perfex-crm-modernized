<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_145 extends App_module_migration
{
    public function up()
    {
        add_option('acc_omni_sales_order_return_fee_payment_account', 1);
        add_option('acc_omni_sales_order_return_fee_deposit_to', 66);
        add_option('acc_omni_sales_order_return_discount_payment_account', 66);
        add_option('acc_omni_sales_order_return_discount_deposit_to', 1);
    }
}
