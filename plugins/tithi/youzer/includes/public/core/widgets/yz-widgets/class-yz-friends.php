<?php

class YZ_Friends {

    /**
     * # Friends Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'widget_name'   => 'friends',
            'widget_icon'   => 'fas fa-handshake',
            'widget_title'  => yz_options( 'yz_wg_friends_title' ),
            'load_effect'   => yz_options( 'yz_friends_load_effect' ),
            'display_title' => yz_options( 'yz_wg_friends_display_title' )
        );

        // Filter
        $args = apply_filters( 'yz_friends_widget_args', $args );

        return $args;
    }
    
    /**
     * # Content.
     */
    function widget() {

        // Get Widget Layout.
        $widget_layout = yz_options( 'yz_wg_friends_layout' );

        // Get User Max Friends Number to show in the widget.
        $max_friends = yz_options( 'yz_wg_max_friends_items' );
        
        // Get Member Friends Number
        $friends_nbr = friends_get_total_friend_count( bp_displayed_user_id() );

        // Get User Data
        $avatar_border_style = yz_options( 'yz_wg_friends_avatar_img_format' );
        
        // Get User Friends List.
        $user_friends = friends_get_friend_user_ids( bp_displayed_user_id() );

        // Limit Friends Number
        $friend_ids = array_slice( $user_friends, 0, $max_friends );

        // Get Widget Class.
        $list_class = array( 
            'yz-profile-friends-widget',
            'yz-items-' . $widget_layout . '-widget',
            'yz-profile-' . $widget_layout . '-widget',
            'yz-list-avatar-' . $avatar_border_style
        );

        ?>

        <div class="<?php echo yz_generate_class( $list_class ); ?>">

        <div class="yz-list-inner">
            
            <?php foreach ( $friend_ids as $friend_id ) : ?>
                
            <div <?php if ( 'avatars' == $widget_layout ) echo 'data-yztooltip="' . bp_core_get_user_displayname( $friend_id )  . '"'; ?> class="yz-list-item">

                <a href="<?php echo bp_core_get_user_domain( $friend_id ); ?>" class="yz-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $friend_id, 'type' => 'full', 'width' => '60px', 'height' => '60px' ) ); ?></a>

                <?php if ( 'list' == $widget_layout ) : ?>

                    <div class="yz-item-data">
                        <a href="<?php echo bp_core_get_user_domain( $friend_id ); ?>" class="yz-item-name"><?php echo bp_core_get_user_displayname( $friend_id ); ?><?php yz_the_user_verification_icon( $friend_id ); ?></a>
                        <div class="yz-item-meta">
                            <div class="yz-meta-item">@<?php echo bp_core_get_username( $friend_id ); ?></div>
                        </div>
                    </div>

                <?php endif; ?>
                
            </div>

            <?php endforeach; ?>

            <?php if ( $friends_nbr > $max_friends ) : ?>
                <?php $more_nbr = $friends_nbr - $max_friends; ?>
                <?php $more_title = ( 'list' == $widget_layout ) ? sprintf( __( 'Show All Friends ( %s )', 'youzer' ), $friends_nbr ) : '+' . $more_nbr; ?>
                <div class="yz-more-items" <?php if ( 'avatars' == $widget_layout ) echo 'data-yztooltip="' . __( 'Show All Friends', 'youzer' )  . '"'; ?>>
                    <a href="<?php echo bp_core_get_user_domain( bp_displayed_user_id() ) . bp_get_friends_slug();?>"><?php echo $more_title; ?></a>
                </div>
            <?php endif; ?>

        </div>
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
                'id'    => 'yz_wg_friends_display_title',
                'desc'  => __( 'show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'widget title', 'youzer' ),
                'id'    => 'yz_wg_friends_title',
                'desc'  => __( 'add widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Widget Layout', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'friends_layout' ),
                'desc'  => __( 'select widget layout', 'youzer' ),
                'id'    => 'yz_wg_friends_layout',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'how you want the widget to be loaded ?', 'youzer' ),
                'id'    => 'yz_friends_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'allowed friends number', 'youzer' ),
                'id'    => 'yz_wg_max_friends_items',
                'desc'  => __( 'maximum number of friends to display', 'youzer' ),
                'type'  => 'number'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Friends Avatar border style', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'yz_wg_friends_avatar_img_format',
                'type'  => 'imgSelect',
                'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}