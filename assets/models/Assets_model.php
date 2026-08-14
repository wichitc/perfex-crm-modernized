<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Assets_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ============================================
    // CORE ASSET METHODS
    // ============================================

    public function get($id = '')
    {
        if ('' == $id) {
            return $this->db->get(db_prefix().'assets')->result_array();
        }
        $this->db->where('id', $id);
        return $this->db->get(db_prefix().'assets')->row();
    }

    public function get_clients_assign_assets($table, $where = [])
    {
        $this->db->select(db_prefix().'assets.*,'.db_prefix().'asset_unit.*,'.db_prefix().'assets_group.*,'.db_prefix().'departments.*, '.db_prefix().'departments.name as dpm_name');

        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->where('visible_to_client', 1);
        $this->db->join(db_prefix().'asset_unit', db_prefix().'asset_unit.unit_id = '.db_prefix().'assets.unit', 'LEFT');
        $this->db->join(db_prefix().'assets_group', db_prefix().'assets_group.group_id = '.db_prefix().'assets.asset_group', 'LEFT');
        $this->db->join(db_prefix().'departments', db_prefix().'departments.departmentid = '.db_prefix().'assets.department', 'LEFT');

        return $this->db->get(db_prefix().$table)->result_array();
    }

    public function add_asset($data)
    {
        $data['unit_price'] = isset($data['unit_price']) && $data['unit_price'] !== ''
            ? reformat_currency_asset($data['unit_price'])
            : 0;
        $data['date_buy'] = !empty($data['date_buy']) ? to_sql_date($data['date_buy']) : date('Y-m-d');
        $data['warranty_period'] = isset($data['warranty_period']) && $data['warranty_period'] !== ''
            ? (int) $data['warranty_period']
            : 0;
        $data['depreciation'] = isset($data['depreciation']) && $data['depreciation'] !== ''
            ? (int) $data['depreciation']
            : 0;
        
        if (isset($data['file_asset'])) {
            unset($data['file_asset']);
        }
        if (!empty($data['clientid'])) {
            $data['belongs_to'] = implode(',', $data['clientid']);
            unset($data['clientid']);
        }
        if (isset($data['visible_to_client'])) {
            $data['visible_to_client'] = 1;
        }
        
        // Handle new fields
        if (!empty($data['expected_end_of_life'])) {
            $data['expected_end_of_life'] = to_sql_date($data['expected_end_of_life']);
        }
        if (!empty($data['insurance_expiry'])) {
            $data['insurance_expiry'] = to_sql_date($data['insurance_expiry']);
        }
        if (!empty($data['next_maintenance_date'])) {
            $data['next_maintenance_date'] = to_sql_date($data['next_maintenance_date']);
        }
        
        // Audit fields
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = get_staff_user_id();
        
        $this->db->insert(db_prefix().'assets', $data);
        $insert_id = $this->db->insert_id();
        
        if ($insert_id) {
            $this->db->insert(db_prefix().'inventory_history', [
                'assets' => $insert_id,
                'date_time' => $data['date_buy'],
                'acction' => 'add_new',
                'inventory_begin' => 0,
                'inventory_end' => $data['amount'],
                'cost' => $data['unit_price'] * $data['amount'],
            ]);

            // Log audit
            $this->log_audit($insert_id, 'created', 'Asset created', null, $data);
            
            // Trigger webhook
            $this->trigger_webhook('asset.created', $insert_id);

            return $insert_id;
        }
        return false;
    }

    public function update_asset($data, $id)
    {
        $old_asset = $this->get($id);
        
        if (!empty($data['clientid'])) {
            $data['belongs_to'] = implode(',', $data['clientid']);
            unset($data['clientid']);
        }
        if (isset($data['visible_to_client'])) {
            $data['visible_to_client'] = 1;
        } else {
            $data['visible_to_client'] = 0;
        }
        if (isset($data['unit_price']) && $data['unit_price'] !== '') {
            $data['unit_price'] = reformat_currency_asset($data['unit_price']);
        }
        if (isset($data['unit_price']) && $data['unit_price'] === '') {
            $data['unit_price'] = 0;
        }
        if (!empty($data['date_buy'])) {
            $data['date_buy'] = to_sql_date($data['date_buy']);
        } elseif (isset($data['date_buy']) && $data['date_buy'] === '') {
            $data['date_buy'] = date('Y-m-d');
        }
        if (isset($data['warranty_period']) && $data['warranty_period'] === '') {
            $data['warranty_period'] = 0;
        }
        if (isset($data['depreciation']) && $data['depreciation'] === '') {
            $data['depreciation'] = 0;
        }
        
        // Handle new fields
        if (isset($data['expected_end_of_life'])) {
            $data['expected_end_of_life'] = !empty($data['expected_end_of_life']) ? to_sql_date($data['expected_end_of_life']) : null;
        }
        if (isset($data['insurance_expiry'])) {
            $data['insurance_expiry'] = !empty($data['insurance_expiry']) ? to_sql_date($data['insurance_expiry']) : null;
        }
        if (isset($data['next_maintenance_date'])) {
            $data['next_maintenance_date'] = !empty($data['next_maintenance_date']) ? to_sql_date($data['next_maintenance_date']) : null;
        }
        
        // Audit fields
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = get_staff_user_id();
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'assets', $data);
        
        if ($this->db->affected_rows() > 0) {
            // Log audit
            $this->log_audit($id, 'updated', 'Asset updated', (array)$old_asset, $data);
            
            // Trigger webhook
            $this->trigger_webhook('asset.updated', $id);
            
            return true;
        }
        return false;
    }

    public function delete_assets($id)
    {
        $asset = $this->get($id);
        
        // Delete attachments
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'assets');
        $attachments = $this->db->get('tblfiles')->result_array();
        foreach ($attachments as $attachment) {
            $this->delete_assets_attachment($attachment['id']);
        }
        
        // Delete related records
        $this->db->where('asset_id', $id)->delete(db_prefix().'asset_maintenance');
        $this->db->where('asset_id', $id)->delete(db_prefix().'asset_checkouts');
        $this->db->where('asset_id', $id)->delete(db_prefix().'asset_reservations');
        $this->db->where('asset_id', $id)->delete(db_prefix().'asset_custom_field_values');
        $this->db->where('asset_id', $id)->delete(db_prefix().'asset_transfers');
        $this->db->where('asset_id', $id)->delete(db_prefix().'asset_project_rel');
        $this->db->where('asset_id', $id)->delete(db_prefix().'asset_expenses');
        $this->db->where('assets', $id)->delete(db_prefix().'inventory_history');
        
        // Delete asset
        $this->db->where('id', $id);
        $this->db->delete(db_prefix().'assets');
        
        if ($this->db->affected_rows() > 0) {
            // Log audit (asset_id will be null since asset is deleted)
            $this->log_audit(null, 'deleted', 'Asset deleted: ' . $asset->assets_code, (array)$asset, null);
            
            // Trigger webhook
            $this->trigger_webhook('asset.deleted', $id, (array)$asset);
            
            return true;
        }
        return false;
    }

    // ============================================
    // ASSET GROUP/UNIT/LOCATION METHODS
    // ============================================

    public function get_asset_group($id = '')
    {
        if ('' == $id) {
            return $this->db->get(db_prefix().'assets_group')->result_array();
        }
        $this->db->where('group_id', $id);
        return $this->db->get(db_prefix().'assets_group')->row();
    }

    public function get_asset_unit($id = '')
    {
        if ('' == $id) {
            return $this->db->get(db_prefix().'asset_unit')->result_array();
        }
        $this->db->where('unit_id', $id);
        return $this->db->get(db_prefix().'asset_unit')->row();
    }

    public function get_asset_location($id = '')
    {
        if ('' == $id) {
            return $this->db->get(db_prefix().'asset_location')->result_array();
        }
        $this->db->where('location_id', $id);
        return $this->db->get(db_prefix().'asset_location')->row();
    }

    public function add_asset_group($data)
    {
        $this->db->insert(db_prefix().'assets_group', $data);
        return $this->db->insert_id();
    }

    public function update_asset_group($data, $id)
    {
        $this->db->where('group_id', $id);
        $this->db->update(db_prefix().'assets_group', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_asset_group($id)
    {
        $this->db->where('group_id', $id);
        $this->db->delete(db_prefix().'assets_group');
        return $this->db->affected_rows() > 0;
    }

    public function add_asset_unit($data)
    {
        $this->db->insert(db_prefix().'asset_unit', $data);
        return $this->db->insert_id();
    }

    public function update_asset_unit($data, $id)
    {
        $this->db->where('unit_id', $id);
        $this->db->update(db_prefix().'asset_unit', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_asset_unit($id)
    {
        $this->db->where('unit_id', $id);
        $this->db->delete(db_prefix().'asset_unit');
        return $this->db->affected_rows() > 0;
    }

    public function add_asset_location($data)
    {
        $this->db->insert(db_prefix().'asset_location', $data);
        return $this->db->insert_id();
    }

    public function update_asset_location($data, $id)
    {
        $this->db->where('location_id', $id);
        $this->db->update(db_prefix().'asset_location', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_asset_location($id)
    {
        $this->db->where('location_id', $id);
        $this->db->delete(db_prefix().'asset_location');
        return $this->db->affected_rows() > 0;
    }

    // ============================================
    // ALLOCATION/REVOCATION METHODS
    // ============================================

    public function allocation_asset($data)
    {
        $assets = $this->get($data['assets']);
        $data['time_acction'] = to_sql_date($data['time_acction'], true);

        // Recipient type drives the audit text and notification path. Drop the
        // column on installs that have not run migration 130 yet, so the insert
        // never references a column that does not exist.
        $recipient_type = isset($data['acction_to_type']) ? $data['acction_to_type'] : 'staff';
        if (!$this->db->field_exists('acction_to_type', db_prefix().'assets_acction_1')) {
            unset($data['acction_to_type']);
        }

        $this->db->insert('tblassets_acction_1', $data);
        $insert_id = $this->db->insert_id();
        
        if ($insert_id) {
            $this->db->insert(db_prefix().'inventory_history', [
                'assets' => $data['assets'],
                'date_time' => $data['time_acction'],
                'acction' => $data['type'],
                'inventory_begin' => $assets->amount - $assets->total_allocation,
                'inventory_end' => $assets->amount - $assets->total_allocation - $data['amount'],
            ]);

            $this->db->where('id', $data['assets']);
            $this->db->update(db_prefix().'assets', [
                'total_allocation' => $assets->total_allocation + $data['amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Log audit
            $this->log_audit($data['assets'], 'allocated', 'Asset allocated to ' . $recipient_type . ' #' . $data['acction_to'], null, $data);

            // Trigger webhook
            $this->trigger_webhook('asset.allocated', $data['assets'], $data);

            // Send notification (only staff members have an in-app inbox)
            if ('staff' === $recipient_type) {
                $this->load->library('assets/assets_notifications');
                $this->assets_notifications->notify_asset_allocated($assets, $data['acction_to'], $data['amount']);
            }

            return $insert_id;
        }
        return false;
    }

    public function revoke_asset($data)
    {
        $assets = $this->get($data['assets']);
        $data['time_acction'] = to_sql_date($data['time_acction'], true);

        $recipient_type = isset($data['acction_to_type']) ? $data['acction_to_type'] : 'staff';
        if (!$this->db->field_exists('acction_to_type', db_prefix().'assets_acction_1')) {
            unset($data['acction_to_type']);
        }

        $this->db->insert('tblassets_acction_1', $data);
        $insert_id = $this->db->insert_id();
        
        if ($insert_id) {
            $this->db->insert(db_prefix().'inventory_history', [
                'assets' => $data['assets'],
                'date_time' => $data['time_acction'],
                'acction' => $data['type'],
                'inventory_begin' => $assets->amount - $assets->total_allocation,
                'inventory_end' => $assets->amount - $assets->total_allocation + $data['amount'],
            ]);

            $this->db->where('id', $data['assets']);
            $this->db->update(db_prefix().'assets', [
                'total_allocation' => $assets->total_allocation - $data['amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Log audit
            $this->log_audit($data['assets'], 'revoked', 'Asset revoked from ' . $recipient_type . ' #' . $data['acction_to'], null, $data);
            
            // Trigger webhook
            $this->trigger_webhook('asset.revoked', $data['assets'], $data);

            return $insert_id;
        }
        return false;
    }

    public function get_asset_allocation_by_staff($recipient, $asset, $type = 'staff')
    {
        $this->db->where('acction_to', $recipient);
        if ($this->db->field_exists('acction_to_type', db_prefix().'assets_acction_1')) {
            $this->db->where('acction_to_type', $type);
        }
        $this->db->where('assets', $asset);
        $this->db->where('type', 'allocation');
        return $this->db->get(db_prefix().'assets_acction_1')->result_array();
    }

    public function get_asset_revoke_by_staff($recipient, $asset, $type = 'staff')
    {
        $this->db->where('acction_to', $recipient);
        if ($this->db->field_exists('acction_to_type', db_prefix().'assets_acction_1')) {
            $this->db->where('acction_to_type', $type);
        }
        $this->db->where('assets', $asset);
        $this->db->where('type', 'revoke');
        return $this->db->get(db_prefix().'assets_acction_1')->result_array();
    }

    // ============================================
    // OTHER ASSET ACTIONS (LOST, BROKEN, WARRANTY, ETC)
    // ============================================

    public function additional_asset($data)
    {
        $assets = $this->get($data['assets']);
        $data['acction_from'] = get_staff_user_id();
        $data['time_acction'] = to_sql_date($data['time_acction'], true);
        $data['cost'] = $assets->unit_price * $data['amount'];
        
        $this->db->insert('tblassets_acction_2', $data);
        $insert_id = $this->db->insert_id();
        
        if ($insert_id) {
            $this->db->insert(db_prefix().'inventory_history', [
                'assets' => $data['assets'],
                'date_time' => $data['time_acction'],
                'acction' => $data['type'],
                'inventory_begin' => $assets->amount - $assets->total_allocation,
                'inventory_end' => $assets->amount - $assets->total_allocation + $data['amount'],
                'cost' => $data['cost'],
            ]);

            $this->db->where('id', $data['assets']);
            $this->db->update(db_prefix().'assets', [
                'amount' => $assets->amount + $data['amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $insert_id;
        }
        return false;
    }

    public function lost_asset($data)
    {
        $assets = $this->get($data['assets']);
        $data['acction_from'] = get_staff_user_id();
        $data['time_acction'] = to_sql_date($data['time_acction'], true);
        
        $this->db->insert('tblassets_acction_2', $data);
        $insert_id = $this->db->insert_id();
        $asset_id = $data['assets'];

        if ($insert_id) {
            $this->db->insert(db_prefix().'inventory_history', [
                'assets' => $data['assets'],
                'date_time' => $data['time_acction'],
                'acction' => $data['type'],
                'inventory_begin' => $assets->amount - $assets->total_allocation,
                'inventory_end' => $assets->amount - $assets->total_allocation - $data['amount'],
            ]);

            $this->db->where('id', $asset_id);
            $this->db->update(db_prefix().'assets', [
                'amount' => $assets->amount - $data['amount'],
                'total_lost' => $assets->total_lost + $data['amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->log_audit($asset_id, 'lost', 'Asset reported lost', null, $data);
            $this->trigger_webhook('asset.lost', $asset_id, $data);

            return $insert_id;
        }
        return false;
    }

    public function broken_asset($data)
    {
        $assets = $this->get($data['assets']);
        $data['acction_from'] = get_staff_user_id();
        $data['time_acction'] = to_sql_date($data['time_acction'], true);
        
        $this->db->insert('tblassets_acction_2', $data);
        $insert_id = $this->db->insert_id();
        $asset_id = $data['assets'];

        if ($insert_id) {
            $this->db->insert(db_prefix().'inventory_history', [
                'assets' => $data['assets'],
                'date_time' => $data['time_acction'],
                'acction' => $data['type'],
                'inventory_begin' => $assets->amount - $assets->total_allocation,
                'inventory_end' => $assets->amount - $assets->total_allocation,
            ]);

            $this->db->where('id', $asset_id);
            $this->db->update(db_prefix().'assets', [
                'total_damages' => $assets->total_damages + $data['amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->log_audit($asset_id, 'broken', 'Asset reported broken', null, $data);
            $this->trigger_webhook('asset.broken', $asset_id, $data);

            return $insert_id;
        }
        return false;
    }

    public function liquidation_asset($data)
    {
        $assets = $this->get($data['assets']);
        $data['cost'] = reformat_currency_asset($data['cost']);
        $data['acction_from'] = get_staff_user_id();
        $data['time_acction'] = to_sql_date($data['time_acction'], true);
        
        $this->db->insert('tblassets_acction_2', $data);
        $insert_id = $this->db->insert_id();
        $asset_id = $data['assets'];

        if ($insert_id) {
            $this->db->insert(db_prefix().'inventory_history', [
                'assets' => $data['assets'],
                'date_time' => $data['time_acction'],
                'acction' => $data['type'],
                'inventory_begin' => $assets->amount - $assets->total_allocation,
                'inventory_end' => $assets->amount - $assets->total_allocation - $data['amount'],
                'cost' => $data['cost'],
            ]);

            $this->db->where('id', $asset_id);
            $this->db->update(db_prefix().'assets', [
                'amount' => $assets->amount - $data['amount'],
                'total_liquidation' => $assets->total_liquidation + $data['amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->log_audit($asset_id, 'liquidated', 'Asset liquidated', null, $data);
            $this->trigger_webhook('asset.liquidated', $asset_id, $data);

            return $insert_id;
        }
        return false;
    }

    public function warranty_asset($data)
    {
        $assets = $this->get($data['assets']);
        $data['cost'] = reformat_currency_asset($data['cost']);
        $data['acction_from'] = get_staff_user_id();
        $data['time_acction'] = to_sql_date($data['time_acction'], true);
        
        $this->db->insert('tblassets_acction_2', $data);
        $insert_id = $this->db->insert_id();
        $asset_id = $data['assets'];

        if ($insert_id) {
            $this->db->insert(db_prefix().'inventory_history', [
                'assets' => $data['assets'],
                'date_time' => $data['time_acction'],
                'acction' => $data['type'],
                'inventory_begin' => $assets->amount - $assets->total_allocation,
                'inventory_end' => $assets->amount - $assets->total_allocation,
                'cost' => $data['cost'],
            ]);

            $this->db->where('id', $asset_id);
            $this->db->update(db_prefix().'assets', [
                'total_warranty' => $assets->total_liquidation + $data['amount'],
                'total_damages' => $assets->total_damages - $data['amount'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->log_audit($asset_id, 'warranty', 'Asset sent for warranty', null, $data);
            $this->trigger_webhook('asset.warranty', $asset_id, $data);

            return $insert_id;
        }
        return false;
    }

    public function get_amount_asset_broken($asset)
    {
        $this->db->where('assets', $asset);
        $this->db->where('type', 'broken');
        return $this->db->get(db_prefix().'assets_acction_2')->result_array();
    }

    public function get_amount_asset_warranty($asset)
    {
        $this->db->where('assets', $asset);
        $this->db->where('type', 'warranty');
        return $this->db->get(db_prefix().'assets_acction_2')->result_array();
    }

    // ============================================
    // FILE ATTACHMENT METHODS
    // ============================================

    public function get_assets_attachments($assets, $id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'assets');
        $result = $this->db->get('tblfiles');
        if (is_numeric($id)) {
            return $result->row();
        }
        return $result->result_array();
    }

    public function delete_assets_attachment($id)
    {
        $attachment = $this->get_assets_attachments('', $id);
        $deleted = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                @unlink(ASSETS_UPLOAD_FOLDER.'/'.$attachment->rel_id.'/'.$attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(ASSETS_UPLOAD_FOLDER.'/'.$attachment->rel_id)) {
                $other_attachments = list_files(ASSETS_UPLOAD_FOLDER.'/'.$attachment->rel_id);
                if (0 == count($other_attachments)) {
                    delete_dir(ASSETS_UPLOAD_FOLDER.'/'.$attachment->rel_id);
                }
            }
        }
        return $deleted;
    }

    public function get_asset_file($asset)
    {
        $this->db->where('rel_id', $asset);
        $this->db->where('rel_type', 'assets');
        return $this->db->get('tblfiles')->result_array();
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

    public function get_assets($id = '')
    {
        if ('' != $id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'assets')->row();
        }
        return $this->db->get(db_prefix().'assets')->result_array();
    }

    // ============================================
    // MAINTENANCE METHODS
    // ============================================

    public function get_maintenance($id = null, $asset_id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'asset_maintenance')->row();
        }
        if ($asset_id) {
            $this->db->where('asset_id', $asset_id);
        }
        $this->db->order_by('scheduled_date', 'DESC');
        return $this->db->get(db_prefix().'asset_maintenance')->result_array();
    }

    public function add_maintenance($data)
    {
        $data['scheduled_date'] = to_sql_date($data['scheduled_date']);
        if (!empty($data['completed_date'])) {
            $data['completed_date'] = to_sql_date($data['completed_date']);
        }
        if (!empty($data['cost'])) {
            $data['cost'] = reformat_currency_asset($data['cost']);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = get_staff_user_id();

        $this->db->insert(db_prefix().'asset_maintenance', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // Update asset's next maintenance date
            $this->db->where('id', $data['asset_id']);
            $this->db->update(db_prefix().'assets', [
                'next_maintenance_date' => $data['scheduled_date'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->log_audit($data['asset_id'], 'maintenance_scheduled', 'Maintenance scheduled', null, $data);
            $this->trigger_webhook('asset.maintenance_scheduled', $data['asset_id'], $data);
        }

        return $insert_id;
    }

    public function update_maintenance($id, $data)
    {
        // Build update array with only valid columns
        $update_data = [];
        
        $valid_columns = ['asset_id', 'maintenance_type', 'title', 'scheduled_date', 'completed_date', 
                          'cost', 'vendor_name', 'vendor_contact', 'description', 'is_recurring', 
                          'recurring_interval', 'recurring_unit', 'status', 'notes'];
        
        foreach ($valid_columns as $col) {
            if (isset($data[$col])) {
                $update_data[$col] = $data[$col];
            }
        }
        
        if (!empty($update_data['scheduled_date'])) {
            $update_data['scheduled_date'] = to_sql_date($update_data['scheduled_date']);
        }
        if (!empty($update_data['completed_date'])) {
            $update_data['completed_date'] = to_sql_date($update_data['completed_date']);
        }
        if (isset($update_data['cost'])) {
            $update_data['cost'] = reformat_currency_asset($update_data['cost']);
        }
        // Handle checkbox - if not set, it means unchecked
        if (!isset($data['is_recurring'])) {
            $update_data['is_recurring'] = 0;
        }
        
        $update_data['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'asset_maintenance', $update_data);

        if ($this->db->affected_rows() > 0) {
            $maintenance = $this->get_maintenance($id);
            
            if (isset($update_data['status']) && $update_data['status'] == 'completed' && !empty($update_data['completed_date'])) {
                // Update asset's last maintenance date
                $this->db->where('id', $maintenance->asset_id);
                $this->db->update(db_prefix().'assets', [
                    'last_maintenance_date' => $update_data['completed_date'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $this->log_audit($maintenance->asset_id, 'maintenance_completed', 'Maintenance completed', null, $update_data);
                $this->trigger_webhook('asset.maintenance_completed', $maintenance->asset_id, $update_data);

                // Create next maintenance if recurring
                if ($maintenance->is_recurring && $maintenance->recurring_interval > 0) {
                    $this->schedule_next_maintenance($maintenance);
                }
            }
            return true;
        }
        return false;
    }

    public function delete_maintenance($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix().'asset_maintenance');
        return $this->db->affected_rows() > 0;
    }

    protected function schedule_next_maintenance($maintenance)
    {
        $interval = $maintenance->recurring_interval;
        $unit = $maintenance->recurring_unit;
        
        $next_date = date('Y-m-d', strtotime("+{$interval} {$unit}", strtotime($maintenance->completed_date)));
        
        $new_maintenance = [
            'asset_id' => $maintenance->asset_id,
            'maintenance_type' => $maintenance->maintenance_type,
            'title' => $maintenance->title,
            'description' => $maintenance->description,
            'scheduled_date' => $next_date,
            'vendor_name' => $maintenance->vendor_name,
            'vendor_contact' => $maintenance->vendor_contact,
            'is_recurring' => 1,
            'recurring_interval' => $maintenance->recurring_interval,
            'recurring_unit' => $maintenance->recurring_unit,
        ];
        
        return $this->add_maintenance($new_maintenance);
    }

    public function get_due_maintenance($days_ahead = 7)
    {
        $date = date('Y-m-d', strtotime("+{$days_ahead} days"));
        $this->db->where('scheduled_date <=', $date);
        $this->db->where('status', 'scheduled');
        return $this->db->get(db_prefix().'asset_maintenance')->result_array();
    }

    public function get_overdue_maintenance()
    {
        $this->db->where('scheduled_date <', date('Y-m-d'));
        $this->db->where_in('status', ['scheduled', 'in_progress']);
        $this->db->update(db_prefix().'asset_maintenance', ['status' => 'overdue']);
        
        $this->db->where('status', 'overdue');
        return $this->db->get(db_prefix().'asset_maintenance')->result_array();
    }

    // ============================================
    // CHECK-IN/CHECK-OUT METHODS
    // ============================================

    public function get_checkouts($id = null, $asset_id = null, $status = null)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'asset_checkouts')->row();
        }
        if ($asset_id) {
            $this->db->where('asset_id', $asset_id);
        }
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('checkout_date', 'DESC');
        return $this->db->get(db_prefix().'asset_checkouts')->result_array();
    }

    public function checkout_asset($data)
    {
        $asset = $this->get($data['asset_id']);
        $available = $asset->amount - $asset->total_allocation;
        
        if ($data['quantity'] > $available) {
            return ['error' => 'Not enough available quantity'];
        }

        $data['checkout_date'] = date('Y-m-d H:i:s');
        if (!empty($data['expected_return_date'])) {
            $data['expected_return_date'] = to_sql_date($data['expected_return_date']);
        }
        $data['checkout_by'] = get_staff_user_id();
        $data['status'] = 'checked_out';
        $data['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix().'asset_checkouts', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // Update asset allocation
            $this->db->where('id', $data['asset_id']);
            $this->db->update(db_prefix().'assets', [
                'total_allocation' => $asset->total_allocation + $data['quantity'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->log_audit($data['asset_id'], 'checked_out', 'Asset checked out to ' . $data['checked_out_to_type'] . ' #' . $data['checked_out_to'], null, $data);
            $this->trigger_webhook('asset.checked_out', $data['asset_id'], $data);
        }

        return $insert_id;
    }

    public function checkin_asset($checkout_id, $data = [])
    {
        $checkout = $this->get_checkouts($checkout_id);
        // Allow check-in for both 'checked_out' and 'overdue' statuses
        if (!$checkout || !in_array($checkout->status, ['checked_out', 'overdue'])) {
            return false;
        }

        $asset = $this->get($checkout->asset_id);

        $update = [
            'actual_return_date' => date('Y-m-d H:i:s'),
            'checkin_by' => get_staff_user_id(),
            'status' => 'returned',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($data['checkin_notes'])) {
            $update['checkin_notes'] = $data['checkin_notes'];
        }
        if (!empty($data['checkin_condition'])) {
            $update['checkin_condition'] = $data['checkin_condition'];
        }

        $this->db->where('id', $checkout_id);
        $this->db->update(db_prefix().'asset_checkouts', $update);

        if ($this->db->affected_rows() > 0) {
            // Update asset allocation
            $this->db->where('id', $checkout->asset_id);
            $this->db->update(db_prefix().'assets', [
                'total_allocation' => max(0, $asset->total_allocation - $checkout->quantity),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->log_audit($checkout->asset_id, 'checked_in', 'Asset checked in', null, $update);
            $this->trigger_webhook('asset.checked_in', $checkout->asset_id, array_merge((array)$checkout, $update));

            return true;
        }
        return false;
    }

    public function get_overdue_checkouts()
    {
        $this->db->where('expected_return_date <', date('Y-m-d'));
        $this->db->where('status', 'checked_out');
        $this->db->update(db_prefix().'asset_checkouts', ['status' => 'overdue']);
        
        $this->db->where('status', 'overdue');
        return $this->db->get(db_prefix().'asset_checkouts')->result_array();
    }

    // ============================================
    // RESERVATION METHODS
    // ============================================

    public function get_reservations($id = null, $asset_id = null, $status = null)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'asset_reservations')->row();
        }
        if ($asset_id) {
            $this->db->where('asset_id', $asset_id);
        }
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('reservation_start', 'DESC');
        return $this->db->get(db_prefix().'asset_reservations')->result_array();
    }

    public function create_reservation($data)
    {
        $data['reservation_start'] = to_sql_date($data['reservation_start'], true);
        $data['reservation_end'] = to_sql_date($data['reservation_end'], true);
        $data['reserved_by'] = isset($data['reserved_by']) ? $data['reserved_by'] : get_staff_user_id();
        $data['status'] = isset($data['status']) ? $data['status'] : 'pending';
        $data['created_at'] = date('Y-m-d H:i:s');

        // Check availability
        if (!$this->check_availability($data['asset_id'], $data['reservation_start'], $data['reservation_end'], $data['quantity'])) {
            return ['error' => 'Asset not available for the requested time period'];
        }

        $this->db->insert(db_prefix().'asset_reservations', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            $this->log_audit($data['asset_id'], 'reserved', 'Asset reserved', null, $data);
            $this->trigger_webhook('asset.reserved', $data['asset_id'], $data);
        }

        return $insert_id;
    }

    public function update_reservation($id, $data)
    {
        if (!empty($data['reservation_start'])) {
            $data['reservation_start'] = to_sql_date($data['reservation_start'], true);
        }
        if (!empty($data['reservation_end'])) {
            $data['reservation_end'] = to_sql_date($data['reservation_end'], true);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'asset_reservations', $data);

        return $this->db->affected_rows() > 0;
    }

    public function approve_reservation($id)
    {
        $reservation = $this->get_reservations($id);
        if (!$reservation) return false;

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'asset_reservations', [
            'status' => 'approved',
            'approved_by' => get_staff_user_id(),
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() > 0) {
            $asset = $this->get($reservation->asset_id);
            $this->trigger_webhook('asset.reservation_approved', $reservation->asset_id, (array)$reservation);
            
            $this->load->library('assets/assets_notifications');
            $this->assets_notifications->notify_reservation_status($reservation, $asset, 'approved');
            
            return true;
        }
        return false;
    }

    public function reject_reservation($id, $reason = '')
    {
        $reservation = $this->get_reservations($id);
        if (!$reservation) return false;

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'asset_reservations', [
            'status' => 'rejected',
            'notes' => $reason,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() > 0) {
            $asset = $this->get($reservation->asset_id);
            $this->trigger_webhook('asset.reservation_rejected', $reservation->asset_id, (array)$reservation);
            
            $this->load->library('assets/assets_notifications');
            $this->assets_notifications->notify_reservation_status($reservation, $asset, 'rejected');
            
            return true;
        }
        return false;
    }

    public function check_availability($asset_id, $start, $end, $quantity)
    {
        $asset = $this->get($asset_id);
        $available = $asset->amount - $asset->total_allocation;

        // Check existing reservations
        $this->db->where('asset_id', $asset_id);
        $this->db->where('status', 'approved');
        $this->db->group_start();
        $this->db->where('reservation_start <=', $end);
        $this->db->where('reservation_end >=', $start);
        $this->db->group_end();
        
        $reservations = $this->db->get(db_prefix().'asset_reservations')->result();
        
        $reserved_quantity = 0;
        foreach ($reservations as $res) {
            $reserved_quantity += $res->quantity;
        }

        return ($available - $reserved_quantity) >= $quantity;
    }

    public function delete_reservation($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix().'asset_reservations');
        return $this->db->affected_rows() > 0;
    }

    // ============================================
    // CUSTOM FIELDS METHODS
    // ============================================

    public function get_custom_fields($id = null, $active_only = true)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'asset_custom_fields')->row();
        }
        if ($active_only) {
            $this->db->where('active', 1);
        }
        $this->db->order_by('field_order', 'ASC');
        return $this->db->get(db_prefix().'asset_custom_fields')->result_array();
    }

    public function add_custom_field($data)
    {
        $data['field_slug'] = url_title($data['field_name'], '-', true);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if (!empty($data['field_options']) && is_array($data['field_options'])) {
            $data['field_options'] = json_encode($data['field_options']);
        }
        if (!empty($data['applies_to_groups']) && is_array($data['applies_to_groups'])) {
            $data['applies_to_groups'] = json_encode($data['applies_to_groups']);
        }

        $this->db->insert(db_prefix().'asset_custom_fields', $data);
        return $this->db->insert_id();
    }

    public function update_custom_field($id, $data)
    {
        if (isset($data['field_name'])) {
            $data['field_slug'] = url_title($data['field_name'], '-', true);
        }
        if (!empty($data['field_options']) && is_array($data['field_options'])) {
            $data['field_options'] = json_encode($data['field_options']);
        }
        if (!empty($data['applies_to_groups']) && is_array($data['applies_to_groups'])) {
            $data['applies_to_groups'] = json_encode($data['applies_to_groups']);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'asset_custom_fields', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_custom_field($id)
    {
        $this->db->where('field_id', $id)->delete(db_prefix().'asset_custom_field_values');
        $this->db->where('id', $id)->delete(db_prefix().'asset_custom_fields');
        return $this->db->affected_rows() > 0;
    }

    public function get_custom_field_values($asset_id)
    {
        $this->db->select('v.*, f.field_name, f.field_slug, f.field_type');
        $this->db->from(db_prefix().'asset_custom_field_values v');
        $this->db->join(db_prefix().'asset_custom_fields f', 'f.id = v.field_id');
        $this->db->where('v.asset_id', $asset_id);
        return $this->db->get()->result_array();
    }

    public function save_custom_field_value($asset_id, $field_id, $value)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->where('field_id', $field_id);
        $exists = $this->db->get(db_prefix().'asset_custom_field_values')->row();

        if ($exists) {
            $this->db->where('id', $exists->id);
            $this->db->update(db_prefix().'asset_custom_field_values', ['field_value' => $value]);
        } else {
            $this->db->insert(db_prefix().'asset_custom_field_values', [
                'asset_id' => $asset_id,
                'field_id' => $field_id,
                'field_value' => $value
            ]);
        }
        return true;
    }

    // ============================================
    // TRANSFER METHODS
    // ============================================

    public function get_transfers($id = null, $asset_id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'asset_transfers')->row();
        }
        if ($asset_id) {
            $this->db->where('asset_id', $asset_id);
        }
        $this->db->order_by('transfer_date', 'DESC');
        return $this->db->get(db_prefix().'asset_transfers')->result_array();
    }

    public function create_transfer($data)
    {
        // Keep only valid database columns
        $insert_data = [
            'asset_id' => $data['asset_id'],
            'from_location' => isset($data['from_location']) ? $data['from_location'] : null,
            'to_location' => $data['to_location'],
            'from_department' => isset($data['from_department']) ? $data['from_department'] : null,
            'to_department' => isset($data['to_department']) ? $data['to_department'] : null,
            'quantity' => isset($data['quantity']) ? $data['quantity'] : 1,
            'transfer_date' => to_sql_date($data['transfer_date'], true),
            'reason' => isset($data['reason']) ? $data['reason'] : '',
            'notes' => isset($data['notes']) ? $data['notes'] : '',
            'transferred_by' => get_staff_user_id(),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert(db_prefix().'asset_transfers', $insert_data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            $this->log_audit($data['asset_id'], 'transfer_initiated', 'Asset transfer initiated', null, $insert_data);
        }

        return $insert_id;
    }

    public function complete_transfer($id)
    {
        $transfer = $this->get_transfers($id);
        if (!$transfer) return false;

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'asset_transfers', [
            'status' => 'completed',
            'received_by' => get_staff_user_id(),
            'received_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() > 0) {
            // Update asset location
            $this->db->where('id', $transfer->asset_id);
            $this->db->update(db_prefix().'assets', [
                'asset_location' => $transfer->to_location,
                'department' => $transfer->to_department ?: null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->log_audit($transfer->asset_id, 'transferred', 'Asset transferred', null, (array)$transfer);
            $this->trigger_webhook('asset.transferred', $transfer->asset_id, (array)$transfer);

            return true;
        }
        return false;
    }

    // ============================================
    // PROJECT INTEGRATION METHODS
    // ============================================

    public function assign_to_project($asset_id, $project_id, $quantity = 1, $notes = '')
    {
        $data = [
            'asset_id' => $asset_id,
            'project_id' => $project_id,
            'assigned_date' => date('Y-m-d'),
            'quantity' => $quantity,
            'notes' => $notes,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => get_staff_user_id()
        ];

        $this->db->insert(db_prefix().'asset_project_rel', $data);
        return $this->db->insert_id();
    }

    public function remove_from_project($id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'asset_project_rel', ['removed_date' => date('Y-m-d')]);
        return $this->db->affected_rows() > 0;
    }

    public function get_project_assets($project_id)
    {
        $this->db->select('r.*, a.assets_name, a.assets_code, a.asset_image');
        $this->db->from(db_prefix().'asset_project_rel r');
        $this->db->join(db_prefix().'assets a', 'a.id = r.asset_id');
        $this->db->where('r.project_id', $project_id);
        $this->db->where('r.removed_date IS NULL');
        return $this->db->get()->result_array();
    }

    public function get_asset_projects($asset_id)
    {
        $this->db->select('r.*, p.name as project_name');
        $this->db->from(db_prefix().'asset_project_rel r');
        $this->db->join(db_prefix().'projects p', 'p.id = r.project_id');
        $this->db->where('r.asset_id', $asset_id);
        return $this->db->get()->result_array();
    }

    // ============================================
    // EXPENSE METHODS
    // ============================================

    public function add_expense($data)
    {
        $data['amount'] = reformat_currency_asset($data['amount']);
        $data['date'] = to_sql_date($data['date']);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = get_staff_user_id();

        $this->db->insert(db_prefix().'asset_expenses', $data);
        return $this->db->insert_id();
    }

    public function get_asset_expenses($asset_id)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->order_by('date', 'DESC');
        return $this->db->get(db_prefix().'asset_expenses')->result_array();
    }

    public function get_total_expenses($asset_id)
    {
        $this->db->select_sum('amount');
        $this->db->where('asset_id', $asset_id);
        $result = $this->db->get(db_prefix().'asset_expenses')->row();
        return $result->amount ?: 0;
    }

    // ============================================
    // DASHBOARD/STATISTICS METHODS
    // ============================================

    public function get_dashboard_stats()
    {
        $stats = [];

        // Total assets
        $stats['total_assets'] = $this->db->count_all(db_prefix().'assets');

        // Total value
        $this->db->select('SUM(amount * unit_price) as total_value');
        $result = $this->db->get(db_prefix().'assets')->row();
        $stats['total_value'] = $result->total_value ?: 0;

        // Assets by status
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        $status_counts = $this->db->get(db_prefix().'assets')->result_array();
        $stats['by_status'] = [];
        foreach ($status_counts as $sc) {
            $stats['by_status'][$sc['status']] = $sc['count'];
        }

        // Pending checkouts
        $this->db->where('status', 'checked_out');
        $stats['checked_out'] = $this->db->count_all_results(db_prefix().'asset_checkouts');

        // Overdue checkouts
        $this->db->where('status', 'overdue');
        $stats['overdue_checkouts'] = $this->db->count_all_results(db_prefix().'asset_checkouts');

        // Pending reservations
        $this->db->where('status', 'pending');
        $stats['pending_reservations'] = $this->db->count_all_results(db_prefix().'asset_reservations');

        // Due maintenance
        $this->db->where('scheduled_date <=', date('Y-m-d', strtotime('+7 days')));
        $this->db->where('status', 'scheduled');
        $stats['due_maintenance'] = $this->db->count_all_results(db_prefix().'asset_maintenance');

        // Expiring warranties (next 30 days)
        $this->db->where('date_buy IS NOT NULL');
        $this->db->where('warranty_period > 0');
        $this->db->where('DATE_ADD(date_buy, INTERVAL warranty_period MONTH) <= DATE_ADD(NOW(), INTERVAL 30 DAY)');
        $this->db->where('DATE_ADD(date_buy, INTERVAL warranty_period MONTH) >= NOW()');
        $stats['expiring_warranties'] = $this->db->count_all_results(db_prefix().'assets');

        // Recently added (last 7 days)
        $this->db->where('created_at >=', date('Y-m-d', strtotime('-7 days')));
        $stats['recently_added'] = $this->db->count_all_results(db_prefix().'assets');

        // Assets by group
        $this->db->select('g.group_name, COUNT(a.id) as count');
        $this->db->from(db_prefix().'assets a');
        $this->db->join(db_prefix().'assets_group g', 'g.group_id = a.asset_group', 'left');
        $this->db->group_by('a.asset_group');
        $stats['by_group'] = $this->db->get()->result_array();

        // Assets by location
        $this->db->select('l.location, COUNT(a.id) as count');
        $this->db->from(db_prefix().'assets a');
        $this->db->join(db_prefix().'asset_location l', 'l.location_id = a.asset_location', 'left');
        $this->db->group_by('a.asset_location');
        $stats['by_location'] = $this->db->get()->result_array();

        return $stats;
    }

    public function get_depreciation_summary()
    {
        $this->db->select('
            SUM(amount * unit_price) as original_value,
            SUM(
                CASE 
                    WHEN depreciation > 0 THEN 
                        LEAST(
                            (amount * unit_price),
                            (amount * unit_price / depreciation) * TIMESTAMPDIFF(MONTH, date_buy, NOW())
                        )
                    ELSE 0 
                END
            ) as total_depreciation
        ');
        $result = $this->db->get(db_prefix().'assets')->row();
        
        return [
            'original_value' => $result->original_value ?: 0,
            'total_depreciation' => $result->total_depreciation ?: 0,
            'current_value' => ($result->original_value ?: 0) - ($result->total_depreciation ?: 0)
        ];
    }

    // ============================================
    // BULK IMPORT/EXPORT METHODS
    // ============================================

    public function import_assets($data, $filename)
    {
        $imported = 0;
        $failed = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                $asset_data = $this->map_import_row($row);
                if ($this->add_asset($asset_data)) {
                    $imported++;
                } else {
                    $failed++;
                    $errors[] = "Row " . ($index + 2) . ": Failed to insert";
                }
            } catch (Exception $e) {
                $failed++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        // Log import
        $this->db->insert(db_prefix().'asset_import_history', [
            'filename' => $filename,
            'total_rows' => count($data),
            'imported_rows' => $imported,
            'failed_rows' => $failed,
            'errors' => json_encode($errors),
            'imported_by' => get_staff_user_id(),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return [
            'imported' => $imported,
            'failed' => $failed,
            'errors' => $errors
        ];
    }

    protected function map_import_row($row)
    {
        return [
            'assets_code' => $row['asset_code'] ?? $row['code'] ?? uniqid('AST'),
            'assets_name' => $row['asset_name'] ?? $row['name'] ?? '',
            'amount' => $row['quantity'] ?? $row['amount'] ?? 1,
            'unit' => $this->get_or_create_unit($row['unit'] ?? ''),
            'asset_group' => $this->get_or_create_group($row['group'] ?? $row['category'] ?? ''),
            'asset_location' => $this->get_or_create_location($row['location'] ?? ''),
            'date_buy' => $row['purchase_date'] ?? $row['date_buy'] ?? date('Y-m-d'),
            'warranty_period' => $row['warranty_months'] ?? $row['warranty_period'] ?? 0,
            'unit_price' => $row['price'] ?? $row['unit_price'] ?? 0,
            'depreciation' => $row['depreciation_months'] ?? $row['depreciation'] ?? 0,
            'supplier_name' => $row['supplier'] ?? $row['supplier_name'] ?? '',
            'description' => $row['description'] ?? '',
            'manufacturer' => $row['manufacturer'] ?? '',
            'model_number' => $row['model'] ?? $row['model_number'] ?? '',
            'series' => $row['serial'] ?? $row['series'] ?? '',
        ];
    }

    protected function get_or_create_unit($name)
    {
        if (empty($name)) return null;
        
        $this->db->where('unit_name', $name);
        $unit = $this->db->get(db_prefix().'asset_unit')->row();
        
        if ($unit) return $unit->unit_id;
        
        return $this->add_asset_unit(['unit_name' => $name]);
    }

    protected function get_or_create_group($name)
    {
        if (empty($name)) return null;
        
        $this->db->where('group_name', $name);
        $group = $this->db->get(db_prefix().'assets_group')->row();
        
        if ($group) return $group->group_id;
        
        return $this->add_asset_group(['group_name' => $name]);
    }

    protected function get_or_create_location($name)
    {
        if (empty($name)) return null;
        
        $this->db->where('location', $name);
        $location = $this->db->get(db_prefix().'asset_location')->row();
        
        if ($location) return $location->location_id;
        
        return $this->add_asset_location(['location' => $name]);
    }

    // ============================================
    // AUDIT LOG METHODS
    // ============================================

    public function log_audit($asset_id, $action, $description, $old_values = null, $new_values = null)
    {
        $this->db->insert(db_prefix().'asset_audit_log', [
            'asset_id' => $asset_id,
            'action' => $action,
            'description' => $description,
            'old_values' => $old_values ? json_encode($old_values) : null,
            'new_values' => $new_values ? json_encode($new_values) : null,
            'performed_by' => get_staff_user_id(),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => substr($this->input->user_agent(), 0, 255),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_audit_log($asset_id = null, $limit = 100)
    {
        if ($asset_id) {
            $this->db->where('asset_id', $asset_id);
        }
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get(db_prefix().'asset_audit_log')->result_array();
    }

    // ============================================
    // WEBHOOK TRIGGER HELPER
    // ============================================

    protected function trigger_webhook($event, $asset_id, $extra_data = [])
    {
        try {
            $this->load->library('assets/assets_webhooks');
            $asset = $this->get($asset_id);
            
            $payload = [
                'asset_id' => $asset_id,
                'asset' => $asset ? (array)$asset : $extra_data,
            ];
            
            if (!empty($extra_data)) {
                $payload['data'] = $extra_data;
            }
            
            $this->assets_webhooks->trigger($event, $payload);
        } catch (Exception $e) {
            // Log error but don't fail the main operation
            log_message('error', 'Webhook trigger failed: ' . $e->getMessage());
        }
    }
}
