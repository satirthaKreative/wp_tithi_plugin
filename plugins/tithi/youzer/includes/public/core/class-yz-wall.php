<?php

class Youzer_Wall {

	protected $youzer;

    public function __construct() {

		global $Youzer;

    	$this->youzer = &$Youzer;

		// Ajax - Upload Attachments
		add_action( 'wp_ajax_yz_upload_wall_attachments', array( &$this, 'upload_attachments' ) );

		// Ajax - Delete Attachments
		add_action( 'wp_ajax_yz_delete_wall_attachment', array( &$this, 'delete_attachment' ) );

		// Wall Post Attachments
		add_action( 'bp_activity_entry_content', array( &$this, 'wall_post_attachments' ) );

    }

    /**
     * Get Wall Attachments.
     */
	function wall_post_attachments() {

		global $activities_template;

		// Get Activity ID.
		$activity_id = $activities_template->activity->id;

		// Get Attachments
		$attachments = (array) unserialize( bp_activity_get_meta( $activity_id, 'attachments' ) );

		// Get Post Type
		$post_type = bp_activity_get_meta( $activity_id, 'post-type' );

		echo '<div class="yz-post-attachments">';

		switch ( $post_type ) {
			case 'photo':
				$this->get_wall_post_images( $attachments, $activity_id );
				break;
			case 'video':
				$this->get_wall_post_video( $attachments );
				break;
			case 'audio':
				$this->get_wall_post_audio( $attachments );
				break;
			case 'link':
				$this->get_wall_post_link( $attachments, $activity_id );
				break;
			case 'slideshow':
				$this->get_wall_post_slideshow( $attachments );
				break;		
			case 'file':
				$this->get_wall_post_file( $attachments );
				break;
			case 'quote':
				$this->get_wall_post_quote( $attachments, $activity_id );
				break;
			case 'cover':
				$this->get_wall_post_cover( $activity_id );
				break;
		}

		// Get Url Preview
		$this->get_activity_url_preview( $activity_id, $activities_template->activity->content );

		echo '</div>';

	}

	/**
	 * Cover Post.
	 */
	function get_wall_post_cover( $activity_id ) {

		// Get Cover Photo Url.
		$cover_url = bp_activity_get_meta( $activity_id, 'yz-cover-image' );
		
		if ( $cover_url ) {
			echo '<img src="' . $cover_url . '" alt="">';
		}

	}

	/**
	 * Quote Post.
	 */
	function get_wall_post_quote( $attachments, $activity_id ) {

		// Get Quote Cover Url. 
		$cover_img = ! empty( $attachments ) ? yz_get_file_url( $attachments[0] ): false;

		// Get Link Data
		$quote_txt = bp_activity_get_meta( $activity_id, 'yz-quote-text' );
		$quote_owner = bp_activity_get_meta( $activity_id, 'yz-quote-owner' );

		// Get User Data
	    $quote_bg = "style='background-image:url( $cover_img );'";

	    ?>

	    <div class="yzw-quote-post">
		    <div class="yzw-quote-content quote-with-img">
		        <?php if ( $cover_img ) : ?>
		            <div class="yzw-quote-cover" <?php echo $quote_bg; ?>></div>
		        <?php endif; ?>
		        <div class="yzw-quote-main-content">
		            <div class="yzw-quote-icon"><i class="fas fa-quote-right"></i></div>
		            <blockquote><?php echo $quote_txt; ?></blockquote>
		            <h3 class="yzw-quote-owner"><?php echo $quote_owner; ?></h3>
		        </div>
		    </div>
	    </div>
		
		<?php
	}

	/**
	 * File Post.
	 */
	function get_wall_post_file( $attachments ) { 

		// Get File Data
		$real_name = $attachments[0]['real_name'];
	    $file_url  = yz_get_file_url( $attachments[0] ); 
		$name_excerpt = yz_get_filename_excerpt( $real_name, 45 );
		$file_size = yz_file_format_size( $attachments[0]['file_size'] );

		?>

		<div class="yzw-file-post">
			<i class="fas fa-cloud-download yzw-file-icon"></i>
			<div class="yzw-file-title" title="<?php echo $real_name; ?>"><?php echo $name_excerpt; ?></div>
			<div class="yzw-file-size"><?php echo $file_size; ?></div>
			<a href="<?php echo $file_url; ?>" class="yzw-file-download"><i class="fas fa-download"></i><?php _e( 'download', 'youzer' ); ?></a>
		</div>

		<?php
	}

