<?php

class Youzer_Dashboard {

	/**
	 * # General Settings.
	 */
	function general_settings() {

		// Menu Tabs List
		$tabs = array(
			'general' => array(
				'icon'  => 'fas fa-cogs',
				'id'   	=> 'general',
				'function' => 'yz_general_settings',
				'title' => __( 'general settings', 'youzer' ),
			),
			'wall' => array(
				'id' 		=> 'wall',
				'icon'  	=> 'fas fa-address-card',
				'function' 	=> 'yz_wall_settings',
				'title' 	=> __( 'wall settings', 'youzer' ),
			),
			'groups' => array(
				'id'    => 'groups',
				'icon'  => 'fas fa-users',
				'function'  => 'yz_groups_settings',
				'title' => __( 'Groups settings', 'youzer' ),
			),
			'schemes' => array(
				'id'    => 'schemes',
				'icon'  => 'fas fa-paint-brush',
				'function' => 'yz_schemes_settings',
				'title' => __( 'schemes settings', 'youzer' ),
			),
			'panel' => array(
				'icon'  => 'fas fa-cogs',
				'id'    => 'panel',
				'function' => 'yz_panel_settings',
				'title' => __( 'panel settings', 'youzer' ),
			),
			'emoji' => array(
				'icon'  => 'fas fa-smile',
				'id'    => 'emoji',
				'function' => 'yz_emoji_settings',
				'title' => __( 'emoji settings', 'youzer' ),
			),
			'author-box' => array(
				'id'    => 'author-box',
				'icon'  => 'fas fa-address-book',
				'function' => 'yz_author_box_settings',
				'title' => __( 'author box settings', 'youzer' ),
			),
			'custom-styling' => array(
				'id'    => 'custom-styling',
				'icon'  => 'fas fa-code',
				'function'  => 'yz_custom_styling_settings',
				'title' => __( 'Custom Styling settings', 'youzer' ),
			),
			'social-networks' => array(
				'icon'  => 'fas fa-share-alt',
				'id'    => 'social-networks',
				'title' => __( 'social networks settings', 'youzer' ),
			),
			'account-verification' => array(
				'id'    => 'account-verification',
				'icon'  => 'fas fa-check-circle',
				'function'  => 'yz_account_verification_settings',
				'title' => __( 'Account Verification settings', 'youzer' ),
			),
			'reviews' => array(
				'id'    => 'reviews',
				'icon'  => 'fas fa-star',
				'function'  => 'yz_reviews_settings',
				'title' => __( 'Reviews settings', 'youzer' ),
			),
			'bookmarks' => array(
				'id'    => 'bookmarks',
				'icon'  => 'fas fa-bookmark',
				'function'  => 'yz_bookmarks_settings',
				'title' => __( 'Bookmarks settings', 'youzer' ),
			),
			'groups-directory' => array(
				'id'    => 'groups-directory',
				'icon'  => 'fas fa-list-alt',
				'function'  => 'yz_groups_directory_settings',
				'title' => __( 'Groups Directory settings', 'youzer' ),
			),
			'members-directory' => array(
				'id'    => 'members-directory',
				'icon'  => 'fas fa-list',
				'function'  => 'yz_members_directory_settings',
				'title' => __( 'Members Directory settings', 'youzer' ),
			)
		);
		
		// Filter.
		$tabs = apply_filters( 'yz_panel_general_settings_menus', $tabs );

		// Get Settings.
		$this->get_settings( $tabs );

	}

