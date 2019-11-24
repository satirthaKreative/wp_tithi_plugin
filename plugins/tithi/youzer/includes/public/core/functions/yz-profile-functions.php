<?php
 /**
 * # Get Profile Layout
 */
function yz_get_profile_layout() {

    // Set Up Variable
    $header_layout = yz_options( 'yz_header_layout' );

    if ( false !== strpos( $header_layout, 'yzb-author' ) ) {
        $profile_layout = 'yz-vertical-layout';
    } else {
        $profile_layout = 'yz-horizontal-layout';
    }

    return $profile_layout;
}

/**
 * # Get Profile Class.
 */
function yz_get_profile_class() {

    // New Array
    $profile_class = array();

    // Get Profile Layout
    $profile_class[] = yz_get_profile_layout();

    // Get Profile Width Type
    $profile_class[] = 'yz-wild-content';

    // Get Tabs List Icons Style
    $profile_class[] = yz_options( 'yz_tabs_list_icons_style' );

    // Get Elements Border Style.
    $profile_class[] = 'yz-wg-border-' . yz_options( 'yz_wgs_border_style' );

    // Get Navbar Layout
    $navbar_layout = yz_options( 'yz_vertical_layout_navbar_type' );

    // Get Page Buttons Style
    $profile_class[] = 'yz-page-btns-border-' . yz_options( 'yz_buttons_border_style' );

    // Add Vertical Wild Navbar. 
    if ( yz_is_wild_navbar_active() ) { 
        $profile_class[] = "yz-vertical-wild-navbar";
    }

    // Get Profile 404 Page Class
    $profile_class[] = yz_is_404_profile() ? ' yz-404-profile' : null;

    return yz_generate_class( $profile_class );
}

/**
 * Check is Wild Navbar Activated
 */
function yz_is_wild_navbar_active() {

    // Get Profile Layout
    $profile_layout = yz_get_profile_layout();

    // Get Navbar Layout
    $navbar_layout = yz_options( 'yz_vertical_layout_navbar_type' );

    // Add Vertical Wild Navbar. 
    if ( 'yz-vertical-layout' == $profile_layout && 'wild-navbar' == $navbar_layout ) { 
        return true;
    }

    return false;
}

/**
 * # Navbar Settings Menu.
 */
function yz_get_social_buttons( $user_id = false ) {

    if ( ! is_user_logged_in() ) {
        return;
    }
    
    if ( ! bp_is_active( 'friends' ) && ! bp_is_active( 'messages' ) ) {
        return false;
    }

    ?>

    <div id="item-header" class="yz-social-buttons">
        <?php 
            if ( bp_is_active( 'friends' ) ) {
                bp_add_friend_button( $user_id );
            }
        ?>

        <?php do_action( 'yz_social_buttons', $user_id );  ?>
        
        <?php
            if ( ! yz_is_bpfollowers_active() ) {
                yz_send_private_message_button( $user_id );
            }
        ?>
    </div>

    <?php
}

/**
 * # User Quick Buttons.
 */
function yz_user_quick_buttons( $user_id = false ) { ?>

    <div class="yz-quick-buttons">        

        <?php if ( bp_is_active( 'friends' ) ) : ?>
            
            <?php 

                // Get Buttons Data
                $friends_url = bp_loggedin_user_domain() . bp_get_friends_slug();
                $friend_requests = bp_friend_get_total_requests_count();
                $friends_link = ( $friend_requests > 0 ) ? $friends_url . '/requests' : $friends_url;

            ?>

            <a href="<?php echo $friends_link; ?>" class="yz-button-item yz-friends-btn">
                <span class="dashicons dashicons-groups"></span>
                <?php if ( $friend_requests > 0 ) : ?>
                    <div class="yz-button-count"><?php echo $friend_requests; ?></div>
                <?php endif; ?>
            </a>

        <?php endif; ?>

        <?php if ( bp_is_active( 'messages' ) ) : ?>
            
            <?php $msgs_nbr = bp_get_total_unread_messages_count(); ?>
            
            <a href="<?php echo bp_nav_menu_get_item_url( 'messages' ); ?>" class="yz-button-item yz-messages-btn">
                <span class="dashicons dashicons-email-alt"></span>
                <?php if ( $msgs_nbr > 0 ) : ?>
                <div class="yz-button-count"><?php echo $msgs_nbr; ?></div>
                <?php endif; ?>
            </a>

        <?php endif; ?>
        
        <?php if ( bp_is_active( 'notifications' ) ) : ?>
    
            <?php $notification_nbr = bp_notifications_get_unread_notification_count(); ?>
            
            <a href="<?php echo bp_nav_menu_get_item_url( 'notifications' ); ?>" class="yz-button-item yz-notification-btn">
                <i class="fas fa-globe-asia"></i>
                <?php if ( $notification_nbr > 0 ) : ?>
                <div class="yz-button-count"><?php echo $notification_nbr; ?></div>
                <?php endif; ?>
            </a>

        <?php endif; ?>

    </div>

    <?php
}

