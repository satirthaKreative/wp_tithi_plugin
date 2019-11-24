<?php

class YZ_Post {

    /**
     * # Post Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 90,
            'display_title' => false,
            'widget_name'   => 'post',
            'widget_icon'   => 'fas fa-pencil-alt',
            'main_data'     => 'yz_profile_wg_post_id',
            'widget_title'  => yz_options( 'yz_wg_post_title' ),
            'load_effect'   => yz_options( 'yz_post_load_effect' )
        );

        // Filter
        $args = apply_filters( 'yz_post_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {
        // Get Post Data
        $post_type  = yz_data( 'wg_post_type' );
        $post_id    = yz_data( 'yz_profile_wg_post_id' );
        // Call Post TYPE FUNCTION
        $this->get_post_type( $post_id, $post_type );
    }

    /***
     * Get Post Type.
     */
    function get_post_type( $post_id, $post_type ) {

        // Get Post Data.
        $post = get_post( $post_id );

        if ( ! $post ) {
            return false;
        }

        // Show / Hide Post Elements
        $display_cats     = yz_options( 'yz_display_wg_post_cats' );
        $display_date     = yz_options( 'yz_display_wg_post_date' );
        $display_meta     = yz_options( 'yz_display_wg_post_meta' );
        $display_tags     = yz_options( 'yz_display_wg_post_tags' );
        $display_readmore = yz_options( 'yz_display_wg_post_readmore' );
        $display_excerpt  = yz_options( 'yz_display_wg_post_excerpt' );
        $display_comments = yz_options( 'yz_display_wg_post_comments' );
        $display_icons    = yz_options( 'yz_display_wg_post_meta_icons' );

        ?>

        <div class="yz-post-content">

            <?php yz_get_post_thumbnail( array( 'post_id' => $post_id ) ); ?>

            <div class="yz-post-container">

                <div class="yz-post-inner-content">

                    <div class="yz-post-head">

                        <a class="yz-post-type"><?php echo $post_type; ?></a>

                        <h2 class="yz-post-title"><a href="<?php the_permalink( $post_id ); ?>"><?php echo $post->post_title; ?></a></h2>

                        <?php if ( 'on' == $display_meta ) : ?>

                        <div class="yz-post-meta">

                            <ul>

                                <?php if ( 'on' == $display_date ) : ?>
                                    <li>
                                        <?php
                                            if ( 'on' == $display_icons ) {
                                                echo '<i class="far fa-calendar-alt"></i>';
                                            }
                                            // Print date.
                                            echo get_the_date( 'F j, Y', $post_id );
                                        ?>
                                    </li>
                                <?php endif; ?>

                                <?php
                                    if ( 'on' == $display_cats )  {
                                        yz_get_post_categories( $post_id, $display_icons );
                                    }
                                ?>

                                <?php if ( 'on' == $display_comments ) : ?>
                                    <li>
                                        <?php

                                            if ( 'on' == $display_icons ) {
                                                echo '<i class="far fa-comments"></i>';
                                            }

                                            // Print Comments Number
                                            echo $post->comment_count;

                                        ?>
                                    </li>
                                <?php endif; ?>

                            </ul>

                        </div>

                        <?php endif; ?>

                    </div>

                    <?php if ( 'on' == $display_excerpt ) : ?>
                        <div class="yz-post-text">
                            <p><?php echo yz_get_excerpt( $post->post_content, 35 ) ; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php  if ( 'on' == $display_tags ) { $this->get_post_tags( $post_id ); } ?>

                    <?php if ( 'on' == $display_readmore ) : ?>
                        <a href="<?php the_permalink( $post_id ); ?>" class="yz-read-more">
                            <div class="yz-rm-icon">
                                <i class="fas fa-angle-double-right"></i>
                            </div>
                            <?php _e( 'read more', 'youzer' ); ?>
                        </a>
                    <?php endif; ?>

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
                'title' => yz_options( 'yz_wg_post_title' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post Type', 'youzer' ),
                'id'    => 'wg_post_type',
                'desc'  => __( 'choose post type', 'youzer' ),
                'opts'  => yz_get_select_options( 'yz_wg_post_types' ),
                'type'  => 'select'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post', 'youzer' ),
                'id'    => 'yz_profile_wg_post_id',
                'desc'  => __( 'choose your post', 'youzer' ),
                'opts'  => $this->get_user_posts_titles( bp_displayed_user_id() ),
                'type'  => 'select'
            ), true
        );

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Get Post Tags
     */
    function get_post_tags( $post_id ) {

        ?>

        <ul class="yz-post-tags">

        <?php

            $tags_list = get_the_tags( $post_id );

            if ( $tags_list ) :

                foreach( $tags_list as $tag ) :
                    $link     = get_tag_link( $tag->term_taxonomy_id );
                    $tag_name = $tag->name;
                    $tag_link = "<a href='$link'>$tag_name</a>";
                    echo "<li><span class='yz-tag-symbole'>#</span>$tag_link</li>";
                endforeach;

            endif;

        ?>

        </ul>

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
                'title' => __( 'post types', 'youzer' ),
                'id'    => 'yz_wg_post_types',
                'desc'  => __( 'add post types', 'youzer' ),
                'type'  => 'taxonomy'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
                'id'    => 'yz_post_load_effect',
                'type'  => 'select'
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
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_post_title',
                'desc'  => __( 'type widget title', 'youzer' ),
                'type'  => 'text'
            )
        );
        
        $Yz_Settings->get_field(
            array(
                'title' => __( 'post meta', 'youzer' ),
                'id'    => 'yz_display_wg_post_meta',
                'desc'  => __( 'show post meta', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post meta icons', 'youzer' ),
                'desc'  => __( 'show meta icons', 'youzer' ),
                'id'    => 'yz_display_wg_post_meta_icons',
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post date', 'youzer' ),
                'id'    => 'yz_display_wg_post_date',
                'desc'  => __( 'show post date', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post comments', 'youzer' ),
                'id'    => 'yz_display_wg_post_comments',
                'desc'  => __( 'show post comments', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post excerpt', 'youzer' ),
                'id'    => 'yz_display_wg_post_excerpt',
                'desc'  => __( 'show post excerpt', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post tags', 'youzer' ),
                'id'    => 'yz_display_wg_post_tags',
                'desc'  => __( 'show post tags', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'read more', 'youzer' ),
                'id'    => 'yz_display_wg_post_readmore',
                'desc'  => __( 'show read more button', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Styling widget', 'youzer' ),
                'class' => 'ukai-box-3cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post type background', 'youzer' ),
                'id'    => 'yz_wg_post_type_bg_color',
                'desc'  => __( 'post type background', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'post type text', 'youzer' ),
                'id'    => 'yz_wg_post_type_txt_color',
                'desc'  => __( 'type text color ', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'title', 'youzer' ),
                'id'    => 'yz_wg_post_title_color',
                'desc'  => __( 'post title color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'meta', 'youzer' ),
                'id'    => 'yz_wg_post_meta_txt_color',
                'desc'  => __( 'post meta color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'meta icons', 'youzer' ),
                'id'    => 'yz_wg_post_meta_icon_color',
                'desc'  => __( 'meta icons color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'excerpt', 'youzer' ),
                'id'    => 'yz_wg_post_text_color',
                'desc'  => __( 'post excerpt color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'tags', 'youzer' ),
                'id'    => 'yz_wg_post_tags_color',
                'desc'  => __( 'post tags color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'tags background', 'youzer' ),
                'id'    => 'yz_wg_post_tags_bg_color',
                'desc'  => __( 'tags background color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'tags hashtag', 'youzer' ),
                'id'    => 'yz_wg_post_tags_hashtag_color',
                'desc'  => __( 'post hashtags color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'read more', 'youzer' ),
                'id'    => 'yz_wg_post_rm_color',
                'desc'  => __( 'read more text color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'read more background', 'youzer' ),
                'id'    => 'yz_wg_post_rm_bg_color',
                'desc'  => __( 'read more background color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'read more icon', 'youzer' ),
                'id'    => 'yz_wg_post_rm_icon_color',
                'desc'  => __( 'read more icon color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    }

    /**
     * # Get User Posts Titles for Post Settings
     */
    function get_user_posts_titles( $user_id ) {

        $post_titles = array();

        $args = array(
            'author'         => $user_id,
            'orderby'        => 'post_date',
            'posts_per_page' => -1,
            'order'          => 'DESC'
            );

        $posts = get_posts( $args );

        // Add No Post Option.
        $post_titles[] = __( 'No Post', 'youzer' );
        
        if ( $posts ) {
            foreach ( $posts as $post ) {
                $post_titles[ $post->ID ] = $post->post_title;
            }
            return $post_titles;
        }

    }

}