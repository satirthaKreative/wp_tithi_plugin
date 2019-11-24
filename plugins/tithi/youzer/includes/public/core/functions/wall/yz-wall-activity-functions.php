<?php

/**
 * # Get Acitivity Class.
 */
function yz_get_activity_page_class() {

    // New Array
    $activity_class = array();

    // Get Profile Width Type
    $activity_class[] = 'yz-horizontal-layout yz-global-wall';

    // Get Tabs List Icons Style
    $activity_class[] = yz_options( 'yz_tabs_list_icons_style' );

    // Get Profile Scheme
    $activity_class[] = yz_options( 'yz_profile_scheme' );

    // Get Page Buttons Style
    $activity_class[] = 'yz-page-btns-border-' . yz_options( 'yz_buttons_border_style' );

    return yz_generate_class( $activity_class );
}

/**
 * Activity Wall Posts Per page
 */
function yz_activity_wall_posts_per_page( $query ) {
	
	// if its not the activity directory exit.
	if ( ! bp_is_activity_directory() ) {
		return $query;
	}

	// Get Posts Per Page Number.
	$posts_per_page = yz_options( 'yz_activity_wall_posts_per_page' );
	
	if ( ! empty( $query ) ) {
        $query .= '&';
    }

	// Query String.
	$query .= 'per_page=' . $posts_per_page;

	return $query;
}

add_filter( 'bp_legacy_theme_ajax_querystring', 'yz_activity_wall_posts_per_page' );

/**
 * Activity - Default Filter
 */
function yz_activity_default_filter( $retval ) { 
    
    // Youzer Filter Option.
    $use_youzer_filter = yz_options( 'yz_enable_youzer_activity_filter' );

    if ( 'off' == $use_youzer_filter ) {
        return $retval;
    }

    if ( ! isset( $retval['type'] ) ) {
        $retval['action'] = yz_wall_show_everything_filter();    
    }
    
    return $retval;    
}

add_filter( 'bp_after_has_activities_parse_args', 'yz_activity_default_filter' );

/**
 * Add profile activity page filter bar.
 */
function yz_profile_activity_tab_filter_bar() {

    if ( ! bp_is_user_activity() ) {
        return;
    }
    
    if ( 'on' == yz_options( 'yz_enable_wall_filter_bar' ) ) :

?>

<div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Member secondary navigation', 'youzer' ); ?>" role="navigation">
    <ul>

        <?php bp_get_options_nav(); ?>

        <li id="activity-filter-select" class="last">
            <label for="activity-filter-by"><?php _e( 'Show:', 'youzer' ); ?></label>
            <select id="activity-filter-by">
                <option value="<?php echo yz_wall_show_everything_filter(); ?>"><?php _e( '&mdash; Everything &mdash;', 'youzer' ); ?></option>

                <?php bp_activity_show_filters(); ?>

                <?php

                /**
                 * Fires inside the select input for member activity filter options.
                 *
                 * @since 1.2.0
                 */
                do_action( 'bp_member_activity_filter_options' ); ?>

            </select>
        </li>
    </ul>
</div><!-- .item-list-tabs -->

<?php endif;

}

add_action( 'yz_profile_main_content', 'yz_profile_activity_tab_filter_bar' );