<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons mbot15">
                            <?php if (has_permission('acc_imprests', '', 'create') || is_admin()) { ?>
                            <a href="<?php echo admin_url('accounting/add_imprest'); ?>" class="btn btn-primary pull-left display-block">
                                <i class="fa fa-plus-circle"></i> <?php echo _l('acc_new_imprest_request'); ?>
                            </a>
                            <?php } ?>
                            <div class="clearfix"></div>
                        </div>
                        
                        <!-- Filters -->
                        <div class="row mbot15">
                            <div class="col-md-3">
                                <?php echo render_select('filter_project_id', $projects, array('id', 'name'), 'project'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_select('filter_category_id', $categories, array('id', 'name'), 'budget_category'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_select('filter_staff_id', $staff, array('staffid', array('firstname', 'lastname')), 'staff', '', ['data-live-search' => 'true']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php 
                                $statuses = [
                                    ['id' => 'disbursed', 'name' => _l('acc_disbursed')],
                                    ['id' => 'completed', 'name' => _l('acc_completed')],
                                    ['id' => 'pending_refund', 'name' => _l('acc_pending_refund')],
                                    ['id' => 'pending_payment', 'name' => _l('acc_pending_payment')],
                                ];
                                echo render_select('filter_status', $statuses, array('id', 'name'), 'status'); 
                                ?>
                            </div>
                        </div>
                        <hr />
                        
                        <div class="table-responsive">
                            <?php
                            $table_data = [
                                _l('acc_ref_no'),
                                _l('project'),
                                _l('acc_budget_category'),
                                _l('date'),
                                _l('staff'),
                                _l('acc_requested'),
                                _l('acc_retired'),
                                _l('acc_variance'),
                                _l('status'),
                                _l('options'),
                            ];
                            render_datatable($table_data, 'imprests');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        var ImprestServerParams = {
            'project_id': '[name="filter_project_id"]',
            'category_id': '[name="filter_category_id"]',
            'staff_id': '[name="filter_staff_id"]',
            'status': '[name="filter_status"]',
        };
        initDataTable('.table-imprests', admin_url + 'accounting/imprests_table', [9], [9], ImprestServerParams, [0, 'desc']);
        
        $('select[name="filter_project_id"], select[name="filter_category_id"], select[name="filter_staff_id"], select[name="filter_status"]').on('change', function() {
            $('.table-imprests').DataTable().ajax.reload();
        });
    });
</script>
</body>
</html>
