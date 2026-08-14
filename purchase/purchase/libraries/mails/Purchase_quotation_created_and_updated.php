<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_quotation_created_and_updated extends App_mail_template
{
    protected $for = 'contact';

    protected $data;

    public $slug = 'purchase-quotation-created-updated';

    public function __construct($data)
    {
        parent::__construct();

        $this->data = $data;
        // For SMS and merge fields for email
        $this->set_merge_fields('purchase_quotation_merge_fields', $this->data);
    }
    public function build()
    {
        $this->to($this->data->mail_to);
    }
}
