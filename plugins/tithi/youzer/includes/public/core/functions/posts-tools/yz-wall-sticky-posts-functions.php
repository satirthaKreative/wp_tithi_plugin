<?php

/**
 * Add New Activity Tool.
 */
function yz_add_pin_posts_tool( $tools, $post_id ) {
	
	if ( ! yz_is_user_can_pin_posts() ) {
		return $tools;
	}

	if ( yz_is_post_pinned( $post_id ) ) {
		// Get Unpin Button Data.
		$action = 'unpin';
		$class = 'yz-unpin-post';
		$title = __( 'Unpin Post', 'youzer' );
		$icon  = 'fas fa-thumbtack fa-flip-vertical';
	} else {
		// Get Pin Button Data.
		$action = 'pin';
		$icon  = 'fas fa-thumbtack';
		$class = 'yz-pin-post';
		$title = __( 'Pin Post', 'youzer' );
	}

	// Get Tool Data.
	$tools[] = array(
		'icon' => $icon,
		'title' => $title,
		'action' => $action,
		'class' => array( 'yz-pin-tool', $class ),
	);

	return $tools;
}

add_filter( 'yz_activity_tools', 'yz_add_pin_posts_tool', 10, 2 );

/**
 * Exclude Sticky Activities
 */
function yz_exclude_sticky_posts( $query ) {

	if ( ! bp_is_group_activity() && ! bp_is_activity_directory() ) {
		return $query;
	}

	// Get Posts Per Page Number.
	$sticky_posts = yz_get_sticky_posts_ids();
	
	if ( ! empty( $query ) ) {
        $query .= '&';
    }

    // Convert Query into Args.
    $args = wp_parse_args( $query );

    // Exclude Activities.
    if ( ! empty( $args['exclude'] ) ) {
		$query .= 'exclude=' . $args['exclude'] . ',' . $sticky_posts;
    } else {
		$query .= 'exclude=' . $sticky_posts;
    }

	return $query;
}

add_filter( 'bp_ajax_querystring', 'yz_exclude_sticky_posts', 999 );
add_filter( 'bp_legacy_theme_ajax_querystring', 'yz_exclude_sticky_posts', 999 );

/**
 * Add Pinned Activity Class
 */
function yz_add_pinned_post_class( $class ) {

	// Get Activity ID.
	$activity_id = bp_get_activity_id();

	// Check if activity is pinned.
	if ( ! yz_is_post_pinned( $activity_id ) ) {
		return $class;
	}

	// Add Pinned Class.
	$class .= ' yz-pinned-post';

	// Remove Data Class.
	$class = str_replace( 'date-recorded-', 'date-', $class );

	return $class;

}

add_filter( 'bp_get_activity_css_class', 'yz_add_pinned_post_class' );

/**
 * Check if Activity is Pinned
 */
function yz_is_post_pinned( $activity_id = null ) {

	// Check if Sticky Activities Are Enabled.
	if ( ! yz_is_sticky_posts_active() ) {
		return false;
	}

	// Get Sticky Activities.
	$sticky_activities = yz_get_sticky_posts();

	if ( empty( $activity_id ) || empty( $sticky_activities ) ) {
		return false;
	}

	if ( ! in_array( $activity_id, $sticky_activities ) ) {
		return false;
	}

	return true;

}

/**
 * Profile Set Stick Post
 */
function yz_profile_wall_set_sticky_post( $query, $args ) {

	if ( ! bp_is_group_activity() && ! bp_is_activity_directory() ) {
		return $query;
	}

	// Get Sticky Posts ID's.
	$posts_ids = yz_get_sticky_posts_ids();

	if ( empty( $posts_ids ) || isset( $args['page'] ) && $args['page'] > 1 ) {
		return $query;
	}

	// Get Sticky Posts Number
	$count = count( explode( ',', $posts_ids ) );

	// Get Sticky Activities.
	$sticky_activities = BP_Activity_Activity::get( 
		array( 'in' => $posts_ids, 'page' => 1, 'per_page' => $count, 'show_hidden' => 1, 'display_comments' => 'threaded' )
	);

	// Get Activities.
	$query['activities'] = array_merge( $sticky_activities['activities'], $query['activities'] );

	return $query;

}

add_filter( 'bp_activity_get', 'yz_profile_wall_set_sticky_post', 1, 2 );

/**
 * Check is User Can Pin Activities.
 */
