<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="<?php echo admin_url('hrm/job_descriptions'); ?>" class="btn btn-default pull-left mright5"><?php echo _l('job_descriptions'); ?></a>
    <a href="#" onclick="new_job_position(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('new_job_position'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('id'); ?></th>
    <th><?php echo _l('job_position'); ?></th>
    <th><?php echo _l('job_description_groups'); ?></th>
    <th><?php echo _l('duties_responsibilities'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
 <tbody>
    <?php foreach($positions as $job){ ?>
    <tr>
       <td><?php echo htmlspecialchars($job['position_id']); ?></td>
       <td><?php echo htmlspecialchars($job['position_name'] ?? ''); ?></td>
       <td><?php 
$gid = (int)($job['job_description_group_id'] ?? 0);
$gname = '-';
foreach ((array)($job_groups ?? []) as $g) { if ((int)$g['id'] === $gid) { $gname = htmlspecialchars($g['name']); break; } }
echo $gname;
?></td>
       <td><?php 
$duties = strip_tags($job['duties_responsibilities'] ?? '');
echo $duties !== '' ? (strlen($duties) > 60 ? substr($duties, 0, 60) . '...' : $duties) : '-'; 
?></td>
       <td>
         <span class="hide job-duties-data" data-position-id="<?php echo (int)$job['position_id']; ?>"><?php echo htmlspecialchars($job['duties_responsibilities'] ?? ''); ?></span>
         <a href="#" onclick="edit_job_position(this,<?php echo htmlspecialchars($job['position_id']); ?>); return false" data-name="<?php echo htmlspecialchars($job['position_name'] ?? ''); ?>" data-group-id="<?php echo (int)($job['job_description_group_id'] ?? 0); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
          <a href="<?php echo admin_url('hrm/delete_job_position/'.$job['position_id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
       </td>
    </tr>
    <?php } ?>
 </tbody>
</table>       
<div class="modal fade" id="job_position" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(admin_url('hrm/job_position')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_job_position'); ?></span>
                    <span class="add-title"><?php echo _l('new_job_position'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                     <div id="additional"></div>   
                     <div class="form">     
                        <?php 
                        echo render_input('position_name','job_position'); ?>
                        <?php 
                        $group_options = array_merge([['id' => '', 'name' => _l('dropdown_non_selected_tex')]], (array)($job_groups ?? []));
                        echo render_select('job_description_group_id', $group_options, ['id', 'name'], 'job_description_groups', ''); 
                        ?>
                        <?php echo render_textarea('duties_responsibilities', 'duties_responsibilities', '', ['rows' => 6]); ?>
                    </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
            </div><!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
</body>
</html>
