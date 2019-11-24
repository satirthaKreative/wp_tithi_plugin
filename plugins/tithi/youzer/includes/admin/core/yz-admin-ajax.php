<?php

class Youzer_Admin_Ajax {
 
	function __construct() {

		// Save Settings
		add_action( 'wp_ajax_youzer_admin_data_save',  array( &$this, 'save_settings' ) );

		// Reset Settings
		add_action( 'wp_ajax_youzer_reset_settings',  array( &$this, 'reset_settings' ) );

	}

	/**
	 * # Save Settings With Ajax.
	 */
	function save_settings() {

		check_ajax_referer( 'youzer-settings-data', 'security' );

		do_action( 'yz_before_panel_save_settings' );

		$data = $_POST;

		unset( $data['security'], $data['action'] );

		global $Youzer;

	    // Youzer Panel options
	    $options = isset( $data['youzer_options'] ) ? $data['youzer_options'] : null;
	   
	    // Save Options
	    if ( $options ) {

	    	// Get Active Styles.
	    	$active_styles = yz_options( 'yz_active_styles' );

	    	// Get All Youzer Styles 
	    	$all_styles = $Youzer->styling->get_all_styles( 'ids' );

		    foreach ( $options as $option => $value ) {
		    	
		    	// Get Option Value
		        if ( ! is_array( $value ) ) {
		        	$the_value = stripslashes( $value );
		        } else {
		        	$the_value = $value;
		        }

		        // Save Option or Delete Option if Empty
		        if ( isset( $option ) ) {
		        	update_option( $option, $the_value, true );
		        } else {
		        	delete_option( $option );
		        }

		        // Update Active Style.
		        if ( in_array( $option, $all_styles ) ) {

		        	// Get Option Key.
		        	$option_key = array_search( $option, $all_styles );

		        	if ( isset( $the_value['color'] ) && empty( $the_value['color'] ) ) {
		        		unset( $active_styles[ $option_key ] );
		        		continue;
		        	}

		        	if ( empty( $the_value ) ) {
		        		unset( $active_styles[ $option_key ] );
		        		continue;
		        	}

		        	$active_styles[] = $option;
		        }

		    }

		    if ( ! empty( $active_styles ) ) {

		    	// Get Unique Values.
		    	$active_styles = array_unique( $active_styles );

		    	// Save New Styles.
		    	update_option( 'yz_active_styles', array_filter( $active_styles ), true );

		    }
		    
	    }

		// Save "Disable Delete Accounts"
		$disable_account_deletion = 'bp-disable-account-deletion';
		
        if ( isset( $options[ $disable_account_deletion ] ) ) {
	    	if ( 'on' == $options[ $disable_account_deletion ] ) {
	    		update_option( $disable_account_deletion, 0 );
	    	} else {
	    		update_option( $disable_account_deletion, 1 );
	    	}
	    }

		// Save Registration Value
		$register_opts = 'users_can_register';
        if ( isset( $options[ $register_opts ] ) ) {
	    	if ( 'on' == $options[ $register_opts ] ) {
	    		update_option( $register_opts, 1 );
	    	} else {
	    		update_option( $register_opts, 0 );
	    	}
	    }

	    // If User want to save Youzer Plugin Pages.
	    if ( isset( $data['youzer_pages'] ) ) {
		    $this->save_youzer_pages( $data['youzer_pages'] );
	    }

	    // If User want to save Logy.
	    if ( isset( $data['logy_pages'] ) ) {
		    $this->save_logy_pages( $data['logy_pages'] );
	    }

	    // Save Ads.
		if ( isset( $data['yz_ads_form'] ) ) {
			$yz_ads = $data['yz_ads'];
			$this->save_ads( $yz_ads );
		}

	    // Save Social Networks.
	    if ( isset( $data['yz_networks_form'] ) ) {
     		// Social Networks options
	    	$yz_sn = $data['yz_networks'];
		    // Update Options
	    	$this->save_social_networks( $yz_sn );
	    }

	    // Save Custom Widgets.
	    if ( isset( $data['yz_custom_widgets_form'] ) ) {
     		// Custom Widgets options
	    	$yz_cw = $data['yz_custom_widgets'];
		    // Update Options
	    	$this->save_custom_widgets( $yz_cw );
	    }

	    // Save Custom Widgets.
	    if ( isset( $data['yz_custom_tabs_form'] ) ) {
     		// Custom Widgets options
	    	$yz_ct = $data['yz_custom_tabs'];
		    // Update Options
	    	$this->save_custom_tabs( $yz_ct );
	    }

	    // Save User Tags.
	    if ( isset( $data['yz_user_tags_form'] ) ) {
     		// User Tags Options
	    	$yz_ut = $data['yz_user_tags'];
		    // Update Options
	    	$this->save_user_tags( $yz_ut );
	    }

	    // Save Profile Structure.
	    if ( isset( $data['yz_profile_stucture'] ) ) {
	    	// Get Data
	    	$main_widgets    = $data['yz_profile_main_widgets'];
	    	$sidebar_widgets = $data['yz_profile_sidebar_widgets'];
	    	// Update Options
	    	update_option( 'yz_profile_main_widgets', $main_widgets, true );
	    	update_option( 'yz_profile_sidebar_widgets', $sidebar_widgets, true );
	    }

	    // Actions
	    do_action( 'yz_panel_save_settings', $data );

	   	die( '1' );

	}

