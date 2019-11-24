<?php
/**
 * Captcha Settings
 */
function logy_captcha_settings() {

    global $Yz_Settings;

    // Get Captcha Url
    $captcha_url = 'https://www.google.com/recaptcha/intro/index.html';

    $Yz_Settings->get_field(
        array(
            'title'     => __( 'How to Get Your Captcha Keys?', 'youzer' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'logy_msgbox_captcha',
            'msg'       => sprintf( __( 'To get your keys Visit <strong><a href="%s">The reCAPTCHA Site</a></strong> or Check the documentation.', 'youzer' ), $captcha_url )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable captcha', 'youzer' ),
            'desc'  => __( 'enable using the captcha', 'youzer' ),
            'id'    => 'logy_enable_recaptcha',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'captcha site key', 'youzer' ),
            'desc'  => __( 'the reCaptcha site key', 'youzer' ),
            'id'    => 'logy_recaptcha_site_key',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'captcha secret key', 'youzer' ),
            'desc'  => __( 'the reCaptcha secret key', 'youzer' ),
            'id'    => 'logy_recaptcha_secret_key',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}