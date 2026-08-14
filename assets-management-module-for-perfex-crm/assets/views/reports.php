<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-4">
                            <i class="fa fa-file-text"></i> <?php echo _l('asset_reports'); ?>
                        </h4>

                        <div class="row">
                            <!-- Assets Report -->
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('assets_list_report'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><?php echo _l('assets_list_report_desc'); ?></p>
                                        <?php echo form_open(admin_url('assets/export_report/assets'), ['method' => 'get', 'target' => '_blank']); ?>
                                        <div class="form-group">
                                            <label><?php echo _l('filter_by_group'); ?></label>
                                            <select name="group" class="selectpicker" data-width="100%">
                                                <option value=""><?php echo _l('all_groups'); ?></option>
                                                <?php foreach ($groups as $g): ?>
                                                <option value="<?php echo $g['group_id']; ?>"><?php echo htmlspecialchars($g['group_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo _l('filter_by_location'); ?></label>
                                            <select name="location" class="selectpicker" data-width="100%">
                                                <option value=""><?php echo _l('all_locations'); ?></option>
                                                <?php foreach ($locations as $l): ?>
                                                <option value="<?php echo $l['location_id']; ?>"><?php echo htmlspecialchars($l['location']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo _l('format'); ?></label>
                                            <select name="format" class="selectpicker" data-width="100%">
                                                <option value="pdf">HTML (Print to PDF)</option>
                                                <option value="excel">Excel</option>
                                                <option value="csv">CSV</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-download"></i> <?php echo _l('generate_report'); ?>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Depreciation Report -->
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('depreciation_report'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><?php echo _l('depreciation_report_desc'); ?></p>
                                        <?php echo form_open(admin_url('assets/export_report/depreciation'), ['method' => 'get', 'target' => '_blank']); ?>
                                        <div class="form-group">
                                            <label><?php echo _l('filter_by_group'); ?></label>
                                            <select name="group" class="selectpicker" data-width="100%">
                                                <option value=""><?php echo _l('all_groups'); ?></option>
                                                <?php foreach ($groups as $g): ?>
                                                <option value="<?php echo $g['group_id']; ?>"><?php echo htmlspecialchars($g['group_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo _l('format'); ?></label>
                                            <select name="format" class="selectpicker" data-width="100%">
                                                <option value="pdf">HTML (Print to PDF)</option>
                                                <option value="excel">Excel</option>
                                                <option value="csv">CSV</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-download"></i> <?php echo _l('generate_report'); ?>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Maintenance Report -->
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('maintenance_report'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><?php echo _l('maintenance_report_desc'); ?></p>
                                        <?php echo form_open(admin_url('assets/export_report/maintenance'), ['method' => 'get', 'target' => '_blank']); ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo render_date_input('date_from', 'date_from'); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo render_date_input('date_to', 'date_to'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo _l('status'); ?></label>
                                            <select name="status" class="selectpicker" data-width="100%">
                                                <option value=""><?php echo _l('all_statuses'); ?></option>
                                                <option value="scheduled"><?php echo _l('scheduled'); ?></option>
                                                <option value="completed"><?php echo _l('completed'); ?></option>
                                                <option value="overdue"><?php echo _l('overdue'); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo _l('format'); ?></label>
                                            <select name="format" class="selectpicker" data-width="100%">
                                                <option value="pdf">HTML (Print to PDF)</option>
                                                <option value="excel">Excel</option>
                                                <option value="csv">CSV</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-download"></i> <?php echo _l('generate_report'); ?>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Checkout Report -->
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('checkout_report'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><?php echo _l('checkout_report_desc'); ?></p>
                                        <?php echo form_open(admin_url('assets/export_report/checkouts'), ['method' => 'get', 'target' => '_blank']); ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo render_date_input('date_from', 'date_from'); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo render_date_input('date_to', 'date_to'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo _l('status'); ?></label>
                                            <select name="status" class="selectpicker" data-width="100%">
                                                <option value=""><?php echo _l('all_statuses'); ?></option>
                                                <option value="checked_out"><?php echo _l('checked_out'); ?></option>
                                                <option value="returned"><?php echo _l('returned'); ?></option>
                                                <option value="overdue"><?php echo _l('overdue'); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo _l('format'); ?></label>
                                            <select name="format" class="selectpicker" data-width="100%">
                                                <option value="pdf">HTML (Print to PDF)</option>
                                                <option value="excel">Excel</option>
                                                <option value="csv">CSV</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-download"></i> <?php echo _l('generate_report'); ?>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Audit Report -->
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('audit_report'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><?php echo _l('audit_report_desc'); ?></p>
                                        <?php echo form_open(admin_url('assets/export_report/audit'), ['method' => 'get', 'target' => '_blank']); ?>
                                        <div class="form-group">
                                            <label><?php echo _l('format'); ?></label>
                                            <select name="format" class="selectpicker" data-width="100%">
                                                <option value="pdf">HTML (Print to PDF)</option>
                                                <option value="excel">Excel</option>
                                                <option value="csv">CSV</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-download"></i> <?php echo _l('generate_report'); ?>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Utilization Report -->
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo _l('utilization_report'); ?></h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><?php echo _l('utilization_report_desc'); ?></p>
                                        <?php echo form_open(admin_url('assets/export_report/utilization'), ['method' => 'get', 'target' => '_blank']); ?>
                                        <div class="form-group">
                                            <label><?php echo _l('filter_by_group'); ?></label>
                                            <select name="group" class="selectpicker" data-width="100%">
                                                <option value=""><?php echo _l('all_groups'); ?></option>
                                                <?php foreach ($groups as $g): ?>
                                                <option value="<?php echo $g['group_id']; ?>"><?php echo htmlspecialchars($g['group_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo _l('format'); ?></label>
                                            <select name="format" class="selectpicker" data-width="100%">
                                                <option value="pdf">HTML (Print to PDF)</option>
                                                <option value="excel">Excel</option>
                                                <option value="csv">CSV</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-download"></i> <?php echo _l('generate_report'); ?>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
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
