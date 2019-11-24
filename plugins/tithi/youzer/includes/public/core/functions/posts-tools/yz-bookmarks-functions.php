<?php

/**
 * Add New Activity Tool.
 */
function yz_posts_add_bookmark_tool( $tools, $post_id ) {
	
	if ( ! yz_is_user_can_bookmark() ) {
		return $tools;
	}

	if ( yz_get_bookmark_id( bp_loggedin_user_id(), bp_get_activity_id(), 'activity' ) ) {
		// Get Unpin Button Data.
		$action = 'unsave';
		$class = 'yz-unsave-post';
		$title = __( 'Remove Bookmark', 'youzer' );
		$icon  = 'fas fa-times';
	} else {
		// Get Pin Button Data.
		$action = 'save';
		$icon  = 'fas fa-bookmark';
		$class = 'yz-save-post';
		$title = __( 'Bookmark', 'youzer' );
	}

	// Get Tool Data.
	$tools[] = array(
		'icon' => $icon,
		'title' => $title,
		'action' => $action,
		'class' => array( 'yz-bookmark-tool', $class ),
		'attributes' => array( 'item-type' => 'activity' )
	);

	return $tools;
}

add_filter( 'yz_activity_tools', 'yz_posts_add_bookmark_tool', 10, 2 );

/**
 * Check is User Can Bookmark Activities.
 */
