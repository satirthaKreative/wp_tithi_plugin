<?php

/**
 * # Get Group Page Class.
 */
function yz_group_page_class() {

	global $Youzer;

    // New Array
    $class = array();

    // Get Group Layout
    $class[] = $Youzer->group->layout();

    // Get Group Page
    $class[] = 'yz-page yz-group';
    
    // Get Group Width Type
    $class[] = 'yz-wild-content';

    // Get Tabs List Icons Style
    $class[] = yz_options( 'yz_tabs_list_icons_style' );

    // Get Page Buttons Style
    $class[] = 'yz-page-btns-border-' . yz_options( 'yz_buttons_border_style' );

    // Get Elements Border Style.
    $class[] = 'yz-wg-border-' . yz_options( 'yz_wgs_border_style' );

    $class = apply_filters( 'yz_group_class', $class );

    return yz_generate_class( $class );
}

/**
 * Create Group New Subnavs.
 */
function yz_add_new_groups_subnavs() {

	// Check if its a group page.
	if ( ! bp_is_groups_component() || ! bp_is_single_item() ) {
		return false;
	}

	global $bp;

	$group = $bp->groups->current_group;

	// Add Group 'Infos' Nav.
	bp_core_new_subnav_item(
		array(
			'slug' => 'group-info',
			'parent_slug' => $group->slug,
			'name' => __( 'Info', 'youzer' ),
			'parent_url' => bp_get_group_permalink( $group ),
			'screen_function' => 'yz_groups_screen_group_infos',
			'position' => 10
		)
	);

	// Get Requests Number
	$requests_nbr = yz_get_group_membership_requests_count( $group->id );

	if ( bp_is_item_admin() && 'private' ==  bp_get_group_status( $group ) && '0' != $requests_nbr ) {
		// Create 'Requests' Subnav.
		bp_core_new_subnav_item( array(
				'name' => sprintf( __( 'Requests %s', 'youzer' ), '<span>' . number_format( $requests_nbr ) . '</span>' ),
				'parent_slug' => $group->slug,
				'slug' => 'membership-requests',
				'parent_url' => trailingslashit( bp_get_group_permalink( $group ) . 'admin' ),
				'screen_function' => 'groups_screen_group_admin',
				'position' => 60
			)
		);
	}

}

add_action( 'wp', 'yz_add_new_groups_subnavs' );

/**
 * Get Group Infos Page Title.
 */
function yz_groups_screen_group_infos_title() {
	_e( 'Information', 'youzer' );
}

/**
 * Get Group Infos Page Content.
 */
function yz_groups_screen_group_infos_content() {

	global $bp;

	$group = $bp->groups->current_group;

	?>
	
	<div class="yz-group-infos-widget">
		<div class="yz-group-widget-title">
			<i class="fas fa-file-alt"></i>
			<?php echo _e( 'description', 'youzer' ); ?>
		</div>
		<div class="yz-group-widget-content"><?php echo apply_filters( 'the_content', html_entity_decode( $group->description ) ); ?></div>
	</div>

	<?php

}

/**
 * Add New Group Infos Page.
 */
function yz_groups_screen_group_infos() {

	add_action( 'bp_template_title', 'yz_groups_screen_group_infos_title' );
	add_action( 'bp_template_content', 'yz_groups_screen_group_infos_content' );

	$templates = array( 'groups/single/plugins.php', 'plugin-template.php' );

    // Load Tab Template
    bp_core_load_template( 'buddypress/groups/single/plugins' );

}

/**
 * Get Group Membership requests Count.
 */
function yz_get_group_membership_requests_count( $group_id ) {

	global $bp, $wpdb;

	// Result.
	$requests_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$bp->groups->table_name_members} WHERE is_confirmed = 0 AND inviter_id = 0 AND group_id = $group_id" );

	return $requests_count;

}

/**
 * Get Friends List To invite
 */
