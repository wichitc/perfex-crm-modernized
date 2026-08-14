<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Accountplanning extends AdminController
{
	 public function __construct()
    {
        parent::__construct();
        $this->load->model('tickets_model');
        $this->load->model('accountplanning_model');
        $this->load->model('staff_model');
    }
    public function settings()
    {
        if (!is_admin()) {
            access_denied('accountplanning');
        }
        $data['title'] = _l('settings') . ' - ' . _l('accountplanning');
        $data['webhooks'] = [];
        if ($this->db->table_exists(db_prefix() . 'accountplanning_webhooks')) {
            $data['webhooks'] = $this->db->get(db_prefix() . 'accountplanning_webhooks')->result_array();
        }
        $this->load->view('accountplanning/settings', $data);
    }

    public function add_webhook()
    {
        if (!is_admin()) {
            access_denied('accountplanning');
        }
        $url = $this->input->post('webhook_url');
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            if ($this->db->table_exists(db_prefix() . 'accountplanning_webhooks')) {
                $this->db->insert(db_prefix() . 'accountplanning_webhooks', [
                    'url' => $url,
                    'events' => json_encode(['plan.created', 'plan.updated', 'plan.deleted']),
                    'active' => 1,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);
                set_alert('success', _l('added_successfully', _l('ap_webhooks')));
            }
        } else {
            set_alert('danger', _l('invalid_data'));
        }
        redirect(admin_url('accountplanning/settings'));
    }

    public function delete_webhook($id)
    {
        if (!is_admin()) {
            access_denied('accountplanning');
        }
        if ($this->db->table_exists(db_prefix() . 'accountplanning_webhooks')) {
            $this->db->where('id', (int) $id);
            $this->db->delete(db_prefix() . 'accountplanning_webhooks');
            set_alert('success', _l('deleted', _l('ap_webhooks')));
        }
        redirect(admin_url('accountplanning/settings'));
    }

    public function create_next_period($id)
    {
        if (!has_permission('accountplanning', '', 'create')) {
            access_denied('accountplanning');
        }
        $plan = $this->accountplanning_model->get($id);
        if (!$plan || !is_object($plan)) {
            show_404();
        }
        $plan = (array) $plan;
        $current_date = !empty($plan['date']) ? $plan['date'] : date('Y-m-d');
        $next_date = date('Y-m-01', strtotime($current_date . ' +1 month'));
        $copy_data = ['client_id' => $plan['client_id'], 'date' => $next_date, 'subject' => ($plan['subject'] ?? '') . ' - ' . _d($next_date)];
        $new_id = $this->accountplanning_model->copy($id, $copy_data);
        if ($new_id) {
            set_alert('success', _l('ap_create_next_period') . ': ' . _l('added_successfully', _l('accountplanning')));
            redirect(admin_url('accountplanning/view/' . $new_id));
        } else {
            set_alert('danger', _l('something_went_wrong'));
            redirect(admin_url('accountplanning/view/' . $id));
        }
    }

    public function compare($id)
    {
        if (!has_permission('accountplanning', '', 'view')) {
            access_denied('accountplanning');
        }
        $plan_a = $this->accountplanning_model->get($id);
        if (!$plan_a || !is_object($plan_a)) {
            show_404();
        }
        $plan_a = (array) $plan_a;
        $client_id = $plan_a['client_id'];
        $other_plans = $this->accountplanning_model->get_plans_by_client($client_id);
        $data['plan_a'] = $plan_a;
        $data['plan_b'] = null;
        $data['plan_b_id'] = $this->input->get('plan_b');
        $data['other_plans'] = array_filter($other_plans, function($p) use ($id) { return $p['id'] != $id; });
        if ($data['plan_b_id']) {
            $plan_b = $this->accountplanning_model->get($data['plan_b_id']);
            if ($plan_b && $plan_b->client_id == $client_id) {
                $data['plan_b'] = is_object($plan_b) ? (array) $plan_b : $plan_b;
            }
        }
        $data['title'] = _l('ap_compare_plans');
        $this->load->view('accountplanning/compare', $data);
    }

    public function notify_assignees($id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        $plan = $this->accountplanning_model->get($id);
        if (!$plan || !is_object($plan)) {
            show_404();
        }
        $this->db->select('t.*');
        $this->db->from(db_prefix() . 'accountplanning_task t');
        $this->db->where('t.accountplanning_id', (int) $id);
        $this->db->where('t.deadline <', date('Y-m-d'));
        $this->db->where('t.status !=', 'Complete');
        $this->db->where('(t.convert_to_task = 0 OR t.convert_to_task IS NULL)', null, false);
        $tasks = $this->db->get()->result_array();
        $staff_emails = [];
        foreach ($tasks as $t) {
            if (!empty($t['pic'])) {
                foreach (array_filter(explode('|', $t['pic'])) as $staff_id) {
                    $staff = $this->staff_model->get($staff_id);
                    if ($staff && !empty($staff->email)) {
                        $staff_emails[$staff_id] = $staff;
                    }
                }
            }
        }
        $sent = 0;
        $subject = _l('accountplanning') . ': ' . _l('ap_overdue_tasks') . ' - ' . ($plan->subject ?? '');
        $body = _l('ap_overdue_tasks') . ":\n\n";
        foreach ($tasks as $t) {
            $body .= '- ' . ($t['action_needed'] ?? $t['item'] ?? '') . ' (' . _d($t['deadline'] ?? '') . ")\n";
        }
        $body .= "\n" . admin_url('accountplanning/view/' . $id . '?group=planning');
        $this->load->helper('email');
        foreach ($staff_emails as $staff) {
            if (send_email($staff->email, $subject, $body)) {
                $sent++;
            }
        }
        if ($sent > 0) {
            set_alert('success', sprintf(_l('ap_notify_sent'), $sent));
        } else {
            set_alert('warning', _l('ap_no_assignees_to_notify'));
        }
        redirect(admin_url('accountplanning/view/' . $id . '?group=planning'));
    }

    public function add_meeting_note($accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        if ($this->accountplanning_model->add_meeting_note($accountplanning_id, $this->input->post())) {
            set_alert('success', _l('added_successfully', _l('ap_meeting_notes')));
        }
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=meeting_notes'));
    }

    public function update_meeting_note($id, $accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        if ($this->accountplanning_model->update_meeting_note($id, $this->input->post())) {
            set_alert('success', _l('updated_successfully', _l('ap_meeting_notes')));
        }
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=meeting_notes'));
    }

    public function delete_meeting_note($id, $accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        $this->accountplanning_model->delete_meeting_note($id);
        set_alert('success', _l('deleted', _l('ap_meeting_notes')));
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=meeting_notes'));
    }

    public function add_competitor($accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        if ($this->accountplanning_model->add_competitor($accountplanning_id, $this->input->post())) {
            set_alert('success', _l('added_successfully', _l('ap_competitors')));
        }
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=competitors'));
    }

    public function update_competitor($id, $accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        if ($this->accountplanning_model->update_competitor($id, $this->input->post())) {
            set_alert('success', _l('updated_successfully', _l('ap_competitors')));
        }
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=competitors'));
    }

    public function delete_competitor($id, $accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        $this->accountplanning_model->delete_competitor($id);
        set_alert('success', _l('deleted', _l('ap_competitors')));
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=competitors'));
    }

    public function mark_update_request_handled($accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        $tbl = db_prefix() . 'accountplanning_update_requests';
        if ($this->db->table_exists($tbl)) {
            $this->db->where('accountplanning_id', (int) $accountplanning_id);
            $this->db->where('status', 'pending');
            $this->db->update($tbl, ['status' => 'handled']);
        }
        set_alert('success', _l('settings_updated'));
        redirect(admin_url('accountplanning/view/' . $accountplanning_id));
    }

    public function add_goal($accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        $name = $this->input->post('name');
        if ($name && $this->db->table_exists(db_prefix() . 'accountplanning_goals')) {
            $this->accountplanning_model->add_goal($accountplanning_id, [
                'name' => $name,
                'target' => $this->input->post('target') ?: null,
                'actual' => $this->input->post('actual') ?: null,
                'due_date' => $this->input->post('due_date') ? to_sql_date($this->input->post('due_date')) : null,
            ]);
            set_alert('success', _l('added_successfully', _l('ap_goals_kpis')));
        }
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=planning'));
    }

    public function delete_goal($accountplanning_id, $id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        if ($this->db->table_exists(db_prefix() . 'accountplanning_goals')) {
            $this->accountplanning_model->delete_goal($id);
            set_alert('success', _l('deleted', _l('ap_goals_kpis')));
        }
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=planning'));
    }

    public function save_settings()
    {
        if (!is_admin()) {
            access_denied('accountplanning');
        }
        update_option('accountplanning_industry_options', $this->input->post('accountplanning_industry_options'));
        update_option('accountplanning_client_portal_enabled', $this->input->post('accountplanning_client_portal_enabled') ? '1' : '0');
        update_option('accountplanning_reminders_enabled', $this->input->post('accountplanning_reminders_enabled') ? '1' : '0');
        $reminder_days = (int) $this->input->post('accountplanning_reminder_days');
        update_option('accountplanning_reminder_days', $reminder_days >= 1 && $reminder_days <= 30 ? $reminder_days : 3);
        $default_status = $this->input->post('accountplanning_default_status');
        if (in_array($default_status, ['draft', 'in_progress', 'review'])) {
            update_option('accountplanning_default_status', $default_status);
        }
        update_option('accountplanning_webhook_ssl_verify', $this->input->post('accountplanning_webhook_ssl_verify') ? '0' : '1');
        update_option('accountplanning_recurring_cron_enabled', $this->input->post('accountplanning_recurring_cron_enabled') ? '1' : '0');
        $recurring_period = $this->input->post('accountplanning_recurring_period');
        if (in_array($recurring_period, ['month', 'quarter'])) {
            update_option('accountplanning_recurring_period', $recurring_period);
        }
        set_alert('success', _l('settings_updated'));
        redirect(admin_url('accountplanning/settings'));
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('accountplanning', 'table'));
        }
        $data['month'] = $this->accountplanning_model->get_month();
        $data['saved_filters'] = $this->accountplanning_model->get_saved_filters();
        $data['clients'] = $this->clients_model->get();
        $data['staff_for_pic'] = $this->staff_model->get();
        $data['title']          = _l('als_accountplanning');
        $this->load->view('accountplanning/manage', $data);
    }
    public function table($client_id = '')
    {
        if($client_id == ''){
            $this->app->get_table_data('accountplanning');
        }else{
            $this->app->get_table_data('accountplanning', [
            'client_id' => $client_id
        ]);
        }
    }
    public function templates()
    {
        if (!has_permission('accountplanning', '', 'view')) {
            access_denied('accountplanning');
        }
        $data['templates'] = $this->accountplanning_model->get_templates();
        $data['title'] = _l('ap_templates');
        $this->load->view('accountplanning/templates', $data);
    }

    public function get_template($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $t = $this->accountplanning_model->get_template($id);
        header('Content-Type: application/json');
        echo json_encode($t ? (array)$t : null);
    }

    public function save_template()
    {
        if (!has_permission('accountplanning', '', 'create')) {
            access_denied('accountplanning');
        }
        $id = $this->input->post('id');
        $data = [
            'name' => $this->input->post('name'),
            'subject' => $this->input->post('subject'),
            'vision' => $this->input->post('vision'),
            'mission' => $this->input->post('mission'),
            'objectives' => $this->input->post('objectives'),
            'threat' => $this->input->post('threat'),
            'opportunity' => $this->input->post('opportunity'),
            'criteria_to_success' => $this->input->post('criteria_to_success'),
            'constraints' => $this->input->post('constraints'),
        ];
        if ($id) {
            $this->accountplanning_model->update_template($id, $data);
            set_alert('success', _l('updated_successfully', _l('ap_templates')));
        } else {
            $this->accountplanning_model->add_template($data);
            set_alert('success', _l('added_successfully', _l('ap_templates')));
        }
        redirect(admin_url('accountplanning/templates'));
    }

    public function delete_template($id)
    {
        if (!has_permission('accountplanning', '', 'delete')) {
            access_denied('accountplanning');
        }
        $this->accountplanning_model->delete_template($id);
        set_alert('success', _l('deleted', _l('ap_templates')));
        redirect(admin_url('accountplanning/templates'));
    }

    public function create_from_template($template_id)
    {
        if (!has_permission('accountplanning', '', 'create')) {
            access_denied('accountplanning');
        }
        $template = $this->accountplanning_model->get_template($template_id);
        if (!$template) {
            show_404();
        }
        $data['template'] = $template;
        $data['clients'] = $this->clients_model->get();
        $data['month'] = $this->accountplanning_model->get_month();
        $data['title'] = _l('ap_create_from_template');
        $this->load->view('accountplanning/create_from_template', $data);
    }

    public function do_create_from_template($template_id)
    {
        if (!has_permission('accountplanning', '', 'create')) {
            access_denied('accountplanning');
        }
        $client_id = $this->input->post('client_id');
        $subject = $this->input->post('subject');
        $date = $this->input->post('date');
        if (!$client_id || !$subject || !$date) {
            set_alert('danger', _l('invalid_data'));
            redirect(admin_url('accountplanning/create_from_template/' . $template_id));
        }
        $id = $this->accountplanning_model->create_from_template($template_id, $client_id, $subject, $date);
        if ($id) {
            set_alert('success', _l('added_successfully', _l('accountplanning')));
            redirect(admin_url('accountplanning/view/' . $id));
        } else {
            set_alert('danger', _l('something_went_wrong'));
            redirect(admin_url('accountplanning/create_from_template/' . $template_id));
        }
    }

    public function new_account($id = '')
    {

        if(!has_permission('accountplanning','','create')){
                access_denied('accountplanning');
        }
        $data['month'] = $this->accountplanning_model->get_month();
    	$data['priorities'] = $this->tickets_model->get_priority();
        $data['title']     = _l('new_account');
        $this->load->view('accountplanning/new_account', $data);
		\modules\accountplanning\core\Apiinit::ease_of_mind('accountplanning');
		\modules\accountplanning\core\Apiinit::the_da_vinci_code('accountplanning');
    }
    public function add()
    {
    	$data = $this->input->post();
    	if (empty($data['client_id'])) {
    	    set_alert('danger', str_replace('{field}', _l('client'), _l('form_validation_required')));
    	    redirect(admin_url('accountplanning/new_account'));
    	    return;
    	}
    	if (isset($data['custom_fields'])) {
    	    $custom_fields = $data['custom_fields'];
    	    unset($data['custom_fields']);
    	} else {
    	    $custom_fields = [];
    	}
    	$id = $this->accountplanning_model->add($data);
    	if ($id && !empty($custom_fields)) {
    	    handle_custom_fields_post($id, $custom_fields);
    	}
    	if ($id) {
	        set_alert('success', _l('added_successfully', _l('accountplanning')));
	        redirect(admin_url('accountplanning?added=1'));
    	} else {
	        set_alert('danger', _l('something_went_wrong'));
	        redirect(admin_url('accountplanning/new_account'));
    	}
    }

    public function delete($id)
    {
        if(!has_permission('accountplanning','','delete')){
                access_denied('accountplanning');
        }
        if (!$id) {
            redirect(admin_url('accountplanning'));
        }
        $response = $this->accountplanning_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('accountplanning')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('accountplanning')));
        }
        redirect(admin_url('accountplanning'));
    }

    public function bulk_action()
    {
        if (!$this->input->is_ajax_request()) {
            access_denied('accountplanning');
        }
        header('Content-Type: application/json');
        $ids = $this->input->post('ids');
        if (!is_array($ids) || empty($ids)) {
            echo json_encode(['success' => false, 'message' => _l('ap_select_at_least_one')]);
            return;
        }
        if ($this->input->post('mass_delete')) {
            if (!has_permission('accountplanning', '', 'delete')) {
                echo json_encode(['success' => false, 'message' => _l('access_denied')]);
                return;
            }
            $deleted = 0;
            foreach ($ids as $id) {
                if ($this->accountplanning_model->delete($id)) {
                    $deleted++;
                }
            }
            echo json_encode(['success' => true, 'message' => _l('deleted', $deleted . ' ' . _l('accountplanning'))]);
        } elseif ($status = $this->input->post('bulk_status')) {
            if (!has_permission('accountplanning', '', 'edit')) {
                echo json_encode(['success' => false, 'message' => _l('access_denied')]);
                return;
            }
            $valid = ['draft', 'in_progress', 'review', 'completed', 'archived'];
            if (!in_array($status, $valid)) {
                echo json_encode(['success' => false, 'message' => _l('invalid_status')]);
                return;
            }
            $updated = $this->accountplanning_model->bulk_update_status($ids, $status);
            echo json_encode(['success' => true, 'message' => _l('updated_successfully', _l('accountplanning'))]);
        } elseif ($this->input->post('bulk_copy') && $this->input->post('bulk_copy_date')) {
            if (!has_permission('accountplanning', '', 'create')) {
                echo json_encode(['success' => false, 'message' => _l('access_denied')]);
                return;
            }
            $date = to_sql_date($this->input->post('bulk_copy_date'));
            $copied = 0;
            foreach ($ids as $id) {
                $plan = $this->accountplanning_model->get($id);
                if ($plan && is_object($plan)) {
                    $copyData = ['client_id' => $plan->client_id, 'date' => $date, 'subject' => $plan->subject];
                    if ($this->accountplanning_model->copy($id, $copyData)) {
                        $copied++;
                    }
                }
            }
            echo json_encode(['success' => true, 'message' => _l('ap_bulk_copy') . ': ' . $copied . ' ' . _l('accountplanning')]);
        } else {
            echo json_encode(['success' => false, 'message' => _l('ap_select_action')]);
        }
    }

    public function update_due_diligence($id)
    {
        if(!has_permission('accountplanning','','edit')){
                access_denied('accountplanning/view/'.$id);
        }
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$id));
        }
        $data = $this->input->post();
        if (isset($data['custom_fields'])) {
            handle_custom_fields_post($id, $data['custom_fields']);
            unset($data['custom_fields']);
        }
        $response = $this->accountplanning_model->update_due_diligence($id, $data);
        if ($response == true) {
            set_alert('success', _l('updated_successfully', _l('accountplanning')));
        } else {
            set_alert('warning', _l('problem_updating', _l('accountplanning')));
        }
        redirect(admin_url('accountplanning/view/'.$id));
    }
    public function update_team_information($id)
    {
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$id.'?group=team_information'));
        }
        if(!has_permission('accountplanning','','edit')){
                access_denied('accountplanning/view/'.$id.'?group=team_information');
        }
        $data = $this->input->post();
        $response = $this->accountplanning_model->update_team_information($id, $data);
        if ($response == true) {
            set_alert('success', _l('updated_successfully', _l('accountplanning')));
        } else {
            set_alert('warning', _l('problem_updating', _l('accountplanning')));
        }
        redirect(admin_url('accountplanning/view/'.$id.'?group=team_information'));
    }

    public function update_service_ability_offering($id)
    {
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$id.'?group=service_ability_offering'));
        }
        if(!has_permission('accountplanning','','edit')){
                access_denied('accountplanning/view/'.$id.'?group=service_ability_offering');
        }
        $data = $this->input->post();
        $response = $this->accountplanning_model->update_service_ability_offering($id, $data);
        if ($response == true) {
            set_alert('success', _l('updated_successfully', _l('accountplanning')));
        } else {
            set_alert('warning', _l('problem_updating', _l('accountplanning')));
        }
        redirect(admin_url('accountplanning/view/'.$id.'?group=service_ability_offering'));
    }

    public function update_planning($id)
    {
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
        }
        if(!has_permission('accountplanning','','edit')){
                access_denied('accountplanning/view/'.$id.'?group=planning');
        }
        $data = $this->input->post();
        $data['data_tree'] = $this->input->post('data_tree', false);
        $data['objectives'] = $this->input->post('objectives', false);
        $data['threat'] = $this->input->post('threat', false);
        $data['opportunity'] = $this->input->post('opportunity', false);
        $data['criteria_to_success'] = $this->input->post('criteria_to_success', false);
        $data['constraints'] = $this->input->post('constraints', false);

        $response = $this->accountplanning_model->update_planning($id, $data);
        if ($response == true) {
            set_alert('success', _l('updated_successfully', _l('accountplanning')));
        } else {
            set_alert('warning', _l('problem_updating', _l('accountplanning')));
        }
		
		\modules\accountplanning\core\Apiinit::ease_of_mind('accountplanning');
		\modules\accountplanning\core\Apiinit::the_da_vinci_code('accountplanning');
		
        redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
    }

    public function view($id = '')
    {
		\modules\accountplanning\core\Apiinit::ease_of_mind('accountplanning');
		\modules\accountplanning\core\Apiinit::the_da_vinci_code('accountplanning');
        if($id != ''){  
            $data['account'] = $this->accountplanning_model->get($id);
            $data['pending_update_requests'] = $this->accountplanning_model->get_pending_update_requests($id);
            if (!$this->input->get('group')) {
                $group = 'due_diligence';
            } else {
                $group = $this->input->get('group');
            }
            if($group == 'team_information'){
                $data['teams'] = $this->staff_model->get();
                $data['client_team'] = $this->clients_model->get_contacts($data['account']->client_id);
                $data['data_pmax_team'] = $this->accountplanning_model->get_pmax_team($id);
                $data['data_client_team'] = $this->accountplanning_model->get_client_team($id);
            }elseif ($group == 'due_diligence') {
                $data['financial'] = $this->accountplanning_model->get_financial($id);
                $data['marketing_activities'] = $this->accountplanning_model->get_marketing_activities($id);
                $data['billing_shipping'] = $this->clients_model->get_customer_billing_and_shipping_details($data['account']->client_id);
                $data['billing_shipping'] =$data['billing_shipping'][0];
            }elseif ($group == 'service_ability_offering') {
                $data['service_ability_offering'] = $this->accountplanning_model->get_service_ability_offering($id);
                $data['current_service'] = $this->accountplanning_model->get_current_service($id);
            }elseif ($group == 'planning') {
                $data['staff'] = $this->accountplanning_model->get_pic_todolist();
                $data['month'] = $this->accountplanning_model->get_month();
                $data['todo_list'] = $this->accountplanning_model->get_todo_list($id);
                $data['objectives'] = $this->accountplanning_model->get_objectives($id);
                $data['items'] = $this->accountplanning_model->get_items($id);
                $data['health_score'] = $this->accountplanning_model->get_health_score($id);
                $data['goals'] = $this->accountplanning_model->get_goals($id);
            } elseif ($group == 'relations') {
                $relations = $this->accountplanning_model->get_relations($id);
                $data['relations'] = $this->accountplanning_model->get_relation_details($relations);
                $data['project_tasks'] = $this->accountplanning_model->get_linked_project_tasks($id);
                $data['project_milestones'] = $this->accountplanning_model->get_linked_project_milestones($id);
            } elseif ($group == 'meeting_notes') {
                $data['meeting_notes'] = $this->accountplanning_model->get_meeting_notes($id);
            } elseif ($group == 'competitors') {
                $data['competitors'] = $this->accountplanning_model->get_competitors($id);
            }
            

            $data['priorities'] = $this->tickets_model->get_priority();
            $data['group'] = $group;
            $data['title']     = _l('accountplanning');
            $this->load->view('accountplanning/accountplanning', $data);
        }else{
            $data['title']          = _l('als_accountplanning');
            $data = add_breadcrumbs($data,$this);
            $this->load->view('accountplanning/manage', $data);
        }
    }
    public function client_change_data($customer_id = '')
    {
        if ($this->input->is_ajax_request()) {
            $data                     = [];
            $data['billing_shipping'] = $this->clients_model->get_customer_billing_and_shipping_details($customer_id);
            $data['billing_shipping'][0]['billing_country'] = get_country_short_name($data['billing_shipping'][0]['billing_country']);
            echo json_encode($data);
        }
    }

    public function objective($id = '')
    {
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $data = $this->input->post();
                $response = $this->accountplanning_model->add_objective($id, $data);
                if ($response == true) {
                    set_alert('success', _l('added_successfully', _l('objective')));
                } else {
                    set_alert('warning', _l('failed_to_insert', _l('objective')));
                }
                redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
                if (!is_admin()) {
                    access_denied('Ticket Priorities');
                }
            } else {
                $data = $this->input->post();
                $objective_id   = $data['id'];
                unset($data['id']);
                $success = $this->accountplanning_model->update_objective($objective_id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('objective')));
                }
                redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
            }
            die;
        }
    }
    public function item($id = '')
    {
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $data = $this->input->post();
                $response = $this->accountplanning_model->add_item($id, $data);
                if ($response == true) {
                    set_alert('success', _l('added_successfully', _l('objective_items')));
                } else {
                    set_alert('warning', _l('failed_to_insert', _l('objective_items')));
                }
                redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
            } else {
                $data = $this->input->post();
                $item_id   = $data['id'];
                unset($data['id']);
                $success = $this->accountplanning_model->update_item($item_id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('objective_items')));
                }
                redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
            }
            die;
        }
    }
    public function task($id = '')
    {
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $data = $this->input->post();
                $response = $this->accountplanning_model->add_task($id, $data);
                if ($response == true) {
                    set_alert('success', _l('added_successfully', _l('task')));
                } else {
                    set_alert('warning', _l('failed_to_insert', _l('task')));
                }
                redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
            } else {
                $data = $this->input->post();
                $task_id   = $data['id'];
                unset($data['id']);
                $success = $this->accountplanning_model->update_task($task_id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('objective_items')));
                }
                redirect(admin_url('accountplanning/view/'.$id.'?group=planning'));
            }
            die;
        }
    }

    public function delete_objective($accountplanning_id, $id = '')
    {
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$accountplanning_id.'?group=planning'));
        }
        $response = $this->accountplanning_model->delete_objective($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('objective')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('objective')));
        }
        redirect(admin_url('accountplanning/view/'.$accountplanning_id.'?group=planning'));
    }
    public function delete_item($accountplanning_id, $id = '')
    {
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$accountplanning_id.'?group=planning'));
        }
        $data = $this->input->post();
        $response = $this->accountplanning_model->delete_item($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('objective')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('objective')));
        }
        redirect(admin_url('accountplanning/view/'.$accountplanning_id.'?group=planning'));
    }
    public function delete_task($accountplanning_id, $id = '')
    {
        if(!has_permission('accountplanning','','edit')){
            access_denied('accountplanning/view/'.$accountplanning_id.'?group=planning');
        }
        if (!$id) {
            redirect(admin_url('accountplanning/view/'.$accountplanning_id.'?group=planning'));
        }
        $data = $this->input->post();
        $response = $this->accountplanning_model->delete_task($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('objective')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('objective')));
        }
        redirect(admin_url('accountplanning/view/'.$accountplanning_id.'?group=planning'));
    }

    public function copy($accountplanning_id)
    {
        if(!has_permission('accountplanning','','create')){
                access_denied('accountplanning');
        }
        $id = $this->accountplanning_model->copy($accountplanning_id, $this->input->post());
        if ($id) {
            set_alert('success', _l('accountplanning_copied_successfully'));
            redirect(admin_url('accountplanning'));
        } else {
            set_alert('danger', _l('failed_to_copy_accountplanning'));
            redirect(admin_url('accountplanning'));
        }
    }

    public function delete_attachment($id)
    {
        $file = $this->misc_model->get_file($id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo htmlspecialchars($this->accountplanning_model->delete_attachment($id));
        } else {
            header('HTTP/1.0 400 Bad error');
            echo htmlspecialchars(_l('access_denied'));
            die;
        }
    }

    public function file($id, $rel_id)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin']             = is_admin();
        $data['file'] = $this->accountplanning_model->get_file($id, $rel_id);
        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }
        $this->load->view('accountplanning/_file', $data);
    }

    public function download_file($attachmentid = '')
    {
        $this->load->helper('download');
        $this->db->where('attachment_key', $attachmentid);
        $attachment = $this->db->get(db_prefix().'files')->row();
        if (!$attachment) {
            show_404();
        }
        $path = ACCOUNT_PLANNING_ATTACHMENTS_FOLDER . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
    }

    public function add_relation()
    {
        if (!$this->input->is_ajax_request() || !has_permission('accountplanning', '', 'edit')) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => _l('access_denied')]);
            return;
        }
        $accountplanning_id = (int) $this->input->post('accountplanning_id');
        $rel_type = $this->input->post('rel_type');
        $rel_id = (int) $this->input->post('rel_id');
        $valid = ['project', 'invoice', 'estimate', 'proposal'];
        if (!in_array($rel_type, $valid) || !$accountplanning_id || !$rel_id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => _l('invalid_data')]);
            return;
        }
        $id = $this->accountplanning_model->add_relation($accountplanning_id, $rel_type, $rel_id);
        header('Content-Type: application/json');
        if ($id) {
            echo json_encode(['success' => true, 'message' => _l('added_successfully', _l('ap_relations'))]);
        } else {
            echo json_encode(['success' => false, 'message' => _l('ap_relation_already_exists')]);
        }
        exit;
    }

    public function delete_relation($id, $accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'edit')) {
            access_denied('accountplanning');
        }
        $this->accountplanning_model->delete_relation($id);
        redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=relations'));
    }

    public function get_relation_options($type, $accountplanning_id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $options = [];
        $client_id = $this->accountplanning_model->get($accountplanning_id);
        $client_id = $client_id && is_object($client_id) ? $client_id->client_id : 0;
        if ($type == 'project' && $this->db->table_exists(db_prefix() . 'projects')) {
            $this->db->select('id, name');
            if ($client_id) {
                $this->db->where('clientid', $client_id);
            }
            $rows = $this->db->get(db_prefix() . 'projects')->result();
            foreach ($rows as $r) {
                $options[] = ['id' => $r->id, 'name' => $r->name];
            }
        } elseif ($type == 'invoice' && $this->db->table_exists(db_prefix() . 'invoices')) {
            $this->db->select('id');
            if ($client_id) {
                $this->db->where('clientid', $client_id);
            }
            $rows = $this->db->get(db_prefix() . 'invoices')->result();
            foreach ($rows as $r) {
                $options[] = ['id' => $r->id, 'name' => (function_exists('format_invoice_number') ? format_invoice_number($r->id) : ('Invoice #' . $r->id))];
            }
        } elseif ($type == 'estimate' && $this->db->table_exists(db_prefix() . 'estimates')) {
            $this->db->select('id');
            if ($client_id) {
                $this->db->where('clientid', $client_id);
            }
            $rows = $this->db->get(db_prefix() . 'estimates')->result();
            foreach ($rows as $r) {
                $options[] = ['id' => $r->id, 'name' => (function_exists('format_estimate_number') ? format_estimate_number($r->id) : ('Estimate #' . $r->id))];
            }
        } elseif ($type == 'proposal' && $this->db->table_exists(db_prefix() . 'proposals')) {
            $this->db->select('id, subject');
            if ($client_id) {
                $this->db->where('rel_id', $client_id);
                $this->db->where('rel_type', 'customer');
            }
            $rows = $this->db->get(db_prefix() . 'proposals')->result();
            foreach ($rows as $r) {
                $options[] = ['id' => $r->id, 'name' => $r->subject];
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['options' => $options]);
    }

    public function save_filter()
    {
        if (!$this->input->post() || !has_permission('accountplanning', '', 'view')) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => _l('access_denied')]);
            return;
        }
        $name = $this->input->post('name');
        $filters = $this->input->post('filters');
        if (empty($name)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => _l('ap_filter_name')]);
            return;
        }
        $id = $this->accountplanning_model->add_saved_filter($name, $filters);
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool)$id, 'message' => $id ? _l('added_successfully', _l('ap_saved_filters')) : _l('something_went_wrong')]);
    }

    public function delete_saved_filter($id)
    {
        if (!has_permission('accountplanning', '', 'view')) {
            access_denied('accountplanning');
        }
        $this->accountplanning_model->delete_saved_filter($id);
        redirect(admin_url('accountplanning'));
    }

    public function quick_create_project($accountplanning_id)
    {
        if (!has_permission('accountplanning', '', 'create') || !$this->db->table_exists(db_prefix() . 'projects')) {
            access_denied('accountplanning');
        }
        $account = $this->accountplanning_model->get($accountplanning_id);
        if (!$account) {
            show_404();
        }
        $project_data = [
            'name' => $account->subject . ' - ' . _l('accountplanning'),
            'clientid' => $account->client_id,
            'description' => $account->objectives ?? '',
            'start_date' => date('Y-m-d'),
            'billing_type' => 1,
        ];
        if (function_exists('get_project_tabs_admin')) {
            $tabs = get_project_tabs_admin();
            $tab_slugs = [];
            foreach ($tabs as $tab) {
                if (isset($tab['collapse']) && !empty($tab['children'])) {
                    foreach ($tab['children'] as $d) {
                        $tab_slugs[] = $d['slug'];
                    }
                } elseif (!empty($tab['slug'])) {
                    $tab_slugs[] = $tab['slug'];
                }
            }
            if (!empty($tab_slugs)) {
                $project_data['settings'] = ['available_features' => $tab_slugs];
            }
        }
        if (class_exists('Projects_model')) {
            $this->load->model('projects_model');
            $pid = $this->projects_model->add($project_data);
        } else {
            $this->db->insert(db_prefix() . 'projects', array_merge($project_data, ['datecreated' => date('Y-m-d H:i:s')]));
            $pid = $this->db->insert_id();
        }
        if ($pid) {
            $this->accountplanning_model->add_relation($accountplanning_id, 'project', $pid);
            set_alert('success', _l('added_successfully', _l('project')));
            redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=relations'));
        } else {
            set_alert('danger', _l('something_went_wrong'));
            redirect(admin_url('accountplanning/view/' . $accountplanning_id . '?group=relations'));
        }
    }

    public function kanban()
    {
        if (!has_permission('accountplanning', '', 'view')) {
            access_denied('accountplanning');
        }
        $data['plans'] = [];
        $rows = $this->accountplanning_model->get();
        foreach ($rows as $r) {
            $s = isset($r['status']) ? $r['status'] : 'draft';
            if (!isset($data['plans'][$s])) $data['plans'][$s] = [];
            $data['plans'][$s][] = $r;
        }
        $data['title'] = _l('ap_kanban_view');
        $this->load->view('accountplanning/kanban', $data);
    }

    public function update_status_ajax()
    {
        if (!$this->input->is_ajax_request() || !has_permission('accountplanning', '', 'edit')) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
            return;
        }
        $id = (int) $this->input->post('id');
        $status = $this->input->post('status');
        $valid = ['draft', 'in_progress', 'review', 'completed', 'archived'];
        if (!$id || !in_array($status, $valid)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
            return;
        }
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'accountplanning', ['status' => $status, 'new_update' => date('Y-m-d H:i:s')]);
        $updated = $this->db->affected_rows() > 0;
        if ($updated && function_exists('accountplanning_trigger_webhooks')) {
            accountplanning_trigger_webhooks('plan.updated', $id, ['status' => $status]);
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => $updated]);
    }

    public function export_excel()
    {
        if (!has_permission('accountplanning', '', 'view')) {
            access_denied('accountplanning');
        }
        $plans = $this->accountplanning_model->get();
        $headers = ['ID', _l('subject'), _l('client_name'), _l('date'), _l('plan_status'), _l('objective'), _l('revenue_next_year'), _l('margin'), _l('wallet_share'), _l('client_status'), _l('bcg_model')];
        $basecur = get_base_currency();

        if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle(_l('accountplanning'));
            $row = 1;
            $col = 1;
            foreach ($headers as $h) {
                $sheet->setCellValueByColumnAndRow($col++, $row, $h);
            }
            $row++;
            foreach ($plans as $p) {
                $col = 1;
                $sheet->setCellValueByColumnAndRow($col++, $row, $p['id']);
                $sheet->setCellValueByColumnAndRow($col++, $row, $p['subject']);
                $sheet->setCellValueByColumnAndRow($col++, $row, $p['company']);
                $sheet->setCellValueByColumnAndRow($col++, $row, _d($p['date']));
                $sheet->setCellValueByColumnAndRow($col++, $row, isset($p['status']) ? _l('ap_status_' . $p['status']) : _l('ap_status_draft'));
                $sheet->setCellValueByColumnAndRow($col++, $row, $p['objectives']);
                $sheet->setCellValueByColumnAndRow($col++, $row, app_format_money($p['revenue_next_year'], $basecur));
                $sheet->setCellValueByColumnAndRow($col++, $row, $p['margin']);
                $sheet->setCellValueByColumnAndRow($col++, $row, $p['wallet_share']);
                $sheet->setCellValueByColumnAndRow($col++, $row, $p['client_status']);
                $sheet->setCellValueByColumnAndRow($col++, $row, $p['bcg_model']);
                $row++;
            }
            $filename = 'account-plans-' . date('Y-m-d') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        $output = [];
        $output[] = $headers;
        foreach ($plans as $p) {
            $output[] = [
                $p['id'],
                $p['subject'],
                $p['company'],
                _d($p['date']),
                isset($p['status']) ? _l('ap_status_' . $p['status']) : _l('ap_status_draft'),
                $p['objectives'],
                app_format_money($p['revenue_next_year'], $basecur),
                $p['margin'],
                $p['wallet_share'],
                $p['client_status'],
                $p['bcg_model'],
            ];
        }
        $filename = 'account-plans-' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        foreach ($output as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }

    public function export_pdf($id)
    {
        if (!has_permission('accountplanning', '', 'view')) {
            access_denied('accountplanning');
        }
        $account = $this->accountplanning_model->get($id);
        if (!$account) {
            show_404();
        }
        $data['account'] = $account;
        $data['financial'] = $this->accountplanning_model->get_financial($id);
        $data['marketing_activities'] = $this->accountplanning_model->get_marketing_activities($id);
        $data['todo_list'] = $this->accountplanning_model->get_todo_list($id);
        $data['objectives'] = $this->accountplanning_model->get_objectives($id);
        $data['service_ability_offering'] = $this->accountplanning_model->get_service_ability_offering($id);
        $data['current_service'] = $this->accountplanning_model->get_current_service($id);
        $data['pmax_team'] = $this->accountplanning_model->get_pmax_team($id);
        $data['client_team'] = $this->accountplanning_model->get_client_team($id);
        $html = $this->load->view('accountplanning/export_pdf', $data, true);
        $pdf = $this->app_pdf->load();
        $pdf->SetTitle(_l('accountplanning') . ' - ' . $account->subject);
        $pdf->WriteHTML($html);
        $pdf->Output('account-plan-' . $id . '.pdf', 'D');
    }
}