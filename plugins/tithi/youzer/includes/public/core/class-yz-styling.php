<?php

class Youzer_Styling {

    function __construct() {

        // Add Filters
        add_filter( 'youzer_styling', array( &$this, 'global_styling' ) );
        add_filter( 'youzer_styling', array( &$this, 'group_styling' ) );
        add_filter( 'youzer_styling', array( &$this, 'profile404_styling' ) );
        add_filter( 'youzer_styling', array( &$this, 'profile_styling' ) );
        add_filter( 'youzer_styling', array( &$this, 'posts_tab_styling' ) );
        add_filter( 'youzer_styling', array( &$this, 'comments_tab_styling' ) );

        // iNIT
        add_action( 'init', array( &$this, 'get_active_styles' ) );

        add_action( 'wp_enqueue_scripts', array( &$this, 'custom_scheme' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'custom_styling' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'gradient_styling' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'init_styling_networks' ) );

    }

    /**
     * Get All Styles
     */
    function get_all_styles( $query = null ) {

        // Get Styles
        $styles = array_merge( $this->posts_tab_styling( array(), true ), $this->comments_tab_styling( array(), true ), $this->group_styling( array(), true ), $this->profile_styling( array(), true ),  $this->profile404_styling( array(), true ), $this->global_styling( array(), true ) );

        if ( $query == 'ids' ) {

            // Get Styles Ids.
            $styles = wp_list_pluck( $styles, 'id' );
           
        }
            
        return $styles;
    }

    /**
     * Get Active Fields!
     */
    function get_active_styles() {
        
        // Get Option.
        $option_id = 'set_active_styles';

        // Check if Function Already executed !
        if ( get_option( $option_id ) ) {
            return;
        }

        // Init Vars.
        $active_styles = array();

        // Get Styles Ids.
        $styles_ids = wp_list_pluck( $this->get_all_styles(), 'id' );
        
        foreach ( $styles_ids as $id ) {

            // Get Value.
            $get_value = yz_options( $id );

            if ( isset( $get_value['color'] ) && ! empty( $get_value['color'] )  ) {
                $active_styles[] = $id;
            }

        }

        update_option( $option_id, $active_styles );

    }

    /**
     * Posts Tab Styling
     */
    function posts_tab_styling( $elements = array(), $get_array = false ) {
        
        if ( ! bp_is_current_component( 'posts' ) && ! $get_array ) {
            return $elements;
        }

        $data = array(
            array(
                'id'        =>  'yz_post_title_color',
                'selector'  =>  '.yz-tab-post .yz-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_meta_color',
                'selector'  =>  '.yz-tab-post .yz-post-meta ul li, .yz-tab-post .yz-post-meta ul li a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_meta_icons_color',
                'selector'  =>  '.yz-tab-post .yz-post-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_text_color',
                'selector'  =>  '.yz-tab-post .yz-post-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_button_color',
                'selector'  =>  '.yz-tab-post .yz-read-more',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_post_button_text_color',
                'selector'  =>  '.yz-tab-post .yz-read-more',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_button_icon_color',
                'selector'  =>  '.yz-tab-post .yz-rm-icon i',
                'property'  =>  'color'
            )
        );

        return array_merge( $elements, $data );
    }

    /**
     * Comments Tab Styling
     */
    function comments_tab_styling( $elements = array(), $get_array = false ) {

        if ( ! bp_is_current_component( 'comments' ) && ! $get_array ) {
            return $elements;
        }

        $data = array(
            array(
                'id'        =>  'yz_comment_author_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-fullname',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_username_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-author',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_date_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-date',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_text_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-excerpt p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_button_bg_color',
                'selector'  =>  '.yz-tab-comment .view-comment-button',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_comment_button_text_color',
                'selector'  =>  '.yz-tab-comment .view-comment-button',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_button_icon_color',
                'selector'  =>  '.yz-tab-comment .view-comment-button i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_author_border_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-img',
                'property'  =>  'border-color'
            )
        );
        
        return array_merge( $elements, $data );

    }

