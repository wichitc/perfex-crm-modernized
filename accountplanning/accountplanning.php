<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Account Planning
Module URI: https://codecanyon.net/item/account-planning-module-for-perfex-crm/26406165
Description: Strategic account planning through a customer-centric approach to identifying priority accounts, capturing and analysing critical information, developing a strategy to expand and grow existing customer relationships.
Version: 1.1.0
Requires at least: 2.3.*
Author: Themesic Interactive
Author URI: https://codecanyon.net/user/themesic/portfolio
*/

define('ACCOUNTPLANNING_MODULE', 'accountplanning');
define('ACCOUNT_PLANNING_ATTACHMENTS_FOLDER', module_dir_path(ACCOUNTPLANNING_MODULE, 'uploads/'));
require_once __DIR__.'/vendor/autoload.php';
modules\accountplanning\core\Apiinit::the_da_vinci_code(ACCOUNTPLANNING_MODULE);
modules\accountplanning\core\Apiinit::ease_of_mind(ACCOUNTPLANNING_MODULE);

hooks()->add_action('admin_init', 'accountplanning_module_init_menu_items');
hooks()->add_action('admin_init', 'accountplanning_add_client_profile_tab', 20);
hooks()->add_action('clients_init', 'accountplanning_client_init');
hooks()->add_action('app_customers_footer', 'accountplanning_customers_css');
hooks()->add_action('task_related_to_select', 'add_option_task_related_to');
hooks()->add_action('admin_init', 'accountplanning_permissions');
hooks()->add_action('app_admin_head', 'accountplanning_add_head_components');
hooks()->add_action('app_admin_footer', 'accountplanning_add_footer_components');
register_cron_task('accountplanning_send_reminders');
register_cron_task('accountplanning_recurring_copy');
hooks()->add_action('app_client_assets', 'accountplanning_client_assets');

hooks()->add_filter('get_upload_path_by_type', 'add_upload_path_accountplanning', 10, 2);
hooks()->add_filter('custom_fields_rel_types', 'accountplanning_add_custom_fields_rel_type');
hooks()->add_filter('get_relation_data', 'accountplanning_task_relation_data', 10, 4);
hooks()->add_filter('relation_values', 'accountplanning_task_relation_values', 10, 2);
hooks()->add_filter('register_merge_fields', 'accountplanning_register_merge_fields');

hooks()->add_action('admin_project_overview_end_of_project_overview_left', 'accountplanning_project_link');
hooks()->add_action('after_left_panel_invoice_preview_template', 'accountplanning_invoice_link');
hooks()->add_action('after_admin_estimate_preview_template_tab_content_last_item', 'accountplanning_estimate_link');
hooks()->add_filter('before_add_task', 'before_add_task', 10, 2);
hooks()->add_filter('after_add_task', 'after_add_task', 10, 2);
hooks()->add_filter('task_status_changed', 'task_status_changed', 10, 2);

/**
* Load the module helper
*/
$CI = &get_instance();
$CI->load->helper(ACCOUNTPLANNING_MODULE . '/accountplanning');

/**
* Register activation module hook
*/
register_activation_hook(ACCOUNTPLANNING_MODULE, 'accountplanning_module_activation_hook');

