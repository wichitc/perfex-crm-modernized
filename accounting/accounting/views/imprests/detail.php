<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$payment_method_names = [];
foreach (($payment_modes ?? []) as $mode) {
    if (isset($mode['id'], $mode['name'])) {
        $payment_method_names[(string) $mode['id']] = $mode['name'];
    }
}
$currency_name = isset($currency) && $currency ? $currency->name : '';
?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="no-margin bold text-primary"><?php echo $title; ?></h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if (($imprest->status == 'draft' || $imprest->status == 'pending_approval' || $imprest->status == 'rejected') && (has_permission('acc_imprests', '', 'edit') || is_admin())) { ?>
                                    <a href="<?php echo admin_url('accounting/edit_imprest/' . $imprest->id); ?>" class="btn btn-default mright5" id="btn-edit-request"><i class="fa fa-pencil-square-o"></i> <?php echo _l('acc_edit_request'); ?></a>
                                <?php } ?>
                                <?php if (isset($check_appr) && $check_appr && ($imprest->status == 'draft' || $imprest->status == 'rejected') && (has_permission('acc_imprests', '', 'edit') || is_admin())) { ?>
                                    <a href="<?php echo admin_url('accounting/submit_imprest_for_approval/' . $imprest->id); ?>" class="btn btn-info mright5"><i class="fa fa-paper-plane"></i> <?php echo _l('acc_submit_for_approval'); ?></a>
                                <?php } ?>
                                <?php if ($imprest->status == 'disbursed' && (has_permission('acc_imprests', '', 'edit') || is_admin())) { ?>
                                    <a href="<?php echo admin_url('accounting/retire_imprest/' . $imprest->id); ?>" class="btn btn-success mright5" id="btn-retire-cash"><i class="fa fa-check-circle"></i> <?php echo _l('acc_retire_cash'); ?></a>
                                <?php } ?>
                                <?php if (in_array($imprest->status, ['completed', 'pending_refund', 'pending_payment']) && (has_permission('acc_imprests', '', 'edit') || is_admin())) { ?>
                                    <a href="<?php echo admin_url('accounting/retire_imprest/' . $imprest->id); ?>" class="btn btn-warning mright5" id="btn-edit-retirement"><i class="fa fa-pencil-square-o"></i> <?php echo _l('acc_edit_retirement'); ?></a>
                                <?php } ?>
                                <?php if (in_array($imprest->status, ['completed', 'pending_refund', 'pending_payment']) && (has_permission('acc_imprests', '', 'delete') || is_admin())) { ?>
                                    <a href="<?php echo admin_url('accounting/delete_imprest_retirement/' . $imprest->id); ?>" class="btn btn-danger mright5 _delete" id="btn-delete-retirement"><i class="fa fa-trash"></i> <?php echo _l('acc_delete_retirement'); ?></a>
                                <?php } ?>
                                <?php if (($imprest->status == 'pending_refund' || $imprest->status == 'pending_payment') && (has_permission('acc_imprests', '', 'edit') || is_admin())) { ?>
                                    <a href="<?php echo admin_url('accounting/update_imprest_status/' . $imprest->id); ?>" class="btn btn-info mright5" id="btn-mark-completed" onclick="return confirm('<?php echo html_escape(_l('acc_confirm_mark_as_completed')); ?>');"><i class="fa fa-check-square-o"></i> <?php echo _l('acc_mark_as_completed'); ?></a>
                                <?php } ?>
                                <?php if (has_permission('acc_imprests', '', 'delete') || is_admin()) { ?>
                                    <a href="<?php echo admin_url('accounting/delete_imprest/' . $imprest->id); ?>" class="btn btn-danger mright5 _delete" id="btn-delete-imprest"><i class="fa fa-remove"></i> <?php echo _l('delete'); ?></a>
                                <?php } ?>
                                <a href="<?php echo admin_url('accounting/imprests'); ?>" class="btn btn-default" id="btn-back-list"><i class="fa fa-arrow-left"></i> <?php echo _l('acc_back_to_list'); ?></a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <div class="row">
                            <!-- Left Column: Request & Retirement Information -->
                            <div class="col-md-6">
                                <div class="panel_s">
                                    <div class="panel-body bg-light-gray">
                                        <h5 class="bold text-muted mbot15"><i class="fa fa-info-circle"></i> <?php echo _l('acc_request_details'); ?></h5>
                                        <table class="table table-striped table-bordered no-mtop">
                                            <tbody>
                                                <tr>
                                                    <td class="bold" width="35%"><?php echo _l('reference_no'); ?></td>
                                                    <td><strong><?php echo html_escape($imprest->reference_no); ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_request_date'); ?></td>
                                                    <td><?php echo _d($imprest->request_date); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('status'); ?></td>
                                                    <td>
                                                        <?php
                                                        $status_class = 'default';
                                                        if ($imprest->status == 'draft') $status_class = 'default';
                                                        if ($imprest->status == 'pending_approval') $status_class = 'info';
                                                        if ($imprest->status == 'rejected') $status_class = 'danger';
                                                        if ($imprest->status == 'disbursed') $status_class = 'warning';
                                                        if ($imprest->status == 'completed') $status_class = 'success';
                                                        if ($imprest->status == 'pending_refund') $status_class = 'warning';
                                                        if ($imprest->status == 'pending_payment') $status_class = 'warning';
                                                        ?>
                                                        <span class="label label-<?php echo $status_class; ?> inline-block">
                                                            <?php echo html_escape(_l('acc_' . $imprest->status)); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('project'); ?></td>
                                                    <td><a href="<?php echo admin_url('projects/view/' . $imprest->project_id); ?>" class="bold"><?php echo html_escape($imprest->project_name); ?></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_budget_category'); ?></td>
                                                    <td><?php echo html_escape($imprest->category_name); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_staff_member'); ?></td>
                                                    <td><?php echo html_escape($imprest->staff_name); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_amount_requested'); ?></td>
                                                    <td><span class="bold text-primary"><?php echo app_format_money($imprest->amount_requested, $currency_name); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_payment_method'); ?></td>
                                                    <td><?php echo html_escape($payment_method_names[(string) $imprest->payment_method] ?? $imprest->payment_method); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('description'); ?></td>
                                                    <td><?php echo nl2br(html_escape($imprest->description)); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_created_by_date'); ?></td>
                                                    <td>
                                                        <?php 
                                                        $creator = get_staff($imprest->created_by);
                                                        echo html_escape($creator ? $creator->firstname . ' ' . $creator->lastname : ''); 
                                                        ?> 
                                                        (<?php echo _dt($imprest->created_at); ?>)
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- Attachments -->
                                        <h5 class="bold text-muted mtop20 mbot15"><i class="fa fa-paperclip"></i> <?php echo _l('acc_request_attachments'); ?></h5>
                                        <div class="well well-sm">
                                            <?php 
                                            $request_attachments = array_filter($attachments, function($att) { return $att['rel_type'] == 'imprest_request'; });
                                            if (empty($request_attachments)) {
                                                echo '<p class="text-muted no-margin">' . _l('acc_no_attachments_uploaded') . '</p>';
                                            } else {
                                                foreach ($request_attachments as $att) { ?>
                                                    <div class="mbot10">
                                                        <a href="<?php echo admin_url('accounting/download_imprest_file/' . $att['id']); ?>" target="_blank"><i class="fa fa-file"></i> <?php echo html_escape($att['file_name']); ?></a>
                                                        <?php if (has_permission('acc_imprests', '', 'delete') || is_admin()) { ?>
                                                            <a href="<?php echo admin_url('accounting/delete_imprest_attachment/' . $att['id']); ?>" class="text-danger mleft10 _delete" onclick="return confirm('<?php echo html_escape(_l('acc_confirm_delete_attachment')); ?>');"><i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
 
                                <?php if ($imprest->status != 'draft' && $imprest->status != 'pending_approval' && $imprest->status != 'rejected' && $imprest->status != 'disbursed') { ?>
                                <div class="panel_s mtop20">
                                    <div class="panel-body bg-light-gray">
                                        <div class="clearfix mbot15">
                                            <h5 class="bold text-success pull-left no-margin"><i class="fa fa-check-circle"></i> <?php echo _l('acc_retirement_details'); ?></h5>
                                            <div class="pull-right">
                                                <?php if (has_permission('acc_imprests', '', 'edit') || is_admin()) { ?>
                                                    <a href="<?php echo admin_url('accounting/retire_imprest/' . $imprest->id); ?>" class="btn btn-warning btn-xs mright5"><i class="fa fa-pencil-square-o"></i> <?php echo _l('edit'); ?></a>
                                                <?php } ?>
                                                <?php if (has_permission('acc_imprests', '', 'delete') || is_admin()) { ?>
                                                    <a href="<?php echo admin_url('accounting/delete_imprest_retirement/' . $imprest->id); ?>" class="btn btn-danger btn-xs _delete"><i class="fa fa-trash"></i> <?php echo _l('delete'); ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-bordered no-mtop">
                                            <tbody>
                                                <tr>
                                                    <td class="bold" width="35%"><?php echo _l('acc_retire_date'); ?></td>
                                                    <td><?php echo _d($imprest->retire_date); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_actual_spent'); ?></td>
                                                    <td><span class="bold text-warning"><?php echo app_format_money($imprest->amount_retired, $currency_name); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_variance'); ?></td>
                                                    <td>
                                                        <?php 
                                                        $variance = floatval($imprest->variance);
                                                        $variance_class = 'text-primary';
                                                        $variance_desc = _l('acc_variance_exact_match');
                                                        if ($variance > 0) {
                                                            $variance_class = 'text-success';
                                                            $variance_desc = _l('acc_variance_under_spent');
                                                        } elseif ($variance < 0) {
                                                            $variance_class = 'text-danger';
                                                            $variance_desc = _l('acc_variance_over_spent');
                                                        }
                                                        ?>
                                                        <span class="bold <?php echo $variance_class; ?>"><?php echo app_format_money($variance, $currency_name); ?></span>
                                                        <br><small class="text-muted"><?php echo $variance_desc; ?></small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_payment_method'); ?></td>
                                                    <td><?php echo html_escape($payment_method_names[(string) $imprest->retire_payment_method] ?? $imprest->retire_payment_method); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('acc_transaction_id'); ?></td>
                                                    <td><?php echo html_escape($imprest->retire_transaction_id); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="bold"><?php echo _l('notes'); ?></td>
                                                    <td><?php echo nl2br(html_escape($imprest->retire_notes)); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
 
                                        <!-- Retirement Receipts -->
                                        <h5 class="bold text-muted mtop20 mbot15"><i class="fa fa-file-text-o"></i> <?php echo _l('acc_uploaded_receipts_retirement'); ?></h5>
                                        <div class="well well-sm">
                                            <?php 
                                            $retirement_attachments = array_filter($attachments, function($att) { return $att['rel_type'] == 'imprest_retirement'; });
                                            if (empty($retirement_attachments)) {
                                                echo '<p class="text-muted no-margin">' . _l('acc_no_receipts_uploaded') . '</p>';
                                            } else {
                                                foreach ($retirement_attachments as $att) { ?>
                                                    <div class="mbot10">
                                                        <a href="<?php echo admin_url('accounting/download_imprest_file/' . $att['id']); ?>" target="_blank"><i class="fa fa-file"></i> <?php echo html_escape($att['file_name']); ?></a>
                                                        <?php if (has_permission('acc_imprests', '', 'delete') || is_admin()) { ?>
                                                            <a href="<?php echo admin_url('accounting/delete_imprest_attachment/' . $att['id']); ?>" class="text-danger mleft10 _delete" onclick="return confirm('<?php echo html_escape(_l('acc_confirm_delete_receipt')); ?>');"><i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>

                            <!-- Right Column: Accounting Mapping & Ledger Entries & Approval Workflow -->
                            <div class="col-md-6">
                                <?php if(isset($list_approve_status) && count($list_approve_status) > 0 ){ ?>
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                                            <h5 class="bold text-muted no-margin"><i class="fa fa-list-ol"></i> <?php echo _l('pur_approval_infor'); ?></h5>
                                            <div class="approval-actions">
                                                 <?php 
                                                 if(isset($check_appr) && $check_appr && $check_appr != false){
                                                 if(($imprest->status == 'draft' && ($check_approve_status == false || $check_approve_status == 'reject')) || ($imprest->status == 'draft' && isset($appr_setting->approval_type) && $appr_setting->approval_type == 1 && is_array($check_approve_status['staffid']) && count($check_approve_status['staffid']) != count($list_approve_status)) ){ ?>
                                                    <a data-toggle="tooltip" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-success btn-xs" data-placement="top" href="<?php echo admin_url('accounting/submit_imprest_for_approval/' . $imprest->id); ?>"><i class="fa fa-paper-plane"></i> <?php echo _l('send_request_approve_pur'); ?></a>
                                                 <?php } }
                                                 if(isset($check_approve_status['staffid']) && is_array($check_approve_status['staffid'])){
                                                     if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && !in_array(get_staff_user_id(), $get_staff_sign) && $imprest->status == 'pending_approval'){ ?>
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

                                                                             <?php echo form_hidden('approve_rel_id', $imprest->id) ?>
                                                                             <?php echo form_hidden('approve_rel_type', 'imprest') ?>

                                                                          <?php echo form_close(); ?>   
                                                                       </div> 
                                                                    <?php } ?>

                                                                 </li>
                                                                   <li>
                                                                     <div class="row text-right col-md-12 pad_right_0 mbot15" style="margin-left: 0;">
                                                                       <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="approve_request_imprest(<?php echo html_escape($imprest->id); ?>); return false;" class="btn btn-success mright15"><?php echo _l('approve'); ?></a>
                                                                      <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="deny_request_imprest(<?php echo html_escape($imprest->id); ?>); return false;" class="btn btn-warning"><?php echo _l('deny'); ?></a></div>
                                                                   </li>
                                                                </ul>
                                                             </div>
                                                     <?php }
                                                     if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && in_array(get_staff_user_id(), $get_staff_sign) && $imprest->status == 'pending_approval'){ ?>
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
                                                           <?php if (file_exists(ACCOUTING_MODULE_UPLOAD_FOLDER.'/imprests/signature/'.$imprest->id.'/signature_'.$value['id'].'.png')) { ?>
                                                               <img src="<?php echo site_url(ACCOUTING_PATH.'imprests/signature/'.$imprest->id.'/signature_'.$value['id'].'.png'); ?>" class="img_style">
                                                           <?php } elseif (file_exists(ACCOUTING_MODULE_UPLOAD_FOLDER.'/imprests/signature/'.$imprest->id.'/signature_'.$value['id'].'.jpg')) { ?>
                                                               <img src="<?php echo site_url(ACCOUTING_PATH.'imprests/signature/'.$imprest->id.'/signature_'.$value['id'].'.jpg'); ?>" class="img_style">
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
                                                           <strong><?php echo _l('note'); ?>:</strong> <?php echo html_escape($value['note']); ?>
                                                       </div>
                                                   <?php } ?>
                                               </div>
                                           </div>
                                           <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php echo form_open(admin_url('accounting/update_imprest_mapping/' . $imprest->id), ['id' => 'mapping-form']); ?>
                                
                                <!-- Disbursement Entries -->
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h5 class="bold text-primary mbot15"><i class="fa fa-exchange"></i> <?php echo _l('acc_disbursement_ledger_entries'); ?></h5>
                                        <div class="">
                                            <table class="table table-bordered table-striped no-mtop">
                                                <thead>
                                                    <tr>
                                                        <th width="50%"><?php echo _l('account'); ?></th>
                                                        <th><?php echo _l('debit'); ?></th>
                                                        <th><?php echo _l('credit'); ?></th>
                                                        <th><?php echo _l('description'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $disbursed_lines = array_filter($ledger_entries, function($entry) { return $entry['rel_type'] == 'imprest'; });
                                                    if (empty($disbursed_lines)) {
                                                        echo '<tr><td colspan="4" class="text-muted text-center">' . _l('acc_no_ledger_entries_created_yet') . '</td></tr>';
                                                    } else {
                                                        foreach ($disbursed_lines as $entry) { ?>
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

                                <!-- Retirement Entries -->
                                <?php if ($imprest->status != 'draft' && $imprest->status != 'pending_approval' && $imprest->status != 'rejected' && $imprest->status != 'disbursed') { ?>
                                <div class="panel_s mtop20">
                                    <div class="panel-body">
                                        <h5 class="bold text-success mbot15"><i class="fa fa-exchange"></i> <?php echo _l('acc_retirement_ledger_entries'); ?></h5>
                                        <div class="">
                                            <table class="table table-bordered table-striped no-mtop">
                                                <thead>
                                                    <tr>
                                                        <th width="50%"><?php echo _l('account'); ?></th>
                                                        <th><?php echo _l('debit'); ?></th>
                                                        <th><?php echo _l('credit'); ?></th>
                                                        <th><?php echo _l('description'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $retirement_lines = array_filter($ledger_entries, function($entry) { return $entry['rel_type'] == 'imprest_retirement'; });
                                                    if (empty($retirement_lines)) {
                                                        echo '<tr><td colspan="4" class="text-muted text-center">' . _l('acc_no_retirement_entries_created_yet') . '</td></tr>';
                                                    } else {
                                                        foreach ($retirement_lines as $entry) { ?>
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
                                <?php } ?>

                                <?php if (!empty($ledger_entries) && (has_permission('acc_imprests', '', 'edit') || is_admin())) { ?>
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

                  <?php echo form_hidden('approve_rel_id', $imprest->id) ?>
                  <?php echo form_hidden('approve_rel_type', 'imprest') ?>

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
           <button id="sign_button" onclick="sign_request_imprest(<?php echo html_escape($imprest->id); ?>);" data-loading-text="<?php echo _l('wait_text'); ?>" data-original-text="<?php echo _l('e_signature_sign'); ?>" autocomplete="off" class="btn btn-success"><?php echo _l('e_signature_sign'); ?></button>
          </div>

      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?>
</body>
</html>
