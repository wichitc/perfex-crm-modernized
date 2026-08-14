<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="vueApp">
    <div class="panel_s">
        <div class="panel-body">
            <div class="project_receipt">
                <?php echo form_hidden('_project_id', $project->id); ?>
                <?php render_datatable(array(
                    _l('id'),
                    _l('stock_received_docket_code'),
                    _l('supplier_name'),
                    _l('Buyer'),
                    _l('reference_purchase_order'),
                    _l('day_vouchers'),
                    _l('total_tax_money'),
                    _l('total_goods_money'),
                    _l('value_of_inventory'),
                    _l('total_money'),
                    _l('status_label'),
                ),'table_manage_goods_receipt',['purchase_sm' => 'purchase_sm']); ?>
            </div>
        </div>
    </div>
</div>