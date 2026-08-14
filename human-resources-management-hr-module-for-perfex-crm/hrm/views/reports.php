<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('hr_reports'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('hr_reports'); ?> - HR Reports</p>
            <div class="row">
              <div class="col-md-4">
                <a href="<?php echo admin_url('hrm/reports/layoff_staff'); ?>" class="btn btn-default btn-block">
                  <i class="fa fa-user-times"></i> <?php echo _l('report_layoff_staff'); ?>
                </a>
              </div>
              <div class="col-md-4">
                <a href="<?php echo admin_url('hrm/reports/salary_changes'); ?>" class="btn btn-default btn-block">
                  <i class="fa fa-money"></i> <?php echo _l('report_salary_changes'); ?>
                </a>
              </div>
              <div class="col-md-4">
                <a href="<?php echo admin_url('hrm/reports/seniority_changes'); ?>" class="btn btn-default btn-block">
                  <i class="fa fa-line-chart"></i> <?php echo _l('report_seniority_changes'); ?>
                </a>
              </div>
            </div>
            <div class="row mtop15">
              <div class="col-md-4">
                <a href="<?php echo admin_url('hrm/reports/monthly_changes'); ?>" class="btn btn-default btn-block">
                  <i class="fa fa-calendar"></i> <?php echo _l('report_monthly_changes'); ?>
                </a>
              </div>
              <div class="col-md-4">
                <a href="<?php echo admin_url('hrm/reports/qualifications'); ?>" class="btn btn-default btn-block">
                  <i class="fa fa-graduation-cap"></i> <?php echo _l('report_qualifications'); ?>
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