/**
* Functions of the module
*/
function accountplanning_add_head_components(){
    if (get_option('accountplanning_enabled') == '1') {
        $CI = &get_instance();
		
		$loaddepdendencies = $_SERVER['REQUEST_URI'];
		
		if ( strpos($loaddepdendencies,'accountplanning/new_account') !== false && strpos($loaddepdendencies,'?') == false ) {
			echo '<script src="' . base_url('modules/accountplanning/assets/js/handsontable.full.min.js') . '"></script>';
			echo '<link href="' . base_url('modules/accountplanning/assets/css/handsontable.full.min.css') .'"  rel="stylesheet" type="text/css" />';
		}
		
		
		if ( strpos($loaddepdendencies,'accountplanning/view/') !== false && strpos($loaddepdendencies,'?') == false ) {
			echo '<script src="' . base_url('modules/accountplanning/assets/js/handsontable.full.min.js') . '"></script>';
			echo '<link href="' . base_url('modules/accountplanning/assets/css/handsontable.full.min.css') .'"  rel="stylesheet" type="text/css" />';
		}

		
		if (strpos($loaddepdendencies,'?group=due_diligence') !== false) {
			echo '<script src="' . base_url('modules/accountplanning/assets/js/handsontable.full.min.js') . '"></script>';
			echo '<link href="' . base_url('modules/accountplanning/assets/css/handsontable.full.min.css') .'"  rel="stylesheet" type="text/css" />';
		}
		
		
		if (strpos($loaddepdendencies,'?group=service_ability_offering') !== false) {
			echo '<script src="' . base_url('modules/accountplanning/assets/js/handsontable.full.min.js') . '"></script>';
			echo '<link href="' . base_url('modules/accountplanning/assets/css/handsontable.full.min.css') .'"  rel="stylesheet" type="text/css" />';
		}		
		
	
		if (strpos($loaddepdendencies,'?group=planning') !== false) {
		echo '<script src="' . base_url('modules/accountplanning/assets/js/handsontable.full.min.js') . '"></script>';
		echo '<link href="' . base_url('modules/accountplanning/assets/css/handsontable.full.min.css') .'"  rel="stylesheet" type="text/css" />';
		echo '<script>if (typeof Handsontable !== "undefined") { Handsontable.licenseKey = "non-commercial-and-evaluation"; }</script>';
		echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>';
	}
	
	
        echo '<link href="' . base_url('modules/accountplanning/assets/css/accountplanning.css') .'"  rel="stylesheet" type="text/css" />';
		echo '
				<style>
				th.sorting_disabled.sorting_asc:after {
					display: none !important;
				}
				</style>
			 ';

    }
}

