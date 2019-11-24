<?php

class YZ_Products {

    /**
     * # Products Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 20,
            'widget_icon'   => 'fas fa-dollar',
            'widget_name'   => 'products',
            'main_data'     => 'youzer_products',
            'widget_title'  => yz_options( 'yz_wg_products_title' ),
            'load_effect'   => yz_options( 'yz_products_load_effect' ),
            'display_title' => yz_options( 'yz_wg_products_display_title' ),
        );

        // Filter
        $args = apply_filters( 'yz_products_widget_args', $args );

        return $args;
    }
    
    /**
     * # Content.
     */
    function widget() {

        // Variables.
        $products = yz_data( 'youzer_products' );

        if ( empty( $products ) ) {
            return false;
        }

        echo '<div class="yz-products-content yz-default-content">';

        foreach ( $products as $product ) :

            // Get Product Data
            $barcolor   = $product['barcolor'];
            $barpercent = $product['barpercent'];
            $title      = sanitize_text_field( $product['title'] );

            //
            if ( empty( $title ) && empty( $barpercent ) ) {
                continue;
            }

            // Get Item Class
            $class = ( $barpercent > 95 ) ? 'yz-skillbar clearfix yz-whitepercent' : 'yz-skillbar clearfix';

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
                                                    echo '<i class="fas fa-comments"></i>';
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

        endforeach;

        echo '</div>';

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
                'title'          => yz_options( 'yz_wg_skills_title' ),
                'button_text'    => __( 'add new skill', 'youzer' ),
                'id'             => $args['widget_name'],
                'icon'           => $args['widget_icon'],
                'button_id'      => 'yz-skill-button',
                'widget_section' => true,
                'type'           => 'open',
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'   => 'yz-skills-data',
                'type' => 'hidden'
            ), false, 'yz_data'
        );

        echo '<ul class="yz-wg-opts yz-wg-skills-options">';
        $this->get_user_skills();
        echo '</ul>';

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Skills Content .
     */
    function get_user_skills() {

        global $Yz_Translation;

        // Get Data
        $i              = 0;
        $skills         = yz_data( 'youzer_skills' );
        $maximum_skills = yz_options( 'yz_wg_max_skills' );

        if ( ! empty( $skills ) ) :

        foreach ( $skills as $skill ) : $i++; ?>

            <li class="yz-wg-item" data-wg="skills">

                <!-- Option Item. -->
                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php _e( 'title', 'youzer' ); ?></label>
                            <p class="option-desc"><?php echo $Yz_Translation['skill_desc_title']; ?></p>
                        </div>
                        <div class="option-content">
                            <input type="text" name="youzer_skills[<?php echo $i; ?>][title]" value="<?php echo $skill['title']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Option Item. -->
                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php _e( 'percent (%)', 'youzer' ); ?></label>
                            <p class="option-desc"><?php echo $Yz_Translation['skill_desc_percent']; ?></p>
                        </div>
                        <div class="option-content">
                            <input type="number" min="1" max="100" name="youzer_skills[<?php echo $i; ?>][barpercent]" value="<?php echo $skill['barpercent']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Option Item. -->
                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php _e( 'color', 'youzer' ); ?></label>
                            <p class="option-desc"><?php echo $Yz_Translation['skill_desc_color']; ?></p>
                        </div>
                        <div class="option-content">
                            <input type="text" class="yz-picker-input" name="youzer_skills[<?php echo $i; ?>][barcolor]" value="<?php echo $skill['barcolor']; ?>">
                        </div>
                    </div>
                </div>

                <a class="yz-delete-item"></a>

            </li>

            <?php endforeach; endif; ?>

            <script>
                var yz_skill_nextCell = <?php echo $i+1 ?>,
                    yz_maximum_skills = <?php echo $maximum_skills; ?>;
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
                'desc'  => __( 'show skills title', 'youzer' ),
                'id'    => 'yz_wg_skills_display_title',
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_skills_title',
                'desc'  => __( 'type widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_skills_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Allowed Skills Number', 'youzer' ),
                'desc'  => __( 'Maximum Allowed skills number.', 'youzer' ),
                'id'    => 'yz_wg_max_skills',
                'std'   => 3,
                'type'  => 'number'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}