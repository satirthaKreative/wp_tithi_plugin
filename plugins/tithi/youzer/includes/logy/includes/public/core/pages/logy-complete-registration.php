<?php

class Logy_Complete_Registration {

	protected $logy;
	
	/**
	 * Init Actions & Filters.
	 */
	public function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

    	// Add "[logy_register]" Shortcode.
		add_shortcode( 'logy_complete_registration_page', array( $this, 'get_form' ) );

		// Complete Registration.
		add_action( 'init', array( $this, 'register_user' ) );

    }
	
	/**
	 * Get Registration form.
	 */
	public function get_form() {
		// Render the form.
		return $this->logy->form->get_page( 'complete_registration' );
	}

	/**
	 * Attributes
	 */
	function attributes() {

		$attrs = $this->logy->register->attributes();

		// Edit Button title
		$attrs['submit_title'] = __( 'Complete Registration', 'youzer' );

		return $attrs;
	}

	/**
	 * Save "Complete Registartion" Form Data.
	 */
	public function register_user() {

		if ( is_user_logged_in() ) {
			return false;
		}

	    // Get User Session Data.
	    $user_session_data = logy_user_session_data( 'get' );

		if ( ! isset( $_POST['complete-registration'] ) || empty( $user_session_data ) ) {
			return false;
		}
		
		// Check if Registration is Enabled.
		if ( ! get_option( 'users_can_register' ) ) {
			$this->redirect( 'registration_disabled' );
		}

		$bp = buddypress();
		
		// Init Vars.
		$errors = array();

	 	// Get User Profile Data
		$user_profile_data = logy_user_profile_data( 'get' );
		
	 	$user = ! empty( $user_profile_data ) ? json_decode( $user_profile_data ) : null;

	 	if ( empty( $user ) ) {
	        // Display Error.
			$errors[] = logy_get_message( $this->logy->form->get_error_message( 'cant_connect' ) );
	 	}

	 	// Update User Data.
	 	$user->email = isset( $_POST['signup_email'] ) ? sanitize_email( $_POST['signup_email'] ) : $user->email;
	 	$user->emailVerified = isset( $_POST['signup_email'] ) ? sanitize_email( $_POST['signup_email'] ): $user->emailVerified;
	 	$user->displayName = isset( $_POST['signup_username'] ) ? sanitize_user( $_POST['signup_username'] ) : $this->logy->social->get_unique_username( $user->displayName );

		/**
		 * Fires before the validation of a new signup.
		 */
		do_action( 'bp_signup_pre_validate' );

		// Check the base account details for problems.
		$account_details = bp_core_validate_user_signup( $user->displayName, $user->email );

		// If there are errors with account details, set them for display.
		if ( ! empty( $account_details['errors']->errors['user_name'] ) ) {
			$errors[] = logy_get_message( $account_details['errors']->errors['user_name'][0] );
		}

		if ( ! empty( $account_details['errors']->errors['user_email'] ) ) {
			$errors[] = logy_get_message( $account_details['errors']->errors['user_email'][0] );
		}

		// Display Errors
		if ( ! empty( $errors ) ) {
			logy_add_message( $errors );
		} else {
			
			// Prepare User Data
			$user_data = array(
				'user_email'    => $user->email,
				'user_login'    => $user->displayName,
				'display_name'  => $user->displayName,
				'first_name'    => $user->firstName,
				'last_name'     => $user->lastName,
				'description'   => $user->description,
				'user_pass'     => wp_generate_password(),
				'user_url'      => $this->get_user_url( $user->webSiteURL, $user->profileURL ),
			);

		 	// Register User.
		 	$this->logy->social->bp_register_user( $user );

			/**
			 * Fires after the completion of a new signup.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_complete_signup' );

			// Redirect.
			bp_core_redirect( logy_page_url( 'register' ));

		}
	}

	/**
	 * Redirect User To Specific Page..
	 */
	public function redirect( $code, $redirect_to = null, $type = null ) {
		
		// Init Array.
		$messages = array();

		// Get Redirect Url.	
		$redirect_url = ! empty( $redirect_to ) ? $redirect_to : logy_page_url( 'complete-registration' );
			
		// Get Message.
		$messages[] = logy_get_message( $this->logy->form->get_error_message( $code ), $type );

		// Get Messages.
		logy_add_message( $messages, $type );
		
		// Redirect User.
		wp_redirect( $redirect_url );

		// Exit.
		exit;

	}

}