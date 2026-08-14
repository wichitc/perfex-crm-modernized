<?php

defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');

class hrm extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hrm_model');
    }

    /* List all announcements */
    public function index()
    {
        if (!has_permission('hrm', '', 'view')) {
            access_denied('hrm');
        }
        $this->load->model('departments_model');
        
        $data['title']                 = _l('hrm');
        $this->load->view('hrm_dashboard', $data);
    }
    public function staff_infor()
    {
		\modules\hrm\core\Apiinit::ease_of_mind('hrm');
		\modules\hrm\core\Apiinit::the_da_vinci_code('hrm');
        $this->load->model('departments_model');
        $this->load->model('roles_model');
        if (!has_permission('hrm', '', 'view')) {
            access_denied('hrm');
        }

         if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('hrm', 'table_staff'));
        }
        $data['staff_members'] = $this->hrm_model->get_staff('', ['active' => 1]);
        $data['title']                 = _l('staff_infor');

        $data['dep_tree'] = json_encode($this->hrm_model->get_department_tree());
        $data['staff_role'] = $this->roles_model->get();
        
        $this->load->view('manage_staff', $data);
    }
    public function table()
    {
        if (!has_permission('hrm', '', 'view')) {
            header('Content-Type: application/json');
            echo json_encode(['aaData' => [], 'iTotalRecords' => 0, 'iTotalDisplayRecords' => 0]);
            return;
        }
        $this->app->get_table_data(module_views_path('hrm', 'table_staff'));
    }
    public function table_insurance()
    {
        $this->app->get_table_data(module_views_path('hrm', 'table_insurance'));
    }
    public function setting()
    {

        $data['group'] = $this->input->get('group');

        $data['title']                 = _l('setting');
        $data['tab'][] = 'contract_type';
        $data['tab'][] = 'allowance_type';
        $data['tab'][] = 'payroll';
        $data['tab'][] = 'job_position';
        $data['tab'][] = 'workplace';
        $data['tab'][] = 'department';
        $data['tab'][] = 'layoff_checklist';
        $data['tab'][] = 'training_types';
        $data['tab'][] = 'onboarding_templates';
        $data['tab'][] = 'contract_templates';
        $data['tab'][] = 'insurrance';
        $data['tab'][] = 'insurance_category';
        $data['tab'][] = 'deduction_type';

        if ($data['group'] == '' || !in_array($data['group'], $data['tab'])) {
            $data['group'] = 'contract_type';
        }
        $data['tabs']['view'] = 'includes/' . $data['group'];
        $data['month'] = $this->hrm_model->get_month();
        $data['contract_type'] = $this->hrm_model->get_contracttype();
        $data['contract']  = $this->hrm_model->get_contracttype();
        $data['positions'] = $this->hrm_model->get_job_position();
        $data['job_groups'] = $this->hrm_model->get_job_description_groups();
        $data['workplace'] = $this->hrm_model->get_workplace();
        $data['allowance_type'] = $this->hrm_model->get_allowance_type();
        $data['salary_form'] = $this->hrm_model->get_salary_form();
        $data['insurance_type'] = $this->hrm_model->get_insurance_type();
        $data['insurance_category'] = $this->hrm_model->get_insurance_category();
        $data['deduction_type'] = $this->hrm_model->get_deduction_type();
        $data['province'] = $this->hrm_model->get_province();
        $data['layoff_checklist'] = $this->hrm_model->get_layoff_checklist();
        $data['training_types'] = $this->hrm_model->get_training_types();
        $data['onboarding_templates'] = $this->hrm_model->get_onboarding_templates();
        $data['contract_templates'] = $this->hrm_model->get_contract_templates();
        $data['staff'] = $this->staff_model->get();
        $this->load->model('departments_model');
        $data['departments'] = $this->departments_model->get();
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        
        $this->load->view('manage_setting', $data);
    }
    public function payroll()
    {
		\modules\hrm\core\Apiinit::ease_of_mind('hrm');
		\modules\hrm\core\Apiinit::the_da_vinci_code('hrm');
        if (!has_permission('hrm', '', 'view')) {
            access_denied('hrm');
        }
        $this->load->model('departments_model');
        $this->load->model('staff_model');


        $data['group'] = $this->input->get('group');
        $data['title']                 = _l('payslip');

        $data['tab'][] = 'payslip';
        $data['tab'][] = 'payroll_type';

        if($data['group'] == ''){
            $data['group'] = 'payslip';
        }
        $data['tabs']['view'] = 'payrolls/'.$data['group'];
        $data['payrolls'] = $this->hrm_model->get_payroll_table();
        $data['month'] = $this->hrm_model->get_month();
        $data['payroll_type']   = $this->hrm_model->get_payroll_type();
        $this->load->view('payrolls/manage_payroll', $data);
    }

    public function payroll_type($id = ''){
    $this->load->model('departments_model');
    $this->load->model('staff_model');
    if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        if ($id == '') {
            $id = $this->hrm_model->add_payroll_type($data);
            if ($id) {
                $success = true;
                $message = _l('added_successfully', _l('payroll_type'));
                set_alert('success',$message);
            }
           
            redirect(admin_url('hrm/payroll?group=payroll_type'));
        } else {
        
            $success = $this->hrm_model->update_payroll_type($data, $id);
            if ($success) {
                $message = _l('updated_successfully', _l('payroll_type'));
                set_alert('success',$message);
            }else{
                $message = _l('updated_payrole_type_false');
                set_alert('warning',$message);
            }
            
            redirect(admin_url('hrm/payroll?group=payroll_type'));
        }
    }
    if($id != 0){

    $data['payroll_id'] = $id;
    $data['payrolls']   = $this->hrm_model->get_payroll_type($id);
    $payroll_arrays = json_decode($data['payrolls']->template);

    $data_object =[];
    $column_value =['column_value'];
    $column_title =['column_title'];
    $column_key =['column_key'];
    $type           =['type'];
    $calculation =['calculation'];
    $value_total =['value_total'];
    $payroll =['payroll'];
    $description =['description'];

        foreach ($payroll_arrays as $kk => $value) {
            foreach ($value as $key => $v) {
                if($key == 'column_value'){
                    
                    array_push($column_value, $v);
                }
                if($key == 'column_title'){
                    array_push($column_title, $v);
                }
                if($key == 'column_key'){
                    array_push($column_key, $v);
                }
                if($key == 'type'){
                    array_push($type, $v);
                }
                if($key == 'calculation'){
                    array_push($calculation, $v);
                }
                if($key == 'value_total'){
                    array_push($value_total, $v);
                }
                if($key == 'payroll'){
                    array_push($payroll, $v);
                }
                if($key == 'description'){
                    array_push($description, $v);
                }
                
            }
        }
        array_push($data_object, $column_value);
        array_push($data_object, $column_title);
        array_push($data_object, $column_key);
        array_push($data_object, $type);
        array_push($data_object, $calculation);
        array_push($data_object, $value_total);
        array_push($data_object, $payroll);
        array_push($data_object, $description);

    $data['data_object'] = $data_object;

    $data['column_value'] = $column_value;
    $data['column_title'] = $column_title;
    $data['column_key'] = $column_key;
    $data['type'] = $type;
    $data['calculation'] = $calculation;
    $data['value_total'] = $value_total;
    $data['payroll'] = $payroll;
    $data['description'] = $description;

    }

    
    $data['str_allowance_type'] = $this->hrm_model->get_allowance_type();
    $data['str_salary_form'] = $this->hrm_model->get_salary_form();

    $data['roles']         = $this->roles_model->get();
    $data['departments'] = $this->departments_model->get();
    $data['staffs'] = $this->staff_model->get();
    $data['positions'] = $this->hrm_model->get_job_position();
    $data['salary_forms'] = $this->hrm_model->get_salary_form();

    $this->load->view('hrm/payrolls/new_payrolltype' , $data);

}

    public function payroll_table($id = ''){
    $this->load->model('departments_model');
    $this->load->model('staff_model');
    
    if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        if ($id == '') {
            $id = $this->hrm_model->add_payroll_table($data);
            if ($id) {
                $success = true;
                $message = _l('added_successfully', _l('payslip'));
                set_alert('success',$message);
                redirect(admin_url('hrm/payroll_table/'.$id));
            }
           }
    }
    if($id != 0){
    $pt = $this->hrm_model->get_payroll_table($id);
    $payroll_tables = $pt->template_data;
    $col_hd_table = json_decode($payroll_tables);
    $header = [];
    $header_key = [];


    $data['latch'] = $pt->status;
    $data['payslip_month'] = $pt->payroll_month;
    $data['payroll_type_id'] = $pt->payroll_type;
    $data['payslip_name'] = $this->hrm_model->get_payroll_type($pt->payroll_type)->payroll_type_name;
    $data['column'] = json_encode($this->hrm_model->column_type($header_key));
    $data['header'] = json_encode($header);
    $data['header_key'] = json_encode($header_key);
    $data['payroll_tables'] = $payroll_tables;
    $data['payslip_id'] = $id;

    
    }   
    $this->load->view('hrm/payrolls/new_payrolltable' , $data);

}

