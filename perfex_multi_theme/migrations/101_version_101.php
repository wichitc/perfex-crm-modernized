<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
    public function up()
    {
    	$CI = &get_instance();

        $multithemetable = db_prefix() . '_multi_theme';

        if (!$CI->db->field_exists('staff_id', $multithemetable)) {

            $CI->db->query("ALTER TABLE `" . $multithemetable . "` ADD `staff_id` INT(11) DEFAULT NULL;");

        }
    }
}