<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('service_ability_offering')); ?></h4>
<div class="clearfix"></div>
<?php echo form_open(admin_url('accountplanning/update_service_ability_offering/'.$account->id),array('id'=>'service-ability-offering-form')); ?>
    <?php  if (has_permission('accountplanning', '', 'edit')) { ?>
    <div class="btn-bottom-toolbar btn-toolbar-container-out text-right ap-calc100-20left">
    <button class="btn btn-info only-save service-ability-offering-form-submiter">
    <?php echo htmlspecialchars(_l( 'submit')); ?>
    </button>
    </div>
  <?php } ?>
        <h4 class="bold"><?php echo htmlspecialchars(_l('service_ability_offering_a')); ?></h4>
    <div class="form-group ap-margin-left-20">
        <label for="current_service_know_pmax" class="control-label bold"><?php echo htmlspecialchars(_l('know_company',get_option('companyname'))); ?></label>
            <div id="radioBtn" class="btn-group ap-margin-left-20">
                <a class="btn btn-primary btn-sm <?php if($account->current_service_know_pmax == 'no'){echo 'Active';}else{echo 'notActive';} ?>" data-toggle="current_service_know_pmax" data-title="no"><?php echo htmlspecialchars(_l('settings_no')); ?></a>
                <a class="btn btn-primary btn-sm <?php if($account->current_service_know_pmax == 'yes'){echo 'Active';}else{echo 'notActive';} ?>" data-toggle="current_service_know_pmax" data-title="yes"><?php echo htmlspecialchars(_l('settings_yes')); ?></a>
            </div>
            <?php echo form_hidden('current_service_know_pmax','no'); ?>
    </div><br>
    <label for="current_service" class="control-label bold ap-margin-left-20"><?php echo htmlspecialchars(_l('current_service')); ?></label><br><br>
    <div id="current_service" class="ap-width400-left-20"></div>
    <?php echo form_hidden('current_service'); ?>

    <br>
    <h4 class="bold"><?php echo htmlspecialchars(_l('service_ability_offering_b')); ?></h4>
    <br>
    <div id="service_ability_offering"></div>
    <?php echo form_hidden('service_ability_offering'); ?>
<?php echo form_close(); ?>
<style>
    #radioBtn .notActive{
    color: #3276b1;
    background-color: #fff;
}
</style>
<script>
var hotElement = document.querySelector('#service_ability_offering');
var hot_2Element = document.querySelector('#current_service');
var dataObject = [
  {
    service: ''
  },
  {
    service: ''
  },
  {
    service: ''
  },
  {
    service: ''
  },
  {
    service: ''
  },
];
var dataObject2 = [
  {
    service: ''
  },
  {
    service: ''
  },
  {
    service: ''
  },
  {
    service: ''
  },
  {
    service: ''
  },
];
var hotSettings = {
  data: <?php if(count($service_ability_offering) > 0) { echo json_encode($service_ability_offering); }else{ ?> dataObject <?php } ?>,
  columns: [
    {
      data: 'service',
      type: 'text'
    },
    {
      data: 'potential',
      editor: 'select',
      selectOptions: ['High', 'Medium', 'Low']
    },
    {
      data: 'scale',
      type: 'text'
    },
    {
      data: 'convert',
      type: 'text'
    },
    {
      data: 'prioritization',
      type: 'text'
    }
  ],
  stretchH: 'all',
  autoWrapRow: true,
  rowHeights: 50,
  rowHeaders: true,
  colHeaders: [
    ' ',
    '<?php echo htmlspecialchars(_l('potential')); ?>',
    '<?php echo htmlspecialchars(_l('scale')); ?>',
    '<?php echo htmlspecialchars(_l('convert')); ?>',
    '<?php echo htmlspecialchars(_l('prioritization')); ?>'
  ],
  nestedHeaders: [
    [
      ' ',
      ' ',
      {
        label: '<?php echo htmlspecialchars(_l('opportunity')); ?>',
        colspan: 2
      },
      ' '
    ],
    [
      ' ',
      '<?php echo htmlspecialchars(_l('potential')); ?>',
      '<?php echo htmlspecialchars(_l('scale')); ?>',
      '<?php echo htmlspecialchars(_l('convert')); ?>',
      '<?php echo htmlspecialchars(_l('prioritization')); ?>'
    ]
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
 data: <?php if(count($current_service) > 0) { echo json_encode($current_service); }else{ ?> dataObject2 <?php } ?>,
  columns: [
    {
      data: 'name',
      type: 'text'
    },
    {
      data: 'potential',
      editor: 'select',
      selectOptions: ['High', 'Medium', 'Low']
    }
  ],
  stretchH: 'all',
  autoWrapRow: true,
  rowHeights: 50,
  rowHeaders: true,
  colHeaders: [
    '<?php echo htmlspecialchars(_l('name')); ?>',
    '<?php echo htmlspecialchars(_l('potential')); ?>',
  ],
  columnSorting: {
    indicator: true
  },
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

var current_service = new Handsontable(hot_2Element, hot_2Settings);

var service_ability_offering = new Handsontable(hotElement, hotSettings);
</script>