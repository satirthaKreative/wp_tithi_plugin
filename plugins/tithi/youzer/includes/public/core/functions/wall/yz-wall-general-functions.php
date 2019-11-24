<?php

/**
 * Check if activity has content.
 */
function yz_activity_has_content() {

	global $activities_template;

	// Get Activity ID.
	$activity_id = $activities_template->activity->id;

	// Get Post Type
	$post_type = bp_activity_get_meta( $activity_id, 'post-type' );

	// Fobidden Post Types
	$fobidden_types = array( 'cover' );

	if ( ! in_array( $post_type, $fobidden_types ) ) {
		return true;
	}

	return false;
}

/**
 * Get Wall Post Url
 */
function yz_get_wall_post_url( $activity_id ) {
	// Get Post Url.
	$post_link = bp_get_root_domain() . '/' . bp_get_activity_root_slug() . '/p/' . $activity_id . '/';
	// Return Link.
	return $post_link;
}

/**
 * Check if post belong to a group.
 */
function yz_wall_is_group_post( $activity ) {

	if ( bp_is_active( 'groups' ) && 'groups' == $activity->component && ! bp_is_groups_component() ) {
		return true;
	}

	return false;
}

/**
 * Wall- Status Action
 */
function yz_activity_action_wall_posts( $action, $activity ) {

	// Get User & Post Data.
	$post_link = yz_get_wall_post_url( $activity->id );
	$user_link = bp_core_get_userlink( $activity->user_id );
	$post_type = bp_activity_get_meta( $activity->id, 'post-type' );

	// Get Post Action.
	switch ( $post_type ) {

		case 'activity_update':
		case 'slideshow':
		case 'status':
		case 'quote':
		case 'video':
		case 'audio':
		case 'link':
		case 'file':

			// Add Group Description.
			if ( yz_wall_is_group_post( $activity ) ) {
				$action =  sprintf( __( '%1s posted', 'youzer' ), $user_link );
			} else {
				$action = $user_link;
			}

			break;

		case 'cover':
			$action = sprintf(
				__( '%1s changed their profile cover', 'youzer' ), $user_link );
			break;

		case 'photo':

			// Get Attachments.
			$attachments = (array) unserialize( bp_activity_get_meta( $activity->id, 'attachments' ) );

			// Get Attchments Number.
			$photos_nbr = count( $attachments );

			if ( $photos_nbr < 2 ) {

				// Add Group Post Action.
				if ( yz_wall_is_group_post( $activity ) ) {
					$action = sprintf( __( '%1s posted', 'youzer' ), $user_link );
				} else {
					$action = $user_link;
				}

			} else {

				// Get Action Label.
				$action_label = sprintf( _n( 'a new photo', '%s new photos', $photos_nbr, 'youzer' ), $photos_nbr );

				// Get Wall Post Action.
				$action = sprintf( __( '%1s added <a href="%2s">%3s</a>', 'youzer' ), $user_link, $post_link, $action_label );

			}

			break;
	};

	// Add Group Description.
	$hide_group_description = array( 'joined_group', 'created_group', 'activity_update' );

	if (
		bp_is_active( 'groups' ) && 'groups' == $activity->component && ! bp_is_groups_component() &&
		! in_array( $activity->type, $hide_group_description ) ) {
		$group = groups_get_group( $activity->item_id );
		$action .= sprintf( __( ' in the group %1s', 'youzer' ), '<a href="' . bp_get_group_permalink( $group ) . '">' . esc_attr( $group->name ) . '</a>' );
	}

	// Return Action
	return apply_filters( 'yz_activity_new_post_action', $action, $activity );

}

add_filter( 'bp_get_activity_action_pre_meta' , 'yz_activity_action_wall_posts', 999, 2 );

/**
 * Wall - Hide Private Users Posts
 */
function yz_wall_hide_private_users_posts( $query ) {

	// If Private Profile Not Allowed Show Default Query.
    if ( 'off' == yz_options( 'yz_allow_private_profiles' ) ) {
		return $query;
    }
    
	// if its not the activity directory exit.
	if ( ! bp_is_activity_directory() ) {
		return $query;
	}
	
	// If current user is an admin show all activities.
	if ( is_super_admin( bp_loggedin_user_id() ) ) {
		return $query;
	}

    // Get List of Private Users.
    $private_users = yz_get_private_user_profiles();

    // Check if there's no private users.
    if ( empty( $private_users ) ) {
    	return $query;
    }

    // Get List of activities to exclude.
    $exclude_activities = yz_get_private_users_activity_ids( $private_users );
	
	// Check if private users have no activities.
	if ( empty( $exclude_activities ) ) {
    	return $query;
    }
	
	// Covert List of Activities ids to string.
    $exclude_activities = implode( ',', array_map( 'absint', $exclude_activities ) );

	if ( ! empty( $query ) ) {
        $query .= '&';
    }

    $query .= 'exclude='. $exclude_activities;
 
    return $query;

}

add_filter( 'bp_ajax_querystring', 'yz_wall_hide_private_users_posts', 999 );
add_filter( 'bp_legacy_theme_ajax_querystring', 'yz_wall_hide_private_users_posts', 999 );

/**
 * Wall Show Everything filter.
 */
function yz_wall_show_everything_filter() {

	// Init Array.
	$filter_actions = array();

  	// Get Allowed Post Types.
  	$unallowed_post_types = yz_get_wall_unallowed_post_types();

  	// Get Context.
  	$context = bp_activity_get_current_context();
  	// Get Actions By Context
	foreach ( bp_activity_get_actions() as $component_actions ) {
		foreach ( $component_actions as $component_action ) {
			if ( in_array( $context, (array) $component_action['context'], true ) || empty( $component_action['context'] ) ) {
				$context_actions[] = $component_action;
			}
		}
	}

	// Get Context Actions Keys
	$context_actions = wp_list_pluck( $context_actions, 'key' );

	foreach ( $context_actions as $action ) {
		if ( ! in_array( $action, $unallowed_post_types ) ) {
			$filter_actions[] = $action;
		}
	}

	$filter_actions = apply_filters( 'actions', $filter_actions );

  	// Get Post Allowed Actions.
  	$actions = implode( ',' , $filter_actions );

  	return $actions;

}

