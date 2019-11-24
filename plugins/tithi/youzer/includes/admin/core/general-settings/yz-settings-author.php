<?php

/**
 * # Author Settings.
 */

function yz_author_box_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable author photo border', 'youzer' ),
            'id'    => 'yz_enable_author_photo_border',
            'desc'  => __( 'enable photo transparent border', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'author box meta icon', 'youzer' ),
            'desc'  => __( 'box user meta icon ?', 'youzer' ),
            'id'    => 'yz_author_meta_icon',
            'type'  => 'icon'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'author box meta', 'youzer' ),
            'desc'  => __( 'under box title meta type ?', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_author_meta_type',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics borders', 'youzer' ),
            'desc'  => __( 'use box statistics borders ?', 'youzer' ),
            'id'    => 'yz_author_use_statistics_borders',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics background', 'youzer' ),
            'desc'  => __( 'use box statistics silver background ?', 'youzer' ),
            'id'    => 'yz_author_use_statistics_bg',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'author box layout', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_author_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'author-box-v1'  => 'yzb-author-v1',
                'author-box-v2'  => 'yzb-author-v2',
                'author-box-v3'  => 'yzb-author-v3',
                'author-box-v4'  => 'yzb-author-v4',
                'author-box-v5'  => 'yzb-author-v5',
                'author-box-v6'  => 'yzb-author-v6'
            )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'author image format', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_author_photo_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Networks settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'display social networks', 'youzer' ),
            'desc'  => __( 'show header social networks', 'youzer' ),
            'id'    => 'yz_display_author_networks',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'networks type', 'youzer' ),
            'id'    => 'yz_author_sn_bg_type',
            'desc'  => __( 'header background type', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'icons_colors' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'networks style', 'youzer' ),
            'id'    => 'yz_author_sn_bg_style',
            'desc'  => __( 'networks background style', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'border_styles' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'statistics settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'first statistic', 'youzer' ),
            'id'    => 'yz_display_author_first_statistic',
            'desc'  => __( 'display first statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'second statistic', 'youzer' ),
            'id'    => 'yz_display_author_second_statistic',
            'desc'  => __( 'display second statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'third statistic', 'youzer' ),
            'id'    => 'yz_display_author_third_statistic',
            'desc'  => __( 'display third statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'first statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'select header first statistic', 'youzer' ),
            'id'    => 'yz_author_first_statistic',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'second statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'select header second statistic', 'youzer' ),
            'id'    => 'yz_author_second_statistic',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'third statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'select header third statistic', 'youzer' ),
            'id'    => 'yz_author_third_statistic',
            'type'  => 'select'
        )
    );
    

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Box Button Styling', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'icon color', 'youzer' ),
            'desc'  => __( 'button icon color', 'youzer' ),
            'id'    => 'yz_abox_button_icon_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'text color', 'youzer' ),
            'desc'  => __( 'button text color', 'youzer' ),
            'id'    => 'yz_abox_button_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'background color', 'youzer' ),
            'desc'  => __( 'button background color', 'youzer' ),
            'id'    => 'yz_abox_button_bg_color',
            'type'  => 'color'
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
            'title' => __( 'box margin top', 'youzer' ),
            'id'    => 'yz_author_box_margin_top',
            'desc'  => __( 'specify author box top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'box margin bottom', 'youzer' ),
            'id'    => 'yz_author_box_margin_bottom',
            'desc'  => __( 'specify author box bottom margin', 'youzer' ),
            'type'  => 'number'
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
            'id'    => 'yz_enable_author_overlay',
            'desc'  => __( 'enable cover dark background', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_author_overlay_opacity',
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
            'id'    => 'yz_enable_author_pattern',
            'desc'  => __( 'enable cover dotted pattern', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_author_pattern_opacity',
            'desc'  => __( 'choose a value between 0.1 - 1', 'youzer' ),
            'type'  => 'number',
            'step'  => 0.01
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}