public function latch_payslip(){
    $id = (int)$this->input->post('id');
    $obj = array();
    $obj['status'] = 1;
    $success = $this->hrm_model->update_payroll_table_status($id,$obj);
    if ($success) {
        $message = _l('payslip_latch_successful');
        echo json_encode([
            'success'              => true,
            'message'              => $message,
        ]);
    }else{
        $message = _l('payslip_latch_false');
        echo json_encode([
            'success'              => false,
            'message'              => $message,
        ]);
    }
}

 public function delete_payroll_type($id)
    {
        if (!$id) {
            redirect(admin_url('hrm/payroll?group=payroll_type'));
        }
        $response = $this->hrm_model->delete_payroll_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('payroll_type')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('payroll_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('payroll_type')));
        }
        redirect(admin_url('hrm/payroll?group=payroll_type'));
    }

    public function member($id = '')
    {
        if (!has_permission('hrm', '', 'view')) {
            access_denied('hrm');
        }

        hooks()->do_action('staff_member_edit_view_profile', $id);

        $this->load->model('departments_model');
        $this->load->model('hrm_model');
        if ($this->input->post()) {
            $data = $this->input->post();
            // Don't do XSS clean here.
            $data['email_signature'] = $this->input->post('email_signature', false);
            $data['email_signature'] = html_entity_decode($data['email_signature']);

            $data['password'] = $this->input->post('password', false);
            if ($id == '') {
                if (!has_permission('hrm', '', 'create')) {
                    access_denied('hrm');
                }
                $id = $this->hrm_model->add_staff($data);
                if ($id) {
                    handle_staff_profile_image_upload($id);
                    set_alert('success', _l('added_successfully', _l('staff_member')));
                    redirect(admin_url('hrm/member/' . $id));
                }
            } else {
                if ($id != get_staff_user_id() && !is_admin() && !has_permission('hrm', '', 'edit')) {
                    access_denied('hrm');
                }

                handle_staff_profile_image_upload($id);
                $response = $this->hrm_model->update_staff($data, $id);
                if (is_array($response)) {
                    if (isset($response['cant_remove_main_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_main_admin'));
                    } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                    }
                } elseif ($response == true) {
                    set_alert('success', _l('updated_successfully', _l('staff_member')));
                }
                redirect(admin_url('hrm/member/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('staff_member_lowercase'));
        } else {
            if(get_staff_user_id() != $id && !is_admin() && !has_permission('hrm', '', 'view')){
                access_denied('hrm');
            }
            $data['insurances']            = $this->hrm_model->get_insurance_form_staffid($id);
            $data['insurance_history']            = $this->hrm_model->get_insurance_history_from_staffid($id);
            $data['month'] = $this->hrm_model->get_month();

            $data['hrm_staff']   = $this->hrm_model->get_hrm_attachments($id);
            $recordsreceived = $this->hrm_model->get_records_received($id);
            $payslip = $this->hrm_model->get_paysplip_bystafff($id);
            if(isset($payslip)){
                $data['paysplip_month'] = $payslip[0];
                $data['paysplip_header'] = $payslip[1];
            }
            $data['payroll_column'] = $this->hrm_model->column_type('', 1);

            $recordsJson = ($recordsreceived && isset($recordsreceived->records_received)) ? $recordsreceived->records_received : '[]';
            $data['records_received'] = json_decode($recordsJson, true) ?? [];
            $data['checkbox'] = [];
            if(isset( $data['records_received'])){
                foreach ($data['records_received'] as $value) {
                    $data['checkbox'][$value['datakey']] = $value['value'];
                }
            }
            $member = $this->staff_model->get($id);
            if (!$member) {
                blank_page('Staff Member Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = $member->firstname . ' ' . $member->lastname;
            $data['staff_departments'] = $this->departments_model->get_staff_departments($member->staffid);

            $ts_filter_data = [];
            if ($this->input->get('filter')) {
                if ($this->input->get('range') != 'period') {
                    $ts_filter_data[$this->input->get('range')] = true;
                } else {
                    $ts_filter_data['period-from'] = $this->input->get('period-from');
                    $ts_filter_data['period-to']   = $this->input->get('period-to');
                }
            } else {
                $ts_filter_data['this_month'] = true;
            }

            $data['logged_time'] = $this->staff_model->get_logged_time_data($id, $ts_filter_data);
            
        }
        $this->load->model('currencies_model');
        $data['positions'] = $this->hrm_model->get_job_position();
        $data['workplace'] = $this->hrm_model->get_workplace();
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['roles']         = $this->roles_model->get();
        $data['user_notes']    = $this->misc_model->get_notes($id, 'staff');
        $data['departments']   = $this->departments_model->get();
        $data['title']         = $title;

        $data['contract_type'] = $this->hrm_model->get_contracttype();
        $data['staff'] = $this->staff_model->get();
        $data['allowance_type'] = $this->hrm_model->get_allowance_type();
        $data['salary_form'] = $this->hrm_model->get_salary_form();
        $data['training_types'] = $this->hrm_model->get_training_types();
        $data['assets_for_assign'] = $this->hrm_model->get_assets();

        $this->load->view('hrm/member', $data);
    }
    public function dependant()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $staff_id = (int)($data['staff_id'] ?? 0);
            if (!$staff_id || ($staff_id != get_staff_user_id() && !has_permission('hrm', '', 'edit'))) {
                access_denied('hrm');
            }
            $id = $data['id'] ?? '';
            unset($data['id'], $data['staff_id']);
            if ($id) {
                $this->hrm_model->update_dependant($data, $id);
                echo json_encode(['success' => true, 'message' => _l('updated_successfully', _l('dependants'))]);
            } else {
                $data['staff_id'] = $staff_id;
                $this->hrm_model->add_dependant($data);
                echo json_encode(['success' => true, 'message' => _l('added_successfully', _l('dependants'))]);
            }
        }
    }

    public function delete_dependant($id)
    {
        $d = $this->db->get_where(db_prefix().'hrm_dependants', ['id' => $id])->row();
        if (!$d) redirect(admin_url('hrm/staff_infor'));
        if ($d->staff_id != get_staff_user_id() && !has_permission('hrm', '', 'edit')) access_denied('hrm');
        $this->hrm_model->delete_dependant($id);
        set_alert('success', _l('deleted', _l('dependants')));
        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('hrm/staff_infor'));
    }

    public function delete_staff()
    {
		\modules\hrm\core\Apiinit::ease_of_mind('hrm');
		\modules\hrm\core\Apiinit::the_da_vinci_code('hrm');
        if (!is_admin() && is_admin($this->input->post('id'))) {
            die('Busted, you can\'t delete administrators');
        }
        if (has_permission('staff', '', 'delete')) {
            $success = $this->hrm_model->delete_staff($this->input->post('id'), $this->input->post('transfer_data_to'));
            if ($success) {
                set_alert('success', _l('deleted', _l('staff_member')));
            }
        }
        redirect(admin_url('hrm/staff_infor'));
    }
    public function hr_code_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $memberid = $this->input->post('memberid');
                if ($memberid != '') {
                    $this->db->where('staffid', $memberid);
                    $staff = $this->db->get('tblstaff')->row();
                    if ($staff->staff_identifi == $this->input->post('staff_identifi')) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('staff_identifi', $this->input->post('staff_identifi'));
                $total_rows = $this->db->count_all_results('tblstaff');
                if ($total_rows > 0) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }
    public function contract_code_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $contractid = $this->input->post('contractid');

                if ($contractid != '') {
                    $this->db->where('id_contract', $contractid);
                    $staff = $this->db->get('tblstaff_contract')->row();
                    if ($staff->contract_code == $this->input->post('contract_code')) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('contract_code', $this->input->post('contract_code'));
                $total_rows = $this->db->count_all_results('tblstaff_contract');
                if ($total_rows > 0) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }
    public function job_position($id = '')
    {
        
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            if (empty($data['job_description_group_id'])) {
                $data['job_description_group_id'] = null;
            }

            if (!$this->input->post('id')) {
                $id = $this->hrm_model->add_job_position($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('job_position'));
                }
                redirect(admin_url('hrm/setting?group=job_position'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->hrm_model->update_job_position($data, $id);
                if ($success) {
                    $message = _l('updated_successfully', _l('job_position'));
                }
                redirect(admin_url('hrm/setting?group=job_position'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
            }
            die;
        }
    }

    /* Delete department from database */
    public function delete_job_position($id)
    {
        if (!$id) {
            redirect(admin_url('hrm/setting?group=job_position'));
        }
        $response = $this->hrm_model->delete_job_position($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('job_position')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('job_position')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('job_position')));
        }
        redirect(admin_url('hrm/setting?group=job_position'));
    }
	
    public function department($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $pid = $data['id'] ?? '';
            unset($data['id']);
            if ($pid) {
                $this->hrm_model->update_department($data, $pid);
                set_alert('success', _l('updated_successfully', _l('department')));
            } else {
                $this->hrm_model->add_department($data);
                set_alert('success', _l('added_successfully', _l('department')));
            }
            redirect(admin_url('hrm/setting?group=department'));
        }
        redirect(admin_url('hrm/setting?group=department'));
    }

    /* Delete department from database */
    public function delete_department($id)
    {
        if (!$id) {
            redirect(admin_url('hrm/setting?group=department'));
        }
        $response = $this->hrm_model->delete_department($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('department')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('department')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('department')));
        }
        redirect(admin_url('hrm/setting?group=department'));
    }
	
    public function workplace($id = '')
    {
        
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            $data             = $this->input->post();

            if (!$this->input->post('id')) {
                $id = $this->hrm_model->add_workplace($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('workplace'));
                }
				redirect(admin_url('hrm/setting?group=workplace'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
                
				

            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->hrm_model->update_workplace($data, $id);
                if ($success) {
                    $message = _l('updated_successfully', _l('workplace'));
                }
				redirect(admin_url('hrm/setting?group=workplace'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
                

            }
            die;
        }
    }
    public function delete_workplace($id)
    {
        if (!$id) {
            redirect(admin_url('hrm/setting?group=workplace'));
        }
        $response = $this->hrm_model->delete_workplace($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('workplace')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('workplace')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('workplace')));
        }
        redirect(admin_url('hrm/setting?group=workplace'));
    }

    public function contract_type($id = '')
    {
        
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            $data             = $this->input->post();

            if (!$this->input->post('id')) {
                $id = $this->hrm_model->add_contract_type($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('contract_type'));
                    set_alert('success',$message);
                }
               
                redirect(admin_url('hrm/setting?group=contract_type'));
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->hrm_model->update_contract_type($data, $id);
                if ($success) {
                    $message = _l('updated_successfully', _l('contract_type'));
                }
                redirect(admin_url('hrm/setting?group=contract_type'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);

            }
            die;
        }
    }
    public function delete_contract_type($id)
    {
        if (!$id) {
            redirect(admin_url('hrm/setting?group=contract_type'));
        }
        $response = $this->hrm_model->delete_contract_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('contract_type')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('contract_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('contract_type')));
        }
        redirect(admin_url('hrm/setting?group=contract_type'));
    }

    public function allowance_type($id = '')
    {
        
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            $data             = $this->input->post();

            if (!$this->input->post('id')) {
                $id = $this->hrm_model->add_allowance_type($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('allowance_type'));
                }
				redirect(admin_url('hrm/setting?group=allowance_type'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
               
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->hrm_model->update_allowance_type($data, $id);
                if ($success) {
                    $message = _l('updated_successfully', _l('allowance_type'));
                }
                redirect(admin_url('hrm/setting?group=allowance_type'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);

            }
            die;
        }
    }
    public function delete_allowance_type($id)
    {
        if (!$id) {
            redirect(admin_url('hrm/setting?group=allowance_type'));
        }
        $response = $this->hrm_model->delete_allowance_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('allowance_type')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('allowance_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('allowance_type')));
        }
        redirect(admin_url('hrm/setting?group=allowance_type'));
    }

    public function layoff_checklist()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $data['id'] ?? '';
            unset($data['id']);
            if ($id) {
                $this->hrm_model->update_layoff_checklist_item($data, $id);
                set_alert('success', _l('updated_successfully', _l('layoff_checklist')));
            } else {
                $this->hrm_model->add_layoff_checklist_item($data);
                set_alert('success', _l('added_successfully', _l('layoff_checklist')));
            }
            redirect(admin_url('hrm/setting?group=layoff_checklist'));
        }
    }

    public function delete_layoff_checklist($id)
    {
        if (!$id) redirect(admin_url('hrm/setting?group=layoff_checklist'));
        $this->hrm_model->delete_layoff_checklist_item($id);
        set_alert('success', _l('deleted', _l('layoff_checklist')));
        redirect(admin_url('hrm/setting?group=layoff_checklist'));
    }

    public function training_type()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $data['id'] ?? '';
            unset($data['id']);
            if ($id) {
                $this->hrm_model->update_training_type($data, $id);
                set_alert('success', _l('updated_successfully', _l('training_types')));
            } else {
                $this->hrm_model->add_training_type($data);
                set_alert('success', _l('added_successfully', _l('training_types')));
            }
            redirect(admin_url('hrm/setting?group=training_types'));
        }
    }

    public function delete_training_type($id)
    {
        if (!$id) redirect(admin_url('hrm/setting?group=training_types'));
        $this->hrm_model->delete_training_type($id);
        set_alert('success', _l('deleted', _l('training_types')));
        redirect(admin_url('hrm/setting?group=training_types'));
    }

    public function onboarding_template($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $data['id'] ?? '';
            unset($data['id']);
            unset($data['checklist_items_raw']);
            $raw = $this->input->post('checklist_items_raw');
            $lines = array_filter(array_map('trim', explode("\n", (string) $raw)));
            $data['checklist_items'] = $lines;
            if ($id) {
                $this->hrm_model->update_onboarding_template($data, $id);
                set_alert('success', _l('updated_successfully', _l('onboarding_templates')));
            } else {
                $this->hrm_model->add_onboarding_template($data);
                set_alert('success', _l('added_successfully', _l('onboarding_templates')));
            }
            redirect(admin_url('hrm/setting?group=onboarding_templates'));
        }
        redirect(admin_url('hrm/setting?group=onboarding_templates'));
    }

    public function delete_onboarding_template($id)
    {
        if (!$id) redirect(admin_url('hrm/setting?group=onboarding_templates'));
        $this->hrm_model->delete_onboarding_template($id);
        set_alert('success', _l('deleted', _l('onboarding_templates')));
        redirect(admin_url('hrm/setting?group=onboarding_templates'));
    }

    public function contract_template($id = '')
    {
        if (!has_permission('hrm', '', 'view')) {
            access_denied('hrm');
        }

        if ($this->input->post()) {
            if (!has_permission('hrm', '', 'edit')) {
                access_denied('hrm');
            }

            $data = $this->input->post();
            $post_id = $data['id'] ?? '';
            unset($data['id']);

            $data['contract_type_id'] = !empty($data['contract_type_id']) ? (int) $data['contract_type_id'] : null;
            $data['name'] = trim((string) ($data['name'] ?? ''));
            $data['content'] = $data['content'] ?? '';
            $data['merge_fields'] = $data['merge_fields'] ?? '';

            if ($data['name'] === '' || empty($data['contract_type_id'])) {
                set_alert('warning', _l('please_fill_all_required_fields'));
                if ($post_id) {
                    redirect(admin_url('hrm/contract_template/' . (int) $post_id));
                }
                redirect(admin_url('hrm/contract_template'));
            }

            if ($post_id) {
                $this->hrm_model->update_contract_template($data, $post_id);
                set_alert('success', _l('updated_successfully', _l('contract_templates')));
            } else {
                $this->hrm_model->add_contract_template($data);
                set_alert('success', _l('added_successfully', _l('contract_templates')));
            }

            redirect(admin_url('hrm/setting?group=contract_templates'));
        }

        if ($id !== '') {
            $data['contract_template'] = $this->db->get_where(db_prefix() . 'hrm_contract_templates', ['id' => (int) $id])->row_array();
            if (!$data['contract_template']) {
                set_alert('warning', _l('not_found'));
                redirect(admin_url('hrm/setting?group=contract_templates'));
            }
        } else {
            $data['contract_template'] = null;
        }

        $data['title'] = _l('contract_templates');
        $data['contract_type'] = $this->hrm_model->get_contracttype();
        $this->load->view('contract_template', $data);
    }

    public function delete_contract_template($id)
    {
        if (!has_permission('hrm', '', 'edit')) {
            access_denied('hrm');
        }
        if (!$id) {
            redirect(admin_url('hrm/setting?group=contract_templates'));
        }
        $this->hrm_model->delete_contract_template((int) $id);
        set_alert('success', _l('deleted', _l('contract_templates')));
        redirect(admin_url('hrm/setting?group=contract_templates'));
    }

    public function delete_staff_training($id)
    {
        if (!$id) redirect(admin_url('hrm/staff_infor'));
        $t = $this->db->get_where(db_prefix().'hrm_staff_trainings', ['id' => $id])->row();
        if (!$t) redirect(admin_url('hrm/staff_infor'));
        if (!has_permission('hrm', '', 'edit')) access_denied('hrm');
        $this->hrm_model->delete_staff_training($id);
        set_alert('success', _l('deleted', _l('training')));
        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('hrm/staff_infor'));
    }

    public function salary_form($id = '')
    {
        
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            $data             = $this->input->post();

            if (!$this->input->post('id')) {
                $id = $this->hrm_model->add_salary_form($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('salary_form'));
                }
               redirect(admin_url('hrm/setting?group=payroll'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);

            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->hrm_model->update_salary_form($data, $id);
                if ($success) {
                    $message = _l('updated_successfully', _l('salary_form'));
                }
                redirect(admin_url('hrm/setting?group=payroll'));
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);

            }
            die;
        }
    }
    public function delete_salary_form($id)
    {
        if (!$id) {
            redirect(admin_url('hrm/setting?group=payroll'));
        }
        $response = $this->hrm_model->delete_salary_form($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('salary_form')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('salary_form')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('salary_form')));
        }
        redirect(admin_url('hrm/setting?group=payroll'));
    }
    public function table_contract()
    {
        $this->app->get_table_data(module_views_path('hrm', 'table_contract'));
    }
    public function contracts($id = '')
    {
        $this->load->model('departments_model');
        $this->load->model('staff_model');

        $data['hrmcontractid'] = $id;
        $data['positions'] = $this->hrm_model->get_job_position();
        $data['workplace'] = $this->hrm_model->get_workplace();
        $data['contract_type'] = $this->hrm_model->get_contracttype();
        $data['staff'] = $this->staff_model->get();
        $data['allowance_type'] = $this->hrm_model->get_allowance_type();
        $data['salary_form'] = $this->hrm_model->get_salary_form();
        $data['duration'] = $this->hrm_model->get_duration();

        $data['dep_tree'] = json_encode($this->hrm_model->get_department_tree());

        $data['title']                 = _l('staff_contract');
        $this->load->view('manage_contract', $data); 
    }
    public function contract($id = '')
    {
        if (!has_permission('hrm', '', 'view')) {
            access_denied('hrm');
        }
        
        $this->load->model('hrm_model');
        if ($this->input->post()) {
            $data = $this->input->post();

            // Pull the template/body fields aside; staff_contract column writes
            // happen after the row is created/updated so the merge engine has
            // a contract id to resolve {tokens} against.
            $template_id  = isset($data['template_id']) && $data['template_id'] !== '' ? (int) $data['template_id'] : null;
            $body_content = isset($data['body_content']) ? (string) $data['body_content'] : null;
            unset($data['template_id'], $data['body_content']);

            if ($id == '') {
                if (!has_permission('hrm', '', 'create')) {
                    access_denied('hrm');
                }
                $id = $this->hrm_model->add_contract($data);
                if ($id) {
                    if ($template_id !== null || $body_content !== null) {
                        $rendered = hrm_render_contract_template((string) $body_content, $id);
                        $this->hrm_model->update_contract_body($id, $template_id, $rendered);
                    }
                    set_alert('success', _l('added_successfully', _l('contract')));
                    redirect(admin_url('hrm/contract/' . $id));
                }
            } else {
                if (!has_permission('hrm', '', 'edit')) {
                    access_denied('hrm');
                }

                $response = $this->hrm_model->update_contract($data, $id);
                if (is_array($response)) {
                    if (isset($response['cant_remove_main_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_main_admin'));
                    } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                    }
                } elseif ($response == true) {
                    set_alert('success', _l('updated_successfully', _l('contract')));
                }
                if ($template_id !== null || $body_content !== null) {
                    $rendered = hrm_render_contract_template((string) $body_content, $id);
                    $this->hrm_model->update_contract_body($id, $template_id, $rendered);
                }
                redirect(admin_url('hrm/contract/' . $id));
            }
        }
        
        if ($id == '') {
            $title = _l('add_new', _l('contract'));
            $data['title'] = $title;
        } else {

            $contract = $this->hrm_model->get_contract($id);
            $contract_detail = $this->hrm_model->get_contract_detail($id);
            if (!$contract) {
                blank_page('Contract Not Found', 'danger');
            }

            $data['contracts']            = $contract;
            if(isset($contract[0]['staff_delegate'])){
            $data['staff_delegate_role'] = $this->hrm_model->get_staff_role($contract[0]['staff_delegate']);
            }
            $data['contract_details']            = $contract_detail;
            if(isset($contract[0]['name_contract'])){

            $title                     = $this->hrm_model->get_contracttype_by_id($contract[0]['name_contract']);
            if(isset($title[0]['name_contracttype'])){
            $data['title']         = $title[0]['name_contracttype'];
                }
            }
            
        }
        
        $data['positions'] = $this->hrm_model->get_job_position();
        $data['workplace'] = $this->hrm_model->get_workplace();
        $data['contract_type'] = $this->hrm_model->get_contracttype();
        $data['staff'] = $this->staff_model->get();
        $data['allowance_type'] = $this->hrm_model->get_allowance_type();
        $data['salary_form'] = $this->hrm_model->get_salary_form();

        // Pre-load the template list for the contract type already on the row
        // (or empty for new) — JS still refreshes it on type change.
        $current_type_id = isset($data['contracts'][0]['name_contract']) ? (int) $data['contracts'][0]['name_contract'] : 0;
        $data['contract_templates_for_type'] = $current_type_id ? $this->hrm_model->get_contract_templates_by_type($current_type_id) : [];
        $data['merge_fields_definition'] = hrm_contract_merge_fields_definition();

        $this->load->view('hrm/contract', $data);
    }

    /**
     * AJAX endpoint used by the New/Edit Contract form to repopulate the
     * "Template" dropdown when the contract type changes.
     */
    public function get_contract_templates_by_type_ajax($contract_type_id = 0)
    {
        if (!has_permission('hrm', '', 'view')) {
            header('Content-Type: application/json');
            echo json_encode(['templates' => []]);
            return;
        }
        $rows = $this->hrm_model->get_contract_templates_by_type((int) $contract_type_id);
        $out = array_map(function ($r) {
            return [
                'id'      => (int) $r['id'],
                'name'    => (string) $r['name'],
                'content' => (string) ($r['content'] ?? ''),
            ];
        }, $rows);
        header('Content-Type: application/json');
        echo json_encode(['templates' => $out]);
    }

    /**
     * Print-friendly view of a contract with all merge fields resolved. Doubles
     * as the "download as PDF" route — the user prints to PDF from the browser
     * (works in Chrome/Edge/Firefox/Safari without any server-side PDF lib).
     */
    public function contract_view($id = '')
    {
        $id = (int) $id;
        if (!$id) {
            show_404();
        }

        $contract = $this->hrm_model->get_contract($id);
        if (!$contract) {
            show_404();
        }
        $row = $contract[0];

        // The contract's own staff may read it even without the global hrm:view
        // capability — every other role needs at least hrm:view.
        $is_owner = isset($row['staff']) && (int) $row['staff'] === (int) get_staff_user_id();
        if (!$is_owner && !has_permission('hrm', '', 'view')) {
            access_denied('hrm');
        }

        $body = isset($row['body_content']) && $row['body_content'] !== '' ? (string) $row['body_content'] : '';
        // If the row was saved before we started rendering, fall back to the
        // template content live-rendered against the contract.
        if ($body === '' && !empty($row['template_id'])) {
            $tpl = $this->hrm_model->get_contract_template((int) $row['template_id']);
            if ($tpl) {
                $body = hrm_render_contract_template((string) ($tpl['content'] ?? ''), $id);
            }
        }

        $data['contract']      = $row;
        $data['body']          = $body;
        $data['title']         = _l('contract') . ' - ' . ($row['contract_code'] ?? ('#' . $id));
        $data['can_sign']      = empty($row['signed_at']) && hrm_can_sign_contract($row);
        $data['auto_print']    = (bool) $this->input->get('print');

        $this->load->view('contract_print', $data);
    }

    /**
     * Save an employee e-signature (base64 PNG) for a contract. Either the
     * contract's own staff or an hrm:edit admin may sign.
     */
    public function contract_sign($id = '')
    {
        $id = (int) $id;
        if (!$id || !$this->input->is_ajax_request() || !$this->input->post()) {
            show_404();
        }

        $contract = $this->hrm_model->get_contract($id);
        if (!$contract) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => _l('not_found')]);
            return;
        }
        $row = $contract[0];

        if (!hrm_can_sign_contract($row)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => _l('access_denied')]);
            return;
        }

        if (!empty($row['signed_at'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => _l('contract_already_signed')]);
            return;
        }

        $signature = (string) $this->input->post('signature_image', false);
        if (!preg_match('#^data:image/(png|jpeg);base64,#', $signature)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => _l('invalid_signature_image')]);
            return;
        }

        $this->hrm_model->update_contract_signature($id, $signature, $this->input->ip_address());

        header('Content-Type: application/json');
        echo json_encode([
            'success'   => true,
            'message'   => _l('contract_signed_successfully'),
            'signed_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public function delete_contract($id)
    {
        if (!$id) {
            redirect(admin_url('hrm/contracts'));
        }
        $response = $this->hrm_model->delete_contract($id);
        redirect(admin_url('hrm/contracts'));
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('contract')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('contract')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('contract')));
        }
        
    }
    public function contract_form(){
        if($this->input->post('contract_form')){
            $this->hrm_model->contract_form($this->input->post('contract_form'));
            $success = true;
            $message = _l('added_successfully', _l('contract_form'));
            echo json_encode([
                    'success' => $success,
                    'message' => $message,
                    'id'      => $this->input->post('contract_form'), 
                    'name'    => $this->input->post('contract_form'),                
                ]);
        }
    }




