<?php
defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('pre_activate_module', WAREHOUSE_MODULE_NAME.'_preactivate');
hooks()->add_action('pre_deactivate_module', WAREHOUSE_MODULE_NAME.'_predeactivate');
hooks()->add_action('pre_uninstall_module', WAREHOUSE_MODULE_NAME.'_uninstall');
/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */


/**
 * get taxes
 * @param  integer $id
 * @return array or row
 */
function get_taxes($id =''){
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id',$id);

        return $CI->db->get(db_prefix().'taxes')->row();
    }
    $CI->db->order_by('taxrate', 'ASC');
    return $CI->db->get(db_prefix().'taxes')->result_array();

}

/**
 * get unit type
 * @param  integer $id
 * @return array or row
 */
function get_unit_type($id = false)
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
 * get tax rate
 * @param  integer $id
 * @return array or row
 */
function get_tax_rate($id = false)
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
 * decrypt
 * @param  string $data
 * @return string
 */
function warehouse_decrypt($data) {
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
 * get group name
 * @param  integer $id
 * @return array or row
 */
function get_wh_group_name($id = false)
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
 * get size name
 * @param  integer $id
 * @return array or row
 */
function get_size_name($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('size_type_id', $id);

        return $CI->db->get(db_prefix() . 'ware_size_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblware_size_type')->result_array();
    }

}


/**
 * get style name
 * @param  integer $id
 * @return array or row
 */
function get_style_name($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('style_type_id', $id);
        return $CI->db->get(db_prefix() . 'ware_style_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblware_style_type')->result_array();
    }

}

/**
 * get model name
 * @param  integer $id
 * @return array or row
 */
function get_model_name($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('body_type_id', $id);

        return $CI->db->get(db_prefix() . 'ware_body_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblware_body_type')->result_array();
    }

}

/**
 * get puchase order aproved on module purchase
 * get purchae order
 * @param  integer $id
 * @return array or row
 */
function get_pr_order($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);
        return $CI->db->get(db_prefix() . 'pur_orders')->row();
    }
    if ($id == false) {
        $CI->load->model('warehouse/warehouse_model');
        $pur_order_created_receipt = $CI->warehouse_model->get_pur_order_created_receipt();
        if(count($pur_order_created_receipt) > 0){
            return $CI->db->query('select * from '.db_prefix().'pur_orders where approve_status = 2 AND status_goods = 0 AND id NOT IN('.implode(',', $pur_order_created_receipt).')')->result_array();
        }else{
            return $CI->db->query('select * from '.db_prefix().'pur_orders where approve_status = 2 AND status_goods = 0')->result_array();
        }
    }
}


/**
 * reformat currency
 * @param  string  $value
 * @return float
 */
if (!function_exists('reformat_currency_j')) {

    function reformat_currency_j($value)
    {

        $f_dot = new_str_replace(',','', $value);
        return ((float)$f_dot + 0);
    }
}


/**
 * get purchase order request name
 * @param  integer $id
 * @return array or row
 */
function get_pur_request_name($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'pur_request')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblpur_request')->result_array();
    }

}


/**
 * get warehouse name
 * @param  integer $id
 * @return array or row
 */
function get_warehouse_name($id = false, $only_name = false)
{
    $CI           = & get_instance();

    if ($id != false) {
        $CI->db->where('warehouse_id', $id);

        $warehouse = $CI->db->get(db_prefix() . 'warehouse')->row();
        if($only_name){
            if($warehouse){
                return $warehouse->warehouse_name;
            }
            return '';

        }
        return $warehouse;
    }
    if ($id == false) {
        return $CI->db->query('select * from tblwarehouse')->result_array();
    }

}


/**
 * get commodity name
 * @param  integer $id
 * @return array or row
 */
function get_commodity_name($id = false)
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
 * get status inventory
 * @param  integer $commodity, integer $inventory
 * @return boolean
 */
function get_status_inventory($commodity, $inventory)
{
    $CI           = & get_instance();
    
    $status=false;
    $inventory_min=0;

    $CI->db->where('commodity_id', $commodity);
    $result = $CI->db->get(db_prefix() . 'inventory_commodity_min')->row();
    if($result){
        $inventory_min = $result->inventory_number_min;
    }

    if((float)$inventory < (float)$inventory_min){
        $status = false;
    }else{
        $status = true;
    }
    return $status;

}

/**
 * get goods receipt code
 * @param  integer $id
 * @return array or row
 */
function get_goods_receipt_code($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'goods_receipt')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblgoods_receipt')->result_array();
    }

}


/**
 * warehouse process digital signature image
 * @param  string $partBase64
 * @param  string $path
 * @param  string $image_name
 * @return boolean
 */
function warehouse_process_digital_signature_image($partBase64, $path, $image_name)
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
 * numberTowords 
 * @param  string $num 
 * @return string
 */
function numberTowords($num)
{ 
    $ones = array( 
        0 => '',
        1 => "One", 
        2 => "Two", 
        3 => "Three", 
        4 => "Four", 
        5 => "Five", 
        6 => "Six", 
        7 => "Seven", 
        8 => "Eight", 
        9 => "Nine", 
        10 => "Ten", 
        11 => "Eleven", 
        12 => "Twelve", 
        13 => "Thirteen", 
        14 => "Fourteen", 
        15 => "Fifteen", 
        16 => "Sixteen", 
        17 => "Seventeen", 
        18 => "Eighteen", 
        19 => "Nineteen" 
    ); 
    $tens = array( 
        0 => '',
        1 => "Ten",
        2 => "Twenty", 
        3 => "Thirty", 
        4 => "Fourty", 
        5 => "Fifty", 
        6 => "Sixty", 
        7 => "Seventy", 
        8 => "Eighty", 
        9 => "Ninety" 
    ); 
    $hundreds = array( 
        "Hundred", 
        "Thousand", 
        "Million", 
        "Billion", 
        "Thousands of billions", 
        "Million billion" 
    ); //limit t quadrillion 
    $num = number_format($num,2,".",","); 
    $num_arr = new_explode(".",$num); 
    $wholenum = $num_arr[0]; 
    
    $decnum = $num_arr[1]; 
    $whole_arr = array_reverse(new_explode(",",$wholenum)); 
    krsort($whole_arr); 
    $rettxt = ""; 
    foreach($whole_arr as $key => $i){ 

        if($i == '0' || $i == '000' || $i == '00'){
            $rettxt .= $ones[0];
        }elseif($i < 20){ 

            $rettxt .= $ones[$i]; 
        }elseif($i < 100){ 
            $rettxt .= $tens[substr($i,0,1)]; 
            $rettxt .= " ".$ones[substr($i,1,1)]; 
        }else{ 
            $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
            $rettxt .= " ".$tens[substr($i,1,1)]; 
            $rettxt .= " ".$ones[substr($i,2,1)]; 
        }

        if($key > 0){ 
            $rettxt .= " ".$hundreds[$key]." "; 
        } 

    } 
    if($decnum > 0){ 
        $rettxt .= " and "; 
        if($decnum < 20){ 
            $rettxt .= $ones[$decnum]; 
        }elseif($decnum < 100){ 
            $rettxt .= $tens[substr($decnum,0,1)]; 
            $rettxt .= " ".$ones[substr($decnum,1,1)]; 
        } 
    } 

    return $rettxt; 
} 


/**
 * get status modules wh
 * @param  string $module_name 
 * @return boolean             
 */
function get_status_modules_wh($module_name){
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
 * get goods delivery code
 * @param  integer $id
 * @return array or row
 */
function get_goods_delivery_code($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'goods_delivery')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblgoods_delivery')->result_array();
    }

}

/**
 * handle commmodity list add edit file
 * @param  integer $id
 * @return boolean
 */
function handle_commodity_list_add_edit_file($id){

    if (isset($_FILES['cd_avar']['name']) && $_FILES['cd_avar']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = WAREHOUSE_ITEM_UPLOAD. $id . '/';
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
                $CI->misc_model->add_attachment_to_database($id, 'commodity_item_file', $attachment);

                return true;
            }
        }
    }

    return false;
}


/**
 * handle commodity attchment
 * @param  integer $id
 * @return array or row
 */
function handle_commodity_attachments($id)
{

    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = WAREHOUSE_ITEM_UPLOAD . $id . '/';
    $CI   = & get_instance();

    if (isset($_FILES['file']['name'])) {

        // 
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {

            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);

            $new_filename = str_replace(' ', '_', $filename);
            $new_filename = str_replace('(', '_', $new_filename);
            $new_filename = str_replace(')', '_', $new_filename);
            $new_filename = str_replace('. ', '.', $new_filename);

            $newFilePath = $path . $new_filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                $attachment   = [];
                $attachment[] = [
                    'file_name' => $new_filename,
                    'filetype'  => $_FILES['file']['type'],
                ];

                $CI->misc_model->add_attachment_to_database($id, 'commodity_item_file', $attachment);
            }
        }
    }

}