/**
 * Wall Filter Bar.
 */
function yz_edit_wall_filter( $filters ) {

	// Get Wall Post Types.
	$post_types = yz_get_wall_post_types_visibility();

	foreach ( $filters as $filter => $name ) {
		if ( isset( $post_types[ $filter ] ) && 'off' == $post_types[ $filter ] ) {
			unset( $filters[ $filter ] );
		}
	}

	// Unset Friendship Filter.
	if ( 'off' == $post_types['friendship_created'] && 'off' == $post_types['friendship_accepted'] ) {
		if ( isset( $filters['friendship_accepted,friendship_created'] ) ) {
			unset( $filters['friendship_accepted,friendship_created'] );
		}
	}

	// Get Unwanted Filters.
	$unwanted_filters = array( 'group_details_updated', 'activity_update', 'update_avatar', 'updated_profile' );

	// Unset Unwanted Filters.
	foreach ( $unwanted_filters as $filter) {
		if ( isset( $filters[ $filter ] ) ) {
			unset( $filters[ $filter ] );
		}
	}

	return $filters;
}

add_filter( 'bp_get_activity_show_filters_options', 'yz_edit_wall_filter' );

/**
 * Get Wall Post Types Visibility.
 */
function yz_get_wall_post_types_visibility() {

	// Get Post Types Visibility
	$post_types = array(
		'activity_link' 		=> yz_options( 'yz_enable_wall_link' ),
		'activity_file' 		=> yz_options( 'yz_enable_wall_file' ),
		'activity_audio' 		=> yz_options( 'yz_enable_wall_audio' ),
		'activity_photo' 		=> yz_options( 'yz_enable_wall_photo' ),
		'activity_video' 		=> yz_options( 'yz_enable_wall_video' ),
		'activity_quote' 		=> yz_options( 'yz_enable_wall_quote' ),
		'activity_status' 		=> yz_options( 'yz_enable_wall_status' ),
		'activity_update' 		=> yz_options( 'yz_enable_wall_status' ),
		'activity_comment' 		=> yz_options( 'yz_enable_wall_comments' ),
		'new_cover' 			=> yz_options( 'yz_enable_wall_new_cover' ),
		'activity_slideshow'	=> yz_options( 'yz_enable_wall_slideshow' ),
		'new_avatar' 			=> yz_options( 'yz_enable_wall_new_avatar' ),
		'new_member' 			=> yz_options( 'yz_enable_wall_new_member' ),
		'joined_group' 			=> yz_options( 'yz_enable_wall_joined_group' ),
		'new_blog_post' 		=> yz_options( 'yz_enable_wall_new_blog_post' ),
		'created_group' 		=> yz_options( 'yz_enable_wall_created_group' ),
		'updated_profile' 		=> yz_options( 'yz_enable_wall_updated_profile' ),
		'new_blog_comment' 		=> yz_options( 'yz_enable_wall_new_blog_comment' ),
		'friendship_created' 	=> yz_options( 'yz_enable_wall_friendship_created' ),
		'friendship_accepted' 	=> yz_options( 'yz_enable_wall_friendship_accepted' ),
	);

	// Filter Post.
	$post_types = apply_filters( 'yz_wall_post_types_visibility', $post_types );
	
	return $post_types;
}

/**
 * Get Allowed Post Types.
 */
function yz_get_wall_unallowed_post_types() {

	// Init Array.
	$unallowed_types = array();

	// Get Wall Post Types.
	$post_types = yz_get_wall_post_types_visibility();

	// Get Allowed Post Types.
	foreach ( $post_types as $type => $visibility ) {
		if ( 'off' == $visibility ) {
			$unallowed_types[] = $type;
		}
	}

	return $unallowed_types;

}

/**
 * Wall- Video Action
 */
function yz_activity_action_wall_video( $action, $activity ) {

	// Get User & Post Data.
	$user_link = bp_core_get_userlink( $activity->user_id );
	$post_link = bp_activity_get_permalink( $activity->id );

	// Prepare Action.
	$action = sprintf( __( '%1s added a new <a href="%2s">video</a>', 'youzer' ), $user_link, $post_link );

	// Return Action
	return apply_filters( 'yz_activity_new_video_action', $action, $activity );

}

/**
 * Wall- Audio Action
 */
function yz_activity_action_wall_audio( $action, $activity ) {

	// Get User & Post Data.
	$user_link = bp_core_get_userlink( $activity->user_id );
	$post_link = bp_activity_get_permalink( $activity->id );

	// Prepare Action.
	$action = sprintf( __( '%1s added a new <a href="%2s">audio</a>', 'youzer' ), $user_link, $post_link );

	// Return Action
	return apply_filters( 'yz_activity_new_audio_action', $action, $activity );

}

/**
 * Wall- Quote Action
 */
function yz_activity_format_activity_action_activity_quote( $action, $activity ) {

	// Get User & Post Data.
	$user_link = bp_core_get_userlink( $activity->user_id );

	// Prepare Action.
	$action = sprintf( __( '%1s added a new <a href="%2s">quote</a>', 'youzer' ), $user_link );

	// Return Action
	return apply_filters( 'yz_activity_new_quote_action', $action, $activity );

}

/*
 * Get File Format Size.
 **/
