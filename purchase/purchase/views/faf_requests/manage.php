<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="panel_s mbot10">
				<div class="panel-body">
					<h4 class="no-margin font-bold"><i class="fa fa-list" aria-hidden="true"></i> <?php echo pur_html_entity_decode($title); ?></h4>
                    <hr class="hr-panel-heading" />

                    <div class="row mbot15"> 
                       
                       	<div class="col-md-12"> 
                        	<?php if(has_permission('purchase_faf', '', 'create')){ ?>
                        		<a href="<?php echo admin_url('purchase/faf_request'); ?>" class="btn btn-info"><?php echo _l('new_request'); ?></a>
                        	<?php } ?>

                        </div>
                        
                    </div>

                     <div class="row"> 
                     	<div class="col-md-3 form-group">

                           <label for="vendor"><?php echo _l('vendor'); ?></label>
                          <select name="vendor_ft[]" id="vendor_ft" class="ajax-sesarch" multiple="true"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('vendor'); ?>" >
                           
                          </select>
	                    </div>
                        <div class="col-md-3 form-group">
                            <?php 
                            $statuses = [0 => ['id' => '1', 'name' => _l('purchase_not_yet_approve')],
                            1 => ['id' => '2', 'name' => _l('purchase_approved')],
                            2 => ['id' => '3', 'name' => _l('purchase_reject')],
                            3 => ['id' => '4', 'name' => _l('cancelled')],];

                            echo render_select('status[]',$statuses,array('id','name'),'approval_status','',array('data-width'=>'100%','data-none-selected-text'=>_l('leads_all'),'multiple'=>true,'data-actions-box'=>true),array(),'no-mbot','',false); ?>
                        </div>

                        <div class="col-md-3 form-group">
                           <label for="department"><?php echo _l('department'); ?></label>
                          <select name="department[]" readonly="true" id="department" class="selectpicker" multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('leads_all'); ?>" >
                                     
                             <?php foreach($departments as $dpm){ ?>
                               <option value="<?php echo pur_html_entity_decode($dpm['departmentid']); ?>" ><?php echo pur_html_entity_decode($dpm['name']); ?></option>
                             <?php } ?>
                          </select>
                       </div>

                       <div class="col-md-3 form-group">
                           <label for="requestor"><?php echo _l('pur_requestor'); ?></label>
                          <select name="requestor[]" readonly="true" id="requestor" class="selectpicker" multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('leads_all'); ?>" >
                                     
                             <?php foreach($staffs as $staff){ ?>
                               <option value="<?php echo pur_html_entity_decode($staff['staffid']); ?>" ><?php echo pur_html_entity_decode($staff['full_name']); ?></option>
                             <?php } ?>
                          </select>
                       </div>


                       <div class="col-md-2">
                            <?php echo render_date_input('from_date',_l('from_date'),''); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo render_date_input('to_date',_l('to_date'),''); ?>
                        </div>

                        <div class="_buttons col-md-1 pull-right">
                        <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs pull-right" onclick="toggle_small_faf_request_view('.table-faf_requests','#faf_request_div'); return false;" data-toggle="tooltip" title="<?php echo _l('estimates_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
                        </div>

                     </div>
				</div>
			</div>

            <div class="row">
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body">
                        <?php echo form_hidden('fafid',$fafid); ?>
                         <?php
                        $table_data = [];
                                

                               $table_data = array_merge($table_data, [
                                _l('pur_reference_number'),
                                _l('pur_vendor'),
                                _l('pur_amount_requested'),
                                _l('pur_generated_po_number'),
                                _l('pur_department'),
                                _l('pur_requestor'),
                                _l('pur_created_at'),
                                _l('pur_status'),
                                _l('options'),
                              ]);

                           echo render_datatable($table_data, 'faf_requests', [],['id' => 'table-faf_requests']); ?>

                            
                        </div>
                    </div>
                </div>
                
            <div class="col-md-7 small-table-right-col">
                <div id="faf_request_div" class="hide">
                </div>
             </div>
            </div>

		</div>
	</div>
</div>

<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/faf_request/manage_js.php';?>