<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Recruitment Management
Description: This is a set of tools designed to automate and manage your organization's recruiting and staffing operations
Version: 1.2.6
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('RECRUITMENT_MODULE_NAME', 'recruitment');
define('RECRUITMENT_MODULE_UPLOAD_FOLDER', module_dir_path(RECRUITMENT_MODULE_NAME, 'uploads'));
define('RECRUITMENT_PATH', 'modules/recruitment/uploads/');
define('RECRUITMENT_COMPANY_UPLOAD', module_dir_path(RECRUITMENT_MODULE_NAME, 'uploads/company_images/'));
define('TEMFOLDER_EXPORT_CANDIDATE', module_dir_path(RECRUITMENT_MODULE_NAME, 'uploads/export_candidate/'));
define('CANDIDATE_IMAGE_UPLOAD', module_dir_path(RECRUITMENT_MODULE_NAME, 'uploads/candidate/avartar/'));
define('CANDIDATE_CV_UPLOAD', module_dir_path(RECRUITMENT_MODULE_NAME, 'uploads/candidate/files/'));
define('CANDIDATE_IMPORT_ERROR', 'modules/recruitment/uploads/import_candidates_error/');


hooks()->add_action('admin_init', 'recruitment_permissions');
hooks()->add_action('app_admin_head', 'recruitment_head_components');
hooks()->add_action('app_admin_footer', 'recruitment_add_footer_components');
hooks()->add_action('admin_init', 'recruitment_module_init_menu_items');

hooks()->add_action('app_customers_portal_head', 'recruitment_portal_add_head_components');
hooks()->add_action('app_customers_portal_footer', 'recruitment_portal_add_footer_components');
hooks()->add_action('forms_head', 'forms_add_head_components');
hooks()->add_action('forms_footer', 'forms_add_footer_components');

//recruitment add customfield
hooks()->add_action('after_custom_fields_select_options','init_recruitment_customfield');

/*add menu on client portal*/
hooks()->add_action('customers_navigation_after_profile', 'init_recruitment_portal_menu');

/*email template*/
register_merge_fields('recruitment/merge_fields/change_candidate_status_merge_fields');
register_merge_fields('recruitment/merge_fields/new_candidate_have_applied_merge_fields');
register_merge_fields('recruitment/merge_fields/change_candidate_job_applied_status_merge_fields');
register_merge_fields('recruitment/merge_fields/change_candidate_interview_schedule_status_merge_fields');
register_merge_fields('recruitment/merge_fields/send_interview_schedule_merge_fields');

hooks()->add_filter('other_merge_fields_available_for', 'new_candidate_have_applied_register_other_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'change_rec_candidate_status_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'change_candidate_job_applied_status_register_other_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'change_candidate_interview_schedule_status_register_other_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'send_interview_schedule_register_other_merge_fields');


define('RE_REVISION', 1261);

/**
 * Register activation module hook
 */
register_activation_hook(RECRUITMENT_MODULE_NAME, 'recruitment_module_activation_hook');
/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(RECRUITMENT_MODULE_NAME . '/recruitment');

//ReC portal UI
if(rec_get_status_modules('theme_style') == 1){
    hooks()->add_action('app_rec_portal_head', 'theme_style_rec_portal_area_head');
}

function recruitment_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(RECRUITMENT_MODULE_NAME, [RECRUITMENT_MODULE_NAME]);

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function recruitment_module_init_menu_items() {

	$CI = &get_instance();
	if (has_permission('recruitment', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('recruitment', [
			'name' => _l('recruitment'),
			'icon' => 'fa fa-address-book',
			'position' => 30,
		]);
		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'recruitment_dashboard',
			'name' => _l('dashboard'),
			'icon' => 'fa fa-home',
			'href' => admin_url('recruitment/dashboard'),
			'position' => 1,
		]);

		if (get_recruitment_option('recruitment_create_campaign_with_plan') == 1) {
			$CI->app_menu->add_sidebar_children_item('recruitment', [
				'slug' => 'recruitment-proposal',
				'name' => _l('_proposal'),
				'icon' => 'fa fa-address-card',
				'href' => admin_url('recruitment/recruitment_proposal'),
				'position' => 2,
			]);
		}

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'recruitment-campaign',
			'name' => _l('campaign'),
			'icon' => 'fa fa-sitemap',
			'href' => admin_url('recruitment/recruitment_campaign'),
			'position' => 3,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'candidate-profile',
			'name' => _l('candidate_profile'),
			'icon' => 'fa fa-user',
			'href' => admin_url('recruitment/candidate_profile'),
			'position' => 4,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'interview-schedule',
			'name' => _l('interview_schedule'),
			'icon' => 'fa fa-calendar',
			'href' => admin_url('recruitment/interview_schedule'),
			'position' => 5,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'recruitment-channel',
			'name' => _l('_recruitment_channel'),
			'icon' => 'fa fa-feed',
			'href' => admin_url('recruitment/recruitment_channel'),
			'position' => 6,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'recruitment-portal',
			'name' => _l('recruitment_portal'),
			'icon' => 'fa fa-bars menu-icon',
			'href' => site_url('recruitment/recruitment_portal'),
			'position' => 7,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'rec_settings',
			'name' => _l('setting'),
			'icon' => 'fa fa-gears',
			'href' => admin_url('recruitment/setting'),
			'position' => 8,
		]);
	}

}

