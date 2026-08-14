<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_160 extends App_module_migration
{
   public function up()
   {
     add_option('show_purchase_order_name_on_po_pdf', 0);
   }

}