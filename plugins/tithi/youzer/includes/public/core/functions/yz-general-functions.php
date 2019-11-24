<?php

/**
 * Check if youzer is active.
 */
function youzer_is_active() {
    return true;
}

/**
 * # Is Youzer Membership system is active.
 */
function yz_is_membership_system_active() {

    if ( 'off' != get_option( 'yz_activate_membership_system' ) ) {
        return true;
    }

    return false;

}

/**
 * Is Activity Component
 */
function yz_is_activity_component() {
    
    // Init Var.
    $active = false;

    if ( bp_is_current_component( 'activity' ) || bp_is_single_activity() || bp_is_group_activity() ) {
        $active = true;
    }

    return apply_filters( 'yz_is_activity_component', $active );

}

/**
 * # Get Youzer Page Template.
 */
function yz_youzer_template( $page_template ) {

    // Init Var.
    $enable_youzer_page = bp_current_component() && ! is_404();

    // Filter
    $enable_youzer_page = apply_filters( 'yz_enable_youzer_page', $enable_youzer_page );

    // Check if its youzer plugin page
    if ( $enable_youzer_page ) {
        return YZ_TEMPLATE . 'youzer-template.php';
    }

    return $page_template;

}

add_filter( 'template_include', 'yz_youzer_template', 99999 );

/**
 * Youzer Options
 */
function yz_options( $option_id ) {

    // Get Option Value.
    $option_value = get_option( $option_id );

    // Filter Option Value.
    $option_value = apply_filters( 'youzer_edit_options', $option_value, $option_id );

    if ( ! isset( $option_value ) || empty( $option_value ) ) {
        $Yz_default_options = yz_standard_options();
        if ( isset( $Yz_default_options[ $option_id ] ) ) {
            $option_value = $Yz_default_options[ $option_id ];
        }
    }

    return $option_value;
}

/**
 * # Youzer Standard Options .
 */
