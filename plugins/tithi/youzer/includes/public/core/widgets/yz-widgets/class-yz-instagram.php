<?php

class YZ_Instagram {

    /**
     * Constructor
     */
    function __construct() {

        // Actions.
        add_action( 'init', array( $this, 'process_authentication' ) );
        add_filter( 'yz_is_widget_visible', array( $this, 'is_widget_visible' ), 10, 2 );
        add_filter( 'yz_profile_widget_visibility', array( $this, 'display_widget' ), 10, 2 );

    }

    /**
     * # Display Widget.
     */
    function is_widget_visible( $visibility, $widget_name ) {

        if ( 'instagram' != $widget_name ) {
            return $visibility;
        }

        // Get Instagram Account.
        $app_id = yz_options( 'yz_wg_instagram_app_id' );
        $app_secret = yz_options( 'yz_wg_instagram_app_secret' );

        if ( empty( $app_id ) || empty( $app_secret ) ) {
            return false;
        }

        return true;

    }

    /**
     * # Display Widget.
     */
    function display_widget( $visibility, $widget_name ) {

        if ( 'instagram' != $widget_name ) {
            return $visibility;
        }

        if ( ! $this->is_widget_visible( false, 'instagram' ) ) {
            return false;
        }

        global $Youzer;

        // Get Instagram Account.
        $instagram = yz_data( 'wg_instagram_account_token' );

        if ( empty( $instagram ) ) {
            return false;
        }

        return true;

    }

    /**
     * # Instagram Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 100,
            'widget_name'   => 'instagram',
            'widget_icon'   => 'fab fa-instagram',
            'main_data'     => 'wg_instagram_account_id',
            'widget_title'  => yz_options( 'yz_wg_instagram_title' ),
            'load_effect'   => yz_options( 'yz_instagram_load_effect' ),
            'display_title' => yz_options( 'yz_wg_instagram_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_instagram_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {

        // Get User Data
        $user_id = bp_displayed_user_id();
        $photos_number = yz_options( 'yz_wg_max_instagram_items' );

        // Get Instagram Photos
        $instagram_photos = $this->get_instagram_photos( $user_id, $photos_number );

        ?>

        <ul class="yz-portfolio-content yz-instagram-photos">

        <?php

            if ( ! empty( $instagram_photos ) ) :

            foreach ( $instagram_photos as $photo ) :

            // Get Photo Url.
            $photo_path = esc_url( $photo['full'] );
            $full_photo_path = esc_url( $photo['full'] );

            // Get Photo Data .
            $photo_link  = esc_url( $photo['link'] );
            $photo_title = sanitize_text_field( $photo['title'] );

            // If Photo Link is not available replace it with Photo Source Link
            if ( ! $photo_link ) {
                $photo_link = $photo_path;
            }

            // Show / Hide Instagram Elements
            $display_url = yz_options( 'yz_display_instagram_url' );
            $display_zoom = yz_options( 'yz_display_instagram_zoom' );
            $display_title = yz_options( 'yz_display_instagram_title' );

            // Photo Caption
            $data_data = null;
            if ( 'on' == $display_title && $photo_title ) {
                $data_data = "data-title='$photo_title'";
            }

        ?>

        <li>
            <figure class="yz-project-item">
                <div class="yz-projet-img" style="background-image: url(<?php echo $photo_path; ?>)" ></div>
                <figcaption class="yz-pf-buttons">
                        <?php if ( 'on' == $display_url ) : ?>
                            <a class="yz-pf-url" rel="nofollow noopener" href="<?php echo $photo_link; ?>" target="_blank" >
                                <i class="fas fa-link"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ( 'on' == $display_zoom ) : ?>
                            <a class="yz-pf-zoom"><i class="fas fa-search"></i></a>
                        <?php endif; ?>

                        <a class="yz-lightbox-img" rel="nofollow noopener" href="<?php echo $full_photo_path; ?>" data-lightbox="yz-instagram" <?php echo $data_data; ?>></a>
                </figcaption>
            </figure>
        </li>

        <?php endforeach; endif; ?>

        </ul>

        <?php
    }

    /**
     * Get Instagram Photos By Username
     */
    function get_instagram_photos( $user_id, $limit = 6 ) {

        // Init Vars.
        $images = array();
       
        // Get Data
        $instagram_data = $this->get_data( $user_id, $limit );

        // if data is empty return false.
        if ( empty( $instagram_data['data'] ) ) {
            return false;
        }

        foreach ( $instagram_data['data'] as $data ) :
            // Get Image Data.
            $image = array(
                'type'      => $data['type'],
                'full'      => $data['images']['standard_resolution']['url'],
                'original'  => $data['images']['standard_resolution']['url'],
                'small'     => $data['images']['thumbnail']['url'],
                'thumbnail' => $data['images']['thumbnail']['url'],
                'time'      => $data['created_time'],
                'likes'     => $data['likes']['count'],
                'comments'  => $data['comments']['count'],
                'title'     => $data['attribution'],
                'link'      => $data['link'],
            );

            // Fill Images with the new image item.
            array_push( $images, $image );

        endforeach;

        return $images;
    }

