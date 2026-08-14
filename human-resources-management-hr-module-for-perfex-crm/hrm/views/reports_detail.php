<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <a href="<?php echo admin_url('hrm/reports'); ?>" class="btn btn-default mbot15"><i class="fa fa-arrow-left"></i> <?php echo _l('back'); ?></a>
            <h4 class="no-margin"><?php echo _l('hr_reports'); ?> - <?php echo _l('report_' . $report_type); ?></h4>
            <hr class="hr-panel-heading" />
            <?php if ($report_type == 'layoff_staff'): ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('staff'); ?></th><th><?php echo _l('layoff_date'); ?></th><th><?php echo _l('reason'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$report_data as $r): ?>
                <tr>
                  <td><?php echo get_staff_full_name($r['staff_id']); ?></td>
                  <td><?php echo !empty($r['layoff_date']) ? _d($r['layoff_date']) : '-'; ?></td>
                  <td><?php echo htmlspecialchars($r['reason'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($report_data)): ?><tr><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
            <?php elseif ($report_type == 'salary_changes'): ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('staff'); ?></th><th><?php echo _l('date_create'); ?></th><th><?php echo _l('salary_changes'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$report_data as $r): ?>
                <tr>
                  <td><?php echo get_staff_full_name($r['staff']); ?></td>
                  <td><?php echo !empty($r['first_date']) ? _d($r['first_date']) : '-'; ?> - <?php echo !empty($r['last_date']) ? _d($r['last_date']) : '-'; ?></td>
                  <td><?php echo (int)($r['detail_count'] ?? 0); ?> <?php echo _l('changes'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($report_data)): ?><tr><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
            <?php elseif ($report_type == 'seniority_changes'): ?>
            <div class="row">
              <div class="col-md-6">
                <h5><?php echo _l('new_staff_for_month'); ?></h5>
                <table class="table table-striped">
                  <thead><tr><th><?php echo _l('month'); ?></th><th><?php echo _l('total_staff'); ?></th></tr></thead>
                  <tbody>
                    <?php foreach ((array)($report_data['hires'] ?? []) as $h): ?>
                    <tr><td><?php echo $h['yr']; ?>-<?php echo str_pad($h['mo'], 2, '0', STR_PAD_LEFT); ?></td><td><?php echo $h['cnt']; ?></td></tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div class="col-md-6">
                <h5><?php echo _l('quit_work_for_the_month'); ?></h5>
                <table class="table table-striped">
                  <thead><tr><th><?php echo _l('month'); ?></th><th><?php echo _l('total_staff'); ?></th></tr></thead>
                  <tbody>
                    <?php foreach ((array)($report_data['layoffs'] ?? []) as $l): ?>
                    <tr><td><?php echo $l['yr']; ?>-<?php echo str_pad($l['mo'], 2, '0', STR_PAD_LEFT); ?></td><td><?php echo $l['cnt']; ?></td></tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <?php elseif ($report_type == 'monthly_changes'): ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('month'); ?></th><th><?php echo _l('new_staff_for_month'); ?></th><th><?php echo _l('quit_work_for_the_month'); ?></th><th><?php echo _l('net'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$report_data as $r): ?>
                <tr>
                  <td><?php echo $r['yr']; ?>-<?php echo str_pad($r['mo'], 2, '0', STR_PAD_LEFT); ?></td>
                  <td><?php echo $r['new_count']; ?></td>
                  <td><?php echo $r['leave_count'] ?? 0; ?></td>
                  <td><?php echo ($r['new_count'] ?? 0) - ($r['leave_count'] ?? 0); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($report_data)): ?><tr><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
            <?php elseif ($report_type == 'qualifications'): ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('staff'); ?></th><th><?php echo _l('deparment_name'); ?></th><th><?php echo _l('literacy'); ?></th><th><?php echo _l('training'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$report_data as $r): ?>
                <tr>
                  <td><?php echo get_staff_full_name($r['staffid']); ?></td>
                  <td><?php echo htmlspecialchars($r['department_name'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($r['literacy'] ?? '-'); ?></td>
                  <td><?php echo (int)($r['training_count'] ?? 0); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($report_data)): ?><tr><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
            <?php else: ?>
            <p class="text-muted"><?php echo _l('report_' . $report_type); ?> - <?php echo _l('no_results'); ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
