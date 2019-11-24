<?php

class YZ_Info_Tab {

    /**
     * Constructor
     */
    function __construct() {

        // Add new shortcode !.
        add_shortcode( 'yz_custom_information', array( $this, 'get_user_infos_shortcode' ) );
        
    }

    /**
     * # Tab.
     */
    function tab() {

    	$Yz_Widgets = youzer()->widgets;

        // Get Basic Infos Args
        $basic_infos_args = $Yz_Widgets->basic_infos->args();

    	// Print Basic informations.
    	$Yz_Widgets->yz_widget_core( $basic_infos_args );

        // Get User Profile Widgets
        $this->get_user_infos();

        do_action( 'youzer_after_infos_widgets' );
    }

    /**
     * # Get Custom Widgets functions.
     */
    function get_user_infos() {
        
        if ( ! bp_is_active( 'xprofile' ) ) {
            return false;
        }

        do_action( 'bp_before_profile_loop_content' );
        
        if ( bp_has_profile() ) : while ( bp_profile_groups() ) : bp_the_profile_group();
                
            if ( bp_profile_group_has_fields() ) :
                    
                $group_id = bp_get_the_profile_group_id();

                // Custom infos Widget Arguments
                $custom_infos_args = array(
                    'widget_icon'       => yz_get_xprofile_group_icon( $group_id ),
                    'widget_title'      => bp_get_the_profile_group_name(),
                    'widget_name'       => 'custom_infos',
                );

                youzer()->widgets->yz_widget_core( $custom_infos_args );

        endif; endwhile;
        
        endif;

        do_action( 'bp_after_profile_loop_content' );

    }

    /**
     * # Get Custom Widgets functions.
     */
    function get_user_infos_shortcode( $atts = null ) {

        $options = shortcode_atts( array(
            'user_id' => bp_displayed_user_id(),
            'profile_group_id' => false,
        ), $atts );

        if ( ! bp_is_active( 'xprofile' ) ) {
            return false;
        }

        do_action( 'bp_before_profile_loop_content' );
        
        if ( bp_has_profile( $options ) ) : while ( bp_profile_groups() ) : bp_the_profile_group();
                
            if ( bp_profile_group_has_fields() ) :
                    
                $group_id = bp_get_the_profile_group_id();

                // Custom infos Widget Arguments
                $custom_infos_args = array(
                    'widget_icon'       => yz_get_xprofile_group_icon( $group_id ),
                    'widget_title'      => bp_get_the_profile_group_name(),
                    'widget_name'       => 'custom_infos',
                );

                youzer()->widgets->custom_infos->widget();

        endif; endwhile;
        
        endif;

        do_action( 'bp_after_profile_loop_content' );

    }

    /**
     * Infos settings
     */
    function settings() {

        global $Yz_Settings;
        
        $Yz_Settings->get_field(
            array(
                'title' => __( 'info styling settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'info title', 'youzer' ),
                'desc'  => __( 'info titles color', 'youzer' ),
                'id'    => 'yz_infos_wg_title_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'info value', 'youzer' ),
                'desc'  => __( 'info values color', 'youzer' ),
                'id'    => 'yz_infos_wg_value_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}