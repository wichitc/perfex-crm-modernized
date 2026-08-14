<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.9
 * Fix project_settings.available_features when stored as "0"/"1" (causes unserialize error in project view)
 */

class Migration_Version_109 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $tbl = db_prefix() . 'project_settings';

        if (!$CI->db->table_exists($tbl)) {
            return;
        }

        $CI->db->where('name', 'available_features');
        $rows = $CI->db->get($tbl)->result_array();

        $tab_settings = [];
        if (function_exists('get_project_tabs_admin')) {
            $tabs = get_project_tabs_admin();
            foreach ($tabs as $tab) {
                if (isset($tab['collapse']) && !empty($tab['children'])) {
                    foreach ($tab['children'] as $d) {
                        $tab_settings[$d['slug']] = 1;
                    }
                } elseif (!empty($tab['slug'])) {
                    $tab_settings[$tab['slug']] = 1;
                }
            }
        }
        if (empty($tab_settings)) {
            $tab_settings = ['project_overview' => 1, 'project_tasks' => 1, 'project_milestones' => 1];
        }

        $valid_value = serialize($tab_settings);

        foreach ($rows as $row) {
            $val = $row['value'] ?? '';
            $needs_fix = false;
            if (strlen($val) < 10) {
                $needs_fix = true;
            } else {
                $un = @unserialize($val);
                if ($un === false && $val !== serialize(false)) {
                    $needs_fix = true;
                } elseif (!is_array($un)) {
                    $needs_fix = true;
                }
            }
            if ($needs_fix) {
                $CI->db->where('id', $row['id']);
                $CI->db->update($tbl, ['value' => $valid_value]);
            }
        }
    }
}
