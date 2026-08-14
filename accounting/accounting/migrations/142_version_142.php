<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_142 extends App_module_migration
{
    public function up()
    {
      add_option('acc_wh_stock_import_tax_automatic_conversion', 1);
      add_option('acc_wh_stock_export_tax_automatic_conversion', 1);
    }
}
