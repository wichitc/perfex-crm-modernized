<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
        	<div class="col-md-12">
        		<h4 class=""><?php echo pur_html_entity_decode($title); ?></h4>
        	</div>

            <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'work-order-form')); ?>
            <div class="col-md-12">
                <div class="panel_s panel-table-full">
                    <div class="panel-body">
                        <div class="row">
                           
                            <div class="form-group col-md-6">
                      
                                <label for="vendor_id"><span class="text-danger">* </span><?php echo _l('vendor'); ?></label>
                                <select name="vendor_id" id="vendor_id" class="ajax-sesarch" data-live-search="true" data-width="100%" required="true" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                        <?php
                                    if(isset($faf)) {
                                        $rel_data = pur_get_relation_data('vendors', $faf->vendor_id);
                                        $rel_val  = pur_get_relation_values($rel_data, 'vendors');
                                        echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                    }

                                  ?>  
                                </select>  
                                  
                            </div>

                            <div class="col-md-6">
                                <label for="reference_number"><span class="text-danger">* </span><?php echo _l('pur_reference_number'); ?></label>
                                <?php $reference_number = (isset($faf) ? $faf->reference_number : '');
                                echo render_input('reference_number', '', $reference_number, 'text', ['required' => 'true']); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="amount_request"><?php echo _l('pur_amount_requested'); ?></label>
                                <?php $amount_request = (isset($faf) ? $faf->amount_request : '');
                                echo render_input('amount_request', '', $amount_request, 'number', ['step' => 'any']); ?>
                            </div>

                            <div class="col-md-6 ">
                                 <?php
                                    $currency_attr = array('data-show-subtext'=>true);

                                    $selected = '';
                                    foreach($currencies as $currency){
                                      if(isset($faf) && $faf->currency != 0){
                                        if($currency['id'] == $faf->currency){
                                          $selected = $currency['id'];
                                        }
                                      }else{
                                       if($currency['isdefault'] == 1){
                                         $selected = $currency['id'];
                                       }
                                      }
                                    }
                   
                                    ?>
                                 <?php echo render_select('currency', $currencies, array('id','name','symbol'), 'invoice_add_edit_currency', $selected, $currency_attr); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="genrated_po_number"><?php echo _l('pur_generated_po_number'); ?></label>
                                <?php $genrated_po_number = (isset($faf) ? $faf->genrated_po_number : '');
                                echo render_input('genrated_po_number', '', $genrated_po_number, 'text'); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="department"><span class="text-danger">* </span><?php echo _l('pur_department'); ?></label>
                                <?php $department = (isset($faf) ? $faf->department : '');
                                echo render_select('department', $departments, array('departmentid', 'name'), '',$department, ['required' => 'true']); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="requestor"><span class="text-danger">* </span><?php echo _l('pur_requestor'); ?></label>
                                <?php $requestor = (isset($faf) ? $faf->requestor : '');
                                echo render_select('requestor', [], array('id', 'name'), '', $requestor, ['required' => 'true'] ) ?>
                            </div>

                            <div class="attachments_area">
                                <div class=" attachments">
                                    <div class="attachment">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="attachment" class="control-label"><?php echo _l('pur_attachment') ?></label>
                                                <div class="input-group">
                                                    <input type="file" extension="jpg,png,pdf,doc,zip,rar" filesize="67108864" class="form-control" name="attachments[0]" accept=".jpg,.png,.pdf,.doc,.zip,.rar,image/jpeg,image/png,application/pdf,application/msword,application/x-zip,application/x-rar">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default add_more_attachments" data-max="6" type="button"><i class="fa fa-plus"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>

                            <?php if(isset($faf)){ ?>
                                <div class="col-md-12" id="faf_request_pv_file">
                                <?php
                                    $file_html = '';
                                    if(count($faf_attachments) > 0){
                                        $file_html .= '<hr />';
                                        foreach ($faf_attachments as $f) {
                                            $href_url = site_url(PURCHASE_PATH.'faf_requests/'.$f['rel_id'].'/'.$f['file_name']).'" download';
                                                            if(!empty($f['external'])){
                                                              $href_url = $f['external_link'];
                                                            }
                                           $file_html .= '<div class="mbot15 row inline-block full-width" data-attachment-id="'. $f['id'].'">
                                          <div class="col-md-8">
                                             <a name="preview-faf_request-btn" onclick="preview_faf_request_btn(this); return false;" rel_id = "'. $f['rel_id']. '" id = "'.$f['id'].'" href="Javascript:void(0);" class="mbot10 mright5 btn btn-success pull-left" data-toggle="tooltip" title data-original-title="'. _l('preview_file').'"><i class="fa fa-eye"></i></a>
                                             <div class="pull-left"><i class="'. get_mime_class($f['filetype']).'"></i></div>
                                             <a href=" '. $href_url.'" target="_blank" download>'.$f['file_name'].'</a>
                                             <br />
                                             <small class="text-muted">'.$f['filetype'].'</small>
                                          </div>
                                          <div class="col-md-4 text-right">';
                                            if($f['staffid'] == get_staff_user_id() || is_admin()){
                                            $file_html .= '<a href="#" class="text-danger" onclick="delete_faf_request_attachment('. $f['id'].'); return false;"><i class="fa fa-times"></i></a>';
                                            } 
                                           $file_html .= '</div></div>';
                                        }
                                        echo pur_html_entity_decode($file_html);
                                    }
                                 ?>
                                </div>

                                <div id="faf_request_file_data"></div>
                            <?php } ?>
                       

         

                            <?php if(get_option('pur_can_select_approvers_on_faf_form') == 1){ ?>
                           
                                <div class="col-md-12 mtop25">
                                  <p class="text-bold mbot5"><strong><?php echo _l('pur_list_approvers'); ?></strong></p>
                                  <hr class="hr-panel-heading mtop5" />
                                </div>
                                 <hr>
                                <div class="list_approve">
                                    <div id="item_approve">
                                        <div class="col-md-11">
                                        <div class="col-md-4 hide">
                                          <div class="select-placeholder form-group">
                                            <label for="approver[0]"><?php echo _l('approver'); ?></label>
                                        <select name="approver[0]" id="approver[0]" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" required>
                                            <option value=""></option>
                                           
                                            <option value="staff" selected><?php echo _l('staff'); ?></option>
                                        </select>
                                       </div> 
                                      </div>
                                      <div class="col-md-6">
                                          <div class="select-placeholder form-group">
                                            <label for="staff[0]"><?php echo _l('staff'); ?></label>
                                        <select name="staff[0]" id="staff[0]" class="selectpicker" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true">
                                            <option value=""></option>
                                            <?php foreach($staff as $val){
                                             $selected = '';
                                              ?>
                                          <option value="<?php echo pur_html_entity_decode($val['staffid']); ?>">
                                             <?php echo get_staff_full_name($val['staffid']); ?>
                                          </option>
                                          <?php } ?>
                                        </select>
                                       </div> 
                                      </div>
                                      <div class="col-md-6" id="is_staff_0">
                                          <div class="select-placeholder form-group">
                                            <label for="action[0]"><?php echo _l('action'); ?></label>
                                        <select name="action[0]" id="action[0]" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true">
                                            <option value="approve" selected><?php echo _l('approve'); ?></option>
                                            <option value="sign"><?php echo _l('sign'); ?></option>
                                        </select>
                                       </div> 
                                      </div>
                                      </div>
                                        <div class="col-md-1 btn_apr">
                                        <span class="pull-bot">
                                            <button name="add" class="btn new_vendor_requests btn-success" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                                            </span>
                                      </div>
                                    </div>
                                </div>
                                
                            <?php } ?>

                            

                         
                                
                                <div class="col-md-12">
                                    <hr class="hr-panel-heading" />
                                    <?php $summary = (isset($faf) ? $faf->summary :  ''); ?>
                                    <?php echo render_textarea('summary','pur_summary_justification',$summary,array(),array(),'mtop15','tinymce'); ?>
                                </div>
                     
                           
                        </div>

                    </div>
                </div>
            </div>

            <div class="btn-bottom-toolbar text-right">
                <a href="javascript:history.back();" class="btn btn-default"><i class="fa fa-undo"></i><?php echo _l('pur_cancel'); ?></a>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit') ?></button>
            </div>

            <?php echo form_close(); ?>

        </div>
    </div>
</div>
<?php init_tail(); ?>
<?php require 'modules/purchase/assets/js/faf_request/faf_request_js.php';?>