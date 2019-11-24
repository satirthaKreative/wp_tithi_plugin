<?php

class YZ_Link {

    /**
     * # Link Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 70,
            'widget_name'   => 'link',
            'widget_icon'   => 'fas fa-link',
            'main_data'     => 'wg_link_url',
            'widget_title'  => yz_options( 'yz_wg_link_title' ),
            'load_effect'   => yz_options( 'yz_link_load_effect' ),
            'display_title' => yz_options( 'yz_wg_link_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_link_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {

        // Get Widget Data
        $use_bg      = yz_data( 'wg_link_use_bg' );
        $link_url    = esc_url( yz_data( 'wg_link_url' ) );
        $img_data    = yz_data( 'wg_link_img' );
        $link_img    = yz_get_file_url( $img_data );
        $link_txt    = sanitize_text_field( yz_data( 'wg_link_txt' ) );
        $link_bg     = "style='background-image:url( $link_img );'";

        ?>

        <div class="yz-link-content link-with-img">
            <?php if ( $link_img && 'on' == $use_bg ) : ?>
                <div class="yz-link-cover" <?php echo $link_bg; ?>></div>
            <?php endif; ?>
            <div class="yz-link-main-content">
                <div class="yz-link-inner-content">
                    <div class="yz-link-icon"><i class="fas fa-link"></i></div>
                    <?php if ( $link_txt ) : ?>
                        <p><?php echo $link_txt; ?></p>
                    <?php endif; ?>
                    <a href="<?php echo $link_url; ?>" class="yz-link-url" target="_blank" rel="nofollow noopener"><?php echo yz_esc_url( $link_url ); ?></a>
                </div>
            </div>
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
                'title' => yz_options( 'yz_wg_link_title' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'use background Image', 'youzer' ),
                'id'    => 'wg_link_use_bg',
                'desc'  => __( 'use link cover', 'youzer' ),
                'type'  => 'checkbox'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'link backgorund image', 'youzer' ),
                'id'    => 'wg_link_img',
                'desc'  => __( 'upload link cover', 'youzer' ),
                'type'  => 'image'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'link description', 'youzer' ),
                'id'    => 'wg_link_txt',
                'desc'  => __( 'add link description', 'youzer' ),
                'type'  => 'textarea'
                ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'link url', 'youzer' ),
                'desc'  => __( 'add your link', 'youzer' ),
                'id'    => 'wg_link_url',
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
                'id'    => 'yz_wg_link_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_link_title',
                'desc'  => __( 'type widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_link_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget Styling widget', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'link icon background', 'youzer' ),
                'desc'  => __( 'icon background color', 'youzer' ),
                'id'    => 'yz_wg_link_icon_bg',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'link icon', 'youzer' ),
                'id'    => 'yz_wg_link_icon',
                'desc'  => __( 'link icon color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'link description', 'youzer' ),
                'id'    => 'yz_wg_link_txt',
                'desc'  => __( 'link description color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'link url', 'youzer' ),
                'id'    => 'yz_wg_link_url',
                'desc'  => __( 'choose link color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }
}