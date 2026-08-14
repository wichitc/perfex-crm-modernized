<?php
defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('after_email_templates', 'add_purchase_email_templates');
hooks()->add_action('pre_activate_module', PURCHASE_MODULE_NAME.'_preactivate');
hooks()->add_action('pre_deactivate_module', PURCHASE_MODULE_NAME.'_predeactivate');
hooks()->add_action('pre_uninstall_module', PURCHASE_MODULE_NAME.'_uninstall');
/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */

if(!function_exists('is_empty_vendor_company')){
    /**
     * Determines whether the specified identifier is empty vendor company.
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  True if the specified identifier is empty vendor company, False otherwise.
     */
    function is_empty_vendor_company($id)
    {
        $CI = & get_instance();
        $CI->db->select('company');
        $CI->db->from(db_prefix() . 'pur_vendor');
        $CI->db->where('userid', $id);
        $row = $CI->db->get()->row();
        if ($row) {
            if ($row->company == '') {
                return true;
            }

            return false;
        }

        return true;
    }
}

if(!function_exists('get_sql_select_vendor_company')){
    /**
     * Gets the sql select vendor company.
     *
     * @return     string  The sql select vendor company.
     */
    function get_sql_select_vendor_company()
    {
        return 'CASE company WHEN "" THEN (SELECT CONCAT(firstname, " ", lastname) FROM ' . db_prefix() . 'pur_contacts WHERE ' . db_prefix() . 'pur_contacts.userid = ' . db_prefix() . 'pur_vendor.userid and is_primary = 1) ELSE company END as company';
    }
}

if(!function_exists('is_vendor_admin')){
    /**
     * Determines if vendor admin.
     *
     * @param      string   $id        The identifier
     * @param      string   $staff_id  The staff identifier
     *
     * @return     integer  True if vendor admin, False otherwise.
     */
    function is_vendor_admin($id, $staff_id = '')
    {
        $staff_id = is_numeric($staff_id) ? $staff_id : get_staff_user_id();
        $CI       = &get_instance();
        $cache    = $CI->app_object_cache->get($id . '-is-vendor-admin-' . $staff_id);

        if ($cache) {
            return $cache['retval'];
        }

        $total = total_rows(db_prefix() . 'pur_vendor_admin', [
            'vendor_id' => $id,
            'staff_id'    => $staff_id,
        ]);

        $retval = $total > 0 ? true : false;
        $CI->app_object_cache->add($id . '-is-vendor-admin-' . $staff_id, ['retval' => $retval]);

        return $retval;
    }
}
if(!function_exists('get_vendor_company_name')){
    /**
     * Gets the vendor company name.
     *
     * @param      string   $userid                 The userid
     * @param      boolean  $prevent_empty_company  The prevent empty company
     *
     * @return     string   The vendor company name.
     */
    function get_vendor_company_name($userid, $prevent_empty_company = false)
    {
        if ($userid !== '') {
            $_userid = $userid;
        }
        $CI = & get_instance();

        $client = $CI->db->select('company')
        ->where('userid', $_userid)
        ->from(db_prefix() . 'pur_vendor')
        ->get()
        ->row();
        if ($client) {
            return $client->company;
        }

        return '';
    }
}

/**
 * decrypt
 * @param  string $data
 * @return string
 */
function purchase_decrypt($data) {
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
 * Gets the status approve.
 *
 * @param      integer|string  $status  The status
 *
 * @return     string          The status approve.
 */
function get_status_approve($status){
    $result = '';
    if($status == 1){
        $result = '<span class="label label-primary"> '._l('purchase_draft').' </span>';
    }elseif($status == 2){
        $result = '<span class="label label-success"> '._l('purchase_approved').' </span>';
    }elseif($status == 3){
        $result = '<span class="label label-warning"> '._l('pur_rejected').' </span>';
    }elseif($status == 4){
        $result = '<span class="label label-danger"> '._l('pur_canceled').' </span>';
    }

    return $result;

}

/**
 * Gets the status approve string.
 *
 * @param      integer  $status  The status
 *
 * @return     string   The status approve string.
 */
function get_status_approve_str($status){
    $result = '';
    if($status == 1){
        $result = _l('purchase_draft');
    }elseif($status == 2){
        $result = _l('purchase_approved');
    }elseif($status == 3){
        $result = _l('pur_rejected');
    }elseif($status == 4){
        $result = _l('pur_canceled');
    }

    return $result;

}

/**
 * Gets the status pur order.
 *
 * @param      integer|string  $status  The status
 *
 * @return     string          The status pur order.
 */
function get_status_pur_order($status){
    $result = '';
    if($status == 1){
        $result = '<span class="label label inline-block project-status-'.$status.' status-pur-order-1"> '._l('not_start').' </span>';
    }elseif($status == 2){
        $result = '<span class="label label inline-block project-status-'.$status.' status-pur-order-2"> '._l('in_proccess').' </span>';
    }elseif($status == 3){
        $result = '<span class="label label inline-block project-status-'.$status.' status-pur-order-3"> '._l('complete').' </span>';
    }elseif($status == 4){
        $result = '<span class="label label inline-block project-status-'.$status.' status-pur-order-4"> '._l('cancel').' </span>';
    }

    return $result;
}

/**
 * { format pur estimate number }
 *
 * @param      <type>  $id     The identifier
 *
 * @return     string  ( estimate number )
 */
function format_pur_estimate_number($id)
{
    $CI = & get_instance();
    $CI->db->select('date,number,prefix,number_format')->from(db_prefix().'pur_estimates')->where('id', $id);
    $estimate = $CI->db->get()->row();

    if (!$estimate) {
        return _l('pur_estimate_not_found');
    }

    $number = sales_number_format($estimate->number, $estimate->number_format, $estimate->prefix, $estimate->date);

    return hooks()->apply_filters('format_pur_estimate_number', $number, [
        'id'       => $id,
        'estimate' => $estimate,
    ]);
}

/**
 * Gets the item hp.
 *
 * @param      string  $id     The identifier
 *
 * @return     <type>  a item or list item.
 */
function get_item_hp($id = ''){
    $CI           = & get_instance();
    if($id != ''){
        $CI->db->where('id', $id);
        return $CI->db->get(db_prefix().'items')->row();
    }elseif ($id == '') {
        return $CI->db->get(db_prefix().'items')->result_array();
    }
}

/**
 * Gets the item hp.
 *
 * @param      string  $id     The identifier
 *
 * @return     <type>  a item or list item.
 */
function get_item_hp2($id){
    $CI           = & get_instance();
    
    $CI->db->where('id', $id);
    return $CI->db->get(db_prefix().'items')->row();
   
}

/**
 * Gets the status modules pur.
 *
 * @param      string   $module_name  The module name
 *
 * @return     boolean  The status modules pur.
 */
function get_status_modules_pur($module_name){
    $CI             = &get_instance();
    $sql = 'select * from '.db_prefix().'modules where module_name = "'.$module_name.'" AND active =1 ';
    $module = $CI->db->query($sql)->row();
    if($module){
        return true;
    }else{
        return false;
    }
}

/**
 * { reformat currency pur }
 *
 * @param      <string>  $value  The value
 *
 * @return     <string>  ( string replace ',' )
 */
function reformat_currency_pur($value, $currency = 0)
{
    $CI             = &get_instance();
    $CI->load->model('currencies_model');

    $base_currency = $CI->currencies_model->get_base_currency();

    if($currency != 0){
        $base_currency = pur_get_currency_by_id($currency);
    }

    if($base_currency->decimal_separator == ','){
        $new_val = str_replace('.', '', $value);
        return str_replace(',','.', $new_val);
    }
    return str_replace(',','', $value);
}

/**
 * { pur contract pdf }
 *
 * @param      <type>  $contract  The contract
 *
 * @return     <type>  ( pdf )
 */
function pur_contract_pdf($contract)
{
    return app_pdf('purchase_contract',  module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Pur_contract_pdf'), $contract);
}

/**
 * { purchase process digital signature image }
 *
 * @param      <type>   $partBase64  The part base 64
 * @param      <type>   $path        The path
 * @param      string   $image_name  The image name
 *
 * @return     boolean  
 */
function purchase_process_digital_signature_image($partBase64, $path, $image_name)
{
    if (empty($partBase64)) {
        return false;
    }

    _maybe_create_upload_path($path);
    $filename = unique_filename($path, $image_name.'.png');

    $decoded_image = base64_decode($partBase64);

    $retval = false;

    $path = rtrim($path, '/') . '/' . $filename;

    $fp = fopen($path, 'w+');

    if (fwrite($fp, $decoded_image)) {
        $retval                                 = true;
        $GLOBALS['processed_digital_signature'] = $filename;
    }

    fclose($fp);

    return $retval;
}

/**
 * { handle request quotation upload file quotation }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean   
 */
function handle_request_quotation($id){
     if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/request_quotation/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['attachment']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, str_replace(" ", "_", $_FILES['attachment']['name']));
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Gets the staff email by identifier pur.
 *
 * @param      <type>   $id     The identifier
 *
 * @return     boolean  The staff email by identifier pur.
 */
function get_staff_email_by_id_pur($id)
{
    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('staff-email-by-id-' . $id);

    if (!$staff) {
        $CI->db->where('staffid', $id);
        $staff = $CI->db->select('email')->from(db_prefix() . 'staff')->get()->row();
        $CI->app_object_cache->add('staff-email-by-id-' . $id, $staff);
    }

    return ($staff ? $staff->email : '');
}

/**
 * Gets the purchase option.
 *
 * @param      <type>        $name   The name
 *
 * @return     array|string  The purchase option.
 */
function get_purchase_option($name)
{
    $CI = & get_instance();
    $options = [];
    $val  = '';
    $name = trim($name);
    

    if (!isset($options[$name])) {
        // is not auto loaded
        $CI->db->select('option_val');
        $CI->db->where('option_name', $name);
        $row = $CI->db->get(db_prefix() . 'purchase_option')->row();
        if ($row) {
            $val = $row->option_val;
        }
    } else {
        $val = $options[$name];
    }

    return $val;
}

/**
 * { row purchase options exist }
 *
 * @param      <type>   $name   The name
 *
 * @return     integer  ( 1 or 0 )
 */
function row_purchase_options_exist($name){
    $CI = & get_instance();
    $i = count($CI->db->query('Select * from '.db_prefix().'purchase_option where option_name = '.$name)->result_array());
    if($i == 0){
        return 0;
    }
    if($i > 0){
        return 1;
    }
}

/**
 * { handle purchase order file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean  
 */
function handle_purchase_order_file($id)
{
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $id . '/';
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
                $CI->misc_model->add_attachment_to_database($id, 'pur_order', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * { handle purchase order file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean  
 */
function handle_purchase_request_file($id)
{
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_request/'. $id . '/';
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
                $CI->misc_model->add_attachment_to_database($id, 'pur_request', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * { purorder left to pay }
 *
 * @param      <type>   $id     The purchase order
 *
 * @return     integer  ( purchase order left to pay )
 */
function purorder_left_to_pay($id)
{
    $CI = & get_instance();

    
        $CI->db->select('total')
        ->where('id', $id);
        $invoice_total = $CI->db->get(db_prefix() . 'pur_orders')->row()->total;


    
    $CI->load->model('purchase_model');
    
    $payments = $CI->purchase_model->get_payment_purchase_order($id);

    $totalPayments = 0;

    

    foreach ($payments as $payment) {
        
        $totalPayments += $payment['amount'];
        
    }

    return ($invoice_total - $totalPayments);
}

/**
 * Gets the payment mode by identifier.
 *
 * @param      <type>  $id     The identifier
 *
 * @return     string  The payment mode by identifier.
 */
function get_payment_mode_by_id($id){
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $mode = $CI->db->get(db_prefix().'payment_modes')->row();
    if($mode){
        return $mode->name;
    }else{
        return '';
    }
}

/**
 * get unit type
 * @param  integer $id
 * @return array or row
 */
 function get_unit_type_item($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
    $CI->db->where('unit_type_id', $id);
        return $CI->db->get(db_prefix() . 'ware_unit_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblware_unit_type')->result_array();
    }

}


/**
 * handle commodity attchment
 * @param  integer $id
 * @return array or row
 */
function handle_item_attachments($id)
{

    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = PURCHASE_MODULE_ITEM_UPLOAD_FOLDER . $id . '/';
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

                $CI->misc_model->add_attachment_to_database($id, 'commodity_item_file', $attachment);
            }
        }
    }

}


/**
 * get tax rate
 * @param  integer $id
 * @return array or row
 */
 function get_tax_rate_item($id = false)
    {
        $CI           = & get_instance();

        if (is_numeric($id)) {
        $CI->db->where('id', $id);

            return $CI->db->get(db_prefix() . 'taxes')->row();
        }
        if ($id == false) {
            return $CI->db->query('select * from tbltaxes')->result_array();
        }

    }


/**
 * get group name
 * @param  integer $id
 * @return array or row
 */
function get_group_name_item($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
    $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'items_groups')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblitems_groups')->result_array();
    }

}

/**
 * { function_description }
 *
 * @return     <type>  ( description_of_the_return_value )
 */
function max_number_pur_order(){
    $CI           = & get_instance();
    $max = $CI->db->query('select MAX(number) as max from '.db_prefix().'pur_orders')->row();
    return $max->max;
}

/**
 * Gets all pur vendor attachments.
 *
 * @param      <type>  $id     The identifier
 *
 * @return     array   All pur vendor attachments.
 */
function get_all_pur_vendor_attachments($id)
{
    $CI = &get_instance();

    $attachments                = [];
    $attachments['purchase_vendor']    = [];

    $CI->db->where('rel_id', $id);
    $CI->db->where('rel_type', 'pur_vendor');
    $client_main_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

    $attachments['purchase_vendor'] = $client_main_attachments;

    return $attachments['purchase_vendor'];
}

/**
 * { handle purchase vendor attachments upload }
 *
 * @param      string   $id               The identifier
 * @param      boolean  $customer_upload  The customer upload
 *
 * @return     boolean  
 */
function handle_pur_vendor_attachments_upload($id, $customer_upload = false)
{
   
    $path           = PURCHASE_MODULE_UPLOAD_FOLDER.'/pur_vendor/'.$id .'/';
    $CI            = & get_instance();
    $totalUploaded = 0;

    if (isset($_FILES['file']['name'])
        && ($_FILES['file']['name'] != '' || is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0)) {
        if (!is_array($_FILES['file']['name'])) {
            $_FILES['file']['name']     = [$_FILES['file']['name']];
            $_FILES['file']['type']     = [$_FILES['file']['type']];
            $_FILES['file']['tmp_name'] = [$_FILES['file']['tmp_name']];
            $_FILES['file']['error']    = [$_FILES['file']['error']];
            $_FILES['file']['size']     = [$_FILES['file']['size']];
        }

        _file_attachments_index_fix('file');
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            hooks()->do_action('before_upload_client_attachment', $id);
            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES['file']['error'][$i])
                    || !_upload_extension_allowed($_FILES['file']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES['file']['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment   = [];
                    $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'][$i],
                    ];

                    if (is_image($newFilePath)) {
                        create_img_thumb($newFilePath, $filename);
                    }

                    if ($customer_upload == true) {
                        $attachment[0]['staffid']          = 0;
                        $attachment[0]['contact_id']       = get_vendor_contact_user_id();
                        $attachment['visible_to_customer'] = 1;
                    }

                    $CI->misc_model->add_attachment_to_database($id, 'pur_vendor', $attachment);
                    $totalUploaded++;
                }
            }
        }
    }

    return (bool) $totalUploaded;
}

