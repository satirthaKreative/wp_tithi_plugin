<?php

class Logy_Admin {

	function __construct() {

		// Init Admin Area
		$this->init();

		// Add Plugin Admin Pages.
		add_action( 'admin_menu', array( &$this, 'init_pages' ), 20 );

	}

	/**
	 * # Initialize Admin Panel
	 */
	function init() {

		// Init Admin Files.
		require_once LOGY_ADMIN . 'core/logy-admin-dashboard.php';

		// Settings .
		require_once LOGY_ADMIN . 'core/settings/logy-settings-login.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-general.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-captcha.php';
		require_once LOGY_ADMIN . 'core/settings/logy-settings-newsletter.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-limit-login.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-registration.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-social-login.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-lost-password.php';

		// Init Administration
		$this->dashboard = new logy_Dashboard();
	
	}

	/**
	 * # Add Admin Pages .
	 */
	function init_pages() {

		// Show Panel to Admin's Only.
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		global $submenu;

		// Add "General Settings" Page .
	    add_submenu_page(
	    	'youzer-panel',
	    	__( 'Youzer - Membership Settings', 'youzer' ),
	    	__( 'Membership Settings', 'youzer' ),
	    	'administrator',
	    	'yz-membership-settings',
	    	array( &$this->dashboard, 'general_settings' ),
	    	false
	    );

	}
		
	/**
	 * Reset Settings.
	 */
	function reset_settings() {

		global $Logy;

		// Reset Styling Input's
        foreach ( $Logy->styling->styles_data() as $key ) {
			if ( get_option( $key['id'] ) ) {
				delete_option( $key['id'] );
			}
        }

		// Specific Options.
		$specific_options = array(
			'logy_login_cover',
			'logy_signup_cover',
			'logy_lostpswd_cover'
		);

		// Reset Specific Options.
		foreach ( $specific_options as $option ) {
			if ( get_option( $option ) ) {
				delete_option( $option );
			}
		}

		// Get Providers.
		$providers = logy_get_providers();

		// Reset Social Provider Input's.
        foreach ( $providers as $provider ) {

        	// Transform Provider Name to lower case.
        	$provider = strtolower( $provider );

        	// Reset Provider Status's
			if ( get_option( 'logy_' . $provider . '_app_status' ) ) {
				delete_option( 'logy_' . $provider . '_app_status' );
			}

        	// Reset Provider Keys.
			if ( get_option( 'logy_' . $provider . '_app_key' ) ) {
				delete_option( 'logy_' . $provider . '_app_key' );
			}

        	// Reset Provider Secret Keys.
			if ( get_option( 'logy_' . $provider . '_app_secret' ) ) {
				delete_option( 'logy_' . $provider . '_app_secret' );
			}

        	// Reset Provider Notes.
			if ( get_option( 'logy_' . $provider .'_setup_steps' ) ) {
				delete_option( 'logy_' . $provider .'_setup_steps' );
			}

        }
	}
}