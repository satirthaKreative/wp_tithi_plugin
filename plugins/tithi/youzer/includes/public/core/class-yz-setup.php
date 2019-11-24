<?php

class Youzer_Setup {

    public function __construct() {
	}
	
	/**
	 * # Install Youzer Options .
	 */
	function install_options() {

		// Add Default Social Netwokrs
		$social_networks = array(
			'yz_sn_1' 	=> array(
				'icon' 	=> 'fas fa-facebook-f',
				'name' 	=> __( 'facebook', 'youzer' ),
				'color'	=> '#4987bd'
			),
			'yz_sn_2' 	=> array(
				'icon' 	=> 'google-plus-g',
				'name'	=> __( 'google plus', 'youzer' ),
				'color'	=> '#ed4242'
			),
			'yz_sn_3' 	=> array(
				'icon' 	=> 'twitter',
				'name'	=> __( 'twitter', 'youzer' ),
				'color'	=> '#63CDF1'
				),
			'yz_sn_4' 	=> array(
				'icon' 	=> 'instagram',
				'name'	=> __( 'instagram', 'youzer' ),
				'color'	=> '#ffcd21'
			)
		);

		if ( ! get_option( 'yz_social_networks' ) ) {
			update_option( 'yz_social_networks' , $social_networks );
			update_option( 'yz_next_snetwork_nbr', '5' );
        	update_option( 'hide-loggedout-adminbar', 1 );
		}

		if ( ! get_option( 'yz_next_snetwork_nbr' ) ) {
			update_option( 'yz_next_snetwork_nbr', '5' );
		}

		if ( ! get_option( 'yz_next_widget_nbr' ) ) {
			update_option( 'yz_next_widget_nbr', '1' );
		}

		if ( ! get_option( 'yz_next_field_nbr' ) ) {
			update_option( 'yz_next_field_nbr', '1' );
		}

		if ( ! get_option( 'yz_next_ad_nbr' ) ) {
			update_option( 'yz_next_ad_nbr', '1' );
		}

		if ( ! get_option( 'yz_next_custom_widget_nbr' ) ) {
			update_option( 'yz_next_custom_widget_nbr', '1' );
		}
		
		if ( ! get_option( 'yz_next_custom_tab_nbr' ) ) {
			update_option( 'yz_next_custom_tab_nbr', '1' );
		}
		
		if ( ! get_option( 'yz_next_user_tag_nbr' ) ) {
			update_option( 'yz_next_user_tag_nbr', '1' );
		}
		
		if ( ! get_option( 'yz_profile_default_tab' ) ) {
            update_option( 'yz_profile_default_tab', 'overview' );
		}

		// Get Available Social Networks.
		$providers = array( 'Facebook', 'Twitter', 'Google', 'LinkedIn', 'Instagram' );

		if ( ! get_option( 'logy_social_providers' ) ) {
			update_option( 'logy_social_providers', $providers );
		}

	}

    /**
     * Install New Version Options
     */
    function install_new_version_options() {

        if ( get_option( 'install_youzer_2.1.5_options' ) ) {
            return false;
        }

        $main_widgets = get_option( 'yz_profile_main_widgets' );

        if ( ! empty( $main_widgets ) ) {
        	$main_widgets[] = array( 'reviews' => 'visible' );
        	update_option( 'yz_profile_main_widgets', $sidebar_widgets );
        }

        // Mark New Options As Installed
        update_option( 'install_youzer_2.1.5_options', 1 );

    }