function yz_standard_options() {

    $default_options = array(

        // Author Box
        'yz_author_photo_effect'        => 'on',
        'yz_display_author_networks'    => 'on',
        'yz_enable_author_pattern'      => 'on',
        'yz_enable_author_overlay'      => 'on',
        'yz_enable_author_photo_border' => 'on',
        'yz_author_photo_border_style'  => 'circle',
        'yz_author_sn_bg_type'          => 'silver',
        'yz_author_sn_bg_style'         => 'radius',
        'yz_author_meta_type'           => 'location',
        'yz_author_meta_type'           => 'full_location',
        'yz_author_meta_icon'           => 'map-marker',
        'yz_author_layout'              => 'yzb-author-v1',
        'yz_display_author_first_statistic' => 'on',
        'yz_display_author_third_statistic' => 'on',
        'yz_display_author_second_statistic'=> 'on',
        'yz_author_first_statistic' => 'posts',
        'yz_author_third_statistic' => 'views',
        'yz_author_second_statistic'=> 'comments',

        // Author Statistics.
        'yz_author_use_statistics_bg' => 'on',
        'yz_display_widget_networks' => 'on',
        'yz_author_use_statistics_borders' => 'on',

        // User Profile Header  
        'yz_profile_photo_effect'           => 'on',
        'yz_display_header_site'            => 'on',
        'yz_display_header_networks'        => 'on',
        'yz_display_header_location'        => 'on',
        'yz_enable_header_pattern'          => 'on',
        'yz_enable_header_overlay'          => 'on',
        'yz_header_enable_user_status'      => 'on',
        'yz_enable_header_photo_border'     => 'on',
        'yz_header_use_photo_as_cover'      => 'off',
        'yz_header_photo_border_style'      => 'circle',
        'yz_header_sn_bg_type'              => 'silver',
        'yz_header_sn_bg_style'             => 'circle',
        'yz_header_layout'                  => 'hdr-v1',
        'yz_header_meta_type'               => 'full_location',
        'yz_header_meta_icon'               => 'map-marker',
        'yz_header_use_statistics_bg'       => 'on',
        'yz_header_use_statistics_borders'  => 'off',
        'yz_display_header_first_statistic' => 'on',
        'yz_display_header_third_statistic' => 'on',
        'yz_display_header_second_statistic'=> 'on',
        'yz_header_first_statistic'         => 'posts',
        'yz_header_third_statistic'         => 'views',
        'yz_header_second_statistic'        => 'comments',

        // Group Header 
        'yz_group_photo_effect'                 => 'on',
        'yz_display_group_header_privacy'       => 'on',
        'yz_display_group_header_posts'         => 'on',
        'yz_display_group_header_members'       => 'on',
        'yz_display_group_header_networks'      => 'on',
        'yz_display_group_header_activity'      => 'on',
        'yz_enable_group_header_pattern'        => 'on',
        'yz_enable_group_header_overlay'        => 'on',
        'yz_enable_group_header_avatar_border'  => 'on',
        'yz_group_header_use_avatar_as_cover'   => 'on',
        'yz_group_header_sn_bg_type'            => 'silver',
        'yz_group_header_sn_bg_style'           => 'circle',
        'yz_group_header_layout'                => 'hdr-v1',
        'yz_group_header_avatar_border_style'   => 'circle',
        'yz_group_header_use_statistics_bg'     => 'on',
        'yz_group_header_use_statistics_borders'=> 'off',

        // WP Navbar
        'yz_disable_wp_menu_avatar_icon' => 'on',

        // Navbar
        'yz_display_navbar_icons' => 'on',
        'yz_profile_navbar_menus_limit' => 5,
        'yz_navbar_icons_style' => 'navbar-inline-icons',
        'yz_vertical_layout_navbar_type' => 'wild-navbar',

        // Posts Tab
        'yz_profile_posts_per_page'  => 5,
        'yz_display_post_meta'       => 'on',
        'yz_display_post_excerpt'    => 'on',
        'yz_display_post_date'       => 'on',
        'yz_display_post_cats'       => 'on',
        'yz_display_post_comments'   => 'on',
        'yz_display_post_readmore'   => 'on',
        'yz_display_posts_tab'       => 'on',
        'yz_display_post_meta_icons' => 'on',
        'yz_posts_tab_icon'          => 'fas fa-pencil-alt',
        'yz_posts_tab_title'         => __( 'Posts', 'youzer' ),

        // Overview Tab
        'yz_display_overview_tab' => 'on',
        'yz_overview_tab_icon'    => 'fas fa-globe-asia',
        'yz_bookmarks_tab_icon'    => 'fas fa-bookmark',
        'yz_reviews_tab_icon'       => 'fas fa-star',
        'yz_overview_tab_title'   => __( 'Overview', 'youzer' ),

        // Overview Tab
        'yz_display_wall_tab' => 'on',
        'yz_wall_tab_icon'    => 'fas fa-address-card',
        'yz_wall_tab_title'   => __( 'Wall', 'youzer' ),

        // infos Tab
        'yz_display_infos_tab'  => 'on',
        'yz_info_tab_icon'      => 'fas fa-info',
        'yz_info_tab_title' => __( 'Info', 'youzer' ),

        // Comments Tab
        'yz_profile_comments_nbr'     => 5,
        'yz_display_comment_date'     => 'on',
        'yz_display_comments_tab'     => 'on',
        'yz_display_view_comment'     => 'on',
        'yz_display_comment_username' => 'on',
        'yz_comments_tab_icon'        => 'fas fa-comments',
        'yz_comments_tab_title'       => __( 'Comments', 'youzer' ),

        // Widgets Settings
        'yz_display_wg_title_icon' => 'on',
        'yz_use_wg_title_icon_bg'  => 'on',
        'yz_wgs_border_style'      => 'radius',

        // Display Widget Titles
        'yz_wg_sn_display_title'        => 'on',
        'yz_wg_link_display_title'      => 'off',
        'yz_wg_quote_display_title'     => 'off',
        'yz_wg_video_display_title'     => 'on',
        'yz_wg_rposts_display_title'    => 'on',
        'yz_wg_skills_display_title'    => 'on',
        'yz_wg_flickr_display_title'    => 'on',
        'yz_wg_about_me_display_title'  => 'on',
        'yz_wg_services_display_title'  => 'on',
        'yz_wg_portfolio_display_title' => 'on',
        'yz_wg_friends_display_title'   => 'on',
        'yz_wg_reviews_display_title'   => 'on',
        'yz_wg_groups_display_title'    => 'on',
        'yz_wg_instagram_display_title' => 'on',
        'yz_wg_slideshow_display_title' => 'off',
        'yz_wg_user_tags_display_title' => 'off',
        'yz_wg_user_badges_display_title' => 'on',
        'yz_wg_user_balance_display_title' => 'off',

        // Widget Titles
        'yz_wg_post_title'      => __( 'Post', 'youzer' ),
        'yz_wg_link_title'      => __( 'Link', 'youzer' ),
        'yz_wg_video_title'     => __( 'Video', 'youzer' ),
        'yz_wg_quote_title'     => __( 'Quote', 'youzer' ),
        'yz_wg_skills_title'    => __( 'Skills', 'youzer' ),
        'yz_wg_flickr_title'    => __( 'Flickr', 'youzer' ),
        'yz_wg_reviews_title'   => __( 'Reviews', 'youzer' ),
        'yz_wg_friends_title'   => __( 'Friends', 'youzer' ),
        'yz_wg_groups_title'    => __( 'Groups', 'youzer' ),
        'yz_wg_project_title'   => __( 'Project', 'youzer' ),
        'yz_wg_aboutme_title'   => __( 'About me', 'youzer' ),
        'yz_wg_services_title'  => __( 'Services', 'youzer' ),
        'yz_wg_portfolio_title' => __( 'Portfolio', 'youzer' ),
        'yz_wg_instagram_title' => __( 'Instagram', 'youzer' ),
        'yz_wg_user_tags_title' => __( 'User Tags', 'youzer' ),
        'yz_wg_slideshow_title' => __( 'Slideshow', 'youzer' ),
        'yz_wg_rposts_title'    => __( 'Recent posts', 'youzer' ),
        'yz_wg_sn_title'        => __( 'Keep in touch', 'youzer' ),
        'yz_wg_user_badges_title'  => __( 'user badges', 'youzer' ),
        'yz_wg_user_balance_title' => __( 'user balance', 'youzer' ),

        // Social Networks
        'yz_wg_sn_bg_style'   => 'radius',
        'yz_wg_sn_bg_type'    => 'colorful',
        'yz_wg_sn_icons_size' => 'full-width',

        // Badges.
        'yz_wg_max_user_badges_items' => 12,

        // Skills
        'yz_wg_max_skills' => 5,

        // About Me
        'yz_wg_aboutme_img_format' => 'circle',

        // Project
        'yz_display_prjct_meta' => 'on',
        'yz_display_prjct_tags' => 'on',
        'yz_display_prjct_meta_icons' => 'on',
        'yz_wg_project_types' => array(
            __( 'featured project', 'youzer' ),
            __( 'recent project', 'youzer' )
        ),

        // Post
        'yz_display_wg_post_meta'       => 'on',
        'yz_display_wg_post_readmore'   => 'on',
        'yz_display_wg_post_tags'       => 'on',
        'yz_display_wg_post_excerpt'    => 'on',
        'yz_display_wg_post_date'       => 'on',
        'yz_display_wg_post_comments'   => 'on',
        'yz_display_wg_post_meta_icons' => 'on',
        'yz_wg_post_types'              => array(
            __( 'featured post', 'youzer' ),
            __( 'recent post', 'youzer' )
        ),

        // Login Page Settings.
        'yz_login_page' => null,
        'yz_login_page_type' => 'url',
        'yz_enable_ajax_login' => 'off',
        'yz_enable_login_popup' => 'off',
        // 'yz_login_page_url' => wp_login_url(),

        // Services
        'yz_wg_max_services' => 4,
        'yz_display_service_icon' => 'on',
        'yz_display_service_text' => 'on',
        'yz_display_service_title' => 'on',
        'yz_wg_service_icon_bg_format' => 'circle',
        'yz_wg_services_layout' => 'vertical-services-layout',

        // Slideshow
        'yz_wg_max_slideshow_items' => 3,
        'yz_slideshow_height_type' => 'fixed',
        'yz_display_slideshow_title' => 'off',

        // Portfolio
        'yz_wg_max_portfolio_items' => 9,
        'yz_display_portfolio_url'  => 'on',
        'yz_display_portfolio_zoom' => 'on',
        'yz_display_portfolio_title' => 'on',

        // Flickr
        'yz_wg_max_flickr_items' => 6,

        // Friends
        'yz_wg_max_friends_items' => 5,
        'yz_wg_friends_layout' => 'list',
        'yz_wg_friends_avatar_img_format' => 'circle',

        // Groups
        'yz_wg_max_groups_items' => 3,
        'yz_wg_groups_avatar_img_format' => 'circle',

        // Instagram
        'yz_wg_max_instagram_items' => 9,
        'yz_display_instagram_url'  => 'on',
        'yz_display_instagram_zoom' => 'on',
        'yz_display_instagram_title' => 'on',

        // Recent Posts
        'yz_wg_max_rposts' => 3,
        'yz_wg_rposts_img_format' => 'circle',

        // Use Profile Effects
        'yz_use_effects' => 'off',
        'yz_profile_login_button' => 'on',

        // Profile Main Content Available Widgets
        'yz_profile_main_widgets' => array(
            array( 'slideshow'  => 'visible' ),
            array( 'project'    => 'visible' ),
            array( 'skills'     => 'visible' ),
            array( 'portfolio'  => 'visible' ),
            array( 'quote'      => 'visible' ),
            array( 'instagram'  => 'visible' ),
            array( 'services'   => 'visible' ),
            array( 'post'       => 'visible' ),
            array( 'link'       => 'visible' ),
            array( 'video'      => 'visible' ),
            array( 'reviews'    => 'visible' ),
        ),

        // Profile Sidebar Available Widgets
        'yz_profile_sidebar_widgets' => array (
            array( 'user_balance'    => 'visible' ),
            array( 'user_badges'     => 'visible' ),
            array( 'about_me'        => 'visible' ),
            array( 'social_networks' => 'visible' ),
            array( 'friends'         => 'visible' ),
            array( 'flickr'          => 'visible' ),
            array( 'groups'          => 'visible' ),
            array( 'recent_posts'    => 'visible' ),
            array( 'user_tags'       => 'visible' ),
            array( 'email'           => 'visible' ),
            array( 'address'         => 'visible' ),
            array( 'website'         => 'visible' ),
            array( 'phone'           => 'visible' ),
        ),

        // Profile 404
        'yz_profile_404_button' => __( 'Go Back Home', 'youzer' ),
        'yz_profile_404_desc'   => __( "we're sorry the profile you're looking for cannot be found.", 'youzer' ),

        // Profil Scheme.
        'yz_profile_scheme' => 'yz-blue-scheme',
        'yz_enable_profile_custom_scheme' => 'off',

        // Panel Options.
        'yz_enable_panel_fixed_save_btn' => 'on',
        'yz_panel_scheme' => 'uk-yellow-scheme',
        'yz_tabs_list_icons_style' => 'yz-tabs-list-gradient',

        // Tabs Settings.
        'yz_display_profile_tabs_count' => 'on',

        // Panel Messages.
        'yz_msgbox_mailchimp' => 'on',
        'yz_msgbox_captcha' => 'on',
        'yz_msgbox_logy_login' => 'on',
        'yz_msgbox_mail_tags' => 'off',
        'yz_msgbox_mail_content' => 'on',
        'yz_msgbox_ads_placement' => 'on',
        'yz_msgbox_profile_schemes' => 'on',
        'yz_msgbox_profile_structure' => 'on',
        'yz_msgbox_instagram_wg_app_setup_steps' => 'on',
        'yz_msgbox_custom_widgets_placement' => 'on',
        'yz_msgbox_user_badges_widget_notice' => 'on',
        'yz_msgbox_user_balance_widget_notice' => 'on',

        // Account Settings
        'yz_enable_account_scroll_button' => 'on',
        'yz_files_max_size' => 3,

        // Wall Settings
        'yz_enable_youzer_activity_filter' => 'on',
        'yz_enable_wall_url_preview' => 'on',
        'yz_enable_wall_posts_reply' => 'on',
        'yz_enable_wall_posts_likes' => 'on',
        'yz_enable_wall_posts_comments' => 'on',
        'yz_enable_wall_posts_deletion' => 'on',
        'yz_enable_activity_directory_filter_bar' => 'on',
        'yz_attachments_max_size' => 10,
        'yz_attachments_max_nbr'  => 200,
        'yz_atts_allowed_images_exts' => array(
            'png', 'jpg', 'jpeg', 'gif'
        ),
        'yz_atts_allowed_videos_exts' => array(
            'mp4', 'ogg', 'ogv', 'webm',
        ),
        'yz_atts_allowed_audios_exts' => array(
            'mp3', 'ogg', 'wav',
        ),
        'yz_atts_allowed_files_exts' => array(
            'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar',
            'zip', 'mp4', 'mp3', 'ogg', 'pfi'
        ),

        // Reviews Settings
        'yz_enable_reviews' => 'on',
        'yz_user_reviews_privacy' => 'public',
        'yz_allow_users_reviews_edition' => 'off',
        'yz_profile_reviews_per_page' => 25,
        'yz_wg_max_reviews_items' => 3,
        

        // Bookmarking Posts.
        'yz_enable_bookmarks' => 'on',
        'yz_enable_bookmarks_privacy' => 'private',

        // Sticky Posts.
        'yz_enable_sticky_posts' => 'on',
        'yz_enable_groups_sticky_posts' => 'on',
        'yz_enable_activity_sticky_posts' => 'on',
        
        // Scroll to top.
        'yz_display_scrolltotop' => 'off',
        'yz_display_group_scrolltotop' => 'off',
        'yz_enable_settings_copyright' => 'on',

        // Wall Posts Per Page
        'yz_activity_wall_posts_per_page' => 20,
        'yz_profile_wall_posts_per_page' => 20,
        'yz_groups_wall_posts_per_page' => 20,
        
        // Wall Settings.
        'yz_enable_wall_file' => 'on',
        'yz_enable_wall_link' => 'on',
        'yz_enable_wall_photo' => 'on',
        'yz_enable_wall_audio' => 'on',
        'yz_enable_wall_video' => 'on',
        'yz_enable_wall_quote' => 'on',
        'yz_enable_wall_status' => 'on',
        'yz_enable_wall_comments' => 'off',
        'yz_enable_wall_new_cover' => 'on',
        'yz_enable_wall_new_member' => 'on',
        'yz_enable_wall_slideshow' => 'on',
        'yz_enable_wall_filter_bar' => 'on',
        'yz_enable_wall_new_avatar' => 'on',
        'yz_enable_wall_joined_group' => 'on',
        'yz_enable_wall_posts_embeds' => 'on',
        'yz_enable_wall_new_blog_post' => 'on',
        'yz_enable_wall_created_group' => 'on',
        'yz_enable_wall_comments_embeds' => 'on',
        'yz_enable_wall_updated_profile' => 'off',
        'yz_enable_wall_new_blog_comment' => 'off',
        'yz_enable_wall_friendship_created' => 'on',
        'yz_enable_wall_friendship_accepted' => 'on',

        // Profile Settings
        'yz_allow_private_profiles' => 'off',

        // Profile Settings
        'yz_disable_bp_registration' => 'off',

        // Members Directory
        'yz_md_users_per_page' => 18,
        'yz_md_card_meta_icon' => 'at',
        'yz_enable_md_cards_cover' => 'on',
        'yz_enable_md_cards_status' => 'on',
        'yz_show_md_cards_online_only' => 'on',
        'yz_enable_md_users_statistics' => 'on',
        'yz_md_card_meta_field' => 'user_login',
        'yz_enable_md_custom_card_meta' => 'off',
        'yz_enable_md_cards_avatar_border' => 'off',
        'yz_enable_md_user_followers_statistics' => 'on',
        'yz_enable_md_user_following_statistics' => 'on',
        'yz_enable_md_user_points_statistics' => 'on',
        'yz_enable_md_user_views_statistics' => 'on',
        'yz_enable_md_cards_actions_buttons' => 'on',
        'yz_enable_md_user_posts_statistics' => 'on',
        'yz_enable_md_user_friends_statistics' => 'on',
        'yz_enable_md_user_comments_statistics' => 'on',

        // Groups Directory
        'yz_gd_groups_per_page' => 18,
        'yz_enable_gd_cards_cover' => 'on',
        'yz_show_gd_cards_online_only' => 'on',
        'yz_enable_gd_groups_statistics' => 'on',
        'yz_enable_gd_cards_avatar_border' => 'on',
        'yz_enable_gd_cards_actions_buttons' => 'on',
        'yz_enable_gd_group_posts_statistics' => 'on',
        'yz_enable_gd_group_members_statistics' => 'on',
        'yz_enable_gd_group_activity_statistics' => 'on',

        // Groups Directory - Styling
        'yz_gd_cards_buttons_border_style' => 'oval',
        'yz_gd_cards_avatar_border_style' => 'circle',
        'yz_gd_cards_buttons_layout' => 'block',

        // Members Directory - Styling
        'yz_md_cards_buttons_layout' => 'block',
        'yz_md_cards_buttons_border_style' => 'oval',
        'yz_md_cards_avatar_border_style' => 'circle',

        // Custom Styling.
        'yz_enable_global_custom_styling'   => 'off',
        'yz_enable_profile_custom_styling'  => 'off',
        'yz_enable_account_custom_styling'  => 'off',
        'yz_enable_activity_custom_styling' => 'off',
        'yz_enable_groups_custom_styling'   => 'off',
        'yz_enable_groups_directory_custom_styling'  => 'off',
        'yz_enable_members_directory_custom_styling' => 'off',

        // Emoji Settings.
        'yz_enable_posts_emoji' => 'on',
        'yz_enable_comments_emoji' => 'on',
        'yz_enable_messages_emoji' => 'on',

        // General.
        'yz_buttons_border_style' => 'oval',
        'yz_activate_membership_system' => 'on',

        // Account Verification
        'yz_enable_account_verification' => 'on',

        // Login Form
        'logy_login_form_enable_header'     => 'on',
        'logy_user_after_login_redirect'    => 'home',
        'logy_after_logout_redirect'        => 'login',
        'logy_admin_after_login_redirect'   => 'dashboard',
        'logy_login_form_layout'            => 'logy-field-v1',
        'logy_login_icons_position'         => 'logy-icons-left',
        'logy_login_actions_layout'         => 'logy-actions-v1',
        'logy_login_btn_icons_position'     => 'logy-icons-left',
        'logy_login_btn_format'             => 'logy-border-radius',
        'logy_login_fields_format'          => 'logy-border-flat',
        'logy_login_form_title'             => __( 'Login', 'youzer' ),
        'logy_login_signin_btn_title'       => __( 'Log In', 'youzer' ),
        'logy_login_register_btn_title'     => __( 'Create New Account', 'youzer' ),
        'logy_login_lostpswd_title'         => __( 'Lost password?', 'youzer' ),
        'logy_login_form_subtitle'          => __( 'Sign in to your account', 'youzer' ),

        // Social Login
        'logy_social_btns_icons_position'   => 'logy-icons-left',
        'logy_social_btns_format'           => 'logy-border-radius',
        'logy_social_btns_type'             => 'logy-only-icon',
        'logy_enable_social_login'          => 'on',
        'logy_use_auth_modal'               => 'on',

        // Lost Password Form
        'logy_lostpswd_form_enable_header'  => 'on',
        'logy_lostpswd_form_title'          => __( 'Forgot your password?', 'youzer' ),
        'logy_lostpswd_submit_btn_title'    => __( 'Reset Password', 'youzer' ),
        'logy_lostpswd_form_subtitle'       => __( 'Reset your account password', 'youzer' ),

        // Register Form
        'logy_show_terms_privacy_note'      => 'on',
        'logy_signup_form_enable_header'    => 'on',
        'logy_signup_form_layout'           => 'logy-field-v1',
        'logy_signup_icons_position'        => 'logy-icons-left',
        'logy_signup_actions_layout'        => 'logy-regactions-v1',
        'logy_signup_btn_icons_position'    => 'logy-icons-left',
        'logy_signup_btn_format'            => 'logy-border-radius',
        'logy_signup_fields_format'         => 'logy-border-flat',
        'logy_signup_signin_btn_title'      => __( 'Log In', 'youzer' ),
        'logy_signup_form_title'            => __( 'Sign Up', 'youzer' ),
        'logy_signup_register_btn_title'    => __( 'Sign Up', 'youzer' ),
        'logy_signup_form_subtitle'         => __( 'Create New Account', 'youzer' ),

        // Limit Login Settings
        'logy_long_lockout_duration'    => 86400,
        'logy_short_lockout_duration'   => 43200,
        'logy_retries_duration'         => 1200,
        'logy_enable_limit_login'       => 'on',
        'logy_allowed_retries'          => 4,
        'logy_allowed_lockouts'         => 2,

        // User Tags Settings
        'yz_enable_user_tags' => 'on',
        'yz_enable_user_tags_icon' => 'on',
        'yz_enable_user_tags_description' => 'on',
        'yz_wg_user_tags_border_style' => 'radius',


        // Mail Settings
        'yz_enable_mailster' => 'off',
        'yz_enable_mailchimp' => 'off',
        'logy_notify_admin_on_registration' => 'on',

        // Admin Toolbar & Dashboard
        'logy_hide_subscribers_dash' => 'off',

        // Captcha.
        'logy_enable_recaptcha' => 'on',

        // Panel Messages.
        'logy_msgbox_captcha' => 'on',

    );
    
    // Filter.
    $default_options = apply_filters( 'yz_default_options', $default_options );
    
    return $default_options;
}

