<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_140 extends App_module_migration
{
    public function up()
    {
      add_option('acc_check_deposit_to', 87);
      add_option('acc_mapping_label_type', 'payment_deposit');
    }
}
