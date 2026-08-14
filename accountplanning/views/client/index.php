<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="accountplanning-client-content">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="bold"><?php echo htmlspecialchars(_l('accountplanning')); ?></h4>
                <p class="text-muted"><?php echo _l('ap_client_portal_intro'); ?></p>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (empty($plans)) { ?>
                        <p class="text-muted"><?php echo _l('ap_no_plans_visible'); ?></p>
                        <?php } else { ?>
                        <table class="table table-striped ap-client-plans-table">
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
                                    <td data-label="<?php echo _l('id'); ?>">#<?php echo $p['id']; ?></td>
                                    <td data-label="<?php echo _l('subject'); ?>"><?php echo htmlspecialchars($p['subject']); ?></td>
                                    <td data-label="<?php echo _l('plan_status'); ?>"><?php echo htmlspecialchars($status_label); ?></td>
                                    <td data-label="<?php echo _l('date'); ?>"><?php echo _d($p['date']); ?></td>
                                    <td data-label=""><a href="<?php echo site_url('accountplanning/client/view_plan/' . $p['id']); ?>" class="btn btn-default btn-sm"><?php echo _l('view'); ?></a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
