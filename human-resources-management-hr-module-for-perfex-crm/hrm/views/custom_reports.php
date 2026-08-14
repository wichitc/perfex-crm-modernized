<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('custom_reports'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('custom_reports'); ?> - <?php echo _l('build_custom_report'); ?></p>
            <?php echo form_open(admin_url('hrm/custom_report_generate'), ['id' => 'custom_report_form']); ?>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label><?php echo _l('report_type'); ?></label>
                  <select name="report_type" class="form-control selectpicker" data-width="100%">
                    <option value="staff_list"><?php echo _l('staff_list'); ?></option>
                    <option value="staff_by_department"><?php echo _l('staff_by_department'); ?></option>
                    <option value="staff_by_position"><?php echo _l('staff_by_position'); ?></option>
                    <option value="contracts_summary"><?php echo _l('contracts_summary'); ?></option>
                    <option value="training_summary"><?php echo _l('training_summary'); ?></option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label><?php echo _l('format'); ?></label>
                  <select name="format" class="form-control selectpicker" data-width="100%">
                    <option value="html">HTML</option>
                    <option value="csv">CSV</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-info btn-block"><i class="fa fa-refresh"></i> <?php echo _l('generate'); ?></button>
                </div>
              </div>
            </div>
            <?php echo form_close(); ?>
            <div id="custom_report_output" class="mtop15"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  $('#custom_report_form').on('submit', function(e) {
    var fmt = $('select[name="format"]').val();
    if (fmt === 'csv') {
      $(this).attr('target', '_blank');
      return;
    }
    e.preventDefault();
    $.post(admin_url + 'hrm/custom_report_generate', $(this).serialize()).done(function(html) {
      $('#custom_report_output').html(html);
    });
  });
</script>
