<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="accountplanning-client-content">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="bold"><?php echo htmlspecialchars($account->subject); ?></h4>
                <p class="text-muted"><?php echo _l('plan_status'); ?>: <?php echo _l('ap_status_' . (isset($account->status) ? $account->status : 'draft')); ?></p>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (!empty($account->objectives)) { ?>
                        <h5 class="bold"><?php echo _l('objective'); ?></h5>
                        <div class="mbot15"><?php echo $account->objectives; ?></div>
                        <?php } ?>
                        <?php if (!empty($objectives)) { ?>
                        <h5 class="bold"><?php echo _l('planning_a'); ?></h5>
                        <ul class="list-unstyled">
                            <?php foreach ($objectives as $obj) { ?>
                            <li><i class="fa fa-check-circle text-success"></i> <?php echo htmlspecialchars($obj['name']); ?></li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                        <?php if (!empty($todo_list) && isset($todo_list[0]['action_needed']) && $todo_list[0]['action_needed'] !== '') { ?>
                        <h5 class="bold mtop15"><?php echo _l('to_do_list'); ?></h5>
                        <table class="table table-striped ap-client-todo-table">
                            <thead>
                                <tr>
                                    <th><?php echo _l('action_needed'); ?></th>
                                    <th><?php echo _l('deadline'); ?></th>
                                    <th><?php echo _l('serving_status'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($todo_list as $t) {
                                    if (empty($t['action_needed'])) continue;
                                ?>
                                <tr>
                                    <td data-label="<?php echo _l('action_needed'); ?>"><?php echo htmlspecialchars($t['action_needed']); ?></td>
                                    <td data-label="<?php echo _l('deadline'); ?>"><?php echo $t['deadline']; ?></td>
                                    <td data-label="<?php echo _l('serving_status'); ?>"><?php echo htmlspecialchars($t['status']); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                        <div class="mtop15">
                            <a href="<?php echo site_url('accountplanning/client/request_update/' . $account->id); ?>" class="btn btn-info"><i class="fa fa-bell"></i> <?php echo _l('ap_request_update'); ?></a>
                            <a href="<?php echo site_url('accountplanning/client'); ?>" class="btn btn-default"><?php echo _l('ap_back_to_plans'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
