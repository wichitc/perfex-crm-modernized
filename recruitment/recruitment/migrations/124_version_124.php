<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_124 extends App_module_migration
{
     public function up()
     {
          $CI = &get_instance();

          
          create_email_template("[{companyname}] Invitation to Interview", "<p>Dear<span> <strong>{candidate_name}</strong></span>,</p>
               <p>Thank you for your application to the<span> <strong>{position} </strong></span><span></span>role at<span> <strong>{companyname}</strong></span><strong>.</strong></p>
               <p>We would like to invite you to interview for the role with<span> <strong>{interviewer}</strong></span>,<span> <strong>{is_name}</strong></span><strong>.</strong><span> The interview will take place on <strong>{from_time} - {to_time} on {interview_day}</strong></span>.</p>
               <p>Our office is located at {interview_location}</p>
               <p>Please reply to this email directly with your availability time</p>
               <p>We look forward to speaking with you.</p>
               <p>Sincerely,</p>
               <p><span>{email_signature}</span><span></span></p>", "send_interview_schedule", "Send Interview Schedule (Sent to Candidate)", "send-interview-schedule-to-candidate");
     }
}
