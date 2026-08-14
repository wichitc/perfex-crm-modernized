<div id="accordion">
  <div class="card">
    <table class="tree">
      <tbody>
        <tr>
          <td colspan="7">
              <h3 class="text-center"><?php echo get_option('companyname'); ?></h3>
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
            <h4 class="text-center"><?php echo _l('account_history'); ?></h4>
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
            <p class="text-center"><?php echo _d($data_report['from_date']) .' - '. _d($data_report['to_date']); ?></p>
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
          <td  width="15%" class="text-bold"><?php echo _l('invoice_payments_table_date_heading'); ?></td>
          <td width="15%" class="text-bold"><?php echo _l('transaction_type'); ?></td>
          <td width="20%" class="text-bold"><?php echo _l('split'); ?></td>
          <td width="10%" class="text-bold"><?php echo _l('description'); ?></td>
          <?php if($data_report['account_type'] == 3){ ?>
            <td width="15%" class="total_amount text-bold"><?php echo _l('acc_payment'); ?></td>
            <td width="15%" class="total_amount text-bold"><?php echo _l('acc_deposit'); ?></td>
          <?php }elseif($data_report['account_type'] == 7 || $data_report['account_type'] == 1){ ?>
            <td width="15%" class="total_amount text-bold"><?php echo _l('charge'); ?></td>
            <td width="15%" class="total_amount text-bold"><?php echo _l('acc_payment'); ?></td>
          <?php }else{ ?>
            <td width="15%" class="total_amount text-bold"><?php echo _l('decrease'); ?></td>
            <td width="15%" class="total_amount text-bold"><?php echo _l('increase'); ?></td>
          <?php } ?>
          <td width="10%" class="total_amount text-bold"><?php echo _l('balance'); ?></td>
        </tr>
        <?php
         $row_index = 0; 
         ?>

         <?php foreach ($data_report['data'] as $val) { 
              $row_index += 1;
            ?>
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>"  data-node-pid="10000">
              <td>
                <?php $url = get_url_by_type_id($val['rel_type'], $val['rel_id']); ?>
              <a href="<?php echo html_entity_decode($url); ?>" class="text-default-bl"><?php echo _d($val['date']); ?></a> 
              </td>
              <td>
              <?php echo new_html_entity_decode($val['type']); ?> 
              </td>
              <td>
              <?php echo new_html_entity_decode($val['split']); ?> 
              </td>
              <td>
              <?php echo new_html_entity_decode($val['description']); ?> 
              </td>
              <td class="total_amount">
              <?php echo app_format_money($val['decrease'], $currency->name); ?> 
              </td>
              <td class="total_amount">
              <?php echo app_format_money($val['increase'], $currency->name); ?> 
              </td>
              <td class="total_amount">
              <?php echo app_format_money($val['balance'], $currency->name); ?> 
              </td>
            </tr>
          <?php }
            $row_index += 1;
           ?>
      </tbody>
    </table>
  </div>
</div>