<?php 
$data_html = []; 
$data_html[1] = '<tr>
          <td colspan="6">
              <h3 class="text-center">'.get_option('companyname').'</h3>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="6">
            <h4 class="text-center">'._l('accounts_payable_ageing_detail').'</h4>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="6">
            <p class="text-center">'._l('acc_as_of', _d($data_report['to_date'])).'</p>
          </td>
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
        </tr>
        <tr class="tr_header">
          <td width="16%" class="text-bold">'._l('invoice_payments_table_date_heading').'</td>
          <td width="16%" class="text-bold">'._l('transaction_type').'</td>
          <td width="16%" class="text-bold">'._l('acc_num').'</td>
          <td width="20%" class="text-bold">'._l('name').'</td>
          <td width="16%" class="text-bold">'._l('invoice_add_edit_duedate').'</td>
          <td width="16%" class="text-right text-bold">'._l('open_balance').'</td>
        </tr>';
  
         $row_index = 1; 
         $parent_index = 1; 
         $total = 0; 

         $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="10000" class="parent-node expanded">
            <td class="parent">'._l('current').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
         foreach ($data_report['data']['current'] as $val) {
              $row_index += 1;
              $total += $val['amount'];
            
              $url = get_url_by_type_id($val['rel_type'], $val['rel_id']);
            $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'" data-node-pid="10000">
              <td>
              <a href="'.new_html_entity_decode($url).'" class="text-default-bl">'._d($val['date']).'</a>
              </td>
              <td>
              '.new_html_entity_decode($val['type']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['number']).' 
              </td>
              <td>
              '.($val['customer'] != 0 ? '('._l('customer').')'.get_company_name($val['customer']) : ($val['vendor'] != 0 ? '('._l('vendor').')'.acc_get_vendor_name($val['vendor']) : '')).' 
              </td>
              <td>
              '._d($val['duedate']).' 
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
            </tr>';
          }
            $row_index += 1;
           
          
          $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'" class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('current')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
          </tr>';

         $row_index++; 
         $total = 0; 

         $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="10001" class="parent-node expanded">
            <td class="parent">'._l('1_30_days_past_due').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
         foreach ($data_report['data']['1_30_days_past_due'] as $val) {
              $row_index += 1;
              $total += $val['amount'];
              $url = get_url_by_type_id($val['rel_type'], $val['rel_id']);
           
            $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'" data-node-pid="10001">
              <td>
              <a href="'.new_html_entity_decode($url).'" class="text-default-bl">'._d($val['date']).'</a>
              </td>
              <td>
              '.new_html_entity_decode($val['type']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['number']).' 
              </td>
              <td>
              '.($val['customer'] != 0 ? '('._l('customer').')'.get_company_name($val['customer']) : ($val['vendor'] != 0 ? '('._l('vendor').')'.acc_get_vendor_name($val['vendor']) : '')).' 
              </td>
              <td>
              '._d($val['duedate']).' 
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
            </tr>';
           }
            $row_index += 1;
           
          
           $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'"  class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('1_30_days_past_due')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
          </tr>';


         $row_index++; 
         $total = 0; 
         
         $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="10002" class="parent-node expanded">
            <td class="parent">'._l('31_60_days_past_due').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
         foreach ($data_report['data']['31_60_days_past_due'] as $val) {
              $row_index += 1;
              $total += $val['amount'];
            
              $url = get_url_by_type_id($val['rel_type'], $val['rel_id']);
            $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'" data-node-pid="10002">
              <td>
              <a href="'.new_html_entity_decode($url).'" class="text-default-bl">'._d($val['date']).'</a>
              </td>
              <td>
              '.new_html_entity_decode($val['type']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['number']).' 
              </td>
              <td>
              '.($val['customer'] != 0 ? '('._l('customer').')'.get_company_name($val['customer']) : ($val['vendor'] != 0 ? '('._l('vendor').')'.acc_get_vendor_name($val['vendor']) : '')).' 
              </td>
              <td>
              '._d($val['duedate']).' 
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
            </tr>';
          }
            $row_index += 1;
          
          
           $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'" class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('31_60_days_past_due')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
          </tr>';
          $row_index += 1; 
           $total = 0;
          $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="10003" class="parent-node expanded">
            <td class="parent">'._l('61_90_days_past_due').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
          foreach ($data_report['data']['61_90_days_past_due'] as $val) {
              $row_index += 1;
              $total += $val['amount'];
           
               $url = get_url_by_type_id($val['rel_type'], $val['rel_id']);
          $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'" data-node-pid="10003">
              <td>
              <a href="'.new_html_entity_decode($url).'" class="text-default-bl">'._d($val['date']).'</a>
              </td>
              <td>
              '.new_html_entity_decode($val['type']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['number']).' 
              </td>
              <td>
              '.($val['customer'] != 0 ? '('._l('customer').')'.get_company_name($val['customer']) : ($val['vendor'] != 0 ? '('._l('vendor').')'.acc_get_vendor_name($val['vendor']) : '')).' 
              </td>
              <td>
              '._d($val['duedate']).' 
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
            </tr>';
          }
            $row_index += 1;
           
          
           $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'"  class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('61_90_days_past_due')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
          </tr>';


         $row_index++; 
         $total = 0; 
  
         $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="10004" class="parent-node expanded">
            <td class="parent">> 90</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
         foreach ($data_report['data']['91_and_over'] as $val) {
              $row_index += 1;
              $total += $val['amount'];
           
              $url = get_url_by_type_id($val['rel_type'], $val['rel_id']);
            $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'" data-node-pid="10004">
              <td>
              <a href="'.new_html_entity_decode($url).'" class="text-default-bl">'._d($val['date']).'</a>
              </td>
              <td>
              '.new_html_entity_decode($val['type']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['number']).' 
              </td>
              <td>
              '.($val['customer'] != 0 ? '('._l('customer').')'.get_company_name($val['customer']) : ($val['vendor'] != 0 ? '('._l('vendor').')'.acc_get_vendor_name($val['vendor']) : '')).' 
              </td>
              <td>
              '._d($val['duedate']).' 
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
            </tr>';
           }
            $row_index += 1;
          
          
           $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'"  class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('91_and_over')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
          </tr>';
     

if(isset($data_html[$page])){
  echo $data_html[$page];
}

if(count($data_html) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class="parent-node load_more_btn">
  <td id="load_more_td"><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}