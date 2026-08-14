<?php 
$count = 1;
switch ($data_report['display_columns_by']) {
            case 'total_only':
              $count++;
              break;

            case 'months':
              $start = $month = strtotime($data_report['from_date']);
              $end = strtotime($data_report['to_date']);
              while($month <= $end)
              {
                $count++;
                  $month = strtotime("+1 month", $month);
              }

              $count++;
              break;

            case 'quarters':
              $from_date = $data_report['from_date'];
              $to_date = $data_report['to_date'];

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

                  $count++;

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
                          $count++;
                      }
                  }
              }
              $count++;
              break;

            case 'years':
              $from_date = $data_report['from_date'];
              $to_date = $data_report['to_date'];

              while (strtotime($from_date) < strtotime($to_date)) {
                  $year = date('Y', strtotime($from_date));

                  $count++;

                  $from_date = date('Y-m-d', strtotime('+1 year', strtotime($from_date)));

                  if(strtotime($from_date) > strtotime($to_date)){
                      $year_2 = date('Y', strtotime($to_date));
                  
                      if($year != $year_2){
                          $count++;
                      }
                  }
              }
              $count++;
              break;

            case 'vendors':
              $this->load->model('purchase/purchase_model');
              $vendors = $this->purchase_model->get_vendor();
              foreach ($vendors as $key => $vendor) {
                  $count++;
              }
              $count++;
              $count++;
              break;

            case 'employees':
              $this->load->model('staff_model');
              $staffs = $this->staff_model->get();
              foreach ($staffs as $key => $staff) {
                $count++;
              }
              $count++;
              break;

            case 'customers':
              $this->load->model('clients_model');
              $clients = $this->clients_model->get();
              foreach ($clients as $key => $client) {
                $count++;
              }
              $count++;
              $count++;
              break;
            default:
              // code...
              break;
          }

          $rate_col = 100/$count;

