<?php

/**
 * # Porfile 404 Settings.
 */

function yz_profile_404_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'error message', 'youzer' ),
            'desc'  => __( 'page error message', 'youzer' ),
            'id'    => 'yz_profile_404_desc',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'button title', 'youzer' ),
            'desc'  => __( 'page button title', 'youzer' ),
            'id'    => 'yz_profile_404_button',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'photo', 'youzer' ),
            'desc'  => __( 'upload 404 profile photo', 'youzer' ),
            'id'    => 'yz_profile_404_photo',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'cover', 'youzer' ),
            'desc'  => __( 'upload 404 profile cover', 'youzer' ),
            'id'    => 'yz_profile_404_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'title', 'youzer' ),
            'desc'  => __( 'title color', 'youzer' ),
            'id'    => 'yz_profile_404_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'description', 'youzer' ),
            'desc'  => __( 'description color', 'youzer' ),
            'id'    => 'yz_profile_404_desc_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'button text', 'youzer' ),
            'desc'  => __( 'button text color', 'youzer' ),
            'id'    => 'yz_profile_404_button_txt_color',
            'type'  => 'color'
        )
    );
    $Yz_Settings->get_field(
        array(
            'title' => __( 'button background', 'youzer' ),
            'desc'  => __( 'button background color', 'youzer' ),
            'id'    => 'yz_profile_404_button_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}