	/**
	 * # Save Pages.
	 */
	function save_logy_pages( $logy_pages ) {

		// Get How much time page is repeated.
		$page_counts = array_count_values( $logy_pages );

		// if page is already used show error messsage.
		foreach ( $page_counts as $id => $nbr ) {
			if ( $nbr > 1 ) {
				die( __( 'You are using same page more than ones.', 'youzer' ) );
			}
		}

		// Update Pages in Database.
		$update_pages = update_option( 'logy_pages', $logy_pages );

		if ( $update_pages ) {
			foreach ( $logy_pages as $page => $id ) {
				// Update Option ID
				update_option( $page, $id );
			}
		}
	}

	/**
	 * # Save Social Networks.
	 */
	function save_social_networks( $networks ) {

		if ( empty( $networks ) ) {
			delete_option( 'yz_social_networks' );
			return false;
		}

    	$update_options = update_option( 'yz_social_networks', $networks, true );

		// Update Next Network ID
    	if ( $update_options ) {
			update_option( 'yz_next_snetwork_nbr', $this->get_next_ID( $networks, 'snetwork' ) );
    	}

	}

	/**
	 * # Save Custom Tabs.
	 */
	function save_custom_tabs( $tabs ) {

		if ( empty( $tabs ) ) {
			delete_option( 'yz_custom_tabs' );
			return false;
		}
		
		// Update Tabs.
    	$update_options = update_option( 'yz_custom_tabs', $tabs, true );

		// Update Next ID
    	if ( $update_options ) {
			update_option( 'yz_next_custom_tab_nbr', $this->get_next_ID( $tabs, 'custom_tab' ), true );
    	}

	}

	/**
	 * # Save User Tags.
	 */
	function save_user_tags( $tags ) {

		if ( empty( $tags ) ) {
			delete_option( 'yz_user_tags' );
			return false;
		}
		
		// Update Types.
    	$update_options = update_option( 'yz_user_tags', $tags, true );

		// Update Next ID
    	if ( $update_options ) {
			update_option(
				'yz_next_user_tag_nbr', $this->get_next_ID( $tags, 'user_tag' )
			);
    	}

	}

