<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if(has_permission('accounting_setting', '', 'create') || is_admin()){ ?>
<div>
    <a href="#" class="btn btn-primary mbot15 add-new-budget-category">
        <i class="fa fa-plus-circle"></i> <?php echo _l('add_new', _l('budget_categories')); ?>
    </a>
</div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <?php 
            $table_data = array(
                _l('name'),
                _l('created_at'),
                );
            render_datatable($table_data, 'budget-categories');
        ?>
    </div>
</div>

<div class="modal fade" id="budget-category-modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo _l('budget_categories')?></h4>
         </div>
         <?php echo form_open(admin_url('accounting/budget_category'), array('id'=>'budget-category-form'));?>
         <?php echo form_hidden('id'); ?>
         
         <div class="modal-body">
            <?php echo render_input('name','name', '', 'text', array('required' => 'true')); ?>
         </div>
         
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-primary btn-submit"><?php echo _l('submit'); ?></button>
         </div>
         <?php echo form_close(); ?>  
      </div>
   </div>
</div>