    /**
     * Global Styling
     */
    function global_styling( $elements = array(), $get_array = false ) {

        $data = array(
            array(
                'id'        =>  'yz_plugin_content_width',
                'selector'  =>  '.yz-hdr-v1 .yz-cover-content .yz-inner-content,
                                #yz-profile-navmenu .yz-inner-content,
                                .yz-vertical-layout .yz-content,
                                .youzer .yz-boxed-navbar,
                                .youzer .wild-content,
                                .yz-page-main-content,
                                .yz-header-content,
                                .yz-cover-content',
                'property'  =>  'max-width',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_background',
                'selector'  =>  '.yz-page',
                'property'  =>  'background-color'
            ),
            // Spacing Styles
            array(
                'id'        =>  'yz_plugin_margin_top',
                'selector'  =>  '.yz-page',
                'property'  =>  'margin-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_margin_bottom',
                'selector'  =>  '.yz-page',
                'property'  =>  'margin-bottom',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_padding_top',
                'selector'  =>  '.yz-page',
                'property'  =>  'padding-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_padding_bottom',
                'selector'  =>  '.yz-page',
                'property'  =>  'padding-bottom',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_padding_left',
                'selector'  =>  '.yz-page',
                'property'  =>  'padding-left',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_padding_right',
                'selector'  =>  '.yz-page',
                'property'  =>  'padding-right',
                'unit'      => 'px'
            ),
            // Auhtor Box Styling .
            array(
                'id'        =>  'yz_author_pattern_opacity',
                'selector'  =>  '.yzb-author.yz-header-pattern .yz-header-cover:after',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_author_overlay_opacity',
                'selector'  =>  '.yzb-author.yz-header-overlay .yz-header-cover:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_author_box_margin_top',
                'selector'  =>  '.yz-author-box-widget',
                'property'  =>  'margin-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_author_box_margin_bottom',
                'selector'  =>  '.yz-author-box-widget',
                'property'  =>  'margin-bottom',
                'unit'      =>  'px'
            )
        );
        
        return array_merge( $elements, $data );
    }

    /**
     * Profile 404 Styling
     */
    function profile404_styling( $elements = array(), $get_array = false ) {
        
        if ( ! yz_is_404_profile() && ! $get_array ) {
            return $elements;
        }

        $data = array(
            array(
                'id'        =>  'yz_profile_404_title_color',
                'selector'  =>  '.yz-box-404 h2',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_404_desc_color',
                'selector'  =>  '.yz-box-404 p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_404_button_txt_color',
                'selector'  =>  '.yz-box-404 .yz-box-button',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_404_button_bg_color',
                'selector'  =>  '.yz-box-404 .yz-box-button',
                'property'  =>  'background-color'
            )
        );
        
        return array_merge( $elements, $data );
    }

    /**
     * Group Styling
     */
    function group_styling( $elements = array(), $get_array = false ) {

        if ( ! bp_is_group() && ! $get_array ) {
            return $elements;
        }

        $data = array(
            array(
                'id'        =>  'yz_group_header_bg_color',
                'selector'  =>  '.yz-group .yz-header-overlay .yz-header-cover:before',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_group_header_username_color',
                'selector'  =>  '.yz-group .yz-name h2,.yz-profile .yzb-head-content h2',
                'property'  =>  'color'
            ),array(
                'id'        =>  'yz_group_header_text_color',
                'selector'  =>  '.yz-group .yz-usermeta li span, .yz-profile .yzb-head-meta',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_group_header_icons_color',
                'selector'  =>  '.yz-group .yz-usermeta li i, .yz-profile .yzb-head-meta i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_group_header_statistics_nbr_color',
                'selector'  =>  '.yz-group .yz-user-statistics ul li .yz-snumber',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_group_header_statistics_title_color',
                'selector'  =>  '.yz-group .yz-user-statistics .yz-sdescription',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_group_header_overlay_opacity',
                'selector'  =>  '.yz-group .yz-profile-header.yz-header-overlay .yz-header-cover:before,' .
                                '.yz-group .yz-hdr-v3 .yz-inner-content:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_group_header_pattern_opacity',
                'selector'  =>  '.yz-group .yz-profile-header.yz-header-pattern .yz-header-cover:after,
                                 .yz-group .yz-hdr-v3 .yz-inner-content:after',
                'property'  =>  'opacity'
            )
        );

        return array_merge( $elements, $data );
    }

