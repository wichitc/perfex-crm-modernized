<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Change_candidate_job_applied_status_to_candidate extends App_mail_template
{

    protected $job_applied;


    public $slug = 'change-candidate-job-applied-status-to-candidate';

    public function __construct($job_applied)
    {
        parent::__construct();

        $this->job_applied = $job_applied;


        // For SMS and merge fields for email
        $this->set_merge_fields('change_candidate_job_applied_status_merge_fields', $this->job_applied);
    }
    public function build()
    {

        $this->to($this->job_applied->email);
        
    }
}