function yz_get_new_group_invite_friend_list( $items, $r ) {

	// Setup empty items array.
	$items = array();

	// Get user's friends who are not in this group already.
	$friends = friends_get_friends_invite_list( $r['user_id'], $r['group_id'] );

	if ( ! empty( $friends ) ) {

		// Get already invited users.
		$invites = groups_get_invites_for_group( $r['user_id'], $r['group_id'] );
	    
		for ( $i = 0, $count = count( $friends ); $i < $count; ++$i ) {

			// Get Friend ID.
			$friend_id = $friends[ $i ]['id'];

			// Get Friend Avatar
			$friend_avatar = bp_core_fetch_avatar(
				array(
					'item_id' => $friend_id,
					'type' => 'thumb',
					'width' => '35px',
					'height' => '35px'
				)
			);

			// Check if Friend is already Invited.
			$checked = in_array( (int) $friends[ $i ]['id'], (array) $invites );

			// Get Code.
			$items[] = '<' . $r['separator'] . '><label class="yz_cs_checkbox_field" for="f-' . esc_attr( $friend_id ) . '"><input' . checked( $checked, true, false ) . ' type="checkbox" name="friends[]" id="f-' . esc_attr( $friend_id ) . '" value="' . esc_attr( $friend_id ) . '" /> ' . $friend_avatar . esc_html( $friends[ $i ]['full_name'] ) . '<div class="yz_field_indication"></div></label></' . $r['separator'] . '>';

		}
	}

	return $items;
}

add_filter( 'bp_get_new_group_invite_friend_list', 'yz_get_new_group_invite_friend_list', 10,  2 );



/**
 * Get Widget Default Settings
 */
function yz_get_widget_defaults_settings( $widget_id ) {
	
	global $wp_widget_factory;

	// Get Widgets List.
	$wp_widgets = $wp_widget_factory->widgets;

	if ( isset( $wp_widgets[ $widget_id ] ) ) {
		return $wp_widgets[ $widget_id ]->default_options;
	}

    return false;
}

/**
 * Call Groups Sidebar
 */
function yz_get_groups_sidebar() {
  	// Display Widgets.
	if ( is_active_sidebar( 'yz-groups-sidebar' ) ) {
		dynamic_sidebar( 'yz-groups-sidebar' );
	}
}

add_action( 'yz_group_sidebar', 'yz_get_groups_sidebar' );

/**
 * Get Group Total Posts Number.
 */
function yz_get_group_total_posts_count( $group_id ) {
 	
 	if ( ! bp_is_active( 'activity' ) ) {
 		return 0;
 	}

	global $bp,$wpdb;
 
	$total_updates = $wpdb->get_var( "SELECT COUNT(*) FROM {$bp->activity->table_name} WHERE component = 'groups' AND item_id = '$group_id' ");
									  
	return $total_updates;
}

/**
 * # Check if Group Have Social Networks Accounts.
 */
function is_group_have_networks( $group_id = null ) {

	// This will be activated in coming updates.
	return false;
    
    // Get Group ID.
    $group_id = ! empty( $group_id ) ? $group_id : null;

    // Get Social Networks
    $social_networks = yz_options( 'yz_group_social_networks' );

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    // Check if there's URL related to the icons.
    foreach ( $social_networks as $network => $data ) {
        $network = yz_data( $network, $user_id );
        if ( ! empty( $network ) ) {
            return true;
        }
    }

    return false;
}

/**
 * Hide Request Membership Nav
 */
function yz_hide_request_membership_from_menu( $code ) {
	return false;
}

add_filter( 'bp_get_options_nav_request-membership', 'yz_hide_request_membership_from_menu' );

/**
 * Display Group Sidebar.
 */
function yz_show_group_sidebar() {
	
	if ( is_super_admin() ) {
		return true;
	}

	global $bp;

	// Get Current Group Status.
	$status = $bp->groups->current_group->status;

	// Get Current Group ID
	$group_id = $bp->groups->current_group->id;

	if ( $status == 'private' && ( ! is_user_logged_in() || ! groups_is_user_member( bp_loggedin_user_id(), $group_id ) ) )  {
		return false;
	}

	return true;
}

