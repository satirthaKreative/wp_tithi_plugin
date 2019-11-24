<?php

/**
 * # Get Youzer Page Template.
 */
function yz_bbp_youzer_template( $page_template ) {

    // Check if its youzer plugin page
    if ( is_bbpress() && ! is_404() ) {
        return YZ_TEMPLATE . 'bbpress-template.php';
    }
    return $page_template;
}

add_filter( 'template_include', 'yz_bbp_youzer_template', 999 );

/**
 * Register Youzer BBpress Templates Folder Location
 */
function yz_bbp_register_template_location() {
    return YZ_TEMPLATE . '/bbpress';
}
  
/**
 * Over Load BBpress Templates.
 */ 
function yz_bbp_overload_templates() {

    // Get New Templates Location
    if ( function_exists( 'bbp_register_template_stack' ) ) {
        bbp_register_template_stack( 'yz_bbp_register_template_location', 1 );
    }
     
}

add_action( 'bp_init', 'yz_bbp_overload_templates' );

/**
 * Forum Topic Head Meta
 */
function yz_bbp_forum_topic_head( $args = array() ) {

	// Parse arguments against default values
	$r = bbp_parse_args( $args, array(
		'topic_id'  => 0,
		'before'    => '<div class="bbp-template-notice info"><p class="bbp-topic-description">',
		'after'     => '</p></div>',
		'size'      => 20
	), 'get_single_topic_description' );

	// Validate topic_id
	$topic_id = bbp_get_topic_id( $r['topic_id'] );

	// Unhook the 'view all' query var adder
	remove_filter( 'bbp_get_topic_permalink', 'bbp_add_view_all' );

	// Build the topic description
	$vc_int      = bbp_get_topic_voice_count   ( $topic_id, true  );
	$voice_count = bbp_get_topic_voice_count   ( $topic_id, false );
	$reply_count = bbp_get_topic_replies_link  ( $topic_id        );
	$time_since  = bbp_get_topic_freshness_link( $topic_id        );

	// Singular/Plural
	$voice_count = sprintf( _n( '%s voice', '%s voices', $vc_int, 'bbpress' ), $voice_count );

	// Topic has replies
	$last_reply = bbp_get_topic_last_reply_id( $topic_id );
	if ( !empty( $last_reply ) ) {
		$last_updated_by = bbp_get_author_link( array( 'post_id' => $last_reply, 'size' => $r['size'] ) );
		$retstr          = sprintf( esc_html__( 'last updated by %1$s %2$s', 'bbpress' ), $last_updated_by, $time_since );

	// Topic has no replies
	} elseif ( ! empty( $voice_count ) && ! empty( $reply_count ) ) {
		$retstr = sprintf( esc_html__( 'This topic contains %1$s and has %2$s.', 'bbpress' ), $voice_count, $reply_count );

	// Topic has no replies and no voices
	} elseif ( empty( $voice_count ) && empty( $reply_count ) ) {
		$retstr = sprintf( esc_html__( 'This topic has no replies.', 'bbpress' ), $voice_count, $reply_count );
	}

	// Add the 'view all' filter back
	add_filter( 'bbp_get_topic_permalink', 'bbp_add_view_all' );

	?>

	<div class="yz-bbp-topic-head-meta">
		<div class="yz-bbp-topic-head-meta-item yz-bbp-head-meta-last-updated"><?php echo $retstr; ?></div>
		<div class="yz-bbp-topic-head-meta-item">
			<i class="fas fa-microphone"></i><?php echo $voice_count; ?>
		</div>
		<div class="yz-bbp-topic-head-meta-item">
			<i class="fas fa-comments"></i><?php echo $reply_count; ?>
		</div>
	</div>

	<?php

}

/**
 * Get Topic Icon
 */
