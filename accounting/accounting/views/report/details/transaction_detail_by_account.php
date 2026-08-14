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
            <h4 class="text-center">'. _l('transaction_detail_by_account').'</h4>
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
          <td width="20%" class="text-bold">'. _l('customer').'</td>
          <td width="10%" class="text-bold">'. _l('description').'</td>
          <td width="10%" class="text-bold">'. _l('split').'</td>
          <td width="15%" class="total_amount text-bold">'. _l('acc_amount').'</td>
          <td width="15%" class="total_amount text-bold">'. _l('balance').'</td>
        </tr>';
         $row_index = 0;
         $parent_index = 0;

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['accounts_receivable'], ['html' => $data_html, 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['cash_and_cash_equivalents'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['current_assets'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['fixed_assets'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['non_current_assets'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['accounts_payable'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['credit_card'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['current_liabilities'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['non_current_liabilities'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['owner_equity'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['income'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['other_income'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['cost_of_sales'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['expenses'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

        $data = $this->accounting_model->get_html_transaction_detail_by_account($data_report['data']['other_expenses'], ['html' => $data['html'], 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
        $row_index = $data['row_index'];
        // echo new_html_entity_decode($data['html']);

   if(isset($data['html'][$page])){
  echo $data['html'][$page];
}

if(count($data['html']) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class="parent-node load_more_btn">
  <td id="load_more_td"><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}
