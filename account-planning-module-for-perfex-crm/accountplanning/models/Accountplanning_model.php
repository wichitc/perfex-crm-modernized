<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Accountplanning_model extends App_Model
{
	public function __construct()
    {
        parent::__construct();
    }

     public function add($data)
    {
    	if(isset($data['financial'])){
	    	 $data['financial'] = explode ( ',', $data['financial']);
	    	 $financial_col = ['year','revenue','sales_spent','traffic','loss'];
	    	 $row = [];
	    	 $financial = [];
	    	 for ($i=0; $i < count($data['financial']); $i++) {
	    	 		$row[] = $data['financial'][$i];
	    	 	if((($i+1)%5) == 0){
	    	 		$financial[] = array_combine($financial_col, $row);
	    	 		$row = [];
	    	 	}
	    	 }
	    	unset($data['financial']);
    	}
        if(isset($data['marketing_activities'])){
             $data['marketing_activities'] = explode ( ',', $data['marketing_activities']);
             $marketing_activities_col = ['item','reference'];
             $row = [];
             $marketing_activities = [];
             for ($i=0; $i < count($data['marketing_activities']); $i++) {
                    $row[] = $data['marketing_activities'][$i];
                if((($i+1)%2) == 0){
                    $marketing_activities[] = array_combine($marketing_activities_col, $row);
                    $row = [];
                }
             }
            unset($data['marketing_activities']);
        }
    	if(isset($data['revenue'])){
        	unset($data['revenue']);
    	}
    	if(isset($data['sales_spent'])){
        	unset($data['sales_spent']);
    	}
    	if(isset($data['traffic'])){
        	unset($data['traffic']);
    	}
    	if(isset($data['loss'])){
        	unset($data['loss']);
    	}

        if(isset($data['date'])){
            $data['date'] = to_sql_date($data['date']);
        }
        if (!isset($data['status']) || empty($data['status'])) {
            $data['status'] = get_option('accountplanning_default_status', 'draft');
        }
        $data['latest_update'] = date('Y-m-d H:i:s');
        $this->db->insert('tblaccountplanning', $data);

        $userid = $this->db->insert_id();
        if ($userid) {
            if (function_exists('accountplanning_trigger_webhooks')) {
                accountplanning_trigger_webhooks('plan.created', $userid, ['subject' => $data['subject'] ?? '', 'client_id' => $data['client_id'] ?? 0]);
            }
        	if(isset($financial)){
        		foreach ($financial as $value) {
        			$value['accountplanning_id'] = $userid;
        			$this->db->insert('tblaccountplanning_financial', $value);
        		}
        	}
            if(isset($marketing_activities)){
                foreach ($marketing_activities as $value) {
                    $value['accountplanning_id'] = $userid;
                    $this->db->insert('tblaccountplanning_marketing_activities', $value);
                }
            }
            $log = 'ID: ' . $userid;

            if ($log == '' && isset($contact_id)) {
                $log = get_contact_full_name($contact_id);
            }

            $isStaff = null;
            if (!is_client_logged_in() && is_staff_logged_in()) {
                $log .= ', From Staff: ' . get_staff_user_id();
                $isStaff = get_staff_user_id();
            }

            log_activity('New Account planning Created [' . $log . ']', $isStaff);
        }

        return $userid;
    }

    public function delete($id)
    {
        if (function_exists('accountplanning_trigger_webhooks')) {
            accountplanning_trigger_webhooks('plan.deleted', (int) $id, []);
        }
        $this->db->where('id', $id);
        $this->db->delete('tblaccountplanning');
        if ($this->db->affected_rows() > 0) {
            log_activity('Account Planning Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function bulk_update_status($ids, $status)
    {
        if (empty($ids) || !is_array($ids)) {
            return 0;
        }
        $this->db->where_in('id', $ids);
        $this->db->update('tblaccountplanning', ['status' => $status, 'new_update' => date('Y-m-d H:i:s')]);
        $affected = $this->db->affected_rows();
        if ($affected > 0) {
            log_activity('Account Planning bulk status update [' . $affected . ' plans to ' . $status . ']');
            if (function_exists('accountplanning_trigger_webhooks')) {
                foreach ($ids as $id) {
                    accountplanning_trigger_webhooks('plan.updated', (int) $id, ['status' => $status]);
                }
            }
        }
        return $affected;
    }
    public function update_due_diligence($id, $data)
    {
        if(isset($data['financial'])){
	    	 $data['financial'] = explode ( ',', $data['financial']);
	    	 $financial_col = ['year','revenue','traffic','sales_spent','loss'];
	    	 $row = [];
	    	 $financial = [];
	    	 for ($i=0; $i < count($data['financial']); $i++) {
	    	 		$row[] = $data['financial'][$i];
	    	 	if((($i+1)%5) == 0){
	    	 		$financial[] = array_combine($financial_col, $row);
	    	 		$row = [];
	    	 	}
	    	 }
	    	unset($data['financial']);
    	}
        if(isset($data['marketing_activities'])){
             $data['marketing_activities'] = explode ( ',', $data['marketing_activities']);
             $marketing_activities_col = ['item','reference'];
             $row = [];
             $marketing_activities = [];
             for ($i=0; $i < count($data['marketing_activities']); $i++) {
                    $row[] = $data['marketing_activities'][$i];
                if((($i+1)%2) == 0){
                    $marketing_activities[] = array_combine($marketing_activities_col, $row);
                    $row = [];
                }
             }
            unset($data['marketing_activities']);
        }
    	$data['new_update'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('tblaccountplanning', $data);

        $this->db->where('accountplanning_id', $id);
        $this->db->delete('tblaccountplanning_financial');
    	if(isset($financial)){
    		foreach ($financial as $value) {
    			$value['accountplanning_id'] = $id;
    			$this->db->insert('tblaccountplanning_financial', $value);
    		}
    	}

        $this->db->where('accountplanning_id', $id);
        $this->db->delete('tblaccountplanning_marketing_activities');
        if(isset($marketing_activities)){
                foreach ($marketing_activities as $value) {
                    $value['accountplanning_id'] = $id;
                    $this->db->insert('tblaccountplanning_marketing_activities', $value);
                }
            }
    	log_activity('Account Planning updated [ID: ' . $id . ']');
        if (function_exists('accountplanning_trigger_webhooks')) {
            accountplanning_trigger_webhooks('plan.updated', $id, []);
        }
        return true;
    }
	public function update_team_information($id, $data)
    {
    	if(isset($data['DataTables_Table_0_length'])){
    		unset($data['DataTables_Table_0_length']);
    	}
    	if(isset($data['DataTables_Table_1_length'])){
    		unset($data['DataTables_Table_1_length']);
    	}
    	$this->db->where('accountplanning_id', $id);
        $this->db->delete('tblaccountplanning_team');
        if(isset($data['client_team'])){
	    	foreach ($data['client_team'] as $value) {
	    	 	$this->db->insert('tblaccountplanning_team', [
	    	 		'accountplanning_id' => $id,
	    	 		'rel_id' => $value,
	    	 		'rel_type' => 'client_team'
	    	 	]);
	    	 } 
    	}
    	if(isset($data['pmax_team'])){
	    	foreach ($data['pmax_team'] as $value) {
	    	 	$this->db->insert('tblaccountplanning_team', [
	    	 		'accountplanning_id' => $id,
	    	 		'rel_id' => $value,
	    	 		'rel_type' => 'pmax_team'
	    	 	]);
	    	 } 
    	}

        $new_update['new_update'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('tblaccountplanning', $new_update);

    	log_activity('Account Planning updated [ID: ' . $id . ']');
        if (function_exists('accountplanning_trigger_webhooks')) {
            accountplanning_trigger_webhooks('plan.updated', $id, []);
        }
        return true;
    }
    public function update_service_ability_offering($id, $data)
    {
        if(isset($data['service_ability_offering'])){
             $data['service_ability_offering'] = explode ( ',', $data['service_ability_offering']);
             $service_ability_offering_col = ['service','potential','scale','convert','prioritization'];
             $row = [];
             $service_ability_offering = [];
             for ($i=0; $i < count($data['service_ability_offering']); $i++) {
                    $row[] = $data['service_ability_offering'][$i];
                if((($i+1)%5) == 0){
                    $service_ability_offering[] = array_combine($service_ability_offering_col, $row);
                    $row = [];
                }
             }
            unset($data['service_ability_offering']);
        }

        if(isset($data['current_service'])){
             $data['current_service'] = explode ( ',', $data['current_service']);
             $current_service_col = ['name','potential'];
             $row = [];
             $current_service = [];
             for ($i=0; $i < count($data['current_service']); $i++) {
                    $row[] = $data['current_service'][$i];
                if((($i+1)%2) == 0){
                    $current_service[] = array_combine($current_service_col, $row);
                    $row = [];
                }
             }
            unset($data['current_service']);
        }

        $data['new_update'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('tblaccountplanning', $data);

        $this->db->where('accountplanning_id', $id);
        $this->db->delete('tblaccountplanning_current_service');
        if(isset($current_service)){
            foreach ($current_service as $value) {
                $value['accountplanning_id'] = $id;
                $this->db->insert('tblaccountplanning_current_service', $value);
            }
        }

        $this->db->where('accountplanning_id', $id);
        $this->db->delete('tblaccountplanning_service_ability_offering');
        if(isset($service_ability_offering)){
            foreach ($service_ability_offering as $value) {
                $value['accountplanning_id'] = $id;
                $this->db->insert('tblaccountplanning_service_ability_offering', $value);
            }
        }

        log_activity('Account Planning updated [ID: ' . $id . ']');
        if (function_exists('accountplanning_trigger_webhooks')) {
            accountplanning_trigger_webhooks('plan.updated', $id, ['subject' => $data['subject'] ?? '']);
        }
        return true;
    }

    public function update_planning($id, $data)
    {
        $todo_list = [];
        if(isset($data['todo_list'])){
             $data['todo_list'] = explode ( ',', $data['todo_list']);
             $todo_list_col = ['objective','items','action_needed','prioritization','pic','deadline','status','task_id','option'];
             $row = [];
             for ($i=0; $i < count($data['todo_list']); $i++) {
                    $row[] = $data['todo_list'][$i];
                if((($i+1)%9) == 0){
                    $todo_list[] = array_combine($todo_list_col, $row);
                    $row = [];
                }
             }
            unset($data['todo_list']);
        }
        if(isset($data['date'])){
            $data['date'] = to_sql_date($data['date']);
        }
        $data['data_tree']     = nl2br($data['data_tree']);
        $data['objectives']     = nl2br($data['objectives']);
        $data['threat']     = nl2br($data['threat']);
        $data['opportunity']     = nl2br($data['opportunity']);
        $data['criteria_to_success']     = nl2br($data['criteria_to_success']);
        $data['constraints']     = nl2br($data['constraints']);

        $data['revenue_next_year'] = reformat_currency($data['revenue_next_year']);
        if(isset($data['DataTables_Table_0_length'])){
            unset($data['DataTables_Table_0_length']);
        }
        $data['new_update'] = date('Y-m-d H:i:s');
        if (isset($data['status']) && $data['status'] == 'completed') {
            $data['approved_by'] = get_staff_user_id();
            $data['approved_date'] = date('Y-m-d H:i:s');
        }

        foreach ($todo_list as $key => $value) {
            if($value['option'] != ''){
                $convertToTask = null;
                if (function_exists('accountplanning_plan_to_perfex_status') && $this->db->table_exists(db_prefix() . 'tasks')) {
                    $existing = $this->db->get_where(db_prefix() . 'accountplanning_task', ['id' => $value['task_id']])->row();
                    $convertToTask = $existing && !empty($existing->convert_to_task) ? (int) $existing->convert_to_task : null;
                }
                $data_row = [];
                $data_row['action_needed'] = $value['action_needed'];
                $data_row['prioritization'] = $value['prioritization'];
                $data_row['pic'] = $value['pic'];
                $data_row['deadline'] = to_sql_date($value['deadline']);
                $data_row['status'] = $value['status'];
                $data_row['item'] = $value['items'];
                $data_row['objective'] = $value['objective'];
                $this->db->where('id',$value['task_id']);
                $this->db->update('tblaccountplanning_task', $data_row);
                if ($convertToTask && function_exists('accountplanning_plan_to_perfex_status')) {
                    $perfexStatus = accountplanning_plan_to_perfex_status($value['status']);
                    $this->db->where('id', $convertToTask);
                    $this->db->update(db_prefix() . 'tasks', ['status' => $perfexStatus]);
                }
            }else{
                $data_row = [];
                $data_row['items_id'] = 0;
                $data_row['accountplanning_id'] = $id;
                $data_row['item'] = $value['items'];
                $data_row['objective'] = $value['objective'];
                $data_row['action_needed'] = $value['action_needed'];
                $data_row['prioritization'] = $value['prioritization'];
                $data_row['pic'] = $value['pic'];
                $data_row['deadline'] = to_sql_date($value['deadline']);
                $data_row['status'] = $value['status'];
                $this->db->insert('tblaccountplanning_task', $data_row);
            }

            handle_accountplanning($id);
        }

        $this->db->where('id', $id);
        $this->db->update('tblaccountplanning', $data);

        log_activity('Account Planning updated [ID: ' . $id . ']');
        if (function_exists('accountplanning_trigger_webhooks')) {
            accountplanning_trigger_webhooks('plan.updated', $id, $data);
        }
        return true;

    }

    public function get_health_score($id)
    {
        $plan = $this->get($id);
        if (!$plan) {
            return 0;
        }
        $plan = is_array($plan) ? (object) $plan : $plan;
        $score = 0;
        $max = 100;
        if (!empty($plan->subject)) { $score += 10; } else { $score += 0; }
        if (!empty($plan->objectives)) { $score += 15; } else { $score += 0; }
        if (!empty($plan->revenue_next_year) && $plan->revenue_next_year > 0) { $score += 15; } else { $score += 0; }
        if (!empty($plan->client_status)) { $score += 10; } else { $score += 0; }
        $this->db->where('accountplanning_id', (int) $id);
        $tasks = $this->db->get(db_prefix() . 'accountplanning_task')->result_array();
        $totalTasks = count($tasks);
        if ($totalTasks > 0) {
            $doneTasks = count(array_filter($tasks, function($t) { return isset($t['status']) && $t['status'] == 'Complete'; }));
            $score += (int)(30 * $doneTasks / $totalTasks);
        } else {
            $score += 15;
        }
        $daysSinceUpdate = !empty($plan->latest_update) ? (time() - strtotime($plan->latest_update)) / 86400 : 999;
        if ($daysSinceUpdate <= 7) { $score += 20; } elseif ($daysSinceUpdate <= 30) { $score += 10; } else { $score += 0; }
        return min(100, max(0, (int) $score));
    }

    public function get_goals($id)
    {
        $this->db->where('accountplanning_id', (int) $id);
        return $this->db->get(db_prefix() . 'accountplanning_goals')->result_array();
    }

    public function add_goal($accountplanning_id, $data)
    {
        $data['accountplanning_id'] = (int) $accountplanning_id;
        $data['datecreated'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'accountplanning_goals', $data);
        return $this->db->insert_id();
    }

    public function update_goal($id, $data)
    {
        $this->db->where('id', (int) $id);
        $this->db->update(db_prefix() . 'accountplanning_goals', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_goal($id)
    {
        $this->db->where('id', (int) $id);
        $this->db->delete(db_prefix() . 'accountplanning_goals');
        return $this->db->affected_rows() > 0;
    }

    public function get($id = '', $where = [])
    {
        $this->db->select('*, (select company from tblclients where tblclients.userid = client_id) as company, (select company from tblclients where tblclients.userid = client_id) as client_name');


        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('tblaccountplanning.id', $id);
            $account = $this->db->get('tblaccountplanning')->row();
            $account->attachments = $this->get_attachments($id);
            return $account;

        }
        return $this->db->get('tblaccountplanning')->result_array();
    }

    public function get_financial($id = '')
    {
        $this->db->select('*');
        if (is_numeric($id)) {
            $this->db->where('accountplanning_id', $id);
            return $this->db->get('tblaccountplanning_financial')->result_array();
        }
        return $this->db->get('tblaccountplanning_financial')->result_array();
    }

    public function get_service_ability_offering($id = '')
    {
        $this->db->select('*');
        if (is_numeric($id)) {
            $this->db->where('accountplanning_id', $id);
            return $this->db->get('tblaccountplanning_service_ability_offering')->result_array();
        }
        return $this->db->get('tblaccountplanning_service_ability_offering')->result_array();
    }
    public function get_current_service($id = '')
    {
        $this->db->select('*');
        if (is_numeric($id)) {
            $this->db->where('accountplanning_id', $id);
            return $this->db->get('tblaccountplanning_current_service')->result_array();
        }
        return $this->db->get('tblaccountplanning_current_service')->result_array();
    }
    public function get_marketing_activities($id = '')
    {
        $this->db->select('*');
        if (is_numeric($id)) {
            $this->db->where('accountplanning_id', $id);
            return $this->db->get('tblaccountplanning_marketing_activities')->result_array();
        }
        return $this->db->get('tblaccountplanning_marketing_activities')->result_array();
    }

    public function get_pmax_team($id = '')
    {
        $this->db->select('tblaccountplanning_team.id,rel_id,tblstaff.email,tblstaff.firstname,tblstaff.lastname,tblstaff.facebook,tblstaff.phonenumber,tblstaff.skype,tblstaff.linkedin,tblstaff.profile_image, tbldepartments.name as department, (select name from tblroles where tblroles.roleid = role) as title');
        $this->db->join('tblstaff', 'tblstaff.staffid = tblaccountplanning_team.rel_id', 'left');
        $this->db->join('tblstaff_departments', 'tblstaff_departments.staffid = tblaccountplanning_team.rel_id', 'left');
        $this->db->join('tbldepartments','tbldepartments.departmentid = tblstaff_departments.departmentid', 'left');
        $this->db->where('rel_type', 'pmax_team');
        if (is_numeric($id)) {
            $this->db->where('accountplanning_id', $id);
            return $this->db->get('tblaccountplanning_team')->result_array();
        }
        return $this->db->get('tblaccountplanning_team')->result_array();
    }
    public function get_client_team($id = '')
    {
        $this->db->select('*');
        $this->db->join('tblcontacts', 'tblcontacts.id = tblaccountplanning_team.rel_id', 'left');
        
        $this->db->where('rel_type', 'client_team');
        if (is_numeric($id)) {
            $this->db->where('accountplanning_id', $id);
            return $this->db->get('tblaccountplanning_team')->result_array();
        }
        return $this->db->get('tblaccountplanning_team')->result_array();
    }

    public function get_objectives($id = '')
    {
        $this->db->select('*');
        if (is_numeric($id)) {
            $this->db->where('accountplanning_id', $id);
            return $this->db->get('tblaccountplanning_objective')->result_array();
        }
        return $this->db->get('tblaccountplanning_objective')->result_array();
    }

    public function get_items($id = '')
    {
        $this->db->select('*');
        if (is_numeric($id)) {
            $this->db->where('accountplanning_id', $id);
            return $this->db->get('tblaccountplanning_items')->result_array();
        }
        return $this->db->get('tblaccountplanning_items')->result_array();
    }

    public function add_objective($id ,$data)
    {
        if($data['objective_name'] != ''){
            $data['accountplanning_id'] = $id;
            $data['name'] = $data['objective_name'];
            unset($data['objective_name']);
            $data['datecreated'] = date('Y-m-d H:i:s');
            $this->db->insert('tblaccountplanning_objective', $data);

            log_activity('Account Planning updated [ID: ' . $id . ']');
            return true;
        }else {
            return false; 
        }
    }
    public function add_item($id ,$data)
    {
        if($data['items_name'] != '' && $data['objective'] != ''){
            $data['accountplanning_id'] = $id;
            $data['objective_id'] = $data['objective'];
            unset($data['objective']);
            $data['name'] = $data['items_name'];
            unset($data['items_name']);
            $data['datecreated'] = date('Y-m-d H:i:s');
            $this->db->insert('tblaccountplanning_items', $data);

            return true;
        }else{
            return false;
        }
    }
    public function add_task($id ,$data)
    {
        if($data['task_name'] != '' && $data['items_id'] != ''){
            $data['action_needed'] =  $data['task_name'];
            $data['deadline'] = to_sql_date($data['deadline']);
            unset($data['task_name']);

            $this->db->insert('tblaccountplanning_task', $data);
            return true;
        }else{
            return false;
        }
    }

    public function update_objective($id ,$data)
    {
        if($data['objective_name'] != ''){
            $data['name'] = $data['objective_name'];
            unset($data['objective_name']);
            $this->db->where('id', $id);
            $this->db->update('tblaccountplanning_objective', $data);

            log_activity('Objective updated [ID: ' . $id . ']');
            return true;
        }else {
            return false; 
        }
    }
    public function update_item($id ,$data)
    {
        if($data['items_name'] != '' && $data['objective'] != ''){
            $data['objective_id'] = $data['objective'];
            unset($data['objective']);
            $data['name'] = $data['items_name'];
            unset($data['items_name']);

            $this->db->where('id', $id);
            $this->db->update('tblaccountplanning_items', $data);

            return true;
        }else{
            return false;
        }
    }
    public function update_task($id ,$data)
    {
        if($data['task_name'] != '' && $data['items_id'] != ''){
            $data['action_needed'] =  $data['task_name'];
            $data['deadline'] = to_sql_date($data['deadline']);
            unset($data['task_name']);

            $this->db->where('id', $id);
            $this->db->update('tblaccountplanning_task', $data);
            return true;
        }else{
            return false;
        }
    }

    public function delete_objective($id = '', $accountplanning_id = '')
    {
        if($id != '' || $accountplanning_id != ''){
            if($accountplanning_id != ''){
                $this->db->where('accountplanning_id', $accountplanning_id);
            }
            if($id != ''){
                $this->db->where('id', $id);
            }
            $this->db->delete('tblaccountplanning_objective');

            $this->delete_item('', $id);
            return true;
        }else{
            return false;
        }
    }
    public function delete_item($id = '', $objective_id = '')
    {
        if($id != '' || $objective_id != ''){
            if($objective_id != ''){
                $this->db->where('objective_id', $objective_id);
            }
            if($id != ''){
                $this->db->where('id', $id);
            }
            $this->db->delete('tblaccountplanning_items');

            $this->delete_task('', $id);

            return true;
        }else{
            return false;
        }

    }

    public function delete_task($id = '', $items_id = '')
    {   if($id != '' || $items_id != ''){
            if($items_id != ''){
                $this->db->where('items_id', $items_id);
            }
            if($id != ''){
                $this->db->where('id', $id);
            }
            $this->db->delete('tblaccountplanning_task');
                return true;
        }else{
            return false;
        }
    }
    public function get_todo_list($id)
    {


        $this->db->where('accountplanning_id', $id);
        $todo_list = $this->db->get('tblaccountplanning_task')->result_array();
        if(count($todo_list) > 0){
            foreach ($todo_list as $key => $value) {
                $todo_list[$key]['deadline'] = _d($value['deadline']);
                if($value['convert_to_task'] == 0){
                    if (has_permission('accountplanning', '', 'edit')) {
                    $todo_list[$key]['button'] =/*'<a href="#" class="btn btn-default btn-xs" onclick="convert_to_task('.$id.', '.$value['id'].'); return false;">'. _l('convert_to_task') .'</a> '*/icon_btn('#', 'fa fa-external-link', 'btn-success', ['data-subject' => $value['item'],'data-description' => $value['action_needed'],'data-priority' => $value['prioritization'],'data-deadline' => _d($value['deadline']), 'data-pic' => $value['pic'], "data-toggle"=>"tooltip", "title"=>_l('convert_to_task'), "onclick" => "convert_to_task(this,".$id.", ".$value['id']."); return false;"]).' '.icon_btn('accountplanning/delete_task/' . $value['id'].'/'.$id, 'fa fa-remove', 'btn-danger _delete', ["data-toggle"=>"tooltip", "title"=>_l('delete_task')]);
                    }
                }else{
                    if (has_permission('accountplanning', '', 'edit')) {
                    $todo_list[$key]['button'] =/*'<a href="'.admin_url('tasks/view/'.$value['convert_to_task']).'" onclick="init_task_modal(\''.$value['convert_to_task'].'\'); return false;" class="btn btn-default btn-xs">'. _l('view_task') .'</a> '*/icon_btn('#', 'fa fa-eye', 'btn-success', ["data-toggle"=>"tooltip", "title"=>_l('view_task'), "onclick" => "init_task_modal('".$value['convert_to_task']."'); return false;"]).' '.icon_btn('accountplanning/delete_task/' . $value['id'].'/'.$id, 'fa fa-remove', 'btn-danger _delete', ["data-toggle"=>"tooltip", "title"=>_l('delete_task')]);
                    }
                }
            }
            return $todo_list;
        }else{
            $data_todo_list = [];
            $data_todo_list[0]['objective'] = '';
            $data_todo_list[0]['item'] = '';
            $data_todo_list[0]['action_needed'] = '';
            $data_todo_list[0]['prioritization'] = '';
            $data_todo_list[0]['pic'] = '';
            $data_todo_list[0]['deadline'] = '';
            $data_todo_list[0]['status'] = '';
            $data_todo_list[0]['id'] = '';
            $data_todo_list[0]['button'] = '';
            return $data_todo_list;
        }
    }

    function get_accountplanning_tabs($id)
    {
        $accountplanning_tabs = [
          [
            'name'    => 'due_diligence',
            'url'     => admin_url('accountplanning/view/' . $id . '?group=due_diligence'),
            'icon'    => 'fa fa-user-circle',
            'lang'    => _l('due_diligence'),
            'visible' => true,
            'order'   => 1,
        ],
        [
            'name'    => 'team_information',
            'url'     => admin_url('accountplanning/view/' . $id . '?group=team_information'),
            'icon'    => 'fa fa-users',
            'lang'    => _l('team_information'),
            'visible' => true,
            'order'   => 2,
        ],
          [
            'name'    => 'service_ability_offering',
            'url'     => admin_url('accountplanning/view/' . $id . '?group=service_ability_offering'),
            'icon'    => 'fa fa-sticky-note',
            'lang'    => _l('service_ability_offering'),
            'visible' => true,
            'order'   => 3,
        ],
          [
            'name'    => 'planning',
            'url'     => admin_url('accountplanning/view/' . $id . '?group=planning'),
            'icon'    => 'fa fa-area-chart',
            'lang'    => _l('planning'),
            'visible' => true,
            'order'   => 4,
        ],
          [
            'name'    => 'activity',
            'url'     => admin_url('accountplanning/view/' . $id . '?group=activity'),
            'icon'    => 'fa fa-history',
            'lang'    => _l('utility_activity_log'),
            'visible' => true,
            'order'   => 5,
        ],
          [
            'name'    => 'relations',
            'url'     => admin_url('accountplanning/view/' . $id . '?group=relations'),
            'icon'    => 'fa fa-link',
            'lang'    => _l('ap_relations'),
            'visible' => true,
            'order'   => 6,
        ],
          [
            'name'    => 'meeting_notes',
            'url'     => admin_url('accountplanning/view/' . $id . '?group=meeting_notes'),
            'icon'    => 'fa fa-phone',
            'lang'    => _l('ap_meeting_notes'),
            'visible' => true,
            'order'   => 7,
        ],
          [
            'name'    => 'competitors',
            'url'     => admin_url('accountplanning/view/' . $id . '?group=competitors'),
            'icon'    => 'fa fa-trophy',
            'lang'    => _l('ap_competitors'),
            'visible' => true,
            'order'   => 8,
        ]
      ];
        return $accountplanning_tabs;
    }
    public function copy($accountplanning_id, $data)
    {

        $account   = $this->get($accountplanning_id);
        $_new_data = [];
        foreach ($account as $key => $value) {
           $_new_data[$key] = $value;
        }
        if(isset($_new_data['attachments'])){
            $attachments = $_new_data['attachments'];
            unset($_new_data['attachments']);
        }
        unset($_new_data['id']);
        unset($_new_data['company']);
        unset($_new_data['client_name']);
        $_new_data['client_id'] = $data['client_id'];
        $_new_data['date'] = to_sql_date($data['date']);
        $_new_data['subject'] = $data['subject'];
        $_new_data['status'] = isset($_new_data['status']) ? $_new_data['status'] : 'draft';
        $_new_data['latest_update'] = date('Y-m-d H:i:s');

        $this->db->insert('tblaccountplanning', $_new_data);
        $id = $this->db->insert_id();
        if ($id) {
            $financial = $this->get_financial($accountplanning_id);
            if(isset($financial)){
                foreach ($financial as $value) {
                    unset($value['id']);
                    $value['accountplanning_id'] = $id;
                    $this->db->insert('tblaccountplanning_financial', $value);
                }
            }
            $marketing_activities = $this->get_marketing_activities($accountplanning_id);
            if(isset($marketing_activities)){
                foreach ($marketing_activities as $value) {
                    unset($value['id']);
                    $value['accountplanning_id'] = $id;
                    $this->db->insert('tblaccountplanning_marketing_activities', $value);
                }
            }

            $client_team = $this->get_client_team($accountplanning_id);
            if(isset($client_team)){
                foreach ($client_team as $value) {
                    $row = [];
                    $row['accountplanning_id'] = $id;
                    $row['rel_id'] = $value['rel_id'];
                    $row['rel_type'] = 'client_team';
                    $this->db->insert('tblaccountplanning_team', $row);
                }
            }
            $pmax_team = $this->get_pmax_team($accountplanning_id);
            if(isset($pmax_team)){
                foreach ($pmax_team as $value) {
                    $row = [];
                    $row['accountplanning_id'] = $id;
                    $row['rel_id'] = $value['rel_id'];
                    $row['rel_type'] = 'pmax_team';
                    $this->db->insert('tblaccountplanning_team', $row);
                }
            }
            $todo_list = $this->get_todo_list($accountplanning_id);
            if(isset($todo_list)){

                foreach ($todo_list as $key => $value) {
                    unset($value['id']);
                    $value['accountplanning_id'] = $id;

                    /*unset($value['objective_id']);
                    unset($value['objective']);
                    unset($value['items']);*/
                    unset($value['deadline']);
                    unset($value['button']);
                    unset($value['convert_to_task']);

                    $this->db->insert('tblaccountplanning_task', $value);

                }
            }
            $service_ability_offering = $this->get_service_ability_offering($accountplanning_id);
            if(isset($service_ability_offering)){
                foreach ($service_ability_offering as $value) {
                    unset($value['id']);
                    $value['accountplanning_id'] = $id;
                    $this->db->insert('tblaccountplanning_service_ability_offering', $value);
                }
            }

            $current_service = $this->get_current_service($accountplanning_id);
            if(isset($current_service)){
                foreach ($current_service as $value) {
                    unset($value['id']);
                    $value['accountplanning_id'] = $id;
                    $this->db->insert('tblaccountplanning_current_service', $value);
                }
            }

            if(isset($attachments)){
                if (is_dir(get_upload_path_by_type('accountplanning') . $accountplanning_id)) {
                    xcopy(get_upload_path_by_type('accountplanning') . $accountplanning_id, get_upload_path_by_type('accountplanning') . $id);
                }
                foreach ($attachments as $at) {
                    $at['rel_id'] = $id;
                    $at['dateadded'] = date('Y-m-d H:i:s');
                    $at['attachment_key'] = app_generate_hash();
                    unset($at['id']);
                    $this->db->insert('tblfiles', $at);
                }
            }

            return $id;
        }

        return false;
    }

    public function get_attachments($accountplanning_id, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $accountplanning_id);
        }
        $this->db->where('rel_type', 'accountplanning');
        $result = $this->db->get('tblfiles');
        if (is_numeric($id)) {
            return $result->row();
        }
        return $result->result_array();
    }

    public function get_month()
    {
        $date = getdate();
        $date_1 = mktime(0, 0, 0, ($date['mon'] - 5), 1, $date['year']);
        $date_2 = mktime(0, 0, 0, ($date['mon'] - 4), 1, $date['year']);
        $date_3 = mktime(0, 0, 0, ($date['mon'] - 3), 1, $date['year']);
        $date_4 = mktime(0, 0, 0, ($date['mon'] - 2), 1, $date['year']);
        $date_5 = mktime(0, 0, 0, ($date['mon'] - 1), 1, $date['year']);
        $date_6 = mktime(0, 0, 0, $date['mon'], 1, $date['year']);
        $date_7 = mktime(0, 0, 0, ($date['mon'] + 1), 1, $date['year']);
        $date_8 = mktime(0, 0, 0, ($date['mon'] + 2), 1, $date['year']);
        $date_9 = mktime(0, 0, 0, ($date['mon'] + 3), 1, $date['year']);
        $date_10 = mktime(0, 0, 0, ($date['mon'] + 4), 1, $date['year']);
        $date_11 = mktime(0, 0, 0, ($date['mon'] + 5), 1, $date['year']);
        $date_12 = mktime(0, 0, 0, ($date['mon'] + 6), 1, $date['year']);
        $month = [ '1' => ['id' => date('Y-m-d', $date_1), 'name' => date('F - Y', $date_1)],
                    '2' => ['id' => date('Y-m-d', $date_2), 'name' => date('F - Y', $date_2)],
                    '3' => ['id' => date('Y-m-d', $date_3), 'name' => date('F - Y', $date_3)],
                    '4' => ['id' => date('Y-m-d', $date_4), 'name' => date('F - Y', $date_4)],
                    '5' => ['id' => date('Y-m-d', $date_5), 'name' => date('F - Y', $date_5)],
                    '6' => ['id' => date('Y-m-d', $date_6), 'name' => date('F - Y', $date_6)],
                    '7' => ['id' => date('Y-m-d', $date_7), 'name' => date('F - Y', $date_7)],
                    '8' => ['id' => date('Y-m-d', $date_8), 'name' => date('F - Y', $date_8)],
                    '9' => ['id' => date('Y-m-d', $date_9), 'name' => date('F - Y', $date_9)],
                    '10' => ['id' => date('Y-m-d', $date_10), 'name' => date('F - Y', $date_10)],
                    '11' => ['id' => date('Y-m-d', $date_11), 'name' => date('F - Y', $date_11)],
                    '12' => ['id' => date('Y-m-d', $date_12), 'name' => date('F - Y', $date_12)],
            ];

        return $month;
    }

    /**
     *  Delete invoice attachment
     * @since  Version 1.0.4
     * @param   mixed $id  attachmentid
     * @return  boolean
     */
    public function delete_attachment($id)
    {
        $attachment = $this->get_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('accountplanning') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('Account Planning Attachment Deleted [Account Planning ID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('accountplanning') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('accountplanning') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('accountplanning') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }


    public function get_file($id, $rel_id = false)
    {
        $this->db->where('id', $id);
        $file = $this->db->get('tblfiles')->row();

        if ($file && $rel_id) {
            if ($file->rel_id != $rel_id) {
                return false;
            }
        }
        return $file;
    }
    public function get_pic_todolist($staffid = '')
    {
        $staff = $this->staff_model->get();
        $list_staff = [];
        foreach ($staff as $key => $value) {
            $note = [];
            $note['id'] = $value['staffid'];
            $note['label'] = trim($value['staffid'].' - '.$value['full_name']);
            $list_staff[] = $note;
        }
        return $list_staff;
    }

    public function get_dashboard_stats()
    {
        $stats = ['draft' => 0, 'in_progress' => 0, 'completed' => 0, 'overdue_tasks' => 0, 'client_red' => 0, 'client_yellow' => 0, 'client_green' => 0];
        $this->db->select('status, COUNT(*) as cnt');
        $this->db->group_by('status');
        $rows = $this->db->get('tblaccountplanning')->result();
        foreach ($rows as $r) {
            if (isset($stats[$r->status])) {
                $stats[$r->status] = (int) $r->cnt;
            }
        }
        $this->db->select('client_status, COUNT(*) as cnt');
        $this->db->where('client_status IS NOT NULL');
        $this->db->where('client_status !=', '');
        $this->db->group_by('client_status');
        $clientRows = $this->db->get('tblaccountplanning')->result();
        foreach ($clientRows as $r) {
            if ($r->client_status == 'Red') $stats['client_red'] = (int) $r->cnt;
            elseif ($r->client_status == 'Yellow') $stats['client_yellow'] = (int) $r->cnt;
            elseif ($r->client_status == 'Green') $stats['client_green'] = (int) $r->cnt;
        }
        $this->db->select('COUNT(*) as cnt');
        $this->db->from('tblaccountplanning_task');
        $this->db->where('deadline <', date('Y-m-d'));
        $this->db->where('status !=', 'Complete');
        $this->db->where('(convert_to_task = 0 OR convert_to_task IS NULL)', null, false);
        $overdue = $this->db->get()->row();
        $stats['overdue_tasks'] = $overdue ? (int) $overdue->cnt : 0;
        $this->db->select('COALESCE(SUM(revenue_next_year), 0) as total');
        $planRev = $this->db->get('tblaccountplanning')->row();
        $stats['planned_revenue'] = $planRev ? (float) $planRev->total : 0;
        if ($this->db->table_exists(db_prefix() . 'accountplanning_relations') && $this->db->table_exists(db_prefix() . 'invoices')) {
            $this->db->select('COALESCE(SUM(i.total), 0) as total');
            $this->db->from(db_prefix() . 'accountplanning_relations ar');
            $this->db->join(db_prefix() . 'invoices i', 'i.id = ar.rel_id');
            $this->db->where('ar.rel_type', 'invoice');
            $invRev = $this->db->get()->row();
            $stats['invoice_revenue'] = $invRev ? (float) $invRev->total : 0;
        } else {
            $stats['invoice_revenue'] = 0;
        }
        $this->db->select('COUNT(DISTINCT a.id) as cnt');
        $this->db->from(db_prefix() . 'accountplanning a');
        $this->db->where('a.client_status', 'Red');
        $this->db->where('EXISTS (SELECT 1 FROM ' . db_prefix() . 'accountplanning_task t WHERE t.accountplanning_id = a.id AND t.deadline < "' . date('Y-m-d') . '" AND t.status != "Complete" AND (t.convert_to_task = 0 OR t.convert_to_task IS NULL))', null, false);
        $atRisk = $this->db->get()->row();
        $stats['at_risk_count'] = $atRisk ? (int) $atRisk->cnt : 0;
        return $stats;
    }

    /**
     * Get account plan linked to a project, invoice, or estimate
     * @param string $rel_type project|invoice|estimate
     * @param int $rel_id
     * @return array|null plan row or null
     */
    public function get_plan_by_relation($rel_type, $rel_id)
    {
        if (!in_array($rel_type, ['project', 'invoice', 'estimate']) || !$rel_id) {
            return null;
        }
        $this->db->select('a.*');
        $this->db->from(db_prefix() . 'accountplanning_relations ar');
        $this->db->join(db_prefix() . 'accountplanning a', 'a.id = ar.accountplanning_id');
        $this->db->where('ar.rel_type', $rel_type);
        $this->db->where('ar.rel_id', (int) $rel_id);
        $row = $this->db->get()->row_array();
        return $row ?: null;
    }

    public function get_relations($accountplanning_id, $rel_type = null)
    {
        $this->db->where('accountplanning_id', $accountplanning_id);
        if ($rel_type) {
            $this->db->where('rel_type', $rel_type);
        }
        return $this->db->get(db_prefix() . 'accountplanning_relations')->result_array();
    }

    public function add_relation($accountplanning_id, $rel_type, $rel_id)
    {
        $this->db->where('accountplanning_id', $accountplanning_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->where('rel_id', $rel_id);
        if ($this->db->count_all_results(db_prefix() . 'accountplanning_relations') > 0) {
            return false;
        }
        $this->db->insert(db_prefix() . 'accountplanning_relations', [
            'accountplanning_id' => $accountplanning_id,
            'rel_type' => $rel_type,
            'rel_id' => $rel_id,
            'dateadded' => date('Y-m-d H:i:s'),
        ]);
        return $this->db->insert_id();
    }

    public function delete_relation($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'accountplanning_relations');
        return $this->db->affected_rows() > 0;
    }

    public function get_relation_details($relations)
    {
        $result = [];
        foreach ($relations as $r) {
            $item = ['id' => $r['id'], 'rel_type' => $r['rel_type'], 'rel_id' => $r['rel_id'], 'name' => '', 'url' => ''];
            if ($r['rel_type'] == 'project' && $this->db->table_exists(db_prefix() . 'projects')) {
                $p = $this->db->get_where(db_prefix() . 'projects', ['id' => $r['rel_id']])->row();
                if ($p) {
                    $item['name'] = $p->name;
                    $item['url'] = admin_url('projects/view/' . $r['rel_id']);
                }
            } elseif ($r['rel_type'] == 'invoice' && $this->db->table_exists(db_prefix() . 'invoices')) {
                $inv = $this->db->get_where(db_prefix() . 'invoices', ['id' => $r['rel_id']])->row();
                if ($inv) {
                    $item['name'] = function_exists('format_invoice_number') ? format_invoice_number($inv->id) : ('Invoice #' . $inv->id);
                    $item['url'] = admin_url('invoices/list_invoices/' . $r['rel_id']);
                }
            } elseif ($r['rel_type'] == 'estimate' && $this->db->table_exists(db_prefix() . 'estimates')) {
                $est = $this->db->get_where(db_prefix() . 'estimates', ['id' => $r['rel_id']])->row();
                if ($est) {
                    $item['name'] = function_exists('format_estimate_number') ? format_estimate_number($est->id) : ('Estimate #' . $est->id);
                    $item['url'] = admin_url('estimates/list_estimates/' . $r['rel_id']);
                }
            } elseif ($r['rel_type'] == 'proposal' && $this->db->table_exists(db_prefix() . 'proposals')) {
                $prop = $this->db->get_where(db_prefix() . 'proposals', ['id' => $r['rel_id']])->row();
                if ($prop) {
                    $item['name'] = $prop->subject;
                    $item['url'] = admin_url('proposals/list_proposals/' . $r['rel_id']);
                }
            }
            if ($item['name'] !== '') {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * Get project tasks for a plan's linked project (for task/milestone sync display)
     */
    public function get_linked_project_tasks($accountplanning_id)
    {
        $relations = $this->get_relations($accountplanning_id, 'project');
        if (empty($relations) || !$this->db->table_exists(db_prefix() . 'tasks')) {
            return [];
        }
        $projectIds = array_column($relations, 'rel_id');
        if (empty($projectIds)) {
            return [];
        }
        $this->db->select('t.id, t.name, t.duedate, t.status, t.rel_id as project_id');
        $this->db->from(db_prefix() . 'tasks t');
        $this->db->where('t.rel_type', 'project');
        $this->db->where_in('t.rel_id', $projectIds);
        $this->db->order_by('t.duedate', 'ASC');
        $tasks = $this->db->get()->result_array();
        $statusNames = [1 => 'Not Started', 2 => 'In Progress', 3 => 'Waiting for feedback', 4 => 'On Hold', 5 => 'Complete'];
        foreach ($tasks as &$t) {
            $t['status_name'] = isset($statusNames[$t['status']]) ? $statusNames[$t['status']] : 'N/A';
            $t['url'] = admin_url('tasks/view/' . $t['id']);
        }
        return $tasks;
    }

    /**
     * Get project milestones for a plan's linked project
     */
    public function get_linked_project_milestones($accountplanning_id)
    {
        $relations = $this->get_relations($accountplanning_id, 'project');
        if (empty($relations) || !$this->db->table_exists(db_prefix() . 'project_milestones')) {
            return [];
        }
        $projectIds = array_column($relations, 'rel_id');
        if (empty($projectIds)) {
            return [];
        }
        $this->db->select('m.id, m.name, m.due_date, m.description, m.project_id');
        $this->db->from(db_prefix() . 'project_milestones m');
        $this->db->where_in('m.project_id', $projectIds);
        $this->db->order_by('m.due_date', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_meeting_notes($accountplanning_id)
    {
        $tbl = db_prefix() . 'accountplanning_meeting_notes';
        if (!$this->db->table_exists($tbl)) {
            return [];
        }
        $this->db->where('accountplanning_id', (int) $accountplanning_id);
        $this->db->order_by('meeting_date', 'DESC');
        return $this->db->get($tbl)->result_array();
    }

    public function add_meeting_note($accountplanning_id, $data)
    {
        $tbl = db_prefix() . 'accountplanning_meeting_notes';
        if (!$this->db->table_exists($tbl)) {
            return false;
        }
        $insert = [
            'accountplanning_id' => (int) $accountplanning_id,
            'subject' => $data['subject'] ?? null,
            'notes' => $data['notes'] ?? null,
            'meeting_date' => !empty($data['meeting_date']) ? to_sql_date($data['meeting_date']) : null,
            'datecreated' => date('Y-m-d H:i:s'),
            'addedfrom' => get_staff_user_id(),
        ];
        $this->db->insert($tbl, $insert);
        return $this->db->insert_id();
    }

    public function update_meeting_note($id, $data)
    {
        $tbl = db_prefix() . 'accountplanning_meeting_notes';
        if (!$this->db->table_exists($tbl)) {
            return false;
        }
        $update = ['subject' => $data['subject'] ?? null, 'notes' => $data['notes'] ?? null];
        if (!empty($data['meeting_date'])) {
            $update['meeting_date'] = to_sql_date($data['meeting_date']);
        }
        $this->db->where('id', (int) $id);
        $this->db->update($tbl, $update);
        return $this->db->affected_rows() >= 0;
    }

    public function delete_meeting_note($id)
    {
        $tbl = db_prefix() . 'accountplanning_meeting_notes';
        if (!$this->db->table_exists($tbl)) {
            return false;
        }
        $this->db->where('id', (int) $id);
        $this->db->delete($tbl);
        return $this->db->affected_rows() > 0;
    }

    public function get_competitors($accountplanning_id)
    {
        $tbl = db_prefix() . 'accountplanning_competitors';
        if (!$this->db->table_exists($tbl)) {
            return [];
        }
        $this->db->where('accountplanning_id', (int) $accountplanning_id);
        return $this->db->get($tbl)->result_array();
    }

    public function add_competitor($accountplanning_id, $data)
    {
        $tbl = db_prefix() . 'accountplanning_competitors';
        if (!$this->db->table_exists($tbl)) {
            return false;
        }
        $insert = [
            'accountplanning_id' => (int) $accountplanning_id,
            'name' => $data['name'] ?? null,
            'threat_level' => $data['threat_level'] ?? null,
            'notes' => $data['notes'] ?? null,
            'datecreated' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert($tbl, $insert);
        return $this->db->insert_id();
    }

    public function update_competitor($id, $data)
    {
        $tbl = db_prefix() . 'accountplanning_competitors';
        if (!$this->db->table_exists($tbl)) {
            return false;
        }
        $update = [
            'name' => $data['name'] ?? null,
            'threat_level' => $data['threat_level'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];
        $this->db->where('id', (int) $id);
        $this->db->update($tbl, $update);
        return $this->db->affected_rows() >= 0;
    }

    public function delete_competitor($id)
    {
        $tbl = db_prefix() . 'accountplanning_competitors';
        if (!$this->db->table_exists($tbl)) {
            return false;
        }
        $this->db->where('id', (int) $id);
        $this->db->delete($tbl);
        return $this->db->affected_rows() > 0;
    }

    public function get_plans_by_client($client_id)
    {
        $this->db->where('client_id', (int) $client_id);
        $this->db->order_by('date', 'DESC');
        return $this->db->get('tblaccountplanning')->result_array();
    }

    public function get_pending_update_requests($accountplanning_id)
    {
        $tbl = db_prefix() . 'accountplanning_update_requests';
        if (!$this->db->table_exists($tbl)) {
            return [];
        }
        $this->db->where('accountplanning_id', (int) $accountplanning_id);
        $this->db->where('status', 'pending');
        return $this->db->get($tbl)->result_array();
    }

    public function mark_update_request_handled($request_id)
    {
        $tbl = db_prefix() . 'accountplanning_update_requests';
        if (!$this->db->table_exists($tbl)) {
            return false;
        }
        $this->db->where('id', (int) $request_id);
        $this->db->update($tbl, ['status' => 'handled']);
        return $this->db->affected_rows() > 0;
    }

    public function get_templates()
    {
        return $this->db->get(db_prefix() . 'accountplanning_templates')->result_array();
    }

    public function get_template($id)
    {
        return $this->db->get_where(db_prefix() . 'accountplanning_templates', ['id' => $id])->row();
    }

    public function add_template($data)
    {
        $data['datecreated'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'accountplanning_templates', $data);
        return $this->db->insert_id();
    }

    public function update_template($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'accountplanning_templates', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_template($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'accountplanning_templates');
        return $this->db->affected_rows() > 0;
    }

    public function create_from_template($template_id, $client_id, $subject, $date)
    {
        $tpl = $this->get_template($template_id);
        if (!$tpl) {
            return false;
        }
        $data = [
            'client_id' => $client_id,
            'subject' => $subject,
            'date' => to_sql_date($date),
            'vision' => $tpl->vision,
            'mission' => $tpl->mission,
            'objectives' => $tpl->objectives,
            'threat' => $tpl->threat,
            'opportunity' => $tpl->opportunity,
            'criteria_to_success' => $tpl->criteria_to_success,
            'constraints' => $tpl->constraints,
            'data_tree' => $tpl->data_tree,
            'status' => get_option('accountplanning_default_status', 'draft'),
        ];
        return $this->add($data);
    }

    public function get_saved_filters($staffid = null)
    {
        if (!$staffid) {
            $staffid = get_staff_user_id();
        }
        $this->db->where('staffid', $staffid);
        return $this->db->get(db_prefix() . 'accountplanning_saved_filters')->result_array();
    }

    public function add_saved_filter($name, $filters)
    {
        $this->db->insert(db_prefix() . 'accountplanning_saved_filters', [
            'staffid' => get_staff_user_id(),
            'name' => $name,
            'filters' => is_array($filters) ? json_encode($filters) : $filters,
            'datecreated' => date('Y-m-d H:i:s'),
        ]);
        return $this->db->insert_id();
    }

    public function delete_saved_filter($id)
    {
        $this->db->where('id', $id);
        $this->db->where('staffid', get_staff_user_id());
        $this->db->delete(db_prefix() . 'accountplanning_saved_filters');
        return $this->db->affected_rows() > 0;
    }

    public function _search_accountplanning($q, $limit = 0)
    {
        $result = [
            'result'         => [],
            'type'           => 'accountplanning',
            'search_heading' => _l('accountplanning'),
        ];
		
		// Uncomment if you wish to restrict usage: if (has_permission('staff', '', 'view')) {
            // Staff
            $this->db->select();
            $this->db->from('tblaccountplanning');
            $this->db->join('tblclients', 'tblclients.userid = tblaccountplanning.client_id');
            $this->db->like('company', $q);
            $this->db->or_like('id', $q);
            $this->db->or_like('subject', $q);
            if ($limit != 0) {
                $this->db->limit($limit);
            }
            $this->db->order_by('subject', 'ASC');
            $result['result'] = $this->db->get()->result_array();
        // End of possible uncommenting: }
           
        return $result;
    }
}
