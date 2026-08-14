<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_118 extends App_module_migration
{
     public function up()
     {
          $CI = &get_instance();

           add_option('display_quantity_to_be_recruited', 1, 1);
     }
}
