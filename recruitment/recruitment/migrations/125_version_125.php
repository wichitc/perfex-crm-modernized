<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_125 extends App_module_migration
{
     public function up()
     {
          $CI = &get_instance();

          if (!$CI->db->field_exists('currency' ,db_prefix() . 'rec_proposal')) { 
             $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_proposal`
                ADD COLUMN `currency` int(11) NOT NULL DEFAULT '0'
                ;");
        }
        if (!$CI->db->field_exists('currency' ,db_prefix() . 'rec_campaign')) { 
             $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_campaign`
                ADD COLUMN `currency` int(11) NOT NULL DEFAULT '0'
                ;");
        }
        if (!$CI->db->field_exists('currency' ,db_prefix() . 'rec_candidate')) { 
             $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
                ADD COLUMN `currency` int(11) NOT NULL DEFAULT '0'
                ;");
        }

   }
}
