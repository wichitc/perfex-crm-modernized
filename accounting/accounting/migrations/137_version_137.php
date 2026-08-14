<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_137 extends App_module_migration
{
    public function up()
    {
        add_option('acc_pur_order_discount_payment_account', 80);
        add_option('acc_pur_order_discount_deposit_to', 19);

        add_option('acc_pur_invoice_discount_payment_account', 80);
        add_option('acc_pur_invoice_discount_deposit_to', 19);

        add_option('acc_pur_order_return_discount_payment_account', 19);
        add_option('acc_pur_order_return_discount_deposit_to', 80);

        add_option('acc_pur_order_shipping_payment_account', 13);
        add_option('acc_pur_order_shipping_deposit_to', 80);

        add_option('acc_pur_invoice_shipping_payment_account', 13);
        add_option('acc_pur_invoice_shipping_deposit_to', 80);

        add_option('acc_pur_order_return_fee_payment_account', 80);
        add_option('acc_pur_order_return_fee_deposit_to', 13);
    }
}
