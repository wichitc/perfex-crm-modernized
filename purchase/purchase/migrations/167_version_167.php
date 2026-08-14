<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_167 extends App_module_migration
{
   public function up()
   {
      add_option('can_edit_po_number', '0');
      add_option('can_edit_pr_number', '0');
   }

}