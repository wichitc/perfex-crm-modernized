<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Asset Management
Module URI: https://codecanyon.net/item/assets-management-module-for-perfex-crm/25615418
Description: Complete asset management module with allocation, check-in/out, maintenance scheduling, reservations, depreciation tracking, barcode support, webhooks, and more.
Version: 1.3.0
Requires at least: 2.3.*
Author: Themesic Interactive
Author URI: https://codecanyon.net/user/themesic/portfolio
*/

define('ASSETS_MODULE', 'assets');
define('ASSETS_MODULE_VERSION', '1.3.0');
define('ASSETS_PATH', 'modules/assets/uploads/');
define('ASSETS_UPLOAD_FOLDER', module_dir_path(ASSETS_MODULE, 'uploads'));

require_once __DIR__.'/vendor/autoload.php';
modules\assets\core\Apiinit::the_da_vinci_code(ASSETS_MODULE);
modules\assets\core\Apiinit::ease_of_mind(ASSETS_MODULE);

// Register hooks
hooks()->add_action('admin_init', 'assets_permissions');
hooks()->add_action('admin_init', 'assets_module_init_menu_items');
hooks()->add_action('app_admin_head', 'assets_add_head_components');
hooks()->add_action('app_admin_footer', 'assets_add_footer_components');

/**
 * Injects CSS into admin head.
 */
function assets_add_head_components()
{
    $CI = &get_instance();
    echo '<link href="'.base_url('modules/assets/css/style.css').'?v='.ASSETS_MODULE_VERSION.'"  rel="stylesheet" type="text/css" />';
    // Lightbox for image preview
    if (strpos(uri_string(), 'assets') !== false) {
        echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />';
    }
}

/**
 * Injects JS into admin footer.
 */
function assets_add_footer_components()
{
    $CI = &get_instance();
    if (strpos(uri_string(), 'assets') !== false) {
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>';
        echo '<script src="'.base_url('modules/assets/js/assets.js').'?v='.ASSETS_MODULE_VERSION.'"></script>';
    }
}

// Register activation module hook
register_activation_hook(ASSETS_MODULE, 'assets_module_activation_hook');

/**
 * Load the module helper.
 */
$CI = &get_instance();

function assets_module_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__.'/install.php';
}

// Register language files
register_language_files(ASSETS_MODULE, [ASSETS_MODULE]);

$CI = &get_instance();
$CI->load->helper(ASSETS_MODULE.'/asset');

/**
 * Init assets module menu items.
 */
function assets_module_init_menu_items()
{
    $CI = &get_instance();
    if (has_permission('assets', '', 'view') || is_admin()) {
        // Main Assets Menu
        $CI->app_menu->add_sidebar_menu_item('assets', [
            'name'     => _l('asset_management'),
            'icon'     => 'fa fa-bank',
            'position' => 40,
        ]);
        
        // Dashboard
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'assets_dashboard',
            'name'     => _l('dashboard'),
            'icon'     => 'fa fa-tachometer',
            'href'     => admin_url('assets/dashboard'),
            'position' => 0,
        ]);
        
        // Assets List
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'assets_menu',
            'name'     => _l('assets'),
            'icon'     => 'fa fa-bank',
            'href'     => admin_url('assets/manage_assets'),
            'position' => 1,
        ]);

        // Allocations
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'allocations',
            'name'     => _l('allocation'),
            'icon'     => 'fa fa-pencil',
            'href'     => admin_url('assets/allocation'),
            'position' => 2,
        ]);

        // Revocations
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'evictions',
            'name'     => _l('eviction'),
            'icon'     => 'fa fa-pencil-square',
            'href'     => admin_url('assets/eviction'),
            'position' => 3,
        ]);

        // Check-in/Check-out
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'checkouts',
            'name'     => _l('checkin_out'),
            'icon'     => 'fa fa-exchange',
            'href'     => admin_url('assets/checkouts'),
            'position' => 4,
        ]);

        // Reservations
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'reservations',
            'name'     => _l('reservations'),
            'icon'     => 'fa fa-calendar',
            'href'     => admin_url('assets/reservations'),
            'position' => 5,
        ]);

        // Maintenance
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'maintenance',
            'name'     => _l('maintenance'),
            'icon'     => 'fa fa-wrench',
            'href'     => admin_url('assets/maintenance'),
            'position' => 6,
        ]);

        // Transfers
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'transfers',
            'name'     => _l('transfers'),
            'icon'     => 'fa fa-truck',
            'href'     => admin_url('assets/transfers'),
            'position' => 7,
        ]);

        // Depreciation
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'depreciations',
            'name'     => _l('depreciation'),
            'icon'     => 'fa fa-legal',
            'href'     => admin_url('assets/depreciation'),
            'position' => 8,
        ]);

        // Reports
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'reports',
            'name'     => _l('reports'),
            'icon'     => 'fa fa-file-text',
            'href'     => admin_url('assets/reports'),
            'position' => 9,
        ]);

        // Import
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'import',
            'name'     => _l('import'),
            'icon'     => 'fa fa-upload',
            'href'     => admin_url('assets/import'),
            'position' => 10,
        ]);

        // Audit Log
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'audit_log',
            'name'     => _l('audit_log'),
            'icon'     => 'fa fa-history',
            'href'     => admin_url('assets/audit_log'),
            'position' => 11,
        ]);

        // Settings
        $CI->app_menu->add_sidebar_children_item('assets', [
            'slug'     => 'settings',
            'name'     => _l('setting'),
            'icon'     => 'fa fa-cogs',
            'href'     => admin_url('assets/setting'),
            'position' => 99,
        ]);
    }
}