    /**
     * Styling Data.
     */
    function profile_styling( $elements = null, $get_array = false ) {

        if ( ! bp_is_user() && ! $get_array ) {
            return $elements;
        }

        $data = array(
            // Profile Header Styling,
            array(
                'id'        =>  'yz_profile_header_bg_color',
                'selector'  =>  '.yz-profile .yz-header-overlay .yz-header-cover:before',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_profile_header_username_color',
                'selector'  =>  '.yz-profile .yz-name h2,.yz-profile .yzb-head-content h2',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_text_color',
                'selector'  =>  '.yz-profile .yz-usermeta li span, .yz-profile .yzb-head-meta',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_icons_color',
                'selector'  =>  '.yz-profile .yz-usermeta li i, .yz-profile .yzb-head-meta i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_statistics_nbr_color',
                'selector'  =>  '.yz-profile .yz-user-statistics ul li .yz-snumber',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_statistics_title_color',
                'selector'  =>  '.yz-profile .yz-user-statistics .yz-sdescription',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_overlay_opacity',
                'selector'  =>  '.yz-profile .yz-profile-header.yz-header-overlay .yz-header-cover:before,' .
                                '.yz-profile .yz-hdr-v3 .yz-inner-content:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_profile_header_pattern_opacity',
                'selector'  =>  '.yz-profile .yz-profile-header.yz-header-pattern .yz-header-cover:after,
                                 .yz-profile .yz-hdr-v3 .yz-inner-content:after',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_navbar_bg_color',
                'selector'  =>  '#yz-profile-navmenu',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_navbar_icons_color',
                'selector'  =>  '.youzer .yz-profile-navmenu li i,' .
                                '.yz-profile .yz-nav-settings .yz-settings-icon',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_navbar_icons_color',
                'selector'  =>  '.yz-profile .yz-responsive-menu span::before,' .
                                '.yz-profile .yz-responsive-menu span::after,' .
                                '.yz-profile .yz-responsive-menu span',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_navbar_links_color',
                'selector'  =>  '.youzer .yz-profile-navmenu a,.yz-profile .yz-settings-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_navbar_links_hover_color',
                'selector'  =>  '.youzer .yz-profile-navmenu a:hover',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_navbar_menu_border_color',
                'selector'  =>  '.youzer .yz-nav-effect .yz-menu-border',
                'property'  =>  'background-color'
            ),
            // Pagination Tab Styling .
            array(
                'id'        =>  'yz_paginationbg_color',
                'selector'  =>  '.yz-pagination .page-numbers,.yz-pagination .yz-pagination-pages',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_pagination_text_color',
               'selector'  =>  '.yz-pagination .page-numbers,.yz-pagination .yz-pagination-pages',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_paginationcurrent_bg_color',
                'selector'  =>  '.yz-pagination .page-numbers.current',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_pagination_current_text_color',
                'selector'  =>  '.yz-pagination .current .yz-page-nbr',
                'property'  =>  'color'
            ),
            // Widgets Styling .
            array(
                'id'        =>  'yz_wgs_title_bg',
                'selector'  =>  '.yz-widget .yz-widget-head',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wgs_title_border_color',
                'selector'  =>  '.yz-widget .yz-widget-head',
                'property'  =>  'border-color'
            ),
            array(
                'id'        =>  'yz_wgs_title_color',
                'selector'  =>  '.yz-widget .yz-widget-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wgs_title_icon_color',
                'selector'  =>  '.yz-widget-title i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wgs_title_icon_bg',
                'selector'  =>  '.yz-wg-title-icon-bg .yz-widget-title i',
                'property'  =>  'background-color'
            ),
            // Widget - About Me - Styling .
            array(
                'id'        =>  'yz_wg_aboutme_title_color',
                'selector'  =>  '.yz-aboutme-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_aboutme_desc_color',
                'selector'  =>  '.yz-aboutme-description',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_aboutme_txt_color',
                'selector'  =>  '.yz-aboutme-bio',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_aboutme_head_border_color',
                'selector'  =>  '.yz-aboutme-head:after',
                'property'  =>  'background-color'
            ),
            // Widget - Project - Styling .
            array(
                'id'        =>  'yz_wg_project_color',
                'selector'  =>  '.yz-project-container',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_project_type_bg_color',
                'selector'  =>  '.yz-project-content .yz-project-type',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_project_type_txt_color',
                'selector'  =>  '.yz-project-content .yz-project-type',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_title_color',
                'selector'  =>  '.yz-project-content .yz-project-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_meta_txt_color',
                'selector'  =>  '.yz-project-content .yz-project-meta ul li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_meta_icon_color',
                'selector'  =>  '.yz-project-content .yz-project-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_desc_color',
                'selector'  =>  '.yz-project-content .yz-project-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_tags_color',
                'selector'  =>  '.yz-project-content .yz-project-tags li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_tags_bg_color',
                'selector'  =>  '.yz-project-content .yz-project-tags li',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_project_tags_hashtag_color',
                'selector'  =>  '.yz-project-content .yz-project-tags .yz-tag-symbole',
                'property'  =>  'color'
            ),
            // Widget - User Tags - Styling .
            array(
                'id'        =>  'yz_wg_user_tags_title_color',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_user_tags_icon_color',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-name i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_user_tags_desc_color',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-description',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_user_tags_background',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-values .yz-utag-value-item',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_user_tags_color',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-values .yz-utag-value-item,
                .yz-widget .yz-user-tags .yz-utag-values .yz-utag-value-item a',
                'property'  =>  'color'
            ),
            // Widget - Post - Styling .
            array(
                'id'        =>  'yz_wg_post_type_bg_color',
                'selector'  =>  '.yz-post-content .yz-post-type',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_post_type_txt_color',
                'selector'  =>  '.yz-post-content .yz-post-type',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_title_color',
                'selector'  =>  '.yz-post-content .yz-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_meta_txt_color',
                'selector'  =>  '.yz-post-content .yz-post-meta ul li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_meta_icon_color',
                'selector'  =>  '.yz-post-content .yz-post-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_text_color',
                'selector'  =>  '.yz-post-content .yz-post-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_tags_color',
                'selector'  =>  '.yz-post-content .yz-post-tags li a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_tags_bg_color',
                'selector'  =>  '.yz-post-content .yz-post-tags li',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_post_tags_hashtag_color',
                'selector'  =>  '.yz-post-content .yz-post-tags .yz-tag-symbole',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_rm_color',
                'selector'  =>  '.yz-post .yz-read-more',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_rm_icon_color',
                'selector'  =>  '.yz-post .yz-read-more i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_rm_bg_color',
                'selector'  =>  '.yz-post .yz-read-more',
                'property'  =>  'background-color'
            ),
            // Widget - Services - Styling .
            array(
                'id'        =>  'yz_wg_service_icon_bg_color',
                'selector'  =>  '.yz-service-item .yz-service-icon i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_service_icon_color',
                'selector'  =>  '.yz-service-item .yz-service-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_service_title_color',
                'selector'  =>  '.yz-service-item .yz-item-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_service_text_color',
                'selector'  =>  '.yz-service-item .yz-item-content p',
                'property'  =>  'color'
            ),
            // Widget - Portfolio - Styling .
            array(
                'id'        =>  'yz_wg_portfolio_title_border_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption h3:after',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_txt_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_hov_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a i:hover',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_txt_hov_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a i:hover',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_border_hov_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a:hover',
                'property'  =>  'border-color'
            ),
            // Widget - Instagram - Styling .
            array(
                'id'        =>  'yz_wg_instagram_img_icon_bg_color',
                'selector'  =>  '.yz-instagram .yz-portfolio-content figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_instagram_img_icon_color',
                'selector'  =>  '.yz-instagram .yz-portfolio-content figcaption a i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_instagram_img_icon_bg_color_hover',
                'selector'  =>  '.yz-instagram .yz-portfolio-content figcaption a i:hover',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_instagram_img_icon_color_hover',
                'selector'  =>  '.yz-instagram .yz-portfolio-content figcaption a i:hover',
                'property'  =>  'color'
            ),
            // Widget - Flickr - Styling .
            array(
                'id'        =>  'yz_wg_flickr_img_bg_color',
                'selector'  =>  '.yz-flickr-photos figcaption',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_flickr_img_icon_bg_color',
                'selector'  =>  '.yz-flickr-photos figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_flickr_img_icon_color',
                'selector'  =>  '.yz-flickr-photos figcaption a i',
                'property'  =>  'color'
            ),
            // Widget - Recent Posts - Styling .
            array(
                'id'        =>  'yz_wg_rposts_title_color',
                'selector'  =>  '.yz-recent-posts .yz-post-head .yz-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_rposts_date_color',
                'selector'  =>  '.yz-recent-posts .yz-post-meta ul li',
                'property'  =>  'color'
            ),
            // Widget - infos - Styling .
            array(
                'id'        =>  'yz_infos_wg_title_color',
                'selector'  =>  '.youzer .yz-infos-content .yz-info-label',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_infos_wg_value_color',
                'selector'  =>  '.youzer .yz-infos-content .yz-info-data',
                'property'  =>  'color'
            ),
            // Widget - Quote - Styling .
            array(
                'id'        =>  'yz_wg_quote_content_bg',
                'selector'  =>  '.youzer .yz-quote-main-content',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_quote_icon_bg',
                'selector'  =>  '.youzer .yz-quote-icon',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_quote_txt',
                'selector'  =>  '.youzer .yz-quote-main-content blockquote',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_quote_icon',
                'selector'  =>  '.youzer .yz-quote-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_quote_owner',
                'selector'  =>  '.youzer .yz-quote-owner',
                'property'  =>  'color'
            // Widget - Link - Styling .
            ),
            array(
                'id'        =>  'yz_wg_link_content_bg',
                'selector'  =>  '.youzer .yz-link-main-content',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_link_icon_bg',
                'selector'  =>  '.youzer .yz-link-icon i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_link_txt',
                'selector'  =>  '.youzer .yz-link-main-content p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_link_icon',
                'selector'  =>  '.youzer .yz-link-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_link_url',
                'selector'  =>  '.youzer .yz-link-url',
                'property'  =>  'color'
            ),
            // Widget - Video - Styling .
            array(
                'id'        =>  'yz_wg_video_title_color',
                'selector'  =>  '.youzer .yz-video-head .yz-video-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_video_desc_color',
                'selector'  =>  '.youzer .yz-video-head .yz-video-desc',
                'property'  =>  'color'
            ),
            // Scroll to top Styling .
            array(
                'id'        =>  'yz_scroll_button_color',
                'selector'  =>  '.yz-scrolltotop i:hover',
                'property'  =>  'background-color'
            ),
            // Widget Slideshow .
            array(
                'id'        =>  'yz_wg_slideshow_pagination_color',
                'selector'  =>  '.youzer .owl-theme .owl-controls .owl-page span',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_slideshow_np_color',
                'selector'  =>  '.youzer .owl-buttons div::before, .owl-buttons div::after',
                'property'  =>  'background-color'
            ),
            // Author Box Widget
            array(
                'id'        =>  'yz_abox_button_icon_color',
                'selector'  =>  '.yz-author-box-widget .yzb-button i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_abox_button_txt_color',
                'selector'  =>  '.yz-author-box-widget .yzb-button .yzb-button-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_abox_button_bg_color',
                'selector'  =>  '.yz-author-box-widget .yzb-button',
                'property'  =>  'background-color'
            ),
            // Verified accounts
            array(
                'id'        =>  'yz_verified_badge_background_color',
                'selector'  =>  '.yz-account-verified',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_verified_badge_icon_color',
                'selector'  =>  '.yz-account-verified',
                'property'  =>  'color'
            )
        );

        return array_merge( $elements, $data );
    }