/**
 * # Get Youzer Plugin Pages
 */
function youzer_pages( $request_type = null, $id = null ) {

    // Get youzer pages.
    $youzer_pages = yz_options( 'youzer_pages' );

    // Switch Key <=> Values
    if ( 'ids' == $request_type ) {
        $yz_pages_ids = array_flip( $youzer_pages );
        return $yz_pages_ids;
    }

    return $youzer_pages;
}

/**
 * # Get Current Profile User id.
 */
function yz_profileUserID() {
    return bp_displayed_user_id();
}

/**
 * # Get Page URL.
 */
function yz_page_url( $page_name, $user_id = null ) {

	// Get Page Data
    $page_id  = yz_page_id( $page_name );
    $page_url = yz_fix_path( get_permalink( $page_id ) );

    // Get Username
    if ( ! empty( $user_id ) ) {
        $username = get_the_author_meta( 'user_login', $user_id );
    }

	// Get Page with Current User if page = profile or account .
	if ( 'profile' == $page_name && ! empty( $user_id ) ) {
        $page_url = $page_url . $username;
    } elseif ( 'profile' == $page_name && empty( $user_id ) ) {
        $page_url = $page_url . esc_html(  yz_data( 'user_login' ) );
    }

	// Return Page Url.
    return $page_url;

}

