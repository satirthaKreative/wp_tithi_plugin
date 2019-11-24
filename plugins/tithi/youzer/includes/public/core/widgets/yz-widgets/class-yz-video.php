<?php

class YZ_Video {

    /**
     * # Video Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 80,
            'widget_name'   => 'video',
            'widget_icon'   => 'fas fa-video',
            'main_data'     => 'wg_video_url',
            'widget_title'  => yz_options( 'yz_wg_video_title' ),
            'load_effect'   => yz_options( 'yz_video_load_effect' ),
            'display_title' => yz_options( 'yz_wg_video_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_video_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {

        // Get Widget Data
        $video_url   = esc_url( yz_data( 'wg_video_url' ) );
        $video_desc  = esc_textarea( yz_data( 'wg_video_desc' ) );
        $video_title = sanitize_text_field( yz_data( 'wg_video_title' ) );

        ?>

        <div class="yz-video-content">
            <div class="fittobox">
                <?php
                    if ( false != filter_var( $video_url, FILTER_VALIDATE_URL )  ) {
                        $content = apply_filters( 'the_content', $video_url );
                        echo $content;
                    }
                ?>
            </div>

            <?php if ( ! empty( $video_title ) || ! empty( $video_desc ) ) : ?>
                <div class="yz-video-head">
                    <h2 class="yz-video-title"><?php echo $video_title; ?></h2>
                    <?php if ( $video_desc ) : ?>
                        <p class="yz-video-desc"><?php echo $video_desc; ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

        <?php

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
                'title' => yz_options( 'yz_wg_video_title' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'video title', 'youzer' ),
                'id'    => 'wg_video_title',
                'desc'  => __( 'add video title', 'youzer' ),
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'video description', 'youzer' ),
                'desc'  => __( 'add video description', 'youzer' ),
                'id'    => 'wg_video_desc',
                'type'  => 'textarea'
            ), true
        );

        // Get Supported Videos Url.
        $supported_videos = apply_filters( 'yz_account_supported_videos_url', 'https://en.support.wordpress.com/videos/' );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'video url', 'youzer' ),
                'desc'  => sprintf( __( "check the <a href='%s' target='_blank'>list of supported websites</a>", 'youzer' ), $supported_videos ),
                'id'    => 'wg_video_url',
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
                'id'    => 'yz_wg_video_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_video_title',
                'desc'  => __( 'type widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_video_load_effect',
                'type'  => 'select'
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
                'title' => __( 'video title', 'youzer' ),
                'id'    => 'yz_wg_video_title_color',
                'desc'  => __( 'video title color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'video description', 'youzer' ),
                'id'    => 'yz_wg_video_desc_color',
                'desc'  => __( 'video description color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}