function yz_bbp_get_topic_icon( $topic_id = null ) {
	
	$icon = 'fas fa-file-alt';

	if ( bbp_is_topic_sticky( $topic_id ) ) {
		$icon = 'fas fa-thumbtack';
	} elseif( bbp_get_topic_status( $topic_id ) == 'closed' ) {
		$icon = 'fas fa-lock';
	} elseif( bbp_get_topic_status( $topic_id ) == 'trash' ) {
		$icon = 'fas fa-trash-alt';
	} elseif( bbp_get_topic_status( $topic_id ) == 'pending' ) {
		$icon = 'fas fa-ellipsis-h';
	} elseif ( bbp_get_topic_status( $topic_id ) == 'spam' ) {
		$icon = 'fas fa-ban';
	} elseif ( bbp_is_topic_anonymous( $topic_id ) ) {
		$icon = 'fas fa-user-secret';
	}

	return '<i class="' . $icon . '"></i>';

}

/**
 * Get Forum Icon
 */
function yz_bbp_get_forum_icon( $forum_id = null ) {
	
	$icon = 'fas fa-comment-dots';

	if ( bbp_is_topic_sticky( $forum_id ) ) {
		$icon = 'fas fa-thumb-tack';
	} elseif( bbp_get_forum_visibility( $forum_id ) == 'hidden' ) {
		$icon = 'fas fa-eye-slash';
	} elseif( bbp_get_forum_visibility( $forum_id ) == 'private' ) {
		$icon = 'fas fa-user-secret';
	} elseif ( bbp_get_forum_status( $forum_id ) == 'closed' ) {
		$icon = 'fas fa-lock';
	} elseif ( bbp_is_topic_anonymous( $forum_id ) ) {
		$icon = 'fas fa-user-secret';
	}

	return '<i class="' . $icon . '"></i>';

}

/**
 * Get Single Forum Meta
 */
function yz_bbp_single_forum_head_meta(  $args = '' ) {

	// Parse arguments against default values
	$r = bbp_parse_args( $args, array(
		'forum_id'  => 0,
		'before'    => '<div class="bbp-template-notice info"><p class="bbp-forum-description">',
		'after'     => '</p></div>',
		'size'      => 14,
		'feed'      => true
	), 'get_single_forum_description' );

	// Validate forum_id
	$forum_id = bbp_get_forum_id( $r['forum_id'] );

	// Unhook the 'view all' query var adder
	remove_filter( 'bbp_get_forum_permalink', 'bbp_add_view_all' );

	// Get some forum data
	$tc_int      = bbp_get_forum_topic_count( $forum_id, false );
	$rc_int      = bbp_get_forum_reply_count( $forum_id, false );
	$topic_count = bbp_get_forum_topic_count( $forum_id );
	$reply_count = bbp_get_forum_reply_count( $forum_id );
	$last_active = bbp_get_forum_last_active_id( $forum_id );

	// Has replies
	if ( ! empty( $reply_count ) ) {
		$reply_text = sprintf( _n( '%s reply', '%s replies', $rc_int, 'bbpress' ), $reply_count );
	}

	// Forum has active data
	if ( !empty( $last_active ) ) {
		$topic_text      = bbp_get_forum_topics_link( $forum_id );
		$time_since      = bbp_get_forum_freshness_link( $forum_id );
		$last_updated_by = bbp_get_author_link( array( 'post_id' => $last_active, 'size' => $r['size'] ) );

	// Forum has no last active data
	} else {
		$topic_text      = sprintf( _n( '%s topic', '%s topics', $tc_int, 'bbpress' ), $topic_count );
	}

	// Forum has active data
	if ( !empty( $last_active ) ) {

		if ( !empty( $reply_count ) ) {

			if ( bbp_is_forum_category( $forum_id ) ) {
				$retstr = sprintf( esc_html__( 'last updated by %1$s %2$s.', 'bbpress' ),$last_updated_by, $time_since );
			} else {
				$retstr = sprintf( esc_html__( 'last updated by %1$s %2$s.',    'bbpress' ), $last_updated_by, $time_since );
			}

		} else {

			if ( bbp_is_forum_category( $forum_id ) ) {
				$retstr = sprintf( esc_html__( 'last updated by %1$s %2$s.', 'bbpress' ), $last_updated_by, $time_since );
			} else {
				$retstr = sprintf( esc_html__( 'last updated by %1$s %2$s.', 'bbpress' ), $last_updated_by, $time_since );
			}
		}

	}

	// Add the 'view all' filter back
	add_filter( 'bbp_get_forum_permalink', 'bbp_add_view_all' );

	?>

	<div class="yz-bbp-topic-head-meta">
		<?php if ( isset( $retstr ) ) : ?>
		<div class="yz-bbp-topic-head-meta-item yz-bbp-head-meta-last-updated"><?php echo $retstr; ?></div>
		<?php endif; ?>
		
		<?php if ( isset( $topic_text ) ) : ?>
		<div class="yz-bbp-topic-head-meta-item">
			<i class="fas fa-pencil-alt"></i><?php echo $topic_text; ?>
		</div>
		<?php endif; ?>
		<?php if ( isset( $reply_text ) ) : ?>
		<div class="yz-bbp-topic-head-meta-item">
			<i class="fas fa-comments"></i><?php echo $reply_text; ?>
		</div>
		<?php endif; ?>
	</div>
<?php
}