$data_html = []; 
$data_html[1] = '<tr>
          <td width="100%" colspan="'.$count.'">
              <h3 class="text-center">'. get_option('companyname').'</h3>
          </td>
         
        </tr>
        <tr>
          <td width="100%" colspan="'.$count.'">
            <h4 class="text-center">'. _l('custom_summary_report').'</h4>
          </td>
          
        </tr>
        <tr>
          <td width="100%" colspan="'.$count.'">
            <p class="text-center">'. _d($data_report['from_date']) .' - '. _d($data_report['to_date']).'</p>
          </td>';

           
          switch ($data_report['display_columns_by']) {
            case 'total_only':
              $data_html[1] .= '<td></td>';
              break;

            case 'months':
              $start = $month = strtotime($data_report['from_date']);
              $end = strtotime($data_report['to_date']);
              while($month <= $end)
              {
                $data_html[1] .= '<td></td>';
                  $month = strtotime("+1 month", $month);
              }

              $data_html[1] .= '<td></td>';
              break;

            case 'quarters':
              $from_date = $data_report['from_date'];
              $to_date = $data_report['to_date'];

              while (strtotime($from_date) < strtotime($to_date)) {
                  $month = date('m', strtotime($from_date));
                  $year = date('Y', strtotime($from_date));

                  $data_html[1] .= '<td></td>';

                  $from_date = date('Y-m-d', strtotime('+3 month', strtotime($from_date)));

                  if(strtotime($from_date) > strtotime($to_date)){
                      $month_2 = date('m', strtotime($from_date));
                      $year_2 = date('Y', strtotime($from_date));

                      if($month . ' - ' . $year != $month_2 . ' - ' . $year_2){
                          $data_html[1] .= '<td></td>';
                      }
                  }
              }
              $data_html[1] .= '<td></td>';
              break;

            case 'years':
              $from_date = $data_report['from_date'];
              $to_date = $data_report['to_date'];

              while (strtotime($from_date) < strtotime($to_date)) {
                  $year = date('Y', strtotime($from_date));

                  $data_html[1] .= '<td></td>';

                  $from_date = date('Y-m-d', strtotime('+1 year', strtotime($from_date)));

                  if(strtotime($from_date) > strtotime($to_date)){
                      $year_2 = date('Y', strtotime($to_date));
                  
                      if($year != $year_2){
                          $data_html[1] .= '<td></td>';
                      }
                  }
              }
              $data_html[1] .= '<td></td>';
              break;

            case 'vendors':
              $this->load->model('purchase/purchase_model');
              $vendors = $this->purchase_model->get_vendor();
              foreach ($vendors as $key => $vendor) {
                  $data_html[1] .= '<td></td>';
              }
              $data_html[1] .= '<td></td>';
              $data_html[1] .= '<td></td>';
              break;

            case 'employees':
              $this->load->model('staff_model');
              $staffs = $this->staff_model->get();
              foreach ($staffs as $key => $staff) {
                  $data_html[1] .= '<td></td>';
              }
              $data_html[1] .= '<td></td>';
              break;
            case 'customers':
              $this->load->model('clients_model');
              $clients = $this->clients_model->get();
              foreach ($clients as $key => $client) {
                $data_html[1] .= '<td></td>';
              }
              $data_html[1] .= '<td></td>';
              $data_html[1] .= '<td></td>';
              break;
            default:
              // code...
              break;
          }
          
          $data_html[1] .= '<td></td>
        </tr>
        <tr>
          <td>
          </td>
          <td></td>
        </tr>
        <tr class="tr_header">
          <td width="'.$rate_col.'%"></td>';
           
          switch ($data_report['display_columns_by']) {
            case 'total_only':
              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('total') .'</td>';
              break;

            case 'months':
              $start = $month = strtotime($data_report['from_date']);
              $end = strtotime($data_report['to_date']);
              while($month <= $end)
              {
                $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'.date('F', $month).'<br>'.date('Y', $month).'</td>';
                  $month = strtotime("+1 month", $month);
              }

              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('total') .'</td>';
              break;

            case 'quarters':
              $from_date = $data_report['from_date'];
              $to_date = $data_report['to_date'];

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

                  $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'.$t.'</td>';

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
                          $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'.$t_2.'</td>';
                      }
                  }
              }
              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('total') .'</td>';
              break;

            case 'years':
              $from_date = $data_report['from_date'];
              $to_date = $data_report['to_date'];

              while (strtotime($from_date) < strtotime($to_date)) {
                  $year = date('Y', strtotime($from_date));

                  $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'.$year.'</td>';

                  $from_date = date('Y-m-d', strtotime('+1 year', strtotime($from_date)));

                  if(strtotime($from_date) > strtotime($to_date)){
                      $year_2 = date('Y', strtotime($to_date));
                  
                      if($year != $year_2){
                          $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'.$year_2.'</td>';
                      }
                  }
              }
              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('total') .'</td>';
              break;

            case 'vendors':
              $this->load->model('purchase/purchase_model');
              $vendors = $this->purchase_model->get_vendor();
              foreach ($vendors as $key => $vendor) {
                  $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'.$vendor['company'].'</td>';
              }
              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('not_specified') .'</td>';
              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('total') .'</td>';
              break;

            case 'employees':
              $this->load->model('staff_model');
              $staffs = $this->staff_model->get();
              foreach ($staffs as $key => $staff) {
                $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'.$staff['full_name'].'</td>';
              }
              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('total') .'</td>';
              break;

            case 'customers':
              $this->load->model('clients_model');
              $clients = $this->clients_model->get();
              foreach ($clients as $key => $client) {
                $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'.$client['company'].'</td>';
              }
              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('not_specified') .'</td>';
              $data_html[1] .= '<td width="'.$rate_col.'%" class="th_total_width_auto text-bold">'. _l('total') .'</td>';
              break;
            default:
              // code...
              break;
          }
          
        $data_html[1] .= '</tr>';
        
          $row_index = 0;
          $parent_index = 0;
          $row_index += 1;
          $parent_index = $row_index;

          
          $total = 0;
          
             foreach ($data_report['data'] as $val) {
              $total_amount = 0;
              $row_index += 1;
            
            $_page = $row_index/250 + 1;
            if(!isset($data_html[floor($_page)])){
                $data_html[floor($_page)] = '';
            }
            $data_html[floor($_page)] .= '<tr data-node-id="'. new_html_entity_decode($row_index).'" data-node-pid="10000">
              <td>
              '. new_html_entity_decode($val['name']).' 
              </td>';
               
                foreach($val['columns'] as $column){ 
              $data_html[floor($_page)] .= '<td class="total_amount">
                   '. app_format_money($column, $currency->name).' 
                  </td>';
               
              $total += $column;
              $total_amount += $column;
              } 
               if ($data_report['display_columns_by'] != 'total_only') { 
              $data_html[floor($_page)] .= '<td class="total_amount">
                '. app_format_money($total_amount, $currency->name).' 
              </td>';
               } 
            $data_html[floor($_page)] .= '</tr>';
           } 
   
if(isset($data_html[$page])){
  echo $data_html[$page];
}

if(count($data_html) > $page){
  echo '<tr data-node-id="'. new_html_entity_decode($row_index+1).'" data-node-pid="1000000" class=" parent-node load_more_btn">
  <td id="load_more_td"><a href="javascript:void(0);" onclick="report_loadmore('.($page + 1) .')" class="btn btn-primary mleft10 mbot10">'._l('load_more').'</a></td></tr>';
}