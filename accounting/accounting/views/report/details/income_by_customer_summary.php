<?php 
$data_html = []; 
$data_html[1] = '<tr>
          <td colspan="4">
              <h3 class="text-center">'. get_option('companyname').'</h3>
          </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="4">
            <h4 class="text-center">'. _l('income_by_customer_summary').'</h4>
          </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="4">
            <p class="text-center">'. _d($data_report['from_date']) .' - '. _d($data_report['to_date']).'</p>
          </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr class="tr_header">
          <td width="40%" class="text-bold">'. _l('customer').'</td>
          <td width="20%" class="text-right text-bold">'. _l('acc_income').'</td>
          <td width="20%" class="text-right text-bold">'. _l('expenses').'</td>
          <td width="20%" class="text-right text-bold">'. _l('acc_net_income').'</td>
        </tr>';
          $row_index = 1;
          $total_income = 0;
          $total_expenses = 0;
          $total_net_income = 0;
        foreach ($data_report['list_customer'] as $key => $value) {
          if($value == ''){
            continue;
          }
          $income = isset($data_report['total']['income'][$value]) ? $data_report['total']['income'][$value] : 0;
          $expenses = isset($data_report['total']['expenses'][$value]) ? $data_report['total']['expenses'][$value] : 0;
          $cost_of_sales = isset($data_report['total']['cost_of_sales'][$value]) ? $data_report['total']['cost_of_sales'][$value] : 0;
          $other_income = isset($data_report['total']['other_income'][$value]) ? $data_report['total']['other_income'][$value] : 0;
          $other_expenses = isset($data_report['total']['other_expenses'][$value]) ? $data_report['total']['other_expenses'][$value] : 0;

          $_income = $income + $other_income;
          $_expenses = $expenses + $other_expenses + $cost_of_sales;
          $row_index += 1;
          $total_income += $_income;
          $total_expenses += $_expenses;
          $total_net_income += $_income - $_expenses;
          
          $_page = $row_index/250 + 1;
           if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
        $data_html[floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'"  class="parent-node expanded">
            <td class="parent">'. get_company_name($value).'</td>
            <td class="total_amount">'. app_format_money($_income, $currency->name).' </td>
            <td class="total_amount">'. app_format_money($_expenses, $currency->name).' </td>
            <td class="total_amount">'. app_format_money(($_income - $_expenses), $currency->name).' </td>
          </tr>';
         } 
          
            $row_index += 1;
           
        $_page = $row_index/250 + 1;
           if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
        $data_html[floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'"  class="parent-node expanded tr_total">
            <td class="parent">'. _l('total').'</td>
            <td class="total_amount">'. app_format_money($total_income, $currency->name).' </td>
            <td class="total_amount">'. app_format_money($total_expenses, $currency->name).' </td>
            <td class="total_amount">'. app_format_money($total_net_income, $currency->name).' </td>
          </tr>';

if(isset($data_html[$page])){
  echo $data_html[$page];
}

if(count($data_html) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class="parent-node load_more_btn">
  <td id="load_more_td"><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}
