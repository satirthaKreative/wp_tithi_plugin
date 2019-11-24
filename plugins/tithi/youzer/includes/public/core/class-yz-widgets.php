<?php

class Youzer_Widgets {

	function __construct() {

    	// Init Widgets.
		$this->custom_widgets 	= new YZ_Custom_Widgets();
		$this->groups 			= new YZ_Groups_Widget();
		$this->recent_posts 	= new YZ_Recent_Posts();
		$this->custom_infos 	= new YZ_Custom_Infos();
		$this->user_balance 	= new YZ_User_Balance();
		$this->basic_infos 		= new YZ_Basic_Infos();
		$this->user_badges 		= new YZ_User_Badges();
		$this->user_tags 		= new YZ_User_Tags();
		$this->portfolio 		= new YZ_Portfolio();
		$this->instagram 		= new YZ_Instagram();
		$this->slideshow 		= new YZ_Slideshow();
		$this->info_box 		= new YZ_Info_Box();
		$this->social_networks 	= new YZ_Networks();
		$this->about_me 		= new YZ_About_Me();
		$this->services 		= new YZ_Services();
		$this->friends			= new YZ_Friends();
		$this->project 			= new YZ_Project();
		$this->reviews 			= new YZ_Reviews();
		$this->flickr 			= new YZ_Flickr();
		$this->skills 			= new YZ_Skills();
		$this->video 			= new YZ_Video();
		$this->quote 			= new YZ_Quote();
		$this->link 			= new YZ_Link();
		$this->post 			= new YZ_Post();
		$this->ad 				= new YZ_Ads();

		// Information Boxes
	 	$this->infos_boxes = array( 'address', 'phone', 'email', 'website' );

	 	// Filters.
	 	add_filter( 'yz_display_profile_widget_title', array( &$this, 'display_widgets_title_for_profile_owner' ), 10, 2 );

	}
	
	/**
	 * Widgets List
	 */
	function widgets() {
		// Widget Arguments
		$widgets = array(
			'post'				=> 	$this->post->args(),
			'link' 				=>	$this->link->args(),
			'quote'				=>	$this->quote->args(),
			'video' 			=>	$this->video->args(),
			'flickr'			=> 	$this->flickr->args(),
			'skills'			=>	$this->skills->args(),
			'groups' 			=>  $this->groups->args(),
			'friends' 			=>  $this->friends->args(),
			'project'			=>	$this->project->args(),
			'reviews'			=> 	$this->reviews->args(),
			'about_me'			=> 	$this->about_me->args(),
			'services'			=>	$this->services->args(),
			'user_tags'			=> 	$this->user_tags->args(),
			'portfolio'			=> 	$this->portfolio->args(),
			'slideshow'			=> 	$this->slideshow->args(),
			'instagram'			=>	$this->instagram->args(),
			'instagram'			=>	$this->instagram->args(),
			'user_badges'		=>	$this->user_badges->args(),
			'user_balance'		=>	$this->user_balance->args(),
			'recent_posts'		=>	$this->recent_posts->args(),
			'phone'				=>	$this->info_box->phone_args(),
			'email'				=>	$this->info_box->email_args(),
			'social_networks'	=>  $this->social_networks->args(),
			'website'			=>	$this->info_box->website_args(),
			'address'			=>	$this->info_box->address_args(),
		);

		return apply_filters( 'yz_profile_widgets_args', $widgets );
	}

