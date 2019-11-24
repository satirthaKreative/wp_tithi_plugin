<?php

class Youzer_Account {

	function __construct() {

		// Add Actions
		add_action( 'bp_init', array( &$this, 'settings_actions' ) );
		add_action( 'wp_ajax_yz_unlink_provider_account', array( &$this, 'unlink_provider_account' ) );

	}

	/**
	 * # Account Settings Pages.
	 */
	function get_account_menu_icon( $icon = null, $slug ) {

		switch ( $slug ) {
			case 'notifications':
				$icon = 'fas fa-bell';
				break;
		}

		return $icon;
	}

	/**
	 * # Account Settings Pages.
	 */
	function account_settings_pages() {

		// Init Pages.
		$pages = array();

		// Add Spam Account nav item.
		if ( bp_current_user_can( 'bp_moderate' ) && ! bp_is_my_profile() ) {

			$pages['capabilities'] = array(
				'name'	=> __( 'Capabilities Settings', 'youzer' ),
				'icon'	=> 'fas fa-wrench',
				'order'	=> 50
			);

		}

		$pages['change-password'] = array(
			'name'	=> __( 'Change Password', 'youzer' ),
			'icon'	=> 'fas fa-lock',
			'order'	=> 50
		);

	    if ( 'on' == yz_options( 'yz_allow_private_profiles' ) ) {
	    	$pages['account-privacy'] = array(
				'name'	=> __( 'Account Privacy', 'youzer' ),
				'icon'	=> 'fas fa-user-secret',
				'order'	=> 60
			);
	    }

		$pages['export-data'] = array(
			'name'	=> __( 'Export Data', 'youzer' ),
			'icon'	=> 'fas fa-download',
			'order'	=> 80
		);

	    if ( ( ! bp_disable_account_deletion() && bp_is_my_profile() ) || bp_current_user_can( 'delete_users' ) ) {
	    	if ( ! is_super_admin( bp_displayed_user_id() ) ) {	
		    	$pages['delete-account'] = array(
					'name'	=> __( 'Delete account', 'youzer' ),
					'icon'	=> 'fas fa-trash-alt',
					'order'	=> 60
				);
	    	}
	    }

		// Filter
		$pages = apply_filters( 'yz_account_settings_pages', $pages );

	    return $pages;
	}

	/**
	 * # Profile Settings Pages.
	 */
	function profile_settings_pages() {
		
		$pages = array(
			'profile-info' => array(
				'name'	=> __( 'Profile Info', 'youzer' ),
				'icon'	=> 'fas fa-cogs',
				'order' => 10
			),
			'contact-info' => array(
				'name' 	=> __( 'Contact Info', 'youzer' ),
				'icon'	=> 'fas fa-envelope',
				'order' 	=> 20
			),
			'change-avatar' => array(
				'name'	=> __( 'Profile Picture', 'youzer' ),
				'icon'	=> 'fas fa-user-circle',
				'order'	=> 30
			),
			'change-cover-image' => array(
				'name'	=> __( 'Profile Cover', 'youzer' ),
				'icon'	=> 'fas fa-camera-retro',
				'order'	=> 35
			),
			'social-networks' => array(
				'name'	=> __( 'Social Networks', 'youzer' ),
				'icon'	=> 'fas fa-share-alt',
				'order'	=> 40
			)
		);

		if ( bp_is_active( 'xprofile' ) ) {

			// Fields Groups.
			$groups = bp_profile_get_field_groups();

			foreach ( $groups as $group ) {

				// Hide Empty Fields Groups
				if ( count( $group->fields ) <= 0 ) {
					continue;
				}
				
				$group_slug = 'edit/group/' . $group->id;

				// Prepare Item Data.
				$page_item = array(
					'name'	=> $group->name,
					'widget_name'	=> $group->name,
					'icon'	=> yz_get_xprofile_group_icon( $group->id ),
					'order'	=> 100
				);

				// Add Groups Pages List.
				$pages[ $group_slug ] = $page_item;
			}

		}
		
		// if there's no networks don't show the networks form..
		$networks = get_option( 'yz_social_networks' );
		if ( empty( $networks ) ) {
			unset( $pages['social_networks'] );
		}

		// Filter
		$pages = apply_filters( 'yz_profile_settings_pages', $pages );
		
		return $pages;
	}

