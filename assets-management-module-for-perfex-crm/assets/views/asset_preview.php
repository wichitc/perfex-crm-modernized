<div class="panel_s" style="border-radius: 8px; overflow: hidden;">
	<div class="panel-body" style="padding: 0;">
       <!-- Asset Header -->
       <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; color: #fff;">
         <div class="row">
           <div class="col-md-8">
             <h4 class="no-margin" style="color: #fff; font-weight: 600; font-size: 20px;">
               <i class="fa fa-cube mright5"></i> <?php echo htmlspecialchars($assets->assets_name); ?>
             </h4>
             <span style="opacity: 0.85; font-size: 13px;"><?php echo htmlspecialchars($assets->assets_code); ?></span>
           </div>
           <div class="col-md-4 text-right">
             <?php if (has_permission('assets', '', 'edit') || is_admin()) { ?>
             <a href="#" onclick="edit_asset_by_id(<?php echo $assets->id; ?>); return false;" class="btn btn-sm" style="background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
               <i class="fa fa-pencil"></i> <?php echo _l('edit'); ?>
             </a>
             <?php } ?>
           </div>
         </div>
       </div>
       
       <div style="padding: 20px;">
       <!-- Action Buttons -->
      <?php if (has_permission('assets', '', 'edit') || is_admin()) { ?>
     <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
             <div class="btn-toolbar" role="toolbar">
               <a href="#" onclick="allocation(); return false;" class="btn btn-primary btn-sm mright5 mtop5" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('allocation')); ?>"><i class="fa fa-user-plus"></i><span class="hidden-sm hidden-xs"><?php echo ' '.htmlspecialchars(_l('allocation')); ?></span></a>
               <a href="#" onclick="recalled(); return false;" class="btn btn-info btn-sm mright5 mtop5" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('recalled')); ?>"><i class="fa fa-undo"></i><span class="hidden-sm hidden-xs"><?php echo ' '.htmlspecialchars(_l('recalled')); ?></span></a>
               <a href="#" onclick="additional(); return false;" class="btn btn-success btn-sm mright5 mtop5" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('additional')); ?>"><i class="fa fa-plus-circle"></i><span class="hidden-sm hidden-xs"><?php echo ' '.htmlspecialchars(_l('additional')); ?></span></a>
               <a href="#" onclick="noti_lost(); return false;" class="btn btn-warning btn-sm mright5 mtop5" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('noti_lost')); ?>"><i class="fa fa-exclamation-triangle"></i><span class="hidden-sm hidden-xs"><?php echo ' '.htmlspecialchars(_l('noti_lost')); ?></span></a>
               <a href="#" onclick="broken(); return false;" class="btn btn-danger btn-sm mright5 mtop5" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('noti_broken')); ?>"><i class="fa fa-chain-broken"></i><span class="hidden-sm hidden-xs"><?php echo ' '.htmlspecialchars(_l('noti_broken')); ?></span></a>
               <a href="#" onclick="liquidation(); return false;" class="btn btn-default btn-sm mright5 mtop5" style="background-color:#6c757d;color:#fff;border-color:#6c757d;" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('liquidation')); ?>"><i class="fa fa-archive"></i><span class="hidden-sm hidden-xs"><?php echo ' '.htmlspecialchars(_l('liquidation')); ?></span></a>
               <a href="#" onclick="warranty(); return false;" class="btn btn-sm mright5 mtop5" style="background-color:#17a2b8;color:#fff;border-color:#17a2b8;" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('warranty')); ?>"><i class="fa fa-shield"></i><span class="hidden-sm hidden-xs"><?php echo ' '.htmlspecialchars(_l('warranty')); ?></span></a>
             </div>
        </div>
     </div>
     <?php } ?>
	   	<div class="row">
	      	<div class="horizontal-scrollable-tabs preview-tabs-top">
              <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
              <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
              <div class="horizontal-tabs">
              	<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
              	  <li role="presentation" class="active">
                     <a href="#general_infor" aria-controls="general_infor" role="tab" data-toggle="tab" aria-controls="general_infor">
                     <?php echo htmlspecialchars(_l('general_infor')); ?>
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#inventory_history" aria-controls="inventory_history" role="tab" data-toggle="tab" aria-controls="inventory_history">
                     <?php echo htmlspecialchars(_l('inventory_history')); ?>
                     </a>
                  </li>

                  <li role="presentation">
                     <a href="#pending_withdrawing" aria-controls="pending_withdrawing" role="tab" data-toggle="tab" aria-controls="pending_withdrawing">
                     <?php echo htmlspecialchars(_l('pending_withdrawing_history')); ?>
                     </a>
                  </li>
              	</ul>
              </div>
	    	</div> 
	    	<div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="general_infor">
           	 <div class="panel panel-info">
                 <div class="panel-body">
               	<!-- New Layout: Info on Left, Image/Codes on Right -->
               	<div class="row">
               	    <!-- LEFT SIDE: All Asset Information -->
               	    <div class="col-md-6">
               	        <h4><?php echo htmlspecialchars(_l('asset_information')); ?></h4>
               	        <hr/>
               	        <table class="table table-striped table-bordered">
                           <tbody>
                              <tr class="project-overview">
                                 <td class="bold" style="width:40%;"><?php echo htmlspecialchars(_l('asset_code')); ?></td>
                                 <td><?php echo htmlspecialchars($assets->assets_code); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('asset_name')); ?></td>
                                 <td><?php echo htmlspecialchars($assets->assets_name); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('asset_group')); ?></td>
                                 <td><?php echo htmlspecialchars(get_asset_group($assets->asset_group)); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('asset_location')); ?></td>
                                 <td><?php echo htmlspecialchars(get_asset_location($assets->asset_location)); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('room_management')); ?></td>
                                 <td><?php echo htmlspecialchars(get_asset_dpm($assets->department)); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('series')); ?></td>
                                 <td><?php echo htmlspecialchars($assets->series); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('date_buy')); ?></td>
                                 <td><?php echo htmlspecialchars(_d($assets->date_buy)); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('warranty_period')); ?></td>
                                 <td><?php echo htmlspecialchars($assets->warranty_period.' '._l('month')); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('depreciation')); ?></td>
                                 <td><?php echo htmlspecialchars($assets->depreciation.' '._l('month')); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('visible_to_client')); ?></td>
                                 <td><?php echo htmlspecialchars(($assets->visible_to_client) ? 'Yes' : 'No'); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('allocation')); ?></td>
                                 <td>
                                   <?php
                                     if (!empty($assets->belongs_to)) {
                                         $belongs_to = explode(',', $assets->belongs_to);
                                         $html       = '';
                                         foreach ($belongs_to as $value) {
                                             if (!empty($value)) {
                                                 $html .= '<a href="'.admin_url('clients/client/'.$value).'">'.get_company_name($value).'</a><br>';
                                             }
                                         }
                                         echo $html ?: '-';
                                     } else {
                                         echo '-';
                                     }
                                   ?>
                                 </td>
                              </tr>
                            </tbody>
                         </table>
                         
                         <h4 style="margin-top:20px;"><?php echo htmlspecialchars(_l('supplier_information')); ?></h4>
                         <hr/>
                         <table class="table table-striped table-bordered">
                           <tbody>
                              <tr class="project-overview">
                                 <td class="bold" style="width:40%;"><?php echo htmlspecialchars(_l('supplier_name')); ?></td>
                                 <td><?php echo htmlspecialchars($assets->supplier_name); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('supplier_phone')); ?></td>
                                 <td><?php echo htmlspecialchars($assets->supplier_phone); ?></td>
                              </tr>
                              <tr class="project-overview">
                                 <td class="bold"><?php echo htmlspecialchars(_l('supplier_address')); ?></td>
                                 <td><?php echo htmlspecialchars($assets->supplier_address); ?></td>
                              </tr>
                            </tbody>
                         </table>
               	    </div>
               	    
               	    <!-- RIGHT SIDE: Image, Barcode & QR Code -->
               	    <div class="col-md-6">
               	        <!-- Asset Image Card -->
               	        <div class="text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); margin-bottom: 20px;">
               	            <a href="<?php echo module_dir_url('assets', 'uploads').'/'.$assets->asset_image; ?>" data-lightbox="asset-image" data-title="<?php echo htmlspecialchars($assets->assets_name); ?>">
               	                <img alt="<?php echo htmlspecialchars($assets->assets_name); ?>" 
               	                     src="<?php echo module_dir_url('assets', 'uploads').'/'.$assets->asset_image; ?>" 
               	                     class="img-thumbnail" 
               	                     style="width: 200px; height: 200px; object-fit: cover; border: 4px solid #fff; border-radius: 10px; cursor: pointer;" 
               	                     onerror="this.src='<?php echo module_dir_url('assets', 'uploads'); ?>/image-not-available.png'">
               	            </a>
               	            <p style="margin-top: 12px; margin-bottom: 0; color: #fff; font-weight: 700; font-size: 16px;">
               	                <?php echo htmlspecialchars($assets->assets_code); ?>
               	            </p>
               	        </div>
               	        
               	        <!-- Barcode & QR Code Section -->
               	        <div class="panel panel-default">
               	            <div class="panel-heading text-center">
               	                <strong><i class="fa fa-barcode"></i> <?php echo _l('barcode_qr_codes'); ?></strong>
               	            </div>
               	            <div class="panel-body">
               	                <div class="row">
               	                    <div class="col-xs-6 text-center" style="border-right: 1px solid #eee;">
               	                        <p><strong><?php echo _l('barcode'); ?></strong></p>
               	                        <?php if (!empty($barcode_url)): ?>
               	                            <img src="<?php echo $barcode_url; ?>" alt="Barcode" style="max-width:100%; max-height:60px; margin-bottom:10px;">
               	                            <br>
               	                        <?php else: ?>
               	                            <p class="text-muted" style="font-size:12px;"><?php echo _l('no_barcode'); ?></p>
               	                        <?php endif; ?>
               	                        <?php if (has_permission('assets', '', 'edit') || is_admin()): ?>
               	                            <a href="<?php echo admin_url('assets/generate_barcode/' . $assets->id); ?>" class="btn btn-default btn-xs">
               	                                <i class="fa fa-barcode"></i> <?php echo _l('generate_barcode'); ?>
               	                            </a>
               	                        <?php endif; ?>
               	                    </div>
               	                    <div class="col-xs-6 text-center">
               	                        <p><strong><?php echo _l('qr_code'); ?></strong></p>
               	                        <?php if (!empty($qr_url)): ?>
               	                            <img src="<?php echo $qr_url; ?>" alt="QR Code" style="max-width:100px; max-height:100px; margin-bottom:10px;">
               	                            <br>
               	                        <?php else: ?>
               	                            <p class="text-muted" style="font-size:12px;"><?php echo _l('no_qr_code'); ?></p>
               	                        <?php endif; ?>
               	                        <?php if (has_permission('assets', '', 'edit') || is_admin()): ?>
               	                            <a href="<?php echo admin_url('assets/generate_qr_code/' . $assets->id); ?>" class="btn btn-default btn-xs">
               	                                <i class="fa fa-qrcode"></i> <?php echo _l('generate_qr'); ?>
               	                            </a>
               	                        <?php endif; ?>
               	                    </div>
               	                </div>
               	            </div>
               	        </div>
               	    </div>
               	</div>
               	
               	<!-- Asset Files Section -->
               	<div class="row">
               	  <div class="col-md-12" id="assets_pv_file">
               	  	<?php
                           $file_html = '';
                           if (count($asset_file) > 0) {
                               $file_html .= '<hr />
				                    <p class="bold text-muted">'._l('assets_files').'</p>';
                               foreach ($asset_file as $f) {
                                   $href_url = site_url(ASSETS_PATH.$f['rel_id'].'/'.$f['file_name']).'" download';
                                   if (!empty($f['external'])) {
                                       $href_url = $f['external_link'];
                                   }
                                   $file_html .= '<div class="mbot15 row inline-block full-width" data-attachment-id="'.$f['id'].'" style="display: flex; align-items: center;">
				              <div class="col-md-8" style="display: flex; align-items: center; gap: 10px;">
				                 <a name="preview-ase-btn" onclick="preview_asset_btn(this); return false;" rel_id = "'.$f['rel_id'].'" id = "'.$f['id'].'" href="Javascript:void(0);" class="btn btn-success" data-toggle="tooltip" title data-original-title="'._l('preview_file').'" style="margin-right: 10px;"><i class="fa fa-eye"></i></a>
				                 <div style="margin-right: 8px;"><i class="'.get_mime_class($f['filetype']).'"></i></div>
				                 <div>
				                   <a href=" '.$href_url.'" target="_blank" download>'.$f['file_name'].'</a>
				                   <br />
				                   <small class="text-muted">'.$f['filetype'].'</small>
				                 </div>
				              </div>
				              <div class="col-md-4 text-right">';
                                   if ($f['staffid'] == get_staff_user_id() || is_admin()) {
                                       $file_html .= '<a href="#" class="text-danger" onclick="delete_asset_attachment('.$f['id'].'); return false;"><i class="fa fa-times"></i></a>';
                                   }
                                   $file_html .= '</div></div>';
                               }
                               $file_html .= '<hr />';
                               echo $file_html;
                           }
                        ?>
               	  </div>
               	</div>
                 </div>
                </div>
                 <div class="panel panel-danger backgroundscroll">
                  <div class="panel-body">
                	<div class="row col-md-12">
                		<h4><?php echo htmlspecialchars(_l('asset_value')); ?></h4>
                		<hr/>
                	</div>
                	<div class="col-md-6 noleftrightpadding">
                		<table class="table border table-striped nomargintop">
                        <tbody>
                           <tr class="project-overview">
                              <td class="bold"><?php echo htmlspecialchars(_l('amount')); ?></td>
                              <td><?php echo htmlspecialchars($assets->amount); ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo htmlspecialchars(_l('unit_price')); ?></td>
                              <td><?php echo htmlspecialchars(app_format_money($assets->unit_price, '')); ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo htmlspecialchars(_l('amount_allocate')); ?></td>
                              <td><?php echo htmlspecialchars($assets->total_allocation); ?></td>
                           </tr>
                           
                           <tr class="project-overview">
                              <td class="bold"><?php echo htmlspecialchars(_l('depreciation_value')); ?></td>
                              <td><?php
                               $m           = (strtotime(date('Y-m-d')) - strtotime($assets->date_buy)) / (60 * 60 * 24 * 31);
                               $d_per_month = ($assets->unit_price * $assets->amount) / $assets->depreciation;
                               echo htmlspecialchars(app_format_money($m * $d_per_month, ''));
                                ?></td>
                           </tr>
	                        </tbody>
	                     </table>
                	</div>
                	<div class="col-md-6 noleftrightpadding">
                		<table class="table table-striped nomargintop">
                        <tbody>
                           <tr class="project-overview">
                              <td class="bold"><?php echo htmlspecialchars(_l('unit')); ?></td>
                              <td><?php echo htmlspecialchars(get_asset_units($assets->unit)); ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo htmlspecialchars(_l('original_price')); ?></td>
                              <td><?php echo htmlspecialchars(app_format_money($assets->unit_price * $assets->amount, '')); ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo htmlspecialchars(_l('amount_rest')); ?></td>
                              <td><?php echo htmlspecialchars($assets->amount - $assets->total_allocation); ?></td>
                           </tr>
                           
                           <tr class="project-overview">
                              <td class="bold"><?php echo htmlspecialchars(_l('residual_value')); ?></td>
                              <td><?php echo htmlspecialchars(app_format_money($assets->unit_price * $assets->amount - $m * $d_per_month, '')); ?></td>
                           </tr>
	                        </tbody>
	                     </table>
                		</div>
                    </div>
                  </div>    
                </div>
                <div role="tabpanel" class="tab-pane" id="inventory_history">
                	<?php
                        $table_data = [
                            _l('time'),
                            _l('action'),
                            _l('inventory_begin'),
                            _l('inventory_end'),
                            _l('cost'),
                            ];
                        render_datatable($table_data, 'table_inventory_history');
                        ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="pending_withdrawing">
                	<?php
                        $table_data = [
                            _l('time'),
                            _l('asset_name'),
                            _l('action'),
                            _l('acction_code'),
                            _l('quantity_as_qty'),
                            _l('acction_from'),
                            _l('acction_to'),
                            ];
                        render_datatable($table_data, 'table_action');
                        ?>
                </div>
            </div>  
        </div>
       </div><!-- End padding div -->		
	</div>
