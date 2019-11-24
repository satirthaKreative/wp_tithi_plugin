<?php

/**
 * # General Settings.
 */

function yz_general_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Pages Background', 'youzer' ),
            'desc'  => __( 'Plugin Pages background color', 'youzer' ),
            'id'    => 'yz_plugin_background',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Pages Content Width', 'youzer' ),
            'desc'  => __( 'content width by default is 1170px', 'youzer' ),
            'id'    => 'yz_plugin_content_width',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Style', 'youzer' ),
            'id'    => 'yz_buttons_border_style',
            'desc'  => __( 'Pages Buttons Border Style', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'buttons_border_styles' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tabs Icons Style', 'youzer' ),
            'id'    => 'yz_tabs_list_icons_style',
            'desc'  => __( 'Pages Tabs Icons Style', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'tabs_list_icons_style' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Wordpress Menu Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'disable avatar dropdown icon', 'youzer' ),
            'desc'  => __( 'disable youzer avatar dropdown icon in the wordpress menu', 'youzer' ),
            'id'    => 'yz_disable_wp_menu_avatar_icon',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Membership System Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Activate Membership System', 'youzer' ),
            'desc'  => __( 'activate youzer membership system', 'youzer' ),
            'id'    => 'yz_activate_membership_system',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Disable Buddypress Registration', 'youzer' ),
            'desc'  => __( 'works only if the Youzer Membership System is disabled', 'youzer' ),
            'id'    => 'yz_disable_bp_registration',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'info', 'youzer' ),
            'id'       => 'yz_msgbox_logy_login',
            'msg'      => __( "if the <strong>Youzer Membership System</strong> is active the <strong>Login Pages Settings</strong> below won't work", 'youzer' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Page Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'use login option', 'youzer' ),
            'desc'  => __( 'choose login page option', 'youzer' ),
            'id'    => 'yz_login_page_type',
            'opts'  => array(
                'url'  => __( 'Url', 'youzer' ),
                'page' => __( 'Page', 'youzer' ),
            ),
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login page', 'youzer' ),
            'desc'  => __( 'choose login page', 'youzer' ),
            'id'    => 'yz_login_page',
            'opts'  => yz_get_pages(),
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'login page url', 'youzer' ),
            'desc'  => __( 'type login page url', 'youzer' ),
            'id'    => 'yz_login_page_url',
            'std'   => wp_login_url(),
            'type'  => 'text'
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
            'id'    => 'yz_plugin_margin_top',
            'desc'  => __( 'specify plugin top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'margin bottom', 'youzer' ),
            'id'    => 'yz_plugin_margin_bottom',
            'desc'  => __( 'specify plugin bottom margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Padding Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'padding top', 'youzer' ),
            'id'    => 'yz_plugin_padding_top',
            'desc'  => __( 'specify plugin top padding', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'padding bottom', 'youzer' ),
            'id'    => 'yz_plugin_padding_bottom',
            'desc'  => __( 'specify plugin bottom padding', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'padding left', 'youzer' ),
            'id'    => 'yz_plugin_padding_left',
            'desc'  => __( 'specify plugin left padding', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'padding right', 'youzer' ),
            'id'    => 'yz_plugin_padding_right',
            'desc'  => __( 'specify plugin right padding', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'scroll to top Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'display scroll button', 'youzer' ),
            'id'    => 'yz_display_scrolltotop',
            'desc'  => __( 'display scroll to top button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'button hover color', 'youzer' ),
            'desc'  => __( 'scroll to top hover color', 'youzer' ),
            'id'    => 'yz_scroll_button_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'reset Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'button_title' => __( 'Reset all settings', 'youzer' ),
            'title' => __( 'Reset all settings', 'youzer' ),
            'desc'  => __( 'reset all youzer plugin settings', 'youzer' ),
            'id'    => 'yz-reset-all-settings',
            'type'  => 'button'
        )
    );

    yz_popup_dialog( 'reset_all' );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}