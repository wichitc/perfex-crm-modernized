<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Assets Management Module - Helper Functions
 * Version 1.2.0
 */

/**
 * Handle asset file upload
 */
function handle_asset_file($id)
{
    if (isset($_FILES['file']['name']) && '' != $_FILES['file']['name']) {
        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = ASSETS_UPLOAD_FOLDER.'/'.$id.'/';
        $tmpFilePath = $_FILES['file']['tmp_name'];
        
        if (!empty($tmpFilePath) && '' != $tmpFilePath) {
            _maybe_create_upload_path($path);
            $filename = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path.$filename;
            
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI = &get_instance();
                $attachment = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                ];
                $CI->misc_model->add_attachment_to_database($id, 'assets', $attachment);
                return true;
            }
        }
    }
    return false;
}

/**
 * Handle asset image upload
 */
function handle_asset_image_upload($asset_id)
{
    $CI = &get_instance();
    if (isset($_FILES['asset_image']['name']) && '' != $_FILES['asset_image']['name']) {
        $path = get_upload_path_by_type('assets');
        $tmpFilePath = $_FILES['asset_image']['tmp_name'];
        
        if (!empty($tmpFilePath) && '' != $tmpFilePath) {
            $path_parts = pathinfo($_FILES['asset_image']['name']);
            $extension = strtolower($path_parts['extension']);
            
            // Validate extension
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($extension, $allowed)) {
                return false;
            }
            
            $filename = 'asset_'.$asset_id.'.'.$extension;
            $newFilePath = $path.$filename;
            _maybe_create_upload_path($path);
            
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI->load->model('assets/assets_model');
                $CI->assets_model->update_asset(['asset_image' => $filename], $asset_id);
                return true;
            }
        }
    }
    return false;
}

/**
 * Get asset location name by ID
 */
function get_asset_location($id = '')
{
    $CI = &get_instance();
    if (0 != $id && !empty($id)) {
        $CI->db->where('location_id', $id);
        $location = $CI->db->get(db_prefix().'asset_location')->row();
        return $location ? $location->location : '';
    }
    return '';
}

/**
 * Get asset group name by ID
 */
function get_asset_group($id = '')
{
    $CI = &get_instance();
    if (0 != $id && !empty($id)) {
        $CI->db->where('group_id', $id);
        $group = $CI->db->get(db_prefix().'assets_group')->row();
        return $group ? $group->group_name : '';
    }
    return '';
}

/**
 * Get asset unit name by ID
 */
function get_asset_units($id = '')
{
    $CI = &get_instance();
    if (0 != $id && !empty($id)) {
        $CI->db->where('unit_id', $id);
        $unit = $CI->db->get(db_prefix().'asset_unit')->row();
        return $unit ? $unit->unit_name : '';
    }
    return '';
}

/**
 * Get department name by ID
 */
function get_asset_dpm($id = '')
{
    $CI = &get_instance();
    if (0 != $id && !empty($id)) {
        $CI->db->where('departmentid', $id);
        $dpm = $CI->db->get(db_prefix().'departments')->row();
        return $dpm ? $dpm->name : '';
    }
    return '';
}

/**
 * Get asset name by ID
 */
function get_asset_name_by_id($id)
{
    $CI = &get_instance();
    $CI->db->where('id', $id);
    $assets = $CI->db->get(db_prefix().'assets')->row();
    return $assets ? $assets->assets_name : '';
}

/**
 * Get asset by ID
 */
function get_asset_by_id($id)
{
    $CI = &get_instance();
    $CI->db->where('id', $id);
    return $CI->db->get(db_prefix().'assets')->row();
}

/**
 * Reformat currency value (remove formatting)
 */
function reformat_currency_asset($value)
{
    return str_replace(',', '', $value);
}

/**
 * Get asset image URL
 */