/**
 * Register permissions.
 */
function assets_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view').'('._l('permission_global').')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('assets', $capabilities, _l('assets'));
}

// Inject upload folder location for assets module
hooks()->add_filter('get_upload_path_by_type', 'asset_upload_folder', 10, 2);
function asset_upload_folder($path, $type)
{
    if ('assets' == $type) {
        return ASSETS_UPLOAD_FOLDER.'/';
    }
    return $path;
}

// Add Menu In Customer Side
hooks()->add_action('customers_navigation_start', 'add_asset_menu');
function add_asset_menu()
{
    $CI = &get_instance();
    if (is_client_logged_in()) {
        $CI->load->model('assets/assets_model');
        $client_user_id = $CI->session->userdata('client_user_id');
        $where["find_in_set('".$client_user_id."',`belongs_to`) <>"] = 0;
        $allocated_asset = $CI->assets_model->get_clients_assign_assets('assets', $where);
        if (!empty($allocated_asset) && has_contact_permission('asset')) {
            echo '<li class="customers-nav-item-contracts">
                <a href="'.site_url('assets/client').'">'._l('assets').'</a>
            </li>';
        }
    }
}

hooks()->add_filter('get_contact_permissions', 'add_asset_permission');
function add_asset_permission($permissions)
{
    $permissions[] = [
        'id'         => 7,
        'name'       => _l('assets'),
        'short_name' => 'asset',
    ];
    return $permissions;
}

// Cron job hook for automated tasks
hooks()->add_action('after_cron_run', 'assets_cron_job');
function assets_cron_job()
{
    $CI = &get_instance();
    $CI->load->model('assets/assets_model');
    $CI->load->library('assets/assets_notifications');
    $CI->load->library('assets/assets_webhooks');

    // Check for overdue checkouts
    $overdue_checkouts = $CI->assets_model->get_overdue_checkouts();
    foreach ($overdue_checkouts as $checkout) {
        $asset = $CI->assets_model->get($checkout['asset_id']);
        $CI->assets_notifications->notify_checkout_overdue((object)$checkout, $asset);
        $CI->assets_webhooks->trigger('alert.checkout_overdue', [
            'checkout' => $checkout,
            'asset' => (array)$asset
        ]);
    }

    // Check for overdue maintenance
    $overdue_maintenance = $CI->assets_model->get_overdue_maintenance();

    // Check for expiring warranties (30 days)
    $CI->db->where('date_buy IS NOT NULL');
    $CI->db->where('warranty_period > 0');
    $assets = $CI->db->get(db_prefix().'assets')->result();
    
    foreach ($assets as $asset) {
        $warranty_end = date('Y-m-d', strtotime($asset->date_buy . ' + ' . $asset->warranty_period . ' months'));
        $days_until_expiry = (strtotime($warranty_end) - time()) / 86400;
        
        if ($days_until_expiry > 0 && $days_until_expiry <= 30) {
            $CI->assets_notifications->notify_warranty_expiry($asset, round($days_until_expiry));
            $CI->assets_webhooks->trigger('alert.warranty_expiring', [
                'asset' => (array)$asset,
                'days_until_expiry' => round($days_until_expiry)
            ]);
        }
    }

    // Check for upcoming maintenance (7 days)
    $due_maintenance = $CI->assets_model->get_due_maintenance(7);
    foreach ($due_maintenance as $maintenance) {
        $asset = $CI->assets_model->get($maintenance['asset_id']);
        $CI->assets_notifications->notify_maintenance_due($asset, (object)$maintenance);
        $CI->assets_webhooks->trigger('alert.maintenance_due', [
            'maintenance' => $maintenance,
            'asset' => (array)$asset
        ]);
    }

    // Clean up old webhook logs (older than 30 days)
    $CI->assets_webhooks->clear_old_logs(30);
    
    // Clean up old notifications (older than 90 days)
    $CI->assets_notifications->delete_old_notifications(90);
}

