<?php

if ( ! class_exists( 'Youzer' ) ) :

/**
 * Main Youzer Class.
 */
class Youzer {

    /**
     * Init Vars
     */
    private static $instance;
    public $profile;
    public $group;
    public $header;
    public $account;
    public $fields;
    public $user;
    public $tabs;
    public $ajax;
    public $wall;
    public $widgets;
    public $styling;
    public $version = '2.1.5';
    public $account_verification;

    /**
     * Main Youzer Instance.
     */
    public static function instance() {


        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Youzer ) ) {
        
            self::$instance = new Youzer;

            // Setup Constants.
            self::$instance->setup_constants();

            add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

            self::$instance->includes();

            // Init Classes
            self::$instance->profile  = new Youzer_Profile();
            self::$instance->group    = new Youzer_Group();
            self::$instance->header   = new Youzer_Header();
            self::$instance->account  = new Youzer_Account();
            self::$instance->fields   = new Youzer_Fields();
            self::$instance->author   = new Youzer_Author();
            self::$instance->user     = new Youzer_User();
            self::$instance->tabs     = new Youzer_Tabs();
            self::$instance->ajax     = new Youzer_Ajax();
            self::$instance->wall     = new Youzer_Wall();
            self::$instance->widgets  = new Youzer_Widgets();
            self::$instance->styling  = new Youzer_Styling();
            self::$instance->account_verification = new Youzer_Account_Verification();

            // Setup Globals.
            self::$instance->youzer_globals();

            // Setup Actions.
            self::$instance->setup_actions();

        }

        return self::$instance;
    }

    /**
     * Setup plugin constants.
     *
     * @access private
     * @since 2.1.0
     * @return void
     */
    private function setup_constants() {

        // Youzer Basename
        define( 'YOUZER_BASENAME', plugin_basename( __FILE__ ) );

        // Youzer Path.
        define( 'YZ_PATH', plugin_dir_path( __FILE__ ) );

        // Youzer Path.
        define( 'YZ_URL', plugin_dir_url( __FILE__ ) );

        // Templates Path.
        define( 'YZ_TEMPLATE', YZ_PATH . 'includes/public/templates/' );

        // Public & Admin Core Path's
        define( 'YZ_PUBLIC_CORE', YZ_PATH. 'includes/public/core/' );
        define( 'YZ_ADMIN_CORE', YZ_PATH . 'includes/admin/core/' );

        // Assets ( PA = Public Assets & AA = Admin Assets ).
        define( 'YZ_PA', plugin_dir_url( __FILE__ ) . 'includes/public/assets/' );
        define( 'YZ_AA', plugin_dir_url( __FILE__ ) . 'includes/admin/assets/' );

    }

    /**
     * Load Youzer Text Domain!
     */
    public function load_textdomain() {

        $domain = 'youzer';
        $mofile_custom = trailingslashit( WP_LANG_DIR ) . sprintf( '%s-%s.mo', $domain, get_locale() );

        if ( is_readable( $mofile_custom ) ) {
            return load_textdomain( $domain, $mofile_custom );
        } else {
            return load_plugin_textdomain( $domain, FALSE, dirname( YOUZER_BASENAME ) . '/languages/' );

        }
    }
    

    /** nb
     * Include required files.
     */
    private function includes() {

        // Youzer General Functions.
        require_once YZ_PUBLIC_CORE . 'functions/yz-general-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-buddypress-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-scripts-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-profile-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-groups-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-user-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-admin-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-xprofile-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-account-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-messages-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-navbar-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-mailchimp-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-mailster-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-account-verification-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-authentication-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/yz-export-functions.php';
        
        if ( yz_is_bpfollowers_active() ) {
            require_once YZ_PUBLIC_CORE . 'functions/yz-buddypress-followers-integration.php';
        }
        
        if ( yz_is_mycred_installed() ) {
            require_once YZ_PUBLIC_CORE . 'mycred/yz-mycred-functions.php';
        }
        
        if ( yz_is_bbpress_installed() ) {
            require_once YZ_PUBLIC_CORE . 'functions/yz-bbpress.php';
        }

        // Wall Functions.
        require_once YZ_PUBLIC_CORE . 'functions/wall/yz-wall-form-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/wall/yz-wall-general-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/wall/yz-wall-profile-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/wall/yz-wall-groups-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/wall/yz-wall-activity-functions.php';

        // Posts Tools.
        require_once YZ_PUBLIC_CORE . 'functions/posts-tools/yz-posts-tools-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/posts-tools/yz-wall-sticky-posts-functions.php';
        
        // Directory Functions.
        require_once YZ_PUBLIC_CORE . 'functions/directories/yz-members-directory-functions.php';
        require_once YZ_PUBLIC_CORE . 'functions/directories/yz-groups-directory-functions.php';

        // Youzer Classes.
        require_once YZ_PUBLIC_CORE . 'class-yz-header.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-widgets.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-author.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-fields.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-user.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-tabs.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-ajax.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-wall.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-groups.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-styling.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-account-verification.php';

        // Include Youzer Main Pages.
        require_once YZ_PUBLIC_CORE . 'pages/yz-account.php';
        require_once YZ_PUBLIC_CORE . 'pages/yz-profile.php';

        // Youzer Profile Tabs.
        require_once YZ_PUBLIC_CORE . 'tabs/yz-tab-wall.php';
        require_once YZ_PUBLIC_CORE . 'tabs/yz-tab-info.php';
        require_once YZ_PUBLIC_CORE . 'tabs/yz-tab-posts.php';
        require_once YZ_PUBLIC_CORE . 'tabs/yz-custom-tabs.php';
        require_once YZ_PUBLIC_CORE . 'tabs/yz-tab-overview.php';
        require_once YZ_PUBLIC_CORE . 'tabs/yz-tab-comments.php';

        // Youzer Account Settings.
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-ads.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-post.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-link.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-video.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-quote.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-skills.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-flickr.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-groups.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-project.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-friends.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-reviews.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-about-me.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-services.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-slideshow.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-instagram.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-user-tags.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-portfolio.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-basic-infos.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-infos-boxes.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-user-badges.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-user-balance.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-recent-posts.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-custom-infos.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-custom-widgets.php';
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-social-networks.php';

        // Wordpress Widgets.
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-author-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-rss-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-my-account-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-mods-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-post-author-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-admins-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-smart-author-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-activity-rss-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-notifications-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-suggestions-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-friend-suggestions-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-description-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-mycred-balance-widget.php';
        require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-verified-users-widget.php';
        require_once YZ_PUBLIC_CORE . 'install.php';

        // Include Membership System.
        if ( yz_is_membership_system_active() ) {
            require_once YZ_PATH . 'includes/logy/logy.php';
        }

        global $Youzer_Admin;
        
        // Init Admin
        if ( is_admin() ) {
            require_once YZ_PATH . 'includes/admin/class.youzer-admin.php';
            $Youzer_Admin = new Youzer_Admin();
        }

    }

    /**
     * # Youzer Global Variables .
     */
    private function youzer_globals() {

        global $wpdb, $Yz_Translation, $Yz_Settings, $YZ_upload_url, $YZ_upload_dir, $Logy_users_table, $Yz_bookmark_table, $Yz_reviews_table;

        // Get Data.
        $Yz_Settings = $this->fields;

        // Get Uploads Directory Path.
        $upload_dir = wp_upload_dir();

        // Get Uploads Directory.
        $YZ_upload_url = apply_filters( 'youzer_upload_url', $upload_dir['baseurl'] . '/youzer/', $upload_dir['baseurl'] );
        $YZ_upload_dir = apply_filters( 'youzer_upload_dir', $upload_dir['basedir'] . '/youzer/', $upload_dir['basedir'] );

        // Translation Data.
        $Yz_Translation = $this->global_localize();

        // Get Table Names.
        $Logy_users_table = $wpdb->prefix . 'logy_users';
        $Yz_bookmark_table = $wpdb->prefix . 'yz_bookmark';
        $Yz_reviews_table = $wpdb->prefix . 'yz_reviews';
        
    }

    /**
     * Youzer Text Domain
     */
    function global_localize() {
        
        global $YZ_upload_url;

        // Init Var.
        $localize = array(
            'try_later'             => __( 'Something went wrong, please try again later.', 'youzer' ),
            'reset_error'           => __( 'An Error Occurred While Resetting The Options !!', 'youzer' ),
            'move_wg'               => __( 'This widget can\'t be moved to the other side.', 'youzer' ),
            'empty_network'         => __( 'Network Name Is Empty or Is Already Exist', 'youzer' ),
            'empty_wg'              => __( 'Widget Title Is Empty or Is Already Exist', 'youzer' ),
            'empty_ad'              => __( 'Ad Name Is Empty or Is Already Exist !', 'youzer' ),
            'items_nbr'             => __( 'The Number Of Items Allowed is ', 'youzer' ),
            'reset_dialog_title'    => __( 'Resetting Options Confirmation', 'youzer' ),
            'name_exist'            => __( 'The Name Is Already Exist !', 'youzer' ),
            'no_networks'           => __( 'No social networks Found !', 'youzer' ),
            'no_custom_widgets'     => __( 'No custom widgets Found !', 'youzer' ),
            'required_fields'       => __( 'all fields are required !', 'youzer' ),
            'invalid_url'           => __( 'Please enter a valid URL.', 'youzer' ),
            'utag_name_empty'       => __( 'user tag name is empty!', 'youzer' ),
            'empty_banner'          => __( 'Banner field is empty !', 'youzer' ),
            'banner_url'            => __( 'Banner Url not working.', 'youzer' ),
            'serv_desc_desc'        => __( 'add service description', 'youzer' ),
            'tab_url_empty'         => __( 'Tab LinK Url is Empty!', 'youzer' ),
            'no_custom_tabs'        => __( 'No custom tabs Found !', 'youzer' ),
            'tab_code_empty'        => __( 'Tab Content is Empty!', 'youzer' ),
            'empty_field'           => __( 'Field Name Is Empty !', 'youzer' ),
            'update_user_tag'       => __( 'update user tags type', 'youzer' ),
            'no_user_tags'          => __( 'No user tags Found !', 'youzer' ),
            'serv_desc_icon'        => __( 'select service icon', 'youzer' ),
            'service_desc'          => __( 'service description', 'youzer' ),
            'tab_title_empty'       => __( 'Tab title is empty!', 'youzer' ),
            'empty_options'         => __( 'Options are empty !', 'youzer' ),
            'no_wg'                 => __( 'No widgets Found !', 'youzer' ),
            'serv_desc_title'       => __( 'type service title', 'youzer' ),
            'code_empty'            => __( 'AD Code is Empty!', 'youzer' ),
            'skill_desc_percent'    => __( 'skill bar percent', 'youzer' ),
            'skill_desc_title'      => __( 'type skill title', 'youzer' ),
            'no_items'              => __( 'no items found !', 'youzer' ),
            'skill_desc_color'      => __( 'skill bar color', 'youzer' ),
            'processing'            => __( 'processing... !', 'youzer' ),
            'no_ads'                => __( 'No ads Found !', 'youzer' ),
            'update_network'        => __( 'update network', 'youzer' ),
            'update_widget'         => __( 'update widget', 'youzer' ),
            'service_title'         => __( 'service title', 'youzer' ),
            'update_field'          => __( 'update field', 'youzer' ),
            'service_icon'          => __( 'service icon', 'youzer' ),
            'save_changes'          => __( 'save changes', 'youzer' ),
            'upload_photo'          => __( 'upload photo', 'youzer' ),
            'error_msg'             => __( 'Ops, Error !', 'youzer' ),
            'photo_title'           => __( 'photo title', 'youzer' ),
            'show_wg'               => __( 'Show Widget', 'youzer' ),
            'hide_wg'               => __( 'Hide Widget', 'youzer' ),
            'edit_item'             => __( 'delete item', 'youzer' ),
            'photo_path'            => __( 'photo path', 'youzer' ),
            'update_tab'            => __( 'update tab', 'youzer' ),
            'bar_percent'           => __( 'percent (%)', 'youzer' ),
            'photo_link'            => __( 'photo link', 'youzer' ),
            'success_msg'           => __( "Success !", 'youzer' ),
            'edit_item'             => __( 'edit item', 'youzer' ),
            'update_ad'             => __( 'update ad', 'youzer' ),
            'got_it'                => __( 'got it!', 'youzer' ),
            'bar_title'             => __( 'title', 'youzer' ),
            'bar_color'             => __( 'color', 'youzer' ),
            'done'                  => __( 'save', 'youzer' ),
            // Passing Data.
            'default_img' => YZ_PA . 'images/default-img.png',
            'ajax_url'    => admin_url( 'admin-ajax.php' ),
            'upload_url' => $YZ_upload_url

        );

        return apply_filters( 'yz_global_localize_vars', $localize );

    }

    /**
     * Set up the default hooks and actions.
     *
     */
    private function setup_actions() {

        // Add actions to plugin activation and deactivation hooks
        add_action( 'activate_'   . YOUZER_BASENAME, 'youzer_activation'   );
        add_action( 'deactivate_' . YOUZER_BASENAME, 'youzer_deactivation' );

        // If Youzer is being deactivated, do not add any actions
        if ( yz_is_deactivation( YOUZER_BASENAME ) ) {
            return;
        }

        // Array of Youzer core actions
        $actions = array(
            'init',              // Setup the default theme compat
            'setup_theme',              // Setup the default theme compat
            'setup_current_user',       // Setup currently logged in user
            'register_post_types',      // Register post types
            'register_post_statuses',   // Register post statuses
            'register_taxonomies',      // Register taxonomies
            'register_views',           // Register the views
            'register_theme_directory', // Register the theme directory
            'register_theme_packages',  // Register bundled theme packages (bp-themes)
            'load_textdomain',          // Load textdomain
            'add_rewrite_tags',         // Add rewrite tags
            'generate_rewrite_rules'    // Generate rewrite rules
        );

        // Add the actions
        foreach( $actions as $class_action ) {
            if ( method_exists( $this, $class_action ) ) {
                add_action( 'youzer_' . $class_action, array( $this, $class_action ), 5 );
            }
        }

        /**
         * Fires after the setup of all BuddyPress actions.
         *
         * Includes bbp-core-hooks.php.
         *
         * @since 1.7.0
         *
         * @param BuddyPress $this. Current BuddyPress instance. Passed by reference.
         */
        do_action_ref_array( 'youzer_after_setup_actions', array( &$this ) );
    }

}

endif;