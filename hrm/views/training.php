<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('training'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('training'); ?> <?php echo _l('training_types'); ?></p>
            <a href="<?php echo admin_url('hrm/setting?group=training_types'); ?>" class="btn btn-info"><?php echo _l('manage') . ' ' . _l('training_types'); ?></a>
            <?php if (has_permission('hrm', '', 'edit')): ?>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#training_modal"><i class="fa fa-plus"></i> <?php echo _l('add'); ?></button>
            <?php endif; ?>
            <table class="table dt-table table-striped mtop15">
              <thead><tr><th><?php echo _l('staff'); ?></th><th><?php echo _l('training_types'); ?></th><th><?php echo _l('completed_date'); ?></th><th><?php echo _l('certificate_number'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$staff_trainings as $st): ?>
                <tr>
                  <td><?php echo get_staff_full_name($st['staff_id']); ?></td>
                  <td><?php echo htmlspecialchars($st['training_type_name'] ?? $st['training_name'] ?? '-'); ?></td>
                  <td><?php echo !empty($st['completed_date']) ? _d($st['completed_date']) : '-'; ?></td>
                  <td><?php echo htmlspecialchars($st['certificate_number'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($staff_trainings)): ?><tr><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (has_permission('hrm', '', 'edit')): ?>
<div class="modal fade" id="training_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('add'); ?> <?php echo _l('training'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/training_add')); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="training_staff_id"><?php echo _l('staff'); ?></label>
          <select name="staff_id" id="training_staff_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required>
            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
            <?php foreach ((array)($staff_list ?? []) as $s): ?>
            <option value="<?php echo $s['staffid']; ?>"><?php echo ($s['firstname'] ?? '') . ' ' . ($s['lastname'] ?? ''); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="training_type_id"><?php echo _l('training_types'); ?></label>
          <select name="training_type_id" id="training_type_id" class="form-control selectpicker" data-width="100%">
            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
            <?php foreach ((array)($training_types ?? []) as $tt): ?>
            <option value="<?php echo $tt['id']; ?>"><?php echo htmlspecialchars($tt['name'] ?? ''); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php echo render_input('training_name', 'name', '', 'text'); ?>
        <?php echo render_date_input('completed_date', 'completed_date', ''); ?>
        <?php echo render_input('certificate_number', 'certificate_number', '', 'text'); ?>
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