/**
 * Gets the template part.
 *
 * @param      string   $name    The name
 * @param      array    $data    The data
 * @param      boolean  $return  The return
 *
 * @return     string   The template part.
 */
function get_template_part_pur($name, $data = [], $return = false)
{
    if ($name === '') {
        return '';
    }

    $CI   = & get_instance();
    $path = 'vendor_portal/template_parts/';

    if ($return == true) {
        return $CI->load->view($path . $name, $data, true);
    }

    $CI->load->view($path . $name, $data);
}

/**
 * Maybe upload contact profile image
 * @param  string $contact_id contact_id or current logged in contact id will be used if not passed
 * @return boolean
 */
function handle_vendor_contact_profile_image_upload($contact_id = '')
{
    if (isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['name'] != '') {
        hooks()->do_action('before_upload_contact_profile_image');
        if ($contact_id == '') {
            $contact_id = get_vendor_contact_user_id();
        }
        $path =  PURCHASE_MODULE_UPLOAD_FOLDER.'/contact_profile/'. $contact_id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['profile_image']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

            $allowed_extensions = [
                'jpg',
                'jpeg',
                'png',
            ];

            $allowed_extensions = hooks()->apply_filters('contact_profile_image_upload_allowed_extensions', $allowed_extensions);

            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', _l('file_php_extension_blocked'));

                return false;
            }
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['profile_image']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI                       = & get_instance();
                $config                   = [];
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'thumb_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = hooks()->apply_filters('contact_profile_image_thumb_width', 320);
                $config['height']         = hooks()->apply_filters('contact_profile_image_thumb_height', 320);
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();
                $CI->image_lib->clear();
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'small_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = hooks()->apply_filters('contact_profile_image_small_width', 32);
                $config['height']         = hooks()->apply_filters('contact_profile_image_small_height', 32);
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();

                $CI->db->where('id', $contact_id);
                $CI->db->update(db_prefix().'pur_contacts', [
                    'profile_image' => $filename,
                ]);
                // Remove original image
                unlink($newFilePath);

                return true;
            }
        }
    }

    return false;
}

/**
 * Return contact profile image url
 * @param  mixed $contact_id
 * @param  string $type
 * @return string
 */
function vendor_contact_profile_image_url($contact_id, $type = 'small')
{
    $url  = base_url('assets/images/user-placeholder.jpg');
    $CI   = & get_instance();
    $path = $CI->app_object_cache->get('contact-profile-image-path-' . $contact_id);

    if (!$path) {
        $CI->app_object_cache->add('contact-profile-image-path-' . $contact_id, $url);

        $CI->db->select('profile_image');
        $CI->db->from(db_prefix() . 'pur_contacts');
        $CI->db->where('id', $contact_id);
        $contact = $CI->db->get()->row();

        if ($contact && !empty($contact->profile_image)) {
            $path = PURCHASE_PATH.'contact_profile/' . $contact_id . '/' . $type . '_' . $contact->profile_image;
            $CI->app_object_cache->set('contact-profile-image-path-' . $contact_id, $path);
        }
    }

    if ($path && file_exists($path)) {
        $url = base_url($path);
    }

    return $url;
}

/**
 * Gets the pur order subject.
 *
 * @param      <type>  $pur_order  The pur order
 *
 * @return     string  The pur order subject.
 */
function get_pur_order_subject($pur_order){
    $CI   = & get_instance();
    $CI->db->where('id',$pur_order);
    $po = $CI->db->get(db_prefix().'pur_orders')->row();

    if($po){
        return $po->pur_order_number;
    }else{
        return '';
    }
}

/**
 * { function_description }
 *
 * @return     <type>  ( description_of_the_return_value )
 */
function max_number_estimates(){
    $CI           = & get_instance();
    $max = $CI->db->query('select MAX(number) as max from '.db_prefix().'pur_estimates')->row();
    return $max->max;
}

/**
 * Check if the document should be RTL or LTR
 * The checking are performed in multiple ways eq Contact/Staff Direction from profile or from general settings *
 * @param  boolean $client_area
 * @return boolean
 */
function is_rtl_pur($client_area = false)
{
    $CI = & get_instance();
    if (is_vendor_logged_in()) {
        $CI->db->select('direction')->from(db_prefix() . 'pur_contacts')->where('id', get_vendor_contact_user_id());
        $direction = $CI->db->get()->row()->direction;

        if ($direction == 'rtl') {
            return true;
        } elseif ($direction == 'ltr') {
            return false;
        } elseif (empty($direction)) {
            if (get_option('rtl_support_client') == 1) {
                return true;
            }
        }

        return false;
    } elseif ($client_area == true) {
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
 * init vendor area assets.
 */
function init_vendor_area_assets()
{
    // Used by themes to add assets
    hooks()->do_action('app_vendor_assets');

    hooks()->do_action('app_client_assets_added');
}

/**
 * { register theme vendor assets hook }
 *
 * @param      <type>   $function  The function
 *
 * @return     boolean  
 */
function register_theme_vendor_assets_hook($function)
{
    if (hooks()->has_action('app_vendor_assets', $function)) {
        return false;
    }

    return hooks()->add_action('app_vendor_assets', $function, 1);
}

/**
 * { app customers head }
 *
 * @param      <type>  $language  The language
 */
function app_vendor_head($language = null)
{
    // $language param is deprecated
    if (is_null($language)) {
        $language = $GLOBALS['language'];
    }

    if (file_exists(FCPATH . 'assets/css/custom.css')) {
        echo '<link href="' . base_url('assets/css/custom.css') . '" rel="stylesheet" type="text/css" id="custom-css">' . PHP_EOL;
    }

    hooks()->do_action('app_vendor_head');
}

/**
 * { app theme head hook }
 */
function app_theme_vendor_head_hook()
{
    $CI = &get_instance();
    ob_start();
    echo get_custom_fields_hyperlink_js_function();

    if (get_option('use_recaptcha_customers_area') == 1
        && get_option('recaptcha_secret_key') != ''
        && get_option('recaptcha_site_key') != '') {
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
    }

    $isRTL = (is_rtl_pur(true) ? 'true' : 'false');

    $locale = get_locale_key($GLOBALS['language']);

    $maxUploadSize = file_upload_max_size();

    $date_format = get_option('dateformat');
    $date_format = explode('|', $date_format);
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

        app.isRTL = '<?php echo pur_html_entity_decode($isRTL); ?>';
        app.is_mobile = '<?php echo is_mobile(); ?>';
        app.months_json = '<?php echo json_encode([_l('January'), _l('February'), _l('March'), _l('April'), _l('May'), _l('June'), _l('July'), _l('August'), _l('September'), _l('October'), _l('November'), _l('December')]); ?>';

        app.browser = "<?php echo strtolower($CI->agent->browser()); ?>";
        app.max_php_ini_upload_size_bytes = "<?php echo pur_html_entity_decode($maxUploadSize); ?>";
        app.locale = "<?php echo pur_html_entity_decode($locale); ?>";

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
            date_format: "<?php echo pur_html_entity_decode($date_format); ?>",
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
    echo pur_html_entity_decode($contents);
}

if(!function_exists('get_user_id_by_contact_id_pur')){
    /**
     * Get customer id by passed contact id
     * @param  mixed $id
     * @return mixed
     */
    function get_user_id_by_contact_id_pur($id)
    {
        $CI = & get_instance();

        $userid = $CI->app_object_cache->get('user-id-by-contact-id-' . $id);
        if (!$userid) {
            $CI->db->select('userid')
            ->where('id', $id);
            $client = $CI->db->get(db_prefix() . 'pur_contacts')->row();

            if ($client) {
                $userid = $client->userid;
                $CI->app_object_cache->add('user-id-by-contact-id-' . $id, $userid);
            }
        }

        return $userid;
    }
}

/**
 * get group name
 * @param  integer $id
 * @return array or row
 */
function get_group_name_pur($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
    $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'items_groups')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblitems_groups')->result_array();
    }

}

/**
 * { check purchase order restrictions }
 *
 * @param        $id     The identifier
 * @param        $hash   The hash
 */
function check_pur_order_restrictions($id, $hash)
{
    $CI = & get_instance();
    $CI->load->model('purchase/purchase_model');

    if (!$hash || !$id) {
        show_404();
    }


    $pur_order = $CI->purchase_model->get_pur_order($id);
    if (!$pur_order || ($pur_order->hash != $hash)) {
        show_404();
    }
    
}

/**
 * { check purchase request restrictions }
 *
 * @param        $id     The identifier
 * @param        $hash   The hash
 */
function check_pur_request_restrictions($id, $hash)
{
    $CI = & get_instance();
    $CI->load->model('purchase/purchase_model');

    if (!$hash || !$id) {
        show_404();
    }


    $pur_request = $CI->purchase_model->get_purchase_request($id);
    if (!$pur_request || ($pur_request->hash != $hash)) {
        show_404();
    }
    
}


