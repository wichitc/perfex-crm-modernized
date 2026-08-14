<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_122 extends App_module_migration
{
     public function up()
     {
          $CI = &get_instance();

          if (!$CI->db->field_exists('job_meta_title', 'rec_campaign')) {
             $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_campaign` 
                  ADD COLUMN `job_meta_title` text null,
                  ADD COLUMN `job_meta_description` text null
                  ;');            
        }

     }
}
