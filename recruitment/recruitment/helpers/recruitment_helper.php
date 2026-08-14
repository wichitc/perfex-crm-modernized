<?php
defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('after_email_templates', 'add_recruitment_email_templates');
hooks()->add_action('recruitment_init',RECRUITMENT_MODULE_NAME.'_appint');
hooks()->add_action('pre_activate_module', RECRUITMENT_MODULE_NAME.'_preactivate');
hooks()->add_action('pre_deactivate_module', RECRUITMENT_MODULE_NAME.'_predeactivate');
hooks()->add_action('pre_uninstall_module', RECRUITMENT_MODULE_NAME.'_uninstall');

if (!function_exists('add_recruitment_email_templates')) {
    /**
     * Init appointly email templates and assign languages
     * @return void
     */
    function add_recruitment_email_templates() {
        $CI = &get_instance();

        $data['change_candidate_status_templates'] = $CI->emails_model->get(['type' => 'change_candidate_status', 'language' => 'english']);
        $data['change_candidate_job_applied_status_templates'] = $CI->emails_model->get(['type' => 'change_candidate_job_applied_status', 'language' => 'english']);
        $data['change_candidate_interview_schedule_status_templates'] = $CI->emails_model->get(['type' => 'change_candidate_interview_schedule_status', 'language' => 'english']);
        $data['new_candidate_have_applied_templates'] = $CI->emails_model->get(['type' => 'new_candidate_have_applied', 'language' => 'english']);
        $data['send_interview_schedule_templates'] = $CI->emails_model->get(['type' => 'send_interview_schedule', 'language' => 'english']);

        $CI->load->view('recruitment/email_templates/change_candidate_status_email_template', $data);
    }
}


/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */
function handle_rec_proposal_file($id)
{

    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = RECRUITMENT_MODULE_UPLOAD_FOLDER .'/proposal/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = & get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                    ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_proposal', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * reformat currency rec
 * @param  string $value
 * @return string
 */
function reformat_currency_rec($value)
{
    return new_str_replace(',','', $value);
}

/**
 * decrypt
 * @param  string $data
 * @return string
 */
function recruitment_decrypt($data) {
	$key = 'greentech_solutions';
	$c = base64_decode($data);
	$ivlen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len = 32);
	$ciphertext_raw = substr($c, $ivlen + $sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
	$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
	if (hash_equals($hmac, $calcmac))
	{
		return $original_plaintext;
	}
}

/**
 * get rec dpm name
 * @param  int $id
 * @return string
 */
function get_rec_dpm_name($id){
    $CI           = & get_instance();
    if($id != 0){
        $CI->db->where('departmentid',$id);
        $dpm = $CI->db->get(db_prefix().'departments')->row();
        if($dpm->name){
            return $dpm->name;
        }else{
            return '';
        }
        
    }else{
        return '';
    }
}

/**
 * get rec position name
 * @param  int $id
 * @return string
 */
function get_rec_position_name($id){
    $CI           = & get_instance();
    if($id != 0){
        $CI->db->where('position_id',$id);
        $dpm = $CI->db->get(db_prefix().'rec_job_position')->row();
        if($dpm->position_name){
            return $dpm->position_name;
        }else{
            return '';
        }
        
    }else{
        return '';
    }
}

/**
 * handle rec campaign file
 * @param  int $id 
 * @return bool
 */
function handle_rec_campaign_file($id){
     if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = RECRUITMENT_MODULE_UPLOAD_FOLDER .'/campaign/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = & get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                    ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_campaign', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * handle rec candidate file
 * @param  int $id
 * @return bool
 */
function handle_rec_candidate_file($id){
     if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = RECRUITMENT_MODULE_UPLOAD_FOLDER .'/candidate/files/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = & get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                    ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_cadidate_file', $attachment);

                return true;
            }
        }
    }
    return false;
}

/**
 * handle rec candidate avar file
 * @param  int $id
 * @return bool   
 */
function handle_rec_candidate_avar_file($id){
    if (isset($_FILES['cd_avar']['name']) && $_FILES['cd_avar']['name'] != '') {
        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = RECRUITMENT_MODULE_UPLOAD_FOLDER .'/candidate/avartar/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['cd_avar']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['cd_avar']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = & get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['cd_avar']['type'],
                    ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_cadidate_avar', $attachment);
                return true;
            }
        }
    }

    return false;
}

/**
 * get rec campaign hp
 * @param  string $id
 * @return string
 */
function get_rec_campaign_hp($id = ''){
    $CI           = & get_instance();
    if($id != ''){
        $CI->db->where('cp_id', $id);
        return $CI->db->get(db_prefix().'rec_campaign')->row();
    }elseif ($id == '') {
        return $CI->db->get(db_prefix().'rec_campaign')->result_array();
    }
}

/**
 * get status candidate
 * @param  int $status
 * @return string
 */
function get_status_candidate($status){
    $result = '';
    if($status == 1){
        $result = '<span class="label label inline-block project-status-'.$status.' application-style"> '._l('application').' </span>';
    }elseif($status == 2){
        $result = '<span class="label label inline-block project-status-'.$status.' potential-style"> '._l('potential').' </span>';
    }elseif($status == 3){
        $result = '<span class="label label inline-block project-status-'.$status.' interview-style"> '._l('interview').' </span>';
    }elseif($status == 4){
        $result = '<span class="label label inline-block project-status-'.$status.' won_interview-style"> '._l('won_interview').' </span>';
    }elseif($status == 5){
        $result = '<span class="label label inline-block project-status-'.$status.' send_offer-style"> '._l('send_offer').' </span>';
    }elseif($status == 6){
        $result = '<span class="label label inline-block project-status-'.$status.' elect-style"> '._l('elect').' </span>';
    }elseif($status == 7){
        $result = '<span class="label label inline-block project-status-'.$status.' non_elect-style"> '._l('non_elect').' </span>';
    }elseif($status == 8){
        $result = '<span class="label label inline-block project-status-'.$status.' unanswer-style"> '._l('unanswer').' </span>';
    }elseif($status == 9){
        $result = '<span class="label label inline-block project-status-'.$status.' transferred-style"> '._l('transferred').' </span>';
    }elseif($status == 10){
        $result = '<span class="label label inline-block project-status-'.$status.' freedom-style"> '._l('freedom').' </span>';
    }

    return $result;
}

/**
 * candidate profile image
 * @param  int $id     
 * @param  array  $classes
 * @param  string $type   
 * @param  array  $img_attrs
 * @return string
 */
