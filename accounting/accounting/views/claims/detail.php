<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php $currency_name = isset($currency) && $currency ? $currency->name : ''; ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="no-margin bold text-primary"><?php echo $title; ?></h4>
                            </div>
                            <div class="col-md-8 text-right">
                                <?php 
                                $total_refunded = 0;
                                foreach ($refunds as $ref) {
                                    $total_refunded += floatval($ref['amount']);
                                }
                                $remaining = floatval($claim->amount) - $total_refunded;
                                ?>
                                <?php if (($claim->status == 'draft' || $claim->status == 'pending_approval' || $claim->status == 'rejected') && (has_permission('acc_claims', '', 'edit') || is_admin())) { ?>
                                    <a href="<?php echo admin_url('accounting/edit_claim/' . $claim->id); ?>" class="btn btn-default mright5"><i class="fa fa-edit"></i> <?php echo _l('acc_edit_claim'); ?></a>
                                <?php } ?>
                                <?php if (isset($check_appr) && $check_appr && ($claim->status == 'draft' || $claim->status == 'rejected') && (has_permission('acc_claims', '', 'edit') || is_admin())) { ?>
                                    <a href="<?php echo admin_url('accounting/submit_claim_for_approval/' . $claim->id); ?>" class="btn btn-info mright5"><i class="fa fa-paper-plane"></i> <?php echo _l('acc_submit_for_approval'); ?></a>
                                <?php } ?>
                                <?php if ($claim->status == 'approved' && $remaining > 0 && (has_permission('acc_claims', '', 'edit') || is_admin())) { ?>
                                    <button class="btn btn-success mright5" id="btn-pay-refund" data-toggle="modal" data-target="#refund-modal"><i class="fa fa-money"></i> <?php echo _l('acc_pay_refund'); ?></button>
                                <?php } ?>
                                <?php if (has_permission('acc_claims', '', 'delete') || is_admin()) { ?>
                                    <a href="<?php echo admin_url('accounting/delete_claim/' . $claim->id); ?>" class="btn btn-danger mright5 _delete" id="btn-delete-claim"><i class="fa fa-remove"></i> <?php echo _l('delete'); ?></a>
                                <?php } ?>
                                <a href="<?php echo admin_url('accounting/claims'); ?>" class="btn btn-default" id="btn-back-list"><i class="fa fa-arrow-left"></i> <?php echo _l('acc_back_to_list'); ?></a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />


                        <div class="row">
                            <!-- Left Column: Claim & Refund Information -->
                            <div class="col-md-6">
                                <div class="panel_s">
                                    <div class="panel-body bg-light-gray">
                                        <h5 class="bold text-muted mbot15"><i class="fa fa-info-circle"></i> <?php echo _l('acc_claim_details'); ?></h5>
                                        <table class="table table-striped table-bordered no-mtop">
                                            <tbody>
                                                <tr>
                                                    <td class="bold" width="35%"><?php echo _l('acc_expense_date'); ?></td>
                                                    <td><?php echo _d($claim->expense_date); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_approval_status'); ?></td>
                                                    <td>
                                                        <?php
                                                        $status_class = 'default';
                                                        if ($claim->status == 'draft') $status_class = 'default';
                                                        if ($claim->status == 'pending_approval') $status_class = 'info';
                                                        if ($claim->status == 'approved') $status_class = 'warning';
                                                        if ($claim->status == 'rejected') $status_class = 'danger';
                                                        if ($claim->status == 'paid') $status_class = 'success';
                                                        ?>
                                                        <span class="label label-<?php echo $status_class; ?> inline-block">
                                                            <?php echo html_escape(_l('acc_' . $claim->status)); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_project'); ?></td>
                                                    <td><a href="<?php echo admin_url('projects/view/' . $claim->project_id); ?>" class="bold"><?php echo html_escape($claim->project_name); ?></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_budget_category'); ?></td>
                                                    <td><?php echo html_escape($claim->category_name); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_staff_member'); ?></td>
                                                    <td><?php echo html_escape($claim->staff_name); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_claim_amount'); ?></td>
                                                    <td><span class="bold text-danger"><?php echo app_format_money($claim->amount, $currency_name); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_total_refunded'); ?></td>
                                                    <td><span class="bold text-success"><?php echo app_format_money($total_refunded, $currency_name); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_remaining_balance'); ?></td>
                                                    <td><span class="bold text-primary"><?php echo app_format_money($remaining, $currency_name); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('description'); ?></td>
                                                    <td><?php echo nl2br(html_escape($claim->description)); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_created_by_date'); ?></td>
                                                    <td>
                                                        <?php 
                                                        $creator = get_staff($claim->created_by);
                                                        echo html_escape($creator ? $creator->firstname . ' ' . $creator->lastname : ''); 
                                                        ?> 
                                                        (<?php echo _dt($claim->created_at); ?>)
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- Claim Request Attachments -->
                                        <h5 class="bold text-muted mtop20 mbot15"><i class="fa fa-paperclip"></i> <?php echo _l('acc_claim_attachments'); ?></h5>
                                        <div class="well well-sm">
                                            <?php 
                                            $claim_attachments = array_filter($attachments, function($att) { return $att['rel_type'] == 'claim_request'; });
                                            if (empty($claim_attachments)) {
                                                echo '<p class="text-muted no-margin">' . _l('acc_no_attachments_uploaded') . '</p>';
                                            } else {
                                                foreach ($claim_attachments as $att) { ?>
                                                    <div class="mbot10">
                                                        <a href="<?php echo admin_url('accounting/download_claim_file/' . $att['id']); ?>" target="_blank"><i class="fa fa-file"></i> <?php echo html_escape($att['file_name']); ?></a>
                                                        <?php if (has_permission('acc_claims', '', 'delete') || is_admin()) { ?>
                                                            <a href="<?php echo admin_url('accounting/delete_claim_attachment/' . $att['id']); ?>" class="text-danger mleft10 _delete" onclick="return confirm('<?php echo _l('acc_confirm_delete_attachment'); ?>');"><i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Refunds History -->
                                <div class="panel_s mtop20">
                                    <div class="panel-body bg-light-gray">
                                        <h5 class="bold text-success mbot15"><i class="fa fa-check-circle"></i> <?php echo _l('acc_refunds_reimbursements'); ?></h5>
                                        <?php if (empty($refunds)) { ?>
                                            <p class="text-muted no-margin"><?php echo _l('acc_no_refunds_processed_yet'); ?></p>
                                        <?php } else { ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped no-mtop">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo _l('date'); ?></th>
                                                            <th><?php echo _l('amount'); ?></th>
                                                            <th><?php echo _l('acc_payment_method'); ?></th>
                                                            <th><?php echo _l('notes'); ?></th>
                                                            <th class="text-right"><?php echo _l('options'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($refunds as $ref) { ?>
                                                            <?php $max_edit_refund = $remaining + floatval($ref['amount']); ?>
                                                            <tr>
                                                                <td><?php echo _d($ref['payment_date']); ?></td>
                                                                <td class="bold text-success"><?php echo app_format_money($ref['amount'], $currency_name); ?></td>
                                                                <td><?php echo html_escape($ref['payment_method']); ?></td>
                                                                <td>
                                                                    <?php echo html_escape($ref['notes']); ?>
                                                                    
                                                                    <!-- Refund Attachment -->
                                                                    <?php 
                                                                    $refund_attachments = array_filter($attachments, function($att) use ($ref) { 
                                                                        return $att['rel_type'] == 'claim_refund' && $att['rel_id'] == $ref['id']; 
                                                                    });
                                                                    if (!empty($refund_attachments)) { ?>
                                                                        <div class="mtop10">
                                                                            <small class="bold text-muted"><?php echo _l('acc_receipts'); ?>:</small><br>
                                                                            <?php foreach ($refund_attachments as $att) { ?>
                                                                                <div class="mbot5">
                                                                                    <a href="<?php echo admin_url('accounting/download_claim_file/' . $att['id']); ?>" target="_blank"><i class="fa fa-file"></i> <small><?php echo html_escape($att['file_name']); ?></small></a>
                                                                                    <?php if (has_permission('acc_claims', '', 'delete') || is_admin()) { ?>
                                                                                        <a href="<?php echo admin_url('accounting/delete_claim_attachment/' . $att['id']); ?>" class="text-danger mleft5 _delete" onclick="return confirm('<?php echo _l('acc_confirm_delete_receipt'); ?>');"><i class="fa fa-remove"></i></a>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php if (has_permission('acc_claims', '', 'edit') || is_admin()) { ?>
                                                                        <button type="button" class="btn btn-default btn-xs btn-edit-claim-refund"
                                                                            data-id="<?php echo html_escape($ref['id']); ?>"
                                                                            data-amount="<?php echo html_escape($ref['amount']); ?>"
                                                                            data-max="<?php echo html_escape($max_edit_refund); ?>"
                                                                            data-payment-date="<?php echo html_escape(_d($ref['payment_date'])); ?>"
                                                                            data-payment-method="<?php echo html_escape($ref['payment_method']); ?>"
                                                                            data-credit-account-id="<?php echo html_escape($ref['credit_account_id']); ?>"
                                                                            data-notes="<?php echo html_escape($ref['notes']); ?>">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>
                                                                    <?php } ?>
                                                                    <?php if (has_permission('acc_claims', '', 'delete') || is_admin()) { ?>
                                                                        <a href="<?php echo admin_url('accounting/delete_claim_refund/' . $ref['id']); ?>" class="btn btn-danger btn-xs _delete">
                                                                            <i class="fa fa-remove"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Accounting Mapping & Ledger Entries -->
                            <div class="col-md-6">
                                <?php if(isset($list_approve_status) && count($list_approve_status) > 0 ){ ?>
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                                            <h5 class="bold text-muted no-margin"><i class="fa fa-list-ol"></i> <?php echo _l('pur_approval_infor'); ?></h5>
                                            <div class="approval-actions">
                                                 <?php 
                                                 if(isset($check_appr) && $check_appr && $check_appr != false){
                                                 if(($claim->status == 'draft' && ($check_approve_status == false || $check_approve_status == 'reject')) || ($claim->status == 'draft' && isset($appr_setting->approval_type) && $appr_setting->approval_type == 1 && is_array($check_approve_status['staffid']) && count($check_approve_status['staffid']) != count($list_approve_status)) ){ ?>
                                                    <a data-toggle="tooltip" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-success btn-xs" data-placement="top" href="<?php echo admin_url('accounting/submit_claim_for_approval/' . $claim->id); ?>"><i class="fa fa-paper-plane"></i> <?php echo _l('send_request_approve_pur'); ?></a>
                                                 <?php } }
                                                 if(isset($check_approve_status['staffid']) && is_array($check_approve_status['staffid'])){
                                                     if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && !in_array(get_staff_user_id(), $get_staff_sign) && $claim->status == 'pending_approval'){ ?>
                                                         <div class="btn-group" >
                                                                <a href="#" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('approve'); ?> <span class="caret"></span></a>
                                                                <ul class="dropdown-menu dropdown-menu-right" style="min-width: 250px; padding: 15px; z-index: 9999;">
                                                                 <li>
                                                                   <div class="col-md-12" style="padding-left: 0; padding-right: 0;">
                                                                     <?php echo render_textarea('reason', 'reason'); ?>
                                                                   </div>

                                                                   <?php if(get_option('allow_upload_esign_for_approve_type') == 1){ ?>
                                                                       <div class="col-md-12 mbot15" style="padding-left: 0; padding-right: 0;">
                                                                          <?php echo form_open_multipart(admin_url('accounting/sign_attachment'),array('id'=>'sign_attachment-form')); ?>

                                                                             <label for="sign_attachment"><?php echo _l('e_sign'); ?></label>
                                                                             <input type="file" id="sign_attachment_file" accept=".png, .jpg" name="sign_attachment" class="form-control">

                                                                             <?php echo form_hidden('approve_rel_id', $claim->id) ?>
                                                                             <?php echo form_hidden('approve_rel_type', 'claim') ?>

                                                                          <?php echo form_close(); ?>   
                                                                       </div> 
                                                                    <?php } ?>

                                                                 </li>
                                                                   <li>
                                                                     <div class="row text-right col-md-12 pad_right_0 mbot15" style="margin-left: 0;">
                                                                       <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="approve_request_claim(<?php echo html_escape($claim->id); ?>); return false;" class="btn btn-success mright15"><?php echo _l('approve'); ?></a>
                                                                      <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="deny_request_claim(<?php echo html_escape($claim->id); ?>); return false;" class="btn btn-warning"><?php echo _l('deny'); ?></a></div>
                                                                   </li>
                                                                </ul>
                                                             </div>
                                                     <?php }
                                                     if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && in_array(get_staff_user_id(), $get_staff_sign) && $claim->status == 'pending_approval'){ ?>
                                                         <button onclick="accept_action();" class="btn btn-success btn-xs"><?php echo _l('e_signature_sign'); ?></button>
                                                     <?php }
                                                 }
                                                 ?>
                                            </div>
                                        </div>
                                        
                                        <div class="approval-timeline">
                                          <?php 
                                           $this->load->model('staff_model');
                                           foreach ($list_approve_status as $value) {
                                             $value['staffid'] = explode(', ', $value['staffid'] ?? '');
                                             
                                             // Determine status class
                                             $step_status_class = 'pending';
                                             if ($value['approve'] == 2) {
                                                 $step_status_class = 'approved';
                                             } elseif ($value['approve'] == 3) {
                                                 $step_status_class = 'rejected';
                                             }
                                             
                                             $staff_name = '';
                                             foreach ($value['staffid'] as $val) {
                                               if ($staff_name != '') {
                                                 $staff_name .= ' or ';
                                               }
                                               $staff_name .= isset($this->staff_model->get($val)->full_name) ? $this->staff_model->get($val)->full_name : '';
                                               $staff_position = pur_get_job_position($val);
                                             }
                                           ?>
                                           <div class="approval-step <?php echo html_escape($step_status_class); ?>">
                                               <div class="approval-icon-indicator"></div>
                                               <div class="approval-content">
                                                   <h6>
                                                       <?php echo html_escape($staff_name); ?>
                                                       <?php if ($value['action'] == 'sign') { ?>
                                                           <span class="label label-info mleft5" style="font-size: 10px; padding: 2px 5px;">Signer</span>
                                                       <?php } else { ?>
                                                           <span class="label label-default mleft5" style="font-size: 10px; padding: 2px 5px;">Approver</span>
                                                       <?php } ?>
                                                   </h6>
                                                   <?php if (isset($staff_position) && $staff_position != '') { ?>
                                                       <span class="position"><?php echo html_escape($staff_position); ?></span>
                                                   <?php } ?>
                                                   
                                                   <?php if ($value['approve'] == 2) { ?>
                                                       <div class="signature-container">
                                                           <?php if (file_exists(ACCOUTING_MODULE_UPLOAD_FOLDER.'/claims/signature/'.$claim->id.'/signature_'.$value['id'].'.png')) { ?>
                                                               <img src="<?php echo site_url(ACCOUTING_PATH.'claims/signature/'.$claim->id.'/signature_'.$value['id'].'.png'); ?>" class="img_style">
                                                           <?php } elseif (file_exists(ACCOUTING_MODULE_UPLOAD_FOLDER.'/claims/signature/'.$claim->id.'/signature_'.$value['id'].'.jpg')) { ?>
                                                               <img src="<?php echo site_url(ACCOUTING_PATH.'claims/signature/'.$claim->id.'/signature_'.$value['id'].'.jpg'); ?>" class="img_style">
                                                           <?php } else { ?>
                                                               <img src="<?php echo site_url(ACCOUTING_PATH.'approval/approved.png'); ?>" class="img_style" style="width: 100px; height: auto;">
                                                           <?php } ?>
                                                       </div>
                                                       <p class="approval-date bold text-success" style="margin-top: 5px;">
                                                           <i class="fa fa-check-circle"></i> <?php echo _l('signed') . ' - ' . _dt($value['date']); ?>
                                                       </p>
                                                   <?php } elseif ($value['approve'] == 3) { ?>
                                                       <div class="signature-container">
                                                           <img src="<?php echo site_url(ACCOUTING_PATH.'approval/rejected.png'); ?>" class="img_style" style="width: 100px; height: auto;">
                                                       </div>
                                                       <p class="approval-date bold text-danger" style="margin-top: 5px;">
                                                           <i class="fa fa-times-circle"></i> <?php echo _l('rejected') . ' - ' . _dt($value['date']); ?>
                                                       </p>
                                                   <?php } else { ?>
                                                       <p class="approval-date text-warning" style="margin-top: 5px;">
                                                           <i class="fa fa-clock-o"></i> <?php echo _l('acc_waiting_for_approval'); ?>
                                                       </p>
                                                   <?php } ?>
                                                   
                                                   <?php if (!empty($value['note'])) { ?>
                                                       <div class="approval-note">
                                                           <strong><?php echo _l('acc_note'); ?>:</strong> <?php echo html_escape($value['note']); ?>
                                                       </div>
                                                   <?php } ?>
                                               </div>
                                           </div>
                                           <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php echo form_open(admin_url('accounting/update_claim_mapping/' . $claim->id), ['id' => 'mapping-form']); ?>
                                
                                <!-- Claim Booking Entries -->
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h5 class="bold text-primary mbot15"><i class="fa fa-exchange"></i> <?php echo _l('acc_claim_booking_ledger_entries'); ?></h5>
                                        <div class="">
                                            <table class="table table-bordered table-striped no-mtop">
                                                <thead>
                                                    <tr>
                                                        <th width="50%"><?php echo _l('acc_account'); ?></th>
                                                        <th><?php echo _l('debit'); ?></th>
                                                        <th><?php echo _l('credit'); ?></th>
                                                        <th><?php echo _l('description'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $booking_lines = array_filter($ledger_entries, function($entry) { return $entry['rel_type'] == 'claim'; });
                                                    if (empty($booking_lines)) {
                                                        echo '<tr><td colspan="4" class="text-muted text-center">' . _l('acc_no_ledger_entries_created_yet_claim_draft') . '</td></tr>';
                                                    } else {
                                                        foreach ($booking_lines as $entry) { ?>
                                                            <tr>
                                                                <td>
                                                                    <select name="mappings[<?php echo $entry['id']; ?>]" class="selectpicker" data-width="100%" data-live-search="true" required>
                                                                        <?php foreach ($accounts as $acc) { 
                                                                            $account_name = $acc['name'];
                                                                            if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                                                                $account_name = $acc['number'] . ' - ' . $account_name;
                                                                            }
                                                                        ?>
                                                                        <option value="<?php echo $acc['id']; ?>" <?php echo $acc['id'] == $entry['account'] ? 'selected' : ''; ?>>
                                                                            <?php echo html_escape($account_name); ?>
                                                                        </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td class="bold text-success"><?php echo $entry['debit'] > 0 ? app_format_money($entry['debit'], $currency_name) : '-'; ?></td>
                                                                <td class="bold text-danger"><?php echo $entry['credit'] > 0 ? app_format_money($entry['credit'], $currency_name) : '-'; ?></td>
                                                                <td><small class="text-muted"><?php echo html_escape($entry['description']); ?></small></td>
                                                            </tr>
                                                        <?php }
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Refund Entries -->
                                <?php 
                                $refund_lines = array_filter($ledger_entries, function($entry) { return $entry['rel_type'] == 'claim_refund'; });
                                if (!empty($refund_lines)) { ?>
                                <div class="panel_s mtop20">
                                    <div class="panel-body">
                                        <h5 class="bold text-success mbot15"><i class="fa fa-exchange"></i> <?php echo _l('acc_refund_payments_ledger_entries'); ?></h5>
                                        <div class="">
                                            <table class="table table-bordered table-striped no-mtop">
                                                <thead>
                                                    <tr>
                                                        <th width="50%"><?php echo _l('acc_account'); ?></th>
                                                        <th><?php echo _l('debit'); ?></th>
                                                        <th><?php echo _l('credit'); ?></th>
                                                        <th><?php echo _l('description'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    foreach ($refund_lines as $entry) { ?>
                                                        <tr>
                                                            <td>
                                                                <select name="mappings[<?php echo $entry['id']; ?>]" class="selectpicker" data-width="100%" data-live-search="true" required>
                                                                    <?php foreach ($accounts as $acc) { 
                                                                        $account_name = $acc['name'];
                                                                        if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                                                            $account_name = $acc['number'] . ' - ' . $account_name;
                                                                        }
                                                                    ?>
                                                                    <option value="<?php echo $acc['id']; ?>" <?php echo $acc['id'] == $entry['account'] ? 'selected' : ''; ?>>
                                                                        <?php echo html_escape($account_name); ?>
                                                                    </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td class="bold text-success"><?php echo $entry['debit'] > 0 ? app_format_money($entry['debit'], $currency_name) : '-'; ?></td>
                                                            <td class="bold text-danger"><?php echo $entry['credit'] > 0 ? app_format_money($entry['credit'], $currency_name) : '-'; ?></td>
                                                            <td><small class="text-muted"><?php echo html_escape($entry['description']); ?></small></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if (!empty($ledger_entries) && (has_permission('acc_claims', '', 'edit') || is_admin())) { ?>
                                    <div class="text-right mtop15">
                                        <button type="submit" class="btn btn-primary" id="btn-save-mappings"><i class="fa fa-save"></i> <?php echo _l('acc_save_account_mappings'); ?></button>
                                    </div>
                                <?php } ?>
                                
                                <?php echo form_close(); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refund-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('acc_pay_refund_reimburse_staff'); ?></h4>
            </div>
            <?php echo form_open_multipart(admin_url('accounting/add_claim_refund'), ['id' => 'refund-form']); ?>
            <input type="hidden" name="claim_id" id="claim_id" value="<?php echo $claim->id; ?>">
            <input type="hidden" name="redirect_to_detail" value="1">
            <div class="modal-body">
                <div class="form-group">
                    <label for="refund_amount" class="control-label"><?php echo _l('acc_refund_amount'); ?></label>
                    <input type="number" step="0.01" name="amount" id="refund_amount" class="form-control" value="<?php echo number_format($remaining, 2, '.', ''); ?>" max="<?php echo $remaining; ?>" required>
                    <p class="help-block"><?php echo _l('acc_maximum_allowed_refund'); ?>: <?php echo number_format($remaining, 2, '.', ''); ?></p>
                </div>
                
                <?php echo render_date_input('payment_date', 'acc_payment_date', _d(date('Y-m-d')), array('required' => true)); ?>
                
                <div class="form-group">
                    <label for="payment_method" class="control-label"><?php echo _l('acc_payment_method'); ?></label>
                    <select name="payment_method" id="payment_method" class="selectpicker" data-width="100%">
                        <option value="Cash"><?php echo _l('acc_cash'); ?></option>
                        <option value="Bank Transfer"><?php echo _l('acc_bank_transfer'); ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="credit_account_id" class="control-label"><?php echo _l('acc_payment_source_credit_cash_bank'); ?></label>
                    <select name="credit_account_id" id="credit_account_id" class="selectpicker" data-width="100%" data-live-search="true" required>
                        <option value=""></option>
                        <?php foreach ($accounts as $acc) { 
                            $account_name = $acc['name'];
                            if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                $account_name = $acc['number'] . ' - ' . $account_name;
                            }
                        ?>
                        <option value="<?php echo $acc['id']; ?>" <?php echo (strpos(strtolower($acc['name']), 'tiền mặt') !== false || strpos(strtolower($acc['name']), 'cash') !== false || strpos(strtolower($acc['name']), '111') !== false) ? 'selected' : ''; ?>>
                            <?php echo html_escape($account_name); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes" class="control-label"><?php echo _l('acc_payment_notes_description'); ?></label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="file" class="control-label"><?php echo _l('acc_upload_receipts_multiple'); ?></label>
                    <input type="file" name="file[]" id="file" class="form-control" multiple>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('acc_process_payment'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-refund-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('edit'); ?> <?php echo _l('acc_refunds_reimbursements'); ?></h4>
            </div>
            <?php echo form_open_multipart('', ['id' => 'edit-refund-form', 'data-action-base' => admin_url('accounting/edit_claim_refund/')]); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_refund_amount" class="control-label"><?php echo _l('acc_refund_amount'); ?></label>
                    <input type="number" step="0.01" name="amount" id="edit_refund_amount" class="form-control" required>
                    <p class="help-block edit-refund-max-help"></p>
                </div>
                
                <?php echo render_date_input('payment_date', 'acc_payment_date', '', array('required' => true, 'id' => 'edit_payment_date')); ?>
                
                <div class="form-group">
                    <label for="edit_payment_method" class="control-label"><?php echo _l('acc_payment_method'); ?></label>
                    <select name="payment_method" id="edit_payment_method" class="selectpicker" data-width="100%">
                        <option value="Cash"><?php echo _l('acc_cash'); ?></option>
                        <option value="Bank Transfer"><?php echo _l('acc_bank_transfer'); ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_credit_account_id" class="control-label"><?php echo _l('acc_payment_source_credit_cash_bank'); ?></label>
                    <select name="credit_account_id" id="edit_credit_account_id" class="selectpicker" data-width="100%" data-live-search="true" required>
                        <option value=""></option>
                        <?php foreach ($accounts as $acc) { 
                            $account_name = $acc['name'];
                            if (!empty($acc['number']) && strpos($account_name, $acc['number']) !== 0) {
                                $account_name = $acc['number'] . ' - ' . $account_name;
                            }
                        ?>
                        <option value="<?php echo $acc['id']; ?>">
                            <?php echo html_escape($account_name); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_refund_notes" class="control-label"><?php echo _l('acc_payment_notes_description'); ?></label>
                    <textarea name="notes" id="edit_refund_notes" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_refund_file" class="control-label"><?php echo _l('acc_upload_receipts_multiple'); ?></label>
                    <input type="file" name="file[]" id="edit_refund_file" class="form-control" multiple>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('save'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

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
               <?php echo form_open_multipart(admin_url('accounting/sign_attachment'),array('id'=>'sign_attachment-form')); ?>

                  <input type="file" id="sign_attachment_file" accept=".png, .jpg" name="sign_attachment" class="form-control">

                  <?php echo form_hidden('approve_rel_id', $claim->id) ?>
                  <?php echo form_hidden('approve_rel_type', 'claim') ?>

               <?php echo form_close(); ?>    
            </div>

            <div id="sign_pad" >
               <div class="signature-pad--body">
                 <canvas id="signature" height="130" width="550"></canvas>
               </div>
               <input type="text" class="ip_style" tabindex="-1" name="signature" id="signatureInput" style="display: none;">

               <div class="dispay-block">
                 <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" onclick="signature_clear();"><?php echo _l('clear'); ?></button>
               
               </div>
            </div>
            

           

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
           <button id="sign_button" onclick="sign_request_claim(<?php echo html_escape($claim->id); ?>);" data-loading-text="<?php echo _l('wait_text'); ?>" data-original-text="<?php echo _l('e_signature_sign'); ?>" autocomplete="off" class="btn btn-success"><?php echo _l('e_signature_sign'); ?></button>
          </div>

      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php init_tail(); ?>
</body>
</html>
