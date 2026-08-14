<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="#" onclick="new_insurance_type(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add'); ?> <?php echo _l('insurance_type'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('from_month'); ?></th>
    <th><?php echo _l('social_insurance'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
 <tbody>
    <?php foreach ((array) $insurance_type as $it) { ?>
    <tr>
       <td><?php echo htmlspecialchars($it['id']); ?></td>
       <td><?php echo _d($it['from_month']); ?></td>
       <td><?php echo htmlspecialchars(($it['social_company'] ?? '').'% / '.( $it['social_staff'] ?? '').'%'); ?></td>
       <td>
         <a href="#" onclick="edit_insurance_type(this,<?php echo (int)$it['id']; ?>); return false" class="btn btn-default btn-icon" data-from_month="<?php echo htmlspecialchars($it['from_month']); ?>" data-social_company="<?php echo htmlspecialchars($it['social_company'] ?? ''); ?>" data-social_staff="<?php echo htmlspecialchars($it['social_staff'] ?? ''); ?>" data-labor_accident_company="<?php echo htmlspecialchars($it['labor_accident_company'] ?? ''); ?>" data-labor_accident_staff="<?php echo htmlspecialchars($it['labor_accident_staff'] ?? ''); ?>" data-health_company="<?php echo htmlspecialchars($it['health_company'] ?? ''); ?>" data-health_staff="<?php echo htmlspecialchars($it['health_staff'] ?? ''); ?>" data-unemployment_company="<?php echo htmlspecialchars($it['unemployment_company'] ?? ''); ?>" data-unemployment_staff="<?php echo htmlspecialchars($it['unemployment_staff'] ?? ''); ?>"><i class="fa fa-pencil-square"></i></a>
          <a href="<?php echo admin_url('hrm/delete_insurance_type/'.(int)$it['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>

<hr class="hr-panel-heading" />
<h4><?php echo _l('insurance_conditions'); ?></h4>
<?php echo form_open(admin_url('hrm/insurance_conditions_setting')); ?>
<div class="row">
    <div class="col-md-6">
        <h5><?php echo _l('install_1'); ?></h5>
        <?php render_hrm_yes_no_option('sign_a_labor_contract', 'sign_a_labor_contract'); ?>
        <?php render_hrm_yes_no_option('maternity_leave_to_return_to_work', 'maternity_leave_to_return_to_work'); ?>
        <?php render_hrm_yes_no_option('unpaid_leave_to_return_to_work', 'unpaid_leave_to_return_to_work'); ?>
        <?php render_hrm_yes_no_option('increase_the_premium', 'increase_the_premium'); ?>
    </div>
    <div class="col-md-6">
        <h5><?php echo _l('install_2'); ?></h5>
        <?php render_hrm_yes_no_option('contract_paid_for_unemployment', 'contract_paid_for_unemployment'); ?>
        <?php render_hrm_yes_no_option('maternity_leave_regime', 'maternity_leave_regime'); ?>
        <?php render_hrm_yes_no_option('reduced_premiums', 'reduced_premiums'); ?>
    </div>
</div>
<button type="submit" class="btn btn-info mtop15"><?php echo _l('submit'); ?></button>
<?php echo form_close(); ?>

<div class="modal fade" id="insurance_type_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(admin_url('hrm/insurance_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('insurance_type'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="from_month"><?php echo _l('from_month'); ?></label>
                    <select name="from_month" id="insurance_from_month" class="form-control selectpicker" data-width="100%" required>
                        <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                        <?php foreach ((array) $month as $m) { ?>
                        <option value="<?php echo htmlspecialchars($m['id']); ?>"><?php echo htmlspecialchars($m['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h5><?php echo _l('social_insurance'); ?></h5>
                        <?php echo render_input('social_company', 'company', '', 'text', ['placeholder' => '%']); ?>
                        <?php echo render_input('social_staff', 'worker', '', 'text', ['placeholder' => '%']); ?>
                    </div>
                    <div class="col-md-6">
                        <h5><?php echo _l('labor_accident_insurance'); ?></h5>
                        <?php echo render_input('labor_accident_company', 'company', '', 'text', ['placeholder' => '%']); ?>
                        <?php echo render_input('labor_accident_staff', 'worker', '', 'text', ['placeholder' => '%']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h5><?php echo _l('health_insurance'); ?></h5>
                        <?php echo render_input('health_company', 'company', '', 'text', ['placeholder' => '%']); ?>
                        <?php echo render_input('health_staff', 'worker', '', 'text', ['placeholder' => '%']); ?>
                    </div>
                    <div class="col-md-6">
                        <h5><?php echo _l('unemployment_insurance'); ?></h5>
                        <?php echo render_input('unemployment_company', 'company', '', 'text', ['placeholder' => '%']); ?>
                        <?php echo render_input('unemployment_staff', 'worker', '', 'text', ['placeholder' => '%']); ?>
                    </div>
                </div>
                <input type="hidden" name="id" id="insurance_type_id">
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
function new_insurance_type() {
    var $m = $('#insurance_type_modal');
    $m.find('#insurance_type_id').val('');
    $m.find('#insurance_from_month').val('');
    $m.find('input[name="social_company"]').val('');
    $m.find('input[name="social_staff"]').val('');
    $m.find('input[name="labor_accident_company"]').val('');
    $m.find('input[name="labor_accident_staff"]').val('');
    $m.find('input[name="health_company"]').val('');
    $m.find('input[name="health_staff"]').val('');
    $m.find('input[name="unemployment_company"]').val('');
    $m.find('input[name="unemployment_staff"]').val('');
    $m.modal('show');
}
function edit_insurance_type(el, id) {
    var $m = $('#insurance_type_modal');
    $m.find('#insurance_type_id').val(id);
    $m.find('#insurance_from_month').val($(el).data('from_month'));
    $m.find('input[name="social_company"]').val($(el).data('social_company')||'');
    $m.find('input[name="social_staff"]').val($(el).data('social_staff')||'');
    $m.find('input[name="labor_accident_company"]').val($(el).data('labor_accident_company')||'');
    $m.find('input[name="labor_accident_staff"]').val($(el).data('labor_accident_staff')||'');
    $m.find('input[name="health_company"]').val($(el).data('health_company')||'');
    $m.find('input[name="health_staff"]').val($(el).data('health_staff')||'');
    $m.find('input[name="unemployment_company"]').val($(el).data('unemployment_company')||'');
    $m.find('input[name="unemployment_staff"]').val($(el).data('unemployment_staff')||'');
    if (typeof $.fn.selectpicker !== 'undefined') { $m.find('.selectpicker').selectpicker('refresh'); }
    $m.modal('show');
}
</script>
