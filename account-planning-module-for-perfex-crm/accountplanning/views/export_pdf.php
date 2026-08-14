<style>
table { border-collapse: collapse; width: 100%; margin-bottom: 15px; }
th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
th { background: #f5f5f5; }
h2 { margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
</style>
<h1><?php echo htmlspecialchars(_l('accountplanning') . ' - ' . $account->subject); ?></h1>
<p><strong><?php echo _l('client_name'); ?>:</strong> <?php echo htmlspecialchars($account->client_name ?? $account->company ?? ''); ?></p>
<p><strong><?php echo _l('time'); ?>:</strong> <?php echo $account->date ? _d($account->date) : '-'; ?></p>
<p><strong><?php echo _l('plan_status'); ?>:</strong> <?php echo isset($account->status) ? _l('ap_status_' . $account->status) : _l('ap_status_draft'); ?></p>

<h2><?php echo _l('due_diligence'); ?></h2>
<p><strong><?php echo _l('vision'); ?>:</strong> <?php echo htmlspecialchars($account->vision ?? ''); ?></p>
<p><strong><?php echo _l('mission'); ?>:</strong> <?php echo htmlspecialchars($account->mission ?? ''); ?></p>
<p><strong><?php echo _l('product'); ?>:</strong> <?php echo htmlspecialchars($account->product ?? ''); ?></p>

<h2><?php echo _l('new_account_b'); ?></h2>
<?php if (!empty($financial)) { ?>
<table>
<tr><th><?php echo _l('year'); ?></th><th><?php echo _l('revenue'); ?></th><th><?php echo _l('traffic'); ?></th><th><?php echo _l('sales_spent'); ?></th><th><?php echo _l('loss'); ?></th></tr>
<?php foreach ($financial as $row) { ?>
<tr><td><?php echo htmlspecialchars($row['year'] ?? ''); ?></td><td><?php echo htmlspecialchars($row['revenue'] ?? ''); ?></td><td><?php echo htmlspecialchars($row['traffic'] ?? ''); ?></td><td><?php echo htmlspecialchars($row['sales_spent'] ?? ''); ?></td><td><?php echo htmlspecialchars($row['loss'] ?? ''); ?></td></tr>
<?php } ?>
</table>
<?php } ?>

<h2><?php echo _l('planning'); ?></h2>
<p><strong><?php echo _l('planning_a'); ?>:</strong> <?php echo $account->objectives ? strip_tags($account->objectives) : '-'; ?></p>
<p><strong><?php echo _l('revenue_next_year'); ?>:</strong> <?php echo $account->revenue_next_year ? app_format_money($account->revenue_next_year, get_base_currency()) : '-'; ?></p>
<p><strong><?php echo _l('client_status'); ?>:</strong> <?php echo htmlspecialchars($account->client_status ?? '-'); ?></p>
<p><strong><?php echo _l('bcg_model'); ?>:</strong> <?php echo htmlspecialchars($account->bcg_model ?? '-'); ?></p>

<h2><?php echo _l('to_do_list'); ?></h2>
<?php if (!empty($todo_list) && is_array($todo_list)) { ?>
<table>
<tr><th><?php echo _l('objective'); ?></th><th><?php echo _l('action_needed'); ?></th><th><?php echo _l('prioritization'); ?></th><th><?php echo _l('deadline'); ?></th><th><?php echo _l('status'); ?></th></tr>
<?php foreach ($todo_list as $row) { if (isset($row['button'])) continue; ?>
<tr><td><?php echo htmlspecialchars($row['objective'] ?? ''); ?></td><td><?php echo htmlspecialchars($row['action_needed'] ?? $row['item'] ?? ''); ?></td><td><?php echo htmlspecialchars($row['prioritization'] ?? ''); ?></td><td><?php echo htmlspecialchars($row['deadline'] ?? ''); ?></td><td><?php echo htmlspecialchars($row['status'] ?? ''); ?></td></tr>
<?php } ?>
</table>
<?php } ?>
