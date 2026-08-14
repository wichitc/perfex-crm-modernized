<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('employee_engagement'); ?></h4>
            <hr class="hr-panel-heading" />
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#tab_surveys"><?php echo _l('surveys'); ?></a></li>
              <li><a data-toggle="tab" href="#tab_oneonone"><?php echo _l('one_on_one_notes'); ?></a></li>
            </ul>
            <div class="tab-content mtop15">
              <div id="tab_surveys" class="tab-pane active">
                <?php if (has_permission('hrm', '', 'edit')): ?>
                <button type="button" class="btn btn-success mbot15" data-toggle="modal" data-target="#survey_modal"><i class="fa fa-plus"></i> <?php echo _l('add'); ?> <?php echo _l('survey'); ?></button>
                <?php endif; ?>
                <table class="table dt-table table-striped">
                  <thead><tr><th><?php echo _l('title'); ?></th><th><?php echo _l('date_from'); ?></th><th><?php echo _l('date_to'); ?></th></tr></thead>
                  <tbody>
                    <?php foreach ((array)$surveys as $s): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($s['title']); ?></td>
                      <td><?php echo !empty($s['date_from']) ? _d($s['date_from']) : '-'; ?></td>
                      <td><?php echo !empty($s['date_to']) ? _d($s['date_to']) : '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($surveys)): ?><tr><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
                  </tbody>
                </table>
              </div>
              <div id="tab_oneonone" class="tab-pane">
                <?php if (has_permission('hrm', '', 'edit')): ?>
                <button type="button" class="btn btn-success mbot15" data-toggle="modal" data-target="#oneonone_modal"><i class="fa fa-plus"></i> <?php echo _l('add'); ?> <?php echo _l('one_on_one_note'); ?></button>
                <?php endif; ?>
                <table class="table dt-table table-striped">
                  <thead><tr><th><?php echo _l('staff'); ?></th><th><?php echo _l('meeting_date'); ?></th><th><?php echo _l('notes'); ?></th></tr></thead>
                  <tbody>
                    <?php foreach ((array)$oneonone as $o): ?>
                    <tr>
                      <td><?php echo get_staff_full_name($o['staff_id']); ?></td>
                      <td><?php echo !empty($o['meeting_date']) ? _d($o['meeting_date']) : '-'; ?></td>
                      <td><?php echo htmlspecialchars(substr($o['notes'] ?? '', 0, 80)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($oneonone)): ?><tr><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (has_permission('hrm', '', 'edit')): ?>
<div class="modal fade" id="survey_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('add'); ?> <?php echo _l('survey'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/engagement_survey_add')); ?>
      <div class="modal-body">
        <?php echo render_input('title', 'title', '', 'text', ['required' => true]); ?>
        <?php echo render_date_input('date_from', 'date_from', ''); ?>
        <?php echo render_date_input('date_to', 'date_to', ''); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="oneonone_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('add'); ?> <?php echo _l('one_on_one_note'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/oneonone_add')); ?>
      <div class="modal-body">
        <div class="form-group">
          <label><?php echo _l('staff'); ?></label>
          <select name="staff_id" class="form-control selectpicker" data-width="100%" data-live-search="true" required>
            <?php foreach ((array)($staff_list ?? []) as $s): ?>
            <option value="<?php echo $s['staffid']; ?>"><?php echo ($s['firstname'] ?? '') . ' ' . ($s['lastname'] ?? ''); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php echo render_date_input('meeting_date', 'meeting_date', ''); ?>
        <div class="form-group">
          <label><?php echo _l('notes'); ?></label>
          <textarea name="notes" class="form-control" rows="4" required></textarea>
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
