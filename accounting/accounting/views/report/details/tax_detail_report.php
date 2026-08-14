<?php 
$data_html = []; 
$data_html[1] = '<tr>
          <td colspan="8">
              <h3 class="text-center">'.get_option('companyname').'</h3>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="8">
            <h4 class="text-center">'._l('tax_detail_report').'</h4>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="8">
            <p class="text-center">'._d($data_report['from_date']) .' - '. _d($data_report['to_date']).'</p>
          </td>
          <td></td>
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
          <td></td>
        </tr>
        <tr class="tr_header">
          <td width="10%" class="text-bold">'._l('invoice_payments_table_date_heading').'</td>
          <td width="10%" class="text-bold">'._l('transaction_type').'</td>
          <td width="15%" class="text-bold">'._l('description').'</td>
          <td width="15%" class="text-bold">'._l('customer').'</td>
          <td width="18%" class="text-bold">'._l('tax_name').'</td>
          <td width="7%" class="text-bold">'._l('tax_rate').'</td>
          <td width="12%" class="total_amount text-bold">'._l('amount').'</td>
          <td width="13%" class="total_amount text-bold">'._l('balance').'</td>
        </tr>';
         $row_index = 1; 
         $parent_index = 1; 
         $total = 0; 
$data_html[1] .= '<tr data-node-id="10000" class="parent-node expanded">
            <td class="parent">'._l('tax_collected_on_sales').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
         foreach ($data_report['data']['tax_collected_on_sales'] as $val) {
              $row_index += 1;
              $total += $val['amount'];
            
            $_page = $row_index/250 + 1;
              $url = get_url_by_type_id($val['rel_type'], $val['rel_id']);
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
              '.new_html_entity_decode($val['description']).' 
              </td>
              <td>
              '.get_company_name($val['customer']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['tax_name']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['tax_rate']).'%
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
              <td class="total_amount">
              '.app_format_money($total, $currency->name).' 
              </td>
            </tr>';
          }
            $row_index += 1;
           
          
            $_page = $row_index/250 + 1;
           if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
        $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'"  class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('tax_collected_on_sales')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
            <td class="total_amount"></td>
          </tr>';

         $row_index++; 
         $total = 0; 
            $_page = $row_index/250 + 1;
         if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
        $data_html[floor($_page)] .= '<tr data-node-id="10001" class="parent-node expanded">
            <td class="parent">'._l('total_taxable_sales_in_period_before_tax').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
         foreach ($data_report['data']['total_taxable_sales_in_period_before_tax'] as $val) {
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
              '.new_html_entity_decode($val['description']).' 
              </td>
              <td>
              '.get_company_name($val['customer']).' 
              </td>
              <td>
              </td>
              <td>
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
              <td class="total_amount">
              '.app_format_money($total, $currency->name).' 
              </td>
            </tr>';
           }
            $row_index += 1;
          
            $_page = $row_index/250 + 1;
           if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
            $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'"  class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('total_taxable_sales_in_period_before_tax')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
            <td class="total_amount"></td>
          </tr>';
          $row_index += 1; 
           $total = 0;
            $_page = $row_index/250 + 1;
          if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
            $data_html[floor($_page)] .= '<tr data-node-id="10002" class="parent-node expanded">
            <td class="parent">'._l('tax_reclaimable_on_purchases').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
         foreach ($data_report['data']['tax_reclaimable_on_purchases'] as $val) {
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
              '.new_html_entity_decode($val['description']).' 
              </td>
              <td>
              '.get_company_name($val['customer']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['tax_name']).' 
              </td>
              <td>
              '.new_html_entity_decode($val['tax_rate']).'%
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
              <td class="total_amount">
              '.app_format_money($total, $currency->name).' 
              </td>
            </tr>';
          }
            $row_index += 1;
          
            $_page = $row_index/250 + 1;
           if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
            $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'"  class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('tax_reclaimable_on_purchases')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
            <td class="total_amount"></td>
          </tr>';
          $row_index += 1; 
           $total = 0;
          $_page = $row_index/250 + 1;
           if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
            $data_html[floor($_page)] .= '<tr data-node-id="10003" class="parent-node expanded">
            <td class="parent">'._l('total_taxable_purchases_in_period_before_tax').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
         foreach ($data_report['data']['total_taxable_purchases_in_period_before_tax'] as $val) {
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
              '.new_html_entity_decode($val['description']).' 
              </td>
              <td>
              '.get_company_name($val['customer']).' 
              </td>
              <td>
              
              </td>
              <td>
              
              </td>
              <td class="total_amount">
              '.app_format_money($val['amount'], $currency->name).' 
              </td>
              <td class="total_amount">
              '.app_format_money($total, $currency->name).' 
              </td>
            </tr>';
           }
            $row_index += 1;
          
           $_page = $row_index/250 + 1;
           if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
            $data_html[floor($_page)] .= '<tr data-node-id="'.new_html_entity_decode($row_index).'"  class="parent-node expanded tr_total">
            <td class="parent">'._l('total_for', _l('total_taxable_purchases_in_period_before_tax')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'.app_format_money($total, $currency->name).'</td>
            <td class="total_amount"></td>
          </tr>';
  

if(isset($data_html[$page])){
  echo $data_html[$page];
}

if(count($data_html) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class="parent-node load_more_btn">
  <td id="load_more_td"><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}