/**
 * # Navbar Settings Menu.
 */
function yz_account_settings_menu() {
    
	do_action( 'yz_profile_navbar_right_area' );
	
    // Get Header Layout.
    $header_layout = yz_get_profile_layout();

    if ( ! bp_is_my_profile() && 'yz-horizontal-layout' == $header_layout  ) {
        yz_get_social_buttons();
        return false;
    }

    if ( ! bp_is_my_profile() ) {
        return false;
    }

    global $Youzer;

    // Get User Photo
    $profile_photo = bp_core_fetch_avatar( 
        array(
            'item_id' => bp_displayed_user_id(),
            'type'    => 'full',
            'html'    => false,
        )
    );

    ?>

    <div class="yz-settings-area">

        <?php 

            // Get Navbar Quick Buttons.
            if ( 'yz-horizontal-layout' == $header_layout || yz_is_wild_navbar_active() ) {
                yz_user_quick_buttons();
            }

        ?>
        
        <div class="yz-nav-settings">
            <div class="yz-settings-img" style="background-image: url(<?php echo $profile_photo; ?>)"></div>
            <i class="fas fa-angle-down yz-settings-icon"></i>
        </div>

        <?php $Youzer->user->settings(); ?>
        
    </div>

    <?php

}

/**
 * # Add Login Button to Profile Page.
 */
function yz_sidebar_login_button() {

    // Get Login Button Visibility Option.
    $hide_button = ( 'off' == yz_options( 'yz_profile_login_button' ) ) ? true : false;

    // Check Visibility Requirements.
    if ( $hide_button || 'yz-vertical-layout' == yz_get_profile_layout() || is_user_logged_in() ) {
        return false;
    }

    global $Youzer, $wp;

    // Get Box Data Attribute
    $box_data = $Youzer->widgets->get_loading_effect( 'fadeInDown' );

    // Get Box Class Name.
    $box_class[] = 'yz-profile-login';

    // Get Effect Style
    $box_class[] = $Youzer->widgets->get_loading_effect( 'fadeInDown', 'class' );

    ?>

    <a href="<?php echo yz_get_login_page_url(); ?>" data-show-youzer-login="true" class="<?php echo yz_generate_class( $box_class ); ?>" <?php echo $box_data ?>>
        <i class="fas fa-user-circle"></i>
        <?php _e( 'Sign in to your account', 'youzer' ); ?>
    </a>

    <?php

}

add_action( 'yz_profile_sidebar', 'yz_sidebar_login_button', 1 );

/**
 * Get Post Thumbnail
 */
function yz_post_img() {

    global $post;

    // Get Post Format
    $post_format = get_post_format();
    $post_format = ! empty( $post_format ) ? $post_format : 'standard';

    if ( has_post_thumbnail() ) {

        // Get Data
        $post_thumb = get_the_post_thumbnail_url( 'large' );

    ?>

    <div class="yz-post-img" style="background-image: url(<?php echo $post_thumb; ?>);"></div>

    <?php

    } elseif ( ! has_post_thumbnail() ) {
        echo '<div class="ukai-alt-thumbnail">';
        echo '<div class="thumbnail-icon"><i class="'. yz_get_format_icon( $post_format ) .'"></i></div>';
        echo '</div>';
    }
}

/**
 * # Get Posts Excerpt.
 */
function yz_get_excerpt( $content, $limit = 12 ) {

    $excerpt = wp_strip_all_tags( $content );

    $excerpt = explode( ' ', $excerpt, $limit );

    if ( count( $excerpt ) >= $limit ) {
        array_pop( $excerpt );
        $excerpt = implode( " ", $excerpt ) . '...';
    } else {
        $excerpt = implode( " ", $excerpt );
    }

    $excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );

    return $excerpt;
}

/**
 * # Get Pagination Loading Spinner.
 */
function yz_loading() {

    ?>

    <div class="yz-loading">
        <div class="youzer_msg wait_msg">
            <div class="youzer-msg-icon">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <span><?php _e( 'Please wait ...', 'youzer' ); ?></span>
        </div>
    </div>

    <?php

}

