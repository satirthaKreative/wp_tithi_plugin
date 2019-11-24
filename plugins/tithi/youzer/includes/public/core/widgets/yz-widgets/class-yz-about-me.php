<?php

class YZ_About_Me {

    function __construct() {
    }

    /**
     * # About me Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 10,
            'widget_icon'   => 'fas fa-user',
            'widget_name'   => 'about_me',
            'main_data'     => 'wg_about_me_bio',
            'widget_title'  => yz_options( 'yz_wg_aboutme_title' ),
            'load_effect'   => yz_options( 'yz_aboutme_load_effect' ),
            'display_title' => yz_options( 'yz_wg_about_me_display_title' ),
        );

        // Filter
        $args = apply_filters( 'yz_about_me_widget_args', $args );

        return $args;
    }

    /**
     * # Profile Content.
     */
    function widget() {

    	// Get Widget Data
    	$wg_photo      = $this->about_me_photo();
        $wg_biography  = esc_textarea( yz_data( 'wg_about_me_bio' ) );
        $photo_border  = yz_options( 'yz_wg_aboutme_img_format' );
        $wg_description = esc_textarea( yz_data( 'wg_about_me_desc' ) );
        $wg_title      = sanitize_text_field( yz_data( 'wg_about_me_title' ) );

    	?>

    	<div class="yz-aboutme-content yz-default-content">

    		<div class="yz-user-img yz-photo-<?php echo $photo_border; ?>" style="background-image: url(<?php echo $wg_photo; ?>);"></div>

    		<div class="yz-aboutme-container">

                <?php if ( $wg_title || $wg_description ) : ?>
    			<div class="yz-aboutme-head">
    				<h2 class="yz-aboutme-name"><?php echo $wg_title; ?></h2>
    				<h2 class="yz-aboutme-description"><?php echo $wg_description; ?></h2>
    			</div>
                <?php endif; ?>

                <?php do_action( 'yz_after_about_me_widget_head' ); ?>

                <?php if ( $wg_biography ) : ?>
                    <p class="yz-aboutme-bio"><?php echo $wg_biography; ?></p>
                <?php endif; ?>

    		</div>

    	</div>

    	<?php

    }

    /**
     * # Profile Settings.
     */
    function settings() {

        global $Yz_Settings;

        // Get Args 
        $args = $this->args();

        $Yz_Settings->get_field(
            array(
                'title' => yz_options( 'yz_wg_aboutme_title' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'wg_about_me_photo',
                'title' => __( 'Upload photo', 'youzer' ),
                'desc'  => __( 'upload about me photo', 'youzer' ),
                'type'  => 'image'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'title', 'youzer' ),
                'id'    => 'wg_about_me_title',
                'desc'  => __( 'type your full name', 'youzer' ),
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'description', 'youzer' ),
                'desc'  => __( 'type your position', 'youzer' ),
                'id'    => 'wg_about_me_desc',
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'biography', 'youzer' ),
                'id'    => 'wg_about_me_bio',
                'desc'  => __( 'add your biography', 'youzer' ),
                'type'  => 'textarea'
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
                'desc'  => __( 'show widget title area', 'youzer' ),
                'id'    => 'yz_wg_about_me_display_title',
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_aboutme_title',
                'desc'  => __( 'type widget name', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_aboutme_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'image border style', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    =>  'yz_wg_aboutme_img_format',
                'desc'  => __( 'widget photo border style', 'youzer' ),
                'type'  => 'imgSelect',
                'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
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
                'title' => __( 'title', 'youzer' ),
                'id'    => 'yz_wg_aboutme_title_color',
                'desc'  => __( 'user full name color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'position', 'youzer' ),
                'id'    => 'yz_wg_aboutme_desc_color',
                'desc'  => __( 'user position color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'biography', 'youzer' ),
                'id'    => 'yz_wg_aboutme_txt_color',
                'desc'  => __( 'user biography color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'title border', 'youzer' ),
                'id'    => 'yz_wg_aboutme_head_border_color',
                'desc'  => __( 'widget title border', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

    /**
     * # Get "About Me" Photo.
     */
    function about_me_photo() {
        $about_me_photo = yz_data( 'wg_about_me_photo' );
        $wg_photo = yz_get_file_url( $about_me_photo );
        if ( ! $wg_photo ) {
            $profile_photo = esc_url( yz_data( 'profile_photo' ) );
            $wg_photo      = yz_get_user_profile_photo( $profile_photo );
        }
        return $wg_photo;
    }

}