    /**
     * Styling Elements 
     */
    function styles_data( $elements = array() ) {
        return apply_filters( 'youzer_styling', $elements );
    }

    /**
     * Custom Styling.
     */
    function custom_styling() {

        // Page Styles
        $page_styles = $this->styles_data();

        // Get Active Styles.
        $active_styles = (array) yz_options( 'yz_active_styles' );

        foreach ( $page_styles as $key => $data ) {
            if ( ! in_array( $data['id'], $active_styles ) ) {
                unset( $page_styles[ $key ] );
            }
        }

        if ( empty( $page_styles ) ) {
            return;
        }

        // Custom Styling File.
        wp_enqueue_style( 'youzer-customStyle', YZ_AA . 'css/custom-script.css' );        

        // Print Styles
        foreach ( $page_styles as $key ) {

            // Get Data.
            $selector = $key['selector'];
            $property = $key['property'];

            $option = yz_options( $key['id'] );
            $option = isset( $option['color'] ) ? $option['color'] : $option;
            if ( empty( $key['type'] ) && ! empty( $option ) ) {
                $unit = isset( $key['unit'] ) ? $key['unit'] : null;
                $custom_css = "
                    $selector {
                	$property: $option$unit !important;
                    }";
                wp_add_inline_style( 'youzer-customStyle', $custom_css );
            }
        }
    }