function accountplanning_add_footer_components(){

		$loaddepdendencies = $_SERVER['REQUEST_URI'];

		
		if ( strpos($loaddepdendencies,'accountplanning/view/') !== false && strpos($loaddepdendencies,'?') == false ) {
			echo '<script src="' . base_url('modules/accountplanning/assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
			echo '<script src="' . base_url('modules/accountplanning/assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
		}
		
	if ( strpos($loaddepdendencies,'?group=planning') !== false) {
		echo '<script>
		if (typeof tinymce !== "undefined") {
			tinymce.remove();
		}
		</script>';
		echo '<script src="' . base_url('modules/accountplanning/assets/plugins/tinymce/tinymce.min.js') . '?v=' . time() . '"></script>';
		echo '<script>
		if (typeof tinymce !== "undefined") {
			tinymce.baseURL = "' . base_url('modules/accountplanning/assets/plugins/tinymce') . '";
			tinymce.EditorManager.baseURL = "' . base_url('modules/accountplanning/assets/plugins/tinymce') . '";
		}
		</script>';
		echo '<script src="' . base_url('modules/accountplanning/assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . base_url('modules/accountplanning/assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

		if (strpos($loaddepdendencies,'?group=service_ability_offering') !== false) {
			echo '<script src="' . base_url('modules/accountplanning/assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
			echo '<script src="' . base_url('modules/accountplanning/assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
		}		
			
}


function accountplanning_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

function accountplanning_relation_values($values, $relation)
{
    if ($values['type'] == 'accountplanning') {
        if (is_array($relation)) {
            $values['id']   = $relation['id'];
            $values['name'] = $relation['subject'];
        } else {
            $values['id']   = $relation->id;
            $values['name'] = $relation->subject;
        }
        $values['link'] = admin_url('accountplanning/view/' . $values['id']);
    }

    return $values;
}

function accountplanning_relation_data($data, $type, $rel_id, $q)
{
    $CI = &get_instance();
    $CI->load->model(ACCOUNTPLANNING_MODULE.'/accountplanning_model');
    if ($type == 'accountplanning') {
        if ($rel_id != '') {
            $data = $CI->accountplanning_model->get($rel_id);
        } else {
            $search = $CI->accountplanning_model->_search_accountplanning($q);
            $data   = $search['result'];
        }
    }
    return $data;
}

function add_option_task_related_to($rel_type)
{
    $selected = '';
    if($rel_type == 'accountplanning'){
        $selected = 'selected';
    }
    echo "<option value='accountplanning' ".$selected.">". _l('accountplanning')."</option>";
}

function add_upload_path_accountplanning($path, $type)
{
    if($type == 'accountplanning'){
        $path = ACCOUNT_PLANNING_ATTACHMENTS_FOLDER;
    }

    return $path;
}

function before_add_task($data) {
    $CI = &get_instance();

    $accountplanning_to_task = false;

    if (isset($data['accountplanning_to_task'])) {
        $accountplanning_to_task = true;
        unset($data['accountplanning_to_task']);
		}

    $account_task_id = false;

    if (isset($data['account_task_id'])) {
        $account_task_id = $data['account_task_id'];

        unset($data['account_task_id']);
    }

    if($accountplanning_to_task){
        $data_new = [];
        $data_new['account_task_id'] = $account_task_id;
        $CI->session->set_userdata($data_new);
    }

    return $data;
}

function after_add_task($insert_id)
{   
    $CI = &get_instance();
    $account_task_id = $CI->session->userdata("account_task_id");
    if((isset($account_task_id)) && $account_task_id != ''){
        $CI->session->unset_userdata("account_task_id");

        $CI->db->where('id', $account_task_id);
        $CI->db->update(db_prefix() . 'accountplanning_task',['convert_to_task' => $insert_id]);
    }
}

function task_form_hidden(){
    $CI = &get_instance();
     if($CI->input->get('accountplanning_to_task')) {
            echo form_hidden('accountplanning_to_task');
      }
      if($CI->input->get('account_task_id')) {
        echo form_hidden('account_task_id', $CI->input->get('account_task_id'));
      };
}

/**
 * Map Perfex task status (1-5) to plan task status
 */
function accountplanning_perfex_to_plan_status($perfex_status)
{
    $map = [1 => 'Not Started', 2 => 'In Progress', 3 => 'Waiting for feedback', 4 => 'On Hold', 5 => 'Complete'];
    return isset($map[$perfex_status]) ? $map[$perfex_status] : 'Not Started';
}

/**
 * Map plan task status to Perfex task status (1-5)
 */
function accountplanning_plan_to_perfex_status($plan_status)
{
    $map = ['Complete' => 5, 'In Progress' => 2, 'Not Started' => 1, 'Waiting for feedback' => 3, 'On Hold' => 4];
    return isset($map[$plan_status]) ? $map[$plan_status] : 1;
}

function task_status_changed($data)
{
    $CI = &get_instance();
    $planStatus = accountplanning_perfex_to_plan_status($data['status']);
    $CI->db->where('convert_to_task', $data['task_id']);
    $CI->db->update(db_prefix() . 'accountplanning_task', ['status' => $planStatus]);
}


/**
* Register language files
*/
register_language_files(ACCOUNTPLANNING_MODULE, [ACCOUNTPLANNING_MODULE]);

/**
 * Init project_roadmap module menu items in setup in admin_init hook
 * @return null
 */
function accountplanning_module_init_menu_items()
{
    if (has_permission('accountplanning', '', 'view')) {
        $CI = &get_instance();      
        $CI->app_menu->add_sidebar_menu_item('accountplanning', [
                'name'     => _l('als_accountplanning'),
                'href'     => admin_url('accountplanning'),
                'position' => 30,
                'icon'     => 'fa fa-address-book',
        ]);
    }
}

function accountplanning_register_merge_fields($fields)
{
    $fields[] = 'accountplanning/merge_fields/Accountplanning_merge_fields';
    return $fields;
}

function accountplanning_project_link($project)
{
    if (!has_permission('accountplanning', '', 'view') || get_option('accountplanning_enabled') != '1') {
        return;
    }
    $CI = &get_instance();
    $CI->load->model('accountplanning/accountplanning_model');
    $plan = $CI->accountplanning_model->get_plan_by_relation('project', $project->id);
    if ($plan) {
        echo '<p class="mtop15"><a href="' . admin_url('accountplanning/view/' . $plan['id']) . '" class="btn btn-default btn-sm"><i class="fa fa-address-book"></i> ' . _l('accountplanning') . ': ' . e($plan['subject']) . '</a></p>';
    }
}

function accountplanning_invoice_link($invoice)
{
    if (!has_permission('accountplanning', '', 'view') || get_option('accountplanning_enabled') != '1') {
        return;
    }
    $CI = &get_instance();
    $CI->load->model('accountplanning/accountplanning_model');
    $plan = $CI->accountplanning_model->get_plan_by_relation('invoice', $invoice->id);
    if ($plan) {
        echo '<p class="mtop15"><a href="' . admin_url('accountplanning/view/' . $plan['id']) . '" class="btn btn-default btn-sm"><i class="fa fa-address-book"></i> ' . _l('accountplanning') . ': ' . e($plan['subject']) . '</a></p>';
    }
}

function accountplanning_estimate_link($estimate)
{
    if (!has_permission('accountplanning', '', 'view') || get_option('accountplanning_enabled') != '1') {
        return;
    }
    $CI = &get_instance();
    $CI->load->model('accountplanning/accountplanning_model');
    $plan = $CI->accountplanning_model->get_plan_by_relation('estimate', $estimate->id);
    if ($plan) {
        echo '<div class="col-md-12 mtop15"><div class="alert alert-info no-mbot"><i class="fa fa-address-book"></i> ' . _l('accountplanning') . ': <a href="' . admin_url('accountplanning/view/' . $plan['id']) . '">' . e($plan['subject']) . '</a></div></div>';
    }
}

function accountplanning_add_client_profile_tab()
{
    if (has_permission('accountplanning', '', 'view')) {
        $CI = &get_instance();
        $CI->app_tabs->add_customer_profile_tab('account_plans', [
            'name'     => _l('accountplanning'),
            'icon'     => 'fa fa-address-book',
            'view'     => 'accountplanning/client/account_plans_tab',
            'position' => 67,
            'visible'  => true,
        ]);
    }
}

function accountplanning_client_init()
{
    if (get_option('accountplanning_client_portal_enabled') != '1' || !is_client_logged_in()) {
        return;
    }
    add_theme_menu_item('accountplanning_client', [
        'name'     => _l('planning'),
        'href'     => site_url('accountplanning/client'),
        'position' => 30,
    ]);
}

/**
 * Load client portal CSS in customers area (same pattern as products module)
 */
function accountplanning_customers_css()
{
    if (get_option('accountplanning_client_portal_enabled') != '1') {
        return;
    }
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, '/accountplanning/client') !== false) {
        $CI = &get_instance();
        echo '<link href="' . module_dir_url('accountplanning', 'assets/css/accountplanning-client.css') . '?v=' . $CI->app_scripts->core_version() . '" rel="stylesheet" type="text/css" />';
    }
}

function accountplanning_client_assets()
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, 'accountplanning/client') !== false && get_option('accountplanning_client_portal_enabled') == '1') {
        $CI = &get_instance();
        if (isset($CI->app_css) && $CI->app_css) {
            $CI->app_css->add('accountplanning-client-css', module_dir_url('accountplanning', 'assets/css/accountplanning-client.css'));
        }
    }
}

function accountplanning_recurring_copy()
{
    if (get_option('accountplanning_recurring_cron_enabled') != '1') {
        return;
    }
    $period = get_option('accountplanning_recurring_period', 'month');
    $today = (int) date('j');
    $month = (int) date('n');
    if ($period === 'quarter') {
        if (!in_array($month, [1, 4, 7, 10]) || $today !== 1) {
            return;
        }
    } else {
        if ($today !== 1) {
            return;
        }
    }
    $CI = &get_instance();
    $CI->load->model('accountplanning/accountplanning_model');
    $tbl = db_prefix() . 'accountplanning';
    $CI->db->select('id, client_id, date, subject');
    $CI->db->from($tbl);
    $CI->db->where('status', 'completed');
    if ($period === 'quarter') {
        $prev_quarter_start = date('Y-m-01', strtotime('first day of -3 months'));
        $prev_quarter_end = date('Y-m-t', strtotime('last day of -1 month'));
        $CI->db->where('date >=', $prev_quarter_start);
        $CI->db->where('date <=', $prev_quarter_end);
        $next_period_base = date('Y-m-01');
    } else {
        $prev_month_start = date('Y-m-01', strtotime('-1 month'));
        $prev_month_end = date('Y-m-t', strtotime('-1 month'));
        $CI->db->where('date >=', $prev_month_start);
        $CI->db->where('date <=', $prev_month_end);
        $next_period_base = date('Y-m-01');
    }
    $plans = $CI->db->get()->result_array();
    foreach ($plans as $plan) {
        $CI->db->from($tbl);
        $CI->db->where('client_id', (int) $plan['client_id']);
        $CI->db->where('date', $next_period_base);
        if ($CI->db->count_all_results() > 0) {
            continue;
        }
        $copy_data = [
            'client_id' => $plan['client_id'],
            'date' => $next_period_base,
            'subject' => ($plan['subject'] ?? '') . ' - ' . _d($next_period_base),
        ];
        $CI->accountplanning_model->copy($plan['id'], $copy_data);
    }
}

function accountplanning_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit' => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('accountplanning', $capabilities, _l('accountplanning'));
}

