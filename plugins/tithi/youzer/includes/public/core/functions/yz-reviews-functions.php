<?php

/**
 * Check is User Reviewed.
 */
function yz_is_user_already_reviewed( $reviewed, $reviewer = null ) {
	
	// Init Var.
	$is_reviewed = false;

	// Get Reviewer.
	$reviewer = ! empty( $reviewer ) ? $reviewer : bp_loggedin_user_id();

 	// Check if user Already Reviewed.
	$review_id = youzer()->reviews->query->get_review_id( $reviewed, $reviewer );

	if ( $review_id ) {
		$is_reviewed = $review_id;
	}

	return apply_filters( 'yz_is_user_already_reviewed', $is_reviewed );

}

/**
 * Reviews Slug.
 */
function yz_reviews_tab_slug() {
	return apply_filters( 'yz_reviews_tab_slug', 'reviews' );
}

/**
 * Get Reviews Tab Screen Function.
 */
function yz_reviews_screen() {
	
	do_action( 'yz_reviews_screen' );

    add_action( 'bp_template_content', 'yz_get_user_reviews_template' );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Reviews Tab Content.
 */
function yz_get_user_reviews_template() {
	bp_get_template_part( 'members/single/reviews' );
}

/**
 * Check is User Can see Reviews.
 */
function yz_is_user_can_see_reviews() {
	
	// Init var.
	$visibility = false;

	// Get Who can see reviews.
	$privacy = yz_options( 'yz_user_reviews_privacy' );

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

	return apply_filters( 'yz_is_user_can_see_reviews', $visibility );

}

/**
 * Get Reviews Rating System
 */
function yz_get_ratings_stars_data() {

	$args = array(
		array( 'number' => 5, 'description' => __( 'Excellent', 'youzer' ) ),
		array( 'number' => 4, 'description' => __( 'Good', 'youzer' ) ),
		array( 'number' => 3, 'description' => __( 'Average', 'youzer' ) ),
		array( 'number' => 2, 'description' => __( 'Below Average', 'youzer' ) ),
		array( 'number' => 1, 'description' => __( 'Poor', 'youzer' ) )
	);

	$args = apply_filters( 'yz_ratings_stars_data', $args );

 	return $args;

}

/**
 * Get Review Stars
 */
function yz_get_review_stars_form( $args = null ) {

	$args = wp_parse_args( $args , array(
		'fractional' => false,
		'rating' => 0,
	) );

	$html = '<div class="yz-rate-user">';
	$html .= '<span class="yz-rate-user-desc">' . __( 'what is your rating ?', 'youzer' ) . '</span>';

	// Get
	$ratings = yz_get_ratings_stars_data();

 	foreach ( $ratings as $rating ) {
 		
 		$rating = $rating['number'];
		$class = is_integer( $rating ) ? 'full' : 'half';
		$checked = $rating == $args['rating'] ? 'checked' : '';
		$id = is_integer( $rating ) ? $rating : ( $rating - 0.5 ) . 'half' ;

		// Get Star HTML.
		$star_html = "<input type='radio' id='star$id' name='rating' value='$rating' $checked /><label class='$class' for='star$id' ></label>";

		// Filter
		$html .= apply_filters( 'yz_review_star_html', $star_html, $rating );

	}
	
	$html .= '</div>';

	return apply_filters( 'yz_review_stars_form', $html );

}

function yz_is_user_review_description_required() {
	return apply_filters( 'yz_is_user_review_description_required', 'true' );
}

/**
 * Get Profiles/Groups Share Buttons
 */
function yz_get_user_review_form() {

    // Get Data.
    $options = array();
    $reviewer = bp_loggedin_user_id();
    $operation = isset( $_POST['operation'] ) ? $_POST['operation'] : null; 
    $user_id = isset( $_POST['user_id'] ) ? $_POST['user_id'] : null; 
    $review_id = isset( $_POST['review_id'] ) ? $_POST['review_id'] : null; 

    if ( empty( $operation ) || empty( $user_id ) ) {
        $response['error'] = __( "Sorry we didn't received enough data to process this action.", 'youzer' );
		die( json_encode( $response ) );
    }

    // Args
    $modal_args = array(
        'show_close' => false,
        'id'        => 'yz-review-form',
        'button_id' => 'yz-add-review',
        'operation' => $operation
    );

    if ( $operation == 'add' ) {

		// Check if user Already Reviewed Post.
		$review_id = youzer()->reviews->query->get_review_id( $user_id, $reviewer );

		if ( $review_id ) {
			$response['error'] = __( 'you already reviewed this user.', 'youzer' );
			die( json_encode( $response ) );
		}

    	$options['reviewed'] = $user_id;
	    $modal_args['title'] = __( 'add review', 'youzer' );
	    $modal_args['button_title'] = __( 'submit review', 'youzer' );

    } elseif ( $operation == 'edit' ) {
    	
		// Get Review Data.
		$options = youzer()->reviews->query->get_review_data( $review_id );

    	if ( ! yz_is_user_can_edit_reviews( $options ) ) {
			$response['error'] = __( 'You are not allowed to edit reviews.', 'youzer' );
			die( json_encode( $response ) );
    	}

	    $modal_args['title'] = __( 'edit review', 'youzer' );
	    $modal_args['button_title'] = __( 'update review', 'youzer' );
	    $modal_args['delete_button_title'] = __( 'delete review', 'youzer' );
	    $modal_args['delete_button_id'] = 'yz-delete-review';
	    $modal_args['show_delete_button'] = true;


    }

    // Add Data. 
    $options['action'] = $operation;

    // Get User Review Form.
    yz_modal( $modal_args, 'yz_user_review_form', $options );

}

add_action( 'wp_ajax_yz_get_user_review_form', 'yz_get_user_review_form' );

/**
 * User Review Form
 */
function yz_user_review_form( $args = null ) {

	// Get Data
	$review_id = isset( $args['id'] ) ? $args['id'] : null;
	$action = isset( $args['action'] ) ? $args['action'] : null;
	$review = isset( $args['review'] ) ? $args['review'] : null;
	$reviewed = isset( $args['reviewed'] ) ? $args['reviewed'] : null;
	
	// Get Ratins
	$rating_args['rating'] = isset( $args['rating'] ) ? $args['rating'] : null;

	echo yz_get_review_stars_form( $rating_args );

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-networks-form'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'        => __( 'review', 'youzer' ),
            'desc'         => __( 'type the review description', 'youzer' ),
            'id'           => 'review',
            'type'         => 'textarea',
            'std' 		   => $review,
            'no_options'   => true,
        )
    );

    ?>

	<input type="hidden" name="reviewed" value="<?php echo $reviewed; ?>">
	<input type="hidden" name="operation" value="<?php echo $action; ?>">
	
	<?php if ( ! empty( $review_id ) ) : ?>
		<input type="hidden" name="review_id" value="<?php echo $review_id; ?>">
	<?php endif; ?>
    
    <?php
    
    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Check is User Can Review Users.
 */
