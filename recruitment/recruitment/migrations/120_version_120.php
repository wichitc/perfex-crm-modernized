<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_120 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();

		if (!$CI->db->field_exists("password" ,db_prefix() . "rec_candidate")) { 
			$CI->db->query("ALTER TABLE `" . db_prefix() . "rec_candidate`

				ADD COLUMN `password` varchar(255) NULL,
				ADD COLUMN `new_pass_key` varchar(32) NULL,
				ADD COLUMN `new_pass_key_requested` datetime NULL,
				ADD COLUMN `email_verified_at` datetime NULL,
				ADD COLUMN `email_verification_key` varchar(32) NULL,
				ADD COLUMN `email_verification_sent_at` DATETIME NULL,
				ADD COLUMN `last_ip` varchar(40) NULL,
				ADD COLUMN `last_login` DATETIME NULL,
				ADD COLUMN `last_password_change` DATETIME NULL,
				ADD COLUMN `active` TINYINT(1) NOT NULL DEFAULT '1'

				;");
		}

		add_option('candidate_code_prefix', 'ID', 1);
		add_option('candidate_code_number', 1, 1);
		add_option('send_email_welcome_for_new_candidate', 1, 1);

		if (!$CI->db->table_exists(db_prefix() . "rec_applied_jobs")) {
			$CI->db->query("CREATE TABLE `" . db_prefix() . "rec_applied_jobs` (
				`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				`candidate_id` int NULL ,
				`campaign_id` int NULL ,
				`date_created` datetime NULL,
				`status` TEXT NULL,
				`activate` TEXT NULL,

				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
		}

		if (!$CI->db->field_exists('status' ,db_prefix() . 'cd_interview')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "cd_interview`
				ADD COLUMN `status` int(11) NULL
				;");
		}

		if (!$CI->db->field_exists("candidate_id" ,db_prefix() . "user_meta")) { 
			$CI->db->query("ALTER TABLE `" . db_prefix() . "user_meta`
				ADD COLUMN `candidate_id` bigint(20) unsigned NOT NULL DEFAULT '0'
				;");
		}
		if (!$CI->db->field_exists("candidate_id" ,db_prefix() . "consents")) { 
			$CI->db->query("ALTER TABLE `" . db_prefix() . "consents`
				ADD COLUMN `candidate_id` bigint(20) unsigned NOT NULL DEFAULT '0'
				;");
		}

	}
}