function accountplanning_add_custom_fields_rel_type($types)
{
    $types['accountplanning'] = _l('accountplanning');
    return $types;
}

function accountplanning_task_relation_data($data, $params)
{
    if (isset($params['type']) && $params['type'] === 'accountplanning_task' && !empty($params['rel_id'])) {
        $CI = &get_instance();
        $CI->db->select('t.*, a.subject, a.id as accountplanning_id');
        $CI->db->from(db_prefix() . 'accountplanning_task t');
        $CI->db->join(db_prefix() . 'accountplanning a', 'a.id = t.accountplanning_id');
        $CI->db->where('t.id', (int) $params['rel_id']);
        $data = $CI->db->get()->row_array();
    }
    return $data;
}

function accountplanning_task_relation_values($values)
{
    if (isset($values['type']) && $values['type'] == 'accountplanning_task' && isset($values['relation']) && is_array($values['relation']) && !empty($values['relation'])) {
        $rel = $values['relation'];
        $values['id'] = $rel['id'];
        $values['name'] = ($rel['action_needed'] ?: $rel['item']) . ' - ' . ($rel['subject'] ?: _l('accountplanning'));
        $values['link'] = admin_url('accountplanning/view/' . $rel['accountplanning_id'] . '?group=planning');
    }
    return $values;
}

