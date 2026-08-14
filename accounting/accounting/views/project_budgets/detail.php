<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php $currency_name = isset($currency) && $currency ? $currency->name : ''; ?>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Header with Action Buttons -->
                <div class="mbot20 clearfix">
                    <div class="pull-left">
                        <h4 class="project-budget-title">
                            <?php echo _l('project_budget_details'); ?>: <?php echo html_escape($project->name); ?>
                        </h4>
                        <?php
                            $status_class = 'default';
                            if ($budget->status == 'approved') {
                                $status_class = 'success';
                            } elseif ($budget->status == 'pending_approval') {
                                $status_class = 'warning';
                            } elseif ($budget->status == 'rejected') {
                                $status_class = 'danger';
                            }
                        ?>
                        <span class="label label-<?php echo $status_class; ?> mtop5">
                            <?php echo _l('status'); ?>: <?php 
                                $status_lang_key = 'acc_' . $budget->status;
                                echo html_escape(_l($status_lang_key, ucfirst(str_replace('_', ' ', $budget->status)))); 
                            ?>
                        </span>
                        <?php if (!empty($budget->start_date) || !empty($budget->end_date)) { ?>
                            <span class="text-muted mleft10 budget-date-badge">
                                <i class="fa fa-calendar"></i> 
                                <?php 
                                    $s_date = !empty($budget->start_date) ? _d($budget->start_date) : 'N/A';
                                    $e_date = !empty($budget->end_date) ? _d($budget->end_date) : 'N/A';
                                    echo html_escape($s_date . ' - ' . $e_date);
                                ?>
                            </span>
                        <?php } ?>
                    </div>
                    <div class="pull-right budget-header-right">
                        <a href="<?php echo admin_url('accounting/project_budgets'); ?>" class="btn btn-custom btn-custom-default budget-header-btn">
                            <i class="fa fa-arrow-left"></i> <?php echo _l('back_to_list'); ?>
                        </a>
                        
                        <?php
                        $approver_id = get_option('acc_budget_approver_id');
                        $is_approver = (is_admin() || get_staff_user_id() == $approver_id);
                        $can_edit = (has_permission('acc_project_budgets', '', 'edit') || is_admin());
                        
                        if ($budget->status == 'draft' || $budget->status == 'rejected') {
                            if ($can_edit) { ?>
                                <a href="<?php echo admin_url('accounting/submit_project_budget_for_approval/' . $budget->id); ?>" class="btn btn-custom btn-custom-warning budget-header-btn">
                                    <i class="fa fa-paper-plane"></i> <?php echo _l('submit_for_approval'); ?>
                                </a>
                            <?php }
                        } elseif ($budget->status == 'pending_approval') {
                            if (!(isset($list_approve_status) && count($list_approve_status) > 0)) {
                                if ($is_approver) { ?>
                                    <a href="<?php echo admin_url('accounting/change_project_budget_status/' . $budget->id . '/approved'); ?>" class="btn btn-custom btn-custom-success budget-header-btn">
                                        <i class="fa fa-check"></i> <?php echo _l('acc_approve'); ?>
                                    </a>
                                    <a href="<?php echo admin_url('accounting/change_project_budget_status/' . $budget->id . '/rejected'); ?>" class="btn btn-custom btn-custom-danger budget-header-btn">
                                        <i class="fa fa-times"></i> <?php echo _l('acc_reject'); ?>
                                    </a>
                                <?php } else { ?>
                                    <span class="text-warning bold mright15 budget-pending-status">
                                        <i class="fa fa-hourglass-half"></i> <?php echo _l('acc_pending_approval'); ?>
                                    </span>
                                <?php }
                            } else { ?>
                                <span class="text-warning bold mright15 budget-pending-status">
                                    <i class="fa fa-hourglass-half"></i> <?php echo _l('acc_pending_approval'); ?>
                                </span>
                            <?php }
                        } elseif ($budget->status == 'approved') {
                            if ($is_approver) { ?>
                                <a href="<?php echo admin_url('accounting/change_project_budget_status/' . $budget->id . '/rejected'); ?>" class="btn btn-custom btn-custom-danger budget-header-btn" onclick="return confirm('<?php echo _l('acc_confirm_void_approved_budget'); ?>');">
                                    <i class="fa fa-times"></i> <?php echo _l('void_reject'); ?>
                                </a>
                                <a href="<?php echo admin_url('accounting/change_project_budget_status/' . $budget->id . '/draft'); ?>" class="btn btn-custom btn-custom-default budget-header-btn">
                                    <i class="fa fa-undo"></i> <?php echo _l('reset_to_draft'); ?>
                                </a>
                            <?php }
                        }
                        ?>
 
                        <button onclick="window.print();" class="btn btn-custom btn-custom-default budget-header-btn">
                            <i class="fa fa-print"></i> <?php echo _l('print_report_pdf'); ?>
                        </button>
                        <a href="<?php echo admin_url('accounting/export_project_budget_excel/' . $budget->id); ?>" class="btn btn-custom btn-custom-primary budget-header-btn">
                            <i class="fa fa-file-excel-o"></i> <?php echo _l('export_to_excel'); ?>
                        </a>
                    </div>
                </div>

                <!-- Metric Cards Row -->
                <div class="row metric-cards-row">
                    <!-- Total Budget -->
                    <div class="col-md-3 col-sm-6">
                        <div class="metric-card metric-blue">
                            <div class="metric-label"><?php echo _l('budget_amount'); ?></div>
                            <div class="metric-value"><?php echo app_format_money($total['budget_amount'], $currency_name); ?></div>
                            <i class="fa fa-calculator card-icon text-primary"></i>
                        </div>
                    </div>
                    <!-- Allocated / Committed -->
                    <div class="col-md-3 col-sm-6">
                        <div class="metric-card metric-warning">
                            <div class="metric-label"><?php echo _l('allocated_committed'); ?></div>
                            <div class="metric-value"><?php echo app_format_money($total['allocated'], $currency_name); ?></div>
                            <i class="fa fa-clock-o card-icon text-warning"></i>
                        </div>
                    </div>
                    <!-- Actual Spent -->
                    <div class="col-md-3 col-sm-6">
                        <div class="metric-card metric-success">
                            <div class="metric-label"><?php echo _l('actual_spent'); ?></div>
                            <div class="metric-value"><?php echo app_format_money($total['spent'], $currency_name); ?></div>
                            <i class="fa fa-credit-card card-icon text-success"></i>
                        </div>
                    </div>
                    <!-- Remaining -->
                    <div class="col-md-3 col-sm-6">
                        <?php 
                            $remaining_class = $total['remaining'] >= 0 ? 'metric-success' : 'metric-danger';
                            $remaining_icon_class = $total['remaining'] >= 0 ? 'text-success' : 'text-danger';
                        ?>
                        <div class="metric-card <?php echo $remaining_class; ?>">
                            <div class="metric-label"><?php echo _l('remaining_budget'); ?></div>
                            <div class="metric-value"><?php echo app_format_money($total['remaining'], $currency_name); ?></div>
                            <i class="fa fa-money card-icon <?php echo $remaining_icon_class; ?>"></i>
                        </div>
                    </div>
                </div>
 
                <div class="row">
                    <!-- Left: Category Breakdown -->
                    <div class="<?php echo (isset($list_approve_status) && count($list_approve_status) > 0) ? 'col-md-8' : 'col-md-12'; ?>">
                        <div class="panel-custom">
                            <div class="panel-custom-header">
                                <h3 class="panel-custom-title">
                                    <i class="fa fa-table"></i> <?php echo _l('budget_breakdown_by_category'); ?>
                                </h3>
                            </div>
                            <div class="panel-custom-body table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('budget_category'); ?></th>
                                            <th class="text-right"><?php echo _l('budget_amount'); ?></th>
                                            <th class="text-right"><?php echo _l('allocated_committed'); ?></th>
                                            <th class="text-right"><?php echo _l('actual_spent'); ?></th>
                                            <th class="text-right"><?php echo _l('remaining_budget'); ?></th>
                                            <th class="text-right th-usage-width"><?php echo _l('usage_percentage'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $cat) { ?>
                                            <tr>
                                                <td class="bold"><?php echo html_escape($cat['category_name']); ?></td>
                                                <td class="text-right bold text-primary"><?php echo app_format_money($cat['budget_amount'], $currency_name); ?></td>
                                                <td class="text-right text-warning"><?php echo app_format_money($cat['allocated'], $currency_name); ?></td>
                                                <td class="text-right text-success"><?php echo app_format_money($cat['spent'], $currency_name); ?></td>
                                                <?php 
                                                    $rem_class = $cat['remaining'] >= 0 ? 'text-success' : 'text-danger';
                                                ?>
                                                <td class="text-right bold <?php echo $rem_class; ?>">
                                                    <?php echo app_format_money($cat['remaining'], $currency_name); ?>
                                                </td>
                                                <td class="text-right usage-percent-cell">
                                                    <?php 
                                                        $pct = floatval($cat['percent_used']);
                                                        $bar_class = 'progress-bar-success';
                                                        $pct_class = 'progress-percent-success';
                                                        if ($pct > 70 && $pct <= 90) {
                                                            $bar_class = 'progress-bar-warning';
                                                            $pct_class = 'progress-percent-warning';
                                                        } elseif ($pct > 90) {
                                                            $bar_class = 'progress-bar-danger';
                                                            $pct_class = 'progress-percent-danger';
                                                        }
                                                        // Cap width at 100 for display
                                                        $width_pct = min($pct, 100);
                                                    ?>
                                                    <div class="progress">
                                                        <div class="progress-bar <?php echo $bar_class; ?> no-percent-text" role="progressbar" data-percent="<?php echo number_format(min($pct, 100), 1); ?>" style="width: <?php echo $width_pct; ?>%"></div>
                                                    </div>
                                                    <span class="progress-percent <?php echo $pct_class; ?> text-right">
                                                        <?php echo number_format($pct, 1); ?>%
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        
                                         <!-- Total Row -->
                                        <tr class="total-row">
                                            <td><?php echo _l('total'); ?></td>
                                            <td class="text-right"><?php echo app_format_money($total['budget_amount'], $currency_name); ?></td>
                                            <td class="text-right"><?php echo app_format_money($total['allocated'], $currency_name); ?></td>
                                            <td class="text-right"><?php echo app_format_money($total['spent'], $currency_name); ?></td>
                                            <td class="text-right <?php echo $total['remaining'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                <?php echo app_format_money($total['remaining'], $currency_name); ?>
                                            </td>
                                            <td class="text-right usage-percent-cell">
                                                <?php 
                                                    $total_pct = floatval($total['percent_used']);
                                                    $total_bar_class = 'progress-bar-success';
                                                    $total_pct_class = 'progress-percent-success';
                                                    if ($total_pct > 70 && $total_pct <= 90) {
                                                        $total_bar_class = 'progress-bar-warning';
                                                        $total_pct_class = 'progress-percent-warning';
                                                    } elseif ($total_pct > 90) {
                                                        $total_bar_class = 'progress-bar-danger';
                                                        $total_pct_class = 'progress-percent-danger';
                                                    }
                                                    $total_width_pct = min($total_pct, 100);
                                                ?>
                                                <div class="progress">
                                                    <div class="progress-bar <?php echo $total_bar_class; ?> no-percent-text" role="progressbar" data-percent="<?php echo number_format(min($total_pct, 100), 1); ?>" style="width: <?php echo $total_width_pct; ?>%"></div>
                                                </div>
                                                <span class="progress-percent <?php echo $total_pct_class; ?> text-right">
                                                    <?php echo number_format($total_pct, 1); ?>%
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($list_approve_status) && count($list_approve_status) > 0) { ?>
                    <!-- Right: Approval Workflow Timeline -->
                    <div class="col-md-4">
                        <div class="panel-custom">
                            <div class="panel-custom-header panel-flex-header">
                                <h3 class="panel-custom-title margin-0">
                                    <i class="fa fa-list-ol"></i> <?php echo _l('pur_approval_infor'); ?>
                                </h3>
                                <div class="approval-actions">
                                     <?php 
                                     if(isset($check_appr) && $check_appr && $check_appr != false){
                                     if(($budget->status == 'draft' && ($check_approve_status == false || $check_approve_status == 'reject')) || ($budget->status == 'draft' && isset($appr_setting->approval_type) && $appr_setting->approval_type == 1 && is_array($check_approve_status['staffid']) && count($check_approve_status['staffid']) != count($list_approve_status)) ){ ?>
                                        <a data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-success btn-xs" href="<?php echo admin_url('accounting/submit_project_budget_for_approval/' . $budget->id); ?>"><i class="fa fa-paper-plane"></i> <?php echo _l('send_request_approve_pur'); ?></a>
                                     <?php } }
                                     if(isset($check_approve_status['staffid']) && is_array($check_approve_status['staffid'])){
                                         if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && !in_array(get_staff_user_id(), $get_staff_sign) && $budget->status == 'pending_approval'){ ?>
                                             <div class="btn-group" >
                                                    <a href="#" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('approve'); ?> <span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right z-9999 min-width-250 padding-15">
                                                     <li>
                                                       <div class="col-md-12 no-left-right-padding">
                                                         <?php echo render_textarea('reason', 'reason'); ?>
                                                       </div>

                                                       <?php if(get_option('allow_upload_esign_for_approve_type') == 1){ ?>
                                                           <div class="col-md-12 mbot15 no-left-right-padding">
                                                              <?php echo form_open_multipart(admin_url('accounting/sign_attachment'),array('id'=>'sign_attachment-form')); ?>

                                                                 <label for="sign_attachment_file"><?php echo _l('e_sign'); ?></label>
                                                                 <input type="file" id="sign_attachment_file" accept=".png, .jpg" name="sign_attachment" class="form-control">

                                                                 <?php echo form_hidden('approve_rel_id', $budget->id) ?>
                                                                 <?php echo form_hidden('approve_rel_type', 'project_budget') ?>

                                                              <?php echo form_close(); ?>   
                                                           </div> 
                                                       <?php } ?>

                                                     </li>
                                                       <li>
                                                         <div class="row text-right col-md-12 pad_right_0 mbot15" style="margin-left: 0;">
                                                           <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="approve_request_project_budget(<?php echo html_escape($budget->id); ?>); return false;" class="btn btn-success mright15"><?php echo _l('approve'); ?></a>
                                                          <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="deny_request_project_budget(<?php echo html_escape($budget->id); ?>); return false;" class="btn btn-warning"><?php echo _l('deny'); ?></a></div>
                                                       </li>
                                                    </ul>
                                                 </div>
                                         <?php }
                                         if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && in_array(get_staff_user_id(), $get_staff_sign) && $budget->status == 'pending_approval'){ ?>
                                             <button onclick="accept_action();" class="btn btn-success btn-xs"><?php echo _l('e_signature_sign'); ?></button>
                                         <?php }
                                     }
                                     ?>
                                </div>
                            </div>
                            <div class="panel-custom-body">
                                <div class="approval-timeline">
                                  <?php 
                                   $this->load->model('staff_model');
                                   foreach ($list_approve_status as $value) {
                                     $value['staffid'] = explode(', ', $value['staffid'] ?? '');
                                     
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
                                     }
                                   ?>
                                   <div class="approval-step <?php echo html_escape($step_status_class); ?>">
                                       <div class="approval-icon-indicator"></div>
                                       <div class="approval-content">
                                           <h6 class="margin-0 font-weight-600">
                                               <?php echo html_escape($staff_name); ?>
                                               <?php if ($value['action'] == 'sign') { ?>
                                                   <span class="label label-info mleft5 signer-label-size"><?php echo _l('acc_signer'); ?></span>
                                               <?php } else { ?>
                                                   <span class="label label-default mleft5 signer-label-size"><?php echo _l('acc_approver'); ?></span>
                                               <?php } ?>
                                           </h6>
                                           <?php 
                                           $staff_position = '';
                                           if (function_exists('pur_get_job_position')) {
                                               $staff_position = pur_get_job_position($value['staffid'][0]);
                                           }
                                           if (!empty($staff_position)) { ?>
                                               <span class="position display-block-desc"><?php echo html_escape($staff_position); ?></span>
                                           <?php } ?>
                                           
                                           <?php if ($value['approve'] == 2) { ?>
                                               <div class="signature-container">
                                                   <?php if (file_exists(ACCOUTING_MODULE_UPLOAD_FOLDER.'/project_budgets/signature/'.$budget->id.'/signature_'.$value['id'].'.png')) { ?>
                                                       <img src="<?php echo site_url(ACCOUTING_PATH.'project_budgets/signature/'.$budget->id.'/signature_'.$value['id'].'.png'); ?>" class="max-width-150-img">
                                                   <?php } elseif (file_exists(ACCOUTING_MODULE_UPLOAD_FOLDER.'/project_budgets/signature/'.$budget->id.'/signature_'.$value['id'].'.jpg')) { ?>
                                                       <img src="<?php echo site_url(ACCOUTING_PATH.'project_budgets/signature/'.$budget->id.'/signature_'.$value['id'].'.jpg'); ?>" class="max-width-150-img">
                                                   <?php } else { ?>
                                                       <img src="<?php echo site_url(ACCOUTING_PATH.'approval/approved.png'); ?>" class="width-80-img">
                                                   <?php } ?>
                                               </div>
                                               <p class="approval-date bold text-success step-status-date-margin">
                                                   <i class="fa fa-check-circle"></i> <?php echo html_escape(_l('signed') . ' - ' . _dt($value['date'])); ?>
                                               </p>
                                           <?php } elseif ($value['approve'] == 3) { ?>
                                               <div class="signature-container">
                                                   <img src="<?php echo site_url(ACCOUTING_PATH.'approval/rejected.png'); ?>" class="width-80-img">
                                               </div>
                                               <p class="approval-date bold text-danger step-status-date-margin">
                                                   <i class="fa fa-times-circle"></i> <?php echo html_escape(_l('rejected') . ' - ' . _dt($value['date'])); ?>
                                               </p>
                                           <?php } else { ?>
                                               <p class="approval-date text-warning step-status-date-margin">
                                                   <i class="fa fa-clock-o"></i> <?php echo _l('acc_waiting_for_approval'); ?>
                                               </p>
                                           <?php } ?>
                                           
                                           <?php if (!empty($value['note'])) { ?>
                                               <div class="approval-note step-note-padding">
                                                   <strong><?php echo _l('acc_note'); ?>:</strong> <?php echo html_escape($value['note']); ?>
                                               </div>
                                           <?php } ?>
                                       </div>
                                   </div>
                                   <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Transaction Log Panel -->
                <div class="panel-custom project-budget-transaction-log-panel">
                    <div class="panel-custom-header">
                        <h3 class="panel-custom-title">
                            <i class="fa fa-list"></i> <?php echo _l('detailed_transaction_logs'); ?>
                        </h3>
                    </div>
                    <div class="panel-custom-body table-responsive">
                        <?php if (empty($transactions)) { ?>
                            <p class="text-muted text-center no-transactions-padding"><?php echo _l('no_transactions_mapped'); ?></p>
                        <?php } else { ?>
                            <?php
                                $can_approve_budget_transactions = (get_staff_user_id() == get_option('acc_budget_approver_id'));
                                $transaction_types = [];
                                $transaction_categories = [];
                                foreach ($transactions as $t_filter) {
                                    $transaction_types[$t_filter['type']] = $t_filter['type'];
                                    $transaction_categories[$t_filter['category']] = $t_filter['category'];
                                }
                                asort($transaction_types);
                                asort($transaction_categories);
                            ?>
                            <div class="row mbot15 transaction-log-filters">
                                <div class="col-md-3">
                                    <?php echo render_date_input('pb_transaction_from_date', 'from_date', '', ['id' => 'pb_transaction_from_date']); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo render_date_input('pb_transaction_to_date', 'to_date', '', ['id' => 'pb_transaction_to_date']); ?>
                                </div>
                                <div class="col-md-3">
                                    <label for="pb_transaction_type" class="control-label"><?php echo _l('transaction_type'); ?></label>
                                    <select id="pb_transaction_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""></option>
                                        <?php foreach ($transaction_types as $type) { ?>
                                            <option value="<?php echo html_escape($type); ?>"><?php echo html_escape($type); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="pb_transaction_category" class="control-label"><?php echo _l('budget_category'); ?></label>
                                    <select id="pb_transaction_category" class="selectpicker" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""></option>
                                        <?php foreach ($transaction_categories as $category) { ?>
                                            <option value="<?php echo html_escape($category); ?>"><?php echo html_escape($category); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mbot15">
                                <button type="button" class="btn btn-default btn-sm" id="pb_transaction_filter_clear">
                                    <i class="fa fa-refresh"></i> <?php echo _l('clear'); ?>
                                </button>
                            </div>
                            <table class="table custom-table transaction-logs-table">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('transaction_type'); ?></th>
                                        <th><?php echo _l('reference_no'); ?></th>
                                        <th><?php echo _l('date'); ?></th>
                                        <th><?php echo _l('budget_category'); ?></th>
                                        <th class="text-right"><?php echo _l('amount'); ?></th>
                                        <th><?php echo _l('status'); ?></th>
                                        <th class="text-right"><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $t) { ?>
                                        <tr data-created-date="<?php echo html_escape($t['created_date'] ?? $t['date']); ?>" data-transaction-type="<?php echo html_escape($t['type']); ?>" data-budget-category="<?php echo html_escape($t['category']); ?>">
                                            <td>
                                                <span class="bold"><?php echo html_escape($t['type']); ?></span>
                                            </td>
                                            <td><?php echo html_escape($t['ref_no']); ?></td>
                                            <td><?php echo _d($t['date']); ?></td>
                                            <td><?php echo html_escape($t['category']); ?></td>
                                            <td class="text-right bold"><?php echo app_format_money($t['amount'], $currency_name); ?></td>
                                            <td>
                                                <?php 
                                                    $status_class = 'default';
                                                    $stat = strtolower($t['status']);
                                                    if (in_array($stat, ['approved', 'completed', 'paid'])) {
                                                        $status_class = 'success';
                                                    } elseif (in_array($stat, ['draft', 'pending', 'disbursed', 'pending_payment', 'pending_refund'])) {
                                                        $status_class = 'warning';
                                                    } elseif (in_array($stat, ['rejected', 'cancelled'])) {
                                                        $status_class = 'danger';
                                                    }
                                                ?>
                                                <span class="label label-<?php echo $status_class; ?>">
                                                    <?php echo _l($t['status']); ?>
                                                </span>
                                                <?php
                                                    $budget_approval_status = isset($t['budget_approval_status']) ? $t['budget_approval_status'] : 'approved';
                                                    if ($budget_approval_status == 'pending') {
                                                        echo ' <span class="label label-warning">Budget Pending</span>';
                                                    } elseif ($budget_approval_status == 'rejected') {
                                                        echo ' <span class="label label-danger">Budget Rejected</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td class="text-right">
                                                <?php if (isset($t['budget_approval_status']) && $t['budget_approval_status'] == 'pending' && $can_approve_budget_transactions) { ?>
                                                    <a href="<?php echo admin_url('accounting/change_budget_transaction_approval/' . $t['rel_type'] . '/' . $t['rel_id'] . '/approved'); ?>" class="btn btn-success btn-icon" title="<?php echo _l('approve'); ?>">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('accounting/change_budget_transaction_approval/' . $t['rel_type'] . '/' . $t['rel_id'] . '/rejected'); ?>" class="btn btn-danger btn-icon" title="<?php echo _l('reject'); ?>">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                <?php } ?>
                                                <a href="<?php echo html_escape($t['link']); ?>" class="btn btn-default btn-icon" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr class="transaction-log-empty-filter hide">
                                        <td colspan="7" class="text-center text-muted"><?php echo _l('no_transactions_mapped'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>

                <!-- Footer / Budget Description -->
                <?php if (!empty($budget->description)) { ?>
                    <div class="panel-custom budget-desc-panel">
                        <h4 class="bold budget-desc-title"><?php echo _l('description_notes'); ?></h4>
                        <p class="text-muted budget-desc-content">
                            <?php echo nl2br(html_escape($budget->description)); ?>
                        </p>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_action" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <p class="bold" id="signatureLabel"><?php echo html_escape(_l('signature')); ?></p>
          <div class="form-group">
              <div class="radio radio-primary radio-inline">
                  <input type="radio" id="sign" name="sign_type" value="1" checked>
                  <label for="sign">
                     <?php echo html_escape(_l('sign')); ?>      
                   </label>
              </div>
              <div class="radio radio-primary radio-inline">
                  <input type="radio" id="upload" name="sign_type" value="0" >
                  <label for="upload">
                      <?php echo html_escape(_l('pur_upload')); ?>    
                   </label>
              </div>
          </div>

          <div id="upload_sign" class="mbot15 hide">
             <?php echo form_open_multipart(admin_url('accounting/sign_attachment'),array('id'=>'sign_attachment-form')); ?>
                <input type="file" id="sign_attachment_file" accept=".png, .jpg" name="sign_attachment" class="form-control">
                <?php echo form_hidden('approve_rel_id', $budget->id) ?>
                <?php echo form_hidden('approve_rel_type', 'project_budget') ?>
             <?php echo form_close(); ?>    
          </div>

          <div id="sign_pad" >
             <div class="signature-pad--body">
               <canvas id="signature" height="130" width="550"></canvas>
             </div>
             <input type="text" class="ip_style" tabindex="-1" name="signature" id="signatureInput">
             <div class="dispay-block">
               <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" onclick="signature_clear();"><?php echo html_escape(_l('clear')); ?></button>
             </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_escape(_l('close')); ?></button>
          <button id="sign_button" onclick="sign_request_project_budget(<?php echo html_escape($budget->id); ?>);" data-loading-text="<?php echo html_escape(_l('wait_text')); ?>" data-original-text="<?php echo html_escape(_l('e_signature_sign')); ?>" autocomplete="off" class="btn btn-success"><?php echo html_escape(_l('e_signature_sign')); ?></button>
        </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?>
</body>
</html>
