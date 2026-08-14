<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <a href="<?php echo admin_url('hrm/onboarding'); ?>" class="btn btn-default mbot15"><i class="fa fa-arrow-left"></i> <?php echo _l('back'); ?></a>
            <h4 class="no-margin"><?php echo _l('onboarding'); ?> - <?php echo get_staff_full_name($record['staff_id']); ?></h4>
            <hr class="hr-panel-heading" />
            <p><strong><?php echo _l('template'); ?>:</strong> <?php echo htmlspecialchars($record['template_name'] ?? '-'); ?></p>
            <p><strong><?php echo _l('status'); ?>:</strong> <span class="label label-<?php echo $record['status'] == 'completed' ? 'success' : 'info'; ?>"><?php echo htmlspecialchars($record['status'] ?? 'in_progress'); ?></span></p>
            <p><strong><?php echo _l('started_date'); ?>:</strong> <?php echo !empty($record['started_date']) ? _d($record['started_date']) : '-'; ?></p>
            <?php if (!empty($record['completed_date'])): ?>
            <p><strong><?php echo _l('completed_date'); ?>:</strong> <?php echo _d($record['completed_date']); ?></p>
            <?php endif; ?>
            <hr />
            <h5><?php echo _l('checklist'); ?></h5>
            <?php 
            $checklist_items = [];
            if (!empty($record['checklist_items'])) {
              $items = json_decode($record['checklist_items'], true);
              $checklist_items = is_array($items) ? $items : (is_string($record['checklist_items']) ? explode("\n", $record['checklist_items']) : []);
            }
            $checklist_data = [];
            if (!empty($record['checklist_data'])) {
              $checklist_data = json_decode($record['checklist_data'], true);
              $checklist_data = is_array($checklist_data) ? $checklist_data : [];
            }
            if (!empty($checklist_items)):
              foreach ($checklist_items as $i => $item):
                $item_name = is_array($item) ? ($item['name'] ?? $item['text'] ?? 'Item ' . ($i+1)) : (string)$item;
                $done = !empty($checklist_data[$i]) || (isset($checklist_data[$i]) && $checklist_data[$i] === true) || (isset($checklist_data[$item_name]) && $checklist_data[$item_name]);
            ?>
            <div class="checkbox">
              <label><input type="checkbox" <?php echo $done ? 'checked' : ''; ?> disabled> <?php echo htmlspecialchars($item_name); ?></label>
            </div>
            <?php endforeach; ?>
            <?php elseif (empty($checklist_items) && empty($record['checklist_items'])): ?>
            <p class="text-muted"><?php echo _l('no_checklist_items'); ?></p>
            <?php endif; ?>
            <?php if (has_permission('hrm', '', 'edit') && ($record['status'] ?? '') != 'completed'): ?>
            <hr />
            <?php echo form_open(admin_url('hrm/onboarding_update_checklist/'.$record['id'])); ?>
            <div class="form-group">
              <label><input type="checkbox" name="mark_completed" value="1"> <?php echo _l('mark_as_completed'); ?></label>
            </div>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            <?php echo form_close(); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