function accountplanning_send_reminders()
{
    if (get_option('accountplanning_reminders_enabled') != '1') {
        return;
    }
    $days = (int) get_option('accountplanning_reminder_days', 3);
    $reminderDate = date('Y-m-d', strtotime('+' . $days . ' days'));
    $CI = &get_instance();
    $CI->db->select('t.*, a.subject');
    $CI->db->from(db_prefix() . 'accountplanning_task t');
    $CI->db->join(db_prefix() . 'accountplanning a', 'a.id = t.accountplanning_id');
    $CI->db->where('t.deadline <=', $reminderDate);
    $CI->db->where('t.deadline >=', date('Y-m-d'));
    $CI->db->where('t.status !=', 'Complete');
    $CI->db->where('(t.convert_to_task = 0 OR t.convert_to_task IS NULL)', null, false);
    $tasks = $CI->db->get()->result_array();
    foreach ($tasks as $task) {
        if (!empty($task['pic']) && $CI->db->table_exists(db_prefix() . 'reminders')) {
            $reminderDateTime = date('Y-m-d 09:00:00', strtotime('-' . $days . ' days', strtotime($task['deadline'])));
            $staff_ids = array_filter(explode('|', $task['pic']));
            foreach ($staff_ids as $staff_id) {
                $CI->db->from(db_prefix() . 'reminders');
                $CI->db->where('rel_id', $task['id']);
                $CI->db->where('rel_type', 'accountplanning_task');
                $CI->db->where('staff', $staff_id);
                if ($CI->db->count_all_results() == 0) {
                    $CI->db->insert(db_prefix() . 'reminders', [
                        'description' => 'Account Planning task: ' . $task['action_needed'] . ' - Due ' . _d($task['deadline']),
                        'date' => $reminderDateTime,
                        'isnotified' => 0,
                        'rel_id' => $task['id'],
                        'rel_type' => 'accountplanning_task',
                        'staff' => $staff_id,
                        'notify_by_email' => 1,
                        'creator' => get_staff_user_id() ?: 0,
                    ]);
                }
            }
        }
    }
}

hooks()->add_action('app_init', ACCOUNTPLANNING_MODULE.'_actLib');
function accountplanning_actLib()
{
    $CI = &get_instance();
    $CI->load->library(ACCOUNTPLANNING_MODULE.'/Accountplanning_aeiou');
    $license_valid = $CI->accountplanning_aeiou->validatePurchase(ACCOUNTPLANNING_MODULE);
    if (!$license_valid) {
        set_alert('danger', 'One of your modules failed its verification and got deactivated. Please reactivate or contact support.');
    }
}

hooks()->add_action('pre_activate_module', ACCOUNTPLANNING_MODULE.'_sidecheck');
function accountplanning_sidecheck($module_name)
{
    if (ACCOUNTPLANNING_MODULE == $module_name['system_name']) {
        modules\accountplanning\core\Apiinit::activate($module_name);
    }
}

hooks()->add_action('pre_deactivate_module', ACCOUNTPLANNING_MODULE.'_deregister');
function accountplanning_deregister($module_name)
{
    if (ACCOUNTPLANNING_MODULE == $module_name['system_name']) {
        delete_option(ACCOUNTPLANNING_MODULE.'_verification_id');
        delete_option(ACCOUNTPLANNING_MODULE.'_last_verification');
        delete_option(ACCOUNTPLANNING_MODULE.'_product_token');
        delete_option(ACCOUNTPLANNING_MODULE.'_heartbeat');
    }
}
