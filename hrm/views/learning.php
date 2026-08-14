<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('learning_paths'); ?></h4>
            <hr class="hr-panel-heading" />
            <?php if (has_permission('hrm', '', 'edit')): ?>
            <button type="button" class="btn btn-success mbot15" data-toggle="modal" data-target="#course_modal"><i class="fa fa-plus"></i> <?php echo _l('add'); ?> <?php echo _l('course'); ?></button>
            <button type="button" class="btn btn-info mbot15" data-toggle="modal" data-target="#enroll_modal"><i class="fa fa-user-plus"></i> <?php echo _l('enroll_staff'); ?></button>
            <?php endif; ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('name'); ?></th><th><?php echo _l('category'); ?></th><th><?php echo _l('duration_hours'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$courses as $c): ?>
                <tr>
                  <td><?php echo htmlspecialchars($c['name']); ?></td>
                  <td><?php echo htmlspecialchars($c['category'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($c['duration_hours'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($courses)): ?><tr><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (has_permission('hrm', '', 'edit')): ?>
<div class="modal fade" id="course_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('add'); ?> <?php echo _l('course'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/learning_course_add')); ?>
      <div class="modal-body">
        <?php echo render_input('name', 'name', '', 'text', ['required' => true]); ?>
        <?php echo render_input('category', 'category', '', 'text'); ?>
        <?php echo render_input('duration_hours', 'duration_hours', '', 'number'); ?>
        <div class="form-group">
          <label><?php echo _l('description'); ?></label>
          <textarea name="description" class="form-control" rows="3"></textarea>
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

<div class="modal fade" id="enroll_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('enroll_staff'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/learning_enroll')); ?>
      <div class="modal-body">
        <div class="form-group">
          <label><?php echo _l('staff'); ?></label>
          <select name="staff_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required>
            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
            <?php foreach ((array)($staff_list ?? []) as $s): ?>
            <option value="<?php echo $s['staffid']; ?>"><?php echo ($s['firstname'] ?? '') . ' ' . ($s['lastname'] ?? ''); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label><?php echo _l('course'); ?></label>
          <select name="course_id" class="form-control selectpicker" data-width="100%" required>
            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
            <?php foreach ((array)($courses ?? []) as $c): ?>
            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
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