	/**
	 * Link Post.
	 */
	function get_wall_post_link( $attachments, $activity_id ) {

		// Get Link Data
		$link_url = bp_activity_get_meta( $activity_id, 'yz-link-url' );
		$link_desc = bp_activity_get_meta( $activity_id, 'yz-link-desc' );
		$link_title = bp_activity_get_meta( $activity_id, 'yz-link-title' );

		?>

		<a class="yz-wall-link-content" href="<?php echo $link_url; ?>" target="_blank">
			<?php if ( ! empty( $attachments ) && isset( $attachments[0]['original'] ) ) : ?>
				<img src="<?php echo yz_get_file_url( $attachments[0] ); ?>" alt="">
			<?php endif; ?>
			<div class="yz-wall-link-data">
				<div class="yz-wall-link-title"><?php echo $link_title; ?></div>
				<div class="yz-wall-link-desc"><?php echo $link_desc; ?></div>
				<div class="yz-wall-link-url"><?php echo $link_url; ?></div>
			</div>
		</a>

		<?php
	}

	/**
	 * Get Url Preview
	 */
	function get_activity_url_preview( $activity_id, $activity_content = null ) {

		// Get Url Data.
		$url_data = bp_activity_get_meta( $activity_id, 'url_preview' );

		// Unserialize data.
		$url_data = is_serialized( $url_data ) ? unserialize( $url_data ) : maybe_unserialize( base64_decode( $url_data ) );
		
		// Get Preview Link.
		$preview_link = ! empty( $url_data['link'] ) ? $url_data['link'] : null;

		if ( empty( $url_data ) || empty( $preview_link ) ) {
			return false;
		}

		// Check if preview url already exist in content.
		if ( ! empty( $activity_content ) && strpos( $preview_link, $activity_content ) !== false ) {

			// Get Embed Url Code.
		    $embed_code = wp_oembed_get( $preview_link );

		    // Check if url is supported from buddypress.
		    if ( $embed_code ) {
		    	return false;
		    }

		}

		?>
 
		<a class="yz-wall-link-content" href="<?php echo $url_data['link']; ?>" target="_blank">
			<?php if ( ! empty( $url_data['image'] ) && empty( $url_data['use_thumbnail'] ) ) : ?><div class="yz-wall-link-thumbnail" style="background-image:url(<?php echo $url_data['image']; ?>);"></div><?php endif; ?>
			<div class="yz-wall-link-data">
				<?php if ( ! empty( $url_data['title'] ) ) : ?><div class="yz-wall-link-title"><?php echo $url_data['title']; ?></div><?php endif; ?>
				<?php if ( ! empty( $url_data['description'] ) ) : ?><div class="yz-wall-link-desc"><?php echo $url_data['description']; ?></div><?php endif; ?>
				<?php if ( ! empty( $url_data['site'] ) ) : ?><div class="yz-wall-link-url"><?php echo $url_data['site']; ?></div><?php endif; ?>
			</div>
		</a>
		<?php

	}

	/**
	 * Audio Post.
	 */
	function get_wall_post_audio( $attachments ) {

		// Get Audio Url.
		$audio_url = yz_get_file_url( $attachments[0] );

		?>

		<audio controls>
			<source src="<?php echo $audio_url; ?>" type="audio/mpeg">
			<?php _e( 'Your browser does not support the audio element.', 'youzer' ); ?>
		</audio>

		<?php

	}

	/**
	 * Video Post.
	 */
	function get_wall_post_video( $attachments ) {

		// Get Video Url.
		$video_url = yz_get_file_url( $attachments[0] );

		?>

		<video width="100%" controls>
			<source src="<?php echo $video_url; ?>" type="video/mp4">
			<?php _e( 'Your browser does not support the video tag.', 'youzer' ); ?>
		</video>

		<?php

	}

