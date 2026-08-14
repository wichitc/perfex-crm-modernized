<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
   <div class="content">
    <?php echo form_open($this->uri->uri_string(),array('id'=>'add_edit_pur_request-form','class'=>'_transaction_form')); ?>
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="customer-profile-group-heading mtop20"><?php if(isset($pur_request)){ echo pur_html_entity_decode($pur_request->pur_rq_code); }else{ echo _l($title). ' '._l('purchase_request') ; } ?></h4>
                  <?php 

                  if(isset($pur_request)){
                           echo form_hidden('isedit');
                        }?>
                <div class="row accounting-template">


                  <div class="row ">
                    <div class="col-md-12">
                      <div class="col-md-6">
                       <?php
                          $prefix = get_purchase_option('pur_request_prefix');
                          $next_number = get_purchase_option('next_pr_number');
                          $number = (isset($pur_request) ? $pur_request->number : $next_number);
                          echo form_hidden('number',$number); ?> 


                      <?php 
                        if(get_option('can_edit_pr_number') == 1){ 
                          $attr_pr = array();
                        }else{ 
                          $attr_pr = array('readonly' => '');
                        } 
                      ?>     
                      <?php $pur_rq_code = ( isset($pur_request) ? $pur_request->pur_rq_code : $prefix.'-'.str_pad($next_number,5,'0',STR_PAD_LEFT).'-'.date('Y'));
                      echo render_input('pur_rq_code','pur_rq_code',$pur_rq_code ,'text', $attr_pr); ?>
                    </div>
                    <div class="col-md-3">
                      <?php $pur_rq_name = ( isset($pur_request) ? $pur_request->pur_rq_name : '');
                      echo render_input('pur_rq_name','pur_rq_name', $pur_rq_name); ?>
                    </div>

                    <div class="col-md-3">
                      <?php $request_date = ( isset($pur_request) ? _d(date('Y-m-d',  strtotime($pur_request->request_date)) ) : _d(date('Y-m-d')) );
                      echo render_date_input('request_date', 'requirement_date', $request_date); ?>
                    </div>

                    <?php 
                      $project_id = '';
                      if($this->input->get('project')){
                        $project_id = $this->input->get('project'); 
                      }
                    ?>
                     <div class="row ">
                      <div class="col-md-12">
                        <div class="col-md-3 form-group">
                          <label for="project"><?php echo _l('project'); ?></label>
                            <select name="project" id="project" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                              <option value=""></option>
                              <?php foreach($projects as $s) { ?>
                                <option value="<?php echo pur_html_entity_decode($s['id']); ?>" <?php if(isset($pur_request) && $s['id'] == $pur_request->project){ echo 'selected'; }else if(!isset($pur_request) && $s['id'] == $project_id ){ echo 'selected';  } ?>><?php echo pur_html_entity_decode($s['name']); ?></option>
                                <?php } ?>
                            </select>
                            <br><br>
                        </div>

                        <div class="col-md-3 form-group">
                          <label for="sale_estimate"><?php echo _l('sale_estimate'); ?></label>
                            <select name="sale_estimate" id="sale_estimate" onchange="coppy_sale_estimate(); return false;" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                              <option value=""></option>
                              <?php foreach($salse_estimates as $s) { ?>
                                <option value="<?php echo pur_html_entity_decode($s['id']); ?>" <?php if(isset($pur_request) && $s['id'] == $pur_request->sale_estimate){ echo 'selected'; } ?>><?php echo format_estimate_number($s['id']); ?></option>
                                <?php } ?>
                            </select>
                            <br><br>
                        </div>

                          <div class="col-md-3 form-group">
                            <label for="type"><?php echo _l('type'); ?></label>
                              <select name="type" id="type" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                                <option value=""></option>
                                <option value="capex" <?php if(isset($pur_request) && $pur_request->type == 'capex'){ echo 'selected';} ?>><?php echo _l('capex'); ?></option>
                                <option value="opex" <?php if(isset($pur_request) && $pur_request->type == 'opex'){ echo 'selected';} ?>><?php echo _l('opex'); ?></option>
                              </select>
                              <br><br>
                          </div>

                          <div class="col-md-3 ">
                           <?php
                              $currency_attr = array();

                              $selected = (isset($pur_request) && $pur_request->currency != 0) ? $pur_request->currency : '';
                              if($selected == ''){
                                foreach($currencies as $currency){
            
                                 if($currency['isdefault'] == 1){
                                   $selected = $currency['id'];
                                 }
                                
                                }
                              }
                              ?>
                           <?php echo render_select('currency', $currencies, array('id','name','symbol'), 'invoice_add_edit_currency', $selected, $currency_attr); ?>
                        </div>
                      </div>
                  </div>

                    <div class="col-md-3 form-group">
                      <label for="department"><?php if(get_option('pur_department_required_condition') == 1){ echo '<span class="text-danger">* </span>'; } ?><?php echo _l('department'); ?></label>
                        <select name="department" id="department" class="selectpicker" <?php if(get_option('pur_department_required_condition') == 1){ echo 'required="true"'; } ?> onchange="department_change(this); return false;" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                          <option value=""></option>
                          <?php foreach($departments as $s) { ?>
                            <option value="<?php echo pur_html_entity_decode($s['departmentid']); ?>" <?php if(isset($pur_request) && $s['departmentid'] == $pur_request->department){ echo 'selected'; } ?>><?php echo pur_html_entity_decode($s['name']); ?></option>
                            <?php } ?>
                        </select>
                        <br><br>
                    </div>

                    <div class="col-md-3 form-group ">
                      <label for="sale_invoice"><?php echo _l('sale_invoice'); ?></label>
                        <select name="sale_invoice" onchange="coppy_sale_invoice(); return false;" id="sale_invoice" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                          <option value=""></option>
                          <?php foreach($invoices as $inv) { ?>
                            <option value="<?php echo pur_html_entity_decode($inv['id']); ?>" <?php if(isset($pur_request) && $inv['id'] == $pur_request->sale_invoice){ echo 'selected'; } ?>><?php echo format_invoice_number($inv['id']); ?></option>
                            <?php } ?>
                        </select>
                        
                    </div>
                 

                    <div class="col-md-3 form-group">
                      <label for="requester"><?php echo _l('requester'); ?></label>
                        <select name="requester" id="requester" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                          <option value=""></option>
                          <?php foreach($staffs as $s) { ?>
                            <option value="<?php echo pur_html_entity_decode($s['staffid']); ?>" <?php if(isset($pur_request) && $s['staffid'] == $pur_request->requester){ echo 'selected'; }elseif($s['staffid'] == get_staff_user_id()){ echo 'selected'; } ?>><?php echo pur_html_entity_decode($s['lastname'] . ' '. $s['firstname']); ?></option>
                            <?php } ?>
                        </select>
                        <br><br>
                    </div>
                    
                    <div class="col-md-3 ">
                      

                      <div class="form-group" id="rel_id_wrapper">
                          <label for="send_to_vendors"><?php echo _l('pur_send_to_vendors'); ?></label>
                          <div id="send_to_vendors_select">
                              <select name="send_to_vendors[]" id="send_to_vendors" class="ajax-sesarch" multiple="true" data-width="100%"
                                  data-live-search="true"
                                  data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
               

                             <?php
                            if(isset($pur_request)) {
                              $vendors_arr = explode(',', $pur_request->send_to_vendors ?? '');
                              foreach($vendors_arr as $vd_id) { 
                                $rel_data = get_relation_data('vendors', $vd_id);
                                $rel_val  = get_relation_values($rel_data, 'vendors');
                                echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                              }

                            }
                          ?>          

                              </select>
                          </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <?php $rq_description = ( isset($pur_request) ? $pur_request->rq_description : '');
                      echo render_textarea('rq_description','rq_description',$rq_description); ?>
                    </div>
                  </div>
                 
                  </div>
                </div>
              </div>
            </div>

          <div class="row ">
            <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">   
                  <div class="mtop10 invoice-item">
                    
                    
                      <div class="row">
                        <div class="col-md-4">
                          <?php $this->load->view('purchase/item_include/main_item_select'); ?>
                        </div>

                        <?php
                              $pur_request_currency = $base_currency;
                              if(isset($pur_request) && $pur_request->currency != 0){
                                $pur_request_currency = pur_get_currency_by_id($pur_request->currency);
                              } 

                              $from_currency = (isset($pur_request) && $pur_request->from_currency != null) ? $pur_request->from_currency : $base_currency->id;
                              echo form_hidden('from_currency', $from_currency);

                            ?>
                        <div class="col-md-8 <?php if($pur_request_currency->id == $base_currency->id){ echo 'hide'; } ?>" id="currency_rate_div">
                          <div class="col-md-10 text-right">
                            
                            <p class="mtop10"><?php echo _l('currency_rate'); ?><span id="convert_str"><?php echo ' ('.$base_currency->name.' => '.$pur_request_currency->name.'): ';  ?></span></p>
                          </div>
                          <div class="col-md-2 pull-right">
                            <?php $currency_rate = 1;
                              if(isset($pur_request) && $pur_request->currency != 0){
                                $currency_rate = pur_get_currency_rate($base_currency->name, $pur_request_currency->name);
                              }
                            echo render_input('currency_rate', '', $currency_rate, 'number', [], [], '', 'text-right'); 
                            ?>
                          </div>
                        </div>

                      </div>
                      <div class="table-responsive s_table ">
                        <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop">
                          <thead>
                            <tr>
                              <th></th>
                              <th width="25%" align="left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_description_new_lines_notice'); ?>"></i> <?php echo _l('debit_note_table_item_heading'); ?></th>
                              <th width="10%" align="right"><?php echo _l('unit_price'); ?><span class="th_currency"><?php echo '('.$pur_request_currency->name.')'; ?></span></th>
                              <th width="10%" align="right" class="qty"><?php echo _l('purchase_quantity'); ?></th>
                              <th width="10%" align="right"><?php echo _l('subtotal'); ?><span class="th_currency"><?php echo '('.$pur_request_currency->name.')'; ?></span></th>
                              <th width="15%" align="right"><?php echo _l('debit_note_table_tax_heading'); ?></th>
                              <th width="10%" align="right"><?php echo _l('tax_value'); ?><span class="th_currency"><?php echo '('.$pur_request_currency->name.')'; ?></span></th>
                              <th width="10%" align="right"><?php echo _l('debit_note_total'); ?><span class="th_currency"><?php echo '('.$pur_request_currency->name.')'; ?></span></th>
                              <th align="right"><i class="fa fa-cog"></i></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php echo pur_html_entity_decode($purchase_request_row_template); ?>
                          </tbody>
                        </table>
                      </div>

                    


                  <div class="col-md-6 pright0 col-md-offset-6">
                     <table class="table text-right mbot0">
                       <tbody>
                          <tr id="subtotal">
                             <td class="td_style"><span class="bold"><?php echo _l('subtotal'); ?></span>
                             </td>
                             <td width="65%" id="total_td">
                              
                               <div class="input-group" id="discount-total">

                                      <input type="text" readonly="true"  class="form-control text-right" name="subtotal" value="<?php if(isset($pur_request)){ echo app_format_money($pur_request->subtotal,''); } ?>">

                                     <div class="input-group-addon">
                                        <div class="dropdown">
                                           
                                           <span class="discount-type-selected currency_span" id="subtotal_currency">
                                            <?php 
                                              if(!isset($pur_request)){
                                                echo pur_html_entity_decode($base_currency->symbol); 
                                              }else{
                                                if($pur_request->currency != 0){
                                                  $_currency_symbol = pur_get_currency_name_symbol($pur_request->currency, 'symbol');
                                                  echo pur_html_entity_decode($_currency_symbol); 

                                                }else{
                                                  echo pur_html_entity_decode($base_currency->symbol); 
                                                }
                                              }
                                            ?>
                                           </span>
                                           
                                           
                                        </div>
                                     </div>

                                  </div>
                             </td>
                          </tr>

                          <tr id="total">
                             <td class="td_style"><span class="bold"><?php echo _l('total'); ?></span>
                             </td>
                             <td width="65%" id="total_td">
                               <div class="input-group" id="total">
                                     <input type="text" readonly="true" class="form-control text-right" name="total_mn" value="<?php if(isset($pur_request)){ echo app_format_money($pur_request->total,''); } ?>">
                                     <div class="input-group-addon">
                                        <div class="dropdown">
                                           
                                           <span class="discount-type-selected currency_span">
                                            <?php 
                                              if(!isset($pur_request)){
                                                echo pur_html_entity_decode($base_currency->symbol); 
                                              }else{
                                                if($pur_request->currency != 0){
                                                  $_currency_symbol = pur_get_currency_name_symbol($pur_request->currency, 'symbol');
                                                  echo pur_html_entity_decode($_currency_symbol); 

                                                }else{
                                                  echo pur_html_entity_decode($base_currency->symbol); 
                                                }
                                              }
                                            ?>
                                           </span>
                                        </div>
                                     </div>

                                  </div>
                             </td>
                          </tr>
                        </tbody>
                      </table>


                  </div>

                  <div id="removed-items"></div>
                  </div>

                  </div>

                    <div class="clearfix"></div>

                  <div class="btn-bottom-toolbar text-right">  
                    <a href="javascript:history.back();" class="btn btn-default"><i class="fa fa-undo"></i><?php echo _l('pur_cancel'); ?></a>
                    
                    <button type="submit" class="btn-tr save_detail btn btn-info mleft10">
                  <?php echo _l('submit'); ?>
                  </button>

                  </div>
                   <div class="btn-bottom-pusher"></div>

                  
               </div>

            </div>

            </div>
         </div>
      </div>
      <?php echo form_close(); ?>
   </div>
</div>

<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/pur_request_js.php';?>
