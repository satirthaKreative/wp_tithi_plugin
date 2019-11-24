<?php

/**
 * # Check if Username Already Exist.
 */
function yz_username_exist() {

    // Get Profile Username.
    $yz_uzer = get_query_var( 'yz_user' );

    // Convert %20 to Space.
    $yz_uzer = str_replace( '%20', ' ', $yz_uzer );

    if ( ! empty( $yz_uzer ) ) {
        return username_exists( $yz_uzer );
    } elseif ( empty( $yz_uzer ) && is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        return $current_user->user_login;
    }

    return false;
}

/**
 * # Get User ID By Username
 */
function yz_get_user_id( $username ) {
    $profile_user = get_user_by( 'login', $username );
    return $profile_user->ID;
}

/**
 * Get Private Users ID's.
 */
function yz_get_private_user_profiles() {

    global $wpdb;

    // Sql
    $sql = "SELECT user_id FROM " . $wpdb->base_prefix . "usermeta WHERE meta_key = 'yz_enable_private_account' AND meta_value LIKE '%on%'";

    // Get Result
    $users = $wpdb->get_results( $sql , ARRAY_A );

    // Get List of ID's Only.
    $users_ids = wp_list_pluck( $users, 'user_id' );
    // Remove Current user ID.
    if ( in_array( bp_loggedin_user_id(), $users_ids ) ) {
        // Get ID index.
        $id_index = array_search( bp_loggedin_user_id(), $users_ids );
        unset( $users_ids[ $id_index ] );
    }

    // Remove Friends ID's.
    foreach ( $users_ids as $key => $user_id ) {

        $is_friend = BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $user_id );

        if ( $is_friend == 'is_friend' ) {
            unset( $users_ids[ $key ] );
        }

    }

    return $users_ids;
}

/**
 * Get Private Users Activity ID.
 */
function yz_get_private_users_activity_ids( $users ) {

    global $bp, $wpdb;

    // If the given users is array convert it to string.
    if ( is_array( $users ) ) {
        $users = implode( ',', array_map( 'absint', $users ) );
    }

    // Get SQL.
    $sql = "SELECT id FROM {$bp->activity->table_name} WHERE user_id IN ( $users )";

    // Get Result
    $activities = $wpdb->get_results( $sql , ARRAY_A );

    // Return Array List.
    $activities_ids = wp_list_pluck( $activities, 'id' );

    return $activities_ids;

}

/**
 * # Check if User Have Social Networks Accounts.
 */
function is_user_have_networks( $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : null;

    // Get Social Networks
    $social_networks = yz_options( 'yz_social_networks' );

    if ( empty( $social_networks ) ) {
        return false;
    }

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    // Check if there's URL related to the icons.
    foreach ( $social_networks as $network => $data ) {
        $network = yz_data( $network, $user_id );
        if ( ! empty( $network ) ) {
            return true;
        }
    }

    return false;
}

/**
 * # Check if Current Profile Belong To The Current User.
 */
function yz_is_current_user_profile() {

    // If current profile username match the logged-in user return true.
    if ( bp_is_my_profile() ) {
    	return true;
    }

    return false;
}

/**
 * # Get Profile Photo.
 */
function yz_get_user_profile_photo( $img_url = null ) {

    if ( ! empty( $img_url ) ) {
        $img_path = $img_url;
    } else {
        $img_path = bp_core_avatar_default();
    }

    return $img_path;
}

/**
 * # Get Profile Photo.
 */
function yz_get_image_url( $img_url = null ) {

    if ( ! empty( $img_url ) ) {
        $img_path = $img_url;
    } else {
        $img_path = YZ_PA . 'images/default-img.png';
    }

    return $img_path;
}

/**
 * # Get Users Posts Number
 */
function yz_get_user_posts_nbr( $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get Transient Option.
    $transient_id = 'yz_count_user_posts_' . $user_id;

    $user_post_count = get_transient( $transient_id );

    if ( false === $user_post_count ) :

        $user_post_count = count_user_posts( $user_id );
        
        set_transient( $transient_id, $user_post_count, 12 * HOUR_IN_SECONDS );
        
    endif;

    return $user_post_count;
}

