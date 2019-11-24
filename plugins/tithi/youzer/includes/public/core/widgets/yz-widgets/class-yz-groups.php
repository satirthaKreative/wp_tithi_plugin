<?php

class YZ_Groups_Widget {

    /**
     * # Groups Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'widget_icon'   => 'fas fa-users',
            'widget_name'   => 'groups',
            'widget_title'  => yz_options( 'yz_wg_groups_title' ),
            'load_effect'   => yz_options( 'yz_groups_load_effect' ),
            'display_title' => yz_options( 'yz_wg_groups_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_groups_widget_args', $args );

        return $args;
    }
    
    /**
     * # Content.
     */
    function widget() {

        global $Youzer;

        // Get User Max Groups Number to show in the widget.
        $max_groups = yz_options( 'yz_wg_max_groups_items' );
        
        // Get Member Groups Number.
        $groups_nbr = yz_get_group_total_for_member( bp_displayed_user_id() );

        // Get User Data
        $avatar_border_style = yz_options( 'yz_wg_groups_avatar_img_format' );

        // Get Widget Class.
        $list_class = array( 'yz-items-list-widget', 'yz-profile-list-widget', 'yz-profile-groups-widget' );

        // Add Widgets Avatars Border Style Class.
        $list_class[] = 'yz-list-avatar-' . $avatar_border_style;

        $groups_ids = groups_get_user_groups( bp_displayed_user_id() );

        // Limit Groups Number
        $groups_ids = array_slice( $groups_ids['groups'], 0, $max_groups );

        ?>
        
        <div class="<?php echo yz_generate_class( $list_class ); ?>">

            <?php foreach ( $groups_ids as $group_id ) : ?>

            <?php $group = groups_get_group( array( 'group_id' => $group_id ) ); ?>
            <?php $group_url = bp_get_group_permalink( $group ); ?>

            <div class="yz-list-item">

                <a href="<?php echo $group_url; ?>" class="yz-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $group_id, 'object' => 'group' ) ); ?></a>

                <div class="yz-item-data">
                    <a href="<?php echo $group_url; ?>" class="yz-item-name"><?php echo $group->name; ?></a>
                    <div class="yz-item-meta">
                        <div class="yz-meta-item"><?php $Youzer->group->status( $group ); ?></div>
                    </div>
                </div>
            </div>

            <?php endforeach; ?>

            <?php if ( $groups_nbr > $max_groups ) : ?>
                <div class="yz-more-items">
                    <a href="<?php echo bp_core_get_user_domain( bp_displayed_user_id() ) . bp_get_groups_slug();?>"><?php echo sprintf( __( 'Show All Groups ( %s )', 'youzer' ), $groups_nbr ); ?></a>
                </div>
            <?php endif; ?>

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
                'title' => __( 'display title', 'youzer' ),
                'id'    => 'yz_wg_groups_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_groups_title',
                'desc'  => __( 'add widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
                'id'    => 'yz_groups_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'allowed groups number', 'youzer' ),
                'id'    => 'yz_wg_max_groups_items',
                'desc'  => __( 'maximum number of groups to display', 'youzer' ),
                'type'  => 'number'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Groups Avatar border style', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'yz_wg_groups_avatar_img_format',
                'type'  => 'imgSelect',
                'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}