<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('performance_management'); ?></h4>
            <hr class="hr-panel-heading" />
            <?php if (has_permission('hrm', '', 'edit')): ?>
            <button type="button" class="btn btn-success mbot15" data-toggle="modal" data-target="#review_modal"><i class="fa fa-plus"></i> <?php echo _l('add'); ?> <?php echo _l('performance_review'); ?></button>
            <?php endif; ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('staff'); ?></th><th><?php echo _l('review_period'); ?></th><th><?php echo _l('review_date'); ?></th><th><?php echo _l('rating'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$reviews as $r): ?>
                <tr>
                  <td><?php echo get_staff_full_name($r['staff_id']); ?></td>
                  <td><?php echo htmlspecialchars($r['review_period'] ?? '-'); ?></td>
                  <td><?php echo !empty($r['review_date']) ? _d($r['review_date']) : '-'; ?></td>
                  <td><?php echo htmlspecialchars($r['rating'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($reviews)): ?><tr><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (has_permission('hrm', '', 'edit')): ?>
<div class="modal fade" id="review_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('add'); ?> <?php echo _l('performance_review'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/performance_review_add')); ?>
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
        <?php echo render_input('review_period', 'review_period', '', 'text'); ?>
        <?php echo render_date_input('review_date', 'review_date', ''); ?>
        <?php echo render_input('rating', 'rating', '', 'number', ['step' => '0.01']); ?>
        <div class="form-group">
          <label><?php echo _l('notes'); ?></label>
          <textarea name="notes" class="form-control" rows="4"></textarea>
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