/**
 * # Get Page ID.
 */
function yz_page_id( $page ) {
    $youzer_pages = yz_options( 'youzer_pages' );
    return $youzer_pages[ $page ];
}

/**
 * Get Wordpress Pages
 */
function yz_get_pages() {

    // Set Up Variables
    $pages    = array();
    $wp_pages = get_pages();

    // Add Default Page.
    $pages[] = __( '-- Select --', 'youzer' );

    // Add Wordpress Pages
    foreach ( $wp_pages as $page ) {
        $pages[ $page->ID ] = sprintf( __( '%1s ( ID : %2d )','youzer' ), $page->post_title, $page->ID );
    }

    return $pages;
}

/**
 * # Sort list by numeric order.
 */
function yz_sortByMenuOrder( $a, $b ) {

    if ( ! isset( $a['menu_order'] ) || ! isset( $b['menu_order'] ) ) {
        return false;
    }

    $a = $a['menu_order'];
    $b = $b['menu_order'];

    if ( $a == $b ) {
        return 0;
    }

    return ( $a < $b ) ? -1 : 1;
}

/**
 * # Class Generator.
 */
function yz_generate_class( $classes ) {
    // Convert Array to String.
    return implode( ' ' , array_filter( (array) $classes ) );
}

/**
 * # Check widget visibility
 */
