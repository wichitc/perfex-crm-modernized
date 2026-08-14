<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_order_discuss_to_staff extends App_mail_template
{
    protected $for = 'staff';

    protected $data;

    public $slug = 'purchase-order-discuss-to-staff';

    public function __construct($data)
    {
        parent::__construct();

        $this->data = $data;
        // For SMS and merge fields for email
        $this->set_merge_fields('purchase_order_merge_fields', $this->data);
    }
    public function build()
    {
        $this->to($this->data->mail_to);
    }
}
