<?php
/**
 * The ajax call back
 */
function wp_swift_submit_bulk_action_callback() {
    check_ajax_referer( 'bulk-action-nonce', 'security' );
    $response = wp_swift_publish_customer_area_files( $_POST["post_ids"], $_POST["option"] );
    echo json_encode( $response );
    die();
}
add_action( 'wp_ajax_wp_swift_submit_bulk_action', 'wp_swift_submit_bulk_action_callback' );

/**
 * Do the bulk action on all selected posts
 */
function wp_swift_publish_customer_area_files( $post_ids, $option ) {
    $updated = array();
    $html = '';
    $alert = '';   
    foreach ( $post_ids as $post_id ) {
        $current_post = get_post( $post_id );
        $post_status = $current_post->post_status;


        if ( $post_status === 'pending' || $post_status === 'draft' || $post_status === 'auto-draft' ) {
            $post = array( 'ID' => $post_id, 'post_status' => 'publish' );

            if ( $option === 'publish-post' ) {
                $updated[] = $post_id;
                wp_update_post($post);
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
        }         
    }

    $response = array(
        "html" => $html,
        "alert" => $alert,
        "updated" => $updated,
        "post_ids" => $post_ids,
        "option" => $option,        
    );
    return $response;
}