<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('onboarding'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('onboarding'); ?> - <?php echo _l('onboarding_templates'); ?></p>
            <a href="<?php echo admin_url('hrm/setting?group=onboarding_templates'); ?>" class="btn btn-info"><?php echo _l('manage') . ' ' . _l('onboarding_templates'); ?></a>
            <?php if (has_permission('hrm', '', 'edit')): ?>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#onboarding_modal"><i class="fa fa-plus"></i> <?php echo _l('assign_onboarding'); ?></button>
            <?php endif; ?>
            <table class="table dt-table table-striped mtop15">
              <thead><tr><th><?php echo _l('staff'); ?></th><th><?php echo _l('template'); ?></th><th><?php echo _l('status'); ?></th><th><?php echo _l('started_date'); ?></th><th><?php echo _l('options'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$records as $r): ?>
                <tr>
                  <td><?php echo get_staff_full_name($r['staff_id']); ?></td>
                  <td><?php echo htmlspecialchars($r['template_name'] ?? '-'); ?></td>
                  <td><span class="label label-<?php echo $r['status'] == 'completed' ? 'success' : 'info'; ?>"><?php echo htmlspecialchars($r['status'] ?? 'in_progress'); ?></span></td>
                  <td><?php echo !empty($r['started_date']) ? _d($r['started_date']) : '-'; ?></td>
                  <td>
                    <a href="<?php echo admin_url('hrm/onboarding_record/'.$r['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                    <?php if (has_permission('hrm', '', 'edit')): ?>
                    <a href="<?php echo admin_url('hrm/delete_onboarding_record/'.$r['id']); ?>" class="btn btn-danger btn-sm _delete"><i class="fa fa-remove"></i></a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($records)): ?><tr><td></td><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (has_permission('hrm', '', 'edit')): ?>
<div class="modal fade" id="onboarding_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('assign_onboarding'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/onboarding_assign')); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="onboarding_staff_id"><?php echo _l('staff'); ?></label>
          <select name="staff_id" id="onboarding_staff_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required>
            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
            <?php foreach ((array)($staff_list ?? []) as $s): ?>
            <option value="<?php echo $s['staffid']; ?>"><?php echo ($s['firstname'] ?? '') . ' ' . ($s['lastname'] ?? ''); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="onboarding_template_id"><?php echo _l('template'); ?></label>
          <select name="template_id" id="onboarding_template_id" class="form-control selectpicker" data-width="100%" required>
            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
            <?php foreach ((array)($templates ?? []) as $t): ?>
            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name'] ?? ''); ?></option>
            <?php endforeach; ?>
          </select>
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
