<?php

/**
 * Profile Wall Posts Per page
 */
function yz_profile_wall_posts_per_page( $query ) {
	
	if ( ! bp_is_user_activity() ) {
		return $query;
	}

	// Get Posts Per Page Number.
	$posts_per_page = yz_options( 'yz_profile_wall_posts_per_page' );
	
	if ( ! empty( $query ) ) {
        $query .= '&';
    }

	// Query String.
	$query .= 'per_page=' . $posts_per_page;

	return $query;
}

add_filter( 'bp_legacy_theme_ajax_querystring', 'yz_profile_wall_posts_per_page' );