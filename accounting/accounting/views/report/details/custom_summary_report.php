<?php 
$data_html = []; 
$data_html[1] = '<tr>
  <td colspan="14">
      <h3 class="text-center">'. get_option('companyname').'</h3>
  </td>
  <td></td>
</tr>
<tr>
  <td colspan="14">
    <h4 class="text-center">'. _l('custom_summary_report').'</h4>
  </td>
  <td></td>
</tr>
<tr>
  <td colspan="14">
    <p class="text-center">'. _d($data_report['from_date']) .' - '. _d($data_report['to_date']).'</p>
  </td>';
  
  if($data_report['display_columns_by'] == 'total_only'){
    $data_html[1] .= '<td></td>';
  }elseif($data_report['display_columns_by'] == 'months'){
    $start = $month = strtotime($data_report['from_date']);
    $end = strtotime($data_report['to_date']);
    while($month <= $end)
    {
      $data_html[1] .= '<td></td>';
        $month = strtotime("+1 month", $month);
    }
    $data_html[1] .= '<td></td>';
   }elseif ($data_report['display_columns_by'] == 'quarters') {
    while (strtotime($from_date) < strtotime($to_date)) {
        $month = date('m', strtotime($from_date));
        $year = date('Y', strtotime($from_date));
        if($month>=1 && $month<=3)
        {
            $t = 'Q1 - '.$year;
        }
        else  if($month>=4 && $month<=6)
        {
            $t = 'Q2 - '.$year;
        }
        else  if($month>=7 && $month<=9)
        {
            $t = 'Q3 - '.$year;
        }
        else  if($month>=10 && $month<=12)
        {
            $t = 'Q4 - '.$year;
        }

        $data_html[1] .= '<td>'.$t.'</td>';

        $from_date = date('Y-m-d', strtotime('+3 month', strtotime($from_date)));

        if(strtotime($from_date) > strtotime($to_date)){
            $month_2 = date('m', strtotime($from_date));
            $year_2 = date('Y', strtotime($from_date));
            if($month_2>=1 && $month_2<=3)
            {
                $t_2 = 'Q1 - '.$year_2;
            }
            else  if($month_2>=4 && $month_2<=6)
            {
                $t_2 = 'Q2 - '.$year_2;
            }
            else  if($month_2>=7 && $month_2<=9)
            {
                $t_2 = 'Q3 - '.$year_2;
            }
            else  if($month_2>=10 && $month_2<=12)
            {
                $t_2 = 'Q4 - '.$year_2;
            }

            if($month . ' - ' . $year != $month_2 . ' - ' . $year_2){
                $data_html[1] .= '<td>'.$t_2.'</td>';
            }
        }
    }
  } elseif ($data_report['display_columns_by'] == 'years') {
    // code...
  } 
  
   $data_html[1] .= '<td></td>
</tr>
<tr>
  <td>
  </td>
  <td></td>
</tr>
<tr class="tr_header">
  <td></td>';
  
  if($data_report['display_columns_by'] == 'total_only'){
    $data_html[1] .= '<td class="th_total_width_auto text-bold">'. _l('total').'</td>';
  }elseif($data_report['display_columns_by'] == 'months'){
    $start = $month = strtotime($data_report['from_date']);
    $end = strtotime($data_report['to_date']);
    while($month <= $end)
    {
      $data_html[1] .= '<td class="th_total_width_auto text-bold">'.date('F', $month).'<br>'.date('Y', $month).'</td>';
        $month = strtotime("+1 month", $month);
    }
    $data_html[1] .= '<td class="th_total_width_auto text-bold">'. _l('total').'</td>';
  }elseif ($data_report['display_columns_by'] == 'quarters') {
    // code...
  } elseif ($data_report['display_columns_by'] == 'years') {
    // code...
  } 
  
