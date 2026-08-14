<?php 
$data_html = []; 
$data_html[1] = '<tr>
          <td colspan="5">
              <h3 class="text-center">'. get_option('companyname').'</h3>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="5">
            <h4 class="text-center">'. _l('profit_and_loss_detail').'</h4>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="5">
            <p class="text-center">'. _d($data_report['from_date']) .' - '. _d($data_report['to_date']).'</p>
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
          <td width="20%" class="text-bold">'. _l('invoice_payments_table_date_heading').'</td>
          <td width="15%" class="text-bold">'. _l('transaction_type').'</td>
          <td width="30%" class="text-bold">'. _l('description').'</td>
          <td width="10%" class="text-bold">'. _l('split').'</td>
          <td width="10%" class="total_amount text-bold">'. _l('acc_amount').'</td>
          <td width="15%" class="total_amount text-bold">'. _l('balance').'</td>
        </tr>
        <tr data-node-id="1000" class="parent-node expanded">
          <td class="parent">'. _l('acc_ordinary_income_expenses').'</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>';
         $row_index = 1;
         $parent_index = 1;

        $data_html[1] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded">
          <td class="parent">'. _l('acc_income').'</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>';
        $total_income = 0;
        $data = $this->accounting_model->get_html_profit_and_loss_detail($data_report['data']['income'], ['html' => $data_html, 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            // echo new_html_entity_decode($data['html']);
            $total_income = $data['total_amount'];

        $row_index += 1;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
        $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded tr_total">
            <td class="parent">'. _l('total_for', _l('acc_income')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'. app_format_money($total_income, $currency->name).' </td>
            <td></td>
          </tr>';

         $row_index += 1;
         $parent_index = $row_index;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
        $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded">
          <td class="parent">'. _l('acc_cost_of_sales').'</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>';
          $data = $this->accounting_model->get_html_profit_and_loss_detail($data_report['data']['cost_of_sales'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            // echo new_html_entity_decode($data['html']);
            $total_cost_of_sales = $data['total_amount'];
         $row_index += 1;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
        $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded tr_total">
            <td class="parent">'. _l('total_for', _l('acc_cost_of_sales')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'. app_format_money($total_cost_of_sales, $currency->name).' </td>
            <td></td>
          </tr>';
        $row_index += 1;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
        $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded tr_total">
          <td class="parent">'. _l('gross_profit').'</td>
          <td></td>
          <td></td>
          <td></td>
          <td class="total_amount">'. app_format_money($total_income - $total_cost_of_sales, $currency->name).'</td>
          <td></td>
        </tr>';
         $row_index += 1;
         $parent_index = $row_index;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
        $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded">
          <td class="parent">'. _l('acc_other_income').'</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>';
        $data = $this->accounting_model->get_html_profit_and_loss_detail($data_report['data']['other_income'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            // echo new_html_entity_decode($data['html']);
            $total_other_income = $data['total_amount'];
        $row_index += 1;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
        
        $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded tr_total">
            <td class="parent">'. _l('total_for', _l('acc_other_income')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'. app_format_money($total_other_income, $currency->name).' </td>
            <td></td>
          </tr>';
         $row_index += 1;
         $parent_index = $row_index;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
        $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded">
          <td class="parent">'. _l('acc_expenses').'</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>';
        $data = $this->accounting_model->get_html_profit_and_loss_detail($data_report['data']['expenses'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            // echo new_html_entity_decode($data['html']);
            $total_expenses = $data['total_amount'];
          $row_index += 1;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
          $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded tr_total">
            <td class="parent">'. _l('total_for', _l('acc_expenses')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'. app_format_money($total_expenses, $currency->name).' </td>
            <td></td>
          </tr>';
         $row_index += 1;
         $parent_index = $row_index;
        $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
          $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded">
          <td class="parent">'. _l('acc_other_expenses').'</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>';
        $data = $this->accounting_model->get_html_profit_and_loss_detail($data_report['data']['other_expenses'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            // echo new_html_entity_decode($data['html']);
            $total_other_expenses = $data['total_amount'];
          $row_index += 1;
          $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
          $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded tr_total">
            <td class="parent">'. _l('total_for', _l('acc_other_expenses')).'</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="total_amount">'. app_format_money($total_other_expenses, $currency->name).' </td>
            <td></td>
          </tr>';
           $row_index += 1;
          $_page = $row_index/250 + 1;
        if(!isset($data['html'][floor($_page)])){
            $data['html'][floor($_page)] = '';
        }
        $data['html'][floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="1000" class="parent-node expanded tr_total">
          <td class="parent">'. _l('acc_net_income').'</td>
          <td></td>
          <td></td>
          <td></td>
          <td class="total_amount">'. app_format_money(($total_income + $total_other_income) - ($total_cost_of_sales + $total_expenses + $total_other_expenses), $currency->name).'</td>
          <td></td>
        </tr>';
     
if(isset($data['html'][$page])){
  echo $data['html'][$page];
}

if(count($data['html']) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class="parent-node load_more_btn">
  <td id="load_more_td" ><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}
