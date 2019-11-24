<?php

/**
 * # Profile General Settings.
 */

function yz_profile_general_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_allow_private_profiles',
            'title' => __( 'Allow Private Profiles', 'youzer' ),
            'desc'  => __( 'allow users to make their profiles private', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'use profile effects ?', 'youzer' ),
            'id'    => 'yz_use_effects',
            'desc'  => __( 'load profile elements with effects', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Login Button ?', 'youzer' ),
            'id'    => 'yz_profile_login_button',
            'desc'  => __( 'show profile sidebar login button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Account Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'bp-disable-account-deletion',
            'title' => __( 'Allow Delete Accounts', 'youzer' ),
            'desc'  => __( 'Allow registered members to delete their own accounts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'desc'  => __( 'show account settings copyright', 'youzer' ),
            'title' => __( 'Enable Account Copyright', 'youzer' ),
            'id'    => 'yz_enable_settings_copyright',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'display account scroll button', 'youzer' ),
            'id'    => 'yz_enable_account_scroll_button',
            'desc'  => __( 'display scroll to top button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Profiles Photos Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Profile Avatar', 'youzer' ),
            'desc'  => __( 'upload default profiles avatar', 'youzer' ),
            'id'    => 'yz_default_profiles_avatar',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Profile Cover', 'youzer' ),
            'desc'  => __( 'upload default profiles cover', 'youzer' ),
            'id'    => 'yz_default_profiles_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}