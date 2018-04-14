<?php
/**
 * The ajax call back
 */
function wp_swift_submit_bulk_action_callback() {
    check_ajax_referer( 'bulk-action-nonce', 'security' );
    $response = wp_swift_publish_customer_area_files( $_POST["post_ids"], $_POST["option"] );
    write_log($_POST);// @debug
    echo json_encode( $response );
    die();
}

/**
 * Do the bulk action on all selected posts
 */
function wp_swift_publish_customer_area_files( $post_ids, $option ) {
    wp_swift_log_testing($option, 'start');// @debug
    $updated = array();
    $notice = '';
    $notice_type = '';
    $alert = ''; 
    $user_id = get_current_user_id();
    // $debug_user_id = 2;

    write_log('user_id: '. $user_id);
    if (function_exists('cuar_addon')) {
        $po_addon = cuar_addon('post-owner'); 
    }  
    if ( class_exists('CUAR_NotificationsAddOn')) {
        $notifications = new CUAR_NotificationsAddOn();
    }

    foreach ( $post_ids as $key => $post_id ) {
        $current_post = get_post( $post_id );
        $post_status = $current_post->post_status;

        if ( isset($po_addon) && isset($notifications) ) {

            $notification_id = "Testing";
            $usr = '';
            $grp = '';

            $owners = $po_addon->get_post_owners($post_id);

            if (isset( $owners["usr"] )) {
                $recipient_id = $owners["usr"];
            }
            if (isset( $owners["usr"] )) {
                $recipient_ids = $owners["grp"];
            }
        }


        $post = array( 'ID' => $post_id, 'post_status' => 'publish' );

        if ( $option === 'publish-post' ) {
            if( $post_status === 'pending' || $post_status === 'draft' || $post_status === 'auto-draft' ) {

                $updated[] = $post_id;
                $post_id = wp_update_post($post);
                // if (!is_wp_error($post_id)) {}
            }
        }
        elseif( $option === 'publish-post-notify' ) {
            /**
             * to-do
             *
             * This needs to publish and notify
             */
            $alert = "Sorry, we did contact the server but we were unable to preform this action." . "\n" . 
                     "Please notify support and and report this message:." . "\n\n" .
                     "[NOTIFICATIONS ARE NOT AVAILABLE AT THIS TIME]";
        }
        elseif( $option === 'test' ) {// @debug

            /**
             * CUAR_NotificationsAddOn mailer notes
             *
             * send_notification($recipient_id, $notification_id, $post_id = null, $notif_settings = null, $extra = array())
             * send_mass_notification($recipient_ids, $notification_id, $post_id = null, $extra = array())
             *
             */                
            if (isset( $recipient_ids )) {
                write_log("\n\n@Count: $key __________________________________________________\n");
                write_log('$recipient_ids: ');
                write_log($recipient_ids);
                write_log('$post_id: '. $post_id);                    
                write_log('$notification_id: '. $notification_id);
                write_log('$notif_settings: null');
                write_log("\$extra: null\n");
                $notice = "<p>Successfully contacted server using <strong>Test Ajax</strong> function on " . date("l jS \of F Y h:i:s A").".</p>".
                        "<p>No action was was taken but you should open <i>debug.log</i> for more details. (Requires WordPress debugging to be turned on)</p>";
                $notice_type = 'notice-info';
                // $notifications->send_test_email();
                // $notifications->mailer()->send_notification( $user_id, $notification_id );                    
            }
        }      
    }

    $response = array(
        "notice" => $notice,
        "notice_type" => $notice_type,
        "alert" => $alert,
        "updated" => $updated,
        "post_ids" => $post_ids,
        "option" => $option,        
    );
    wp_swift_log_testing($option, 'end');// @debug
    return $response;
}
function wp_swift_log_testing($option, $label) {
    if( $option === 'test' ) {
        write_log("\n{{ @$label testing }}\n");
    }    
}
if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( var_export( $log, true ) );//print_r
      } else {
         error_log( $log );
      }
   }
}