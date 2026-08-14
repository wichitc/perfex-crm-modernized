<?php
defined('BASEPATH') or exit('No direct script access allowed');
use app\services\ValidatesContact;

/**
 * Recruitment portal Controller
 */
class Recruitment_portal extends App_Controller
{   


    public $template = [];

    public $data = [];

    public $use_footer = true;

    public $use_submenu = true;

    public $use_navigation = true;

    /**
     * construct
     */
    public function __construct() {

        hooks()->do_action('after_clients_area_init', $this);

        parent::__construct();

        $this->load->library('app_rec_portal_area_constructor');

        $this->load->model('recruitment_model');

        if (is_candidate_logged_in()) {
            $this->load->model('Authentication_candidate_model');

            $currentUser = $this->recruitment_model->get_candidate_v1(get_candidate_id());
            // Deleted or inactive but have session
            if (!$currentUser || $currentUser->active == 0) {
                $this->Authentication_candidate_model->logout();
                redirect(site_url('recruitment/recruitment_portal'));
            }
            $GLOBALS['current_candidate'] = $currentUser;
        }
    }

    /**
     * index
     * @return view
     */
    public function index()
    {   
        $data['title']            = _l('recruitment_portal');
        $data['rec_campaingn'] = $this->recruitment_model->do_recruitment_portal_search(true, '', $page = 1, $count = false, $where = []);
        $this->data($data);

        $this->view('recruitment_portal/portal');
        $this->layout();

        
    }

    /**
     * job detail
     * @return view 
     */
    public function job_detail($id ='')
    {   
        $data['title']            = _l('recruitment_portal');
        $data['rec_campaingn'] = $this->recruitment_model->get_rec_campaign_detail($id);
        $data['rec_channel'] = $this->recruitment_model->get_recruitment_channel_form_campaingn($id);
        $data['id'] = $id;

        if(is_candidate_logged_in()){ 
            $candidate_id = get_candidate_id();
            $get_candidates = $this->recruitment_model->get_candidates($candidate_id);
            if(isset($get_candidates->applied_job_activate)){
                $data['applied_job_activate'] = $get_candidates->applied_job_activate;
            }else{
                $data['applied_job_activate'] = [];
            }
        }else{
            $data['applied_job_activate'] = [];
        }

        if($data['rec_campaingn']->job_meta_title != null && strlen($data['rec_campaingn']->job_meta_title) > 0){
            $data['title']            = $data['rec_campaingn']->job_meta_title;
        }else{
            $data['title']            = $data['rec_campaingn']->campaign_name;
        }
        $data['meta_description']            = $data['rec_campaingn']->job_meta_description;

        $this->data($data);

        $this->view('recruitment_portal/job_detail');
        $this->layout();

        
    }

    /**
     * search job
     * 
     */
    public function search_job()
    {

        $search = $this->input->post('search');
        $page = $this->input->post('page');
        $status = true;

        $data['title']            = _l('showing_search_result', $search);
        $data['rec_campaingn'] = $this->recruitment_model->do_recruitment_portal_search($status, $search, $page = 1, $count = false, $where = []);
        $data['rec_campaingn_total'] = $this->recruitment_model->do_recruitment_portal_search($status, $search, $page = 1, $count = true, $where = []);

        $data['search'] = $search;
        $data['page'] = (float)$page+1;
        $this->data($data);

        $this->view('recruitment_portal/portal');
        $this->layout();

    }

    /**
     * show more job
     *  
     */
    public function show_more_job(){

        $search = $this->input->post('search');
        $page = $this->input->post('page');

        $status = true;

        $data = $this->recruitment_model->do_recruitment_show_more_job($status, $search, $page, $count = false, $where = []);

        echo json_encode([
                'page'=> $data['page'],
                'data' => $data['value'],
                'status' => $data['status']
            ]);
        die;

    }

    /**
     * job live search
     * @return json 
     */
    public function job_live_search()
    {
        $search = $this->input->post('search');
        $page = $this->input->post('page');
        $status = true;
        
        $data = $this->recruitment_model->do_recruitment_show_more_job($status, $search, $page = 1, $count = false, $where = []);

        $rec_campaingn_total = $this->recruitment_model->do_recruitment_portal_search($status, $search, $page = 1, $count = true, $where = []);

        echo json_encode([
                'page'=> $data['page'],
                'data' => $data['value'],
                'status' => $data['status'],
                'rec_campaingn_total' => $rec_campaingn_total
            ]);
        die;


    }

