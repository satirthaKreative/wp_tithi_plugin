<?php

/**
 * # Emoji Settings.
 */
function yz_emoji_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'posts emoji', 'youzer' ),
            'desc'  => __( 'enable emoji in posts', 'youzer' ),
            'id'    => 'yz_enable_posts_emoji',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'comments emoji', 'youzer' ),
            'desc'  => __( 'enable emoji in comments', 'youzer' ),
            'id'    => 'yz_enable_comments_emoji',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'messages emoji', 'youzer' ),
            'desc'  => __( 'enable emoji in messages', 'youzer' ),
            'id'    => 'yz_enable_messages_emoji',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}