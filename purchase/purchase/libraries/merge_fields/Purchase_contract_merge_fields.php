<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_contract_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Contract number',
                'key'       => '{contract_number}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
            [
                'name'      => 'Contract link',
                'key'       => '{contract_link}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
            [
                'name'      => 'Contract Admin link',
                'key'       => '{contract_admin_link}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
            [
                'name'      => 'Contract name',
                'key'       => '{contract_name}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
            [
                'name'      => 'Service Category',
                'key'       => '{service_category}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
            [
                'name'      => 'Payment Amount',
                'key'       => '{payment_amount}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
             [
                'name'      => 'Signed Date',
                'key'       => '{signed_date}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
            [
                'name'      => 'Signed Status',
                'key'       => '{signed_status}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],

            [
                'name'      => 'Contract value',
                'key'       => '{contract_value}',
                'available' => [
                    'purchase_contract',
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
            [
                'name'      => 'Additional content',
                'key'       => '{additional_content}',
                'available' => [
                ],
                'templates' => [
                    'purchase-contract-to-contact',
                ],
            ],
        ];
    }

    /**
     * Merge field for appointments
     * @param  mixed $teampassword 
     * @return array
     */
    public function format($data)
    {
        $contract_id = $data->contract_id;


        $fields = [];

        $this->ci->db->where('id', $contract_id);
        $contract = $this->ci->db->get(db_prefix() . 'pur_contracts')->row();


        if (!$contract) {
            return $fields;
        }

        $fields['{contract_link}']                  = site_url('purchase/vendors_portal/view_contract/' . $contract->id);
        $fields['{contract_name}']                  =  $contract->contract_name;
        $fields['{service_category}']                  =  $contract->service_category;
        $fields['{payment_amount}']                  =  $contract->payment_amount;
        $fields['{signed_date}']                  =  _d($contract->signed_date);
        $fields['{signed_status}']                  =  _l($contract->signed_status);
        $fields['{contract_number}']                  =  $contract->contract_number;
        $fields['{contract_value}']                   =  app_format_money($contract->contract_value, '');
        $fields['{additional_content}'] = $data->content;
        $fields['{contract_admin_link}'] = admin_url('purchase/contract/'.$contract_id);

        return $fields;
    }
}
