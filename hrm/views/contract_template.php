<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo !empty($contract_template) ? _l('edit') . ' ' . _l('contract_templates') : _l('add') . ' ' . _l('contract_templates'); ?>
                        </h4>
                        <hr class="hr-panel-heading" />

                        <?php echo form_open(admin_url('hrm/contract_template' . (!empty($contract_template) ? '/' . (int) $contract_template['id'] : ''))); ?>
                        <?php echo form_hidden('id', !empty($contract_template) ? (int) $contract_template['id'] : ''); ?>

                        <div class="form-group">
                            <label for="name" class="control-label">
                                <?php echo _l('name'); ?> <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                value="<?php echo !empty($contract_template['name']) ? htmlspecialchars($contract_template['name']) : ''; ?>"
                                required
                            >
                        </div>

                        <?php
                        $contract_type_options = [];
                        foreach ((array) $contract_type as $type) {
                            $contract_type_options[] = [
                                'id'   => $type['id_contracttype'],
                                'name' => $type['name_contracttype'],
                            ];
                        }
                        $selected_contract_type = !empty($contract_template['contract_type_id']) ? (int) $contract_template['contract_type_id'] : '';
                        ?>
                        <div class="form-group">
                            <label for="contract_type_id" class="control-label">
                                <?php echo _l('contract_type'); ?> <span class="text-danger">*</span>
                            </label>
                            <select name="contract_type_id" id="contract_type_id" class="form-control selectpicker" data-live-search="true" required>
                                <option value=""></option>
                                <?php foreach ($contract_type_options as $option) { ?>
                                    <option value="<?php echo (int) $option['id']; ?>" <?php echo ((string) $selected_contract_type === (string) $option['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($option['name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <?php echo render_textarea('content', _l('content'), !empty($contract_template['content']) ? $contract_template['content'] : '', ['rows' => 18, 'class' => 'tinymce']); ?>

                        <div class="form-group">
                            <label class="control-label"><?php echo _l('available_merge_fields'); ?></label>
                            <p class="text-muted"><?php echo _l('merge_fields_template_help'); ?></p>
                            <div class="hrm-merge-fields-cheat" style="border:1px solid #eee;background:#fafafa;padding:10px;">
                                <?php foreach (hrm_contract_merge_fields_definition() as $group) { ?>
                                    <div style="margin-bottom:6px;">
                                        <strong><?php echo htmlspecialchars($group['label']); ?>:</strong>
                                        <?php foreach ($group['fields'] as $token => $label) { ?>
                                            <code class="hrm-mf-token"
                                                  style="cursor:pointer;margin:2px;display:inline-block;"
                                                  data-token="<?php echo htmlspecialchars($token); ?>"
                                                  title="<?php echo htmlspecialchars($label); ?>">
                                                <?php echo htmlspecialchars($token); ?>
                                            </code>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <small class="text-muted"><?php echo _l('merge_fields_click_to_copy'); ?></small>
                            </div>
                        </div>

                        <?php echo render_textarea('merge_fields', _l('merge_fields_notes'), !empty($contract_template['merge_fields']) ? $contract_template['merge_fields'] : '', ['rows' => 3]); ?>

                        <div class="btn-bottom-toolbar text-right">
                            <a href="<?php echo admin_url('hrm/setting?group=contract_templates'); ?>" class="btn btn-default">
                                <?php echo _l('back'); ?>
                            </a>
                            <button type="submit" class="btn btn-info">
                                <?php echo _l('submit'); ?>
                            </button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function () {
    $(document).on('click', '.hrm-mf-token', function () {
        var token = $(this).data('token');
        if (!token) return;
        var ta = document.createElement('textarea');
        ta.value = token;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.focus(); ta.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(ta);
        if (typeof alert_float === 'function') {
            alert_float('success', token + ' copied');
        }
    });
});
</script>
</body>
</html>
