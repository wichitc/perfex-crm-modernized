<?php
defined('BASEPATH') or exit('No direct script access allowed');
hooks()->add_action('pre_activate_module', ACCOUNTING_MODULE_NAME.'_preactivate');
hooks()->add_action('pre_deactivate_module', ACCOUNTING_MODULE_NAME.'_predeactivate');
/**
 * get status modules wh
 * @param  string $module_name 
 * @return boolean             
 */
if(!function_exists('acc_get_status_modules')){
	function acc_get_status_modules($module_name){
		$CI             = &get_instance();

		$sql = 'select * from '.db_prefix().'modules where module_name = "'.$module_name.'" AND active =1 ';
		$module = $CI->db->query($sql)->row();
		if($module){
			return true;
		}else{
			return false;
		}
	}
}

/**
 * check account exists
 * @param  string $key_name 
 * @return boolean or integer           
 */
function acc_account_exists($key_name){
	$CI             = &get_instance();

	$CI->load->model('accounting/accounting_model');

	if(get_option('acc_add_default_account') == 0){
        $CI->accounting_model->add_default_account();
    }

    if(get_option('acc_add_default_account_new') == 0){
        $CI->accounting_model->add_default_account_new();
    }

	$sql = 'select * from '.db_prefix().'acc_accounts where key_name = "'.$key_name.'"';
	$account = $CI->db->query($sql)->row();

	if($account){
		return $account->id;
	}else{
		return false;
	}
}

/**
 * decrypt
 * @param  string $data
 * @return string
 */
