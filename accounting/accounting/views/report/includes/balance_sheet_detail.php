<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <a href="<?php echo admin_url('accounting/report'); ?>"><?php echo _l('back_to_report_list'); ?></a>
          <hr />
          <div class="row">
            <div class="col-md-10">
              <div class="row">
              <?php echo form_open(admin_url('accounting/view_report'),array('id'=>'filter-form')); ?>
                <div class="col-md-3">
                  <?php echo render_date_input('from_date','from_date', _d($from_date)); ?>
                </div>
                <div class="col-md-3">
                  <?php echo render_date_input('to_date','to_date', _d($to_date)); ?>
                </div>
                <div class="col-md-3">
                  <?php 
                  $method = [
                          1 => ['id' => 'cash', 'name' => _l('cash')],
                          2 => ['id' => 'accrual', 'name' => _l('accrual')],
                         ];
                  echo render_select('accounting_method', $method, array('id', 'name'),'accounting_method', $accounting_method, array(), array(), '', '', false);
                  ?>
                </div>
                <div class="col-md-3">
                  <?php echo render_select('items[]',$items,array('id', 'description', 'sku_code'),'acc_item', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
                </div>
                <div class="col-md-3">
                  <?php echo form_hidden('type', 'balance_sheet_detail'); ?>
                  <?php echo form_hidden('page', 1); ?>
                  <a href="javascript:void(0);" onclick="report_load();" class="btn btn-info btn-submit mtop25"><?php echo _l('filter'); ?></a>
                </div>
              <?php echo form_close(); ?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="btn-group pull-right mtop25">
                 <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                 <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                       <a href="#" onclick="printDiv2(); return false;">
                       <?php echo _l('export_to_pdf'); ?>
                       </a>
                    </li>
                    <li>
                       <a href="#" onclick="printExcel(); return false;">
                       <?php echo _l('export_to_excel'); ?>
                       </a>
                    </li>
                 </ul>
              </div>
            </div>
          </div>
          <div class="row"> 
            <div class="col-md-12"> 
              <hr>
            </div>
          </div>
          <button type="button" class="btn btn-default" id="expander"><?php echo _l('expand_all'); ?></button>
          <button type="button" class="btn btn-default" id="collapser"><?php echo _l('collapse_all'); ?></button>
          <div class="page-size2" id="DivIdToPrint">
            <div id="accordion">
              <div class="card">
                <table class="tree">
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo form_open(admin_url('accounting/covert_pdf_report'),array('id'=>'render_pdf-form')); ?>
<?php echo form_hidden('html'); ?>
<?php echo form_hidden('pdf_name'); ?>
<?php echo form_close(); ?>

<!-- box loading -->
<div id="accounting-box-loading"></div>
<?php init_tail(); ?>
</body>
</html>
