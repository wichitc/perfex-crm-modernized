<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_123 extends App_module_migration
{
     public function up()
     {
          $CI = &get_instance();

          create_email_template('New candidate have applied', '<p><span style="font-size: 12pt;">New Candidate have been applied.</span><br /><br /><span style="font-size: 12pt;">You can view the Candidate profile on the following link: <a href="{candidate_link}">#{candidate_link}</a><br /><br />Kind Regards,</span><br /><span style="font-size: 12pt;">{email_signature}</span></p>', 'new_candidate_have_applied', 'New candidate have applied (Sent to Responsible)', 'new-candidate-have-applied');
     }
}
