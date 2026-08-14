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
            <h4 class="text-center"><?php echo _l('tax_liability_report'); ?></h4>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2">
            <p class="text-center"><?php echo _d($data_report['from_date']) .' - '. _d($data_report['to_date']); ?></p>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
          </td>
          <td></td>
        </tr>
        <tr class="tr_header">
          <td width="70%"></td>
          <td width="30%" class="text-right text-bold"><?php echo _l('amount'); ?></td>
        </tr>
        <?php
         $row_index = 0; 
         $parent_index = 0; 
         $total = 0; 
         ?>

         <?php foreach ($data_report['data'] as $val) { 
              $row_index += 1;
              $total += $val['amount'];
            ?>
            <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" data-node-pid="10000">
              <td>
              <?php echo new_html_entity_decode($val['name']); ?> 
              </td>
              <td class="total_amount">
              <?php echo app_format_money($val['amount'], $currency->name); ?> 
              </td>
            </tr>
          <?php }
            $row_index += 1;
           ?>
          
           <tr data-node-id="<?php echo new_html_entity_decode($row_index); ?>" class="expanded tr_total" data-node-pid="10000">
            <td class="parent"><?php echo _l('total'); ?></td>
            <td class="total_amount"><?php echo app_format_money($total, $currency->name); ?> </td>
          </tr>
      </tbody>
    </table>
  </div>
</div>