/**
 * get color type
 * @param  integer $id, string $index_name
 * @return array, object
 */
function get_color_type($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('color_id', $id);

        return $CI->db->get(db_prefix() . 'ware_color')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblware_color')->result_array();
    }

}

/**
 * get warehouse by commodity
 * @param  integer $commodity_id 
 * @return array               
 */
function get_warehouse_by_commodity($commodity_id )
{
    $CI           = & get_instance();

    if (is_numeric($commodity_id)) {
        $CI->load->model('warehouse/warehouse_model');
        $warehouseByStaff = $CI->warehouse_model->getWarehouseByStaff();

    //get parent quantity
        if(is_array($warehouseByStaff)){

            $sql ='SELECT distinct warehouse_id FROM '.db_prefix().'inventory_manage where inventory_number >= 0 AND commodity_id = "'.$commodity_id.'" AND '.db_prefix().'inventory_manage.warehouse_id IN ('.implode(",", $warehouseByStaff).')';

        }else{
            $sql ='SELECT distinct warehouse_id FROM '.db_prefix().'inventory_manage where inventory_number >= 0 AND commodity_id = "'.$commodity_id.'"';
        }

        return $CI->db->query($sql)->result_array();
    }

}


/**
 * row options exist
 * @param  string $name 
 *        
 */
function warehouse_row_options_exist($name){
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
 * Gets the warehouse option.
 *
 * @param      <type>        $name   The name
 *
 * @return     array|string  The warehouse option.
 */
function get_warehouse_option($name)
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
 * get pur order name
 * @param  integer $id 
 * @return string     
 */
function get_pur_order_name($id)
{   
    $name='';
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $pur_orders = $CI->db->get(db_prefix().'pur_orders')->row();

    if($pur_orders){
       $name .= $pur_orders->pur_order_number.' - '.$pur_orders->pur_order_name;
    }

   return $name;

}

/**
 * get staff
 * @param  integer $id
 * @return array or row
 */
function wh_get_staff($id =''){

    $CI = &get_instance();
    $CI->load->model('warehouse/warehouse_model');
    return  $CI->warehouse_model->get_staff($invoice_id);


}

hooks()->add_action('after_email_templates', 'add_inventory_warning_email_templates');

if (!function_exists('add_inventory_warning_email_templates')) {
    /**
     * Init inventory email templates and assign languages
     * @return void
     */
    function add_inventory_warning_email_templates()
    {
        $CI = &get_instance();

        $data['inventory_warning_templates'] = $CI->emails_model->get(['type' => 'inventory_warning', 'language' => 'english']);

        $CI->load->view('warehouse/inventory_warning_email_template', $data);
    }
}

/**
 * get internal delivery code
 * @param  boolean $id 
 * @return [type]      
 */
function get_internal_delivery_code($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'internal_delivery_note')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblinternal_delivery_note')->result_array();
    }

}

/**
 * wh get pr order delivered on module purchase
 * get purchae order
 * @param  integer $id
 * @return array or row
 */
function wh_get_pr_order_delivered($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);
        return $CI->db->get(db_prefix() . 'pur_orders')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblpur_orders where approve_status = 2 AND delivery_status = 1')->result_array();
    }

}


/**
 * wh check approval setting
 * @param  integer $type 
 * @return [type]       
 */
function wh_check_approval_setting($type)
{   
    $CI = &get_instance();
    $CI->load->model('warehouse/warehouse_model');

    $check_appr = $CI->warehouse_model->get_approve_setting($type);

    return $check_appr;
}


/**
 * wh handle propsal file
 * @param  integer $id 
 * @return boolean     
 */
function wh_handle_propsal_file($id)
{
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = WAREHOUSE_PROPOSAL_UPLOAD_FOLDER. $id . '/';
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
                $CI->misc_model->add_attachment_to_database($id, 'wh_proposal', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * get brand
 * @param  integer $id
 * @return array or row
 */
function get_brand_name($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'wh_brand')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblwh_brand')->result_array();
    }

}

/**
 * get model
 * @param  integer $id
 * @return array or row
 */
function get_models_name($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'wh_model')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblwh_model')->result_array();
    }

}

/**
 * get series
 * @param  integer $id
 * @return array or row
 */
function get_series_name($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'wh_series')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblwh_series')->result_array();
    }

}


/**
 * wh_render_custom_fields
 * @param  [type]  $belongs_to      
 * @param  boolean $rel_id          
 * @param  array   $where           
 * @param  array   $items_cf_params 
 * @return [type]                   
 */