function candidate_profile_image($id, $classes = ['staff-profile-image'], $type = 'small', $img_attrs = [])
{
    $CI           = & get_instance();
    $url = base_url('assets/images/user-placeholder.jpg');
    $_attributes = '';
    foreach ($img_attrs as $key => $val) {
        $_attributes .= $key . '=' . '"' . html_escape($val) . '" ';
    }

    $blankImageFormatted = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';

    $CI->db->where('rel_id',$id);
    $CI->db->where('rel_type','rec_cadidate_avar');
    $result = $CI->db->get(db_prefix().'files')->row();  

    if (!$result) {
        return $blankImageFormatted;
    }

    if ($result && $result->file_name !== null) {
        $profileImagePath = RECRUITMENT_PATH.'candidate/avartar/'.$id.'/'.$result->file_name;
        if (file_exists($profileImagePath)) {
            $profile_image = '<img ' . $_attributes . ' src="' . site_url($profileImagePath) . '" class="' . implode(' ', $classes) . '" />';
        } else {
            return $blankImageFormatted;
        }
    } else {
        $profile_image = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';
    }

    return $profile_image;
}

/**
 * get candidate name
 * @param  int $id
 * @return string
 */
function get_candidate_name($id){
    $CI           = & get_instance();
    $CI->db->where('id',$id);
    $candidate = $CI->db->get(db_prefix().'rec_candidate')->row();
    if($candidate && $candidate->candidate_name != ''){
        return $candidate->candidate_name.' '.$candidate->last_name;
    }else{
        return '';
    }
}

/**
 * get candidate interview
 * @param  int $id
 * @return 
 */
function get_candidate_interview($id){
    $CI           = & get_instance();
    $CI->db->where('interview',$id);
    $data_rs = array();
    $cdinterview = $CI->db->get(db_prefix().'cd_interview')->result_array();
    
    foreach($cdinterview as $cd){
        $data_rs[] = $cd['candidate'];
    }

    return $data_rs;
}

/**
 * count criteria
 * @param  int $id
 * @return int
 */
function count_criteria($id){
    $CI           = & get_instance();
    $CI->db->where('evaluation_form',$id);
    $list = $CI->db->get(db_prefix().'rec_list_criteria')->result_array();

    return count($list);
}

/**
 * get criteria name
 * @param  int $id
 * @return string
 */
function get_criteria_name($id){
    $CI           = & get_instance();
    $CI->db->where('criteria_id',$id);
    $CI->db->select('criteria_title');
    $list = $CI->db->get(db_prefix().'rec_criteria')->row();
    if($list->criteria_title){
        return $list->criteria_title;
    }else{
        return '';
    }
    
}

/**
 * handle rec set transfer record
 * @param  int $id
 * @return bool
 */