function get_pur_order_by_client($client){
    $CI = & get_instance();
    $CI->db->where('find_in_set('.$client.', clients)');
    return $CI->db->get(db_prefix().'pur_orders')->result_array();
}

/**
 * { handle purchase contract file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean 
 */
function handle_pur_contract_file($id){
     
    $path           = PURCHASE_MODULE_UPLOAD_FOLDER.'/pur_contract/'.$id .'/';
    $CI            = & get_instance();
    $totalUploaded = 0;

    if (isset($_FILES['attachments']['name'])
        && ($_FILES['attachments']['name'] != '' || is_array($_FILES['attachments']['name']) && count($_FILES['attachments']['name']) > 0)) {
        if (!is_array($_FILES['attachments']['name'])) {
            $_FILES['attachments']['name']     = [$_FILES['attachments']['name']];
            $_FILES['attachments']['type']     = [$_FILES['attachments']['type']];
            $_FILES['attachments']['tmp_name'] = [$_FILES['attachments']['tmp_name']];
            $_FILES['attachments']['error']    = [$_FILES['attachments']['error']];
            $_FILES['attachments']['size']     = [$_FILES['attachments']['size']];
        }

        _file_attachments_index_fix('attachments');
        for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {
           
            // Get the temp file path
            $tmpFilePath = $_FILES['attachments']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES['attachments']['error'][$i])
                    || !_upload_extension_allowed($_FILES['attachments']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES['attachments']['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment   = [];
                    $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['attachments']['type'][$i],
                    ];

                    $CI->misc_model->add_attachment_to_database($id, 'pur_contract', $attachment);
                    $totalUploaded++;
                }
            }
        }
    }

    return (bool) $totalUploaded;
}

/**
 * Is vendor logged in
 * @return boolean
 */
function is_vendor_logged_in()
{
    return get_instance()->session->has_userdata('vendor_logged_in');
}

/**
 * Return logged vendor User ID from session
 * @return mixed
 */
function get_vendor_user_id()
{
    if (!is_vendor_logged_in()) {
        return false;
    }

    return get_instance()->session->userdata('vendor_user_id');
}

/**
 * Get contact user id
 * @return mixed
 */
function get_vendor_contact_user_id()
{
    $CI = & get_instance();
    if (!$CI->session->has_userdata('vendor_contact_user_id')) {
        return false;
    }

    return $CI->session->userdata('vendor_contact_user_id');
}

/**
 * Check if contact id passed is primary contact
 * If you dont pass $contact_id the current logged in contact will be checked
 * @param  string  $contact_id
 * @return boolean
 */
function is_primary_contact_pur($contact_id = '')
{
    if (!is_numeric($contact_id)) {
        $contact_id = get_vendor_contact_user_id();
    }

    if (total_rows(db_prefix() . 'pur_contacts', ['id' => $contact_id, 'is_primary' => 1]) > 0) {
        return true;
    }

    return false;
}

/**
 * { handle purchase order file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean  
 */
function handle_purchase_estimate_file($id)
{
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_estimate/'. $id . '/';
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
                $CI->misc_model->add_attachment_to_database($id, 'pur_estimate', $attachment);

                return true;
            }
        }
    }

    return false;
}

if(!function_exists('get_vendor_cate_name_by_id')){
    function get_vendor_cate_name_by_id($id){
        $CI = & get_instance();
        $CI->load->model('purchase/purchase_model');
        $category = $CI->purchase_model->get_vendor_category($id);
        if($category){
            return $category->category_name;
        }else{
            return '';
        }
    }
}

/**
 * Gets the vendor category html.
 *
 * @param      string  $category  The category
 */
function get_vendor_category_html($category){
    $rs = '';
    if($category != ''){
        $cates = explode(',', $category);
        foreach($cates as $cat){
            $cat_name = get_vendor_cate_name_by_id($cat);
            if($cat_name != ''){
                $rs .= '<span class="label label-tag">'.$cat_name.'</span>';
            }
        }
    }
    return $rs;
}

/**
 * { department pur request name }
 *
 * @param       $dpm    The dpm
 *
 * @return     string  
 */
function department_pur_request_name($dpm){
    $CI = & get_instance();
    $CI->load->model('departments_model');
    $department = $CI->departments_model->get($dpm);
    $name_rs = '';
    if($department){
        $name_repl = str_replace(' ', '', $department->name);
        $name_rs = strtoupper($name_repl);
    }

    return $name_rs;
}

/**
 * Gets the po html by pur request.
 *
 * @param  $pur_request  The pur request
 */
function get_po_html_by_pur_request($pur_request){
    $CI = & get_instance();
    $CI->db->where('pur_request',$pur_request);
    $list = $CI->db->get(db_prefix().'pur_orders')->result_array();
    $rs = '';
    $count = 0;
    if(count($list) > 0){
        foreach($list as $li){
            $rs .= '<a href="'.admin_url('purchase/purchase_order/'.$li['id']).'" ><span class="label label-tag mbot5">'.$li['pur_order_number'].'</span></a>&nbsp;';
        }
    }
    return $rs;
}

/**
 * Gets the pur contract number.
 *
 * @param        $id     The identifier
 *
 * @return       The pur contract number.
 */
function get_pur_contract_number($id){
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $contract = $CI->db->get(db_prefix().'pur_contracts')->row();
    if($contract){
        return $contract->contract_number;
    }else{
        return '';
    }
}

/**
 * Gets the pur invoice number.
 *
 * @param        $id     The identifier
 *
 * @return     string  The pur invoice number.
 */
function get_pur_invoice_number($id){
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $inv = $CI->db->get(db_prefix().'pur_invoices')->row();
    if($inv){
        return $inv->invoice_number;
    }else{
        return '';
    }
}

/**
 * { purchase invoice left to pay }
 *
 * @param      <type>   $id     The purchase order
 *
 * @return     integer  ( purchase order left to pay )
 */
function purinvoice_left_to_pay($id)
{
    $CI = & get_instance();

    
    $CI->db->select('total')
        ->where('id', $id);
        $invoice_total = $CI->db->get(db_prefix() . 'pur_invoices')->row()->total;


    $CI->db->where('pur_invoice',$id);
    $CI->db->where('approval_status', 2);
    $payments = $CI->db->get(db_prefix().'pur_invoice_payment')->result_array();

    $debits  = $CI->purchase_model->get_applied_invoice_debits($id);

    $payments = array_merge($payments, $debits);
    
    
    $totalPayments = 0;


    foreach ($payments as $payment) {
        
        $totalPayments += $payment['amount'];
        
    }

    return ($invoice_total - $totalPayments);
}

/**
 * Gets the payment mode name by identifier.
 *
 * @param        $id     The identifier
 *
 * @return     string  The payment mode name by identifier.
 */
function get_payment_mode_name_by_id($id){
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $mode = $CI->db->get(db_prefix().'payment_modes')->row();
    if($mode){
        return $mode->name;
    }else{
        return '';
    }
}

/**
 * Gets the payment request status by inv.
 *
 * @param        $id     The identifier
 *
 * @return     string  The payment request status by inv.
 */
function get_payment_request_status_by_inv($id){
    $CI = & get_instance();
    $CI->db->where('pur_invoice',$id);
    $payments = $CI->db->get(db_prefix().'pur_invoice_payment')->result_array();
    $status = '';
    $class = '';
    if(count($payments) > 0){
        $status = 'created';
        $class = 'info';
        $CI->db->where('pur_invoice',$id);
        $CI->db->where('approval_status', 2);
        $payments_approved = $CI->db->get(db_prefix().'pur_invoice_payment')->result_array();
        if(count($payments_approved)){
            $status = 'approved';
            $class = 'success';
        }
    }else{
        $status = 'blank';
        $class = 'warning';
    }

    if($status != ''){
        return '<span class="label label-'.$class.' s-status invoice-status-3">'._l($status).'</span>';
    }else{
        return '';
    }

}

/**
 * { handle pur invoice file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean  
 */
function handle_pur_invoice_file($id, $customer_upload = false) {
    $type = 'pur_invoice';
    $path = PURCHASE_MODULE_UPLOAD_FOLDER . '/' . $type . '/' . $id . '/';
    $CI = &get_instance();
    $totalUploaded = 0;

    if (isset($_FILES['attachments']['name'])
        && ($_FILES['attachments']['name'] != '' || is_array($_FILES['attachments']['name']) && count($_FILES['attachments']['name']) > 0)) {
        if (!is_array($_FILES['attachments']['name'])) {
            $_FILES['attachments']['name'] = [$_FILES['attachments']['name']];
            $_FILES['attachments']['type'] = [$_FILES['attachments']['type']];
            $_FILES['attachments']['tmp_name'] = [$_FILES['attachments']['tmp_name']];
            $_FILES['attachments']['error'] = [$_FILES['attachments']['error']];
            $_FILES['attachments']['size'] = [$_FILES['attachments']['size']];
        }

        _file_attachments_index_fix('attachments');
        for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {

            // Get the temp file path
            $tmpFilePath = $_FILES['attachments']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES['attachments']['error'][$i])
                    || !_upload_extension_allowed($_FILES['attachments']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename = unique_filename($path, $_FILES['attachments']['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment = [];
                    $attachment[] = [
                        'file_name' => $filename,
                        'filetype' => $_FILES['attachments']['type'][$i],
                    ];

                    if ($customer_upload == true) {
                        $attachment[0]['staffid']          = 0;
                        $attachment[0]['contact_id']       = get_vendor_contact_user_id();
                        $attachment['visible_to_customer'] = 1;
                    }

                    $CI->misc_model->add_attachment_to_database($id, $type, $attachment);
                    $totalUploaded++;
                }
            }
        }
    }

    return (bool) $totalUploaded;
}

/**
 * { handle send quotation upload file  }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean   
 */
function handle_send_quotation($id){
     if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {

        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/send_quotation/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['attachment']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, str_replace(" ", "_", $_FILES['attachment']['name']));
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * { handle send quotation upload file  }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean   
 */
function handle_send_po($id){
     if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {

        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/send_po/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['attachment']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, str_replace(" ", "_", $_FILES['attachment']['name']));
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                return true;
            }
        }
    }

    return false;
}

if (!function_exists('add_purchase_email_templates')) {
    /**
     * Init appointly email templates and assign languages
     * @return void
     */
    function add_purchase_email_templates() {
        $CI = &get_instance();

        $data['purchase_templates'] = $CI->emails_model->get(['type' => 'purchase_order', 'language' => 'english']);

        $CI->load->view('purchase/email_templates', $data);
    }
}

/*
* php delete function that deals with directories recursively
*/
function delete_files_pur($target) {
    if (is_dir($target)) {
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
        foreach( $files as $file )
        {   
            if( $file != $target.'signature\\' && is_dir($file)){
                delete_dir( $file );
            }
        }

    } 
}


/**
 * { handle purchase order file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean  
 */
