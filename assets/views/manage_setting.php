<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
    <div class="row">
  
   <div class="col-md-3">
    <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
      <?php
      $i = 0;
      $icons = [
          'asset_group' => 'fa-cubes',
          'asset_unit' => 'fa-cube',
          'asset_location' => 'fa-location-arrow',
          'custom_fields' => 'fa-list-alt',
          'webhooks' => 'fa-plug',
          'notifications' => 'fa-bell',
      ];
      $current_group = isset($group) ? $group : 'asset_group';
      foreach ($tab as $tab_group) {
          $active = ($current_group == $tab_group) ? " class='active'" : "";
          ?>
        <li<?php echo $active; ?>>
        <a href="<?php echo admin_url('assets/setting?group='.$tab_group); ?>" data-group="<?php echo htmlspecialchars($tab_group); ?>">
        <i class="fa <?php echo $icons[$tab_group] ?? 'fa-cog'; ?>"></i>  <?php echo htmlspecialchars(_l($tab_group)); ?></a>
        </li>
        <?php ++$i;
      } ?>
      </ul>
      
      
  </div>
  <div class="col-md-9">
    <div class="panel_s">
     <div class="panel-body">

        <?php $this->load->view($tabs['view']); ?>
        
     </div>
  </div>
</div>
<div class="clearfix"></div>
</div>
<?php echo form_close(); ?>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>
<script>
  appValidateForm($('form'),{group_name:'required', unit_name:'required'});
</script>

</body>
</html>