	/**
	 * # Widget Core
	 */
	function check_widget_content( $widget_name, $data ) {

		$meta_options = array(
			'portfolio', 'services', 'skills', 'quote', 'link', 'video',
			'post', 'about_me', 'address', 'email', 'phone', 'website','project'
		);

		if ( in_array( $widget_name, $meta_options )  ) {
			if ( ! yz_data( $data ) ) {
				return false;
			}
		} elseif ( 'slideshow' == $widget_name ) {
			// Get Slides.
			$slides = yz_data( $data );
			if ( ! $slides ) {
				return false;
			}
		} elseif ( 'social_networks' == $widget_name ) {
			if ( ! is_user_have_networks() ) {
				return false;
			}
		} elseif ( 'recent_posts' == $widget_name ) {
			$user_post_count = yz_get_user_posts_nbr();
			if ( $user_post_count == 0 ) {
				return false;
			}
		} elseif ( 'friends' == $widget_name ) {

	         if ( ! bp_is_active( 'friends' ) ) {
	         	return false;
	         }

	        // Get Friends Number
			$friends_nbr = friends_get_total_friend_count( yz_profileUserID() );

			if ( $friends_nbr == 0 ) {
				return false;
			}

		} elseif ( 'groups' == $widget_name ) {

	         if ( ! bp_is_active( 'groups' ) ) {
	         	return false;
	         }

	        // Get Groups Number
			$groups_nbr = yz_get_group_total_for_member( yz_profileUserID() );

			if ( $groups_nbr == 0 ) {
				return false;
			}

		} elseif ( 'user_tags' == $widget_name ) {

			if ( ! yz_is_user_have_user_tags() ) {
				return false;
			}

		} elseif ( 'flickr' == $widget_name ) {

			global $Youzer;

			// Get Flickr Account.
			$flickr_id = yz_data( 'wg_flickr_account_id' );
			
			if ( empty( $flickr_id ) ) {
				return false;
			}

			// Get Flickr Data
			$flickr_data = $Youzer->widgets->flickr->get_flickr_photos( $flickr_id );

			if ( empty( $flickr_data ) ) {
				return false;
			}

		} elseif ( 'ad' == $widget_name ) {
			return true;
		}

		return apply_filters( 'yz_profile_widget_visibility', true, $widget_name );
	}

	/**
	 * # Widget Core
	 */
	function yz_widget_core( $args ) {

   		// if Page = 404 to do not show anything.
    	if ( yz_is_404_profile() ) {
    		return false;
    	}

		// Init variables.
		$main_data = isset( $args['main_data'] ) ? $args['main_data'] : true;

		// Check Content Existence.
		$display_widget = $this->check_widget_content( $args['widget_name'], $main_data );

		// if there's no content exit.
		if ( false === $display_widget ) {
			return false;
		}

		// Get Widget Data.
	    $class_name 	  = $this->yz_widget_class_name( $args );
		$display_icon 	  = yz_options( 'yz_display_wg_title_icon' );
		$display_title 	  = isset( $args['display_title'] ) ? $args['display_title'] : 'on';
		$function_options = isset( $args['function_options'] ) ? $args['function_options'] : null;

		// Display tilte if value equal true also.
		if ( 'true' == $display_title ) {
			$display_title = 'on';
		}
	    // Get loading effect.
	    $load_effect = isset( $args['load_effect'] ) ? $args['load_effect'] : null;

	    // Get Widget Data.
	 	$widget_data = $this->get_loading_effect( $load_effect );
	 	$widget_name = $args['widget_name'];
		$widget_icon = isset( $args['widget_icon'] ) ? $args['widget_icon'] : null;

	 	// If widget is an info box change the widget name to it.
	 	if ( in_array( $widget_name, $this->infos_boxes ) ) {
	 		$widget_name = 'info_box';
	 	}

		// Filter.
		$display_title = apply_filters( 'yz_display_profile_widget_title', $display_title, $widget_name );

		?>

		<div class="yz-widget <?php echo $class_name ?>" <?php echo $widget_data; ?>>

			<div class="yz-widget-main-content">

				<?php if ( 'on' == $display_title ) : ?>
				<div class="yz-widget-head">
					<h2 class="yz-widget-title">
						<?php if ( 'on' == $display_icon ) : ?>
							<i class="<?php echo $widget_icon; ?>"></i>
						<?php endif; ?>
						<?php echo $args['widget_title']; ?>
					</h2>
					<?php if ( bp_core_can_edit_settings() && ! in_array(  $widget_name, $this->widgets_without_settings() ) ) : ?>
					<a href="<?php echo apply_filters( 'yz_profile_widgets_edit_link', yz_get_widgets_settings_url( $widget_name ), $widget_name ); ?>"><i class="far fa-edit yz-edit-widget"></i></a>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<div class="yz-widget-content">
					<?php
						if ( isset( $this->$widget_name ) ) {
							$this->$widget_name->widget( $function_options ); 
						}
					?>
				</div>

			</div>

		</div>

		<?php

	}

