<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_119 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();

		if (!$CI->db->table_exists(db_prefix() . "rec_activity_log")) {
			$CI->db->query("CREATE TABLE `" . db_prefix() . "rec_activity_log` (
				`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				`rel_id` int NULL ,
				`rel_type` varchar(100) NULL ,
				`description` mediumtext NULL,
				`additional_data` text NULL,
				`date` datetime NULL,
				`staffid` int(11) NULL,
				`full_name` varchar(100) NULL,

				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
		}

		if (!$CI->db->field_exists('cd_from_hours' ,db_prefix() . 'cd_interview')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "cd_interview`

				ADD COLUMN `cd_from_hours` datetime NULL,
				ADD COLUMN `cd_to_hours` datetime NULL
				;");
		}

		if (!$CI->db->field_exists('send_notify' ,db_prefix() . 'rec_interview')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "rec_interview`

				ADD COLUMN `send_notify` int(1) NULL DEFAULT 0
				;");
		}

		if (!$CI->db->field_exists('interview_location' ,db_prefix() . 'rec_interview')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "rec_interview`

				ADD COLUMN `interview_location` TEXT NULL
				;");
		}


	}
}
