<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Change_candidate_interview_schedule_status_to_candidate extends App_mail_template
{

    protected $interview_schedule;


    public $slug = 'change-candidate-interview-schedule-status-to-candidate';

    public function __construct($interview_schedule)
    {
        parent::__construct();

        $this->interview_schedule = $interview_schedule;


        // For SMS and merge fields for email
        $this->set_merge_fields('change_candidate_interview_schedule_status_merge_fields', $this->interview_schedule);
    }
    public function build()
    {

        $this->to($this->interview_schedule->email);
        
    }
}
