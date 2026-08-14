<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Send_interview_schedule_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Fist name',
                'key'       => '{candidate_name}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],
            [
                'name'      => 'Last name',
                'key'       => '{last_name}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],

            [
                'name'      => 'Interviewer',
                'key'       => '{interviewer}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],
            [
                'name'      => 'Job Title',
                'key'       => '{position}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],


            [
                'name'      => 'Interviewer Job Title',
                'key'       => '{is_name}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],
            [
                'name'      => 'From (hour)',
                'key'       => '{from_time}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],
            [
                'name'      => 'To (hour)',
                'key'       => '{to_time}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],
            [
                'name'      => 'Interview date',
                'key'       => '{interview_day}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],
            [
                'name'      => 'Interview Location',
                'key'       => '{interview_location}',
                'available' => [
                    'send_interview_schedule',
                ],
            ],

            
        ];
    }


    /**
     * Merge field for appointments
     * @param  mixed $teampassword 
     * @return array
     */
    public function format($interview)
    {
        $fields = [];

        if (!$interview) {
            return $fields;
        }

        $fields['{interviewer}'] = $interview->interviewer;
        $fields['{position}'] = $interview->position;
        $fields['{is_name}'] = $interview->is_name;
        $fields['{from_time}'] = $interview->from_time;
        $fields['{to_time}'] = $interview->to_time;
        $fields['{interview_day}'] = $interview->interview_day;
        $fields['{interview_location}'] = $interview->interview_location;

        $fields['{candidate_name}']                  =  $interview->candidate_name ;
        $fields['{last_name}']                       =  $interview->last_name ;

        return $fields;
    }


}