/**
 * recruitment permissions
 * @return
 */
function recruitment_permissions() {
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('recruitment', $capabilities, _l('recruitment'));
}

/**
 * add head components
 */
function recruitment_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, '/admin/recruitment') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/styles.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/dashboard') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/dashboard.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/candidates') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/candidate.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/candidate') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/candidate_detail.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/setting') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/setting.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/interview_schedule') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/interview_schedule_preview.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_campaign') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/campaign_preview.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/candidate_profile') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/candidate_profile.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_proposal') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/recruitment_proposal.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_campaign') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/recruitment_proposal.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/setting?group=company') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/company.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, '/admin/recruitment/recruitment_portal/job_detail') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/recruitment_proposal.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, '/admin/recruitment/import_candidate') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/import_candidate.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

}

/**
 * add footer_components
 * @return
 */
function recruitment_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];

	if (!(strpos($viewuri, '/admin/recruitment') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/deactivate_hotkey.js') . '?v=' . RE_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/recruitment/dashboard') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_proposal') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/proposal.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/candidates') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/candidate.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/candidate_profile') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/candidate_profile.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/transfer_to_hr') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/transferhr.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/setting?group=evaluation_criteria') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/evaluation_criteria.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/setting?group=evaluation_form') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/evaluation_form.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/setting?group=job_position') === false) || !(strpos($viewuri, '/admin/recruitment/setting') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/job_position.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/setting?group=tranfer_personnel') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/tranfer_personnel.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/interview_schedule') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/interview_schedule.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_campaign') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/campaign.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_campaign') === false)) {
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_channel') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/channel.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/calendar_interview_schedule') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/interview_schedule.js') . '?v=' . RE_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/setting?group=skills') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/skill.js') . '?v=' . RE_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/recruitment/setting?group=recruitment_campaign_setting') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/recruitment_campaign_setting.js') . '?v=' . RE_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/recruitment/setting?group=industry_list') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/industry.js') . '?v=' . RE_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/recruitment_portal/job_detail') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/job_detail_portal.js') . '?v=' . RE_REVISION . '"></script>';
	}

}

/**
 * recruitment portal add head components
 *
 */
function recruitment_portal_add_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];

	if (!(strpos($viewuri, 'recruitment') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/recruitment_portal.css') . '?v=' . RE_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

}

function recruitment_appint(){
    $CI = & get_instance();    
    require_once 'libraries/gtsslib.php';
    $recruitment_api = new RecruitmentLic();
    $recruitment_gtssres = $recruitment_api->verify_license(true);    
    if(!$recruitment_gtssres || ($recruitment_gtssres && isset($recruitment_gtssres['status']) && !$recruitment_gtssres['status'])){
        $CI->app_modules->deactivate(RECRUITMENT_MODULE_NAME);
        set_alert('danger', recruitment_decrypt('plGJBtUappDE3nHKyVHLrW7/hP1hS3GF2gj8p2o/ZLC5ETPQIs2GtJLQ1eOaoWXUBkF/F/RnkOiHDxsFxIAzAQlGrUZAfVufkpsftfXeGYKDHJWgpXR0Flf6Tw1G123yLoumNQnMbhRW2RPdhagn7A+KxbS8QEG0NzP8I0zFUG/ZG+V6oG4OTdETpxFBRQincNmA960Lj/a4aDuS1Hj2Hw=='));
        redirect(admin_url('modules'));
    }    
}
function recruitment_preactivate($module_name){
    if ($module_name['system_name'] == RECRUITMENT_MODULE_NAME) {             
        require_once 'libraries/gtsslib.php';
        $recruitment_api = new RecruitmentLic();
        $recruitment_gtssres = $recruitment_api->verify_license();          
        if(!$recruitment_gtssres || ($recruitment_gtssres && isset($recruitment_gtssres['status']) && !$recruitment_gtssres['status'])){
             $CI = & get_instance();
            $data['submit_url'] = $module_name['system_name'].'/gtsverify/activate'; 
            $data['original_url'] = admin_url('modules/activate/'.RECRUITMENT_MODULE_NAME); 
            $data['module_name'] = RECRUITMENT_MODULE_NAME; 
            $data['title'] = "Module License Activation"; 
            echo $CI->load->view($module_name['system_name'].'/activate', $data, true);
            exit();
        }        
    }
}
function recruitment_predeactivate($module_name){
    if ($module_name['system_name'] == RECRUITMENT_MODULE_NAME) {
        require_once 'libraries/gtsslib.php';
        $recruitment_api = new RecruitmentLic();
        $recruitment_api->deactivate_license();
    }
}
function recruitment_uninstall($module_name){
    if ($module_name['system_name'] == RECRUITMENT_MODULE_NAME) {
        require_once 'libraries/gtsslib.php';
        $recruitment_api = new RecruitmentLic();
        $recruitment_api->deactivate_license();
    }
}

/**
 * recruitment portal add footer components
 *
 */
function recruitment_portal_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];

	if (!(strpos($viewuri, 'recruitment/recruitment_portal') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/recruitment_portal.js') . '?v=' . RE_REVISION . '"></script>';
	}

	if(!(strpos($viewuri,'recruitment/recruitment_portal') === false)){
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/recruitment_portals/main.js') . '?v=' . RE_REVISION . '"></script>';
		echo '<script src="' . base_url('assets/plugins/internal/desktop-notifications/notifications.js') . '?v=' . RE_REVISION . '"></script>';

	}

}

