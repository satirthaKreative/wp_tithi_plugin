<?php 

/**
 * # Add Mycred Settings Tab
 */
function yz_mycred_settings_menu( $tabs ) {

	$tabs['mycred'] = array(
   	    'icon'  => 'fas fa-trophy',
   	    'id'    => 'mycred',
   	    'function' => 'yz_mycred_settings',
   	    'title' => __( 'MyCRED settings', 'youzer' ),
    );
    
    return $tabs;

}

add_filter( 'yz_panel_general_settings_menus', 'yz_mycred_settings_menu' );

/**
 * Get Mycred Badges Settings
 */
function get_mycred_badges_widget_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'display title', 'youzer' ),
            'id'    => 'yz_wg_user_badges_display_title',
            'desc'  => __( 'show widget title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'widget title', 'youzer' ),
            'id'    => 'yz_wg_user_badges_title',
            'desc'  => __( 'add widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'loading effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
            'id'    => 'yz_user_badges_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'allowed badges number', 'youzer' ),
            'id'    => 'yz_wg_max_user_badges_items',
            'desc'  => __( 'maximum number of badges to display', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
}

add_action( 'yz_user_badges_widget_settings', 'get_mycred_badges_widget_settings' );

/**
 * Get Mycred Balance Settings
 */
function get_mycred_balance_widget_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'display title', 'youzer' ),
            'id'    => 'yz_wg_user_balance_display_title',
            'desc'  => __( 'show widget title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'widget title', 'youzer' ),
            'id'    => 'yz_wg_user_balance_title',
            'desc'  => __( 'add widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'loading effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
            'id'    => 'yz_user_balance_load_effect',
            'type'  => 'select'
        )
    );


    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
        $Yz_Settings->get_field(
            array(
                'title' => __( 'Box gradient settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Left Color', 'youzer' ),
                'id'    => 'yz_user_balance_gradient_left_color',
                'desc'  => __( 'gradient left color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Right Color', 'youzer' ),
                'id'    => 'yz_user_balance_gradient_right_color',
                'desc'  => __( 'gradient right color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

add_action( 'yz_user_balance_widget_settings', 'get_mycred_balance_widget_settings' );

/**
 * # Add Mycred Settings Tab
 */
function yz_mycred_settings() {

    global $Youzer_Admin, $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'MyCred Integration', 'youzer' ),
            'desc'  => __( 'enable MyCred integration', 'youzer' ),
            'id'    => 'yz_enable_mycred',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'members directory Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable badges', 'youzer' ),
            'desc'  => __( 'enable cards badges', 'youzer' ),
            'id'    => 'yz_enable_cards_mycred_badges',
            'type'  => 'checkbox'
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Max badges', 'youzer' ),
            'desc'  => __( 'max badges per card', 'youzer' ),
            'id'    => 'yz_wg_max_card_user_badges_items',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Author Box Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable badges', 'youzer' ),
            'desc'  => __( 'enable author box badges', 'youzer' ),
            'id'    => 'yz_enable_author_box_mycred_badges',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Max badges', 'youzer' ),
            'desc'  => __( 'max badges per author box', 'youzer' ),
            'id'    => 'yz_author_box_max_user_badges_items',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

