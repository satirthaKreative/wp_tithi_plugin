<?php

/**
 * Profile Navigation Menu
 */
function yz_profile_navigation_menu() {

    ?>

    <ul class="yz-profile-navmenu">

        <?php yz_get_displayed_user_nav(); ?>

        <?php

        /**
         * Fires after the display of member options navigation.
         *
         * @since 1.2.4
         */
        do_action( 'bp_member_options_nav' ); ?>

    </ul>
    
    <?php
}

/**
 * Profile Get Tab Visibility
 */
function yz_get_profile_tab_visibility( $slug ) {

	// Get Default Tabs Args.
	$defaults = yz_profile_tabs_default_value();

	// Get Default Visibility.
	$default_visibility = isset( $defaults[ $slug ]['visibility'] ) ? $defaults[ $slug ]['visibility'] : 'on';

	// Get visibility.
	$option_id = 'yz_display_' . $slug . '_tab';

	// Get Option.
	$visibility_value = get_option( $option_id );

	// Get Visibility
	$visibility = ! empty( $visibility_value ) ? $visibility_value : $default_visibility;

	// Filter Visibility
	$visibility = apply_filters( 'yz_profile_tab_visibility', $visibility, $slug );

	return $visibility;

}

/**
 * Profile Tabs Default Values
 */
function yz_profile_tabs_default_value() {

	// Init 
	$tabs = array();

	// Overview Args.
	$tabs['overview'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-globe-asia',
	);

	// Wall Args.
	$tabs['wall'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-address-card',
	);

	// Info Args.
	$tabs['info'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-info',
	);

	// Comments Args.
	$tabs['comments'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-comments',
	);

	// Posts Args.
	$tabs['posts'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-pencil-alt',
	);

	// Friends Args.
	$tabs['friends'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-handshake',
	);

	// Friends Args.
	$tabs['follows'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-rss',
	);

	// Groups Args.
	$tabs['groups'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-users',
	);

	// Notifications Args.
	$tabs['notifications'] = array(
		'visibility' => 'off',
		'icon' => 'fas fa-bell',
	);

	// Messages Args.
	$tabs['messages'] = array(
		'visibility' => 'off',
		'icon' => 'fas fa-envelope-open-text',
	);
	
	// Widgets Settings Args.
	$tabs['widgets-settings'] = array(
		'visibility' => 'off',
		'icon' => 'fas fa-sliders',
	);

	// Account Settings Args.
	$tabs['settings'] = array(
		'visibility' => 'off',
		'icon' => 'fas fa-cogs',
	);

	// Profile Settings Args.
	$tabs['profile'] = array(
		'visibility' => 'off',
		'icon' => 'fas fa-user-circle',
	);
	
	// Profile Settings Args.
	$tabs['badges'] = array(
		'visibility' => 'on',
		'icon' => 'fas fa-trophy',
	);

	return $tabs;
}

/**
 * Filter Navigation Menu
 */
