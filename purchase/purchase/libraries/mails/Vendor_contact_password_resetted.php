<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Vendor_contact_password_resetted extends App_mail_template
{
    protected $for = 'vendor';

    protected $contact_email;

    protected $contact_id;

    protected $vendor_id;

    public $slug = 'vendor-contact-password-reseted';

    public $rel_type = 'contact';

    public function __construct($contact_email, $vendor_id, $contact_id)
    {
        parent::__construct();

        $this->contact_email = $contact_email;
        $this->contact_id    = $contact_id;
        $this->vendor_id     = $vendor_id;
    }

    public function build()
    {
        $this->to($this->contact_email)
        ->set_rel_id($this->contact_id)
        ->set_merge_fields('vendor_merge_fields', $this->vendor_id, $this->contact_id);
    }
}
