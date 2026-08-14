<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('due_diligence')); ?></h4>
<div class="clearfix"></div>
   <?php echo form_open(admin_url('accountplanning/update_due_diligence/'.$account->id),array('id'=>'due-diligence-form')); ?>
    <?php  if (has_permission('accountplanning', '', 'edit')) { ?>

            <div class="btn-bottom-toolbar btn-toolbar-container-out text-right ap-calc100-20left">
            <button class="btn btn-info only-save due-diligence-form-submiter">
            <?php echo htmlspecialchars(_l( 'submit')); ?>
            </button>
            </div>
          <?php } ?>
            <div class="tab-content">
               <div class="row">
               <div class="col-md-6">
                  <h4 class="bold"><?php echo htmlspecialchars(_l('new_account_a')); ?></h4>
                  <div class="form-group select-placeholder">
                     <label for="client_id" class="control-label"><span class="text-danger">* </span><?php echo htmlspecialchars(_l('client')); ?></label>
                     <select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo htmlspecialchars(_l('dropdown_non_selected_tex')); ?>" required>
                        <?php $selected = $account->client_id;
                         if($selected != ''){
                            $rel_data = get_relation_data('customer',$selected);
                            $rel_val = get_relation_values($rel_data,'customer');
                            echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                           } ?>
                     </select>
                  </div>
                  <div class="col-md-12">
                  &nbsp;
                  </div>
                  <div class="col-md-6">
                     <p class="bold"><?php echo htmlspecialchars(_l('address')); ?></p>
                     <address>
                     <span class="billing_street">
                     <?php $billing_street = (isset($billing_shipping) ? $billing_shipping['billing_street'] : '--'); ?>
                     <?php $billing_street = ($billing_street == '' ? '--' :$billing_street); ?>
                     <?php echo htmlspecialchars($billing_street); ?></span><br>
                     <span class="billing_city">
                     <?php $billing_city = (isset($billing_shipping) ? $billing_shipping['billing_city'] : '--'); ?>
                     <?php $billing_city = ($billing_city == '' ? '--' :$billing_city); ?>
                     <?php echo htmlspecialchars($billing_city); ?></span>,
                     <span class="billing_state">
                 
                     <?php $billing_state = (isset($billing_shipping) ? $billing_shipping['billing_state'] : '--'); ?>
                     <?php $billing_state = ($billing_state == '' ? '--' :$billing_state); ?>
                     <?php echo htmlspecialchars($billing_state); ?></span>
                     <br/>
                     <span class="billing_country">
                     <?php $billing_country = (isset($billing_shipping) ? get_country_short_name($billing_shipping['billing_country']) : '--'); ?>
                     <?php $billing_country = ($billing_country == '' ? '--' :$billing_country); ?>
                     <?php echo htmlspecialchars($billing_country); ?></span>,
                     <span class="billing_zip">
                     <?php $billing_zip = (isset($billing_shipping) ? $billing_shipping['billing_zip'] : '--'); ?>
                     <?php $billing_zip = ($billing_zip == '' ? '--' :$billing_zip); ?>
                     <?php echo htmlspecialchars($billing_zip); ?></span>
                     </address>
                  </div>
                  <div class="col-md-6">
                     <?php
                     $industry_opts = get_option('accountplanning_industry_options');
                     $industry_options = [['id' => '', 'name' => _l('dropdown_non_selected_tex')]];
                     if (!empty($industry_opts)) {
                         foreach (preg_split('/[\r\n,]+/', $industry_opts, -1, PREG_SPLIT_NO_EMPTY) as $opt) {
                             $opt = trim($opt);
                             if ($opt) $industry_options[] = ['id' => $opt, 'name' => $opt];
                         }
                     }
                     $value = (isset($account->industry) ? $account->industry : '');
                     if (!empty($value) && !in_array($value, array_column($industry_options, 'id'))) {
                         $industry_options[] = ['id' => $value, 'name' => $value];
                     }
                     echo render_select('industry', $industry_options, ['id', 'name'], 'industry', $value, ['data-allow-clear' => true, 'data-width' => '100%']);
                     ?>
                  </div>
                  
                  <?php $value = (isset($account->product) ? $account->product : '') ?>
                  <?php echo render_input( 'product', 'product',$value); ?>
                  <?php $value = (isset($account->sale_channel_online) ? $account->sale_channel_online : '') ?>
                  <?php echo render_input( 'sale_channel_online', 'sale_channel_online',$value); ?>
                  <?php $value = (isset($account->sale_channel_offline) ? $account->sale_channel_offline : '') ?>
                  <?php echo render_input( 'sale_channel_offline', 'sale_channel_offline',$value); ?>
                  <?php echo render_custom_fields('accountplanning', $account->id); ?>
               
               </div>
               <div class="col-md-6">
                  <h4 class="bold">&nbsp;</h4>
                  <?php $value = (isset($account->vision) ? $account->vision : '') ?>
                  <?php echo render_textarea( 'vision', 'vision', $value); ?>
                  <?php $value = (isset($account->mission) ? $account->mission : '') ?>
                  <?php echo render_textarea( 'mission', 'mission', $value); ?>
               </div>
               </div>
               <hr />
               <div class="row">  
                  <div class="col-md-12">
                     <h4 class="bold"><?php echo htmlspecialchars(_l('new_account_b')); ?></h4>
                  <div id="hot"></div>
                  <?php echo form_hidden('financial'); ?>
                  </div>
               </div>
               <div class="row">
                  <hr>
               <div class="col-md-12">
                  <h4 class="bold"><?php echo htmlspecialchars(_l('new_account_c')); ?></h4>
                  <h5 class="bold"><?php echo htmlspecialchars(_l('new_account_lable_1')); ?></h5>
                  <?php $value = (isset($account->lead_generation) ? $account->lead_generation : '') ?>
                  <?php $priorities['callback_translate'] = 'ticket_priority_translate';
                  echo render_select('lead_generation',$priorities,array('priorityid','name'),'lead_generation', $value); ?>
                  <br>
                  <h5 class="bold"><?php echo htmlspecialchars(_l('new_account_lable_2')); ?></h5>
                  <div id="hot_2"></div>
                  <?php echo form_hidden('marketing_activities'); ?>
               </div>
               </div>
            </div>
            <?php echo form_hidden('financial'); ?>
   <?php echo form_close(); ?>
   <style>
     textarea.form-control {
    height: 183px;
    padding-top: 10px;
}
   </style>
