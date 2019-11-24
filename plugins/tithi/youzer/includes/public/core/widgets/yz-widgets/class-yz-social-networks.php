<?php

class YZ_Networks {

    function __construct() {
        
        // Filters.
        add_filter( 'yz_profile_without_front_end_settings', array( &$this, 'display_networks_widget_edit_icon' ) );
        add_filter( 'yz_profile_widgets_edit_link', array( &$this, 'edit_social_networks_settings_widget_link' ), 10, 2 );

    }
    
    /**
     * # Social Networks Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'widget_icon'   => 'fas fa-share-alt',
            'widget_name'   => 'social_networks',
            'main_data'     => 'yz_social_networks',
            'widget_title'  => yz_options( 'yz_wg_sn_title' ),
            'load_effect'   => yz_options( 'yz_sn_load_effect' ),
            'display_title' => yz_options( 'yz_wg_sn_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_social_networks_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {

        global $Youzer;

        // Set Up Networks Arguments.
        $args = array(
            'target' => 'widget'
        );

        // Call Networks Widget.
        $Youzer->user->networks( $args );
    }

    /**
     * # Settings.
     */
    function settings() {

        global $Yz_Settings;

        // Get Social Networks
        $social_networks = yz_options( 'yz_social_networks' );

        // Unserialize data
        if ( is_serialized( $social_networks ) ) {
            $social_networks = unserialize( $social_networks );
        }

        // Get Args 
        $args = $this->args();

        $Yz_Settings->get_field(
            array(
                'title' => __( 'social networks', 'youzer' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        if ( ! empty( $social_networks )  ) {

            foreach ( $social_networks as $network => $data ) {

                // Get Widget Data
                $name = sanitize_text_field( $data['name'] );

                $Yz_Settings->get_field(
                    array(
                        'title' => $name,
                        'id'    => $network,
                        'type'  => 'text'
                    ), true
                );
            }

        }

        $Yz_Settings->get_field( array( 'type' => 'close' ) );
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
                'id'    => 'yz_wg_sn_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_sn_title',
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_sn_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'networks styling settings', 'youzer' ),
                'class' => 'ukai-box-3cols',
                'type'  => 'openBox'
            )
        );
        $Yz_Settings->get_field(
            array(
                'title' => __( 'networks icons size', 'youzer' ),
                'desc'  => __( 'select icons size', 'youzer' ),
                'id'    => 'yz_wg_sn_icons_size',
                'type'  => 'select',
                'opts'  => $Yz_Settings->get_field_options( 'icons_sizes' )
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'networks background', 'youzer' ),
                'desc'  => __( 'select background type', 'youzer' ),
                'id'    => 'yz_wg_sn_bg_type',
                'type'  => 'select',
                'opts'  => $Yz_Settings->get_field_options( 'wg_icons_colors' )
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'networks border style', 'youzer' ),
                'desc'  => __( 'select networks border style', 'youzer' ),
                'id'    => 'yz_wg_sn_bg_style',
                'type'  => 'select',
                'opts'  =>  $Yz_Settings->get_field_options( 'border_styles' )
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

    /**
     * # Edit Social Networks Links.
     */
    function edit_social_networks_settings_widget_link( $url, $widget_name ) {
        
        if ( $widget_name == 'social_networks' ) {
            return yz_get_profile_settings_url( 'social-networks' );
        }

        return $url;
    }

    /**
     * # Display Networks Edit Icon.
     */
    function display_networks_widget_edit_icon( $widgets ) {
        
        if ( yz_is_account_page() ) {
            return $widgets;
        }

        // Get Key.
        $key = array_search( 'social_networks', $widgets );

        // Delete Widget.
        if ( isset( $widgets[ $key ] ) ) {
            unset( $widgets[ $key ] );
        }

        return $widgets;
    }
}