	/**
	 * # Profile Settings Menu.
	 */
	function profile_menu() {

		// Get Menu Data.
		$menu_settings = array(
			'slug'		=> 'profile',
			'menu_list'  => $this->profile_settings_pages(),
			'menu_title' => __( 'Profile Settings', 'youzer' )
		);

		$this->get_menu( $menu_settings );

	}

	/**
	 * # Account Settings Menu.
	 */
	function account_menu() {

		// Get Menu Data.
		$menu_settings = array(
			'slug'		=> 'settings',
			'menu_list'  => $this->account_settings_pages(),
			'menu_title' => __( 'Account Settings', 'youzer' )
		);

		$this->get_menu( $menu_settings );

	}

	/**
	 * # Widgets Settings Menu.
	 */
	function widgets_menu() {

		// Get Widgets Menu List.
		$menu_list = youzer()->widgets->get_settings_widgets();

		// Filter.
		$menu_list = apply_filters( 'account_widgets_settings_pages', $menu_list );

		// Prepare Account Menu List
		$menu_settings = array(
			'slug'		 => 'widgets-settings',
			'menu_title' => __( 'Widgets Settings', 'youzer' ),
			'menu_list'	 => $menu_list
		);

		// Print Menu's
		$this->get_menu( $menu_settings );

	}

	/**
	 * Convert Widgets to Pages.
	 */
	function convert_widgets_to_pages( $widgets ) {

		$pages = null;

		foreach ( $widgets as $widget ) {

			// Get Widget Key.
			$key = $widget['widget_name'];

			// Get Page Data.
			$pages[ $key ] = array(
				'name' => $widget['widget_title'],
				'icon' => $widget['widget_icon']
			);

		}
		return $pages;
	}

	/**
	 * # Menu Content
	 */
	function get_menu( $args ) {

		// Get Menu.
		$menu = $args['menu_list'];

		// Get Current Page.
		$current = bp_current_action();

		// Get Current Widget Name.
		if ( 'widgets-settings' == bp_current_component() ) {
			$current_widget = bp_current_action() && bp_current_action() != bp_current_component() ? bp_current_action() : $menu[0]['widget_name'];
			$menu = $this->convert_widgets_to_pages( $menu );
		} elseif ( 'edit' == $current ) {

	        // Get Widget Name.
	        $current_widget = 'edit/group/' . bp_get_current_profile_group_id();

		} else {
			$current_widget = $current;	
		}

	    // Get Buddypress Variables.
	    $bp = buddypress();

	    // Get Tab Navigation  Menu
	    $account_nav = $bp->members->nav->get_secondary( array( 'parent_slug' => bp_current_component() ) );


	    echo "<div class='account-menus'><div class='yz-menu-head'>";
	    echo "<h2>{$args['menu_title']}</h2><i class='fas fa-caret-up'></i>";
	    echo "</div><ul>";

	    // Get Menu.
		foreach ( $account_nav as $page ) {

			// Get Page Slug.
			$slug = $page['slug'];

			// Hide Tab if user has no access
			if ( empty( $page['user_has_access'] ) || 'edit' == $slug  ) {
				continue;
			}

			// Get Menu Data.
			$menu_data = isset( $menu[ $slug ] ) ? $menu[ $slug ] : null;

			// Get Menu Class Name.	
			$menu_class = ( $current_widget == $slug ) ? 'class="yz-active-menu"' : null;

			// Get Page Url.
			if ( isset( $page['group_slug'] ) ) {
				$page_url = yz_get_profile_settings_url( $page['group_slug'] );
			} elseif ( 'settings' == $args['slug'] ) {
				$page_url = yz_get_settings_url( $slug );
			} elseif ( 'profile' == $args['slug'] ) {
				$page_url = yz_get_profile_settings_url( $slug );
			} elseif ( 'widgets-settings' == $args['slug'] ) {
				$page_url = yz_get_widgets_settings_url( $slug );
			}

			$icon = isset( $menu_data['icon'] ) ? $menu_data['icon'] : 'gear';

			$icon = apply_filters( 'yz_account_menu_icon', $icon, $slug );

			echo '<li>';
			echo '<i class="'. $icon. '"></i>';
			echo "<a $menu_class href='$page_url'>{$page['name']}</a>";
			echo '</li>';

		}

	    echo '</ul></div>';

	}