/**
 * # Get Post Thumbnail
 */
function yz_get_post_thumbnail( $args = false ) {

    $widget = isset( $args['widget'] ) ? $args['widget'] : 'post';

    if ( 'post' == $widget ) {

        // Get Post Data
        $post_id  = isset( $args['post_id'] ) ? $args['post_id'] : false;
        $img_size = isset( $args['img_size'] ) ? $args['img_size'] : 'large'; 

        if ( $post_id ) {
            $img_id  = get_post_thumbnail_id( $post_id );
            $img_url = wp_get_attachment_image_src( $img_id , $img_size );
            if ( ! empty( $img_url[0] ) ) {
                echo '<div class="yz-post-thumbnail" style="background-image: url(' . $img_url[0] . ');"></div>';
            } else {
                // Get Post Format
                $format = get_post_format();
                $format = ! empty( $format ) ? $format : 'standard';
                echo '<div class="yz-no-thumbnail">';
                echo '<div class="thumbnail-icon"><i class="'. yz_get_format_icon( $format ) .'"></i></div>';
                echo '</div>';
            }
        }
    } else {
        // Setup Variables.
        $img_url = isset( $args['img_url'] ) ? $args['img_url'] : false;
        if ( $img_url ) {
            echo "<div class='yz-$widget-thumbnail' style='background-image: url( $img_url );'></div>";
        } else {
            echo '<div class="yz-no-thumbnail">';
            echo '<div class="thumbnail-icon"><i class="fas fa-image"></i></div>';
            echo '</div>';
        }
    }

}

/**
 * # Get Post Categories
 */
function yz_get_post_categories( $post_id , $hide_icon = false ) {

    $post_categories = get_the_category_list( ', ', '', $post_id );

    if ( $post_categories ) {
        echo '<li>';
        if ( 'on' == $hide_icon ) {
            echo '<i class="fas fa-tags"></i>';
        }
        echo $post_categories;
        echo '</li>';
    }

}

/**
 * # Get Project Tags
 */
function yz_get_project_tags( $tags_list ) {

    if ( ! $tags_list ) {
        return false;
    }

    ?>

    <ul class="yz-project-tags">
        <?php
            foreach( $tags_list as $tag ) {
                echo "<li><span class='yz-tag-symbole'>#</span>$tag</li>";
            }
        ?>
    </ul>

    <?php

}

/**
 * Check if is widget = AD widget
 */
function yz_is_ad_widget( $widget_name ) {
    if ( false !== strpos( $widget_name, 'yz_ad_' ) ) {
        return true;
    }
    return false;
}

/**
 * Check if is widget = Custom widget
 */
function yz_is_custom_widget( $widget_name ) {
    if ( false !== strpos( $widget_name, 'yz_custom_widget_' ) ) {
        return true;
    }
    return false;
}

/**
 * Check if tab is a Custom Tab.
 */
function yz_is_custom_tab( $tab_name ) {
    if ( false !== strpos( $tab_name, 'yz_custom_tab_' ) ) {
        return true;
    }
    return false;
}

/**
 * # Check Link HTTP .
 */
function yz_esc_url( $url ) {
    $url = esc_url( $url );
    $disallowed = array( 'http://', 'https://' );
    foreach( $disallowed as $protocole ) {
        if ( strpos( $url, $protocole ) === 0 ) {
            return str_replace( $protocole, '', $url );
        }
    }
    return $url;
}

/**
 * #  Enable Widgets Shortcode.
 */
add_filter( 'widget_text', 'do_shortcode' );

/**
 * # Get Post Format Icon.
 */
function yz_get_format_icon( $format = "standard" ) {

    switch ( $format ) {
        case 'video':
            return "fas fa-video";
            break;

        case 'image':
            return "fas fa-image";
            break;

        case 'status':
            return "fas fa-pencil-alt";
            break;

        case 'quote':
            return "fas fa-quote-right";
            break;

        case 'link':
            return "fas fa-link";
            break;

        case 'gallery':
            return "fas fa-images";
            break;

        case 'standard':
            return "fas fa-file-alt";
            break;

        case 'audio':
            return "fas fa-volume-up";
            break;

        default:
            return "fas fa-pencil-alt";
            break;
    }
}

/**
 * Make Profile Tab Private for other users.
 */