// Add assets to project view
hooks()->add_action('after_project_overview_tabs', 'assets_project_tab');
function assets_project_tab($project_id)
{
    echo '<li role="presentation">
        <a href="#project_assets" aria-controls="project_assets" role="tab" data-toggle="tab">
            <i class="fa fa-bank"></i> '._l('assets').'
        </a>
    </li>';
}

hooks()->add_action('after_project_overview_tab_content', 'assets_project_tab_content');
function assets_project_tab_content($project_id)
{
    $CI = &get_instance();
    $CI->load->model('assets/assets_model');
    $assets = $CI->assets_model->get_project_assets($project_id);
    
    echo '<div role="tabpanel" class="tab-pane" id="project_assets">';
    echo '<div class="panel_s">';
    echo '<div class="panel-body">';
    
    if (has_permission('assets', '', 'create') || is_admin()) {
        echo '<a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#assignAssetModal"><i class="fa fa-plus"></i> '._l('assign_asset').'</a>';
    }
    
    echo '<h4 class="font-medium mbot15">'._l('project_assets').'</h4>';
    
    if (empty($assets)) {
        echo '<p class="text-muted">'._l('no_assets_assigned').'</p>';
    } else {
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>'._l('asset_code').'</th><th>'._l('asset_name').'</th><th>'._l('quantity').'</th><th>'._l('assigned_date').'</th><th></th></tr></thead>';
        echo '<tbody>';
        foreach ($assets as $asset) {
            echo '<tr>';
            echo '<td>'.htmlspecialchars($asset['assets_code']).'</td>';
            echo '<td>'.htmlspecialchars($asset['assets_name']).'</td>';
            echo '<td>'.$asset['quantity'].'</td>';
            echo '<td>'.($asset['assigned_date'] ? _d($asset['assigned_date']) : '-').'</td>';
            echo '<td>';
            if (has_permission('assets', '', 'edit') || is_admin()) {
                echo '<a href="'.admin_url('assets/remove_from_project/'.$asset['id']).'" class="btn btn-danger btn-xs _delete"><i class="fa fa-times"></i></a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    
    echo '</div></div></div>';
    
    // Modal for assigning asset
    if (has_permission('assets', '', 'create') || is_admin()) {
        $CI->load->model('assets/assets_model');
        $all_assets = $CI->assets_model->get_assets();
        
        echo '<div class="modal fade" id="assignAssetModal" tabindex="-1" role="dialog">';
        echo '<div class="modal-dialog"><div class="modal-content">';
        echo form_open(admin_url('assets/assign_to_project'));
        echo form_hidden('project_id', $project_id);
        echo '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">'._l('assign_asset').'</h4></div>';
        echo '<div class="modal-body">';
        echo '<div class="form-group"><label>'._l('asset').'</label><select name="asset_id" class="selectpicker" data-live-search="true" data-width="100%" required>';
        echo '<option value="">'._l('select_asset').'</option>';
        foreach ($all_assets as $a) {
            echo '<option value="'.$a['id'].'">'.htmlspecialchars($a['assets_code'].' - '.$a['assets_name']).'</option>';
        }
        echo '</select></div>';
        echo '<div class="form-group"><label>'._l('quantity').'</label><input type="number" name="quantity" class="form-control" value="1" min="1"></div>';
        echo '<div class="form-group"><label>'._l('notes').'</label><textarea name="notes" class="form-control" rows="3"></textarea></div>';
        echo '</div>';
        echo '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'._l('close').'</button><button type="submit" class="btn btn-primary">'._l('assign').'</button></div>';
        echo form_close();
        echo '</div></div></div>';
    }
}

hooks()->add_action('app_init', ASSETS_MODULE.'_actLib');
function assets_actLib()
{
    $CI = &get_instance();
    $CI->load->library(ASSETS_MODULE.'/Assets_aeiou');
    $license_valid = $CI->assets_aeiou->validatePurchase(ASSETS_MODULE);
    if (!$license_valid) {
        set_alert('danger', 'One of your modules failed its verification and got deactivated. Please reactivate or contact support.');
    }
}

hooks()->add_action('pre_activate_module', ASSETS_MODULE.'_sidecheck');
function assets_sidecheck($module_name)
{
    if (ASSETS_MODULE == $module_name['system_name']) {
        modules\assets\core\Apiinit::activate($module_name);
    }
}

hooks()->add_action('pre_deactivate_module', ASSETS_MODULE.'_deregister');
function assets_deregister($module_name)
{
    if (ASSETS_MODULE == $module_name['system_name']) {
        delete_option(ASSETS_MODULE.'_verification_id');
        delete_option(ASSETS_MODULE.'_last_verification');
        delete_option(ASSETS_MODULE.'_product_token');
        delete_option(ASSETS_MODULE.'_heartbeat');
    }
}
