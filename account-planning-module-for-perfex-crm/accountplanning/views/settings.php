<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="bold"><?php echo _l('settings'); ?> - <?php echo _l('accountplanning'); ?></h4>
                <?php echo form_open(admin_url('accountplanning/save_settings'), ['id' => 'accountplanning-settings-form']); ?>
                <div class="panel_s mtop15">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="accountplanning_industry_options"><?php echo _l('industry'); ?> <?php echo _l('ap_industry_options_help'); ?></label>
                            <textarea name="accountplanning_industry_options" id="accountplanning_industry_options" class="form-control" rows="6"><?php echo htmlspecialchars(get_option('accountplanning_industry_options')); ?></textarea>
                            <small class="text-muted"><?php echo _l('ap_industry_one_per_line'); ?></small>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <input type="checkbox" name="accountplanning_client_portal_enabled" id="accountplanning_client_portal_enabled" value="1" <?php echo get_option('accountplanning_client_portal_enabled') == '1' ? 'checked' : ''; ?>>
                                <label for="accountplanning_client_portal_enabled"><?php echo _l('ap_client_portal_enabled'); ?></label>
                            </div>
                        </div>
                        <hr>
                        <h4 class="bold"><?php echo _l('reminders'); ?></h4>
                        <div class="form-group">
                            <div class="checkbox">
                                <input type="checkbox" name="accountplanning_reminders_enabled" id="accountplanning_reminders_enabled" value="1" <?php echo get_option('accountplanning_reminders_enabled') == '1' ? 'checked' : ''; ?>>
                                <label for="accountplanning_reminders_enabled"><?php echo _l('ap_reminders_enabled'); ?></label>
                            </div>
                            <small class="text-muted"><?php echo _l('ap_reminder_days_help'); ?></small>
                        </div>
                        <div class="form-group">
                            <label for="accountplanning_reminder_days"><?php echo _l('ap_reminder_days'); ?></label>
                            <input type="number" name="accountplanning_reminder_days" id="accountplanning_reminder_days" class="form-control" min="1" max="30" value="<?php echo htmlspecialchars(get_option('accountplanning_reminder_days', 3)); ?>">
                        </div>
                        <hr>
                        <h4 class="bold"><?php echo _l('ap_recurring_plans'); ?></h4>
                        <div class="form-group">
                            <div class="checkbox">
                                <input type="checkbox" name="accountplanning_recurring_cron_enabled" id="accountplanning_recurring_cron_enabled" value="1" <?php echo get_option('accountplanning_recurring_cron_enabled') == '1' ? 'checked' : ''; ?>>
                                <label for="accountplanning_recurring_cron_enabled"><?php echo _l('ap_recurring_cron_enabled'); ?></label>
                            </div>
                            <small class="text-muted"><?php echo _l('ap_recurring_cron_help'); ?></small>
                        </div>
                        <div class="form-group">
                            <label for="accountplanning_recurring_period"><?php echo _l('ap_recurring_period'); ?></label>
                            <?php
                            $periods = [['id' => 'month', 'name' => _l('ap_recurring_month')], ['id' => 'quarter', 'name' => _l('ap_recurring_quarter')]];
                            echo render_select('accountplanning_recurring_period', $periods, ['id', 'name'], '', get_option('accountplanning_recurring_period', 'month'), ['data-width' => '100%']);
                            ?>
                        </div>
                        <hr>
                        <h4 class="bold"><?php echo _l('plan_status'); ?></h4>
                        <div class="form-group">
                            <label for="accountplanning_default_status"><?php echo _l('ap_default_status'); ?></label>
                            <?php
                            $statuses = [['id' => 'draft', 'name' => _l('ap_status_draft')], ['id' => 'in_progress', 'name' => _l('ap_status_in_progress')], ['id' => 'review', 'name' => _l('ap_status_review')]];
                            echo render_select('accountplanning_default_status', $statuses, ['id', 'name'], '', get_option('accountplanning_default_status', 'draft'), ['data-width' => '100%']);
                            ?>
                            <small class="text-muted"><?php echo _l('ap_default_status_help'); ?></small>
                        </div>
                        <div class="alert alert-info mtop15">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <?php
                            $cron_url = site_url('cron/index' . (defined('APP_CRON_KEY') ? '/' . APP_CRON_KEY : ''));
                            echo sprintf(_l('ap_cron_setup_info'), htmlspecialchars($cron_url));
                            ?>
                            <br>
                            <a href="<?php echo admin_url('settings?group=cronjob'); ?>" class="alert-link"><?php echo _l('settings'); ?> → <?php echo _l('settings_group_cronjob'); ?></a>
                        </div>
                        <hr>
                        <h4 class="bold"><?php echo _l('ap_webhook_options'); ?></h4>
                        <div class="form-group">
                            <div class="checkbox">
                                <input type="checkbox" name="accountplanning_webhook_ssl_verify" id="accountplanning_webhook_ssl_verify" value="0" <?php echo get_option('accountplanning_webhook_ssl_verify') === '0' ? 'checked' : ''; ?>>
                                <label for="accountplanning_webhook_ssl_verify"><?php echo _l('ap_webhook_disable_ssl'); ?></label>
                            </div>
                            <small class="text-muted"><?php echo _l('ap_webhook_disable_ssl_help'); ?></small>
                        </div>
                        <button type="submit" class="btn btn-info"><?php echo _l('save'); ?></button>
                        <a href="<?php echo admin_url('accountplanning'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <div class="panel_s mtop15">
                    <div class="panel-body">
                        <h4 class="bold"><?php echo _l('ap_webhooks'); ?></h4>
                        <p class="text-muted"><?php echo _l('ap_webhooks_help'); ?></p>
                        <?php if (!empty($webhooks)) { ?>
                        <div class="table-responsive mtop15">
                            <table class="table table-striped">
                                <thead><tr><th><?php echo _l('ap_webhook_url'); ?></th><th width="80"><?php echo _l('options'); ?></th></tr></thead>
                                <tbody>
                                <?php foreach ($webhooks as $wh) { ?>
                                <tr><td><?php echo htmlspecialchars($wh['url']); ?></td><td><a href="<?php echo admin_url('accountplanning/delete_webhook/' . $wh['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-trash"></i></a></td></tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>
                        <div class="form-group mtop15">
                            <label><?php echo _l('ap_add_webhook'); ?></label>
                            <?php echo form_open(admin_url('accountplanning/add_webhook'), ['id' => 'ap_webhook_form']); ?>
                            <div class="input-group">
                                <input type="url" name="webhook_url" class="form-control" placeholder="https://webhook.site/your-unique-id" required>
                                <span class="input-group-btn"><button type="submit" class="btn btn-info"><?php echo _l('add'); ?></button></span>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
