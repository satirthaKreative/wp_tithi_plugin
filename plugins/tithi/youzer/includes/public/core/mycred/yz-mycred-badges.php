<?php

/**
 * Get User Badges
 */
function yz_mycred_get_user_badges( $user_id = null, $max_badges = 6, $more_type = 'box', $width = MYCRED_BADGE_WIDTH, $height = MYCRED_BADGE_HEIGHT ) {

	// Get User ID.
	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	// Get Ballance 
	$user_badges = mycred_get_users_badges( $user_id );
	
	// Get Badges total
	$badges_nbr = count( $user_badges );
	
	?>
	

		<?php if ( ! empty( $user_badges ) ) : ?>
		
		<div class="yz-user-badges">

			<?php

		    // Limit Bqdges Number
		    $user_badges = array_slice( $user_badges, 0, $max_badges, true );

			foreach ( $user_badges as $badge_id => $level ) {

				$badge = mycred_get_badge( $badge_id, $level );

				if ( $badge === false ) continue;

				$badge->image_width  = $width;
				$badge->image_height = $height;

				if ( $badge->level_image !== false ){
					echo '<div class="yz-badge-item" data-yztooltip="'. $badge->title .'">';
					echo apply_filters( 'mycred_the_badge', $badge->get_image( $level ), $badge_id, $badge, $user_id );
					echo '</div>';
				}

			}

		?>

    	<?php if ( 'box' == $more_type ) { yz_mycred_get_badges_more_button( $user_id, $badges_nbr, $max_badges, $more_type ); } ?>

		</div>
    
        <?php endif; ?>


    <?php if ( 'text' == $more_type ) { yz_mycred_get_badges_more_button( $user_id, $badges_nbr, $max_badges, $more_type ); } ?>

	<?php
}

/**
 * Get Badges Widget More Button.
 */
function yz_mycred_get_badges_more_button( $user_id = null, $badges_nbr = null, $max_badges = null, $more_type = 'box' ) {
    
    if ( $badges_nbr > $max_badges ) :

    	$more_nbr = $badges_nbr - $max_badges;
    	$more_title = ( 'text' == $more_type ) ? sprintf( __( 'Show All Badges ( %s )', 'youzer' ), $badges_nbr ) : '+' . $more_nbr; ?>
        <div class="yz-badge-item yz-more-items yz-user-badges-more-<?php echo $more_type ?>" <?php if ( 'box' == $more_type ) echo 'data-yztooltip="' . __( 'Show All Badges', 'youzer' )  . '"'; ?>>
            <a href="<?php echo bp_core_get_user_domain( $user_id ) . yz_mycred_badges_slug();?>"><?php echo $more_title; ?></a>
        </div>
    <?php endif;

}

/**
 * Get Profile Badges Widget.
 */
function yz_mycred_profile_badges_widget_content() {
	
	// Get User id.
	$user_id = bp_displayed_user_id();

	// Get Bages max number.
	$max_badges = yz_options( 'yz_wg_max_user_badges_items' );

	// Get Badges
	yz_mycred_get_user_badges( $user_id, $max_badges, 'text' );

}

add_action( 'yz_user_badges_widget_content', 'yz_mycred_profile_badges_widget_content' );

/**
 * Get Mycred Badges slug
 */
function yz_mycred_badges_slug() {
	return apply_filters( 'yz_mycred_badges_slug', 'badges' );
}


/**
 * Add Badges Tab
 */
function yz_add_mycred_badges_tab() {

	global $bp;

	// Add Badges Tab.
    bp_core_new_nav_item(
        array( 
            'position' => 100,
            'slug' => yz_mycred_badges_slug(), 
            'parent_slug' => $bp->profile->slug,
            'default_subnav_slug' => yz_mycred_badges_slug(),
            'name' => yz_options( 'yz_mycred_badges_tab_title' ), 
            'screen_function' => 'yz_profile_mycred_badges_tab_screen', 
            'parent_url' => bp_loggedin_user_domain() . yz_mycred_badges_slug()
        )
    );

}

add_action( 'bp_setup_nav', 'yz_add_mycred_badges_tab', 1 );

/**
 * Get Badges Tab Template
 */