    /**
     * send mail list candidate
     * @return redirect
     */
    public function send_mail_list_candidate() {
        if ($this->input->post()) {
            $data = $this->input->post();
            $job_detail_id = '';
            if(isset($data['job_detail_id'])){
                $job_detail_id .= $data['job_detail_id'] ;
                unset($data['job_detail_id']);
            }

            $rs = $this->recruitment_model->portal_send_mail_to_friend($data);
            if ($rs == true) {
                set_alert('success', _l('send_mail_successfully'));

            }

            if(isset($job_detail_id)){
                redirect(site_url('recruitment/recruitment_portal/job_detail/'.$job_detail_id));

            }else{
                redirect(site_url('recruitment/recruitment_portal'));

            }

        }
    }

    /**
     * profile
     * @return [type] 
     */
    public function profile()
    {
        if(!is_candidate_logged_in()){ 
            redirect_after_login_to_current_url();
            redirect(site_url('recruitment/authentication_candidate/login'));
        }

        if ($this->input->post('profile')) {
            $this->form_validation->set_rules('candidate_name', _l('client_firstname'), 'required');
            $this->form_validation->set_rules('last_name', _l('client_lastname'), 'required');

            if ($this->form_validation->run() !== false) {

                $data = $this->input->post();
                if(isset($data['profile'])){
                    unset($data['profile']);
                }
                $success = $this->recruitment_model->update_cadidate($data, get_candidate_id());

                handle_rec_candidate_file(get_candidate_id());
                handle_rec_candidate_avar_file(get_candidate_id());
                if ($success == true) {
                    set_alert('success', _l('clients_profile_updated'));
                }

                redirect(site_url('recruitment/recruitment_portal/profile'));
            }
        } elseif ($this->input->post('change_password')) {
            $this->form_validation->set_rules('oldpassword', _l('clients_edit_profile_old_password'), 'required');
            $this->form_validation->set_rules('newpassword', _l('clients_edit_profile_new_password'), 'required');
            $this->form_validation->set_rules('newpasswordr', _l('clients_edit_profile_new_password_repeat'), 'required|matches[newpassword]');
            if ($this->form_validation->run() !== false) {
                $success = $this->recruitment_model->change_candidate_password(
                    get_candidate_id(),
                    $this->input->post('oldpassword', false),
                    $this->input->post('newpasswordr', false)
                );

                if (is_array($success) && isset($success['old_password_not_match'])) {
                    set_alert('danger', _l('client_old_password_incorrect'));
                } elseif ($success == true) {
                    set_alert('success', _l('client_password_changed'));
                }

                redirect(site_url('recruitment/recruitment_portal/profile'));
            }
        }
        $candidate_id = get_candidate_id();
        $data['title'] = _l('clients_profile_heading');
        $data['candidate'] = $this->recruitment_model->get_candidates($candidate_id);
        $data['csv'] = $this->recruitment_model->get_candidate_attachments($candidate_id);
        $data['skills'] = $this->recruitment_model->get_skill();
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $this->data($data);
        $this->view('recruitment_portal/candidates/profiles/candidate_profile');
        $this->layout();
    }

    /**
     * applied jobs
     * @return [type] 
     */
    public function applied_jobs()
    {
        if(!is_candidate_logged_in()){ 
            redirect_after_login_to_current_url();
            redirect(site_url('recruitment/authentication_candidate/login'));
        }

        $candidate_id = get_candidate_id();
        $data['title'] = _l('re_applied_jobs');
        $data['candidate'] = $this->recruitment_model->get_candidates($candidate_id);

        $this->data($data);
        $this->view('recruitment_portal/candidates/applied_jobs/applied_job');
        $this->layout();
    }

    /**
     * applied now
     * @param  [type] $campaingn_id 
     * @return [type]               
     */
    public function applied_now($campaingn_id, $form_key)
    {
        if(!is_candidate_logged_in()){ 
            redirect_after_login_to_current_url();
            redirect(site_url('recruitment/authentication_candidate/login'));
        }

        $candidate_id = get_candidate_id();

        $status = '1';
        $message = 'rec_Thank_you_for_your_apply_for_for_this_position';
        $this->db->where('form_key', $form_key);
        $rec_campaign_form_web = $this->db->get(db_prefix() . 'rec_campaign_form_web')->row();
        if ($rec_campaign_form_web) {
            $status = $rec_campaign_form_web->lead_status;
            $message = $rec_campaign_form_web->success_submit_msg;
        }

        $data['title'] = _l('re_applied_jobs');
        $data['candidate'] = $this->recruitment_model->candidate_apply($candidate_id, $campaingn_id, $status);
        set_alert('success', _l($message));
        redirect(site_url('recruitment/recruitment_portal/job_detail/'.$campaingn_id));
    }

