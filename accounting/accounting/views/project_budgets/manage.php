<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons mbot15">
                            <?php if (has_permission('acc_project_budgets', '', 'create') || is_admin()) { ?>
                            <a href="<?php echo admin_url('accounting/project_budget'); ?>" class="btn btn-primary pull-left display-block">
                                <i class="fa fa-plus-circle"></i> <?php echo _l('new_project_budget'); ?>
                            </a>
                            <?php } ?>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="table-responsive">
                            <?php
                            $table_data = [
                                _l('project'),
                                _l('project_manager'),
                                _l('start_date'),
                                _l('end_date'),
                                _l('allocated_budget'),
                                _l('actual_spent'),
                                _l('remaining_budget'),
                                _l('status'),
                                _l('options'),
                            ];
                            render_datatable($table_data, 'project-budgets');
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
        initDataTable('.table-project-budgets', admin_url + 'accounting/project_budgets_table', [8], [8], {}, [0, 'asc']);
    });
</script>
</body>
</html>