    /**
     * Check if account is working.
     */
    function get_data( $user_id = null, $limit = 6 ) {

        // Get Transient ID.
        $transient_id = 'yz_instagram_feed_' . $user_id;
        
        // Get Feed.
        $feed = apply_filters( 'yz_instagram_widget_get_transient', get_transient( $transient_id ) );
        
        if ( false === $feed ) {

        // Get Access Token
        $token = yz_data( 'wg_instagram_account_token', $user_id );

        // Get User Images Feed
        $profile_url = 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $token . '&count=' . $limit;

        $remote = wp_remote_get( $profile_url );

        // Check if remote is returning a false answer
        if ( is_wp_error( $remote ) ) {
            return false;
        }

        // Check If Url Is working.
        if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
           return false;
        }

        // GET User Data.
        $response = wp_remote_retrieve_body( $remote );
        if ( $response === false ) {
            return false;
        }
        
        // Decode Data.
        $feed = json_decode( $response, true );

        if ( $feed === null ) {
            return false;
        }
            // Set Cache.
            set_transient( $transient_id, $feed, HOURS_IN_SECONDS );
        }

        return $feed;
    }

    /**
     * # Settings.
     */
    function settings() {

        global $Yz_Settings;

        // Get Args 
        $args = $this->args();

        $Yz_Settings->get_field(
            array(
                'title' => yz_options( 'yz_wg_instagram_title' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'icon'  => 'instagram',
                'provider' => 'instagram',
                'id'    => 'wg_instagram_account_token',
                'title' => __( 'Instagram Username', 'youzer' ),
                'button'=> __( 'Connect with instagram', 'youzer' ),
                'desc'  => __( 'Connect to your instagram account so we can get the permission to display your photos', 'youzer' ),
                'type'  => 'connect'
            ), true
        );

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
                'id'    => 'yz_wg_instagram_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_instagram_title',
                'desc'  => __( 'add widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
                'id'    => 'yz_instagram_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'allowed Photos number', 'youzer' ),
                'id'    => 'yz_wg_max_instagram_items',
                'desc'  => __( 'maximum allowed photos', 'youzer' ),
                'std'   => 6,
                'type'  => 'number'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget visibility settings', 'youzer' ),
                'class' => 'ukai-box-3cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'photo title', 'youzer' ),
                'id'    => 'yz_display_instagram_title',
                'desc'  => __( 'show photo title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'photo url', 'youzer' ),
                'id'    => 'yz_display_instagram_url',
                'desc'  => __( 'show link button', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'photo zoom', 'youzer' ),
                'id'    => 'yz_display_instagram_zoom',
                'desc'  => __( 'show zoom button', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget styling settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'icon background', 'youzer' ),
                'id'    => 'yz_wg_instagram_img_icon_bg_color',
                'desc'  => __( 'icon background color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'icon hover color', 'youzer' ),
                'id'    => 'yz_wg_instagram_img_icon_color',
                'desc'  => __( 'icon text color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'icon hover background', 'youzer' ),
                'id'    => 'yz_wg_instagram_img_icon_bg_color_hover',
                'desc'  => __( 'icon hover background color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'icon hover color', 'youzer' ),
                'id'    => 'yz_wg_instagram_img_icon_color_hover',
                'desc'  => __( 'icon text hover color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );        

        $Yz_Settings->get_field(
            array(
                'msg_type'  => 'info',
                'type'      => 'msgBox',
                'id'        => 'yz_msgbox_instagram_wg_app_setup_steps',
                'title'     => __( 'How to get instagram keys?', 'youzer' ),
                'msg'       => implode( '<br>', $this->get_instagram_app_register_steps() )
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Instagram app settings', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Application ID', 'youzer' ),
                'desc'  => __( 'enter application ID', 'youzer' ),
                'id'    => 'yz_wg_instagram_app_id',
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Application Secret', 'youzer' ),
                'desc'  => __( 'enter application secret key', 'youzer' ),
                'id'    => 'yz_wg_instagram_app_secret',
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    }

    /**
     * Authenticate User.
     */
    public function process_authentication() {

        if ( ! is_user_logged_in() || ! isset( $_GET['action'] ) || 'yz_account_connect' != $_GET['action'] || 'instagram' != $_GET['provider'] ) {
            return false;
        }
        
        // Inculue Files.
        if ( ! class_exists( 'Hybrid_Auth', false ) ) {
            require_once( YZ_PUBLIC_CORE . 'hybridauth/Hybrid/Auth.php' );
        }

        if ( ! class_exists( 'Hybrid_Endpoint', false ) ) {
            require_once( YZ_PUBLIC_CORE . "hybridauth/Hybrid/Endpoint.php" );
        }

        try {

            // Get Instagram app ID & Secret.
            $app_id = yz_options( 'yz_wg_instagram_app_id' );
            $app_secret = yz_options( 'yz_wg_instagram_app_secret' );

            $config['providers']['Instagram'] = array( 
                'base_url'   => home_url( '/' ),
                "debug_file" => 'debug-instagram.txt',
                "debug_mode" => true,
                'enabled' => true,
                'keys' => array(
                    'id'  => $app_id,
                    'secret' => $app_secret,
                )
            );

            // Create an Instance with The Config Data.
            $hybridauth = new Hybrid_Auth( $config );

            // Start the Authentication Process.
            $adapter = $hybridauth->authenticate( 'Instagram' );

            $accessToken = $adapter->getAccessToken();

            if ( isset( $accessToken['access_token'] ) && ! empty( $accessToken['access_token'] ) ) {
                update_user_meta( get_current_user_id(), 'wg_instagram_account_token', $accessToken['access_token'] );
            }

            // Get User Data.
            $user_data = $adapter->getUserProfile();

            if ( ! empty( $user_data ) ) {
                update_user_meta( get_current_user_id(), 'wg_instagram_account_user_data', $user_data );
                do_action( 'yz_after_linking_instagram_account', get_current_user_id(), $accessToken['access_token']  );
            }

        } catch( Exception $e ) {

            yz_auth_redirect( $e );

        }
        
        wp_redirect( yz_get_widgets_settings_url( 'instagram', get_current_user_id() ) );
        exit;
    }
    
    /**
     * How to register an instagram application
     */
    function get_instagram_app_register_steps() {

            // Init Vars.
            $apps_url = 'https://instagram.com/developer/clients/manage/';
            $auth_url = home_url( '/?hauth.done=Instagram' ); 
            
            // Get Note
            $steps[] = __( '<strong><a>Note:</a> You should submit your application for review and it should be approved in order to make your website users able to use the instagram widget.</strong>', 'youzer' ) . '<br>'; 
            
            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzer' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Register new Client".', 'youzer' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'youzer' );
            $steps[] = __( '4. Put the below url as OAuth redirect_uri  Authorized Redirect URLs:', 'youzer' );
            $steps[] = sprintf( __( '5. Redirect Url: <strong><a>%s</a></strong>', 'youzer' ), $auth_url );
            $steps[] = __( '6. Once you have registered, copy the created application credentials ( Client ID and Secret ) .', 'youzer' );
            return $steps;
    }

}