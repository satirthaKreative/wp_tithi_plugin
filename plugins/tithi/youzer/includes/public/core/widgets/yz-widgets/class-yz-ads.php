<?php

class YZ_Ads {

    function __construct() {
    }

    /**
     * # Ad Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'widget_name'     => 'ad',
            'widget_icon'     => 'fas fa-bullhorn',
            'widget_title'    => 'sponsored',
            'widget_function' => 'yz_ad_widget',
            'load_effect'     => yz_options( 'yz_ads_load_effect' ),
        );

        // Filter
        $args = apply_filters( 'yz_ad_widget_args', $args );

        return $args;
    }
    
    /**
     * # Content.
     */
    function widget( $ad_name ) {

        // Get ADS.
        $ads = yz_options( 'yz_ads' );

        // Get Widget Ad.
        $ad = $ads[ $ad_name ];

        // Filter Ad Widget
        $ad = apply_filters( 'youzer_edit_ad', $ad );

        // Get data
        $ad_type   = $ad['type'];
        $ad_code   = $ad['code'];
        $ad_url    = esc_url( $ad['url'] );
        $ad_banner = esc_url( $ad['banner'] );

        // Get AD content.
        if ( 'banner' == $ad_type ) {
            $ad_content = "<a href='$ad_url' target='_blank'><img src='$ad_banner'></a>";
        } elseif ( 'adsense' == $ad_type ) {
            $ad_content = urldecode( $ad_code );
        }

        // Display AD.
        echo "<div class='yz-ad-box'>$ad_content</div>";
    }

    /**
     * Get Ad Args.
     */
    function get_args( $widget_name ) {

        // Get ads
        $get_ads = yz_options( 'yz_ads' );

        // AD Args.
        $args = array(
            'widget_name'       => 'ad',
            'widget_icon'       => 'bullhorn',
            'widget_title'      => 'sponsored',
            'function_options'  => $widget_name,
            'widget_function'   => 'yz_ad_widget',
            'load_effect'       => yz_options( 'yz_ads_load_effect' ),
            'display_title'     => $get_ads[ $widget_name ]['is_sponsored']
        );

        return $args;
    }

    /**
     * Get ADS data.
     */
    function get_ads_data( $ad_name, $data_type ) {
        $ads = yz_options( 'yz_ads' );
        return $ads[ $ad_name ][ $data_type ];
    }

    /**
     * Check if key is exist.
     */
    function is_key_exist( $array, $key ) {
        $is_exist = false;
        foreach ( $array as $item ) {
            $is_exist = array_key_exists( $key, $item );
            if ( $is_exist ) {
                break;
            }
        }
        return $is_exist;
    }

    /**
     * Get Exist ADS widgets
     */
    function get_ads_widgets( $widgets ) {

        // Set Up new array
        $ads_widgets = array();

        foreach ( $widgets as $widget => $data ) {
            // If key contains 'yz_ad_'.
            if ( false !== strpos( key( $data ), 'yz_ad_' ) ) {
                $ads_widgets[] = $data;
            }
        }

        return $ads_widgets;
    }

}