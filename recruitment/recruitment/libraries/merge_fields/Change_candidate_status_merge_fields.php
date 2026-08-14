<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Change_candidate_status_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Fist name',
                'key'       => '{candidate_name}',
                'available' => [
                    'change_candidate_status',
                ],
            ],
            [
                'name'      => 'Last name',
                'key'       => '{last_name}',
                'available' => [
                    'change_candidate_status',
                ],
            ],
            
            [
                'name'      => 'Candidate status',
                'key'       => '{candidate_status}',
                'available' => [
                    'change_candidate_status',
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
        $fields['{candidate_status}']                =  $candidate->candidate_status ;

        return $fields;
    }


}
