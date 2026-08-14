<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('utility_activity_log')); ?></h4>
<div class="clearfix"></div>
<?php
$CI = &get_instance();
$tbl = db_prefix() . 'activity_log';
$CI->db->from($tbl);
$CI->db->where('(description LIKE "%Account Planning%" AND description LIKE "%' . (int)$account->id . '%")', null, false);
$CI->db->order_by('date', 'DESC');
$CI->db->limit(100);
$activities = $CI->db->get()->result_array();
?>
<div class="panel_s">
    <div class="panel-body">
        <?php if (!empty($activities)) { ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo _l('utility_activity_log_dt_date'); ?></th>
                    <th><?php echo _l('utility_activity_log_dt_description'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $act) { ?>
                <tr>
                    <td><?php echo _dt($act['date']); ?></td>
                    <td><?php echo $act['description']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p class="text-muted"><?php echo _l('no_results_found'); ?></p>
        <?php } ?>
    </div>
</div>
