<?php

class Yz_Custom_Widgets {
 
    function __construct() {
    }

    /**
     * # Content.
     */
    function widget( $widget_id ) {

        // Get Widgets.
        $custom_widgets = yz_options( 'yz_custom_widgets' );

        // Get Widget.
        $widget = $custom_widgets[ $widget_id ];

        // init Array.
        $widget_class = array();

        // Add Main Widget Class
        $widget_class[] = 'yz-custom-widget-box';

        // Add Padding class.
        $widget_class[] = ( 'true' == $widget['display_padding'] ) ? 'yz-custom-widget-box-padding' : null;

        // Generate Class. 
        $widget_class = yz_generate_class( $widget_class );

        // Display Widget.
        echo "<div class='" . $widget_class . "'>";
        echo apply_filters( 'the_content', urldecode( $widget['content'] ) );
        echo "</div>";

    }

    /**
     * # Custom Widgets Settings.
     */
    function admin_settings() {

        global $Youzer_Admin, $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'msg_type' => 'info',
                'type'     => 'msgBox',
                'title'    => __( 'info', 'youzer' ),
                'id'       => 'yz_msgbox_custom_widgets_placement',
                'msg'      => __( 'All the custom widgets created will be added automatically to the bottom of the profile sidebar to change their placement or control their visibility go to <strong>Youzer Panel > Profile Settings > Profile Structure</strong>.', 'youzer' )
            )
        );

        $modal_args = array(
            'id'        => 'yz-custom-widgets-form',
            'title'     => __( 'create new widget', 'youzer' ),
            'button_id' => 'yz-add-custom-widget'
        );

        // Get New Custom Widgets Form.
        $Youzer_Admin->panel->modal( $modal_args, array( &$this, 'form' ) );

        // Get Widgets List.
        $this->get_widgets_list();

        $Yz_Settings->get_field(
            array(
                'title' => __( 'general Settings', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'choose how you want your custom widgets to be loaded ?', 'youzer' ),
                'id'    => 'yz_custom_widgets_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

    /**
     * # Create New Custom Widgets Form.
     */
    function form() {

        // Get Data.
        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'type'  => 'openDiv',
                'class' => 'yz-custom-widgets-form'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'        => __( 'Widget icon', 'youzer' ),
                'desc'         => __( 'select widget icon', 'youzer' ),
                'id'           => 'yz_widget_icon',
                'std'          => 'fas fa-globe',
                'type'         => 'icon',
                'icons_type'   => 'web_application',
                'no_options'   => true
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'        => __( 'widget title', 'youzer' ),
                'desc'         => __( 'add widget title', 'youzer' ),
                'id'           => 'yz_widget_name',
                'type'         => 'text',
                'no_options'   => true
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'display widget title', 'youzer' ),
                'desc'       => __( 'show widget title', 'youzer' ),
                'id'         => 'yz_widget_display_title',
                'type'       => 'checkbox',
                'std'        => 'on',
                'no_options' => true
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'use widget padding', 'youzer' ),
                'desc'       => __( 'display widget padding', 'youzer' ),
                'id'         => 'yz_widget_display_padding',
                'type'       => 'checkbox',
                'std'        => 'on',
                'no_options' => true
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'widget content', 'youzer' ),
                'id'         => 'yz_widget_content',
                'desc'       => __( 'paste your shortcode or any html code', 'youzer' ),
                'type'       => 'textarea',
                'no_options' => true
            )
        );

        // Add Hidden Input
        $Yz_Settings->get_field(
            array(
                'id'         => 'yz_custom_widgets_form',
                'type'       => 'hidden',
                'class'      => 'yz-keys-name',
                'std'        => 'yz_custom_widgets',
                'no_options' => true
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

    }

    /**
     * Get Widgets List
     */
    function get_widgets_list() {

        global $Yz_Settings;

        // Get Custom Widgets
        $yz_custom_widgets = yz_options( 'yz_custom_widgets' );

        // Next ID
        $next_id = yz_options( 'yz_next_custom_widget_nbr' );
        $yz_nextCustomWidget = ! empty( $next_id ) ? $next_id : '1';

        ?>

        <script> var yz_nextCustomWidget = <?php echo $yz_nextCustomWidget; ?>; </script>

        <div class="yz-custom-section">
            <div class="yz-cs-head">
                <div class="yz-cs-buttons">
                    <button class="yz-md-trigger yz-custom-widget-button" data-modal="yz-custom-widgets-form">
                        <i class="fas fa-plus"></i>
                        <?php _e( 'add new widget', 'youzer' ); ?>
                    </button>
                </div>
            </div>
        </div>

        <ul id="yz_custom_widgets" class="yz-cs-content">

        <?php

            // Show No Ads Found .
            if ( empty( $yz_custom_widgets ) ) {
                global $Yz_Translation;
                $msg = $Yz_Translation['no_custom_widgets'];
                echo "<p class='yz-no-content yz-no-custom-widgets'>$msg</p></ul>";
                return false;
            }

            foreach ( $yz_custom_widgets as $widget => $data ) :

                // Get Widget Data.
                $icon = $data['icon'];
                $name = $data['name'];
                $content = $data['content'];
                $display_title = $data['display_title'];
                $display_padding = $data['display_padding'];

                // Get Field Name.
                $input_name = "yz_custom_widgets[$widget]";

                ?>

                <!-- Widget Item -->
                <li class="yz-custom-widget-item" data-widget-name="<?php echo $widget;?>">
                    <h2 class="yz-custom-widget-name">
                        <i class="yz-custom-widget-icon <?php echo $icon; ?>"></i>
                        <span><?php echo $name; ?></span>
                    </h2>
                    <input type="hidden" name="<?php echo $input_name; ?>[icon]" value="<?php echo $icon; ?>">
                    <input type="hidden" name="<?php echo $input_name; ?>[display_title]" value="<?php echo $display_title; ?>">
                    <input type="hidden" name="<?php echo $input_name; ?>[display_padding]" value="<?php echo $display_padding; ?>">
                    <input type="hidden" name="<?php echo $input_name; ?>[name]" value="<?php echo $name; ?>">
                    <input type="hidden" name="<?php echo $input_name; ?>[content]" value="<?php echo $content; ?>">
                    <a class="yz-edit-item yz-edit-custom-widget"></a>
                    <a class="yz-delete-item yz-delete-custom-widget"></a>
                </li>

            <?php endforeach; ?>

        </ul>

        <?php
    }

    /**
     * Check if key is exist.
     */
    function is_key_exist( $array, $key ) {
        $is_exist = false;
        foreach ( $array as $item ) {
            $is_exist = array_key_exists( $key, $item );
            if ( $is_exist ) {
                break;
            }
        }
        return $is_exist;
    }

    /**
     * Get Exist ADS widgets
     */
    function get_custom_widgets( $widgets ) {

        // Set Up new array
        $custom_widgets = array();

        foreach ( $widgets as $widget => $data ) {
            // If key contains 'yz_custom_widget_'.
            if ( false !== strpos( key( $data ), 'yz_custom_widget_' ) ) {
                $custom_widgets[] = $data;
            }
        }

        return $custom_widgets;
    }

    /**
     * Get Custom Widget data.
     */
    function get_all_data( $widget_name ) {
        $widgets = yz_options( 'yz_custom_widgets' );
        return $widgets[ $widget_name ];
    }

    /**
     * Get Custom Widget data.
     */
    function get_args( $widget_name ) {

        // Get Custom Widgets
        $custom_widget_data = $this->get_all_data( $widget_name );

        // Get Custom Widget Args.
        $args = array(
            'widget_name'       => 'custom_widgets',
            'function_options'  => $widget_name,
            'main_data'         => 'yz_custom_widgets',
            'widget_title'      => $custom_widget_data['name'],
            'widget_icon'       => $custom_widget_data['icon'],
            'display_title'     => $custom_widget_data['display_title'],
            'display_padding'   => $custom_widget_data['display_padding'],
            'load_effect'       => yz_options( 'yz_custom_widgets_load_effect' )
        );

        return $args;
    }

    /**
     * Get Custom Widget data.
     */
    function get_widget_data( $widget_name, $data_type ) {
        $widgets = yz_options( 'yz_custom_widgets' );
        return $widgets[ $widget_name ][ $data_type ];
    }
}