<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-gap-2 tw-mb-4">
                    <i class="fa fa-tachometer"></i> <?php echo _l('assets_dashboard'); ?>
                </h4>
            </div>
        </div>

        <!-- Statistics Cards Row 1: Key Metrics -->
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel_s dashboard-stat-card" style="border-left: 4px solid #4e73df; min-height: 100px; cursor: pointer;" onclick="window.location='<?php echo admin_url('assets/manage_assets'); ?>'">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-primary tw-mb-1"><?php echo $stats['total_assets']; ?></h3>
                                <span class="tw-text-neutral-500 tw-text-sm"><?php echo _l('total_assets'); ?></span>
                            </div>
                            <div class="tw-text-4xl" style="color: #4e73df; opacity: 0.5;">
                                <i class="fa fa-cubes"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel_s dashboard-stat-card" style="border-left: 4px solid #1cc88a; min-height: 100px; cursor: pointer;" onclick="window.location='<?php echo admin_url('assets/reports'); ?>'">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-xl tw-font-bold tw-text-success tw-mb-1" style="font-size: 18px;"><?php echo app_format_money($stats['total_value'], get_base_currency()); ?></h3>
                                <span class="tw-text-neutral-500 tw-text-sm"><?php echo _l('total_asset_value'); ?></span>
                            </div>
                            <div class="tw-text-4xl" style="color: #1cc88a; opacity: 0.5;">
                                <i class="fa fa-line-chart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel_s dashboard-stat-card" style="border-left: 4px solid #36b9cc; min-height: 100px; cursor: pointer;" onclick="window.location='<?php echo admin_url('assets/checkouts'); ?>'">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-info tw-mb-1"><?php echo $stats['checked_out']; ?></h3>
                                <span class="tw-text-neutral-500 tw-text-sm"><?php echo _l('currently_checked_out'); ?></span>
                            </div>
                            <div class="tw-text-4xl" style="color: #36b9cc; opacity: 0.5;">
                                <i class="fa fa-external-link"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel_s dashboard-stat-card" style="border-left: 4px solid #6f42c1; min-height: 100px; cursor: pointer;" onclick="window.location='<?php echo admin_url('assets/reservations'); ?>'">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-2xl tw-font-bold tw-mb-1" style="color:#6f42c1;"><?php echo $stats['pending_reservations']; ?></h3>
                                <span class="tw-text-neutral-500 tw-text-sm"><?php echo _l('pending_reservations'); ?></span>
                            </div>
                            <div class="tw-text-4xl" style="color: #6f42c1; opacity: 0.5;">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel_s dashboard-stat-card" style="border-left: 4px solid #1cc88a; min-height: 100px; cursor: pointer;" onclick="window.location='<?php echo admin_url('assets/manage_assets'); ?>'">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-success tw-mb-1"><?php echo $stats['recently_added']; ?></h3>
                                <span class="tw-text-neutral-500 tw-text-sm"><?php echo _l('recently_added_7_days'); ?></span>
                            </div>
                            <div class="tw-text-4xl" style="color: #1cc88a; opacity: 0.5;">
                                <i class="fa fa-plus-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel_s dashboard-stat-card" style="border-left: 4px solid #fd7e14; min-height: 100px; cursor: pointer;" onclick="window.location='<?php echo admin_url('assets/maintenance'); ?>'">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-2xl tw-font-bold tw-mb-1" style="color:#fd7e14;"><?php echo $stats['due_maintenance']; ?></h3>
                                <span class="tw-text-neutral-500 tw-text-sm"><?php echo _l('maintenance_due_soon'); ?></span>
                            </div>
                            <div class="tw-text-4xl" style="color: #fd7e14; opacity: 0.5;">
                                <i class="fa fa-wrench"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row 2: Alerts & Warnings -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="panel_s dashboard-stat-card" style="border-left: 4px solid #e74a3b; min-height: 100px; cursor: pointer;" onclick="window.location='<?php echo admin_url('assets/checkouts'); ?>'">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-danger tw-mb-1"><?php echo $stats['overdue_checkouts']; ?></h3>
                                <span class="tw-text-neutral-500 tw-text-sm"><?php echo _l('overdue_checkouts'); ?></span>
                            </div>
                            <div class="tw-text-4xl" style="color: #e74a3b; opacity: 0.5;">
                                <i class="fa fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="panel_s dashboard-stat-card" style="border-left: 4px solid #f6c23e; min-height: 100px; cursor: pointer;" onclick="window.location='<?php echo admin_url('assets/reports?type=warranty'); ?>'">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-warning tw-mb-1"><?php echo $stats['expiring_warranties']; ?></h3>
                                <span class="tw-text-neutral-500 tw-text-sm"><?php echo _l('warranties_expiring_soon'); ?></span>
                            </div>
                            <div class="tw-text-4xl" style="color: #f6c23e; opacity: 0.5;">
                                <i class="fa fa-shield"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Depreciation Summary -->
        <div class="row">
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo _l('depreciation_summary'); ?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tw-mb-4">
                                    <span class="tw-text-neutral-500"><?php echo _l('original_value'); ?></span>
                                    <h4 class="tw-font-bold tw-mb-0"><?php echo app_format_money($depreciation['original_value'], get_base_currency()); ?></h4>
                                </div>
                                <div class="tw-mb-4">
                                    <span class="tw-text-neutral-500"><?php echo _l('total_depreciation'); ?></span>
                                    <h4 class="tw-font-bold tw-text-danger tw-mb-0">-<?php echo app_format_money($depreciation['total_depreciation'], get_base_currency()); ?></h4>
                                </div>
                                <hr>
                                <div>
                                    <span class="tw-text-neutral-500"><?php echo _l('current_book_value'); ?></span>
                                    <h4 class="tw-font-bold tw-text-success tw-mb-0"><?php echo app_format_money($depreciation['current_value'], get_base_currency()); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Assets by Status Chart -->
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo _l('assets_by_status'); ?></h4>
                    </div>
                    <div class="panel-body">
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Assets by Group Chart -->
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo _l('assets_by_group'); ?></h4>
                    </div>
                    <div class="panel-body">
                        <canvas id="groupChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Due Maintenance -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo _l('recent_activity'); ?></h4>
                    </div>
                    <div class="panel-body" style="max-height: 400px; overflow-y: auto;">
                        <?php if (empty($recent_activity)): ?>
                            <p class="tw-text-neutral-500"><?php echo _l('no_recent_activity'); ?></p>
                        <?php else: ?>
                            <ul class="list-unstyled tw-space-y-3">
                                <?php foreach ($recent_activity as $activity): ?>
                                    <li class="tw-flex tw-items-start tw-gap-3 tw-pb-3 tw-border-b">
                                        <span class="tw-bg-primary tw-text-white tw-rounded-full tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                                            <i class="fa fa-<?php echo get_activity_icon($activity['action']); ?>"></i>
                                        </span>
                                        <div class="tw-flex-1">
                                            <p class="tw-mb-1"><?php echo htmlspecialchars($activity['description']); ?></p>
                                            <small class="tw-text-neutral-400">
                                                <?php echo _dt($activity['created_at']); ?>
                                                <?php if ($activity['performed_by']): ?>
                                                    - <?php echo get_staff_full_name($activity['performed_by']); ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer">
                        <a href="<?php echo admin_url('assets/audit_log'); ?>"><?php echo _l('view_all_activity'); ?> &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo _l('upcoming_maintenance'); ?></h4>
                    </div>
                    <div class="panel-body" style="max-height: 400px; overflow-y: auto;">
                        <?php if (empty($due_maintenance)): ?>
                            <p class="tw-text-neutral-500"><?php echo _l('no_upcoming_maintenance'); ?></p>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('asset'); ?></th>
                                        <th><?php echo _l('maintenance_type'); ?></th>
                                        <th><?php echo _l('scheduled_date'); ?></th>
                                        <th><?php echo _l('status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($due_maintenance as $m): 
                                        $asset = $this->assets_model->get($m['asset_id']);
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($asset->assets_name); ?></td>
                                            <td><?php echo htmlspecialchars($m['maintenance_type']); ?></td>
                                            <td><?php echo _d($m['scheduled_date']); ?></td>
                                            <td>
                                                <span class="label label-<?php echo $m['status'] == 'overdue' ? 'danger' : 'warning'; ?>">
                                                    <?php echo _l($m['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer">
                        <a href="<?php echo admin_url('assets/maintenance'); ?>"><?php echo _l('view_all_maintenance'); ?> &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s" style="border-radius: 8px;">
                    <div class="panel-heading" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: #fff; border-radius: 8px 8px 0 0;">
                        <h4 class="panel-title" style="color: #fff;"><i class="fa fa-bolt mright5"></i> <?php echo _l('quick_actions'); ?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-6 text-center" style="margin-bottom: 15px;">
                                <a href="<?php echo admin_url('assets/manage_assets'); ?>" class="btn btn-lg btn-block" style="background: #4e73df; color: #fff; border-radius: 8px; padding: 15px;">
                                    <i class="fa fa-plus fa-2x" style="display: block; margin-bottom: 8px;"></i>
                                    <span style="font-size: 12px;"><?php echo _l('new_asset'); ?></span>
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6 text-center" style="margin-bottom: 15px;">
                                <a href="<?php echo admin_url('assets/checkouts'); ?>" class="btn btn-lg btn-block" style="background: #36b9cc; color: #fff; border-radius: 8px; padding: 15px;">
                                    <i class="fa fa-sign-out fa-2x" style="display: block; margin-bottom: 8px;"></i>
                                    <span style="font-size: 12px;"><?php echo _l('checkout_asset'); ?></span>
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6 text-center" style="margin-bottom: 15px;">
                                <a href="<?php echo admin_url('assets/reservations'); ?>" class="btn btn-lg btn-block" style="background: #6f42c1; color: #fff; border-radius: 8px; padding: 15px;">
                                    <i class="fa fa-calendar fa-2x" style="display: block; margin-bottom: 8px;"></i>
                                    <span style="font-size: 12px;"><?php echo _l('make_reservation'); ?></span>
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6 text-center" style="margin-bottom: 15px;">
                                <a href="<?php echo admin_url('assets/maintenance'); ?>" class="btn btn-lg btn-block" style="background: #1cc88a; color: #fff; border-radius: 8px; padding: 15px;">
                                    <i class="fa fa-wrench fa-2x" style="display: block; margin-bottom: 8px;"></i>
                                    <span style="font-size: 12px;"><?php echo _l('schedule_maintenance'); ?></span>
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6 text-center" style="margin-bottom: 15px;">
                                <a href="<?php echo admin_url('assets/reports'); ?>" class="btn btn-lg btn-block" style="background: #5a5c69; color: #fff; border-radius: 8px; padding: 15px;">
                                    <i class="fa fa-file-text fa-2x" style="display: block; margin-bottom: 8px;"></i>
                                    <span style="font-size: 12px;"><?php echo _l('generate_report'); ?></span>
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6 text-center" style="margin-bottom: 15px;">
                                <a href="<?php echo admin_url('assets/import'); ?>" class="btn btn-lg btn-block" style="background: #fd7e14; color: #fff; border-radius: 8px; padding: 15px;">
                                    <i class="fa fa-upload fa-2x" style="display: block; margin-bottom: 8px;"></i>
                                    <span style="font-size: 12px;"><?php echo _l('import_assets'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Status Chart
var statusData = <?php echo json_encode($stats['by_status']); ?>;
var statusLabels = {
    1: '<?php echo _l('not_pending_yet'); ?>',
    2: '<?php echo _l('using'); ?>',
    3: '<?php echo _l('liquidation'); ?>',
    4: '<?php echo _l('warranty_repair'); ?>',
    5: '<?php echo _l('lost'); ?>',
    6: '<?php echo _l('broken'); ?>'
};
var statusColors = ['#1cc88a', '#4e73df', '#858796', '#f6c23e', '#e74a3b', '#fd7e14'];

var statusChartData = {
    labels: Object.keys(statusData).map(k => statusLabels[k] || k),
    datasets: [{
        data: Object.values(statusData),
        backgroundColor: statusColors.slice(0, Object.keys(statusData).length)
    }]
};

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: statusChartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Group Chart
var groupData = <?php echo json_encode($stats['by_group']); ?>;
new Chart(document.getElementById('groupChart'), {
    type: 'bar',
    data: {
        labels: groupData.map(g => g.group_name || '<?php echo _l('uncategorized'); ?>'),
        datasets: [{
            label: '<?php echo _l('assets'); ?>',
            data: groupData.map(g => g.count),
            backgroundColor: '#4e73df'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<?php
function get_activity_icon($action) {
    $icons = [
        'created' => 'plus',
        'updated' => 'edit',
        'deleted' => 'trash',
        'allocated' => 'user-plus',
        'revoked' => 'user-minus',
        'checked_out' => 'sign-out',
        'checked_in' => 'sign-in',
        'maintenance_scheduled' => 'wrench',
        'maintenance_completed' => 'check',
        'transferred' => 'exchange',
        'lost' => 'exclamation-triangle',
        'broken' => 'times-circle',
        'warranty' => 'shield',
        'liquidated' => 'archive'
    ];
    return $icons[$action] ?? 'info';
}
?>
</body>
</html>
