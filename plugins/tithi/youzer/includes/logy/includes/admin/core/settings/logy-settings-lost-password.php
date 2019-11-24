<?php

/**
 * Lost Password Settings
 */

function logy_lost_password_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form title', 'youzer' ),
            'desc'  => __( 'lost password form title', 'youzer' ),
            'id'    => 'logy_lostpswd_form_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form Sub title', 'youzer' ),
            'desc'  => __( 'lost password Sub title', 'youzer' ),
            'id'    => 'logy_lostpswd_form_subtitle',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'reset button title', 'youzer' ),
            'desc'  => __( 'reset password button title', 'youzer' ),
            'id'    => 'logy_lostpswd_submit_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'cover Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable form cover', 'youzer' ),
            'desc'  => __( 'enable form header cover?', 'youzer' ),
            'id'    => 'logy_lostpswd_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form cover', 'youzer' ),
            'desc'  => __( 'upload login form cover', 'youzer' ),
            'id'    => 'logy_lostpswd_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}