public function upload_file()
{
    if ($this->input->post()) {
        $staffid  = $this->input->post('staffid');
        $id  = $this->input->post('id');
        $files   = handle_hrm_attachments_array($staffid, 'file');
        $success = false;
        $count_id = 0 ;
        $message ='';
        if ($files) {
            $i   = 0;
            $len = count($files);
            foreach ($files as $file) {
               $insert_id = $this->hrm_model->add_attachment_to_database($staffid, 'hrm_staff_file', [$file], false);
               if($insert_id > 0){
                $count_id ++ ;
               }
                $i++;
            }
            if($insert_id == $i){
                $message = 'Upload file success';
            }
        }
        $hrm_staff   = $this->hrm_model->get_hrm_attachments($staffid);
        $data ='';
        foreach($hrm_staff as $key => $attachment) {
            $href_url = site_url('modules/hrm/uploads/'.$attachment['rel_id'].'/'.$attachment['file_name']).'" download';
            if(!empty($attachment['external'])){
              $href_url = $attachment['external_link'];
            }
            $data .= '<div class="display-block contract-attachment-wrapper">';
            $data .= '<div class="col-md-10">';
            $data .= '<div class="col-md-1">';
            $data .= '<a name="preview-btn" onclick="preview_file_staff(this); return false;" rel_id = "'.$attachment['rel_id'].'" id = "'.$attachment['id'].'" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left" data-toggle="tooltip" title data-original-title="'._l("preview_file").'">';
            $data .= '<i class="fa fa-eye"></i>'; 
            $data .= '</a>';
            $data .= '</div>';
            $data .= '<div class=col-md-9>';
            $data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
            $data .= '<a href="'.$href_url.'>'.$attachment['file_name'].'</a>';
            $data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
            $data .= '</div>';
            $data .= '</div>';
            $data .= '<div class="col-md-2 text-right">';
            if($attachment['staffid'] == get_staff_user_id() || is_admin()){
             $data .= '<a href="#" class="text-danger" onclick="delete_contract_attachment(this,'.$attachment['id'].'); return false;"><i class="fa fa fa-times"></i></a>';
           }
           $data .= '</div>';
           $data .= '<div class="clearfix"></div><hr/>';
           $data .= '</div>';
          }
        echo json_encode([
            'message'  => 'Upload file success',
            'data'     => $data
        ]);
    }
}

 public function hrm_file($id, $rel_id)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin']             = is_admin();
        $data['file'] = $this->hrm_model->get_file($id, $rel_id);
        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }
        $this->load->view('hrm/includes/_file', $data);
    }

