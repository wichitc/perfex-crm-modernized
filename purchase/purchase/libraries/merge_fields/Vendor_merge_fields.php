<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Vendor_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Vendor Company Name',
                'key'       => '{vendorcompanyname}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                    'vendor-registration-confirmed',
                    'new-contact-created',
                    'pur-new-vendor-register',
                ],
            ],
            [
                'name'      => 'Contact first name',
                'key'       => '{contact_firstname}',
                'available' => [
                   
                ],
                'templates' => [
                    'vendor-registration-confirmed',
                    'new-contact-created',
                ],
            ],
            [
                'name'      => 'Contact last name',
                'key'       => '{contact_lastname}',
                'available' => [
                   
                ],
                'templates' => [
                    'vendor-registration-confirmed',
                    'new-contact-created',
                ],
            ],
            [
                'name'      => 'Vendor Portal Link',
                'key'       => '{vendor_portal_link}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                    'vendor-registration-confirmed',
                    'new-contact-created',
                ],
            ],
            [
                'name'      => 'Password',
                'key'       => '{password}',
                'available' => [
                   
                ],
                'templates' => [
                    'new-contact-created',
                ],
            ],
            [
                'name'      => 'Reset Password URL',
                'key'       => '{reset_password_url}',
                'available' => [
                ],
                'templates' => [
                    'vendor-contact-forgot-password',
                ],
            ],

             [
                'name'      => 'Vendor URL',
                'key'       => '{vendor_link}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                    'pur-new-vendor-register',
                ],
            ],

            [
                'name'      => 'Vendor Code',
                'key'       => '{vendor_code}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                    'pur-new-vendor-register',
                ],
            ],

            [
                'name'      => 'Vendor VAT Number',
                'key'       => '{vendor_vat}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                   
                ],
            ],
             [
                'name'      => 'Vendor Phone Number',
                'key'       => '{vendor_phonenumber}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                   
                ],
            ],

            [
                'name'      => 'Vendor Address',
                'key'       => '{vendor_address}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                   
                ],
            ],
            [
                'name'      => 'Vendor state',
                'key'       => '{vendor_state}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                   
                ],
            ],
            [
                'name'      => 'Vendor ZIP',
                'key'       => '{vendor_zip}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                   
                ],
            ],
            [
                'name'      => 'Vendor City',
                'key'       => '{vendor_city}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                   
                ],
            ],
             [
                'name'      => 'Vendor country',
                'key'       => '{vendor_country}',
                'available' => [
                   'vendor',
                ],
                'templates' => [
                   
                ],
            ],

        ];
    }

    /**
     * Merge field for appointments
     * @param  mixed $teampassword 
     * @return array
     */
    public function format($_contact = '', $vendor_id = '', $contact_id = '')
    {

        $this->ci->db->where('id', $contact_id);
        $contact = $this->ci->db->get(db_prefix() . 'pur_contacts')->row();

        if (isset($contact->id)) {
            $fields['{contact_firstname}']          = $contact->firstname;
            $fields['{contact_lastname}']           = $contact->lastname;
        }


        $this->ci->db->where('userid', $vendor_id);
        $vendor = $this->ci->db->get(db_prefix() . 'pur_vendor')->row();

        $fields['{password}'] = '';
        if( isset($_contact->password_before_hash) ){
            $fields['{password}'] = $_contact->password_before_hash;
        }


        $fields['{vendorcompanyname}']          = get_vendor_company_name($vendor_id);

        $fields['{vendor_portal_url}']          = site_url('purchase/authentication_vendor/login');
        $fields['{vendor_portal_link}']          = site_url('purchase/authentication_vendor/login');
        $fields['{vendor_link}'] = admin_url('purchase/vendor/'.$vendor_id);
        

        if ($vendor) {
            $fields['{vendor_code}']          = $vendor->vendor_code;
            $fields['{vendor_vat}']          = $vendor->vat;
            $fields['{vendor_phonenumber}']           = $vendor->phonenumber;
            $fields['{vendor_address}'] = $vendor->address;
            $fields['{vendor_state}'] = $vendor->state;
            $fields['{vendor_zip}'] = $vendor->zip;
            $fields['{vendor_city}'] = $vendor->city;
            $fields['{vendor_country}'] = get_country_name($vendor->country);
        }

        return $fields;
    }


    /**
     * Password merge fields
     * @param  array $data
     * @param  string $type  template type
     * @return array
     */
    public function password($data, $type)
    {
        $fields['{reset_password_url}'] = '';
        $fields['{set_password_url}']   = '';

        if ($type == 'forgot') {
            $fields['{reset_password_url}'] = site_url('purchase/authentication_vendor/reset_password/0/' . $data['userid'] . '/' . $data['new_pass_key']);
        } elseif ($type == 'set') {
            $fields['{set_password_url}'] = site_url('purchase/authentication_vendor/set_password/0/' . $data['userid'] . '/' . $data['new_pass_key']);
        }

        return $fields;
    }
}
