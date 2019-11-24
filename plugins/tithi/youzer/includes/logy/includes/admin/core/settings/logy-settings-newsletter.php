<?php
/**
 * Newsletter Settings
 */
function logy_newsletter_settings() {

    global $Yz_Settings;

    // Get Tutorial Url
    $tutorial_url = 'http://www.bigpixels.com/where-can-i-find-my-mailchimp-api-key-and-list-id/';

    $Yz_Settings->get_field(
        array(
            'title'     => __( 'How to Get Your MailChimp API Key & List ID?', 'youzer' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'yz_msgbox_mailchimp',
            'msg'       => sprintf( __( 'To learn how to get your api key and list id Visit the tutorial <strong><a href="%s">How to Get MailChimp Api Key and List ID.</a></strong>', 'youzer' ), $tutorial_url )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Mailchimp Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable MailChimp', 'youzer' ),
            'desc'  => __( 'enable mailchimp integration', 'youzer' ),
            'id'    => 'yz_enable_mailchimp',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'MailChimp API Key', 'youzer' ),
            'desc'  => __( 'the mailchimp api key', 'youzer' ),
            'id'    => 'yz_mailchimp_api_key',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'MailChimp List ID', 'youzer' ),
            'desc'  => __( 'the mailchimp list id', 'youzer' ),
            'id'    => 'yz_mailchimp_list_id',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Mailster Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'enable Mailster', 'youzer' ),
            'desc'  => __( 'enable mailster integration', 'youzer' ),
            'id'    => 'yz_enable_mailster',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( "Mailsters List ID's", 'youzer' ),
            'desc'  => __( "type the mailster list id, use ',' to separate ids example : 1,2", 'youzer' ),
            'id'    => 'yz_mailster_list_ids',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}