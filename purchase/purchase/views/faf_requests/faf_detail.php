<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <div class="panel_s panel-table-full">
        <div class="panel-body">
            
            <div class="horizontal-scrollable-tabs preview-tabs-top">
                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                    <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                          <li role="presentation" class="active">
                             <a href="#tab_faf" aria-controls="tab_faf" role="tab" data-toggle="tab">
                             <?php echo _l('pur_faf_request'); ?>
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#tab_reminders" onclick="initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + <?php echo pur_html_entity_decode($faf->id) ;?> + '/' + 'pur_faf', undefined, undefined, undefined,[1,'asc']); return false;" aria-controls="tab_reminders" role="tab" data-toggle="tab">
                             <?php echo _l('reminders'); ?>
                             <?php
                                $total_reminders = total_rows(db_prefix().'reminders',
                                  array(
                                   'isnotified'=>0,
                                   'staff'=>get_staff_user_id(),
                                   'rel_type'=>'pur_faf',
                                   'rel_id'=>$faf->id
                                   )
                                  );
                                if($total_reminders > 0){
                                  echo '<span class="badge">'.$total_reminders.'</span>';
                                }
                                ?>
                             </a>
                          </li>
                
                          <li role="presentation">
                             <a href="#tab_tasks" onclick="init_rel_tasks_table(<?php echo pur_html_entity_decode($faf->id); ?>,'pur_faf'); return false;" aria-controls="tab_tasks" role="tab" data-toggle="tab">
                             <?php echo _l('tasks'); ?>
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#tab_notes" onclick="get_sales_notes_faf(<?php echo pur_html_entity_decode($faf->id); ?>,'purchase'); return false" aria-controls="tab_notes" role="tab" data-toggle="tab">
                             <?php echo _l('faf_notes'); ?>
                             <span class="notes-total">
                                <?php $totalNotes       = total_rows(db_prefix().'notes', ['rel_id' => $faf->id, 'rel_type' => 'pur_faf']);
                                if($totalNotes > 0){ ?>
                                   <span class="badge"><?php echo ($totalNotes); ?></span>
                                <?php } ?>
                             </span>
                             </a>
                          </li>
                          <li role="presentation"  class="tab-separator" >
                             <a href="#activity_log" aria-controls="activity_log" role="tab" data-toggle="tab">
                             <?php echo _l('activity_logs'); ?>
                             </a>
                          </li>
                    
                          <li role="presentation" class="tab-separator">
                             <a href="#attachment" aria-controls="attachment" role="tab" data-toggle="tab">
                             <?php echo _l('attachment'); ?>
                             </a>
                          </li>  
                          
                          <li role="presentation" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>" class="tab-separator toggle_view">
                             <a href="#" onclick="small_faf_table_full_view(); return false;">
                             <i class="fa fa-expand"></i></a>
                          </li>
                          
                       </ul>
                    </div>
                </div>


            <div class="row">
                <div class="col-md-3">
                    <?php echo get_status_approve($faf->approve_status); ?>
                   
                </div>
                <div class="col-md-9 _buttons">
                    <div class="visible-xs">
                      <div class="mtop10"></div>
                    </div>
                    <div class="pull-right">
                        <div class="btn-group mleft5">
                            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" ><i class="fa-regular fa-file-pdf"></i><?php if (is_mobile()) {
                            echo ' PDF';
                        } ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="hidden-xs"><a
                                        href="<?php echo admin_url('purchase/faf_pdf/' . $faf->id . '?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a>
                                </li>
                                <li class="hidden-xs"><a
                                        href="<?php echo admin_url('purchase/faf_pdf/' . $faf->id . '?output_type=I'); ?>"
                                        target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                                <li><a
                                        href="<?php echo admin_url('purchase/faf_pdf/' . $faf->id); ?>"><?php echo _l('download'); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo admin_url('purchase/faf_pdf/' . $faf->id . '?print=true'); ?>"
                                        target="_blank">
                                        <?php echo _l('print'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <?php if($faf->approve_status != 2 && has_permission('purchase_faf', '', 'edit') && count($list_approve_status) == 0){ ?>
                            <a href="<?php echo admin_url('purchase/faf_request/'.$faf->id); ?>" data-toggle="tooltip" title="<?php echo _l('edit_invoice'); ?>" class="btn btn-default btn-with-tooltip  mleft5" data-placement="bottom"><i class="fa fa-pencil-square"></i></a>
                        <?php } ?>
          
                    </div>
                    <?php if(has_permission('purchase_faf', '', 'edit')){ ?>     
                           <select name="status" id="status" class="selectpicker pull-right" onchange="change_status_faf(this,<?php echo ($faf->id); ?>); return false;" data-live-search="true" data-width="30%" data-none-selected-text="<?php echo _l('pur_change_status_to'); ?>">
                             <option value=""></option>
                             <option value="1" class="<?php if($faf->approve_status == 1) { echo 'hide';}?>"><?php echo _l('purchase_draft'); ?></option>
                             <option value="2" class="<?php if($faf->approve_status == 2) { echo 'hide';}?>"><?php echo _l('purchase_approved'); ?></option>
                             <option value="3" class="<?php if($faf->approve_status == 3) { echo 'hide';}?>"><?php echo _l('pur_rejected'); ?></option>
                             <option value="4" class="<?php if($faf->approve_status == 4) { echo 'hide';}?>"><?php echo _l('pur_canceled'); ?></option>
                           </select>
                         <?php } ?>

                </div>
            </div>

            <div class="clearfix"></div>
            <hr class="hr-panel-heading" />

            <?php
            $base_currency = get_base_currency_pur();
            if($faf->currency != 0 && $faf->currency != ''){
                $base_currency = pur_get_currency_by_id($faf->currency);
            }
            ?>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane ptop10 active" id="tab_faf">
                    <div class="row">
                        <div id="detail_div" class="col-md-12 col-md-offset-0 border-custom">
                            <div class="col-md-8 mtop25">
                                <h4 class="text-uppercase"><strong><?php echo _l('pur_financial_approval_form'); ?></strong></h4>
                                <p><?php echo _l('pur_supplier').': '; ?><strong><?php echo get_vendor_company_name($faf->vendor_id); ?></strong></p>
                                <p><?php echo _l('pur_reference_number').': '; ?><strong><?php echo pur_html_entity_decode($faf->reference_number); ?></strong></p>
                                <p><?php echo _l('pur_amount_requested').': '; ?><strong><?php echo app_format_money($faf->amount_request, $base_currency->id); ?></strong></p>
                                <p><?php echo _l('pur_generated_po_number').': '; ?><strong><?php echo pur_html_entity_decode($faf->genrated_po_number); ?></strong></p>
                                <p><?php echo _l('pur_department').': '; ?><strong><?php echo department_pur_request_name($faf->department); ?></strong></p>
                            </div>

                            <div class="col-md-4 mtop25">
                                <?php echo get_po_logo(300, "img img-responsive", 'setting'); ?>
                            </div>

                            <div class="col-md-12">
                                <h4 class="text-uppercase"><strong><?php echo _l('pur_summary_justification'); ?></strong></h4>

                                <p><span><?php echo pur_html_entity_decode($faf->summary); ?></span></p>
                            </div>

                            <div class="col-md-12"><h4 class="text-uppercase"><strong><?php echo _l('pur_department_requestor'); ?></strong></h4></div>
                            <div class="col-md-3 text-center mtop15">
                                

                                <?php if(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/faf_requests/requestor_sign/'.$faf->id.'/signature_'.$faf->id.'.png')){ ?>
                                    <p class="text-uppercase text-muted no-mtop bold">
                                        <?php echo get_staff_full_name($faf->requestor); ?>
                                    </p>

                                    <?php 
                                    $staff_position = pur_get_job_position($faf->requestor); 
                                    if(isset($staff_position) && $staff_position != ''){
                                         echo '<span>'.$staff_position.'</span><br>';
                                     }
                                    ?>


                                    <img src="<?php echo site_url(PURCHASE_PATH.'faf_requests/requestor_sign/'.$faf->id.'/signature_'.$faf->id.'.png'); ?>" class="img_style">

                                    <p class="bold text-success mtop15"><?php echo _l('signed').' '._dt($faf->requestor_signed_at); ?></p> 
                                <?php }else if(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/faf_requests/requestor_sign/'.$faf->id.'/signature_'.$faf->id.'.jpg')){ ?>
                                    <p class="text-uppercase text-muted no-mtop bold">
                                        <?php echo get_staff_full_name($faf->requestor); ?>
                                    </p>
                                    <?php 
                                    $staff_position = pur_get_job_position($faf->requestor); 
                                    if(isset($staff_position) && $staff_position != ''){
                                         echo '<span>'.$staff_position.'</span><br>';
                                     }
                                    ?>

                                   <img src="<?php echo site_url(PURCHASE_PATH.'faf_requests/requestor_sign/'.$faf->id.'/signature_'.$faf->id.'.jpg'); ?>" class="img_style">

                                   <p class="bold text-center text-success mtop15"><?php echo _l('signed').' '._dt($faf->requestor_signed_at); ?></p> 
                                <?php }else{ ?>
                                    <p class="text-uppercase text-muted no-mtop bold">
                                        <?php echo get_staff_full_name($faf->requestor); ?>
                                    </p>
                                    <?php 
                                    $staff_position = pur_get_job_position($faf->requestor); 
                                    if(isset($staff_position) && $staff_position != ''){
                                         echo '<span>'.$staff_position.'</span><br>';
                                     }
                                    ?>

                                    <?php if(get_staff_user_id() == $faf->requestor){ ?>
                                     <a href="javascript:void(0);" class="btn btn-primary sign-open-modal" onclick="requestor_sign_faf(this); return false;"><i class="fa fa-pencil">  </i><?php echo ' '._l('pur_sign'); ?></a>
                                     <?php } ?>

                                <?php } ?>
                            </div>


                            <div class="col-md-12 mbot25">
                                <h4 class="text-uppercase"><strong><?php echo _l('pur_approvals'); ?></strong></h4>


                                <div class=" mleft5 mright5">
                                <?php $select_approver = get_option('pur_can_select_approvers_on_faf_form');
                                      if($check_appr && $check_appr != false || ($select_approver == 1 && $faf->approval_setting != '')){
                                     
                                       if(($faf->approve_status == 1 && ($check_approve_status == false || $check_approve_status == 'reject')) || ($faf->approve_status == 1 && isset($appr_setting->approval_type) && $appr_setting->approval_type == 1 && is_array($check_approve_status['staffid']) && count($check_approve_status['staffid']) != count($list_approve_status)) ){ ?>
                                  <a data-toggle="tooltip" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-success lead-top-btn lead-view" data-placement="top" href="#" onclick="send_request_approve(<?php echo pur_html_entity_decode($faf->id); ?>); return false;"><?php echo _l('send_request_approve_pur'); ?></a>
                                <?php } }
                                  if(isset($check_approve_status['staffid'])){
                                      ?>
                                      <?php 
                                  if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && !in_array(get_staff_user_id(), $get_staff_sign) && $faf->approve_status == 1){ ?>
                                      <div class="btn-group" >
                                             <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('approve'); ?><span class="caret"></span></a>
                                             <ul class="dropdown-menu dropdown-menu-<?php if(is_mobile()){ echo 'left';}else{ echo 'right';} ?> ul_style" >
                                              <li>
                                                <div class="col-md-12">
                                                  <?php echo render_textarea('reason', 'reason'); ?>
                                                </div>

                                                <?php if(get_option('allow_upload_esign_for_approve_type') == 1){ ?>
                                                   <div class="col-md-12 mbot15">
                                                      <?php echo form_open_multipart(admin_url('purchase/sign_attachment'),array('id'=>'sign_attachment-form')); ?>

                                                         <label for="sign_attachment"><?php echo _l('e_sign'); ?></label>
                                                         <input type="file" id="sign_attachment_file" accept=".png, .jpg" name="sign_attachment" class="form-control">

                                                         <?php echo form_hidden('approve_rel_id', $faf->id) ?>
                                                         <?php echo form_hidden('approve_rel_type', 'faf_requests') ?>

                                                      <?php echo form_close(); ?>   
                                                   </div> 
                                                <?php } ?>

                                              </li>
                                                <li>
                                                  <div class="row text-right col-md-12 pad_right_0 mbot15">
                                                    <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="approve_request(<?php echo pur_html_entity_decode($faf->id); ?>); return false;" class="btn btn-success mright15" ><?php echo _l('approve'); ?></a>
                                                   <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="deny_request(<?php echo pur_html_entity_decode($faf->id); ?>); return false;" class="btn btn-warning"><?php echo _l('deny'); ?></a></div>
                                                </li>
                                             </ul>
                                          </div>
                                    <?php }
                                      ?>
                                      
                                    <?php
                                     if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && in_array(get_staff_user_id(), $get_staff_sign) && $faf->approve_status == 1){ ?>
                                      <button onclick="accept_action();" class="btn btn-success pull-right action-button"><?php echo _l('e_signature_sign'); ?></button>
                                    <?php }
                                      ?>
                                      <?php 
                                       }
                                      ?>
                                    </div>


                                <div class="project-overview-right">
                                    <?php if(count($list_approve_status) > 0 ){ ?>
                                      
                                     <div class="row">
                                       <div class="col-md-12 project-overview-expenses-finance">
                                        <?php 
                                          $this->load->model('staff_model');
                                          $enter_charge_code = 0;
                                        foreach ($list_approve_status as $value) {
                                          $value['staffid'] = explode(', ',$value['staffid'] ?? '');
                                          if($value['action'] == 'sign'){
                                         ?>
                                         <div class="col-md-4 apr_div">
                                             <p class="text-uppercase text-muted no-mtop bold">
                                              <?php
                                              $staff_name = '';
                                              $st = _l('status_0');
                                              $color = 'warning';
                                              foreach ($value['staffid'] as $key => $val) {
                                                if($staff_name != '')
                                                {
                                                  $staff_name .= ' or ';
                                                }
                                                $firstname = isset($this->staff_model->get($val)->full_name) ? $this->staff_model->get($val)->full_name : '';
                                                $staff_name .= $firstname;
                                                $staff_position = pur_get_job_position($val);
                                              }
                                              echo pur_html_entity_decode($staff_name); 
                                              ?></p>
                                                <?php 
                                             
                                             if(isset($staff_position) && $staff_position != ''){
                                                 echo '<span>'.$staff_position.'</span><br>';
                                             }
                                             ?>
                                             <?php if($value['approve'] == 2){ 
                                              ?>
                                              <?php if(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.png')){ ?>
                                                <img src="<?php echo site_url(PURCHASE_PATH.'faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.png'); ?>" class="img_style">
                                                <?php }elseif(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.jpg')){ ?>
                                                   <img src="<?php echo site_url(PURCHASE_PATH.'faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.jpg'); ?>" class="img_style">
                                                <?php } ?>
                                               <br><br>
                                             <p class="bold text-center text-success"><?php echo _l('signed').' '._dt($value['date']); ?></p> 
                                             <?php } ?> 
                                               
                                        </div>
                                        <?php }else{ ?>
                                        <div class="col-md-4 apr_div" >
                                             <p class="text-uppercase text-muted no-mtop bold">
                                              <?php
                                              $staff_name = '';
                                              foreach ($value['staffid'] as $key => $val) {
                                                if($staff_name != '')
                                                {
                                                  $staff_name .= ' or ';
                                                }
                                                $firstname = isset($this->staff_model->get($val)->full_name) ? $this->staff_model->get($val)->full_name : '';
                                                $staff_name .= $firstname;
                                                $staff_position = pur_get_job_position($val);
                                              }
                                              echo pur_html_entity_decode($staff_name); 
                                              ?></p>
                                              <?php 
                                             
                                             if(isset($staff_position) && $staff_position != ''){
                                                 echo '<span>'.$staff_position.'</span><br>';
                                             }
                                             ?>
                                              
                                             <?php if($value['approve'] == 2){ 
                                              ?>
                                              <img src="<?php echo site_url(PURCHASE_PATH.'approval/approved.png'); ?>" class="img_style">

                                              <?php if(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.png')){ ?>
                                                <br><img src="<?php echo site_url(PURCHASE_PATH.'faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.png'); ?>" class="img_style">
                                                <?php }elseif(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.jpg')){ ?>
                                                   <br><img src="<?php echo site_url(PURCHASE_PATH.'faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.jpg'); ?>" class="img_style">
                                                <?php } ?>

                                             <?php }elseif($value['approve'] == 3){ ?>
                                                <img src="<?php echo site_url(PURCHASE_PATH.'approval/rejected.png'); ?>" class="img_style">


                                                <?php if(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.png')){ ?>
                                                <br><img src="<?php echo site_url(PURCHASE_PATH.'faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.png'); ?>" class="img_style">
                                                <?php }elseif(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.jpg')){ ?>
                                                   <br><img src="<?php echo site_url(PURCHASE_PATH.'faf_requests/signature/'.$faf->id.'/signature_'.$value['id'].'.jpg'); ?>" class="img_style">
                                                <?php } ?>
                                            <?php } ?> 
                                            <br><br>  
                                             <p><?php echo pur_html_entity_decode($value['note']) ?></p>  
                                            <p class="bold text-center text-<?php if($value['approve'] == 2){ echo 'success'; }elseif($value['approve'] == 3){ echo 'danger'; } ?>"><?php echo _dt($value['date']); ?></p> 
                                        </div>
                                        <?php }
                                        } ?>
                                       </div>
                                    </div>
                                    
                                    <?php } ?>
                                    </div>
                                
                            </div>

                        </div>


                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tab_reminders">
                    <a href="#" data-toggle="modal" class="btn btn-info" data-target=".reminder-modal-pur_faf-<?php echo pur_html_entity_decode($faf->id); ?>"><i class="fa fa-bell-o"></i> <?php echo _l('estimate_set_reminder_title'); ?></a>
                    <hr />
                    <?php render_datatable(array( _l( 'reminder_description'), _l( 'reminder_date'), _l( 'reminder_staff'), _l( 'reminder_is_notified')), 'reminders'); ?>
                    <?php $this->load->view('admin/includes/modals/reminder',array('id'=>$faf->id,'name'=>'pur_faf','members'=>$members,'reminder_title'=>_l('estimate_set_reminder_title'))); ?>
                </div>

                <div role="tabpanel" class="tab-pane" id="tab_notes">
                    <br>
                     <?php echo form_open(admin_url('purchase/add_pur_faf_note/'.$faf->id),array('id'=>'sales-notes','class'=>'pur_faf-notes-form')); ?>
                     <?php echo render_textarea('description'); ?>
                     <div class="text-right">
                        <button type="submit" class="btn btn-info mtop15 mbot15"><?php echo _l('estimate_add_note'); ?></button>
                     </div>
                     <?php echo form_close(); ?>
                     <hr />
                     <div class="panel_s mtop20 no-shadow" id="sales_notes_area">
                     </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="activity_log">
                   <table class="table dt-table">
                       <thead>
                         <th><?php echo _l('pur_date'); ?></th>
                          <th><?php echo _l('staff'); ?></th>
                          <th><?php echo _l('note'); ?></th>
                          
                       </thead>
                      <tbody>
                         <?php foreach($activity_logs as $log) { ?>
                  
                            <tr>
                               <td><?php echo _dt($log['date']); ?></td>
                               <td><?php echo get_staff_full_name($log['staffid']); ?></td>
                               <td><?php echo pur_html_entity_decode($log['note']); ?></td>

                            </tr>
                         <?php } ?>
                      </tbody>
                   </table>

                </div>

                <div role="tabpanel" class="tab-pane" id="attachment">
          
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
                 
                </div>

                <div role="tabpanel" class="tab-pane" id="tab_tasks">
                 <?php init_relation_tasks_table(array('data-new-rel-id'=>$faf->id,'data-new-rel-type'=>'pur_faf')); ?>
                </div>
            </div>

        </div>
    </div>
