<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="<?php echo admin_url('hrm/contract_template'); ?>" class="btn btn-info pull-left display-block">
        <?php echo _l('add'); ?> <?php echo _l('contract_templates'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('name'); ?></th>
    <th><?php echo _l('contract_type'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
 <tbody>
    <?php foreach((array)$contract_templates as $c){ ?>
    <tr>
       <td><?php echo htmlspecialchars($c['id']); ?></td>
       <td><?php echo htmlspecialchars($c['name']); ?></td>
       <td><?php 
           $ct_name = '-';
           foreach((array)$contract_type as $t) { if(isset($t['id_contracttype']) && $t['id_contracttype'] == $c['contract_type_id']) { $ct_name = $t['name_contracttype']; break; } }
           echo htmlspecialchars($ct_name);
       ?></td>
       <td>
         <a href="<?php echo admin_url('hrm/contract_template/'.$c['id']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
          <a href="<?php echo admin_url('hrm/delete_contract_template/'.(int)$c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>       
</div>