function yz_get_displayed_user_nav() {
    
    global $Youzer;
    
    // Init Vars.
    $display_icons = yz_options( 'yz_display_navbar_icons' );

    // Get Navbar Items.
    $profile_navbar = (array) yz_get_profile_primary_nav();

    // Get Menus Limit
    $menus_limit = yz_options( 'yz_profile_navbar_menus_limit' );

    // Get Visible Menu
    $visible_menu = array_slice( $profile_navbar, 0, $menus_limit );
    
    // Get Hidden View More Menu
    $view_more_menu = array_slice( $profile_navbar, $menus_limit );

    foreach ( $visible_menu as $menu_item ) :

    	// Get Menu Data.
    	$menu_data = yz_get_profile_menu_item_data( $menu_item );

    	if ( ! $menu_data ) {
    		continue;
    	}

        ?>

        <li class="yz-navbar-item <?php echo $menu_data['class']; ?>">
            <a href="<?php echo esc_url( $menu_data['link'] ); ?>">
                <?php if ( 'on' == $display_icons && ! empty( $menu_data['icon'] ) ) : ?><i class="<?php echo $menu_data['icon']; ?>"></i><?php endif; ?><?php echo $menu_data['title']; ?></a>
        </li>

        <?php

    endforeach;
    
    if ( empty( $view_more_menu ) ) {
    	return false;
    }

    echo '<li class="yz-navbar-item yz-navbar-view-more"><a>';
	
	if ( 'on' == $display_icons ) {	
	    echo '<i class="fas fa-bars"></i>';
	}
	
	echo __( 'More', 'youzer' ) . '</a>';

    echo '<ul class="yz-nav-view-more-menu">';
    
    foreach ( $view_more_menu as $menu_item ) :

    	$menu_data = yz_get_profile_menu_item_data( $menu_item );

    	if ( ! $menu_data ) {
    		continue;
    	}

        ?>
        
		<li class="yz-navbar-item <?php echo $menu_data['class']; ?>">
            <a href="<?php echo esc_url( $menu_data['link'] ); ?>">
                <?php if ( 'on' == $display_icons && ! empty( $menu_data['icon'] ) ) : ?><i class="<?php echo $menu_data['icon']; ?>"></i><?php endif; ?><?php echo $menu_data['title']; ?></a>
        </li>

        <?php

    endforeach;

    echo '</ul></li>';

}

add_filter( 'bp_nav_menu_args', 'yz_filter_profile_nav_menu', 10 );

/**
 * Get Menu Data
 */
function yz_get_profile_menu_item_data( $menu_item ) {

    // Init Menu Data
    $menu_data = array();

    // Get Tab Class.
    $menu_data['class'] = bp_is_current_component( $menu_item->slug ) ? 'yz-active-menu': null;
    
    // Get Tab Icon
    $menu_data['icon'] = yz_get_profile_nav_menu_icon( $menu_item->slug );
    
    // Get Tab Link
    $menu_data['link'] = yz_get_profile_nav_menu_link( $menu_item );
    
    // Delete Tabs Count.
    $show_count = yz_options( 'yz_display_profile_tabs_count' );

    // Get Tab title.
    $menu_data['title'] = ( $show_count == 'off' ) ? _bp_strip_spans_from_title( $menu_item->name ) : $menu_item->name;

    return $menu_data;
}

/**
 * Get Profile Navigation Menu Link
 */
function yz_get_profile_nav_menu_link( $menu_item = null ) {

	global $Youzer;

 	// Get Tab Link.
    if ( bp_loggedin_user_domain() ) {
        $tab_link = str_replace( bp_loggedin_user_domain(), bp_displayed_user_domain(), $menu_item->link );
    } else {
        $tab_link = trailingslashit( bp_displayed_user_domain() . $menu_item->link );
    }

	// Get Tab ID.
	$tab_id = yz_get_custom_tab_id_by_slug( $menu_item->slug );

    // Get Custom Tab Link.
	if ( yz_is_custom_tab( $tab_id ) ) {

		// Get Tab Type.
		$tab_type = $Youzer->tabs->custom->get_tab_data( $tab_id, 'type' );

		if ( 'link' == $tab_type ) {
			// Get Tab Link.
			$tab_link = $Youzer->tabs->custom->get_tab_data( $tab_id, 'link' );
			// Filter Tab Url.
			$tab_link = apply_filters( 'yz_profile_custom_tab_url', $tab_link );
		}

	}

	return $tab_link;
}

/**
 * Get Profile Navigation Menu Icon
 */
function yz_get_profile_nav_menu_icon( $slug = null ) {

    global $Youzer;

    // Default Tab Values.
    $default_tabs = yz_profile_tabs_default_value();

    // Default Icon.
    $default_icon = isset( $default_tabs[ $slug ] ) ? $default_tabs[ $slug ]['icon'] : 'fas fa-globe-asia';

	// Get Option ID.
	$option_id = 'yz_' . $slug . '_tab_icon';

	// Get Option.
	$icon_value = yz_options( $option_id );

	// Get Icon.
	$icon = ! empty( $icon_value ) ? $icon_value : $default_icon;

    // Filter Icon.
    $icon = apply_filters( 'yz_profile_nav_menu_icon', $icon, $slug );

    return $icon;

}