</div>

 

<div class="modal fade" id="requestor_sign_faf" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         
        <div class="modal-body">
         <p class="bold" id="requestor_signatureLabel"><?php echo _l('signature'); ?></p>

         <div class="form-group">
             <div class="radio radio-primary radio-inline">
                 <input type="radio" id="requestor_sign" name="requestor_sign_type" value="1" checked>
                 <label for="requestor_sign">
                    <?php echo _l('sign'); ?>      
                  </label>
             </div>
             <div class="radio radio-primary radio-inline">
                 <input type="radio" id="requestor_upload" name="requestor_sign_type" value="0" >
                 <label for="requestor_upload">
                     <?php echo _l('pur_upload'); ?>    
                  </label>
             </div>
         </div>


            <div id="requestor_upload_sign" class="mbot15 hide">
               <?php echo form_open_multipart(admin_url('purchase/requestor_sign_attachment/'.$faf->id),array('id'=>'requestor_sign_attachment-form')); ?>

                  <input type="file" id="requestor_sign_attachment_file" accept=".png, .jpg" name="requestor_sign_attachment" class="form-control">

               <?php echo form_close(); ?>    
            </div>

            <div id="requestor_sign_pad" >
               <div class="signature-pad--body">
                 <canvas id="requestor_signature" height="130" width="550"></canvas>
               </div>
               <input type="text" class="ip_style" tabindex="-1" name="requestor_signature" id="requestor_signatureInput">

               <div class="dispay-block">
                 <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" onclick="requestor_signature_clear();"><?php echo _l('clear'); ?></button>
               
               </div>
            </div>
            

           

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
           <button id="requestor_sign_button" onclick="requestor_sign_request(<?php echo pur_html_entity_decode($faf->id); ?>);" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" class="btn btn-success"><?php echo _l('e_signature_sign'); ?></button>
          </div>

      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="add_action" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         
        <div class="modal-body">
         <p class="bold" id="signatureLabel"><?php echo _l('signature'); ?></p>

         <div class="form-group">
             <div class="radio radio-primary radio-inline">
                 <input type="radio" id="sign" name="sign_type" value="1" checked>
                 <label for="sign">
                    <?php echo _l('sign'); ?>      
                  </label>
             </div>
             <div class="radio radio-primary radio-inline">
                 <input type="radio" id="upload" name="sign_type" value="0" >
                 <label for="upload">
                     <?php echo _l('pur_upload'); ?>    
                  </label>
             </div>
         </div>


            <div id="upload_sign" class="mbot15 hide">
               <?php echo form_open_multipart(admin_url('purchase/sign_attachment'),array('id'=>'sign_attachment-form')); ?>

                  <input type="file" id="sign_attachment_file" accept=".png, .jpg" name="sign_attachment" class="form-control">

                  <?php echo form_hidden('approve_rel_id', $faf->id) ?>
                  <?php echo form_hidden('approve_rel_type', 'faf_requests') ?>

               <?php echo form_close(); ?>    
            </div>

            <div id="sign_pad" >
               <div class="signature-pad--body">
                 <canvas id="signature" height="130" width="550"></canvas>
               </div>
               <input type="text" class="ip_style" tabindex="-1" name="signature" id="signatureInput">

               <div class="dispay-block">
                 <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" onclick="signature_clear();"><?php echo _l('clear'); ?></button>
               
               </div>
            </div>
            

           

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
           <button id="sign_button" onclick="sign_request(<?php echo pur_html_entity_decode($faf->id); ?>);" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" class="btn btn-success"><?php echo _l('e_signature_sign'); ?></button>
          </div>

      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php require 'modules/purchase/assets/js/faf_request/faf_detail_js.php';?>