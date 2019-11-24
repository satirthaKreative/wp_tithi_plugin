<?php
 
/**
 * # Get Option Array Values
 */
function yz_get_select_options( $option_id ) {

	// Set Up Variables
    $array_values  = array();
    $option_value  = get_option( $option_id );

    // Get Default Value
    if ( ! $option_value ) {
        $Yz_default_options = yz_standard_options();
        $option_value = $Yz_default_options[ $option_id ];
    }

    foreach ( $option_value as $key => $value ) {
    	$array_values[ $value ] = $value;
    }

    return $array_values;
}

/**
 * Add Links to the Admin Bar
 */
function yz_admin_bar_pages( $wp_admin_bar ) {
    
    if ( ! is_user_logged_in() ) {
        return false;
    }

    // Add 'Youzer Panel' Bar Link.
    if ( is_super_admin() ) {
        $youzer_panel = array(
            'parent' => 'appearance',
            'id'     => 'youzer-panel',
            'title'  =>  __( 'Youzer Panel', 'youzer' ),
            'href'   => admin_url( 'admin.php?page=youzer-panel' ),
        );
        $wp_admin_bar->add_node( $youzer_panel );
    }
}

add_action( 'admin_bar_menu', 'yz_admin_bar_pages', 999 );

/**
 * Top Bar Youzer Icon Css
 */
function yz_bar_icons_css() {

    // Show "Youzer Panel" Bar Icon
    if ( is_super_admin() ) {

        echo '<style>
            #adminmenu .toplevel_page_youzer-panel img {
                padding-top: 5px !important;
            }
            </style>';
    }

}

add_action( 'wp_head','yz_bar_icons_css' );
add_action( 'admin_head','yz_bar_icons_css' );

/**
 * Register & Load Youzer widgets
 */
function yz_load_author_widget() {
    register_widget( 'YZ_Author_Widget' );
    register_widget( 'YZ_Group_Rss_Widget' );
    register_widget( 'YZ_My_Account_Widget' );
    register_widget( 'YZ_Notifications_Widget' );
    register_widget( 'YZ_Post_Author_Widget' );
    register_widget( 'YZ_Activity_Rss_Widget' );
    register_widget( 'YZ_Smart_Author_Widget' );
    register_widget( 'YZ_Group_Admins_Widget' );
    register_widget( 'YZ_Group_Mods_Widget' );
    register_widget( 'YZ_Group_Description_Widget' );
    register_widget( 'YZ_Group_Suggestions_Widget' );
    register_widget( 'YZ_Friend_Suggestions_Widget' );
    register_widget( 'YZ_Verified_Users_Widget' );
}

add_action( 'widgets_init', 'yz_load_author_widget' );

/**
* Add Documentation Submenu.
*/
function yz_add_documentation_submenu() {

    global $submenu;
    
    // Add Documentation Url
    $documentation_url = 'http://kainelabs.com/docs/youzer/';

    // Add Documentation Menu.
    $submenu['youzer-panel'][] = array(
        __( 'Documentation','youzer' ),
        'manage_options',
        $documentation_url
    );

}

add_action( 'admin_menu', 'yz_add_documentation_submenu', 9999 );

/**
 * Get Panel Profile Fields.
 */
function yz_get_panel_profile_fields() {

    // Init Panel Fields.
    $panel_fields = array();

    // Get All Fields.
    $all_fields = yz_get_all_profile_fields();

    foreach ( $all_fields as $field ) {

        // Get ID.
        $field_id = $field['id'];

        // Add Data.
        $panel_fields[ $field_id ] = $field['name'];

    }

    // Add User Login Option Data.
    $panel_fields['user_login'] = __( 'Username', 'youzer' );

    return $panel_fields;
}

/**
 * Get Panel Profile Fields.
 */
function yz_get_user_tags_xprofile_fields() {

    // Init Panel Fields.
    $xprofile_fields = array();

    // Get xprofile Fields.
    $fields = yz_get_bp_profile_fields();

    foreach ( $fields as $field ) {

        // Get ID.
        $field_id = $field['id'];

        // Add Data.
        $xprofile_fields[ $field_id ] = $field['name'];

    }

    return $xprofile_fields;
}

/**
 * Check if page is an admin page  tab
 */
function yz_is_panel_tab( $page_name, $tab_name ) {

    if ( is_admin() && isset( $_GET['page'] ) && isset( $_GET['tab'] ) && $_GET['page'] == $page_name && $_GET['tab'] == $tab_name ) {
        return true;
    }

    return false;
}
