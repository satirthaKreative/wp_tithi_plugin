<?php

class YZ_Recent_Posts {

    /**
     * # Recent Posts Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'widget_icon'   => 'fas fa-newspaper',
            'widget_name'   => 'recent_posts',
            'main_data'     => 'recent_posts',
            'widget_title'  => yz_options( 'yz_wg_rposts_title' ),
            'load_effect'   => yz_options( 'yz_rposts_load_effect' ),
            'display_title' => yz_options( 'yz_wg_rposts_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_recent_posts_widget_args', $args );

        return $args;
    }
    

    /**
     * # Content.
     */
    function widget() {

    	// Get Data .
	    $posts_number  = yz_options( 'yz_wg_max_rposts' );
        $photos_border = yz_options( 'yz_wg_rposts_img_format' );

        $recent_posts = get_posts( array(
            'author'  => bp_displayed_user_id(),
            'orderby' => 'date',
            'order'   => 'desc',
            'numberposts' => $posts_number
        ) );

		?>
		<div class="yz-posts-by-author yz-recent-posts yz-rp-img-<?php echo $photos_border; ?>">
            <?php foreach ( $recent_posts as $post ) : ?>
            <div class="yz-post-item">
                <?php yz_get_post_thumbnail( array( 'post_id' => $post->ID, 'img_size' => 'thumbnail' ) );
                ?>
                <div class="yz-post-head">
                    <h2 class="yz-post-title">
                        <a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_the_title( $post->ID ); ?></a>
                    </h2>
                    <div class="yz-post-meta">
                        <ul><li><?php echo get_the_date( '',$post->ID ); ?></li></ul>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
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
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_rposts_title',
                'desc'  => __( 'type widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_rposts_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'display title', 'youzer' ),
                'id'    => 'yz_wg_rposts_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'allowed posts number', 'youzer' ),
                'desc'  => __( 'maximum allowed posts', 'youzer' ),
                'id'    => 'yz_wg_max_rposts',
                'std'   => 3,
                'type'  => 'number'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'posts thumbnail border style', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'yz_wg_rposts_img_format',
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
                'title' => __( 'post title', 'youzer' ),
                'desc'  => __( 'post title color', 'youzer' ),
                'id'    => 'yz_wg_rposts_title_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post date', 'youzer' ),
                'id'    => 'yz_wg_rposts_date_color',
                'desc'  => __( 'post date color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}