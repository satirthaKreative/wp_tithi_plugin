<?php

/**
 * Get Groups Directory Class
 */
function yz_groups_directory_class() {

    // New Array
    $directory_class = array();

    // Add Directory Class
    $directory_class[] = 'yz-directory';
    
    // Add Page Class
    $directory_class[] = 'yz-page yz-groups-directory-page';

    // Add Scheme Class
    $directory_class[] = yz_options( 'yz_profile_scheme' );

    // Add Lists Icons Styles Class
    $directory_class[] = yz_options( 'yz_tabs_list_icons_style' );

    return yz_generate_class( $directory_class );
}

/**
 * Get Groups Directory Group Cover.
 */
function yz_groups_directory_group_cover( $group_id ) { 

    if ( 'off' == yz_options( 'yz_enable_gd_cards_cover' ) ) {
        return false;
    }

    global $Youzer;

    // Get User Cover.
    $user_cover = $Youzer->group->cover( 'style', $group_id );       

    ?>

    <div class="yz-group-cover" <?php echo $user_cover; ?>></div>

    <?php

}

/**
 * Groups Directory - Edit Groups Class.
 */
function yz_edit_group_directory_class( $classes ) {

    if ( 'on' == yz_options( 'yz_enable_gd_cards_cover' ) ) {
        $classes[] = 'yz-show-cover';
    }

    return $classes;
}

add_filter( 'bp_get_group_class', 'yz_edit_group_directory_class' );

/**
 * Groups Directory - Get Member Data Statitics.
 */
function yz_get_group_statistics_data( $group_id ) {

    if ( 'off' == yz_options( 'yz_enable_gd_groups_statistics' ) ) {
        return false;
    }

    global $Youzer;

    // Get Data
    $members_nbr = bp_get_group_member_count();
    $posts_nbr = yz_get_group_total_posts_count( $group_id );

    ?>

    <div class="yzg-user-statistics">

        <?php if ( 'on' == yz_options( 'yz_enable_gd_group_posts_statistics' ) ) : ?>
        <div class="yz-data-item yz-data-posts" data-yztooltip="<?php echo sprintf( _n( '%s post', '%s posts', $posts_nbr, 'youzer' ), $posts_nbr ); ?>">
            <span class="dashicons dashicons-edit"></span>
        </div>
        <?php endif; ?>

        <?php if ( 'on' == yz_options( 'yz_enable_gd_group_activity_statistics' ) ) : ?>
        <div class="yz-data-item yz-data-activity" data-yztooltip="<?php printf( __( 'active %s', 'youzer' ), bp_get_group_last_active() ); ?>">
            <span class="dashicons dashicons-clock"></span>
        </div>
        <?php endif; ?>

        <?php if ( 'on' == yz_options( 'yz_enable_gd_group_members_statistics' ) ) : ?>
        <div class="yz-data-item yz-data-members" data-yztooltip="<?php echo $members_nbr; ?>">
            <span class="dashicons dashicons-groups"></span>
        </div>
        <?php endif; ?>


    </div>

    <?php
}

/**
 * Groups Directory - Get Group Buttons.
 */
function yz_get_gd_manage_group_buttons() {

    if ( ! is_user_logged_in() || ! bp_is_groups_directory() ) { 
        return false;
    }

    // Check if Current User is admin.
    if ( false == groups_is_user_admin( get_current_user_id(), bp_get_group_id() ) ) {
        return false;
    }

    ?>
    
    <a href="<?php echo bp_get_group_admin_permalink(); ?>" class="yz-manage-group"><i class="fas fa-cogs"></i><?php _e( 'manage group', 'youzer' ); ?></a>

    <?php

}

add_action( 'bp_directory_groups_actions', 'yz_get_gd_manage_group_buttons', 999 );

/**
 * Groups Directory - Max Groups Number per Page.
 */
function yz_groups_directory_groups_per_page( $loop ) {
    if ( bp_is_groups_directory() ) {
        $groups_per_page = yz_options( 'yz_gd_groups_per_page' );
        $loop['per_page'] = $groups_per_page;
    }
    return $loop;
}

add_filter( 'bp_after_has_groups_parse_args', 'yz_groups_directory_groups_per_page' );

/**
 * Groups Directory - Cards Class.
 */
function yz_groups_list_class() {

    // Init Array().
    $classes = array();

    // Main Class.
    $classes[] = 'item-list';

    if ( ! bp_is_groups_directory() ) {
        return yz_generate_class( $classes );
    }

    // Get Avatar Border Visibility.
    $enable_avatar_border = yz_options( 'yz_enable_gd_cards_avatar_border' );

    if ( 'on' == $enable_avatar_border) {

        // Show Avatar Border.
        $classes[] = 'yz-card-show-avatar-border';

    }

    // Get Cards Avatar Style.
    $avatar_border_style = yz_options( 'yz_gd_cards_avatar_border_style' );

    // Add Avatar Border Style.
    $classes[] = 'yz-card-avatar-border-' . $avatar_border_style;

    // Get Buttons Layout.
    $buttons_layout = yz_options( 'yz_gd_cards_buttons_layout' );
    
    // Add Buttons Layout.    
    $classes[] = 'yz-card-action-buttons-' . $buttons_layout;
    
    // Get Buttons Border Style.
    $buttons_border_style = yz_options( 'yz_gd_cards_buttons_border_style' );

    // Add Buttons Border Style.    
    $classes[] = 'yz-card-action-buttons-border-' . $buttons_border_style;

    return yz_generate_class( $classes );

}