function yz_hide_profile_menu_tabs() {

    if ( bp_is_user() && ! is_super_admin() && ! bp_is_my_profile() ) {
        bp_core_remove_nav_item( bp_get_profile_slug() );
    }

}

add_action( 'bp_setup_nav', 'yz_hide_profile_menu_tabs', 15 );

/**
 * Get Posts Tab Content
 */
function yz_profile_posts_tab_screen() {

    global $Youzer;

    // Call Posts Tab Content.
    add_action( 'bp_template_content', array( &$Youzer->tabs->posts, 'tab' ) );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Comments Tab Content
 */
function yz_profile_comments_tab_screen() {

    global $Youzer;

    // Call Posts Tab Content.
    add_action( 'bp_template_content', array( &$Youzer->tabs->comments, 'tab' ) );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Widgets Settings Tab Content
 */
function yz_profile_widgets_settings_tab_screen() {

    global $Youzer;

    // Call Posts Tab Content.
    add_action( 'bp_template_content', array( &$Youzer->account, 'get_widgets_settings' ) );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Custom Tab Content
 */
function yz_profile_custom_tab_screen() {

    global $Youzer;

    // Call Posts Tab Content.
    add_action( 'bp_template_content', array( &$Youzer->tabs->custom, 'tab' ) );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Overview Tab Content
 */
function yz_profile_overview_tab_screen() {

    global $Youzer;

    // Call Posts Tab Content.
    add_action( 'bp_template_content', array( &$Youzer->tabs->overview, 'tab' ) );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Infos Tab Content
 */
function yz_profile_infos_tab_screen() {

    global $Youzer;

    // Call Posts Tab Content.
    add_action( 'bp_template_content', array( &$Youzer->tabs->info, 'tab' ) );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get User Profile Page.
 */
function yz_get_user_profile_page( $slug = false, $user_id = false ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get User Profile Url.
    $page_url = bp_core_get_user_domain( $user_id );

    if ( $slug ) {
        $page_url = $page_url . $slug;
    }

    return $page_url;
}

/**
 * Display Profile
 */
function yz_display_profile() {

    if ( 'off' == yz_options( 'yz_allow_private_profiles' ) ) {
        return true;
    }

    // Get Profile Visitbily.
    $display_profile = yz_data( 'yz_enable_private_account', bp_displayed_user_id() );
    $profile_visibility = $display_profile ? $display_profile : 'off';

    if ( 'off' == $profile_visibility ) {
        return true;
    }

    if ( yz_displayed_user_is_friend() ) {
        return true;
    }

    return false;
}

/**
 * Private Account Message.
 */
function yz_private_account_message() { ?>

    <div id="yz-not-friend-message">
        <i class="fas fa-user-secret"></i>
        <strong><?php _e( 'Private Account', 'youzer' ); ?></strong>
        <p><?php _e( 'You must be friends in order to access this profile.', 'youzer' ); ?></p>
    </div>

    <?php
}

/**
 * Change Cover Image Size.
 */
function yz_attachments_get_cover_image_dimensions( $wh ) {
    return array( 'width' => 1350, 'height' => 350 );
}

add_filter( 'bp_attachments_get_cover_image_dimensions', 'yz_attachments_get_cover_image_dimensions' );

/**
 * Display Spammer Profile as 404 Profile Page
 */
function yz_show_spammer_404() {

    if ( bp_displayed_user_id() && bp_is_user_spammer( bp_displayed_user_id() ) && ! bp_current_user_can( 'bp_moderate' ) ) {
        return true;
    }

    return false;
}

/**
 * Check is Page: Profile 404
 */
function yz_is_404_profile() {

    if ( yz_show_spammer_404() ) {
        return true;
    }

    global $wp_query;

    // Get Members Slug
    $members_slug = bp_get_members_slug();

    // Get Page Path.
    $page_path = isset( $wp_query->query['pagename'] ) ? $wp_query->query['pagename'] : null;

    if ( ! $page_path ) {
        return false;
    }

    // Get Sub Pages
    $sub_pages = explode( '/', $page_path );

    // Get Current Component.
    $component = isset( $sub_pages[0] ) ? $sub_pages[0] : null;

    if ( $component == $page_path ) {
        return;
    }

    // Get Buddypresss Values
    $bp = buddypress();

    // Get User ID.
    $user_id = ! empty( $bp->displayed_user->id ) ? $bp->displayed_user->id : 0;

    // Check if it's a 404 profile
    if ( strcasecmp( $members_slug, $component ) == 0 && 0 == $user_id ) {
        return true;
    }

    return false;
}

/**
 * 404 Porfile Template
 */
function yz_404_profile_template() {

    // Get Header
    get_header();

    // Get Profile Template.
    include YZ_TEMPLATE . 'profile-template.php';

    // Get Footer
    get_footer();
    
}

/**
 * # Get 404 Profile Template
 */
function yz_get_404_profile_template( $template ) {

    if ( is_404() && yz_is_404_profile() ) {

        if ( ! yz_show_spammer_404() ) {

            global $wp_query;

            status_header( 200 );
            
            // Mark Page As 404.
            $wp_query->is_404 = false;

        }

        return yz_404_profile_template();

    }

    return $template;
}

add_filter( 'template_include', 'yz_get_404_profile_template' );

/**
 * Replace Author Url By Buddypress Profile Url.
 */
function yz_edit_author_link_url( $link, $author_id, $author_nicename ) {
    return bp_core_get_user_domain( $author_id );
}

add_filter( 'author_link', 'yz_edit_author_link_url', 10, 3 );

/**
 * Redirect Author Page to Buddypress Profile Page.
 */
function yz_redirect_author_page_to_bp_profile() {

    if ( is_author() && function_exists( 'bp_core_redirect' ) ) {

        // Get Author ID.
        $author_id = get_queried_object_id();

        // Redirect.
        bp_core_redirect( bp_core_get_user_domain( $author_id ) );

    }

}

add_action( 'template_redirect', 'yz_redirect_author_page_to_bp_profile', 5 );

/**
 * Set Default Profile Avatar.
 */
function yz_set_default_profile_avatar( $avatar ) {

    // Get Default Avatar.
    $default_avatar = yz_options( 'yz_default_profiles_avatar' );

    if ( empty( $default_avatar ) ) {
        return $avatar;
    }

    return $default_avatar;

}

add_filter( 'bp_core_default_avatar_user', 'yz_set_default_profile_avatar', 10 );

/**
 * Check if User Has Gravatar
 */
function yz_user_has_gravatar( $email_address ) {

    // Get User Hash
    $hash = md5( strtolower( trim ( $email_address ) ) );

    // Build the Gravatar URL by hasing the email address
    $url = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';

    // Now check the headers...
    $headers = @get_headers( $url );

    // If 200 is found, the user has a Gravatar; otherwise, they don't.
    return preg_match( '|200|', $headers[0] ) ? true : false;

}

/**
 * Change Profile Avatar
 */
function yz_edit_profile_default_avatar( $avatar_url = null , $params = null ) {

    // Get Default Avatar.
    $default_avatar = yz_options( 'yz_default_profiles_avatar' );

    if ( empty( $default_avatar ) ) {
        return $avatar_url;
    }

    if (
        isset( $params['email'] )&& ! yz_user_has_gravatar( $params['email'] )&&
        strpos( $avatar_url, 'gravatar' ) !== false
    ) {
        return $default_avatar;
    }

    // Return Avatar Url.
    return $avatar_url;
}

add_filter( 'bp_core_fetch_avatar_url', 'yz_edit_profile_default_avatar', 99, 2 );

/**
 * Add Profiles Open Graph Support.
 */
function yz_profiles_open_graph() {

    if ( ! bp_is_user() || bp_is_user_activity() ) {
        return false;
    }

    global $Youzer;

    // Get Displayed Profile user id.
    $user_id = bp_displayed_user_id();

    // Get Username
    $user_name = bp_core_get_user_displayname( $user_id );
    
    // Get User Cover Image
    $user_image = bp_attachments_get_attachment( 'url', array('object_dir' => 'members', 'item_id' => $user_id ) );

    // Get Avatar if Cover Not found.
    if ( empty( $user_image ) ) {
        
        $profile_avatar = bp_core_fetch_avatar(
            array( 'item_id' => $user_id, 'type' => 'full', 'html' => false, 'no_grav' => true )
        );

        $user_image = apply_filters( 'yz_og_profile_default_thumbnail', $profile_avatar );

    }

    // Get User Description.
    $user_desc = get_the_author_meta( 'description', $user_id );

    // Get Page Url !
    $url = bp_core_get_user_domain( $user_id );

    // if description empty get about me description
    if ( empty( $user_desc ) ) {
        $user_desc = get_the_author_meta( 'wg_about_me_bio', $user_id );
    } 

    yz_get_open_graph_tags( 'profile', $url, $user_name, $user_desc, $user_image );

}

add_action( 'wp_head', 'yz_profiles_open_graph' );
