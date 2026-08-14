<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="tw-font-semibold tw-mb-4"><?php echo _l('webhooks'); ?></h4>

<?php if (has_permission('assets', '', 'create') || is_admin()): ?>
<button type="button" class="btn btn-primary btn-sm tw-mb-4" data-toggle="modal" data-target="#webhookModal" onclick="resetWebhookForm()">
    <i class="fa fa-plus"></i> <?php echo _l('add_new'); ?>
</button>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo _l('webhook_name'); ?></th>
                <th><?php echo _l('webhook_url'); ?></th>
                <th><?php echo _l('events'); ?></th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('last_triggered'); ?></th>
                <th><?php echo _l('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($webhooks)): ?>
                <?php foreach ($webhooks as $webhook): ?>
                <tr>
                    <td><?php echo htmlspecialchars($webhook['name']); ?></td>
                    <td>
                        <code style="font-size: 11px;"><?php echo htmlspecialchars(mb_substr($webhook['url'], 0, 40)); ?><?php echo strlen($webhook['url']) > 40 ? '...' : ''; ?></code>
                    </td>
                    <td>
                        <?php 
                        $events = is_string($webhook['events']) ? json_decode($webhook['events'], true) : $webhook['events'];
                        if (!empty($events)): 
                            foreach (array_slice($events, 0, 2) as $event): ?>
                                <span class="label label-default"><?php echo htmlspecialchars($event); ?></span>
                            <?php endforeach;
                            if (count($events) > 2): ?>
                                <span class="label label-info">+<?php echo count($events) - 2; ?></span>
                            <?php endif;
                        endif; ?>
                    </td>
                    <td>
                        <?php if ($webhook['active']): ?>
                            <span class="label label-success"><?php echo _l('active'); ?></span>
                        <?php else: ?>
                            <span class="label label-default"><?php echo _l('inactive'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $webhook['last_triggered'] ? _dt($webhook['last_triggered']) : '-'; ?>
                        <?php if ($webhook['failure_count'] > 0): ?>
                            <span class="label label-danger"><?php echo $webhook['failure_count']; ?> <?php echo _l('failures'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (has_permission('assets', '', 'edit') || is_admin()): ?>
                        <button type="button" class="btn btn-default btn-xs" onclick='editWebhook(<?php echo json_encode($webhook); ?>)'>
                            <i class="fa fa-pencil"></i>
                        </button>
                        <a href="<?php echo admin_url('assets/test_webhook/' . $webhook['id']); ?>" class="btn btn-info btn-xs" title="<?php echo _l('test_webhook'); ?>">
                            <i class="fa fa-bolt"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (has_permission('assets', '', 'delete') || is_admin()): ?>
                        <a href="<?php echo admin_url('assets/delete_webhook/' . $webhook['id']); ?>" class="btn btn-danger btn-xs _delete">
                            <i class="fa fa-trash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center"><?php echo _l('no_records_found'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Webhook Modal -->
<div class="modal fade" id="webhookModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(admin_url('assets/webhook'), ['id' => 'webhookForm']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="webhookModalTitle"><?php echo _l('webhook'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="webhook_id">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_input('name', 'webhook_name', '', 'text', ['required' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input('url', 'webhook_url', '', 'url', ['required' => true, 'placeholder' => 'https://example.com/webhook']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_input('secret_key', 'secret_key', '', 'text', ['placeholder' => _l('optional')]); ?>
                        <small class="text-muted"><?php echo _l('webhook_signature_info'); ?></small>
                    </div>
                    <div class="col-md-6">
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="active" id="webhook_active" value="1" checked>
                            <label for="webhook_active"><?php echo _l('active'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo _l('events'); ?> <span class="text-danger">*</span></label>
                    <div class="row">
                        <?php foreach ($webhook_events as $event_key => $event_name): ?>
                        <div class="col-md-4">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" name="events[]" id="event_<?php echo $event_key; ?>" value="<?php echo $event_key; ?>">
                                <label for="event_<?php echo $event_key; ?>"><?php echo htmlspecialchars($event_name); ?></label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
function resetWebhookForm() {
    $('#webhookForm')[0].reset();
    $('#webhook_id').val('');
    $('#webhookModalTitle').text('<?php echo _l('webhook'); ?>');
    $('input[name="events[]"]').prop('checked', false);
    $('#webhook_active').prop('checked', true);
}

function editWebhook(webhook) {
    $('#webhook_id').val(webhook.id);
    $('#webhookModalTitle').text('<?php echo _l('edit'); ?> <?php echo _l('webhook'); ?>');
    $('input[name="name"]').val(webhook.name);
    $('input[name="url"]').val(webhook.url);
    $('input[name="secret_key"]').val(webhook.secret_key);
    $('#webhook_active').prop('checked', webhook.active == 1);
    
    // Reset and set events
    $('input[name="events[]"]').prop('checked', false);
    var events = typeof webhook.events === 'string' ? JSON.parse(webhook.events) : webhook.events;
    if (events) {
        events.forEach(function(event) {
            $('input[value="' + event + '"]').prop('checked', true);
        });
    }
    
    $('#webhookModal').modal('show');
}
</script>