function yz_is_user_can_pin_posts() {

	if ( ! is_user_logged_in() || ! yz_is_sticky_posts_active() ) {
		return false;
	}

	if ( bp_is_active( 'activity' ) && bp_is_active( 'groups' ) && bp_is_groups_component() && bp_group_is_admin() && bp_is_group_activity() ) {
		return true;
	}

	// Get Current User Data.
	$user = wp_get_current_user();

	// Allowed Roles
	$allowed_roles = array( 'administrator' );
	
	// Filter Roles.
	$allowed_roles = apply_filters( 'yz_allowed_roles_to_pin_posts', $allowed_roles );

	foreach ( $allowed_roles as $role ) {
		if ( in_array( $role, (array) $user->roles ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Get Sticky Posts.
 */
function yz_get_sticky_posts( $component = null, $group_id = null ) {

	if ( ! yz_is_sticky_posts_active() ) {
		return false;
	}

	// Get Component.
	$component = bp_is_groups_component() ? 'groups' : 'activity';

	// Get Group ID.
	if ( bp_is_active( 'groups' ) ) {
		$group_id = ! empty( $group_id ) ? $group_id : bp_get_current_group_id();
	}

	// Get Sticky Posts ID's
	$posts_ids = yz_options( 'yz_' . $component . '_sticky_posts' );

	// Filter Sticky Posts ID's
	$posts_ids = apply_filters( 'yz_get_sticky_posts', $posts_ids, $component, $group_id );

	// Get Group Sticky Posts ID's.
	if ( 'groups' == $component ) {
		$posts_ids = isset( $posts_ids[ $group_id ] ) ? $posts_ids[ $group_id ] : array();
	}

	// Remove Duplicated Values.
	$posts_ids = is_array( $posts_ids ) ? array_unique( $posts_ids ) : $posts_ids;

	return $posts_ids;

}

/**
 * Get Sticky Posts ID's ( String )
 */
function yz_get_sticky_posts_ids( $component = null, $group_id = null ) {

	// Get Stikcy Posts Array
	$sticky_posts = yz_get_sticky_posts( $component, $group_id );

	// Convert Ids into a list seprarated with comas
	$posts_ids = implode( ',', (array) $sticky_posts );

	return $posts_ids;

}

/**
 * Add "Pinned Tag" to activity.
 */
function yz_activity_pinned_tag( $action, $activity ) {

	// Check if Activity is pinned.
	if ( ! yz_is_post_pinned( $activity->id ) ) {
		return $action;
	}

	// Get Tag.
	$pinned_tag = '<span class="yz-pinned-post-tag"><i class="fas fa-thumbtack"></i>' . __( 'pinned post' ) . '</span>';

	// Filter Pinned Tag.
	$pinned_tag = apply_filters( 'yz_activity_pinned_tag', $pinned_tag );

	return $action . $pinned_tag;

}

add_filter( 'yz_activity_new_post_action', 'yz_activity_pinned_tag', 10, 2 );

/**
 * Check if Sticky Posts are Enabled.
 */
function yz_is_sticky_posts_active() {

	// Get Value.
	$sticky_posts = yz_options( 'yz_enable_sticky_posts' );

	if ( 'on' == $sticky_posts ) {
		$activate = true;
	} else {
		$activate = false;
	}

	return apply_filters( 'yz_is_sticky_posts_active', $activate );

}

/**
 * Enable / Disable Groups Sticky Posts.
 */
function yz_enable_groups_sticky_posts( $default ) {

	if ( ! bp_is_group_activity() ) {
		return $default;
	}

	// Get Option.
	$enable_posts = yz_options( 'yz_enable_groups_sticky_posts' ); 

	if ( 'on' == $enable_posts ) {
		return true;
	}

	return false;
}

add_filter( 'yz_is_sticky_posts_active', 'yz_enable_groups_sticky_posts', 10 );

/**
 * Enable / Disable Activity Sticky Posts.
 */
function yz_enable_activity_sticky_posts( $default ) {

	if ( ! bp_is_activity_directory() ) {
		return $default;
	}

	// Get Option.
	$enable_posts = yz_options( 'yz_enable_activity_sticky_posts' ); 

	if ( 'on' == $enable_posts ) {
		return true;
	}

	return false;
}

add_filter( 'yz_is_sticky_posts_active', 'yz_enable_activity_sticky_posts', 10 );

/**
 * Handle Sticky Posts
 */
function yz_handle_sticky_posts() {

	// Hook.
	do_action( 'yz_before_handle_sticky_posts' );

	// Check Ajax Referer.
	check_ajax_referer( 'yz-sticky-posts', 'security' );

	if ( ! yz_is_user_can_pin_posts() || ! is_user_logged_in() ) {
		$data['error'] = __( 'The action you have requested is not allowed.', 'youzer' );
		die( json_encode( $data ) );
	}

	// Get Data.
	$data = $_POST;
	
	// Allowed Actions
	$allowed_actions = array( 'pin', 'unpin' );

	// Get Data.
	$post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : null;
	$group_id = isset( $_POST['group_id'] ) ? $_POST['group_id'] : null;
	$action = isset( $_POST['operation'] ) ? $_POST['operation'] : null;
	$component = isset( $_POST['component'] ) ? $_POST['component'] : null;

	// Check if The Post ID & The Component are Exist.
	if ( empty( $post_id ) || empty( $component ) ) {
		$data['error'] = __( "Sorry we didn't received enough data to process this action.", 'youzer' );
		die( json_encode( $data ) );
	}

	// Check Requested Action.
	if ( empty( $action ) || ! in_array( $action, $allowed_actions ) ) {
		$data['error'] = __( 'The action you have requested is not exist.', 'youzer' );
		die( json_encode( $data ) );
	}

	if ( 'pin' == $action ) {
		// Pin Activity.
		yz_add_sticky_post( $component, $post_id, $group_id );
		$data['action'] = 'unpin';
		$data['msg'] = __( 'The activity is pinned successfully', 'youzer' );
	} elseif ( 'unpin' == $action ) {
		// Unpin Activity.
		yz_delete_sticky_post( $component, $post_id, $group_id );
		$data['action'] = 'pin';
		$data['msg'] = __( 'The activity is unpinned successfully', 'youzer' );
	}

	die( json_encode( $data ) );

}

add_action( 'wp_ajax_yz_handle_sticky_posts', 'yz_handle_sticky_posts' );

/**
 * Add Sticky Posts
 */
function yz_add_sticky_post( $component, $post_id, $group_id = null ) {

	// Get All Sticky Posts.
	$sticky_posts = yz_options( 'yz_' . $component . '_sticky_posts' );

	// Add the new pinned post.
	if ( 'groups' == $component ) {
		$sticky_posts[ $group_id ][] = $post_id;
	} elseif ( 'activity' == $component ) {
		$sticky_posts[] = $post_id;
	}

	// Update Sticky Posts.
	update_option( 'yz_' . $component . '_sticky_posts', $sticky_posts );
}

/**
 * Delete Sticky Activities
 */
function yz_delete_sticky_post( $component, $post_id, $group_id = null ) {

	// Get All Sticky Posts.
	$sticky_posts = yz_options( 'yz_' . $component . '_sticky_posts' );
	
	if ( 'groups' == $component ) {

		// Get Removed Post Key.
		$post_key = array_search( $post_id, $sticky_posts[ $group_id ] );

		// Remove Post.
		if ( isset( $sticky_posts[ $group_id ][ $post_key ] ) ) {
			unset( $sticky_posts[ $group_id ][ $post_key ] ); 
		}
		
	} elseif ( 'activity' == $component ) {
		
		// Get Removed Post Key.
		$post_key = array_search( $post_id, $sticky_posts );

		// Remove Post.
		if ( isset( $sticky_posts[ $post_key ] ) ) {
			unset( $sticky_posts[ $post_key ] ); 
		}

	}
	

	// Update Sticky Posts.
	update_option( 'yz_' . $component . '_sticky_posts', $sticky_posts );
}

/**
 * Sticksy Posts Scripts
 */
function yz_sticky_posts_scripts() {

    // Pin Activities Script
    if ( yz_is_user_can_pin_posts() ) {

        // Call Sticky Posts Script.
        wp_enqueue_script( 'yz-sticky-posts', YZ_PA . 'js/yz-sticky-posts.min.js' );

        // Get Data
        $script_data = array(
            'current_component' => bp_is_groups_component() ? 'groups' : 'activity',
            'security_nonce' => wp_create_nonce( 'yz-sticky-posts' ),
            'unpin_post' => __( 'Unpin Post', 'youzer' ),
            'pin_post' => __( 'Pin Post', 'youzer' ),
        );

        // Get Current Group
        if ( bp_is_active( 'groups' ) ) {
            $script_data['current_group'] = bp_get_current_group_id();
        }

        // Localize Script.
        wp_localize_script( 'yz-sticky-posts', 'Yz_Sticky_Posts', $script_data );

    }

}

add_action( 'yz_activity_scripts', 'yz_sticky_posts_scripts' );
add_action( 'yz_call_activity_scripts', 'yz_sticky_posts_scripts' );
