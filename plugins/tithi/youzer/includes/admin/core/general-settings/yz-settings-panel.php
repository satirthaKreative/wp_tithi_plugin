<?php

/**
 * # Panel Settings.
 */

function yz_panel_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable fixed Save Icon', 'youzer' ),
            'desc' => __( 'enable fixed Save icon button', 'youzer' ),
            'id'    => 'yz_enable_panel_fixed_save_btn',
            'type'  => 'checkbox',
        )
    );
    
    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'panel schemes', 'youzer' ),
            'class' => 'uk-panel-scheme',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_panel_scheme',
            'type'  => 'imgSelect',
            'opts'  => array(
                'uk-orange-scheme', 'uk-white-scheme', 'uk-pink-scheme',
                'uk-red-scheme', 'uk-darkgray-scheme', 'uk-yellow-scheme',
                'uk-blue-scheme', 'uk-purple-scheme', 'uk-green-scheme'
            )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}