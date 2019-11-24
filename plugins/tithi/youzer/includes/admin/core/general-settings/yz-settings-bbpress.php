<?php 

/**
 * # Add BBpress Settings Tab
 */
function yz_bbpress_settings_menu( $tabs ) {

	$tabs['bbpress'] = array(
   	    'icon'  => 'far fa-comments',
   	    'id'    => 'bbpress',
   	    'function' => 'yz_bbpress_settings',
   	    'title' => __( 'BBpress settings', 'youzer' ),
    );
    
    return $tabs;

}

add_filter( 'yz_panel_general_settings_menus', 'yz_bbpress_settings_menu' );

/**
 * # Add BBpress Settings Tab
 */
function yz_bbpress_settings() {

    global $Youzer_Admin, $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'BBpress Integration', 'youzer' ),
            'desc'  => __( 'enable Bbpress integration', 'youzer' ),
            'id'    => 'yz_enable_bbpress',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

