<?php

/**
 * # Widgets Settings.
 */

function yz_widgets_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'widgets Border Style', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_wgs_border_style',
            'desc'  => __( 'widgets border style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'widgets_formats' ),
            'type'  => 'imgSelect',
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'widgets title Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'display title icon', 'youzer' ),
            'id'    => 'yz_display_wg_title_icon',
            'desc'  => __( 'show widget title icon', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'use title icon background', 'youzer' ),
            'id'    => 'yz_use_wg_title_icon_bg',
            'desc'  => __( 'use widget icon background', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'title styling settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'title', 'youzer' ),
            'id'    => 'yz_wgs_title_color',
            'desc'  => __( 'widget title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'title background', 'youzer' ),
            'id'    => 'yz_wgs_title_bg',
            'desc'  => __( 'widget title background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'title border', 'youzer' ),
            'id'    => 'yz_wgs_title_border_color',
            'desc'  => __( 'title bottom border color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'icon', 'youzer' ),
            'id'    => 'yz_wgs_title_icon_color',
            'desc'  => __( 'title icon color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'icon background', 'youzer' ),
            'id'    => 'yz_wgs_title_icon_bg',
            'desc'  => __( 'title icon background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}