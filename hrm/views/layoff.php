<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('layoff_management'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('layoff_management'); ?> - <?php echo _l('report_layoff_staff'); ?></p>
            <a href="<?php echo admin_url('hrm/setting?group=layoff_checklist'); ?>" class="btn btn-info"><?php echo _l('manage') . ' ' . _l('layoff_checklist'); ?></a>
            <?php if (has_permission('hrm', '', 'edit')): ?>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#layoff_modal"><i class="fa fa-plus"></i> <?php echo _l('add'); ?></button>
            <?php endif; ?>
            <table class="table dt-table table-striped mtop15">
              <thead><tr><th><?php echo _l('staff'); ?></th><th><?php echo _l('layoff_date'); ?></th><th><?php echo _l('reason'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$layoff_records as $lr): ?>
                <tr>
                  <td><?php echo get_staff_full_name($lr['staff_id']); ?></td>
                  <td><?php echo !empty($lr['layoff_date']) ? _d($lr['layoff_date']) : '-'; ?></td>
                  <td><?php echo htmlspecialchars(substr($lr['reason'] ?? '', 0, 80)); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($layoff_records)): ?><tr><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (has_permission('hrm', '', 'edit')): ?>
<div class="modal fade" id="layoff_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('add'); ?> <?php echo _l('layoff_management'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/layoff_add')); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="layoff_staff_id"><?php echo _l('staff'); ?></label>
          <select name="staff_id" id="layoff_staff_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required>
            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
            <?php foreach ((array)($staff_list ?? []) as $s): ?>
            <option value="<?php echo $s['staffid']; ?>"><?php echo $s['firstname'] . ' ' . $s['lastname']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php echo render_date_input('layoff_date', 'layoff_date', ''); ?>
        <div class="form-group">
          <label for="layoff_reason"><?php echo _l('reason'); ?></label>
          <textarea name="reason" id="layoff_reason" class="form-control" rows="4"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php init_tail(); ?>