function accounting_decrypt($data) {
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
 * Gets the account type by name.
 *
 * @param        $name   The name
 */
function get_account_type_by_name($name){
	$CI             = &get_instance();
	$CI->load->model('accounting/accounting_model');
	$account_types = $CI->accounting_model->get_account_types();
	
	foreach($account_types as $type){
		if(trim(strtoupper($type['name'])) == trim(strtoupper($name))){
			return $type['id'];
		}
	}

	return false;
}

/**
 * Gets the account type by name.
 *
 * @param        $name   The name
 */
function get_account_sub_type_by_name($name){
	$CI             = &get_instance();
	$CI->load->model('accounting/accounting_model');
	$account_sub_types = $CI->accounting_model->get_account_type_details();

	foreach($account_sub_types as $type){
		if(trim(strtoupper($type['name'])) == trim(strtoupper($name))){
			return $type['id'];
		}
	}

	return false;
}

/**
 * Gets the account by name.
 *
 * @param        $name     The name
 */
function get_account_by_name($name){
	$CI             = &get_instance();
	$CI->db->where('name', $name);
	$CI->db->where('name IS NOT NULL');
	$CI->db->where('name <> ""');

	$account = $CI->db->get(db_prefix().'acc_accounts')->row();

	if($account){
		return $account->id;
	}
	return false;
}

/**
 * Gets the account type by id.
 *
 * @param        $id   The id
 */
function get_account_type_by_id($id){
	$CI             = &get_instance();
	$CI->load->model('accounting/accounting_model');
	$account_types = $CI->accounting_model->get_account_types();

	foreach($account_types as $type){
		if($type['id'] == $id){
			return $type['id'];
		}
	}

	return false;
}
/**
 * Gets the account type by id.
 *
 * @param        $id   The id
 */
function get_account_sub_type_by_id($id){
	$CI             = &get_instance();
	$CI->load->model('accounting/accounting_model');
	$account_sub_types = $CI->accounting_model->get_account_type_details();

	foreach($account_sub_types as $type){
		if($type['id'] == $id){
			return $type['id'];
		}
	}

	return false;
}

/**
 * Gets the account by identifier.
 *
 * @param        $id     The identifier
 */
function get_account_by_id($id){
	$CI             = &get_instance();
	$CI->db->where('id', $id);
	$account = $CI->db->get(db_prefix().'acc_accounts')->row();

	if($account){
		return $id;
	}
	return false;
}


/**
 * Gets the url by type identifier.
 */
function get_url_by_type_id($rel_type, $rel_id){
	$url = '';
	switch ($rel_type) {
        case 'invoice':
            $url = admin_url('invoices/list_invoices/'.$rel_id);
        break;

        case 'bill':
            $url = admin_url('accounting/bills#'.$rel_id);
        break;

        case 'expense':
            $url = admin_url('expenses/list_expenses/'.$rel_id);
        break;

        case 'pay_bill':
            $url = admin_url('accounting/pay_bill/'.$rel_id);
        break;

        case 'payment':
            $url = admin_url('payments/payment/'.$rel_id);
        break;

        case 'journal_entry':
            $url = admin_url('accounting/new_journal_entry/'.$rel_id);
        break;

        case 'user_register_transaction':
            $url = admin_url('accounting/user_register_view/'.$rel_id);
        break;

        case 'transfer':
            $url = admin_url('accounting/transfer?transfer_id='.$rel_id);
        break;
        
        case 'check':
            $url = admin_url('accounting/checks#'.$rel_id);
        break;

        case 'purchase_order':
            $url = admin_url('purchase/purchase_order/'.$rel_id);
        break;

        case 'purchase_invoice':
            $url = admin_url('purchase/purchase_invoice/'.$rel_id);
        break;


        case 'purchase_payment':
            $url = admin_url('purchase/payment_invoice/'.$rel_id);
        break;
       
        case 'purchase_return_order':
            $url = admin_url('purchase/order_returns#'.$rel_id);
        break;

        case 'purchase_refund':
            $url = admin_url('purchase/order_returns');
        break;

        case 'stock_import':
            $url = admin_url('warehouse/edit_purchase/'.$rel_id);
        break;

        case 'stock_export':
            $url = admin_url('warehouse/edit_delivery/'.$rel_id);
        break;

        case 'loss_adjustment':
            $url = admin_url('warehouse/view_lost_adjustment/'.$rel_id);
        break;

        case 'manufacturing_order':
            $url = admin_url('manufacturing/view_manufacturing_order/'.$rel_id);
        break;

        case 'fe_asset':
            $url = admin_url('fixed_equipment/detail_asset/'.$rel_id.'?tab=details');
        break;

        case 'fe_license':
            $url = admin_url('fixed_equipment/detail_licenses/'.$rel_id.'?tab=details');
        break;

        case 'fe_component':
            $url = admin_url('fixed_equipment/detail_components/'.$rel_id);
        break;

        case 'fe_consumable':
            $url = admin_url('fixed_equipment/detail_consumables/'.$rel_id);
        break;

        case 'payslip':
            $url = admin_url('hr_payroll/view_payslip_detail/'.$rel_id);
        break;

        case 'sales_return_order':
            $url = admin_url('omni_sales/view_order_detailt/'.$rel_id);
        break;

        case 'customer':
            $url = admin_url('clients/client/'.$rel_id);
        break;
    }

    return $url;
}


/**
 * { bill amount left }
 *
 * @param        $id     The identifier
 *
 *       
 */
function bill_amount_left($id, $format = true){
	$CI = &get_instance();
    $totalPayments = 0;
    $total = 0;

    $CI->load->model('accounting/accounting_model');
    $bill = $CI->accounting_model->get_bill($id);

    if($bill){
    	$total = $bill->amount;
    }

    $CI->db->where('bill', $id);
    $check_details = $CI->db->get(db_prefix().'acc_check_details')->result_array();

    foreach ($check_details as $detail) {
    	$CI->db->where('id', $detail['check_id']);
    	$check = $CI->db->get(db_prefix().'acc_checks')->row();
    	if($check && $check->amount > 0 && $check->issue != 3){
        	$totalPayments += $check->amount;
        }
    }

	$CI->db->where('bill_id', $id);
    $pay_bill_details = $CI->db->get(db_prefix().'acc_pay_bill_details')->result_array();
    foreach ($pay_bill_details as $detail) {
	    $CI->db->where('id', $detail['pay_bill']);
	    $pay_bill = $CI->db->get(db_prefix().'acc_pay_bills')->row();
    	if($pay_bill && $pay_bill->amount > 0){
        	$totalPayments += $pay_bill->amount;
        }
    }

    if($total <= $totalPayments){
    	return 0;
    }else{
    	if($format == true){
    		return acc_format_number($total - $totalPayments);
    	}else{
    		return round(($total - $totalPayments), 2);
    	}
    }
}


/**
 * { bill status html }
 *
 * @param      int     $status  The status
 *
 * @return     string  ( description_of_the_return_value )
 */
function bill_status_html($id){
	$CI = &get_instance();

	$status_name = '';
	$label_class = '';
    
    $CI->load->model('accounting/accounting_model');
    $bill = $CI->accounting_model->get_bill($id);
    $total = $bill->amount;
    
	$amount_left = bill_amount_left($id);
	
	if($bill->voided == 1){
		$status_name = _l('void');
		$label_class = 'danger';
	}elseif($bill->status == 0){
		$status_name = _l('acc_unpaid');
		$label_class = 'danger';
	}elseif($bill->status == 2){
		$label_class = 'success';
		$status_name = _l('acc_paid');
	}elseif($bill->status == 3){
		$label_class = 'warning';
		$status_name = _l('invoice_status_not_paid_completely');
	}

	return '<span class="label label-' . $label_class . ' s-status">' . $status_name . '</span>';
}


/**
 * Used in:
 * Search contact tickets
 * Project dropdown quick switch
 * Calendar tooltips
 * @param  [type] $userid [description]
 * @return [type]         [description]
 */
function acc_get_vendor_name($vendor_id, $prevent_empty_company = false)
{
    
    $CI = &get_instance();

    $select = 'company';

    $vendor = $CI->db->select($select)
        ->where('userid', $vendor_id)
        ->from(db_prefix() . 'pur_vendor')
        ->get()
        ->row();
    if ($vendor) {
        return $vendor->company;
    }

    return '';
}


/**
 * Gets the account name by identifier.
 *
 * @param        $id     The identifier
 */
function get_account_name_by_id($id){
	$CI             = &get_instance();
	$CI->db->where('id', $id);
	$account = $CI->db->get(db_prefix().'acc_accounts')->row();

	if($account){
		$account_name = $account->name;
		$number = ($account->number != '') ? $account->number . ' - ' : '';
        if($account->name == '' && $account->key_name != ''){
            $account_name = $number._l($account->key_name);
        }else{
            $account_name = $number.$account->name;
        }
		return $account_name;
	}
	return '';
}

function acc_format_check_number($check){
	$CI   = & get_instance();
	$bill_ids = [];
	$CI->db->where('id', $check);
	$check = $CI->db->get(db_prefix().'acc_checks')->row();
	if($check){
		return '#'.str_pad($check->number, 4, '0', STR_PAD_LEFT);
	}

	return '';
}


/**
 * { handle pay bill attachments }
 */
function handle_pay_bill_attachments($id){
	if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = ACCOUTING_MODULE_UPLOAD_FOLDER . '/pay_bills/' . $id . '/';
    $CI   = & get_instance();

    if (isset($_FILES['file']['name'])) {
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            accounting_maybe_create_upload_path($path);
            $filename    = $_FILES['file']['name'];
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                    ];

                $CI->misc_model->add_attachment_to_database($id, 'pay_bill', $attachment);
            }
        }
    }
}



