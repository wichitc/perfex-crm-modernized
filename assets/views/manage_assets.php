<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="row" style="margin-bottom: 5px;">
                     <div class="col-md-6">
                      <h4 class="no-margin font-bold" style="display: flex; align-items: center; gap: 10px;">
                        <span style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: #fff; padding: 8px 12px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">
                          <i class="fa fa-cubes" aria-hidden="true"></i>
                        </span>
                        <?php echo _l($title); ?>
                      </h4>
                     </div>
                     <div class="col-md-6 text-right">
                    	<?php if (has_permission('assets', '', 'create') || is_admin()) { ?>
                        <a href="#" onclick="new_asset(); return false;" class="btn btn-success" style="padding: 8px 16px; font-weight: 500;">
                            <i class="fa fa-plus"></i> <?php echo _l('new_asset'); ?>
                        </a>
                    	<?php } ?>
                     </div>
                  </div>
                  <hr class="hr-panel-heading" />
                <div class="horizontal-scrollable-tabs preview-tabs-top" style="position: relative;">
                  <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                  <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                  <!-- Toggle Button positioned absolutely to the right -->
                  <a href="#" class="btn btn-default btn-sm btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view_asset('.asset_sm','#asset_sm_view'); return false;" data-toggle="tooltip" title="<?php echo htmlspecialchars(_l('invoices_toggle_table_tooltip')); ?>" style="position: absolute; right: 0; top: 5px; z-index: 10;"><i class="fa fa-angle-double-left"></i></a>
                  <div class="horizontal-tabs" style="margin-right: 40px;">
                  <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                 <li role="presentation" class="active">
                     <a href="#all_asset" aria-controls="all_asset" role="tab" data-toggle="tab" aria-controls="all_asset">
                     <span class="glyphicon glyphicon-align-justify"></span>&nbsp;<?php echo _l('all_asset'); ?>
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#not_pending_yet" aria-controls="not_pending_yet" role="tab" data-toggle="tab" aria-controls="not_pending_yet">
                     <span class="glyphicon glyphicon-briefcase"></span>&nbsp;<?php echo htmlspecialchars(_l('not_pending_yet')); ?>
                     </a>
                  </li>

                  <li role="presentation">
                     <a href="#using" aria-controls="using" role="tab" data-toggle="tab" aria-controls="using">
                     <span class="glyphicon glyphicon-expand"></span>&nbsp;<?php echo htmlspecialchars(_l('using')); ?>
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#liquidation" aria-controls="liquidation" role="tab" data-toggle="tab" aria-controls="liquidation">
                     <i class="glyphicon glyphicon-unchecked"></i>&nbsp;<?php echo htmlspecialchars(_l('liquidation')); ?>
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#warranty_repair" aria-controls="warranty_repair" role="tab" data-toggle="tab" aria-controls="warranty_repair">
                     <i class="glyphicon glyphicon-cog"></i>&nbsp;<?php echo htmlspecialchars(_l('warranty_repair')); ?>
                     </a>
                  </li>
                   <li role="presentation">
                     <a href="#lost" aria-controls="lost" role="tab" data-toggle="tab" aria-controls="lost">
                     <span class="glyphicon glyphicon-new-window"></span>&nbsp;<?php echo htmlspecialchars(_l('lost')); ?>
                     </a>
                  </li>
                   <li role="presentation">
                     <a href="#broken" aria-controls="broken" role="tab" data-toggle="tab" aria-controls="broken">
                     <span class="glyphicon glyphicon-remove"></span>&nbsp;<?php echo htmlspecialchars(_l('broken')); ?>
                     </a>
                  </li>
               </ul>
             </div>
           </div>
         </div>
       </div>
     </div>
     

               
     <div class="col-md-12" id="small-table">
            <div class="panel_s">
               <div class="panel-body">
                <?php echo form_hidden('asset_id', $asset_id); ?>
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="all_asset">
                    <?php
                        $table_data = [];
                        array_push($table_data, [
                          'name'    => _l('asset_image'),
                          'th_attrs'=> ['id'=>'th-consent', 'class'=>'not-export'],
                        ]);
                        $table_data = array_merge($table_data, [
                            _l('asset_image'),
                            _l('asset_code'),
                            _l('asset_name'),
                            _l('asset_group'),
                            _l('date_buy'),
                            _l('amount_allocate'),
                            _l('amount_rest'),
                            _l('original_price'),
                            _l('unit'),
                            _l('department'),
                            _l('assigned_to_customer'),
                            ]);
                        render_datatable($table_data, 'table_assets1', ['asset_sm' => 'asset_sm']);
                        ?>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="not_pending_yet">
                    <?php
                        $table_data = [];
                        array_push($table_data, [
                          'name'    => _l('asset_image'),
                          'th_attrs'=> ['id'=>'th-consent', 'class'=>'not-export'],
                        ]);
                        $table_data = array_merge($table_data, [
                            _l('asset_image'),
                            _l('asset_code'),
                            _l('asset_name'),
                            _l('asset_group'),
                            _l('date_buy'),
                            _l('amount_allocate'),
                            _l('amount_rest'),
                            _l('original_price'),
                            _l('unit'),
                            _l('department'),
                            _l('assigned_to_customer'),
                            ]);
                        render_datatable($table_data, 'table_assets2', ['asset_sm' => 'asset_sm']);
                        ?>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="using">
                    <?php
                        $table_data = [];
                        array_push($table_data, [
                          'name'    => _l('asset_image'),
                          'th_attrs'=> ['id'=>'th-consent', 'class'=>'not-export'],
                        ]);
                        $table_data = array_merge($table_data, [
                            _l('asset_image'),
                            _l('asset_code'),
                            _l('asset_name'),
                            _l('asset_group'),
                            _l('date_buy'),
                            _l('amount_allocate'),
                            _l('amount_rest'),
                            _l('original_price'),
                            _l('unit'),
                            _l('department'),
                            _l('assigned_to_customer'),
                            ]);
                        render_datatable($table_data, 'table_assets3', ['asset_sm' => 'asset_sm']);
                        ?>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="liquidation">
                    <?php
                        $table_data = [];
                        array_push($table_data, [
                          'name'    => _l('asset_image'),
                          'th_attrs'=> ['id'=>'th-consent', 'class'=>'not-export'],
                        ]);
                        $table_data = array_merge($table_data, [
                            _l('asset_image'),
                            _l('asset_code'),
                            _l('asset_name'),
                            _l('asset_group'),
                            _l('date_buy'),
                            _l('amount_allocate'),
                            _l('amount_rest'),
                            _l('original_price'),
                            _l('unit'),
                            _l('department'),
                            _l('assigned_to_customer'),
                            ]);
                        render_datatable($table_data, 'table_assets4', ['asset_sm' => 'asset_sm']);
                        ?>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="warranty_repair">
                    <?php
                        $table_data = [];
                        array_push($table_data, [
                          'name'    => _l('asset_image'),
                          'th_attrs'=> ['id'=>'th-consent', 'class'=>'not-export'],
                        ]);
                        $table_data = array_merge($table_data, [
                            _l('asset_image'),
                            _l('asset_code'),
                            _l('asset_name'),
                            _l('asset_group'),
                            _l('date_buy'),
                            _l('amount_allocate'),
                            _l('amount_rest'),
                            _l('original_price'),
                            _l('unit'),
                            _l('department'),
                            _l('assigned_to_customer'),
                            ]);
                        render_datatable($table_data, 'table_assets5', ['asset_sm' => 'asset_sm']);
                        ?>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="lost">
                    <?php
                        $table_data = [];
                        array_push($table_data, [
                          'name'    => _l('asset_image'),
                          'th_attrs'=> ['id'=>'th-consent', 'class'=>'not-export'],
                        ]);
                        $table_data = array_merge($table_data, [
                            _l('asset_image'),
                            _l('asset_code'),
                            _l('asset_name'),
                            _l('asset_group'),
                            _l('date_buy'),
                            _l('amount_allocate'),
                            _l('amount_rest'),
                            _l('original_price'),
                            _l('unit'),
                            _l('department'),
                            _l('assigned_to_customer'),
                            ]);
                        render_datatable($table_data, 'table_assets6', ['asset_sm' => 'asset_sm']);
                        ?>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="broken">
                    <?php
                        $table_data = [];
                        array_push($table_data, [
                          'name'    => _l('asset_image'),
                          'th_attrs'=> ['id'=>'th-consent', 'class'=>'not-export'],
                        ]);
                        $table_data = array_merge($table_data, [
                            _l('asset_image'),
                            _l('asset_code'),
                            _l('asset_name'),
                            _l('asset_group'),
                            _l('date_buy'),
                            _l('amount_allocate'),
                            _l('amount_rest'),
                            _l('original_price'),
                            _l('unit'),
                            _l('department'),
                            _l('assigned_to_customer'),
                            ]);
                        render_datatable($table_data, 'table_assets7', ['asset_sm' => 'asset_sm']);
                        ?>
                  </div>
                </div>
               </div>
            </div>
         </div>
         <div class="col-md-7 small-table-right-col">
            <div id="asset_sm_view" class="hide">
            </div>
         </div>

      </div>
   </div>
