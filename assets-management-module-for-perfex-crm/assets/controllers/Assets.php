<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Assets extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('assets_model');
    }

    // ============================================
    // DASHBOARD
    // ============================================

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $data['title'] = _l('assets_dashboard');
        $data['stats'] = $this->assets_model->get_dashboard_stats();
        $data['depreciation'] = $this->assets_model->get_depreciation_summary();
        $data['recent_activity'] = $this->assets_model->get_audit_log(null, 10);
        $data['due_maintenance'] = $this->assets_model->get_due_maintenance(7);
        $data['overdue_checkouts'] = $this->assets_model->get_overdue_checkouts();

        $this->load->view('dashboard', $data);
    }

    // ============================================
    // SETTINGS
    // ============================================

    public function setting()
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            access_denied('assets');
        }
        $data['group'] = $this->input->get('group');

        $data['title'] = _l('setting');
        $data['tab'][] = 'asset_group';
        $data['tab'][] = 'asset_unit';
        $data['tab'][] = 'asset_location';
        $data['tab'][] = 'custom_fields';
        $data['tab'][] = 'webhooks';
        $data['tab'][] = 'notifications';
        
        if ('' == $data['group']) {
            $data['group'] = 'asset_group';
        }
        $data['tabs']['view'] = 'includes/'.$data['group'];
        $data['asset_group'] = $this->assets_model->get_asset_group();
        $data['asset_unit'] = $this->assets_model->get_asset_unit();
        $data['asset_location'] = $this->assets_model->get_asset_location();
        $data['custom_fields'] = $this->assets_model->get_custom_fields(null, false);
        
        // Webhooks
        $this->load->library('assets_webhooks');
        $data['webhooks'] = $this->assets_webhooks->get_webhooks();
        $data['webhook_events'] = $this->assets_webhooks->get_available_events();
        
        // Notifications
        $this->load->library('assets_notifications');
        $data['notification_settings'] = $this->assets_notifications->get_settings();
        
        $this->load->view('manage_setting', $data);
        \modules\assets\core\Apiinit::ease_of_mind('assets');
        \modules\assets\core\Apiinit::the_da_vinci_code('assets');
    }

    public function asset_group()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->assets_model->add_asset_group($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('asset_group')));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                if ($this->assets_model->update_asset_group($data, $id)) {
                    set_alert('success', _l('updated_successfully', _l('asset_group')));
                }
            }
            redirect(admin_url('assets/setting?group=asset_group'));
        }
    }

    public function delete_assets_group($id)
    {
        if (!$id) {
            redirect(admin_url('assets/setting?group=asset_group'));
        }
        if ($this->assets_model->delete_asset_group($id)) {
            set_alert('success', _l('deleted', _l('asset_group')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('asset_group')));
        }
        redirect(admin_url('assets/setting?group=asset_group'));
    }

    public function asset_unit()
    {
        \modules\assets\core\Apiinit::ease_of_mind('assets');
        \modules\assets\core\Apiinit::the_da_vinci_code('assets');
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->assets_model->add_asset_unit($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('asset_unit')));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                if ($this->assets_model->update_asset_unit($data, $id)) {
                    set_alert('success', _l('updated_successfully', _l('asset_unit')));
                }
            }
            redirect(admin_url('assets/setting?group=asset_unit'));
        }
    }

    public function delete_asset_unit($id)
    {
        if (!$id) {
            redirect(admin_url('assets/setting?group=asset_unit'));
        }
        if ($this->assets_model->delete_asset_unit($id)) {
            set_alert('success', _l('deleted', _l('asset_unit')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('asset_unit')));
        }
        redirect(admin_url('assets/setting?group=asset_unit'));
    }

    public function asset_location()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->assets_model->add_asset_location($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('asset_location')));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                if ($this->assets_model->update_asset_location($data, $id)) {
                    set_alert('success', _l('updated_successfully', _l('asset_location')));
                }
            }
            redirect(admin_url('assets/setting?group=asset_location'));
        }
    }

    public function delete_asset_location($id)
    {
        if (!$id) {
            redirect(admin_url('assets/setting?group=asset_location'));
        }
        if ($this->assets_model->delete_asset_location($id)) {
            set_alert('success', _l('deleted', _l('asset_location')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('asset_location')));
        }
        redirect(admin_url('assets/setting?group=asset_location'));
    }

    // ============================================
    // ASSET MANAGEMENT
    // ============================================

    public function manage_assets($asset_id = '')
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }
        $this->load->model('departments_model');
        $data['title'] = _l('assets');
        $data['unit'] = $this->assets_model->get_asset_unit();
        $data['group'] = $this->assets_model->get_asset_group();
        $data['location'] = $this->assets_model->get_asset_location();
        $data['departments'] = $this->departments_model->get();
        $data['custom_fields'] = $this->assets_model->get_custom_fields();
        $data['asset_id'] = $asset_id;
        $this->load->view('manage_assets', $data);
    }

    public function table_assets($status = '')
    {
        $params = [];
        if ('' != $status && 'all_asset' != $status) {
            $status_map = [
                'not_pending_yet' => 1,
                'using' => 2,
                'liquidation' => 3,
                'warranty_repair' => 4,
                'lost' => 5,
                'broken' => 6
            ];
            if (isset($status_map[$status])) {
                $params['status'] = $status_map[$status];
            }
        }
        $this->app->get_table_data(module_views_path('assets', 'table_assets'), $params);
    }

    public function asset()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $custom_fields = isset($data['custom_fields']) ? $data['custom_fields'] : [];
            unset($data['custom_fields']);

            if (!$this->input->post('id')) {
                if (!has_permission('assets', '', 'create') && !is_admin()) {
                    access_denied('assets');
                }
                $id = $this->assets_model->add_asset($data);
                if ($id) {
                    handle_asset_file($id);
                    handle_asset_image_upload($id);
                    $this->save_custom_fields($id, $custom_fields);
                    set_alert('success', _l('added_successfully', _l('assets')));
                    redirect(admin_url('assets/manage_assets'));
                }
            } else {
                if (!has_permission('assets', '', 'edit') && !is_admin()) {
                    access_denied('assets');
                }
                $id = $data['id'];
                unset($data['id']);
                $success = $this->assets_model->update_asset($data, $id);
                handle_asset_file($id);
                $this->save_custom_fields($id, $custom_fields);
                if ($success || handle_asset_image_upload($id)) {
                    set_alert('success', _l('updated_successfully', _l('assets')));
                    redirect(admin_url('assets/manage_assets'));
                }
            }
        }
    }

    protected function save_custom_fields($asset_id, $fields)
    {
        foreach ($fields as $field_id => $value) {
            $this->assets_model->save_custom_field_value($asset_id, $field_id, $value);
        }
    }

    public function delete_assets($id)
    {
        if (!has_permission('assets', '', 'delete') && !is_admin()) {
            access_denied('assets');
        }
        if (!$id) {
            redirect(admin_url('assets/manage_assets'));
        }
        if ($this->assets_model->delete_assets($id)) {
            set_alert('success', _l('deleted', _l('assets')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('assets')));
        }
        redirect(admin_url('assets/manage_assets'));
    }

    public function assets_code_exists()
    {
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $id = $this->input->post('id');
            if ('' != $id) {
                $this->db->where('id', $id);
                $assets = $this->db->get('tblassets')->row();
                if ($assets->assets_code == $this->input->post('assets_code')) {
                    echo json_encode(true);
                    die();
                }
            }
            $this->db->where('assets_code', $this->input->post('assets_code'));
            $total_rows = $this->db->count_all_results('tblassets');
            echo json_encode($total_rows == 0);
            die();
        }
    }

    public function get_asset_data_ajax($asset_id)
    {
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();
        $data['asset_file'] = $this->assets_model->get_asset_file($asset_id);
        $data['assets'] = $this->assets_model->get($asset_id);
        $data['custom_field_values'] = $this->assets_model->get_custom_field_values($asset_id);
        $data['checkouts'] = $this->assets_model->get_checkouts(null, $asset_id);
        $data['reservations'] = $this->assets_model->get_reservations(null, $asset_id);
        $data['maintenance'] = $this->assets_model->get_maintenance(null, $asset_id);
        $data['transfers'] = $this->assets_model->get_transfers(null, $asset_id);
        $data['expenses'] = $this->assets_model->get_asset_expenses($asset_id);
        $data['audit_log'] = $this->assets_model->get_audit_log($asset_id, 20);

        $broken = $this->assets_model->get_amount_asset_broken($asset_id);
        $warr = $this->assets_model->get_amount_asset_warranty($asset_id);
        $brokens = 0;
        $warrs = 0;
        foreach ($broken as $a) {
            $brokens += $a['amount'];
        }
        foreach ($warr as $a) {
            $warrs += $a['amount'];
        }
        $data['total_broken'] = $brokens - $warrs;
        
        // Barcode URLs
        $this->load->library('assets_barcode');
        $data['barcode_url'] = $this->assets_barcode->get_barcode_url($asset_id);
        $data['qr_url'] = $this->assets_barcode->get_qr_url($asset_id);
        
        $this->load->view('asset_preview', $data);
    }

    /**
     * Get asset data as JSON for AJAX edit
     */
    public function get_asset($id)
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            ajax_access_denied();
        }
        
        $asset = $this->assets_model->get($id);
        if ($asset) {
            echo json_encode($asset);
        } else {
            echo json_encode(['error' => 'Asset not found']);
        }
    }

    public function file($id, $rel_id)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin'] = is_admin();
        $data['file'] = $this->assets_model->get_file($id, $rel_id);
        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }
        $this->load->view('_file', $data);
    }

    public function delete_asset_attachment($id)
    {
        $this->load->model('misc_model');
        $file = $this->misc_model->get_file($id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo $this->assets_model->delete_assets_attachment($id);
        } else {
            header('HTTP/1.0 400 Bad error');
            echo _l('access_denied');
            die;
        }
    }

    // ============================================
    // ALLOCATION/REVOCATION
    // ============================================

    public function allocation_asset()
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            // Decode the "type:id" recipient into id + discriminator.
            $recipient = parse_asset_recipient($this->input->post('acction_to'));
            if (null === $recipient) {
                set_alert('danger', _l('invalid_recipient'));
                redirect(admin_url('assets/manage_assets#'.$this->input->post('assets')));
            }
            $data['acction_to']      = $recipient['id'];
            $data['acction_to_type'] = $recipient['type'];

            // The allocation modal has no "from" field; the actor is always
            // the current staff member.
            if (empty($data['acction_from'])) {
                $data['acction_from'] = get_staff_user_id();
            }

            $id = $this->assets_model->allocation_asset($data);
            if ($id) {
                set_alert('success', _l('allocation_asset').' '._l('successfully'));
                redirect(admin_url('assets/manage_assets#'.$data['assets']));
            }
        }
    }

    public function acction_code_exists()
    {
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $this->db->where('acction_code', $this->input->post('acction_code'));
            $total_rows = $this->db->count_all_results('tblassets_acction_1');
            echo json_encode($total_rows == 0);
            die();
        }
    }

    public function acction2_code_exists()
    {
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $this->db->where('acction_code', $this->input->post('acction_code'));
            $total_rows = $this->db->count_all_results('tblassets_acction_2');
            echo json_encode($total_rows == 0);
            die();
        }
    }

    public function get_asset_allocation_by_staff($staff = null, $assets = null)
    {
        // The revoke modal posts recipient_id + recipient_type, because the
        // recipient value is "type:id" which cannot travel in a URL segment.
        $type = $this->input->post('recipient_type') ?: 'staff';
        if (null !== $this->input->post('recipient_id')) {
            $staff = $this->input->post('recipient_id');
        }
        if (null !== $this->input->post('assets')) {
            $assets = $this->input->post('assets');
        }

        $allocation = $this->assets_model->get_asset_allocation_by_staff($staff, $assets, $type);
        $revoke = $this->assets_model->get_asset_revoke_by_staff($staff, $assets, $type);
        $total_allocate = 0;
        $total_revoke = 0;
        foreach ($allocation as $a) {
            $total_allocate += $a['amount'];
        }
        foreach ($revoke as $a) {
            $total_revoke += $a['amount'];
        }
        echo json_encode(['total' => $total_allocate - $total_revoke]);
    }

    public function revoke_asset()
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            // Decode the "type:id" recipient the asset is being recalled from.
            $recipient = parse_asset_recipient($this->input->post('acction_to'));
            if (null === $recipient) {
                set_alert('danger', _l('invalid_recipient'));
                redirect(admin_url('assets/manage_assets#'.$this->input->post('assets')));
            }
            $data['acction_to']      = $recipient['id'];
            $data['acction_to_type'] = $recipient['type'];

            if (empty($data['acction_from'])) {
                $data['acction_from'] = get_staff_user_id();
            }

            $id = $this->assets_model->revoke_asset($data);
            if ($id) {
                set_alert('success', _l('recalled_asset').' '._l('successfully'));
                redirect(admin_url('assets/manage_assets#'.$data['assets']));
            }
        }
    }

    public function additional_asset()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $this->assets_model->additional_asset($data);
            if ($id) {
                set_alert('success', _l('additional_asset').' '._l('successfully'));
                redirect(admin_url('assets/manage_assets#'.$data['assets']));
            }
        }
    }

    public function lost_asset()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $this->assets_model->lost_asset($data);
            if ($id) {
                set_alert('success', _l('report_lost').' '._l('successfully'));
                redirect(admin_url('assets/manage_assets#'.$data['assets']));
            }
        }
    }

    public function broken_asset()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $this->assets_model->broken_asset($data);
            if ($id) {
                set_alert('success', _l('report_broken').' '._l('successfully'));
                redirect(admin_url('assets/manage_assets#'.$data['assets']));
            }
        }
    }

    public function liquidation_asset()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $this->assets_model->liquidation_asset($data);
            if ($id) {
                set_alert('success', _l('liquidation_asset').' '._l('successfully'));
                redirect(admin_url('assets/manage_assets#'.$data['assets']));
            }
        }
    }

    public function warranty_asset()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $this->assets_model->warranty_asset($data);
            if ($id) {
                set_alert('success', _l('warranty_asset').' '._l('successfully'));
                redirect(admin_url('assets/manage_assets#'.$data['assets']));
            }
        }
    }

    // ============================================
    // TABLES
    // ============================================

    public function table_inventory_history($asset_id)
    {
        $this->app->get_table_data(module_views_path('assets', 'includes/table_inventory_history'), ['asset_id' => $asset_id]);
    }

    public function table_action($asset_id)
    {
        $this->app->get_table_data(module_views_path('assets', 'includes/table_action'), ['asset_id' => $asset_id]);
    }

    public function table_action_allocate($type)
    {
        $this->app->get_table_data(module_views_path('assets', 'includes/table_action'), ['type' => $type]);
    }

    public function allocation()
    {
        $data['title'] = _l('allocation');
        $this->load->view('allocation', $data);
    }

    public function eviction()
    {
        $data['title'] = _l('eviction');
        $this->load->view('eviction', $data);
    }

    public function depreciation()
    {
        $data['group'] = $this->assets_model->get_asset_group();
        $data['assets'] = $this->assets_model->get_assets();
        $data['title'] = _l('depreciation');
        $this->load->view('depreciation', $data);
    }

    public function table_depreciation()
    {
        $this->app->get_table_data(module_views_path('assets', 'includes/table_depreciation'));
    }

    // ============================================
    // MAINTENANCE
    // ============================================

    public function maintenance($id = null)
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        if ($id) {
            $data['maintenance'] = $this->assets_model->get_maintenance($id);
            $data['asset'] = $this->assets_model->get($data['maintenance']->asset_id);
        }

        $data['title'] = _l('maintenance');
        $data['all_maintenance'] = $this->assets_model->get_maintenance();
        $data['assets'] = $this->assets_model->get_assets();
        $this->load->view('maintenance', $data);
    }

    public function add_maintenance()
    {
        if (!has_permission('assets', '', 'create') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $this->assets_model->add_maintenance($data);
            if ($id) {
                set_alert('success', _l('added_successfully', _l('maintenance')));
            }
            redirect(admin_url('assets/maintenance'));
        }
    }

    public function update_maintenance($id)
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            // Remove fields that shouldn't be in the database update
            unset($data['id']);
            
            if ($this->assets_model->update_maintenance($id, $data)) {
                set_alert('success', _l('updated_successfully', _l('maintenance')));
            }
            redirect(admin_url('assets/maintenance'));
        }
    }

    public function delete_maintenance($id)
    {
        if (!has_permission('assets', '', 'delete') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->assets_model->delete_maintenance($id)) {
            set_alert('success', _l('deleted', _l('maintenance')));
        }
        redirect(admin_url('assets/maintenance'));
    }

    public function table_maintenance()
    {
        $this->app->get_table_data(module_views_path('assets', 'includes/table_maintenance'));
    }

    // ============================================
    // CHECK-IN/CHECK-OUT
    // ============================================

    public function checkouts()
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $data['title'] = _l('checkouts');
        $data['assets'] = $this->assets_model->get_assets();
        $this->load->model('staff_model');
        $data['staff'] = $this->staff_model->get();
        $this->load->view('checkouts', $data);
    }

    public function checkout_asset()
    {
        if (!has_permission('assets', '', 'create') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->input->post()) {
            $data = $this->input->post();

            // Decode the "type:id" recipient into the two stored columns.
            $recipient = parse_asset_recipient($this->input->post('checked_out_to'));
            if (null === $recipient) {
                set_alert('danger', _l('invalid_recipient'));
                redirect(admin_url('assets/checkouts'));
            }
            $data['checked_out_to']      = $recipient['id'];
            $data['checked_out_to_type'] = $recipient['type'];

            $result = $this->assets_model->checkout_asset($data);

            if (is_array($result) && isset($result['error'])) {
                set_alert('danger', $result['error']);
            } elseif ($result) {
                set_alert('success', _l('asset_checked_out_successfully'));
            }
            redirect(admin_url('assets/checkouts'));
        }
    }

    public function checkin_asset($checkout_id)
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => _l('access_denied')]);
                exit;
            }
            access_denied('assets');
        }

        $data = $this->input->post();
        $success = $this->assets_model->checkin_asset($checkout_id, $data);
        
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => $success, 'message' => $success ? _l('asset_checked_in_successfully') : _l('error_occurred')]);
            exit;
        }
        
        if ($success) {
            set_alert('success', _l('asset_checked_in_successfully'));
        }
        redirect(admin_url('assets/checkouts'));
    }

    public function table_checkouts()
    {
        $this->app->get_table_data(module_views_path('assets', 'includes/table_checkouts'));
    }

    // ============================================
    // RESERVATIONS
    // ============================================

    public function reservations()
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $data['title'] = _l('reservations');
        $data['assets'] = $this->assets_model->get_assets();
        $data['reservations'] = $this->assets_model->get_reservations();
        $this->load->view('reservations', $data);
    }

    public function create_reservation()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $result = $this->assets_model->create_reservation($data);
            
            if (is_array($result) && isset($result['error'])) {
                set_alert('danger', $result['error']);
            } elseif ($result) {
                set_alert('success', _l('reservation_created_successfully'));
            }
            redirect(admin_url('assets/reservations'));
        }
    }

    public function approve_reservation($id)
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->assets_model->approve_reservation($id)) {
            set_alert('success', _l('reservation_approved'));
        }
        redirect(admin_url('assets/reservations'));
    }

    public function reject_reservation($id)
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            access_denied('assets');
        }

        $reason = $this->input->post('reason');
        if ($this->assets_model->reject_reservation($id, $reason)) {
            set_alert('success', _l('reservation_rejected'));
        }
        redirect(admin_url('assets/reservations'));
    }

    public function delete_reservation($id)
    {
        if (!has_permission('assets', '', 'delete') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->assets_model->delete_reservation($id)) {
            set_alert('success', _l('deleted', _l('reservation')));
        }
        redirect(admin_url('assets/reservations'));
    }

    public function table_reservations()
    {
        $this->app->get_table_data(module_views_path('assets', 'includes/table_reservations'));
    }

    public function get_reservations_calendar()
    {
        // Get all reservations (pending and approved) for calendar view
        $reservations = $this->assets_model->get_reservations();
        $events = [];
        
        $status_colors = [
            'pending' => '#f6c23e',   // Yellow/warning
            'approved' => '#1cc88a', // Green/success
            'rejected' => '#e74a3b', // Red/danger
            'completed' => '#36b9cc', // Cyan/info
            'cancelled' => '#858796'  // Gray
        ];
        
        foreach ($reservations as $res) {
            $asset = $this->assets_model->get($res['asset_id']);
            if (!$asset) continue;
            
            $events[] = [
                'id' => $res['id'],
                'title' => $asset->assets_name . ' (' . $res['quantity'] . ') - ' . ucfirst($res['status']),
                'start' => $res['reservation_start'],
                'end' => $res['reservation_end'],
                'color' => $status_colors[$res['status']] ?? '#4e73df'
            ];
        }
        
        echo json_encode($events);
    }

    // ============================================
    // TRANSFERS
    // ============================================

    public function transfers()
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $data['title'] = _l('transfers');
        $data['assets'] = $this->assets_model->get_assets();
        $data['locations'] = $this->assets_model->get_asset_location();
        $this->load->model('departments_model');
        $data['departments'] = $this->departments_model->get();
        $this->load->view('transfers', $data);
    }

    public function create_transfer()
    {
        if (!has_permission('assets', '', 'create') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            if ($this->assets_model->create_transfer($data)) {
                set_alert('success', _l('transfer_created_successfully'));
            }
            redirect(admin_url('assets/transfers'));
        }
    }

    public function complete_transfer($id)
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->assets_model->complete_transfer($id)) {
            set_alert('success', _l('transfer_completed'));
        }
        redirect(admin_url('assets/transfers'));
    }

    public function table_transfers()
    {
        $this->app->get_table_data(module_views_path('assets', 'includes/table_transfers'));
    }

    // ============================================
    // CUSTOM FIELDS
    // ============================================

    public function custom_field()
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->assets_model->add_custom_field($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('custom_field')));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                if ($this->assets_model->update_custom_field($id, $data)) {
                    set_alert('success', _l('updated_successfully', _l('custom_field')));
                }
            }
            redirect(admin_url('assets/setting?group=custom_fields'));
        }
    }

    public function delete_custom_field($id)
    {
        if (!has_permission('assets', '', 'delete') && !is_admin()) {
            access_denied('assets');
        }

        if ($this->assets_model->delete_custom_field($id)) {
            set_alert('success', _l('deleted', _l('custom_field')));
        }
        redirect(admin_url('assets/setting?group=custom_fields'));
    }

    /**
     * Get custom field values for a specific asset (AJAX)
     */
    public function get_asset_custom_field_values($asset_id)
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            echo json_encode([]);
            exit;
        }

        $values = $this->assets_model->get_custom_field_values($asset_id);
        echo json_encode($values);
    }

    // ============================================
    // WEBHOOKS
    // ============================================

    public function webhook()
    {
        if (!is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_webhooks');

        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->assets_webhooks->create_webhook($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('webhook')));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                if ($this->assets_webhooks->update_webhook($id, $data)) {
                    set_alert('success', _l('updated_successfully', _l('webhook')));
                }
            }
            redirect(admin_url('assets/setting?group=webhooks'));
        }
    }

    public function delete_webhook($id)
    {
        if (!is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_webhooks');
        if ($this->assets_webhooks->delete_webhook($id)) {
            set_alert('success', _l('deleted', _l('webhook')));
        }
        redirect(admin_url('assets/setting?group=webhooks'));
    }

    public function test_webhook($id)
    {
        if (!is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_webhooks');
        if ($this->assets_webhooks->test_webhook($id)) {
            set_alert('success', _l('webhook_test_sent'));
        } else {
            set_alert('danger', _l('webhook_test_failed'));
        }
        redirect(admin_url('assets/setting?group=webhooks'));
    }

    public function webhook_logs($webhook_id = null)
    {
        if (!is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_webhooks');
        $data['logs'] = $this->assets_webhooks->get_logs($webhook_id);
        $data['title'] = _l('webhook_logs');
        $this->load->view('webhook_logs', $data);
    }

    // ============================================
    // NOTIFICATIONS
    // ============================================

    public function notification_settings()
    {
        if (!is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_notifications');

        if ($this->input->post()) {
            $settings = $this->input->post('settings');
            foreach ($settings as $type => $data) {
                $this->assets_notifications->update_settings($type, $data);
            }
            set_alert('success', _l('settings_updated'));
            redirect(admin_url('assets/setting?group=notifications'));
        }
    }

    public function get_notifications()
    {
        $this->load->library('assets_notifications');
        $notifications = $this->assets_notifications->get_notifications(get_staff_user_id(), false, 20);
        echo json_encode($notifications);
    }

    public function mark_notification_read($id)
    {
        $this->load->library('assets_notifications');
        $this->assets_notifications->mark_as_read($id, get_staff_user_id());
        echo json_encode(['success' => true]);
    }

    public function mark_all_notifications_read()
    {
        $this->load->library('assets_notifications');
        $this->assets_notifications->mark_all_as_read(get_staff_user_id());
        echo json_encode(['success' => true]);
    }

    // ============================================
    // BARCODE/QR CODE
    // ============================================

    public function generate_barcode($asset_id)
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_barcode');
        if ($this->assets_barcode->generate_barcode($asset_id)) {
            set_alert('success', _l('barcode_generated'));
        } else {
            set_alert('danger', _l('barcode_generation_failed'));
        }
        redirect(admin_url('assets/manage_assets#' . $asset_id));
    }

    public function generate_qr_code($asset_id)
    {
        if (!has_permission('assets', '', 'edit') && !is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_barcode');
        if ($this->assets_barcode->generate_qr_code($asset_id)) {
            set_alert('success', _l('qr_code_generated'));
        } else {
            set_alert('danger', _l('qr_code_generation_failed'));
        }
        redirect(admin_url('assets/manage_assets#' . $asset_id));
    }

    public function generate_all_barcodes()
    {
        if (!is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_barcode');
        $count = $this->assets_barcode->generate_all_barcodes();
        set_alert('success', sprintf(_l('barcodes_generated_count'), $count));
        redirect(admin_url('assets/manage_assets'));
    }

    public function print_labels()
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $asset_ids = $this->input->get('ids');
        if (!$asset_ids) {
            redirect(admin_url('assets/manage_assets'));
        }

        $ids = explode(',', $asset_ids);
        $this->load->library('assets_barcode');
        $data['labels'] = $this->assets_barcode->generate_label_pdf($ids);
        $data['title'] = _l('print_labels');
        $this->load->view('print_labels', $data);
    }

    public function scan_barcode()
    {
        $code = $this->input->post('code');
        $this->load->library('assets_barcode');
        $asset = $this->assets_barcode->find_by_barcode($code);
        
        if ($asset) {
            echo json_encode(['success' => true, 'asset' => $asset]);
        } else {
            echo json_encode(['success' => false, 'message' => _l('asset_not_found')]);
        }
    }

    // ============================================
    // REPORTS
    // ============================================

    public function reports()
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $data['title'] = _l('reports');
        $data['groups'] = $this->assets_model->get_asset_group();
        $data['locations'] = $this->assets_model->get_asset_location();
        $this->load->view('reports', $data);
    }

    public function export_report($type)
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_reports');
        $format = $this->input->get('format') ?: 'pdf';
        $filters = $this->input->get();
        unset($filters['format']);

        switch ($type) {
            case 'assets':
                $report = $this->assets_reports->generate_assets_report($filters, $format);
                break;
            case 'depreciation':
                $report = $this->assets_reports->generate_depreciation_report($filters, $format);
                break;
            case 'maintenance':
                $report = $this->assets_reports->generate_maintenance_report($filters, $format);
                break;
            case 'checkouts':
                $report = $this->assets_reports->generate_checkout_report($filters, $format);
                break;
            case 'audit':
                $asset_id = $this->input->get('asset_id');
                $report = $this->assets_reports->generate_audit_report($asset_id, $format);
                break;
            case 'utilization':
                $report = $this->assets_reports->generate_utilization_report($filters, $format);
                break;
            default:
                redirect(admin_url('assets/reports'));
        }

        if ($report) {
            header('Content-Type: ' . $report['mime']);
            header('Content-Disposition: attachment; filename="' . $report['filename'] . '"');
            echo $report['content'];
            exit;
        }
    }

    // ============================================
    // BULK IMPORT/EXPORT
    // ============================================

    public function import()
    {
        if (!has_permission('assets', '', 'create') && !is_admin()) {
            access_denied('assets');
        }

        $data['title'] = _l('import_assets');
        $this->load->view('import', $data);
    }

    public function process_import()
    {
        if (!has_permission('assets', '', 'create') && !is_admin()) {
            access_denied('assets');
        }

        if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] == 0) {
            $file = $_FILES['import_file']['tmp_name'];
            $filename = $_FILES['import_file']['name'];
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            if ($extension == 'csv') {
                $data = $this->parse_csv($file);
            } else {
                set_alert('danger', _l('invalid_file_format'));
                redirect(admin_url('assets/import'));
            }

            $result = $this->assets_model->import_assets($data, $filename);
            
            if ($result['imported'] > 0) {
                set_alert('success', sprintf(_l('import_completed'), $result['imported'], $result['failed']));
            } else {
                set_alert('danger', _l('import_failed'));
            }
        }

        redirect(admin_url('assets/import'));
    }

    protected function parse_csv($file)
    {
        $data = [];
        if (($handle = fopen($file, 'r')) !== false) {
            $headers = fgetcsv($handle);
            $headers = array_map('strtolower', $headers);
            $headers = array_map('trim', $headers);
            
            while (($row = fgetcsv($handle)) !== false) {
                $item = [];
                foreach ($headers as $i => $header) {
                    $item[$header] = isset($row[$i]) ? trim($row[$i]) : '';
                }
                $data[] = $item;
            }
            fclose($handle);
        }
        return $data;
    }

    public function export_template()
    {
        $headers = [
            'asset_code', 'asset_name', 'quantity', 'unit', 'group', 'location',
            'purchase_date', 'warranty_months', 'price', 'depreciation_months',
            'supplier', 'description', 'manufacturer', 'model', 'serial'
        ];

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="assets_import_template.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, $headers);
        
        // Add sample row
        fputcsv($output, [
            'AST001', 'Sample Asset', '10', 'Piece', 'Electronics', 'Main Office',
            '2024-01-01', '12', '1000', '36', 'Sample Supplier', 'Sample description',
            'Brand', 'Model X', 'SN12345'
        ]);
        
        fclose($output);
        exit;
    }

    public function export_assets()
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $this->load->library('assets_reports');
        $format = $this->input->get('format') ?: 'csv';
        $report = $this->assets_reports->generate_assets_report([], $format);

        header('Content-Type: ' . $report['mime']);
        header('Content-Disposition: attachment; filename="' . $report['filename'] . '"');
        echo $report['content'];
        exit;
    }

    // ============================================
    // PROJECT INTEGRATION
    // ============================================

    public function assign_to_project()
    {
        if ($this->input->post()) {
            $asset_id = $this->input->post('asset_id');
            $project_id = $this->input->post('project_id');
            $quantity = $this->input->post('quantity') ?: 1;
            $notes = $this->input->post('notes');

            if ($this->assets_model->assign_to_project($asset_id, $project_id, $quantity, $notes)) {
                set_alert('success', _l('asset_assigned_to_project'));
            }
        }
        redirect($_SERVER['HTTP_REFERER'] ?: admin_url('assets/manage_assets'));
    }

    public function remove_from_project($id)
    {
        if ($this->assets_model->remove_from_project($id)) {
            set_alert('success', _l('asset_removed_from_project'));
        }
        redirect($_SERVER['HTTP_REFERER'] ?: admin_url('assets/manage_assets'));
    }

    public function project_assets($project_id)
    {
        $data['assets'] = $this->assets_model->get_project_assets($project_id);
        $data['project_id'] = $project_id;
        $this->load->view('project_assets', $data);
    }

    // ============================================
    // EXPENSES
    // ============================================

    public function add_expense()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($this->assets_model->add_expense($data)) {
                set_alert('success', _l('expense_added'));
            }
        }
        redirect($_SERVER['HTTP_REFERER'] ?: admin_url('assets/manage_assets'));
    }

    // ============================================
    // AUDIT LOG
    // ============================================

    public function audit_log($asset_id = null)
    {
        if (!has_permission('assets', '', 'view') && !is_admin()) {
            access_denied('assets');
        }

        $data['title'] = _l('audit_log');
        $data['logs'] = $this->assets_model->get_audit_log($asset_id, 500);
        $data['asset_id'] = $asset_id;
        
        if ($asset_id) {
            $data['asset'] = $this->assets_model->get($asset_id);
        }
        
        $this->load->view('audit_log', $data);
    }
}