function yz_is_user_can_bookmark() {

	if ( ! is_user_logged_in() || ! yz_is_bookmark_active() ) {
		return false;
	}

	// Get Current User Data.
	$user = wp_get_current_user();

 	// Allowed Roles
	$allowed_roles = array( 'administrator', 'editor', 'subscriber', 'author' );
	
	// Filter Roles.
	$allowed_roles = apply_filters( 'yz_allowed_roles_to_bookmark_posts', $allowed_roles );

	foreach ( $allowed_roles as $role ) {
		if ( in_array( $role, (array) $user->roles ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Handle Posts Bookmark
 */
function yz_handle_posts_bookmark() {

	// Hook.
	do_action( 'yz_before_handle_bookmark_posts' );

	// Check Ajax Referer.
	check_ajax_referer( 'youzer-nonce', 'security' );

	if ( ! yz_is_user_can_bookmark() || ! is_user_logged_in() ) {
		$response['error'] = __( 'The action you have requested is not allowed.', 'youzer' );
		die( json_encode( $response ) );
	}
	
	// Allowed Actions
	$allowed_actions = array( 'save', 'unsave' );

	// Get Table Data.
	$data = array(
		'user_id' => bp_loggedin_user_id(),
		'item_id' => isset( $_POST['item_id'] ) ? $_POST['item_id'] : null,
		'item_type' => isset( $_POST['item_type'] ) ? $_POST['item_type'] : null,
		'collection_id' => isset( $_POST['collection_id'] ) ? $_POST['collection_id'] : '0'
	);

	// Get Data.
	$action = isset( $_POST['operation'] ) ? $_POST['operation'] : null;

	// Check if The Post ID & The Component are Exist.
	if ( empty( $data['item_id'] ) || empty( $data['item_type'] ) ) {
		$response['error'] = __( "Sorry we didn't received enough data to process this action.", 'youzer' );
		die( json_encode( $response ) );
	}

	// Check Requested Action.
	if ( empty( $action ) || ! in_array( $action, $allowed_actions ) ) {
		$response['error'] = __( 'The action you have requested is not exist.', 'youzer' );
		die( json_encode( $response ) );
	}

	// Check if user Already Bookmarked Post ( Returns ID ).
	$bookmark_id = yz_get_bookmark_id( $data['user_id'], $data['item_id'], $data['item_type'] );

	if ( 'save' == $action ) {

		// Check is post already bookmarked !
		if ( $bookmark_id ) {
			$response['error'] = __( 'This item is already bookmarked.', 'youzer' );
			die( json_encode( $response ) );
		}

		// Bookmark Post.
		$bookmark_id = yz_save_post_bookmark( $data );

		if ( $bookmark_id ) {
			do_action( 'yz_after_bookmark_save', $bookmark_id, $data['user_id'] );
		}

		$response['action'] = 'unsave';
		$response['msg'] = __( 'The item is bookmarked successfully', 'youzer' );

	} elseif ( 'unsave' == $action ) {

		// Hook.
		do_action( 'yz_before_bookmark_delete', $bookmark_id, $data['user_id'] );

		// Delete Bookmark.
		yz_delete_post_bookmark( $bookmark_id );

		$response['action'] = 'save';
		$response['msg'] = __( 'The bookmark is removed successfully', 'youzer' );
	}

	die( json_encode( $response ) );

}

add_action( 'wp_ajax_yz_handle_posts_bookmark', 'yz_handle_posts_bookmark' );

/**
 * Bookmark Slug.
 */
function yz_bookmarks_tab_slug() {
	return apply_filters( 'yz_bookmarks_tab_slug', 'bookmarks' );
}

/**
 * # Setup Tabs.
 */
function yz_bookmarks_tabs() {
	
	if ( ! yz_is_user_can_see_bookmarks() ) {
		return false;
	}

	$bp = buddypress();


	$bookmarks_slug = yz_bookmarks_tab_slug();

	// Add Follows Tab.
	bp_core_new_nav_item(
	    array( 
	        'position' => 200,
	        'slug' => $bookmarks_slug, 
	        'name' => __( 'Bookmarks' , 'youzer' ), 
	        'default_subnav_slug' => 'activities',
	        'parent_slug' => $bp->profile->slug,
	        'screen_function' => 'yz_bookmarks_screen', 
	        'parent_url' => bp_displayed_user_domain() . "$bookmarks_slug/"
	    )
	);

	// Add Activities Sub Tab.
    bp_core_new_subnav_item( array(
            'slug' => 'activities',
            'name' => __( 'Activities', 'youzer' ),
            'parent_slug' => $bookmarks_slug,
            'parent_url' => bp_displayed_user_domain() . "$bookmarks_slug/",
            'screen_function' => 'yz_bookmarks_screen',
        )
    );
}

add_action( 'bp_setup_nav', 'yz_bookmarks_tabs' );

/**
 * Get Bookmarks Tab Screen Function.
 */
function yz_bookmarks_screen() {
	
	do_action( 'bp_bookmarks_screen' );

    add_action( 'bp_template_content', 'yz_get_user_bookmarks_template' );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Bookmars Tab Content.
 */
function yz_get_user_bookmarks_template() {
	bp_get_template_part( 'members/single/bookmarks' );
}

/**
 * Get User Bookmarks.
 */
function yz_get_user_bookmarks( $user_id, $item_type, $result_type = null ) {
   
    // Get Transient Option.
    $transient_id = 'yz_user_bookmarks_' . $user_id;

    $user_bookmarks = get_transient( $transient_id );

    if ( false === $user_bookmarks ) {

		global $wpdb, $Yz_bookmark_table;
		
		// Get SQL Query.
		$sql = $wpdb->prepare(
			"SELECT item_id FROM $Yz_bookmark_table WHERE user_id = %d AND item_type = %s",
			$user_id, $item_type
		);

		// Get Result
	    $result = $wpdb->get_col( $sql );

	   	// Clean up array.
	   	$user_bookmarks = wp_parse_id_list( $result );

        set_transient( $transient_id, $user_bookmarks, 12 * HOUR_IN_SECONDS );
        
	}

	// Return.
	return $user_bookmarks;

}

/**
 * Enable Activity Component.
 */
function yz_enable_bookmark_activity_component( $active ) {

	if ( bp_is_current_component( 'bookmarks' ) ) {
		return true;
	}

	return $active;

}

add_filter( 'yz_is_activity_component', 'yz_enable_bookmark_activity_component' );

/**
 * Set User Bookmarks Query.
 */
function yz_set_user_bookmarks_query( $retval ) {

	if ( ! bp_is_current_component( 'bookmarks' ) ) {
		return $retval;
	}

	// Get List of bookmarked items.
   	$items_ids = yz_get_user_bookmarks( bp_displayed_user_id(), 'activity', 'list' );

	// Check if private users have no activities.
	if ( empty( $items_ids ) ) {
    	return $retval;
    }
	
	// // Covert List of Activities ids to string.
    $items_ids = implode( ',', $items_ids );

    // Set Activities
    $retval['include'] = $items_ids;
    $retval['per_page'] = 25;

    return $retval;
}

add_filter( 'bp_after_has_activities_parse_args', 'yz_set_user_bookmarks_query' );

/**
 * Check if User Have Bookmarks
 */
function yz_bookmarks_loop_has_content( $has_activities , $activities_template, $r ) {

	if ( ! bp_is_current_component( 'bookmarks' ) ) {
		return $has_activities;
	}

	// Check if user has bookmarks.
	if ( isset( $r['include'] ) && empty( $r['include'] ) ) {
    	return false;
    }

    return $has_activities;
}

add_filter( 'bp_has_activities', 'yz_bookmarks_loop_has_content', 999, 3 );

/**
 * Check if User Have Bookmarks
 */
function yz_set_bookmarks_as_has_nochildren( $has_children ) {

	if ( ! bp_is_current_component( 'bookmarks' ) ) {
		return $has_children;
	}

    return false;
}

add_filter( 'yz_is_current_tab_has_children', 'yz_set_bookmarks_as_has_nochildren', 999, 3 );

/**
 * Get Bookmarked Post.
 */
function yz_get_bookmark_id( $user_id, $item_id, $item_type ) {

	global $wpdb, $Yz_bookmark_table;

	// Prepare Sql
	$sql = $wpdb->prepare(
		"SELECT id FROM $Yz_bookmark_table WHERE user_id = %d AND item_id = %d AND item_type = %s",
		$user_id, $item_id, $item_type
	);

	// Get Result
	$result = $wpdb->get_var( $sql );

	return $result;

}

/**
 * Save Bookmark.
 */
function yz_save_post_bookmark( $data = array() ) {

	global $wpdb, $Yz_bookmark_table;

	// Get Current Time.
	$data['time'] = current_time( 'mysql' );
	
	// Insert Post.
	$result = $wpdb->insert( $Yz_bookmark_table, $data );

	if ( $result ) {
		// Return Reaction ID.
		return $wpdb->insert_id;
	}

	return false;

}

/**
 * Delete Bookmark.
 */
function yz_delete_post_bookmark( $bookmark_id ) {

	global $wpdb, $Yz_bookmark_table;

	if ( ! $bookmark_id ) {
		return false;
	}

	// Delete Bookmark.
	$delete_bookmark = $wpdb->delete( $Yz_bookmark_table, array( 'id' => $bookmark_id ), array( '%d' ) );

	// Get Result.
	if ( $delete_bookmark ) {
		return true;
	}

	return false;

}

/**
 * Delete Posts Count Transient.
 */
function yz_delete_user_bookmarks_transient( $bookmark_id = null, $user_id = null ) {

    // Delete Transient.
    delete_transient( 'yz_user_bookmarks_' . $user_id );
}

add_action( 'yz_after_bookmark_save', 'yz_delete_user_bookmarks_transient', 10, 2 );
add_action( 'yz_before_bookmark_delete', 'yz_delete_user_bookmarks_transient', 10, 2 );

/**
 * Check is User Can see Bookmarks.
 */
function yz_is_user_can_see_bookmarks() {
	
	// Init var.
	$visibility = false;

	// Get Who can see bookmarks.
	$privacy = yz_options( 'yz_enable_bookmarks_privacy' );

	switch ( $privacy ) {

		case 'public':
			$visibility = true;
			break;
		
		case 'private':

			$visibility = bp_core_can_edit_settings() ? true : false;

			break;

		case 'loggedin':

			$visibility = is_user_logged_in() ? true : false;

			break;

		case 'friends':

			if ( bp_is_active( 'friends' ) ) {

				// Get User ID
				$loggedin_user = bp_loggedin_user_id();

				// Get Profile User ID
				$profile_user = bp_displayed_user_id();

				$visibility = friends_check_friendship( $loggedin_user, $profile_user ) ? true : false;

			}

			break;
		
		default:
			$visibility = false;
			break;

	}

	if ( bp_core_can_edit_settings() ) {
		$visibility = true;
	}

	return apply_filters( 'yz_is_user_can_see_bookmarks', $visibility );

}

/**
 * Bookmark Scripts
 */
function yz_bookmarks_scripts() {

    // Pin Activities Script
    if ( yz_is_user_can_bookmark() ) {

        // Call Bookmark Posts Script.
        wp_enqueue_script( 'yz-bookmark-posts', YZ_PA . 'js/yz-bookmark-posts.min.js' );

        // Get Data
        $script_data = array(
            'unsave_post' => __( 'Remove Bookmark', 'youzer' ),
            'save_post' => __( 'Bookmark', 'youzer' ),
        );

        // Localize Script.
        wp_localize_script( 'yz-bookmark-posts', 'Yz_Bookmark_Posts', $script_data );

    }

}

add_action( 'yz_activity_scripts', 'yz_bookmarks_scripts' );
add_action( 'yz_call_activity_scripts', 'yz_bookmarks_scripts' );
