<?php

/**
 * # Admin Settings.
 */
function logy_login_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable Ajax login', 'youzer' ),
            'desc'  => __( 'enable ajax in login forms?', 'youzer' ),
            'id'    => 'yz_enable_ajax_login',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable login popup', 'youzer' ),
            'desc'  => __( 'enable popup login form?', 'youzer' ),
            'id'    => 'yz_enable_login_popup',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login button title', 'youzer' ),
            'desc'  => __( 'type login button title', 'youzer' ),
            'id'    => 'logy_login_signin_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'register button title', 'youzer' ),
            'desc'  => __( 'type register button title', 'youzer' ),
            'id'    => 'logy_login_register_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'lost password title', 'youzer' ),
            'desc'  => __( 'type lost password title', 'youzer' ),
            'id'    => 'logy_login_lostpswd_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'custom registration link', 'youzer' ),
            'desc'  => __( 'paste a custom registration page link', 'youzer' ),
            'id'    => 'logy_login_custom_register_link',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'redirect Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    // Get Website Pages.
    $website_pages = (array) logy_get_pages();

    // Get User Default Redirect Options
    $default_user_redirect_options = $Yz_Settings->get_field_options( 'user_login_redirect_pages' );

    // Get All Redirect Options.
    $user_login_redirect_pages = $default_user_redirect_options + $website_pages;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'after login redirect user to?', 'youzer' ),
            'desc'  => __( 'after user login redirect to which page ?', 'youzer' ),
            'id'    => 'logy_user_after_login_redirect',
           'opts'  => $user_login_redirect_pages,
            'type'  => 'select'
        )
    );

    // Get Admin Default Redirect Options
    $default_admin_redirect_options = $Yz_Settings->get_field_options( 'admin_login_redirect_pages' );

    // Get All Redirect Options.
    $admin_login_redirect_pages = $default_admin_redirect_options + $website_pages;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'after login redirect admin to?', 'youzer' ),
            'desc'  => __( 'after admin login redirect to which page ?', 'youzer' ),
            'id'    => 'logy_admin_after_login_redirect',
            'opts'  => $admin_login_redirect_pages,
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'after logout redirect user to?', 'youzer' ),
            'desc'  => __( 'after user logout redirect to which page ?', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'logout_redirect_pages' ),
            'id'    => 'logy_after_logout_redirect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Header Settings
    logy_login_header_settings();

    // Get Fields Settings
    logy_login_fields_settings();

    // Get Buttons Settings
    logy_login_buttons_settings();

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Widget Margin Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'box margin top', 'youzer' ),
            'id'    => 'logy_login_wg_margin_top',
            'desc'  => __( 'specify box top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'box margin bottom', 'youzer' ),
            'id'    => 'logy_login_wg_margin_bottom',
            'desc'  => __( 'specify box bottom margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Header Settings
 */
function logy_login_header_settings() {

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
            'id'    => 'logy_login_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form title', 'youzer' ),
            'desc'  => __( 'login form title', 'youzer' ),
            'id'    => 'logy_login_form_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form Sub title', 'youzer' ),
            'desc'  => __( 'login form Sub title', 'youzer' ),
            'id'    => 'logy_login_form_subtitle',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form cover', 'youzer' ),
            'desc'  => __( 'upload login form cover', 'youzer' ),
            'id'    => 'logy_login_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Fields Settings
 */
function logy_login_fields_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Fields layouts', 'youzer' ),
            'class' => 'ukai-center-elements',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'logy_login_form_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-field-v1', 'logy-field-v2', 'logy-field-v3', 'logy-field-v4', 'logy-field-v5',
                'logy-field-v6', 'logy-field-v7', 'logy-field-v8', 'logy-field-v9', 'logy-field-v10',
                'logy-field-v11', 'logy-field-v12'
            )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'fields Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'fields icons position', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'select fields icons position <br>( works only with layouts that support icons ! )', 'youzer' ),
            'id'    => 'logy_login_icons_position',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'fields border style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'select fields border style', 'youzer' ),
            'id'    => 'logy_login_fields_format',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Buttons Settings
 */
function logy_login_buttons_settings() {

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
            'id'    => 'logy_login_actions_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-actions-v1', 'logy-actions-v2', 'logy-actions-v3', 'logy-actions-v4',
                'logy-actions-v5', 'logy-actions-v6', 'logy-actions-v7', 'logy-actions-v8',
                'logy-actions-v9', 'logy-actions-v10'
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
            'id'    => 'logy_login_btn_icons_position',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons border style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'select buttons border style', 'youzer' ),
            'id'    => 'logy_login_btn_format',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Styling Settings
 */
function logy_login_styling_settings() {

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
            'id'    => 'logy_login_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form subtitle', 'youzer' ),
            'desc'  => __( 'form subtitle color', 'youzer' ),
            'id'    => 'logy_login_subtitle_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'cover title background', 'youzer' ),
            'desc'  => __( 'cover title background color', 'youzer' ),
            'id'    => 'logy_login_cover_title_bg_color',
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
            'id'    => 'logy_login_label_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'placeholder', 'youzer' ),
            'desc'  => __( 'form labels color', 'youzer' ),
            'id'    => 'logy_login_placeholder_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'inputs text', 'youzer' ),
            'desc'  => __( 'inputs text color', 'youzer' ),
            'id'    => 'logy_login_inputs_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'inputs background', 'youzer' ),
            'desc'  => __( 'inputs background color', 'youzer' ),
            'id'    => 'logy_login_inputs_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'inputs border', 'youzer' ),
            'desc'  => __( 'inputs border color', 'youzer' ),
            'id'    => 'logy_login_inputs_border_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'icons', 'youzer' ),
            'desc'  => __( 'fields icons color', 'youzer' ),
            'id'    => 'logy_login_fields_icons_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'icons background', 'youzer' ),
            'desc'  => __( 'fields icons background color', 'youzer' ),
            'id'    => 'logy_login_fields_icons_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Remember Me Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( '"remember me" color', 'youzer' ),
            'desc'  => __( 'form "remember me" color', 'youzer' ),
            'id'    => 'logy_login_remember_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'checkbox border', 'youzer' ),
            'desc'  => __( 'form checkbox border color', 'youzer' ),
            'id'    => 'logy_login_checkbox_border_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'checkbox icon', 'youzer' ),
            'desc'  => __( 'form checkbox icon color', 'youzer' ),
            'id'    => 'logy_login_checkbox_icon_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( '"lost password" color', 'youzer' ),
            'desc'  => __( 'form "lost password" color', 'youzer' ),
            'id'    => 'logy_login_lostpswd_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login button color', 'youzer' ),
            'desc'  => __( 'login button background color', 'youzer' ),
            'id'    => 'logy_login_submit_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login button text', 'youzer' ),
            'desc'  => __( 'login button text color', 'youzer' ),
            'id'    => 'logy_login_submit_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'register button color', 'youzer' ),
            'desc'  => __( 'register button background color', 'youzer' ),
            'id'    => 'logy_login_regbutton_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'register button text', 'youzer' ),
            'desc'  => __( 'register button text color', 'youzer' ),
            'id'    => 'logy_login_regbutton_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}