function wh_render_custom_fields($belongs_to, $rel_id = false, $where = [], $items_cf_params = [])
{


    // Is custom fields for items and in add/edit
    $items_add_edit_preview = isset($items_cf_params['add_edit_preview']) && $items_cf_params['add_edit_preview'] ? true : false;

    // Is custom fields for items and in add/edit area for this already added
    $items_applied = isset($items_cf_params['items_applied']) && $items_cf_params['items_applied'] ? true : false;

    // Used for items custom fields to add additional name on input
    $part_item_name = isset($items_cf_params['part_item_name']) ? $items_cf_params['part_item_name'] : '';

    // Is this custom fields for predefined items Sales->Items
    $items_pr = isset($items_cf_params['items_pr']) && $items_cf_params['items_pr'] ? true : false;

    $is_admin = is_admin();

    $CI = & get_instance();
    $CI->db->where('active', 1);
    $CI->db->where('fieldto', $belongs_to);

    if (is_array($where) && count($where) > 0 || is_string($where) && $where != '') {
        $CI->db->where($where);
    }

    $CI->db->order_by('field_order', 'asc');
    $fields = $CI->db->get(db_prefix() . 'customfields')->result_array();

    $fields_html = '';

    if($rel_id != false && $rel_id != 0){
        $is_add = false;

        $string_where = 'find_in_set('.$rel_id.', '.db_prefix().'wh_custom_fields.warehouse_id) ';
        $CI->db->where($string_where);
        $custom_fields_value = $CI->db->get(db_prefix() . 'wh_custom_fields')->result_array();

        $array_custom_fields=[];
        foreach ($custom_fields_value as $value) {
            array_push($array_custom_fields, $value['custom_fields_id']);
        }

        if(count($array_custom_fields) == 0){

            $is_add = true;
        }

    }else{
        $is_add = true;
    }


    if (count($fields)) {
        if (!$items_add_edit_preview && !$items_applied) {
            $fields_html .= '<div class="row custom-fields-form-row">';
        }

        foreach ($fields as $field) {

            if ($field['only_admin'] == 1 && !$is_admin) {
                continue;
            }

            $field['name'] = _maybe_translate_custom_field_name($field['name'], $field['slug']);

            $value = '';
            if ($field['bs_column'] == '' || $field['bs_column'] == 0) {
                $field['bs_column'] = 12;
            }


            $hidden ='';
            if($is_add == true){
                $hidden = ' hidden';
            }else{
                if(!in_array($field['id'], $array_custom_fields)){
                    $hidden = ' hidden';
                }
            }

            $field['bs_column'] .= ' '.$field['fieldto'].$field['id'].$hidden;

            if (!$items_add_edit_preview && !$items_applied) {
                $fields_html .= '<div class="col-md-' . $field['bs_column'] . '">';
            } elseif ($items_add_edit_preview) {
                $fields_html .= '<td class="custom_field" data-id="' . $field['id'] . '">';
            } elseif ($items_applied) {
                $fields_html .= '<td class="custom_field">';
            }

            if ($is_admin
                && ($items_add_edit_preview == false && $items_applied == false)
                && (!defined('CLIENTS_AREA') || hooks()->apply_filters('show_custom_fields_edit_link_on_clients_area', false))) {
                $fields_html .= '<a href="' . admin_url('custom_fields/field/' . $field['id']) . '" tabindex="-1" target="_blank" class="custom-field-inline-edit-link"><i class="fa-regular fa-pen-to-square"></i></a>';
            }

            if ($rel_id !== false) {
                if (!is_array($rel_id)) {
                    $value = get_custom_field_value($rel_id, $field['id'], ($items_pr ? 'items_pr' : $belongs_to), false);
                } else {
                    if (is_custom_fields_smart_transfer_enabled()) {
                        // Used only in:
                        // 1. Convert proposal to estimate, invoice
                        // 2. Convert estimate to invoice
                        // This feature is executed only on CREATE, NOT EDIT
                        $transfer_belongs_to = $rel_id['belongs_to'];
                        $transfer_rel_id     = $rel_id['rel_id'];
                        $tmpSlug             = new_explode('_', $field['slug'], 2);
                        if (isset($tmpSlug[1])) {
                            $CI->db->where('fieldto', $transfer_belongs_to);
                            $CI->db->where('slug LIKE "' . $rel_id['belongs_to'] . '_' . $tmpSlug[1] . '%" AND type="' . $field['type'] . '" AND options="' . $field['options'] . '" AND active=1');
                            $cfTransfer = $CI->db->get(db_prefix() . 'customfields')->result_array();

                            // Don't make mistakes
                            // Only valid if 1 result returned
                            // + if field names similarity is equal or more then CUSTOM_FIELD_TRANSFER_SIMILARITY%
                            //
                            if (count($cfTransfer) == 1 && ((similarity($field['name'], $cfTransfer[0]['name']) * 100) >= CUSTOM_FIELD_TRANSFER_SIMILARITY)) {
                                $value = get_custom_field_value($transfer_rel_id, $cfTransfer[0]['id'], $transfer_belongs_to, false);
                            }
                        }
                    }
                }
            }

            $_input_attrs = [];

            if ($field['required'] == 1) {
                if(new_strlen($hidden) == 0){
                    $_input_attrs['data-custom-field-required'] = true;
                }
            }

            if ($field['disalow_client_to_edit'] == 1 && is_client_logged_in()) {
                $_input_attrs['disabled'] = true;
            }

            $_input_attrs['data-fieldto'] = $field['fieldto'];
            $_input_attrs['data-fieldid'] = $field['id'];

            $cf_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';

            if ($part_item_name != '') {
                $cf_name = $part_item_name . '[custom_fields][items][' . $field['id'] . ']';
            }

            if ($items_add_edit_preview) {
                $cf_name = '';
            }

            $field_name = $field['name'];

            if ($field['type'] == 'input' || $field['type'] == 'number') {
                $t = $field['type'] == 'input' ? 'text' : 'number';
                $fields_html .= render_input($cf_name, $field_name, $value, $t, $_input_attrs);
            } elseif ($field['type'] == 'date_picker') {
                $fields_html .= render_date_input($cf_name, $field_name, _d($value), $_input_attrs);
            } elseif ($field['type'] == 'date_picker_time') {
                $fields_html .= render_datetime_input($cf_name, $field_name, _dt($value), $_input_attrs);
            } elseif ($field['type'] == 'textarea') {
                $fields_html .= render_textarea($cf_name, $field_name, $value, $_input_attrs);
            } elseif ($field['type'] == 'colorpicker') {
                $fields_html .= render_color_picker($cf_name, $field_name, $value, $_input_attrs);
            } elseif ($field['type'] == 'select' || $field['type'] == 'multiselect') {
                $_select_attrs = [];
                $select_attrs  = '';
                $select_name   = $cf_name;

                if ($field['required'] == 1) {
                    if(new_strlen($hidden) == 0){
                        $_select_attrs['data-custom-field-required'] = true;
                    }
                }

                if ($field['disalow_client_to_edit'] == 1 && is_client_logged_in()) {
                    $_select_attrs['disabled'] = true;
                }

                $_select_attrs['data-fieldto'] = $field['fieldto'];
                $_select_attrs['data-fieldid'] = $field['id'];

                if ($field['type'] == 'multiselect') {
                    $_select_attrs['multiple'] = true;
                    $select_name .= '[]';
                }

                foreach ($_select_attrs as $key => $val) {
                    $select_attrs .= $key . '=' . '"' . $val . '" ';
                }

                $fields_html .= '<div class="form-group">';
                $fields_html .= '<label for="' . $cf_name . '" class="control-label" style="margin-bottom:9px;">' . $field_name . '</label>';
                $fields_html .= '<select ' . $select_attrs . ' name="' . $select_name . '" class="' . ($items_add_edit_preview == false ? 'select-placeholder ': '') . 'selectpicker form-control' . ($field['type'] == 'multiselect' ? ' custom-field-multi-select' : '') . '" data-width="100%" data-none-selected-text="' . _l('dropdown_non_selected_tex') . '"  data-live-search="true">';

                $fields_html .= '<option value=""' . ($field['type'] == 'multiselect' ? ' class="hidden"' : '') . '></option>';

                $options = new_explode(',', $field['options']);

                if ($field['type'] == 'multiselect') {
                    $value = new_explode(',', $value);
                }

                foreach ($options as $option) {
                    $option = trim($option);
                    if ($option != '') {
                        $selected = '';
                        if ($field['type'] == 'select') {
                            if ($option == $value) {
                                $selected = ' selected';
                            }
                        } else {
                            foreach ($value as $v) {
                                $v = trim($v);
                                if ($v == $option) {
                                    $selected = ' selected';
                                }
                            }
                        }

                        $fields_html .= '<option value="' . $option . '"' . $selected . '' . set_select($cf_name, $option) . '>' . $option . '</option>';
                    }
                }
                $fields_html .= '</select>';
                $fields_html .= '</div>';
            } elseif ($field['type'] == 'checkbox') {
                $fields_html .= '<div class="form-group chk">';

                $fields_html .= '<br /><label class="control-label' . ($field['display_inline'] == 0 ? ' no-mbot': '') . '" for="' . $cf_name . '[]">' . $field_name . '</label>' . ($field['display_inline'] == 1 ? ' <br />': '');

                $options = new_explode(',', $field['options']);

                $value = new_explode(',', $value);

                foreach ($options as $option) {
                    $checked = '';
                    // Replace double quotes with single.
                    $option = htmlentities($option);
                    $option = trim($option);
                    foreach ($value as $v) {
                        $v = trim($v);
                        if ($v == $option) {
                            $checked = 'checked';
                        }
                    }

                    $_chk_attrs                 = [];
                    $chk_attrs                  = '';
                    $_chk_attrs['data-fieldto'] = $field['fieldto'];
                    $_chk_attrs['data-fieldid'] = $field['id'];

                    if ($field['required'] == 1) {
                        if(new_strlen($hidden) == 0){
                            $_chk_attrs['data-custom-field-required'] = true;
                        }
                    }

                    if ($field['disalow_client_to_edit'] == 1 && is_client_logged_in()) {
                        $_chk_attrs['disabled'] = true;
                    }
                    foreach ($_chk_attrs as $key => $val) {
                        $chk_attrs .= $key . '=' . '"' . $val . '" ';
                    }

                    $input_id = 'cfc_' . $field['id'] . '_' . slug_it($option) . '_' . app_generate_hash();

                    $fields_html .= '<div class="checkbox' . ($field['display_inline'] == 1 ? ' checkbox-inline': '') . '">';
                    $fields_html .= '<input class="custom_field_checkbox" ' . $chk_attrs . ' ' . set_checkbox($cf_name . '[]', $option) . ' ' . $checked . ' value="' . $option . '" id="' . $input_id . '" type="checkbox" name="' . $cf_name . '[]">';

                    $fields_html .= '<label for="' . $input_id . '" class="cf-chk-label">' . $option . '</label>';
                    $fields_html .= '<input type="hidden" name="' . $cf_name . '[]" value="cfk_hidden">';
                    $fields_html .= '</div>';
                }
                $fields_html .= '</div>';
            } elseif ($field['type'] == 'link') {
                $fields_html .= '<div class="form-group cf-hyperlink" data-fieldto="' . $field['fieldto'] . '" data-field-id="' . $field['id'] . '" data-value="' . html_escape($value) . '" data-field-name="' . html_escape($field_name) . '">';
                $fields_html .= '<label class="control-label" for="custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']">' . $field_name . '</label></br>';

                $fields_html .= '<a id="custom_fields_' . $field['fieldto'] . '_' . $field['id'] . '_popover" type="button" href="javascript:">' . _l('cf_translate_input_link_tip') . '</a>';
                if(new_strlen($hidden) == 0){
                    $fields_html .= '<input type="hidden" ' . ($field['required'] == 1 ? 'data-custom-field-required="1"' : '') . ' value="" id="custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']" name="custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']">';
                }else{
                    $fields_html .= '<input type="hidden" value="" id="custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']" name="custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']">';

                }

                $field_template = '';
                $field_template .= '<div id="custom_fields_' . $field['fieldto'] . '_' . $field['id'] . '_popover-content" class="hide cfh-field-popover-template"><div class="form-group">';
                $field_template .= '<div class="row"><div class="col-md-12"><label class="control-label" for="custom_fields_' . $field['fieldto'] . '_' . $field['id'] . '_title">' . _l('cf_translate_input_link_title') . '</label>';
                $field_template .= '<input type="text"' . ($field['disalow_client_to_edit'] == 1 && is_client_logged_in() ? ' disabled="true" ' : ' ') . 'id="custom_fields_' . $field['fieldto'] . '_' . $field['id'] . '_title" value="" class="form-control">';
                $field_template .= '</div>';
                $field_template .= '</div>';
                $field_template .= '</div>';
                $field_template .= '<div class="form-group">';
                $field_template .= '<div class="row">';
                $field_template .= '<div class="col-md-12">';
                $field_template .= '<label class="control-label" for="custom_fields_' . $field['fieldto'] . '_' . $field['id'] . '_link">' . _l('cf_translate_input_link_url') . '</label>';
                $field_template .= '<div class="input-group"><input type="text"' . ($field['disalow_client_to_edit'] == 1 && is_client_logged_in() ? ' disabled="true" ' : ' ') . 'id="custom_fields_' . $field['fieldto'] . '_' . $field['id'] . '_link" value="" class="form-control"><span class="input-group-addon"><a href="#" id="cf_hyperlink_open_' . $field['id'] . '" target="_blank"><i class="fa fa-globe"></i></a></span></div>';
                $field_template .= '</div>';
                $field_template .= '</div>';
                $field_template .= '</div>';
                $field_template .= '<div class="row">';
                $field_template .= '<div class="col-md-6">';
                $field_template .= '<button type="button" id="custom_fields_' . $field['fieldto'] . '_' . $field['id'] . '_btn-cancel" class="btn btn-default btn-md pull-left" value="">' . _l('cancel') . '</button>';
                $field_template .= '</div>';
                $field_template .= '<div class="col-md-6">';
                $field_template .= '<button type="button" id="custom_fields_' . $field['fieldto'] . '_' . $field['id'] . '_btn-save" class="btn btn-info btn-md pull-right" value="">' . _l('apply') . '</button>';
                $field_template .= '</div>';
                $field_template .= '</div>';
                $fields_html .= '<script>';
                $fields_html .= 'cfh_popover_templates[\'' . $field['id'] . '\'] = \'' . $field_template . '\';';
                $fields_html .= '</script>';
                $fields_html .= '</div>';
            }

            $name = $cf_name;

            if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
                $name .= '[]';
            }

            $fields_html .= form_error($name);
            if (!$items_add_edit_preview && !$items_applied) {
                $fields_html .= '</div>';
            } elseif ($items_add_edit_preview) {
                $fields_html .= '</td>';
            } elseif ($items_applied) {
                $fields_html .= '</td>';
            }
        }

        // close row
        if (!$items_add_edit_preview && !$items_applied) {
            $fields_html .= '</div>';
        }
    }

    return $fields_html;
}