function yz_is_widget_visible( $widget_name ) {

    $visibility = false;

    $overview_widgets = yz_options( 'yz_profile_main_widgets' );
    $sidebar_widgets  = yz_options( 'yz_profile_sidebar_widgets' );
    $all_widgets      = array_merge( $overview_widgets, $sidebar_widgets );

    foreach ( $all_widgets as $widget ) {
        if ( isset( $widget[ $widget_name ] ) && 'visible' == $widget[ $widget_name ] ) {
            $visibility = true;
        }
    }

    // If its a Custom wiget Return True.
    if ( false !== strpos( $widget_name, 'yz_cwg' ) ) {
        $visibility = true;
    }

    $visibility = apply_filters( 'yz_is_widget_visible', $visibility, $widget_name );

    return $visibility;
}

/**
 * Get Array Key Index.
 */
function yz_get_key_index( $value, $array ) {
    $key = array_search( $value, $array );
    if ( false !== $key ) {
        return $key;
    }
}

/**
 * # Form Messages.
 */
add_action( 'youzer_admin_after_form', 'yz_form_messages' );
add_action( 'youzer_account_footer', 'yz_form_messages' );

function yz_form_messages() {

    ?>

    <div class="youzer-form-msg">
        <div id="youzer-action-message"></div>
        <div id="youzer-wait-message">
            <div class="youzer_msg wait_msg">
                <div class="youzer-msg-icon">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <span><?php _e( 'Please wait ...', 'youzer' ); ?></span>
            </div>
        </div>
    </div>

    <?php

}

/**
 * Popup Dialog Message
 */
function yz_popup_dialog( $type = null ) {

    // Init Alert Types.
    $alert_types = array( 'reset_tab', 'reset_all' );

    // Get Dialog Class.
    $form_class = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? 'alert' : 'error';
    
    // Get Dialog Name.
    $form_type  = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? $type : 'error';

    ?>

    <div id="uk_popup_<?php echo $form_type; ?>" class="uk-popup uk-<?php echo $form_class; ?>-popup">
        <div class="uk-popup-container">
            <div class="uk-popup-msg"><?php yz_get_dialog_msg( $form_type ); ?></div>
            <ul class="uk-buttons"><?php yz_get_dialog_buttons( $form_type ); ?></ul>
            <i class="fas fa-times uk-popup-close"></i>
        </div>
    </div>

    <?php
}

/**
 * Get Pop Up Dialog Buttons
 */
function yz_get_dialog_buttons( $type = null ) {

    // Get Cancel Button title.
    $confirm = __( 'confirm', 'youzer' );
    $cancel  = ( 'error' == $type ) ? __( 'Got it!', 'youzer' ) : __( 'cancel', 'youzer' );

    if ( 'reset_all' == $type ) : ?>
        <li>
            <a class="uk-confirm-popup yz-confirm-reset" data-reset="all"><?php echo $confirm; ?></a>
        </li>
    <?php elseif ( 'reset_tab' == $type ) : ?>
        <li>
            <a class="uk-confirm-popup yz-confirm-reset" data-reset="tab"><?php echo $confirm; ?></a>
        </li>
    <?php endif; ?>

    <li><a class="uk-close-popup"><?php echo $cancel; ?></a></li>

    <?php
}

/**
 * Get Pop Up Dialog Message
 */
function yz_get_dialog_msg( $type = null ) {

    if ( 'reset_all' == $type ) : ?>

    <span class="dashicons dashicons-warning"></span>
    <h3><?php _e( 'Are you sure you want to reset all the settings?', 'youzer' ); ?></h3>
    <p><?php _e( 'Be careful! this will reset all the youzer plugin settings.', 'youzer' ); ?></p>

    <?php elseif ( 'reset_tab' == $type ) : ?>

    <span class="dashicons dashicons-warning"></span>
    <h3><?php _e( 'Are you sure you want to do this ?', 'youzer' ); ?></h3>
    <p><?php _e( 'Be careful! this will reset all the current tab settings.', 'youzer' ); ?></p>

    <?php elseif ( 'error' == $type ) : ?>

    <i class="fas fa-exclamation-triangle"></i>
    <h3><?php _e( 'Oops!', 'youzer' ); ?></h3>
    <div class="uk-msg-content"></div>

    <?php endif;

}

/**
 * Fix Url Path.
 */
function yz_scroll_to_top() {

    if ( 'off' == yz_options( 'yz_enable_account_scroll_button' ) ) {
        return false;
    }

    echo '<a class="yz-scrolltotop"><i class="fas fa-chevron-up"></i></a>';
}

add_action( 'youzer_account_footer', 'yz_scroll_to_top' );

/**
 * Fix Url Path.
 */
function yz_fix_path( $url ) {
    $url = str_replace( '\\', '/', trim( $url ) );
    return ( substr( $url,-1 ) != '/' ) ? $url .= '/' : $url;
}

/**
 * # Get Post ID .
 */
function yz_get_post_id( $post_type, $key_meta , $meta_value ) {

    // Get Posts
    $posts = get_posts(
        array(
            'post_type'  => $post_type,
            'meta_key'   => $key_meta,
            'meta_value' => $meta_value )
        );

    if ( isset( $posts[0] ) && ! empty( $posts ) ) {
        return $posts[0]->ID;
    }

    return false;
}

/**
 * Detect if Logy Plugin Is installed.
 */
function yz_is_logy_active() {

    if ( yz_is_membership_system_active() ) {
        return true;
    }

    return false;

}

/**
 * Get Login Page Url.
 */
function yz_get_login_page_url() {
        
    // If Logy Plugin Installed Return Login Page Url.
    if ( yz_is_logy_active() ) {
        return logy_page_url( 'login' );
    }

    // Init Vars.
    $login_url = wp_login_url();

    // Get Login Type.
    $login_type = yz_options( 'yz_login_page_type' );

    // Get Login Url.
    if ( 'url' == $login_type ) {
        $url = wp_login_url(); 
        $login_url = ! empty( $url ) ? $url : $login_url;
    } elseif ( 'page' == $login_type ) {
        $page_id = yz_options( 'yz_login_page' );
        $login_url = ! empty( $page_id ) ? get_the_permalink( $page_id ) : $login_url;
    }

    return $login_url;

}

/**
 * Get Arguments consedering default values.
 */