/**
 * Set Default Groups Avatar.
 */
function yz_set_group_default_avatar( $avatar, $params ) {

    // Get Default Avatar.
    $default_avatar = yz_options( 'yz_default_groups_avatar' );

    if ( empty( $default_avatar ) ) {
        return $avatar;
    }

    // Set Avatar.
    $avatar = $default_avatar;
    
    return $avatar;
}

add_filter( 'bp_core_default_avatar_group', 'yz_set_group_default_avatar', 10, 2 );


/**
 * Add Groups Open Graph Support.
 */
function yz_groups_open_graph() {

    if ( ! bp_is_group() ) {
        return false;
    }

   	global $bp;

   	// Get Current Group Data.
	$group = $bp->groups->current_group;

    // Get Group Cover Image
    $group_img = bp_attachments_get_attachment(
    	'url', array( 'item_id' => $group->id, 'object_dir' => 'groups' )
    );

    // Get Avatar if Cover Not found.
    if ( empty( $group_img ) ) {
        $group_avatar = bp_core_fetch_avatar( array(
			'avatar_dir' => 'group-avatars',
			'item_id'    => $group->id,
			'object' 	 => 'group',
			'type'	  	 => 'full',
			'html' 	  	 => false
			)
		);

        $group_img = apply_filters( 'yz_og_group_default_thumbnail', $group_avatar );

    }

    // Get Group Link.
    $url = bp_get_group_permalink( $group );

    // Get Group Description.
    $group_description = esc_html( $group->description );

    yz_get_open_graph_tags( 'profile', $url, $group->name, $group_description, $group_img );

}

add_action( 'wp_head', 'yz_groups_open_graph' );

/**
 * Add group header Tools
 */
function yz_get_group_header_tools() {

   	global $bp;

   	// Get Current Group Data.
	$group = $bp->groups->current_group;

	if ( ! $group ) {
		return false;
	}

	yz_get_group_tools( $group->id, 'full-btns' );
}

add_action( 'youzer_group_header', 'yz_get_group_header_tools' );

/**
 * Get Group Tools
 */
function yz_get_group_tools( $group_id = null, $icons = null ) {

	$icons = ! empty( $icons ) ? $icons : 'only-icons';
	
	// Get Ajax Nonce
	$ajax_nonce = wp_create_nonce( 'yz-tools-nonce-' . $group_id );

	?>

	<div class="yz-tools yz-group-tools yz-tools-<?php echo $icons; ?>" data-nonce="<?php echo $ajax_nonce; ?>" data-group-id="<?php echo $group_id; ?>" data-component="group">
		<?php do_action( 'yz_group_tools', $group_id, $icons ); ?>
	</div>

	<?php
}

/**
 * Get User total groups.
 */

function yz_get_group_total_for_member( $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get Transient Option.
    $transient_id = 'yz_count_user_groups_' . $user_id;

    $user_groups = get_transient( $transient_id );

    if ( false === $user_groups ) :

        $user_groups = bp_get_group_total_for_member( $user_id );
        
        set_transient( $transient_id, $user_groups, 12 * HOUR_IN_SECONDS );
        
    endif;

    return $user_groups;
}

/**
 * Delete Groups Count.
 */
function yz_user_groups_count_transient( $user_id = null  ) {
	// Delete Transient.
	delete_transient( 'yz_count_user_groups_' . $user_id );
}

add_action( 'groups_ban_member', 'yz_user_groups_count_transient', 10, 1 );
add_action( 'groups_leave_group', 'yz_user_groups_count_transient', 10, 1 );
add_action( 'groups_remove_member', 'yz_user_groups_count_transient', 10, 1 );
add_action( 'groups_membership_accepted', 'yz_user_groups_count_transient', 10, 1 );