    /**
     * interview schedules
     * @return [type] 
     */
    public function interview_schedules()
    {
        if(!is_candidate_logged_in()){ 
            redirect_after_login_to_current_url();
            redirect(site_url('recruitment/authentication_candidate/login'));
        }

        $candidate_id = get_candidate_id();
        $data['title'] = _l('rec_interview_schedules');
        $data['list_interview'] = $this->recruitment_model->get_interview_by_candidate($candidate_id);

        $this->data($data);
        $this->view('recruitment_portal/candidates/interview_schedules/interview_schedule');
        $this->layout();
    }

    /**
     * remove profile image
     * @param  [type] $image_id 
     * @return [type]           
     */
    public function remove_profile_image($image_id)
    {
        $id = get_candidate_id();

        hooks()->do_action('before_remove_candidate_profile_image', $id);

        if (file_exists(CANDIDATE_IMAGE_UPLOAD . $id)) {
            delete_dir(CANDIDATE_IMAGE_UPLOAD . $id);
        }

        $this->db->where('id', $image_id);
        $this->db->delete(db_prefix() . 'files');

        if ($this->db->affected_rows() > 0) {
            redirect(site_url('recruitment/recruitment_portal/profile'));
        }
    }

    /**
     * remove candidate cv
     * @param  [type] $cv_id 
     * @return [type]        
     */
    public function remove_candidate_cv($cv_id)
    {
        $id = get_candidate_id();

        if (file_exists(CANDIDATE_CV_UPLOAD . $id)) {
            delete_dir(CANDIDATE_CV_UPLOAD . $id);
        }

        $this->db->where('id', $cv_id);
        $this->db->delete(db_prefix() . 'files');

        if ($this->db->affected_rows() > 0) {
            redirect(site_url('recruitment/recruitment_portal/profile'));
        }
    }