	/**
	 * Slideshow Post.
	 */
	function get_wall_post_slideshow( $slides ) {

        // Get Slides Height Option
        $height_option = yz_options( 'yz_slideshow_height_type' );

		?>

	    <div class="yzw-slider yz-slides-<?php echo $height_option; ?>-height">

	    <?php

	       	foreach ( $slides as $slide ) :

	        // Get Slide Image Url
	        $slide_url = yz_get_file_url( $slide );

		?>

		<div class="yzw-slideshow-item">

            <?php if ( 'auto' == $height_option ) : ?>
            <img src="<?php echo $slide_url; ?>" alt="" >
            <?php else : ?>
            <div class="yzw-slideshow-img" style="background-image: url(<?php echo $slide_url; ?>)" ></div>
            <?php endif; ?>
	    </div>

	    <?php endforeach; ?>

		</div>

		<?php

	}

	/**
	 * Photo Post.
	 */
	function get_wall_post_images( $attachments, $activity_id ) {

		// Get Attachments number.
		$count_atts = count( $attachments );
		
		if ( 1 == $count_atts && ! empty( $attachments[0] ) ) { ?>
			
			<?php $img_url = yz_get_file_url( $attachments[0] ); ?>
			<?php 
				$size = yz_get_image_size( $img_url ); 
				$class = isset( $size[0] ) && ( $size[0] < 800 ) ? 'yz-img-with-padding' : 'yz-full-width-img';
			 ?>
			<a href="<?php echo $img_url; ?>" class="<?php echo $class; ?>" data-lightbox="yz-post-<?php echo $activity_id; ?>">
				<img src="<?php echo $img_url; ?>" alt="" />
			</a>
			
			<?php } elseif ( 2 == $count_atts || 3 == $count_atts ) { ?>

			<div class="yz-post-<?php echo $count_atts; ?>imgs">

				<?php foreach( $attachments as $i => $attachment ) : ?>
					
					<?php $img_url = yz_get_file_url( $attachment ); ?>
					<a class="yz-post-img<?php echo $i + 1;?>" href="<?php echo $img_url; ?>" data-lightbox="yz-post-<?php echo $activity_id; ?>">
						<div class="yz-post-img" style="background-image: url(<?php echo $img_url; ?>)"></div>
					</a>

				<?php endforeach; ?>

			</div>

		<?php } elseif ( 4 <= $count_atts ) { ?>

			<div class="yz-post-4imgs">
				
				<?php foreach( $attachments as $i => $attachment ) : ?>
				<?php $img_url = yz_get_file_url( $attachment ); ?>
				<a class="yz-post-img<?php echo $i + 1; if ( 3 == $i && $count_atts > 4  ) { echo ' yz-post-plus4imgs'; }?>" href="<?php echo $img_url; ?>" data-lightbox="yz-post-<?php echo $activity_id; ?>">
					<div class="yz-post-img" style="background-image: url(<?php echo $img_url; ?>)">
						<?php 
							if ( 3 == $i && $count_atts > 4 ) {
								$images_nbr = $count_atts - 4;
								echo '<span class="yz-post-imgs-nbr">+' . $images_nbr . '</span>';
							}
						?>
					</div>
				</a>

				<?php endforeach; ?>

			</div>
			<?php
		}
	}

