<?php

defined('BASEPATH') or exit('No direct script access allowed');
$get_base_currency =  get_base_currency();
if($get_base_currency){
    $base_currency = $get_base_currency->id;
}else{
    $base_currency = 0;
}

$aColumns = [
    db_prefix().'goods_receipt.id as id',
    'goods_receipt_code',
    'supplier_name',
    'buyer_id',
    'pr_order_id',
    'date_add',
    'total_tax_money', 
    'total_goods_money',
    'value_of_inventory',
    'total_money',
    'approval',
];
$sIndexColumn = 'id';
$sTable       = db_prefix().'goods_receipt';
$join         = [ ];
$where = [];

if($this->ci->input->post('day_vouchers')){
    $day_vouchers = to_sql_date($this->ci->input->post('day_vouchers'));
}

if (isset($day_vouchers)) {

    $where[] = 'AND tblgoods_receipt.date_add <= "' . $day_vouchers . '"';
    
}
if (!has_permission('wh_stock_import', '', 'view')) {
    array_push($where, 'AND (' . db_prefix() . 'goods_receipt.addedfrom=' . get_staff_user_id().' OR ' . db_prefix() . 'goods_receipt.buyer_id=' . get_staff_user_id() .')');
}

if($this->ci->input->post('_project_id')){
    $where[] = 'AND '.db_prefix().'goods_receipt.project = '.$this->ci->input->post('_project_id');
}

$custom_fields = get_custom_fields('iv_receipt', [
    'show_on_table' => 1,
]);

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
	array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'goods_receipt.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="iv_receipt" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['date_add','date_c','goods_receipt_code', 'supplier_code', 'currency']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $currency = $base_currency;
    $row = [];

    if(is_numeric($aRow['currency']) && $aRow['currency'] != 0){
        $currency = $aRow['currency'];
    }

    for ($i = 0; $i < count($aColumns); $i++) {

        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if($aColumns[$i] == 'supplier_name'){

            if (get_status_modules_wh('purchase') && ($aRow['supplier_code'] != '') && ($aRow['supplier_code'] != 0) ){
                $_data = wh_get_vendor_company_name($aRow['supplier_code']);
            }else{
                $_data = $aRow['supplier_name'];
            }

        }elseif($aColumns[$i] == 'buyer_id'){
            $_data = '<a href="' . admin_url('staff/profile/' . $aRow['buyer_id']) . '">' . staff_profile_image($aRow['buyer_id'] ?? 0, [
                'staff-profile-image-small',
            ]) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['buyer_id']) . '">' . get_staff_full_name($aRow['buyer_id']) . '</a>';
        }elseif($aColumns[$i] == 'date_add'){
            $_data = _d($aRow['date_add']);
        }elseif ($aColumns[$i] == 'total_tax_money') {
            $_data = app_format_money((float)$aRow['total_tax_money'], $currency);
        }elseif($aColumns[$i] == 'goods_receipt_code'){
            if($this->ci->input->post('_project_id')){
                $name = '<a href="' . admin_url('warehouse/manage_purchase/' . $aRow['id'] ).'" target="_blank">' . $aRow['goods_receipt_code'] . '</a>';
            }else{
                $name = '<a href="' . admin_url('warehouse/view_purchase/' . $aRow['id'] ).'" onclick="init_goods_receipt('.$aRow['id'].'); return false;">' . $aRow['goods_receipt_code'] . '</a>';
            }

            $name .= '<div class="row-options">';
            if($this->ci->input->post('_project_id')){
                $name .= '<a href="' . admin_url('warehouse/manage_purchase/' . $aRow['id'] ).'" target="_blank">' . _l('view') . '</a>';
            }else{
                $name .= '<a href="' . admin_url('warehouse/view_purchase/' . $aRow['id'] ).'" onclick="init_goods_receipt('.$aRow['id'].'); return false;">' . _l('view') . '</a>';
            }

            if((has_permission('wh_stock_import', '', 'edit') || is_admin()) && ($aRow['approval'] == 0)){
                $name .= ' | <a href="' . admin_url('warehouse/manage_goods_receipt/' . $aRow['id'] ).'" >' . _l('edit') . '</a>';
            }

            if ((has_permission('wh_stock_import', '', 'delete') || is_admin()) && ($aRow['approval'] == 0)) {
                $name .= ' | <a href="' . admin_url('warehouse/delete_goods_receipt/' . $aRow['id'] ).'" class="text-danger _delete" >' . _l('delete') . '</a>';
            }

            if(get_warehouse_option('revert_goods_receipt_goods_delivery') == 1 ){
                if ((has_permission('wh_stock_import', '', 'delete') || is_admin()) && ($aRow['approval'] == 1)) {
                    $name .= ' | <a href="' . admin_url('warehouse/revert_goods_receipt/' . $aRow['id'] ).'" class="text-danger _delete" >' . _l('delete_after_approval') . '</a>';
                }
            }
            

            
            $name .= '</div>';

            $_data = $name;
        }elseif ($aColumns[$i] == 'total_goods_money') {
            $_data = app_format_money((float)$aRow['total_goods_money'], $currency);
        }elseif ($aColumns[$i] == 'total_money') {
            $_data = app_format_money((float)$aRow['total_money'], $currency);
        }elseif($aColumns[$i] == 'value_of_inventory') {
            $_data = app_format_money((float)$aRow['value_of_inventory'], $currency);
        }elseif($aColumns[$i] == 'approval') {
           
           if($aRow['approval'] == 1){
            $_data = '<span class="label label-tag tag-id-1 label-tab1"><span class="tag">'._l('approved').'</span><span class="hide">, </span></span>&nbsp';
        }elseif($aRow['approval'] == 0){
            $_data = '<span class="label label-tag tag-id-1 label-tab2"><span class="tag">'._l('not_yet_approve').'</span><span class="hide">, </span></span>&nbsp';
        }elseif($aRow['approval'] == -1){
            $_data = '<span class="label label-tag tag-id-1 label-tab3"><span class="tag">'._l('reject').'</span><span class="hide">, </span></span>&nbsp';
        }
    }elseif($aColumns[$i] == 'pr_order_id'){
        $get_pur_order_name ='';
        if (get_status_modules_wh('purchase')) {
            if( ($aRow['pr_order_id'] != '') && ($aRow['pr_order_id'] != 0) ){
                $get_pur_order_name .='<a href="'. admin_url('purchase/purchase_order/'.$aRow['pr_order_id']) .'" >'. get_pur_order_name($aRow['pr_order_id']) .'</a>';
            }
        }

        $_data = $get_pur_order_name;

    }
    


    $row[] = $_data;
}
$output['aaData'][] = $row;

}
