<?php defined('BASEPATH') or exit('No direct script access allowed');
echo theme_head_view();
get_template_part_rec_portal($navigationEnabled ? 'navigation' : '');
?>
<?php hooks()->do_action('app_customers_portal_head'); ?>

<div id="wrapper">
   <div id="content">
      <div class="container">
         <div class="row">
            <?php get_template_part_rec_portal('alerts'); ?>
         </div>
      </div>
  
      <div class="container">
         <?php hooks()->do_action('customers_content_container_start'); ?>
         <div class="row">
           
            <?php echo theme_template_view(); ?>
         </div>
      </div>
   </div>
   <?php
   echo theme_footer_view();
   ?>
</div>
<?php
/* Always have app_customers_footer() just before the closing </body>  */
app_customers_footer();
   /**
   * Check for any alerts stored in session
   */
   app_js_alerts();
   ?>
   <script type="text/javascript" id="pusher-js" src="https://js.pusher.com/5.0/pusher.min.js"></script>
   <?php
    hooks()->do_action('app_customers_portal_footer');

/**
 * Check pusher real time notifications
 */

if(get_option('pusher_realtime_notifications') == 1){ ?>
   <script type="text/javascript">
      $(function(){
         // Enable pusher logging - don't include this in production
         <?php $pusher_options = hooks()->apply_filters('pusher_options', array(['disableStats'=>true]));
         if(!isset($pusher_options['cluster']) && get_option('pusher_cluster') != ''){
            $pusher_options['cluster'] = get_option('pusher_cluster');
         }
         ?>
         var pusher_options = <?php echo json_encode($pusher_options); ?>;
         var pusher = new Pusher("<?php echo get_option('pusher_app_key'); ?>", pusher_options);
         var channel = pusher.subscribe('candidate-notifications-channel-<?php echo get_candidate_id(); ?>');
         channel.bind('notification', function(data) {
            candidate_fetch_notifications();
         });
      });
   </script>
<?php } ?>

</body>
</html>
