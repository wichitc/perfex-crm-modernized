<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open(admin_url('accounting/covert_pdf_report'),array('id'=>'render_pdf-form')); ?>
<?php echo form_hidden('html'); ?>
<?php echo form_hidden('pdf_name'); ?>
<?php echo form_hidden('orientation'); ?>
<?php echo form_close(); ?>