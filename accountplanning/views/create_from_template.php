<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="bold"><?php echo _l('ap_create_from_template'); ?>: <?php echo htmlspecialchars($template->name); ?></h4>
                <?php echo form_open(admin_url('accountplanning/do_create_from_template/' . $template->id)); ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_select('client_id', $clients, ['userid', 'company'], 'client', '', ['required' => true]); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('subject', 'subject', $template->subject, 'text', ['required' => true]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_select('date', $month, ['id', 'name'], 'Period', '', ['required' => true]); ?>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-info"><?php echo _l('ap_create_from_template'); ?></button>
                        <a href="<?php echo admin_url('accountplanning/templates'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
