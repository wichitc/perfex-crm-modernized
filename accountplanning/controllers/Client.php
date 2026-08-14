<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Client portal controller for Account Planning module
 * URL: accountplanning/client
 */
class Client extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model('accountplanning/accountplanning_model');

        if (get_option('accountplanning_client_portal_enabled') != '1') {
            if (!is_client_logged_in()) {
                redirect(site_url('authentication/login'));
            }
            show_404();
        }
        if (!is_client_logged_in()) {
            redirect(site_url('authentication/login'));
        }

        $client_id = get_client_user_id();
        $this->db->select('a.*, c.company as client_name');
        $this->db->from(db_prefix() . 'accountplanning a');
        $this->db->join(db_prefix() . 'clients c', 'c.userid = a.client_id');
        $this->db->where('a.client_id', $client_id);
        $this->db->where_in('a.status', ['in_progress', 'review', 'completed']);
        $plans = $this->db->get()->result_array();

        $this->data(['plans' => $plans]);
        $this->title(_l('accountplanning'));
        $this->view('client/index');
        $this->layout();
    }

    /**
     * View a single account plan
     * Note: Named view_plan() instead of view() to avoid collision with ClientsController::view()
     */
    public function view_plan($id)
    {
        $this->load->model('accountplanning/accountplanning_model');
        if (get_option('accountplanning_client_portal_enabled') != '1') {
            if (!is_client_logged_in()) {
                redirect(site_url('authentication/login'));
            }
            show_404();
        }
        if (!is_client_logged_in()) {
            redirect(site_url('authentication/login'));
        }
        if (!is_numeric($id)) {
            show_404();
        }
        $account = $this->accountplanning_model->get($id);
        if (!$account) {
            show_404();
        }
        if (is_array($account)) {
            $account = isset($account[0]) ? (object) $account[0] : (object) $account;
        }
        if ($account->client_id != get_client_user_id()) {
            show_404();
        }
        if (!in_array($account->status, ['in_progress', 'review', 'completed'])) {
            show_404();
        }
        $this->data([
            'account'    => $account,
            'objectives' => $this->accountplanning_model->get_objectives($id),
            'todo_list'  => $this->accountplanning_model->get_todo_list($id),
        ]);
        $this->title($account->subject);
        $this->view('client/view');
        $this->layout();
    }

    public function request_update($id)
    {
        $this->load->model('accountplanning/accountplanning_model');
        if (get_option('accountplanning_client_portal_enabled') != '1') {
            if (!is_client_logged_in()) {
                redirect(site_url('authentication/login'));
            }
            show_404();
        }
        if (!is_client_logged_in()) {
            redirect(site_url('authentication/login'));
        }
        if (!is_numeric($id)) {
            show_404();
        }
        $account = $this->accountplanning_model->get($id);
        if (!$account) {
            show_404();
        }
        if (is_array($account)) {
            $account = isset($account[0]) ? (object) $account[0] : (object) $account;
        }
        if ($account->client_id != get_client_user_id()) {
            show_404();
        }
        if (!in_array($account->status, ['in_progress', 'review', 'completed'])) {
            show_404();
        }
        $tbl = db_prefix() . 'accountplanning_update_requests';
        if (!$this->db->table_exists($tbl)) {
            set_alert('danger', _l('something_went_wrong'));
            redirect(site_url('accountplanning/client/view_plan/' . $id));
            return;
        }
        $contact_id = get_contact_user_id();
        $this->db->insert($tbl, [
            'accountplanning_id' => (int) $id,
            'contact_id' => $contact_id ?: null,
            'dateadded' => date('Y-m-d H:i:s'),
            'status' => 'pending',
        ]);
        $contact_name = '';
        if ($contact_id && $this->db->table_exists(db_prefix() . 'contacts')) {
            $c = $this->db->get_where(db_prefix() . 'contacts', ['id' => $contact_id])->row();
            $contact_name = $c ? get_contact_full_name($c->id) : '';
        }
        log_activity('Account Planning update requested by client [Plan ID: ' . $id . ', Subject: ' . ($account->subject ?? '') . ($contact_name ? ', Contact: ' . $contact_name : '') . ']');
        if (function_exists('accountplanning_notify_staff_of_update_request')) {
            accountplanning_notify_staff_of_update_request($id, $account, $contact_name);
        }
        set_alert('success', _l('ap_request_update_success'));
        redirect(site_url('accountplanning/client/view_plan/' . $id));
    }
}
