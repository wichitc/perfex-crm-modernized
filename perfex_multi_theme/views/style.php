<?php $dashboard_image = get_option('dashboard_bg_image'); 
$login_image = get_option('login_bg_image'); 
if($dashboard_image){ ?>
<style>
    .active_theme {
        background-image: url("<?php echo base_url('uploads/company/'.$dashboard_image); ?>") !important;
         background-size: cover;
    
     }
</style>

 <?php } ?>