</div>
<div class="modal fade" id="assets" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open_multipart(admin_url('assets/asset'), ['id'=>'assets-form']); ?>
        <div class="modal-content modalwidth">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo htmlspecialchars(_l('edit_asset')); ?></span>
                    <span class="add-title"><?php echo htmlspecialchars(_l('new_asset')); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                    <div class="panel panel-info">
                      <div class="panel-heading"><?php echo htmlspecialchars(_l('asset_information')); ?></div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-6">
                          <?php echo render_input('assets_code', 'asset_code', ''); ?>
                          </div>
                          <div class="col-md-6">
                          <?php echo render_input('assets_name', 'asset_name', ''); ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                          <?php $arrAtt        = [];
                          $arrAtt['data-type'] = 'currency';
                          echo render_input('amount', 'amounts', '', 'number'); ?>
                          </div>
                          <div class="col-md-6">
                          <label for="unit"><?php echo htmlspecialchars(_l('unit')); ?></label>
                          <select name="unit" id="unit" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                            <option value=""></option>
                            <?php foreach ($unit as $s) { ?>
                              <option value="<?php echo htmlspecialchars($s['unit_id']); ?>"><?php echo htmlspecialchars($s['unit_name']); ?></option>
                              <?php } ?>
                          </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                          <?php echo render_input('series', 'series', ''); ?>
                          </div>
                          <div class="col-md-6">
                          <label for="asset_group"><?php echo htmlspecialchars(_l('asset_group')); ?></label>
                          <select name="asset_group" id="asset_group" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                            <option value=""></option>
                            <?php foreach ($group as $s) { ?>
                              <option value="<?php echo htmlspecialchars($s['group_id']); ?>"><?php echo htmlspecialchars($s['group_name']); ?></option>
                              <?php } ?>
                          </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                          <label for="department"><?php echo htmlspecialchars(_l('room_management')); ?></label>
                          <select name="department" id="department" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                            <option value=""></option>
                            <?php foreach ($departments as $s) { ?>
                              <option value="<?php echo htmlspecialchars($s['departmentid']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                              <?php } ?>
                          </select>
                          </div>
                          <div class="col-md-6">
                          <label for="asset_location"><?php echo htmlspecialchars(_l('asset_location')); ?></label>
                          <select name="asset_location" id="asset_location" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo htmlspecialchars(_l('ticket_settings_none_assigned')); ?>">
                            <option value=""></option>
                            <?php foreach ($location as $s) { ?>
                              <option value="<?php echo htmlspecialchars($s['location_id']); ?>"><?php echo htmlspecialchars($s['location']); ?></option>
                              <?php } ?>
                          </select>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-md-6">
                          <?php echo render_date_input('date_buy', 'date_buy', ''); ?>
                          </div>
                          <div class="col-md-6">
                          <?php echo render_input('warranty_period', 'warranty_period', '', 'number'); ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                          <?php echo render_input('unit_price', 'unit_price', '', 'text', $arrAtt); ?>
                          </div>
                          <div class="col-md-6">
                          <?php echo render_input('depreciation', 'depreciation_month', '', 'number'); ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group select-placeholder">
                              <label for="clientid" class="control-label"><?php echo _l('client_belongs_to'); ?></label>
                              <select id="clientid" name="clientid[]" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" multiple></select>
                            </div>
                          </div>
                          <div class="col-md-6">
                              <label for="visible_to_client"><?php echo _l('visible_to_client'); ?></label>
                              <div class="checkbox checkbox-danger">
                                  <input type="checkbox" name="visible_to_client" id="visible_to_client" value="<?php echo isset($product) ? $product->visible_to_client : ''; ?>"  <?php echo isset($product) ? ('1' == $product->visible_to_client) ? 'checked' : '' : ''; ?> >
                                    <label></label>
                              </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="attachment">
                              <div class="form-group">
                                <label for="attachment" class="control-label"><?php echo _l('asset_image'); ?></label>
                                <input type="file" extension="png,jpg,jpeg,gif" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="asset_image" id="asset_image">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div id="asset_existing_image"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="panel panel-info">
                      <div class="panel-heading"><?php echo htmlspecialchars(_l('supplier_information')); ?></div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-6">
                          <?php echo render_input('supplier_name', 'supplier_name', ''); ?>
                          </div>
                          <div class="col-md-6">
                          <?php echo render_input('supplier_phone', 'supplier_phone', ''); ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                          <?php echo render_input('supplier_address', 'supplier_address', ''); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                        <div class="row">
                          <div class="col-md-12">
                          <?php echo render_textarea('description', 'description', ''); ?>
                          </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($custom_fields)): ?>
                    <div class="panel panel-info" id="custom_fields_panel">
                      <div class="panel-heading"><?php echo htmlspecialchars(_l('custom_fields')); ?></div>
                      <div class="panel-body" id="custom_fields_container">
                        <?php foreach ($custom_fields as $cf): ?>
                        <?php 
                            $cf_id = $cf['id'];
                            $cf_name = 'custom_fields[' . $cf_id . ']';
                            $cf_label = $cf['field_name'];
                            $cf_required = $cf['required'] == 1;
                            $cf_type = $cf['field_type'];
                            $cf_options = $cf['field_options'];
                            $applies_to = $cf['applies_to_groups'];
                            
                            // Parse applies_to_groups - can be JSON or comma-separated
                            $applies_groups = [];
                            if (!empty($applies_to)) {
                                $decoded = json_decode($applies_to, true);
                                if (is_array($decoded)) {
                                    $applies_groups = $decoded;
                                } else {
                                    $applies_groups = explode(',', $applies_to);
                                }
                            }
                        ?>
                        <div class="form-group custom-field-row" data-field-id="<?php echo $cf_id; ?>" data-applies-to="<?php echo htmlspecialchars(json_encode($applies_groups)); ?>">
                            <label for="cf_<?php echo $cf_id; ?>"><?php echo htmlspecialchars($cf_label); ?><?php if ($cf_required): ?> <span class="text-danger">*</span><?php endif; ?></label>
                            <?php
                            switch ($cf_type) {
                                case 'text':
                                    echo '<input type="text" name="' . $cf_name . '" id="cf_' . $cf_id . '" class="form-control"' . ($cf_required ? ' data-cf-required="1"' : '') . '>';
                                    break;
                                case 'textarea':
                                    echo '<textarea name="' . $cf_name . '" id="cf_' . $cf_id . '" class="form-control" rows="3"' . ($cf_required ? ' data-cf-required="1"' : '') . '></textarea>';
                                    break;
                                case 'number':
                                    echo '<input type="number" name="' . $cf_name . '" id="cf_' . $cf_id . '" class="form-control"' . ($cf_required ? ' data-cf-required="1"' : '') . '>';
                                    break;
                                case 'date':
                                    echo '<input type="text" name="' . $cf_name . '" id="cf_' . $cf_id . '" class="form-control datepicker"' . ($cf_required ? ' data-cf-required="1"' : '') . '>';
                                    break;
                                case 'select':
                                    $options = explode('|', $cf_options);
                                    echo '<select name="' . $cf_name . '" id="cf_' . $cf_id . '" class="selectpicker" data-width="100%"' . ($cf_required ? ' data-cf-required="1"' : '') . '>';
                                    echo '<option value=""></option>';
                                    foreach ($options as $opt) {
                                        echo '<option value="' . htmlspecialchars(trim($opt)) . '">' . htmlspecialchars(trim($opt)) . '</option>';
                                    }
                                    echo '</select>';
                                    break;
                                case 'checkbox':
                                    echo '<div class="checkbox checkbox-primary"><input type="checkbox" name="' . $cf_name . '" id="cf_' . $cf_id . '" value="1"><label for="cf_' . $cf_id . '"></label></div>';
                                    break;
                                case 'url':
                                    echo '<input type="url" name="' . $cf_name . '" id="cf_' . $cf_id . '" class="form-control"' . ($cf_required ? ' data-cf-required="1"' : '') . '>';
                                    break;
                                default:
                                    echo '<input type="text" name="' . $cf_name . '" id="cf_' . $cf_id . '" class="form-control"' . ($cf_required ? ' data-cf-required="1"' : '') . '>';
                            }
                            ?>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                    <?php endif; ?>
                </div>
              
            </div>
                <div class="modal-footer">
                    <button type="
                    " class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></button>
                    <button id="sm_btn" type="submit" class="btn btn-info"><?php echo htmlspecialchars(_l('submit')); ?></button>
                </div>
            </div><!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<?php init_tail(); ?>