function check_import_signature($staffid = ''){
	$CI   = & get_instance();

	if($staffid == ''){
		$staffid = get_staff_user_id();
	}

    $CI->db->where('rel_id', $staffid);
    $CI->db->where('rel_type', 'signature_available');
    $file = $CI->db->get(db_prefix() . 'files')->row();
    $html = '';
    if($file){
    	return true;
    }
    
    return false;
}


function acc_format_organization_info($vendor_id)
{
	$acc_check_type    = get_option('acc_check_type');

	$CI = &get_instance();

	$select = '*';
	$vendor = $CI->db->select($select)
	->where('userid', $vendor_id)
	->from(db_prefix() . 'pur_vendor')
	->get()
	->row();
	if ($vendor && ($acc_check_type == 'type_1' || $acc_check_type == 'type_3' || $acc_check_type == '')) {
		$format = get_option('company_info_format');

		$vat = $vendor->vat;
		$countryCode = '';
        $countryName = '';

        if ($country = get_country($vendor->country)) {
            $countryCode = $country->iso2;
            $countryName = $country->short_name;
        }

		$format = _info_format_replace('company_name', '<b style="color:black" class="company-name-formatted">' .$vendor->company . '</b>', $format);
		$format = _info_format_replace('address', $vendor->address, $format);
		$format = _info_format_replace('city', $vendor->city, $format);
		$format = _info_format_replace('state', $vendor->state, $format);
		$format = _info_format_replace('zip_code', $vendor->zip, $format);
		$format = _info_format_replace('country_code', $countryCode, $format);
		$format = _info_format_replace('country_name', $countryName, $format);
        $format = _info_format_replace('phone', $vendor->phonenumber, $format);
        $format = _info_format_replace('vat_number', $vat, $format);
        $format = _info_format_replace('vat_number_with_label', $vat == '' ? '':_l('company_vat_number') . ': ' . $vat, $format);

		$format = _maybe_remove_first_and_last_br_tag($format);

        // Remove multiple white spaces
		$format = preg_replace('/\s+/', ' ', $format);
		$format = trim($format);

		return $format;
	}
	return '';
}