    /**
     * candidate_file
     * @param  [type] $id     
     * @param  [type] $rel_id 
     * @return [type]         
     */
    public function candidate_file($id, $rel_id) {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin'] = is_admin();
        $data['file'] = $this->recruitment_model->get_file($id, $rel_id);
        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }
        $this->data($data);
        $this->view('candidate_profile/_file');
        $this->layout();
    }

    /**
     * delete applied job
     * @param  [type] $applied_job_id 
     * @return [type]                 
     */
    public function delete_applied_job($applied_job_id)
    {

        $this->db->where('id', $applied_job_id);
        $this->db->update(db_prefix() . 'rec_applied_jobs', ['activate' => '0']);
        set_alert('success', _l('deleted'));

        redirect(site_url('recruitment/recruitment_portal/applied_jobs'));
    }

    /**
     * gdpr
     * @return [type] 
     */
    public function gdpr()
    {
        $this->load->model('gdpr_model');

        if (is_gdpr()
            && $this->input->post('removal_request')
            && get_option('gdpr_contact_enable_right_to_be_forgotten') == '1') {
            $success = $this->gdpr_model->add_removal_request([
                'description'  => nl2br($this->input->post('removal_description')),
                'request_from' => get_candidate_name(get_candidate_id()),
                'contact_id'   => get_candidate_id(),
                'clientid'     => get_candidate_id(),
            ]);
            if ($success) {
                if(1==2){
                    send_gdpr_email_template('gdpr_removal_request_by_customer', get_contact_user_id());
                }
                set_alert('success', _l('data_removal_request_sent'));
            }
            redirect(site_url('recruitment/recruitment_portal/gdpr'));
        }

        $data['title'] = _l('gdpr');
        $this->data($data);
        $this->view('recruitment_portal/gdpr');
        $this->layout();
    }

    /**
     * tem and conditions
     * @return [type] 
     */
    public function tem_and_conditions()
    {
        $data['terms'] = get_option('terms_and_conditions');
        $data['title'] = _l('terms_and_conditions') . ' - ' . get_option('companyname');
        $this->data($data);
        $this->view('recruitment_portal/terms_and_conditions');
        $this->layout();        
    }

    public function candidate_consent($key)
    {
        if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '0' || !is_gdpr()) {
            show_error('This page is currently disabled, check back later.');
        }

        $this->db->where('meta_value', $key);
        $this->db->where('meta_key', 'consent_key');
        $meta = $this->db->get(db_prefix() . 'user_meta')->row();

        if (!$meta) {
            show_404();
        }

        $candidate = $this->recruitment_model->get_candidates($meta->candidate_id);

        if (!$candidate) {
            show_404();
        }

        $this->load->model('gdpr_model');

        if ($this->input->post()) {
            foreach ($this->input->post('action') as $purpose_id => $action) {
                $purpose = $this->gdpr_model->get_consent_purpose($purpose_id);
                if ($purpose) {
                    $this->gdpr_model->add_consent([
                        'action'                     => $action,
                        'purpose_id'                 => $purpose_id,
                        'candidate_id'                 => $candidate->id,
                        'description'                => 'Consent Updated From Web Form',
                        'opt_in_purpose_description' => $purpose->description,
                    ]);
                }
            }
            redirect($_SERVER['HTTP_REFERER']);
        }

        $data['candidate']  = $candidate;
        $data['purposes'] = $this->gdpr_model->get_consent_purposes($candidate->id, 'candidate');
        $data['title']    = _l('gdpr') . ' - ' . get_candidate_name(get_candidate_id());

        $data['bodyclass'] = 'consent';
        $this->data($data);
        $this->view('recruitment_portal/consent');
        no_index_customers_area();

        $this->disableNavigation();
        $this->disableSubMenu();
        $this->layout();
    }

    /* Set notifications to read */
    public function set_notifications_read()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'success' => $this->recruitment_model->set_notifications_read(),
            ]);
        }
    }

    /**
     * set notification read inline
     * @param [type] $id 
     */
    public function set_notification_read_inline($id)
    {
        $this->recruitment_model->set_notification_read_inline($id);
    }

    /**
     * set desktop notification read
     * @param [type] $id 
     */
    public function set_desktop_notification_read($id)
    {
        $this->recruitment_model->set_desktop_notification_read($id);
    }

    /**
     * mark all notifications as read inline
     * @return [type] 
     */
    public function mark_all_notifications_as_read_inline()
    {
        $this->recruitment_model->mark_all_notifications_as_read_inline();
    }

    /**
     * notifications check
     * @return [type] 
     */
    public function notifications_check()
    {
        $notificationsIds = [];
        if (get_option('desktop_notifications') == '1') {
            $notifications = $this->recruitment_model->get_user_notifications();

            $notificationsPluck = array_filter($notifications, function ($n) {
                return $n['isread'] == 0;
            });

            $notificationsIds = array_pluck($notificationsPluck, 'id');
        }

        echo json_encode([
        'html'             => $this->load->view('recruitment_portal/rec_portal/notifications', ['notifications_check' => true], true),
        'notificationsIds' => $notificationsIds,
        ]);
    }

    /**
     * profile
     * @param  string $id 
     * @return [type]     
     */
    public function notifications_detail($id = '')
    {
        if ($id == '') {
            $id = get_candidate_id();
        }

        $data['staff_p']     = $this->recruitment_model->get_candidate_v1($id);

        if (!$data['staff_p']) {
            blank_page('Candidate Not Found', 'danger');
        }

        $data['title']             = _l('staff_profile_string') . ' - ' . $data['staff_p']->candidate_name . ' ' . $data['staff_p']->last_name;
        // notifications
        $total_notifications = total_rows(db_prefix() . 'rec_notifications', [
            'touserid' => get_candidate_id(),
        ]);
        $data['total_pages'] = ceil($total_notifications / $this->recruitment_model->get_candidate_notifications_limit());

        $this->data($data);
        $this->view('recruitment_portal/rec_portal/notifications_detail');
        $this->layout();   

    }

    /**
     * notifications
     * @return [type] 
     */
    public function notifications()
    {
        if ($this->input->post()) {
            $page   = $this->input->post('page');
            $offset = ($page * $this->recruitment_model->get_candidate_notifications_limit());
            $this->db->limit($this->recruitment_model->get_candidate_notifications_limit(), $offset);
            $this->db->where('touserid', get_candidate_id());
            $this->db->order_by('date', 'desc');
            $notifications = $this->db->get(db_prefix() . 'rec_notifications')->result_array();
            $i             = 0;
            foreach ($notifications as $notification) {
                if (($notification['fromcompany'] == null && $notification['fromuserid'] != 0) || ($notification['fromcompany'] == null && $notification['fromclientid'] != 0)) {
                    if ($notification['fromuserid'] != 0) {
                        $notifications[$i]['profile_image'] = '<a href="#">' . staff_profile_image($notification['fromuserid'], [
                            'staff-profile-image-small',
                            'img-circle',
                            'pull-left',
                        ]) . '</a>';
                    } else {
                        $notifications[$i]['profile_image'] = '<a href="#">
                    <img class="client-profile-image-small img-circle pull-left" src="' . contact_profile_image_url($notification['fromclientid']) . '"></a>';
                    }
                } else {
                    $notifications[$i]['profile_image'] = '';
                    $notifications[$i]['full_name']     = '';
                }
                $additional_data = '';
                if (!empty($notification['additional_data'])) {
                    $additional_data = unserialize($notification['additional_data']);
                    $x               = 0;
                    foreach ($additional_data as $data) {
                        if (strpos($data, '<lang>') !== false) {
                            $lang = get_string_between($data, '<lang>', '</lang>');
                            $temp = _l($lang);
                            if (strpos($temp, 'project_status_') !== false) {
                                $status = get_project_status_by_id(strafter($temp, 'project_status_'));
                                $temp   = $status['name'];
                            }
                            $additional_data[$x] = $temp;
                        }
                        $x++;
                    }
                }
                $notifications[$i]['description'] = _l($notification['description'], $additional_data);
                $notifications[$i]['date']        = time_ago($notification['date']);
                $notifications[$i]['full_date']   = $notification['date'];
                $i++;
            }
            echo json_encode($notifications);
            die;
        }
    }

    /**
     * version 1.1.2
     * seperation recruitment portal
     */

    public function layout($notInThemeViewFiles = false)
    {

        /**
         * Navigation and submenu
         * @deprecated 2.3.2
         * @var boolean
         */

        $this->data['use_navigation'] = $this->use_navigation == true;
        $this->data['use_submenu']    = $this->use_submenu == true;

        /**
         * @since  2.3.2 new variables
         * @var array
         */
        $this->data['navigationEnabled'] = $this->use_navigation == true;
        $this->data['subMenuEnabled']    = $this->use_submenu == true;

        /**
         * Theme head file
         * @var string
         */
        $this->template['head'] = $this->load->view('recruitment_portal/rec_portal/head', $this->data, true);

        $GLOBALS['customers_head'] = $this->template['head'];

        /**
         * Load the template view
         * @var string
         */
        $module                       = CI::$APP->router->fetch_module();
        $this->data['current_module'] = $module;

        $viewPath = !is_null($module) || $notInThemeViewFiles ? $this->view : 'recruitment_portal/' . $this->view;

        $this->template['view']    = $this->load->view($viewPath, $this->data, true);
        $GLOBALS['customers_view'] = $this->template['view'];

        /**
         * Theme footer
         * @var string
         */
        $this->template['footer'] = $this->use_footer == true
        ? $this->load->view('recruitment_portal/rec_portal/footer', $this->data, true)
        : '';
        $GLOBALS['customers_footer'] = $this->template['footer'];

        /**
         * @deprecated 2.3.0
         * Theme scripts.php file is no longer used since vresion 2.3.0, add app_customers_footer() in themes/[theme]/index.php
         * @var string
         */
        $this->template['scripts'] = '';
        if (file_exists(VIEWPATH . 'recruitment_portal/scripts.php')) {
            if (ENVIRONMENT != 'production') {
                trigger_error(sprintf('%1$s', 'Clients area theme file scripts.php file is no longer used since version 2.3.0, add app_customers_footer() in themes/[theme]/index.php. You can check the original theme index.php for example.'));
            }

            $this->template['scripts'] = $this->load->view('recruitment_portal/scripts', $this->data, true);
        }

        /**
         * Load the theme compiled template
         */
        $this->load->view('recruitment_portal/index', $this->template);
    }

    /**
     * Sets view data
     * @param  array $data
     * @return core/ClientsController
     */
    public function data($data)
    {
        if (!is_array($data)) {
            return false;
        }

        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Set view to load
     * @param  string $view view file
     * @return core/ClientsController
     */
    public function view($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Sets view title
     * @param  string $title
     * @return core/ClientsController
     */
    public function title($title)
    {
        $this->data['title'] = $title;

        return $this;
    }

    /**
     * Disables theme navigation
     * @return core/ClientsController
     */
    public function disableNavigation()
    {
        $this->use_navigation = false;

        return $this;
    }

    /**
     * Disables theme navigation
     * @return core/ClientsController
     */
    public function disableSubMenu()
    {
        $this->use_submenu = false;

        return $this;
    }

    /**
    * Disables theme footer
    * @return core/ClientsController
    */
    public function disableFooter()
    {
        $this->use_footer = false;

        return $this;
    }

    


}
