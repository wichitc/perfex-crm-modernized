<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_121 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();
		create_email_template("Changed Candidate Status", "<br />Hi {candidate_name} {last_name}<br /><br />I would like to inform you that your candidate profile status changed to {candidate_status}<br /><br />I will contact you again as soon as I have any news.<br /><br />Kind Regards,<br />{email_signature}.", "change_candidate_status", "Change Candidate Status (Sent to Candidate)", "change-candidate-status-to-candidate");

		create_email_template("Changed Candidate Job Applied Status", "<br />Hi {candidate_name} {last_name}<br /><br />I would like to inform you that your Job Applied status changed to {job_applied_status}<br /><br />I will contact you again as soon as I have any news.<br /><br />Kind Regards,<br />{email_signature}.", "change_candidate_job_applied_status", "Change Candidate Job Applied Status (Sent to Candidate)", "change-candidate-job-applied-status-to-candidate");

		create_email_template("Changed Candidate Interview Schedule Status", "<br />Hi {candidate_name} {last_name}<br /><br />I would like to inform you that your Interview Schedule status changed to {interview_schedule_status}<br /><br />I will contact you again as soon as I have any news.<br /><br />Kind Regards,<br />{email_signature}.", "change_candidate_interview_schedule_status", "Change Candidate Interview Schedule Status (Sent to Candidate)", "change-candidate-interview-schedule-status-to-candidate");

		if (!$CI->db->table_exists(db_prefix() . "rec_notifications")) {
			$CI->db->query("CREATE TABLE `" . db_prefix() . "rec_notifications` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`isread` int(11) NOT NULL DEFAULT '0',
				`isread_inline` tinyint(1) NOT NULL DEFAULT '0',
				`date` datetime NOT NULL,
				`description` text NOT NULL,
				`fromuserid` int(11) NOT NULL,
				`fromclientid` int(11) NOT NULL DEFAULT '0',
				`from_fullname` varchar(100) NOT NULL,
				`touserid` int(11) NOT NULL,
				`fromcompany` int(11) DEFAULT NULL,
				`link` mediumtext,
				`additional_data` text,

				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
		}

	}
}
