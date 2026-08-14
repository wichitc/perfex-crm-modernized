<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */


function get_hrm_option($name)
{
	$CI = & get_instance();
	$options = [];
    $val  = '';
    $name = trim($name);
    

    if (!isset($options[$name])) {
        // is not auto loaded
        $CI->db->select('option_val');
        $CI->db->where('option_name', $name);
        $row = $CI->db->get(db_prefix() . 'hrm_option')->row();
        if ($row) {
            $val = $row->option_val;
        }
    } else {
        $val = $options[$name];
    }

    return hooks()->apply_filters('get_hrm_option', $val, $name);
}
function row_options_exist($name){
    $CI = & get_instance();
    $i = count($CI->db->query('Select * from '.db_prefix().'hrm_option where option_name = '.$name)->result_array());
    if($i == 0){
        return 0;
    }
    if($i > 0){
        return 1;
    }
}

function handle_hrm_attachments_array($staffid, $index_name = 'attachments')
{
    $uploaded_files = [];
    $path           = HRM_MODULE_UPLOAD_FOLDER.'/'.$staffid .'/';
    $CI             = &get_instance();
    if (isset($_FILES[$index_name]['name'])
        && ($_FILES[$index_name]['name'] != '' || is_array($_FILES[$index_name]['name']) && count($_FILES[$index_name]['name']) > 0)) {
        if (!is_array($_FILES[$index_name]['name'])) {
            $_FILES[$index_name]['name']     = [$_FILES[$index_name]['name']];
            $_FILES[$index_name]['type']     = [$_FILES[$index_name]['type']];
            $_FILES[$index_name]['tmp_name'] = [$_FILES[$index_name]['tmp_name']];
            $_FILES[$index_name]['error']    = [$_FILES[$index_name]['error']];
            $_FILES[$index_name]['size']     = [$_FILES[$index_name]['size']];
        }

        _file_attachments_index_fix($index_name);
        for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
            // Get the temp file path
            $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];

            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES[$index_name]['error'][$i])
                    || !_upload_extension_allowed($_FILES[$index_name]['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES[$index_name]['name'][$i]);
                $newFilePath = $path . $filename;

                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    array_push($uploaded_files, [
                    'file_name' => $filename,
                    'filetype'  => $_FILES[$index_name]['type'][$i],
                    ]);
                    if (is_image($newFilePath)) {
                        create_img_thumb($path, $filename);
                    }
                }
            }
        }
    }

    if (count($uploaded_files) > 0) {
        return $uploaded_files;
    }

    return false;
}
function render_hrm_yes_no_option($option_value, $label, $tooltip = '', $replace_yes_text = '', $replace_no_text = '', $replace_1 = '', $replace_0 = '')
{
    ob_start(); ?>
    <div class="form-group">
        <label for="<?php echo htmlspecialchars($option_value); ?>" class="control-label clearfix">
            <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l($label, '', false); ?>
        </label>
        <div class="radio radio-primary radio-inline">
            <input type="radio" id="y_opt_1_<?php echo htmlspecialchars($label); ?>" name="hrm_setting[<?php echo htmlspecialchars($option_value); ?>]" value="<?php echo htmlspecialchars($replace_1) == '' ? 1 : $replace_1; ?>" <?php if (get_hrm_option($option_value) == ($replace_1 == '' ? '1' : $replace_1)) {
        echo 'checked';
    } ?>>
            <label for="y_opt_1_<?php echo htmlspecialchars($label); ?>">
                <?php echo htmlspecialchars($replace_yes_text) == '' ? _l('settings_yes') : $replace_yes_text; ?>
            </label>
        </div>
        <div class="radio radio-primary radio-inline">
                <input type="radio" id="y_opt_2_<?php echo htmlspecialchars($label); ?>" name="hrm_setting[<?php echo htmlspecialchars($option_value); ?>]" value="<?php echo htmlspecialchars($replace_0) == '' ? 0 : $replace_0; ?>" <?php if (get_hrm_option($option_value) == ($replace_0 == '' ? '0' : $replace_0)) {
        echo 'checked';
    } ?>>
                <label for="y_opt_2_<?php echo htmlspecialchars($label); ?>">
                    <?php echo htmlspecialchars($replace_no_text) == '' ? _l('settings_no') : $replace_no_text; ?>
                </label>
        </div>
    </div>
    <?php
    $settings = ob_get_contents();
    ob_end_clean();
    echo $settings;
}

