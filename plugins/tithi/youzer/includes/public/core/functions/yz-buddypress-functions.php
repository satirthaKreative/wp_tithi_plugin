<?php

/**
 * Check if Buddypress Installed and active.
 */
function yz_is_bp_active() {

    if ( function_exists( 'bp_is_active' ) ) {
        return true;
    }

    return false;
}

/**
 * Disable Buddypress Default CSS.
 */
function yz_deregister_bppress_styles() {
    wp_dequeue_style( 'bp-nouveau' );
    wp_dequeue_style( 'bp-parent-css' );
    wp_dequeue_style( 'bp-legacy-css' );
    wp_dequeue_style( 'bp-legacy-css-rtl' );
    wp_dequeue_style( 'bp-messages-autocomplete' );
}

add_action( 'wp_print_styles', 'yz_deregister_bppress_styles', 15 );

/**
 * Register Youzer Buddypress Templates Folder Location
 */
function yz_bp_register_template_location() {
    return YZ_TEMPLATE . '/';
}

/**
 * Replace Buddypress Templates With Youzer Templates
 */
function yz_bp_replace_template( $old_template, $slug, $name ) {

    $new_template = null;

    if ( 'members/single/home' == $slug || 'activity/single/home' == $slug  ) {
        if ( yz_is_account_page() ) {
            $new_template =  array( 'account-template.php' );
        } else {
            $new_template = array( 'profile-template.php' );
        }
    }

    $new_template = ! empty( $new_template ) ? $new_template : $old_template;

    return apply_filters( 'yz_bp_replace_template', $new_template, $old_template );

}
 
/**
 * Over Load Templates.
 */ 
function yz_bp_overload_templates() {

    // Get New Templates Location
    if ( function_exists( 'bp_register_template_stack' ) ) {
        bp_register_template_stack( 'yz_bp_register_template_location', 1 );
    }
     
    // If Viewing A Member Page, Overload The Template
    if ( bp_is_user()  ) {
        add_filter( 'bp_get_template_part', 'yz_bp_replace_template', 10, 3 );
    }

}

add_action( 'bp_init', 'yz_bp_overload_templates' );

/**
 * Translate Some Buddypress Words.
 */

function yz_bp_multiple_translate_text( $translated_text ) {
    switch ( $translated_text ) {
        case 'Remove Favorite' :
            $translated_text = __( 'Unlike', 'youzer' );
            break;
        case 'Favorite' :
            $translated_text = __( 'Like', 'youzer' );
            break;
        case 'Favorites' :
            $translated_text = __( 'Likes', 'youzer' );
            break;
        case '(required)' :
            $translated_text = __( 'required', 'youzer' );
            break;
        case 'Cancel Friendship Request' :
            $translated_text = __( 'Cancel Request', 'youzer' );
            break;
        case '[Read more]' :
            $translated_text = __( 'Read More', 'youzer' );
            break;
    }
    return $translated_text;
}

add_filter( 'gettext', 'yz_bp_multiple_translate_text', 20 );

/**
 * Edit Friendship Buttons Text
 */
function yz_edit_friendship_buttons_text( $button ) {

    switch ( $button['id'] ) {

        case 'is_friend' :
        $button['link_text'] = __( 'Unfriend', 'youzer' );      
        break;
        
        case 'awaiting_response' :
        $button['link_text'] = __( 'Confirm Request', 'youzer' );      
        break;

        case 'pending' :
        $button['link_text'] = __( 'Cancel Request', 'youzer' );      
        break;

    }

    return $button;
}

add_filter( 'bp_get_add_friend_button', 'yz_edit_friendship_buttons_text', 999 );

/**
 * Disables BuddyPress' registration process and fallsback to WordPress' one.
 */
function yz_disable_bp_registration() {

    if ( yz_is_logy_active() || is_user_logged_in() ) {
        return false;
    }
    
	$disable_registration = yz_options( 'yz_disable_bp_registration' );

	if ( 'off' == $disable_registration ) {
		return false;
	}

	remove_action( 'bp_init',    'bp_core_wpsignup_redirect' );
	remove_action( 'bp_screens', 'bp_core_screen_signup' );
}

add_action( 'bp_loaded', 'yz_disable_bp_registration' );

/**
 * Is Youzer Custom Component
 */
function yz_is_youzer_custom_component() {
    if (
        bp_is_current_component( 'posts' ) || bp_is_current_component( 'comments' ) ||
        bp_is_current_component( 'info' ) || bp_is_current_component( 'overview' )
    ) {
        return true;
    }

    return false;
}