function handle_rec_set_transfer_record($id){

    if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {
        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = RECRUITMENT_MODULE_UPLOAD_FOLDER .'/set_transfer/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['attachment']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['attachment']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = & get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['attachment']['type'],
                    ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_set_transfer', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * Gets the staff email by identifier.
 *
 * @param      int   $id     The identifier
 *
 * @return     String  The staff email by identifier.
 */
function get_staff_email_by_id_rec($id)
{
    $CI           = & get_instance();
    $CI->db->where('staffid', $id);
    $staff = $CI->db->select('email')->from(db_prefix() . 'staff')->get()->row();

    return ($staff ? $staff->email : '');
}


/**
 * [handle rec candidate file form description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function handle_rec_candidate_file_form($id){
     if (isset($_FILES['file-input']['name']) && $_FILES['file-input']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = RECRUITMENT_MODULE_UPLOAD_FOLDER .'/candidate/files/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file-input']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file-input']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = & get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file-input']['type'],
                    ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_cadidate_file', $attachment);

                return true;
            }
        }
    }
    return false;
}

/**
 * Format html task assignees
 * This function is used to save up on query
 * @param  string $ids   string coma separated assignee staff id
 * @param  string $names compa separated in the same order like assignee ids
 * @return string
 */
function format_members_by_ids_and_names_recruitment($ids, $names, $hidden_export_table = true, $image_class = 'staff-profile-image-small')
{
    $outputAssignees = '';
    $exportAssignees = '';

    $assignees   = new_explode(',', $names);
    $assigneeIds = new_explode(',', $ids);
    foreach ($assignees as $key => $assigned) {
        $assignee_id = $assigneeIds[$key];
        $assignee_id = trim($assignee_id);
        if ($assigned != '') {
            $outputAssignees .= '<a href="' . admin_url('profile/' . $assignee_id) . '">' .
                staff_profile_image($assignee_id, [
                  $image_class . ' mright5',
                ], 'small', [
                  'data-toggle' => 'tooltip',
                  'data-title'  => $assigned,
                ]) . '</a>';
            $exportAssignees .= $assigned . ', ';
        }
    }

    if ($exportAssignees != '') {
        $outputAssignees .= '<span class="hide">' . mb_substr($exportAssignees, 0, -2) . '</span>';
    }

    return $outputAssignees;
}

/**
 * get_kan ban status candidate color
 * @param  integer  $status 
 * @param  boolean $name   
 * @return string         
 */
function get_kan_ban_status_candidate_color($status, $name = false){
    $result = '';
    if($name == false){
        if($status == 1){
            $result = 'application-style';
        }elseif($status == 2){
            $result = 'potential-style';
        }elseif($status == 3){
            $result = 'interview-style';
        }elseif($status == 4){
            $result = 'won_interview-style';
        }elseif($status == 5){
            $result = 'send_offer-style';
        }elseif($status == 6){
            $result = 'elect-style';
        }elseif($status == 7){
            $result = 'non_elect-style';
        }elseif($status == 8){
            $result = 'unanswer-style';
        }elseif($status == 9){
            $result = 'transferred-style';
        }elseif($status == 10){
            $result = 'freedom-style';
        }
    }else{
        if($status == 1){
            $result = _l('application');
        }elseif($status == 2){
            $result = _l('potential');
        }elseif($status == 3){
            $result = _l('interview');
        }elseif($status == 4){
            $result = _l('won_interview');
        }elseif($status == 5){
            $result = _l('send_offer');
        }elseif($status == 6){
            $result = _l('elect');
        }elseif($status == 7){
            $result = _l('non_elect');
        }elseif($status == 8){
            $result = _l('unanswer');
        }elseif($status == 9){
            $result = _l('transferred');
        }elseif($status == 10){
            $result = _l('freedom');
        }
    }

    return $result;
}

/**
 * Used for customer forms eq. leads form, builded from the form builder plugin
 * @param  object $field field from database
 * @return mixed
 */
function render_form_builder_field_recruitment($field)
{

    $type         = $field->type;
    $classNameCol = 'col-md-12';
    if (isset($field->className)) {
        if (strpos($field->className, 'form-col') !== false) {
            $classNames = new_explode(' ', $field->className);
            if (is_array($classNames)) {
                $classNameColArray = array_filter($classNames, function ($class) {
                    return startsWith($class, 'form-col');
                });

                $classNameCol = implode(' ', $classNameColArray);
                $classNameCol = trim($classNameCol);

                $classNameCol = new_str_replace('form-col-xs', 'col-xs', $classNameCol);
                $classNameCol = new_str_replace('form-col-sm', 'col-sm', $classNameCol);
                $classNameCol = new_str_replace('form-col-md', 'col-md', $classNameCol);
                $classNameCol = new_str_replace('form-col-lg', 'col-lg', $classNameCol);

                // Default col-md-X
                $classNameCol = new_str_replace('form-col', 'col-md', $classNameCol);
            }
        }
    }

    echo '<div class="' . $classNameCol . '">';
    if ($type == 'header' || $type == 'paragraph') {
        echo '<' . $field->subtype . ' class="' . (isset($field->className) ? $field->className : '') . '">' . check_for_links(nl2br($field->label)) . '</' . $field->subtype . '>';
    } else {
        echo '<div class="form-group" data-type="' . $type . '" data-name="' . $field->name . '" data-required="' . (isset($field->required) ? true : 'false') . '">';
        echo '<label class="control-label" for="' . $field->name . '">' . (isset($field->required) ? ' <span class="text-danger">* </span> ': '') . $field->label . '' . (isset($field->description) ? ' <i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . $field->description . '" data-placement="' . (is_rtl(true) ? 'left' : 'right') . '"></i>' : '') . '</label>';
        if (isset($field->subtype) && $field->subtype == 'color') {
            echo '<div class="input-group colorpicker-input">
     <input' . (isset($field->required) ? ' required="true"': '') . ' placeholder="' . (isset($field->placeholder) ? $field->placeholder : '') . '" type="text"' . (isset($field->value) ? ' value="' . $field->value . '"' : '') . ' name="' . $field->name . '" id="' . $field->name . '" class="' . (isset($field->className) ? $field->className : '') . '" />
         <span class="input-group-addon"><i></i></span>
     </div>';
        } elseif (($type == 'file' || $type == 'text' || $type == 'number') && ($field->name != 'skill')) {
            $ftype = isset($field->subtype) ? $field->subtype : $type;
            echo '<input' . (isset($field->required) ? ' required="true"': '') . (isset($field->placeholder) ? ' placeholder="' . $field->placeholder . '"' : '') . ' type="' . $ftype . '" name="' . $field->name . '" id="' . $field->name . '" class="' . (isset($field->className) ? $field->className : '') . '" value="' . (isset($field->value) ? $field->value : '') . '"' . ($field->type == 'file' ? ' accept="' . get_form_accepted_mimes() . '" filesize="' . file_upload_max_size() . '"' : '') . '>';
        } elseif ($type == 'textarea') {
            echo '<textarea' . (isset($field->required) ? ' required="true"': '') . ' id="' . $field->name . '" name="' . $field->name . '" rows="' . (isset($field->rows) ? $field->rows : '4') . '" class="' . (isset($field->className) ? $field->className : '') . '" placeholder="' . (isset($field->placeholder) ? $field->placeholder : '') . '">' . (isset($field->value) ? $field->value : '') . '</textarea>';
        } elseif ($type == 'date') {
            echo '<input' . (isset($field->required) ? ' required="true"': '') . ' placeholder="' . (isset($field->placeholder) ? $field->placeholder : '') . '" type="text" class="' . (isset($field->className) ? $field->className : '') . ' datepicker" name="' . $field->name . '" id="' . $field->name . '" value="' . (isset($field->value) ? _d($field->value) : '') . '">';
        } elseif ($type == 'datetime-local') {
            echo '<input' . (isset($field->required) ? ' required="true"': '') . ' placeholder="' . (isset($field->placeholder) ? $field->placeholder : '') . '" type="text" class="' . (isset($field->className) ? $field->className : '') . ' datetimepicker" name="' . $field->name . '" id="' . $field->name . '" value="' . (isset($field->value) ? _dt($field->value) : '') . '">';
        } elseif ($type == 'select') {
            echo '<select' . (isset($field->required) ? ' required="true"': '') . '' . (isset($field->multiple) ? ' multiple="true"' : '') . ' class="' . (isset($field->className) ? $field->className : '') . '" name="' . $field->name . (isset($field->multiple) ? '[]' : '') . '" id="' . $field->name . '"' . (isset($field->values) && count($field->values) > 10 ? 'data-live-search="true"': '') . 'data-none-selected-text="' . (isset($field->placeholder) ? $field->placeholder : '') . '">';
            $values = [];
            if (isset($field->values) && count($field->values) > 0) {
                foreach ($field->values as $option) {
                    echo '<option value="' . $option->value . '" ' . (isset($option->selected) ? ' selected' : '') . '>' . $option->label . '</option>';
                }
            }
            echo '</select>';
        } elseif ($type == 'checkbox-group') {
            $values = [];
            if (isset($field->values) && count($field->values) > 0) {
                $i = 0;
                echo '<div class="chk">';
                foreach ($field->values as $checkbox) {
                    echo '<div class="checkbox' . ((isset($field->inline) && $field->inline == 'true') || (isset($field->className) && strpos($field->className, 'form-inline-checkbox') !== false) ? ' checkbox-inline' : '') . '">';
                    echo '<input' . (isset($field->required) ? ' required="true"': '') . ' class="' . (isset($field->className) ? $field->className : '') . '" type="checkbox" id="chk_' . $field->name . '_' . $i . '" value="' . $checkbox->value . '" name="' . $field->name . '[]"' . (isset($checkbox->selected) ? ' checked' : '') . '>';
                    echo '<label for="chk_' . $field->name . '_' . $i . '">';
                    echo new_html_entity_decode($checkbox->label);
                    echo '</label>';
                    echo '</div>';
                    $i++;
                }
                echo '</div>';
            }
        }
        echo '</div>';
    }
    echo '</div>';
}


/**
 * row options exist
 * @param  string $name 
 *        
 */
function recruitment_row_options_exist($name){
    $CI = & get_instance();
    $i = count($CI->db->query('Select * from '.db_prefix().'options where name = '.$name)->result_array());
    if($i == 0){
        return 0;
    }
    if($i > 0){
        return 1;
    }
}

/**
 * Gets the recruitment option.
 *
 * @param      <type>        $name   The name
 *
 * @return     array|string  The recruitment option.
 */
function get_recruitment_option($name)
{
    $CI = & get_instance();
    $options = [];
    $val  = '';
    $name = trim($name);
    

    if (!isset($options[$name])) {
        // is not auto loaded
        $CI->db->select('value');
        $CI->db->where('name', $name);
        $row = $CI->db->get(db_prefix() . 'options')->row();
        if ($row) {
            $val = $row->value;
        }
    } else {
        $val = $options[$name];
    }

    return $val;
}

/**
 * handle company attchment
 * @param  integer $id
 * @return array or row
 */
function handle_company_attachments($id)
{

    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = RECRUITMENT_COMPANY_UPLOAD . $id . '/';
    $CI   = & get_instance();

    if (isset($_FILES['file']['name'])) {

        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {

            _maybe_create_upload_path($path);
            $filename    = $_FILES['file']['name'];
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                    ];

                $CI->misc_model->add_attachment_to_database($id, 'rec_company', $attachment);
            }
        }
    }

}

/**
 * get industry name
 * @param  integer $id 
 * @return string     
 */
function get_rec_industry_name($id){

    
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $job_industry = $CI->db->get(db_prefix().'job_industry')->row();

    if($job_industry){
        return $job_industry->industry_name;
    }else{
        return '';
    }

}

/**
 * get company name
 * @param  integer $id 
 * @return string    
 */
function get_rec_company_name($id)
{
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $rec_company = $CI->db->get(db_prefix().'rec_company')->row();

    if($rec_company){
        return $rec_company->company_name;
    }else{
        return '';
    }

}

/*separation portal v1.1.2*/

/**
 * app rec portal head
 * @param  [type] $language 
 * @return [type]           
 */
function app_rec_portal_head($language = null)
{
    // $language param is deprecated
    if (is_null($language)) {
        $language = $GLOBALS['language'];
    }

    if (file_exists(FCPATH . 'assets/css/custom.css')) {
        echo '<link href="' . base_url('assets/css/custom.css') . '" rel="stylesheet" type="text/css" id="custom-css">' . PHP_EOL;
    }

    hooks()->do_action('app_rec_portal_head');
}

/**
 * get template part rec portal
* @param      string   $name    The name
 * @param      array    $data    The data
 * @param      boolean  $return  The return
 *
 * @return     string   The template part.
 */
function get_template_part_rec_portal($name, $data = [], $return = false)
{
    if ($name === '') {
        return '';
    }

    $CI   = & get_instance();
    $path = 'recruitment_portal/template_parts/';

    if ($return == true) {
        return $CI->load->view($path . $name, $data, true);
    }

    $CI->load->view($path . $name, $data);
}

/**
 * init rec_portal area assets.
 */
function init_rec_portal_area_assets()
{
    // Used by themes to add assets
    hooks()->do_action('app_rec_portal_assets');

    hooks()->do_action('app_client_assets_added');
}

/**
 * { register theme rec_portal assets hook }
 *
 * @param      <type>   $function  The function
 *
 * @return     boolean  
 */
function register_theme_rec_portal_assets_hook($function)
{
    if (hooks()->has_action('app_rec_portal_assets', $function)) {
        return false;
    }

    return hooks()->add_action('app_rec_portal_assets', $function, 1);
}

/**
 * { app theme head hook }
 */
function app_theme_rec_portal_head_hook()
{
    $CI = &get_instance();
    ob_start();
    echo get_custom_fields_hyperlink_js_function();

    if (get_option('use_recaptcha_customers_area') == 1
        && get_option('recaptcha_secret_key') != ''
        && get_option('recaptcha_site_key') != '') {
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
    }

    $isRTL = (is_rtl_rec(true) ? 'true' : 'false');

    $locale = get_locale_key($GLOBALS['language']);

    $maxUploadSize = file_upload_max_size();

    $date_format = get_option('dateformat');
    $date_format = new_explode('|', $date_format);
    $date_format = $date_format[0]; ?>
    <script>
        <?php if (is_staff_logged_in()) {
        ?>
        var admin_url = '<?php echo admin_url(); ?>';
        <?php
    } ?>

        var site_url = '<?php echo site_url(''); ?>',
        app = {},
        cfh_popover_templates  = {};

        app.isRTL = '<?php echo new_html_entity_decode($isRTL); ?>';
        app.is_mobile = '<?php echo is_mobile(); ?>';
        app.months_json = '<?php echo json_encode([_l('January'), _l('February'), _l('March'), _l('April'), _l('May'), _l('June'), _l('July'), _l('August'), _l('September'), _l('October'), _l('November'), _l('December')]); ?>';

        app.browser = "<?php echo strtolower($CI->agent->browser()); ?>";
        app.max_php_ini_upload_size_bytes = "<?php echo new_html_entity_decode($maxUploadSize); ?>";
        app.locale = "<?php echo new_html_entity_decode($locale); ?>";

        app.options = {
            calendar_events_limit: "<?php echo get_option('calendar_events_limit'); ?>",
            calendar_first_day: "<?php echo get_option('calendar_first_day'); ?>",
            tables_pagination_limit: "<?php echo get_option('tables_pagination_limit'); ?>",
            enable_google_picker: "<?php echo get_option('enable_google_picker'); ?>",
            google_client_id: "<?php echo get_option('google_client_id'); ?>",
            google_api: "<?php echo get_option('google_api_key'); ?>",
            default_view_calendar: "<?php echo get_option('default_view_calendar'); ?>",
            timezone: "<?php echo get_option('default_timezone'); ?>",
            allowed_files: "<?php echo get_option('allowed_files'); ?>",
            date_format: "<?php echo new_html_entity_decode($date_format); ?>",
            time_format: "<?php echo get_option('time_format'); ?>",
        };

        app.lang = {
            file_exceeds_maxfile_size_in_form: "<?php echo _l('file_exceeds_maxfile_size_in_form'); ?>" + ' (<?php echo bytesToSize('', $maxUploadSize); ?>)',
            file_exceeds_max_filesize: "<?php echo _l('file_exceeds_max_filesize'); ?>" + ' (<?php echo bytesToSize('', $maxUploadSize); ?>)',
            validation_extension_not_allowed: "<?php echo _l('validation_extension_not_allowed'); ?>",
            sign_document_validation: "<?php echo _l('sign_document_validation'); ?>",
            dt_length_menu_all: "<?php echo _l('dt_length_menu_all'); ?>",
            drop_files_here_to_upload: "<?php echo _l('drop_files_here_to_upload'); ?>",
            browser_not_support_drag_and_drop: "<?php echo _l('browser_not_support_drag_and_drop'); ?>",
            confirm_action_prompt: "<?php echo _l('confirm_action_prompt'); ?>",
            datatables: <?php echo json_encode(get_datatables_language_array()); ?>,
            discussions_lang: <?php echo json_encode(get_project_discussions_language_array()); ?>,
        };
        window.addEventListener('load',function(){
            custom_fields_hyperlink();
        });
    </script>
    <?php

    _do_clients_area_deprecated_js_vars($date_format, $locale, $maxUploadSize, $isRTL);

    $contents = ob_get_contents();
    ob_end_clean();
    echo new_html_entity_decode($contents);
}

/**
 * get company name
 * @param  integer $id 
 * @return string    
 */
function get_rec_channel_form_key($id)
{
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $rec_campaign_form_web = $CI->db->get(db_prefix().'rec_campaign_form_web')->row();

    if($rec_campaign_form_web){
        return $rec_campaign_form_web->form_key;
    }else{
        return '';
    }

}

/**
 * is_rtl_rec
 * @param  boolean $client_area 
 * @return boolean              
 */
function is_rtl_rec($client_area = false)
{
    $CI = & get_instance();
    
    if ($client_area == true) {
        // Client not logged in and checked from clients area
        if (get_option('rtl_support_client') == 1) {
            return true;
        }
    } elseif (is_staff_logged_in()) {
        if (isset($GLOBALS['current_user'])) {
            $direction = $GLOBALS['current_user']->direction;
        } else {
            $CI->db->select('direction')->from(db_prefix() . 'staff')->where('staffid', get_staff_user_id());
            $direction = $CI->db->get()->row()->direction;
        }

        if ($direction == 'rtl') {
            return true;
        } elseif ($direction == 'ltr') {
            return false;
        } elseif (empty($direction)) {
            if (get_option('rtl_support_admin') == 1) {
                return true;
            }
        }

        return false;
    } elseif ($client_area == false) {
        if (get_option('rtl_support_admin') == 1) {
            return true;
        }
    }

    return false;
}

/**
 * re pdf logo url
 * @return [type] 
 */
function re_pdf_logo_url()
{
    $custom_pdf_logo_image_url = get_option('custom_pdf_logo_image_url');
    $width                     = get_option('pdf_logo_width');
    $logoUrl                   = '';

    if ($width == '') {
        $width = 120;
    }
    if ($custom_pdf_logo_image_url != '') {
        $logoUrl = $custom_pdf_logo_image_url;
    } else {
        if (get_option('company_logo_dark') != '' && file_exists(get_upload_path_by_type('company') . get_option('company_logo_dark'))) {
            $logoUrl = get_upload_path_by_type('company') . get_option('company_logo_dark');
        } elseif (get_option('company_logo') != '' && file_exists(get_upload_path_by_type('company') . get_option('company_logo'))) {
            $logoUrl = get_upload_path_by_type('company') . get_option('company_logo');
        }
    }

    $logoImage = '';
    if ($logoUrl != '') {
        $logoImage = '<img class="logo_width" src="' . $logoUrl . '">';
    }

    return hooks()->apply_filters('pdf_logo_url', $logoImage);
}

/**
 * rec get status modules
 * @param  [type] $module_name 
 * @return [type]              
 */
function rec_get_status_modules($module_name){
    $CI             = &get_instance();

    $sql = 'select * from '.db_prefix().'modules where module_name = "'.$module_name.'" AND active =1 ';
    $module = $CI->db->query($sql)->row();
    if($module){
        return true;
    }else{
        return false;
    }
}

function portal_handle_rec_candidate_file_form($id){
     if (isset($_FILES['file-input']['name']) && $_FILES['file-input']['name'] != '') {

        $path = RECRUITMENT_MODULE_UPLOAD_FOLDER .'/candidate/files/'. $id . '/';
       

        if(is_array($_FILES['file-input']['name'])){
            for ($i = 0; $i < count($_FILES['file-input']['name']) ; $i++) {

                // Get the temp file path
                $tmpFilePath = $_FILES['file-input']['tmp_name'][$i];

                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    _maybe_create_upload_path($path);
                    $filename    = unique_filename($path, $_FILES['file-input']['name'][$i]);
                    $newFilePath = $path . $filename;
                 // Upload the file into the company uploads dir
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $CI           = & get_instance();
                        $attachment   = [];
                        $attachment[] = [
                            'file_name' => $filename,
                            'filetype'  => $_FILES['file-input']['type'][$i],
                        ];
                        $CI->misc_model->add_attachment_to_database($id, 'rec_cadidate_file', $attachment);

                    }
                }

            }

            return true;

        }else{
             // Get the temp file path
            $tmpFilePath = $_FILES['file-input']['tmp_name'];

            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES['file-input']['name']);
                $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $CI           = & get_instance();
                    $attachment   = [];
                    $attachment[] = [
                        'file_name' => $filename,
                        'filetype'  => $_FILES['file-input']['type'],
                    ];
                    $CI->misc_model->add_attachment_to_database($id, 'rec_cadidate_file', $attachment);

                    return true;
                }
            }

        }

    }
    return false;
}

