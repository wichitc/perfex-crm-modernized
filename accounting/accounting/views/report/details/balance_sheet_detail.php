<?php 
$data_html = []; 
$data_html[1] = '<tr>
      <td colspan="7" class="text-center">
          <h3 class="">'. get_option('companyname').'</h3>
      </td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="7" class="text-center">
        <h4 class="">'. _l('balance_sheet_detail').'</h4>
      </td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="7" class="text-center">
        <p class="">'. _d($data_report['from_date']) .' - '. _d($data_report['to_date']).'</p>
      </td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr class="tr_header">
      <td width="15%" class="text-bold">'. _l('invoice_payments_table_date_heading').'</td>
      <td width="15%" class="text-bold">'. _l('transaction_type').'</td>
      <td width="20%" class="text-bold">'. _l('description').'</td>
      <td  width="10%" class="total_amount text-bold">'. _l('debit').'</td>
      <td  width="10%" class="total_amount text-bold">'. _l('credit').'</td>
      <td  width="15%" class="total_amount text-bold">'. _l('acc_amount').'</td>
      <td  width="15%" class="total_amount text-bold">'. _l('balance').'</td>
    </tr>
    <tr data-node-id="100000" class="parent-node expanded">
      <td class="parent">'. _l('acc_assets').'</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>';

 $row_index = 0;
 $parent_index = 100000;
 $total_assets = 0;
 $balance_assets = 0;

  $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['accounts_receivable'], ['html' => $data_html, 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency);

  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
 $total_assets += $data['total_amount'];
 $balance_assets += $data['total_balance'];

  $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['cash_and_cash_equivalents'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
  $total_assets += $data['total_amount'];
  $balance_assets += $data['total_balance'];

  $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['current_assets'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
  $total_assets += $data['total_amount'];
  $balance_assets += $data['total_balance'];

  $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['fixed_assets'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
  $total_assets += $data['total_amount'];
  $balance_assets += $data['total_balance'];

  $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['non_current_assets'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
  $total_assets += $data['total_amount'];
  $balance_assets += $data['total_balance'];
 $row_index += 1; 
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
$data['html'][floor($_page)] .= '
  <tr data-node-id="'. new_html_entity_decode($row_index).'" class="parent-node expanded tr_total">
    <td class="parent">'. _l('total_assets').'</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="total_amount">'. app_format_money($total_assets, $currency->name).' </td>
    <td class="total_amount">'. app_format_money($balance_assets, $currency->name).' </td>
  </tr>
<tr data-node-id="100001" class="parent-node expanded">
  <td class="parent">'. _l('liabilities_and_shareholders_equity').'</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>';
$row_index += 1;
  $_parent_index = $row_index; 
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
$data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($_parent_index).'" data-node-pid="100001" class=" parent-node expanded">
  <td class="parent">'. _l('liabilities').'</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>';
$total_liabilities = 0;
$balance_liabilities = 0;
  $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['accounts_payable'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
 $total_liabilities += $data['total_amount'];
 $balance_liabilities += $data['total_balance'];

 $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['credit_card'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
 $total_liabilities += $data['total_amount'];
 $balance_liabilities += $data['total_balance'];

 $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['current_liabilities'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
 $total_liabilities += $data['total_amount'];
 $balance_liabilities += $data['total_balance'];

 $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['non_current_liabilities'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
 $total_liabilities += $data['total_amount'];
 $balance_liabilities += $data['total_balance'];

 $row_index += 1;
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
 $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="100001" class=" parent-node expanded tr_total">
    <td class="parent">'. _l('total_for', _l('liabilities')).'</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="total_amount">'. app_format_money($total_liabilities, $currency->name).' </td>
    <td class="total_amount">'. app_format_money($balance_liabilities, $currency->name).' </td>
  </tr>';
 $row_index += 1;
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $_parent_index = $row_index ; 
  $total_equity = 0;
  $balance_equity = 0;
 $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($_parent_index).'" data-node-pid="100001" class=" parent-node expanded">
  <td class="parent">'. _l('equity').'</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>';
 $data = $this->accounting_model->get_html_balance_sheet_detail_new($data_report['data']['owner_equity'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0], $_parent_index, $currency);
  $row_index = $data['row_index'];
  //echo new_html_entity_decode($data['html']);
 $total_equity += $data['total_amount'];
 $total_equity += $data_report['net_income'];
 $balance_equity += $data['total_balance'];
 $row_index += 1;
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}

$data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="'. new_html_entity_decode($_parent_index).'" class=" parent-node expanded tr_total">
    <td class="parent">'. _l('acc_net_income').'</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="total_amount">'. app_format_money($data_report['net_income'], $currency->name).' </td>
    <td class="total_amount">'. app_format_money(($data_report['net_income_beginning_balance'] + $data_report['net_income']), $currency->name).' </td>
  </tr>';
 $row_index += 1; 
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $balance_equity += ($data_report['net_income_beginning_balance'] + $data_report['net_income']);

$data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="100001" class=" parent-node expanded tr_total">
    <td class="parent">'. _l('total_for', _l('equity')).'</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="total_amount">'. app_format_money($total_equity, $currency->name).' </td>
    <td class="total_amount">'. app_format_money($balance_equity, $currency->name).' </td>
  </tr>';
  $row_index += 1; 
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $total_liabilities_and_equity = $total_equity + $total_liabilities;
  $balance_liabilities_and_equity = $balance_equity + $balance_liabilities;
  $row_index += 1;
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="100011" class=" parent-node expanded tr_total">
    <td class="parent">'. _l('total_liabilities_and_equity').'</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="total_amount">'. app_format_money($total_liabilities_and_equity, $currency->name).' </td>
    <td class="total_amount">'. app_format_money($balance_liabilities_and_equity, $currency->name).' </td>
  </tr>';

if(isset($data['html'][$page])){
  echo $data['html'][$page];
}
if(count($data['html']) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class=" parent-node load_more_btn">
  <td id="load_more_td"><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}
