<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_135 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        
        //Version 1.3.5
        
        $transaction_link_index = $CI->db->query("SHOW INDEX FROM ".db_prefix() . "acc_account_history;")->result_array();
        $check_index = true;
        foreach ($transaction_link_index as $key => $value) {
          if($value['Key_name'] == 'transaction_link'){
            $check_index = false;
          }
        }

        if($check_index){
          $CI->db->query("CREATE INDEX transaction_link ON ".db_prefix() . "acc_account_history (rel_id, rel_type);");
        }

        add_option('acc_enable_all_time_filter', 1);
    }
}