	/**
	 * # Widget Class Name
	 */
	function yz_widget_class_name( $args ) {

		// Create Empty Array.
		$widget_class = array();

		// Get Widget Options.
		$title_icon_bg = yz_options( 'yz_use_wg_title_icon_bg' );

		// Prepare Class Name
	    $widget_class[] = 'yz-' . $args['widget_name'];
	    $load_effect 	= isset( $args['load_effect'] ) ? $args['load_effect'] : null;
	    $widget_class[] = $this->get_loading_effect( $load_effect, 'class' );
		$display_title 	= isset( $args['display_title'] ) ? $args['display_title'] : 'on';

	    // Add title class.
		if ( 'off' == $display_title ) {
			$widget_class[] = "without-title";
		}

	    // Add background class.
	    $no_background = array( 'yz_widget_infos_box', 'ad' );
	    if ( ! in_array( $args['widget_name'], $no_background ) ) {
	    	$widget_class[] = 'yz-white-bg';
	    }

		// Get AD class.
	    if ( 'ad' == $args['widget_name'] ) {
			if ( 'true' == $this->is_sponsored_ad( $args['function_options'] ) ) {
				$widget_class[] = 'yz-white-bg';
			} else {
				$widget_class[] = 'yz-no-bg';
			}
	    }

	    // Title Icon Style.
	    if ( 'on' == $title_icon_bg ) {
			$widget_class[] = 'yz-wg-title-icon-bg';
	    }

		// Return Widget Class Name
		return yz_generate_class( $widget_class );
	}

	/**
	 * Get Loading Effect
	 */
	function get_loading_effect( $load_effect, $data_type = 'data' ) {

		// Get effect options.
		$use_effects = yz_options( 'yz_use_effects' );

		// Check if it's allowed to use loading effects.
		if ( 'on' != $use_effects ) {
			return false;
		}

		// Create Empty Array.
		$effects = array();

		// Use effect class.
		if ( 'class' == $data_type || 'navbar' == $data_type ) {
			return "yz_effect";
		} elseif ( $data_type == 'data' ) {
			// Get effects data value.
			if ( ! empty( $load_effect ) ) {
				return "data-effect='$load_effect'";
			} else {
				return 'data-effect="fadeIn"';
			}
		}

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
	 * Get Widgets Without Front-end Settings
	 */
	function widgets_without_settings() {
		$widgets = array(
			'friends', 'groups', 'social_networks', 'recent_posts', 'phone', 'address', 'email', 'website', 'user_tags', 'user_balance', 'user_badges', 'reviews'
		);
		return apply_filters( 'yz_profile_without_front_end_settings', $widgets );
	}

	/**
	 * Get Settings Widgets
	 */
	function get_settings_widgets() {

		$widgets = $this->widgets();

		// Unset Widgets that have no settings.
		foreach ( $this->widgets_without_settings() as $widget ) {
			unset( $widgets[ $widget ] );
		}

		// if user have no posts don't show the post form.
		if ( 0 == yz_get_user_posts_nbr() ) {
			unset( $widgets['post'] );
		}

		// Unset Invisible Widgets.
		foreach ( $widgets as $widget_name => $widget_data ) {
			if ( ! yz_is_widget_visible( $widget_name ) ) {
				unset( $widgets[ $widget_name ] );
			}
		}

		// Sort array numerically.
		usort( $widgets, 'yz_sortByMenuOrder' );

		return $widgets;
	}

	/**
	 * Get AD Class
	 */
	function is_sponsored_ad( $ad_name ) {
		$get_ads = yz_options( 'yz_ads' );
		$is_sponsored = $get_ads[ $ad_name ]['is_sponsored'];
		return $is_sponsored;
	}

	/**
	 * Get Widget Content.
	 */
	function get_widget_content( $widgets ) {

		// Filter
		$widgets = apply_filters( 'yz_get_widgets_content', $widgets );

		// Display Widgets
		foreach ( $widgets as $widget ) {

			$widget_name   = key( $widget );
			$widget_status = $widget[ $widget_name ];

			if ( 'visible' != $widget_status ) {
				continue;
			}

			if ( yz_is_ad_widget( $widget_name ) ) {
				// Get Ad Args.
				$widget_args = $this->ad->get_args( $widget_name );
			} elseif ( yz_is_custom_widget( $widget_name ) ) {
				// Get Custom Widget Data.
				$widget_args = $this->custom_widgets->get_args( $widget_name );
			} else {
				// Get All Youzer Widgets
				$youzer_widgets = $this->widgets();
				// Get Widget Args.
				$widget_args = $youzer_widgets[ $widget_name ];
			}

			$this->yz_widget_core( $widget_args );

		}
	}

	/**
	 * Display Widgets Title For Profile Owner.
	 */
	function display_widgets_title_for_profile_owner( $show, $widget_name ) {

		$widgets = array( 'quote', 'link', 'post', 'project' );

		if ( in_array( $widget_name, $widgets ) ) {
			return 'on';
		} 

		return $show;
	}

}