function yz_file_format_size( $size ) {

	// Get Sizes.
	$sizes = array(
		__( 'Bytes', 'youzer' ),
		__( 'KB', 'youzer' ),
		__( 'MB', 'youzer' )
	);

    if ( 0 == $size ) { 
      	return( 'n/a' );
   	} else {
    	return ( round( $size/pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $sizes[ $i ] );
  	}

}

/**
 * Delete Temporary Files
 */
function yz_delete_temp_files( ) {

    // Get Uploads Directory.
    $upload_dir = wp_upload_dir();

    // Get Temporary Folder.
    $temp_folder = $upload_dir['basedir'] . '/youzer/temp/*';

    // Time until file deletion threshold ( in minutes ).
    $time = 5; 

    // Get All directory files
    $temp_files = glob( $temp_folder );

    if ( empty( $temp_files ) ) {
    	return false;
    }

    // Remove Old Files.
    foreach ( $temp_files as $filename ) {
        if ( file_exists( $filename ) ) {
            if ( time() - filemtime( $filename ) > $time * 60 ) {
                unlink( $filename );
            }
        }
    }

}

add_action( 'init', 'yz_delete_temp_files' );

/**
 * Post an activity update.
 */
function yz_activity_post_update( $args = '' ) {

	$r = wp_parse_args( $args, array(
		'content'    => false,
		'type'    	 => 'activity_update',
		'user_id'    => bp_loggedin_user_id(),
		'error_type' => 'bool',
	) );

	if ( bp_is_user_inactive( $r['user_id'] ) ) {
		return false;
	}

	// Record this on the user's profile.
	$activity_content = $r['content'];
	$primary_link     = bp_core_get_userlink( $r['user_id'], false, true );

	/**
	 * Filters the new activity content for current activity item.
	 */
	$add_content = apply_filters( 'yz_activity_new_update_content', $activity_content );

	/**
	 * Filters the activity primary link for current activity item.
	 */
	$add_primary_link = apply_filters( 'yz_activity_new_update_primary_link', $primary_link );

	// Now write the values.
	$activity_id = bp_activity_add( array(
		'user_id'      => $r['user_id'],
		'content'      => $add_content,
		'primary_link' => $add_primary_link,
		'component'    => buddypress()->activity->id,
		'type'         => $r['type'],
		'error_type'   => $r['error_type']
	) );

	// Bail on failure.
	if ( false === $activity_id || is_wp_error( $activity_id ) ) {
		return $activity_id;
	}

	/**
	 * Filters the latest update content for the activity item.
	 */
	$activity_content = apply_filters( 'yz_activity_latest_update_content', $r['content'], $activity_content );

	// Add this update to the "latest update" usermeta so it can be fetched anywhere.
	bp_update_user_meta( bp_loggedin_user_id(), 'bp_latest_update', array(
		'id'      => $activity_id,
		'content' => $activity_content
	) );

	/**
	 * Fires at the end of an activity post update, before returning the updated activity item ID.
	 *
	 */
	do_action( 'yz_activity_posted_update', $r['content'], $r['user_id'], $activity_id );

	return $activity_id;
}

/**
 * # Get Files Name Excerpt.
 */
function yz_get_filename_excerpt( $name, $lenght = 25 ) {

    // Get Name Lenght.
    $text_lenght = strlen( $name );

    // If Name is not too long keep it.
    if ( $text_lenght < $lenght ) {
        return $name;
    }

    // Get Result.
    $new_name = substr_replace( $name, '...', $lenght / 2, $text_lenght - $lenght );

    // Return The New Name.
    return $new_name;
}

/**
 * Change Default Upload Directory to the Youzer Directory.
 */
function yz_temporary_upload_directory( $dir ) {
    return array(
        'path'   => $dir['basedir'] . '/youzer/temp',
        'url'    => $dir['baseurl'] . '/youzer/temp',
        'subdir' => '/youzer/temp',
    ) + $dir;
}

/**
 * Get File URL By Name.
 */
function yz_get_file_url( $file ) {
	
	if ( empty( $file ) ) {
		return false;
	}

	global $YZ_upload_url;

	// Init Vars.
	$file_name = null;

	$compression_enabled = apply_filters( 'yz_enable_attachments_compression', true );

	// Prepare Url.
	if ( $compression_enabled ) {
		if ( isset( $file['thumbnail'] ) ) {
			$file_name = $file['thumbnail'];
		} else {
			$file_name = yz_save_image_thumbnail( $file );
		}
	}

	if ( empty( $file_name ) ) {

		// Get Backup File.
		$backup_file = isset( $file['file_name'] ) ? $file['file_name'] : $file;

		// Get File Name.
		$file_name = isset( $file['original'] ) ? $file['original'] : $backup_file;

	}

	// Return File Url.
	return $YZ_upload_url . $file_name;

}

/**
 * Save New Thumbnail
 */
function yz_save_image_thumbnail( $file, $activity_id = null ) {
	
	global $YZ_upload_dir;	

    // Get image from file
    $img = false;

	// Get Backup File.
	$backup_file = isset( $file['file_name'] ) ? $file['file_name'] : $file;

    // Get Filename.
    $filename = isset( $file['original'] ) ? $file['original'] : $backup_file;

	// Get File Type.
	$file_type = wp_check_filetype( $filename );

	// Get File Name.
	$file_name = pathinfo( $filename, PATHINFO_FILENAME );

	// Get File Path.
	$file_path = $YZ_upload_dir . $filename;

    // Get New Image Path.
    $new_img_path = $YZ_upload_dir . $file_name . '_thumb.jpg';

    switch ( $file_type['type'] ) {

		case 'image/jpeg': {
	        $img = imagecreatefromjpeg( $file_path );
	        break;          
	    }

	    case 'image/png': {
	        $img = imagecreatefrompng( $file_path );
	        break;          
	    }

    }
    
    if ( empty( $img ) ) {
    	return false;
    }

    // Get Compression Quality.
    $quality = apply_filters( 'yz_attachments_compression_quality', 80 );

	if ( imagejpeg( $img, $new_img_path , $quality ) ) {

		// Get New File Name.
		$file_basename = $file_name . '_thumb.jpg';

		if ( yz_is_activity_component() ) {

			// Get Activity ID.
			$activity_id = bp_get_activity_id();

			// Get Attachments.
			$attachments = (array) unserialize( bp_activity_get_meta( $activity_id, 'attachments' ) );

			// Get File Key
			$file_key = array_search( $file, $attachments );

			// // Add Thumbnail To Data.
			$attachments[ $file_key ]['thumbnail'] = $file_basename;

			// Serialize Data.
			$attachments = serialize( $attachments );
			
			// Update Data.
			bp_activity_update_meta( $activity_id, 'attachments', $attachments );

		}

		return $file_basename;

	}
	
	return false;

}

/**
 * Display Notice Function
 */
function yz_wall_validate_file_extension( $file_ext ) {
       
   // Get a list of allowed mime types.
   $mimes = get_allowed_mime_types();
   
    // Loop through and find the file extension icon.
    foreach ( $mimes as $type => $mime ) {
      if ( false !== strpos( $type, $file_ext ) ) {
          return true;
        }
    }
    
    return false;
}

/**
 * Enable Wall Posts Embeds
 */
function yz_enable_wall_posts_embeds() {
	if ( 'off' == yz_options( 'yz_enable_wall_posts_embeds' ) ) {
	    return false;
	}
	return true;
}

add_filter( 'bp_use_oembed_in_activity', 'yz_enable_wall_posts_embeds' );

/**
 * Enable Wall Comments Embeds
 */
function yz_enable_wall_comments_embeds() {
	if ( 'off' == yz_options( 'yz_enable_wall_comments_embeds' ) ) {
	    return false;
	}
	return true;
}

add_filter( 'bp_use_embed_in_activity_replies', 'yz_enable_wall_comments_embeds' );

/**
 * Get Wall Post Content.
 */
function yz_get_wall_post_content( $content, $activity ) {

    if ( 'joined_group' == $activity->type ) {

        if ( bp_is_groups_component() ) {	
        	$content = yz_get_wall_embed_user( $activity->user_id );
        } else {
        	$content = yz_get_wall_embed_group( $activity->item_id );
        }

    } elseif ( 'created_group' == $activity->type ) {

        $content = yz_get_wall_embed_group( $activity->item_id );

	} elseif ( 'new_member' == $activity->type ) {

        $content = yz_get_wall_embed_user( $activity->user_id );

	} elseif ( 'updated_profile' == $activity->type ) {

        $content = yz_get_wall_embed_user( $activity->user_id );

    } elseif ( 'friendship_created' == $activity->type ) {

     	if ( bp_is_user() && bp_displayed_user_id() != $activity->user_id ) {
     		$user_id = $activity->user_id;
     	} else {
     		$user_id = $activity->secondary_item_id;	
     	}

        $content = yz_get_wall_embed_user( $user_id );
     
    } elseif ( 'new_avatar' == $activity->type ) {

		$avatar_url = bp_activity_get_meta( $activity->id, 'yz-avatar' );
		$content = '<img src="' . $avatar_url .'" alt="">';

    } elseif ( 'new_blog_post' == $activity->type ) {

    	$content = yz_get_wall_new_post( $activity->secondary_item_id );

    }

    if ( ! empty( $content ) ) {
    	$content = '<div class="activity-inner"><p>' . $content . '</p></div>';
    }

    // Filter
    $content = apply_filters( 'yz_get_activity_content_body', $content, $activity );

    return $content;
}

add_filter( 'bp_get_activity_content_body', 'yz_get_wall_post_content', 10, 2 );

/**
 * Strip Emoji from Content.
 */
function yz_remove_emoji( $content ) {
    
    // Clear Content .
    $content = preg_replace('/&#x[\s\S]+?;/', '', $content );

    return $content;
}

/**
 * Wall Embed User
 */
function yz_get_wall_embed_user( $user_id = false ) {

	if ( ! $user_id ) {
		return false;
	}

	global $Youzer;

	ob_start();

	// Get Avatar Path.
	$avatar_path = bp_core_fetch_avatar( 
		array(
			'item_id' => $user_id,
			'type'	  => 'full',
			'html' 	  => false,
		)
	);

	// Get Cover Photo Path.
    $cover_path = $Youzer->user->cover( 'url', $user_id );

    // Get Profile Link.
    $profile_url = bp_core_get_user_domain( $user_id );

	?>

 	<div class="yz-wall-embed yz-wall-embed-user">
 		<div class="yz-embed-cover" <?php yz_get_embed_item_cover( $cover_path ); ?>></div>
 		<a href="<?php echo $profile_url; ?>" class="yz-embed-avatar" style="background-image: url( <?php echo $avatar_path; ?> );"></a>
 		<div class="yz-embed-data">
 			<div class="yz-embed-head">
	 			<a href="<?php echo $profile_url; ?>" class="yz-embed-name"><?php echo bp_core_get_user_displayname( $user_id ); ?></a>
	 			<div class="yz-embed-meta">@<?php echo bp_core_get_username( $user_id ); ?></div>
 			</div>
 			<div class="yz-embed-action">
 				<?php do_action( 'yz_wall_embed_user_actions' ); ?>
 				<?php if ( bp_is_active( 'friends' ) ) { bp_add_friend_button( $user_id ); } ?>
 				<?php yz_send_private_message_button( $user_id ); ?>
 			</div>
 		</div>
 	</div>

	<?php

	$content = ob_get_contents();

	ob_end_clean();

	return $content;

}

/**
 * 	Wall Embed Group
 */
function yz_get_wall_embed_group( $group_id = false ) {

	if ( ! $group_id ) {
		return false;
	}

	ob_start();
	
	global $Youzer;

    $group = groups_get_group( array( 'group_id' => $group_id ) );

    // Get Group Avatar
	$avatar_path = bp_core_fetch_avatar( 
		array(
			'item_id' => $group_id,
			'type'	  => 'full',
			'html' 	  => false,
			'object'  => 'group',
		)
	);

	// Get Cover Photo Path.
    $cover_path = bp_attachments_get_attachment( 'url', array(
        'object_dir' => 'groups',
        'item_id' => $group_id
        )
    );

    // Get Profile Link.
    $group_url = bp_get_group_permalink( $group );

    // Get Group Members Number
    $members_count = bp_get_group_total_members( $group );

	?>

 	<div class="yz-wall-embed yz-wall-embed-group">
 		<div class="yz-embed-cover" <?php yz_get_embed_item_cover( $cover_path ); ?>></div>
 		<a href="<?php echo $group_url; ?>" class="yz-embed-avatar" style="background-image: url( <?php echo $avatar_path; ?> );"></a>
 		<div class="yz-embed-data">
 			<div class="yz-embed-head">
	 			<a href="<?php echo $group_url; ?>" class="yz-embed-name"><?php echo $group->name; ?></a>
	 			<div class="yz-embed-meta">
	 				<div class="yz-embed-meta-item"><?php $Youzer->group->status( $group ); ?></div>
	 				<div class="yz-embed-meta-item">
	 					<i class="fas fa-users"></i><span><?php echo sprintf( _n( '%s member', '%s members', $members_count, 'youzer' ), bp_core_number_format( $members_count ) ); ?></span>
	 				</div>
	 			</div>
 			</div>
 			<div class="yz-embed-action">
 				<?php do_action( 'yz_wall_embed_group_actions' );?>
 				<?php bp_group_join_button( $group ); ?>
 			</div>
 		</div>
 	</div>

	<?php

	$content = ob_get_contents();

	ob_end_clean();

	return $content;

}

/**
 * Get Embed Cover.
 */
function yz_get_embed_item_cover( $cover_path ) {

    if ( ! empty( $cover_path ) ) {		
		// Get Cover Style.
		$cover_style = 'background-size: cover;';
    } else {
		// If cover photo not exist use pattern.
		$cover_path = YZ_PA . 'images/geopattern.png';			
		// Get Cover Style.
		$cover_style = 'background-size: auto;';
    }

	// print Cover
	echo "style='background-image:url( $cover_path ); $cover_style'";

}

/**
 * Add 'user uploaded new avatar' Post.
 */
function yz_set_new_avatar_activity( $activity ) {
	
	if ( 'new_avatar' != $activity->type ) {
		return false;
	}

	// Get User Avatar.
	$avatar_url = bp_core_fetch_avatar( 
		array(
			'item_id' => $activity->user_id,
			'type'	  => 'full',
			'html' 	  => false,
		)
	);

	// Get Avatars Path.
	$avatars_path = xprofile_avatar_upload_dir();

	// Get Avatar.
	$bp_avatar = $avatars_path['path'] . '/' . basename( $avatar_url );

	// Get Cover New Url.
	$avatar_url = yz_copy_image_to_youzer_directory( $bp_avatar );

	if ( $avatar_url ) {
		// Save Avatar Url.
		bp_activity_update_meta( $activity->id, 'yz-avatar', $avatar_url );
	}

}

add_action( 'bp_activity_after_save', 'yz_set_new_avatar_activity' );

/**
 * Add 'User Uploaded New Cover' Post.
 */
function yz_set_new_cover_activity( $item_id ) {

	// Get Activitiy ID.
	$activity_id = bp_activity_add(
		array(
			'type'      => 'new_cover',
			'user_id'   => bp_displayed_user_id(),
			'component' => buddypress()->activity->id,
		)
	);

	// Get Cover Photo Path.
    $cover_path = bp_attachments_get_attachment( 'path', array(
	        'item_id' => $item_id,
	        'object_dir' => 'members'
        )
    );

    // Update Activity Meta.
	bp_activity_update_meta( $activity_id, 'post-type', 'cover' );

	// Get Cover New Url.
	$cover_url = yz_copy_image_to_youzer_directory( $cover_path );

	// Save Cover Url.
	if ( $cover_url ) {
		bp_activity_update_meta( $activity_id, 'yz-cover-image', $cover_url );
	}

}

add_action( 'xprofile_cover_image_uploaded', 'yz_set_new_cover_activity' );

/**
 * Copy Image from Buddypress Directory to Youzer Directory.
 */
function yz_copy_image_to_youzer_directory( $bp_path ) {

	global $YZ_upload_url, $YZ_upload_dir;

	// Get File Name
	$filename = basename( $bp_path );

    // Get File New Name.
    $new_name = $filename;

	// Get Unique File Name for the file.
    while ( file_exists( $YZ_upload_dir . '/' . $new_name ) ) {
		$new_name = uniqid( 'file_' ) . '.' . $ext;
	}

	// Get Files Path.
	$old_file = $bp_path;
	$new_file = $YZ_upload_dir . '/' . $new_name; 

	// Move File From Buddypress Directory to the Youzer Directory.
    if ( copy( $old_file, $new_file ) ) {
    	return  $YZ_upload_url . '/' . $filename; 
    }

   return false;
}

/**
 * Get List of people who liked a post.
 */
function yz_get_who_liked_activities( $activity_id ) {

	// Get Transient Option.
	$transient_id = 'yz_get_who_liked_activities_' . $activity_id;

	$users = get_transient( $transient_id );

	if ( false === $users ) :

	global $wpdb;

	// Prepare Sql
	$sql = $wpdb->prepare( "SELECT user_id FROM " . $wpdb->base_prefix . "usermeta WHERE meta_key = 'bp_favorite_activities' AND meta_value LIKE %s", '%' . $activity_id . '%' );

	// Get Result
	$result = $wpdb->get_results( $sql , ARRAY_A );

	// Get List of user id's.
	$users = wp_list_pluck( $result, 'user_id' );

	// Remove Duplicated Users.
	$users = array_unique( $users );

	// Hide Deleted Users.
	foreach ( $users as $key => $user_id ) {
        if ( ! yz_is_user_exist( $user_id ) ) {
            unset( $users[ $key ] );
        }
	}

    set_transient( $transient_id, $users, 12 * HOUR_IN_SECONDS );
	
	endif;
	
	return $users;
}

/**
 * Display Meta.
 */
function yz_display_wall_post_meta() {
	
	if ( is_user_logged_in() ) {
		return true;
	}

	$show = true;
	
	// Get Post Likes
	$post_likes = (int) yz_get_who_liked_activities( bp_get_activity_id() );

	// Get Post Comments
	$post_comments = (int) bp_activity_get_comment_count();

	if ( 0 == $post_comments && $post_likes <= 0 && ! is_user_logged_in() ) {
		return false;
	}

	return true;
}

/**
 * Display Who Liked a Post.
 */
function yz_show_who_liked_activities() {

	// Check if likes allowed.
	if ( ! bp_activity_can_favorite() ) {
		return false;
	}

	// Get list of people who liked a post.
	$liked_users = yz_get_who_liked_activities( bp_get_activity_id() );

    if ( empty( $liked_users ) ) {
    	return false;
    }

    $output = '';

    // Max User Number.
    $max_users_number = 3;

	$liked_count = (int) bp_activity_get_meta( bp_get_activity_id(), 'favorite_count' ) - $max_users_number;
    
    foreach ( $liked_users as $key => $user_id ) {
    	
    	if ( $key > $max_users_number - 1 ) {
    		break;
    	}

    	// Get User Data.
    	$fullname = bp_core_get_user_displayname( $user_id );
    	$avatar   = bp_core_fetch_avatar(
    		array(
    			'html' => false,
    			'type'	  => 'thumb',
    			'item_id' => $user_id
    		)
    	);

    	// Get User Image Code.
        $output .= "<a data-yztooltip='" . $fullname . "' style='background-image: url( " . $avatar . ");' href='" . bp_core_get_user_domain ( $user_id ) . "'></a>";
    }

    if ( $output ) { ?>
		<div class="yz-post-liked-by">
        	<?php echo $output; ?>
        	<?php if ( $liked_count > 0 ) : ?>
			<a class="yz-trigger-who-modal yz-view-all" data-yztooltip="<?php _e( 'View All', 'youzer' ); ?>"  data-who-liked="<?php echo bp_get_activity_id(); ?>" >+<?php echo $liked_count; ?></a>
			<?php endif ;?>
			<span class="yz-liked-this"><?php _e( 'liked this', 'youzer' ); ?></span>
        </div>
    <?php }

}

add_action( 'bp_activity_entry_meta_non_logged_in', 'yz_show_who_liked_activities' );

/**
 * Get Wall Comments.
 */
function yz_show_wall_post_comments_number() {

	// Check if comments allowed.
	if ( ! bp_activity_can_comment() ) {
		return false;
	}

	if ( is_user_logged_in() || 0 == bp_activity_get_comment_count() ) {
		return false;
	}

	?>

	<div class="yz-post-comments-nbr">
		<i class="fas fa-comment-dots"></i>
		<?php yz_wall_get_comment_button_title(); ?>
	</div>

	<?php

}

add_action( 'bp_activity_entry_meta_non_logged_in', 'yz_show_wall_post_comments_number' );

/**
 * Get Wall Model.
 */
function yz_wall_modal( $args = false ) {

	// item ID.
	$content_function = $args['function'];

	?>

	<div class="yz-wall-modal">
		<div class="yz-wall-modal-title" ><?php echo $args['title']; ?>
		</div>
		<div class="yz-wall-modal-content">
			<?php $content_function( $args['item_id'] ); ?>
		</div>
		<div class="yz-wall-modal-actions">
			<button class="yz-wall-modal-button yz-wall-modal-close"><?php _e( 'close', 'youzer' ); ?></button>
		</div>
	</div>

	<?php
}

/**
 * Get Wall Model Container.
 */
function yz_add_wall_modal(){ ?>
	
	<!-- Wall Modal. -->
	<div id="yz-wall-modal"></div>
	<div class="yz-wall-modal-overlay">
		<div class="yz-modal-loader">
			<i class="fas fa-spinner fa-spin"></i>
		</div>
	</div>

	<?php
}

add_action( 'bp_after_single_activity_post', 'yz_add_wall_modal' );
add_action( 'bp_after_activity_loop', 'yz_add_wall_modal' );

/**
 * Get who liked a post Modal.
 */
function yz_get_who_liked_post_modal() {

	// Get Modal Args
	$args = array( 
		'item_id'  => $_POST['post_id'],
		'function' => 'yz_get_who_liked_post_list',
		'title'    => __( 'People who liked this', 'youzer' )
	);

	// Get Modal Content
	yz_wall_modal( $args );

	die();
}

add_action( 'wp_ajax_yz_get_who_liked_post', 'yz_get_who_liked_post_modal' );
add_action( 'wp_ajax_nopriv_yz_get_who_liked_post', 'yz_get_who_liked_post_modal' );

/**
 * Get who liked a post List.
 */
function yz_get_who_liked_post_list( $post_id ) {

	// Get Liked Users.
	$users = yz_get_who_liked_activities( $post_id );

	echo '<div class="yz-users-who-list">';

	foreach ( $users as $user_id ) {

		// Get Username.
		$username = bp_core_get_username( $user_id );

		// Get User Display Name.
		$user_link = bp_core_get_user_domain( $user_id );

		// Get User Avatar.
    	$avatar = bp_core_fetch_avatar( array( 'type'=> 'thumb', 'item_id'=> $user_id ) );
        
		?>

		<div class="yz-list-item">
			<a href="<?php echo $user_link; ?>" class="yz-item-avatar"><?php echo $avatar; ?></a>
			<div class="yz-item-data">
				<div class="yz-item-name"><?php echo bp_core_get_userlink( $user_id ); ?></div>
				<div class="yz-item-meta">@ <?php echo $username; ?></div>
			</div>
		</div>

	<?php }

	echo '</div>';

}

/**
 * 	Wall New Post Thumbnail
 */
function yz_get_wall_new_post_thumb( $post_id = false ) {

	// Get Image ID.
	$img_id = get_post_thumbnail_id( $post_id );

	// Get Image Url.
    $img_url = wp_get_attachment_image_src( $img_id , 'large' );

    if ( ! empty( $img_url[0] ) ) {
        $thumbnail = '<img src="'. $img_url[0] . '" alt"">';
    } else {

    	// Get Post Format
    	$post_format = get_post_format();

        // Set Post Format
        $format = ! empty( $post_format ) ? $post_format : 'standard';

		// If cover photo not exist use pattern.
		$cover_path = YZ_PA . 'images/geopattern.png';	

        // Get Thumbnail.
        $thumbnail = '<div class="yz-wall-nothumb" style="background-image:url( ' . $cover_path . ' );">';
        $thumbnail .= '<div class="yz-thumbnail-icon"><i class="' . yz_get_format_icon( $format ) . '"></i></div>';
        $thumbnail .= '</div>';

    }

    return $thumbnail;
}

/**
 * 	Wall Embed Post
 */
function yz_get_wall_new_post( $post_id = false ) {

	if ( ! $post_id ) {
		return false;
	}

	$blogs_ids = is_multisite() ? get_sites() : array( (object) array( 'blog_id' => 1 ) );

	foreach( $blogs_ids as $b ) {

	    switch_to_blog( $b->blog_id );

	    // Get Post Data.
	    $post = get_post( $post_id );

	    // Get Categories
	    $post_link = get_the_permalink( $post_id );
	    $post_tumbnail = yz_get_wall_new_post_thumb( $post_id );
	    $categories = get_the_category_list( ', ', ' ', $post_id );

	    restore_current_blog();

	    if ( $post ) {
	    	break;
	    }

	}

	ob_start();

	?>

 	<div class="yz-wall-new-post">
 		<div class="yz-post-img"><a href="<?php echo $post_link; ?>"><?php echo $post_tumbnail; ?></a></div>

 		<?php do_action( 'yz_after_wall_new_post_thumbnail', $post_id ); ?>

 		<div class="yz-post-inner">
	 			
	 		<div class="yz-post-head">
	 			<div class="yz-post-title"><a href="<?php echo $post_link; ?>"><?php echo $post->post_title; ?></a></div>
	 			<div class="yz-post-meta">
	 				<?php if ( ! empty( $categories ) ) : ?>
	 				<div class="yz-meta-item"><i class="fas fa-tags"></i><?php echo $categories; ?></div>
	 				<?php endif; ?>
	 				<div class="yz-meta-item"><i class="far fa-calendar-alt"></i><?php echo get_the_date( 'F j, Y', $post_id ); ?></div>
	 				<div class="yz-meta-item"><i class="far fa-comments-alt"></i><?php echo $post->comment_count; ?></div>
	 			</div>
	 		</div>
	 		<div class="yz-post-excerpt">
		        <p><?php echo yz_get_excerpt( $post->post_content, 40 ); ?></p>
	 		</div>
	 		<a href="<?php echo $post_link; ?>" class="yz-post-more-button"><span class="yz-btn-icon"><i class="fas fa-angle-double-right"></i></span><span class="yz-btn-title"><?php _e( 'read more', 'youzer' ); ?></span></a>
 		</div>
 	</div>

	<?php

	$content = ob_get_contents();

	ob_end_clean();

	return $content;

}

/**
 * Edit 'User wrote a new post' Post Action.
 */
function yz_edit_new_blog_action( $action , $activity ) {

	// Get User Link
	$user_link = bp_core_get_userlink( $activity->user_id );
	
	// Get Action
	$action = sprintf( __( '%1s wrote a new post', 'youzer' ), $user_link );

	return $action;
}

add_filter( 'bp_blogs_format_activity_action_new_blog_post', 'yz_edit_new_blog_action', 10, 2 );

/**
 * Edit 'posted an update' Post Action.
 */
function yz_edit_activity_post_action( $action , $activity ) {

	// Get User Link
	$user_link = bp_core_get_userlink( $activity->user_id );

	// Add Group Description.
	if ( yz_wall_is_group_post( $activity ) ) {
		$action =  sprintf( __( '%1s posted', 'youzer' ), $user_link );
	} else {
		$action = $user_link;
	}

	return $action;
}

add_filter( 'bp_activity_new_update_action', 'yz_edit_activity_post_action', 10, 2 );

/**
 * Exclude Wall Activities.
 */
function yz_exclude_activity() {

	return false;
	
	if ( ! bp_is_user() ) {
		return false;
	}

	// Get Activity Type.
	global $activities_template;

	// Get Activity Component.
	$activity_component = $activities_template->activity->component;

	// Get Activity Type.
	$activity_type = bp_get_activity_type();

	// Get Activity Forbidden Types.
	$forbidden_types = array( 'updated_profile', 'new_blog_comment', 'activity_comment' );

	// Filter Types.
	$forbidden_types = apply_filters( 'yz_exclude_activity_types', $forbidden_types );

	// Allowed Group Types.
	$allowed_groupe_types = array( 'created_group', 'joined_group' );

	if ( in_array( $activity_type, $forbidden_types ) ) {
		return true;
	}

	if ( 'groups' == $activity_component && ! in_array( $activity_type, $allowed_groupe_types ) ) {
		return true;
	}

	return false;
}

/**
 * Wall Post - Get Comment Button Title.
 */
function yz_wall_get_comment_button_title() {

	// Get Comments Number.
	$comments_nbr = bp_activity_get_comment_count();

	if ( $comments_nbr == '0' ) {
		$button_title = __( '<span></span> Comment', 'youzer' );
	} else {
		$button_title = sprintf( _n( '<span>%s</span> Comment', '<span>%s</span> Comments', $comments_nbr, 'youzer' ), $comments_nbr );
	}

	echo $button_title;
}

/**
 * Call Wall Sidebar
 */
function yz_get_wall_sidebar() {
  	// Display Widgets.
	if ( is_active_sidebar( 'yz-wall-sidebar' ) ) {
		dynamic_sidebar( 'yz-wall-sidebar' );
	}
}

add_action( 'yz_global_wall_sidebar', 'yz_get_wall_sidebar' );

/**
 * Get Wall Single Post Content.
 */
function yz_get_single_wall_post() {

    ?>

    <div id="template-notices" role="alert" aria-atomic="true">
        <?php

        /** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
        do_action( 'template_notices' ); ?>

    </div>

    <div class="activity no-ajax">
        <?php if ( bp_has_activities( 'display_comments=threaded&show_hidden=true&include=' . bp_current_action() ) ) : ?>

            <ul id="activity-stream" class="activity-list item-list">

            <?php while ( bp_activities() ) : bp_the_activity(); ?>
                <?php bp_get_template_part( 'activity/entry' ); ?>
            <?php endwhile; ?>

            </ul>

        <?php endif; ?>
    </div>

    <?php do_action( 'bp_after_single_activity_post' ); ?>
    
    <?php
}

/**
 * Enable/Disable Wall Posts Likes
 */

add_filter( 'bp_activity_can_favorite', 'yz_control_wall_posts_likes_visibility' );

function yz_control_wall_posts_likes_visibility() {

	// Get Likes Visibility
	$likes_allowed = yz_options( 'yz_enable_wall_posts_likes' );

	if ( 'on' == $likes_allowed ) {
		return true;
	}

	return false;
}

/**
 * Enable/Disable Wall Posts Comments
 */

add_filter( 'bp_activity_can_comment', 'yz_control_wall_posts_comments_visibility' );

function yz_control_wall_posts_comments_visibility() {

	// Get Likes Visibility
	$comments_allowed = yz_options( 'yz_enable_wall_posts_comments' );

	if ( 'on' == $comments_allowed ) {
		return true;
	}

	return false;
}

/**
 * Enable/Disable Wall Posts Comments Reply
 */

add_filter( 'bp_activity_can_comment_reply', 'yz_control_wall_posts_reply_visibility' );

function yz_control_wall_posts_reply_visibility() {

	// Get Likes Visibility
	$replies_allowed = yz_options( 'yz_enable_wall_posts_reply' );

	if ( 'on' == $replies_allowed ) {
		return true;
	}

	return false;
}


/**
 * Enable/Disable Wall Posts Delete Button
 */

add_filter( 'bp_activity_user_can_delete', 'yz_control_wall_posts_deletion' );

function yz_control_wall_posts_deletion( $can_delete ) {

	// Get Likes Visibility
	$deletion_allowed = yz_options( 'yz_enable_wall_posts_deletion' );

	if ( $can_delete && 'on' == $deletion_allowed ) {
		return true;
	}

	return false;
}

/**
 * Get Post Like Button.
 */
function yz_get_pos_like_button() {

	// Get Activity ID.
	$activity_id = bp_get_activity_id();
	
	if ( ! bp_get_activity_is_favorite() ) {

		// Get Like Link.
		$like_link = bp_get_activity_favorite_link();

		// Get Like Button.
		$button = '<a href="'. $like_link .'" class="button fav bp-secondary-action">' . __( 'Like', 'youzer' ) . '</a>';

		// Filter.
		$button = apply_filters( 'yz_filter_post_like_button', $button, $like_link, $activity_id );

	} else {

		// Get Unlike Link.
		$unlike_link = bp_get_activity_unfavorite_link();

		// Get Like Button.
		$button = '<a href="'. $unlike_link .'" class="button unfav bp-secondary-action">' . __( 'Unlike', 'youzer' ) . '</a>';

		// Filter.
		$button = apply_filters( 'yz_filter_post_unlike_button', $button, $unlike_link, $activity_id );

	}
	
	return $button;

}

/**
 * Get Open Graph Tags
 */
function yz_get_open_graph_tags( $type = null, $url = null,  $title = nul, $description = null, $image = null ) {

    $type = ! empty( $type ) ? $type : 'profile';

    ?>

    <meta property="og:type" content="<?php echo $type; ?>"/>
    
    <?php if ( ! empty( $title ) ) : ?>
        <meta property="og:title" content="<?php echo $title; ?>"/>
    <?php endif; ?>


    <meta property="og:url" content="<?php echo $url; ?>"/>

    <?php if ( ! empty( $image ) ) : ?>
        <?php $image_size = yz_get_image_size( $image ); ?>
        <meta property="og:image" content="<?php echo $image; ?>"/>
        <meta property="og:image:url" content="<?php echo $image; ?>"/>
        <meta property="og:image:width" content="<?php echo $image_size[0]; ?>"/>
        <meta property="og:image:height" content="<?php echo $image_size[1]; ?>"/>
    <?php endif; ?>

    <?php if ( ! empty( $description ) ) : ?>
    	<?php $description = wp_strip_all_tags( $description ); ?>
        <meta property="og:description" content="<?php echo $description; ?>"/>
    <?php endif; ?>
    <?php 

}

/**
 * Get Activity By ID.
 */
function yz_get_activity_by_id( $activity_id ) {

	$activity = BP_Activity_Activity::get( array( 'in' => $activity_id ) );
	$activity = isset( $activity['activities'][0] ) ? $activity['activities'][0] : null;

	return $activity;
}

/**
 * Delete Wall Favs Transient.
 */
function yz_delete_activity_likes_transient( $activity_id = null ) {
	// Delete Transient.
	delete_transient( 'yz_get_who_liked_activities_' . $activity_id );
}

add_action( 'bp_activity_remove_user_favorite', 'yz_delete_activity_likes_transient', 1 );
add_action( 'bp_activity_add_user_favorite', 'yz_delete_activity_likes_transient', 1 );

/**
 * Add Activity Shortcode.
 **/
function yz_activitiy_shortcode( $atts ) {

	// Get Args.
	$args = shortcode_atts(
		array(
			'show_sidebar' => false,
		), $atts, 'yz_verified_users' );

	if ( $args['show_sidebar'] == false ) {	
	    // Remove Sidebar.
	    remove_action( 'yz_global_wall_sidebar', 'yz_get_wall_sidebar' );
	}

	$class = $args['show_sidebar'] == false ? 'yz-no-sidebar' : 'yz-with-sidebar';

    echo "<div class='yz-activity-shortcode $class'>";
    include YZ_TEMPLATE . 'activity/index.php';
    echo "</div>";
}

add_shortcode( 'youzer_activity', 'yz_activitiy_shortcode' );

/**
 * Check For Activity Shortcode.
 */
function yz_check_for_activity_shortcode( $posts ) {

    if ( empty( $posts ) ) {
        return $posts;
    }
 
    // false because we have to search through the posts first
    $found = false;
 
    // search through each post
    foreach ( $posts as $post ) {
        // check the post content for the short code
        if ( stripos( $post->post_content, '[youzer_activity' ) )
            // we have found a post with the short code
            $found = true;
            // stop the search
            break;
    }
 
    if ( $found ) {
    	add_filter( 'bp_activity_maybe_load_mentions_scripts', 'yz_enable_activity_shortcode_mentions' );
    	do_action( 'yz_call_activity_scripts' );
    }

    return $posts;

}

add_action( 'the_posts', 'yz_check_for_activity_shortcode' );

/**
 * Call Shortcode Activity Scripts.
 */
add_action( 'yz_call_activity_scripts', 'yz_activity_scripts' );

/**
 * Set Shortcode page as buddypress component.
 */
function yz_enable_activity_shortcode_mentions( $activate ) {
	return true;
}
