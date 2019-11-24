<?php

/**
 * # Comments Settings.
 */

function yz_comments_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'comments general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'comments per page', 'youzer' ),
            'id'    => 'yz_profile_comments_nbr',
            'desc'  => __( 'how many comments per page ?', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'comments visibility settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'username', 'youzer' ),
            'id'    => 'yz_display_comment_username',
            'desc'  => __( 'show comments username', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'date', 'youzer' ),
            'id'    => 'yz_display_comment_date',
            'desc'  => __( 'show comments date', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'button', 'youzer' ),
            'id'    => 'yz_display_view_comment',
            'desc'  => __( 'show "view comment" button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'comments styling settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'fullname', 'youzer' ),
            'id'    => 'yz_comment_author_color',
            'desc'  => __( 'comments author color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'username', 'youzer' ),
            'id'    => 'yz_comment_username_color',
            'desc'  => __( 'comments username color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'date', 'youzer' ),
            'id'    => 'yz_comment_date_color',
            'desc'  => __( 'comments date color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'comments text', 'youzer' ),
            'id'    => 'yz_comment_text_color',
            'desc'  => __( 'comments text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'button background', 'youzer' ),
            'id'    => 'yz_comment_button_bg_color',
            'desc'  => __( '"view comment" background', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'button text', 'youzer' ),
            'id'    => 'yz_comment_button_text_color',
            'desc'  => __( '"view comment" text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'button icon', 'youzer' ),
            'id'    => 'yz_comment_button_icon_color',
            'desc'  => __( '"view comment" icon color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}