/**
 * BBpress Enqueue scripts.
 */
function yz_bbpress_scripts( $hook_suffix ) {

    global $Youzer;
    
    // Disable BBpress styling.
    wp_dequeue_style( 'bbp-default' );
    wp_dequeue_style( 'bbp-default-rtl' );

    if ( is_singular() || is_bbpress() ) {
	    // Register MyCRED Css.
	    wp_register_style( 'yz-bbpress', YZ_PA . 'css/yz-bbpress.min.css', $Youzer->version );
	    // Call MyCRED Css.
	    wp_enqueue_style( 'yz-bbpress' );
    }

}

add_action( 'wp_enqueue_scripts', 'yz_bbpress_scripts' );

/**
 * Add create forum step class.
 */
function yz_group_create_forum_step_class( $class ) {

	if ( ! bp_is_group_creation_step( 'forum' ) ) {
		return $class;
	}

	$class[] = 'yz-group-create-forum-step';

	return $class;
}

add_filter( 'yz_group_class', 'yz_group_create_forum_step_class' );

/**
 * Register Forums Sidebars
 */
function yz_register_forum_sidebars() {

    register_sidebar(
        array (
            'name' => __( 'Forum Sidebar', 'youzer' ),
            'id' => 'yz-forum-sidebar',
            'description' => __( 'Forums Pages Sidebar', 'youzer' ),
            'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );

}

add_action( 'widgets_init', 'yz_register_forum_sidebars' );

/**
 * Call Forum Sidebar
 */
function yz_get_forum_sidebar() {
  	// Display Widgets.
	if ( is_active_sidebar( 'yz-forum-sidebar' ) ) {
		dynamic_sidebar( 'yz-forum-sidebar' );
	}
}

add_action( 'yz_forum_sidebar', 'yz_get_forum_sidebar' );

/**
 * Display Forum Sidebar.
 */
function yz_show_forum_sidebar() {
	return apply_filters( 'yz_show_forum_sidebar', true );
}

/**
 * Get Like Button
 */
function yz_bbp_topic_favorite_link() {

	bbp_topic_favorite_link(
		array(
			'before' => '',
			'favorite' => '<span class="yz-toggle-btn">' . __( 'Like', 'youzer' ) . '</span>',
			'favorited' => '<span class="yz-toggle-btn">' . __( 'Unlike', 'youzer' ) . '</span>',
		)
	);

}

/**
 * # Get Group Page Class.
 */
function yz_forums_page_class() {

	global $Youzer;

    // New Array
    $class = array();

    // Get Group Page
    $class[] = 'yz-page yz-forum';
    
    // Get Group Width Type
    $class[] = 'yz-wild-content';

    // Get Tabs List Icons Style
    $class[] = yz_options( 'yz_tabs_list_icons_style' );

    // Get Page Buttons Style
    $class[] = 'yz-page-btns-border-' . yz_options( 'yz_buttons_border_style' );

    // Get Elements Border Style.
    $class[] = 'yz-wg-border-' . yz_options( 'yz_wgs_border_style' );

    $class = apply_filters( 'yz_forum_class', $class );

    return yz_generate_class( $class );
}

/**
 * Enable Groups breadcrumb.
 */
add_filter( 'bbp_no_breadcrumb', '__return_false', 20 );
