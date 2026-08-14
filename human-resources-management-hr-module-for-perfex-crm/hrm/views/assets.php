<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('asset_management'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('asset_management'); ?> - <?php echo _l('my_assets'); ?></p>
            <?php if (has_permission('hrm', '', 'edit')): ?>
            <button type="button" class="btn btn-success mbot15" data-toggle="modal" data-target="#asset_modal" onclick="new_asset(); return false;"><i class="fa fa-plus"></i> <?php echo _l('add'); ?></button>
            <?php endif; ?>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('name'); ?></th><th><?php echo _l('asset_code'); ?></th><th><?php echo _l('category'); ?></th><th><?php echo _l('staff'); ?></th><th><?php echo _l('status'); ?></th><th><?php echo _l('options'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$assets as $a): ?>
                <tr>
                  <td><?php echo htmlspecialchars($a['name']); ?></td>
                  <td><?php echo htmlspecialchars($a['asset_code'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($a['category'] ?? '-'); ?></td>
                  <td><?php echo !empty($a['assigned_to']) ? get_staff_full_name($a['assigned_to']) : '-'; ?></td>
                  <td><?php echo htmlspecialchars($a['condition'] ?? '-'); ?></td>
                  <td>
                    <?php if (has_permission('hrm', '', 'edit')): ?>
                    <a href="#" class="btn btn-default btn-sm btn-edit-asset" data-id="<?php echo $a['id']; ?>"><i class="fa fa-pencil"></i></a>
                    <a href="<?php echo admin_url('hrm/delete_asset/'.$a['id']); ?>" class="btn btn-danger btn-sm _delete"><i class="fa fa-remove"></i></a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($assets)): ?><tr><td></td><td></td><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (has_permission('hrm', '', 'edit')): ?>
<div class="modal fade" id="asset_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('add'); ?> <?php echo _l('my_assets'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/asset'), ['id' => 'asset_form']); ?>
        <div class="modal-body">
          <input type="hidden" name="id" id="asset_id" value="">
          <?php echo render_input('name', 'name', '', 'text', ['required' => true]); ?>
          <?php echo render_input('asset_code', 'asset_code', '', 'text'); ?>
          <?php echo render_input('category', 'category', '', 'text'); ?>
          <div class="form-group">
            <label for="assigned_to"><?php echo _l('staff'); ?></label>
            <select name="assigned_to" id="assigned_to" class="form-control selectpicker" data-width="100%" data-live-search="true">
              <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
              <?php foreach ((array)($staff_list ?? []) as $s): ?>
              <option value="<?php echo $s['staffid']; ?>"><?php echo ($s['firstname'] ?? '') . ' ' . ($s['lastname'] ?? ''); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php echo render_date_input('assigned_date', 'assigned_date', ''); ?>
          <?php echo render_input('condition', 'hrm_asset_condition', '', 'text'); ?>
          <div class="form-group">
            <label for="asset_notes"><?php echo _l('notes'); ?></label>
            <textarea name="notes" id="asset_notes" class="form-control" rows="3"></textarea>
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
<script>
  function new_asset() {
    $('#asset_id').val('');
    $('#asset_form')[0].reset();
    $('.selectpicker').selectpicker('refresh');
  }
  $(document).on('click', '.btn-edit-asset', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.get(admin_url + 'hrm/get_asset/' + id).done(function(a) {
      if (a) {
        $('#asset_id').val(a.id);
        $('input[name="name"]').val(a.name);
        $('input[name="asset_code"]').val(a.asset_code || '');
        $('input[name="category"]').val(a.category || '');
        $('#assigned_to').val(a.assigned_to || '').selectpicker('refresh');
        $('input[name="assigned_date"]').val(a.assigned_date || '');
        $('input[name="condition"]').val(a.condition || '');
        $('#asset_notes').val(a.notes || '');
        $('#asset_modal').modal('show');
      }
    });
  });
</script>