/**
 * rec year experience
 * @return [type] 
 */
function rec_year_experience()
{
    $field_array = [];
    $field_array[] = [
        'label' => _l('no_experience_yet'),
        'value' => 'no_experience_yet',
    ];
    $field_array[] = [
        'label' => _l('less_than_1_year'),
        'value' => 'less_than_1_year',
    ];
    $field_array[] = [
        'label' => _l('1_year'),
        'value' => '1_year',
    ];
    $field_array[] = [
        'label' => _l('2_years'),
        'value' => '2_years',
    ];
    $field_array[] = [
        'label' => _l('3_years'),
        'value' => '3_years',
    ];
    $field_array[] = [
        'label' => _l('4_years'),
        'value' => '4_years',
    ];
    $field_array[] = [
        'label' => _l('5_years'),
        'value' => '5_years',
    ];
    $field_array[] = [
        'label' => _l('over_5_years'),
        'value' => 'over_5_years',
    ];

    return $field_array;
}

/**
 * get_rec_skill_name
 * @param  string $id 
 * @return [type]     
 */
function get_rec_skill_name($id = ''){
    $skill_name = '';

    $CI           = & get_instance();
    $CI->db->where('id', $id);
    $rec_skill = $CI->db->get(db_prefix().'rec_skill')->row();
    if($rec_skill){
        $skill_name = $rec_skill->skill_name;
    }
    return $skill_name;
}