function get_dpm_in_dayoff($id){
    $CI             = &get_instance();
    if($id != 0){
        $dpm = $CI->db->query('select name from '.db_prefix().'departments where departmentid = '.$id)->row();
        return $dpm->name;
    }else{
        return _l('all');
    }
}

function get_position_in_dayoff($id){
    $CI             = &get_instance();
    if($id != 0){
        $pss = $CI->db->query('select position_name from '.db_prefix().'job_position where position_id = '.$id)->row();
        return $pss->position_name;
    }else{
        return _l('all');
    }
}

function get_status_modules($module_name){
    $CI             = &get_instance();
    $CI->db->where('module_name',$module_name);
    $module = $CI->db->get(db_prefix().'modules')->row();
    
}

function get_time_rp($id,$type){
    $CI             = &get_instance();
    $CI->db->where('request_id',$id);
    $CI->db->where('name',$type);
    $time = $CI->db->get(db_prefix().'request_form')->row();
    if(isset($time->value))
    {
        return $time->value;
    }else{
        return '';
    }
    
}

function sort_array_by_char($arr_data, $char, $order = 'desc'){
    $arr_cal = array();
    for($i=0;$i<count($arr_data);$i++){
        $c = substr_count($arr_data[$i],$char);
        if(!isset($arr_cal[$c])){
        $arr_cal[$c]=array();
        }
        $arr_cal[$c][] = $arr_data[$i];
    }
    if($order == 'desc'){
        asort($arr_cal);
    } else {
        ksort($arr_cal);
    }
    $res = array();
    foreach($arr_cal as $key => $value){
    $res = array_merge($res, $value);
    }
    
    return $res;
}