    /**
     * # Init Styling Networking Styling.
     */
    function init_styling_networks() {
        // Styling Header Networks
        $this->styling_networks( 'header' );
        // Styling Widget Networks
        $this->styling_networks( 'widget' );
    }

    /**
     * # Header Social Networks Styling.
     */
    function styling_networks( $element = null ) {

        // Get Social Networks Data
        $social_networks  = yz_options( 'yz_social_networks' );
        $display_networks = yz_options( 'yz_display_' . $element . '_networks' );

        // if Element is Widget Make Networks Visible.
        if ( 'widget' == $element ) {
            $element = 'wg';
            $display_networks = 'on';
        }

        if ( 'on' != $display_networks || empty( $social_networks ) ) {
            return false;
        }

        wp_enqueue_style(
            'youzer-customStyle',
            YZ_AA . 'css/custom-script.css'
        );

        // Get Networks Type & Size.
        $networks_size = yz_options( 'yz_wg_sn_icons_size' );
        $networks_type = yz_options( 'yz_' . $element . '_sn_bg_type' );

        // Get Styling Element.
        $icon = ( 'wg' == $element && 'full-width' == $networks_size ) ? 'a' : 'i';

        foreach ( $social_networks as $network => $data ) {

            // Get network Color
            $color = $data['color'];

            // Prepare selector
            $selector = ".yz-$element-networks.yz-icons-$networks_type .$network $icon";

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
            $icon_css = "$selector { $property: $color !important; }";

            // Add Css To The Page
            wp_add_inline_style( 'youzer-customStyle', $icon_css );
        }
    }

