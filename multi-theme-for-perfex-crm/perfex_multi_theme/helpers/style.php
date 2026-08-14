<?php
$login_image = get_option('login_bg_image'); 
if($login_image){ ?>
<style>
    body {
    background-image: url("<?php echo base_url('uploads/company/'.$login_image); ?>") !important;
    background-repeat: no-repeat  !important;
    background-size: cover  !important;
    
 }
</style>
 <?php } ?>