	/**
	 * # Profile Settings.
	 */
	function profile_settings() {

		global $Youzer_Admin, $Yz_Settings;

		// Menu Tabs List.
		$tabs = array(
			'general' => array(
				'id' 		=> 'profile',
				'icon'  	=> 'fas fa-cogs',
				'function' 	=> 'yz_profile_general_settings',
				'title' 	=> __( 'General settings', 'youzer' ),
			),
			'structure' => array(
				'id' 	=> 'structure',
				'icon'  => 'fas fa-sort-amount-down',
				'title' => __( 'profile structure', 'youzer' ),
			),
			'header' => array(
				'id' 	=> 'header',
				'icon'  => 'fas fa-heading',
				'function' => 'yz_header_settings',
				'title' => __( 'header settings', 'youzer' ),
			),
			'navbar' => array(
				'icon'  => 'fas fa-list',
				'id' 	=> 'navbar',
				'function' => 'yz_navbar_settings',
				'title' => __( 'navbar settings', 'youzer' ),
			),
			'ads' => array(
				'id'    => 'ads',
				'icon'  => 'fas fa-bullhorn',
				'title' => __( 'ads settings', 'youzer' ),
			),
			'tabs' => array(
				'id' 	=> 'tabs',
				'icon'  => 'far fa-list-alt',
				'function' => 'yz_tabs_settings',
				'title' => __( 'tabs settings', 'youzer' ),
			),
			'subtabs' => array(
				'id' 	=> 'tabs',
				'icon'  => 'fas fa-indent',
				'function' => 'yz_profile_subtabs_settings',
				'title' => __( 'Subtabs settings', 'youzer' ),
			),
			'custom-tabs' => array(
				'id' 	=> 'custom-tabs',
				'icon'  => 'fas fa-plus-square',
				'title' => __( 'custom tabs settings', 'youzer' ),
			),
			'info' => array(
				'id' 	=> 'info',
				'icon'  => 'fas fa-info',
				'title' => __( 'info tab settings', 'youzer' ),
			),
			'posts' => array(
				'id' 	=> 'posts',
				'icon'  => 'fas fa-file-alt',
				'function' => 'yz_posts_settings',
				'title' => __( 'posts tab settings', 'youzer' ),
			),
			'comments' => array(
				'id' 	=> 'comments',
				'icon'  => 'far fa-comments',
				'function' => 'yz_comments_settings',
				'title' => __( 'comments tab settings', 'youzer' ),
			),
			'profile-404' => array(
				'id'   => 'profile-404',
				'icon'  => 'fas fa-exclamation-triangle',
				'function' => 'yz_profile_404_settings',
				'title' => __( 'Profile 404 settings', 'youzer' ),
			)
		);

		// Add Third Party Plugins Subnavs Settings
        $third_party_tabs = yz_get_profile_third_party_tabs();
        if ( empty( $third_party_tabs ) ) {
			unset( $tabs['subtabs'] );
        }

		$tabs = apply_filters( 'yz_panel_profile_settings_menus', $tabs );


		// Get Settings.
		$this->get_settings( $tabs );

	}

	/**
	 * # Widgets Settings.
	 */
	function widgets_settings() {

		global $Youzer_Admin, $Yz_Settings;

		// Widgets Tabs List.
		$tabs = array(
			'widgets-settings' => array(
				'id' 	=> 'widgets',
				'function' => 'yz_widgets_settings',
				'title' => __( 'widgets settings', 'youzer' ),
				'icon'  => 'fas fa-cogs'
			),
			'about-me-widget' => array(
				'id' 	=> 'about_me',
				'title' => __( 'about me settings', 'youzer' ),
				'icon'  => 'fas fa-user'
			),
			'post-widget' => array(
				'id' 	=> 'post',
				'title' => __( 'post settings', 'youzer' ),
				'icon'  => 'fas fa-pencil-alt'
			),
			'project-widget' => array(
				'id' 	=> 'project',
				'title' => __( 'project settings', 'youzer' ),
				'icon'  => 'fas fa-suitcase'
			),
			'skills-widget' => array(
				'id' 	=> 'skills',
				'title' => __( 'skills settings', 'youzer' ),
				'icon'  => 'fas fa-tasks'
			),
			'services-widget' => array(
				'id' 	=> 'services',
				'title' => __( 'services settings', 'youzer' ),
				'icon'  => 'fas fa-wrench'
			),
			'portfolio-widget' => array(
				'id' 	=> 'portfolio',
				'title' => __( 'portfolio settings', 'youzer' ),
				'icon'  => 'fas fa-camera-retro'
			),
			'slideshow-widget' => array(
				'id' 	=> 'slideshow',
				'title' => __( 'slideshow settings', 'youzer' ),
				'icon'  => 'fas fa-film'
			),
			'quote-widget' => array(
				'id' 	=> 'quote',
				'title' => __( 'quote settings', 'youzer' ),
				'icon'  => 'fas fa-quote-right'
			),
			'link-widget' => array(
				'id' 	=> 'link',
				'title' => __( 'link settings', 'youzer' ),
				'icon'  => 'fas fa-unlink'
			),
			'video-widget' => array(
				'id' 	=> 'video',
				'title' => __( 'video settings', 'youzer' ),
				'icon'  => 'fas fa-video'
			),
			'instagram-widget' => array(
				'id' 	=> 'instagram',
				'title' => __( 'instagram settings', 'youzer' ),
				'icon'  => 'fab fa-instagram'
			),
			'flickr-widget' => array(
				'id' 	=> 'flickr',
				'title' => __( 'flickr settings', 'youzer' ),
				'icon'  => 'fab fa-flickr'
			),
			'user-balance-widget' => array(
				'id' 	=> 'user_balance',
				'title' => __( 'user balance settings', 'youzer' ),
				'icon'  => 'fas fa-gem'
			),
			'user-badges-widget' => array(
				'id' 	=> 'user_badges',
				'title' => __( 'user badges settings', 'youzer' ),
				'icon'  => 'fas fa-trophy'
			),
			'friends-widget' => array(
				'id' 	=> 'friends',
				'title' => __( 'friends settings', 'youzer' ),
				'icon'  => 'far fa-handshake'
			),
			'groups-widget' => array(
				'id' 	=> 'groups',
				'title' => __( 'groups settings', 'youzer' ),
				'icon'  => 'fas fa-users'
			),			
			'reviews-widget' => array(
				'id' 	=> 'reviews',
				'title' => __( 'reviews settings', 'youzer' ),
				'icon'  => 'far fa-star'
			),
			'info-box-widget' => array(
				'id' 	=> 'info_box',
				'title' => __( 'info Boxes settings', 'youzer' ),
				'icon'  => 'fas fa-clipboard'
			),
			'user-tags-widget' => array(
				'id' 	=> 'user_tags',
				'title' => __( 'user tags settings', 'youzer' ),
				'icon'  => 'fas fa-tags'
			),
			'recent-posts-widget' => array(
				'id' 	=> 'recent_posts',
				'title' => __( 'recent posts settings', 'youzer' ),
				'icon'  => 'far fa-newspaper'
			),
			'social-networks-widget' => array(
				'id' 	=> 'social_networks',
				'title' => __( 'social networks settings', 'youzer' ),
				'icon'  => 'fas fa-share-alt'
			),
			'custom-widgets' => array(
				'id' 	=> 'custom_widgets',
				'title' => __( 'Custom Widgets settings', 'youzer' ),
				'icon'  => 'fas fa-plus'
			)
		);
		
		// Filter
		$tabs = apply_filters( 'yz_panel_widgets_settings_menus', $tabs );

		// Get Settings.
		$this->get_settings( $tabs, 'widget-settings' );

	}

