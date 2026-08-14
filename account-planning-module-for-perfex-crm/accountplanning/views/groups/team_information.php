<h4 class="customer-profile-group-heading"><?php echo htmlspecialchars(_l('team_information')); ?></h4>
<div class="clearfix"></div>
<!-- <?php $selected=( isset($account) ? $account->shipping_country : '' ); ?> -->
<?php echo form_open(admin_url('accountplanning/update_team_information/'.$account->id),array('id'=>'due-diligence-form')); ?>
    <?php  if (has_permission('accountplanning', '', 'edit')) { ?>
        <div class="btn-bottom-toolbar btn-toolbar-container-out text-right ap-calc100-20left">
            <button class="btn btn-info only-save team-information-form-submiter">
        <?php echo htmlspecialchars(_l( 'submit')); ?></button>
        </div>
    <?php } ?> 
<?php
$selected = [];
foreach ($data_pmax_team as $value) {
    $selected[] = $value['rel_id'];
}
echo render_select('pmax_team[]',$teams,array('staffid',array('firstname','lastname')), _l('company_team',get_option('companyname')),$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
<br>
<table class="table dt-table scroll-responsive">
<thead>
    <th><?php echo htmlspecialchars(_l('name')); ?></th>
    <th><?php echo htmlspecialchars(_l('department')); ?></th>
    <th><?php echo htmlspecialchars(_l('title')); ?></th>
    <th><?php echo htmlspecialchars(_l('phonenumber')); ?></th>
    <th><?php echo htmlspecialchars(_l('client_email')); ?></th>
    <th><?php echo htmlspecialchars(_l('staff_add_edit_skype')); ?></th>
    <th><?php echo htmlspecialchars(_l('other')); ?></th>
    <th><?php echo htmlspecialchars(_l('serving_status')); ?></th>
</thead>
<tbody>
    
    <?php foreach($data_pmax_team as $staff){ ?>
    <tr>
        <td><?php echo htmlspecialchars(trim(($staff['firstname'] ?? '').' '.($staff['lastname'] ?? ''))); ?></td>
        <td><?php echo htmlspecialchars($staff['department'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($staff['title'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($staff['phonenumber'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($staff['email'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($staff['skype'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($staff['facebook'] ?? ''); ?></td>
        <td><?php echo '-'; ?></td>
    </tr>
    <?php } ?>
</tbody>
</table>
<?php 
$selected = [];
foreach ($data_client_team as $value) {
    $selected[] = $value['id'];
}
echo render_select('client_team[]',$client_team,array('id',array('firstname','lastname')),'client_team',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
<br>
<table class="table dt-table scroll-responsive">
<thead>
    <th><?php echo htmlspecialchars(_l('name')); ?></th>
    <th><?php echo htmlspecialchars(_l('title')); ?></th>
    <th><?php echo htmlspecialchars(_l('role')); ?></th>
    <th><?php echo htmlspecialchars(_l('phonenumber')); ?></th>
    <th><?php echo htmlspecialchars(_l('client_email')); ?></th>
    <th><?php echo htmlspecialchars(_l('staff_add_edit_skype')); ?></th>
    <th><?php echo htmlspecialchars(_l('other')); ?></th>
    <th><?php echo htmlspecialchars(_l('personality')); ?></th>
    <th><?php echo htmlspecialchars(_l('highlight_note')); ?></th>
    <th><?php echo htmlspecialchars(_l('working_status')); ?></th>
</thead>
<tbody>
    <?php foreach($data_client_team as $staff){ ?>
    <tr>
        <td><?php echo htmlspecialchars($staff['firstname'].' '.$staff['lastname']); ?></td>
        <td><?php echo htmlspecialchars($staff['title']); ?></td>
        <td><?php echo '-'; ?></td>
        <td><?php echo htmlspecialchars($staff['phonenumber'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($staff['email'] ?? ''); ?></td>
        <td><?php echo '-'; ?></td>
        <td><?php echo '-'; ?></td>
        <td><?php echo '-'; ?></td>
        <td><?php echo '-'; ?></td>
        <?php if($staff['active'] == 1){ ?>
            <td class="text-success">Active</td>
        <?php }else{ ?>
            <td class="text-danger">Inactive</td>
        <?php } ?>
    </tr>
    <?php } ?>
</tbody>
</table>
<?php echo form_close(); ?>