<?php

defined('BASEPATH') or exit('No direct script access allowed');

class New_candidate_have_applied_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Candidate Fist name',
                'key'       => '{candidate_name}',
                'available' => [
                    'new_candidate_have_applied',
                ],
            ],

            [
                'name'      => 'Candidate Last name',
                'key'       => '{last_name}',
                'available' => [
                    'new_candidate_have_applied',
                ],
            ],
            
            [
                'name'      => 'Candidate Link',
                'key'       => '{candidate_link}',
                'available' => [
                    'new_candidate_have_applied',
                ],
            ],
            
        ];
    }


    /**
     * Merge field for appointments
     * @param  mixed $teampassword 
     * @return array
     */
    public function format($candidate)
    {
        $fields = [];

        if (!$candidate) {
            return $fields;
        }

        $fields['{candidate_name}']                  =  $candidate->candidate_name ;
        $fields['{last_name}']                       =  $candidate->last_name ;
        $fields['{candidate_link}']                =   $candidate->candidate_link;

        return $fields;
    }


}