function get_asset_image_url($asset_id, $asset_image = null)
{
    if (empty($asset_image)) {
        $CI = &get_instance();
        $CI->db->select('asset_image');
        $CI->db->where('id', $asset_id);
        $asset = $CI->db->get(db_prefix().'assets')->row();
        $asset_image = $asset ? $asset->asset_image : null;
    }
    
    if (!empty($asset_image)) {
        return base_url('modules/assets/uploads/' . $asset_image);
    }
    
    return base_url('modules/assets/uploads/image-not-available.png');
}

/**
 * Get asset status label
 */
function get_asset_status_label($status)
{
    $statuses = [
        1 => ['name' => _l('not_pending_yet'), 'class' => 'success'],
        2 => ['name' => _l('using'), 'class' => 'info'],
        3 => ['name' => _l('liquidation'), 'class' => 'default'],
        4 => ['name' => _l('warranty_repair'), 'class' => 'warning'],
        5 => ['name' => _l('lost'), 'class' => 'danger'],
        6 => ['name' => _l('broken'), 'class' => 'danger'],
    ];
    
    if (isset($statuses[$status])) {
        return '<span class="label label-'.$statuses[$status]['class'].'">'.$statuses[$status]['name'].'</span>';
    }

    return '<span class="label label-default">'._l('unknown').'</span>';
}

/*
|--------------------------------------------------------------------------
| Asset allocation / check-out recipients (staff, customers, contacts)
|--------------------------------------------------------------------------
| An asset can be handed over to a staff member, a customer (company) or a
| customer contact. Recipients are encoded in form controls as "type:id"
| (e.g. "staff:5", "client:12", "contact:43") so a single dropdown can carry
| all three kinds. Decode + validate the posted value with parse_asset_recipient().
*/

/**
 * Allowed recipient types for asset allocation / check-out.
 *
 * @return string[]
 */
function asset_recipient_types()
{
    return ['staff', 'client', 'contact'];
}

/**
 * Build the grouped recipient lists shared by the allocation, revoke and
 * check-out dropdowns. Cached per request.
 *
 * @return array staff|clients|contacts lists
 */
function get_asset_recipient_groups()
{
    static $groups = null;
    if (null !== $groups) {
        return $groups;
    }

    $CI = &get_instance();
    $CI->load->model('staff_model');

    $staff = $CI->staff_model->get();

    $clients = $CI->db
        ->select('userid, company')
        ->where('active', 1)
        ->order_by('company', 'asc')
        ->get(db_prefix() . 'clients')
        ->result_array();

    $contacts = $CI->db
        ->select(db_prefix() . 'contacts.id, ' . db_prefix() . 'contacts.firstname, ' . db_prefix() . 'contacts.lastname, ' . db_prefix() . 'clients.company')
        ->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'contacts.userid', 'left')
        ->where(db_prefix() . 'contacts.active', 1)
        ->order_by(db_prefix() . 'contacts.firstname', 'asc')
        ->get(db_prefix() . 'contacts')
        ->result_array();

    $groups = [
        'staff'    => is_array($staff) ? $staff : [],
        'clients'  => $clients,
        'contacts' => $contacts,
    ];

    return $groups;
}

/**
 * Render a grouped recipient <select> (staff / customers / contacts).
 *
 * The submitted value is encoded as "type:id"; decode it server-side with
 * parse_asset_recipient().
 *
 * @param string $name     form field name (e.g. 'acction_to')
 * @param string $selected currently selected "type:id" value
 * @param string $onchange optional inline onchange handler
 * @return string
 */