    /**
     * Custom Scheme.
     */
    function custom_scheme() {

        // Check if is using a custom scheme is enabled.
        $use_custom_scheme = yz_options( 'yz_enable_profile_custom_scheme' );

        // Get Custom Scheme Color
        $scheme_color = yz_options( 'yz_profile_custom_scheme_color' );
        $scheme_color = $scheme_color['color'];

        if ( 'on' != $use_custom_scheme || empty( $scheme_color ) ) {
            return false;
        }

        $custom_css = "
            .yz-items-list-widget .yz-list-item .yz-item-action .yz-add-button i,
            #yz-members-list .yzm-user-actions .friendship-button .requested,
            .yz-wall-embed .yz-embed-action .friendship-button a.requested,
            .yz-widget .yz-user-tags .yz-utag-values .yz-utag-value-item,
            .item-list-tabs #search-message-form #messages_search_submit,
            #yz-groups-list .action .group-button .membership-requested,
            #yz-members-list .yzm-user-actions .friendship-button .add,
            #yz-groups-list .action .group-button .request-membership,
            .yz-wall-embed .yz-embed-action .friendship-button a.add,
            .yz-group-manage-members-search #members_search_submit,
            #yz-groups-list .action .group-button .accept-invite,
            .notifications-options-nav #notification-bulk-manage,
            .notifications .notification-actions .mark-read span,
            .yz-group-settings-tab .yz-group-submit-form input,
            .sitewide-notices .thread-options .activate-notice,
            #yz-groups-list .action .group-button .join-group,
            #bbpress-forums #bbp-search-form #bbp_search_submit,
            .yz-social-buttons .friendship-button a.requested,
            #yz-directory-search-box form input[type=submit],
            .yzm-user-actions .friendship-button a.requested,
            .yz-wall-embed .yz-embed-action .group-button a,
            .widget_display_views li .bbp-view-title:before,
            .yz-forums-topic-item .yz-forums-topic-icon i,
            .yz-forums-forum-item .yz-forums-forum-icon i,
            #yz-group-buttons .group-button a.join-group,
            .messages-notices .thread-options .read span,
            .yz-social-buttons .friendship-button a.add,
            #search-members-form #members_search_submit,
            .messages-options-nav #messages-bulk-manage,
            .yzm-user-actions .friendship-button a.add,
            .widget_display_search #bbp_search_submit,
            .my-friends #friend-list .action a.accept,
            .yz-wall-new-post .yz-post-more-button,
            .bbp-pagination .page-numbers.current,
            .group-request-list .action .accept a,
            #message-recipients .highlight-icon i,
            .yz-pagination .page-numbers.current,
            .yz-project-content .yz-project-type,
            .widget_display_forums li a:before,
            .yzb-author .yzb-account-settings,
            .widget_display_topics li:before,
            .group-button.request-membership,
            #send_message_form .submit #send,
            #send-invite-form .submit input,
            #send-reply #send_reply_button,
            div.bbp-submit-wrapper button,
            #bbpress-forums li.bbp-header, 
            #bbpress-forums li.bbp-footer,
            .yz-wall-actions .yz-wall-post,
            .yz-post-content .yz-post-type,
            .yz-nav-effect .yz-menu-border,
            #group-create-tabs li.current,
            .group-button.accept-invite,
            .yz-tab-post .yz-read-more,
            .group-button.join-group,
            .yz-service-icon i:hover,
            .yz-loading .youzer_msg,
            .yz-scrolltotop i:hover,
            .yz-post .yz-read-more,
            .yzb-author .yzb-login,
            .pagination .current,
            .yz-tab-title-box,
            .yzw-file-post,
            .button.accept {
                background-color: $scheme_color !important;
            }

            .yz-bbp-topic-head-meta .yz-bbp-head-meta-last-updated a:not(.bbp-author-name),
            .widget_display_topics li .topic-author a.bbp-author-name,
            .activity-header .activity-head p a:not(:first-child),
            #message-recipients .highlight .highlight-meta a,
            .thread-sender .thread-from .from .thread-count,
            .yz-profile-navmenu .yz-navbar-item a:hover i,
            .widget_display_replies li a.bbp-author-name,
            .yz-profile-navmenu .yz-navbar-item a:hover,
            .yz-link-main-content .yz-link-url:hover,
            .yz-wall-new-post .yz-post-title a:hover,
            .yz-recent-posts .yz-post-title a:hover,
            .yz-post-content .yz-post-title a:hover,
            .yz-group-settings-tab fieldset legend,
            .yz-wall-link-data .yz-wall-link-url,
            .yz-tab-post .yz-post-title a:hover,
            .yz-project-tags .yz-tag-symbole,
            .yz-post-tags .yz-tag-symbole,
            .yz-group-navmenu li a:hover {
                color: $scheme_color !important;
            }
            
            .yz-bbp-topic-head,
            .yz-profile-navmenu .yz-navbar-item.yz-active-menu,
            .yz-group-navmenu li.current {
                border-color: $scheme_color !important;
            }

        ";

        wp_add_inline_style( 'yz-style', $custom_css );
    }