/**
 * is candidate logged in
 * @return boolean 
 */
function is_candidate_logged_in()
{
    return get_instance()->session->has_userdata('candidate_logged_in');
}

/**
 * get candidate id
 * @return [type] 
 */
function get_candidate_id()
{
    if (!is_candidate_logged_in()) {
        return false;
    }

    return get_instance()->session->userdata('candidate_id');
}

/**
 * candidate profile image url
 * @param  [type] $candidate_id 
 * @param  string $type         
 * @return [type]               
 */
function candidate_profile_image_url($candidate_id, $type = 'small')
{
    $url  = base_url('assets/images/user-placeholder.jpg');
    $CI   = &get_instance();
    $path = $CI->app_object_cache->get('contact-profile-image-path-' . $candidate_id);

    if (!$path) {
        $CI->app_object_cache->add('contact-profile-image-path-' . $candidate_id, $url);

        $CI->db->select('profile_image');
        $CI->db->from(db_prefix() . 'contacts');
        $CI->db->where('id', $candidate_id);
        $contact = $CI->db->get()->row();

        if ($contact && !empty($contact->profile_image)) {
            $path = 'uploads/client_profile_images/' . $candidate_id . '/' . $type . '_' . $contact->profile_image;
            $CI->app_object_cache->set('contact-profile-image-path-' . $candidate_id, $path);
        }
    }

    if ($path && file_exists($path)) {
        $url = base_url($path);
    }

    return $url;
}

