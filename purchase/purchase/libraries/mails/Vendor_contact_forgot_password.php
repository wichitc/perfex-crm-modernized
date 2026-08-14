<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Vendor_contact_forgot_password extends App_mail_template
{
    protected $for = 'vendor';

    protected $contact_email;

    protected $contact_id;

    protected $vendor_id;

    protected $password_data;

    public $slug = 'vendor-contact-forgot-password';

    public $rel_type = 'contact';

    public function __construct($contact_email, $vendor_id, $contact_id, $password_data)
    {
        parent::__construct();

        $this->contact_email = $contact_email;
        $this->contact_id    = $contact_id;
        $this->vendor_id     = $vendor_id;
        $this->password_data = $password_data;
    }

    public function build()
    {
        $this->ci->load->library('merge_fields/vendor_merge_fields');

        $this->to($this->contact_email)
        ->set_rel_id($this->contact_id)
        ->set_merge_fields('vendor_merge_fields', $this->vendor_id, $this->contact_id)
        ->set_merge_fields($this->ci->vendor_merge_fields->password($this->password_data, 'forgot'));
    }
}
