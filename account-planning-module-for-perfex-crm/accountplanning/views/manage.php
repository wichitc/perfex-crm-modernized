<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                
            <div class="panel_s">
                <div class="panel-body">
                    <div class="ap-action-buttons">
                    <a href="<?php echo admin_url('accountplanning/new_account'); ?>" class="btn btn-info pull-left"><?php echo htmlspecialchars(_l('new_account')); ?></a>
                    <a href="<?php echo admin_url('accountplanning/templates'); ?>" class="btn btn-default"><?php echo htmlspecialchars(_l('ap_templates')); ?></a>
                    <a href="<?php echo admin_url('accountplanning/kanban'); ?>" class="btn btn-default"><?php echo htmlspecialchars(_l('ap_kanban_view')); ?></a>
                    <?php if (is_admin()) { ?><a href="<?php echo admin_url('accountplanning/settings'); ?>" class="btn btn-default"><i class="fa fa-cog"></i> <?php echo htmlspecialchars(_l('settings')); ?></a><?php } ?>
                    </div>
                    <a href="#" data-toggle="modal" data-target="#accountplanning_bulk_action" class="bulk-actions-btn table-btn hide" data-table=".table-account-planning"><?php echo htmlspecialchars(_l('bulk_actions')); ?></a>
                    <div class="pull-right">
                        <?php
                        $saved = isset($saved_filters) ? $saved_filters : [];
                        if (!empty($saved)) {
                            echo '<select id="ap_saved_filter" class="selectpicker" data-width="180px"><option value="">' . _l('ap_saved_filters') . '</option>';
                            foreach ($saved as $sf) {
                                $f = json_decode($sf['filters'], true);
                                echo '<option value="' . $sf['id'] . '" data-filters="' . htmlspecialchars($sf['filters']) . '">' . htmlspecialchars($sf['name']) . '</option>';
                            }
                            echo '</select>';
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row mtop15" id="ap_filter_panel">
                        <div class="col-md-12">
                            <div class="form-inline ap-filter-inline">
                                <input type="text" id="ap_filter_search" class="form-control ap-filter-item" placeholder="<?php echo _l('search'); ?>" style="width:150px;">
                                <?php echo render_select('ap_filter_client', isset($clients) ? $clients : [], ['userid', 'company'], 'client', '', ['data-width' => '150px', 'data-none-selected-text' => _l('dropdown_non_selected_tex')], [], ' ap-filter-client-group', '', false); ?>
                                <?php
                                $statuses = [['id' => '', 'name' => _l('plan_status')], ['id' => 'draft', 'name' => _l('ap_status_draft')], ['id' => 'in_progress', 'name' => _l('ap_status_in_progress')], ['id' => 'review', 'name' => _l('ap_status_review')], ['id' => 'completed', 'name' => _l('ap_status_completed')], ['id' => 'archived', 'name' => _l('ap_status_archived')]];
                                echo render_select('ap_filter_status', $statuses, ['id', 'name'], '', '', ['data-width' => '120px'], [], '', '', false);
                                $bcg_opts = [['id' => '', 'name' => _l('bcg_model')], ['id' => 'Question marks', 'name' => 'Question marks'], ['id' => 'Stars', 'name' => 'Stars'], ['id' => 'Dogs', 'name' => 'Dogs'], ['id' => 'Cash cows', 'name' => 'Cash cows']];
                                echo render_select('ap_filter_bcg', $bcg_opts, ['id', 'name'], '', '', ['data-width' => '120px'], [], '', '', false);
                                $staff_for_pic = isset($staff_for_pic) ? $staff_for_pic : [];
                                echo render_select('ap_filter_pic', $staff_for_pic, ['staffid', ['firstname', 'lastname']], _l('pic'), '', ['data-width' => '150px', 'data-none-selected-text' => _l('dropdown_non_selected_tex')], [], '', '', false);
                                $has_rel_opts = [['id' => '', 'name' => _l('ap_has_relation')], ['id' => 'invoice', 'name' => _l('ap_has_invoice')], ['id' => 'proposal', 'name' => _l('ap_has_proposal')]];
                                echo render_select('ap_filter_has_rel', $has_rel_opts, ['id', 'name'], '', '', ['data-width' => '120px'], [], '', '', false);
                                ?>
                                <div class="checkbox mtop5" style="margin-left:8px;">
                                    <input type="checkbox" id="ap_filter_overdue" name="ap_filter_overdue" value="1">
                                    <label for="ap_filter_overdue"><?php echo _l('ap_overdue_tasks'); ?></label>
                                </div>
                                <input type="text" id="ap_filter_date_from" class="form-control datepicker ap-filter-item" placeholder="<?php echo _l('date'); ?> from" style="width:110px;" autocomplete="off">
                                <input type="text" id="ap_filter_date_to" class="form-control datepicker ap-filter-item" placeholder="<?php echo _l('date'); ?> to" style="width:110px;" autocomplete="off">
                                <button type="button" class="btn btn-default ap-filter-item" onclick="ap_apply_filters();"><i class="fa fa-search"></i></button>
                                <button type="button" class="btn btn-default ap-filter-item" onclick="ap_clear_filters();"><i class="fa fa-times"></i></button>
                                <a href="#" data-toggle="modal" data-target="#ap_save_filter_modal" class="btn btn-default" id="ap_save_filter_btn" style="display:none;"><i class="fa fa-save"></i> <?php echo _l('ap_save_filter'); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                                    
                    <div class="modal fade bulk_actions" id="accountplanning_bulk_action" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                         <div class="modal-content">
                          <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                           <h4 class="modal-title"><?php echo htmlspecialchars(_l('bulk_actions')); ?></h4>
                       </div>
                       <div class="modal-body">
                          <?php if(has_permission('accountplanning','','delete')){ ?>
                          <div class="checkbox checkbox-danger">
                            <input type="checkbox" name="mass_delete" id="ap_mass_delete">
                            <label for="ap_mass_delete"><?php echo htmlspecialchars(_l('mass_delete')); ?></label>
                        </div>
                        <hr class="mass_delete_separator" />
                        <?php } ?>
                        <?php if(has_permission('accountplanning','','edit')){ ?>
                        <div id="ap_bulk_status_change" class="mass_delete_separator">
                           <label><?php echo htmlspecialchars(_l('plan_status')); ?></label>
                           <?php
                           $statuses = [['id' => '', 'name' => _l('dropdown_non_selected_tex')], ['id' => 'draft', 'name' => _l('ap_status_draft')], ['id' => 'in_progress', 'name' => _l('ap_status_in_progress')], ['id' => 'review', 'name' => _l('ap_status_review')], ['id' => 'completed', 'name' => _l('ap_status_completed')], ['id' => 'archived', 'name' => _l('ap_status_archived')]];
                           echo render_select('ap_bulk_status', $statuses, ['id', 'name'], '', '', [], [], '', '', false);
                           ?>
                       </div>
                        <?php } ?>
                        <?php if(has_permission('accountplanning','','create')){ ?>
                        <div id="ap_bulk_copy" class="mass_delete_separator">
                           <div class="checkbox">
                            <input type="checkbox" name="ap_bulk_copy_check" id="ap_bulk_copy_check">
                            <label for="ap_bulk_copy_check"><?php echo htmlspecialchars(_l('ap_bulk_copy')); ?></label>
                           </div>
                           <div id="ap_bulk_copy_options" style="display:none; margin-top:8px;">
                            <label><?php echo _l('date'); ?></label>
                            <?php echo render_select('ap_bulk_copy_date', isset($month) ? $month : [], ['id', 'name'], '', '', ['data-width' => '100%'], [], '', '', false); ?>
                           </div>
                       </div>
                        <?php } ?>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></button>
                       <a href="#" class="btn btn-info" onclick="accountplanning_bulk_action(this); return false;"><?php echo htmlspecialchars(_l('confirm')); ?></a>
                   </div>
               </div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->
       </div><!-- /.modal -->
       <div class="modal fade" id="ap_save_filter_modal" tabindex="-1">
           <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                       <h4 class="modal-title"><?php echo _l('ap_save_filter'); ?></h4>
                   </div>
                   <div class="modal-body">
                       <div class="form-group">
                           <label><?php echo _l('ap_filter_name'); ?></label>
                           <input type="text" id="ap_save_filter_name" class="form-control" placeholder="<?php echo _l('ap_filter_name'); ?>">
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                       <button type="button" class="btn btn-info" onclick="ap_do_save_filter();"><?php echo _l('submit'); ?></button>
                   </div>
               </div>
           </div>
       </div>
                        
                           <div class="clearfix mtop20"></div>
                           <?php
                           $table_data = array();
                           $_table_data = array(
                            '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="account-planning"><label></label></div>',
                            _l('id'),
                            _l('subject'),
                            _l('client_name'),
                            _l('time'),
                            _l('plan_status'),
                            _l('objective'),
                            _l('revenue_next_year'),
                            _l('margin'),
                            _l('wallet_share'),
                            _l('client_status'),
                            _l('bcg_model'),
                            );
                           $cf_table = get_table_custom_fields('accountplanning');
                           foreach ($cf_table as $cf) {
                               $_table_data[] = _maybe_translate_custom_field_name($cf['name'], $cf['slug']);
                           }
                           foreach($_table_data as $_t){
                            array_push($table_data,$_t);
                        }
						//print_r($table_data);
                        render_datatable($table_data,'account-planning');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('accountplanning/copy_settings'); ?>
<?php init_tail(); ?>
<style>
/* Action buttons: flex with gaps */
.ap-action-buttons { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.ap-action-buttons .btn { margin: 0; }
/* Filter row: flex layout with consistent gaps */
#ap_filter_panel .ap-filter-inline {
  display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-start;
}
#ap_filter_panel .ap-filter-inline .form-group { margin: 0; }
.ap-filter-client-group label { margin-right: 8px; }
</style>
<script>
    var ap_tableAPI;
    function ap_get_filter_params() {
        var p = {};
        var s = $('#ap_filter_search').val();
        if (s) p.filter_search = s;
        var c = $('select[name="ap_filter_client"]').val();
        if (c) p.filter_client_id = c;
        var st = $('select[name="ap_filter_status"]').val();
        if (st) p.filter_status = st;
        var bcg = $('select[name="ap_filter_bcg"]').val();
        if (bcg) p.filter_bcg_model = bcg;
        var pic = $('select[name="ap_filter_pic"]').val();
        if (pic) p.filter_pic = pic;
        var hasRel = $('select[name="ap_filter_has_rel"]').val();
        if (hasRel === 'invoice') p.filter_has_invoice = '1';
        else if (hasRel === 'proposal') p.filter_has_proposal = '1';
        if ($('#ap_filter_overdue').prop('checked')) p.filter_overdue = '1';
        var df = $('#ap_filter_date_from').val();
        if (df) p.filter_date_from = df;
        var dt = $('#ap_filter_date_to').val();
        if (dt) p.filter_date_to = dt;
        return p;
    }
    function ap_get_table_url() {
        var base = '<?php echo admin_url("accountplanning"); ?>';
        var p = ap_get_filter_params();
        var q = $.param(p);
        return base + (q ? '?' + q : '');
    }
    function ap_apply_filters() {
        var url = ap_get_table_url();
        if (ap_tableAPI) ap_tableAPI.ajax.url(url).load();
        $('#ap_save_filter_btn').show();
    }
    function ap_clear_filters() {
        $('#ap_filter_search').val('');
        $('select[name="ap_filter_client"]').val('').selectpicker('refresh');
        $('select[name="ap_filter_status"]').val('').selectpicker('refresh');
        $('select[name="ap_filter_bcg"]').val('').selectpicker('refresh');
        $('select[name="ap_filter_pic"]').val('').selectpicker('refresh');
        $('select[name="ap_filter_has_rel"]').val('').selectpicker('refresh');
        $('#ap_filter_date_from').val('');
        $('#ap_filter_date_to').val('');
        $('#ap_filter_overdue').prop('checked', false);
        ap_apply_filters();
        $('#ap_save_filter_btn').hide();
    }
    function ap_do_save_filter() {
        var name = $('#ap_save_filter_name').val();
        if (!name) { alert_float('warning', '<?php echo _l('ap_filter_name'); ?>'); return; }
        $.post(admin_url + 'accountplanning/save_filter', { name: name, filters: JSON.stringify(ap_get_filter_params()) }).done(function(r) {
            if (r.success) {
                alert_float('success', r.message);
                $('#ap_save_filter_modal').modal('hide');
                window.location.reload();
            } else alert_float('danger', r.message || '<?php echo _l('something_went_wrong'); ?>');
        });
    }
    function ap_load_saved_filter(id) {
        var opt = $('#ap_saved_filter option:selected');
        if (!opt.length || !opt.val()) return;
        var f = opt.data('filters');
        if (f) {
            try {
                var data = typeof f === 'string' ? JSON.parse(f) : f;
                if (data.filter_search) $('#ap_filter_search').val(data.filter_search);
                if (data.filter_client_id) $('select[name="ap_filter_client"]').val(data.filter_client_id).selectpicker('refresh');
                if (data.filter_status) $('select[name="ap_filter_status"]').val(data.filter_status).selectpicker('refresh');
                if (data.filter_bcg_model) $('select[name="ap_filter_bcg"]').val(data.filter_bcg_model).selectpicker('refresh');
                if (data.filter_pic) $('select[name="ap_filter_pic"]').val(data.filter_pic).selectpicker('refresh');
                if (data.filter_has_invoice) $('select[name="ap_filter_has_rel"]').val('invoice').selectpicker('refresh');
                else if (data.filter_has_proposal) $('select[name="ap_filter_has_rel"]').val('proposal').selectpicker('refresh');
                if (data.filter_overdue) $('#ap_filter_overdue').prop('checked', true);
                if (data.filter_date_from) $('#ap_filter_date_from').val(data.filter_date_from);
                if (data.filter_date_to) $('#ap_filter_date_to').val(data.filter_date_to);
                ap_apply_filters();
            } catch(e) {}
        }
    }
    $(function(){
        $('#ap_filter_search').val('');
        $('#ap_filter_date_from').val('');
        $('#ap_filter_date_to').val('');
        $('#ap_save_filter_btn').hide();
        try {
            $('select[name="ap_filter_client"], select[name="ap_filter_status"], select[name="ap_filter_bcg"], select[name="ap_filter_pic"], select[name="ap_filter_has_rel"], #ap_saved_filter').val('').selectpicker('refresh');
        } catch (e) {}
        var hasAdded = (get_url_param('added') === '1');
        if (hasAdded && window.history && window.history.replaceState) {
            window.history.replaceState({}, '', admin_url + 'accountplanning');
        }
        ap_tableAPI = initDataTable('.table-account-planning', ap_get_table_url(), [0], [0]);
        $('#ap_saved_filter').on('change', function() { ap_load_saved_filter($(this).val()); });
        $('input[name="exclude_inactive"]').on('change',function(){
            if (ap_tableAPI) ap_tableAPI.ajax.reload();
        });
        if (typeof $().datepicker === 'function') {
            $('#ap_filter_date_from, #ap_filter_date_to').datepicker({ format: app.options.date_format || 'yyyy-mm-dd' });
        }
        if (typeof $().selectpicker === 'function') {
            $('#ap_filter_panel .selectpicker').selectpicker('refresh');
        }
        $('#ap_bulk_copy_check').on('change', function() {
            $('#ap_bulk_copy_options').toggle($(this).prop('checked'));
        });
    });
    function accountplanning_bulk_action(event) {
        var ids = [];
        var rows = $('.table-account-planning').find('tbody tr');
        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') == true) {
                ids.push(checkbox.val());
            }
        });
        if (ids.length === 0) {
            alert_float('warning', '<?php echo _l('ap_select_at_least_one'); ?>');
            return false;
        }
        var mass_delete = $('#ap_mass_delete').prop('checked');
        var bulk_status = $('select[name="ap_bulk_status"]').val();
        var bulk_copy = $('#ap_bulk_copy_check').prop('checked');
        var bulk_copy_date = $('select[name="ap_bulk_copy_date"]').val();
        var data = { ids: ids };
        if (mass_delete) {
            if (!confirm('<?php echo _l('ap_confirm_mass_delete'); ?>')) return false;
            data.mass_delete = true;
        } else if (bulk_status) {
            data.bulk_status = bulk_status;
        } else if (bulk_copy && bulk_copy_date) {
            data.bulk_copy = true;
            data.bulk_copy_date = bulk_copy_date;
        } else if (bulk_copy && !bulk_copy_date) {
            alert_float('warning', '<?php echo _l('ap_select_date_for_copy'); ?>');
            return false;
        } else {
            alert_float('warning', '<?php echo _l('ap_select_action'); ?>');
            return false;
        }
        $(event).addClass('disabled');
        $.post(admin_url + 'accountplanning/bulk_action', data, 'json').done(function(response) {
            if (response.success) {
                alert_float('success', response.message);
                $('.table-account-planning').DataTable().ajax.reload();
                $('#accountplanning_bulk_action').modal('hide');
            } else {
                alert_float('danger', response.message || '<?php echo _l('something_went_wrong'); ?>');
            }
        }).fail(function() {
            alert_float('danger', '<?php echo _l('something_went_wrong'); ?>');
        }).always(function() {
            $(event).removeClass('disabled');
        });
    }


</script>
</body>
</html>
