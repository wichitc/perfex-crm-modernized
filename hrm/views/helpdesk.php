<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('hr_helpdesk'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('hr_helpdesk'); ?> - <?php echo _l('policies_qa'); ?></p>
            <button type="button" class="btn btn-success mbot15" data-toggle="modal" data-target="#ticket_modal"><i class="fa fa-plus"></i> <?php echo _l('submit_ticket'); ?></button>
            <table class="table dt-table table-striped">
              <thead><tr><th><?php echo _l('id'); ?></th><th><?php echo _l('staff'); ?></th><th><?php echo _l('title'); ?></th><th><?php echo _l('category'); ?></th><th><?php echo _l('status'); ?></th><th><?php echo _l('date_create'); ?></th><th><?php echo _l('options'); ?></th></tr></thead>
              <tbody>
                <?php foreach ((array)$tickets as $t): ?>
                <tr>
                  <td><?php echo $t['id']; ?></td>
                  <td><?php echo get_staff_full_name($t['staff_id']); ?></td>
                  <td><?php echo htmlspecialchars($t['subject']); ?></td>
                  <td><?php echo htmlspecialchars($t['category'] ?? '-'); ?></td>
                  <td><span class="label label-<?php echo $t['status'] == 'closed' ? 'default' : 'info'; ?>"><?php echo htmlspecialchars($t['status']); ?></span></td>
                  <td><?php echo _dt($t['date_added']); ?></td>
                  <td>
                    <?php if (has_permission('hrm', '', 'edit')): ?>
                    <a href="#" class="btn btn-default btn-sm btn-view-ticket" data-id="<?php echo $t['id']; ?>"><i class="fa fa-eye"></i></a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($tickets)): ?><tr><td></td><td></td><td></td><td></td><td></td><td></td><td><?php echo _l('no_results'); ?></td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="ticket_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo _l('submit_ticket'); ?></h4>
      </div>
      <?php echo form_open(admin_url('hrm/helpdesk_add')); ?>
      <div class="modal-body">
        <?php echo render_input('subject', 'title', '', 'text', ['required' => true]); ?>
        <div class="form-group">
          <label for="ticket_category"><?php echo _l('category'); ?></label>
          <input type="text" name="category" id="ticket_category" class="form-control" placeholder="e.g. Policy, Benefits, Leave">
        </div>
        <div class="form-group">
          <label for="ticket_message"><?php echo _l('message'); ?></label>
          <textarea name="message" id="ticket_message" class="form-control" rows="5" required></textarea>
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

<div class="modal fade" id="ticket_view_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="ticket_view_subject"></h4>
      </div>
      <div class="modal-body" id="ticket_view_message"></div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  $(document).on('click', '.btn-view-ticket', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.get(admin_url + 'hrm/get_helpdesk_ticket/' + id).done(function(t) {
      if (t) {
        $('#ticket_view_subject').text(t.subject);
        $('#ticket_view_message').text(t.message || '<?php echo _l('no_results'); ?>');
        $('#ticket_view_modal').modal('show');
      }
    });
  });
</script>