	/**
	 * Build DataBase Tables.
	 */
	public function build_database_tables() {

        global $wpdb;


        // Set Variables
        $sql = array();
        $users_table = $wpdb->prefix . 'logy_users';
        $bookmark_table = $wpdb->prefix . 'yz_bookmark';
        $reviews_table = $wpdb->prefix . 'yz_reviews';
        $charset_collate = $wpdb->get_charset_collate();

        // Users Table SQL.
		$sql[] = "CREATE TABLE $users_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			provider varchar(200) NOT NULL,
			identifier varchar(200) NOT NULL,
			websiteurl varchar(255) NOT NULL,
			profileurl varchar(255) NOT NULL,
			photourl varchar(255) NOT NULL,
			displayname varchar(150) NOT NULL,
			description varchar(200) NOT NULL,
			firstname varchar(150) NOT NULL,
			lastname varchar(150) NOT NULL,
			gender varchar(10) NOT NULL,
			language varchar(20) NOT NULL,
			age varchar(10) NOT NULL,
			birthday int(11) NOT NULL,
			birthmonth int(11) NOT NULL,
			birthyear int(11) NOT NULL,
			email varchar(255) NOT NULL,
			emailverified varchar(200) NOT NULL,
			phone varchar(75) NOT NULL,
			address varchar(255) NOT NULL,
			country varchar(75) NOT NULL,
			region varchar(50) NOT NULL,
			city varchar(50) NOT NULL,
			zip varchar(25) NOT NULL,
			profile_hash varchar(200) NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		$sql[] = "CREATE TABLE $bookmark_table (
			id BIGINT(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			collection_id int(11) NOT NULL,
			item_id int(11) NOT NULL,
			item_type varchar(200) NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		$sql[] = "CREATE TABLE $reviews_table (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			reviewer int(11) NOT NULL,
			reviewed int(11) NOT NULL,
			rating DECIMAL(2,1) NOT NULL,
			review LONGTEXT NOT NULL,
			options LONGTEXT NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		// Include Files.
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// Build Tables.
		dbDelta( $sql );
	}
	
	/**
	 * # Install Pages .
	 */
	function install_pages() {

		if ( current_user_can( 'manage_options' ) && ! get_option( 'logy_is_installed' ) ) {


			// Plugin Pages
			$pages = array(
				'login' 		 => array( 'title' => __( 'Login', 'youzer' ) ),
			 	'lost-password'  => array( 'title' => __( 'Password Reset', 'youzer' ) ),
			 	'complete-registration'  => array( 'title' => __( 'Complete Registration', 'youzer' ) )
			);

			// Install Core Pages
			foreach ( $pages as $slug => $page ) {

				// Check that the page doesn't exist already
				$is_page_exists = $this->get_post_id( 'page', '_logy_core', $slug );

				if ( ! $is_page_exists ) {

					$user_page = array(
						'post_title'	 => $page['title'],
						'post_name'		 => $slug,
						'post_type' 	 => 'post',
						'post_status'	 => 'publish',
						'post_author'    =>  1,
						'comment_status' => 'closed'
					);

					$post_id = wp_insert_post( $user_page );

					wp_update_post( array('ID' => $post_id, 'post_type' => 'page' ) );

					update_post_meta( $post_id, '_logy_core', $slug );

					$logy_pages[ $slug ] = $post_id;
				}
			}

			if ( isset( $logy_pages ) ) {
				update_option( 'logy_pages', $logy_pages );
			}
			
			update_option( 'logy_is_installed', 1 );
		}
	}

	/**
	 * # Get Post ID .
	 */
	function get_post_id( $post_type, $key_meta , $meta_value ) {

		// Get Posts
		$posts = get_posts(
		    array(
		        'post_type'  => $post_type,
		        'meta_key'   => $key_meta,
		        'meta_value' => $meta_value
		    )
		);

		if ( isset( $posts[0] ) && ! empty( $posts ) ) {
		    return $posts[0]->ID;
		}

		return false;
	}

	/**
	 * Register Reset Password Email.
	 */
	function register_bp_reset_password_email() {

	    // Do not create if it already exists and is not in the trash
	    $post_exists = post_exists( '[{{{site.name}}}] Reset Your Account Password' );
	 
	    if ( $post_exists != 0 && get_post_status( $post_exists ) == 'publish' ) { 
	       return;
	    }

	    // Check if term Already Exist.
		$term = term_exists( 'request_reset_password', bp_get_email_tax_type() );

		if ( $term ) {
			return;
		}
	  
	    // Create post object
	    $my_post = array(
	      'post_title'    => __( '[{{{site.name}}}] Reset Your Account Password', 'youzer' ),
	      'post_content'  => __( "we got a request to reset your account password. If you made this request, visit the following link To reset your password: <a href=\"{{password.reset.url}}\">{{password.reset.url}}</a>\n\n If this was a mistake, just ignore this email and nothing will happen.", "logy" ),  // HTML email content.
	      'post_excerpt'  => __( "we got a request to reset your account password. If you made this request, visit the following link To reset your password: {{password.reset.url}}\n\n If this was a mistake, just ignore this email and nothing will happen.", 'youzer' ),  // Plain text email content.
	      'post_status'   => 'publish',
	      'post_type' => bp_get_email_post_type() // this is the post type for emails
	    );
	 
	    // Insert the email post into the database
	    $post_id = wp_insert_post( $my_post );
	 
	    if ( $post_id ) {
	    // add our email to the taxonomy term 'post_received_comment'
	        // Email is a custom post type, therefore use wp_set_object_terms
	 
	        $tt_ids = wp_set_object_terms( $post_id, 'request_reset_password', bp_get_email_tax_type() );
	        foreach ( $tt_ids as $tt_id ) {
	            $term = get_term_by( 'term_taxonomy_id', (int) $tt_id, bp_get_email_tax_type() );
	            wp_update_term( (int) $term->term_id, bp_get_email_tax_type(), array(
	                'description' => 'A member request a password reset.',
	            ) );
	        }
	    }
	}
}