/**
 * Get Profile Sub Navigation Menu Icon
 */
function yz_get_profile_subnav_menu_icon( $subnav_item ) {

    // Default Icon.
    $icon = 'fas fa-globe';

    // Get Tab Slug.
    $tab_slug = $subnav_item->parent_slug . '_' . $subnav_item->slug;

	// Get Option ID.
	$option_id = 'yz_ctabs_' . $tab_slug . '_icon';
	
	// Get Option.
	$icon_value = yz_options( $option_id );

	// Get Icon.
	if ( ! empty( $icon_value ) ) {
		$icon = $icon_value;
	}

    // Filter Icon.
    $icon = apply_filters( 'yz_profile_subnav_menu_icon', $icon, $option_id );

    return $icon;

}

/**
 * Get Profile Tabs Icons
 */
function yz_profile_subnav_menu_default_icons( $icon = null, $option_id = null ) {

	switch ( $option_id ) {

		case 'yz_ctabs_wall_just-me_icon':
			$icon = 'fas fa-user-circle';
			break;

		case 'yz_ctabs_wall_following_icon':
			$icon = 'fas fa-rss';
			break;
			
		case 'yz_ctabs_follows_followers_icon':
			$icon = 'fas fa-share';
			break;

		case 'yz_ctabs_follows_following_icon':
			$icon = 'fas fa-reply';
			break;

		case 'yz_ctabs_friends_my-friends_icon':
		case 'yz_ctabs_groups_my-groups_icon':
		case 'yz_ctabs_wall_groups_icon':
			$icon = 'fas fa-users';
			break;

		case 'yz_ctabs_friends_requests_icon':
		case 'yz_ctabs_wall_friends_icon':
			$icon = 'fas fa-handshake';
			break;

		case 'yz_ctabs_wall_mentions_icon':
			$icon = 'fas fa-at';
			break;

		case 'yz_ctabs_wall_favorites_icon':
			$icon = 'fas fa-heart';
			break;

		case 'yz_ctabs_notifications_unread_icon':
			$icon = 'fas fa-eye-slash';
			break;

		case 'yz_ctabs_notifications_read_icon':
			$icon = 'fas fa-eye';
			break;

		case 'yz_ctabs_messages_inbox_icon':
			$icon = 'fas fa-inbox';
			break;

		case 'yz_ctabs_messages_starred_icon':
			$icon = 'fas fa-star';
			break;

		case 'yz_ctabs_messages_compose_icon':
			$icon = 'fas fa-edit';
			break;
			
		case 'yz_ctabs_messages_notices_icon':
			$icon = 'fas fa-bullhorn';
			break;
			
		case 'yz_ctabs_messages_sentbox_icon':
			$icon = 'fas fa-paper-plane';
			break;

		case 'yz_ctabs_groups_invites_icon':
			$icon = 'fas fa-paper-plane';
			break;
	
	}

	return $icon;
}

add_filter( 'yz_profile_subnav_menu_icon', 'yz_profile_subnav_menu_default_icons', 10, 2 );

/**
 * Get Primary Navigation Menu Icon
 */
