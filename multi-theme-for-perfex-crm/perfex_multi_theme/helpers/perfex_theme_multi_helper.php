<?php

defined('BASEPATH') or exit('No direct script access allowed');
$staff_id = get_staff_user_id();
// Check if customers theme is enabled
if (get_option('perfex_multi_theme_clients') == '1') {
    hooks()->add_action('app_customers_head', 'perfex_app_client_multi_head_includes');
}

/**
 * Theme customers login includes
 * @return stylesheet / script
 */
function perfex_multi_theme_staff_login()
{
    $CI = &get_instance();
    $css_dir = 'old';
    $current_version = $CI->app->get_current_db_version();
    if ($current_version > 294) {
        $css_dir = 'new';
    }
    $color = $CI->db->select('theme_css')->from(db_prefix() . '_multi_theme')->get()->row();
    if (isset($color)) {
        if (!empty($color->theme_css)) {
            echo '<link href="' . base_url('modules/perfex_multi_theme/assets/' . $css_dir . '/' . $color->theme_css . '-css/staff.css') . '"  rel="stylesheet" type="text/css" >';
            include 'style.php';
        } else {
            echo '<link href="' . base_url('modules/perfex_multi_theme/assets/' . $css_dir . '/' . 'default-css/staff.css') . '"  rel="stylesheet" type="text/css" >';
            include 'style.php';
        }
    }
}

/**
 * Theme clients footer includes
 * @return stylesheet
 */
function perfex_app_client_multi_head_includes()
{
    $staff_id = get_staff_user_id();
    $CI = &get_instance();
    $css_dir = 'old';
    $current_version = $CI->app->get_current_db_version();
    if ($current_version > 294) {
        $css_dir = 'new';
    }
    $color = $CI->db->select('theme_css')->from(db_prefix() . '_multi_theme')->where('staff_id', $staff_id)->get()->row();
    if (isset($color) && !empty($color->theme_css)) {
        echo '<link href="' . module_dir_url('perfex_multi_theme', 'assets/' . $css_dir . '/' . $color->theme_css . '-css/clients/customer.css') . '"  rel="stylesheet" type="text/css" >';
    } else {
        echo '<link href="' . base_url('modules/perfex_multi_theme/assets/' . $css_dir . '/' . 'default-css/clients/customer.css') . '"  rel="stylesheet" type="text/css" >';
    }
}

/**
 * Injects theme CSS
 * @return null
 */
function perfex_multi_theme_head_component()
{
    $staff_id = get_staff_user_id();
    $CI = &get_instance();
    $css_dir = 'old';
    $current_version = $CI->app->get_current_db_version();
    if ($current_version > 294) {
        $css_dir = 'new';
    }
    echo '<link id="sidebar_styles_color" href="' . base_url('modules/perfex_multi_theme/assets/' . $css_dir . '/' . 'sidebar.css') . '" rel="stylesheet" type="text/css" >';
    if ($CI->db->field_exists('staff_id', db_prefix() . '_multi_theme')) {
        $color = $CI->db->select('theme_css')->from(db_prefix() . '_multi_theme')->where('staff_id', $staff_id)->get()->row();
    }
    if (isset($color) && !empty($color->theme_css)) {
        echo '<link id="' . $color->theme_css . '_styles_color" href="' . base_url('modules/perfex_multi_theme/assets/' . $css_dir . '/'  . $color->theme_css . '-css/' . $color->theme_css . '_styles.css') . '"  rel="stylesheet" type="text/css" >';
        echo '<link id="sidebar_styles_color" href="' . base_url('modules/perfex_multi_theme/assets/' . $css_dir . '/' . 'sidebar.css') . '" rel="stylesheet" type="text/css" >';
    }
}
function current_theme_applied()
{
    $staff_id = get_staff_user_id();
    $CI = &get_instance();
    if ($CI->db->field_exists('staff_id', db_prefix() . '_multi_theme')) {
        $color = $CI->db->select('theme_css')->from(db_prefix() . '_multi_theme')->where('staff_id', $staff_id)->get()->row();
    }
    $css = null;
    if (isset($color) && !empty($color->theme_css)) {
        $css = $color->theme_css;
    }
    return $css;
}

/**
 * Check for login background upload
 * @return boolean
 */
