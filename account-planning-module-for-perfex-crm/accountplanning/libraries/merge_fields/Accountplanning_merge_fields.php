<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Accountplanning_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Account Plan Subject',
                'key'       => '{accountplanning_subject}',
                'available' => ['accountplanning'],
            ],
            [
                'name'      => 'Account Plan Link',
                'key'       => '{accountplanning_link}',
                'available' => ['accountplanning'],
            ],
            [
                'name'      => 'Account Plan Client',
                'key'       => '{accountplanning_client}',
                'available' => ['accountplanning'],
            ],
            [
                'name'      => 'Account Plan Status',
                'key'       => '{accountplanning_status}',
                'available' => ['accountplanning'],
            ],
            [
                'name'      => 'Account Plan Revenue Next Year',
                'key'       => '{accountplanning_revenue_next_year}',
                'available' => ['accountplanning'],
            ],
            [
                'name'      => 'Account Plan Date',
                'key'       => '{accountplanning_date}',
                'available' => ['accountplanning'],
            ],
            [
                'name'      => 'Account Plan Task Action',
                'key'       => '{accountplanning_task_action}',
                'available' => ['accountplanning_task'],
            ],
            [
                'name'      => 'Account Plan Task Deadline',
                'key'       => '{accountplanning_task_deadline}',
                'available' => ['accountplanning_task'],
            ],
            [
                'name'      => 'Account Plan Task Link',
                'key'       => '{accountplanning_task_link}',
                'available' => ['accountplanning_task'],
            ],
        ];
    }

    /**
     * Format account plan merge fields
     * @param int|array $plan_id plan id or plan row
     * @return array
     */
    public function format($plan_id)
    {
        $fields = [
            '{accountplanning_subject}'           => '',
            '{accountplanning_link}'               => '',
            '{accountplanning_client}'             => '',
            '{accountplanning_status}'             => '',
            '{accountplanning_revenue_next_year}'  => '',
            '{accountplanning_date}'               => '',
        ];

        $plan = is_array($plan_id) ? (object) $plan_id : null;
        if (!$plan) {
            $this->ci->load->model('accountplanning/accountplanning_model');
            $plan = $this->ci->accountplanning_model->get($plan_id);
            if (is_array($plan)) {
                $plan = (object) $plan;
            }
        }
        if (!$plan) {
            return $fields;
        }

        $fields['{accountplanning_subject}'] = e($plan->subject ?? '');
        $fields['{accountplanning_link}']    = admin_url('accountplanning/view/' . $plan->id);
        $fields['{accountplanning_client}']  = e($plan->company ?? $plan->client_name ?? '');
        $fields['{accountplanning_status}']  = isset($plan->status) ? _l('ap_status_' . $plan->status) : '';
        $fields['{accountplanning_revenue_next_year}'] = isset($plan->revenue_next_year) ? app_format_money($plan->revenue_next_year, get_base_currency()) : '';
        $fields['{accountplanning_date}']    = isset($plan->date) ? _d($plan->date) : '';

        return $fields;
    }

    /**
     * Format account plan task merge fields
     * @param int $task_id
     * @param int $plan_id
     * @return array
     */
    public function format_task($task_id, $plan_id)
    {
        $fields = [
            '{accountplanning_task_action}'   => '',
            '{accountplanning_task_deadline}' => '',
            '{accountplanning_task_link}'     => '',
        ];

        $this->ci->db->select('t.*, a.subject');
        $this->ci->db->from(db_prefix() . 'accountplanning_task t');
        $this->ci->db->join(db_prefix() . 'accountplanning a', 'a.id = t.accountplanning_id');
        $this->ci->db->where('t.id', (int) $task_id);
        $task = $this->ci->db->get()->row();
        if (!$task) {
            return $fields;
        }

        $fields['{accountplanning_task_action}']   = e($task->action_needed ?? '');
        $fields['{accountplanning_task_deadline}'] = isset($task->deadline) ? _d($task->deadline) : '';
        $fields['{accountplanning_task_link}']     = admin_url('accountplanning/view/' . ($plan_id ?: $task->accountplanning_id) . '?group=planning');

        return $fields;
    }
}
