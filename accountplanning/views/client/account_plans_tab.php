<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('accountplanning')); ?></h4>
<?php if (isset($client) && has_permission('accountplanning', '', 'view')) { ?>
<?php if (has_permission('accountplanning', '', 'create')) { ?>
<a href="<?php echo admin_url('accountplanning/new_account?client_id=' . (int)$client->userid); ?>" class="btn btn-primary mbot15">
    <i class="fa fa-plus"></i> <?php echo _l('new_account'); ?>
</a>
<?php } ?>
<?php
$CI = &get_instance();
$CI->load->model('accountplanning/accountplanning_model');
$plans = $CI->accountplanning_model->get('', ['client_id' => $client->userid]);
?>
<?php if (empty($plans)) { ?>
<p class="text-muted"><?php echo _l('ap_no_plans_visible'); ?></p>
<?php } else { ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo _l('id'); ?></th>
            <th><?php echo _l('subject'); ?></th>
            <th><?php echo _l('plan_status'); ?></th>
            <th><?php echo _l('date'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($plans as $p) {
            $status_key = isset($p['status']) ? $p['status'] : 'draft';
            $status_label = _l('ap_status_' . $status_key);
        ?>
        <tr>
            <td>#<?php echo (int)$p['id']; ?></td>
            <td><?php echo htmlspecialchars($p['subject'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($status_label); ?></td>
            <td><?php echo $p['date'] ? _d($p['date']) : '-'; ?></td>
            <td><a href="<?php echo admin_url('accountplanning/view/' . $p['id']); ?>" class="btn btn-default btn-sm"><?php echo _l('view'); ?></a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>
<?php } else { ?>
<p class="text-muted"><?php echo _l('access_denied'); ?></p>
<?php } ?>
