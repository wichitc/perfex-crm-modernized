<?php

defined('BASEPATH') or exit('No direct script access allowed');

class App_rec_portal_area_constructor
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();

        $this->ci->load->library('form_validation');
        $this->ci->form_validation->set_error_delimiters('<p class="text-danger alert-validation">', '</p>');

        $this->ci->form_validation->set_message('required', _l('form_validation_required'));
        $this->ci->form_validation->set_message('valid_email', _l('form_validation_valid_email'));
        $this->ci->form_validation->set_message('matches', _l('form_validation_matches'));
        $this->ci->form_validation->set_message('is_unique', _l('form_validation_is_unique'));

        $this->ci->load->model('recruitment/recruitment_model');
        $this->ci->load->model('recruitment/authentication_candidate_model');

        $vars = [];

        include_once(module_dir_path(RECRUITMENT_MODULE_NAME, 'views/recruitment_portal/rec_portal/functions.php'));
        init_rec_portal_area_assets();

         if (is_candidate_logged_in()) {
            $currentUser = $this->ci->recruitment_model->get_candidate_v1(get_candidate_id());
            $GLOBALS['current_candidate'] = $currentUser;

            if (!$currentUser || $currentUser->active == 0) {
                $this->ci->authentication_candidate_model->logout();
                redirect(site_url('recruitment/recruitment_portal'));
            }

            $vars['current_candidate']                         = $currentUser;
        }


        $vars['menu']            = $this->ci->app_menu->get_theme_items();
        $vars['isRTL']           = (is_rtl_rec(true) ? 'true' : 'false');

        $this->ci->load->vars($vars);
    }
}
