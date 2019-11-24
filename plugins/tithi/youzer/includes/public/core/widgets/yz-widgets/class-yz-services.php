<?php

class YZ_Services {

    /**
     * # Services Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'menu_order'     => 40,
            'widget_icon'    => 'fas fa-wrench',
            'widget_name'    => 'services',
            'main_data'      => 'youzer_services',
            'widget_title'   => yz_options( 'yz_wg_services_title' ),
            'load_effect'    => yz_options( 'yz_services_load_effect' ),
            'display_title'  => yz_options( 'yz_wg_services_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_services_widget_args', $args );

        return $args;
    }

    /**
     * # Content.
     */
    function widget() {

        // Get Services Layout
        $services_layout = yz_options( 'yz_wg_services_layout' );

        ?>

        <div class="yz-services-content <?php echo $services_layout; ?>">

        <?php

            // Get All the Exist Services.
            $services = yz_data( 'youzer_services' );

            if ( ! empty( $services ) ) :

                // Show / Hide Services Elements
                $display_icon  = yz_options( 'yz_display_service_icon' );
                $display_text  = yz_options( 'yz_display_service_text' );
                $display_title = yz_options( 'yz_display_service_title' );
                $icon_border   = yz_options( 'yz_wg_service_icon_bg_format' );

                foreach ( $services as $service ) :

                // Get Services Data .
                $service_title = $service['title'];
                $service_desc  = $service['description'];
                $service_icon  = ! empty( $service['icon'] ) ? $service['icon'] : 'fas fa-globe';

                if ( ! $service_title ) {
                    continue;
                }

            ?>

            <div class="yz-service-item">

                <div class="yz-service-inner">

                    <?php if ( 'on' == $display_icon && $service_icon ) : ?>
                        <div class="yz-service-icon yz-icons-<?php echo $icon_border; ?>">
                            <i class="<?php echo $service_icon ;?>"></i>
                        </div>
                    <?php endif; ?>

                    <div class="yz-item-content">
                        <?php if ( 'on' == $display_title && $service_title ) : ?>
                            <h2 class="yz-item-title"><?php echo sanitize_text_field( $service_title );?></h2>
                        <?php endif; ?>
                        <?php if ( 'on' == $display_text && $service_desc ) : ?>
                            <p><?php echo esc_textarea( $service_desc ); ?></p>
                         <?php endif; ?>
                    </div>

                </div>

            </div>

            <?php endforeach; endif; ?>

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
                'title'          => yz_options( 'yz_wg_services_title' ),
                'button_text'    => __( 'add new service', 'youzer' ),
                'id'             => $args['widget_name'],
                'icon'           => $args['widget_icon'],
                'button_id'      => 'yz-service-button',
                'widget_section' => true,
                'type'           => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'yz-services-data',
                'type'  => 'hidden'
            ), false, 'yz_data'
        );

        echo '<ul class="yz-wg-opts yz-wg-services-options">';
        $this->get_user_services();
        echo '</ul>';

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Services Options.
     */
    function get_user_services() {

        $i = 0;
        global $Yz_Translation;
        $services_options = yz_data( 'youzer_services' );
        $max_services_nbr = yz_options( 'yz_wg_max_services' );

        if ( ! empty( $services_options ) ) :

        // Options titles
        $label_icon  = __( 'service icon', 'youzer' );
        $label_title = __( 'service title', 'youzer' );
        $label_desc  = __( 'service description', 'youzer' );

        foreach ( $services_options as $service ) : $i++;

            // init Variables.
            $service_title = sanitize_text_field( $service['title'] );
            $service_desc  = esc_textarea( $service['description'] );
            $service_icon  = ! empty( $service['icon'] ) ? $service['icon'] : 'fas fa-globe';

        ?>

        <li class="yz-wg-item" data-wg="services">
            <div class="yz-wg-container">

                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php echo $label_icon; ?></label>
                            <p class="option-desc"><?php echo $Yz_Translation['serv_desc_icon']; ?></p>
                        </div>
                        <div class="option-content">
                            <div id="ukai_icon_<?php echo $i; ?>" class="ukai_iconPicker" data-icons-type="web-application">
                                <div class="ukai_icon_selector">
                                    <i class="<?php echo apply_filters( 'yz_service_item_icon', $service_icon ); ?>"></i>
                                    <span class="ukai_select_icon"><i class="fas fa-sort-down"></i></span>
                                </div>
                                <input type="hidden" class="ukai-selected-icon" name="youzer_services[<?php echo $i; ?>][icon]" value="<?php echo $service_icon; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php echo $label_title; ?></label>
                            <p class="option-desc"><?php echo $Yz_Translation['serv_desc_title']; ?></p>
                        </div>
                        <div class="option-content">
                            <input type="text" name="youzer_services[<?php echo $i; ?>][title]" value="<?php echo $service_title; ?>" placeholder="<?php echo $label_title; ?>">
                        </div>
                    </div>
                </div>

                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php echo $label_desc; ?></label>
                            <p class="option-desc"><?php echo $Yz_Translation['serv_desc_desc']; ?></p>
                        </div>
                        <div class="option-content">
                            <textarea name="youzer_services[<?php echo $i; ?>][description]" placeholder="<?php echo $label_desc; ?>"><?php echo $service_desc; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <a class="yz-delete-item"></a>
        </li>

        <?php endforeach; endif; ?>

        <script>
            var yz_service_nextCell = <?php echo $i+1; ?>,
                yz_max_services_nbr = <?php echo $max_services_nbr; ?>;
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
                'id'    => 'yz_wg_services_display_title',
                'desc'  => __( 'show services title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_services_title',
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
                'id'    => 'yz_services_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'allowed services number', 'youzer' ),
                'desc'  => __( 'maximum allowed services number', 'youzer' ),
                'id'    => 'yz_wg_max_services',
                'type'  => 'number'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Services Box Layouts', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    =>  'yz_wg_services_layout',
                'desc'  => __( 'services widget layouts', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'services_layout' ),
                'type'  => 'imgSelect',
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'services icon background style', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'yz_wg_service_icon_bg_format',
                'type'  => 'imgSelect',
                'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget visibility setting', 'youzer' ),
                'class' => 'ukai-box-3cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'service icon', 'youzer' ),
                'desc'  => __( 'show services icon', 'youzer' ),
                'id'    => 'yz_display_service_icon',
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'service title', 'youzer' ),
                'desc'  => __( 'show services title', 'youzer' ),
                'id'    => 'yz_display_service_title',
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'service description', 'youzer' ),
                'id'    => 'yz_display_service_text',
                'desc'  => __( 'show services description', 'youzer' ),
                'type'  => 'checkbox'
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
                'title' => __( 'service icon', 'youzer' ),
                'id'    => 'yz_wg_service_icon_color',
                'desc'  => __( 'service icon color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'service icon background', 'youzer' ),
                'id'    => 'yz_wg_service_icon_bg_color',
                'desc'  => __( 'service icon background', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'service title', 'youzer' ),
                'id'    => 'yz_wg_service_title_color',
                'desc'  => __( 'service title color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'service description', 'youzer' ),
                'desc'  => __( 'service description color', 'youzer' ),
                'id'    => 'yz_wg_service_text_color',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }
}