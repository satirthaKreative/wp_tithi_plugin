<?php

class YZ_User_Balance {

    function __construct() {
        // Check User Badges Widget Visibility.
        add_filter( 'yz_profile_widget_visibility', array( &$this, 'display_balance_widget' ), 1, 2 ); 
    }

    /**
     * Check if my cred is installed 
     */
    function display_balance_widget( $widget_visibility, $widget_name ) {

        if ( 'user_balance' != $widget_name ) {
            return $widget_visibility;
        }

        return false;
    }

    /**
     * # Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 100,
            'widget_icon'   => 'fas fa-gem',
            'widget_name'   => 'user_balance',
            'widget_title'  => yz_options( 'yz_wg_user_balance_title' ),
            'load_effect'   => yz_options( 'yz_user_balance_load_effect' ),
            'display_title' => yz_options( 'yz_wg_user_balance_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_user_balance_widget_args', $args );

        return $args;
    }


    /**
     * # Widget Content.
     */
    function widget() {
        do_action( 'yz_user_balance_widget_content' );
    }

    /**
     * # Widget Settings.
     */
    function admin_settings() {
        do_action( 'yz_user_balance_widget_settings' );
    }

}