function yz_get_profile_primary_nav() {

	global $bp;

	// Init Array().
	$new_primary_nav = array();

	// Get Original Primary Nav
	$primary_nav = $bp->members->nav->get_primary();

	// Hidden Tabs
	$hidden_tabs = array( 'yz-home', 'widgets-settings', 'profile', 'settings' );

	// Filter Hidden Tabs.
	$hidden_tabs = apply_filters( 'yz_profile_hidden_tabs', $hidden_tabs );

	foreach ( $primary_nav as $nav ) {

		// Don't Show Youzer Hidden Tabs.
		if ( in_array( $nav['slug'], $hidden_tabs ) ) {
			continue;
		}

		if ( ! is_admin() ) {

			// Hide Invisible Tabs.
			if ( 'off' == yz_get_profile_tab_visibility( $nav['slug'] ) ) {
				continue;
			}

			// Check if tab should be displayed for the current user.
		    if ( empty( $nav['show_for_displayed_user'] ) && ! bp_is_my_profile() ) {
				continue;
		    }

		    // Hide Comments Menu if User Have no comments.
		    if ( $nav['slug'] == 'comments' && ! yz_is_user_have_comments() ) {
				continue;
		    }

		    // Hide Posts Menu if User Have no posts.
		    if ( $nav['slug'] == 'posts' && ! yz_is_user_have_posts() ) {
				continue;
		    }
		}

		// Add Tab.
		$new_primary_nav[] = $nav;
	}

	// Filter Primary Nav.
	$new_primary_nav = apply_filters( 'yz_profile_primary_nav', $new_primary_nav );

	return $new_primary_nav;
}

/**
 * Primary Tabs Slugs
 */
function yz_get_profile_primary_nav_slugs() {

	// Get Youzer Custom Tabs
	$primary_tabs = yz_get_profile_primary_nav();

	if ( empty( $primary_tabs ) ) {
		return false;
	}

	// Get Only Custom Tabs slugs.
	$tabs_slugs = wp_list_pluck( $primary_tabs, 'slug' );

	// Filter Tabs Slugs
	$tabs_slugs = apply_filters( 'yz_profile_primary_nav_slugs', $tabs_slugs );

	return $tabs_slugs;

}
/**
 * Profile Default Nav Options
 */
function yz_get_profile_default_nav_options() {

	// Get Youzer Custom Tabs
	$primary_tabs = yz_get_profile_primary_nav();

	if ( empty( $primary_tabs ) ) {
		return false;
	}

	global $Youzer;

	// Init
	$tab_options = array();

	foreach ( $primary_tabs as $tab ) {
		
		// Get Tab Slug.
		$tab_slug = $tab['slug'];
		
		// Get Tab ID.
		$tab_id = yz_get_custom_tab_id_by_slug( $tab_slug );

        // Get Custom Tab Link.
		if ( yz_is_custom_tab( $tab_id ) ) {

			// Get Tab Type.
			$tab_type = $Youzer->tabs->custom->get_tab_data( $tab_id, 'type' );

			if ( 'link' == $tab_type ) {
				continue;
			}
		}

		// Check is Tab Deleted.
		if ( yz_is_profile_tab_deleted( $tab_slug ) ) {
			continue;
		}

		// Set Option.
		$tab_options[ $tab_slug ] = _bp_strip_spans_from_title( $tab['name'] );

	}

	return $tab_options;
}

/**
 * Check Is Navigation  deleted by slug.
 */
function yz_is_profile_tab_deleted( $slug ) {

	// Get Delete Tab Value.
	$delete_tab = get_option( 'yz_delete_' . $slug . '_tab' );

	if ( ! empty( $delete_tab ) && 'on' == $delete_tab ) {
		return true;	
	}

	return false;
}

/**
 * Youzer Default Tabs.
 */
function yz_get_youzer_default_tabs() {

	// Default Tabs.
	$tabs = array( 'overview', 'info', 'posts', 'comments', 'badges' );

	return $tabs;
}

/**
 * Get Third Party Navigation Tabs.
 */
function yz_get_profile_third_party_tabs() {

	global $bp;

	// Init Array().
	$third_party_tabs = array();

	// Get Original Primary Nav
	$primary_tabs = $bp->members->nav->get_primary();

	// Hidden Tabs ( All Youzer Default + Custom Tabs ).
	$youzer_tabs = yz_get_all_youzer_tabs_slugs();

	foreach ( $primary_tabs as $tab ) {

		// Don't Show Youzer Hidden Tabs.
		if ( in_array( $tab['slug'], $youzer_tabs ) ) {
			continue;
		}

		// Add Tab.
		$third_party_tabs[] = $tab;
	}

	return $third_party_tabs;
}