function yz_get_args( $pairs, $atts, $prefix = null ) {

    // Set Up Arrays
    $out  = array();
    $atts = (array) $atts;

    // Get Prefix Value.
    $prefix = $prefix ? $prefix . '_' : null;

    // Get Values.
    foreach ( $pairs as $name => $default ) {
        if ( array_key_exists(  $prefix . $name, $atts ) ) {
            $out[ $name ] = $atts[ $prefix . $name ];
        } else {
            $out[ $name ] = $default;
        }
    }

    return $out;
}

/**
 * Register New Sidebars
 */
function yz_new_sidebars() {

    register_sidebar(
        array (
            'name' => __( 'Wall Sidebar', 'youzer' ),
            'id' => 'yz-wall-sidebar',
            'description' => __( 'Activity Sidebar', 'youzer' ),
            'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );

    register_sidebar(
        array (
            'name' => __( 'Groups Sidebar', 'youzer' ),
            'id' => 'yz-groups-sidebar',
            'description' => __( 'Groups Sidebar', 'youzer' ),
            'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}

add_action( 'widgets_init', 'yz_new_sidebars' );

/**
 * Add Groups & Wall Sidebar Widgets
 */
function yz_add_sidebar_widgets( $sidebar_id, $widgets_list ) {

    // Get Sidebar Widgets
    $sidebars_widgets = get_option( 'sidebars_widgets' ); 

    // Check if Sidebar is empty.
    if ( ! empty( $sidebars_widgets[ $sidebar_id ] ) ) { 
        return false;
    } 

    // Add Widgets To sidebar.
    foreach ( $widgets_list as $widget ) {

        // Get Widgets Data.
        $widget_data = get_option( 'widget_' . $widget );

        // Get Last Widget Id
        $last_id = (int) ! empty( $widget_data ) ? max( array_keys( $widget_data ) ) : 0;

        // Get Next ID.
        $counter = $last_id + 1;

        // Add Widget Default Settings.
        $widget_data[] = yz_get_widget_defaults_settings( $widget );

        // Get Widgets Data.
        update_option( 'widget_' . $widget, $widget_data );

        // Add Widget To sidebar
        $sidebars_widgets[ $sidebar_id ][] = strtolower( $widget ) . '-' . $counter;
    }

    // Update Sidebar
    update_option( 'sidebars_widgets', $sidebars_widgets );

}

/**
 * Create New Plugin Page.
 */
function yz_add_new_plugin_page( $args ) {

    // Get Page Slug
    $slug = $args['slug'];

    // Check that the page doesn't exist already
    $is_page_exists = yz_get_post_id( 'page', $args['meta'], $slug );

    if ( $is_page_exists ) {

        if ( ! isset( $pages[ $slug ] ) ) {

            // init Array.
            $pages = get_option( $args['pages'] );

            // Get Page ID
            $page_id = yz_get_post_id( 'page', $args['meta'], $slug );

            // Add New Page Data.
            $pages[ $slug ] = $page_id;
            
            update_option( $args['pages'], $pages );
        }

        return false;
    }

    $user_page = array(
        'post_title'     => $args['title'],
        'post_name'      => $slug,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'post_author'    =>  1,
        'comment_status' => 'closed'
    );

    $post_id = wp_insert_post( $user_page );

    wp_update_post( array('ID' => $post_id, 'post_type' => 'page' ) );

    update_post_meta( $post_id, $args['meta'], $slug );

    // init Array.
    $pages = get_option( $args['pages'] );

    // Add New Page Data.
    $pages[ $slug ] = $post_id;

    if ( isset( $pages ) ) {
        update_option( $args['pages'], $pages );
    }

}

/**
 * Display Notice Function
 */
function yz_display_admin_notice() {

    // Remove Default Function.
    global $BP_Legacy;
    remove_action( 'wp_footer', array( $BP_Legacy, 'sitewide_notices' ), 1 );

}
add_action( 'wp_head', 'yz_display_admin_notice' );

/**f
 * Check is user exist by id
 */
function yz_is_user_exist( $user_id = null ) {

    if ( $user_id instanceof WP_User ) {
        $user_id = $user_id->ID;
    }
    return (bool) get_user_by( 'id', $user_id );
}

/**
 * Template Messages
 */
function yz_template_messages() {

    ?>

    <div id="template-notices" role="alert" aria-atomic="true">
        <?php

        /**
         * Fires towards the top of template pages for notice display.
         *
         * @since 1.0.0
         */
        do_action( 'template_notices' ); ?>

    </div>

    <?php
}

add_action( 'yz_group_main_content', 'yz_template_messages' );
add_action( 'yz_profile_main_content', 'yz_template_messages' );

/**
 * Get Attachments Allowed Extentions
 */
function yz_get_allowed_extentions( $type = null, $format = null ) {

    // Extentions
    $extentions = null;

    switch ( $type ) {

        case 'image':
            // Get Images Extentions.
            $extentions = yz_options( 'yz_atts_allowed_images_exts' );
            break;
        
        case 'video':
            // Get Videos Extentions.
            $extentions = yz_options( 'yz_atts_allowed_videos_exts' );
            break;
        
        case 'audio':
            // Get Audios Extentions.
            $extentions = yz_options( 'yz_atts_allowed_audios_exts' );
            break;

        case 'file':
            // Get Files Extentions.
            $extentions = yz_options( 'yz_atts_allowed_files_exts' );
            break;
        
        default:
            // Get Default Extentions.
            $extentions = array(
                'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar',
                'zip', 'mp4', 'mp3', 'ogg', 'pfi'
            );
            break;
    }

    // Convert Extentions To Lower Case.
    $extentions = array_map( 'strtolower', $extentions );

    // Return Extentions as Text Format
    $extentions = ( $format == 'text' ) ? implode( ', ', $extentions ) : $extentions;

    return $extentions;
}

/**
 * Insert After Array.
 */
function yz_array_insert_after( array $array, $key, array $new ) {
    $keys = array_keys( $array );
    $index = array_search( $key, $keys );
    $pos = false === $index ? count( $array ) : $index + 1;
    return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
}

/**
 * Check is Mycred is Installed & Active.
 */
function yz_is_mycred_installed() {

    if ( ! defined( 'myCRED_VERSION' ) )  {
        return false;
    }

    return true;

}

/**
 * Check is BBpress is Installed & Active.
 */
function yz_is_bbpress_installed() {

    if ( ! class_exists( 'bbPress' ) )  {
        return false;
    }

    return true;

}

/*
 * Set Body Scheme Class
 */
function yz_body_add_youzer_scheme( $classes ) {
 
    // Get Profile Scheme
    $classes[] = yz_options( 'yz_profile_scheme' );
    $classes[] = is_user_logged_in() ? 'logged-in' : 'not-logged-in';
     
    return $classes;

}

add_filter( 'body_class', 'yz_body_add_youzer_scheme' );

/**
 * Compress Images
 */
function yz_compress_profile_elements_images( $key = null, $user_id = null ) {

    // GET User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get 
    $option_id = 'yz_compress_' . $key . $user_id;
    
    if ( ! get_option( $option_id ) ) {

        // Get Data
        $data = get_the_author_meta( $key, $user_id );

        if ( empty( $data ) ) {
            update_option( $option_id, '1' );
            return;
        }

        switch ( $key ) {

            case 'youzer_skills':

                $skills = array();

                foreach ( $data as $skills_data ) {
                    
                    if ( ! isset( $skills_data['wg_skills_title'] ) ) {
                        break;
                    }

                    $new_skill = array(
                        'title' => $skills_data['wg_skills_title'],
                        'barcolor' => $skills_data['wg_skills_barcolor'],
                        'barpercent' => $skills_data['wg_skills_barpercent'],
                    );

                    $skills[] = $new_skill;

                }


                update_user_meta( $user_id, 'youzer_skills', $skills );

                break;

            case 'youzer_services':

                $services = array();

                foreach ( $data as $service_data ) {

                    if ( ! isset( $service_data['wg_service_title'] ) ) {
                        break;
                    }

                    $new_service = array(
                        'icon' => $service_data['wg_service_icon'],
                        'title' => $service_data['wg_service_title'],
                        'description' => $service_data['wg_service_desc'],
                    );

                    $services[] = $new_service;

                }


                update_user_meta( $user_id, 'youzer_services', $services );

                break;

            case 'youzer_slideshow':

                $slideshows = array();

                foreach ( $data as $slide ) {
                    
                    if ( ! isset( $slide['url'] ) ) {
                        continue;
                    }

                    $file_name = basename( $slide['url'] );
                    $file = array( 'original' => $file_name );
                    unset( $slide['url'] );
                    $new_slide['original'] = $file_name;
                    $new_slide['thumbnail'] = yz_save_image_thumbnail( $file );
                    $slideshows[] = $new_slide;

                }

                if ( ! empty( $slideshows ) ) {
                    update_user_meta( $user_id, $key, $slideshows );
                }
                
                break;
            
            case 'youzer_portfolio':

                $portfolio = array();

                foreach ( $data as $photo ) {
                    
                    if ( ! isset( $photo['url'] ) ) {
                        continue;
                    }

                    $file_name = basename( $photo['url'] );
                    $file = array( 'original' => $file_name );
                    unset( $photo['url'] );
                    $photo['original'] = $file_name;
                    $photo['thumbnail'] = yz_save_image_thumbnail( $file );
                    $portfolio[] = $photo;

                }

                if ( ! empty( $portfolio ) ) {
                    update_user_meta( $user_id, $key, $portfolio );
                }
                
                break;
            case 'wg_link_img':
            case 'wg_quote_img':
            case 'wg_about_me_photo':
            case 'wg_project_thumbnail':

                    if ( is_array( $data ) ) {
                        return;
                    }
                    $file_name = basename( $data );
                    $file = array( 'original' => $file_name );
                    $img['original'] = $file_name;
                    $img['thumbnail'] = yz_save_image_thumbnail( $file );

                    if ( ! empty( $img ) ) {
                        update_user_meta( $user_id, $key, $img );
                    }
                    
                break;
            default:
                break;
        }
        update_option( $option_id, '1' );
    }


}

add_action( 'yz_before_get_data', 'yz_compress_profile_elements_images' );

/**
 * Activate Autoload
 */
function yz_activate_options_autoload() {

    if ( ! get_option( 'yz_activate_options_autoload' ) ) {

        $Yz_default_options = yz_standard_options();

        foreach ( $Yz_default_options as $option_id => $value) {
            
            $option_value = get_option( $option_id );
            
            if ( ! empty( $option_value ) ) {
                update_option( $option_id, $option_value, true );
            } else {
                update_option( $option_id, yz_options( $option_id ), true );   
            }

        }
        
        update_option( 'yz_activate_options_autoload', true );

    }

}

add_action( 'init', 'yz_activate_options_autoload' );

/**
 * Check Is Buddypress Followers installed !
 */
function yz_is_bpfollowers_active() {

    /**
     * Detect plugin. For use on Front End only.
     */
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    // check for plugin using plugin name
    if ( is_plugin_active( 'buddypress-followers/loader.php' ) || is_plugin_active( 'buddypress-followers-master/loader.php' ) ) {
        return true;
    } 

    return false;
    
}

/**
 * Check if Bookmarking Posts Option is Enabled.
 */
function yz_is_bookmark_active() {

    // Get Value.
    $bookmarks = yz_options( 'yz_enable_bookmarks' );
    
    if ( bp_is_active( 'activity' ) && 'on' == $bookmarks ) {
        $activate = true;
    } else {
        $activate = false;
    }

    return apply_filters( 'yz_is_bookmarks_active', $activate );

}

/**
 * Init Bookmarks
 */
function yz_init_bookmarks() {

    if ( yz_is_bookmark_active() ) {
        require_once YZ_PUBLIC_CORE . 'functions/posts-tools/yz-bookmarks-functions.php';
    }

}

add_action( 'plugins_loaded', 'yz_init_bookmarks', 999 );

/**
 * Check if Review Option is Enabled.
 */
function yz_is_reviews_active() {
    
    // Get Value.
    $reviews = yz_options( 'yz_enable_reviews' );
    
    if ( 'on' == $reviews ) {
        $activate = true;
    } else {
        $activate = false;
    }

    return apply_filters( 'yz_is_reviews_active', $activate );

}

/**
 * Init Reviews
 */
function yz_init_reviews() {
    
    if ( yz_is_reviews_active() ) {
        global $Youzer;
        require_once YZ_PUBLIC_CORE . 'class-yz-reviews.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-reviews-functions.php';
        require_once YZ_PUBLIC_CORE . 'reviews/yz-reviews-query.php';
        $Youzer->reviews = new Youzer_Reviews();

    }

}

add_action( 'plugins_loaded', 'yz_init_reviews', 999 );

/**
 * Determine whether BuddyPress is in the process of being deactivated.
 *
 * @since 1.6.0
 *
 * @param string $basename BuddyPress basename.
 * @return bool True if deactivating BuddyPress, false if not.
 */
function yz_is_deactivation( $basename = '' ) {
    $bp     = buddypress();
    $action = false;

    if ( ! empty( $_REQUEST['action'] ) && ( '-1' != $_REQUEST['action'] ) ) {
        $action = $_REQUEST['action'];
    } elseif ( ! empty( $_REQUEST['action2'] ) && ( '-1' != $_REQUEST['action2'] ) ) {
        $action = $_REQUEST['action2'];
    }

    // Bail if not deactivating.
    if ( empty( $action ) || !in_array( $action, array( 'deactivate', 'deactivate-selected' ) ) ) {
        return false;
    }

    // The plugin(s) being deactivated.
    if ( 'deactivate' == $action ) {
        $plugins = isset( $_GET['plugin'] ) ? array( $_GET['plugin'] ) : array();
    } else {
        $plugins = isset( $_POST['checked'] ) ? (array) $_POST['checked'] : array();
    }

    // Set basename if empty.
    if ( empty( $basename ) && !empty( $bp->basename ) ) {
        $basename = $bp->basename;
    }

    // Bail if no basename.
    if ( empty( $basename ) ) {
        return false;
    }

    // Is bbPress being deactivated?
    return in_array( $basename, $plugins );

}

/**
 * Get Image Size.
 */
function yz_get_image_size( $img_url ) {

    global $YZ_upload_dir, $YZ_upload_url;

    // Get Image Path;
    $img_path = str_replace( $YZ_upload_url, $YZ_upload_dir, $img_url);

    // Get Image Size
    $img_size = getimagesize( $img_path );
    
    return $img_size;
}

/**
 * Make RTmedia compatible with Youzer.
 */
function yzc_rtmedia_main_template_include( $template ) {

    if ( bp_is_user() ) {
        return YZ_TEMPLATE . 'profile-template.php';
    } elseif ( bp_is_group() ) {
        return YZ_TEMPLATE . 'groups/single/home.php';
    } else {
        return $template;
    }

}

add_filter( 'rtmedia_media_include', 'yzc_rtmedia_main_template_include', 0 );

/**
 * Get Rtmedia Content
 */
function yzc_add_rtmedia_content() {

    global $rtmedia_query;  
    
    if ( $rtmedia_query ) {  
        include_once YZ_TEMPLATE . 'rtmedia/main.php';
    }

}

add_action( 'yz_group_main_column', 'yzc_add_rtmedia_content' );
add_action( 'yz_profile_main_column', 'yzc_add_rtmedia_content' );

/**
 *  Font-edn Modal
 */
function yz_modal( $args, $modal_function, $options = null ) {

    $title        = $args['title'];
    $button_id    = $args['button_id'];
    $default_submit_icon = isset( $args['operation'] ) && $args['operation'] == 'add' ? 'far fa-edit' : 'fas fa-sync-alt'; 
    $submit_btn_icon = isset( $args['submit_button_icon'] ) ? $args['submit_button_icon'] : $default_submit_icon;
    $button_title = isset( $args['button_title'] ) ? $args['button_title'] : __( 'save', 'youzer' );
    $show_close = isset( $args['show_close'] ) ? $args['show_close'] : true;
    $show_delete_btn = isset( $args['show_delete_button'] ) ? $args['show_delete_button'] : false;
    $delete_btn_title = isset( $args['delete_button_title'] ) ? $args['delete_button_title'] : __( 'delete', 'youzer' );
    $delete_btn_id = isset( $args['delete_button_id'] ) ? $args['delete_button_id'] : null;
    $delete_btn_item_id = isset( $args['delete_button_item_id'] ) ? $args['delete_button_item_id'] : null;

    ?>

    <form class="yz-modal" id="<?php echo $args['id'] ;?>" method="post" >

        <div class="yz-modal-title" data-title="<?php echo $title; ?>">
            <?php echo $title; ?>
            <i class="fas fa-times yz-modal-close-icon"></i>
        </div>
        
        <div class="yz-modal-content">
            <?php $modal_function( $options ); ?>
        </div>
        
        <div class="yz-modal-actions">
            <button id="<?php echo $button_id; ?>" data-action="<?php echo $args['operation']; ?>" class="yz-modal-button yz-modal-save">
                <i class="<?php echo $submit_btn_icon; ?>"></i><?php echo $button_title ?>
            </button>

            <?php if ( $show_delete_btn ) : ?>
            <button id="<?php echo $delete_btn_id; ?>" class="yz-md-button yz-modal-delete" data-item-id="<?php echo $delete_btn_item_id ?>">
                <i class="far fa-trash-alt"></i><?php echo $delete_btn_title; ?>
            </button>
            <?php endif; ?>

            <?php if ( $show_close ) : ?>
            <button class="yz-modal-button yz-modal-close">
                <i class="fas fa-times"></i><?php _e( 'close', 'youzer' ); ?>
            </button>
            <?php endif; ?>
        </div>

    </form>

    <?php
}

function yz_fix_networks_icons_css( $icon ) {
    if ( strpos( $icon, ' ' ) === false) {
        $icon = 'fab fa-' . $icon; 
    }
 
    return $icon;

}

add_filter( 'yz_panel_networks_icon', 'yz_fix_networks_icons_css' );
add_filter( 'yz_user_social_networks_icon', 'yz_fix_networks_icons_css' );

function yz_fix_icons_css( $icon ) {
    if ( strpos( $icon, ' ' ) === false) {
        $icon = 'fas fa-' . $icon; 
    }
 
    return $icon;

}

add_filter( 'yz_user_tags_builder_icon', 'yz_fix_icons_css' );
add_filter( 'yz_user_tags_name_icon', 'yz_fix_icons_css' );
add_filter( 'yz_xprofile_group_icon', 'yz_fix_icons_css' );
add_filter( 'yz_service_item_icon', 'yz_fix_icons_css' );
add_filter( 'yz_account_menu_icon', 'yz_fix_icons_css' );

/**
 * Youzer Scrips Vars.
 */
function youzer_scripts_vars() {
    $vars = array(
        'unknown_error' => __( 'An unknown error occurred. Please try again later.', 'youzer' ),
        'slides_height_type' => yz_options( 'yz_slideshow_height_type' ),
        'authenticating' => __( 'Authenticating ...', 'youzer' ),
        'security_nonce' => wp_create_nonce( 'youzer-nonce' ),
        'displayed_user_id' => bp_displayed_user_id(),
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'thanks'   => __( 'ok! thanks', 'youzer' ),
        'confirm' => __( 'Confirm', 'youzer' ),
        'cancel' => __( 'Cancel', 'youzer' ),
        'gotit' => __( 'Got it!', 'youzer' ),
        'done' => __( 'Done !', 'youzer' ),
        'ops' => __( 'Oops !', 'youzer' ),
        'youzer_url' => YZ_URL,
    );

    return apply_filters( 'youzer_scripts_vars', $vars );
}

/**
 * Get Suggestions List.
 */
function yz_get_users_list( $users, $args = null ) {

    if ( empty( $users ) ) {
        return;
    }

    // Get Widget Class.
    $main_class = isset( $args['main_class'] ) ? $args['main_class'] : null;
    
    ?>

    <div class="yz-items-list-widget yz-list-avatar-circle <?php echo yz_generate_class( $main_class ); ?>">

        <?php foreach ( $users as $user_id ) : ?>

        <?php $profile_url = bp_core_get_user_domain( $user_id ); ?>

        <div class="yz-list-item">
            <a href="<?php echo $profile_url; ?>" class="yz-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb' ) ); ?></a>
            <div class="yz-item-data">
                <a href="<?php echo $profile_url; ?>" class="yz-item-name"><?php echo bp_core_get_user_displayname( $user_id ); ?><?php yz_the_user_verification_icon( $user_id ); ?></a>
                <div class="yz-item-meta">
                    <div class="yz-meta-item">@<?php echo bp_core_get_username( $user_id ); ?></div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

    <?php

}