function handle_po_logo()
{
    if (isset($_FILES['po_logo']['name']) && $_FILES['po_logo']['name'] != '') {

        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/po_logo/'.'0/';
        // Get the temp file path
        $tmpFilePath = $_FILES['po_logo']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['po_logo']['name']);
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filename = 'po_logo_'.time().'.'.$extension;
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = & get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['po_logo']['type'],
                    ];
                $CI->misc_model->add_attachment_to_database(0, 'po_logo', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * Gets the po logo.
 */
function get_po_logo($width = 120, $class = '', $type = 'pdf'){
    $CI           = & get_instance();
    $CI->db->where('rel_id', 0);
    $CI->db->where('rel_type','po_logo');
    $logo = $CI->db->get(db_prefix().'files')->result_array();
    
    $logoUrl                   = '';
    if(count($logo) > 0){
        $logoUrl = APP_MODULES_PATH. 'purchase/uploads/po_logo/0/'.$logo[0]['file_name'];
        if($type != 'pdf'){
            $logoUrl = base_url(PURCHASE_PATH .'po_logo/0/'.$logo[0]['file_name']);
        }
    }

    $logoImage = '';
    if($logoUrl != ''){
       $logoImage = '<img style="width:' . $width . 'px" src="' . $logoUrl . '" class="'.$class.'">';
    }


    return  $logoImage;
}

/**
 * { total inv value by pur order }
 *
 * @param        $pur_order  The pur order
 *
 * @return     int     ( description of the return value )
 */
function total_inv_value_by_pur_order($pur_order){
    $CI           = & get_instance();
    $CI->db->where('pur_order', $pur_order);
    $list_inv = $CI->db->get(db_prefix().'pur_invoices')->result_array();
    $rs = 0;
    if(count($list_inv) > 0){
        foreach($list_inv as $inv){
            $rs += $inv['total'];
        }
    }
    return $rs;
}

/**
 * Gets the item identifier by description.
 *
 * @param        $des       The description
 * @param        $long_des  The long description
 *
 * @return     string  The item identifier by description.
 */
function get_item_id_by_des($des, $long_des = ''){
    $CI           = & get_instance();
    $CI->db->where('description', $des);
   
    $item = $CI->db->get(db_prefix().'items')->row();

    if($item){
        return $item->id;
    }
    return '';
}

/**
 * { purorder inv left to pay }
 *
 * @param        $pur_order  The pur order
 */
function purorder_inv_left_to_pay($pur_order){
    $CI           = & get_instance();
    $CI->load->model('purchase/purchase_model');
    $list_payment = $CI->purchase_model->get_inv_payment_purchase_order($pur_order);
    $po = $CI->purchase_model->get_pur_order($pur_order);

    $list_applied_debit = $CI->purchase_model->get_inv_debit_purchase_order($pur_order);
    $paid = 0;
    foreach($list_payment as $payment){
        if($payment['approval_status'] == 2){
            $paid += $payment['amount'];
        }
    }

    foreach($list_applied_debit as $debit){
        $paid += $debit['amount'];
    }

    if($po){
        return $po->total - $paid;
    }
    return 0;
}

/**
 * { row purcahse options exist }
 *
 * @param      <type>   $name   The name
 *
 * @return     integer  ( 1 or 0 )
 */
function row_purchase_tbl_options_exist($name) {
    $CI = &get_instance();
    $i = count($CI->db->query('Select * from ' . db_prefix() . 'options where name = ' . $name)->result_array());
    if ($i == 0) {
        return 0;
    }
    if ($i > 0) {
        return 1;
    }
}

/**
 * Gets the base currency pur.
 *
 * @return       The base currency pur.
 */
function get_base_currency_pur(){
    $CI           = & get_instance();
    $CI->load->model('currencies_model');
    $base_currency = $CI->currencies_model->get_base_currency();
    return $base_currency;
}

/**
 * Gets the arr vendors by pr.
 *
 * @param        $pur_request  The pur request
 *
 * @return     array   The arr vendors by pr.
 */
function get_arr_vendors_by_pr($pur_request){
    $CI           = & get_instance();
    $CI->load->model('purchase/purchase_model');

    $CI->db->where('pur_request', $pur_request);
    $quotes = $CI->db->get(db_prefix().'pur_estimates')->result_array();
    $arr_vendor = [];
    $arr_vendor_rs = [];
    if(count($quotes) > 0){
        foreach($quotes as $quote){
            if(!in_array($quote['vendor'], $arr_vendor)){
                $arr_vendor[] = $quote['vendor'];
                $arr_vendor_rs[] = $CI->purchase_model->get_vendor($quote['vendor']);
            }
        }
    }
    return $arr_vendor_rs;
}

/**
 * Gets the quotations by pur request.
 */
function get_quotations_by_pur_request($pur_request){
    $CI           = & get_instance();

    $CI->db->where('pur_request', $pur_request);
    $quotes = $CI->db->get(db_prefix().'pur_estimates')->result_array();
    return $quotes;
}

/**
 * Gets the item detail in quote.
 *
 * @param        $item           The item
 * @param        $pur_estimates  The pur estimates
 */
function get_item_detail_in_quote($item, $pur_estimates){
    $CI           = & get_instance();
    $CI->db->where('pur_estimate', $pur_estimates);
    $CI->db->where('item_code', $item);
    $item_row = $CI->db->get(db_prefix().'pur_estimate_detail')->row();
    return $item_row;
}

/**
 * Gets the purchase request item taxes.
 *
 * @param       $itemid  The itemid
 *
 * @return       The invoice item taxes.
 */
function get_debit_note_item_taxes($itemid)
{
    $CI = &get_instance();
    $CI->db->where('itemid', $itemid);
    $CI->db->where('rel_type', 'debit_note');
    $taxes = $CI->db->get(db_prefix() . 'item_tax')->result_array();
    $i     = 0;
    foreach ($taxes as $tax) {
        $taxes[$i]['taxname'] = $tax['taxname'] . '|' . $tax['taxrate'];
        $i++;
    }

    return $taxes;
}

/**
 * Function that format credit note number based on the prefix option and the credit note number
 * @param  mixed $id credit note id
 * @return string
 */
function format_debit_note_number($id)
{
    $CI = & get_instance();
    $CI->db->select('date,number,prefix,number_format')
    ->from(db_prefix() . 'pur_debit_notes')
    ->where('id', $id);
    $debit_note = $CI->db->get()->row();

    if (!$debit_note) {
        return '';
    }

    $number = sales_number_format($debit_note->number, $debit_note->number_format, $debit_note->prefix, $debit_note->date);

    return $number;
}

/**
 * Format debit note status
 * @param  mixed  $status credit note current status
 * @param  boolean $text   to return text or with applied styles
 * @return string
 */
function format_debit_note_status($status, $text = false)
{
    $CI = &get_instance();
    if (!class_exists('purchase_model')) {
        $CI->load->model('purchase/purchase_model');
    }

    $statuses    = $CI->purchase_model->get_debit_note_statuses();
    $statusArray = false;
    foreach ($statuses as $s) {
        if ($s['id'] == $status) {
            $statusArray = $s;

            break;
        }
    }

    if (!$statusArray) {
        return $status;
    }

    if ($text) {
        return $statusArray['name'];
    }

    $style = 'border: 1px solid ' . $statusArray['color'] . ';color:' . $statusArray['color'] . ';';
    $class = 'label s-status';

    return '<span class="' . $class . '" style="' . $style . '">' . $statusArray['name'] . '</span>';
}

/**
     * Format vendor address info
     * @param  object  $data        vendor object from database
     * @param  string  $for         where this format will be used? Eq statement invoice etc
     * @param  string  $type        billing/shipping
     * @param  boolean $companyLink company link to be added on vendor company/name, this is used in admin area only
     * @return string
     */
    function format_vendor_info($data, $for, $type, $companyLink = false)
    {
        $format   = get_option('customer_info_format');
        $vendorId = '';

        if ($for == 'statement') {
            $vendorId = $data->userid;
        } elseif ($type == 'billing') {
            $vendorId = $data->vendorid;
        }

        $filterData = [
            'data'         => $data,
            'for'          => $for,
            'type'         => $type,
            'client_id'    => $vendorId,
            'company_link' => $companyLink,
        ];

        $companyName = '';
        if ($for == 'statement') {
            $companyName = get_vendor_company_name($vendorId);
        } elseif ($type == 'billing') {
            $companyName = $data->vendor->company;
        }

        $acceptsPrimaryContactDisplay = ['debit_note'];

        $street  = in_array($type, ['billing', 'shipping']) ? $data->{$type . '_street'} : '';
        $city    = in_array($type, ['billing', 'shipping']) ? $data->{$type . '_city'} : '';
        $state   = in_array($type, ['billing', 'shipping']) ? $data->{$type . '_state'} : '';
        $zipCode = in_array($type, ['billing', 'shipping']) ? $data->{$type . '_zip'} : '';

        $countryCode = '';
        $countryName = '';

        if ($country = in_array($type, ['billing', 'shipping']) ? get_country($data->{$type . '_country'}) : '') {
            $countryCode = $country->iso2;
            $countryName = $country->short_name;
        }

        $phone = '';
        if ($for == 'statement' && isset($data->phonenumber)) {
            $phone = $data->phonenumber;
        } elseif ($type == 'billing' && isset($data->client->phonenumber)) {
            $phone = $data->client->phonenumber;
        }

        $vat = '';
        if ($for == 'statement' && isset($data->vat)) {
            $vat = $data->vat;
        } elseif ($type == 'billing' && isset($data->client->vat)) {
            $vat = $data->client->vat;
        }

        if ($companyLink && (!isset($data->deleted_customer_name) ||
            (isset($data->deleted_customer_name) &&
                empty($data->deleted_customer_name)))) {
            $companyName = '<a href="' . admin_url('purchase/vendor/' . $vendorId) . '" target="_blank"><b>' . $companyName . '</b></a>';
        } elseif ($companyName != '') {
            $companyName = '<b>' . $companyName . '</b>';
        }

        $format = _info_format_replace('company_name', $companyName, $format);
        $format = _info_format_replace('customer_id', $vendorId, $format);
        $format = _info_format_replace('street', $street, $format);
        $format = _info_format_replace('city', $city, $format);
        $format = _info_format_replace('state', $state, $format);
        $format = _info_format_replace('zip_code', $zipCode, $format);
        $format = _info_format_replace('country_code', $countryCode, $format);
        $format = _info_format_replace('country_name', $countryName, $format);
        $format = _info_format_replace('phone', $phone, $format);
        $format = _info_format_replace('vat_number', $vat, $format);
        $format = _info_format_replace('vat_number_with_label', $vat == '' ? '' : _l('client_vat_number') . ': ' . $vat, $format);


        // Remove multiple white spaces
        $format = preg_replace('/\s+/', ' ', $format);
        $format = trim($format);

        return hooks()->apply_filters('customer_info_text', $format, $filterData);
    }

/**
 * Prepare general debit note pdf
 * @param  object $debit_note Debit note as object with all necessary fields
 * @return mixed object
 */
function debit_note_pdf($debit_note)
{
    return app_pdf('debit_note', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Debit_note_pdf'), $debit_note);
}

/**
 * Return debit note status color RGBA for pdf
 * @param  mixed $status_id current credit note status id
 * @return string
 */
function debit_note_status_color_pdf($status_id)
{
    $statusColor = '';

    if ($status_id == 1) {
        $statusColor = '3, 169, 244';
    } elseif ($status_id == 2) {
        $statusColor = '132, 197, 41';
    } else {
        // Status VOID
        $statusColor = '119, 119, 119';
    }

    return $statusColor;
}

/**
 * Check if debit can be applied to invoice based on the invoice status
 * @param  mixed $status_id invoice status id
 * @return boolean
 */
function debits_can_be_applied_to_invoice($status)
{
    if(in_array($status, ["unpaid", "partially_paid"]) ){
        return true;
    }
    return false;
}

/**
 * Prepare customer statement pdf
 * @param  object $statement statement
 * @return mixed
 */
function purchase_statement_pdf($statement)
{
    return app_pdf('vendor_statement', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Vendor_statement_pdf'), $statement);
}

/**
 * purchase get staff id hr permissions
 * @return [type] 
 */
function purchase_get_staff_id_permissions()
{
    $CI = & get_instance();
    $array_staff_id = [];
    $index=0;

    $str_permissions ='';
    foreach (list_purchase_permisstion() as $per_key =>  $per_value) {
        if(strlen($str_permissions) > 0){
            $str_permissions .= ",'".$per_value."'";
        }else{
            $str_permissions .= "'".$per_value."'";
        }

    }


    $sql_where = "SELECT distinct staff_id FROM ".db_prefix()."staff_permissions
    where feature IN (".$str_permissions.")
    ";
    
    $staffs = $CI->db->query($sql_where)->result_array();

    if(count($staffs)>0){
        foreach ($staffs as $key => $value) {
            $array_staff_id[$index] = $value['staff_id'];
            $index++;
        }
    }
    return $array_staff_id;
}


/**
 * list purchase permisstion
 * @return [type] 
 */
function list_purchase_permisstion()
{
    $hr_profile_permissions=[];
    $hr_profile_permissions[]='purchase_items';
    $hr_profile_permissions[]='purchase_vendors';
    $hr_profile_permissions[]='purchase_vendor_items';
    $hr_profile_permissions[]='purchase_request';
    $hr_profile_permissions[]='purchase_quotations';
    $hr_profile_permissions[]='purchase_orders';
    $hr_profile_permissions[]='purchase_faf';
    $hr_profile_permissions[]='purchase_order_return';
    $hr_profile_permissions[]='purchase_contracts';
    $hr_profile_permissions[]='purchase_invoices';
    $hr_profile_permissions[]='purchase_reports';
    $hr_profile_permissions[]='purchase_debit_notes';
    $hr_profile_permissions[]='purchase_settings';
    $hr_profile_permissions[]='purchase_order_change_approve_status';
    $hr_profile_permissions[]='purchase_estimate_change_approve_status';
    $hr_profile_permissions[]='purchase_request_change_approve_status';
    $hr_profile_permissions[]='purchase_invoice_change_approve_status';


    return $hr_profile_permissions;
}

/**
 * purchase get staff id dont permissions
 * @return [type] 
 */
function purchase_get_staff_id_dont_permissions()
{
    $CI = & get_instance();

    $CI->db->where('admin != ', 1);

    if(count(purchase_get_staff_id_permissions()) > 0){
        $CI->db->where_not_in('staffid', purchase_get_staff_id_permissions());
    }
    return $CI->db->get(db_prefix().'staff')->result_array();
    
}

function check_valid_number_with_setting($number){
    $decimal_separator = get_option('decimal_separator');
    $thousand_separator = get_option('thousand_separator');

    $decimal_separator_index = strpos($number, $decimal_separator);
    $thousand_separator_index = strpos($number, $thousand_separator);

    if($decimal_separator_index == false || $thousand_separator_index == false){
        return true;
    }

    if($decimal_separator_index <= $thousand_separator_index){
        return false;
    }

    return true; 
}

function pur_convert_item_taxes($tax, $tax_rate, $tax_name)
{
    /*taxrate taxname
    5.00    TAX5
    id      rate        name
    2|1 ; 6.00|10.00 ; TAX5|TAX10%*/
    $CI           = & get_instance();
    $taxes = [];
    if($tax != null && strlen($tax) > 0){
        $arr_tax_id = explode('|', $tax);
        if($tax_name != null && strlen($tax_name) > 0){
            $arr_tax_name = explode('|', $tax_name);
            $arr_tax_rate = explode('|', $tax_rate);
            foreach ($arr_tax_name as $key => $value) {
                $taxes[]['taxname'] = $value . '|' .  $arr_tax_rate[$key];
            }
        }elseif($tax_rate != null && strlen($tax_rate) > 0){
            $CI->load->model('purchase/purchase_model');
            $arr_tax_id = explode('|', $tax);
            $arr_tax_rate = explode('|', $tax_rate);
            foreach ($arr_tax_id as $key => $value) {
                $_tax_name = $CI->purchase_model->get_tax_name($value);
                if(isset($arr_tax_rate[$key])){
                    $taxes[]['taxname'] = $_tax_name . '|' .  $arr_tax_rate[$key];
                }else{
                    $taxes[]['taxname'] = $_tax_name . '|' .  $CI->purchase_model->tax_rate_by_id($value);

                }
            }
        }else{
            $CI->load->model('purchase/purchase_model');
            $arr_tax_id = explode('|', $tax);
            $arr_tax_rate = explode('|', $tax_rate);
            foreach ($arr_tax_id as $key => $value) {
                $_tax_name = $CI->purchase_model->get_tax_name($value);
                $_tax_rate = $CI->purchase_model->tax_rate_by_id($value);
                $taxes[]['taxname'] = $_tax_name . '|' .  $_tax_rate;
            } 
        }

    }

    return $taxes;
}

/**
 * pur get unit name
 * @param  boolean $id 
 * @return [type]      
 */
function pur_get_unit_name($id = false)
{
    $CI           = & get_instance();
    if (is_numeric($id)) {
        $CI->db->where('unit_type_id', $id);

        $unit = $CI->db->get(db_prefix() . 'ware_unit_type')->row();
        if($unit){
            return $unit->unit_name;
        }
        return '';
    }
}

/**
 * wh get item variatiom
 * @param  [type] $id 
 * @return [type]     
 */
function pur_get_item_variatiom($id)
{
    $CI           = & get_instance();

    $CI->db->where('id', $id);
    $item_value = $CI->db->get(db_prefix() . 'items')->row();

    $name = '';
    if($item_value){
        $CI->load->model('purchase/purchase_model');
        $new_item_value = $CI->purchase_model->row_item_to_variation($item_value);

        $name .= $item_value->commodity_code.'_'.$new_item_value->new_description;
    }

    return $name;
}

/**
 * Function that return invoice item taxes based on passed item id
 * @param  mixed $itemid
 * @return array
 */
function pur_get_invoice_item_taxes($itemid)
{
    $CI = &get_instance();
    $CI->db->where('itemid', $itemid);
    $CI->db->where('rel_type', 'invoice');
    $taxes = $CI->db->get(db_prefix() . 'item_tax')->result_array();
    $i     = 0;
 

    return $taxes;
}

/**
 * { handle item password file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean
 */
function handle_vendor_item_attachment($id) {

    $path = PURCHASE_MODULE_UPLOAD_FOLDER . '/vendor_items/' . $id . '/';
    $CI = &get_instance();
    $totalUploaded = 0;

    if (isset($_FILES['attachments']['name'])
        && ($_FILES['attachments']['name'] != '' || is_array($_FILES['attachments']['name']) && count($_FILES['attachments']['name']) > 0)) {
        if (!is_array($_FILES['attachments']['name'])) {
            $_FILES['attachments']['name'] = [$_FILES['attachments']['name']];
            $_FILES['attachments']['type'] = [$_FILES['attachments']['type']];
            $_FILES['attachments']['tmp_name'] = [$_FILES['attachments']['tmp_name']];
            $_FILES['attachments']['error'] = [$_FILES['attachments']['error']];
            $_FILES['attachments']['size'] = [$_FILES['attachments']['size']];
        }

        _file_attachments_index_fix('attachments');
        for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {

            // Get the temp file path
            $tmpFilePath = $_FILES['attachments']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES['attachments']['error'][$i])
                    || !_upload_extension_allowed($_FILES['attachments']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename = unique_filename($path, $_FILES['attachments']['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment = [];
                    $attachment[] = [
                        'file_name' => $filename,
                        'filetype' => $_FILES['attachments']['type'][$i],
                    ];

                    $CI->misc_model->add_attachment_to_database($id, 'vendor_items', $attachment);
                    $totalUploaded++;
                }
            }
        }
    }

    return (bool) $totalUploaded;
}

/**
 * { vendor item images }
 *
 * @param        $item_id  The item identifier
 */
function vendor_item_images($item_id){
    $CI = &get_instance();

    $CI->db->order_by('dateadded', 'desc');
    $CI->db->where('rel_id', $item_id);
    $CI->db->where('rel_type', 'vendor_items');

    return $CI->db->get(db_prefix() . 'files')->result_array();
}

/**
 * get tax rate
 * @param  integer $id
 * @return array or row
 */
function pur_get_tax_rate($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'taxes')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tbltaxes')->result_array();
    }

}

/**
 * Purchase get currency name symbol
 * @param  [type] $id     
 * @param  string $column 
 * @return [type]         
 */
function pur_get_currency_name_symbol($id, $column='')
{
    $CI   = & get_instance();
    $currency_value='';

    if($column == ''){
        $column = 'name';
    }

    $CI->db->select($column);
    $CI->db->from(db_prefix() . 'currencies');
    $CI->db->where('id', $id);
    $currency = $CI->db->get()->row();
    if($currency){
        $currency_value = $currency->$column;
    }

    return $currency_value;
}

/**
 * get currency rate
 * @param  [type] $from
 * @param  [type] $to
 * @return [type]           
 */
function pur_get_currency_rate($from, $to)
{
    $CI   = & get_instance();
    if($from == $to){
        return 1;
    }

    $amount_after_convertion = 1;

    $CI->db->where('from_currency_name', strtoupper($from));
    $CI->db->where('to_currency_name', strtoupper($to));
    $currency_rates = $CI->db->get(db_prefix().'currency_rates')->row();
    
    if($currency_rates){
        $amount_after_convertion = $currency_rates->to_currency_rate;
    }

    return $amount_after_convertion;
}


/**
 * { pur get currency by id }
 *
 * @param        $id     The identifier
 */
function pur_get_currency_by_id($id){
    $CI   = & get_instance();

    $CI->db->where('id', $id);
    return  $CI->db->get(db_prefix().'currencies')->row();
}

/**
 * Gets the vendor currency.
 *
 * @param        $vendor_id  The vendor identifier
 */
function get_vendor_currency($vendor_id){
    $CI   = & get_instance();

    $CI->db->where('userid', $vendor_id);
    $vendor = $CI->db->get(db_prefix().'pur_vendor')->row();

    if($vendor){
        return $vendor->default_currency;
    }
    return 0;
}

function get_invoice_currency_id($invoice_id){
    $CI   = & get_instance();
    $CI->db->where('id', $invoice_id);
    $invoice = $CI->db->get(db_prefix().'pur_invoices')->row();
    if($invoice){
        return $invoice->currency;
    }
    return 0;
}

/**
 * Client attachments
 * @param  mixed $clientid Client ID to add attachments
 * @return array  - Result values
 */
function handle_vendor_po_attachments_upload($id, $customer_upload = false, $purorder='')
{
    $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $purorder . '/';
    $CI            = & get_instance();
    $totalUploaded = 0;

    if (isset($_FILES['file']['name'])
        && ($_FILES['file']['name'] != '' || is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0)) {
        if (!is_array($_FILES['file']['name'])) {
            $_FILES['file']['name']     = [$_FILES['file']['name']];
            $_FILES['file']['type']     = [$_FILES['file']['type']];
            $_FILES['file']['tmp_name'] = [$_FILES['file']['tmp_name']];
            $_FILES['file']['error']    = [$_FILES['file']['error']];
            $_FILES['file']['size']     = [$_FILES['file']['size']];
        }

        _file_attachments_index_fix('file');
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES['file']['error'][$i])
                    || !_upload_extension_allowed($_FILES['file']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES['file']['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment   = [];
                    $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'][$i],
                    ];

                    if (is_image($newFilePath)) {
                        create_img_thumb($newFilePath, $filename);
                    }

                    if ($customer_upload == true) {
                        $attachment[0]['staffid']          = 0;
                        $attachment[0]['contact_id']       = get_vendor_contact_user_id();
                        $attachment['visible_to_customer'] = 1;
                    }

                    $CI->misc_model->add_attachment_to_database($purorder, 'pur_order', $attachment);
                    $totalUploaded++;
                }
            }
        }
    }

    return (bool) $totalUploaded;
}

