<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Change_candidate_job_applied_status_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Fist name',
                'key'       => '{candidate_name}',
                'available' => [
                    'change_candidate_job_applied_status',
                ],
            ],
            [
                'name'      => 'Last name',
                'key'       => '{last_name}',
                'available' => [
                    'change_candidate_job_applied_status',
                ],
            ],
            
            [
                'name'      => 'Job Applied status',
                'key'       => '{job_applied_status}',
                'available' => [
                    'change_candidate_job_applied_status',
                ],
            ],
            
        ];
    }


    /**
     * Merge field for appointments
     * @param  mixed $teampassword 
     * @return array
     */
    public function format($job_applied)
    {
        $fields = [];

        if (!$job_applied) {
            return $fields;
        }

        $fields['{candidate_name}']                  =  $job_applied->candidate_name ;
        $fields['{last_name}']                       =  $job_applied->last_name ;
        $fields['{job_applied_status}']                =  $job_applied->job_applied_status ;

        return $fields;
    }


}
