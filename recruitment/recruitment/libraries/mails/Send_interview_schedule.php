<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Send_interview_schedule extends App_mail_template
{

    protected $interview;


    public $slug = 'send-interview-schedule-to-candidate';

    public function __construct($interview)
    {
        parent::__construct();

        $this->interview = $interview;


        // For SMS and merge fields for email
        $this->set_merge_fields('send_interview_schedule_merge_fields', $this->interview);
    }
    public function build()
    {

        $this->to($this->interview->email);
    }
}
