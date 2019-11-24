<?php

class YZ_Flickr {

    /**
     * # Flickr Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 100,
            'widget_name'   => 'flickr',
            'widget_icon'   => 'fab fa-flickr',
            'main_data'     => 'wg_flickr_account_id',
            'widget_title'  => yz_options( 'yz_wg_flickr_title' ),
            'load_effect'   => yz_options( 'yz_flickr_load_effect' ),
            'display_title' => yz_options( 'yz_wg_flickr_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_flickr_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {

        // Get User Data
        $flickr_id     = yz_data( 'wg_flickr_account_id' );
        $photos_number = yz_options( 'yz_wg_max_flickr_items' );

        // Get Flickr Photos.
        $flickr_photos = $this->get_flickr_photos( $flickr_id, $photos_number );

        if ( ! $flickr_photos ) {
            _e( 'Flickr Server is Not working ... Please Refresh Page !', 'youzer' );
            return;
        }
        
        echo '<ul id="yz-flickr-wg" class="yz-photos-content yz-flickr-photos">';

        foreach ( $flickr_photos as $photo ) : ?>

        <li>
            <figure class="yz-project-item">
                <div class="yz-projet-img" style="background-image: url(<?php echo $photo['thumbnail']; ?>)"></div>
                <figcaption>
                    <a class="yz-flickr-zoom" rel="nofollow noopener"><i class="fas fa-search"></i></a>
                    <a class="yz-lightbox-img" rel="nofollow noopener" href="<?php echo $photo['full']; ?>" data-lightbox="yz-flickr"></a>
                </figcaption>
            </figure>
        </li>

        <?php endforeach;
        
        echo "</ul>";

    }

    /**
     * Get Flickr Photos By User Id.
     */
    function get_flickr_photos( $user_id = false, $limit = 100 ) {

        // Get Transient ID.
        $transient_id = 'yz_flickr_feed_' . $user_id;
        
        // Get Feed.
        $feed = apply_filters( 'yz_flickr_widget_get_transient', get_transient( $transient_id ) );
        
        if ( false === $feed ) {
                
            // Init Vars.
            $apiKey  = 'ed5819327dbcf671ce68e550e2b0d4d0';
            $method  = 'flickr.people.getPublicPhotos';

            // Get Data Link.
            $feed_url = "https://api.flickr.com/services/rest/?method=$method&api_key=$apiKey&user_id=$user_id&format=json&per_page=$limit&nojsoncallback=1";

            // Init Vars.;
            $feed = array();
            $remote = wp_remote_get( $feed_url );

            // Check if Url is Working.
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
            $data = json_decode( $response, true );

            if ( ! isset( $data['photos'] ) ) {
                return false;
            }

            foreach ( $data['photos']['photo'] as $photo ) :

                $photo_url = 'https://farm' . $photo['farm'] . '.static.flickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'];

                // Get Image Data.
                $image = array(
                    'full'      => $photo_url .'_z.jpg',
                    'thumbnail' => $photo_url .'_q.jpg',
                );

                // Fill Images with the new image item.
                array_push( $feed, $image );

            endforeach;
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
                'title' => yz_options( 'yz_wg_flickr_title' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'flickr ID', 'youzer' ),
                'id'    => 'wg_flickr_account_id',
                'desc'  => __( 'flickr ID format exemple : 12345678@N07', 'youzer' ),
                'type'  => 'text'
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
                'id'    => 'yz_wg_flickr_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_flickr_title',
                'desc'  => __( 'add widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
                'id'    => 'yz_flickr_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'allowed Photos number', 'youzer' ),
                'id'    => 'yz_wg_max_flickr_items',
                'desc'  => __( 'maximum allowed photos', 'youzer' ),
                'std'   => 6,
                'type'  => 'number'
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
                'title' => __( 'icon hover background', 'youzer' ),
                'id'    => 'yz_wg_flickr_img_icon_bg_color',
                'desc'  => __( 'zoom icon hover background color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'icon hover color', 'youzer' ),
                'id'    => 'yz_wg_flickr_img_icon_color',
                'desc'  => __( 'zoom icon hover color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}