function yz_allow_anonymous_reviews() {
	return apply_filters( 'yz_allow_anonymous_reviews', false );
}

/**
 * Check is User Can Add Reviews .
 */
function yz_is_user_can_add_reviews( $user_id = null ) {

	// Init Vars
	$can = false;

	if ( ! yz_allow_anonymous_reviews() && ! is_user_logged_in()  ) {
		$can = false;
	} else {
		$can = true;
	}

	if ( bp_loggedin_user_id() == $user_id ) {
		$can = false;
	}

	return apply_filters( 'yz_is_user_can_add_reviews', $can, $user_id );

}

/**
 * Check is User Can Edit Reviews .
 */
function yz_is_user_can_edit_reviews( $review = null ) {

	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Init Vars
	$can = false;

	if ( 'on' == yz_options( 'yz_allow_users_reviews_edition' ) && isset( $review['reviewer'] ) && $review['reviewer'] == bp_loggedin_user_id() ) {
		$can = true;
	}

	// Get Current User Data.
	$user = wp_get_current_user();
	
	// Filter Allowed Roles.
	$allowed_roles = apply_filters( 'yz_allowed_roles_to_edit_reviews', array( 'administrator' ) );

	foreach ( $allowed_roles as $role ) {
		if ( in_array( $role, (array) $user->roles ) ) {
			$can = true;
		}
	}

	return apply_filters( 'yz_is_user_can_edit_reviews', $can, $user );

}

/**
 * Check is User Can Delete Reviews .
 */
