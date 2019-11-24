<?php

/**
 * Check Is Mailster Enabled.
 */
function yz_is_mailster_active() {

    if ( ! function_exists( 'mailster' ) ) {
        return false;
    }

    // Check if Mailster Sync is Enabled.
    $mailster_enabled = yz_options( 'yz_enable_mailster' );

    if ( $mailster_enabled == 'off' ) {
        return false;
    }

    // Get Mailster List ID's.
    $list_ids = yz_options( 'yz_mailster_list_ids' );

    if ( empty( $list_ids ) ) {
        return false;
    }


    return true;
    
}

/**
 * Subscribe Registered User to Mailster.
 */
function yz_subscribe_user_to_mailser( $user_id, $key, $user ) {
    
    // Check if Mail Chimp is active.
    if ( ! yz_is_mailster_active() ) {
        return false;
    }

    // Get User Infos.
    $user_info = get_userdata( $user_id );

    if ( ! is_object( $user_info ) ) {
        return false;
    }

    // Get List IDs.
    $list_ids = yz_options( 'yz_mailster_list_ids' );

    // define to overwrite existing users
    $overwrite = true;

    // add with double opt in
    $double_opt_in = true;

    // prepare the userdata from a $_POST request. only the email is required
    $userdata = array(
        'email'     => $user_info->user_email,
        'firstname' => $user_info->first_name,
        'lastname'  => $user_info->last_name,
        'status'    => $double_opt_in ? 0 : 1,
    );

    // add a new subscriber and $overwrite it if exists
    $subscriber_id = mailster( 'subscribers' )->add( $userdata, $overwrite );

    // if result isn't a WP_error assign the lists
    if ( ! is_wp_error( $subscriber_id ) ) {

        // List Ids
        $list_ids = explode( ',', $list_ids );
        mailster( 'subscribers' )->assign_lists( $subscriber_id, $list_ids );

    }

};

add_action( 'bp_core_activated_user', 'yz_subscribe_user_to_mailser', 10, 3 );