function get_hrm_department_name_by_staffid($staff_id = ''){
    if($staff_id == ''){
        $staff_id = get_staff_user_id();        
    }
    $CI = & get_instance();
    $arr_dept = $CI->db->query('select d.name from tblstaff s 
        left join tblstaff_departments sd on s.staffid = sd.staffid
        left join tbldepartments d on sd.departmentid = d.departmentid
        where s.staffid = '.$staff_id)->result_array();
    if(count($arr_dept) > 0){
        return $arr_dept[0]['name'] ?? '';
    }
    return '';
}

/**
 * Canonical list of merge-field tokens supported in HRM contract templates.
 *
 * Returned as groups so the template editor can render a structured cheatsheet.
 *
 * @return array<string, array{label:string, fields:array<string,string>}>
 */
function hrm_contract_merge_fields_definition()
{
    return [
        'employee' => [
            'label'  => _l('mf_group_employee'),
            'fields' => [
                '{employee_firstname}'   => _l('mf_employee_firstname'),
                '{employee_lastname}'    => _l('mf_employee_lastname'),
                '{employee_fullname}'    => _l('mf_employee_fullname'),
                '{employee_email}'       => _l('mf_employee_email'),
                '{employee_phone}'       => _l('mf_employee_phone'),
                '{employee_id}'          => _l('mf_employee_id'),
                '{employee_hr_code}'     => _l('mf_employee_hr_code'),
                '{employee_job_title}'   => _l('mf_employee_job_title'),
                '{employee_department}'  => _l('mf_employee_department'),
            ],
        ],
        'contract' => [
            'label'  => _l('mf_group_contract'),
            'fields' => [
                '{contract_code}'        => _l('mf_contract_code'),
                '{contract_type}'        => _l('mf_contract_type'),
                '{contract_status}'      => _l('mf_contract_status'),
                '{contract_start_date}'  => _l('mf_contract_start_date'),
                '{contract_end_date}'    => _l('mf_contract_end_date'),
                '{contract_sign_date}'   => _l('mf_contract_sign_date'),
                '{contract_delegate}'    => _l('mf_contract_delegate'),
            ],
        ],
        'company' => [
            'label'  => _l('mf_group_company'),
            'fields' => [
                '{company_name}'   => _l('mf_company_name'),
                '{today_date}'     => _l('mf_today_date'),
            ],
        ],
    ];
}

/**
 * Resolve an HRM contract merge token against a (contract, staff) row pair.
 *
 * Anything we can't resolve is returned as an empty string so the document never
 * leaks raw `{token}` placeholders.
 */
function hrm_contract_merge_value($token, $contract, $staff)
{
    $contract = (array) $contract;
    $staff    = (array) $staff;
    $CI       = &get_instance();

    $get = function ($arr, $key, $default = '') {
        return isset($arr[$key]) && $arr[$key] !== null ? $arr[$key] : $default;
    };

    switch ($token) {
        case '{employee_firstname}':  return (string) $get($staff, 'firstname');
        case '{employee_lastname}':   return (string) $get($staff, 'lastname');
        case '{employee_fullname}':   return trim($get($staff, 'firstname') . ' ' . $get($staff, 'lastname'));
        case '{employee_email}':      return (string) $get($staff, 'email');
        case '{employee_phone}':      return (string) $get($staff, 'phonenumber');
        case '{employee_id}':         return (string) $get($staff, 'staffid');
        case '{employee_hr_code}':    return (string) $get($staff, 'staff_identifi');
        case '{employee_job_title}':  return (string) $get($staff, 'role');
        case '{employee_department}': return (string) get_hrm_department_name_by_staffid((int) $get($staff, 'staffid', 0));

        case '{contract_code}':       return (string) $get($contract, 'contract_code');
        case '{contract_status}':     return (string) $get($contract, 'contract_status');
        case '{contract_start_date}': return $get($contract, 'start_valid') ? _d($contract['start_valid']) : '';
        case '{contract_end_date}':   return $get($contract, 'end_valid')   ? _d($contract['end_valid'])   : '';
        case '{contract_sign_date}':  return $get($contract, 'sign_day')    ? _d($contract['sign_day'])    : '';

        case '{contract_type}':
            $type_id = (int) $get($contract, 'name_contract', 0);
            if ($type_id) {
                $row = $CI->db->select('name_contracttype')
                              ->where('id_contracttype', $type_id)
                              ->get(db_prefix() . 'staff_contracttype')
                              ->row_array();
                return $row ? (string) $row['name_contracttype'] : '';
            }
            return '';

        case '{contract_delegate}':
            $del_id = (int) $get($contract, 'staff_delegate', 0);
            if ($del_id) {
                $row = $CI->db->select('firstname,lastname')
                              ->where('staffid', $del_id)
                              ->get(db_prefix() . 'staff')
                              ->row_array();
                return $row ? trim($row['firstname'] . ' ' . $row['lastname']) : '';
            }
            return '';

        case '{company_name}': return (string) get_option('companyname');
        case '{today_date}':   return _d(date('Y-m-d'));
    }

    return '';
}

/**
 * Replace every supported {token} in $html with the resolved value for the
 * given staff_contract.id_contract. Falls back to original $html if the
 * contract or staff cannot be loaded.
 */
function hrm_render_contract_template($html, $contract_id)
{
    if (empty($html) || !$contract_id) {
        return (string) $html;
    }

    $CI = &get_instance();

    $contract_id = (int) $contract_id;
    $contract = $CI->db->where('id_contract', $contract_id)
                       ->get(db_prefix() . 'staff_contract')
                       ->row_array();
    if (!$contract) {
        return (string) $html;
    }

    $staff = $CI->db->where('staffid', (int) ($contract['staff'] ?? 0))
                    ->get(db_prefix() . 'staff')
                    ->row_array();
    $staff = $staff ?: [];

    $rendered = (string) $html;
    foreach (hrm_contract_merge_fields_definition() as $group) {
        foreach (array_keys($group['fields']) as $token) {
            $rendered = str_replace($token, (string) hrm_contract_merge_value($token, $contract, $staff), $rendered);
        }
    }

    return $rendered;
}

/**
 * Decide whether the current logged-in user is allowed to electronically sign
 * the given contract: either a global hrm:edit admin, or the staff member who
 * owns the contract.
 */
function hrm_can_sign_contract($contract)
{
    $contract = (array) $contract;
    $owner = (int) ($contract['staff'] ?? 0);
    if ($owner && $owner === (int) get_staff_user_id()) {
        return true;
    }
    return has_permission('hrm', '', 'edit');
}