function render_asset_recipient_select($name, $selected = '', $onchange = '')
{
    $groups = get_asset_recipient_groups();

    $onchange_attr = '' !== $onchange ? ' onchange="' . $onchange . '"' : '';

    $html = '<select name="' . $name . '" id="' . $name . '"' . $onchange_attr
        . ' class="selectpicker" data-live-search="true" data-width="100%"'
        . ' data-none-selected-text="' . _l('ticket_settings_none_assigned') . '">';
    $html .= '<option value=""></option>';

    if (!empty($groups['staff'])) {
        $html .= '<optgroup label="' . _l('asset_recipient_staff') . '">';
        foreach ($groups['staff'] as $s) {
            $value = 'staff:' . $s['staffid'];
            $html .= '<option value="' . $value . '"' . ($selected === $value ? ' selected' : '') . '>'
                . htmlspecialchars(trim($s['firstname'] . ' ' . $s['lastname'])) . '</option>';
        }
        $html .= '</optgroup>';
    }

    if (!empty($groups['clients'])) {
        $html .= '<optgroup label="' . _l('asset_recipient_customers') . '">';
        foreach ($groups['clients'] as $c) {
            $value = 'client:' . $c['userid'];
            $html .= '<option value="' . $value . '"' . ($selected === $value ? ' selected' : '') . '>'
                . htmlspecialchars($c['company']) . '</option>';
        }
        $html .= '</optgroup>';
    }

    if (!empty($groups['contacts'])) {
        $html .= '<optgroup label="' . _l('asset_recipient_contacts') . '">';
        foreach ($groups['contacts'] as $ct) {
            $value = 'contact:' . $ct['id'];
            $label = trim($ct['firstname'] . ' ' . $ct['lastname']);
            if (!empty($ct['company'])) {
                $label .= ' (' . $ct['company'] . ')';
            }
            $html .= '<option value="' . $value . '"' . ($selected === $value ? ' selected' : '') . '>'
                . htmlspecialchars($label) . '</option>';
        }
        $html .= '</optgroup>';
    }

    $html .= '</select>';

    return $html;
}

/**
 * Decode + validate a "type:id" recipient value submitted from a recipient
 * dropdown. Returns ['type' => ..., 'id' => ...] when valid, or null when the
 * value is malformed or the recipient does not exist.
 *
 * A bare numeric value is treated as a staff id so any legacy caller that
 * still posts a raw staff id keeps working.
 *
 * @param string $value
 * @return array|null
 */
function parse_asset_recipient($value)
{
    $value = trim((string) $value);
    if ('' === $value) {
        return null;
    }

    if (false !== strpos($value, ':')) {
        list($type, $id) = explode(':', $value, 2);
    } else {
        $type = 'staff';
        $id   = $value;
    }

    $type = strtolower(trim($type));
    $id   = (int) $id;

    if ($id <= 0 || !in_array($type, asset_recipient_types(), true)) {
        return null;
    }

    if (!asset_recipient_exists($id, $type)) {
        return null;
    }

    return ['type' => $type, 'id' => $id];
}

/**
 * Whether a recipient row actually exists (defence against tampered input).
 *
 * @param int    $id
 * @param string $type
 * @return bool
 */
function asset_recipient_exists($id, $type)
{
    $CI  = &get_instance();
    $map = [
        'staff'   => [db_prefix() . 'staff', 'staffid'],
        'client'  => [db_prefix() . 'clients', 'userid'],
        'contact' => [db_prefix() . 'contacts', 'id'],
    ];

    if (!isset($map[$type])) {
        return false;
    }

    list($table, $key) = $map[$type];

    return $CI->db->where($key, (int) $id)->count_all_results($table) > 0;
}

/**
 * Human-readable name for an asset recipient.
 *
 * @param int    $id
 * @param string $type staff|client|contact
 * @return string
 */
function get_asset_recipient_name($id, $type = 'staff')
{
    $id = (int) $id;

    switch ($type) {
        case 'client':
            $name = get_company_name($id);
            return '' !== $name ? $name : _l('unknown');
        case 'contact':
            $CI      = &get_instance();
            $contact = $CI->db->select('firstname, lastname')->where('id', $id)->get(db_prefix() . 'contacts')->row();
            return $contact ? trim($contact->firstname . ' ' . $contact->lastname) : _l('unknown');
        case 'staff':
        default:
            $name = get_staff_full_name($id);
            return '' !== $name ? $name : _l('unknown');
    }
}

/**
 * Recipient name wrapped in a profile link (HTML). Used in datatables.
 *
 * @param int    $id
 * @param string $type staff|client|contact
 * @return string
 */
