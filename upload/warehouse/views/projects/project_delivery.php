<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="vueApp">
    <div class="panel_s">
        <div class="panel-body">
            <div class="project_receipt">
                <?php echo form_hidden('_project_id', $project->id); ?>
                <?php render_datatable(array(
                    _l('id'),
                    _l('goods_delivery_code'),
                    _l('customer_name'),
                    _l('day_vouchers'),
                    _l('invoices'),
                    _l('to'),
                    _l('address'),
                    _l('staff_id'),
                    _l('status_label'),
                    _l('delivery_status'),
                ),'table_manage_delivery',['delivery_sm' => 'delivery_sm']); ?>
            </div>
        </div>
    </div>
</div>