</body>
</html>
<script>var hidden_columns = [2,3,6,7,8];</script>
<script>
  appValidateForm($('#assets-form'),{assets_name:'required',amount:'required',unit:'required',assets_code: {
               required: true,
               remote: {
                url: site_url + "admin/assets/assets_code_exists",
                type: 'post',
                data: {
                    assets_code: function() {
                        return $('input[name="assets_code"]').val();
                    },
                    id: function() {
                        return $('input[name="id"]').val();
                    }
                }
            }
           }});
  var table = initDataTable('.table-table_assets1', admin_url+'assets/table_assets/'+'all_asset');
  table.column( 1 ).visible( false );
  var table = initDataTable('.table-table_assets2', admin_url+'assets/table_assets/'+'not_pending_yet');
  table.column( 1 ).visible( false );
  var table = initDataTable('.table-table_assets3', admin_url+'assets/table_assets/'+'using');
  table.column( 1 ).visible( false );
  var table = initDataTable('.table-table_assets4', admin_url+'assets/table_assets/'+'liquidation');
  table.column( 1 ).visible( false );
  var table = initDataTable('.table-table_assets5', admin_url+'assets/table_assets/'+'warranty_repair');
  table.column( 1 ).visible( false );
  var table = initDataTable('.table-table_assets6', admin_url+'assets/table_assets/'+'lost');
  table.column( 1 ).visible( false );
  var table = initDataTable('.table-table_assets7', admin_url+'assets/table_assets/'+'broken');
  table.column( 1 ).visible( false );

  function new_asset(){
    $('#assets').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('#additional').html('');

    $('#assets #asset_existing_image').html('');
    $('#assets select#clientid').html('').change();
    $("#assets #asset_image").removeAttr('required');
    $("#assets .attachment .req").hide();
    $('#assets input#visible_to_client').prop('checked', false);
    

  }
  function edit_asset(invoker,id){
    $('#additional').html('');
    $('#additional').append(hidden_input('id',id));
    $('#assets input[name="assets_code"]').val($(invoker).data('assets_code'));
    $('#assets input[name="assets_name"]').val($(invoker).data('assets_name'));
    $('#assets input[name="date_buy"]').val($(invoker).data('date_buy'));
    $('#assets input[name="amount"]').val($(invoker).data('amount'));
    $('#assets input[name="unit_price"]').val($(invoker).data('unit_price'));
    $('#assets input[name="supplier_phone"]').val($(invoker).data('supplier_phone'));
    $('#assets input[name="supplier_name"]').val($(invoker).data('supplier_name'));
    $('#assets input[name="supplier_address"]').val($(invoker).data('supplier_address'));
    $('#assets input[name="series"]').val($(invoker).data('series'));
    $('#assets input[name="warranty_period"]').val($(invoker).data('warranty_period'));
    $('#assets input[name="depreciation"]').val($(invoker).data('depreciation'));
    $('#assets select[name="unit"]').val($(invoker).data('unit'));
    $('#assets select[name="unit"]').change();
    $('#assets select[name="asset_group"]').val($(invoker).data('asset_group'));
    $('#assets select[name="asset_group"]').change();
    $('#assets select[name="department"]').val($(invoker).data('department'));
    $('#assets select[name="department"]').change();
    $('#assets select[name="asset_location"]').val($(invoker).data('asset_location'));
    $('#assets select[name="asset_location"]').change();
    $('#assets textarea[name="description"]').val($(invoker).data('description'));

    $('#assets #asset_existing_image').html(`<img src='${$(invoker).data('asset_image_url')}' class='img-thumbnail img-responsive' style="width: 150px; height: 150px;" onerror="this.src='${site_url}modules/assets/uploads/image-not-available.png'">`);

    $('#assets select#clientid').html($(invoker).data('belongs_to_option'));
    $('#assets select#clientid').change();

    $('#assets input#visible_to_client').prop('checked', false);
    if ($(invoker).data('visible_to_client') == "1") {
      $('#assets input#visible_to_client').prop('checked', true);
    }

    $("#assets #asset_image").removeAttr('required');
    $("#assets .attachment .req").hide();

    $('#assets').modal('show');
    $('.edit-title').removeClass('hide');
    $('.add-title').addClass('hide');
  }
  init_asset();
  function init_asset(id) {
    load_small_table_item_asset(id, '#asset_sm_view', 'asset_id', 'assets/get_asset_data_ajax', '.asset_sm');
  }
  function load_small_table_item_asset(pr_id, selector, input_name, url, table) {
    var _tmpID = $('input[name="' + input_name + '"]').val();
    // Check if id passed from url, hash is prioritized becuase is last
    if (_tmpID !== '' && !window.location.hash) {
        pr_id = _tmpID;
        // Clear the current id value in case user click on the left sidebar credit_note_ids
        $('input[name="' + input_name + '"]').val('');
    } else {
        // check first if hash exists and not id is passed, becuase id is prioritized
        if (window.location.hash && !pr_id) {
            pr_id = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        }
    }
    if (typeof(pr_id) == 'undefined' || pr_id === '') { return; }
    if (!$("body").hasClass('small-table')) { toggle_small_view_asset(table, selector); }
    $('input[name="' + input_name + '"]').val(pr_id);
    do_hash_helper(pr_id);
    $(selector).load(admin_url + url + '/' + pr_id);
    if (is_mobile()) {
        $('html, body').animate({
            scrollTop: $(selector).offset().top + 150
        }, 600);
    }
}
function toggle_small_view_asset(table, main_data) {

    $("body").toggleClass('small-table');
    var tablewrap = $('#small-table');
    if (tablewrap.length === 0) { return; }
    var _visible = false;
    if (tablewrap.hasClass('col-md-5')) {
        tablewrap.removeClass('col-md-5').addClass('col-md-12');
        _visible = true;
        $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-right').addClass('fa fa-angle-double-left');
    } else {
        tablewrap.addClass('col-md-5').removeClass('col-md-12');
        $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
    }
    var _table = $(table).DataTable();
    // Show hide hidden columns
    _table.columns(hidden_columns).visible(_visible, false);
    _table.columns.adjust();
    $(main_data).toggleClass('hide');
    $(window).trigger('resize');
}
function preview_asset_btn(invoker){
    var id = $(invoker).attr('id');
    var rel_id = $(invoker).attr('rel_id');
    view_asset_file(id, rel_id);
}