/**
 * Customize WordPress Toolbar
 */
function yz_bp_customize_toolbar( $wp_admin_bar ) {

    // Get Login Node.
    $login_node = $wp_admin_bar->get_node( 'bp-login' );
    
    if ( $login_node ) {

        // Edit Buddypress Toolbar Login Url
        $wp_admin_bar->add_node(
            array(
                'id'   => 'bp-login',
                'href' => yz_get_login_page_url()
            )
        );

    }

    if ( ! is_user_logged_in() ) {
        return false;
    }

    // Get Current User Domain.
    $user_domain = bp_core_get_user_domain( bp_displayed_user_id() );
    $profile_url = $user_domain . bp_get_profile_slug() . '/';

    // Get Edit Member.
    $edit_member = $wp_admin_bar->get_node( 'user-admin' );
    
    if ( $edit_member ) {

        // Modify "Edit Profile " Link.
        $wp_admin_bar->add_node(
            array(
                'id'   => 'user-admin-edit-profile',
                'href' => $profile_url
            )
        );
    }

    // Get My Account.
    $my_account = $wp_admin_bar->get_node( 'my-account' );
    
    if ( $my_account ) {

        // Get Edit profile link.
        $edit_my_profile_link = bp_loggedin_user_domain() . bp_get_profile_slug() . '/';
        
        // Mofidy "Edit My Profile" Link.
        $wp_admin_bar->add_node(
            array(
                'id'   => 'edit-profile',
                'href' => $edit_my_profile_link
            )
        );        

        if (  bp_is_active( 'xprofile' ) ) {
            
            // Modify "Profile - View " Link.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-xprofile-public',
                    'href' =>  bp_loggedin_user_domain()
                )
            );

            // Modify "Profile - Edit " Link.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-xprofile-edit',
                    'href' => bp_loggedin_user_domain() . bp_get_profile_slug() . '/'
                )
            );

        }

        if (  bp_is_active( 'notifications' ) ) {

            // Modify "Settings - Email" Title.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-settings-notifications',
                    'title'=> __( 'Notifications', 'youzer' )
                )
            );

        }
        
        if (  bp_is_active( 'activity' ) ) {

            // Modify "Activity" Title.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-activity',
                    'title'=> yz_options( 'yz_wall_tab_title' )
                )
            );

        }

        if ( bp_is_active( 'settings' ) ) {

        // Modify "Settings - Email" Title.
        $wp_admin_bar->add_node(
            array(
                'parent' => 'my-account-settings',
                'title'  => __( 'Change Password', 'youzer' ),
                'id'     => 'my-account-settings-change-password',
                'href'   => bp_loggedin_user_domain() . bp_get_settings_slug() . '/change-password'
            )
        );
    }

        // Remove "Settings( General & Profile )" Link.
        $wp_admin_bar->remove_node( 'my-account-settings-general' );
        $wp_admin_bar->remove_node( 'my-account-settings-profile' );

    }
}

add_action( 'admin_bar_menu', 'yz_bp_customize_toolbar', 999 );

/**
 * Remove Topbar activity menu
 */
function yz_remove_top_bar_activity_menu( $wp_admin_bar ) {

    // Get Wall Tab Visibility
    $display_wall = yz_options( 'yz_display_wall_tab' );

    if ( bp_is_active( 'activity' ) && 'on' == $display_wall ) {
        return false;
    }
        
    // Remove Activity Menu.
    $wp_admin_bar->remove_node( 'my-account-activity' );

}

add_action( 'admin_bar_menu', 'yz_remove_top_bar_activity_menu', 999 );

/**
 * Change Activity Name to wall.
 */
function yz_rename_profile_activity_tab() {
    
    if ( ! bp_is_user() ) {
        return false;
    }

    if ( bp_is_active( 'activity' ) ) {
        // Get Wall Tab.
        $tab_title = yz_options( 'yz_wall_tab_title' );

        // Change "Activity" to "wall"
        buddypress()->members->nav->edit_nav( array( 'name' => $tab_title, 'position' => 2 ), bp_get_activity_slug() );
    }

    if ( bp_is_active( 'settings' ) ) {
        // Change "Settings" to "Account Settings"
        buddypress()->members->nav->edit_nav( array( 'name' => __( 'Account Settings', 'youzer' ) ), bp_get_settings_slug() );
    }

    // Change "Profile" to "Profile Settings"
    buddypress()->members->nav->edit_nav( array( 'name' => __( 'Profile Settings', 'youzer' ) ), bp_get_profile_slug() );

}

