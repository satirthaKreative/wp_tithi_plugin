<?php

/**
 * Check is bbpress is Installed & Active.
 */
function yz_is_bbpress_active() {
    
    if ( ! yz_is_bbpress_installed() ) {
        return false;
    }

    // Get Value.
    $is_bbpress_enabled = yz_options( 'yz_enable_bbpress' );

    if ( 'off' == $is_bbpress_enabled ) {
        return false;
    }

    return true;

}

/**
 * # Default Options 
 */
function yz_bbpress_default_options( $options ) {

    // Options.
    $yzsq_options = array(
        'yz_enable_bbpress' => 'on',
        'yz_enable_bbp_reply_create' => 'on',
		'yz_enable_bbp_topic_create' => 'on',
		'yz_forums_tab_icon' => 'fas fa-comments',
		'yz_ctabs_forums_subscriptions_icon' => 'fas fa-bell',
		'yz_ctabs_forums_topics_icon' => 'fas fa-file-alt',
		'yz_ctabs_forums_replies_icon' => 'far fa-comments',
		'yz_ctabs_forums_favorites_icon' => 'fas fa-thumbs-up',
    );
    
    $options = array_merge( $options, $yzsq_options );

    return $options;
}

add_filter( 'yz_default_options', 'yz_bbpress_default_options' );

/**
 * Include Bbpress Files.
 */
function yz_init_bbpress() {

	if ( ! yz_is_bbpress_active() ) {
		return;
	}

	// Include Functions.
    require_once YZ_PUBLIC_CORE . 'bbpress/yz-bbpress-functions.php';
}

add_action( 'setup_theme', 'yz_init_bbpress' );

/**
 * Set Activity forum posts visibility
 **/
function yz_bbp_set_activity_posts_visibility( $post_types ) {

    // Get Post Types Visibility
    $post_types['bbp_reply_create'] = yz_options( 'yz_enable_bbp_reply_create' );
    $post_types['bbp_topic_create'] = yz_options( 'yz_enable_bbp_topic_create' );

    return $post_types;
}

add_filter( 'yz_wall_post_types_visibility', 'yz_bbp_set_activity_posts_visibility' );


/**
 * Add Activity forum posts visibility
 **/
function yz_bbp_add_activity_posts_visibility_settings( $post_types ) {
    
    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_bbp_reply_create',
            'title' => __( 'Forum Replies', 'youzer' ),
            'desc'  => __( 'enable forum replies posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_bbp_topic_create',
            'title' => __( 'Forum Topics', 'youzer' ),
            'desc'  => __( 'enable forum topics posts', 'youzer' ),
        )
    );

}

add_action( 'yz_wall_posts_visibility_settings', 'yz_bbp_add_activity_posts_visibility_settings' );