    /**
     * Gradient Elements.
     */
    function get_gradient_elements() {

        // New Array
        $elements = array();

        $elements[] = array(
            'selector'      => '.quote-with-img:before',
            'left_color'    => 'yz_wg_quote_gradient_left_color',
            'right_color'   => 'yz_wg_quote_gradient_right_color'
        );

        $elements[] = array(
            'selector'      => '.yz-box-email',
            'left_color'    => 'yz_ibox_email_bg_left',
            'right_color'   => 'yz_ibox_email_bg_right'
        );

        $elements[] = array(
            'selector'      => '.yz-box-phone',
            'left_color'    => 'yz_ibox_phone_bg_left',
            'right_color'   => 'yz_ibox_phone_bg_right'
        );

        $elements[] = array(
            'selector'      => '.yz-box-website',
            'left_color'    => 'yz_ibox_website_bg_left',
            'right_color'   => 'yz_ibox_website_bg_right'
        );

        $elements[] = array(
            'selector'      => '.yz-box-address',
            'left_color'    => 'yz_ibox_address_bg_left',
            'right_color'   => 'yz_ibox_address_bg_right'
        );

        $elements[] = array(
            'target'        => 'yz-style',
            'pattern'       => 'geometric',
            'selector'      => '.yz-user-balance-box',
            'left_color'    => 'yz_user_balance_gradient_left_color',
            'right_color'   => 'yz_user_balance_gradient_right_color'
        );

        return $elements;
    }

