<?php

/**
 * Get Members Directory Class
 */
function yz_members_directory_class() {

    // New Array
    $directory_class = array();

    // Add Directory Class
    $directory_class[] = 'yz-directory';
    
    // Add Page Class
    $directory_class[] = 'yz-page yz-members-directory-page';

    // Add Scheme Class
    $directory_class[] = yz_options( 'yz_profile_scheme' );

    // Add Lists Icons Styles Class
    $directory_class[] = yz_options( 'yz_tabs_list_icons_style' );

    return yz_generate_class( $directory_class );
}

/**
 * Get Members Directory User Cover.
 */
function yz_members_directory_user_cover( $user_id ) { 

    if ( 'off' == yz_options( 'yz_enable_md_cards_cover' ) ) {
        return false;
    }

    global $Youzer;

    // Get User Cover.
    $user_cover = $Youzer->user->cover( 'style', $user_id );       

    ?>

    <div class="yzm-user-cover" <?php echo $user_cover; ?>>
        <?php do_action( 'yz_members_directory_cover_content' ); ?>
    </div>

    <?php

}

/**
 * Filters Members Directory Classes.
 */
function yz_edit_members_directory_class( $classes ) {

    // Add OffLine Class.
    if ( 'off' == yz_options( 'yz_show_md_cards_online_only' ) && ! in_array( 'is-online', $classes ) ) {
        $classes[] = 'is-offline';
    }

    // Remove User Status Class
    if ( 'off' == yz_options( 'yz_enable_md_cards_status' ) ) {

        // Get Values Keys.
        $is_online = array_search( 'is-online', $classes );
        $is_offline = array_search( 'is-offline', $classes );
        
        // Remove OnLine Class.
        if ( $is_online !== false ) {
            unset( $classes[ $is_online ] );
        }
        
        // Remove OffLine Class.
        if ( $is_offline !== false ) {
            unset( $classes[ $is_offline ] );
        }
    
    }

    if ( 'on' == yz_options( 'yz_enable_md_cards_cover' ) ) {
        $classes[] = 'yz-show-cover';
    }

    return $classes;
}

add_filter( 'bp_get_member_class', 'yz_edit_members_directory_class' );

/**
 * Members Directory - Max Members Per Page.
 */
function yz_members_directory_members_per_page( $loop ) {
    if ( bp_is_members_directory() ) {
        $users_per_page = yz_options( 'yz_md_users_per_page' );
        $loop['per_page'] = $users_per_page;
    }
    return $loop;
}

add_filter( 'bp_after_has_members_parse_args', 'yz_members_directory_members_per_page' );

/**
 * Members Directory - Cards Class.
 */
function yz_members_list_class() {

    // Init Array().
    $classes = array();

    // Main Class.
    $classes[] = 'item-list';

    if ( ! bp_is_members_directory() ) {
        return yz_generate_class( $classes );
    }

    // Get Avatar Border Visibility.
    $enable_avatar_border = yz_options( 'yz_enable_md_cards_avatar_border' );

    if ( 'on' == $enable_avatar_border) {

        // Show Avatar Border.
        $classes[] = 'yz-card-show-avatar-border';

    }

    // Get Cards Avatar Style.
    $avatar_border_style = yz_options( 'yz_md_cards_avatar_border_style' );

    // Add Avatar Border Style.
    $classes[] = 'yz-card-avatar-border-' . $avatar_border_style;

    // Get Buttons Layout.
    $buttons_layout = yz_options( 'yz_md_cards_buttons_layout' );
    
    // Add Buttons Layout.    
    $classes[] = 'yz-card-action-buttons-' . $buttons_layout;
    
    // Get Buttons Border Style.
    $buttons_border_style = yz_options( 'yz_md_cards_buttons_border_style' );

    // Add Buttons Border Style.    
    $classes[] = 'yz-card-action-buttons-border-' . $buttons_border_style;

    return yz_generate_class( $classes );

}

/**
 * Get Members Directory User settings Button
 */
function yz_get_md_current_user_settings( $user_id = false ) {

    if ( ! is_user_logged_in() || ! bp_is_members_directory() ) { 
        return false;
    }
    
    // Get User Id.
    $user_id = $user_id ? $user_id : yz_get_context_user_id();

    if ( $user_id != bp_loggedin_user_id() ) {
        return false;
    }

    // Get Buttons Layout
    $buttons_layout = yz_options( 'yz_md_cards_buttons_layout' );

    ?>
    
    <a href="<?php echo yz_get_profile_settings_url( false, $user_id ); ?>" class="yz-profile-settings"><i class="fas fa-user-circle"></i><?php _e( 'profile settings', 'youzer' ); ?></a>

    <?php if ( bp_is_active( 'friends' ) && bp_is_active( 'messages' ) && 'block' == $buttons_layout ) : ?>

        <?php if ( bp_is_active( 'settings' ) ) : ?>
            <a href="<?php echo yz_get_settings_url( false, $user_id ); ?>" class="yzmd-second-btn"><i class="fas fa-cogs"></i><?php _e( 'account settings', 'youzer' ); ?></a>
        <?php else : ?>
            <a href="<?php echo yz_get_widgets_settings_url( false, $user_id ); ?>" class="yzmd-second-btn"><i class="fas fa-sliders-h"></i><?php _e( 'widgets settings', 'youzer' ); ?></a>
        <?php endif; ?>

    <?php endif; ?>

    <?php
}