function yz_is_user_can_delete_reviews() {

	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Init Vars
	$can = false;

	// Get Current User Data.
	$user = wp_get_current_user();
	
	// Filter Allowed Roles.
	$allowed_roles = apply_filters( 'yz_allowed_roles_to_delete_reviews', array( 'administrator' ) );

	foreach ( $allowed_roles as $role ) {
		if ( in_array( $role, (array) $user->roles ) ) {
			$can = true;
		}
	}

	return apply_filters( 'yz_is_user_can_delete_reviews', $can );

}
function yz_star_rating( $args = array() ) {
    $defaults = array(
        'rating' => 0,
        'type'   => 'rating',
        'number' => 0,
        'echo'   => true,
    );
    $r = wp_parse_args( $args, $defaults );
 
    // Non-English decimal places when the $rating is coming from a string
    $rating = (float) str_replace( ',', '.', $r['rating'] );
 
    // Convert Percentage to star rating, 0..5 in .5 increments
    if ( 'percent' === $r['type'] ) {
        $rating = round( $rating / 10, 0 ) / 2;
    }
 
    // Calculate the number of each type of star needed
    $full_stars = floor( $rating );
    $half_stars = ceil( $rating - $full_stars );
    $empty_stars = 5 - $full_stars - $half_stars;
 
    if ( $r['number'] ) {
        /* translators: 1: The rating, 2: The number of ratings */
        $format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
        $title = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
    } else {
        /* translators: 1: The rating */
        $title = sprintf( __( '%s rating' ), number_format_i18n( $rating, 1 ) );
    }
 
    $output = '<div class="yz-star-rating">';
    $output .= '<span class="screen-reader-text">' . $title . '</span>';
    $output .= str_repeat( '<i class="fas fa-star star-full"></i>', $full_stars );
    $output .= str_repeat( '<i class="fas fa-star star-half"></i>', $half_stars );
    $output .= str_repeat( '<i class="fas fa-star star-empty"></i>', $empty_stars );
    $output .= '</div>';
 
    if ( $r['echo'] ) {
        echo $output;
    }
 
    return $output;
}

/**
 * Get User Reviews.
 */
function yz_get_user_reviews( $args = null ) {

	$args = wp_parse_args( $args, array(
		'show_review' => true,
		'pagination' => false,
		'show_more' => false,
		'order_by' => 'desc',
		'per_page' => yz_options( 'yz_profile_reviews_per_page' ),
		)
	);

	// Get User ID.
	$user_id = isset( $args['user_id'] ) ? $args['user_id'] : null;
	
	// Filter.
	$args = apply_filters( 'yz_get_user_reviews_args', $args );

	// Get Reviews
	$reviews = youzer()->reviews->query->get_user_reviews( $args );

	// Get Reviews Count
	$reviews_count = youzer()->reviews->query->get_user_reviews_count( $user_id );

	?>

	<div class="yz-user-reviews">
		
		
		<?php do_action( 'yz_before_reviews' ); ?>

		<?php foreach ( $reviews as $review ) : ?>

		<div class="yz-item yz-review-item">
			
			<?php do_action( 'yz_before_review_head', $review ); ?>
			
			<div class="yz-item-head">
				<div class="yz-item-img" style="background-image: url(<?php echo bp_core_fetch_avatar( 
						array(
							'item_id' => $review['reviewer'],
							'type'	  => 'thumbnail',
							'html' 	  => false,
						)
					); ?>)"></div>
				<div class="yz-head-meta">
					
					<div class="yz-item-name"><?php echo bp_core_get_userlink( $review['reviewer'] ); ?></div>
					<div class="yz-item-date"><?php echo date( 'F j, Y', strtotime( $review['time'] ) ); ?></div>
				</div>

				<div class="yz-item-rating"><?php yz_star_rating( array( 'rating' => $review['rating'] ) ); ?></div>
			</div>

			<?php do_action( 'yz_after_review_head', $review ); ?>

			<div class="yz-item-content">
				<?php do_action( 'yz_before_review_content' ); ?>
				<?php if ( $args['show_review'] == true ) : ?>
					<div class="yz-item-desc"><?php echo esc_html( $review['review'] ); ?></div>
				</div>
				<?php do_action( 'yz_after_review_content' ); ?>
			<?php endif; ?>
			
		</div>

		<?php endforeach; ?>

		<?php do_action( 'yz_after_reviews' ); ?>
		
		<?php
			if ( $args['pagination'] == true ) {
				yz_reviews_pagination( $reviews_count, $args['per_page'] );
			}
		?>

		<?php if ( empty( $reviews ) ) : ?>
			<div id="message" class="info">
				<p><?php _e( 'Sorry, there is no reviews.', 'youzer' ); ?></p>
			</div>
		<?php endif; ?>

		
		<?php if ( $args['show_more'] == true && $reviews_count > 0 && $args['per_page'] <= $reviews_count ) : ?>
			<?php $reviews_slug = yz_reviews_tab_slug();  ?>
			<a href="<?php echo yz_get_user_profile_page( $reviews_slug ); ?>" class="yz-rating-show-more"><?php echo sprintf( __( 'Show all ratings ( %s )', 'youzer' ), $reviews_count ); ?></a>
		<?php endif; ?>

	</div>

	<?php
}