/**
 * Get Custom BP Tabs.
 */
function yz_get_custom_bp_tabs() {

	// Get Primary Tabs.
	$tabs = yz_get_profile_primary_nav();

	// Get User Default Tabs
	$youzer_tabs = array();

	// Remove Default Youzer Tabs.
	foreach ( $tabs as $key => $tab ) {
		if ( in_array( $tab['slug'], $youzer_tabs ) ) {
			unset( $tabs[ $key] );
		}
	}

	return $tabs;
}

/**
 * Get Third Party Plugins Sub Tabs.
 */
function yz_get_profile_third_party_subtabs() {

    // Get Tabs
    $custom_tabs = yz_get_profile_third_party_tabs();

    if ( empty( $custom_tabs ) ) {
        return false;
    }
	
    // Init Vars.
    $bp = buddypress();
    $secondary_tabs = array();

    foreach ( $custom_tabs as $tab ) {

        // Get Tab Slug
        $tab_slug = isset( $tab['slug'] ) ? $tab['slug'] : null;

        // Get Tab Navigation  Menu
        $secondary_nav = $bp->members->nav->get_secondary( array( 'parent_slug' => $tab_slug ) );

        if ( empty( $secondary_nav ) ) {
            continue;
        }

        // Get Settings.
        $secondary_tabs[] = $secondary_nav;
    }

    return $secondary_tabs;
}

/**
 * Get All Youzer Tabs.
 */
function yz_get_all_youzer_tabs_slugs() {

	// Get All Youzer Default Tabs.
	$youzer_tabs = array( 'yz-home', 'profile', 'settings', 'widgets-settings', 'messages', 'notifications', 'friends', 'groups', 'comments', 'posts', 'wall', 'overview', 'info', 'badges', 'follows' ); 

	// Get Youzer Custom Tabs Slugs.
	$custom_tabs = (array) yz_custom_youzer_tabs_slugs();

	// Merge Arrays.
	$all_tabs = array_merge( $youzer_tabs, $custom_tabs );

	return $all_tabs;
}

/**
 * Youzer Custom Tabs
 */
function yz_custom_youzer_tabs( $query = null ) {

	// Get Custom Tabs
	$custom_tabs = yz_options( 'yz_custom_tabs' );

	if ( empty( $custom_tabs ) ) {
		return false;
	}

	// Init Array().
	$tabs = array();

	foreach ( $custom_tabs as $tab_id => $data ) {

		if ( 'false' == $data['display_nonloggedin'] && ! is_user_logged_in() ) {
			continue;
		}

		// Get Custom Tab Args.
		$custom_args = array(
			'tab_name'    => $tab_id,
			'tab_title'   => $data['title'],
            'tab_slug'	  => yz_get_custom_tab_slug( $data['title'] )
		);

		// Add tab to the tabs list.
		$tabs[ $tab_id ] = $custom_args;

	}

	// Filter Tabs.
	$tabs = apply_filters( 'yz_custom_youzer_tabs', $tabs );

	return $tabs;
}

/**
 * Youzer Custom Tabs List
 */
function yz_custom_youzer_tabs_slugs() {

	// Init Array.
	$tabs_slugs = array();

	// Get Youzer Custom Tabs
	$custom_tabs = yz_custom_youzer_tabs();

	if ( empty( $custom_tabs ) ) {
		return false;
	}

	foreach ( $custom_tabs as $tab ) {
		$tabs_slugs[] = $tab['tab_slug'];
	}

	// Filter Tabs Slugs
	$tabs_slugs = apply_filters( 'yz_custom_youzer_tabs_slugs', $tabs_slugs );

	return $tabs_slugs;

}

/**
 * Get Custom Tab ID By Slug
 */
function yz_get_custom_tab_id_by_slug( $slug ) {
	
	// Init Vars.
	$tab_id = null;

	// Get Custom Tabs
	$custom_tabs = yz_custom_youzer_tabs();

	if ( empty( $custom_tabs ) ) {
		return $slug;
	}

	// Search For Id.
	foreach ( $custom_tabs as $key => $tab ) {
	    if ( $tab['tab_slug'] == $slug ) {
	    	$tab_id = $key;
	    	break;
	    }
	}

	return $tab_id;
}

