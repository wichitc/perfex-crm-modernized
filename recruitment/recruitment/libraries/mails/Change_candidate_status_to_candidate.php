<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Change_candidate_status_to_candidate extends App_mail_template
{

    protected $candidate;


    public $slug = 'change-candidate-status-to-candidate';

    public function __construct($candidate)
    {
        parent::__construct();

        $this->candidate = $candidate;


        // For SMS and merge fields for email
        $this->set_merge_fields('change_candidate_status_merge_fields', $this->candidate);
    }
    public function build()
    {

        $this->to($this->candidate->email);
        
    }
}
