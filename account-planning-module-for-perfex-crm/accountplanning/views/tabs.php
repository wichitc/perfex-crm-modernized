<?php
    $this->load->model('accountplanning_model');
    $customer_tabs = $this->accountplanning_model->get_accountplanning_tabs($account->id);
?>
<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
   <?php
   $visible_customer_profile_tabs = get_option('visible_customer_profile_tabs');
   if($visible_customer_profile_tabs != 'all') {
      $visible_customer_profile_tabs = unserialize($visible_customer_profile_tabs);
   }
   foreach($customer_tabs as $tab){
      if((isset($tab['visible']) && $tab['visible'] == true) || !isset($tab['visible'])){
        ?>
      <li class="<?php if($tab['name'] == 'profile'){echo 'active ';} ?>customer_tab_<?php echo htmlspecialchars($tab['name']); ?>">
        <a data-group="<?php echo htmlspecialchars($tab['name']); ?>" href="<?php echo htmlspecialchars($tab['url']); ?>"><i class="<?php echo htmlspecialchars($tab['icon']); ?> menu-icon" aria-hidden="true"></i><?php echo htmlspecialchars($tab['lang']); ?>
            <?php if(isset($tab['id']) && $tab['id'] == 'reminders'){
              $total_reminders = total_rows('tblreminders',
                  array(
                   'isnotified'=>0,
                   'staff'=>get_staff_user_id(),
                   'rel_type'=>'customer',
                   'rel_id'=>$client->userid
                   )
                  );
              if($total_reminders > 0){
                echo '<span class="badge">'.$total_reminders.'</span>';
              }
          }
          ?>
      </a>
  </li>
  <?php } ?>
  <?php } ?>
</ul>