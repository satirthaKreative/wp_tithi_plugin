<?php

class YZ_Quote {

    /**
     * # Quote Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 60,
            'widget_name'   => 'quote',
            'widget_icon'   => 'fas fa-quote-right',
            'main_data'     => 'wg_quote_txt',
            'widget_title'  => yz_options( 'yz_wg_quote_title' ),
            'load_effect'   => yz_options( 'yz_quote_load_effect' ),
            'display_title' => yz_options( 'yz_wg_quote_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_quote_widget_args', $args );

        return $args;
    }
    
    /**
     * # Content.
     */
    function widget() {

        // Get User Data
        $img_data     = yz_data( 'wg_quote_img' );
        $quote_img    = yz_get_file_url( $img_data );
        $use_bg       = yz_data( 'wg_quote_use_bg' );
        $quote_txt    = esc_textarea( yz_data( 'wg_quote_txt' ) );
        $quote_bg     = "style='background-image:url( $quote_img );'";
        $quote_owner  = sanitize_text_field( yz_data( 'wg_quote_owner' ) );

        ?>

        <div class="yz-quote-content quote-with-img">
            <?php if ( ! empty( $quote_img ) && 'on' == $use_bg ) : ?>
                <div class="yz-quote-cover" <?php echo $quote_bg; ?>></div>
            <?php endif; ?>
            <div class="yz-quote-main-content">
                <div class="yz-quote-icon"><i class="fas fa-quote-right"></i></div>
                <blockquote><?php echo nl2br( $quote_txt ); ?></blockquote>
                <h3 class="yz-quote-owner"><?php echo $quote_owner; ?></h3>
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
                'title' => yz_options( 'yz_wg_quote_title' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'use background Image', 'youzer' ),
                'id'    => 'wg_quote_use_bg',
                'desc'  => __( 'use quote cover', 'youzer' ),
                'type'  => 'checkbox'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title'  => __( 'quote background image', 'youzer' ),
                'id'     => 'wg_quote_img',
                'desc'   => __( 'upload quote cover', 'youzer' ),
                'type'   => 'image'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'quote text', 'youzer' ),
                'id'    => 'wg_quote_txt',
                'desc'  => __( 'type quote text', 'youzer' ),
                'type'  => 'textarea'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'quote owner', 'youzer' ),
                'desc'  => __( 'type quote owner', 'youzer' ),
                'id'    => 'wg_quote_owner',
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
                'id'    => 'yz_wg_quote_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_quote_title',
                'desc'  => __( 'type widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_quote_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'cover gradient settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Left Color', 'youzer' ),
                'id'    => 'yz_wg_quote_gradient_left_color',
                'desc'  => __( 'gradient left color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Right Color', 'youzer' ),
                'id'    => 'yz_wg_quote_gradient_right_color',
                'desc'  => __( 'gradient right color', 'youzer' ),
                'type'  => 'color'
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
                'title' => __( 'quote icon background', 'youzer' ),
                'id'    => 'yz_wg_quote_icon_bg',
                'desc'  => __( 'icon background color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'quote icon', 'youzer' ),
                'desc'  => __( 'quote icon color', 'youzer' ),
                'id'    => 'yz_wg_quote_icon',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'quote text', 'youzer' ),
                'id'    => 'yz_wg_quote_txt',
                'desc'  => __( 'quote text color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'quote owner', 'youzer' ),
                'desc'  => __( 'quote owner title', 'youzer' ),
                'id'    => 'yz_wg_quote_owner',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}