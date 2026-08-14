<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_117 extends App_module_migration
{
     public function up()
     {
          $CI = &get_instance();

          if (!$CI->db->field_exists('year_experience' ,db_prefix() . 'rec_candidate')) { 
               $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
                    ADD COLUMN `year_experience` VARCHAR(200) NULL
                    ;");
          }
     }
}