	/**
	 * #  Upload Attachment.
	 */
    function upload_attachments( ) {

		
		// echo json_encode( $uploaded_files );
		
    	global $Youzer, $YZ_upload_dir;

		// Before Upload User Files Action.
		do_action( 'yz_before_upload_wall_files' );

		// Check Nonce Security
		// check_ajax_referer( 'yz_wall_nonce_security', 'security' );

		// Get Files.
		$files = $_FILES;

	    if ( ! function_exists( 'wp_handle_upload' ) ) {
	        require_once( ABSPATH . 'wp-admin/includes/file.php' );
	    }

	    $upload_overrides = array( 'test_form' => false );

	    // Get Max File Size in Mega.
	    $max_size = yz_options( 'yz_attachments_max_size' );

		// Set max file size in bytes.
		$max_file_size = $max_size * 1048576;

	    // Change Default Upload Directory to the Plugin Directory.
		add_filter( 'upload_dir' , 'yz_temporary_upload_directory' );

		// Create New Array
		$uploaded_files = array();

	    foreach ( $files as $file ) :

			// Get Uploaded File extension
			$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

			// Check File has the Right Extension.
			if ( ! yz_wall_validate_file_extension( $ext ) ) {
				$data['error'] = $this->msg( 'unpermitted_extension' );
				die( json_encode( $data ) );
			}

			// Check that the file is not too big.
		    if ( $file['size'] > $max_file_size ) {
				$data['error'] = $this->msg( 'max_size' );
				die( json_encode( $data ) );
		    }

			if ( $file['name'] ) {

				// Get Unique File Name.
				$filename = uniqid( 'file_' ) . '.' . $ext;

				// Get Unique File Name for the file.
		        while ( file_exists( $YZ_upload_dir . '/temp/' . $filename ) ) {
					$filename = uniqid( 'file_' ) . '.' . $ext;
				}

				$uploadedfile = array( 
				    'name'     => $filename,
				    'size'     => $file['size'],
				    'type'     => $file['type'],
				    'error'    => $file['error'],
				    'tmp_name' => $file['tmp_name']
				);

		        // Upload File.
		        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		        // Get Files Data.
		        if ( $movefile && ! isset( $movefile['error'] ) ) {
		        	$file_data['real_name'] = $file['name'];
		        	$file_data['file_size'] = $file['size'];
		        	$file_data['original'] = basename( $movefile['url'] );
		        	$uploaded_files[] = $file_data;
		        }

	    	}

	    endforeach;
		
		echo json_encode( $uploaded_files );

	    // Change Upload Directory to the Default Directory .
		remove_filter( 'upload_dir' , 'yz_temporary_upload_directory' );

		die();
    }

	/**
	 * #  Delete Attachments.
	 */
    function delete_attachments( $attachments = null ) {

    	// If There's No attachments Go Out.
    	if ( empty( $attachments ) ) {
    		return false;
    	}

    	global $YZ_upload_dir;

    	// Get Attachments List.
    	$attachments = unserialize( $attachments );

    	foreach ( $attachments as $attachment ) {
			
			// Get File Path.
			$file_path = $YZ_upload_dir . '/' . $attachment['original'];

			// Delete File.
			if ( file_exists( $file_path ) ) {
				unlink( $file_path );
			}

    	}
    }

	/**
	 * #  Delete Attachment.
	 */
    function delete_attachment() {

    	// Get Attachment File Name.
    	$filename = $_POST['attachment'];

		// Before Delete Attachment Action.
		do_action( 'yz_before_delete_attachment' );

		// Check Nonce Security
		check_ajax_referer( 'yz_wall_nonce_security', 'security' );

		// Get Uploads Directory Path.
		$upload_dir = wp_upload_dir();

		// Get File Path.
		$file_path = $upload_dir['basedir'] . "/youzer/temp/" . wp_basename( $filename );

		// Delete File.
		if ( file_exists( $file_path ) ) {
			unlink( $file_path );
		}

		die();
    }

	/**
	 * #  Move Temporary Files To The Main Attachments Directory.
	 */
    function move_attachments( $attachments ) {
    	
    	global $YZ_upload_dir;

    	// Get Maximum Files Number.
	    $max_files = yz_options( 'yz_attachments_max_nbr' );

		// Check attachments files number.	
	    if ( count( $attachments ) > $max_files ) {
			$data['error'] = $this->msg( 'max_files' );
			die( json_encode( $data ) );
	    }

    	// New Attachments List.
    	$new_attachments = array();

		// Get File Path.
		$temp_path = $YZ_upload_dir . '/temp/' ;

 		foreach ( $attachments as $attachment ) {

 			// Get File Data.
	    	$attachment = json_decode( stripcslashes( $attachment ), true );

	    	// Get File Names.
	    	$filename = $attachment[0]['original'];
	    	$realname = $attachment[0]['real_name'];
	    	$file_size = $attachment[0]['file_size'];

			// Get Uploaded File extension
			$ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

	        // Get File New Name.
	        $new_name = $filename;

			// Get Unique File Name for the file.
	        while ( file_exists( $YZ_upload_dir . '/' . $new_name ) ) {
				$new_name = uniqid( 'file_' ) . '.' . $ext;
			}

			// Get Files Path.
			$old_file = $temp_path . '/' . $filename;
			$new_file = $YZ_upload_dir . '/' . $new_name; 

			// Move File From Temporary Directory to the Main Directory.
	        if ( rename( $old_file, $new_file ) ) {

	        	// Get Attachment Data.
	        	$atts_data = array( 
	        		'original' => $new_name,
	        		'real_name' => $realname,
	        		'file_size' => $file_size
	        	);

	        	// Get Attchment Thumbnail.
	        	$atts_data['thumbnail'] = yz_save_image_thumbnail( $atts_data );

	        	$new_attachments[] = $atts_data;
	        }

 		}

		// Serialize Attachments.
		$new_attachments = ! empty( $new_attachments ) ? serialize( $new_attachments ) : false;
		
 		return $new_attachments;
    }

