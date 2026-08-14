<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('policies_qa'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('policies_qa'); ?> - Q&A and Company Policy</p>
            <?php if (has_permission('hrm', '', 'edit')): ?>
            <button type="button" class="btn btn-info mbot15" onclick="new_policy(); return false;"><i class="fa fa-plus"></i> <?php echo _l('add'); ?></button>
            <?php endif; ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('id'); ?></th><th><?php echo _l('title'); ?></th><th><?php echo _l('category'); ?></th><th><?php echo _l('is_faq'); ?></th><th><?php echo _l('options'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$policies as $p): ?>
                <tr>
                  <td><?php echo $p['id']; ?></td>
                  <td><?php echo htmlspecialchars($p['title']); ?></td>
                  <td><?php echo htmlspecialchars($p['category'] ?? '-'); ?></td>
                  <td><?php echo !empty($p['is_faq']) ? _l('yes') : _l('no'); ?></td>
                  <td>
                    <a href="#" class="btn btn-default btn-sm btn-view-policy" data-id="<?php echo $p['id']; ?>"><i class="fa fa-eye"></i></a>
                    <?php if (has_permission('hrm', '', 'edit')): ?>
                    <a href="#" class="btn btn-default btn-sm btn-edit-policy" data-id="<?php echo $p['id']; ?>"><i class="fa fa-pencil"></i></a>
                    <a href="<?php echo admin_url('hrm/delete_policy/'.$p['id']); ?>" class="btn btn-danger btn-sm _delete"><i class="fa fa-remove"></i></a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($policies)): ?><tr><td></td><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Policy Modal -->
<div class="modal fade" id="policy_modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="policy_modal_title"><?php echo _l('add'); ?></h4>
      </div>
      <form id="policy_form">
        <div class="modal-body">
          <input type="hidden" name="id" id="policy_id" value="">
          <?php echo render_input('title', 'title', '', 'text', ['id'=>'policy_title']); ?>
          <?php echo render_input('category', 'category', '', 'text', ['id'=>'policy_category']); ?>
          <div class="form-group">
            <label for="policy_is_faq"><?php echo _l('is_faq'); ?></label>
            <select name="is_faq" id="policy_is_faq" class="form-control selectpicker">
              <option value="0"><?php echo _l('no'); ?></option>
              <option value="1"><?php echo _l('yes'); ?></option>
            </select>
          </div>
          <div class="form-group">
            <label for="policy_content"><?php echo _l('content'); ?></label>
            <textarea name="content" id="policy_content" class="form-control" rows="8"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Policy View Modal -->
<div class="modal fade" id="policy_view_modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="policy_view_title"></h4>
      </div>
      <div class="modal-body" id="policy_view_content"></div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  function new_policy() {
    $('#policy_id').val('');
    $('#policy_title').val('');
    $('#policy_category').val('');
    $('#policy_is_faq').val('0').selectpicker('refresh');
    $('#policy_content').val('');
    $('#policy_modal_title').text('<?php echo _l('add'); ?>');
    $('#policy_modal').modal('show');
  }
  $(document).on('click', '.btn-view-policy', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.get(admin_url + 'hrm/get_policy/' + id).done(function(p) {
      if (p) {
        $('#policy_view_title').text(p.title);
        $('#policy_view_content').html(p.content || '<p class="text-muted"><?php echo _l('no_results'); ?></p>');
        $('#policy_view_modal').modal('show');
      }
    });
  });
  $(document).on('click', '.btn-edit-policy', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.get(admin_url + 'hrm/get_policy/' + id).done(function(p) {
      if (p) {
        $('#policy_id').val(p.id);
        $('#policy_title').val(p.title);
        $('#policy_category').val(p.category || '');
        $('#policy_is_faq').val(p.is_faq ? '1' : '0').selectpicker('refresh');
        $('#policy_content').val(p.content || '');
        $('#policy_modal_title').text('<?php echo _l('edit'); ?>');
        $('#policy_modal').modal('show');
      }
    });
  });
  $('#policy_form').on('submit', function(e) {
    e.preventDefault();
    $.post(admin_url + 'hrm/policy', $(this).serialize()).done(function(r) {
      r = typeof r === 'string' ? JSON.parse(r) : r;
      if (r.success) { alert_float('success', r.message); $('#policy_modal').modal('hide'); location.reload(); }
    });
  });
</script>