function yz_profile_mycred_badges_tab_screen() {

    // Call Posts Tab Content.
    add_action( 'bp_template_content', 'yz_get_mycred_badges_page_content' );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Badges Tab Content.
 */
function yz_get_mycred_badges_page_content() {

	// Get User ID.
	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	// Get Ballance 
	$user_badges = mycred_get_users_badges( $user_id );

	// Get Badges Total 
	$badges_total = isset( $user_badges ) ? count( $user_badges ) : 0;
	
	$full_name = bp_get_displayed_user_fullname();

	$username = ! empty( bp_get_user_firstname( $full_name ) ) ? bp_get_user_firstname( $full_name ) : bp_core_get_username( $user_id );

	$page_title = bp_is_my_profile() ? __( 'My Badges', 'youzer' ) : sprintf( __( "%1s's Badges", "youzer" ), $username );
	
	?>
	

	<div class="yz-tab-title-box">
		<div class="yz-tab-title-icon"><i class="fas fa-trophy"></i></div>
		<div class="yz-tab-title-content">
			<h2><?php echo $page_title; ?></h2>
			<span><?php echo sprintf( _n( '%s Badge', '%s Badges', $badges_total, 'youzer' ), $badges_total ); ?></span>
		</div>
	</div>
	
	<div class="yz-user-badges-tab">

		<?php 

		if ( ! empty( $user_badges ) ) {

			foreach ( $user_badges as $badge_id => $level ) {

				$badge = mycred_get_badge( $badge_id, $level );
				// print_r($level);

				if ( $badge === false ) continue;

				$badge->image_width  = 100;
				$badge->image_height = 100;

				if ( $badge->level_image !== false ){
					echo '<div class="yz-user-badge-item">';
					echo apply_filters( 'mycred_the_badge', $badge->get_image( $level ), $badge_id, $badge, $user_id );
					$badge_title = '<div class="yz-user-badge-title">' . $badge->title . '</div>';
					echo apply_filters( 'yz_mycred_the_badge_title', $badge_title, $badge, $level );

					echo '</div>';
				}

			}

		}

		?>

		<?php do_action( 'yz_after_user_badges_tab' ); ?>

	</div>

	<?php
}


/**
 * Members Directory - Display Badges 
 */
function yz_md_display_user_badges() {

	if ( ! bp_is_members_directory() ) {
		return false;
	}
	
    // Get badges visibility
    $show_badges = yz_options( 'yz_enable_cards_mycred_badges' );

    if ( 'off' == $show_badges ) {
        return;
    }

    // Get User id.
    $user_id = bp_get_member_user_id();

    // Get Bages max number.
    $max_badges = yz_options( 'yz_wg_max_card_user_badges_items' );

    ?>

    <div class="yz-md-user-badges">
        <?php yz_mycred_get_user_badges( $user_id, $max_badges, 'box' ); ?>
    </div>

    <?php
}

add_action( 'bp_directory_members_item', 'yz_md_display_user_badges');

/**
 * Author Box - Display Badges
 */
function yz_mycred_author_box_badges( $args = null ) {

    // Get badges visibility
    $show_badges = yz_options( 'yz_enable_author_box_mycred_badges' );

    if ( 'off' == $show_badges ) {
        return;
    }

    // Get Bages max number.
    $max_badges = yz_options( 'yz_author_box_max_user_badges_items' );

    ?>

    <div class="yzb-user-badges">
        <?php yz_mycred_get_user_badges( $args['user_id'], $max_badges, 'box' ); ?>
    </div>

    <?php
}

add_action( 'yz_author_box_badges_content', 'yz_mycred_author_box_badges' );

/**
 * Check User Badges Widget Visibility.
 */
function yz_mycred_is_user_have_widgets( $widget_visibility, $widget_name ) {

    if ( 'user_badges' != $widget_name ) {
        return $widget_visibility;
    }

    // Get User Badges. 
    $user_badges = mycred_get_users_badges( bp_displayed_user_id() );
    
    if ( empty( $user_badges ) ) {
        return false;
    }

    return true;
}

add_filter( 'yz_profile_widget_visibility', 'yz_mycred_is_user_have_widgets', 10, 2 ); 