	/**
	 * # Get Custom Widgets Menu List
	 */
	function custom_widgets_menus() {

		// Get Custom Widgets
		$custom_widgets = yz_options( 'yz_custom_widgets' );

		if ( empty( $custom_widgets ) ) {
			return false;
		}

		// Create new Array.
		$wg_menu_list = array();

		foreach ( $custom_widgets as $widget => $data ) {

        	// Check if Custom Widget have at least one field.
			if ( ! yz_check_custom_widget( $widget ) ) {
				continue;
			}

			// Prepare Menu item Then Add it to menu List
			$wg_menu_list[] = array(
				'widget_name' 	=> $widget,
				'widget_title' 	=> $data['name'],
				'widget_icon' 	=> $data['icon'],
				'menu_order'  	=> 1
			);

		}

		return $wg_menu_list;

	}

	/**
	 * # Settings Header.
	 */
	function settings_header() {

		// Get Image URL.
		$img_url = bp_core_fetch_avatar( 
			array(
				'item_id' => bp_displayed_user_id(),
				'type'	  => 'full',
				'html' 	  => false,
			)
		);

		// Get Data.
		$account_pages = $this->get_account_main_menu();
		$icon_url = yz_get_profile_settings_url( 'change-avatar' );
		$member_year = date( 'Y', strtotime( yz_data( 'user_registered' ) ) );

		?>

		<div class="yz-account-header">
			<div class="yz-account-img" style="background-image: url(<?php echo $img_url; ?>)">
				<a href="<?php echo esc_url( $icon_url ); ?>" class="yza-change-photo">
					<i class="fas fa-camera"></i>
				</a>
			</div>
			<div class="yz-account-head">
				<h2>@ <?php echo yz_data( 'user_login' ); ?></h2>
				<span><?php printf( esc_html__( 'member since %1$s', 'youzer' ), $member_year ); ?></span>
			</div>
			<ul>
				<?php foreach ( $account_pages as $page ) : ?>
				
				<?php 
					if ( isset( $page['visibility'] ) && false == $page['visibility'] ) {
						continue;
					}
				?>

				<li>
					<a href="<?php echo esc_url( $page['href'] ); ?>" class="<?php echo $page['class'] ?>">
						<div class="yza-icon"><i class="<?php echo $page['icon'] ?>"></i></div>
						<span class="yza-link-item"><?php echo $page['title']; ?></span>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php
	}

	/**
	 * Get Profile Settings
	 */
	function get_profile_settings() {

    	global $Youzer;
	    	
	    // Get Current Sub Page.
		$page = bp_current_action();
	    
	    switch ( $page ) {

			// Edit
			case 'edit':
	            $Youzer->widgets->basic_infos->group_fields();
				break;

	        case 'contact-info':
	            $Youzer->widgets->basic_infos->contact_infos();
				break;	      
	        
	        case 'profile-info':
	            	$Youzer->widgets->basic_infos->profile_infos();
				break;

			case 'change-avatar':
	            $Youzer->widgets->basic_infos->profile_picture();
				break;	      

			case 'change-cover-image':
	            $Youzer->widgets->basic_infos->profile_cover();
				break;	      

			case 'social-networks':
	            $Youzer->widgets->social_networks->settings();
				break;

			default:
				bp_get_template_part( 'members/single/plugins' );
				break;
	    }
	}

