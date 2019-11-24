<?php

class Youzer_Profile {

	function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
	}
	
	/**
	 * Init Profile.
	 */
	function init() {
	
		if ( ! bp_is_user() ) {
			return false;
		}

		// Get Profile Layout.
		$profile_layout = yz_get_profile_layout();

		// Get Navbar Layout
		$navbar_layout = yz_options( 'yz_vertical_layout_navbar_type' );

		// Load Profile Scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'profile_scripts' ) );

		// Profile Navbar Content
		if ( yz_is_wild_navbar_active() ) {
			add_action( 'youzer_profile_before_header', array( &$this, 'navbar' ) );
		} else {
			add_action( 'youzer_profile_navbar', array( &$this, 'navbar' ) );
		}

		// Profile Main Content
		add_action( 'yz_profile_main_content', array( &$this, 'profile_main_content' ) );

		// Profile Sidebar Content.
		if ( 'yz-vertical-layout' == $profile_layout ) {
			add_action( 'youzer_profile_sidebar', array( &$this, 'sidebar_widgets' )  );
		} elseif ( 'yz-horizontal-layout' == $profile_layout ) {
			add_action( 'yz_profile_sidebar', array( &$this, 'sidebar_widgets' )  );
		}

	}

	/**
	 * # Navbar Menu.
	 */
	function navbar() {
   		
   		if ( yz_is_404_profile() ) {
	    	return false;
	    }
		
		global $Youzer;
		
		// Get Navbar Options.
		$navbar_effect = yz_options( 'yz_navbar_load_effect' );

		// Get Navbar Data.
		$navbar_class = $this->get_navbar_class();
		$navbar_data  = $Youzer->widgets->get_loading_effect( $navbar_effect );

		echo "<nav id='yz-profile-navmenu' class='$navbar_class' $navbar_data>";
		echo '<div class="yz-inner-content">';

		// Get Toogle Menu Code.
		$toogle_menu = '<div class="yz-open-nav"><button class="yz-responsive-menu"><span>toggle menu</span></button></div>';

		$toogle_menu = apply_filters( 'yz_profile_navbar_toggle_menu', $toogle_menu );

		echo $toogle_menu;

		// Get Primary Navigation Menu
		yz_profile_navigation_menu();

	    // Get Account Settings
		yz_account_settings_menu();
		
		echo '</div></nav>';

	}

	/**
	 * # Navbar Class.
	 */
	function get_navbar_class() {

		// Create Empty Array.
		$navbar_class = array();

		// Get Options.
		$header_layout = yz_options( 'yz_header_layout' );
		$icons_style = yz_options( 'yz_navbar_icons_style' );
		$navbar_effect = yz_options( 'yz_navbar_load_effect' );

		// Add Header Main Class
		$navbar_class[] = youzer()->widgets->get_loading_effect( $navbar_effect, 'navbar' );

		// Get Icons Style
		$navbar_class[] = 'yz-' . $icons_style;
		
		// Add a class depending on another one.
		if ( 'hdr-v2' == $header_layout || 'hdr-v7' == $header_layout ) {
			$navbar_class[] = 'yz-boxed-navbar';
		}
		
		// Add Boxed Navbar Class.
		if ( yz_is_wild_navbar_active() ) {	
			$navbar_class[] = 'yz-boxed-navbar';
		}

	 	// Return Class Name.
		return yz_generate_class( $navbar_class );
	}

	/**
	 * # Profile Main Content.
	 */
	function profile_main_content() {

		if ( yz_is_404_profile() ) {
			$this->profile_404();
			return false;
		}

        // Hide sidebar if profile is private.
        if ( ! yz_display_profile() ) {
            yz_private_account_message();
            return false;
        }

		?>

		<div class="yz-main-column">
			<div class="yz-column-content">
				<?php do_action( 'yz_profile_main_column' ); ?>
			</div>
		</div>

		<div class="yz-sidebar-column">
			<div class="yz-column-content">
				<?php do_action( 'yz_profile_sidebar' ); ?>
			</div>
		</div>

		<!-- Scroll to top -->
		<?php $this->get_scroll_to_top(); ?>

		<?php do_action( 'yz_profile_content' ); ?>

		<?php
	}

	/**
	 * # Scroll to top .
	 */
	function get_scroll_to_top() {
		if ( 'on' == yz_options( 'yz_display_scrolltotop' ) ) {
			yz_scroll_to_top();
		}
	}

	/**
	 * # Sidebar Content .
	 */
	function sidebar_widgets() {

		if ( ! yz_display_profile() ) {
            return false;
        }

		// Get Overview Widgets
		$sidebar_widgets = yz_options( 'yz_profile_sidebar_widgets' );

		// Filter 
		$sidebar_widgets = apply_filters( 'yz_profile_sidebar_widgets', $sidebar_widgets );
		
		echo '<div class="yz-profile-sidebar">';

		// Get Widget Content.
		youzer()->widgets->get_widget_content( $sidebar_widgets );

		echo '</div>';
	}

	/*
	 * # Profile Scripts .
	 */
	function profile_scripts() {

		// Hide Scripts if page is account settings or profile settings.
		if ( yz_is_account_page() ) {
			return false;
		}

	    if ( ! bp_current_component() && ! bp_is_user() ) {
	    	return false;
	    }

        global $Youzer;

        // Get Options
        $use_effects = yz_options( 'yz_use_effects' );

        // Set Up Variable
        $jquery = array( 'jquery' );

        // Load Header Styles.
    	wp_enqueue_style( 'yz-headers' );

        // Load Profile Schemes.
        wp_enqueue_style( 'yz-schemes' );

        // Load Profile Style
        wp_enqueue_style( 'yz-profile' );

        // Load Profile Script.
        $profile_args = array( 'jquery', 'jquery-effects-fade' );
	    wp_enqueue_script( 'yz-profile', YZ_PA . 'js/yz-profile.min.js', $profile_args, $Youzer->version, true  );

        // If Effects are enabled active effects scripts.
        if ( 'on' == $use_effects ) {
            // Profile Animation CSS
            wp_enqueue_style( 'yz-animation', YZ_PA . 'css/animate.min.css', $Youzer->version );
	        // Load View Port Checker Script
	        wp_enqueue_script( 'yz-viewchecker', YZ_PA . 'js/yz-viewportChecker.min.js', $jquery, $Youzer->version, true  );
        }

        // Get Data
        $instagram 	= yz_data( 'wg_instagram_account_id' );
        $flickr 	= yz_data( 'wg_flickr_account_id' );
        $portfolio 	= yz_data( 'youzer_portfolio' );
        $slideshow 	= yz_data( 'youzer_slideshow' );

        // Get Visibility Data.
    	$is_slideshow_visible = yz_is_widget_visible( 'slideshow' );
        $posts_tab_visible 	  = yz_options( 'yz_display_posts_tab' );
        $comments_tab_visible = yz_options( 'yz_display_comments_tab' );

        if ( ( $slideshow && $is_slideshow_visible && count( $slideshow) > 1 ) || bp_is_current_component( 'activity' ) ) {
            // Load Carousel CSS and JS.
            wp_enqueue_style( 'yz-carousel-css', YZ_PA . 'css/owl.carousel.min.css', $Youzer->version );
            wp_enqueue_script( 'yz-carousel-js', YZ_PA . 'js/owl.carousel.min.js', $jquery, $Youzer->version, true );
            wp_enqueue_script( 'yz-slider', YZ_PA . 'js/yz-slider.min.js', $jquery, $Youzer->version, true );
        }
        
        // Load Posts & Comments Ajax Pagination Script.
        if (
        	( 'on' == $posts_tab_visible && yz_is_user_have_posts() && yz_check_pagination( 'posts' ) && bp_is_current_component( 'posts' ) )
        	||
        	( 'on' == $comments_tab_visible && yz_is_user_have_comments() && yz_check_pagination( 'comments' ) && bp_is_current_component( 'comments' ) )
        ) {

	        wp_localize_script( 'yz-pagination', 'ajaxpagination',
	            array(
	                'ajaxurl' 	 => admin_url( 'admin-ajax.php' ),
	                'query_vars' => json_encode( array( 'yz_user' => yz_profileUserID() ) )
	            )
	        );

	    	// Call Ajax-Pagination Script
	        wp_enqueue_script( 'yz-pagination' );
		}

        // Load Scroll to top Script.
		if ( 'on' == yz_options( 'yz_display_scrolltotop' ) ) {
			wp_enqueue_script( 'yz-scrolltotop' );
		}

	}

	/**
	 * #  404 Profile .
	 */
	function profile_404() {

		?>

		<div class="yz-box-404">
			<h2>404</h2>
			<p><?php echo esc_textarea( yz_options( 'yz_profile_404_desc' ) ); ?></p>
			<a class="yz-box-button" href="<?php echo home_url(); ?>">
				<?php echo sanitize_text_field( yz_options( 'yz_profile_404_button' ) ); ?>
			</a>
		</div>

		<?php

	}

}