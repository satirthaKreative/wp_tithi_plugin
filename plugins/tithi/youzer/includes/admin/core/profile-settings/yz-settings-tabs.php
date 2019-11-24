<?php

/**
 * # Tabs Settings.
 */

function yz_tabs_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'tabs general settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    // Get Defaut Tab Options.
    $default_tab_options = (array) yz_get_profile_default_nav_options();
    $default_option = array( '' => __( '-- Select Default Tab --', 'youzer' ) );
    $default_tab_options = $default_option + $default_tab_options;

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_display_profile_tabs_count',
            'title' => __( 'Display tabs count', 'youzer' ),
            'desc'  => __( 'show profile tabs count', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_profile_default_tab',
            'title' => __( 'Default Tab', 'youzer' ),
            'desc'  => __( 'choose profile default tab', 'youzer' ),
            'opts'  => $default_tab_options,
            'type'  => 'select'
        )
    );

   $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Tabs
    $custom_tabs = yz_get_profile_primary_nav();

    if ( empty( $custom_tabs ) ) {
        return false;
    }

    // Get Custom Tabs Settings.
    yz_custom_buddypress_tabs_settings( $custom_tabs );
        
    $Yz_Settings->get_field(
        array(
            'title' => __( 'pagination styling settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'numbers color', 'youzer' ),
            'id'    => 'yz_pagination_text_color',
            'desc'  => __( 'pages numbers color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'numbers background', 'youzer' ),
            'id'    => 'yz_pagination_bg_color',
            'desc'  => __( 'pages numbers backgorund', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'active page background', 'youzer' ),
            'id'    => 'yz_pagination_current_bg_color',
            'desc'  => __( 'active page background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'active page number', 'youzer' ),
            'id'    => 'yz_pagination_current_text_color',
            'desc'  => __( 'active page number color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * # Get Custom Buddypress Tabs Settings.
 */
function yz_custom_buddypress_tabs_settings( $custom_tabs ) {

    global $Yz_Settings;

    // Default Tab Values.
    $default_tabs = yz_profile_tabs_default_value();

    foreach ( $custom_tabs as $tab ) {

        // Get Tab Name
        $tab_name = isset( $tab['name'] ) ? $tab['name'] : $tab['slug'];

        // Filter Name.
        $tab_name = _bp_strip_spans_from_title( $tab_name );

        // Get Tab Slug
        $tab_slug = isset( $tab['slug'] ) ? $tab['slug'] : null;

        // Get Tab ID.
        $tab_id = 'yz_ctabs_' . $tab['slug'] . '_icon';

        // Get Default Tab Icon
        $std_icon = isset( $default_tabs[ $tab_slug ] ) ? $default_tabs[ $tab_slug ]['icon'] : 'fas fa-globe';

        // Get Default Tab Visibility
        $std_visibility = isset( $default_tabs[ $tab_slug ] ) ? $default_tabs[ $tab_slug ]['visibility'] : 'on';

        $Yz_Settings->get_field(
            array(
                'title' => sprintf( __( '%s tab', 'youzer' ), $tab_name ),
                'class' => 'ukai-box-3cols kl-accordion-box',
                'type'  => 'openBox',
                'hide'  => true,
            )
        );

        $Yz_Settings->get_field(
            array(
                'type'  => 'checkbox',
                'std'   => $std_visibility,
                'id'    => 'yz_display_' . $tab_slug . '_tab',
                'title' => sprintf( __( 'Display tab', 'youzer' ), $tab_name ),
                'desc'  => sprintf( __( 'show %s tab', 'youzer' ), $tab_name ),
            )
        );

        $Yz_Settings->get_field(
            array(
                'type'  => 'icon',
                'std'   => $std_icon,
                'id'    => 'yz_' . $tab_slug . '_tab_icon',
                'title' => sprintf( __( '%s icon', 'youzer' ), $tab_name ),
                'desc'  => sprintf( __( '%s tab icon', 'youzer' ), $tab_name ),
            )
        );

        $Yz_Settings->get_field(
            array(
                'type'  => 'text',
                'std'   => $tab_name,
                'id'    => 'yz_' . $tab_slug . '_tab_title',
                'title' => sprintf( __( '%s title', 'youzer' ), $tab_name ),
                'desc' => sprintf( __( '%s tab title', 'youzer' ), $tab_name ),
            )
        );

        $Yz_Settings->get_field(
            array(
                'type'  => 'number',
                'std'   => $tab['position'],
                'id'    => 'yz_' . $tab_slug . '_tab_order',
                'title' => sprintf( __( '%s order', 'youzer' ), $tab_name ),
                'desc'  => sprintf( __( '%s tab order', 'youzer' ), $tab_name ),
            )
        );

        if ( in_array( $tab_slug, yz_profile_deletable_tabs() ) ) {
            $Yz_Settings->get_field(
                array(
                    'std'   => 'off',
                    'type'  => 'checkbox',
                    'id'    => 'yz_delete_' . $tab_slug . '_tab',
                    'title' => sprintf( __( 'Delete tab', 'youzer' ), $tab_name ),
                    'desc'  => sprintf( __( 'Delete %s tab', 'youzer' ), $tab_name ),
                )
            );
        }

       $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    }

}

/**
 * # Get Third Party Tabs Settings.
 */
function yz_profile_subtabs_settings() {

    // Get Primary Third Party Tabs.
    $primary_tabs = yz_get_profile_third_party_tabs();

    if ( empty( $primary_tabs ) ) {
        // Get Message.
        $no_subtabs = __( 'Sorry, No Subtabs Settings Exist !' );
        // Print Message.
        echo '<p class="yz-no-content">' . $no_subtabs . '</p>';
        return false;
    }

    // Init Vars.
    $bp = buddypress();

    foreach ( $primary_tabs as $primary_tab ) {

        // Get Tab Slug
        $tab_slug = isset( $primary_tab['slug'] ) ? $primary_tab['slug'] : null;

        // Get Tab Navigation  Menu
        $secondary_tabs = $bp->members->nav->get_secondary( array( 'parent_slug' => $tab_slug ) );

        if ( empty( $secondary_tabs ) ) {
            continue;
        }

        // Get Settings.
        yz_third_party_subtabs_settings( $secondary_tabs, $primary_tab );

    }

}

/**
 * Get Third Party SubTabs Settings
 */
function yz_third_party_subtabs_settings( $tabs, $primary_tab ) {

    global $Yz_Settings;

    // Get Primary Tab Slug
    $primary_slug = isset( $primary_tab['slug'] ) ? $primary_tab['slug'] : null;

    // Get Primary Tab Name
    $primary_name = isset( $primary_tab['name'] ) ? $primary_tab['name'] : $primary_slug;

    $Yz_Settings->get_field(
        array(
            'title' => sprintf( __( '%s Sub Tabs Settings', 'youzer' ), $primary_name ),
            'type'  => 'openBox'
        )
    );

    foreach ( $tabs as $tab ) {

        // Get Tab Name
        $tab_name = isset( $tab['name'] ) ? $tab['name'] : $tab['slug'];

        // Get Tab ID.
        $tab_id = 'yz_ctabs_' . $primary_slug . '_' . $tab['slug'] . '_icon';

        $Yz_Settings->get_field(
            array(
                'std' => 'fas fa-globe',
                'type'  => 'icon',
                'id'    => $tab_id,
                'title' => sprintf( __( '%s icon', 'youzer' ), $tab_name ),
                'desc' => sprintf( __( '%s tab icon', 'youzer' ), $tab_name ),
            )   
        );
    
    }
    
    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}