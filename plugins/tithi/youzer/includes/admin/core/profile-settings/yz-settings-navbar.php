<?php

/**
 * # Navbar Settings.
 */

function yz_navbar_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Navbar general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Navbar Icons', 'youzer' ),
            'id'    => 'yz_display_navbar_icons',
            'desc'  => __( 'show navbar pages icons', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Navbar Menus Limit', 'youzer' ),
            'id'    => 'yz_profile_navbar_menus_limit',
            'desc'  => __( 'number of visible pages on the navbar', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'loading effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'how you want the navbar to be loaded ?', 'youzer' ),
            'id'    => 'yz_navbar_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Navbar Icons Style settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'imgSelect',
            'id'    =>  'yz_navbar_icons_style',
            'opts'  => $Yz_Settings->get_field_options( 'navbar_icons_style' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Vertical Layout Navbar settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'imgSelect',
            'id'    =>  'yz_vertical_layout_navbar_type',
            'opts'  => $Yz_Settings->get_field_options( 'vertical_layout_navbar' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Navbar Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'background', 'youzer' ),
            'id'    => 'yz_navbar_bg_color',
            'desc'  => __( 'navbar background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'icons color', 'youzer' ),
            'id'    => 'yz_navbar_icons_color',
            'desc'  => __( 'navbar icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'tabs title', 'youzer' ),
            'id'    => 'yz_navbar_links_color',
            'desc'  => __( 'pages links color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'tabs hover', 'youzer' ),
            'id'    => 'yz_navbar_links_hover_color',
            'desc'  => __( 'pages links hover color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'border color', 'youzer' ),
            'id'    => 'yz_navbar_menu_border_color',
            'desc'  => __( 'links border color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}