<script>
var hotElement = document.querySelector('#hot');
var hot_2Element = document.querySelector('#hot_2');
var hotSettings = {
  data: <?php echo json_encode($financial); ?>,
  columns: [
    {
      data: 'year',
      type: 'text'
    },
    {
      data: 'revenue',
      type: 'text'
    },
    {
      data: 'traffic',
      type: 'text'
    },
    {
      data: 'sales_spent',
      type: 'text'
    },
    {
      data: 'loss',
      type: 'text'
    }
  ],
  stretchH: 'all',
  autoWrapRow: true,
  rowHeights: 50,
  maxRows: 22,
  rowHeaders: true,
  colHeaders: [
    '<?php echo htmlspecialchars(_l('year')); ?>',
    '<?php echo htmlspecialchars(_l('revenue')); ?>',
    '<?php echo htmlspecialchars(_l('traffic')); ?>',
    '<?php echo htmlspecialchars(_l('sales_spent')); ?>',
    '<?php echo htmlspecialchars(_l('loss')); ?>'
  ],
  autoColumnSize: {
    samplingRatio: 23
  },
  dropdownMenu: true,
  mergeCells: true,
  contextMenu: true,
  manualRowMove: true,
  manualColumnMove: true,
  multiColumnSorting: {
    indicator: true
  },
  filters: true,
  manualRowResize: true,
  manualColumnResize: true
};
var hot_2Settings = {
 data: <?php echo json_encode($marketing_activities); ?>,
  columns: [
    {
      data: 'item',
      type: 'text'
    },
    {
      data: 'reference',
      type: 'text'
    }
  ],
  stretchH: 'all',
  autoWrapRow: true,
  rowHeights: 50,
  maxRows: 22,
  rowHeaders: true,
  colHeaders: [
    '<?php echo htmlspecialchars(_l('reports_item')); ?>',
    '<?php echo htmlspecialchars(_l('reference')); ?>'
  ],
  autoColumnSize: {
    samplingRatio: 23
  },
  dropdownMenu: true,
  mergeCells: true,
  contextMenu: true,
  manualRowMove: true,
  manualColumnMove: true,
  multiColumnSorting: {
    indicator: true
  },
  filters: true,
  manualRowResize: true,
  manualColumnResize: true
};
var hot = new Handsontable(hotElement, hotSettings);
var hot_2 = new Handsontable(hot_2Element, hot_2Settings);


</script>