<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="#" onclick="new_onboarding_template(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add'); ?> <?php echo _l('onboarding_templates'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('name'); ?></th>
    <th><?php echo _l('description'); ?></th>
    <th><?php echo _l('checklist'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
 <tbody>
    <?php foreach((array)$onboarding_templates as $c){
        $checklist_preview = '';
        if (!empty($c['checklist_items'])) {
            $items = json_decode($c['checklist_items'], true);
            $arr = is_array($items) ? $items : [];
            $lines = array_map(function($x) { return is_array($x) ? ($x['name'] ?? $x['text'] ?? '') : (string)$x; }, $arr);
            $checklist_preview = implode(', ', array_slice(array_filter($lines), 0, 3));
            if (count($lines) > 3) $checklist_preview .= '...';
        }
    ?>
    <tr>
       <td><?php echo htmlspecialchars($c['id']); ?></td>
       <td><?php echo htmlspecialchars($c['name']); ?></td>
       <td><?php echo htmlspecialchars($c['description'] ?? ''); ?></td>
       <td><?php echo htmlspecialchars($checklist_preview ?: '-'); ?></td>
       <td>
         <a href="#" onclick="edit_onboarding_template(this,<?php echo (int)$c['id']; ?>); return false" data-name="<?php echo htmlspecialchars($c['name']); ?>" data-description="<?php echo htmlspecialchars($c['description'] ?? ''); ?>" data-checklist-items="<?php echo htmlspecialchars($c['checklist_items'] ?? '[]'); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
          <a href="<?php echo admin_url('hrm/delete_onboarding_template/'.(int)$c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>
<div class="modal fade" id="onboarding_template_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(admin_url('hrm/onboarding_template')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('onboarding_templates'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name"><?php echo _l('name'); ?></label>
                    <input type="text" name="name" id="onboarding_template_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description"><?php echo _l('description'); ?></label>
                    <textarea name="description" id="onboarding_template_description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="onboarding_template_checklist_items"><?php echo _l('checklist_items'); ?></label>
                    <textarea name="checklist_items_raw" id="onboarding_template_checklist_items" class="form-control" rows="6" placeholder="<?php echo _l('checklist_items_one_per_line'); ?>"></textarea>
                    <p class="text-muted"><?php echo _l('checklist_items_one_per_line'); ?></p>
                </div>
                <input type="hidden" name="id" id="onboarding_template_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
</div>
<script>
function new_onboarding_template() {
    $('#onboarding_template_id').val('');
    $('#onboarding_template_name').val('');
    $('#onboarding_template_description').val('');
    $('#onboarding_template_checklist_items').val('');
    $('#onboarding_template_modal').modal('show');
}
function edit_onboarding_template(el, id) {
    var raw = $(el).data('checklist-items');
    var items = [];
    try { items = (typeof raw === 'string' ? JSON.parse(raw || '[]') : (raw || [])); } catch(e) {}
    var lines = Array.isArray(items) ? items.map(function(x){ return typeof x === 'object' ? (x.name || x.text || '') : String(x); }).filter(Boolean) : [];
    $('#onboarding_template_id').val(id);
    $('#onboarding_template_name').val($(el).data('name'));
    $('#onboarding_template_description').val($(el).data('description') || '');
    $('#onboarding_template_checklist_items').val(lines.join('\n'));
    $('#onboarding_template_modal').modal('show');
}
</script>
