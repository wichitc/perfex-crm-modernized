<?php

defined('BASEPATH') or exit('No direct script access allowed');

class New_candidate_have_applied extends App_mail_template
{

    protected $candidate;


    public $slug = 'new-candidate-have-applied';

    public function __construct($candidate)
    {
        parent::__construct();

        $this->candidate = $candidate;


        // For SMS and merge fields for email
        $this->set_merge_fields('new_candidate_have_applied_merge_fields', $this->candidate);
    }
    public function build()
    {

        $this->to($this->candidate->email);
        
    }
}