/**
 * Client attachments
 * @param  mixed $clientid Client ID to add attachments
 * @return array  - Result values
 */
function handle_vendor_estimate_attachments_upload($id, $customer_upload = false, $purorder='')
{
    $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_estimate/'. $purorder . '/';
    $CI            = & get_instance();
    $totalUploaded = 0;

    if (isset($_FILES['file']['name'])
        && ($_FILES['file']['name'] != '' || is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0)) {
        if (!is_array($_FILES['file']['name'])) {
            $_FILES['file']['name']     = [$_FILES['file']['name']];
            $_FILES['file']['type']     = [$_FILES['file']['type']];
            $_FILES['file']['tmp_name'] = [$_FILES['file']['tmp_name']];
            $_FILES['file']['error']    = [$_FILES['file']['error']];
            $_FILES['file']['size']     = [$_FILES['file']['size']];
        }

        _file_attachments_index_fix('file');
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES['file']['error'][$i])
                    || !_upload_extension_allowed($_FILES['file']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES['file']['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment   = [];
                    $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'][$i],
                    ];

                    if (is_image($newFilePath)) {
                        create_img_thumb($newFilePath, $filename);
                    }

                    if ($customer_upload == true) {
                        $attachment[0]['staffid']          = 0;
                        $attachment[0]['contact_id']       = get_vendor_contact_user_id();
                        $attachment['visible_to_customer'] = 1;
                    }

                    $CI->misc_model->add_attachment_to_database($purorder, 'pur_estimate', $attachment);
                    $totalUploaded++;
                }
            }
        }
    }

    return (bool) $totalUploaded;
}

/**
 * wh check approval setting
 * @param  integer $type 
 * @return [type]       
 */
