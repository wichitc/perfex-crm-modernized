<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart(site_url('recruitment/recruitment_portal'), array('id' => 'search_job')); ?>
<?php echo form_close(); ?>
<?php if(!isset($notifications_check)){ ?>
<li class="dropdown notifications-wrapper header-notifications" data-toggle="tooltip" title="<?php echo _l('nav_notifications') ?>" data-placement="bottom">
<?php } ?>

<a href="#" class="dropdown-toggle notifications-icon" data-toggle="dropdown" aria-expanded="false">
  <i class="fa fa-bell fa-fw fa-lg"></i>
  <?php if($current_candidate->total_unread_notifications > 0){ ?>
    <span class="label icon-total-indicator bg-warning icon-notifications"><?php echo new_html_entity_decode($current_candidate->total_unread_notifications); ?></span>
  <?php } ?>
</a>
<ul class="dropdown-menu notifications animated fadeIn width400" data-total-unread="<?php echo new_html_entity_decode($current_candidate->total_unread_notifications); ?>">
  <li class="not_mark_all_as_read">
    <a href="#" onclick="mark_all_notifications_as_read_inline(); return false;"><?php echo _l('mark_all_as_read'); ?></a>
  </li>
  <?php
  $_notifications = $this->recruitment_model->get_user_notifications();
  foreach($_notifications as $notification){ ?>
    <li class="relative notification-wrapper" data-notification-id="<?php echo new_html_entity_decode($notification['id']); ?>">
      <?php if(!empty($notification['link'])){ ?>
        <a href="<?php echo site_url($notification['link']); ?>" class="notification-top notification-link">
        <?php } ?>
        <div class="notification-box<?php if($notification['isread_inline'] == 0){echo ' unread';} ?>">
          <?php
          if(($notification['fromcompany'] == NULL && $notification['fromuserid'] != 0) || ($notification['fromcompany'] == NULL && $notification['fromclientid'] != 0)){
            if($notification['fromuserid'] != 0){
             echo staff_profile_image($notification['fromuserid'],array('staff-profile-image-small','img-circle notification-image','pull-left'));
           } else {
            echo '<img src="'.contact_profile_image_url($notification['fromclientid']).'" class="client-profile-image-small img-circle pull-left notification-image">';
          }
        }
        ?>
        <div class="media-body">
          <?php
          $additional_data = '';
          if(!empty($notification['additional_data'])){
            $additional_data = unserialize($notification['additional_data']);

            $i = 0;
            foreach($additional_data as $data){
              if(strpos($data,'<lang>') !== false){
                $lang = get_string_between($data, '<lang>', '</lang>');
                $temp = _l($lang);
                if(strpos($temp,'project_status_') !== FALSE){
                  $status = get_project_status_by_id(strafter($temp, 'project_status_'));
                  $temp = $status['name'];
                }
                $additional_data[$i] = $temp;
              }
              $i++;
            }
          }
          $description = _l($notification['description'], $additional_data);
          if(($notification['fromcompany'] == NULL && $notification['fromuserid'] != 0)
            || ($notification['fromcompany'] == NULL && $notification['fromclientid'] != 0)){
           if($notification['fromuserid'] != 0){
            $description = $notification['from_fullname']. ' - ' . $description;
          } else {
            $description = $notification['from_fullname']. ' - ' . $description;
          }
        }
        echo '<span class="notification-title">'. $description .'</span>'; ?><br />
        <small class="text-muted">
          <span class="text-has-action" data-placement="right" data-toggle="tooltip" data-title="<?php echo _dt($notification['date']); ?>">
            <?php echo time_ago($notification['date']); ?>
          </span>
        </small>
      </div>
    </div>
    <?php if(!empty($notification['link'])){ ?>
    </a>
  <?php } ?>
  <?php if($notification['isread_inline'] == 0){ ?>
    <a href="#" class="text-muted pull-right not-mark-as-read-inline" onclick="set_notification_read_inline(<?php echo new_html_entity_decode($notification['id']); ?>);" data-placement="left" data-toggle="tooltip" data-title="<?php echo _l('mark_as_read'); ?>"><small><i class="fa fa-circle-thin" aria-hidden="true"></i></small></a>
  <?php } ?>
</li>
<?php } ?>
<?php if(count($_notifications) != 0){ ?>
  <li class="divider no-mbot"></li>
<?php } ?>
<li class="text-center">
  <?php if(count($_notifications) > 0){ ?>
    <a href="<?php echo site_url('recruitment/recruitment_portal/notifications_detail'); ?>"><?php echo _l('nav_view_all_notifications'); ?></a>
  <?php } else { ?>
    <?php echo _l('nav_no_notifications'); ?>
  <?php } ?>
</li>
</ul>
<?php if(!isset($notifications_check)){ ?>
</li>
<?php } ?>