/**
 * # Get User Data
 */
function yz_data( $key, $user_id = null ) {

    do_action( 'yz_before_get_data', $key, $user_id );

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : yz_profileUserID();
    
    // Get user informations.
    $user_data = get_the_author_meta( $key, $user_id );

    return apply_filters( 'yz_get_user_data', $user_data, $user_id, $key );

}

/**
 * 404 Profile - User  Data
 */
function yz_404_profile_user_data( $data = null, $user_id = null, $key ) {

    // Check if Page is Profile
    if ( yz_is_404_profile() ) {

        $page_404 = array(
            'user_login'    => __( '404profile', 'youzer' ),
            'last_name'     => __( '404', 'youzer' ),
            'first_name'    => __( 'page', 'youzer' ),
            'phone_nbr'     => __( '404 404 404 404', 'youzer' ),
            'user_city'     => __( '404 city', 'youzer' ),
            'website'       => __( '404 city', 'youzer' ),
            'email_address' => __( '404@error.404', 'youzer' ),
            'user_country'  => __( 'errors', 'youzer' ),
            'user_url'      => __( 'www.page.404', 'youzer' ),
            'cover_photo'   => yz_options( 'yz_profile_404_cover' ),
            'profile_photo' => yz_options( 'yz_profile_404_photo' ),
        );

        // Get 404 Page Data
        $data = isset( $page_404[ $key ] ) ? $page_404[ $key ] : false;

    }
    
    return $data;
}

add_filter( 'yz_get_user_data', 'yz_404_profile_user_data', 10, 3 );

/**
 * Check if user have Posts.
 */
function yz_is_user_have_posts() {

    // Get User Post Count.
    $user_post_count = yz_get_user_posts_nbr();
    
    if ( 0 == $user_post_count ) {
        return false;
    }

    return true;
}

/**
 * Check If User Posts Or Comments Needs Pagination.
 */
function yz_check_pagination( $type ) {

    $blogs_ids = is_multisite() ? get_sites() : array( (object) array( 'blog_id' => 1 ) );

    if ( 'posts' == $type ) {
        
        // Set Up Variables.
        $user_posts_nbr = 0;
        $posts_per_page = yz_options( 'yz_profile_posts_per_page' );

        foreach( $blogs_ids as $b ) {
            switch_to_blog( $b->blog_id );
            $user_posts_nbr += yz_get_user_posts_nbr();
            restore_current_blog();
        }

        // Check if posts require pagination.
        if ( $user_posts_nbr > $posts_per_page ) {
            return true;
        }

    } elseif ( 'comments' == $type ) {

        // Set Up Variables.
        $comments_nbr = yz_get_comments_number( yz_profileUserID() );
        $comments_per_page = yz_options( 'yz_profile_comments_nbr' );

        // Check if comments require pagination.
        if ( $comments_nbr > $comments_per_page ) {
            return true;
        }

    }

    return false;

}

/**
 * Check if user have Comments.
 */
function yz_is_user_have_comments() {
    // Get Comments Number
    $comments_number = yz_get_comments_number( yz_profileUserID() );
    if ( 0 == $comments_number ) {
        return false;
    }
    return true;
}

/**
 * Get User Comments Number
 */
function yz_get_comments_number( $user_id ) {
    // Set Up Variable
    $args = array(
        'user_id' => $user_id,
        'count'   => true
    );
    // Return Comments Number
    return get_comments( $args );
}

/**
 * Check Custom Widgets Content.
 */
function yz_check_custom_widget( $widget_id ) {

    // Get Custom Widgets
    $custom_widgets = yz_options( 'yz_custom_widgets' );

    // Check If the widget ID already exists.
    if ( empty( $custom_widgets ) || ! isset( $custom_widgets[ $widget_id ] ) ) {
        return false;
    }

    // Check if widget have no fields.
    if ( ! isset( $custom_widgets[ $widget_id ]['fields'] ) ) {
        return false;
    }

    // Remove empty fields from the widgets.
    $fields = array_filter( $custom_widgets[ $widget_id ]['fields'] );

    // Check If there's fields inside the custom widget.
    if ( empty( $fields ) ) {
        return false;
    }

    return true;
}