function pur_check_approval_setting($type)
{   
    $CI = &get_instance();
    $CI->load->model('purchase/purchase_model');

    $check_appr = $CI->purchase_model->get_approve_setting($type);

    return $check_appr;
}

/**
 * Gets the warehouse option.
 *
 * @param      <type>        $name   The name
 *
 * @return     array|string  The warehouse option.
 */
function get_purchase_option_v2($name)
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
 * get commodity name
 * @param  integer $id
 * @return array or row
 */
function pur_get_commodity_name($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'items')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblitems')->result_array();
    }

}

/**
 * wh render taxes html
 * @param  [type] $item_tax 
 * @param  [type] $width    
 * @return [type]           
 */
function pur_render_taxes_html($item_tax, $width)
{
    $itemHTML = '';
    $itemHTML .= '<td align="right" width="' . $width . '%">';

    if(is_array($item_tax) && isset($item_tax)){
        if (count($item_tax) > 0) {
            foreach ($item_tax as $tax) {

                $item_tax = '';
                if ( get_option('remove_tax_name_from_item_table') == false || multiple_taxes_found_for_item($item_tax)) {
                    $tmp      = explode('|', $tax['taxname']);
                    $item_tax = $tmp[0] . ' ' . app_format_number($tmp[1]) . '%<br />';
                } else {
                    $item_tax .= app_format_number($tax['taxrate']) . '%';
                }
                $itemHTML .= $item_tax;
            }
        } else {
            $itemHTML .=  app_format_number(0) . '%';
        }
    }
    $itemHTML .= '</td>';

    return $itemHTML;
}

/**
 * Function that format task status for the final user
 * @param  string  $id    status id
 * @param  boolean $text
 * @param  boolean $clean
 * @return string
 */
function pur_format_approve_status($status, $text = false, $clean = false)
{

    $status_name = '';
    if($status == 1){
        $status_name = _l('purchase_draft');
    }elseif($status == 2){
        $status_name = _l('purchase_approved');
    }elseif($status == 3){
        $status_name = _l('pur_rejected');
    }elseif($status == 4){
        $status_name = _l('pur_canceled');
    }

    if ($clean == true) {
        return $status_name;
    }

    $style = '';
    $class = '';
    if ($text == false) {
        if($status == 1){
            $class = 'label label-primary';
        }elseif($status == 2){
            $class = 'label label-success';
        }elseif($status == 3){
            $class = 'label label-warning';
        }elseif($status == 4){
            $class = 'label label-danger';
        }
    } else {
        if($status == 1){
            $class = 'label text-info';
        }elseif($status == 2){
            $class = 'label text-success';
        }elseif($status == 3){
            $class = 'label text-warning';
        }elseif($status == 4){
            $class = 'label text-danger';
        }
    }    

    return '<span class="' . $class . '" >' . $status_name . '</span>';
}


/**
 * Client attachments
 * @param  mixed $clientid Client ID to add attachments
 * @return array  - Result values
 */
function handle_vendor_pr_attachments_upload($id, $customer_upload = false, $purorder='')
{
    $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_request/'. $purorder . '/';
    $CI            = & get_instance();
    $totalUploaded = 0;

    if (isset($_FILES['file']['name'])
        && ($_FILES['file']['name'] != '' || is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0)) {
        if (!is_array($_FILES['file']['name'])) {
            $_FILES['file']['name']     = [$_FILES['file']['name']];
            $_FILES['file']['type']     = [$_FILES['file']['type']];
            $_FILES['file']['tmp_name'] = [$_FILES['file']['tmp_name']];
            $_FILES['file']['error']    = [$_FILES['file']['error']];
            $_FILES['file']['size']     = [$_FILES['file']['size']];
        }

        _file_attachments_index_fix('file');
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES['file']['error'][$i])
                    || !_upload_extension_allowed($_FILES['file']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES['file']['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment   = [];
                    $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'][$i],
                    ];

                    if (is_image($newFilePath)) {
                        create_img_thumb($newFilePath, $filename);
                    }

                    if ($customer_upload == true) {
                        $attachment[0]['staffid']          = 0;
                        $attachment[0]['contact_id']       = get_vendor_contact_user_id();
                        $attachment['visible_to_customer'] = 1;
                    }

                    $CI->misc_model->add_attachment_to_database($purorder, 'pur_request', $attachment);
                    $totalUploaded++;
                }
            }
        }
    }

    return (bool) $totalUploaded;
}

/**
 * Gets the total order return refunded.
 *
 * @param        $order_return  The order return
 *
 * @return     int     The total order return refunded.
 */
function get_total_order_return_refunded($order_return)
{
    $CI            = & get_instance();
    $CI->db->where('order_return_id', $order_return);
    $refunds = $CI->db->get(db_prefix().'wh_order_returns_refunds')->result_array();

    $total_refunded = 0;
    if(count($refunds) > 0){
        foreach ($refunds as $key => $refund) {
            $total_refunded += $refund['amount'];
        }
    }

    return $total_refunded;
}

function get_order_return_remaining_refund($order_return){
    $CI            = & get_instance();
    $CI->load->model('purchase/purchase_model');

    $order = $CI->purchase_model->get_order_return($order_return);

    $vendor = $CI->purchase_model->get_vendor($order->company_id);

    $remaining_refund = 0;
    $total_refunded = get_total_order_return_refunded($order_return);

    $remaining_refund = $order->total_after_discount - $total_refunded;

    return $remaining_refund;

}

function get_object_comment($rel_id, $rel_type){
    $CI            = & get_instance();
    $table = '';
    if($rel_type == 'pur_order'){
        $table = db_prefix().'pur_orders';
    }else if($rel_type == 'pur_quotation'){
        $table = db_prefix().'pur_estimates';
    }else if($rel_type == 'pur_contract'){
        $table = db_prefix().'pur_contracts';
    }else if($rel_type == 'pur_invoice'){
        $table = db_prefix().'pur_invoices';
    }

    $CI->db->where('id', $rel_id);
    $object = $CI->db->get($table)->row();

    return $object;
}

if(!function_exists('pur_get_primary_contact_user_id')){
    /**
     * Get primary contact user id for specific customer
     * @param  mixed $userid
     * @return mixed
     */
    function pur_get_primary_contact_user_id($userid)
    {
        $CI = &get_instance();
        $CI->db->where('userid', $userid);
        $CI->db->where('is_primary', 1);
        $row = $CI->db->get(db_prefix() . 'pur_contacts')->row();

        if ($row) {
            return $row->id;
        }

        return false;
    }
}

/**
 * Gets the vendor language by email.
 */
function get_vendor_language_by_email($email){
    $CI = &get_instance();
    $CI->db->where('email', $email);
    $contact = $CI->db->get(db_prefix().'pur_contacts')->row();

    if($contact){
        $CI->db->where('userid', $contact->userid);
        $vendor = $CI->db->get(db_prefix().'pur_vendor')->row();
        if($vendor){
            return $vendor->default_language;
        }
    }

    return '';
}

/**
 * Send contract signed notification to staff members
 *
 * @param  int $contract_id
 *
 * @return void
 */
function pur_send_contract_signed_notification_to_staff($contract_id)
{
    $CI = &get_instance();
    $CI->db->where('id', $contract_id);
    $contract = $CI->db->get(db_prefix() . 'pur_contracts')->row();

    if (!$contract) {
        return false;
    }

    // Get creator
    $CI->db->select('staffid, email, phonenumber');
    $CI->db->where('staffid', $contract->add_from);
    $staff_contract = $CI->db->get(db_prefix() . 'staff')->result_array();

    $notifiedUsers = [];

    foreach ($staff_contract as $member) {
        $notified = add_notification([
            'description'     => 'not_contract_signed',
            'touserid'        => $member['staffid'],
            'fromcompany'     => 1,
            'fromuserid'      => 0,
            'link'            => 'purchase/contract/' . $contract->id,
            'additional_data' => serialize([
                '<b>' . $contract->contract_number . '</b>',
            ]),
        ]);

        if ($notified) {
            array_push($notifiedUsers, $member['staffid']);
        }

        if(get_option('sms_notification_when_vendor_sign_contract') == 1){
            if($member['phonenumber'] != ''){
                $CI->load->model('purchase/purchase_model');
                $message = _l('purchase_contract').' '.$contract->contract_number.' '._l('has_been_signed_by').' '.$contract->acceptance_firstname.' '.$contract->acceptance_lastname.'('.$contract->acceptance_email.')';

                $success = $CI->purchase_model->sendSMS($message, $member['phonenumber']);

            }
        }

        $data_sm = [];
        $data_sm['mail_to'] = $member['email'];
        $data_sm['contract_id'] = $contract_id;

       
        $template = mail_template('purchase_contract_is_signed', 'purchase', array_to_object($data_sm));
        $template->send();
    }

    pusher_trigger_notification($notifiedUsers);
}

/**
 * Gets the vendor language.
 */
function get_vendor_language(){
    $vendor_id = get_vendor_user_id();
    $CI = &get_instance();
    $CI->db->where('userid', $vendor_id);
    $vendor = $CI->db->get(db_prefix().'pur_vendor')->row();

    if($vendor){
        return $vendor->default_language;
    }

    return '';
}

/**
 * { pur_pur_html_entity_decode }
 *
 * @param      string  $str    The string
 *
 * @return     <string>  
 */
function pur_html_entity_decode($str){
    return html_entity_decode($str ?? '');
}

/**
 * Render date picker input for admin area
 * @param  [type] $name             input name
 * @param  string $label            input label
 * @param  string $value            default value
 * @param  array  $input_attrs      input attributes
 * @param  array  $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string $form_group_class form group div wrapper additional class
 * @param  string $input_class      <input> additional class
 * @return string
 */
function pur_render_date_input($name, $label = '', $value = '', $input_attrs = [], $form_group_attr = [], $form_group_class = '', $input_class = '')
{
    $input            = '';
    $_form_group_attr = '';
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }

    $_input_attrs = rtrim($_input_attrs);

    $form_group_attr['app-field-wrapper'] = $name;

    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '" ';
    }

    $_form_group_attr = rtrim($_form_group_attr);

    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    if (!empty($input_class)) {
        $input_class = ' ' . $input_class;
    }
    $input .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>';
    if ($label != '') {
        $input .= '<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }
    $input .= '<div class="input-group date">';
    $input .= '<input type="text" id="' . $name . '" name="' . $name . '" class="form-control datepicker' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '" autocomplete="off">';
    $input .= '<div class="input-group-addon">
    <i class="fa fa-regular fa-calendar calendar-icon"></i>
</div>';
    $input .= '</div>';
    $input .= '</div>';

    return $input;
}

/**
 * Gets the vendor admin list.
 *
 * @param        $staffid  The staffid
 */
function get_vendor_admin_list($staffid){

    $CI = &get_instance();
    $CI->db->where('staff_id', $staffid);
    $list = $CI->db->get(db_prefix().'pur_vendor_admin')->result_array();

    $vendor_ids = [];
    if(count($list) > 0){
        foreach($list as $row){
            if(!in_array($row['vendor_id'], $vendor_ids)){
                $vendor_ids[] = $row['vendor_id'];
            }
        }
    }

    return $vendor_ids;

}

/**
 * { pur_check_csrf_protection }
 *
 * @return     string  (  )
 */
function pur_check_csrf_protection()
{
    if(config_item('csrf_protection')){
        return 'true';
    }
    return 'false';
}

/**
 * [handle_upload_sign_image description]
 * @return [type] [description]
 */
function handle_upload_sign_image($rel_id, $rel_type, $approve_id){
    if (isset($_FILES['sign_attachment']['name']) && $_FILES['sign_attachment']['name'] != '') {

        $folder = '';
        if($rel_type == 'pur_order'){
            $folder = 'pur_order/signature';
        }else if($rel_type == 'pur_request'){
            $folder = 'pur_request/signature';
        }else if($rel_type == 'pur_quotation'){
            $folder = 'pur_estimate/signature';
        }else if($rel_type == 'purchase_invoice'){
            $folder = 'pur_invoice/signature';
        }else if($rel_type == 'payment_request'){
            $folder = 'payment_invoice/signature';
        }else if($rel_type == 'faf_requests'){
            $folder = 'faf_requests/signature';
        }

        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/'.$folder.'/'.$rel_id.'/';
        
        $tmpFilePath = $_FILES['sign_attachment']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['sign_attachment']['name']);
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filename = 'signature_'.$approve_id.'.'.$extension;
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                
                return true;
            }
        }
    }

    return false;
}