/**
 * Check Is Current Tab has a Secondary Tab Menu.
 */
function yz_is_current_tab_has_children() {

	// Init Vars.
	$bp = buddypress();
	$has_children = true;

	// Get Current Tab Slug.
	$parent_slug = ! empty( $bp->displayed_user ) ? bp_current_component() : bp_get_root_slug( bp_current_component() );

	// Get Tab Navigation  Menu
	$nav_menu = $bp->members->nav->get_secondary( array( 'parent_slug' => $parent_slug ) );

	if ( empty( $nav_menu ) ) {
		$has_children = false;
	}

	return apply_filters( 'yz_is_current_tab_has_children', $has_children );
}

/**
 * Prepare Secondary Tabs Icons.
 */
function yz_get_secondary_tabs_icons( $menu_item, $subnav_item, $selected_item ) {

	// Get Current Tab Icon.
	$tab_icon = yz_get_profile_subnav_menu_icon( $subnav_item );

	// If the current action or an action variable matches the nav item id, then add a highlight CSS class.
	if ( $subnav_item->slug === $selected_item ) {
		$selected = ' class="current selected"';
	} else {
		$selected = '';
	}

	// List type depends on our current component.
	$list_type = bp_is_group() ? 'groups' : 'personal';

	return '<li id="' . esc_attr( $subnav_item->parent_slug . '-' . $subnav_item->css_id . '-' . $list_type . '-li' ) . '" ' . $selected . '><a id="' . esc_attr( $subnav_item->css_id ) . '" href="' . esc_url( $subnav_item->link ) . '"><i class="' . $tab_icon . '"></i>' . $subnav_item->name . '</a></li>';

}

/**
 * Add Third Party Sub Tabs Icons.
 */
function yz_add_profile_third_party_subtabs_icons() {

	// Init Vars.
	$bp = buddypress();

	// Get Current Tab Slug.
	$parent_slug = ! empty( $bp->displayed_user ) ? bp_current_component() : bp_get_root_slug( bp_current_component() );

	// Get Tab Navigation  Menu
	$custom_tabs = $bp->members->nav->get_secondary( array( 'parent_slug' => $parent_slug ) );

	// Pass Buddpress Default Tabs.
	if ( empty( $custom_tabs ) ) {
		return false;
	}

    foreach ( $custom_tabs as $tab ) {
		// Filter Custom Tabs Menu.
		add_filter( 'bp_get_options_nav_'. $tab['css_id'], 'yz_get_secondary_tabs_icons', 10, 3 );
    }

}

add_action( 'bp_ready', 'yz_add_profile_third_party_subtabs_icons', 999 );

/**
 * Filter Custom Tab Url.
 */
function yz_profile_custom_tab_url( $link = null ) {

    // Get Displayed profile username.
    $displayed_username = bp_core_get_username( bp_displayed_user_id() );

    // Replace Tags.
    $link = wp_kses_decode_entities( str_replace( '{username}', $displayed_username, $link ) );

    return $link;

}

add_filter( 'yz_profile_custom_tab_url', 'yz_profile_custom_tab_url', 999 );

/**
 * Get Custom Tab Slug.
 */
function yz_get_custom_tab_slug( $tab_title ) {
    // Get Slug.
    return strtolower( str_replace( ' ', '-', $tab_title ) );
}

/**
 * Get Custom Tab Slug.
 */
function yz_get_tab_name_by_slug( $current_tab_slug ) {
    
    // Init Var.
    $current_tab = false;

    // Get All Custom Tabs.
    $tabs = yz_options( 'yz_custom_tabs' );

    if ( empty( $tabs ) ) {
        return false;
    }

    foreach ( $tabs as $tab_id => $data ) {

        // Get Tab Slug.
        $tab_slug = yz_get_custom_tab_slug( $data['title'] );

        if ( $current_tab_slug == $tab_slug ) {
            $current_tab = $tab_id;
            break;
        }

    }

    return $current_tab;
}

