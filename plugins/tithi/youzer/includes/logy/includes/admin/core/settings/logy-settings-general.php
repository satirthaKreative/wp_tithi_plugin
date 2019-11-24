<?php

/**
 * # General Settings.
 */

function logy_general_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Pages Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login page', 'youzer' ),
            'desc'  => __( 'choose login page', 'youzer' ),
            'std'   => logy_page_id( 'login' ),
            'id'    => 'login',
            'opts'  => logy_get_pages(),
            'type'  => 'select'
        ),
        false,
        'logy_pages'
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'lost password page', 'youzer' ),
            'desc'  => __( 'choose lost password page', 'youzer' ),
            'std'   => logy_page_id( 'lost-password' ),
            'opts'  => logy_get_pages(),
            'id'    => 'lost-password',
            'type'  => 'select'
        ),
        'logy_pages'
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Dashboard & Toolbar Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Hide dashboard for subscribers', 'youzer' ),
            'desc'  => __( 'hide admin toolbar and dashborad for subscribers', 'youzer' ),
            'id'    => 'logy_hide_subscribers_dash',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Margin Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'margin top', 'youzer' ),
            'id'    => 'logy_plugin_margin_top',
            'desc'  => __( 'specify membership system page top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'margin bottom', 'youzer' ),
            'id'    => 'logy_plugin_margin_bottom',
            'desc'  => __( 'specify membership system page bottom margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}