function get_asset_recipient_link($id, $type = 'staff')
{
    $id   = (int) $id;
    $name = htmlspecialchars(get_asset_recipient_name($id, $type));

    switch ($type) {
        case 'client':
            return '<a href="' . admin_url('clients/client/' . $id) . '">' . $name . '</a>';
        case 'contact':
            $CI      = &get_instance();
            $contact = $CI->db->select('userid')->where('id', $id)->get(db_prefix() . 'contacts')->row();
            if ($contact) {
                return '<a href="' . admin_url('clients/client/' . $contact->userid) . '">' . $name . '</a>';
            }
            return $name;
        case 'staff':
        default:
            return '<a href="' . admin_url('staff/profile/' . $id) . '">' . $name . '</a>';
    }
}

/**
 * Calculate depreciation for an asset
 */
function calculate_asset_depreciation($asset)
{
    if (!is_object($asset)) {
        $CI = &get_instance();
        $CI->db->where('id', $asset);
        $asset = $CI->db->get(db_prefix().'assets')->row();
    }
    
    if (!$asset || $asset->depreciation <= 0) {
        return [
            'months_used' => 0,
            'total_value' => $asset ? ($asset->unit_price * $asset->amount) : 0,
            'depreciation_value' => 0,
            'residual_value' => $asset ? ($asset->unit_price * $asset->amount) : 0,
            'depreciation_percentage' => 0
        ];
    }
    
    $purchase_date = new DateTime($asset->date_buy);
    $now = new DateTime();
    $months_used = $purchase_date->diff($now)->m + ($purchase_date->diff($now)->y * 12);
    
    $total_value = $asset->unit_price * $asset->amount;
    $monthly_depreciation = $total_value / $asset->depreciation;
    $depreciation_value = min($monthly_depreciation * $months_used, $total_value);
    $residual_value = max($total_value - $depreciation_value, 0);
    
    return [
        'months_used' => $months_used,
        'total_value' => $total_value,
        'monthly_depreciation' => $monthly_depreciation,
        'depreciation_value' => $depreciation_value,
        'residual_value' => $residual_value,
        'depreciation_percentage' => $total_value > 0 ? ($depreciation_value / $total_value) * 100 : 0
    ];
}

/**
 * Get available quantity for an asset
 */
function get_asset_available_quantity($asset_id)
{
    $CI = &get_instance();
    $CI->db->select('amount, total_allocation');
    $CI->db->where('id', $asset_id);
    $asset = $CI->db->get(db_prefix().'assets')->row();
    
    if ($asset) {
        return max(0, $asset->amount - $asset->total_allocation);
    }
    
    return 0;
}

/**
 * Check if asset is available for checkout/reservation
 */
function is_asset_available($asset_id, $quantity = 1)
{
    return get_asset_available_quantity($asset_id) >= $quantity;
}

/**
 * Get warranty status for an asset
 */
function get_asset_warranty_status($asset)
{
    if (!is_object($asset)) {
        $asset = get_asset_by_id($asset);
    }
    
    if (!$asset || empty($asset->date_buy) || $asset->warranty_period <= 0) {
        return ['status' => 'none', 'days_remaining' => 0, 'label' => _l('no_warranty')];
    }
    
    $warranty_end = date('Y-m-d', strtotime($asset->date_buy . ' + ' . $asset->warranty_period . ' months'));
    $days_remaining = (strtotime($warranty_end) - time()) / 86400;
    
    if ($days_remaining < 0) {
        return ['status' => 'expired', 'days_remaining' => 0, 'label' => _l('warranty_expired'), 'class' => 'danger'];
    } elseif ($days_remaining <= 30) {
        return ['status' => 'expiring', 'days_remaining' => round($days_remaining), 'label' => sprintf(_l('warranty_expiring_in_days'), round($days_remaining)), 'class' => 'warning'];
    } else {
        return ['status' => 'active', 'days_remaining' => round($days_remaining), 'label' => _l('warranty_active'), 'class' => 'success'];
    }
}

/**
 * Format asset code with prefix
 */
function format_asset_code($code, $prefix = 'AST')
{
    if (strpos($code, $prefix) === 0) {
        return $code;
    }
    return $prefix . str_pad($code, 6, '0', STR_PAD_LEFT);
}

