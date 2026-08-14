<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('job_descriptions'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('job_descriptions'); ?> - <?php echo _l('job_description_groups'); ?> & <?php echo _l('job_position'); ?></p>
            <a href="<?php echo admin_url('hrm/setting?group=job_position'); ?>" class="btn btn-info mbot15"><?php echo _l('manage') . ' ' . _l('job_position'); ?></a>
            <div class="row">
              <div class="col-md-6">
                <div class="_buttons mbot15">
                  <?php if (has_permission('hrm', '', 'edit')): ?>
                  <a href="#" onclick="new_job_description_group(); return false;" class="btn btn-info pull-left display-block">
                    <?php echo _l('new_job_description_group'); ?>
                  </a>
                  <?php endif; ?>
                </div>
                <table class="table table-striped">
                  <thead><tr><th><?php echo _l('id'); ?></th><th><?php echo _l('name'); ?></th><th><?php echo _l('options'); ?></th></tr></thead>
                  <tbody>
                    <?php foreach ((array)$job_groups as $g): ?>
                    <tr>
                      <td><?php echo $g['id']; ?></td>
                      <td><?php echo htmlspecialchars($g['name'] ?? ''); ?></td>
                      <td>
                        <?php if (has_permission('hrm', '', 'edit')): ?>
                        <a href="#" onclick="edit_job_description_group(this,<?php echo (int)$g['id']; ?>); return false" data-name="<?php echo htmlspecialchars($g['name'] ?? ''); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
                        <a href="<?php echo admin_url('hrm/delete_job_description_group/'.$g['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($job_groups)): ?><tr><td colspan="3"><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
                  </tbody>
                </table>
              </div>
              <div class="col-md-6">
                <h5><?php echo _l('job_position'); ?></h5>
                <table class="table table-striped dt-table">
                  <thead><tr><th><?php echo _l('id'); ?></th><th><?php echo _l('job_position'); ?></th><th><?php echo _l('job_description_groups'); ?></th><th><?php echo _l('duties_responsibilities'); ?></th></tr></thead>
                  <tbody>
                    <?php foreach ((array)$positions as $p): 
                      $gid = (int)($p['job_description_group_id'] ?? 0);
                      $gname = '-';
                      foreach ((array)($job_groups ?? []) as $g) { if ((int)$g['id'] === $gid) { $gname = htmlspecialchars($g['name'] ?? ''); break; } }
                    ?>
                    <tr>
                      <td><?php echo $p['position_id']; ?></td>
                      <td><?php echo htmlspecialchars($p['position_name'] ?? ''); ?></td>
                      <td><?php echo $gname; ?></td>
                      <td><?php echo !empty($p['duties_responsibilities']) ? substr(strip_tags($p['duties_responsibilities']), 0, 80) . '...' : '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
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
<div class="modal fade" id="job_description_group" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrm/job_description_group')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title"><?php echo _l('edit_job_description_group'); ?></span>
          <span class="add-title"><?php echo _l('new_job_description_group'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional_job_description_group"></div>
            <?php echo render_input('name', 'name', ''); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<?php endif; ?>

<?php init_tail(); ?>
<script src="<?php echo module_dir_url('hrm', 'assets/js/jobdescriptions.js'); ?>"></script>
