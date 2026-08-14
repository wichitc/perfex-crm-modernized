<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-4">
                            <i class="fa fa-upload"></i> <?php echo _l('import_assets'); ?>
                        </h4>

                        <div class="alert alert-info">
                            <h4><i class="fa fa-info-circle"></i> <?php echo _l('import_instructions'); ?></h4>
                            <ol>
                                <li><?php echo _l('import_step_1'); ?></li>
                                <li><?php echo _l('import_step_2'); ?></li>
                                <li><?php echo _l('import_step_3'); ?></li>
                            </ol>
                            <a href="<?php echo admin_url('assets/export_template'); ?>" class="btn btn-info">
                                <i class="fa fa-download"></i> <?php echo _l('download_template'); ?>
                            </a>
                        </div>

                        <?php echo form_open_multipart(admin_url('assets/process_import'), ['id' => 'importForm']); ?>
                        <div class="form-group">
                            <label for="import_file"><?php echo _l('select_csv_file'); ?> <span class="text-danger">*</span></label>
                            <input type="file" name="import_file" id="import_file" class="form-control" accept=".csv" required>
                            <small class="text-muted"><?php echo _l('csv_file_only'); ?></small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-upload"></i> <?php echo _l('import'); ?>
                        </button>
                        <?php echo form_close(); ?>

                        <hr>

                        <h5><?php echo _l('expected_columns'); ?></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('column_name'); ?></th>
                                        <th><?php echo _l('required'); ?></th>
                                        <th><?php echo _l('description'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>asset_code</td><td><span class="label label-success"><?php echo _l('auto'); ?></span></td><td><?php echo _l('column_asset_code_desc'); ?></td></tr>
                                    <tr><td>asset_name</td><td><span class="label label-danger"><?php echo _l('yes'); ?></span></td><td><?php echo _l('column_asset_name_desc'); ?></td></tr>
                                    <tr><td>quantity</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_quantity_desc'); ?></td></tr>
                                    <tr><td>unit</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_unit_desc'); ?></td></tr>
                                    <tr><td>group</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_group_desc'); ?></td></tr>
                                    <tr><td>location</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_location_desc'); ?></td></tr>
                                    <tr><td>purchase_date</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_purchase_date_desc'); ?></td></tr>
                                    <tr><td>warranty_months</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_warranty_desc'); ?></td></tr>
                                    <tr><td>price</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_price_desc'); ?></td></tr>
                                    <tr><td>depreciation_months</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_depreciation_desc'); ?></td></tr>
                                    <tr><td>supplier</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_supplier_desc'); ?></td></tr>
                                    <tr><td>description</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_description_desc'); ?></td></tr>
                                    <tr><td>manufacturer</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_manufacturer_desc'); ?></td></tr>
                                    <tr><td>model</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_model_desc'); ?></td></tr>
                                    <tr><td>serial</td><td><span class="label label-warning"><?php echo _l('no'); ?></span></td><td><?php echo _l('column_serial_desc'); ?></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