function handle_login_bg_image_upload()
{
    $logoIndex = ['login_bg_image'];
    $success   = false;

    foreach ($logoIndex as $logo) {
        $index =  $logo;
        if (isset($_FILES[$index]) && !empty($_FILES[$index]['name']) && _perfex_upload_error($_FILES[$index]['error'])) {
            set_alert('warning', _perfex_upload_error($_FILES[$index]['error']));

            return false;
        }
        if (isset($_FILES[$index]['name']) && $_FILES[$index]['name'] != '') {
            hooks()->do_action('before_upload_company_logo_attachment');
            $path = get_upload_path_by_type('company');
            // Get the temp file path
            $tmpFilePath = $_FILES[$index]['tmp_name'];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $extension          = strtolower(pathinfo($_FILES[$index]['name'], PATHINFO_EXTENSION));
                $allowed_extensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif',
                    'svg',
                ];

                $allowed_extensions = array_unique(
                    hooks()->apply_filters('company_logo_upload_allowed_extensions', $allowed_extensions)
                );

                if (!in_array($extension, $allowed_extensions)) {
                    set_alert('warning', 'Image extension not allowed.');

                    continue;
                }

                // Setup our new file path
                $filename    = md5($logo . time()) . '.' . $extension;
                $newFilePath = $path . $filename;
                _maybe_create_upload_path($path);
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option($index, $filename);
                    $success = true;
                }
            }
        }
    }


    return $success;
}
/**
 * Check for dashboard background upload
 * @return boolean
 */
function handle_dashboard_bg_image_upload()
{
    $logoIndex = ['dashboard_bg_image'];
    $success   = false;

    foreach ($logoIndex as $logo) {
        $index =  $logo;
        if (isset($_FILES[$index]) && !empty($_FILES[$index]['name']) && _perfex_upload_error($_FILES[$index]['error'])) {
            set_alert('warning', _perfex_upload_error($_FILES[$index]['error']));

            return false;
        }
        if (isset($_FILES[$index]['name']) && $_FILES[$index]['name'] != '') {
            hooks()->do_action('before_upload_company_logo_attachment');
            $path = get_upload_path_by_type('company');
            // Get the temp file path
            $tmpFilePath = $_FILES[$index]['tmp_name'];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $extension          = strtolower(pathinfo($_FILES[$index]['name'], PATHINFO_EXTENSION));
                $allowed_extensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif',
                    'svg',
                ];

                $allowed_extensions = array_unique(
                    hooks()->apply_filters('company_logo_upload_allowed_extensions', $allowed_extensions)
                );

                if (!in_array($extension, $allowed_extensions)) {
                    set_alert('warning', 'Image extension not allowed.');

                    continue;
                }

                // Setup our new file path
                $filename    = md5($logo . time()) . '.' . $extension;
                $newFilePath = $path . $filename;
                _maybe_create_upload_path($path);
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option($index, $filename);
                    $success = true;
                }
            }
        }
    }


    return $success;
}
function handleMultiThemeImageUpload($id)
{
    $logoIndex = ['bakground_image'];
    $success   = false;
    $CI = &get_instance();
    foreach ($logoIndex as $logo) {
        $index =  $logo;
        if (isset($_FILES[$index]) && !empty($_FILES[$index]['name']) && _perfex_upload_error($_FILES[$index]['error'])) {
            set_alert('warning', _perfex_upload_error($_FILES[$index]['error']));
            return false;
        }
        if (isset($_FILES[$index]['name']) && $_FILES[$index]['name'] != '') {
            $path = get_upload_path_by_type('perfex_multi_theme');
            // Get the temp file path
            $tmpFilePath = $_FILES[$index]['tmp_name'];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $extension          = strtolower(pathinfo($_FILES[$index]['name'], PATHINFO_EXTENSION));
                $allowed_extensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif',
                    'svg',
                ];

                if (!in_array($extension, $allowed_extensions)) {
                    set_alert('warning', 'Image extension not allowed.');
                    continue;
                }
                _maybe_create_upload_path($path);

                // Setup our new file path
                $filename    = md5($logo . time()) . '.' . $extension;
                $newFilePath = $path . $filename;
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $CI->db->where('id', $id);
                    $CI->db->update(db_prefix() . 'multi_theme_setup', ['bakground_image' => $filename]);
                    $success = true;
                }
            }
        }
    }
    return $success;
}

function upload_path_by_type_multi_theme($path, $type)
{
    if ($type = 'perfex_multi_theme') {
        $path = PERFEX_MULTI_THEME_MODULE_UPLOADS_FOLDER;
    }
    return $path;
}

function get_multi_themes(){
    $CI = &get_instance();
    if(!class_exists('main_model')){
        $CI->load->model('perfex_multi_theme/main_model');
    }
    return $CI->main_model->get_themes();
}
function get_active_theme(){
    $CI = &get_instance();
    if(!class_exists('main_model')){
        $CI->load->model('perfex_multi_theme/main_model');
    }
    $result =  $CI->main_model->get_active_theme_by_staff();
        return array('color' => isset($result->theme_color) ? $result->theme_color : '', 'bgimg' =>  isset($result->bakground_image) ?  $result->bakground_image : '');
}