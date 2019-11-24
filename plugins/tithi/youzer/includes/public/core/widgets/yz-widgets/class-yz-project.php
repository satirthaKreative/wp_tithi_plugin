<?php

class YZ_Project {

    /**
     * # Project Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'    => 50,
            'display_title' => false,
            'widget_name'   => 'project',
            'widget_icon'   => 'fas fa-suitcase',
            'main_data'     => 'wg_project_desc',
            'widget_title'  => yz_options( 'yz_wg_project_title' ),
            'load_effect'   => yz_options( 'yz_project_load_effect' )
        );

        // Filter
        $args = apply_filters( 'yz_project_widget_args', $args );

        return $args;
    }

    /**
     * # Profile Content.
     */
    function widget() {

    	// Get Project Data
        $project_tags        = yz_data( 'wg_project_tags' );
        $project_categories  = yz_data( 'wg_project_categories' );
        $project_link        = yz_esc_url( yz_data( 'wg_project_link' ) );
        $img_data            = yz_data( 'wg_project_thumbnail' );
        $project_thumbnail   = yz_get_file_url( $img_data );
        $project_description = esc_textarea( yz_data( 'wg_project_desc' ) );
        $project_type        = sanitize_text_field( yz_data( 'wg_project_type' ) );
        $project_title       = sanitize_text_field( yz_data( 'wg_project_title' ) );

        // Get Categories.
    	if ( $project_categories ) {
            $project_categories = implode( ', ', $project_categories );
        }

    	// Show / Hide Project Elements
    	$display_meta  = yz_options( 'yz_display_prjct_meta' );
        $display_tags  = yz_options( 'yz_display_prjct_tags' );
    	$display_icons = yz_options( 'yz_display_prjct_meta_icons' );

        if ( ! $project_title && ! $project_description ) {
            return false;
        }

    	?>

    	<div class="yz-project-content">
    		<?php
                yz_get_post_thumbnail(
                    array(
                        'widget'  => 'project',
                        'img_url' => $project_thumbnail
                    )
                );
            ?>
    		<div class="yz-project-container">
    			<div class="yz-project-inner-content">
    				<div class="yz-project-head">

                        <?php if ( $project_type ) : ?>
    					   <a class="yz-project-type"><?php echo $project_type; ?></a>
                        <?php endif; ?>

                        <?php if ( $project_title ) : ?>
    					   <h2 class="yz-project-title"><?php echo $project_title; ?></h2>
                        <?php endif; ?>

    					<?php if ( 'on' == $display_meta ) : ?>
    					<div class="yz-project-meta">
    						<ul>
                                <?php if ( $project_categories ) : ?>
        							<li class="yz-project-categories">
            							<?php if ( 'on' == $display_icons ) : ?>
                                            <i class="fas fa-tags"></i>
                                        <?php endif ?>
                                        <?php echo $project_categories; ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ( $project_link ) : ?>
                                    <li class="yz-project-link">
            							<?php if ( 'on' == $display_icons ) : ?>
                                            <i class="fas fa-link"></i>
                                        <?php endif; ?>
                                        <a href="<?php echo esc_url( $project_link ) ;?>"><?php echo $project_link; ?></a>
            							
                                    </li>
                                <?php endif; ?>
    						</ul>
    					</div>
    					<?php endif; ?>
    				</div>

    				<div class="yz-project-text">
    					<p><?php echo $project_description; ?></p>
    				</div>

    				<?php if ( 'on' == $display_tags ) : ?>
        				<div class="yz-project-tags">
        					<?php yz_get_project_tags( $project_tags ); ?>
        				</div>
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
                'title' => yz_options( 'yz_wg_project_title' ),
                'id'    => $args['widget_name'],
                'icon'  => $args['widget_icon'],
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Project Type', 'youzer' ),
                'id'    => 'wg_project_type',
                'desc'  => __( 'choose project type', 'youzer' ),
                'opts'  => yz_get_select_options( 'yz_wg_project_types' ),
                'type'  => 'select'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'title', 'youzer' ),
                'id'    => 'wg_project_title',
                'desc'  => __( 'type project title', 'youzer' ),
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project thumbnail', 'youzer' ),
                'id'    => 'wg_project_thumbnail',
                'desc'  => __( 'upload project thumbnail', 'youzer' ),
                'type'  => 'image'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project link', 'youzer' ),
                'id'    => 'wg_project_link',
                'desc'  => __( 'add project link', 'youzer' ),
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project description', 'youzer' ),
                'id'    => 'wg_project_desc',
                'desc'  => __( 'add project description', 'youzer' ),
                'type'  => 'textarea'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Project Categories', 'youzer' ),
                'desc'  => __( 'add project categories', 'youzer' ),
                'id'    => 'wg_project_categories',
                'type'  => 'taxonomy'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Project tags', 'youzer' ),
                'id'    => 'wg_project_tags',
                'desc'  => __( 'add project tags', 'youzer' ),
                'type'  => 'taxonomy'
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
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_project_title',
                'desc'  => __( 'type widget title', 'youzer' ),
                'type'  => 'text'
            )
        );
        
        $Yz_Settings->get_field(
            array(
                'title' => __( 'Project Types', 'youzer' ),
                'id'    => 'yz_wg_project_types',
                'desc'  => __( 'add project types', 'youzer' ),
                'type'  => 'taxonomy'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_project_load_effect',
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
                'title' => __( 'project meta', 'youzer' ),
                'desc'  => __( 'show project meta', 'youzer' ),
                'id'    => 'yz_display_prjct_meta',
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project meta icons', 'youzer' ),
                'desc'  => __( 'show project icons', 'youzer' ),
                'id'    => 'yz_display_prjct_meta_icons',
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project tags', 'youzer' ),
                'id'    => 'yz_display_prjct_tags',
                'desc'  => __( 'show project tags', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget styling settings', 'youzer' ),
                'class' => 'ukai-box-3cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'type background', 'youzer' ),
                'desc'  => __( 'project type background color', 'youzer' ),
                'id'    => 'yz_wg_project_type_bg_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project type text', 'youzer' ),
                'desc'  => __( 'type text color', 'youzer' ),
                'id'    => 'yz_wg_project_type_txt_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project title', 'youzer' ),
                'desc'  => __( 'project title color', 'youzer' ),
                'id'    => 'yz_wg_project_title_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project meta', 'youzer' ),
                'id'    => 'yz_wg_project_meta_txt_color',
                'desc'  => __( 'project meta color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project meta icons', 'youzer' ),
                'id'    => 'yz_wg_project_meta_icon_color',
                'desc'  => __( 'project icons color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project description', 'youzer' ),
                'desc'  => __( 'project description color', 'youzer' ),
                'id'    => 'yz_wg_project_desc_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'project tags', 'youzer' ),
                'id'    => 'yz_wg_project_tags_color',
                'desc'  => __( 'project tags color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'tags background', 'youzer' ),
                'id'    => 'yz_wg_project_tags_bg_color',
                'desc'  => __( 'tags background color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'tags hashtag', 'youzer' ),
                'desc'  => __( 'project hashtags color', 'youzer' ),
                'id'    => 'yz_wg_project_tags_hashtag_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}