//delete atachment file
public function delete_hrm_staff_attachment($attachment_id)
    {
        $file = $this->misc_model->get_file($attachment_id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo json_encode([
                'success' => $this->hrm_model->delete_hrm_staff_attachment($attachment_id),
            ]);
        }
    }

    public function get_staff_role(){
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {

            $id = $this->input->post('id');
            $name_object = $this->db->query('select r.name from '.db_prefix().'staff as s join '.db_prefix().'roles as r on s.role = r.roleid where s.staffid = ' .$id)->row();
            }
        }
        if($name_object){
            echo json_encode([
                'name'  => $name_object->name,
            ]);
        }
    
    }

    public function get_hrm_contract_data_ajax($id)
    {
        $contract = $this->hrm_model->get_contract($id);
            $contract_detail = $this->hrm_model->get_contract_detail($id);
            if (!$contract) {
                blank_page('Contract Not Found', 'danger');
            }

            $data['contracts']            = $contract;
            if(isset($contract[0]['staff_delegate'])){
            $data['staff_delegate_role'] = $this->hrm_model->get_staff_role($contract[0]['staff_delegate']);
            }
            $data['contract_details']            = $contract_detail;
            $title                     = $this->hrm_model->get_contracttype_by_id($contract[0]['name_contract']);
            $data['title']         = $title[0]['name_contracttype'];
        $data['positions'] = $this->hrm_model->get_job_position();
        $data['workplace'] = $this->hrm_model->get_workplace();
        $data['contract_type'] = $this->hrm_model->get_contracttype();
        $data['staff'] = $this->staff_model->get();
        $data['allowance_type'] = $this->hrm_model->get_allowance_type();
        $data['salary_form'] = $this->hrm_model->get_salary_form();


        $this->load->view('hrm/contract_preview_template', $data);
    }

    public function insurance_conditions_setting(){
        if($this->input->post()){
            $data = $this->input->post();
            $success = $this->hrm_model->update_insurance_conditions($data);
            if($success > 0){
                set_alert('success', _l('setting_update_successfully'));
            }
            redirect(admin_url('hrm/setting?group=insurrance'));
        }
    }

       public function get_staff_salary_form(){
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {

            $id = $this->input->post('id');
            $name_object = $this->db->query('select sl.salary_val from '.db_prefix().'salary_form as sl where sl.form_id = ' .$id)->row();
            }
        }
        if($name_object){
            echo json_encode([
                'salary_val'  => $name_object->salary_val,
            ]);
        }
    
    }

        public function get_staff_allowance_type(){
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {

            $id = $this->input->post('id');
            $name_object = $this->db->query('select at.allowance_val from '.db_prefix().'allowance_type as at  where at.type_id = ' .$id)->row();
            }
        }
        if($name_object){
            echo json_encode([
                'allowance_val'  => $name_object->allowance_val,
            ]);
        }
    
    }

    public function insurances(){

        $this->load->model('departments_model');
        $this->load->model('staff_model');
        $this->load->model('hrm_model');
        
        $data['month'] = $this->hrm_model->get_month();

        $data['title'] = _l('insurrance');
        $data['dep_tree'] = json_encode($this->hrm_model->get_department_tree());

        $this->load->view('hrm/insurance/manage_insurance', $data);
    }

    //function add,delete,update insurrance
     public function insurance($id = ''){

        if (!has_permission('hrm', '', 'view')) {
            access_denied('hrm');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($this->input->post('insurance_id') == '') {
                if (!has_permission('hrm', '', 'create')) {
                    access_denied('hrm');
                }
                $id = $this->hrm_model->add_insurance($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('insurance_history')));
                    redirect(admin_url('hrm/insurances'));
                }
            } else {
                if (!has_permission('hrm', '', 'edit')) {
                    access_denied('hrm');
                }

                $response = $this->hrm_model->update_insurance($data, $this->input->post('insurance_id'));
                if (is_array($response)) {
                    if (isset($response['cant_remove_main_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_main_admin'));
                    } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                    }
                } elseif ($response == true) {

                    set_alert('success', _l('updated_successfully', _l('insurance_history')));
                }
                redirect(admin_url('hrm/insurances'));
            }
        }
        
        if ($id == '') {
            $title = _l('add_new', _l('insurrance'));
            $data['title'] = $title;
        } else {
            $title = _l('edit', _l('insurrance'));
            $insurance = $this->hrm_model->get_insurance($id);
            $insurance_history = $this->hrm_model->get_insurance_history($id);
           

            $data['insurances']            = $insurance;
            $data['insurance_history']            = $insurance_history;
            
           
            
        }
        $data['month'] = $this->hrm_model->get_month();
        $data['staff'] = $this->staff_model->get();
        $this->load->view('hrm/insurance/insurance', $data);
     }

    public function insurance_book_exists(){
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $insurance_id = $this->input->post('insurance_id');

                if ($insurance_id != '') {
                    $this->db->where('insurance_id', $insurance_id);
                    $staff = $this->db->get('tblstaff_insurance')->row();
                    if ($staff->insurance_book_num == $this->input->post('insurance_book_num')) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('insurance_book_num', $this->input->post('insurance_book_num'));
                $total_rows = $this->db->count_all_results('tblstaff_insurance');
                if ($total_rows > 0) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }
    public function health_insurance_exists(){
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $insurance_id = $this->input->post('insurance_id');

                if ($insurance_id != '') {
                    $this->db->where('insurance_id', $insurance_id);
                    $staff = $this->db->get('tblstaff_insurance')->row();
                    if ($staff->health_insurance_num == $this->input->post('health_insurance_num')) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('health_insurance_num', $this->input->post('health_insurance_num'));
                $total_rows = $this->db->count_all_results('tblstaff_insurance');
                if ($total_rows > 0) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }

    public function delete_insurance_history(){
         if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {

                $insurance_history_id = $this->input->post('insurance_history_id');
                if ($insurance_history_id != '') {
                    $this->db->where('id', $insurance_history_id);
                    $this->db->delete(db_prefix() . 'staff_insurance_history');
                    if ($this->db->affected_rows() > 0 ){
                       
                        echo json_encode([
                            'data' => true,
                            'message' => _l('delete_insurance_history_success'),
                        ]);
                    }else{
                        
                        echo json_encode([
                            'data' => false,
                            'message' => _l('delete_insurance_history_false'),

                        ]);

                    }
                }
            }
        }
    }

    public function insurance_type(){
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $data['id'] ?? '';
            unset($data['id']);
            if (isset($data['from_month'])) {
                $data['from_month'] = to_sql_date($data['from_month']);
            }
            if ($id) {
                $this->hrm_model->update_insurance_type($data, $id);
                set_alert('success', _l('updated_successfully', _l('insurance_type')));
            } else {
                $this->hrm_model->add_insurance_type($data);
                set_alert('success', _l('added_successfully', _l('insurance_type')));
            }
            redirect(admin_url('hrm/setting?group=insurrance'));
        }
        redirect(admin_url('hrm/setting?group=insurrance'));
    }
    public function delete_insurance_type($id){
        if (!$id) {
            redirect(admin_url('hrm/setting?group=insurrance'));
        }
        $response = $this->hrm_model->delete_insurance_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('insurance_type')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('insurance_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('insurance_type')));
        }
        redirect(admin_url('hrm/setting?group=insurrance'));
    }

    /* =====================================================================
     * v2.6.3 - Custom insurance categories (Life, Dental, Vision, ...)
     * ===================================================================== */
    public function insurance_category()
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $data['id'] ?? '';
            unset($data['id']);
            $data['name']            = trim((string) ($data['name'] ?? ''));
            $data['company_percent'] = (float) ($data['company_percent'] ?? 0);
            $data['staff_percent']   = (float) ($data['staff_percent'] ?? 0);
            $data['active']          = isset($data['active']) ? 1 : 0;
            if ($data['name'] === '') {
                set_alert('warning', _l('please_fill_all_required_fields'));
                redirect(admin_url('hrm/setting?group=insurance_category'));
            }
            if ($id) {
                $this->hrm_model->update_insurance_category($data, $id);
                set_alert('success', _l('updated_successfully', _l('insurance_category')));
            } else {
                $this->hrm_model->add_insurance_category($data);
                set_alert('success', _l('added_successfully', _l('insurance_category')));
            }
            redirect(admin_url('hrm/setting?group=insurance_category'));
        }
        redirect(admin_url('hrm/setting?group=insurance_category'));
    }

    public function delete_insurance_category($id)
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        if (!$id) {
            redirect(admin_url('hrm/setting?group=insurance_category'));
        }
        if ($this->hrm_model->delete_insurance_category($id)) {
            set_alert('success', _l('deleted', _l('insurance_category')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('insurance_category')));
        }
        redirect(admin_url('hrm/setting?group=insurance_category'));
    }

    /* =====================================================================
     * v2.6.3 - Custom deduction types (Cash advance, Loan, Uniform, ...)
     * ===================================================================== */
    public function deduction_type()
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $data['id'] ?? '';
            unset($data['id']);
            $data['name']         = trim((string) ($data['name'] ?? ''));
            $data['calc_type']    = ($data['calc_type'] ?? 'fixed') === 'percent' ? 'percent' : 'fixed';
            $data['amount']       = (float) ($data['amount'] ?? 0);
            $data['taxable']      = !empty($data['taxable']) ? 1 : 0;
            $data['is_recurring'] = !empty($data['is_recurring']) ? 1 : 0;
            if ($data['name'] === '') {
                set_alert('warning', _l('please_fill_all_required_fields'));
                redirect(admin_url('hrm/setting?group=deduction_type'));
            }
            if ($id) {
                $this->hrm_model->update_deduction_type($data, $id);
                set_alert('success', _l('updated_successfully', _l('deduction_type')));
            } else {
                $this->hrm_model->add_deduction_type($data);
                set_alert('success', _l('added_successfully', _l('deduction_type')));
            }
            redirect(admin_url('hrm/setting?group=deduction_type'));
        }
        redirect(admin_url('hrm/setting?group=deduction_type'));
    }

    public function delete_deduction_type($id)
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        if (!$id) {
            redirect(admin_url('hrm/setting?group=deduction_type'));
        }
        if ($this->hrm_model->delete_deduction_type($id)) {
            set_alert('success', _l('deleted', _l('deduction_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('deduction_type')));
        }
        redirect(admin_url('hrm/setting?group=deduction_type'));
    }

    /* =====================================================================
     * v2.6.3 - Employee deductions / cash-advance ledger
     * ===================================================================== */
    public function deductions()
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        $this->load->model('staff_model');

        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $data['id'] ?? '';
            unset($data['id']);
            $data['staff_id']           = (int) ($data['staff_id'] ?? 0);
            $data['deduction_type_id']  = !empty($data['deduction_type_id']) ? (int) $data['deduction_type_id'] : null;
            $data['title']              = trim((string) ($data['title'] ?? ''));
            $data['total_amount']       = (float) ($data['total_amount'] ?? 0);
            $data['installment_amount'] = (float) ($data['installment_amount'] ?? 0);
            $data['collect_type']       = ($data['collect_type'] ?? 'one_time') === 'installment' ? 'installment' : 'one_time';
            $data['auto_collect']       = !empty($data['auto_collect']) ? 1 : 0;

            if (!$data['staff_id'] || $data['title'] === '' || $data['total_amount'] <= 0) {
                set_alert('warning', _l('please_fill_all_required_fields'));
                redirect(admin_url('hrm/deductions'));
            }
            if ($id) {
                $this->hrm_model->update_staff_deduction($data, $id);
                set_alert('success', _l('updated_successfully', _l('staff_deduction')));
            } else {
                $this->hrm_model->add_staff_deduction($data);
                set_alert('success', _l('added_successfully', _l('staff_deduction')));
            }
            redirect(admin_url('hrm/deductions'));
        }

        $data['title'] = _l('staff_deductions');
        $data['deductions'] = $this->hrm_model->get_staff_deduction();
        $data['deduction_type'] = $this->hrm_model->get_deduction_type();
        $data['staff'] = $this->hrm_model->get_staff();
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $this->load->view('deductions', $data);
    }

    public function collect_deduction()
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        if ($this->input->post()) {
            $result = $this->hrm_model->add_deduction_collection($this->input->post());
            if ($result) {
                set_alert('success', _l('deduction_collected_successfully'));
            } else {
                set_alert('warning', _l('deduction_nothing_to_collect'));
            }
        }
        redirect(admin_url('hrm/deductions'));
    }

    public function delete_staff_deduction($id)
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        if (!$id) {
            redirect(admin_url('hrm/deductions'));
        }
        if ($this->hrm_model->delete_staff_deduction($id)) {
            set_alert('success', _l('deleted', _l('staff_deduction')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('staff_deduction')));
        }
        redirect(admin_url('hrm/deductions'));
    }

    /* =====================================================================
     * v2.6.3 - 13th month salary / year-end bonus
     * ===================================================================== */
    public function thirteenth_month()
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        $this->load->model('staff_model');

        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $data['id'] ?? '';
            unset($data['id']);
            $row = [
                'staff_id'      => (int) ($data['staff_id'] ?? 0),
                'year'          => (int) ($data['year'] ?? date('Y')),
                'base_amount'   => (float) ($data['base_amount'] ?? 0),
                'months_worked' => (float) ($data['months_worked'] ?? 12),
                'status'        => in_array(($data['status'] ?? 'draft'), ['draft', 'approved', 'paid']) ? $data['status'] : 'draft',
                'notes'         => $data['notes'] ?? null,
            ];
            if (!$row['staff_id'] || !$row['year']) {
                set_alert('warning', _l('please_fill_all_required_fields'));
                redirect(admin_url('hrm/thirteenth_month'));
            }
            $this->hrm_model->save_thirteenth_month($row, $id);
            set_alert('success', $id ? _l('updated_successfully', _l('thirteenth_month')) : _l('added_successfully', _l('thirteenth_month')));
            redirect(admin_url('hrm/thirteenth_month?year=' . $row['year']));
        }

        $year = $this->input->get('year');
        $year = $year !== null && $year !== '' ? (int) $year : (int) date('Y');
        $data['title'] = _l('thirteenth_month_salary');
        $data['year'] = $year;
        $data['records'] = $this->hrm_model->get_thirteenth_month($year);
        $data['staff'] = $this->hrm_model->get_staff();
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $this->load->view('thirteenth_month', $data);
    }

    /**
     * Bulk-generate draft 13th-month rows for every active employee for a year
     * (skipping staff who already have a row for that year).
     */
    public function generate_thirteenth_month()
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        $year = (int) ($this->input->post('year') ?: date('Y'));
        $default_base = (float) ($this->input->post('default_base') ?? 0);
        $default_months = (float) ($this->input->post('default_months') ?: 12);

        $existing = $this->hrm_model->get_thirteenth_month($year);
        $already = [];
        foreach ($existing as $e) {
            $already[(int) $e['staff_id']] = true;
        }

        $created = 0;
        foreach ((array) $this->hrm_model->get_staff() as $s) {
            $sid = (int) $s['staffid'];
            if (isset($already[$sid])) {
                continue;
            }
            $this->hrm_model->save_thirteenth_month([
                'staff_id'      => $sid,
                'year'          => $year,
                'base_amount'   => $default_base,
                'months_worked' => $default_months,
                'status'        => 'draft',
                'notes'         => null,
            ]);
            $created++;
        }
        set_alert('success', _l('thirteenth_month_generated', $created));
        redirect(admin_url('hrm/thirteenth_month?year=' . $year));
    }

    public function delete_thirteenth_month($id)
    {
        if (!is_admin()) {
            access_denied('hrm');
        }
        $year = (int) ($this->input->get('year') ?: date('Y'));
        if ($id && $this->hrm_model->delete_thirteenth_month($id)) {
            set_alert('success', _l('deleted', _l('thirteenth_month')));
        }
        redirect(admin_url('hrm/thirteenth_month?year=' . $year));
    }

    public function get_hrm_formality(){
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('formality') == 'increase') {
                echo json_encode([
                    'sign_a_labor_contract'  => get_hrm_option('sign_a_labor_contract'),
                    'maternity_leave_to_return_to_work'  => get_hrm_option('maternity_leave_to_return_to_work'),
                    'unpaid_leave_to_return_to_work'  => get_hrm_option('unpaid_leave_to_return_to_work'),
                    'increase_the_premium'  => get_hrm_option('increase_the_premium'),
                ]);
                die();
                
            }elseif ($this->input->post('formality') == 'decrease') {
                echo json_encode([
                    'contract_paid_for_unemployment'  => get_hrm_option('contract_paid_for_unemployment'),
                    'maternity_leave_regime'  => get_hrm_option('maternity_leave_regime'),
                    'reduced_premiums'  => get_hrm_option('reduced_premiums'),
                ]);
                die();
                
            }

        }
    }

    public function get_hrm_staff(){
	
		\modules\hrm\core\Apiinit::ease_of_mind('hrm');
		\modules\hrm\core\Apiinit::the_da_vinci_code('hrm');
	
        if ($this->input->is_ajax_request()) {

            $staffid = $this->input->get('staffid');

            $total_rows = $this->db->query('select si.insurance_id from '.db_prefix().'staff_insurance as si where si.staff_id = '.$staffid)->result_array();
                if (count($total_rows) > 0) {
                $id = $total_rows[0]['insurance_id'];

                $insurance = $this->hrm_model->get_insurance($id);
                if(isset($insurance)){
                    foreach ($insurance as $key => $insuran) {
                        $insurance_book_num = $insuran['insurance_book_num'];
                        $health_insurance_num = $insuran['health_insurance_num'];
                        $city_code = $insuran['city_code'];
                        $registration_medical = $insuran['registration_medical'];
                    }
                }
                $insurance_history = $this->hrm_model->get_insurance_history($id);
                $month = $this->hrm_model->get_month();
                $staff = $this->staff_model->get();

                $data_insert ='';
                  if(isset($insurance_history) && count($insurance_history) != 0){
                        foreach ($insurance_history as $keydetails => $value) {
                        $keydetails = $keydetails +1;
                                             
            $data_insert .= '<div class="row insurance-history ">';
            $data_insert .=     '<div class="col-md-2">';
                                $from_month = (isset($value['from_month']) ? $value['from_month'] : '');
            $data_insert .=   '<label for="from_month['.$keydetails .']">'. _l('from_month').'</label>';

            $data_insert .=   '<select name="from_month['. $keydetails.']" class="selectpicker"';
            $data_insert .=    'id="from_month['.$keydetails.']" data-width="100%"';
            $data_insert .=    'data-none-selected-text="'. _l('dropdown_non_selected_tex').'">' ;

            $data_insert .=   '<option value=""></option>'; 
                                 if(isset($from_month)){
                                  $exploded = explode("-", $from_month);
                                  $exploded = array_reverse($exploded);
                                  $newFormat = implode("/", $exploded);
                                }
                        foreach($month as $m){                             
            $data_insert .=    '<option value="'. $m['id'].'"';
                                 if(isset($from_month) && $newFormat == $m['id'] ){

            $data_insert .=         'selected';
                                    }
            $data_insert .=        '>'. $m['name'].'</option>';
                                }
            $data_insert .=         '</select>';
            
            $data_insert .=         '</div>';
            $data_insert .=        '<div class="col-md-3">';
                                    $formality = isset($value['formality']) ? $value['formality'] : '' ;
            $data_insert .=         '<label for="formality['. $keydetails .']" class="control-label">'._l(                      'formality').'</label>';
            
            $data_insert .=    '<select onchange="OnSelectReason (this)"';
            $data_insert .=      'name="formality['. $keydetails .']" class="selectpicker"';
            $data_insert .=     'id="formality['. $keydetails .']" data-width="100%" data-none-selected-text="'._l('fillter_by_status').'">';
            $data_insert .=     '  <option value=""></option>';
            $data_insert .=     '  <option value="increase"';
                                 if(isset($formality) && $formality == 'increase'){
            $data_insert .=         'selected';
                                    }
             $data_insert .=        '>'._l('increase').'</option><option value="decrease"';
                 if(isset($formality) && $formality == 'decrease'){
             $data_insert .=       'selected';
                                    }
             $data_insert .=        '>'. _l('decrease').'</option></select></div>                      
                                            <div class="col-md-3">';
                                    $reason = isset($value['reason']) ? $value['reason'] : '';
            $data_insert .=         '<label for="reason['.$keydetails .']" class="control-label">'. _l('reason_').'</label><select  name="reason['.$keydetails .']" class="selectpicker" id="reason['.$keydetails .']" data-width="100%" data-none-selected-text="'. _l('fillter_by_formality').'"><option value=""></option><option value="'.$reason.'"  selected><'._l(''.$reason.'') .'></option></select></div>';
            
            $data_insert .=           '<div class="col-md-3">';
                            $premium_rates = isset($value['premium_rates']) ? $value['premium_rates'] : '' ;
                            $attr = array();
                            $attr = ['data-type' => 'currency'];
                                            
            $data_insert .= render_input('premium_rates['. $keydetails .']','premium_rates', app_format_money((int)$premium_rates,''),'text', $attr);
             $data_insert .=        '</div>';
                                    if($keydetails == 1){
            $data_insert .= '<div class="col-md-1 hrm-nowrap hrm-lineheight84" name="add_insurance_history">';
            $data_insert .= '<button name="add_new_insurance_history" class="btn new_insurance_history btn-success hrm-radius20" data-ticket="true" type="button"php title="'. _l('add') .'" ><i class="fa fa-plus" ></i>';
            $data_insert .=    form_hidden('id_history['.$keydetails.']',$value['id']);
            $data_insert .=     '</button>';
            $data_insert .=     '</div>';
                                    } else {
            $data_insert .=     '<div class="col-md-1 hrm-nowrap hrm-lineheight84" name="add_insurance_history">';
            $data_insert .=    '<button name="add_new_insurance_history" class="btn remove_insurance_history btn-danger hrm-radius20" data-ticket="true" type="button" title="'._l('delete').'" ><i class="fa fa-minus"></i>';
            $data_insert .=     form_hidden('id_history['.$keydetails.']',$value['id']);
            $data_insert .=     '</button>';
            $data_insert .=     '</div>';
                                        } 
            $data_insert .=     '</div>';

                        }    
                    }


                    echo json_encode([
                        'id' => $id,
                        'data' => $data_insert,
                        'insurance_book_num'   => $insurance_book_num,
                        'health_insurance_num' => $health_insurance_num,
                        'city_code'            => $city_code,
                        'registration_medical'  => $registration_medical,

                    ]);
                    die();
                }else{
        $month = $this->hrm_model->get_month();
                $staff = $this->staff_model->get();
                $data_null ='';
        $data_null  .=    '<div class="row insurance-history ">';
        $data_null  .=    '<div class="col-md-2">';
                $from_month = (isset($from_month) ? $from_month : '');
        $data_null  .=        '<div class="form-group">';
        $data_null  .=        '<label for="from_month[1]">'. _l('from_month').'</label>';
        $data_null  .=      '<select name="from_month[1]" class="selectpicker" id="from_month[1]" data-width="100%"';

        $data_null  .= 'data-none-selected-text="'. _l('dropdown_non_selected_tex').'">' ;
        $data_null  .=        '<option value=""></option>' ;

                foreach($month as $s){                             
        $data_null  .=         '<option value="'.$s['id'].'">'.$s['name'].'</option>';
                        }
        $data_null  .=     '</select>';
        $data_null  .=        '</div>';
        $data_null  .=    '</div>';
        $data_null  .=   '<div class="col-md-3">';
                $formality = isset($formality) ? $formality : '' ;
        $data_null  .=    '<label for="formality[1]" class="control-label">'. _l('formality').'</label>';
        $data_null  .=    '<select onchange="OnSelectReason (this)" name="formality[1]" class="selectpicker" id="';
        $data_null  .= 'formality[1]" data-width="100%" data-none-selected-text="'. _l('fillter_by_status').'">'; 
        $data_null  .=        '<option value=""></option>';
        $data_null  .=        '<option value="increase">'. _l('increase').'</option>';
        $data_null  .=        '<option value="decrease">'._l('decrease').'</option>';
        $data_null  .=    '</select>';
        $data_null  .=    '</div>';

        $data_null  .=    '<div class="col-md-3">';
                $reason = isset($reason) ? $reason : '' ;
        $data_null  .=    '<label for="reason[1]" class="control-label">'. _l('reason_').'</label>';
        $data_null  .=    '<select  name="reason[1]" class="selectpicker" id="reason[1]" data-width="100%"';

        $data_null  .= 'data-none-selected-text="'. _l('fillter_by_formality').'">' ;
        $data_null  .=        '<option value=""></option>';
        $data_null  .=    '</select>';
        $data_null  .=    '</div>';

        $data_null  .=    '<div class="col-md-3">';
                $premium_rates = isset($premium_rates) ? $premium_rates : '' ;
            
            $attr = array();
            $attr = ['data-type' => 'currency'];
        $data_null  .=    render_input('premium_rates[1]','premium_rates', $premium_rates,'text', $attr);
        $data_null  .=    '</div>';

    $data_null  .= '<div class="col-md-1 hrm-nowrap hrm-lineheight84" name="add_insurance_history">';
        $data_null  .=    '<button name="add_new_insurance_history" class="btn new_insurance_history btn-success hrm-radius20"'; 
        $data_null  .=  'data-ticket="true" type="button" title="'. _l('add') .'"><i class="fa fa-plus"></i></button>';
        $data_null  .=    '</div>';

        $data_null  .= '</div>';
                    echo json_encode([
                        'id' => '',
                        'data_null' => $data_null,
                    ]);
                    die();
                } 
        }
    }

    public function timekeeping(){
        $this->load->model('departments_model');
        $this->load->model('staff_model');

        $data['group'] = $this->input->get('group');
        $data['title'] = _l($data['group']);
        $data['tab'][] = 'manage_dayoff';
        $data['tab'][] = 'allocate_shiftwork';
        $data['tab'][] = 'table_shiftwork';
        

        if($data['group'] == ''){
            $data['group'] = 'manage_dayoff';
            $data['title'] = _l($data['group']);
        }
        $data['departments'] = $this->departments_model->get();
        $data['positions'] = $this->hrm_model->get_job_position();
        $data['holiday'] = $this->hrm_model->get_break_dates('holiday');
        $data['event_break'] = $this->hrm_model->get_break_dates('event_break');
        $data['unexpected_break'] = $this->hrm_model->get_break_dates('unexpected_break');
        $data['shifts'] = $this->hrm_model->get_shifts();


        $data['day_by_month'] = [];
        $data['day_by_month_tk'] = [];
        $data['day_by_month'][] = _l('staff');
        $data['day_by_month_tk'][] = _l('staff_id');
        $data['day_by_month_tk'][] = _l('hr_code');
        $data['day_by_month_tk'][] = _l('staff');

        $data['set_col'] = [];
        $data['set_col_tk'] = [];
        $data['set_col_tk'][] = ['data' => _l('staff_id'), 'type' => 'text'];
        $data['set_col_tk'][] = ['data' => _l('hr_code'), 'type' => 'text','readOnly' => true];
        $data['set_col_tk'][] = ['data' => _l('staff'), 'type' => 'text','readOnly' => true];
        $data['set_col'][] = ['data' => _l('staff'), 'type' => 'text'];

        $month      = date('m');
        $month_year = date('Y');
        for ($d = 1; $d <= 31; $d++) {
            $time = mktime(12, 0, 0, $month, $d, $month_year);
            if (date('m', $time) == $month) {
                array_push($data['day_by_month_tk'], date('d/m D', $time));
                array_push($data['day_by_month'], date('d/m D', $time));
                array_push($data['set_col'],[ 'data' => date('d/m D', $time), 'type' => 'text']);
                array_push($data['set_col_tk'],[ 'data' => date('d/m D', $time), 'type' => 'text']);
            }
        }

        $data['day_by_month'] = json_encode($data['day_by_month']);
        $data['day_by_month_tk'] = json_encode($data['day_by_month_tk']);

        $data['set_col'] = json_encode($data['set_col']);
        $data['set_col_tk'] = json_encode($data['set_col_tk']);

        $data_ts = $this->hrm_model->get_hrm_ts_by_month(date('m'));

        if(isset($data['shifts'][0])){
            $work_shift = $data['shifts'][0];
            $work_shift['shift_s'] = $this->hrm_model->get_data_edit_shift($work_shift['id']);
        }

        $data_map = [];
        foreach($data_ts as $ts){
            $staff_info = array();
            $staff_info['date'] = date('d/m D', strtotime($ts['date_work']));

            
            $ts_type = $this->hrm_model->get_ts_by_date_and_staff($ts['date_work'],$ts['staff_id']);
            if(count($ts_type) <= 1){
                 $staff_info['ts'] = $ts['type'].':'.$ts['value'];
                
            }else{
                $str = '';
                foreach($ts_type as $tp){
                    if($str == ''){
                        $str .= $tp['type'].':'.$tp['value'];
                    }else{
                        $str .= '-'.$tp['type'].':'.$tp['value'];
                    }
                }
                $staff_info['ts'] = $str;
            }
              
            
            
            if(!isset($data_map[$ts['staff_id']])){
                $data_map[$ts['staff_id']] = array();
            }
            $data_map[$ts['staff_id']][$staff_info['date']] = $staff_info;
        }
  

        $data['staff_row_tk'] = [];
        $data['staff_row'] = [];
        $staffs = $this->staff_model->get();
        $shift_staff = [];
        foreach($staffs as $s){
            
            $shift_staff = [_l('staff') => $s['firstname'].' '.$s['lastname']];
                if(isset($work_shift['shift_s'])){
                    for ($d = 1; $d <= 31; $d++) {
                        $time = mktime(12, 0, 0, $month, $d, $month_year);
                        if (date('m', $time) == $month) {
                            if(date('N', $time) == 1){
                                $shift_staff[date('d/m D', $time)] = _l('time_working').': '.$work_shift['shift_s'][0]['monday'] .' - '.$work_shift['shift_s'][1]['monday'].'  '._l('time_lunch').': '.$work_shift['shift_s'][2]['monday'].' - '.$work_shift['shift_s'][3]['monday'];
                            }elseif(date('N', $time) == 2){
                                $shift_staff[date('d/m D', $time)] = _l('time_working').': '.$work_shift['shift_s'][0]['tuesday'] .' - '.$work_shift['shift_s'][1]['tuesday'].'  '._l('time_lunch').': '.$work_shift['shift_s'][2]['tuesday'].' - '.$work_shift['shift_s'][3]['tuesday'];
                            }elseif(date('N', $time) == 3){
                                $shift_staff[date('d/m D', $time)] = _l('time_working').': '.$work_shift['shift_s'][0]['wednesday'] .' - '.$work_shift['shift_s'][1]['wednesday'].'  '._l('time_lunch').': '.$work_shift['shift_s'][2]['wednesday'].' - '.$work_shift['shift_s'][3]['wednesday'];
                            }elseif(date('N', $time) == 4){
                                $shift_staff[date('d/m D', $time)] = _l('time_working').': '.$work_shift['shift_s'][0]['thursday'] .' - '.$work_shift['shift_s'][1]['thursday'].'  '._l('time_lunch').': '.$work_shift['shift_s'][2]['thursday'].' - '.$work_shift['shift_s'][3]['thursday'];
                            }elseif(date('N', $time) == 5){
                                $shift_staff[date('d/m D', $time)] = _l('time_working').': '.$work_shift['shift_s'][0]['friday'] .' - '.$work_shift['shift_s'][1]['friday'].'  '._l('time_lunch').': '.$work_shift['shift_s'][2]['friday'].' - '.$work_shift['shift_s'][3]['friday'];
                            }elseif(date('N', $time) == 7){
                                $shift_staff[date('d/m D', $time)] = _l('time_working').': '.$work_shift['shift_s'][0]['sunday'] .' - '.$work_shift['shift_s'][1]['sunday'].'  '._l('time_lunch').': '.$work_shift['shift_s'][2]['sunday'].' - '.$work_shift['shift_s'][3]['sunday'];
                            }elseif(date('N', $time) == 6 && (date('d', $time)%2) == 1){
                                $shift_staff[date('d/m D', $time)] = _l('time_working').': '.$work_shift['shift_s'][0]['saturday_odd'] .' - '.$work_shift['shift_s'][1]['saturday_odd'].'  '._l('time_lunch').': '.$work_shift['shift_s'][2]['saturday_odd'].' - '.$work_shift['shift_s'][3]['saturday_odd'];
                            }elseif(date('N', $time) == 6 && (date('d', $time)%2) == 0){
                                $shift_staff[date('d/m D', $time)] = _l('time_working').': '.$work_shift['shift_s'][0]['saturday_even'] .' - '.$work_shift['shift_s'][1]['saturday_even'].'  '._l('time_lunch').': '.$work_shift['shift_s'][2]['saturday_even'].' - '.$work_shift['shift_s'][3]['saturday_even'];
                            }
                        }
                    }
                }
            array_push($data['staff_row'], $shift_staff);


            $ts_date = '';
            $ts_ts = '';
            $result_tb = [];
            if(isset($data_map[$s['staffid']])){

                foreach ($data_map[$s['staffid']] as $key => $value) {
                    $ts_date = $data_map[$s['staffid']][$key]['date'];
                    $ts_ts =  $data_map[$s['staffid']][$key]['ts'];
                    $result_tb[] = [$ts_date => $ts_ts];
                }
               
            }
            $dt_ts = [];
            $dt_ts = [_l('staff_id') => $s['staffid'],_l('hr_code') => $s['staff_identifi'],_l('staff') => $s['firstname'].' '.$s['lastname']];
            foreach ($result_tb as $key => $rs) {
                foreach ($rs as $day => $val) {
                   $dt_ts[$day] = $val;
                }
            }

            array_push($data['staff_row_tk'], $dt_ts);
            
        }

        $data['tabs']['view'] = 'timekeeping/'.$data['group'];
        $this->load->view('timekeeping/manage_timekeeping', $data);
    }

    public function day_off(){
        if($this->input->post()){
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $add = $this->hrm_model->add_day_off($data); 
                if($add > 0){
                    $message = _l('day_off').' '. _l('added_successfully');
                    set_alert('success',$message);
                }
                redirect(admin_url('hrm/timekeeping?group=manage_dayoff'));
            }else{
                $id = $data['id'];
                unset($data['id']);
                $success = $this->hrm_model->update_day_off($data,$id);
                if($success == true){
                    $message = _l('day_off').' '._l('updated_successfully');
                    set_alert('success', $message);
                }
                redirect(admin_url('hrm/timekeeping?group=manage_dayoff'));
            }

        }
    }
    public function delete_day_off($id){
        if (!$id) {
            redirect(admin_url('hrm/timekeeping?group=manage_dayoff'));
        }
        $response = $this->hrm_model->delete_day_off($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced').' '. _l('day_off'));
        } elseif ($response == true) {
            set_alert('success', _l('deleted').' '._l('day_off'));
        } else {
            set_alert('warning', _l('problem_deleting').' '. _l('day_off'));
        }
        redirect(admin_url('hrm/timekeeping?group=manage_dayoff'));
    }
    
    public function shifts(){
        if($this->input->post()){
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $add = $this->hrm_model->add_work_shift($data); 
                if($add > 0){
                    $message = _l('shift') . '' . _l('added_successfully');
                    set_alert('success',$message);
                }
                redirect(admin_url('hrm/timekeeping?group=allocate_shiftwork'));
            }else{
                $id = $data['id'];
                unset($data['id']);
                $success = $this->hrm_model->update_work_shift($data,$id);
                if($success == true){
                    $message = _l('shift').' '._l('updated_successfully');
                    set_alert('success', $message);
                }
                redirect(admin_url('hrm/timekeeping?group=allocate_shiftwork'));
            }
        }   
    }

    public function get_data_edit_shift($id){
        $shift_handson = $this->hrm_model->get_data_edit_shift($id);
        $result = [];
        $node = [];
        foreach ($shift_handson as $key => $value) {
            foreach ($value as $col => $val) {
                if($col == 'detail'){
                    if($key == 0){
                        $node[_l($col)] =  _l('time_start_work');
                    }elseif ($key == 1) {
                       $node[_l($col)] =  _l('time_end_work');
                    }elseif($key == 2){
                        $node[_l($col)] =  _l('start_lunch_break_time');
                    }elseif($key == 3){
                        $node[_l($col)] =  _l('end_lunch_break_time');
                    }
                }else{

                    $node[_l($col)] = $val;

                }
            }
            $result[] = $node; 
        }
        echo json_encode([
            'handson' => $result,
        ]);
    }

    public function delete_shift($id){
        if (!$id) {
            redirect(admin_url('hrm/timekeeping?group=allocate_shiftwork'));
        }
        $response = $this->hrm_model->delete_shift($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced').' '. _l('shift'));
        } elseif ($response == true) {
            set_alert('success', _l('deleted').' '._l('shift'));
        } else {
            set_alert('warning', _l('problem_deleting').' '. _l('shift'));
        }
        redirect(admin_url('hrm/timekeeping?group=allocate_shiftwork'));
    }

    public function download_payslip($staff_id, $payroll_table_id)
    {
        if (!has_permission('hrm', '', 'view') && $staff_id != get_staff_user_id()) {
            access_denied('hrm');
        }
        $pt = $this->hrm_model->get_payroll_table($payroll_table_id);
        if (!$pt) {
            show_404();
        }
        $template = json_decode($pt->template_data ?? '[]', true);
        $staff_row = null;
        $staff = $this->staff_model->get($staff_id);
        $staff_name = 'Staff_' . $staff_id;
        if ($staff) {
            $hr_code = $staff->staff_identifi ?? '';
            $staff_name = ($staff->firstname ?? '') . ' ' . ($staff->lastname ?? '');
            foreach ((array)$template as $row) {
                $match = false;
                if (isset($row['staff_id']) && $row['staff_id'] == $staff_id) $match = true;
                if (isset($row['staffid']) && $row['staffid'] == $staff_id) $match = true;
                if (isset($row['hr_code']) && $row['hr_code'] == $hr_code) $match = true;
                if (isset($row['staff']) && stripos($row['staff'], $staff_name) !== false) $match = true;
                if ($match) { $staff_row = $row; break; }
            }
        }
        $filename = 'payslip_' . $staff_name . '_' . date('Y-m', strtotime($pt->payroll_month)) . '.csv';
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        if ($staff_row) {
            foreach ($staff_row as $k => $v) {
                fputcsv($out, [$k, $v]);
            }
        } else {
            fputcsv($out, ['Payslip for ' . $staff_name, date('F Y', strtotime($pt->payroll_month))]);
            fputcsv($out, ['No data found', '']);
        }
        fclose($out);
        exit;
    }

    public function view_payslip($staff_id, $payroll_table_id)
    {
        if (!has_permission('hrm', '', 'view') && $staff_id != get_staff_user_id()) {
            access_denied('hrm');
        }
        $pt = $this->hrm_model->get_payroll_table($payroll_table_id);
        if (!$pt) {
            show_404();
        }
        $template = json_decode($pt->template_data ?? '[]', true);
        $staff_row = null;
        $staff = $this->staff_model->get($staff_id);
        $staff_name = 'Staff_' . $staff_id;
        if ($staff) {
            $hr_code = $staff->staff_identifi ?? '';
            $staff_name = ($staff->firstname ?? '') . ' ' . ($staff->lastname ?? '');
            foreach ((array)$template as $row) {
                $match = false;
                if (isset($row['staff_id']) && $row['staff_id'] == $staff_id) $match = true;
                if (isset($row['staffid']) && $row['staffid'] == $staff_id) $match = true;
                if (isset($row['hr_code']) && $row['hr_code'] == $hr_code) $match = true;
                if (isset($row['staff']) && stripos($row['staff'], $staff_name) !== false) $match = true;
                if ($match) { $staff_row = $row; break; }
            }
        }
        $data['staff_row'] = $staff_row;
        $data['staff_name'] = $staff_name;
        $data['payroll_month'] = $pt->payroll_month;
        $data['payroll_type'] = $this->hrm_model->get_payroll_type($pt->payroll_type);
        $this->load->view('payrolls/payslip_print', $data);
    }

    public function delete_payroll_table($id){
        if (!$id) {
            redirect(admin_url('hrm/payroll?group=payslip'));
        }
        $response = $this->hrm_model->delete_payroll_table($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced').' '. _l('payslip'));
        } elseif ($response == true) {
            set_alert('success', _l('deleted').' '._l('payslip'));
        } else {
            set_alert('warning', _l('problem_deleting').' '. _l('payslip'));
        }
        redirect(admin_url('hrm/payroll?group=payslip'));
    }

    public function paysplit_exists(){
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                $payroll_month = $this->input->post('payroll_month');
                $payroll_type = $this->input->post('payroll_type');
                
                if(strlen($payroll_month) != 0 && strlen($payroll_type) != 0){
 
                $this->db->where('payroll_month', to_sql_date($payroll_month));
                $this->db->where('payroll_type', (int)$payroll_type);
                $total_rows = $this->db->count_all_results('tblpayroll_table');
                    if ($total_rows > 0) {
                        echo json_encode(false);
                    } else {
                        echo json_encode(true);
                    }
                    die();
                }
            }
        }
    }

    public function job_descriptions()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $this->load->model('departments_model');
        $data['title'] = _l('job_descriptions');
        $data['job_groups'] = $this->hrm_model->get_job_description_groups();
        $data['positions'] = $this->hrm_model->get_job_position();
        $this->load->view('job_descriptions', $data);
    }

    public function job_description_group($id = '')
    {
        if (!has_permission('hrm', '', 'edit')) access_denied('hrm');
        if ($this->input->post()) {
            $data = $this->input->post();
            if (empty($data['id'])) {
                $this->hrm_model->add_job_description_group(['name' => $data['name']]);
                set_alert('success', _l('added_successfully', _l('job_description_groups')));
            } else {
                $id = $data['id'];
                unset($data['id']);
                $this->hrm_model->update_job_description_group($data, $id);
                set_alert('success', _l('updated_successfully', _l('job_description_groups')));
            }
            redirect(admin_url('hrm/job_descriptions'));
        }
    }

    public function delete_job_description_group($id)
    {
        if (!has_permission('hrm', '', 'edit')) access_denied('hrm');
        if (!$id) redirect(admin_url('hrm/job_descriptions'));
        $this->db->where('job_description_group_id', $id)->update(db_prefix().'job_position', ['job_description_group_id' => null]);
        $this->hrm_model->delete_job_description_group($id);
        set_alert('success', _l('deleted', _l('job_description_groups')));
        redirect(admin_url('hrm/job_descriptions'));
    }

    public function organizational_chart()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $this->load->model('departments_model');
        $data['title'] = _l('organizational_chart');
        $data['dep_tree'] = json_encode($this->hrm_model->get_department_tree());
        $data['staff'] = $this->staff_model->get();
        $this->load->view('organizational_chart', $data);
    }

    public function onboarding()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('onboarding');
        $data['templates'] = $this->hrm_model->get_onboarding_templates();
        $data['records'] = $this->hrm_model->get_onboarding_records();
        $data['staff_list'] = $this->hrm_model->get_staff('', ['active' => 1]);
        $this->load->view('onboarding', $data);
    }

    public function onboarding_assign()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/onboarding'));
        $data = $this->input->post();
        $this->hrm_model->add_onboarding_record($data);
        set_alert('success', _l('added_successfully', _l('onboarding')));
        redirect(admin_url('hrm/onboarding'));
    }

    public function onboarding_record($id)
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $r = $this->db->select('r.*, t.name as template_name, t.checklist_items')->from(db_prefix().'hrm_onboarding_records r')
            ->join(db_prefix().'hrm_onboarding_templates t', 't.id = r.template_id', 'left')
            ->where('r.id', $id)->get()->row_array();
        if (!$r) show_404();
        $data['record'] = $r;
        $data['title'] = _l('onboarding');
        $this->load->view('onboarding_record', $data);
    }

    public function onboarding_update_checklist($id)
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/onboarding'));
        $mark = $this->input->post('mark_completed');
        $data = [];
        if ($mark) {
            $data['status'] = 'completed';
            $data['completed_date'] = date('Y-m-d');
        }
        if (!empty($data)) $this->hrm_model->update_onboarding_record($data, $id);
        set_alert('success', _l('updated_successfully', _l('onboarding')));
        redirect(admin_url('hrm/onboarding_record/'.$id));
    }

    public function delete_onboarding_record($id)
    {
        if (!$id || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/onboarding'));
        $this->db->where('id', $id)->delete(db_prefix().'hrm_onboarding_records');
        set_alert('success', _l('deleted', _l('onboarding')));
        redirect(admin_url('hrm/onboarding'));
    }

    public function training()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('training');
        $data['training_types'] = $this->hrm_model->get_training_types();
        $data['staff_trainings'] = $this->hrm_model->get_staff_trainings();
        $data['staff_list'] = $this->hrm_model->get_staff('', ['active' => 1]);
        $this->load->view('training', $data);
    }

    public function training_add()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/training'));
        $data = $this->input->post();
        $this->hrm_model->add_staff_training($data);
        set_alert('success', _l('added_successfully', _l('training')));
        redirect(admin_url('hrm/training'));
    }

    public function layoff()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('layoff_management');
        $data['layoff_records'] = $this->hrm_model->get_layoff_records();
        $data['checklist'] = $this->hrm_model->get_layoff_checklist();
        $data['staff_list'] = $this->hrm_model->get_staff('', ['active' => 1]);
        $this->load->view('layoff', $data);
    }

    public function layoff_add()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/layoff'));
        $data = $this->input->post();
        $this->hrm_model->add_layoff_record($data);
        set_alert('success', _l('added_successfully', _l('layoff_management')));
        redirect(admin_url('hrm/layoff'));
    }

    public function policies()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('policies_qa');
        $data['policies'] = $this->hrm_model->get_policies();
        $this->load->view('policies', $data);
    }

    public function policy()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) {
            echo json_encode(['success' => false]);
            return;
        }
        $data = $this->input->post();
        $id = $data['id'] ?? '';
        unset($data['id']);
        $data['is_faq'] = !empty($data['is_faq']) ? 1 : 0;
        if ($id) {
            $this->hrm_model->update_policy($data, $id);
            echo json_encode(['success' => true, 'message' => _l('updated_successfully', _l('policies_qa'))]);
        } else {
            $this->hrm_model->add_policy($data);
            echo json_encode(['success' => true, 'message' => _l('added_successfully', _l('policies_qa'))]);
        }
    }

    public function delete_policy($id)
    {
        if (!$id || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/policies'));
        $this->hrm_model->delete_policy($id);
        set_alert('success', _l('deleted', _l('policies_qa')));
        redirect(admin_url('hrm/policies'));
    }

    public function get_policy($id)
    {
        if (!$this->input->is_ajax_request() || !has_permission('hrm', '', 'view')) {
            echo json_encode(null);
            return;
        }
        $p = $this->db->get_where(db_prefix().'hrm_policies', ['id' => $id])->row_array();
        echo json_encode($p ?: null);
    }

    public function reports($type = '')
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('hr_reports');
        if ($type == 'layoff_staff') {
            $data['report_data'] = $this->hrm_model->get_layoff_records();
            $data['report_type'] = 'layoff_staff';
            $this->load->view('reports_detail', $data);
        } elseif ($type == 'salary_changes') {
            $data['report_data'] = $this->hrm_model->get_report_salary_changes();
            $data['report_type'] = 'salary_changes';
            $this->load->view('reports_detail', $data);
        } elseif ($type == 'seniority_changes') {
            $data['report_data'] = $this->hrm_model->get_report_seniority_changes();
            $data['report_type'] = 'seniority_changes';
            $this->load->view('reports_detail', $data);
        } elseif ($type == 'monthly_changes') {
            $data['report_data'] = $this->hrm_model->get_report_monthly_changes();
            $data['report_type'] = 'monthly_changes';
            $this->load->view('reports_detail', $data);
        } elseif ($type == 'qualifications') {
            $data['report_data'] = $this->hrm_model->get_report_qualifications();
            $data['report_type'] = 'qualifications';
            $this->load->view('reports_detail', $data);
        } else {
            $this->load->view('reports', $data);
        }
    }

    public function assets()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('asset_management');
        $data['assets'] = $this->hrm_model->get_assets();
        $data['staff_list'] = $this->hrm_model->get_staff('', ['active' => 1]);
        $this->load->view('assets', $data);
    }

    public function asset()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/assets'));
        $data = $this->input->post();
        $id = $data['id'] ?? '';
        unset($data['id']);
        if ($id) {
            $this->hrm_model->update_asset($data, $id);
            set_alert('success', _l('updated_successfully', _l('my_assets')));
        } else {
            $this->hrm_model->add_asset($data);
            set_alert('success', _l('added_successfully', _l('my_assets')));
        }
        redirect(admin_url('hrm/assets'));
    }

    public function get_asset($id)
    {
        if (!$this->input->is_ajax_request() || !has_permission('hrm', '', 'view')) {
            echo json_encode(null);
            return;
        }
        $a = $this->db->get_where(db_prefix().'hrm_assets', ['id' => $id])->row_array();
        echo json_encode($a ?: null);
    }

    public function delete_asset($id)
    {
        if (!$id || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/assets'));
        $this->hrm_model->delete_asset($id);
        set_alert('success', _l('deleted', _l('my_assets')));
        redirect(admin_url('hrm/assets'));
    }

    public function asset_assign_to_member()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/staff_infor'));
        $asset_id = (int)$this->input->post('asset_id');
        $assigned_to = (int)$this->input->post('assigned_to');
        if (!$asset_id || !$assigned_to) redirect(admin_url('hrm/staff_infor'));
        $this->hrm_model->update_asset([
            'assigned_to' => $assigned_to,
            'assigned_date' => to_sql_date($this->input->post('assigned_date'))
        ], $asset_id);
        set_alert('success', _l('updated_successfully', _l('my_assets')));
        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('hrm/member/'.$assigned_to));
    }

    public function helpdesk()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('hr_helpdesk');
        $data['tickets'] = $this->hrm_model->get_helpdesk_tickets();
        $this->load->view('helpdesk', $data);
    }

    public function helpdesk_add()
    {
        if (!$this->input->post()) redirect(admin_url('hrm/helpdesk'));
        $data = $this->input->post();
        $data['staff_id'] = get_staff_user_id();
        $this->hrm_model->add_helpdesk_ticket($data);
        set_alert('success', _l('added_successfully', _l('hr_helpdesk')));
        redirect(admin_url('hrm/helpdesk'));
    }

    public function get_helpdesk_ticket($id)
    {
        if (!$this->input->is_ajax_request()) { echo json_encode(null); return; }
        $t = $this->db->get_where(db_prefix().'hrm_helpdesk_tickets', ['id' => $id])->row_array();
        echo json_encode($t ?: null);
    }

    public function performance()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('performance_management');
        $data['reviews'] = $this->hrm_model->get_performance_reviews();
        $data['staff_list'] = $this->hrm_model->get_staff('', ['active' => 1]);
        $this->load->view('performance', $data);
    }

    public function performance_review_add()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/performance'));
        $this->hrm_model->add_performance_review($this->input->post());
        set_alert('success', _l('added_successfully', _l('performance_review')));
        redirect(admin_url('hrm/performance'));
    }

    public function learning()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('learning_paths');
        $data['courses'] = $this->hrm_model->get_learning_courses();
        $data['staff_list'] = $this->hrm_model->get_staff('', ['active' => 1]);
        $this->load->view('learning', $data);
    }

    public function learning_course_add()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/learning'));
        $this->hrm_model->add_learning_course($this->input->post());
        set_alert('success', _l('added_successfully', _l('course')));
        redirect(admin_url('hrm/learning'));
    }

    public function learning_enroll()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/learning'));
        $this->hrm_model->add_staff_course($this->input->post());
        set_alert('success', _l('added_successfully', _l('course')));
        redirect(admin_url('hrm/learning'));
    }

    public function engagement()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('employee_engagement');
        $data['surveys'] = $this->hrm_model->get_engagement_surveys();
        $data['oneonone'] = $this->hrm_model->get_one_on_one_notes();
        $data['staff_list'] = $this->hrm_model->get_staff('', ['active' => 1]);
        $this->load->view('engagement', $data);
    }

    public function engagement_survey_add()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/engagement'));
        $this->hrm_model->add_engagement_survey($this->input->post());
        set_alert('success', _l('added_successfully', _l('survey')));
        redirect(admin_url('hrm/engagement'));
    }

    public function oneonone_add()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/engagement'));
        $data = $this->input->post();
        $data['manager_id'] = get_staff_user_id();
        $this->hrm_model->add_one_on_one_note($data);
        set_alert('success', _l('added_successfully', _l('one_on_one_note')));
        redirect(admin_url('hrm/engagement'));
    }

    public function documents()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('hr_documents');
        $data['documents'] = $this->hrm_model->get_hr_documents();
        $this->load->view('documents', $data);
    }

    public function document_add()
    {
        if (!$this->input->post() || !has_permission('hrm', '', 'edit')) redirect(admin_url('hrm/documents'));
        $data = $this->input->post();
        if (!empty($_FILES['document_file']['name']) && $_FILES['document_file']['error'] == 0) {
            $path = HRM_MODULE_UPLOAD_FOLDER . 'documents/';
            if (!is_dir($path)) mkdir($path, 0755, true);
            $filename = unique_filename($path, $_FILES['document_file']['name']);
            if (move_uploaded_file($_FILES['document_file']['tmp_name'], $path . $filename)) {
                $data['file_name'] = $_FILES['document_file']['name'];
                $data['file_path'] = 'modules/hrm/uploads/documents/' . $filename;
            }
        }
        $this->hrm_model->add_hr_document($data);
        set_alert('success', _l('added_successfully', _l('hr_documents')));
        redirect(admin_url('hrm/documents'));
    }

    public function custom_reports()
    {
        if (!has_permission('hrm', '', 'view')) access_denied('hrm');
        $data['title'] = _l('custom_reports');
        $this->load->view('custom_reports', $data);
    }

    public function custom_report_generate()
    {
        if (!has_permission('hrm', '', 'view')) { echo ''; return; }
        $type = $this->input->post('report_type') ?: $this->input->get('report_type');
        $format = $this->input->post('format') ?: $this->input->get('format') ?: 'html';
        $data = [];
        switch ($type) {
            case 'staff_by_department':
            case 'staff_by_position':
                $data['rows'] = $this->db->get(db_prefix().'staff')->result_array();
                break;
            case 'contracts_summary':
                $data['rows'] = $this->db->get(db_prefix().'staff_contract')->result_array();
                break;
            case 'training_summary':
                $data['rows'] = $this->hrm_model->get_staff_trainings();
                break;
            default:
                $data['rows'] = $this->db->get(db_prefix().'staff')->result_array();
        }
        $data['report_type'] = $type;
        if ($format == 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="hrm_report_' . $type . '_' . date('Y-m-d') . '.csv"');
            $out = fopen('php://output', 'w');
            if (!empty($data['rows'])) {
                fputcsv($out, array_keys($data['rows'][0]));
                foreach ($data['rows'] as $r) fputcsv($out, $r);
            }
            fclose($out);
            exit;
        }
        $this->load->view('custom_report_output', $data);
    }

    public function profile($id = '')
    {
	
		\modules\hrm\core\Apiinit::ease_of_mind('hrm');
		\modules\hrm\core\Apiinit::the_da_vinci_code('hrm');
        $this->load->model('departments_model');
        if ($id == '') {
            $id = get_staff_user_id();
        }

        $member = $this->staff_model->get($id);
        if (!$member) {
            blank_page('Staff Member Not Found', 'danger');
        }
        $data['member']            = $member;
        $title                     = $member->firstname . ' ' . $member->lastname;
        $data['staff_departments'] = $this->departments_model->get_staff_departments($member->staffid);


        $data['staff_departments'] = $this->departments_model->get_staff_departments($data['member']->staffid);
        $data['departments']       = $this->departments_model->get();


        $this->load->view('hrm/profile', $data);
    }


}
