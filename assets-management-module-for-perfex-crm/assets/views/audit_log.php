<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-0">
                                <i class="fa fa-history"></i> <?php echo _l('audit_log'); ?>
                                <?php if (isset($asset)): ?>
                                    - <?php echo htmlspecialchars($asset->assets_name); ?>
                                <?php endif; ?>
                            </h4>
                            <a href="<?php echo admin_url('assets/export_report/audit' . ($asset_id ? '?asset_id=' . $asset_id : '')); ?>" class="btn btn-default" target="_blank">
                                <i class="fa fa-download"></i> <?php echo _l('export'); ?>
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dt-table">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('date_time'); ?></th>
                                        <th><?php echo _l('action'); ?></th>
                                        <th><?php echo _l('description'); ?></th>
                                        <th><?php echo _l('performed_by'); ?></th>
                                        <th><?php echo _l('ip_address'); ?></th>
                                        <th><?php echo _l('details'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?php echo _dt($log['created_at']); ?></td>
                                        <td>
                                            <span class="label label-<?php echo get_audit_action_class($log['action']); ?>">
                                                <?php echo _l($log['action']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($log['description']); ?></td>
                                        <td>
                                            <?php if ($log['performed_by']): ?>
                                                <?php echo get_staff_full_name($log['performed_by']); ?>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo _l('system'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                        <td>
                                            <?php if ($log['old_values'] || $log['new_values']): ?>
                                            <button type="button" class="btn btn-xs btn-default" onclick="showChanges(<?php echo $log['id']; ?>)">
                                                <i class="fa fa-eye"></i> <?php echo _l('view_changes'); ?>
                                            </button>
                                            <div id="changes-<?php echo $log['id']; ?>" style="display:none;">
                                                <?php if ($log['old_values']): ?>
                                                <strong><?php echo _l('old_values'); ?>:</strong>
                                                <pre class="tw-text-xs tw-bg-red-50 tw-p-2 tw-rounded"><?php echo htmlspecialchars(json_encode(json_decode($log['old_values']), JSON_PRETTY_PRINT)); ?></pre>
                                                <?php endif; ?>
                                                <?php if ($log['new_values']): ?>
                                                <strong><?php echo _l('new_values'); ?>:</strong>
                                                <pre class="tw-text-xs tw-bg-green-50 tw-p-2 tw-rounded"><?php echo htmlspecialchars(json_encode(json_decode($log['new_values']), JSON_PRETTY_PRINT)); ?></pre>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
function showChanges(id) {
    var $el = $('#changes-' + id);
    if ($el.is(':visible')) {
        $el.slideUp();
    } else {
        $el.slideDown();
    }
}

$(function() {
    $('.dt-table').DataTable({
        order: [[0, 'desc']],
        pageLength: 25
    });
});
</script>
<?php
function get_audit_action_class($action) {
    $classes = [
        'created' => 'success',
        'updated' => 'info',
        'deleted' => 'danger',
        'allocated' => 'primary',
        'revoked' => 'warning',
        'checked_out' => 'info',
        'checked_in' => 'success',
        'maintenance_scheduled' => 'default',
        'maintenance_completed' => 'success',
        'transferred' => 'info',
        'lost' => 'danger',
        'broken' => 'danger',
        'warranty' => 'warning',
        'liquidated' => 'default'
    ];
    return $classes[$action] ?? 'default';
}
?>
</body>
</html>