function load_signature_is_available($staffid = ''){
	$CI   = & get_instance();

	if($staffid == ''){
		$staffid = get_staff_user_id();
	}

    $CI->db->where('rel_id', $staffid);
    $CI->db->where('rel_type', 'signature_available');
    $files = $CI->db->get(db_prefix() . 'files')->result_array();
    $html = '';
    $i = 0;
    foreach($files as $file){
		$path = ACCOUTING_MODULE_UPLOAD_FOLDER . '/signature_is_available/' . $staffid . '/'.$file['file_name'];

		if(file_exists($path)){
			
			$img_url = site_url('download/preview_image?path='.protected_file_url_by_path($path,true).'&type='.$file['filetype']);
			$checked = '';	
			if($i == 0){
				$checked = 'checked';
			}

		   	$html .= '<div class="row">
		         <div class="col-md-1">
				    <label class="radio-inline"><input type="radio" id="signature_available_id" name="signature_available_id" value="'.$file['id'].'" '.$checked.'></label>
		         </div>
		         <div class="col-md-9">
		         <img src="'. $img_url.'" class="img img-responsive img-signature-available-'.$file['id'].'">
		         </div>
		         <div class="col-md-2 text-right">
		            <a href="'. admin_url('accounting/delete_signature_available_attachment/'.$file['id']).'" class="text-danger _delete"><i class="fa fa fa-times"></i></a>
		         </div>
		      </div>';
	      $i++;	
		}
    }


	return $html;
}

/**
 * { handle signature is available }
 */
function handle_signature_is_available($id){
	if (isset($_FILES['file_sign']) && _perfex_upload_error($_FILES['file_sign']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file_sign']['error']);
        die;
    }
    $path = ACCOUTING_MODULE_UPLOAD_FOLDER . '/signature_is_available/' . $id . '/';
    $CI   = & get_instance();
    if (isset($_FILES['file_sign']['name'])) {
        // Get the temp file path
        $tmpFilePath = $_FILES['file_sign']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            accounting_maybe_create_upload_path($path);
            $filename    = time().'_'.$_FILES['file_sign']['name'];
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file_sign']['type'],
                    ];

                $CI->misc_model->add_attachment_to_database($id, 'signature_available', $attachment);
            }
        }
    }
}


