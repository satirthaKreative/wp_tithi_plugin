<?php

/**
 * # Groups Settings.
 */

function yz_groups_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'display scroll to top', 'youzer' ),
            'desc'  => __( 'show group scroll to top button', 'youzer' ),
            'id'    => 'yz_display_group_scrolltotop',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'group avatar format', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_group_header_avatar_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header visibility settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'avatar border', 'youzer' ),
            'id'    => 'yz_enable_group_header_avatar_border',
            'desc'  => __( 'display photo transparent border', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'privacy', 'youzer' ),
            'id'    => 'yz_display_group_header_privacy',
            'desc'  => __( 'display group privacy', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'activity', 'youzer' ),
            'id'    => 'yz_display_group_header_activity',
            'desc'  => __( 'display group activity', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'members', 'youzer' ),
            'id'    => 'yz_display_group_header_members',
            'desc'  => __( 'display members number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'posts', 'youzer' ),
            'id'    => 'yz_display_group_header_posts',
            'desc'  => __( 'display posts number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Cover Overlay Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Overlay', 'youzer' ),
            'id'    => 'yz_enable_group_header_overlay',
            'desc'  => __( 'enable cover dark background', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_group_header_overlay_opacity',
            'desc'  => __( 'choose a value between 0.1 - 1', 'youzer' ),
            'type'  => 'number',
            'step'  => 0.01
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Cover Pattern Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Dotted Pattern', 'youzer' ),
            'id'    => 'yz_enable_group_header_pattern',
            'desc'  => __( 'enable cover dotted pattern', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_group_header_pattern_opacity',
            'desc'  => __( 'choose a value between 0.1 - 1', 'youzer' ),
            'type'  => 'number',
            'step'  => 0.01
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header styling settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header background', 'youzer' ),
            'id'    => 'yz_group_header_bg_color',
            'desc'  => __( 'header background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'group name', 'youzer' ),
            'id'    => 'yz_group_header_username_color',
            'desc'  => __( 'name text color', 'youzer' ),
            'type'  => 'color'
        )
    );


    $Yz_Settings->get_field(
        array(
            'title' => __( 'meta color', 'youzer' ),
            'id'    => 'yz_group_header_text_color',
            'desc'  => __( 'Group name text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'icons color', 'youzer' ),
            'id'    => 'yz_group_header_icons_color',
            'desc'  => __( 'header icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics title', 'youzer' ),
            'id'    => 'yz_group_header_statistics_title_color',
            'desc'  => __( 'statistics title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics number', 'youzer' ),
            'id'    => 'yz_group_header_statistics_nbr_color',
            'desc'  => __( 'statistics numbers color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header layouts', 'youzer' ),
            'class' => 'uk-center-layouts',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_group_header_layout',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'group_header_layouts' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Groups Photos Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Group Avatar', 'youzer' ),
            'desc'  => __( 'upload default groups avatar', 'youzer' ),
            'id'    => 'yz_default_groups_avatar',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Group Cover', 'youzer' ),
            'desc'  => __( 'upload default groups cover', 'youzer' ),
            'id'    => 'yz_default_groups_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}