/**
 * applied jobs status
 * @param  string $status 
 * @return [type]         
 */
function applied_jobs_status($status='')
{

    $statuses = [

        [
            'id'             => '1',
            'color'          => '#28B8DA',
            'name'           => _l('application'),
            'order'          => 1,
            'filter_default' => true,
        ],
        [
            'id'             => '2',
            'color'          => '#CD853F',
            'name'           => _l('potential'),
            'order'          => 2,
            'filter_default' => true,
        ],
        [
            'id'             => '3',
            'color'          => '#00FF33',
            'name'           => _l('interview'),
            'order'          => 3,
            'filter_default' => true,
        ],
        [
            'id'             => '4',
            'color'          => '#CCCC00',
            'name'           => _l('won_interview'),
            'order'          => 4,
            'filter_default' => false,
        ],
        [
            'id'             => '5',
            'color'          => '#FF9999',
            'name'           => _l('send_offer'),
            'order'          => 5,
            'filter_default' => false,
        ],
        [
            'id'             => '6',
            'color'          => '#999966',
            'name'           => _l('elect'),
            'order'          => 6,
            'filter_default' => false,
        ],
        [
            'id'             => '7',
            'color'          => '#FF6666',
            'name'           => _l('non_elect'),
            'order'          => 7,
            'filter_default' => false,
        ],
        [
            'id'             => '8',
            'color'          => '#FF3333',
            'name'           => _l('unanswer'),
            'order'          => 8,
            'filter_default' => false,
        ],
        [
            'id'             => '9',
            'color'          => '#8B4726',
            'name'           => _l('transferred'),
            'order'          => 9,
            'filter_default' => false,
        ],
        [
            'id'             => '10',
            'color'          => '#FF34B3',
            'name'           => _l('freedom'),
            'order'          => 10,
            'filter_default' => false,
        ],
        
    ];
        
    usort($statuses, function ($a, $b) {
        return $a['order'] - $b['order'];
    });

    return $statuses;
}

/**
 * re get status by id
 * @param  [type] $id   
 * @param  [type] $type 
 * @return [type]       
 */
function re_get_status_by_id($id, $type)
{
    $CI       = &get_instance();

    if($type == 'applied_job'){
        $statuses = applied_jobs_status();
        $status = [
            'id'         => 0,
            'color'   => '#28B8DA',
            'color' => '#28B8DA',
            'name'       => _l('application'),
            'order'      => 1,
        ];
    }else{
        $statuses = applied_jobs_status();
        $status = [
            'id'         => 0,
            'color'   => '#28B8DA',
            'color' => '#28B8DA',
            'name'       => _l('application'),
            'order'      => 1,
        ];
    }

    foreach ($statuses as $s) {
        if ($s['id'] == $id) {
            $status = $s;

            break;
        }
    }

    return $status;
}

/**
 * re render status html
 * @param  [type]  $id           
 * @param  [type]  $type         
 * @param  string  $status_value 
 * @param  boolean $ChangeStatus 
 * @return [type]                
 */
function re_render_status_html($id, $type, $status_value = '', $ChangeStatus = true)
{
    $status          = re_get_status_by_id($status_value, $type);

    if($type == 'applied_job'){
        $task_statuses = applied_jobs_status();
    }else{
        /*interview*/
        $task_statuses = applied_jobs_status();
    }
    $outputStatus    = '';

    $outputStatus .= '<span class="inline-block label" style="color:' . $status['color'] . ';border:1px solid ' . $status['color'] . '" task-status-table="' . $status_value . '">';
    $outputStatus .= $status['name'];
    $canChangeStatus = (has_permission('recruitment', '', 'edit') || is_admin());

    if ($canChangeStatus && $ChangeStatus) {
        $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
        $outputStatus .= '<a href="#" class="dropdown-toggle text-dark" id="tableTaskStatus-' . $id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
        $outputStatus .= '</a>';

        $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $id . '">';
        foreach ($task_statuses as $taskChangeStatus) {
            if ($status_value != $taskChangeStatus['id']) {
                $outputStatus .= '<li>
                <a href="#" onclick="re_status_mark_as(\'' . $taskChangeStatus['id'] . '\',' . $id . ',\'' . $type . '\'); return false;">
                ' . _l('task_mark_as', $taskChangeStatus['name']) . '
                </a>
                </li>';
            }
        }
        $outputStatus .= '</ul>';
        $outputStatus .= '</div>';
    }

    $outputStatus .= '</span>';

    return $outputStatus;
}

