<?php 
$data_html = []; 
$data_html[1] = '<tr>
          <td colspan="7">
              <h3 class="text-center">'. get_option('companyname').'</h3>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="7">
            <h4 class="text-center">'. _l('accounts_receivable_ageing_summary').'</h4>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="7">
            <p class="text-center">'. _l('acc_as_of', _d($data_report['to_date'])).'</p>
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
          <td width="16%"></td>
          <td width="14%" class="text-right text-bold">'. _l('current').'</td>
          <td width="14%" class="text-right text-bold">1 - 30</td>
          <td width="14%" class="text-right text-bold">31 - 60</td>
          <td width="14%" class="text-right text-bold">61 - 90</td>
          <td width="14%" class="text-right text-bold">> 90</td>
          <td width="14%" class="text-right text-bold">'. _l('total').'</td>
        </tr>';
        
         $row_index = 1; 
         $parent_index = 1; 
         $total = 0; 
         $total_current = 0; 
         $total_1_30 = 0; 
         $total_31_60 = 0; 
         $total_61_90 = 0; 
         $total_91_and_over = 0; 
         
          foreach ($data_report['data'] as $customer => $val) {
              $row_index += 1;
              $total_current += $val['current'];
              $total_1_30 += $val['1_30_days_past_due'];
              $total_31_60 += $val['31_60_days_past_due'];
              $total_61_90 += $val['61_90_days_past_due'];
              $total_91_and_over += $val['91_and_over'];
              $total += $val['total'];
            
            $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'"  data-node-pid="10000">
              <td>
              '. get_company_name($customer).' 
              </td>
              <td class="total_amount">
              '. app_format_money($val['current'], $currency->name).' 
              </td>
              <td class="total_amount">
              '. app_format_money($val['1_30_days_past_due'], $currency->name).' 
              </td>
              <td class="total_amount">
              '. app_format_money($val['31_60_days_past_due'], $currency->name).' 
              </td>
              <td class="total_amount">
              '. app_format_money($val['61_90_days_past_due'], $currency->name).' 
              </td>
              <td class="total_amount">
              '. app_format_money($val['91_and_over'], $currency->name).' 
              </td>
              <td class="total_amount">
              '. app_format_money($val['total'], $currency->name).' 
              </td>
            </tr>';
           }
            $row_index += 1;
           
          
           $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'"  data-node-pid="10000" class="parent-node expanded tr_total">
            <td class="parent">'. _l('total').'</td>
            <td class="total_amount">'. app_format_money($total_current, $currency->name).'</td>
            <td class="total_amount">'. app_format_money($total_1_30, $currency->name).'</td>
            <td class="total_amount">'. app_format_money($total_31_60, $currency->name).'</td>
            <td class="total_amount">'. app_format_money($total_61_90, $currency->name).'</td>
            <td class="total_amount">'. app_format_money($total_91_and_over, $currency->name).'</td>
            <td class="total_amount">'. app_format_money($total, $currency->name).'</td>
          </tr>';

               if(isset($data_html[$page])){
  echo $data_html[$page];
}

if(count($data_html) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class="parent-node load_more_btn">
  <td id="load_more_td"><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}
