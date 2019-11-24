<?php

/**
 * Register Wall New Actions.
 */
function yz_add_new_wall_post_actions() {

	// Init Vars
	$bp = buddypress();

	bp_activity_set_action(
		$bp->activity->id,
		'activity_status',
		__( 'Posted a new status', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'status', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_quote',
		__( 'Posted a new quote', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'quotes', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_photo',
		__( 'Posted a new photo', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'photos', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_video',
		__( 'Posted a new video', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'videos', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_audio',
		__( 'Posted a new audio', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'audios', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_slideshow',
		__( 'Posted a new slideshow', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'slideshows', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_link',
		__( 'Posted a new link', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'links', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_file',
		__( 'uploaded a new file', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'files', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'update_avatar',
		__( 'changed their profile avatar', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'avatar', 'youzer' ),
		array( 'activity', 'member' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'new_cover',
		__( 'changed their profile cover', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'cover', 'youzer' ),
		array( 'activity', 'member' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'new_avatar',
		__( 'changed their profile avatar', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'avatar', 'youzer' ),
		array( 'activity', 'member', )
	);

}

add_action( 'bp_register_activity_actions', 'yz_add_new_wall_post_actions' );

/**
 * Post user/group activity update.
 */
function yz_wall_action_post_update() {
	
	global $Youzer;

	// Do not proceed if user is not logged in, not viewing activity, or not posting.
	if ( ! is_user_logged_in() || ! bp_is_activity_component() || ! bp_is_current_action( 'post' ) ) {
		return false;
	}

	do_action( 'yz_before_wall_post_update' );
	
	// Check the nonce.
	check_admin_referer( 'yz_wall_nonce_security', 'security' );

	// Init Vars.
	$post_type = sanitize_text_field( $_POST['post_type'] );

	/**
	 * Filters the content provided in the activity input field.
	 */
	$content = apply_filters( 'yz_bp_activity_post_update_content', $_POST['status'] );
	if ( ! empty( $_POST['yz-whats-new-post-object'] ) ) {

		/**
		 * Filters the item type that the activity update should be associated with.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value Item type to associate with.
		 */
		$object = apply_filters( 'bp_activity_post_update_object', $_POST['yz-whats-new-post-object'] );
	}

	if ( ! empty( $_POST['yz-whats-new-post-in'] ) ) {

		/**
		 * Filters what component the activity is being to.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value Chosen component to post activity to.
		 */
		$item_id = apply_filters( 'bp_activity_post_update_item_id', $_POST['yz-whats-new-post-in'] );
	}

	// Validate Post Type.
	yz_validate_wall_form( $_POST );

	// No existing item_id.
	if ( empty( $item_id ) ) {

		$activity_id = yz_activity_post_update( array(
			'content' => $content,
			'type'    => 'activity_' . $post_type,
		) );

	// Post to groups object.
	} elseif ( 'groups' == $object && bp_is_active( 'groups' ) ) {
		if ( (int) $item_id ) {
			$activity_id = yz_groups_post_update(
				array(
					'content' => $content,
					'group_id' => $item_id,
					'type' => 'activity_' . $post_type
					)
			);
		}
	} else {

		/**
		 * Filters activity object for BuddyPress core and plugin authors before posting activity update.
		 *
		 * @since 1.2.0
		 *
		 * @param string $object  Activity item being associated to.
		 * @param string $item_id Component ID being posted to.
		 * @param string $content Activity content being posted.
		 */
		$activity_id = apply_filters( 'bp_activity_custom_update', $object, $item_id, $content );
	}
	
	// Update Post Type.
	bp_activity_update_meta( $activity_id, 'post-type', $_POST['post_type'] );

	switch ( $post_type ) {

		case 'link':

			// Init Vars.
			$link_url = esc_url( $_POST['link_url'] );
			$link_desc = esc_textarea( $_POST['link_desc'] );
			$link_title = sanitize_text_field( trim( $_POST['link_title'] ) );

			// Save Data
			bp_activity_update_meta( $activity_id, 'yz-link-url', $link_url );
			bp_activity_update_meta( $activity_id, 'yz-link-desc', $link_desc );
			bp_activity_update_meta( $activity_id, 'yz-link-title', $link_title );

			break;
		
		case 'quote':

			// Init Vars.
			$quote_text = esc_textarea( $_POST['quote_text'] );
			$quote_owner = sanitize_text_field( $_POST['quote_owner'] );

			// Save Data.
			bp_activity_update_meta( $activity_id, 'yz-quote-text', $quote_text );
			bp_activity_update_meta( $activity_id, 'yz-quote-owner', $quote_owner );

			break;
	}

	// Save Url Preview Data. 
	if ( isset( $_POST['url_preview_link'] ) && ! empty( $_POST['url_preview_link'] ) ) {

		$url_preview_args = array(
			'site'  		=> esc_url( $_POST['url_preview_site'] ),
			'link'  		=> esc_url( $_POST['url_preview_link'] ),
			'description'   => esc_textarea( $_POST['url_preview_desc'] ),
			'image' 		=> sanitize_text_field( $_POST['url_preview_img'] ),
			'title' 		=> sanitize_text_field( $_POST['url_preview_title'] ),
			'use_thumbnail' => sanitize_text_field( $_POST['url_preview_use_thumbnail'] ),
		);

		// Serialize.
		$url_preview_data = base64_encode( serialize( $url_preview_args ) );

		// Save Url Data.
		bp_activity_update_meta( $activity_id, 'url_preview', $url_preview_data );

	}

	// Save Attachments 
	if ( ! empty( $_POST['attachments_files'] ) ) {
		global $Youzer;
		$atts = $Youzer->wall->move_attachments( $_POST['attachments_files'] );
		bp_activity_update_meta( $activity_id, 'attachments', $atts );
	}

	// Provide user feedback.
	if ( ! empty( $activity_id ) ) {
		bp_core_add_message( __( 'Update Posted!', 'youzer' ) );
	} else {
		bp_core_add_message( __( 'There was an error when posting your update. Please try again.', 'youzer' ), 'error' );
	}

	// Redirect.
	bp_core_redirect( wp_get_referer() );
}

add_action( 'bp_actions', 'yz_wall_action_post_update' );


/**
 * Validate Wall Form.
 */
function yz_validate_wall_form( $post ) {

	global $Youzer;

	// Get Vars.
	$post_type = sanitize_text_field( $post['post_type'] );
	$post_content = sanitize_text_field( $post['status'] );

	// Get Allowed Post Types.
	$allowed_post_types = array(
		'status', 'photo', 'video' , 'audio',
		'link', 'slideshow','file', 'quote'
	);
	
	// Check Post Type.	
	if ( ! in_array( $post_type, $allowed_post_types ) ) {
		$Youzer->wall->redirect( 'error', 'invalid_post_type' );
	}

	// Get Attachments Post Types.
	$attachments_post_types = array( 'photo', 'video', 'audio', 'slideshow', 'file' );

	// Check Attachments.
	if ( in_array( $post_type, $attachments_post_types ) && empty( $post['attachments_files'] ) ) {
		$Youzer->wall->redirect( 'error', 'no_attachments' );
	}
	
	// Check if status is empty.
	if ( 'status' == $post_type ) {

		if ( ( empty( $post_content ) || ! strlen( trim( $post_content ) ) ) && 'off' == yz_options( 'yz_enable_wall_url_preview' ) ) {
			$Youzer->wall->redirect( 'error', 'empty_status' );
		}		

		if ( ( empty( $post_content ) || ! strlen( trim( $post_content ) ) ) && 'on' == yz_options( 'yz_enable_wall_url_preview' ) && empty( $_POST['url_preview_link'] ) ) {
			$Youzer->wall->redirect( 'error', 'empty_status' );
		}

	}

	// Check Slideshow Post.
	if ( 'slideshow' == $post_type && count( $post['attachments_files'] ) < 2 ) {
		$Youzer->wall->redirect( 'error', 'slideshow_need_images' );
	}

	// Check Quote Post.
	if ( 'quote' == $post_type ) {

		// Init Vars.
		$quote_text = esc_textarea( $post['quote_text'] );
		$quote_owner = sanitize_text_field( trim( $post['quote_owner'] ) );

		// Validate Quote text.
		if ( empty( $quote_text ) ) {
			$Youzer->wall->redirect( 'error', 'empty_quote_text' );
		}		

		// Validate Quote Owner.
		if ( empty( $quote_owner ) ) {
			$Youzer->wall->redirect( 'error', 'empty_quote_owner' );
		}

	}

	// Check Link Post.
	if ( 'link' == $post_type ) {

		// Init Vars.
		$link_url = esc_url( $post['link_url'] );
		$link_desc = esc_textarea( $post['link_desc'] );
		$link_title = sanitize_text_field( trim( $post['link_title'] ) );

		// Validate Link Url.
		if ( ! empty( $link_url ) && filter_var( $link_url, FILTER_VALIDATE_URL ) === false ) {
			$Youzer->wall->redirect( 'error', 'invalid_link_url' );
		}			

		// Validate Link title.
		if ( empty( $link_title ) ) {
			$Youzer->wall->redirect( 'error', 'empty_link_title' );
		}		

		// Validate Link Description.
		if ( empty( $link_desc ) ) {
			$Youzer->wall->redirect( 'error', 'empty_link_desc' );
		}
	}

}


/**
 * Post an Activity status update affiliated with a group.
 */
function yz_groups_post_update( $args = '' ) {

	if ( ! bp_is_active( 'activity' ) ) {
		return false;
	}

	$bp = buddypress();

	$defaults = array(
		'content'    => false,
		'type'    	 => 'activity_update',
		'user_id'    => bp_loggedin_user_id(),
		'group_id'   => 0,
		'error_type' => 'bool'
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	if ( empty( $group_id ) && !empty( $bp->groups->current_group->id ) )
		$group_id = $bp->groups->current_group->id;

	if ( empty( $user_id ) || empty( $group_id ) )
		return false;

	$bp->groups->current_group = groups_get_group( $group_id );

	// Be sure the user is a member of the group before posting.
	if ( ! bp_current_user_can( 'bp_moderate' ) && ! groups_is_user_member( $user_id, $group_id ) ) {
		return false;
	}

	// Record this in activity streams.
	$activity_action  = sprintf( __( '%1$s posted an update in the group %2$s', 'youzer' ), bp_core_get_userlink( $user_id ), '<a href="' . bp_get_group_permalink( $bp->groups->current_group ) . '">' . esc_attr( $bp->groups->current_group->name ) . '</a>' );
	$activity_content = $content;

	/**
	 * Filters the action for the new group activity update.
	 */
	$action = apply_filters( 'yz_groups_activity_new_update_action',  $activity_action, $user_id, $group_id );

	/**
	 * Filters the content for the new group activity update.
	 */
	$content_filtered = apply_filters( 'yz_groups_activity_new_update_content', $activity_content );

	$activity_id = groups_record_activity( array(
		'user_id'    => $user_id,
		'action'     => $action,
		'content'    => $content_filtered,
		'type'       => $r['type'],
		'item_id'    => $group_id,
		'error_type' => $error_type
	) );

	groups_update_groupmeta( $group_id, 'last_activity', bp_core_current_time() );

	/**
	 * Fires after posting of an Activity status update affiliated with a group.
	 */
	do_action( 'yz_groups_posted_update', $content, $user_id, $group_id, $activity_id );

	return $activity_id;
}

/**
 * Wall Form Post Types Options. 
 */
function yz_wall_form_post_types_buttons() {

	// Init Array().
	$checked = true;
	$post_types = array();

	// Status Data.
	$post_types[] = array(
		'id'	=> 'status',
		'icon' 	=> 'fas fa-comment-dots',
		'name'  => __( 'status', 'youzer' ),
		'show'	=> yz_options( 'yz_enable_wall_status' )
	);

	// Photo Data.
	$post_types[] = array(
		'id'	=> 'photo',
		'icon' 	=> 'fas fa-camera-retro',
		'name'  => __( 'photo', 'youzer' ),
		'show'	=> yz_options( 'yz_enable_wall_photo' )
	);

	// Slideshow Data.
	$post_types[] = array(
		'icon' 	=> 'fas fa-film',
		'id'	=> 'slideshow',
		'name'  => __( 'slideshow', 'youzer' ),
		'show'	=> yz_options( 'yz_enable_wall_slideshow' )
	);

	// Quote Data.
	$post_types[] = array(
		'id'	=> 'quote',
		'icon' 	=> 'fas fa-quote-right',
		'name'  => __( 'quote', 'youzer' ),
		'show'	=> yz_options( 'yz_enable_wall_quote' )
	);

	// File Data.
	$post_types[] = array(
		'id'	=> 'file',
		'icon' 	=> 'fas fa-cloud-download-alt',
		'name'  => __( 'file', 'youzer' ),
		'show'	=> yz_options( 'yz_enable_wall_file' )
	);

	// Video Data.
	$post_types[] = array(
		'id'	=> 'video',
		'icon' 	=> 'fas fa-video',
		'name'  => __( 'video', 'youzer' ),
		'show'	=> yz_options( 'yz_enable_wall_video' )
	);

	// Audio Data.
	$post_types[] = array(
		'id'	=> 'audio',
		'icon' 	=> 'fas fa-volume-up',
		'name'  => __( 'audio', 'youzer' ),
		'show'	=> yz_options( 'yz_enable_wall_audio' )
	);

	// Link Data.
	$post_types[] = array(
		'id'	=> 'link',
		'icon' 	=> 'fas fa-link',
		'name'  => __( 'link', 'youzer' ),
		'show'	=> yz_options( 'yz_enable_wall_link' )
	);

	// Filter
	$post_types = apply_filters( 'yz_wall_form_post_types_buttons', $post_types );

	// Remove Disabled Post Types.
	foreach ( $post_types as $key => $post_type ) {
		if ( 'off' == $post_type['show'] ) {
			unset( $post_types[ $key] );
		}
	}

	// Print Code.
	foreach ( $post_types as $post_type ) : ?>

		<div class="yz-wall-opts-item">
			<input type="radio" value="<?php echo $post_type['id']; ?>" name="post_type" id="yz-wall-add-<?php echo $post_type['id']; ?>" <?php if ( $checked ) echo 'checked'; ?>>
			<label class="yz-wall-add-<?php echo $post_type['id']; ?>" for="yz-wall-add-<?php echo $post_type['id']; ?>">
				<i class="<?php echo $post_type['icon']; ?>"></i><?php echo $post_type['name']; ?>
			</label>
		</div>

		<?php $checked = false; ?>
	
	<?php endforeach;

	// After Printing Buttons.
	do_action( 'yz_wall_form_post_types' );

	?>

	<?php if ( count( $post_types ) > 5 ) : ?>
		<div class="yz-wall-opts-item yz-wall-opts-show-all">
				<label class="yzw-form-show-all">
					<i class="fas fa-ellipsis-h"></i>
				</label>
		</div>
	<?php endif; ?>
	
	<?php
}