/**
 * Get Tab visibility
 */
function yz_get_tab_visibility( $option_id ) {

    // Get Option Value.
    $option_value = get_option( $option_id );

    // Filter Option Value.
    $option_value = apply_filters( 'yz_get_tab_visibility', $option_value, $option_id );

    if ( $option_value ) {
        return $option_value;
    }

    return 'on';
}
/**
 * Get Profile Deletable Tabs.
 */
function yz_profile_deletable_tabs() {

    // Get Default Tabs Slugs.
    $default_tabs = yz_get_youzer_default_tabs();

	// Get Youzer Custom Tabs Slugs.
	$custom_tabs = (array) yz_custom_youzer_tabs_slugs();

	// Merge Tabs Slugs.
	$all_tabs = array_merge( $default_tabs, $custom_tabs );

	return $all_tabs;
}

/**
 * Delete Profile Navigation Menu
 */
function yz_delete_profile_navigation_menu() {

	if ( ! bp_is_user() ) {
		return false;
	}

	// Get Deletable Tabs Slugs.
	$all_tabs = yz_profile_deletable_tabs();

    foreach ( $all_tabs as $slug ) {

		if ( yz_is_profile_tab_deleted( $slug ) && ! is_admin() ) {
			bp_core_remove_nav_item( $slug );
		}

	}

}

add_action( 'bp_setup_nav', 'yz_delete_profile_navigation_menu' );

/**
 * Update Profile Navigation Menu
 */
function yz_update_profile_navigation_menu() {
	
	if ( ! bp_is_user() ) {
		return;	
	}

    // Get Primary Tabs.
    $primary_tabs = yz_get_profile_primary_nav();

    foreach ( $primary_tabs as $tab ) {

    	// Get Tab Slug
    	$slug = $tab['slug'];

    	// Init Array.
    	$tab_args = array();

    	// Get New Tab Name.
		$tab_title = get_option( 'yz_' . $slug . '_tab_title' );

		if ( ! empty( $tab_title ) ) {	
			$tab_args['name'] = yz_get_new_profile_tab_title( $tab_title, $tab['name'] );
		}

    	// Get New Tab Order.
		$tab_order = get_option( 'yz_' . $slug . '_tab_order' );

		if ( ! empty( $tab_order ) && is_numeric( $tab_order ) ) {
			$tab_args['position'] = $tab_order;
		}	

		if ( empty( $tab_args ) ) {
			continue;
		}

	    buddypress()->members->nav->edit_nav( $tab_args, $tab['slug'] );
	
    }

}

add_action( 'bp_actions', 'yz_update_profile_navigation_menu' );

/**
 * Get New Updated Title
 */
function yz_get_new_profile_tab_title( $new_title = null, $old_title ) {

	if ( ! empty( $new_title ) ) {
		// Get Old Title Count.
		$count = strstr( $old_title, '<span' );
		$new_title = ! empty( $count ) ? $new_title . $count : $new_title;
	}

	return $new_title;
}

/**
 * Set Default Profile Tab
 */
function yz_get_default_profile_tab() {

    // Get default tab
    $default_tab = get_option( 'yz_profile_default_tab' );

    if ( ! empty( $default_tab ) && ! yz_is_profile_tab_deleted( $default_tab ) ) {
    	return $default_tab;
    }

    return yz_get_youzer_default_tab();
}

/**
 * Get Youzer Default Profile Tab.
 */
function yz_get_youzer_default_tab() {

	// Init Var.
	$default_tab = false;

    // Get Youzer Default Tab.
	$youzer_tabs = yz_get_youzer_default_tabs();

	if ( empty( $youzer_tabs ) ) {
		return false;
	}

	foreach ( $youzer_tabs as $tab ) {
		if ( ! yz_is_profile_tab_deleted( $tab ) ) {
			$default_tab = $tab;
			break; 
		}
	}

	return $default_tab;
}