function view_asset_file(id, rel_id) {
      $('#asset_file_data').empty();
      $("#asset_file_data").load(admin_url + 'assets/file/' + id + '/' + rel_id, function(response, status, xhr) {
          if (status == "error") {
              alert_float('danger', xhr.statusText);
          }
      });
}
function close_modal_preview(){
 $('._project_file').modal('hide');
}

// Custom fields handling
function normalizeCustomFieldGroupList(appliesTo) {
    if (!appliesTo) {
        return [];
    }

    if (Array.isArray(appliesTo)) {
        return appliesTo;
    }

    if (typeof appliesTo === 'string') {
        var trimmed = appliesTo.trim();
        if (trimmed === '') {
            return [];
        }

        // Handle JSON-encoded arrays from data attributes.
        try {
            var parsed = JSON.parse(trimmed);
            if (Array.isArray(parsed)) {
                return parsed;
            }
        } catch (e) {
            // Continue and attempt comma-separated fallback.
        }

        // Handle simple comma-separated values.
        return trimmed.split(',').map(function (item) {
            return item.trim();
        }).filter(function (item) {
            return item !== '';
        });
    }

    return [];
}

function filterCustomFieldsByGroup(groupId) {
    $('.custom-field-row').each(function() {
        var appliesTo = normalizeCustomFieldGroupList($(this).data('applies-to'));
        // If appliesTo is empty or not set, show for all groups
        if (!appliesTo || appliesTo.length === 0) {
            $(this).show();
            return;
        }
        // When no group is selected, keep custom fields visible instead of hiding everything.
        if (!groupId) {
            $(this).show();
            return;
        }
        // Check if current group is in the applies_to list
        var groupIdStr = String(groupId);
        var shouldShow = false;
        for (var i = 0; i < appliesTo.length; i++) {
            if (String(appliesTo[i]) === groupIdStr) {
                shouldShow = true;
                break;
            }
        }
        if (shouldShow) {
            $(this).show();
        } else {
            $(this).hide();
            // Clear the field value when hidden
            var input = $(this).find('input, textarea, select');
            if (input.is(':checkbox')) {
                input.prop('checked', false);
            } else if (input.hasClass('selectpicker')) {
                input.val('').selectpicker('refresh');
            } else {
                input.val('');
            }
        }
    });
    // Show/hide the entire custom fields panel if no fields visible
    var visibleFields = $('.custom-field-row:visible').length;
    if (visibleFields > 0) {
        $('#custom_fields_panel').show();
    } else {
        $('#custom_fields_panel').hide();
    }
}