	/**
	 * # Save Ads.
	 */
	function save_ads( $ads ) {

		$yz_ads = array();

		if ( ! empty( $ads ) ) {
			foreach ( $ads as $ad => $data ) {
				$yz_ads[ $ad ] = $data;
			}
		}

		$Yz_Widgets = youzer()->widgets;

		// Update ads List.
    	$update_options = update_option( 'yz_ads', $yz_ads, true );

    	// If ADS not updated stop function right here.
		if ( ! $update_options ) {
			return false;
		} else {
			// Update Next Ad ID
			update_option( 'yz_next_ad_nbr', $this->get_next_ID( $yz_ads, 'ad' ) );
    	}

	    // Get Overview and Sidebar Widgets
	    $overview_wgs = yz_options( 'yz_profile_main_widgets' );
	    $sidebar_wgs  = yz_options( 'yz_profile_sidebar_widgets' );

	    // Merge Overview & Sidebar widgets
	    $all_widgets = array_merge( $overview_wgs, $sidebar_wgs );

	    // Get Ads Widgets
	    $ads_widgets = $Yz_Widgets->ad->get_ads_widgets( $all_widgets );

	    // Delete Removed ADS.
	    foreach ( $ads_widgets as $key => $ad_widget ) {

	        // if ad name is not found.
	        if ( ! isset( $yz_ads[ key ( $ad_widget ) ] ) ) {

	            if ( in_array( $ad_widget, $sidebar_wgs ) ) {
	                // if the removed ad in the sidebar remove it.
	                unset( $sidebar_wgs[ array_search( $ad_widget, $sidebar_wgs) ] );
	            } else {
	                // if the removed ad in the overview remove it.
	                unset( $overview_wgs[ array_search( $ad_widget, $overview_wgs) ] );
	            }

	        }

	    }

	    foreach ( $yz_ads as $ad => $data ) {
	        $new_ad = array( $ad => 'visible' );
	        if ( ! $Yz_Widgets->ad->is_key_exist( $all_widgets, $ad ) ) {
	        	$sidebar_wgs[] = $new_ad;
	        }
	    }

		// Update Overview & Sidebar Widgets.
		update_option( 'yz_profile_main_widgets', $overview_wgs, true );
		update_option( 'yz_profile_sidebar_widgets', $sidebar_wgs, true );

	}

