<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
   <?php echo form_open(admin_url('accountplanning/add'),array('id'=>'new-account-form')); ?>
   <div class="content">
   <br><br>
      <div class="panel_s">
               <div class="btn-bottom-toolbar btn-toolbar-container-out text-right ap-calc100-20left">
            <button class="btn btn-info only-save account-planning-form-submiter">
            <?php echo htmlspecialchars(_l( 'submit')); ?>
            </button>
         </div>
         <div class="panel-body">
            <div class="tab-content">
               <h4 class="customer-profile-group-heading"><?php echo _l('new_account'); ?></h4>
               <div class="row">
                  <?php echo render_input('subject', 'subject','', 'text', array(), array(),'col-md-6'); ?>
                  <?php 
                  $date = getdate();
                  $date_1 = mktime(0, 0, 0, $date['mon'], 1, $date['year']);
                  $value = date('d/m/Y', $date_1);
                  ?>
                  <?php 
                  echo render_select('date', $month,array('id','name'), 'Period', $value, array(), array(), 'col-md-6'); ?>
               </div>
               <div class="row">
               <div class="col-md-6">
                  <h4 class="bold"><?php echo htmlspecialchars(_l('new_account_a')); ?></h4>
                  <div class="form-group select-placeholder" id="rel_id_wrapper">
                  <label for="client_id" class="control-label"><span class="text-danger">* </span><?php echo htmlspecialchars(_l('client')); ?></label>
                  <select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo htmlspecialchars(_l('dropdown_non_selected_tex')); ?>" required></select>
                  </div>
                  <div class="col-md-12">
                  &nbsp;
                  </div>
                  <div class="col-md-6">
                     <p class="bold"><?php echo htmlspecialchars(_l('address')); ?></p>
                     <address>
                     <span class="billing_street">--</span><br>
                     <span class="billing_city">--</span>,
                     <span class="billing_state">--</span><br/>
                     <span class="billing_country">--</span>,
                     <span class="billing_zip">--</span>
                     </address>
                  </div>
                  <div class="col-md-6">
                     <br>
                     <br>
                     <br>
                     <br>
                     <br>
                     <br>
                     <br>
                  </div>
                  <?php $value = '' ?>
                  <?php echo render_input( 'product', 'product',$value); ?>
                  <?php echo render_input( 'sale_channel_online', 'sale_channel_online',$value); ?>
                  <?php echo render_input( 'sale_channel_offline', 'sale_channel_offline',$value); ?>
                  <?php echo render_custom_fields('accountplanning'); ?>
                  
               
               </div>
               <div class="col-md-6">
                  <h4 class="bold">&nbsp;</h4>
                  <?php echo render_textarea( 'vision', 'vision'); ?>
                  <?php echo render_textarea( 'mission', 'mission'); ?>
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
                  <hr>
              <div class="row">  
               <div class="col-md-12">
                  <h4 class="bold"><?php echo htmlspecialchars(_l('new_account_c')); ?></h4>
                  <h5 class="bold"><?php echo htmlspecialchars(_l('new_account_lable_1')); ?></h5>
                  <?php $priorities['callback_translate'] = 'ticket_priority_translate';
                  echo render_select('lead_generation',$priorities,array('priorityid','name'),'lead_generation'); ?>
                  <br>
                  <h5 class="bold"><?php echo htmlspecialchars(_l('new_account_lable_2')); ?></h5>
                  <div id="hot_2"></div>
                  <?php echo form_hidden('marketing_activities'); ?>
               </div>
             </div>
            </div>
         </div>
      </div>
   </div>
   <?php echo form_close(); ?>
</div>

<?php init_tail(); ?>
<style>
  textarea.form-control {
    height: 183px;
    padding-top: 10px;
}
</style>
<script>
$('select[name="client_id"]').on('change', function() {
        var val = $(this).val();
        requestGetJSON('accountplanning/client_change_data/' + val).done(function(response) {
         $('.billing_street').text(response['billing_shipping'][0]['billing_street']);
         $('.billing_city').text(response['billing_shipping'][0]['billing_city']);
         $('.billing_state').text(response['billing_shipping'][0]['billing_state']);
         $('.billing_country').text(response['billing_shipping'][0]['billing_country']);
         $('.billing_zip').text(response['billing_shipping'][0]['billing_zip']);
        });

    });
var dataObject = [
  {
    year: '',
    revenue: '',
    traffic: '',
    sales_spent: '',
    loss: ''
  },
];

var hotElement = document.querySelector('#hot');
var hotSettings = {
  data: dataObject,
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
   defaultRowHeight: 100,
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
var hot = new Handsontable(hotElement, hotSettings);

var hot_2Element = document.querySelector('#hot_2');
var dataObject = [
  {
    item: '',
    reference: ''
  },
  {
    item: '',
    reference: ''
  },
  {
    item: '',
    reference: ''
  },
];
var hot_2Settings = {
 data: dataObject,
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

var hot_2 = new Handsontable(hot_2Element, hot_2Settings);
function ap_set_hidden_values() {
   $('input[name="financial"]').val(hot.getData());
   $('input[name="marketing_activities"]').val(hot_2.getData());
}
$('#new-account-form').on('submit', function() {
   ap_set_hidden_values();
});
$('.account-planning-form-submiter').on('click', function() {
   ap_set_hidden_values();
});
if (typeof init_ajax_search === 'function') {
   init_ajax_search('customer', '#clientid.ajax-search');
}
</script>
</body>
</html>