add_action( 'bp_directory_members_actions', 'yz_get_md_current_user_settings' );

/**
 * Members Directory - Get Member Data Statitics.
 */
function yz_get_member_statistics_data( $user_id ) {

	if ( 'off' == yz_options( 'yz_enable_md_users_statistics' ) ) {
		return false;
	}

	global $Youzer;

	// Get Comments Number
	$posts_nbr = yz_get_user_posts_nbr( $user_id );
	$views_nbr = $Youzer->user->views( $user_id );
	$comments_nbr = yz_get_comments_number( $user_id );

    ?>

    <div class="yzm-user-statistics">

        <?php do_action( 'yz_before_members_directory_card_statistics', $user_id  ); ?>

        <?php if ( 'on' == yz_options( 'yz_enable_md_user_posts_statistics' ) ) : ?>
        <a <?php if (  $posts_nbr > 0 ) { ?> href="<?php echo yz_get_user_profile_page( 'posts', $user_id ); ?>" <?php } ?> class="yz-data-item yz-data-posts" data-yztooltip="<?php echo sprintf( _n( '%s post', '%s posts', $posts_nbr, 'youzer' ), $posts_nbr ); ?>">
            <span class="dashicons dashicons-edit"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == yz_options( 'yz_enable_md_user_comments_statistics' ) ) : ?>
        <a <?php if (  $comments_nbr > 0 ) { ?>  href="<?php echo yz_get_user_profile_page( 'comments', $user_id ); ?>" <?php } ?> class="yz-data-item yz-data-comments" data-yztooltip="<?php echo sprintf( _n( '%s comment', '%s comments', $comments_nbr, 'youzer' ), $comments_nbr ); ?>">
            <span class="dashicons dashicons-format-status"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == yz_options( 'yz_enable_md_user_views_statistics' ) ) : ?>
        <a href="<?php echo bp_member_permalink(); ?>" class="yz-data-item yz-data-vues" data-yztooltip="<?php echo sprintf( _n( '%s view', '%s views', $views_nbr, 'youzer' ), $views_nbr ); ?>">
            <span class="dashicons dashicons-welcome-view-site"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == yz_options( 'yz_enable_md_user_friends_statistics' ) && bp_is_active( 'friends' ) ) :  ?>
	       <?php $friends_nbr = friends_get_total_friend_count( $user_id ); ?>
            <a href="<?php echo yz_get_user_profile_page( 'friends', $user_id ); ?>" class="yz-data-item yz-data-friends" data-yztooltip="<?php echo sprintf( _n( '%s friend', '%s friends', $friends_nbr, 'youzer' ), $friends_nbr ); ?>">
                <span class="dashicons dashicons-groups"></span>
            </a>
        <?php endif; ?>

        <?php do_action( 'yz_after_members_directory_card_statistics', $user_id  ); ?>

    </div>

    <?php
}

/**
 * Get Card Custom Meta.
 */
function yz_get_md_user_meta( $user_id = null ) {

    // Get Custom Card Meta Availability
    $custom_meta = yz_options( 'yz_enable_md_custom_card_meta' );

    if ( 'off' == $custom_meta || ! bp_is_members_directory() ) {

        // Get Default Meta.
        $default_meta = '@' . bp_core_get_username( $user_id );

        return $default_meta;

    }

    // Get Custom Meta Data
    $meta_icon  = yz_options( 'yz_md_card_meta_icon' );
    $field_id   = yz_options( 'yz_md_card_meta_field' );
    $meta_value = yz_get_user_field_data( $field_id, $user_id );

    if ( empty( $meta_value ) ) {
        // Set Default Meta.
        $meta_html = '<i class="fas fa-at"></i>' . bp_core_get_username( $user_id );
    } else {
        // Create Custom Meta HTML Code.
        $meta_html = '<i class="' . $meta_icon .'"></i>' . $meta_value;        
    }

    // Filter
    $meta_html = apply_filters( 'yz_get_md_user_meta', $meta_html, $meta_icon, $field_id, $meta_value );

    return $meta_html;
}   

/**
 * Get Card User Meta Value.
 */
function yz_get_user_field_data( $field_id = null, $user_id = null ) {

    if ( bp_is_active( 'xprofile' ) && is_numeric( $field_id ) ) {
        // Get Field Data.
        $meta_value = xprofile_get_field_data( $field_id, $user_id );
    } elseif ( $field_id == 'full_location' ) {
        global $Youzer;
        $meta_value = $Youzer->user->location( true, $user_id );
    } else {
        // Get Field Data.
        $meta_value = get_the_author_meta( $field_id, $user_id );
    }

    // Filter.
    $meta_value = apply_filters( 'yz_get_user_field_data', $meta_value, $field_id, $user_id );

    // Return Data.
    return $meta_value;
}