	/**
	 * Account Page Main Menu
	 */
	function get_account_main_menu() {

		// Init Menu
		$menu =  array();

		// View Profile Page
		$menu['view_profile'] = array(
			'icon' => 'fas fa-user',
			'class' => 'yza-view-profile',
			'title' => __( 'view profile', 'youzer' ),
			'href' => bp_core_get_user_domain( bp_displayed_user_id() ),
		);

		// Profile Settings Page
		$menu['profile_settings'] = array(
			'icon' => 'fas fa-user-circle',
			'class' => 'yza-profile-settings',
			'title' => __( 'profile settings', 'youzer' ),
			'href' => yz_get_profile_settings_url(),
		);

		// Account Settings Page
		$menu['account_settings'] = array(
			'icon' => 'fas fa-cogs',
			'href' => yz_get_settings_url(),
			'class' => 'yza-account-settings',
			'visibility' => bp_is_active('settings'),
			'title' => __( 'account settings', 'youzer' ),
		);

		// Widgets Settings Page
		$menu['widgets_settings'] = array(
			'icon' => 'fas fa-sliders-h',
			'class' => 'yza-widgets-settings',
			'href' => yz_get_widgets_settings_url(),
			'title' => __( 'widgets settings', 'youzer' ),
		);

		// Logout Page
		$menu['logout'] = array(
			'icon' => 'fas fa-power-off',
			'class' => 'yza-logout',
			'href' => wp_logout_url(),
			'title' => __( 'logout', 'youzer' ),
		);

		// Filter
		$menu = apply_filters( 'yz_account_page_main_menu', $menu );

		return $menu;

	}
	/**
	 * Get Account Settings
	 */
	function get_account_settings() {

    	global $Youzer;
	    	
	    // Get Current Sub Page.
		$page = bp_current_action();

	    switch ( $page ) {
  
			case 'capabilities':
	            $Youzer->widgets->basic_infos->user_capabilities();
				break;

			case 'delete-account':
	            $Youzer->widgets->basic_infos->delete_account();
				break;

			case 'account-privacy':
	            $Youzer->widgets->basic_infos->account_privacy();
				break;	      	      

			case 'change-password':
	            $Youzer->widgets->basic_infos->change_password();
				break;

			case 'export-data':
	            $Youzer->widgets->basic_infos->export_data();
				break;

			case 'notifications':
	            $Youzer->widgets->basic_infos->notifications_settings();
				break;

			default:            	
				bp_get_template_part( 'members/single/plugins' );
				break;
	    }
	}

	/**
	 * Get Widgets Settings
	 */
	function get_widgets_settings() {

    	global $Youzer;
	    	
	    // Get Current Sub Page.
		$page = bp_current_action();
	    
	    switch ( $page ) {

			case 'slideshow':
			case 'instagram':
			case 'portfolio':
			case 'services':
			case 'about_me':
			case 'project':
			case 'flickr':
			case 'skills':
			case 'quote':
			case 'video':
			case 'link':
			case 'post':
				
	            $Youzer->widgets->$page->settings();
				break;
				
			default:

	    		// Get Widgets Settings.
	    		$widgets_menu = $Youzer->widgets->get_settings_widgets();
	    		// Set First Widget Form Menu as Default.
	    		$default_widget = $widgets_menu[0]['widget_name'];
	    		// Print Widget Settings.
            	$Youzer->widgets->$default_widget->settings();
		    	
				break;	      

		}

	}
	
