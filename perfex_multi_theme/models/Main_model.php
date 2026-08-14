<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Main_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new  type
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function update_color($data)
    {
        if (empty($data['theme_css'])) {
            $data['theme_css'] = null;
        }
        unset($data['csrf_token_name']);
        $this->db->where('staff_id', $data['staff_id']);
        $staff = $this->db->get(db_prefix() . '_multi_theme')->row();
        if ($staff) {
            $this->db->where('staff_id', $data['staff_id']);
            $this->db->update(db_prefix() . '_multi_theme', $data);
            if ($this->db->affected_rows() > 0) {
                return true;
            }
        } else {
            $this->db->insert(db_prefix() . '_multi_theme', $data);
            $insert_id = $this->db->insert_id();
            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }
    }

    // version 102

    public function get_themes($id = '', $where = [])
    {
        $this->db->select('*');
        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'multi_theme_setup.id', $id);
            $client = $this->db->get(db_prefix() . 'multi_theme_setup')->row();
            return $client;
        }
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'multi_theme_setup')->result_array();
    }
    public function update_theme($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'multi_theme_setup', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Multi theme Updated [ThemeID: ' . $id . ', Name: ' . $data['theme_name'] . ']');

            return true;
        }
        return false;
    }
    function get_active_theme_by_staff($staff_id = '')
    {
        if (!is_numeric($staff_id)) {
            $staff_id = get_staff_user_id();
        }
        $result = $this->db->query('SELECT mts.* FROM ' . db_prefix() . '_multi_theme mt LEFT JOIN ' . db_prefix() . 'multi_theme_setup mts ON mts.theme_name_slug = mt.theme_css WHERE mt.staff_id = ' . $staff_id . ' UNION ALL SELECT mts.* FROM ' . db_prefix() . 'multi_theme_setup mts WHERE mts.is_default = 1 AND NOT EXISTS (SELECT 1 FROM ' . db_prefix() . '_multi_theme WHERE staff_id = ' . $staff_id . ');')->row();
        return $result;
    }
}