add_shortcode( 'youzer_reviews', 'yz_get_user_reviews' );

/**
 * # Pagination.
 */
function yz_reviews_pagination( $total_items, $per_page = null ) {

	// Get Base 
	$base = isset( $_POST['base'] ) ? $_POST['base'] : get_pagenum_link( 1 );

	// Get items Per Page Number
	$per_page = ! empty( $per_page ) ? $per_page : 1;

	// Get total Pages Number
	$max_page = ceil( $total_items / $per_page );

	// Get Current Page Number
	$cpage = ! empty( $_POST['page'] ) ?  $_POST['page'] : 1 ;

	// Get Next and Previous Pages Number
	if ( ! empty( $cpage ) ) {
		$next_page = $cpage + 1;
		$prev_page = $cpage - 1;
	}

	// Pagination Settings
	$comments_args = array(
		'base'        => $base . '%_%',
		'format' 	  => 'page/%#%',
		'total'       => $max_page,
		'current'     => $cpage,
		'show_all'    => false,
		'end_size'    => 1,
		'mid_size'    => 2,
		'prev_next'   => True,
		'prev_text'   => '<div class="yz-page-symbole">&laquo;</div><span class="yz-next-nbr">'. $prev_page .'</span>',
		'next_text'   => '<div class="yz-page-symbole">&raquo;</div><span class="yz-next-nbr">'. $next_page .'</span>',
		'type'         => 'plain',
		'add_args'     => false,
		'add_fragment' => '',
		'before_page_number' => '<span class="yz-page-nbr">',
		'after_page_number'  => '</span>',
	);

	// Call Pagination Function
	$paginate_comments = paginate_links( $comments_args );

	// Print Comments Pagination
	if ( $paginate_comments ) {
		echo sprintf( '<nav class="yz-pagination" data-base="%1s" data-per-page="%2s">' , $base, $per_page );
		echo '<span class="yz-pagination-pages">';
		printf( __( 'Page %1$d of %2$d' , 'youzer' ), $cpage, $max_page );
		echo "</span><div class='yz-reviews-nav-links yz-nav-links'>$paginate_comments</div></nav>";
	}

}

/**
 * Get User Rating Rate
 */
function yz_get_user_rating_rate_format( $user_rate ) {
	return round( $user_rate, 2 );
}

add_filter( 'yz_get_user_ratings_rate', 'yz_get_user_rating_rate_format', 10 );

/**
 * Get User User Reviews Details
 */
function yz_get_ratings_details( $args = null ) {

	$args = wp_parse_args( $args , array(
		'show_rate' => true,
		'show_stars' => true,
		'show_total' => true,
		'user_id' => bp_displayed_user_id(),
		'separator' => '<span class="yz-separator">•</span>',
	) );

	$youzer_query = youzer()->reviews->query;
	$user_rate = $youzer_query->get_user_ratings_rate( $args['user_id'] ); 
	
	?>

	<div class="yz-user-ratings-details">
		
		<?php if ( $args['show_stars'] == true ) :?>
		<div class="yz-user-rating-stars"><?php yz_star_rating( array( 'rating' => $user_rate ) ); ?></div>
		<?php endif; ?>
		
		<?php if ( $args['show_rate'] == true ) :?>
		<?php echo $args['separator']; ?>
			<div class="yz-user-ratings-rate"><?php echo sprintf( __( '%s out of 5', 'youzer') , $user_rate ); ?></div>
		<?php endif; ?>
	
		<?php if ( $args['show_total'] == true ) :  ?>
			<?php $reviews_count = $youzer_query->get_user_reviews_count( $args['user_id'] ); echo $args['separator']; ?>
			<div class="yz-user-ratings-total"><?php echo sprintf( __( '%s ratings', 'youzer' ) , $reviews_count ); ?></div>
		<?php endif; ?>

	</div>

	<?php

}

/**
 * Add Reviews
 */
function yz_reviews_script_vars( $vars ) {

	// Get User ID.
	$user_id = bp_loggedin_user_id();

	// Add Var.
	$vars['is_user_can_edit_reviews'] = yz_is_user_can_edit_reviews( $user_id );

	return $vars;

}
