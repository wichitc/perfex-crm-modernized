<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('hr_documents'); ?></h4>
            <hr class="hr-panel-heading" />
            <?php if (has_permission('hrm', '', 'edit')): ?>
            <button type="button" class="btn btn-success mbot15" data-toggle="modal" data-target="#doc_modal"><i class="fa fa-plus"></i> <?php echo _l('add'); ?></button>
            <?php endif; ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('title'); ?></th><th><?php echo _l('category'); ?></th><th><?php echo _l('date_create'); ?></th><th><?php echo _l('options'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$documents as $d): ?>
                <tr>
                  <td><?php echo htmlspecialchars($d['title']); ?></td>
                  <td><?php echo htmlspecialchars($d['category'] ?? '-'); ?></td>
                  <td><?php echo _dt($d['date_added'] ?? ''); ?></td>
                  <td>
                    <?php if (!empty($d['file_path'])): ?>
                    <a href="<?php echo site_url($d['file_path']); ?>" class="btn btn-default btn-sm" target="_blank" download><i class="fa fa-download"></i></a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($documents)): ?><tr><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (has_permission('hrm', '', 'edit')): ?>
<div class="modal fade" id="doc_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('add'); ?> <?php echo _l('hr_documents'); ?></h4>
      </div>
      <?php echo form_open_multipart(admin_url('hrm/document_add')); ?>
      <div class="modal-body">
        <?php echo render_input('title', 'title', '', 'text', ['required' => true]); ?>
        <?php echo render_input('category', 'category', '', 'text'); ?>
        <div class="form-group">
          <label><?php echo _l('description'); ?></label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label><?php echo _l('file'); ?></label>
          <input type="file" name="document_file" class="form-control">
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