	/**
	 * Redirect User.
	 */
	public function redirect( $action, $code, $redirect_to = null ) {

	    // Get Reidrect page.
	    $redirect_to = ! empty( $redirect_to ) ? $redirect_to : wp_get_referer();

	    // Add Message.
	    bp_core_add_message( $this->msg( $code ), $action );

		// Redirect User.
        wp_redirect( $redirect_to );

        // Exit.
        exit;

	}

    /**
     * Get Attachments Error Message.
     */
    public function msg( $code ) {
        
        // Set up Variables.
        $max_size = yz_options( 'yz_attachments_max_size' );
        $max_files = yz_options( 'yz_attachments_max_nbr' );

        // Messages
        switch ( $code ) {

            case 'invalid_image_extension':
            	// Get Image Allowed Extentions.
            	$image_extentions = yz_get_allowed_extentions( 'image', 'text' );
                return sprintf( __( 'Invalid Image Extension.<br> Only %1s are allowed.', 'youzer' ), $image_extentions );

            case 'invalid_video_extension':
            	// Get Video Allowed Extentions.
            	$video_extentions = yz_get_allowed_extentions( 'video', 'text' );
                return sprintf( __( 'Invalid Video Extension.<br> Only %1s are allowed.', 'youzer' ), $video_extentions );

            case 'invalid_file_extension':
            	// Get File Allowed Extentions.
            	$file_extentions = yz_get_allowed_extentions( 'file', 'text' );
                return sprintf( __( 'Invalid File Extension.<br> Only %1s are allowed.', 'youzer' ), $file_extentions );

            case 'invalid_audio_extension':
            	// Get Audio Allowed Extentions.
            	$audio_extentions = yz_get_allowed_extentions( 'audio', 'text' );
                return sprintf( __( 'Invalid Audio Extension.<br> Only %1s are allowed.', 'youzer' ), $audio_extentions );

            case 'unpermitted_extension':
                return __( 'Sorry, this file type is not permitted for security reasons.', 'youzer' );

            case 'max_one_file':
                return __( "You can't upload more than one file.", 'youzer' );

            case 'empty_status':
                return __( "Please type some text before posting.", 'youzer' );
                
            case 'invalid_post_type':
                return __( "Invalid post type.", 'youzer' );

            case 'invalid_link_url':
                return __( "Invalid link url.", 'youzer' );

            case 'empty_link_title':
                return __( "Please fill the link title field.", 'youzer' );

            case 'empty_link_desc':
                return __( "Please fill the link description field.", 'youzer' );

            case 'empty_quote_owner':
                return __( "Please fill the quote owner field.", 'youzer' );

            case 'empty_quote_text':
                return __( "Please fill the quote text field.", 'youzer' );

            case 'no_attachments':
                return __( "No attachment was uploaded.", 'youzer' );

            case 'slideshow_need_images':
                return __( "slideshow need at least 2 images to work.", 'youzer' );

            case 'max_files_number':
                return sprintf( __( "You can't upload more than %d files.", 'youzer' ), $max_files );
                
            case 'invalid_file_size':
                return sprintf( __( 'File too large. File must be less than %g megabytes.', 'youzer' ), $max_size );
        }

        return __( 'An unknown error occurred. Please try again later.', 'youzer' );
    }

}