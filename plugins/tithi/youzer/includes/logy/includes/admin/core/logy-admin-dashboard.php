<?php

class Logy_Dashboard {

	/**
	 * # General Settings.
	 */
	function general_settings() {

		global $Youzer_Admin, $Yz_Settings;

		// Menu Tabs List
		$tabs = array(
			'general' => array(
				'icon'  	=> 'fas fa-cogs',
				'id' 		=> 'general',
				'function' 	=> 'logy_general_settings',
				'title' 	=> __( 'general settings', 'youzer' ),
			),
			'login'	=> array(
				'id' 		=> 'login',
				'icon'  	=> 'fas fa-sign-in-alt',
				'function' 	=> 'logy_login_settings',
				'title' 	=> __( 'login settings', 'youzer' ),
			),
			'register' => array(
				'icon'  	=> 'fas fa-pencil-alt',
				'id' 		=> 'register',
				'function' 	=> 'logy_register_settings',
				'title' 	=> __( 'register settings', 'youzer' ),
			),
			'lost_password' => array(
				'icon'  	=> 'fas fa-lock',
				'id' 		=> 'lost_password',
				'function' 	=> 'logy_lost_password_settings',
				'title' 	=> __( 'lost password settings', 'youzer' ),
			),
			'captcha' => array(
				'id' 		=> 'captcha',
				'icon'  	=> 'fas fa-user-secret',
				'function' 	=> 'logy_captcha_settings',
				'title' 	=> __( 'captcha settings', 'youzer' ),
			),
			'social_login' => array(
				'icon'  	=> 'fas fa-share-alt',
				'id' 		=> 'social_login',
				'function' 	=> 'logy_social_login_settings',
				'title' 	=> __( 'social login settings', 'youzer' ),
			),
			'limit_login' => array(
				'icon'  	=> 'fas fa-user-clock',
				'id' 		=> 'limit_login',
				'function' 	=> 'logy_limit_login_settings',
				'title' 	=> __( 'login Attempts settings', 'youzer' ),
			),
			'newsletter' => array(
				'icon'  	=> 'far fa-envelope',
				'id' 		=> 'newsletter',
				'function' 	=> 'logy_newsletter_settings',
				'title' 	=> __( 'Newsletter settings', 'youzer' ),
			),
			'login_styling' => array(
				'icon'  	=> 'fas fa-paint-brush',
				'id' 		=> 'login_styling',
				'function' 	=> 'logy_login_styling_settings',
				'title' 	=> __( 'login styling settings', 'youzer' ),
			),
			'register_styling' => array(
				'icon'  	=> 'fas fa-paint-brush',
				'id' 		=> 'register_styling',
				'function' 	=> 'logy_register_styling_settings',
				'title' 	=> __( 'register styling settings', 'youzer' ),
			)
		);
		
		// Filter
		$tabs = apply_filters( 'yz_panel_membership_settings_menus', $tabs );

		// Get Tabs Keys
		$settings_tabs = array_keys( $tabs );

		// Get Current Tab.
		$current_tab = isset( $_GET['tab'] ) && in_array( $_GET['tab'], $settings_tabs ) ? (string) $_GET['tab'] : (string) key( $tabs );

		// Get Tab Data.
		$tab = $tabs[ $current_tab ];

		// Append Class to the active tab.
		$tabs[ $current_tab ]['class'] = 'yz-active-tab';

		// Get Tab Function Name.
		$settings_function = $tab['function'];

		ob_start();

		$field_args = array(
            'type'  => 'start',
            'id'    => $tab['id'],
            'icon'  => $tab['icon'],
            'title' => $tab['title'],
       	);

        $Yz_Settings->get_field( $field_args );

		// Get Settings
		$settings_function();

        $Yz_Settings->get_field( array( 'type' => 'end' ) );

		$content = ob_get_contents();

		ob_end_clean();

		// Print Panel
		$Youzer_Admin->panel->admin_panel( $tabs, $content );
	}

}