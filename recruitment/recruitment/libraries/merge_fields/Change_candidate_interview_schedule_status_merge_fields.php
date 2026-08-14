<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Change_candidate_interview_schedule_status_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Fist name',
                'key'       => '{candidate_name}',
                'available' => [
                    'change_candidate_interview_schedule_status',
                ],
            ],
            [
                'name'      => 'Last name',
                'key'       => '{last_name}',
                'available' => [
                    'change_candidate_interview_schedule_status',
                ],
            ],
            
            [
                'name'      => 'Interview schedule status',
                'key'       => '{interview_schedule_status}',
                'available' => [
                    'change_candidate_interview_schedule_status',
                ],
            ],
            
        ];
    }


    /**
     * Merge field for appointments
     * @param  mixed $teampassword 
     * @return array
     */
    public function format($interview_schedule)
    {
        $fields = [];

        if (!$interview_schedule) {
            return $fields;
        }

        $fields['{candidate_name}']                  =  $interview_schedule->candidate_name ;
        $fields['{last_name}']                       =  $interview_schedule->last_name ;
        $fields['{interview_schedule_status}']                =  $interview_schedule->interview_schedule_status ;

        return $fields;
    }


}
