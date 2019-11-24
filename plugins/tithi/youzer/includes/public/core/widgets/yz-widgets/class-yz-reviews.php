<?php

class YZ_Reviews {

    /**
     * Constructor
     */
    function __construct() {
        // Filter.
        add_filter( 'yz_profile_widget_visibility', array( $this, 'is_widget_visible' ), 10, 2 );
    }

    /**
     * # Reviews Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'widget_name'   => 'reviews',
            'widget_icon'   => 'far fa-star',
            'widget_title'  => yz_options( 'yz_wg_reviews_title' ),
            'load_effect'   => yz_options( 'yz_reviews_load_effect' ),
            'display_title' => yz_options( 'yz_wg_reviews_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_reviews_widget_args', $args );

        return $args;
    }
    
    /**
     * # Display Widget.
     */
    function is_widget_visible( $visibility, $widget_name ) {

        if ( 'reviews' != $widget_name ) {
            return $visibility;
        }

        if ( ! yz_is_reviews_active() ) {
            return false;
        }

        $reviews_count = youzer()->reviews->query->get_user_reviews_count( bp_displayed_user_id() );

        if ( $reviews_count <= 0 ) {
            return false;
        }

        return true;

    }

    /**
     * # Content.
     */
    function widget() {

        yz_get_user_reviews( array(
            'user_id' => bp_displayed_user_id(),
            'per_page' => yz_options( 'yz_wg_max_reviews_items' ),
            'show_more' => true
        ) );

    }

    /**
     * # Admin Settings.
     */
    function admin_settings() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'general Settings', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'display title', 'youzer' ),
                'id'    => 'yz_wg_reviews_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_reviews_title',
                'desc'  => __( 'add widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
                'id'    => 'yz_reviews_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'reviews per widget', 'youzer' ),
                'id'    => 'yz_wg_max_reviews_items',
                'desc'  => __( 'maximum number of reviews to display', 'youzer' ),
                'type'  => 'number'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    }

}