/**
 * Generate unique asset code
 */
function generate_unique_asset_code($prefix = 'AST')
{
    $CI = &get_instance();
    
    do {
        $code = $prefix . strtoupper(substr(md5(uniqid()), 0, 8));
        $CI->db->where('assets_code', $code);
        $exists = $CI->db->count_all_results(db_prefix().'assets') > 0;
    } while ($exists);
    
    return $code;
}

/**
 * Get total asset value
 */
function get_total_assets_value()
{
    $CI = &get_instance();
    $CI->db->select('SUM(amount * unit_price) as total');
    $result = $CI->db->get(db_prefix().'assets')->row();
    return $result ? ($result->total ?: 0) : 0;
}

/**
 * Get assets count by status
 */
function get_assets_count_by_status($status = null)
{
    $CI = &get_instance();
    
    if ($status !== null) {
        $CI->db->where('status', $status);
    }
    
    return $CI->db->count_all_results(db_prefix().'assets');
}

/**
 * Get maintenance status badge
 */
function get_maintenance_status_badge($status)
{
    $badges = [
        'scheduled' => '<span class="label label-default">'._l('scheduled').'</span>',
        'in_progress' => '<span class="label label-info">'._l('in_progress').'</span>',
        'completed' => '<span class="label label-success">'._l('completed').'</span>',
        'cancelled' => '<span class="label label-warning">'._l('cancelled').'</span>',
        'overdue' => '<span class="label label-danger">'._l('overdue').'</span>',
    ];
    
    return $badges[$status] ?? '<span class="label label-default">'.$status.'</span>';
}

/**
 * Get checkout status badge
 */
function get_checkout_status_badge($status)
{
    $badges = [
        'checked_out' => '<span class="label label-info">'._l('checked_out').'</span>',
        'returned' => '<span class="label label-success">'._l('returned').'</span>',
        'overdue' => '<span class="label label-danger">'._l('overdue').'</span>',
    ];
    
    return $badges[$status] ?? '<span class="label label-default">'.$status.'</span>';
}

/**
 * Get reservation status badge
 */
function get_reservation_status_badge($status)
{
    $badges = [
        'pending' => '<span class="label label-warning">'._l('pending').'</span>',
        'approved' => '<span class="label label-success">'._l('approved').'</span>',
        'rejected' => '<span class="label label-danger">'._l('rejected').'</span>',
        'cancelled' => '<span class="label label-default">'._l('cancelled').'</span>',
        'completed' => '<span class="label label-info">'._l('completed').'</span>',
    ];
    
    return $badges[$status] ?? '<span class="label label-default">'.$status.'</span>';
}

/**
 * Get condition badge
 */
function get_condition_badge($condition)
{
    $badges = [
        'excellent' => '<span class="label label-success">'._l('excellent').'</span>',
        'good' => '<span class="label label-info">'._l('good').'</span>',
        'fair' => '<span class="label label-warning">'._l('fair').'</span>',
        'poor' => '<span class="label label-danger">'._l('poor').'</span>',
        'damaged' => '<span class="label label-danger">'._l('damaged').'</span>',
    ];
    
    return $badges[$condition] ?? '<span class="label label-default">'.$condition.'</span>';
}

/**
 * Log asset activity (shortcut for audit log)
 */
function log_asset_activity($asset_id, $action, $description, $old_values = null, $new_values = null)
{
    $CI = &get_instance();
    $CI->load->model('assets/assets_model');
    $CI->assets_model->log_audit($asset_id, $action, $description, $old_values, $new_values);
}

/**
 * Trigger asset webhook (shortcut)
 */
function trigger_asset_webhook($event, $asset_id, $data = [])
{
    $CI = &get_instance();
    $CI->load->library('assets/assets_webhooks');
    
    $asset = get_asset_by_id($asset_id);
    $payload = [
        'asset_id' => $asset_id,
        'asset' => $asset ? (array)$asset : [],
        'data' => $data
    ];
    
    $CI->assets_webhooks->trigger($event, $payload);
}