/**
 * forms add head components
 *
 */
function forms_add_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];

	if (!(strpos($viewuri, 'recruitment/forms') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/forms.css') . '"  rel="stylesheet" type="text/css" />';

	}

}

/**
 * forms add footer components
 *
 */
function forms_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];

}

/**
 * init recruitment customfield
 * @param  string $custom field 
 * @return [type]               
 */
function init_recruitment_customfield($custom_field = ''){
    $select = '';
    $recruitment_campaign_select = '';
    $recruitment_candidate_profile_select = '';
    $recruitment_interview_select = '';
    if($custom_field != ''){
        if($custom_field->fieldto == 'plan'){
            $select = 'selected';
        }
        if($custom_field->fieldto == 'campaign'){
            $recruitment_campaign_select = 'selected';
        }
        if($custom_field->fieldto == 'candidate'){
            $recruitment_candidate_profile_select = 'selected';
        }
        if($custom_field->fieldto == 'interview'){
            $recruitment_interview_select = 'selected';
        }
        
    }

    $html = '<option value="plan" '.$select.' >'. _l('recruitment_plan').'</option>';
    $html .= '<option value="campaign" '.$recruitment_campaign_select.' >'. _l('recruitment_campaign').'</option>';
    $html .= '<option value="candidate" '.$recruitment_candidate_profile_select.' >'. _l('rec_candidate_profile').'</option>';
    $html .= '<option value="interview" '.$recruitment_interview_select.' >'. _l('rec_interview_schedule').'</option>';

    echo new_html_entity_decode($html);
}

/**
 * init recruitment portal menu
 * @return [type] 
 */
function init_recruitment_portal_menu()
{
	$CI = &get_instance();
	$item ='';
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'recruitment/recruitment_portal') === false) || !(strpos($viewuri, 'recruitment/authentication_candidate') === false) ) {
		$item .= '<li class="customers-nav-item">';
		$item .= '<a href="'.site_url('recruitment/recruitment_portal').'">'._l("recruitment_portal").'';        
		$item .= '</a>';
		$item .= '</li>';

		if(!is_candidate_logged_in() && !(strpos($viewuri, 'recruitment/authentication_candidate/login') != false)){
			$item .= '<li class="customers-nav-item-login">';
			$item .= '<a href="'.site_url('recruitment/authentication_candidate/login').'">'._l("login").'';        
			$item .= '</a>';
			$item .= '</li>';
		}elseif(is_candidate_logged_in()){
			$data = [];
			$CI->load->model('recruitment/recruitment_model');

			$currentCandidate = $CI->recruitment_model->get_candidate_v1(get_candidate_id());
			$GLOBALS['current_candidate'] = $currentCandidate;
			$data['current_candidate'] = $currentCandidate;



			$item .= '<li class="dropdown customers-nav-item-profile">';
			$item .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			'.candidate_profile_image(get_candidate_id(),[
                    'staff-profile-image-small mright5',
                    ], 'small', ['data-toggle' => 'tooltip', 'data-title' => get_candidate_name(get_candidate_id()), 'data-placement' => 'bottom' ]).'			
			<span class="caret"></span>
			</a>
			<ul class="dropdown-menu animated fadeIn">
			<li class="customers-nav-item-edit-profile">
			<a href="'.site_url('recruitment/recruitment_portal/profile').'">
			'. _l('clients_nav_profile').'
			</a>
			</li>';
			if (is_gdpr() && get_option('show_gdpr_in_customers_menu') == '1'){

			$item .= '<li class="customers-nav-item-logout">
			<a href="'. site_url('recruitment/recruitment_portal/gdpr').'">
			'. _l('gdpr_short').'
			</a>
			</li>';
			}


			$item .= '<li class="customers-nav-item-logout">
			<a href="'. site_url('recruitment/recruitment_portal/applied_jobs').'">
			'. _l('re_applied_jobs').'
			</a>
			</li>';

			$item .= '<li class="customers-nav-item-logout">
			<a href="'. site_url('recruitment/recruitment_portal/interview_schedules').'">
			'. _l('rec_interview_schedules').'
			</a>
			</li>';

			$item .= '<li class="customers-nav-item-logout">
			<a href="'. site_url('recruitment/authentication_candidate/logout').'">
			'. _l('clients_nav_logout').'
			</a>
			</li>';

			$item .= '</ul>
			</li>';

			$item .= $CI->load->view('recruitment_portal/rec_portal/notifications', $data);
		}
	}
	echo new_html_entity_decode($item);

}

