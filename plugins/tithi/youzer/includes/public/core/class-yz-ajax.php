<?php

class Youzer_Ajax {

	function __construct() {

		// Upload Files.
		add_action( 'wp_ajax_upload_files', array( &$this, 'upload_files' ) );

		// Ajax - Delete Attachments
		add_action( 'wp_ajax_yz_delete_account_attachment', array( &$this, 'delete_attachment' ) );

		// Posts - Ajax Pagination
		add_action( 'wp_ajax_nopriv_pages_pagination', array( &$this, 'posts_pagination' ) );
		add_action( 'wp_ajax_pages_pagination', array( &$this, 'posts_pagination' ) );

		// Comments - Ajax Pagination
		add_action( 'wp_ajax_nopriv_comments_pagination', array( &$this, 'comments_pagination' ) );
		add_action( 'wp_ajax_comments_pagination', array( &$this, 'comments_pagination' ) );
	}

	/**
	 * #  Delete Attachment.
	 */
    function delete_attachment() {

    	// Get Attachment File Name.
    	$filename = $_POST['attachment'];
    	
    	if ( empty( $filename ) ) {
    		return;
    	}

		// Before Delete Attachment Action.
		do_action( 'yz_before_delete_account_attachment' );

		// Get Uploads Directory Path.
		$upload_dir = wp_upload_dir();

		// Get File Path.
		$file_path = $upload_dir['basedir'] . '/youzer/' . wp_basename( $filename );

		// Delete File.
		if ( file_exists( $file_path ) ) {
			unlink( $file_path );
		}

    }


	/**
	 * # Posts Tab Pagination.
	 */
	function posts_pagination() {

		global $Youzer;
		
		// Get Profile User ID
	    $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );

	    // Pagination Args
		$args = array(
			'order' 		 => 'DESC',
			'post_status'	 => 'publish',
			'base' 		 	 => $_POST['base'],
			'paged' 		 => $_POST['page'],
			'author' 		 => $query_vars['yz_user'],
			'posts_per_page' => yz_options( 'yz_profile_posts_per_page' )
		);

		// Get Posts Core
		$Youzer->tabs->posts->posts_core( $args );

	    die();

	}

	/**
	 * # Comments Tab Pagination.
	 */
	function comments_pagination() {

		global $Youzer;

		// Get Page Number.
		$cpage = $_POST['page'];

		// Get Profile User ID
	    $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );

		// Get Data.
		$commentsNbr = yz_options( 'yz_profile_comments_nbr' );
		$offset 	 = ( $cpage - 1 ) * $commentsNbr;

		// Pagination Args
		$args = array(
			'paged'   => $cpage,
			'offset'  => $offset,
			'number'  => $commentsNbr,
			'base' 	  => $_POST['base'],
			'user_id' => $query_vars['yz_user'],
		);

		// Get Comments Core
		$Youzer->tabs->comments->comments_core( $args );

	    die();
	}

	/**
	 * #  Save Uploaded Files.
	 */
	function upload_files() {

		// Before Upload User Files Action.
		do_action( 'youzer_before_upload_user_files' );

		// Check Nonce Security
		check_ajax_referer( 'yz_nonce_security', 'nonce' );

		// Get Files.
		$files = $_FILES;

	    if ( ! function_exists( 'wp_handle_upload' ) ) {
	        require_once( ABSPATH . 'wp-admin/includes/file.php' );
	    }

	    $upload_overrides = array( 'test_form' => false );

	    // Get Max File Size in Mega.
	    $max_size = yz_options( 'yz_files_max_size' );

		// Set max file size in bytes.
		$max_file_size = $max_size * 1048576;

		// Valid Extensions
		$valid_extensions = array( 'jpeg', 'jpg', 'png', 'gif' );

		// Valid Types
		$valid_types = array( 'image/jpeg', 'image/jpg', 'image/png','image/gif' );

		// Minimum Image Resolutions.
		$min = apply_filters( 'yz_attachments_image_min_resolution', array( 'width' => '100', 'height' => '100' ) );

	    // Change Default Upload Directory to the Youzer Directory.
		add_filter( 'upload_dir' , array( &$this, 'youzer_upload_directory' ) );

		// Create New Array
		$uploaded_files = array();

	    foreach ( $files as $key => $file ) :

		    // Get Image Size.
		    $get_image_size = getimagesize( $file['tmp_name'] );

			// Get Uploaded File extension
			$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

			// Check File has the Right Extension.
			if ( ! in_array( $ext, $valid_extensions ) ) {
				$data['error'] = __( 'Invalid file Extension.', 'youzer' );
				die( json_encode( $data ) );
			}

			// Check That The File is of The Right Type.
			if ( ! in_array( $file['type'], $valid_types ) ) {
				$data['error'] = __( 'Invalid file Type.', 'youzer' );
				die( json_encode( $data ) );
			}

			// Check that the file is not too big.
		    if ( $file['size'] > $max_file_size ) {
				$data['error'] = sprintf( esc_html__( 'File too large. File must be less than %d megabytes.', 'youzer' ), $max_size );
				die( json_encode( $data ) );
		    }

			// Check Image Existence.
			if ( ! $get_image_size ) {
				$data['error'] = __( 'Uploaded file is not a valid image.', 'youzer' );
				die( json_encode( $data ) );
			}

			// Check Image Minimum Width.
			if ( $get_image_size[0] < $min[ 'width' ] ) {
				$data['error'] = sprintf( esc_html__( 'Image Minimum width is %d pixel.', 'youzer' ), $min['width'] );
				die( json_encode( $data ) );
			}
			// Check Image Minimum Height.
			if ( $get_image_size[1] < $min[ 'height' ] ) {
				$data['error'] = sprintf( esc_html__( 'Image Minimum height is %d pixel.', 'youzer' ), $min['height'] );
				die( json_encode( $data ) );
			}

			if ( $file['name'] ) {

				$uploadedfile = array(
				    'size'     => $file['size'],
				    'name'     => $file['name'],
				    'type'     => $file['type'],
				    'error'    => $file['error'],
				    'tmp_name' => $file['tmp_name']
				);

		        // Upload File.
		        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		        if ( $movefile && ! isset( $movefile['error'] ) ) {
		        	$file_name = basename( $movefile['url'] );
		        	$data['original'] = $file_name;
		        	$data['thumbnail'] = yz_save_image_thumbnail( array( 'original' => $file_name ) );
		        	echo json_encode( $data );
		        }

	    	}

	    endforeach;

	    // Change Youzer Upload Directory to the Default Directory .
		remove_filter( 'upload_dir' , array( &$this, 'youzer_upload_directory' ) );

		die();
	}

	/**
	 * Change Default Upload Directory to the Youzer Directory.
	 */
	function youzer_upload_directory( $dir ) {
		global $YZ_upload_url, $YZ_upload_dir;
	    return array(
	        'path'   => $YZ_upload_dir,
	        'url'    => $YZ_upload_url,
	        'subdir' => '/youzer',
	    ) + $dir;
	}

}