    /**
     * Gradient Styling.
     */
    function gradient_styling() {

        // Get Active Styles.
        $active_styles = (array) yz_options( 'yz_active_styles' );

        // Get Elements
        $elements = $this->get_gradient_elements();

        foreach ( $elements as $key => $data ) {
            if ( ! in_array( $data['left_color'], $active_styles ) && ! in_array( $data['right_color'], $active_styles ) ) {
                unset( $elements[ $key ] );
            }            
        }

        if ( empty( $elements ) ) {
            return false;
        }

        // Pattern Path
        foreach ( $elements as $element ) {

            // Get Target
            $target = isset( $element['target'] ) ? $element['target'] : 'yz-profile';
            // Get Pattern Data.
            $pattern_type = isset( $element['pattern'] ) ? 'geopattern' : 'dotted-bg'; 
            $pattern = 'url(' . YZ_PA . 'images/' . $pattern_type . '.png)';

            // Get Options Data
            $selector    = $element['selector'];
            $left_color  = yz_options( $element['left_color'] );
            $right_color = yz_options( $element['right_color'] );

            // Get Colors
            $left_color  = isset( $left_color['color'] ) ? $left_color['color'] : null;
            $right_color =  isset( $right_color['color'] ) ? $right_color['color'] : null;

            // if the one of the values are empty go out.
            if ( empty( $left_color ) || empty( $right_color ) ) {
                continue;
            }

            $custom_css = "
                $selector {
                    background: $pattern,linear-gradient(to right, $left_color , $right_color ) !important;
                    background: $pattern,-webkit-linear-gradient(left, $left_color , $right_color ) !important;
                }
            ";

            wp_add_inline_style( $target, $custom_css );
        }
    }

}