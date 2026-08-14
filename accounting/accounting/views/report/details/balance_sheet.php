<div id="accordion">
  <div class="card">
    <table class="tree">
      <tbody>
        <tr>
          <td colspan="2">
              <h3 class="text-center"><?php echo get_option('companyname'); ?></h3>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2">
            <h4 class="text-center"><?php echo _l('balance_sheet'); ?></h4>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2">
            <?php if ($data_report['date_filter_type'] == 'to_date') { ?>
              <p class="text-center"><?php echo _l('as_of').' '. _d($data_report['to_date']); ?></p>
            <?php }else{ ?>
              <p class="text-center"><?php echo _d($data_report['from_date']) .' - '. _d($data_report['to_date']); ?></p>
            <?php } ?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
          </td>
          <td></td>
        </tr>
        <tr class="tr_header">
          <td width="80%"></td>
          <td width="20%" class="th_total text-bold"><?php echo _l('total'); ?></td>
        </tr>
        <tr data-node-id="1000" class="parent-node expanded">
          <td class="parent"><?php echo _l('acc_assets'); ?></td>
          <td></td>
        </tr>
        <?php
          $row_index = 0;
          $parent_index = 0;
          $row_index += 1;
          $parent_index = $row_index;
          $total_current_assets = 0;
          ?>
          <tr data-node-id="<?php echo new_html_entity_decode($parent_index); ?>" data-node-pid="1000" class="parent-node expanded">
            <td class="parent"><?php echo _l('acc_current_assets'); ?></td>
            <td></td>
          </tr>
          <?php
          $row_index += 1;
          ?>
          <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="<?php echo new_html_entity_decode($parent_index); ?>" class="parent-node expanded">
            <td class="parent"><?php echo _l('acc_accounts_receivable'); ?></td>
            <td></td>
          </tr>
          <?php 
            $_index = $row_index;
            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['accounts_receivable'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_current_assets += $data['total_amount'];
            ?>
          <?php 
            $row_index += 1;
           ?>
          <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="<?php echo new_html_entity_decode($parent_index); ?>" class="parent-node expanded tr_total">
            <td class="parent"><?php echo _l('total_accounts_receivable'); ?></td>
            <td class="total_amount"><?php echo app_format_money($data['total_amount'], $currency->name); ?> </td>
          </tr>
          <?php 

            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['cash_and_cash_equivalents'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_current_assets += $data['total_amount'];

            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['current_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_current_assets += $data['total_amount'];
          
            $row_index += 1;
          ?>
          <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="1000" class="parent-node expanded tr_total">
            <td class="parent"><?php echo _l('total_current_assets'); ?></td>
            <td class="total_amount"><?php echo app_format_money($total_current_assets, $currency->name); ?> </td>
          </tr>
          <?php 
            $row_index += 1;
            $parent_index = $row_index;
            $total_long_term_assets = 0;
          ?>
          <tr data-node-id="<?php echo new_html_entity_decode($parent_index); ?>" data-node-pid="1000" class="parent-node expanded">
            <td class="parent"><?php echo _l('long_term_assets'); ?></td>
            <td></td>
          </tr>
          <?php 
            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['fixed_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_long_term_assets += $data['total_amount'];

            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['non_current_assets'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_long_term_assets += $data['total_amount'];

            $row_index += 1;
            ?>
          <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="1000" class="parent-node expanded tr_total">
            <td class="parent"><?php echo _l('total_long_term_assets'); ?></td>
            <td class="total_amount"><?php echo app_format_money($total_long_term_assets, $currency->name); ?> </td>
          </tr>
          <?php 
            $row_index += 1;
            ?>
          <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" class="tr_total">
            <td class="parent"><?php echo _l('total_assets'); ?></td>
            <td class="total_amount"><?php echo app_format_money($total_current_assets + $total_long_term_assets, $currency->name); ?> </td>
          </tr>
          <?php 
            $row_index += 1;
            ?>
            <tr data-node-id="1001" class="parent-node expanded">
              <td class="parent"><?php echo _l('liabilities_and_shareholders_equity'); ?></td>
              <td></td>
            </tr>
            <?php
            $row_index += 1;
            $parent_index = $row_index;
            $total_current_liabilities = 0;
            ?>
            <tr data-node-id="<?php echo new_html_entity_decode($parent_index); ?>" data-node-pid="1001" class="parent-node expanded">
              <td class="parent"><?php echo _l('acc_current_liabilities'); ?></td>
              <td></td>
            </tr>
            <?php $row_index += 1; ?>
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="<?php echo new_html_entity_decode($parent_index); ?>" class="parent-node expanded">
              <td class="parent"><?php echo _l('accounts_payable'); ?></td>
              <td></td>
            </tr>
            <?php 
              $_index = $row_index;
              $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['accounts_payable'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $_index, $currency);
              $row_index = $data['row_index'];
              echo new_html_entity_decode($data['html']);
              $total_current_liabilities += $data['total_amount'];
              ?>
            <?php $row_index += 1; ?>
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="<?php echo new_html_entity_decode($parent_index); ?>" class="tr_total">
              <td class="parent"><?php echo _l('total_accounts_payable'); ?></td>
              <td class="total_amount"><?php echo app_format_money($data['total_amount'], $currency->name); ?> </td>
            </tr>
            <?php 
            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['credit_card'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_current_liabilities += $data['total_amount'];

            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_current_liabilities += $data['total_amount'];
            ?>
           
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="1001" class="tr_total">
              <td class="parent"><?php echo _l('total_current_liabilities'); ?></td>
              <td class="total_amount"><?php echo app_format_money($total_current_liabilities, $currency->name); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <?php
            $row_index += 1;
            $parent_index = $row_index;
            $total_non_current_liabilities = 0;
            ?>
            <tr data-node-id="<?php echo new_html_entity_decode($parent_index); ?>" data-node-pid="1001" class="parent-node expanded">
              <td class="parent"><?php echo _l('acc_non_current_liabilities'); ?></td>
              <td></td>
            </tr>
            <?php 
            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['non_current_liabilities'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_non_current_liabilities += $data['total_amount'];
            ?>

            <?php $row_index += 1; ?>
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="1001" class="tr_total">
              <td class="parent"><?php echo _l('total_non_current_liabilities'); ?></td>
              <td class="total_amount"><?php echo app_format_money($total_non_current_liabilities, $currency->name); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <?php
            $row_index += 1;
            $parent_index = $row_index;
            $total_shareholders_equity = $data_report['net_income'];
            ?>
            <tr data-node-id="<?php echo new_html_entity_decode($parent_index); ?>" data-node-pid="1001" class="parent-node expanded">
              <td class="parent"><?php echo _l('shareholders_equity'); ?></td>
              <td></td>
            </tr>
            <?php $row_index += 1; ?>
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="<?php echo new_html_entity_decode($parent_index); ?>">
              <td >
                <?php echo _l('acc_net_income'); ?> 
              </td>
              <td class="total_amount">
                <?php echo app_format_money($data_report['net_income'], $currency->name); ?> 
              </td>
            </tr>
            <?php $row_index += 1; ?>
            <?php 
            $data = $this->accounting_model->get_html_balance_sheet($data_report['data']['owner_equity'], ['html' => '', 'row_index' => $row_index + 1, 'total_amount' => 0, 'total_py_amount' => 0], $parent_index, $currency);
            $row_index = $data['row_index'];
            echo new_html_entity_decode($data['html']);
            $total_shareholders_equity += $data['total_amount'];
            ?>

            <?php $row_index += 1; ?>
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="1001" class="tr_total">
              <td class="parent"><?php echo _l('total_shareholders_equity'); ?></td>
              <td class="total_amount"><?php echo app_format_money($total_shareholders_equity, $currency->name); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" class="tr_total">
              <td class="parent"><?php echo _l('total_liabilities_and_equity'); ?></td>
              <td class="total_amount"><?php echo app_format_money($total_shareholders_equity + $total_non_current_liabilities + $total_current_liabilities, $currency->name); ?> </td>
            </tr>
            <?php $row_index += 1; ?>
        </tbody>
    </table>
  </div>
</div>