/**
 * Check Image Existence.
 */
function yz_is_image_exists( $img_url ) {

    // Get Images Directory Path.
    $upload_dir = wp_upload_dir();

    // Get Image Path.
    $img_path = $upload_dir['basedir'] . '/youzer/' . wp_basename( $img_url );

    // Check if image is exist.
    if ( file_exists( $img_path ) ) {
        return true;
    }

    return false;
}

/**
 * Print Author Widget Networks Style.
 */
function yz_author_widget_networks_style( $args ) {

    if ( 'author' != $args['target']  ) {
        return false;
    }

    $icon_css = null;
    $networks_type   = $args['networks_type'];
    $social_networks = yz_options( 'yz_social_networks' );

    foreach ( $social_networks as $network => $data ) {

        // Get network Color
        $color = $data['color'];

        // Prepare selector
        $selector = ".yz-icons-$networks_type .$network i";

        if ( 'colorful' == $networks_type ) {
            $property = "background-color";
        } elseif ( 'silver' == $networks_type || 'transparent' == $networks_type ) {
            $selector .= ':hover';
            $property = "background-color";
        } else {
            $selector .= ':hover';
            $property = "color";
        }

        // Prepare Css Code
        $icon_css .= " $selector { $property: $color !important; }";
    }
    echo "<style type='text/css'>$icon_css</style>";
}

add_action( 'youzer_before_networks', 'yz_author_widget_networks_style' );

/**
 * Change Posts Author Url from Nickname to username.
 */
function yz_change_author_link_slug( $link, $author_id, $author_nicename ) {

    // Get User Data.
    $user = get_user_by( 'id', $author_id );

    if ( ! is_object( $user ) ) {
        return $link;
    }

    // Get Username
    $username = $user->user_login;

    // Change Url Slug.
    if ( $username ) {
        $link = str_replace( $author_nicename, $username, $link );
    }

    return $link;
}

add_filter( 'author_link', 'yz_change_author_link_slug', 10, 3 );

/**
 * Check If The Current User Is A Friend Of The Current Profile User.
 */
function yz_displayed_user_is_friend() {

    global $bp;

    if ( bp_is_profile_component() || bp_is_user() ) {

        // Check is friend.
        $check_is_friend = BP_Friends_Friendship::check_is_friend(
            $bp->loggedin_user->id, $bp->displayed_user->id
        );

        if ( ( 'is_friend' != $check_is_friend ) && ( bp_loggedin_user_id() != bp_displayed_user_id() ) ) {

            if ( ! is_super_admin( bp_loggedin_user_id() ) ) {
                return false;
            }

        }

    }

    return true;
}

/**
 * Delete Posts Count Transient.
 */
function yz_delete_user_posts_count_transient( $post_id = null, $post = null, $updated = false ) {
    
    if ( $updated ) {
        return;
    }
    // Get Post Author.
    $post_author = get_post_field( 'post_author', $post_id );
    // Delete Transient.
    delete_transient( 'yz_count_user_posts_' . $post_author );
}

add_action( 'before_delete_post', 'yz_delete_user_posts_count_transient', 1 );
add_action( 'wp_insert_post', 'yz_delete_user_posts_count_transient', 10, 3 );

/**
 * Get User Statistics Details.
 */
function yz_get_user_statistics_details() {

    $statistics = array(
        'posts'     => __( 'Posts', 'youzer' ),
        'comments'  => __( 'Comments', 'youzer' ),
        'views'     => __( 'Views', 'youzer' )
    );

    return apply_filters( 'yz_get_user_statistics_details', $statistics );

}

/**
 * Get User Statistics
 */
function yz_get_user_statistic_number( $user_id, $type ) {

    $value = null;

    switch ( $type ) {
        
        case 'posts':
            $value = yz_get_user_posts_nbr( $user_id );
            break;

        case 'comments':
            $value = yz_get_comments_number( $user_id );
            break;
        
        case 'views':
            $value = youzer()->user->views( $user_id );
            break;
    }

    return apply_filters( 'yz_get_user_statistic_number', $value, $user_id, $type );    
}