$data_html[1] .= '</tr>';

  $row_index = 0;
  $parent_index = 0;
  $row_index += 1;
  $parent_index = $row_index;

  if($data_report['display_rows_by'] == 'income_statement'){

  
  $data_html[1] .= '<tr data-node-id="'. new_html_entity_decode($parent_index).'" class="parent-node expanded">
    <td class="parent">'. _l('acc_income').'</td>
    <td class="total_amount"></td>
  </tr>';

  $row_index += 1;
    $data = $this->accounting_model->get_html_custom_summary($data_report['data']['income'], ['html' => $data_html, 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
    $row_index = $data['row_index'];
    // echo new_html_entity_decode($data['html']);
    $total_income = $data['total_amount'];
     
$row_index += 1; 
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
$data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="'. new_html_entity_decode($parent_index).'" class="parent-node expanded tr_total">
    <td class="parent">'. _l('total_income').'</td>
    <td class="total_amount">'. app_format_money($total_income, $currency->name).' </td>
  </tr>';
   $row_index += 1;
    $parent_index = $row_index;
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  
   $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($parent_index).'" class="parent-node expanded">
    <td class="parent">'. _l('acc_cost_of_sales').'</td>
    <td></td>
  </tr>';

  $data = $this->accounting_model->get_html_custom_summary($data_report['data']['cost_of_sales'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
    $row_index = $data['row_index'];
    // echo new_html_entity_decode($data['html']);
    $total_cost_of_sales = $data['total_amount'];

   $row_index += 1;
$_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="'. new_html_entity_decode($parent_index).'" class="parent-node expanded tr_total">
    <td class="parent">'. _l('total_cost_of_sales').'</td>
    <td class="total_amount">'. app_format_money($total_cost_of_sales, $currency->name).' </td>
  </tr>';
  $row_index += 1;
  $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" class="parent-node expanded tr_total">
    <td class="parent">'. _l('gross_profit_uppercase').'</td>
    <td class="total_amount">'. app_format_money($total_income - $total_cost_of_sales, $currency->name).' </td>
  </tr>';
  $row_index += 1;
    $parent_index = $row_index;
  
  $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($parent_index).'" class="parent-node expanded">
    <td class="parent">'. _l('acc_other_income').'</td>
    <td></td>
  </tr>';

  $data = $this->accounting_model->get_html_custom_summary($data_report['data']['other_income'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
    $row_index = $data['row_index'];
    // echo new_html_entity_decode($data['html']);
    $total_other_income_loss = $data['total_amount'];
   
 $row_index += 1;

  $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="'. new_html_entity_decode($parent_index).'" class="parent-node expanded tr_total">
    <td class="parent">'. _l('total_other_income_loss').'</td>
    <td class="total_amount">'. app_format_money($total_other_income_loss, $currency->name).' </td>
  </tr>';
  $row_index += 1;
    $parent_index = $row_index;
  
  $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($parent_index).'" class="parent-node expanded">
    <td class="parent">'. _l('acc_expenses').'</td>
    <td></td>
  </tr>';
   
    $data = $this->accounting_model->get_html_custom_summary($data_report['data']['expenses'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
    $row_index = $data['row_index'];
    // echo new_html_entity_decode($data['html']);
    $total_expenses = $data['total_amount'];
     
  $row_index += 1;
  $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="'. new_html_entity_decode($parent_index).'" class="parent-node expanded tr_total">
    <td class="parent">'. _l('total_expenses').'</td>
    <td class="total_amount">'. app_format_money($total_expenses, $currency->name).' </td>
  </tr>';
  $row_index += 1;
    $parent_index = $row_index;
  
  $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($parent_index).'" class="parent-node expanded">
    <td class="parent">'. _l('acc_other_expenses').'</td>
    <td></td>
  </tr>';
   
  $data = $this->accounting_model->get_html_custom_summary($data_report['data']['other_expenses'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
    $row_index = $data['row_index'];
    // echo new_html_entity_decode($data['html']);
    $total_other_expenses = $data['total_amount'];
  
    $row_index += 1;
  
  $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="'. new_html_entity_decode($parent_index).'" class="parent-node expanded tr_total">
    <td class="parent">'. _l('total_other_expenses').'</td>
    <td class="total_amount">'. app_format_money($total_other_expenses, $currency->name).' </td>
  </tr>';
  $row_index += 1;
  $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" class="parent-node expanded tr_total">
    <td class="parent">'. _l('net_earnings_uppercase').'</td>
    <td class="total_amount">'. app_format_money(($total_income + $total_other_income_loss) - ($total_cost_of_sales + $total_expenses + $total_other_expenses), $currency->name).' </td>
  </tr>';
  }elseif ($data_report['display_rows_by'] == 'customers') { 
  $total = 0;
  
     foreach ($data_report['data'] as $val) {
      $total_amount = 0;
      $row_index += 1;
    
    $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="10000">
      <td>
      '. new_html_entity_decode($val['name']).' 
      </td>';
       
        foreach($val['columns'] as $column){
          $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<td class="total_amount">
           '. app_format_money($column, $currency->name).' 
          </td>';
       
      $total += $column;
      $total_amount += $column;
      } 
       if ($data_report['display_columns_by'] != 'total_only') {
      $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '<td class="total_amount">
        '. app_format_money($total_amount, $currency->name).' 
      </td>';
       } 
    $_page = $row_index/250 + 1;
if(!isset($data['html'][floor($_page)])){
  $data['html'][floor($_page)] = '';
}
  $data['html'][floor($_page)] .= '</tr>';
   } 
  }elseif ($data_report['display_rows_by'] == 'balance_sheet') {

 }