	/**
	 * # Settings Actions.
	 */
	function rename_tabs() {

		$bp = buddypress();

		if ( bp_is_active( 'settings' ) ) {

			// Remove Settings Profile, General Pages
			bp_core_remove_subnav_item( bp_get_settings_slug(), 'profile' );
			bp_core_remove_subnav_item( bp_get_settings_slug(), 'general' );


			// Change Notifications Title from "Email" to "Notifications".
			$bp->members->nav->edit_nav(
				array(
					'name' => __( 'Notifications', 'youzer' ),
					'position' => 1
				), 'notifications', bp_get_settings_slug()
			);

			// Set Notifications As Default Page. 
		    bp_core_new_nav_default (
		        array(
		            'subnav_slug'       => 'notifications',
		            'parent_slug'       => bp_get_settings_slug(),
		            'screen_function'   => 'yz_get_profile_settings_page'
		        )
		    );

		}

		// Set Profile-Info As Default Page. 
	    bp_core_new_nav_default (
	        array(
	            'subnav_slug'       => 'profile-info',
	            'parent_slug'       => bp_get_profile_slug(),
	            'screen_function'   => 'yz_get_profile_settings_page'
	        )
	    );

		// Remove Profile Public, Edit Pages
		bp_core_remove_subnav_item( bp_get_profile_slug(), 'public' );
		bp_core_remove_subnav_item( bp_get_profile_slug(), 'change-password' );

	}

	/**
	 * # Settings Actions.
	 */
	function settings_actions() {

		// Rename Account Tabs.
		add_action( 'bp_actions', array( &$this, 'rename_tabs' ), 10 );
		
		add_filter( 'yz_account_menu_icon', array( &$this, 'get_account_menu_icon' ), 10, 2 );
		//  Settings Scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'settings_scripts' ) );

		// Settings Sidebar Menu
		add_action( 'youzer_settings_menus', array( &$this, 'settings_header' ) );

		if ( bp_is_current_component( 'profile' ) ) {
			// Add User Settings Menu.
			add_action( 'youzer_settings_menus', array( &$this, 'profile_menu' ) );
			// Get User Settings.
			add_action( 'youzer_profile_settings', array( &$this, 'get_profile_settings' ) );
		}

		if ( bp_is_current_component( 'settings' ) ) {
			// Add Widgets Settings Menu.
			add_action( 'youzer_settings_menus', array( &$this, 'account_menu' ) );
			// Get Widgets Settings
			add_action( 'youzer_profile_settings', array( &$this, 'get_account_settings' ) );
		}

		if ( bp_is_current_component( 'widgets-settings' ) ) {
			// Add Widgets Settings Menu.
			add_action( 'youzer_settings_menus', array( &$this, 'widgets_menu' ) );
			// Get Widgets Settings
			add_action( 'youzer_profile_settings', array( &$this, 'get_widgets_settings' ) );
		}

		// Add Popup Dialog Message to show Errors.
		add_action( 'youzer_account_footer', 'yz_popup_dialog' );
	}