/**
 * change candidate status register other merge fields
 * @param  [type] $for 
 * @return [type]      
 */
function change_rec_candidate_status_merge_fields($for) {
	$for[] = 'change_candidate_status';

	return $for;
}

/**
 * Init inventory email templates and assign languages
 * @return void
 */
function add_change_candidate_status_email_templates()
{
	$CI = &get_instance();
	$data['change_candidate_status_templates'] = $CI->emails_model->get(['type' => 'change_candidate_status', 'language' => 'english']);
	$data['change_candidate_job_applied_status_templates'] = $CI->emails_model->get(['type' => 'change_candidate_job_applied_status', 'language' => 'english']);
	$data['change_candidate_interview_schedule_status_templates'] = $CI->emails_model->get(['type' => 'change_candidate_interview_schedule_status', 'language' => 'english']);
	$data['new_candidate_have_applied_templates'] = $CI->emails_model->get(['type' => 'new_candidate_have_applied', 'language' => 'english']);
	$data['send_interview_schedule_templates'] = $CI->emails_model->get(['type' => 'send_interview_schedule', 'language' => 'english']);

	$CI->load->view('recruitment/email_templates/change_candidate_status_email_template', $data);
}

/**
 * change candidate status register other merge fields
 * @param  [type] $for 
 * @return [type]      
 */
function change_candidate_job_applied_status_register_other_merge_fields($for) {
	$for[] = 'change_candidate_job_applied_status';

	return $for;
}

/**
 * change candidate status register other merge fields
 * @param  [type] $for 
 * @return [type]      
 */
function change_candidate_interview_schedule_status_register_other_merge_fields($for) {
	$for[] = 'change_candidate_interview_schedule_status';

	return $for;
}

function new_candidate_have_applied_register_other_merge_fields($for) {
	$for[] = 'new_candidate_have_applied';

	return $for;
}

/**
 * change candidate status register other merge fields
 * @param  [type] $for 
 * @return [type]      
 */
function send_interview_schedule_register_other_merge_fields($for) {
	$for[] = 'send_interview_schedule';

	return $for;
}

if(rec_get_status_modules('theme_style') == 1){
    /**
     * Clients area theme applied styles
     * @return null
     */
    function theme_style_rec_portal_area_head()
    {   
        theme_style_render(['general', 'tabs', 'buttons', 'customers', 'modals']);
        theme_style_custom_css_rec('theme_style_custom_clients_area');
    }

    /**
     * Custom CSS
     * @param  string $main_area clients or admin area options
     * @return null
     */
    function theme_style_custom_css_rec($main_area)
    {
        $clients_or_admin_area             = get_option($main_area);
        $custom_css_admin_and_clients_area = get_option('theme_style_custom_clients_and_admin_area');
        if (!empty($clients_or_admin_area) || !empty($custom_css_admin_and_clients_area)) {
            echo '<style id="theme_style_custom_css">' . PHP_EOL;
            if (!empty($clients_or_admin_area)) {
                $clients_or_admin_area = clear_textarea_breaks($clients_or_admin_area);
                echo new_html_entity_decode($clients_or_admin_area) . PHP_EOL;
            }
            if (!empty($custom_css_admin_and_clients_area)) {
                $custom_css_admin_and_clients_area = clear_textarea_breaks($custom_css_admin_and_clients_area);
                echo new_html_entity_decode($custom_css_admin_and_clients_area) . PHP_EOL;
                echo new_html_entity_decode($custom_css_admin_and_clients_area) . PHP_EOL;
            }
            echo '</style>' . PHP_EOL;
        }
    }
}