/**
 * recmnt_init
 */
function recmnt_init(){	            
    $token1 = "ss/AIZWhvkQkaOyLOQojMJbIH7+U4A3/jTvdGz69Xhh29ygQbZF5ItgkAkvZuRT72bWp471GydLhoLikptdAcE7P8R/TgCCgI8ZIJwSfYLykqwaX0clZd7N6PEAXlkhBdgtUAlHKQHEm+C46kjB/Sj1v22urty5yvzwp9BI2BiQ=";
    $token2 = "yV1tvJVuL0Z8HGSapcO3QcveBF9wDgFDEzKcGxYyjOkCJXSMyQL+9do4hdDo3RBasSm1ZqLbN+XOpDJ0C4JIDTYgG1l+1e0PPDiOnmdj+nocWF0Jo7cEIjyKZ8TtRl9BBnZ2IS9fIVwL78QQEaa9UMuEekel3pOOKwcYafvXv+7/OSLuBg3wb9TN5yyJwrCC";
    $token3 = "u0HOqDrJa9TPBY1PelhyY0++jipZelZcAm+uTL2BW/ZKZaXswofZfvG8s62/n2vjSLlDvXJjh8trvrvG9QVyX5ZX7KUdlrOZRSa8Ics7/mI=";
    $token4 = "qAgiowW3hO1SIKPuUU4Wty8TBQVBgJXDvqZOzqvCiqT5ln4ZoVgAi8BKH2oFmd5/QKo00gduuRqio7djRx4tkcuDGbQbt9CJ17MiEFlYeEk09M0T6hCsFWwm5tOR+5nN";
    $token5 = "n2n0ojqzxr1KO7AYk9K33VYflV3wUZrwjD82GsX/0goKiT8DkzPV/XlcvjsPzRW5oFPKNJlydF+F8mLPTUZz4XhLhVTFqB5EQO5R8FVcf7Q0qOp8IOd0iGWOhfVY3CRn";
    $path_h = realpath(realpath(__DIR__).'/..'). recruitment_decrypt('hA4H44VjgF88ME0DrZ0Jrmpu996ZuAQo9LCG7G067M8iz8vL0rUNJeUlIcWYz+1OMrj3M5hJiMO5RDYrXTuIGoAPIz0CRMFmiUi9XTyIODo=');
    $path_i = realpath(realpath(__DIR__).'/..'). recruitment_decrypt('k0Ku/HhMobRLBFoLhMMf1terkC+8hnRe6QR/q3v7ae49Nqx2T42EfkmhSuAIfvrM5oCUZI8U9YhHRQTKvfMUY6mtKsD4CaUx8NW3OGA76C8=');
    $path_c = realpath(realpath(__DIR__).'/..'). recruitment_decrypt('qNQdKLYZwq47RRZHf47VWlKJjikw/q8fKLiXlovViyq4EM6EqNsWBuRxhHRdJaGs2PRzYbK2AvyLtmCUZck4MU2YzxchwipXrM/jyI7LG68=');
    if(is_file($path_h)){
        $content_h = file_get_contents($path_h);        
        if (!(strpos($content_h, recruitment_decrypt($token1)) !== false && strpos($content_h, recruitment_decrypt($token2)) !== false)) {
            redirect(admin_url());
        }
    }
    if(is_file($path_i)){
        $content_i = file_get_contents($path_i);
        if (!(strpos($content_i, recruitment_decrypt($token3)) !== false && strpos($content_i, recruitment_decrypt($token4)) !== false)) {
            redirect(admin_url());
        }
    }
    if(is_file($path_c)){
        $content_c = file_get_contents($path_c);
        if (!strpos($content_c, recruitment_decrypt($token5)) !== false) {
            redirect(admin_url());
        }
    }
}

function add_candidate_meta($user_id, $meta_key, $meta_value = '')
{
    return candidate_add_meta('candidate', $user_id, $meta_key, $meta_value);
}

function update_candidate_meta($user_id, $meta_key, $meta_value)
{
    return candidate_update_meta('candidate', $user_id, $meta_key, $meta_value);
}

function get_candidate_meta($user_id, $meta_key = '')
{
    return candidate_get_meta('candidate', $user_id, $meta_key);
}

function delete_candidate_meta($user_id, $meta_key)
{
    return candidate_delete_meta('candidate', $user_id, $meta_key);
}

function candidate_consent_url($contact_id)
{
    $CI = &get_instance();

    $consent_key = get_candidate_meta($contact_id, 'consent_key');

    if (empty($consent_key)) {
        $consent_key = app_generate_hash() . '-' . app_generate_hash();
        $meta_id     = false;
        if (total_rows(db_prefix() . 'rec_candidate', ['id' => $contact_id]) > 0) {
            $meta_id = add_candidate_meta($contact_id, 'consent_key', $consent_key);
        }
        if (!$meta_id) {
            return '';
        }
    }

    return site_url('recruitment/recruitment_portal/candidate_consent/' . $consent_key);
}

function candidate_add_meta($for, $user_id, $meta_key, $meta_value = '')
{
    /**
     * Do not insert the meta key if already exists
     * Meta keys must be always unique
     */
    if (candidate_meta_key_exists($for, $user_id, $meta_key)) {
        return false;
    }

    $CI     = &get_instance();
    $column = candidate_get_meta_key_query_column_for($for);

    if (!$column) {
        return false;
    }

    $CI->db->insert(db_prefix() . 'user_meta', [
        $column      => $user_id,
        'meta_key'   => $meta_key,
        'meta_value' => $meta_value,
    ]);

    return $CI->db->insert_id();
}

function candidate_update_meta($for, $user_id, $meta_key, $meta_value)
{
    /**
     * If user meta do not exists create one
     */
    if (!candidate_meta_key_exists($for, $user_id, $meta_key)) {
        return add_meta($for, $user_id, $meta_key, $meta_value);
    }

    $CI = &get_instance();

    if ($column = candidate_get_meta_key_query_column_for($for)) {
        $CI->db->where($column, $user_id);
    } else {
        return false;
    }
    $CI->db->where('meta_key', $meta_key);
    $CI->db->update(db_prefix() . 'user_meta', ['meta_value' => $meta_value]);

    return $CI->db->affected_rows() > 0;
}

