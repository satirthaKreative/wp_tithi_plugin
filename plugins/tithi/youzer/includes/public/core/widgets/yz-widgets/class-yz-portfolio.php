<?php

class YZ_Portfolio {

    /**
     * # Portfolio Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 30,
            'widget_name'   => 'portfolio',
            'widget_icon'   => 'fas fa-images',
            'main_data'     => 'youzer_portfolio',
            'widget_title'  => yz_options( 'yz_wg_portfolio_title' ),
            'load_effect'   => yz_options( 'yz_portfolio_load_effect' ),
            'display_title' => yz_options( 'yz_wg_portfolio_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_portfolio_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {

        $portfolio_photos = yz_data( 'youzer_portfolio' );

        ?>

    	<ul class="yz-portfolio-content">

    	<?php

		    if ( ! empty( $portfolio_photos ) ) :

            foreach ( $portfolio_photos as $photo ) :

            // Get Photo Url.
            $photo_path = esc_url( yz_get_file_url( $photo ) );

            // Check Photo Existence
            if ( ! yz_is_image_exists( $photo_path ) ) {
                continue;
            }

	        // Get Photo Data .
            $photo_link  = esc_url( $photo['link'] );
            $photo_title = sanitize_text_field( $photo['title'] );

            // If Photo Link is not available replace it with Photo Source Link
            if ( ! $photo_link ) {
                $photo_link = $photo_path;
            }

            // Show / Hide Portfolio Elements
            $display_title       = yz_options( 'yz_display_portfolio_title' );
            $display_url_button  = yz_options( 'yz_display_portfolio_url' );
            $display_zoom_button = yz_options( 'yz_display_portfolio_zoom' );

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
    					<?php if ( 'on' == $display_url_button ) : ?>
                            <a class="yz-pf-url" href="<?php echo $photo_link; ?>" target="_blank" >
                                <i class="fas fa-link"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ( 'on' == $display_zoom_button ) : ?>
                            <a class="yz-pf-zoom"><i class="fas fa-search"></i></a>
                        <?php endif; ?>

                        <a class="yz-lightbox-img" href="<?php echo $photo_path; ?>" data-lightbox="yz-portfolio" <?php echo $data_data; ?>></a>
				</figcaption>
			</figure>
		</li>

    	<?php endforeach; endif; ?>

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
                'title'          => yz_options( 'yz_wg_portfolio_title' ),
                'button_id'      => 'yz-portfolio-button',
                'button_text'    => __( 'add new photo', 'youzer' ),
                'id'             => $args['widget_name'],
                'icon'           => $args['widget_icon'],
                'widget_section' => true,
                'type'           => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'   => 'yz-portfolio-data',
                'type' => 'hidden'
            ), false, 'yz_data'
        );

        echo '<ul class="yz-wg-opts yz-wg-portfolio-options yz-cphoto-options">';
        $this->get_user_portfolio();
        echo '</ul>';

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Get User Portfolio Items.
     */
    function get_user_portfolio() {

        $i = 0;
        $photos = yz_data( 'youzer_portfolio' );
        $max_photos =  yz_options( 'yz_wg_max_portfolio_items' );

        // Options titles
        $photo_link   = __( 'PHOTO LINK', 'youzer' );
        $photo_title  = __( 'PHOTO TITLE', 'youzer' );
        $photo_button = __( 'upload photo', 'youzer' );

        if ( ! empty( $photos ) ) :

        foreach ( $photos as $photo ) :

            // Get Photo Url
            $item_img = yz_get_file_url( $photo );

            // Check Image Existence
            if ( ! yz_is_image_exists( $item_img ) ) {
                continue;
            }

            // init Variables.
            $item_title = $photo['title'];
            $item_link  = esc_url( $photo['link'] );

            $i++;

        ?>

        <li class="yz-wg-item" data-wg="portfolio">
            <div class="yz-wg-container">
                <div class="yz-cphoto-content">
                    <div class="uk-option-item">
                        <div class="yz-uploader-item">
                            <div class="yz-photo-preview" style="background-image: url(<?php echo $item_img; ?>);"></div>
                            <label for="yz_portfolio_<?php echo $i; ?>" class="yz-upload-photo" ><?php echo $photo_button; ?></label>
                            <input id="yz_portfolio_<?php echo $i; ?>" type="file" name="yz_portfolio_<?php echo $i; ?>" class="yz_upload_file" accept="image/*" />
                            <input type="hidden" name="youzer_portfolio[<?php echo $i; ?>][original]" value="<?php echo $photo['original']; ?>" class="yz-photo-url">
                            <input type="hidden" name="youzer_portfolio[<?php echo $i; ?>][thumbnail]" value="<?php echo $photo['thumbnail']; ?>" class="yz-photo-thumbnail">
                        </div>
                    </div>
                    <div class="uk-option-item">
                        <div class="option-content">
                            <input type="text" name="youzer_portfolio[<?php echo $i; ?>][title]" value="<?php echo $item_title; ?>" placeholder="<?php echo $photo_title; ?>">
                        </div>
                    </div>
                    <div class="uk-option-item">
                        <div class="option-content">
                            <input type="text" name="youzer_portfolio[<?php echo $i; ?>][link]" value="<?php echo $item_link; ?>" placeholder="<?php echo $photo_link; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <a class="yz-delete-item"></a>
        </li>

        <?php endforeach; endif; ?>

        <script>
            var yz_pf_nextCell = <?php echo $i+1; ?>,
                yz_max_portfolio_img = <?php echo $max_photos; ?>;
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
                'id'    => 'yz_wg_portfolio_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_portfolio_title',
                'desc'  => __( 'add widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_portfolio_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'allowed services number', 'youzer' ),
                'id'    => 'yz_wg_max_portfolio_items',
                'desc'  => __( 'maximum allowed services', 'youzer' ),
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
                'id'    => 'yz_display_portfolio_title',
                'desc'  => __( 'show photo title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'photo url', 'youzer' ),
                'id'    => 'yz_display_portfolio_url',
                'desc'  => __( 'show link button', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'photo zoom', 'youzer' ),
                'id'    => 'yz_display_portfolio_zoom',
                'desc'  => __( 'show zoom button', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Styling widget', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'buttons color', 'youzer' ),
                'id'    => 'yz_wg_portfolio_button_color',
                'desc'  => __( 'photo buttons color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'buttons icon', 'youzer' ),
                'id'    => 'yz_wg_portfolio_button_txt_color',
                'desc'  => __( 'button icons color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'buttons hover color', 'youzer' ),
                'id'    => 'yz_wg_portfolio_button_hov_color',
                'desc'  => __( 'buttons hover color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'icons hover', 'youzer' ),
                'desc'  => __( 'buttons icons hover color', 'youzer' ),
                'id'    => 'yz_wg_portfolio_button_txt_hov_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}