	/*
	 * # Account Scripts .
	 */
	function settings_scripts() {
	    
		// If Page is Profile Settings Call Related Scripts.
		if ( ! yz_is_account_page() ) {
			return false;
		}

	    // Get Page Name.
	    $page = bp_current_action();

        // Set Up Variables.
        $jquery 			= array( 'jquery' );
        $extra_args 		= array( 'jquery', 'yz-builder' );
        $skills_visible 	= yz_is_widget_visible( 'skills' );
        $project_visible 	= yz_is_widget_visible( 'project' );
        $services_visible 	= yz_is_widget_visible( 'services' );
        $portfolio_visible 	= yz_is_widget_visible( 'portfolio' );
        $slideshow_visible 	= yz_is_widget_visible( 'slideshow' );

        // Load Profile Settings CSS.
        wp_enqueue_style(
        	'yz-account-css',
        	YZ_PA . 'css/yz-account-style.min.css',
        	array( 'yz-style', 'yz-panel-css' )
        );

        // Load Profile Settings Script
        wp_enqueue_script(
        	'yz-account',
        	YZ_PA . 'js/yz-account.min.js',
        	array( 'jquery', 'yz-panel', 'yz-scrolltotop' ),
        	false, true
        );

        // Load Skills & ColorPicker Scripts
        if ( $page == 'skills' && $skills_visible ) {
        	wp_enqueue_script(
        		'yz-skills',
        		YZ_PA . 'js/yz-skills.min.js',
        		$extra_args, false, true
        	);
			// Load Color Picker Scripts
			$this->colorpicker_scripts();
        }

        // Load Portfolio Script
        if ( $page == 'portfolio' && $portfolio_visible ) {
        	wp_enqueue_script(
        		'yz-portfolio',
        		YZ_PA . 'js/yz-portfolio.min.js',
        		$extra_args, false, true
        	);
        }

        // Load Slideshow Script
        if ( $page == 'slideshow' && $slideshow_visible ) {
        	wp_enqueue_script(
        		'yz-slideshow',
        		YZ_PA . 'js/yz-slideshow.min.js',
        		$extra_args, false, true
        	);
        }

	    // Load Tags Editor Script
		if ( $page == 'project' && $project_visible ) {
	        wp_enqueue_script( 'yz-ukaitags' );
        }

	    // Load Bp Uploader Script
		if ( $page == 'change-avatar' ) {
	        wp_enqueue_style( 'yz-bp-uploader' );
        }

	    // Load Bp Uploader Script
		if ( $page == 'change-cover-image' ) {			
			// Cover Image Uploader Script.
	        wp_enqueue_style( 'yz-bp-uploader' );
			bp_attachments_enqueue_scripts( 'BP_Attachment_Cover_Image' );
        }

        // Load Services & IconPicker Scripts.
        if ( $page == 'services' && $services_visible ) {
        	wp_enqueue_script(
        		'yz-services',
        		YZ_PA . 'js/yz-services.min.js',
        		array( 'jquery', 'yz-builder', 'yz-iconpicker' ),
        		false, true
        	);
        	wp_enqueue_style( 'yz-iconpicker' );
    	}

	}

	/*
	 * # ColorPicker Scripts .
	 */
	function colorpicker_scripts() {

	    wp_enqueue_style( 'wp-color-picker' );

	    wp_enqueue_script(
	        'iris',
	        admin_url( 'js/iris.min.js' ),
	        array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
	        false,
	        1
	    );

	    wp_enqueue_script(
	        'wp-color-picker',
	        admin_url( 'js/color-picker.min.js' ),
	        array( 'iris' ),
	        false,
	        1
	    );

	    $colorpicker_translate = array(
	        'clear' 		=> __( 'Clear', 'youzer' ),
	        'defaultString' => __( 'Default', 'youzer' ),
	        'pick' 			=> __( 'Select Color', 'youzer' ),
	        'current' 		=> __( 'Current Color', 'youzer' ),
	    );

	    wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_translate );

	}

	/**
	 * Unlink Provider Account.
	 */
	function unlink_provider_account() {

		// Hook.
		do_action( 'yz_before_account_unlink_provider' );

		// Check Ajax Referer.
		check_ajax_referer( 'yz-unlink-provider-account', 'security' );

		// Get Data.
		$data = $_POST;
		
		// Get User ID.
		$user_id = bp_displayed_user_id();

		// Get Data.
		$provider = isset( $_POST['provider'] ) ? $_POST['provider'] : null;

		// Get Access Token ID.
		$option_id = 'wg_' . $provider . '_account_token';

		// Delete Token.
		$delete_token = delete_user_meta( $user_id, $option_id );

		if ( $delete_token ) {
			
			// Delete Account infos.
			delete_user_meta( $user_id, 'wg_' . $provider . '_account_user_data' );

			$data['action'] = 'done';
			$data['msg'] = __( 'user account is unlinked successfully', 'youzer' );

			do_action( 'yz_after_unlinking_provider_account', $user_id, $provider );

		} else {

			$data['error'] = __( "we couldn't unlink the account, please try again !", 'youzer' );

		}

		die( json_encode( $data ) );

	}

}