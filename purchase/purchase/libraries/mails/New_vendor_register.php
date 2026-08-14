<?php

defined('BASEPATH') or exit('No direct script access allowed');

class New_vendor_register extends App_mail_template
{
    protected $for = 'staff';

    protected $staff;
    protected $contact;

    public $slug = 'pur-new-vendor-register';

    public $rel_type = 'staff';

    public function __construct($data)
    {
        parent::__construct();
        $this->staff = $data->staff;
        $this->contact = $data->contact;
    }

    public function build()
    {
        $this->to($this->staff->email)
        ->set_rel_id($this->staff->staffid)
        ->set_merge_fields('vendor_merge_fields', $this->contact, $this->contact->userid, $this->contact->id);
    }
}
