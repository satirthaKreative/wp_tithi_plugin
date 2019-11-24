<?php

/**
 * # Admin Settings.
 */
function logy_register_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable registration', 'youzer' ),
            'desc'  => __( 'enable users registration', 'youzer' ),
            'id'    => 'users_can_register',
            'type'  => 'checkbox'
        )
    );

    // Get Site Rules
    global $wp_roles;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'New User Default Role', 'youzer' ),
            'desc'  => __( 'select New User Default Role', 'youzer' ),
            'opts'  => $wp_roles->get_names(),
            'id'    => 'default_role',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'register button title', 'youzer' ),
            'desc'  => __( 'type register button title', 'youzer' ),
            'id'    => 'logy_signup_register_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login button title', 'youzer' ),
            'desc'  => __( 'type login button title', 'youzer' ),
            'id'    => 'logy_signup_signin_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Terms and Privacy Policy Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display note', 'youzer' ),
            'desc'  => __( 'display terms and privacy policy note', 'youzer' ),
            'id'    => 'logy_show_terms_privacy_note',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'terms url', 'youzer' ),
            'desc'  => __( 'enter terms and conditions link', 'youzer' ),
            'id'    => 'logy_terms_url',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'privacy policy url', 'youzer' ),
            'desc'  => __( 'enter privacy policy link', 'youzer' ),
            'id'    => 'logy_privacy_url',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Header Settings
    logy_register_header_settings();

    // Get Buttons Settings
    logy_register_buttons_settings();

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Register Widget Margin Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'box margin top', 'youzer' ),
            'id'    => 'logy_register_wg_margin_top',
            'desc'  => __( 'specify box top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'box margin bottom', 'youzer' ),
            'id'    => 'logy_register_wg_margin_bottom',
            'desc'  => __( 'specify box bottom margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Header Settings
 */
function logy_register_header_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable form cover', 'youzer' ),
            'desc'  => __( 'enable form header cover?', 'youzer' ),
            'id'    => 'logy_signup_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form title', 'youzer' ),
            'desc'  => __( 'registration form title', 'youzer' ),
            'id'    => 'logy_signup_form_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form Sub title', 'youzer' ),
            'desc'  => __( 'Sign up form Sub title', 'youzer' ),
            'id'    => 'logy_signup_form_subtitle',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'upload cover', 'youzer' ),
            'desc'  => __( 'upload registration form cover', 'youzer' ),
            'id'    => 'logy_signup_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * Buttons Settings
 */
function logy_register_buttons_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'buttons layout', 'youzer' ),
            'class' => 'ukai-center-elements',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'logy_signup_actions_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-regactions-v1', 'logy-regactions-v2', 'logy-regactions-v3', 'logy-regactions-v4',
                'logy-regactions-v5', 'logy-regactions-v6'
            )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'buttons Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons icons position', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'select buttons icons position <br>( works only with buttons that support icons ! )', 'youzer' ),
            'id'    => 'logy_signup_btn_icons_position',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons border style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'select buttons border style', 'youzer' ),
            'id'    => 'logy_signup_btn_format',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Styling Settings
 */
function logy_register_styling_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form title', 'youzer' ),
            'desc'  => __( 'form title color', 'youzer' ),
            'id'    => 'logy_signup_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form subtitle', 'youzer' ),
            'desc'  => __( 'form subtitle color', 'youzer' ),
            'id'    => 'logy_signup_subtitle_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'cover title background', 'youzer' ),
            'desc'  => __( 'cover title background color', 'youzer' ),
            'id'    => 'logy_signup_cover_title_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Fields Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'labels', 'youzer' ),
            'desc'  => __( 'form labels color', 'youzer' ),
            'id'    => 'logy_signup_label_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'placeholder', 'youzer' ),
            'desc'  => __( 'form labels color', 'youzer' ),
            'id'    => 'logy_signup_placeholder_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'inputs text', 'youzer' ),
            'desc'  => __( 'inputs text color', 'youzer' ),
            'id'    => 'logy_signup_inputs_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'inputs background', 'youzer' ),
            'desc'  => __( 'inputs background color', 'youzer' ),
            'id'    => 'logy_signup_inputs_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'inputs border', 'youzer' ),
            'desc'  => __( 'inputs border color', 'youzer' ),
            'id'    => 'logy_signup_inputs_border_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'register button color', 'youzer' ),
            'desc' => __( 'submit button background', 'youzer' ),
            'id'    => 'logy_signup_submit_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'register button text', 'youzer' ),
            'desc'  => __( 'register button text color', 'youzer' ),
            'id'    => 'logy_signup_submit_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login button color', 'youzer' ),
            'desc'  => __( 'register button background color', 'youzer' ),
            'id'    => 'logy_signup_loginbutton_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login button text', 'youzer' ),
            'desc'  => __( 'register button text color', 'youzer' ),
            'id'    => 'logy_signup_loginbutton_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}