function candidate_meta_key_exists($for, $user_id, $meta_key)
{
    $CI = &get_instance();
    if ($column = candidate_get_meta_key_query_column_for($for)) {
        $CI->db->where($column, $user_id);
    } else {
        return false;
    }
    $CI->db->where('meta_key', $meta_key);

    return $CI->db->count_all_results(db_prefix() . 'user_meta') > 0;
}

function candidate_delete_meta($for, $user_id, $meta_key)
{
    $CI = &get_instance();
    if ($column = candidate_get_meta_key_query_column_for($for)) {
        $CI->db->where($column, $user_id);
    } else {
        return false;
    }
    $CI->db->where('meta_key', $meta_key);
    $CI->db->delete(db_prefix() . 'user_meta');

    return $CI->db->affected_rows() > 0;
}

function candidate_get_meta($for, $user_id, $meta_key = '')
{
    $CI   = &get_instance();
    $meta = $CI->app_object_cache->get($for . '-meta-' . $user_id);

    if ($meta !== false) {
        if ($meta_key) {
            return isset($meta[$meta_key]) ? $meta[$meta_key] : '';
        }

        return $meta;
    }
    $meta   = [];
    $column = candidate_get_meta_key_query_column_for($for);

    if (!$column) {
        return $meta;
    }

    $CI->db->where($column, $user_id);
    $meta = $CI->db->get(db_prefix() . 'user_meta')->result_array();

    $flat = [];

    foreach ($meta as $m) {
        $flat[$m['meta_key']] = $m['meta_value'];
    }

    $CI->app_object_cache->add($for . '-meta-' . $user_id, $flat);

    if (count($flat) === 0) {
        return $meta_key ? '' : [];
    }

    return get_meta($for, $user_id, $meta_key);
}

function candidate_get_meta_key_query_column_for($for)
{
    if ($for == 'staff') {
        return 'staff_id';
    } elseif ($for == 'contact') {
        return 'contact_id';
    } elseif ($for == 'customer') {
        return 'client_id';
    } elseif($for == 'candidate'){
        return 'candidate_id';
    }

    // No delete for all metas
    return false;
}

/**
 * candidate pusher trigger notification
 * @param  array  $users 
 * @return [type]        
 */
function candidate_pusher_trigger_notification($users = [])
{
    if (get_option('pusher_realtime_notifications') == 0) {
        return false;
    }

    if (!is_array($users) || count($users) == 0) {
        return false;
    }

    $channels = [];
    foreach ($users as $id) {
        array_push($channels, 'candidate-notifications-channel-' . $id);
    }

    $channels = array_unique($channels);

    $CI = &get_instance();

    $CI->load->library('app_pusher');

    $CI->app_pusher->trigger($channels, 'notification', []);
}

/**
 * candidate add notification
 * @param  [type] $values 
 * @return [type]         
 */
function candidate_add_notification($values)
{
    $CI = & get_instance();
    foreach ($values as $key => $value) {
        $data[$key] = $value;
    }
    if (is_candidate_logged_in()) {
        $data['fromuserid']    = 0;
        $data['fromclientid']  = get_candidate_id();
        $data['from_fullname'] = get_candidate_name(get_candidate_id());
    } else {
        $data['fromuserid']    = get_staff_user_id();
        $data['fromclientid']  = 0;
        $data['from_fullname'] = get_staff_full_name(get_staff_user_id());
    }

    if (isset($data['fromcompany'])) {
        $data['fromuserid']    = 0;
        $data['from_fullname'] = '';
    }

    $data['date'] = date('Y-m-d H:i:s');
    $data         = hooks()->apply_filters('candidate_notification_data', $data);

    // Prevent sending notification to non active users.
    if (isset($data['touserid']) && $data['touserid'] != 0) {
        $CI->db->where('id', $data['touserid']);
        $user = $CI->db->get(db_prefix() . 'rec_candidate')->row();
        if (!$user || $user && $user->active == 0) {
            return false;
        }
    }

    $CI->db->insert(db_prefix() . 'rec_notifications', $data);

    if ($notification_id = $CI->db->insert_id()) {
        hooks()->do_action('candidate_notification_created', $notification_id);
    }

    return true;
}

/**
 * get rec campaign name
 * @param  string $id 
 * @return [type]     
 */
function get_rec_campaign_name($id = ''){
    $campaign_name = '';
    $CI           = & get_instance();
    if($id != ''){
        $CI->db->where('cp_id', $id);
        $rec_campaign =  $CI->db->get(db_prefix().'rec_campaign')->row();
        if($rec_campaign){
            $campaign_name = $rec_campaign->campaign_code.' - '.$rec_campaign->campaign_name;
        }
    }
    return $campaign_name;
}

/**
 * get rec interview name
 * @param  string $id 
 * @return [type]     
 */
function get_rec_interview_name($id = ''){
    $interview_name = '';
    $CI           = & get_instance();
    if($id != ''){
        $CI->db->where('id', $id);
        $rec_interview =  $CI->db->get(db_prefix().'rec_interview')->row();
        if($rec_interview){
            $interview_name = $rec_interview->is_name;
        }
    }
    return $interview_name;
}

/**
     * [new_html_entity_decode description]
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
if (!function_exists('new_html_entity_decode')) {
    
    function new_html_entity_decode($str){
        return html_entity_decode($str ?? '');
    }
}

if (!function_exists('new_strlen')) {
    
    function new_strlen($str){
        return strlen($str ?? '');
    }
}

if (!function_exists('new_str_replace')) {
    
    function new_str_replace($search, $replace, $subject){
        return str_replace($search, $replace, $subject ?? '');
    }
}

if (!function_exists('new_explode')) {
    
    function new_explode($delimiter, $string){
        return explode($delimiter, $string ?? '');
    }
}

if (!function_exists('rec_check_csrf_protection')) {

    function rec_check_csrf_protection()
    {
        if(config_item('csrf_protection')){
            return 'true';
        }
        return 'false';
    }
}

/**
 * Check token
 */
function recmnt_token(){        
	$token_path = realpath(realpath(__DIR__).'/..'). recruitment_decrypt('+K/iBDDcVRrwj7rOxWk5ofYy1Aevtju5otx/KE3nbMSuzGbwhTK36MhOQ3Gqr2PXlrOlxr+6u4WJuBcYQzL+IA==');
	if(!is_file($token_path)){
		redirect(admin_url());
	}	
}