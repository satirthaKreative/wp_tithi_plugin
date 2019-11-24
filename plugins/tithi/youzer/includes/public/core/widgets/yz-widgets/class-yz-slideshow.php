<?php

class YZ_Slideshow {

    /**
     * # Slideshow Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 30,
            'widget_name'   => 'slideshow',
            'widget_icon'   => 'fas fa-film',
            'main_data'     => 'youzer_slideshow',
            'widget_title'  => yz_options( 'yz_wg_slideshow_title' ),
            'load_effect'   => yz_options( 'yz_slideshow_load_effect' ),
            'display_title' => yz_options( 'yz_wg_slideshow_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_slideshow_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {

        // Get Slides.
        $slides = yz_data( 'youzer_slideshow' );

        if ( empty( $slides ) ) {
            return false;
        }

        // Get Slides Height Option
        $height_option = yz_options( 'yz_slideshow_height_type' );

        ?>

        <ul class="yz-slider yz-slides-<?php echo $height_option; ?>-height">

        <?php

            foreach ( $slides as $slide ) :

            // Get Slide Image Url
            $slide_url = yz_get_file_url( $slide );

            // Check Slide Image Existence
            if ( ! yz_is_image_exists( $slide_url ) ) {
                continue;
            }

    	?>

		<li class="yz-slideshow-item">
            <?php if ( 'auto' == $height_option ) : ?>
            <img src="<?php echo $slide_url; ?>" alt="" >
            <?php else : ?>
            <div class="yz-slideshow-img" style="background-image: url(<?php echo $slide_url; ?>)" ></div>
            <?php endif; ?>
        </li>

        <?php endforeach; ?>

    	</ul>

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
                'title'          => yz_options( 'yz_wg_slideshow_title' ),
                'button_text'    => __( 'add new slide', 'youzer' ),
                'button_id'      => 'yz-slideshow-button',
                'id'             => $args['widget_name'],
                'icon'           => $args['widget_icon'],
                'widget_section' => true,
                'type'           => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'yz-slideshow-data',
                'type'  => 'hidden'
            ), false, 'yz_data'
        );

        echo '<ul class="yz-wg-opts yz-wg-slideshow-options yz-cphoto-options">';
        $this->get_user_slideshow();
        echo '</ul>';

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Get User Slides.
     */
    function get_user_slideshow() {

        $i = 0;
        $slides     = yz_data( 'youzer_slideshow' );
        $max_photos =  yz_options( 'yz_wg_max_slideshow_items' );

        // Options titles
        $field_button = __( 'upload photo', 'youzer' );

        if ( ! empty( $slides ) ) :

        foreach ( $slides as $slide ) :

            // Get Slide Image Url
            $item_img = yz_get_file_url( $slide );

            // Check Slide Image Existence
            if ( ! yz_is_image_exists( $item_img ) ) {
                continue;
            }

            $i++;

        ?>

        <li class="yz-wg-item" data-wg="slideshow">
            <div class="yz-wg-container">
                <div class="yz-cphoto-content">
                    <div class="uk-option-item">
                        <div class="yz-uploader-item">
                            <div class="yz-photo-preview" style="background-image: url(<?php echo $item_img; ?>);"></div>
                            <label for="yz_slideshow_<?php echo $i; ?>" class="yz-upload-photo" ><?php echo $field_button; ?></label>
                            <input id="yz_slideshow_<?php echo $i; ?>" type="file" name="yz_slideshow_<?php echo $i; ?>" class="yz_upload_file" accept="image/*" />
                            <input type="hidden" name="youzer_slideshow[<?php echo $i; ?>][original]" value="<?php echo $slide['original']; ?>" class="yz-photo-url">
                            <input type="hidden" name="youzer_slideshow[<?php echo $i; ?>][thumbnail]" value="<?php echo $slide['thumbnail']; ?>" class="yz-photo-thumbnail">
                        </div>
                    </div>
                </div>
            </div>
            <a class="yz-delete-item"></a>
        </li>

        <?php endforeach; endif; ?>

        <script>
            var yz_ss_nextCell = <?php echo $i+1; ?>,
                yz_max_slideshow_img = <?php echo $max_photos; ?>;
        </script>

        <?php

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
                'id'    => 'yz_wg_slideshow_display_title',
                'desc'  => __( 'show slideshow title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_slideshow_title',
                'desc'  => __( 'slideshow widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the slideshow to be loaded?', 'youzer' ),
                'id'    => 'yz_slideshow_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'allowed slides number', 'youzer' ),
                'id'    => 'yz_wg_max_slideshow_items',
                'desc'  => __( 'maximum allowed slides', 'youzer' ),
                'type'  => 'number'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'yz_slideshow_height_type',
                'title' => __( 'slides height type', 'youzer' ),
                'desc'  => __( 'set slides height type', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'height_types' ),
                'type'  => 'select',
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'slideshow styling settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'pagination color', 'youzer' ),
                'desc'  => __( 'slider pagination color', 'youzer' ),
                'id'    => 'yz_wg_slideshow_pagination_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'slideshow buttons', 'youzer' ),
                'desc'  => __( '"Next" & "Prev" color', 'youzer' ),
                'id'    => 'yz_wg_slideshow_np_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}