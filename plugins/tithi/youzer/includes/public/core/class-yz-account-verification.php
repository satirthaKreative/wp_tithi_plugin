<?php

class Youzer_Account_Verification {

	protected $youzer;

    public function __construct() {

		global $Youzer;

    	$this->youzer = &$Youzer;
	
	 	// Handle Account Verification.
		add_action( 'wp_ajax_yz_handle_account_verification',  array( &$this, 'handle_verification' ) );
    	
    }

	/**
	 * Handle Account Verification.
	 */
	function handle_verification( $user_id ) {

		// Hook.
		do_action( 'yz_before_handle_account_verification' );

		if ( ! yz_is_user_can_verify_accounts() || ! is_user_logged_in() ) {
			$data['error'] = $this->msg( 'invalid_role' );
			die( json_encode( $data ) );
		}

		// Get Data.
		$data = $_POST;
		
		// Allowed Actions
		$allowed_actions = array( 'verify', 'unverify' );

		// Get User ID.
		$user_id = isset( $_POST['user_id'] ) ? $_POST['user_id'] : null;
		
		if ( empty( $user_id ) ) {
			$data['error'] = $this->msg( 'invalid_user_id' );
			die( json_encode( $data ) );
		}

		check_ajax_referer( 'yz-account-verification-' . $user_id, 'security' );

		// Get Action
		$action = isset( $_POST['verification_action'] ) ? $_POST['verification_action'] : null;

		if ( ! in_array( $action, $allowed_actions ) ) {
			$data['error'] = $this->msg( 'invalid_action' );
			die( json_encode( $data ) );
		}

		if ( 'verify' == $action ) {
			// Mark Account As Verified.
			update_user_meta( $user_id, 'yz_account_verified', 'on' );
			$data['action'] = 'unverify';
			$data['msg'] = __( 'Account marked as verified successfully', 'youzer' );
			do_action('yz_after_verifying_account', $user_id );
		} elseif ( 'unverify' == $action ) {
			// Mark Account As Unverified.
			update_user_meta( $user_id, 'yz_account_verified', 'off' );
			$data['action'] = 'verify';
			$data['msg'] = __( 'Account marked as unverified successfully', 'youzer' );
			do_action('yz_after_unverifying_account', $user_id );
		}

		die( json_encode( $data ) );

	}

    /**
     * Get Error Message.
     */
    public function msg( $code ) {

        // Messages
        switch ( $code ) {

            case 'invalid_role':
                return __( 'The action you have requested is not allowed.', 'youzer' );

            case 'invalid_action':
                return __( 'The action you have requested is not exit.', 'youzer' );

            case 'invalid_user_id':
                return __( 'User id is not found, Please try again later.', 'youzer' );
        }

        return __( 'An unknown error occurred. Please try again later.', 'youzer' );
    }

}