/**
 * Gets the status approve string.
 *
 * @param      integer  $status  The status
 *
 * @return     string   The status approve string.
 */
function pur_get_delivery_status_str($status){
    $result = '';
    if($status == 0){
        $result = _l('undelivered');
    }elseif($status == 1){
        $result = _l('completely_delivered');
    }elseif($status == 2){
        $result = _l('pending_delivered');
    }elseif($status == 3){
        $result = _l('partially_delivered');
    }

    return $result;

}

/**
 * [pur_get_job_position description]
 * @return [type] [description]
 */
function pur_get_job_position($staffid){
    $CI = &get_instance();
    $CI->load->model('staff_model');

    $staff = $CI->staff_model->get($staffid);

    $position_str = '';

    if(isset($staff->job_position)){
        if(function_exists('hr_profile_job_name_by_id')){
            $position_str = hr_profile_job_name_by_id($staff->job_position);    
        }else{
            $CI->db->where('position_id', $staff->job_position);
            $CI->db->select('position_name');
            $dpm = $CI->db->get(db_prefix().'hr_job_position')->row();
            if($dpm){
                $position_str = $dpm->position_name; 
            }
        }

        return $position_str; 
    }

    if(isset($staff->role) && is_numeric($staff->role) && $staff->role > 0){
        $CI->db->where('roleid', $staff->role);
        $role = $CI->db->get(db_prefix().'roles')->row();

        if(isset($role->name)){
            $position_str = $role->name;
        }
    }

    return $position_str; 

}

/**
 * [pur_get_pur_request_code description]
 * @return [type] [description]
 */
function pur_get_pur_request_code($request_id){
    if(!is_numeric($request_id) || $request_id == 0){
        return _l('no_pr_found');
    }
    $CI = &get_instance();

    $CI->db->select('pur_rq_code');
    $CI->db->where('id', $request_id);
    $pur_request = $CI->db->get(db_prefix().'pur_request')->row();
    if(isset($pur_request->pur_rq_code)){
        return $pur_request->pur_rq_code;
    }
    return _l('no_pr_found');
}

if ( ! function_exists('force_download'))
{
    /**
     * Force Download
     *
     * Generates headers that force a download to happen
     *
     * @param   string  filename
     * @param   mixed   the data to be downloaded
     * @param   bool    whether to try and send the actual file MIME type
     * @return  void
     */
    function force_download($filename = '', $data = '', $set_mime = FALSE)
    {
        if ($filename === '' OR $data === '')
        {
            return;
        }
        elseif ($data === NULL)
        {
            if ( ! @is_file($filename) OR ($filesize = @filesize($filename)) === FALSE)
            {
                return;
            }

            $filepath = $filename;
            $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
            $filename = end($filename);
        }
        else
        {
            $filesize = strlen($data);
        }

        // Set the default MIME type to send
        $mime = 'application/octet-stream';

        $x = explode('.', $filename);
        $extension = end($x);

        if ($set_mime === TRUE)
        {
            if (count($x) === 1 OR $extension === '')
            {
                /* If we're going to detect the MIME type,
                 * we'll need a file extension.
                 */
                return;
            }

            // Load the mime types
            $mimes =& get_mimes();

            // Only change the default MIME if we can find one
            if (isset($mimes[$extension]))
            {
                $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
            }
        }

        /* It was reported that browsers on Android 2.1 (and possibly older as well)
         * need to have the filename extension upper-cased in order to be able to
         * download it.
         *
         * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
         */
        if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT']))
        {
            $x[count($x) - 1] = strtoupper($extension);
            $filename = implode('.', $x);
        }

        if ($data === NULL && ($fp = @fopen($filepath, 'rb')) === FALSE)
        {
            return;
        }

        // Clean output buffer
        if (ob_get_level() !== 0 && @ob_end_clean() === FALSE)
        {
            @ob_clean();
        }

        // Generate the server headers
        header('Content-Type: '.$mime);
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.$filesize);
        header('Cache-Control: private, no-transform, no-store, must-revalidate');

        // If we have raw data - just dump it
        if ($data !== NULL)
        {
            exit($data);
        }

        // Flush 1MB chunks of data
        while ( ! feof($fp) && ($data = fread($fp, 1048576)) !== FALSE)
        {
            echo $data;
        }

        fclose($fp);
        exit;
    }
}


/**
 * Function used to get related data based on rel_id and rel_type
 * Eq in the tasks section there is field where this task is related eq invoice with number INV-0005
 * @param  string $type
 * @param  string $rel_id
 * @param  array $extra
 * @return mixed
 */
function pur_get_relation_data($type, $rel_id = '', $extra = [])
{
    $CI = & get_instance();
    $q  = '';
    if ($CI->input->post('q')) {
        $q = $CI->input->post('q');
        $q = trim($q);
    }

    $data = [];
    if ($type == 'customer' || $type == 'customers') {
        $where_clients = '';

        if ($q && !$rel_id) {
            $where_clients .= '(company LIKE "%' . $CI->db->escape_like_str($q) . '%" ESCAPE \'!\' OR CONCAT(firstname, " ", lastname) LIKE "%' . $CI->db->escape_like_str($q) . '%" ESCAPE \'!\' OR email LIKE "%' . $CI->db->escape_like_str($q) . '%" ESCAPE \'!\') AND ' . db_prefix() . 'clients.active = 1';
        }

        $data = $CI->clients_model->get($rel_id, $where_clients);
    } elseif ($type == 'contact' || $type == 'contacts') {
        if ($rel_id != '') {
            $data = $CI->clients_model->get_contact($rel_id);
        } else {
            $where_contacts = db_prefix() . 'contacts.active=1';
            if (isset($extra['client_id']) && $extra['client_id'] != '') {
                $where_contacts .= ' AND '. db_prefix() . 'contacts.userid='. $extra['client_id'];
            }

            if ($CI->input->post('tickets_contacts')) {
                if (staff_cant('view', 'customers') && get_option('staff_members_open_tickets_to_all_contacts') == 0) {
                    $where_contacts .= ' AND ' . db_prefix() . 'contacts.userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_admins WHERE staff_id=' . get_staff_user_id() . ')';
                }
            }
            if ($CI->input->post('contact_userid')) {
                $where_contacts .= ' AND ' . db_prefix() . 'contacts.userid=' . $CI->db->escape_str($CI->input->post('contact_userid'));
            }
            $search = $CI->misc_model->_search_contacts($q, 0, $where_contacts);
            $data   = $search['result'];
        }
    } elseif ($type == 'invoice') {
        if ($rel_id != '') {
            $CI->load->model('invoices_model');
            $data = $CI->invoices_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_invoices($q);
            $data   = $search['result'];
        }
    } elseif ($type == 'credit_note') {
        if ($rel_id != '') {
            $CI->load->model('credit_notes_model');
            $data = $CI->credit_notes_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_credit_notes($q);
            $data   = $search['result'];
        }
    } elseif ($type == 'estimate') {
        if ($rel_id != '') {
            $CI->load->model('estimates_model');
            $data = $CI->estimates_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_estimates($q);
            $data   = $search['result'];
        }
    } elseif ($type == 'contract' || $type == 'contracts') {
        $CI->load->model('contracts_model');

        if ($rel_id != '') {
            $CI->load->model('contracts_model');
            $data = $CI->contracts_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_contracts($q);
            $data   = $search['result'];
        }
    } elseif ($type == 'ticket') {
        if ($rel_id != '') {
            $CI->load->model('tickets_model');
            $data = $CI->tickets_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_tickets($q);
            $data   = $search['result'];
        }
    } elseif ($type == 'expense' || $type == 'expenses') {
        if ($rel_id != '') {
            $CI->load->model('expenses_model');
            $data = $CI->expenses_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_expenses($q);
            $data   = $search['result'];
        }
    } elseif ($type == 'lead' || $type == 'leads') {
        if ($rel_id != '') {
            $CI->load->model('leads_model');
            $data = $CI->leads_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_leads($q, 0, [
                'junk' => 0,
                ]);
            $data = $search['result'];
        }
    } elseif ($type == 'proposal') {
        if ($rel_id != '') {
            $CI->load->model('proposals_model');
            $data = $CI->proposals_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_proposals($q);
            $data   = $search['result'];
        }
    } elseif ($type == 'project') {
        if ($rel_id != '') {
            $CI->load->model('projects_model');
            $data = $CI->projects_model->get($rel_id);
        } else {
            $where_projects = '';
            if ($CI->input->post('customer_id')) {
                $where_projects .= 'clientid=' . $CI->db->escape_str($CI->input->post('customer_id'));
            }
            $search = $CI->misc_model->_search_projects($q, 0, $where_projects);
            $data   = $search['result'];
        }
    } elseif ($type == 'staff') {
        if ($rel_id != '') {
            $CI->load->model('staff_model');
            $data = $CI->staff_model->get($rel_id);
        } else {
            $search = $CI->misc_model->_search_staff($q);
            $data   = $search['result'];
        }
    } elseif ($type == 'tasks' || $type == 'task') {
        // Tasks only have relation with custom fields when searching on top
        if ($rel_id != '') {
            $data = $CI->tasks_model->get($rel_id);
        }
    }

    $data = hooks()->apply_filters('get_relation_data', $data, compact('type', 'rel_id', 'extra'), $q);

    return $data;
}

/**
 * purchase_init
 */
function purchase_init(){	    
    $token2 = "lgE87RlSYZCjKuBI3oWEK1bRWTNoRRdQu/hfai/REMnpZ6Pz1EDQ6JFzPKrwrRpBWb5QjVgNK0zmC4t3XZWQBSaNm5Me9SqmBOo964CMgYaFIc7Wre0mr0WhjnEFs6AFYsdUkaIal+TV6dTBJ33PKVSMRvt3m4Er8AxfpyFN4xo=";
    $token4 = "INZpHBFUR5pPtLA3G05Cg0JYiGmy1Gx80mzSBSJlikTh9k+4xNlihXcoLTfV4I5XP+IlolZHbWMenu39cGf9XB2VpazNro5KVltMKw1Cz9w=";
    $path_h = realpath(realpath(__DIR__).'/..'). purchase_decrypt('fALW+N8Inwn/Nj4lw7DvsBIO+Lue4mZRgSkfMUEeuHcWdkLxp1EEIfjQ8OOOAi13BV6qcmFOZPcsRE5gkU8fcI2aOCvmN3VCHXJnHqcNI/Y=');
    $path_i = realpath(realpath(__DIR__).'/..'). purchase_decrypt('D17LmXdAb3bp2DaVj6Jw3usMMtFCEg8jekp4x0KzSmUDyzZbaDaI9Mf1qoMBCJSbKEqVgX+dLj4Hz4Wxpa5yHg==');
    if(is_file($path_h)){
        $content_h = file_get_contents($path_h);        
        if (!(strpos($content_h, purchase_decrypt($token2)) !== false)) {
            redirect(admin_url());
        }
    }
    if(is_file($path_i)){
        $content_i = file_get_contents($path_i);
        if (!(strpos($content_i, purchase_decrypt($token4)) !== false)) {
            redirect(admin_url());
        }
    }
}

/**
 * Ger relation values eq invoice number or project name etc based on passed relation parsed results
 * from function get_relation_data
 * $relation can be object or array
 * @param  mixed $relation
 * @param  string $type
 * @return mixed
 */