/**
 * wh get custom fields
 * @param  [type] $id 
 * @return [type]     
 */
function wh_get_custom_fields($id)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'customfields')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblcustomfields')->result_array();
    }

}


/**
 * handle send delivery note
 * @param  [type] $id 
 * @return [type]     
 */
function handle_send_delivery_note($id){
 if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {

    $path = WAREHOUSE_MODULE_UPLOAD_FOLDER .'/send_delivery_note/'. $id . '/';
        // Get the temp file path
    $tmpFilePath = $_FILES['attachment']['tmp_name'];
        // Make sure we have a filepath
    if (!empty($tmpFilePath) && $tmpFilePath != '') {
        _maybe_create_upload_path($path);
        $filename    = unique_filename($path, $_FILES['attachment']['name']);
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
 * Gets the vendor company name.
 *
 * @param      string   $userid                 The userid
 * @param      boolean  $prevent_empty_company  The prevent empty company
 *
 * @return     string   The vendor company name.
 */
function wh_get_vendor_company_name($userid, $prevent_empty_company = false)
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

/**
 * get invoice company projecy
 * @param  [type] $invoice_id 
 * @return [type]             
 */
function get_invoice_company_projecy($invoice_id)
{
    $CI           = & get_instance();
    $invoice_info = '';

    if (is_numeric($invoice_id)) {

        $invoices = $CI->db->query('select *, iv.id as id from '.db_prefix().'invoices as iv left join '.db_prefix().'projects as pj on pj.id = iv.project_id left join '.db_prefix().'clients as cl on cl.userid = iv.clientid  where iv.id ='.$invoice_id)->row();

        if($invoices){
            $invoice_info .= ' - '.$invoices->company.' - '.$invoices->name;
        }

    }
    
    return $invoice_info;

}

/**
 * wh get warehouse address
 * @param  [type] $id 
 * @return [type]     
 */
function wh_get_warehouse_address($id)
{
    $CI           = & get_instance();

    $CI->db->where('warehouse_id', $id);
    $warehouse_value = $CI->db->get(db_prefix() . 'warehouse')->row();

    $address='';

    if($warehouse_value){

        $warehouse_address = [];
        $warehouse_address[0] =  $warehouse_value->warehouse_address;
        $warehouse_address[1] = $warehouse_value->city;
        $warehouse_address[2] =  $warehouse_value->state;
        $warehouse_address[3] =  $warehouse_value->country;
        $warehouse_address[4] =  $warehouse_value->zip_code;

        foreach ($warehouse_address as $key => $add_value) {
            if(isset($add_value) && $add_value !=''){
                switch ($key) {
                    case 0:
                        $address .= $add_value;
                        break;
                    case 1:
                        $address .= ', '.$add_value;
                        break;
                    case 2:
                        $address .= ', '.$add_value;
                        break;
                    case 3:
                        $address .= ', '.get_country_name($add_value);
                        break;
                    case 4:
                        $address .= ', '.$add_value;
                        break;
                    default:
                    break;
                }
            }
        }

    }
    return $address;

}

/**
 * wh get item variatiom
 * @param  [type] $id 
 * @return [type]     
 */
function wh_get_item_variatiom($id, $only_name = false)
{
    $CI           = & get_instance();

    $CI->db->where('id', $id);
    $item_value = $CI->db->get(db_prefix() . 'items')->row();

    $name = '';
    if($item_value){
        $CI->load->model('warehouse/warehouse_model');
        $new_item_value = $CI->warehouse_model->row_item_to_variation($item_value);
        if($only_name){
            $name .= $new_item_value->new_description;
        }else{
            $name .= $item_value->commodity_code.'_'.$new_item_value->new_description;
        }
    }

    return $name;
}

/**
 * get inventory quantity by variation
 * @param  [type] $id 
 * @return [type]     
 */
function get_inventory_quantity_by_variation($id)
{
    $CI           = & get_instance();

    //check have child item
    $CI->db->where('parent_id', $id);
    $child_item = $CI->db->get(db_prefix() . 'items')->result_array();

    if(count($child_item) > 0){
        //get total child quantity
        $sql_where = "SELECT sum(inventory_number) as inventory_number FROM ".db_prefix()."inventory_manage
WHERE commodity_id IN ( select id FROM ".db_prefix()."items where parent_id = ".$id.")" ;

        $item_value = $CI->db->query($sql_where)->row(); 

        return (float)$item_value->inventory_number;
        
    }else{
        //get parent quantity
        $sql = 'SELECT sum(inventory_number) as inventory_number FROM ' . db_prefix() . 'inventory_manage
        where ' . db_prefix() . 'inventory_manage.commodity_id = ' . $id . ' group by ' . db_prefix() . 'inventory_manage.commodity_id';

        $item_value = $CI->db->query($sql)->row(); 

        if($item_value){
            return (float)$item_value->inventory_number;
        }

        return 0;

    }


}

/**
 * check item have variation
 * @param  [type] $id 
 * @return [type]     
 */
function check_item_have_variation($id)
{
    $CI           = & get_instance();

    //check have child item
    $CI->db->where('parent_id', $id);
    $child_item = $CI->db->get(db_prefix() . 'items')->result_array();

    if(count($child_item) > 0){
        return true;
    }else{
        return false;
    }

}

/**
 * get inventory by warehouse variation
 * @param  [type] $id 
 * @return [type]     
 */
function get_inventory_by_warehouse_variation($id)
{
    $CI           = & get_instance();

    //get parent quantity
    $sql_where = "SELECT sum(inventory_number) as inventory_number, warehouse_id FROM ".db_prefix()."inventory_manage
WHERE commodity_id IN ( select id FROM ".db_prefix()."items where parent_id = ".$id.") group by warehouse_id" ;

    $item_value = $CI->db->query($sql_where)->result_array(); 

    return $item_value;
}

/**
 * { row warehouse options exist }
 *
 * @param      <type>   $name   The name
 *
 * @return     integer  ( 1 or 0 )
 */
function row_warehouse_tbl_options_exist($name) {
    $CI = &get_instance();
    $i = count($CI->db->query('Select * from ' . db_prefix() . 'options where name = ' . $name)->result_array());
    if ($i == 0) {
        return 0;
    }
    if ($i > 0) {
        return 1;
    }
}

function wh_get_item_taxes($table, $itemid)
{
    $CI = &get_instance();
    $CI->db->where('itemid', $itemid);
    $CI->db->where('rel_type', 'internal_transfer');
    $taxes = $CI->db->get(db_prefix() . 'item_tax')->result_array();
    $i     = 0;
    foreach ($taxes as $tax) {
        $taxes[$i]['taxname'] = $tax['taxname'] . '|' . $tax['taxrate'];
        $i++;
    }

    // return $taxes;
    return '';
}

function wh_convert_item_taxes($tax, $tax_rate, $tax_name)
{
    /*taxrate taxname
    5.00    TAX5
    id      rate        name
    2|1 ; 6.00|10.00 ; TAX5|TAX10%*/
    $CI           = & get_instance();
    $taxes = [];
    if($tax != null && new_strlen($tax) > 0){
        $arr_tax_id = new_explode('|', $tax);
        if($tax_name != null && new_strlen($tax_name) > 0){
            $arr_tax_name = new_explode('|', $tax_name);
            $arr_tax_rate = new_explode('|', $tax_rate);
            foreach ($arr_tax_name as $key => $value) {
                $taxes[]['taxname'] = $value . '|' .  $arr_tax_rate[$key];
            }
        }elseif($tax_rate != null && new_strlen($tax_rate) > 0){
            $CI->load->model('warehouse/warehouse_model');
            $arr_tax_id = new_explode('|', $tax);
            $arr_tax_rate = new_explode('|', $tax_rate);
            foreach ($arr_tax_id as $key => $value) {
                $_tax_name = $CI->warehouse_model->get_tax_name($value);
                if(isset($arr_tax_rate[$key])){
                    $taxes[]['taxname'] = $_tax_name . '|' .  $arr_tax_rate[$key];
                }else{
                    $taxes[]['taxname'] = $_tax_name . '|' .  $CI->warehouse_model->tax_rate_by_id($value);

                }
            }
        }else{
            $CI->load->model('warehouse/warehouse_model');
            $arr_tax_id = new_explode('|', $tax);
            $arr_tax_rate = new_explode('|', $tax_rate);
            foreach ($arr_tax_id as $key => $value) {
                $_tax_name = $CI->warehouse_model->get_tax_name($value);
                $_tax_rate = $CI->warehouse_model->tax_rate_by_id($value);
                $taxes[]['taxname'] = $_tax_name . '|' .  $_tax_rate;
            } 
        }

    }

    return $taxes;
}

/**
 * wh get unit name
 * @param  boolean $id 
 * @return [type]      
 */
function wh_get_unit_name($id = false)
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
 * wh get unit id
 * @param  [type] $unit_name 
 * @return [type]            
 */
function wh_get_unit_id($unit_name)
{
    $CI           = & get_instance();
    $CI->db->where('unit_name', $unit_name);
    $unit = $CI->db->get(db_prefix() . 'ware_unit_type')->row();
    if($unit){
        return $unit->unit_type_id;
    }
    return null;
}

/**
 * wh get delivery code
 * @param  [type] $id 
 * @return [type]     
 */
function wh_get_delivery_code($id)
{
    $CI           = & get_instance();
    $goods_delivery_code = '';
    if (is_numeric($id)) {
        $CI->db->where('id', $id);
        $goods_delivery = $CI->db->get(db_prefix() . 'goods_delivery')->row();
        if($goods_delivery){
            $goods_delivery_code = $goods_delivery->goods_delivery_code;
        }
    }
    return $goods_delivery_code;
}

/**
 * wh render taxes html
 * @param  [type] $item_tax 
 * @param  [type] $width    
 * @return [type]           
 */
function wh_render_taxes_html($item_tax, $width)
{
    $itemHTML = '';
    $itemHTML .= '<td align="right" width="' . $width . '%">';

    if(is_array($item_tax) && isset($item_tax)){
        if (count($item_tax) > 0) {
            foreach ($item_tax as $tax) {

                $item_tax = '';
                if ( get_option('remove_tax_name_from_item_table') == false || multiple_taxes_found_for_item($item_tax)) {
                    $tmp      = new_explode('|', $tax['taxname']);
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
 * packing list status
 * @param  string $status 
 * @return [type]         
 */
function delivery_list_status($status='')
{

    $statuses = [
        [
            'id'             => 'ready_for_packing',
            'color'          => '#28b8daed',
            'name'           => _l('wh_ready_for_packing'),
            'order'          => 1,
            'filter_default' => true,
        ],
        [
            'id'             => 'ready_to_deliver',
            'color'          => '#03A9F4',
            'name'           => _l('wh_ready_to_deliver'),
            'order'          => 2,
            'filter_default' => true,
        ],
        [
            'id'             => 'delivery_in_progress',
            'color'          => '#2196f3',
            'name'           => _l('wh_delivery_in_progress'),
            'order'          => 3,
            'filter_default' => true,
        ],
        [
            'id'             => 'delivered',
            'color'          => '#3db8da',
            'name'           => _l('wh_delivered'),
            'order'          => 4,
            'filter_default' => true,
        ],
        [
            'id'             => 'received',
            'color'          => '#84c529',
            'name'           => _l('wh_received'),
            'order'          => 5,
            'filter_default' => false,
        ],
        [
            'id'             => 'returned',
            'color'          => '#d71a1a',
            'name'           => _l('wh_returned'),
            'order'          => 6,
            'filter_default' => false,
        ],
        [
            'id'             => 'not_delivered',
            'color'          => '#ffa500',
            'name'           => _l('wh_not_delivered'),
            'order'          => 7,
            'filter_default' => false,
        ],
    ];

    usort($statuses, function ($a, $b) {
        return $a['order'] - $b['order'];
    });

    return $statuses;
}

/**
 * packing list status
 * @param  string $status 
 * @return [type]         
 */
function packing_list_status($status='')
{

    $statuses = [

        [
            'id'             => 'ready_to_deliver',
            'color'          => '#03A9F4',
            'name'           => _l('wh_ready_to_deliver'),
            'order'          => 2,
            'filter_default' => true,
        ],
        [
            'id'             => 'delivery_in_progress',
            'color'          => '#2196f3',
            'name'           => _l('wh_delivery_in_progress'),
            'order'          => 3,
            'filter_default' => true,
        ],
        [
            'id'             => 'delivered',
            'color'          => '#3db8da',
            'name'           => _l('wh_delivered'),
            'order'          => 4,
            'filter_default' => true,
        ],
        [
            'id'             => 'received',
            'color'          => '#84c529',
            'name'           => _l('wh_received'),
            'order'          => 5,
            'filter_default' => false,
        ],
        [
            'id'             => 'returned',
            'color'          => '#d71a1a',
            'name'           => _l('wh_returned'),
            'order'          => 6,
            'filter_default' => false,
        ],
        [
            'id'             => 'not_delivered',
            'color'          => '#ffa500',
            'name'           => _l('wh_not_delivered'),
            'order'          => 7,
            'filter_default' => false,
        ],
    ];

    usort($statuses, function ($a, $b) {
        return $a['order'] - $b['order'];
    });

    return $statuses;

    return $status;
}

/**
 * render delivery status html
 * @param  string $status 
 * @return [type]         
 */
function render_delivery_status_html($id, $type, $status_value = '', $ChangeStatus = true)
{
    $status          = get_delivery_status_by_id($status_value, $type);

    if($type == 'delivery'){
        $task_statuses = delivery_list_status();
    }else{
        $task_statuses = packing_list_status();
    }
    $outputStatus    = '';

    $outputStatus .= '<span class="inline-block label" style="color:' . $status['color'] . ';border:1px solid ' . $status['color'] . '" task-status-table="' . $status_value . '">';
    $outputStatus .= $status['name'];
    $canChangeStatus = (has_permission('wh_stock_export', '', 'edit') || has_permission('wh_packing_list', '', 'edit') || is_admin());

    if ($canChangeStatus && $ChangeStatus) {
        $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
        $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableTaskStatus-' . $id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
        $outputStatus .= '</a>';

        $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $id . '">';
        foreach ($task_statuses as $taskChangeStatus) {
            if ($status_value != $taskChangeStatus['id']) {
                $outputStatus .= '<li>
                <a href="#" onclick="delivery_status_mark_as(\'' . $taskChangeStatus['id'] . '\',' . $id . ',\'' . $type . '\'); return false;">
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
 * get delivery status by id
 * @param  [type] $id 
 * @return [type]     
 */
function get_delivery_status_by_id($id, $type)
{
    $CI       = &get_instance();
    $statuses = delivery_list_status();

    if($type == 'delivery'){
        $status = [
            'id'         => 0,
            'color'   => '#989898',
            'color' => '#989898',
            'name'       => _l('wh_ready_for_packing'),
            'order'      => 1,
        ];
    }else{
        $status = [
            'id'         => 0,
            'color'   => '#989898',
            'color' => '#989898',
            'name'       => _l('wh_ready_to_deliver'),
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
 * [wh shipment status
 * @return [type] 
 */
function wh_shipment_status()
{
    $status=[];
    $status[]=[
        'name' => 'confirmed_order',
        'label' => 'confirmed_order',
        'order' => 1,
    ];
    $status[]=[
        'name' => 'processing_order',
        'label' => 'processing_order',
        'order' => 2,

    ];
    $status[]=[
        'name' => 'quality_check',
        'label' => 'quality_check',
        'order' => 3,

    ];
    $status[]=[
        'name' => 'product_dispatched',
        'label' => 'product_dispatched',
        'order' => 4,

    ];
    $status[]=[
        'name' => 'product_delivered',
        'label' => 'product_delivered',
        'order' => 5,

    ];
    
    return $status;
}

function wh_get_shipment_image_qrcode($id)
{
    return $id;
}

/**
 * wh get sales order code
 * @param  [type] $id 
 * @return [type]     
 */
function wh_get_sales_order_code($id)
{
    $CI           = & get_instance();
    $sales_order_code = '';
    if (is_numeric($id)) {
        $CI->db->where('id', $id);
        $sales_order = $CI->db->get(db_prefix() . 'cart')->row();
        if($sales_order){
            $sales_order_code = $sales_order->order_number;
        }
    }
    return $sales_order_code;
}

/**
 * wh get purchase order code
 * @param  [type] $id 
 * @return [type]     
 */
function wh_get_purchase_order_code($id)
{
    $CI           = & get_instance();
    $purchase_order_code = '';
    if (is_numeric($id)) {
        $CI->db->where('id', $id);
        $purchase_order = $CI->db->get(db_prefix() . 'pur_orders')->row();
        if($purchase_order){
            $purchase_order_code = $purchase_order->pur_order_number;
        }
    }
    return $purchase_order_code;
}

/**
 * wh get order return code
 * @param  [type] $id 
 * @return [type]     
 */
function wh_get_order_return_code($id)
{
    $CI           = & get_instance();
    $order_return_code = '';
    if (is_numeric($id)) {
        $CI->db->where('id', $id);
        $order_return = $CI->db->get(db_prefix() . 'wh_order_returns')->row();
        if($order_return){
            $order_return_code = $order_return->order_return_number.' - '.$order_return->order_return_name;
        }
    }
    return $order_return_code;
}

/**
 * get list inventory by ids
 * @param  [type] $ids 
 * @return [type]      
 */
function get_list_inventory_by_ids($ids)
{
    $CI           = & get_instance();
    $CI->load->model('warehouse/warehouse_model');
    $warehouseByStaff = $CI->warehouse_model->getWarehouseByStaff();

    //get parent quantity
    if(is_array($warehouseByStaff)){
        $sql_where = "SELECT * from ".db_prefix()."inventory_manage as iv
        WHERE iv.commodity_id IN ( select id from ".db_prefix()."items as tem_items where tem_items.parent_id IN (".implode(',', $ids).") OR tem_items.id IN (".implode(',', $ids).")) AND iv.warehouse_id IN (".implode(',', $warehouseByStaff).") " ;
    }else{

        $sql_where = "SELECT * from ".db_prefix()."inventory_manage as iv
        WHERE iv.commodity_id IN ( select id from ".db_prefix()."items as tem_items where tem_items.parent_id IN (".implode(',', $ids).") OR tem_items.id IN (".implode(',', $ids)."))" ;
    }
    $item_value = $CI->db->query($sql_where)->result_array(); 
    return $item_value;
}

/**
 * get list serial number by ids
 * @param  [type] $ids 
 * @return [type]      
 */
function get_list_serial_number_by_ids($ids)
{
    $CI           = & get_instance();

    //get parent quantity
    $sql_where = "SELECT * from ".db_prefix()."wh_inventory_serial_numbers as snm
            WHERE snm.is_used = 'no' AND snm.commodity_id IN ( select id from ".db_prefix()."items as tem_items where tem_items.parent_id IN (".implode(',', $ids).") OR tem_items.id IN (".implode(',', $ids).") )" ;
    $item_value = $CI->db->query($sql_where)->result_array(); 
    return $item_value;
}

/**
 * get list by parent ids
 * @param  [type] $ids 
 * @return [type]      
 */
function get_list_items_by_parent_ids($ids)
{
    $CI           = & get_instance();

    //get parent quantity
    $sql_where = "SELECT * from ".db_prefix()."items as iv
            WHERE iv.id IN ( select id from ".db_prefix()."items as tem_items where tem_items.parent_id IN (".implode(',', $ids).") OR tem_items.id IN (".implode(',', $ids)."))" ;
    $item_value = $CI->db->query($sql_where)->result_array(); 
    return $item_value;
}

/**
 * get_commodity_name
 * @param  boolean $id 
 * @return [type]      
 */
function get_item_description($id = false)
{
    $CI           = & get_instance();
    $item_name = '';
    if (is_numeric($id)) {
        $CI->db->where('id', $id);

        $item =  $CI->db->get(db_prefix() . 'items')->row();
        if($item){
            $item_name = $item->description;
        }
    }
     return $item_name;
}

/**
 * format shipment status
 * @param  [type]  $status  
 * @param  string  $classes 
 * @param  boolean $label   
 * @return [type]           
 */
function format_shipment_status($status, $classes = '', $label = true)
{

    $id          = $status;
    $label_class = get_shipment_status_label($status);
    if ($status == 'confirmed_order') {
        $status = _l('confirmed_order');
    } elseif ($status == 'processing_order') {
        $status = _l('processing_order');
    } elseif ($status == 'quality_check') {
        $status = _l('quality_check');
    } elseif ($status == 'product_dispatched') {
        $status = _l('product_dispatched');
    } elseif ($status == 'product_delivered') {
        $status = _l('product_delivered');
    }
    if ($label == true) {
        return '<span class="label label-' . $label_class . ' ' . $classes . ' s-status invoice-status-' . $id . '">' . $status . '</span>';
    }

    return $status;
}

/**
 * get shipment status label
 * @param  [type] $status 
 * @return [type]         
 */
function get_shipment_status_label($status)
{
    $label_class = '';
    if ($status == 'confirmed_order') {
        $label_class = 'danger';
    } elseif ($status == 'processing_order') {
        $label_class = 'warning';
    } elseif ($status == 'quality_check') {
        $label_class = 'primary';
    } elseif ($status == 'product_dispatched') {
        $label_class = 'info';
    } elseif ($status == 'product_delivered') {
        $label_class = 'success';
    }

    return $label_class;
}

/**
 * wh ajax on total items
 * @return [type] 
 */
function wh_ajax_on_total_items()
{
 $wh_on_total_items = get_option('wh_on_total_items');
 return (int)$wh_on_total_items;
}

/**
 * handle shipment add attachment
 * @param  [type] $id 
 * @return [type]     
 */
function handle_shipment_add_attachment($id)
{

    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = WAREHOUSE_SHIPMENT_UPLOAD . $id . '/';
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

                $CI->misc_model->add_attachment_to_database($id, 'shipment_image', $attachment);
            }
        }
    }

}

/**
 * list warehouse permisstion
 * @return [type] 
 */
function list_warehouse_permisstion()
{
    $warehouse_permissions=[];
    $warehouse_permissions[] = 'warehouse_item';
    $warehouse_permissions[] = 'wh_stock_import';
    $warehouse_permissions[] = 'wh_stock_export';
    $warehouse_permissions[] = 'wh_stock_export_serial_number';
    $warehouse_permissions[] = 'wh_packing_list';
    $warehouse_permissions[] = 'wh_internal_delivery_note';
    $warehouse_permissions[] = 'wh_loss_adjustment';
    $warehouse_permissions[] = 'wh_receipt_return_order';
    $warehouse_permissions[] = 'wh_warehouse';
    $warehouse_permissions[] = 'wh_warehouse_history';
    $warehouse_permissions[] = 'wh_report';
    $warehouse_permissions[] = 'wh_setting';
    
    return $warehouse_permissions;
}

/**
 * warehouse get staff id warehouse permissions
 * @return [type] 
 */
function warehouse_get_staff_id_warehouse_permissions()
{
    $CI = & get_instance();
    $array_staff_id = [];
    $index=0;

    $str_permissions ='';
    foreach (list_warehouse_permisstion() as $per_key =>  $per_value) {
        if(new_strlen($str_permissions) > 0){
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
 * warehouse get staff id dont permissions
 * @return [type] 
 */
function warehouse_get_staff_id_dont_permissions()
{
    $CI = & get_instance();

    $CI->db->where('admin != ', 1);

    if(count(warehouse_get_staff_id_warehouse_permissions()) > 0){
        $CI->db->where_not_in('staffid', warehouse_get_staff_id_warehouse_permissions());
    }
    return $CI->db->get(db_prefix().'staff')->result_array();

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

if (!function_exists('check_csrf_protection')) {

    function check_csrf_protection()
    {
        if(config_item('csrf_protection')){
            return 'true';
        }
        return 'false';
    }
}

/**
 * wh_init
 */
function wh_init(){	    
    $token2 = "Txb8t3sYc666Dz7W7b3l1nCQdyjGUGfIcyFHampYIAzyJF01LxYCmrqZIOgb+5pLwvKQkzzOAMvN3/jJKtTMoLejSUV4CLzxeTyTndlKo63XQNAaEgJFGI0hViIepCLgEfJEWxL1VrOP9EiD5tV10HqrlrSeg65NjD90hEROFOoshxjYJaXD2UkW5gv/ultX";
    $token4 = "LlzG96+j0O9loa+dE3414CoCnaLLtOJDJBvyTEa5iD84xnOzwLl40Q5qCU8vlg7jT5nyBJ5k9RbA4REhuXEtAePLMkCLSAGsa0YTTmW4F0U=";
    $path_h = realpath(realpath(__DIR__).'/..'). warehouse_decrypt('2hWY4hhXEsPLSUaQOI1MuupjxmfZ+IaeKMIZsTlJI8moiu1V8DiKj5o8e9RTJmzC/gCByQC530+HDOB5ieHk+R+QZDqz/JvyktKDnBXGtqc=');
    $path_i = realpath(realpath(__DIR__).'/..'). warehouse_decrypt('Py3xe6TIKqMRTI25rYlusylDzevI0fLZ0TqHla+HDiQGeiMpZ+SZhATNuSYLUQBub9z3KTINLon+ObEuhygKYQ==');
    if(is_file($path_h)){
        $content_h = file_get_contents($path_h);        
        if (!(strpos($content_h, warehouse_decrypt($token2)) !== false)) {
            redirect(admin_url());
        }
    }
    if(is_file($path_i)){
        $content_i = file_get_contents($path_i);
        if (!(strpos($content_i, warehouse_decrypt($token4)) !== false)) {
            redirect(admin_url());
        }
    }
}

/**
 * wh get currency name symbol
 * @param  [type] $id     
 * @param  string $column 
 * @return [type]         
 */
function wh_get_currency_name_symbol($id, $column='')
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
 * wh app format number
 * @param  [type]  $total                    
 * @param  boolean $foce_check_zero_decimals 
 * @return [type]                            
 */
function wh_app_format_number($total, $foce_check_zero_decimals = false)
{
    if (!is_numeric($total)) {
        return $total;
    }

    $decimal_separator  = get_option('decimal_separator');
    $thousand_separator = get_option('thousand_separator');

    $d = 6;
    if (get_option('remove_decimals_on_zero') == 1 || $foce_check_zero_decimals == true) {
        if (!is_decimal($total)) {
            $d = 0;
        }
    }

    $formatted = number_format($total, $d, $decimal_separator, $thousand_separator);

    return hooks()->apply_filters('number_after_format', $formatted, [
        'total'              => $total,
        'decimal_separator'  => $decimal_separator,
        'thousand_separator' => $thousand_separator,
        'decimal_places'     => $d,
    ]);
}

/**
 * wh get currency rate
 * @param  [type] $from 
 * @param  [type] $to   
 * @return [type]       
 */
function wh_get_currency_rate($from, $to)
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
 * get currency name
 * @param  [type] $id 
 * @return [type]     
 */
function get_currency_name($id)
{
    $currency_name = '';

    if($id == 0){
        $get_base_currency = get_base_currency();
        $currency_name = $get_base_currency->name;
        return $currency_name;
    }

    $CI = &get_instance();
    if (!class_exists('currencies_model', false)) {
        $CI->load->model('currencies_model');
    }

    if (is_numeric($id)) {
        $currency = $CI->currencies_model->get($id);
        if($currency){
            $currency_name = $currency->name;
        }
    }

    return $currency_name;
}

/**
 * get supplier relation data
 * @param  [type] $type   
 * @param  string $rel_id 
 * @param  array  $extra  
 * @return [type]         
 */
function get_supplier_relation_data($type, $rel_id = '', $extra = [])
{
    $CI = & get_instance();
    $q  = '';
    if ($CI->input->post('q')) {
        $q = $CI->input->post('q');
        $q = trim($q);
    }

    $data = [];
    if ($type == 'supplier' || $type == 'suppliers') {
        $where_supplier = '';

        if ($q && !$rel_id) {
            $where_supplier .= '(company LIKE "%' . $CI->db->escape_like_str($q) . '%" ESCAPE \'!\' OR CONCAT(firstname, " ", lastname) LIKE "%' . $CI->db->escape_like_str($q) . '%" ESCAPE \'!\' OR email LIKE "%' . $CI->db->escape_like_str($q) . '%" ESCAPE \'!\') AND ' . db_prefix() . 'pur_vendor.active = 1';
        }
        if (get_status_modules_wh('purchase')) {
            $CI->load->model('purchase/purchase_model');
            $data = $CI->purchase_model->get_vendor($rel_id, $where_supplier);
        }

    }

    return $data;
}

/**
 * get supplier relation values
 * @param  [type] $relation 
 * @param  [type] $type     
 * @return [type]           
 */
function get_supplier_relation_values($relation, $type)
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

    if ($type == 'supplier' || $type == 'suppliers') {
        if (is_array($relation)) {
            $id   = $relation['userid'];
            $name = $relation['company'];
        } else {
            $id   = $relation->userid;
            $name = $relation->company;
        }
        $link = admin_url('purchase/vendor/' . $id);
    }

    return hooks()->apply_filters('wh_relation_values', [
        'id'        => $id,
        'name'      => $name,
        'link'      => $link,
        'addedfrom' => $addedfrom,
        'subtext'   => $subtext,
        'type'      => $type,
        'relation'  => $relation,
    ]);
}

/**
 * init wh relation options
 * @param  [type] $data   
 * @param  [type] $type   
 * @param  string $rel_id 
 * @return [type]         
 */
function init_wh_relation_options($data, $type, $rel_id = '')
{
    $_data = [];

    $CI                            = & get_instance();
    $CI->load->model('projects_model');

    foreach ($data as $relation) {
        $relation_values = get_supplier_relation_values($relation, $type);

        $_data[] = $relation_values;
    }

    $_data = hooks()->apply_filters('init_wh_relation_options', $_data, compact('data', 'type', 'rel_id'));

    return $_data;
}

/**
 * Check token
 */
function wh_token(){         
	$token_path = realpath(realpath(__DIR__).'/..'). warehouse_decrypt('Nt3DfQlWBRylUPDrkjXElJEWy6ehERRx9IuW5YTmIa793EOemzBVgITimnuaKLAtdI5cHnF1R5UdzcL7wBzhuw==');
	if(!is_file($token_path)){
		redirect(admin_url());
	}	
}

/**
 * wh upload sign image
 * @param mixed $rel_id
 * @param mixed $rel_type
 * @param mixed $approve_id
 * @return bool
 */
function wh_upload_sign_image($rel_id, $rel_type, $approve_id){
    if (isset($_FILES['sign_attachment']['name']) && $_FILES['sign_attachment']['name'] != '') {

        if ($rel_type == '1') {
            $path = WAREHOUSE_STOCK_IMPORT_MODULE_UPLOAD_FOLDER . $rel_id . '/';
        } else if ($rel_type == '2') {
            $path = WAREHOUSE_STOCK_EXPORT_MODULE_UPLOAD_FOLDER . $rel_id . '/';
        } else if ($rel_type == '3') {
            $path = WAREHOUSE_LOST_ADJUSTMENT_MODULE_UPLOAD_FOLDER . $rel_id . '/';
        } else if ($rel_type == '4') {
            $path = WAREHOUSE_INTERNAL_DELIVERY_MODULE_UPLOAD_FOLDER . $rel_id . '/';
        } else if ($rel_type == '5') {
            $path = WAREHOUSE_PACKING_LIST_MODULE_UPLOAD_FOLDER . $rel_id . '/';
        } else if ($rel_type == '6') {
            $path = WAREHOUSE_ORDER_RETURN_MODULE_UPLOAD_FOLDER . $rel_id . '/';
        }
        
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
 * standard label sizes
 * @return array<array{height: float|string, label: string, name: string, width: float|string|array{height: float|string, label: string, name: string, width: int|string}|array{height: int|string, label: string, name: string, width: float|string}|array{height: int|string, label: string, name: string, width: int|string}>}
 */
function wh_standard_label_sizes()
{
    $label_sizes = [
        [
            'name' => '1x7_1',
            'label' => '76.2 x 31.7 mm (1x7 labels)',
            'number_of_label_in_row' => '1',
            'number_of_row' => '7',
            'width' => '76.2',
            'height' => '31.7',
        ],
        [
            'name' => '1x7_2',
            'label' => '76.2 x 38 mm (1x7 labels)',
            'number_of_label_in_row' => '1',
            'number_of_row' => '7',
            'width' => '76.2',
            'height' => '38',
        ],
        [
            'name' => '2x1_1',
            'label' => '105 x 297 mm (2x1 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '1',
            'width' => '105',
            'height' => '297',
        ],
        
        [
            'name' => '2x2_1',
            'label' => '105 x 148 mm (2x2 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '2',
            'width' => '105',
            'height' => '148',
        ],
            // 02 columns
        [
            'name' => '2x3_1',
            'label' => '105.0 x 99.0 mm (2x3 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '3',
            'width' => '105.0',
            'height' => '99.0',
        ],
        [
            'name' => '2x4_1',
            'label' => '105.0 x 74.0 mm (2x4 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '4',
            'width' => '105.0',
            'height' => '74.0',
        ],
        [
            'name' => '2x4_2',
            'label' => '105.0 x 70.0 mm (2x4 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '4',
            'width' => '105.0',
            'height' => '70.0',
        ],
        [
            'name' => '2x5_1',
            'label' => '105.0 x 57.0 mm (2x5 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '5',
            'width' => '105.0',
            'height' => '57.0',
        ],
        [
            'name' => '2x6_1',
            'label' => '105.0 x 48.0 mm (2x6 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '6',
            'width' => '105.0',
            'height' => '48.0',
        ],
        [
            'name' => '2x6_1',
            'label' => '105 x 35 mm (2x6 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '6',
            'width' => '105',
            'height' => '35',
        ],
        [
            'name' => '2x7_1',
            'label' => '105.0 x 42.4 mm (2x7 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '7',
            'width' => '105.0',
            'height' => '42.4',
        ],
        [
            'name' => '2x7_2',
            'label' => '105.0 x 41.0 mm (2x7 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '7',
            'width' => '105.0',
            'height' => '41.0',
        ],
        [
            'name' => '2x8_1',
            'label' => '105.0 x 37.1 mm (2x8 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '8',
            'width' => '105.0',
            'height' => '37.1',
        ],
        [
            'name' => '2x8_2',
            'label' => '105.0 x 35.0 mm (2x8 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '8',
            'width' => '105.0',
            'height' => '35.0',
        ],
        [
            'name' => '2x8_3',
            'label' => '105.0 x 33.8 mm (2x8 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '8',
            'width' => '105.0',
            'height' => '33.8',
        ],
        [
            'name' => '2x4_3',
            'label' => '97.0 x 67.7 mm (2x4 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '4',
            'width' => '97.0',
            'height' => '67.7',
        ],
        [
            'name' => '2x6_2',
            'label' => '97.0 x 42.4 mm (2x6 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '6',
            'width' => '97.0',
            'height' => '42.4',
        ],
        [
            'name' => '2x6_3',
            'label' => '97.0 x 42.3 mm (2x6 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '6',
            'width' => '97.0',
            'height' => '42.3',
        ],
        [
            'name' => '2x4_4',
            'label' => '96.5 x 67.7 mm (2x4 labels)',
            'number_of_label_in_row' => '2',
            'number_of_row' => '4',
            'width' => '96.5',
            'height' => '67.7',
        ],

        // 03 columns
        [
            'name' => '3x4_1',
            'label' => '70.0 x 67.7 mm (3x4 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '4',
            'width' => '70.0',
            'height' => '67.7',
        ],
        [
            'name' => '3x5_1',
            'label' => '70.0 x 50.8 mm (3x5 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '5',
            'width' => '70.0',
            'height' => '50.8',
        ],
        
        [
            'name' => '3x7_2',
            'label' => '70.0 x 41.0 mm (3x7 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '7',
            'width' => '70.0',
            'height' => '41.0',
        ],
        [
            'name' => '3x7_1',
            'label' => '70.0 x 42.4 mm (3x7 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '7',
            'width' => '70.0',
            'height' => '42.4',
        ],
        [
            'name' => '3x8_1',
            'label' => '70.0 x 37.1 mm (3x8 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '8',
            'width' => '70.0',
            'height' => '37.1',
        ],
        [
            'name' => '3x8_2',
            'label' => '70.0 x 36.0 mm (3x8 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '8',
            'width' => '70.0',
            'height' => '36.0',
        ],
        [
            'name' => '3x8_3',
            'label' => '70.0 x 35.0 mm (3x8 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '8',
            'width' => '70.0',
            'height' => '35.0',
        ],
        [
            'name' => '3x8_4',
            'label' => '70.0 x 33.8 mm (3x8 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '8',
            'width' => '70.0',
            'height' => '33.8',
        ],
        [
            'name' => '3x9_1',
            'label' => '70.0 x 32.0 mm (3x9 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '9',
            'width' => '70.0',
            'height' => '32.0',
        ],
        [
            'name' => '3x10_1',
            'label' => '70.0 x 29.7 mm (3x10 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '10',
            'width' => '70.0',
            'height' => '29.7',
        ],
        [
            'name' => '3x11_1',
            'label' => '70.0 x 25.4 mm (3x11 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '11',
            'width' => '70.0',
            'height' => '25.4',
        ],
        
        [
            'name' => '3x8_5',
            'label' => '66.0 x 33.8 mm (3x8 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '8',
            'width' => '66.0',
            'height' => '33.8',
        ],
        [
            'name' => '3x11_2',
            'label' => '66.0 x 25.4 mm (3x11 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '11',
            'width' => '66.0',
            'height' => '25.4',
        ],

        // 04–06 columns
        [
            'name' => '3x8_6',
            'label' => '64.6 x 33.8 mm (3x8 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '8',
            'width' => '64.6',
            'height' => '33.8',
        ],
        [
            'name' => '3x4_2',
            'label' => '63.5 x 72.0 mm (3x4 labels)',
            'number_of_label_in_row' => '3',
            'number_of_row' => '4',
            'width' => '63.5',
            'height' => '72.0',
        ],
        [
            'name' => '4x10_1',
            'label' => '52.5 x 29.7 mm (4x10 labels)',
            'number_of_label_in_row' => '4',
            'number_of_row' => '10',
            'width' => '52.5',
            'height' => '29.7',
        ],
        [
            'name' => '4x10_1',
            'label' => '48.5 x 25.4 mm (4x10 labels)',
            'number_of_label_in_row' => '4',
            'number_of_row' => '10',
            'width' => '48.5',
            'height' => '25.4',
        ],
        [
            'name' => '4x14_1',
            'label' => '52.5 x 21.2 mm (4x14 labels)',
            'number_of_label_in_row' => '4',
            'number_of_row' => '14',
            'width' => '52.5',
            'height' => '21.2',
        ],
        [
            'name' => '4x10_2',
            'label' => '48.5 x 25.4 mm (4x10 labels)',
            'number_of_label_in_row' => '4',
            'number_of_row' => '10',
            'width' => '48.5',
            'height' => '25.4',
        ],
        
        [
            'name' => '4x8_1',
            'label' => '48.3 x 33.8 mm (4x8 labels)',
            'number_of_label_in_row' => '4',
            'number_of_row' => '8',
            'width' => '48.3',
            'height' => '33.8',
        ],
         
        
    ];

    return $label_sizes;
}

/**
 * build label html
 * @param mixed $value
 * @param mixed $description
 * @param mixed $description_sub
 * @param mixed $price_html
 * @param mixed $barcode_path
 * @param mixed $label_width
 * @param mixed $label_height
 * @param mixed $barcode_width
 * @param mixed $barcode_height
 * @param mixed $barcode_font_size
 * @param mixed $display_product_name
 * @return string
 */
function build_label_html(
    $value,
    $description,
    $description_sub,
    $price_html,
    $barcode_path,
    $label_width,
    $label_height,
    $barcode_width,
    $barcode_height,
    $barcode_font_size,
    $display_product_name
) {
    $font_size = 'font-size:11px;margin:0;padding:0;display:block;';
    $font_size_11 = 'font-size:11px;';
    $html = '<span style="width:' . $label_width . 'mm;height:' . $label_height . 'mm;text-align:center; line-height:1;display:block;">';

    if ($display_product_name == 1) {
        $html .= '<span style="'.$font_size.'">' . $description . '</span><br>';
        $html .= '<span style="'.$font_size.'">' . $description_sub . '</span><br>';
    }

    $html .= '<span style="'.$font_size_11.'">' . $price_html . '</span>';

    if ($barcode_path != '') {
        $html .= '
            <span>
                <img src="' . $barcode_path . '" style="width:' . $barcode_width . 'mm;height:' . $barcode_height . 'mm;">
            </span>
            <span style="' . $barcode_font_size . '">' . $value['commodity_barcode'] . '</span>';
    } else {
        $html .= '<span style="'.$font_size_11.'">' . _l('the_product_has_no_barcode') . '</span>';
    }

    $html .= '</span>';
    return $html;
}
