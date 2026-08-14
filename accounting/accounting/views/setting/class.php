<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(has_permission('accounting_setting', '', 'create')){ ?>

<div>
	<a href="#" class="btn btn-primary mbot15 add-new-class"><?php echo _l('add_new', _l('class')); ?></a>
</div>
<?php } ?>

<div class="row">
	<div class="col-md-12">
		<?php 
			$table_data = array(
				_l('name'),
				_l('description'),
				);
			render_datatable($table_data,'class');
		?>
	</div>
</div>
<div class="clearfix"></div>



<div class="modal fade" id="class-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo _l('class')?></h4>
         </div>
         <?php echo form_open_multipart(admin_url('accounting/class'),array('id'=>'class-form'));?>
         <?php echo form_hidden('id'); ?>
         
         <div class="modal-body">
            <?php echo render_input('name','name'); ?>
            <div class="row">
                <div class="col-md-12">
                  <p class="bold"><?php echo _l('dt_expense_description'); ?></p>
                  <?php echo render_textarea('description',''); ?>
                </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-primary btn-submit"><?php echo _l('submit'); ?></button>
         </div>
         <?php echo form_close(); ?>  
      </div>
   </div>
</div>