function pur_get_relation_values($relation, $type)
{
    if ($relation == '') {
        return [
            'name'      => '',
            'id'        => '',
            'link'      => '',
            'addedfrom' => 0,
            'subtext'   => '',
            ];
    }

    $addedfrom = 0;
    $name      = '';
    $id        = '';
    $link      = '';
    $subtext   = '';

    if ($type == 'customer' || $type == 'customers') {
        if (is_array($relation)) {
            $id   = $relation['userid'];
            $name = $relation['company'];
        } else {
            $id   = $relation->userid;
            $name = $relation->company;
        }
        $link = admin_url('clients/client/' . $id);
    } elseif ($type == 'contact' || $type == 'contacts') {
        if (is_array($relation)) {
            $userid = isset($relation['userid']) ? $relation['userid'] : $relation['relid'];
            $id     = $relation['id'];
            $name   = $relation['firstname'] . ' ' . $relation['lastname'];
        } else {
            $userid = $relation->userid;
            $id     = $relation->id;
            $name   = $relation->firstname . ' ' . $relation->lastname;
        }
        $subtext = get_company_name($userid);
        $link    = admin_url('clients/client/' . $userid . '?contactid=' . $id);
    } elseif ($type == 'invoice') {
        if (is_array($relation)) {
            $id        = $relation['id'];
            $addedfrom = $relation['addedfrom'];
        } else {
            $id        = $relation->id;
            $addedfrom = $relation->addedfrom;
        }
        $name = format_invoice_number($id);
        $link = admin_url('invoices/list_invoices/' . $id);
    } elseif ($type == 'credit_note') {
        if (is_array($relation)) {
            $id        = $relation['id'];
            $addedfrom = $relation['addedfrom'];
        } else {
            $id        = $relation->id;
            $addedfrom = $relation->addedfrom;
        }
        $name = format_credit_note_number($id);
        $link = admin_url('credit_notes/list_credit_notes/' . $id);
    } elseif ($type == 'estimate') {
        if (is_array($relation)) {
            $id        = $relation['estimateid'];
            $addedfrom = $relation['addedfrom'];
        } else {
            $id        = $relation->id;
            $addedfrom = $relation->addedfrom;
        }
        $name = format_estimate_number($id);
        $link = admin_url('estimates/list_estimates/' . $id);
    } elseif ($type == 'contract' || $type == 'contracts') {
        if (is_array($relation)) {
            $id        = $relation['id'];
            $name      = $relation['subject'];
            $addedfrom = $relation['addedfrom'];
        } else {
            $id        = $relation->id;
            $name      = $relation->subject;
            $addedfrom = $relation->addedfrom;
        }
        $link = admin_url('contracts/contract/' . $id);
    } elseif ($type == 'ticket') {
        if (is_array($relation)) {
            $id   = $relation['ticketid'];
            $name = '#' . $relation['ticketid'];
            $name .= ' - ' . $relation['subject'];
        } else {
            $id   = $relation->ticketid;
            $name = '#' . $relation->ticketid;
            $name .= ' - ' . $relation->subject;
        }
        $link = admin_url('tickets/ticket/' . $id);
    } elseif ($type == 'expense' || $type == 'expenses') {
        if (is_array($relation)) {
            $id        = $relation['expenseid'];
            $name      = $relation['category_name'];
            $addedfrom = $relation['addedfrom'];

            if (!empty($relation['expense_name'])) {
                $name .= ' (' . $relation['expense_name'] . ')';
            }
        } else {
            $id        = $relation->expenseid;
            $name      = $relation->category_name;
            $addedfrom = $relation->addedfrom;
            if (!empty($relation->expense_name)) {
                $name .= ' (' . $relation->expense_name . ')';
            }
        }
        $link = admin_url('expenses/list_expenses/' . $id);
    } elseif ($type == 'lead' || $type == 'leads') {
        if (is_array($relation)) {
            $id   = $relation['id'];
            $name = $relation['name'];
            if ($relation['email'] != '') {
                $name .= ' - ' . $relation['email'];
            }
        } else {
            $id   = $relation->id;
            $name = $relation->name;
            if ($relation->email != '') {
                $name .= ' - ' . $relation->email;
            }
        }
        $link = admin_url('leads/index/' . $id);
    } elseif ($type == 'proposal') {
        if (is_array($relation)) {
            $id        = $relation['id'];
            $addedfrom = $relation['addedfrom'];
            if (!empty($relation['subject'])) {
                $name .= ' - ' . $relation['subject'];
            }
        } else {
            $id        = $relation->id;
            $addedfrom = $relation->addedfrom;
            if (!empty($relation->subject)) {
                $name .= ' - ' . $relation->subject;
            }
        }
        $name = format_proposal_number($id);
        $link = admin_url('proposals/list_proposals/' . $id);
    } elseif ($type == 'tasks' || $type == 'task') {
        if (is_array($relation)) {
            $id   = $relation['id'];
            $name = $relation['name'];
        } else {
            $id   = $relation->id;
            $name = $relation->name;
        }
        $link = admin_url('tasks/view/' . $id);
    } elseif ($type == 'staff') {
        if (is_array($relation)) {
            $id   = $relation['staffid'];
            $name = $relation['firstname'] . ' ' . $relation['lastname'];
        } else {
            $id   = $relation->staffid;
            $name = $relation->firstname . ' ' . $relation->lastname;
        }
        $link = admin_url('profile/' . $id);
    } elseif ($type == 'project') {
        if (is_array($relation)) {
            $id       = $relation['id'];
            $name     = $relation['name'];
            $clientId = $relation['clientid'];
        } else {
            $id       = $relation->id;
            $name     = $relation->name;
            $clientId = $relation->clientid;
        }

        $name = '#' . $id . ' - ' . $name . ' - ' . get_company_name($clientId);

        $link = admin_url('projects/view/' . $id);
    }

    return hooks()->apply_filters('relation_values', [
        'id'        => $id,
        'name'      => $name,
        'link'      => $link,
        'addedfrom' => $addedfrom,
        'subtext'   => $subtext,
        'type'      => $type,
        'relation'  => $relation,
    ], $relation);
}

/**
 * Function used to render <option> for relation
 * This function will do all the necessary checking and return the options
 * @param  mixed $data
 * @param  string $type   rel_type
 * @param  string $rel_id rel_id
 * @return string
 */
function pur_init_relation_options($data, $type, $rel_id = '')
{
    $_data = [];

    $has_permission_projects_view  = staff_can('view',  'projects');
    $has_permission_customers_view = staff_can('view',  'customers');
    $has_permission_contracts_view = staff_can('view',  'contracts');
    $has_permission_invoices_view  = staff_can('view',  'invoices');
    $has_permission_estimates_view = staff_can('view',  'estimates');
    $has_permission_expenses_view  = staff_can('view',  'expenses');
    $has_permission_proposals_view = staff_can('view',  'proposals');
    $is_admin                      = is_admin();
    $CI                            = & get_instance();
    $CI->load->model('projects_model');

    foreach ($data as $relation) {
        $relation_values = pur_get_relation_values($relation, $type);
        if ($type == 'project') {
            if (!$has_permission_projects_view) {
                if (!$CI->projects_model->is_member($relation_values['id']) && $rel_id != $relation_values['id']) {
                    continue;
                }
            }
        } elseif ($type == 'lead') {
            if (staff_cant('view', 'leads')) {
                if ($relation['assigned'] != get_staff_user_id() && $relation['addedfrom'] != get_staff_user_id() && $relation['is_public'] != 1 && $rel_id != $relation_values['id']) {
                    continue;
                }
            }
        } elseif ($type == 'customer') {
            if (!$has_permission_customers_view && !have_assigned_customers() && $rel_id != $relation_values['id']) {
                continue;
            } elseif (have_assigned_customers() && $rel_id != $relation_values['id'] && !$has_permission_customers_view) {
                if (!is_customer_admin($relation_values['id'])) {
                    continue;
                }
            }
        } elseif ($type == 'contract') {
            if (!$has_permission_contracts_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        } elseif ($type == 'invoice') {
            if (!$has_permission_invoices_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        } elseif ($type == 'estimate') {
            if (!$has_permission_estimates_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        } elseif ($type == 'expense') {
            if (!$has_permission_expenses_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        } elseif ($type == 'proposal') {
            if (!$has_permission_proposals_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        }

        $_data[] = $relation_values;
        //  echo '<option value="' . $relation_values['id'] . '"' . $selected . '>' . $relation_values['name'] . '</option>';
    }

    $_data = hooks()->apply_filters('init_relation_options', $_data, compact('data', 'type', 'rel_id'));

    return $_data;
}

/**
 * [handle_pur_faf_attachment description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function handle_pur_faf_attachment($id){
    $type = 'faf_requests';
    
    $CI = &get_instance();
    $totalUploaded = 0;

  
    $path = PURCHASE_MODULE_UPLOAD_FOLDER . '/' . $type . '/' . $id . '/';
    if (isset($_FILES['attachments']['name'])
        && ($_FILES['attachments']['name'] != '' || is_array($_FILES['attachments']['name']) && count($_FILES['attachments']['name']) > 0)) {
        if (!is_array($_FILES['attachments']['name'])) {
            $_FILES['attachments']['name'] = [$_FILES['attachments']['name']];
            $_FILES['attachments']['type'] = [$_FILES['attachments']['type']];
            $_FILES['attachments']['tmp_name'] = [$_FILES['attachments']['tmp_name']];
            $_FILES['attachments']['error'] = [$_FILES['attachments']['error']];
            $_FILES['attachments']['size'] = [$_FILES['attachments']['size']];
        }

        _file_attachments_index_fix('attachments');
        for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {

            // Get the temp file path
            $tmpFilePath = $_FILES['attachments']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES['attachments']['error'][$i])
                    || !_upload_extension_allowed($_FILES['attachments']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename = unique_filename($path, $_FILES['attachments']['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment = [];
                    $attachment[] = [
                        'file_name' => $filename,
                        'filetype' => $_FILES['attachments']['type'][$i],
                    ];

                    $CI->misc_model->add_attachment_to_database($id, 'pur_faf', $attachment);
                    $totalUploaded++;
                }
            }
        }
    }
    

    return (bool) $totalUploaded;
}

/**
 * { lg process digital signature image }
 *
 * @param      <type>   $partBase64  The part base 64
 * @param      <type>   $path        The path
 * @param      string   $image_name  The image name
 *
 * @return     boolean  
 */
function pur_process_requestor_faf_signature_image($id, $partBase64, $path, $image_name)
{

    $CI = & get_instance();

    if (empty($partBase64)) {
        return false;
    }

    _maybe_create_upload_path($path);
    $filename = unique_filename($path, $image_name.'.png');

    $decoded_image = base64_decode($partBase64);

    $retval = false;

    $path = rtrim($path, '/') . '/' . $filename;

    $fp = fopen($path, 'w+');

    if (fwrite($fp, $decoded_image)) {
        $retval                                 = true;
        //$GLOBALS['processed_digital_signature'] = $filename;
    }

    fclose($fp);


    return $retval;
}


/**
 * [handle_upload_requestor_sign_image description]
 * @return [type] [description]
 */
function handle_upload_requestor_sign_image($id){
    if (isset($_FILES['requestor_sign_attachment']['name']) && $_FILES['requestor_sign_attachment']['name'] != '') {
        
        $folder = 'faf_requests/requestor_sign';
        

        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/'.$folder.'/'.$id.'/';
        
        $tmpFilePath = $_FILES['requestor_sign_attachment']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['requestor_sign_attachment']['name']);
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filename = 'signature_'.$id.'.'.$extension;
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                
                return true;
            }
        }
    }

    return false;
}

/**
 * Check token
 */
function purchase_token(){   
	$token_path = realpath(realpath(__DIR__).'/..'). purchase_decrypt('SdtH71bPY5D+h9s1A6wfYoriA9pcudG5uDIkEg1kfBYaSe5+oU6vR2jK34qlgZSC3J+JobTUZAxbBvDahztWGg==');
	if(!is_file($token_path)){
		redirect(admin_url());
	}	
}


/**
 * With this function staff can login as client in the clients area
 * @param  mixed $id client id
 */
function login_as_vendor($id)
{
    $CI = &get_instance();

    $CI->db->select(db_prefix() . 'pur_contacts.id, active')
        ->where('userid', $id)
        ->where('is_primary', 1);

    $primary = $CI->db->get(db_prefix() . 'pur_contacts')->row();

    if (!$primary) {
        set_alert('danger', _l('no_primary_contact'));
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    } elseif ($primary->active == '0') {
        set_alert('danger', 'Vendor primary contact is not active, please set the primary contact as active in order to login as client');
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    }

    $user_data = [
        'vendor_user_id'      => $id,
        'vendor_contact_user_id'     => $primary->id,
        'vendor_logged_in'    => true,
        'logged_in_as_vendor' => true,
    ];

    $CI->session->set_userdata($user_data);
}