	/**
	 * Get Page Settings
	 */
	function get_settings( $tabs, $page = false ) {

		global $Youzer_Admin, $Yz_Settings;

		// Get Tabs Keys
		$settings_tabs = array_keys( $tabs );

		// Get Current Tab.
		$current_tab = isset( $_GET['tab'] ) && in_array( $_GET['tab'], $settings_tabs ) ? (string) $_GET['tab'] : (string) key( $tabs );

		// Append Class to the active tab.
		$tabs[ $current_tab ]['class'] = 'yz-active-tab';

		// Get Tab Data.
		$tab = $tabs[ $current_tab ];

		// Get Tab Function Name.
		$settings_function = isset( $tab['function'] ) ?  $tab['function']: null;

		ob_start();

        $Yz_Settings->get_field( 
        	array(
	            'type'  => 'start',
	            'id'    => $tab['id'],
	            'icon'  => $tab['icon'],
	            'title' => $tab['title'],
       		)
        );

        switch ( $tab['id'] ) {
        	case 'structure':
        		$structure = new Yz_Profile_Structure();
				$structure->settings();
        		break;

        	case 'ads':
				// Get Ads Settings.
				$ads = new Yz_Ads_Settings();
				$ads->settings();
        		break;
        	
        	case 'info':

				// Get Info Settings.
        		global $Youzer;
				$Youzer->tabs->info->settings();

        		break;
        	
        	case 'social-networks':
				// Get Social Networks Settings.
				$social_networks = new Yz_Networks_Settings();
				$social_networks->settings();
        		break;
        		
        	case 'custom-tabs':
				// Get Custom Tabs Settings.
				$custom_tabs = new Yz_Custom_Tabs();
				$custom_tabs->settings();
        		break;

        	default:
        	
        		// Get Widget Settings.
        		if ( ! empty( $page ) && 'widget-settings' == $page && ! isset( $tab['function'] ) ) {
        			global $Youzer;
        			$widget_name = $tab['id'];
					// Get Widgets Settings
					$Youzer->widgets->$widget_name->admin_settings();
        		} else {
					// Get Settings
					$settings_function();
        		}
        		
        		break;
        }

        $Yz_Settings->get_field( array( 'type' => 'end' ) );

		$content = ob_get_contents();

		ob_end_clean();

		global $Youzer_Admin;
		
		// Print Panel
		$Youzer_Admin->panel->admin_panel( $tabs, $content );

	}

}