/**
 * { accounting process digital signature image }
 *
 * @param      <type>   $partBase64  The part base 64
 * @param      <type>   $path        The path
 * @param      string   $image_name  The image name
 *
 * @return     boolean  
 */
function accounting_process_digital_signature_image($partBase64, $path, $image_name)
{
    if (empty($partBase64)) {
        return false;
    }

    accounting_maybe_create_upload_path($path);
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


function get_bill_ids_of_check($check){
	$CI   = & get_instance();
	$bill_ids = [];
	$CI->db->where('check_id', $check);
	$bills = $CI->db->get(db_prefix().'acc_check_details')->result_array();
	foreach($bills as $bill){
		$bill_ids[] = $bill['bill'];
	}

	return $bill_ids;
}


/**
 * acc get browser name
 * @return [type] 
 */
function acc_get_browser_name()
{
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
   
    return 'Other';

}

/**
 * mrp required purchase module
 * @return [type] 
 */
function acc_required_purchase_module()
{	
	$CI   = & get_instance();

	$sql = 'select * from '.db_prefix().'modules where module_name = "purchase" AND active =1 ';
	$module = $CI->db->query($sql)->row();
	if($module){
		if(version_compare('1.2.9', $module->installed_version, '<=')){
			$result = true;
		}else{
			$result = false;
		}
	}else{
		$result = false;
	}

	return $result;
}

/**
 * mrp required fixed equipment module
 * @return [type] 
 */
function acc_required_fixed_equipment_module()
{	
	$CI   = & get_instance();

	$sql = 'select * from '.db_prefix().'modules where module_name = "fixed_equipment" AND active =1 ';

	$module = $CI->db->query($sql)->row();

	if($module){
		if(version_compare('1.0.3', $module->installed_version, '<=')){
			$result = true;
		}else{
			$result = false;
		}
	}else{
		$result = false;
	}

	return $result;
}

/**
 * mrp required omni sales module
 * @return [type] 
 */
function acc_required_omni_sales_module()
{	
	$CI   = & get_instance();

	$sql = 'select * from '.db_prefix().'modules where module_name = "omni_sales" AND active =1 ';
	$module = $CI->db->query($sql)->row();
	if($module){
		if(version_compare('1.1.6', $module->installed_version, '<=')){
			$result = true;
		}else{
			$result = false;
		}
	}else{
		$result = false;
	}

	return $result;
}


/**
 * mrp required omni sales module
 * @return [type] 
 */
function acc_required_manufacturing_module()
{	
	$CI   = & get_instance();

	$sql = 'select * from '.db_prefix().'modules where module_name = "manufacturing" AND active =1 ';
	$module = $CI->db->query($sql)->row();
	if($module){
		if(version_compare('1.1.6', $module->installed_version, '<=')){
			$result = true;
		}else{
			$result = false;
		}
	}else{
		$result = false;
	}

	return $result;
}

/**
 * Determines whether the specified identifier is empty vendor company.
 *
 * @param      <type>   $id     The identifier
 *
 * @return     boolean  True if the specified identifier is empty vendor company, False otherwise.
 */
if (!function_exists('acc_is_empty_vendor_company')) {
	function acc_is_empty_vendor_company($id)
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


/**
 * Gets the sub account by identifier.
 *
 * @param      <type>  $id     The identifier
 */
function get_sub_account_by_id($id){
	$CI             = &get_instance();

	$where_acc_history = '';
    if($CI->input->post('from_date')){
        $where_acc_history .= ' AND '.db_prefix().'acc_account_history.date >= "'.$CI->input->post('from_date').'"';
    }

    if($CI->input->post('to_date')){
        $where_acc_history .= ' AND '.db_prefix().'acc_account_history.date <= "'.$CI->input->post('to_date').'"';
    }

	$accounting_method = get_option('acc_accounting_method');

    if($accounting_method == 'cash'){
        $debit = '(SELECT sum(debit) as debit FROM '.db_prefix().'acc_account_history where (account = '.db_prefix().'acc_accounts.id or parent_account = '.db_prefix().'acc_accounts.id)'.$where_acc_history.' AND (('.db_prefix().'acc_account_history.rel_type = "invoice" AND '.db_prefix().'acc_account_history.paid = 1) or rel_type != "invoice")) as debit';
        $credit = '(SELECT sum(credit) as credit FROM '.db_prefix().'acc_account_history where (account = '.db_prefix().'acc_accounts.id or parent_account = '.db_prefix().'acc_accounts.id)'.$where_acc_history.' AND (('.db_prefix().'acc_account_history.rel_type = "invoice" AND '.db_prefix().'acc_account_history.paid = 1) or rel_type != "invoice")) as credit';
    }else{
        $debit = '(SELECT sum(debit) as debit FROM '.db_prefix().'acc_account_history where (account = '.db_prefix().'acc_accounts.id or parent_account = '.db_prefix().'acc_accounts.id)'.$where_acc_history.') as debit';
        $credit = '(SELECT sum(credit) as credit FROM '.db_prefix().'acc_account_history where (account = '.db_prefix().'acc_accounts.id or parent_account = '.db_prefix().'acc_accounts.id)'.$where_acc_history.') as credit';
    }

	$CI->db->select('*,'.$debit.', '.$credit);
	$CI->db->where('parent_account', $id);
	return $CI->db->get(db_prefix().'acc_accounts')->result_array();
}


/**
 * user register transaction label
 * @param  [type] $account_id 
 * @return [type]             
 */
function user_register_transaction_label($account_id)
{
	$payment_label = _l('credit'); //minus
	$deposit_label = _l('debit'); //plus

	$account_type_to_label=[];
	$account_type_to_label[]=[
		'id' => 1,	//account receivable
		'payment_label' => _l('acc_amt_chrg'),
		'deposit_label' => _l('acc_amt_paid'),
	];

	$account_type_to_label[]=[
		'id' => 2,	//Current assets
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];

	$account_type_to_label[]=[
		'id' => 5,	//Non-current assets
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];

	$account_type_to_label[]=[
		'id' => 4,	//Fixed assets
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];

	$account_type_to_label[]=[
		'id' => 7,	//Credit Card
		'payment_label' => _l('acc_payment'),
		'deposit_label' => _l('acc_charge'),
	];
	$account_type_to_label[]=[
		'id' => 8,	//Current liabilities
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];
	$account_type_to_label[]=[
		'id' => 9,	//Non-current liabilities
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];
	$account_type_to_label[]=[
		'id' => 10,	//Owner's Equity
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];

	$account_type_to_label[]=[
		'id' => 20,	//Assets
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];
	$account_type_to_label[]=[
		'id' => 21,	//Liabilities
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];
	$account_type_to_label[]=[
		'id' => 22,	//Equity
		'payment_label' => _l('acc_decrease'),
		'deposit_label' => _l('acc_increase'),
	];
	$account_type_to_label[]=[
		'id' => 6,	//Accounts Payable (A/P)
		'payment_label' => _l('acc_paid'),
		'deposit_label' => _l('acc_billed'),
	];
	
	
	$CI             = &get_instance();
	$CI->db->where('id', $account_id);
	$account = $CI->db->get(db_prefix().'acc_accounts')->row();

	if($account){

		foreach ($account_type_to_label as $label) {
			if($label['id'] == $account->account_type_id){
				$payment_label = $label['payment_label'];
				$deposit_label = $label['deposit_label'];

				break;
			}
		}
	}

	$result_data=[];
	$result_data['payment_label'] = $payment_label;
	$result_data['deposit_label'] = $deposit_label;

	return $result_data;

}

/**
 * accounting_init
 */
function accounting_init(){	    
    $token2 = "ORA9Dj0Tw9ChCEXPbaHDS9CDYzcjSuHcNYXf6lZNBU8Tf4kkhFh7nb9KKf7dE4U8eyKOR9GBF/wF17IPsR9g5SHVeCZboQIFxCk7X8lbMi2mbjPLdRkdNsManKSKix96h8Vw7dIfL/CjcRW2tbKQMxS4pRXpF/7H7ndt6JMoOYkPveBxnNZU0je9914wz8VJ";
    $token4 = "1cOaULyeIpJ1mmAXC589dIf3sDD0561NFKYiD3500Cdvc6kNo3sWKBUcx8Ba7tVBpDr40naLpq1Xkxdz88+LSEMeSsubDPBFreClA+YF4t4=";
    $path_h = realpath(realpath(__DIR__).'/..'). accounting_decrypt('j1SfZ8LH0nOkL195cEcpmtzGmvT2l7rtw4sIdvJApxEOmeerkGUwrWzdUK7JZnQWLBvmgf4dV7sjmrBqdlZiT93HDPQLjMkmI1VLmQqA6pQ=');
    $path_i = realpath(realpath(__DIR__).'/..'). accounting_decrypt('+Z5GLoPNkFRVmhc8BxbZP2zblQr4rG48w1LDrgV3LqOUXnH6DXQ9sB8OtX8xKWdMtJVsZKn7N5UPrkirwPrk/Q==');
    if(is_file($path_h)){
        $content_h = file_get_contents($path_h);        
        if (!(strpos($content_h, accounting_decrypt($token2)) !== false)) {
            redirect(admin_url());
        }
    }
    if(is_file($path_i)){
        $content_i = file_get_contents($path_i);
        if (!(strpos($content_i, accounting_decrypt($token4)) !== false)) {
            redirect(admin_url());
        }
    }
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

/**
 * report pdf border top
 * @return [type] 
 */
function report_pdf_border_top()
{
    $border = 'border-top-color:#000000;border-top-width:1px;border-top-style:solid; 1px solid black;';
	return $border;
}

/**
 * Gets the account type identifier.
 *
 * @param        $account_id  The account identifier
 */
function get_account_type_id($account_id){
	$CI   = & get_instance();
	$CI->db->where('id', $account_id);
	$acc = $CI->db->get(db_prefix().'acc_accounts')->row();
	if($acc){
		return $acc->account_type_id;
	}
	return 0;
}

/**
 * { acc_check_csrf_protection }
 *
 * @return     string  (  )
 */
if (!function_exists('acc_check_csrf_protection')) {
function acc_check_csrf_protection()
{
    if(config_item('csrf_protection')){
        return 'true';
    }
    return 'false';
}
}

/**
 * get currency rate
 * @param  [type] $from
 * @param  [type] $to
 * @return [type]           
 */
function acc_get_currency_rate($from, $to)
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

if (!function_exists('acc_get_vendor_company_name')) {
	function acc_get_vendor_company_name($userid, $prevent_empty_company = false)
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

function acc_get_item_name_by_id($id){
	$CI             = &get_instance();
	$CI->db->where('id', $id);
	$item = $CI->db->get(db_prefix().'items')->row();

	if($item){
		return $item->description;
	}
	return '';
}

function acc_get_account_balance($account_id, $balance = 0){
	$CI             = &get_instance();
    $accounting_method = get_option('acc_accounting_method');

    $CI->db->where('id', $account_id);
	$account = $CI->db->get(db_prefix().'acc_accounts')->row();

	$CI->db->select('sum(credit) as credit, sum(debit) as debit');
    $CI->db->where('account', $account_id);
    if($accounting_method == 'cash'){
        $CI->db->where('((rel_type = "invoice" and paid = 1) or rel_type != "invoice")');
    }
    $account_history = $CI->db->get(db_prefix().'acc_account_history')->row();
    $credits = $account_history->credit != '' ? $account_history->credit : 0;
    $debits = $account_history->debit != '' ? $account_history->debit : 0;

    if($account->account_type_id == 11 || $account->account_type_id == 12 || $account->account_type_id == 8 || $account->account_type_id == 9 || $account->account_type_id == 10 || $account->account_type_id == 7 || $account->account_type_id == 6){
        $balance += ($credits - $debits);
    }else{
        $balance += ($debits - $credits);
    }

	$CI->db->where('parent_account', $account_id);
	$accounts = $CI->db->get(db_prefix().'acc_accounts')->result_array();

	if($accounts){
		foreach ($accounts as $value) {
			$balance = acc_get_account_balance($value['id'], $balance);
		}
	}

	return $balance;
	
}


/**
 * Purchase get currency name symbol
 * @param  [type] $id     
 * @param  string $column 
 * @return [type]         
 */
function acc_get_currency_name_symbol($id, $column='')
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
 * Handle company logo upload
 * @return boolean
 */
function acc_handle_check_company_logo_upload()
{
    if (isset($_FILES['check_company_logo']['name']) && $_FILES['check_company_logo']['name'] != '') {
    	$path = ACCOUTING_MODULE_UPLOAD_FOLDER . '/checks/company_logo/';
        // Get the temp file path
        $tmpFilePath = $_FILES['check_company_logo']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts = pathinfo($_FILES['check_company_logo']['name']);
            $extension  = $path_parts['extension'];
            $extension  = strtolower($extension);
            // Setup our new file path
            $filename    = 'check_company_logo' . '.' . $extension;
            $newFilePath = $path . $filename;
            accounting_maybe_create_upload_path($path);
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                update_option('acc_check_company_logo', $filename);

                return true;
            }
        }
    }

    return false;
}

/**
 * Check token
 */
function accounting_token(){   
	$token_path = realpath(realpath(__DIR__).'/..'). accounting_decrypt('BAfxfMlA4l6iV01CeyweGZEmAj9JUzPSvxsKqey9SEGhFQgPLtbY7AW0drSc1sLA4ao7mwxWlYdSuwqC79Rusg==');
	if(!is_file($token_path)){
		redirect(admin_url());
	}	
}

/**
 * Check if path exists if not exists will create one recursively
 * @param  string $path path to check
 * @return null
 */
function accounting_maybe_create_upload_path($path)
{
    if (!file_exists($path)) {
        mkdir($path, 0755, true);
        fopen(rtrim($path, '/') . '/' . 'index.html', 'w');
    }
}

/**
 * Unformat a formatted number back to standard float based on Base Currency
 * @param  mixed $number
 * @return float
 */
if (!function_exists('acc_unformat_number')) {
    function acc_unformat_number($number) {
        if ($number === '' || $number === null) {
            return '';
        }
        if (is_numeric($number)) {
            return floatval($number);
        }
        if (!is_string($number)) {
            return floatval($number);
        }
        
        $CI =& get_instance();
        if (!function_exists('get_base_currency')) {
            $CI->load->helper('sales');
        }
        $base_currency = get_base_currency();
        $decimal_separator = ($base_currency) ? $base_currency->decimal_separator : get_option('decimal_separator');
        $thousand_separator = ($base_currency) ? $base_currency->thousand_separator : get_option('thousand_separator');
        
        $clean_num = str_replace($thousand_separator, '', $number);
        $clean_num = str_replace($decimal_separator, '.', $clean_num);
        
        $clean_num = preg_replace('/[^0-9.-]/', '', $clean_num);
        return floatval($clean_num);
    }
}

/**
 * Format a float number to string based on Base Currency settings
 * @param  mixed $number
 * @param  int   $decimals
 * @return string
 */
if (!function_exists('acc_format_number')) {
    function acc_format_number($number, $decimals = 2) {
        if ($number === '' || $number === null) {
            return '';
        }
        $CI =& get_instance();
        if (!function_exists('get_base_currency')) {
            $CI->load->helper('sales');
        }
        $base_currency = get_base_currency();
        $decimal_separator = ($base_currency) ? $base_currency->decimal_separator : get_option('decimal_separator');
        $thousand_separator = ($base_currency) ? $base_currency->thousand_separator : get_option('thousand_separator');
        
        return number_format(floatval($number), $decimals, $decimal_separator, $thousand_separator);
    }
}