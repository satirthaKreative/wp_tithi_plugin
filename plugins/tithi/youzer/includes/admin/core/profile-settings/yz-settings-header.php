<?php

/**
 * # Header Settings.
 */

function yz_header_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable user status', 'youzer' ),
            'desc'  => __( 'show if user is online or offline !', 'youzer' ),
            'id'    => 'yz_header_enable_user_status',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Vertical Header Meta', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'header meta icon', 'youzer' ),
            'desc'  => __( 'vertical header user meta icon ?', 'youzer' ),
            'id'    => 'yz_header_meta_icon',
            'type'  => 'icon'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'header meta', 'youzer' ),
            'desc'  => __( 'vertical header user meta type ?', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_header_meta_type',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'info', 'youzer' ),
            'id'       => 'yz_msgbox_profile_schemes',
            'msg'      => __( '<strong>"Vertical Header Settings"</strong> Section options works only with the <strong>vertical header layouts</strong>. if you use it with horizontal layouts it will have <strong>no effect</strong>!', 'youzer' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Vertical Header settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'use photo as cover?', 'youzer' ),
            'desc'  => __( 'if cover not exist use profile photo as cover?', 'youzer' ),
            'id'    => 'yz_header_use_photo_as_cover',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics borders', 'youzer' ),
            'desc'  => __( 'use statistics borders ?', 'youzer' ),
            'id'    => 'yz_header_use_statistics_borders',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics background', 'youzer' ),
            'desc'  => __( 'use statistics silver background ?', 'youzer' ),
            'id'    => 'yz_header_use_statistics_bg',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header image format', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_header_photo_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Effects settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header photo effect', 'youzer' ),
            'desc'  => __( 'works only with circle photos !', 'youzer' ),
            'id'    => 'yz_profile_photo_effect',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header loading effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'select header loading effect', 'youzer' ),
            'id'    => 'yz_hdr_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Networks settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'display social networks', 'youzer' ),
            'desc'  => __( 'show header social networks', 'youzer' ),
            'id'    => 'yz_display_header_networks',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'networks type', 'youzer' ),
            'id'    => 'yz_header_sn_bg_type',
            'desc'  => __( 'networks background type', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'icons_colors' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'networks style', 'youzer' ),
            'id'    => 'yz_header_sn_bg_style',
            'desc'  => __( 'networks background style', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'border_styles' )
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
            'title' => __( 'photo border', 'youzer' ),
            'id'    => 'yz_enable_header_photo_border',
            'desc'  => __( 'display photo transparent border', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'location', 'youzer' ),
            'id'    => 'yz_display_header_location',
            'desc'  => __( 'display user location', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'website', 'youzer' ),
            'id'    => 'yz_display_header_site',
            'desc'  => __( 'display user website', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'first statistic', 'youzer' ),
            'id'    => 'yz_display_header_first_statistic',
            'desc'  => __( 'display first statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'second statistic', 'youzer' ),
            'id'    => 'yz_display_header_second_statistic',
            'desc'  => __( 'display second statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'third statistic', 'youzer' ),
            'id'    => 'yz_display_header_third_statistic',
            'desc'  => __( 'display third statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'header statistics settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );


    $Yz_Settings->get_field(
        array(
            'title' => __( 'first statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'select header first statistic', 'youzer' ),
            'id'    => 'yz_header_first_statistic',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'second statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'select header second statistic', 'youzer' ),
            'id'    => 'yz_header_second_statistic',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'third statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'select header third statistic', 'youzer' ),
            'id'    => 'yz_header_third_statistic',
            'type'  => 'select'
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
            'id'    => 'yz_enable_header_overlay',
            'desc'  => __( 'enable cover dark background', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_profile_header_overlay_opacity',
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
            'id'    => 'yz_enable_header_pattern',
            'desc'  => __( 'enable cover dotted pattern', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_profile_header_pattern_opacity',
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
            'id'    => 'yz_profile_header_bg_color',
            'desc'  => __( 'header background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'username', 'youzer' ),
            'id'    => 'yz_profile_header_username_color',
            'desc'  => __( 'username text color', 'youzer' ),
            'type'  => 'color'
        )
    );


    $Yz_Settings->get_field(
        array(
            'title' => __( 'meta color', 'youzer' ),
            'id'    => 'yz_profile_header_text_color',
            'desc'  => __( 'header text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'icons color', 'youzer' ),
            'id'    => 'yz_profile_header_icons_color',
            'desc'  => __( 'header icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics title', 'youzer' ),
            'id'    => 'yz_profile_header_statistics_title_color',
            'desc'  => __( 'statistics title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics number', 'youzer' ),
            'id'    => 'yz_profile_header_statistics_nbr_color',
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
            'id'    => 'yz_header_layout',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'header_layouts' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}