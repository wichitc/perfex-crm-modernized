  <div class="col-md-12">
   <?php echo form_open_multipart(admin_url('warehouse/stock_balance_pdf'), ['id' => 'print_report']); ?>
   <div class="row">
    <div class=" col-md-3">
      <div class="form-group">
        <label><?php echo _l('warehouse_name') ?></label>
        <select name="warehouse_filter[]" id="warehouse_filter" class="selectpicker" multiple="true" data-live-search="true" data-width="100%" data-none-selected-text="" data-actions-box="true">

          <?php foreach ($warehouse_filter as $warehouse) {?>
            <option value="<?php echo new_html_entity_decode($warehouse['warehouse_id']); ?>"><?php echo new_html_entity_decode($warehouse['warehouse_name']); ?></option>
          <?php }?>
        </select>
      </div>
    </div>

    <div class=" col-md-3">
      <?php $this->load->view('warehouse/item_include/item_select', ['select_name' => 'commodity_filter[]', 'id_name' => 'commodity_filter', 'multiple' => true, 'label_name' => 'commodity']);?>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label><?php echo _l('commodity_type') ?></label>
        <select name="commodity_type[]" id="commodity_type" class="selectpicker" multiple="true" data-live-search="true" data-width="100%" data-none-selected-text="" data-actions-box="true">

          <?php foreach ($commodity_types as $commodity_type) {?>
            <option value="<?php echo new_html_entity_decode($commodity_type['commodity_type_id']); ?>"><?php echo new_html_entity_decode($commodity_type['commondity_name']); ?></option>
          <?php }?>
        </select>
      </div>
    </div>

    <div class="col-md-2 hide">
      <?php echo render_date_input('from_date', 'from_date', _d(date('Y-m-d', strtotime('-7 day', strtotime(date('Y-m-d')))))); ?>
    </div>
    <div class="col-md-2 hide">
      <?php echo render_date_input('to_date', 'to_date', _d(date('Y-m-d'))); ?>
    </div>
    <div class="col-md-3">
      <div class="form-group mtop25">
        <div class="btn-group">
         <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf"></i><?php if (is_mobile()) {echo ' PDF';}?> <span class="caret"></span></a>
         <ul class="dropdown-menu dropdown-menu-right">
          <li class="hidden-xs"><a href="?output_type=I" target="_blank" onclick="stock_submit(this); return false;"><?php echo _l('download_pdf'); ?></a></li>
          <li class="hidden-xs"><a href="?output_type=I" target="_blank" onclick="stock_balance_report_export_excel(this); return false;"><?php echo _l('download_xlsx'); ?></a></li>

        </ul>
      </div>
      <a href="#" id="dowload_items"  class="btn btn-warning pull-left  mr-4 button-margin-r-b hide"><?php echo _l('download_xlsx'); ?></a>
      <a href="#" onclick="get_data_stock_balance_report(); return false;" class="btn btn-success" ><?php echo _l('_filter'); ?></a>

    </div>
  </div>

</div>

<?php echo form_close(); ?>
</div>
<?php
    $font_size     = get_option('pdf_font_size');
    $pdf_font_size = ($font_size + 5);
?>
<hr class="hr-panel-heading" />
<div class="col-md-12" id="report">
  <div class="panel panel-info col-md-12 panel-padding">

    <div class="panel-body" id="stock_s_report">

      <div class="col-md-12">
        <div class="table-responsive">
         <table class="table">
          <tr>
            <td align="left" width="30%"></td>
            <td align="center" width="40%"><div style="color:#424242;" style="font-size:                                                                                         <?php echo html_entity_decode($pdf_font_size); ?>px">
              <?php echo html_entity_decode(format_organization_info()); ?>
            </div></td>
            <td align="right" width="30%">
              <span><?php echo _l('clients_invoice_dt_date'); ?>:<?php echo date('d/m/Y H:i') ?></span><br>
              <span><?php echo _l('wh_printed_by'); ?>:<?php echo get_staff_full_name(); ?></span><br>
            </td>
          </tr>
         </table>
        </div>
              <p><h4 class="bold text-center"><?php echo (_l('wh_stock_balance_detail_batch_and_serialized_by_warehouse')); ?></h4></p>

        <div class="table-responsive">
         <table class="table">
          <tr>
            <td align="left" width="30%">
              <span><?php echo _l('clients_invoice_dt_date'); ?>:<?php echo date('d/m/Y H:i') ?></span><br>
            </td>
            <td align="left" width="40%">
              <?php echo _l('warehouse_filter'); ?>:<span id="warehouse_html"></span><br>
            </td>
            <td align="left" width="30%"></td>

          </tr>
         </table>
        </div>
      </div>



      <div class="col-md-12">
        <div class="table-responsive">
         <table class="table table-bordered">
          <thead>
            <th><?php echo _l('_order') ?></th>
             <th><?php echo _l('wh_item_code') ?></th>
             <th><?php echo _l('description') ?></th>
             <th><?php echo _l('wh_item_type') ?></th>
             <th><?php echo _l('wh_group') ?></th>
             <th><?php echo _l('expense_dt_table_heading_category') ?></th>
             <th><?php echo _l('wh_batch_no') ?></th>
             <th><?php echo _l('wh_serial_hashtag') ?></th>
             <th><?php echo _l('expiry_date') ?></th>
             <th><?php echo _l('wh_uom') ?></th>
             <th><?php echo _l('wh_bal_qty') ?></th>
             <th><?php echo _l('wh_unit_cost') ?></th>
             <th><?php echo _l('wh_total_cost') ?></th>
          </thead>
          <tbody id="stock_balance_report">
           <tr>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
            <td>.....</td>
          </tr>
          <tr>
           <th colspan="9" class="text-right"><?php echo _l('total') ?> : </th>
           <th colspan="1"></th>
           <th colspan="1"></th>
           <th colspan="1"></th>
           <th colspan="1"></th>
         </tr>
       </tbody>
     </table>
   </div>
 </div>


 <br>

 <br>
 <br>
 <br>
 <br>

</div>
</div>
</div>
<style type="text/css">
  .table>tbody>tr>td{
    border-top: 0px solid #ddd;
  }
</style>