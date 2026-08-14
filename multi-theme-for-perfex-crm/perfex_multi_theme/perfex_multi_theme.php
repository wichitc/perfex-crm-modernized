<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Multi Theme
Description: Multi Themes
Version: 1.0.2
Author: Zonvoir
Author URI: https://zonvoir.com
Requires at least: 2.3.2
*/
define('PERFEX_MULTI_THEME_MODULE_NAME', 'perfex_multi_theme');
if (!defined('PERFEX_MULTI_THEME_MODULE_UPLOADS_FOLDER')) {
    define('PERFEX_MULTI_THEME_MODULE_UPLOADS_FOLDER', FCPATH . 'uploads/perfex_multi_theme' . '/');
}
hooks()->add_action('app_admin_footer', 'multi_theme_selection_sidebar');
hooks()->add_action('app_admin_head', 'perfex_multi_theme_head_component');
hooks()->add_action('app_admin_authentication_head', 'perfex_multi_theme_staff_login');
$crm_version = get_app_version();
$crm_version_class = version_compare($crm_version, '3.2.1', '<') ? 'old-version' : 'latest-version';

hooks()->add_action('app_admin_head', function () use ($crm_version_class) {
    $theme = get_active_theme();
    $element = '<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.body.classList.add("' . $crm_version_class . '");
        });
    </script><style>:root {--main-bg-color: ' . $theme['color'] . ';}';
    if (isset($theme['bgimg']) && !empty($theme['bgimg'])) {
        $element .= ' .active_theme{background-image: url("' . site_url('uploads/perfex_multi_theme/'.$theme['bgimg']) . '") !important;}';
    } else {
        $element .= ' .active_theme{background-image: url("' . site_url('modules/perfex_multi_theme/assets/new/bg_img/bg.jpg') . '") !important;}';
    }
    $element .='</style>';
    echo $element;
});
$CI = &get_instance();
register_activation_hook(PERFEX_MULTI_THEME_MODULE_NAME, 'perfex_multi_theme_activation_hook');
function perfex_multi_theme_activation_hook()
{
    require(__DIR__ . '/install.php');
}
register_uninstall_hook(PERFEX_MULTI_THEME_MODULE_NAME, 'perfex_multi_theme_uninstall_hook');
function perfex_multi_theme_uninstall_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/uninstall.php');
}
function multi_theme_selection_sidebar()
{
    $CI = &get_instance();
    $CI->load->view('perfex_multi_theme/sidebar');
}
hooks()->add_action('admin_init', 'perfex_multi_theme_module_init_menu_items');
function perfex_multi_theme_module_init_menu_items()
{
    $CI = &get_instance();
    // if (is_admin()) {
        $CI->app_menu->add_setup_menu_item('perfex_multi_theme_setup', [
            'slug' => 'perfex_multi_theme_setup',
            'name' => _l('perfex_multi_theme_settings_first'),
            'href' => admin_url('perfex_multi_theme/main'),
            'position' => 48,
        ]);
    // }
}
register_language_files(PERFEX_MULTI_THEME_MODULE_NAME, ['perfex_multi_theme']);
$CI->load->helper(PERFEX_MULTI_THEME_MODULE_NAME . '/perfex_theme_multi');
hooks()->add_filter('get_upload_path_by_type', 'upload_path_by_type_multi_theme', 10, 2);