	/**
	 * # Save Custom Widgets.
	 */
	function save_custom_widgets( $widgets ) {

		$yz_cw = array();

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget => $data ) {
				$yz_cw[ $widget ] = $data;
			}
		}

		$Yz_Widgets = youzer()->widgets;

		// Update ads List.
    	$update_options = update_option( 'yz_custom_widgets', $yz_cw, true );

    	// If widgets not updated stop function right here.
		if ( ! $update_options ) {
			return false;
		} else {
			// Update Next ID
			update_option( 'yz_next_custom_widget_nbr', $this->get_next_ID( $yz_cw, 'custom_widget' ) );
    	}

	    // Get Overview and Sidebar Widgets
	    $overview_wgs = yz_options( 'yz_profile_main_widgets' );
	    $sidebar_wgs  = yz_options( 'yz_profile_sidebar_widgets' );

	    // Merge Overview & Sidebar widgets
	    $all_widgets = array_merge( $overview_wgs, $sidebar_wgs );

	    // Get Custom Widgets.
	    $custom_widgets = $Yz_Widgets->custom_widgets->get_custom_widgets( $all_widgets );
	    // Delete Removed widgets.
	    foreach ( $custom_widgets as $key => $custom_widget ) {

	        // if widget name is not found.
	        if ( ! isset( $yz_cw[ key( $custom_widget ) ] ) ) {

	            if ( in_array( $custom_widget, $sidebar_wgs ) ) {
	                // if the removed widget in the sidebar remove it.
	                unset( $sidebar_wgs[ array_search( $custom_widget, $sidebar_wgs) ] );
	            } else {
	                // if the removed widget in the overview remove it.
	                unset( $overview_wgs[ array_search( $custom_widget, $overview_wgs) ] );
	            }

	        }

	    }

	    foreach ( $yz_cw as $widget => $data ) {
	        $new_widget = array( $widget => 'visible' );
	        if ( ! $Yz_Widgets->custom_widgets->is_key_exist( $all_widgets, $widget ) ) {
	        	$sidebar_wgs[] = $new_widget;
	        }
	    }

		// Update Overview & Sidebar Widgets.
		update_option( 'yz_profile_main_widgets', $overview_wgs, true );
		update_option( 'yz_profile_sidebar_widgets', $sidebar_wgs, true );

	}

	/**
	 * # Save Youzer Pages.
	 */
	function save_youzer_pages( $youzer_pages ) {

		// Get How much time page is repeated.
		$page_counts = array_count_values( $youzer_pages );

		// if page is already used show error messsage.
		foreach ( $page_counts as $id => $nbr ) {
			if ( $nbr > 1 ) {
				die( __( 'You are using same page more than ones.', 'youzer' ) );
			}
		}

		// Update Youzer Pages in Database.
		$update_pages = update_option( 'youzer_pages', $youzer_pages );

		if ( $update_pages ) {
			foreach ( $youzer_pages as $page => $id ) {
				// Update Option ID
				update_option( $page, $id );
			}
		}
	}

	/**
	 * Reset Settings
	 */
	function reset_settings() {

		do_action( 'yz_before_reset_tab_settings' );
		
		// Get Reset Type.
		$reset_type = $_POST['reset_type'];

	    if ( 'tab' == $reset_type ) {
			check_ajax_referer( 'youzer-settings-data', 'security' );
	    	$result  = $this->reset_tab_settings( $_POST['youzer_options'] );
	    } elseif ( 'all' == $reset_type ) {
	    	$result = $this->reset_all_settings();
	    }

	}

	/**
	 * Reset All Settings.
	 */
	function reset_all_settings() {

		do_action( 'yz_before_reset_all_settings' );
		
		// Delete Active Styles.
	    delete_option( 'yz_active_styles' );

		// Reset Membership Settings.
		if ( yz_is_membership_system_active() ) {
			global $Logy_Admin;
			$Logy_Admin->reset_settings();
		}

		global $Youzer;

		// Get Default Options.
		$default_options = $Youzer->standard_options();

		// Reset Options
		foreach ( $default_options as $option => $value ) {
			if ( get_option( $option ) ) {
				update_option( $option, $value, true );
			}
		}

		// Reset Styling Input's
        foreach ( $Youzer->styling->get_all_styles() as $key ) {
			if ( get_option( $key['id'] ) ) {
				delete_option( $key['id'] );
			}
        }

        // Reset Gradient Elements
        foreach ( $Youzer->styling->get_gradient_elements() as $key ) {
			if ( get_option( $key['left_color'] ) ) {
				delete_option( $key['left_color'] );
			}
			if ( get_option( $key['right_color'] ) ) {
				delete_option( $key['right_color'] );
			}
        }

		// Specific Options
		$specific_options = array(
			'yz_profile_404_photo',
			'yz_profile_404_cover',
			'yz_default_groups_cover',
			'yz_default_groups_avatar',
			'yz_default_profiles_cover',
			'yz_default_profiles_avatar',
			'yz_profile_custom_scheme_color'
		);

		// Reset Specific Options
		foreach ( $specific_options as $option ) {
			if ( get_option( $option ) ) {
				delete_option( $option );
			}
		}

		die( '1' );
	}

	/**
	 * Reset Current Tab Settings.
	 */
	function reset_tab_settings( $tab_options ) {

		if ( empty( $tab_options ) ) {
			return false;
		}

    	// Get Active Styles.
    	$active_styles = yz_options( 'yz_active_styles' );

		// Reset Tab Options
		foreach ( $tab_options as $option => $value ) {

			// Rest Options.
			if ( get_option( $option ) ) {
				delete_option( $option );
			}
			
			// Delete Reseted Active Styles.
			if ( ! empty( $active_styles ) && isset( $value['color'] ) ) {
				
				// Get Option Key.
				$style_key = array_search( $option, $active_styles );

				// Remove Style from the list.
				if ( $style_key !== false ) {
					unset( $active_styles[ $style_key ] );
				}

			}

		}

		// Save Active Styles
		update_option( 'yz_active_styles', $active_styles, true );

		die( '1' );
	}


	/**
	 * Get Fields Next ID.
	 */
	function get_next_ID( $items, $item ) {

		// Set Up Variables.
		$keys = array_keys( $items );

		// Get Keys Numbers.
		foreach ( $keys as $key ) {
			$key_number = preg_match_all( '/\d+/', $key, $matches );
			$new_keys[] = $matches[0][0];
		}

		// Get ID's Data.
		$new_ID = max( $new_keys ); 
		$old_ID = yz_options( 'yz_next_' . $item . '_nbr' );
		$max_ID = ( $new_ID < $old_ID ) ? $old_ID : $new_ID;

		// Return Biggest Key.
		return $max_ID + 1;
	}
	
}