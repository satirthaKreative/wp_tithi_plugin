<?php

/**
 * # Posts Settings.
 */

function yz_posts_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'posts general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'posts per page', 'youzer' ),
            'id'    => 'yz_profile_posts_per_page',
            'desc'  => __( 'how many posts per page ?', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'posts visibility settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post meta', 'youzer' ),
            'id'    => 'yz_display_post_meta',
            'desc'  => __( 'show post meta', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'meta icons', 'youzer' ),
            'id'    => 'yz_display_post_meta_icons',
            'desc'  => __( 'show post meta icons', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post excerpt', 'youzer' ),
            'id'    => 'yz_display_post_excerpt',
            'desc'  => __( 'show post excerpt', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post date', 'youzer' ),
            'id'    => 'yz_display_post_date',
            'desc'  => __( 'show post date', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post categories', 'youzer' ),
            'id'    => 'yz_display_post_cats',
            'desc'  => __( 'show post categories', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post comments', 'youzer' ),
            'id'    => 'yz_display_post_comments',
            'desc'  => __( 'show comments number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'read more', 'youzer' ),
            'id'    => 'yz_display_post_readmore',
            'desc'  => __( 'show read more button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post styling settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post title', 'youzer' ),
            'id'    => 'yz_post_title_color',
            'desc'  => __( 'post title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post meta', 'youzer' ),
            'id'    => 'yz_post_meta_color',
            'desc'  => __( 'post meta color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post meta icons', 'youzer' ),
            'id'    => 'yz_post_meta_icons_color',
            'desc'  => __( 'meta icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'post excerpt', 'youzer' ),
            'id'    => 'yz_post_text_color',
            'desc'  => __( 'post excerpt color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'read more background', 'youzer' ),
            'id'    => 'yz_post_button_color',
            'desc'  => __( 'read more button color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'read more text', 'youzer' ),
            'id'    => 'yz_post_button_text_color',
            'desc'  => __( 'read more text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'read more icon', 'youzer' ),
            'id'    => 'yz_post_button_icon_color',
            'desc'  => __( 'read more icon color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}