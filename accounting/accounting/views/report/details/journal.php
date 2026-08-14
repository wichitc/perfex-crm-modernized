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
            <h4 class="text-center">'. _l('journal').'</h4>
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
            <p class="text-center">'. _d($data_report['from_date']) .' - '. _d($data_report['to_date']).'</p>
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
          <td width="10%" class="text-bold">'. _l('number').'</td>
          <td width="20%" class="text-bold">'. _l('description').'</td>
          <td width="10%" class="text-bold">'. _l('acc_account').'</td>
          <td width="15%" class="total_amount text-bold">'. _l('debit').'</td>
          <td width="15%" class="total_amount text-bold">'. _l('credit').'</td>
        </tr>';

         $row_index = 0; 
         $parent_index = 0; 
         $total_debit = 0; 
         $total_credit = 0; 

          foreach ($data_report['data'] as $val) { 
              $row_index += 1;
              $total_debit += $val['debit'];
              $total_credit += $val['credit'];
            
            $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
               $url = get_url_by_type_id($val['rel_type'], $val['rel_id']);
           $data_html[floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="10000">
              <td>
              <a href="'. new_html_entity_decode($url).'" class="text-default-bl">'. _d($val['date']).'</a> 
              </td>
              <td>
              '. new_html_entity_decode($val['type']).' 
              </td>
              <td>
              '. new_html_entity_decode($val['number']).' 
              </td>
              <td>
              '. new_html_entity_decode($val['description']).' 
              </td>
              <td>
              '. new_html_entity_decode($val['name']).' 
              </td>
              <td class="total_amount">
              '. app_format_money($val['debit'], $currency->name).' 
              </td>
              <td class="total_amount">
              '. app_format_money($val['credit'], $currency->name).' 
              </td>
            </tr>';
           }
            $row_index += 1;
           
          
           $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
           $data_html[floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" class="parent-node expanded tr_total">
            <td class="parent">'. _l('total').'</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'. app_format_money($total_debit, $currency->name).' </td>
            <td class="total_amount">'. app_format_money($total_credit, $currency->name).' </td>
          </tr>';

          if(isset($data_html[$page])){
  echo $data_html[$page];
}

if(count($data_html) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class="parent-node load_more_btn">
  <td id="load_more_td"><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}
