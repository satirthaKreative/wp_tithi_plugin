<?php

class Youzer_Tabs {

	protected $youzer;

    public function __construct() {

		global $Youzer;

    	$this->youzer = &$Youzer;

    	// Init Tabs.
		$this->overview  = new YZ_Overview_Tab();
		$this->comments  = new YZ_Comments_Tab();
		$this->custom 	 = new YZ_Custom_Tabs();
		$this->posts 	 = new YZ_Posts_Tab();
		$this->info 	 = new YZ_Info_Tab();
		$this->wall 	 = new YZ_Wall_Tab();

		// Call Tabs.
		add_action( 'yz_profile_main_column', array( &$this, 'get_tabs' ) );

		// Add New Profile Tabs
		add_action( 'bp_setup_nav', array( &$this, 'add_custom_tabs' ), 1 );
		add_action( 'bp_setup_nav', array( &$this, 'add_profile_tabs' ), 1 );
 
    }

	/**
	 * # Tab Core.
	 */
	function core( $args ) {

		// Get Tab Class Name.
	    $tab_name = $args['tab_name'];
	    $class_name = 'yz-' . $tab_name;

		?>

		<div class="yz-tab <?php echo $class_name; ?>">
			<?php $this->$tab_name->tab_content(); ?>
		</div>

		<?php

	}

	/**
	 * Add Custom Profile Tabs
	 */
	function add_custom_tabs() {
		
	    global $bp;

		// Get Custom Tabs.
		$custom_tabs = yz_options( 'yz_custom_tabs' );

		if ( empty( $custom_tabs ) ) {
			return false;
		}

		foreach ( $custom_tabs as $tab_id => $data ) {

			// Hide Tab For Non Logged-In Users.
			if ( 'false' == $data['display_nonloggedin'] && ! is_user_logged_in() ) {
				continue;
			}

			// Get Slug.
			$tab_slug = yz_get_custom_tab_slug( $data['title'] );

  			// Add New Tab.
  			bp_core_new_nav_item(
		        array( 
		            'position' => 100,
		            'slug' => $tab_slug, 
		            'name' => $data['title'], 
		            'default_subnav_slug' => $tab_slug,
		            'parent_slug' => $bp->profile->slug,
		            'screen_function' => 'yz_profile_custom_tab_screen', 
		            'parent_url' => bp_loggedin_user_domain() . "/$tab_slug/"
		        )
		    );
  		}

	}

	/**
	 * Add Profile Tabs
	 */
	function add_profile_tabs() {

	    global $bp;

		// Add Overview Tab.
	    bp_core_new_nav_item(
	        array( 
	            'position' => 1,
	            'slug' => 'overview', 
	            'default_subnav_slug' => 'overview',
	            'parent_slug' => $bp->profile->slug,
	            'name' => yz_options( 'yz_overview_tab_title' ), 
	            'screen_function' => 'yz_profile_overview_tab_screen', 
	            'parent_url' => bp_loggedin_user_domain() . '/overview/'
	        )
	    );
	    
		// Add Infos Tab.
	    bp_core_new_nav_item(
	        array( 
	            'position' => 3,
	            'slug' => 'info', 
	            'default_subnav_slug' => 'info',
	            'parent_slug' => $bp->profile->slug,
	            'name' => yz_options( 'yz_info_tab_title' ), 
	            'screen_function' => 'yz_profile_infos_tab_screen', 
	            'parent_url' => bp_loggedin_user_domain() . '/info/'
	        )
	    );
	    
		// Add Posts Tab.
	    bp_core_new_nav_item(
	        array( 
	            'position' => 4,
	            'slug' => 'posts', 
	            'default_subnav_slug' => 'posts',
	            'parent_slug' => $bp->profile->slug,
	            'name' => yz_options( 'yz_posts_tab_title' ), 
	            'screen_function' => 'yz_profile_posts_tab_screen', 
	            'parent_url' => bp_loggedin_user_domain() . '/posts/'
	        )
	    );

	    // Add Comments Tab.
	    bp_core_new_nav_item(
	        array( 
	            'position' => 5,
	            'slug' => 'comments', 
	            'parent_slug' => $bp->profile->slug,
	            'default_subnav_slug' => 'comments',
	            'name' => yz_options( 'yz_comments_tab_title' ), 
	            'screen_function' => 'yz_profile_comments_tab_screen', 
	            'parent_url' => bp_loggedin_user_domain() . '/comments/'
	        )
	    );

	    // Get Access.
		$access = bp_core_can_edit_settings();

	    // Profile Widgets Settings.
	    bp_core_new_nav_item(
	        array( 
	            'position' => 60,
	            'slug' => 'widgets-settings', 
	            'parent_slug' => $bp->profile->slug,
				'show_for_displayed_user' => $access,
	            'default_subnav_slug' => 'widgets-settings',
	            'name' => __( 'Widgets Settings', 'youzer' ), 
	            'screen_function' => 'yz_profile_widgets_settings_tab_screen', 
	            'parent_url' => bp_loggedin_user_domain() . '/widgets-settings/'
	        )
	    );

	    // Add My Profile Page.
	    bp_core_new_nav_item(
	        array( 
	            'position' => 200,
	            'slug' => 'yz-home', 
	            'parent_slug' => $bp->profile->slug,
				'show_for_displayed_user' => $access,
	            'default_subnav_slug' => 'yz-home',
	            'name' => __( 'My Profile', 'youzer' ), 
	            'parent_url' => bp_loggedin_user_domain() . '/yz-home/'
	        )
	    );

	    do_action( 'yz_add_new_profile_tabs' );

	}

	/**
	 * Get Tabs Content
	 */
	public function get_tabs() {

		// Show Private Account Message.
		if ( ! yz_display_profile() ) {
			yz_private_account_message();
			return false;
		}

		// If page is single activity show single activity template.
	    if ( bp_is_single_activity() ) {
	        yz_get_single_wall_post();
	        return;
	    }

		/**
		 * Fires before the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_body' );

		if ( bp_is_user_front() ) :
			bp_displayed_user_front_template_part();

		elseif ( bp_is_user_activity() ) :
			bp_get_template_part( 'members/single/activity' );

		elseif ( bp_is_user_blogs() ) :
			bp_get_template_part( 'members/single/blogs'    );

		elseif ( bp_is_user_friends() ) :
			bp_get_template_part( 'members/single/friends'  );

		elseif ( bp_is_user_groups() ) :
			bp_get_template_part( 'members/single/groups'   );

		elseif ( bp_is_user_messages() ) :
			bp_get_template_part( 'members/single/messages' );

		elseif ( bp_is_user_profile() ) :
			bp_get_template_part( 'members/single/profile'  );

		elseif ( bp_is_user_notifications() ) :
			bp_get_template_part( 'members/single/notifications' );

		elseif ( bp_is_user_settings() ) :
			bp_get_template_part( 'members/single/settings' );


		// If nothing sticks, load a generic template
		else :
			bp_get_template_part( 'members/single/plugins'  );

		endif;

		/**
		 * Fires after the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_member_body' );

	}
	
}