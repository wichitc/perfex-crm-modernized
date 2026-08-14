<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="bold"><?php echo _l('ap_compare_plans'); ?></h4>
                <div class="row mbot15">
                    <div class="col-md-6">
                        <strong><?php echo _l('ap_select_plan_a'); ?>:</strong> #<?php echo $plan_a['id']; ?> - <?php echo htmlspecialchars($plan_a['subject'] ?? ''); ?> (<?php echo _d($plan_a['date'] ?? ''); ?>)
                    </div>
                    <div class="col-md-6">
                        <form method="get" class="form-inline">
                            <input type="hidden" name="group" value="<?php echo $this->input->get('group'); ?>">
                            <strong><?php echo _l('ap_select_plan_b'); ?>:</strong>
                            <select name="plan_b" class="form-control mleft5" onchange="this.form.submit()">
                                <option value="">-- <?php echo _l('dropdown_non_selected_tex'); ?> --</option>
                                <?php foreach ($other_plans as $op) { ?>
                                <option value="<?php echo $op['id']; ?>" <?php echo ($plan_b_id ?? '') == $op['id'] ? 'selected' : ''; ?>>
                                    #<?php echo $op['id']; ?> - <?php echo htmlspecialchars($op['subject'] ?? ''); ?> (<?php echo _d($op['date'] ?? ''); ?>)
                                </option>
                                <?php } ?>
                            </select>
                        </form>
                    </div>
                </div>
                <?php if (!empty($plan_b)) { ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="bold"><?php echo _l('ap_select_plan_a'); ?></h5>
                                <table class="table table-condensed">
                                    <tr><th><?php echo _l('subject'); ?></th><td><?php echo htmlspecialchars($plan_a['subject'] ?? '-'); ?></td></tr>
                                    <tr><th><?php echo _l('date'); ?></th><td><?php echo _d($plan_a['date'] ?? '-'); ?></td></tr>
                                    <tr><th><?php echo _l('plan_status'); ?></th><td><?php echo _l('ap_status_' . ($plan_a['status'] ?? 'draft')); ?></td></tr>
                                    <tr><th><?php echo _l('revenue_next_year', ''); ?></th><td><?php echo app_format_money($plan_a['revenue_next_year'] ?? 0, get_base_currency()); ?></td></tr>
                                    <tr><th><?php echo _l('client_status'); ?></th><td><?php echo htmlspecialchars($plan_a['client_status'] ?? '-'); ?></td></tr>
                                    <tr><th><?php echo _l('bcg_model'); ?></th><td><?php echo htmlspecialchars($plan_a['bcg_model'] ?? '-'); ?></td></tr>
                                </table>
                                <?php if (!empty($plan_a['objectives'])) { ?>
                                <p><strong><?php echo _l('objective'); ?>:</strong></p>
                                <div class="well"><?php echo $plan_a['objectives']; ?></div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <h5 class="bold"><?php echo _l('ap_select_plan_b'); ?></h5>
                                <table class="table table-condensed">
                                    <tr><th><?php echo _l('subject'); ?></th><td><?php echo htmlspecialchars($plan_b['subject'] ?? '-'); ?></td></tr>
                                    <tr><th><?php echo _l('date'); ?></th><td><?php echo _d($plan_b['date'] ?? '-'); ?></td></tr>
                                    <tr><th><?php echo _l('plan_status'); ?></th><td><?php echo _l('ap_status_' . ($plan_b['status'] ?? 'draft')); ?></td></tr>
                                    <tr><th><?php echo _l('revenue_next_year', ''); ?></th><td><?php echo app_format_money($plan_b['revenue_next_year'] ?? 0, get_base_currency()); ?></td></tr>
                                    <tr><th><?php echo _l('client_status'); ?></th><td><?php echo htmlspecialchars($plan_b['client_status'] ?? '-'); ?></td></tr>
                                    <tr><th><?php echo _l('bcg_model'); ?></th><td><?php echo htmlspecialchars($plan_b['bcg_model'] ?? '-'); ?></td></tr>
                                </table>
                                <?php if (!empty($plan_b['objectives'])) { ?>
                                <p><strong><?php echo _l('objective'); ?>:</strong></p>
                                <div class="well"><?php echo $plan_b['objectives']; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <a href="<?php echo admin_url('accountplanning/view/' . $plan_a['id']); ?>" class="btn btn-default"><?php echo _l('back'); ?></a>
                    </div>
                </div>
                <?php } else { ?>
                <div class="alert alert-info"><?php echo _l('ap_select_plan_b'); ?></div>
                <a href="<?php echo admin_url('accountplanning/view/' . $plan_a['id']); ?>" class="btn btn-default"><?php echo _l('back'); ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