add_action( 'bp_actions', 'yz_rename_profile_activity_tab', 1 );

/**
 * Edit Activity Slug.
 */
function yz_rename_activity_slug() {

    if ( defined( 'BP_ACTIVITY_SLUG' ) || ! bp_is_active( 'activity' ) ) {
        return false;
    }

    define( 'BP_ACTIVITY_SLUG', 'wall' );
}

add_action( 'init', 'yz_rename_activity_slug', 1 );

/**
 * Max upload file size for any attachment.
 */
function yz_set_max_attachments_file_size( $size = null ) {
    // 10MB.
    return 10240000;
}

add_filter( 'bp_attachments_get_max_upload_file_size', 'yz_set_max_attachments_file_size', 999 );

/**
 * Edit My Profile Page LiNK
 */
function yz_edit_my_profile_menu_link( $items ) {

    foreach( $items as $item ) {

        if ( in_array( 'bp-yz-home-nav', $item->classes ) ) {
            // Get Logged-in User Domain.
            $item->url = bp_loggedin_user_domain();
        }
    }

    return $items;
}

add_filter( 'wp_nav_menu_objects', 'yz_edit_my_profile_menu_link', 10 );

/**
 * Strip Emoji From Content.
 */
function yz_hide_emoji_from_content() {
    
    if ( is_admin() ) {
        return false;
    }

    if ( yz_is_activity_component() ) {

        // Hide Posts Emoji
        if ( 'off' == yz_options( 'yz_enable_posts_emoji' ) ) {
            add_filter( 'bp_get_activity_content_body', 'yz_remove_emoji' );
        }

        // Hide Comments Emoji
        if ( 'off' == yz_options( 'yz_enable_comments_emoji' ) ) {
            add_filter( 'bp_activity_comment_content', 'yz_remove_emoji' );
        }
    
    }

    // Hide Messages Emoji.
    if ( bp_is_messages_component() && 'off' == yz_options( 'yz_enable_messages_emoji' ) ) {
        add_filter( 'bp_get_the_thread_message_content', 'yz_remove_emoji' );
        add_filter( 'bp_get_message_thread_excerpt', 'yz_remove_emoji' );
        add_filter( 'bp_get_message_notice_text', 'yz_remove_emoji' );
    }

}

add_action( 'bp_init', 'yz_hide_emoji_from_content' );

/**
 * Get Xprofile fields by field type.
 */
function yz_get_xprofile_fields_by_type( $fields_type ) {
    
    global $wpdb;

    // Get Fields Table Name.
    $fields_table = buddypress()->profile->table_name_fields;

    // Get Sql Query.
    $sql = "SELECT id FROM {$fields_table} WHERE type = %s";

    // Get Fields ID'S.
    $fields_ids = $wpdb->get_col( $wpdb->prepare( $sql, $fields_type ) );

    return $fields_ids;
}

/**
 * Check is User Online.
 */
function yz_is_user_online( $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get User Last Activity.
    $last_user_activity = bp_get_user_last_activity( $user_id );

    // Check if the last activity is exist.
    if ( ! empty( $last_user_activity ) ) {

        // Calculate some times.
        $current_time  = bp_core_current_time( true, 'timestamp' );
        $last_activity = strtotime( $last_user_activity );
        $still_online  = strtotime( '+5 minutes', $last_activity );

        // Has the user been active recently ?
        if ( $current_time <= $still_online ) {
            return true;
        }

    }

    return false;

}

/**
 * Get User Online Icon.
 */
function yz_add_user_online_status_icon( $username = null, $user_id = null ) {

    // Get User status visibility.
    $status_visibility = yz_options( 'yz_header_enable_user_status' );

    if ( 'off' == $status_visibility ) {
        return $username;
    }

    if ( yz_is_user_online( $user_id ) ) {
        $username .= "<span class='yz-user-status yz-user-online'>" . __( 'online', 'youzer' ) . "</span>";
    } else {
        $username .= "<span class='yz-user-status yz-user-offline'>" . __( 'offline', 'youzer' ) . "</span>";
    }

    return $username;

}

add_filter( 'yz_user_profile_username', 'yz_add_user_online_status_icon', 999 );