</div>
<div id="asset_file_data"></div>
<?php include_once 'includes/allocation_modal.php'; ?>
<?php include_once 'includes/recalled_modal.php'; ?>
<?php include_once 'includes/additional_modal.php'; ?>
<?php include_once 'includes/noti_lost_modal.php'; ?>
<?php include_once 'includes/liquidation_modal.php'; ?>
<?php include_once 'includes/warranty_modal.php'; ?>
<?php include_once 'includes/broken_modal.php'; ?>
<script>
  init_datepicker();
  initDataTable('.table-table_inventory_history', admin_url+'assets/table_inventory_history/<?php echo htmlspecialchars($assets->id); ?>');
  initDataTable('.table-table_action', admin_url+'assets/table_action/<?php echo htmlspecialchars($assets->id); ?>');
  
  // Edit asset by ID - triggers the edit modal with asset data
  function edit_asset_by_id(id) {
    requestGet('assets/get_asset/' + id).done(function(response) {
      if (response) {
        var data = typeof response === 'string' ? JSON.parse(response) : response;
        // Populate form and show modal
        $('#additional').html('');
        $('#additional').append(hidden_input('id', id));
        $('#assets input[name="assets_code"]').val(data.assets_code);
        $('#assets input[name="assets_name"]').val(data.assets_name);
        $('#assets input[name="date_buy"]').val(data.date_buy);
        $('#assets input[name="amount"]').val(data.amount);
        $('#assets input[name="unit_price"]').val(data.unit_price);
        $('#assets input[name="supplier_phone"]').val(data.supplier_phone);
        $('#assets input[name="supplier_name"]').val(data.supplier_name);
        $('#assets input[name="supplier_address"]').val(data.supplier_address);
        $('#assets input[name="series"]').val(data.series);
        $('#assets input[name="warranty_period"]').val(data.warranty_period);
        $('#assets input[name="depreciation"]').val(data.depreciation);
        $('#assets select[name="unit"]').val(data.unit).change();
        $('#assets select[name="asset_group"]').val(data.asset_group).change();
        $('#assets select[name="department"]').val(data.department).change();
        $('#assets select[name="asset_location"]').val(data.asset_location).change();
        $('#assets textarea[name="description"]').val(data.description);
        
        if (data.asset_image) {
          $('#assets #asset_existing_image').html('<img src="' + site_url + 'modules/assets/uploads/' + data.asset_image + '" class="img-thumbnail img-responsive" style="width: 150px; height: 150px;" onerror="this.src=\'' + site_url + 'modules/assets/uploads/image-not-available.png\'">');
        }
        
        $('#assets input#visible_to_client').prop('checked', data.visible_to_client == "1");
        $("#assets #asset_image").removeAttr('required');
        $("#assets .attachment .req").hide();
        
        $('#assets').modal('show');
        $('.edit-title').removeClass('hide');
        $('.add-title').addClass('hide');
      }
    });
  }
	function delete_asset_attachment(id) {
    if (confirm_delete()) {
        requestGet('assets/delete_asset_attachment/' + id).done(function(success) {
            if (success == 1) {
                $("#assets_pv_file").find('[data-attachment-id="' + id + '"]').remove();
            }
        }).fail(function(error) {
            alert_float('danger', error.responseText);
        });
    }
  }
  function allocation(){
    $('#allocation_modal').modal('show');
  }
  function recalled(){
    $('#recalled_modal').modal('show');
  }
  function additional(){
    $('#additional_modal').modal('show');
  }
  function noti_lost(){
    $('#noti_lost_modal').modal('show');
  }
  function liquidation(){
    $('#liquidation_modal').modal('show');
  }
  function warranty(){
    $('#warranty_modal').modal('show');
  }
  function broken(){
    $('#broken_modal').modal('show');
  }
$("input[data-type='currency']").on({
    keyup: function() {        
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
});
function formatNumber(n) {
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function formatCurrency(input, blur) {
  var input_val = input.val();
  if (input_val === "") { return; }
  var original_len = input_val.length;
  var caret_pos = input.prop("selectionStart");
  if (input_val.indexOf(".") >= 0) {
    var decimal_pos = input_val.indexOf(".");
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);
    left_side = formatNumber(left_side);
    right_side = formatNumber(right_side);
    right_side = right_side.substring(0, 2);
    input_val = left_side + "." + right_side;

  } else {
    input_val = formatNumber(input_val);
    input_val = input_val;
  }
  input.val(input_val);
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}
</script>