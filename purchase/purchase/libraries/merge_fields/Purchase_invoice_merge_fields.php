<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_invoice_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Invoice number',
                'key'       => '{invoice_number}',
                'available' => [
                    
                ],
                'templates' => [
                    'purchase-invoice-created-updated',
                    'new-payment-approved',
                    'purchase-invoice-about-to-expire'
                ],
            ],
            [
                'name'      => 'Vendor invoice link',
                'key'       => '{vendor_invoice_link}',
                'available' => [
                    
                ],
                'templates' => [
                    'purchase-invoice-created-updated',
                    'new-payment-approved',
                    'purchase-invoice-about-to-expire'
                ],
            ],
            [
                'name'      => 'Invoice link',
                'key'       => '{invoice_link}',
                'available' => [
                    
                ],
                'templates' => [
                    'purchase-invoice-created-updated',
                    'new-payment-approved',
                    'purchase-invoice-about-to-expire'
                ],
            ],
           
        ];
    }

    /**
     * Merge field for appointments
     * @param  mixed $data 
     * @return array
     */
    public function format($data)
    {
        $invoice_id = $data->invoice_id;

        $fields = [];


        $fields['{vendor_invoice_link}']                  = site_url('purchase/vendors_portal/invoice/'.$invoice_id);
        $fields['{invoice_link}']                  = admin_url('purchase/purchase_invoice/' . $invoice_id);
        $fields['{invoice_number}']                  =  $data->invoice_number;
       

        return $fields;
    }
}
