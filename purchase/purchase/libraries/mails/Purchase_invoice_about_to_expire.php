<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_invoice_about_to_expire extends App_mail_template
{
    protected $for = 'staff';

    protected $data;

    public $slug = 'purchase-invoice-about-to-expire';

    public function __construct($data)
    {
        parent::__construct();

        $this->data = $data;
        // For SMS and merge fields for email
        $this->set_merge_fields('purchase_invoice_merge_fields', $this->data);
    }
    public function build()
    {
        $this->to($this->data->mail_to);
    }
}