/** 
 * Set Default Profile Tab.
 */
function yz_set_default_profile_tab() {

	if ( ! bp_is_user() ) {
		return false;
	}

	// Get Default Tab.
	$default_tab = yz_get_default_profile_tab();

	if ( empty( $default_tab ) )  {
		return false;
	}

	// Set Default Tab
	if ( ! defined( 'BP_DEFAULT_COMPONENT' ) ) {
    	define( 'BP_DEFAULT_COMPONENT', $default_tab );
	}

}

add_action( 'bp_init', 'yz_set_default_profile_tab', 3 );

/**
 * Display Select Default Tab Message.
 */
function yz_reset_profile_default_tab_msg() {

	// Get default tab
    $default_tab = get_option( 'yz_profile_default_tab' );

	if ( ! empty( $default_tab ) ) {
		return false;
	}

	// Get Settings Url.
	$tabs_settings_url = admin_url( 'admin.php?page=yz-profile-settings&tab=tabs' );

	// Get Message Class.
	$class = 'notice notice-info is-dismissible';

	// Get Message Content.
	$message = sprintf( __( 'The default profile tab is deleted or not exist, Please choose another one from the <a href="%s">Profile Tabs Settings</a> !!', 'youzer' ), $tabs_settings_url );

	// Print Message.
	printf( '<div class="%1$s"><strong><p>%2$s</p></strong></div>', esc_attr( $class ), $message );


}

add_action( 'admin_notices', 'yz_reset_profile_default_tab_msg' );

/**
 * Disable Profile Default Tab.
 */
function yz_disable_profile_default_tab() {
	
	if ( ! is_admin() ) {
		return false;
	}	

	// Get default tab
    $default_tab = get_option( 'yz_profile_default_tab' );

	// Get Available Default Tabs.
	$available_tabs = yz_get_profile_default_nav_options();

	// Get Available Tabs Slugs
	$available_tabs_slugs = array_keys( $available_tabs );

	// Delete tab if not exist.
	if ( ! empty( $default_tab ) && ! in_array( $default_tab, $available_tabs_slugs ) ) {
		delete_option( 'yz_profile_default_tab' );
	}

}

add_action( 'bp_ready', 'yz_disable_profile_default_tab', 9999 );

/**
 * Create Menu Shortcode.
 */
function yz_user_account_avatar() {

	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Get Data
	$display_icon = yz_options( 'yz_disable_wp_menu_avatar_icon' );

	// Get Logged-IN User ID.
	$user_id = bp_loggedin_user_id();

	ob_start();

    ?>

    <div class="yz-primary-nav-area">

        <div class="yz-primary-nav-settings">
            <div class="yz-primary-nav-img" style="background-image: url(<?php echo bp_core_fetch_avatar( array(
            'item_id' => $user_id, 'type' => 'thumbnail', 'html' => false ) ); ?>)"></div>
            <?php echo bp_core_get_username( $user_id ); ?>
            <?php if ( 'on' == $display_icon ) : ?><i class="fas fa-angle-down yz-settings-icon"></i><?php endif; ?>
        </div>
        
    </div>

    <?php

    // Get All This Function Content.
	$content = ob_get_contents();

	// Clean
	ob_end_clean();

    return $content;
}

add_shortcode( 'youzer_account_avatar', 'yz_user_account_avatar' );

/**
 * Activate Shortcode in Wordpress Menu
 */
function yz_activate_wp_menus_shortcodes( $title, $item, $args, $depth ) {
    return do_shortcode( $title );
}

add_filter( 'nav_menu_item_title', 'yz_activate_wp_menus_shortcodes', 10, 4 );

/**
 * Replace Wordpress Menu Variable
 */
function menu_override( $atts, $item, $args ) {
    $user = wp_get_current_user();
    $newlink = str_replace( '#yz_user#', $user->user_login, $atts['href'] );
    $atts['href'] = $newlink;
    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'menu_override', 10, 3 );