// Initialize custom fields visibility on group change
$(document).on('change', '#asset_group', function() {
    filterCustomFieldsByGroup($(this).val());
});

// Initialize datepickers in custom fields
$(document).ready(function() {
    $('#custom_fields_container .datepicker').each(function() {
        init_datepicker($(this));
    });
    // Initialize selectpickers in custom fields
    $('#custom_fields_container .selectpicker').selectpicker();
});

// Clear custom fields when opening new asset modal
var originalNewAsset = new_asset;
new_asset = function() {
    originalNewAsset();
    // Clear all custom field values
    $('.custom-field-row').each(function() {
        var input = $(this).find('input, textarea, select');
        if (input.is(':checkbox')) {
            input.prop('checked', false);
        } else if (input.hasClass('selectpicker')) {
            input.val('').selectpicker('refresh');
        } else {
            input.val('');
        }
    });
    // Show all custom fields initially (no group selected)
    $('.custom-field-row').show();
    $('#custom_fields_panel').show();
};

// Populate custom fields when editing asset
var originalEditAsset = edit_asset;
edit_asset = function(invoker, id) {
    originalEditAsset(invoker, id);
    
    // Filter custom fields by selected group
    var groupId = $(invoker).data('asset_group');
    setTimeout(function() {
        filterCustomFieldsByGroup(groupId);
    }, 100);
    
    // Load custom field values via AJAX
    $.get(admin_url + 'assets/get_asset_custom_field_values/' + id, function(response) {
        if (response && response.length > 0) {
            $.each(response, function(i, cfValue) {
                var fieldId = cfValue.field_id;
                var value = cfValue.field_value;
                var $field = $('#cf_' + fieldId);
                
                if ($field.length) {
                    if ($field.is(':checkbox')) {
                        $field.prop('checked', value == '1' || value === true);
                    } else if ($field.hasClass('selectpicker')) {
                        $field.val(value).selectpicker('refresh');
                    } else {
                        $field.val(value);
                    }
                }
            });
        }
    }, 'json');
};
</script>
