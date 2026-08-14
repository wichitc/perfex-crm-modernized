<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="bold"><?php echo _l('ap_kanban_view'); ?></h4>
                <a href="<?php echo admin_url('accountplanning'); ?>" class="btn btn-default mright5"><?php echo _l('back'); ?></a>
                <a href="<?php echo admin_url('accountplanning/export_excel'); ?>" class="btn btn-success mright5"><i class="fa fa-file-excel-o"></i> <?php echo _l('ap_export_excel'); ?></a>
                <div class="clearfix mtop15"></div>
                <div class="row" id="ap_kanban_board">
                    <?php
                    $statuses = ['draft' => _l('ap_status_draft'), 'in_progress' => _l('ap_status_in_progress'), 'review' => _l('ap_status_review'), 'completed' => _l('ap_status_completed'), 'archived' => _l('ap_status_archived')];
                    foreach ($statuses as $sid => $sname) {
                        $col = isset($plans[$sid]) ? $plans[$sid] : [];
                    ?>
                    <div class="col-md-2 col-sm-4 ap-kanban-col" data-status="<?php echo htmlspecialchars($sid); ?>">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h5 class="bold"><?php echo htmlspecialchars($sname); ?> (<?php echo count($col); ?>)</h5>
                                <div class="ap-kanban-cards">
                                    <?php foreach ($col as $p) { ?>
                                    <div class="ap-kanban-card" data-id="<?php echo $p['id']; ?>">
                                        <a href="<?php echo admin_url('accountplanning/view/' . $p['id']); ?>">
                                            <strong>#<?php echo $p['id']; ?> <?php echo htmlspecialchars($p['subject']); ?></strong>
                                        </a>
                                        <br><small><?php echo htmlspecialchars($p['company']); ?></small>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function(){ 
    if (typeof $.fn.sortable !== 'undefined') {
        $('.ap-kanban-cards').sortable({ connectWith: '.ap-kanban-cards', cursor: 'move', receive: function(e, ui) {
            var card = ui.item;
            var newStatus = card.closest('.ap-kanban-col').data('status');
            var id = card.data('id');
            if (id && newStatus) {
                $.post(admin_url + 'accountplanning/update_status_ajax', { id: id, status: newStatus }).done(function(r) {
                    if (r && r.success) alert_float('success', '<?php echo _l('updated_successfully'); ?>');
                }).fail(function() {
                    alert_float('danger', '<?php echo _l('something_went_wrong'); ?>');
                    $(this).sortable('cancel');
                });
            }
        }});
    }
});
</script>
<style>
.ap-kanban-card { background:#fff; border:1px solid #ddd; padding:10px; margin-bottom:8px; border-radius:4px; cursor:move; }
.ap-kanban-card:hover { background:#f9f9f9; }
.ap-kanban-col { min-height:400px; }
</style>
</body>
</html>
