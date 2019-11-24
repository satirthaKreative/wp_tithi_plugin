<?php

/**
 * Group Notifications Widget
 */

class YZ_Notifications_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_notifications_widget',
			__( 'Youzer - Notifications', 'youzer' ),
			array( 'description' => __( 'User Notifications Widget.', 'youzer' ) )
		);
	}
	
	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

		global $Youzer;

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance,
		    array(
		    	'title' => __( 'Notifications', 'youzer' ),
		        'icons_border_style' => 'circle',
		        'icons_style' => 'colorful',
		        'limit' => '5'
		    )
	     );

	    // Get Input's Data.
		$limit = absint( $instance['limit'] );
		$title = strip_tags( $instance['title'] );
		$border_style = $Youzer->fields->get_field_options( 'border_styles' );
		$icons_style = array(
			'silver'	=> __( 'Silver', 'youzer' ),
			'colorful'	=> __( 'Colorful', 'youzer' ),
			'no-bg'		=> __( 'No background', 'youzer' )
		);

		?>

		<!-- Title. -->   
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'youzer' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<!-- Notifications Number. -->   
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Notifications Limit:', 'bp-group-suggest' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" style="width: 30%" />
			</label>
		</p>

		<!-- Icons Background Style -->
	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icons_style' ) ); ?>"><?php esc_attr_e( 'Icons Background Style:', 'youzer' ); ?></label> 
	        <select id="<?php echo $this->get_field_id( 'icons_style' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icons_style' ) ); ?>" class="widefat" style="width'100%;">
	            <?php foreach( $icons_style as $style_id => $style_name ) { ?>
	            	<option <?php selected( $instance['icons_style'], $style_id ); ?> value="<?php echo $style_id; ?>"><?php echo $style_name; ?></option>
	            <?php } ?>      
	        </select>
	    </p>

		<!-- Icons Border Style -->
	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icons_border_style' ) ); ?>"><?php esc_attr_e( 'Icons Border Style:', 'youzer' ); ?></label> 
	        <select id="<?php echo $this->get_field_id( 'icons_border_style' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icons_border_style' ) ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( $border_style as $style_id => $style_name ) { ?>
	            	<option <?php selected( $instance['icons_border_style'], $style_id ); ?> value="<?php echo $style_id; ?>"><?php echo $style_name; ?></option>
	            <?php } ?>      
	        </select>
	    </p>
		
		<?php 
	}
	
	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance = $old_instance;
		$instance['limit'] = absint( $new_instance['limit'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['icons_style'] = strip_tags( $new_instance['icons_style'] );
		$instance['icons_border_style'] = strip_tags( $new_instance['icons_border_style'] );

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		// Hide Widget if notifications not enabled and for Not Logged-In users.
		if ( ! bp_is_active( 'notifications' ) || ! is_user_logged_in() ) {
			return false;
		}

		// Get Notifications
		$notifications_nbr = bp_notifications_get_unread_notification_count();

		if ( $notifications_nbr <= 0  ) {
			return false;
		}
		
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'] );
			echo $args['after_title'];
		}

		$this->get_notifications( $instance );

		echo $args['after_widget'];

	}

	/**
	 * Get User Notifications.
	 */
	function get_notifications( $args ) {

		// Get All User Notifications.
		$notifications = bp_notifications_get_notifications_for_user( get_current_user_id(), 'object' );

		// Get Notifications Count.
		$notifications_nbr = count( $notifications );

		// Limit Notifications Number
		$notifications = array_slice( $notifications, 0, $args['limit'] );
		
		?>

		<div class="yz-notifications-widget yz-notif-icons-<?php echo $args['icons_border_style']; ?> yz-notif-icons-<?php echo $args['icons_style']; ?>">
		
		<?php foreach ( $notifications as $notification ) : ?>

		<div class="yz-notif-item yz-notif-<?php echo $notification->component_action; ?>">
			<a href="<?php echo $notification->href; ?>" class="yz-notif-icon"><?php echo $this->get_notification_icon( $notification ); ?></a>
			<div class="yz-notif-content">
				<a href="<?php echo $notification->href; ?>" class="yz-notif-desc"><?php echo $notification->content; ?></a>
				<span class="yz-notif-time"><i class="far fa-clock"></i><?php echo bp_core_time_since( $notification->date_notified ); ?></span>
			</div>
		</div>
							
		<?php endforeach; ?>
	
        <?php if ( $notifications_nbr > $args['limit'] ) : ?>
            <div class="yz-more-items">
                <a href="<?php echo bp_nav_menu_get_item_url( 'notifications' ); ?>"><i class="far fa-bell"></i><?php echo sprintf( __( 'Show All Notifications ( %s )', 'youzer' ), $notifications_nbr ); ?></a>
            </div>
        <?php endif; ?>

		</div>

		<?php
	}
	
	/**
	 * Get Notification Icon.
	 */
	function get_notification_icon( $args ) {

		// Get Notification Type
		$notification_action = $args->component_action;

		switch ( $notification_action ) {

			case 'new_at_mention':
				$icon = '<i class="fas fa-at"></i>';
				break;
				
			case 'membership_request_accepted':
				$icon = '<i class="fas fa-thumbs-up"></i>';
				break;

			case 'membership_request_rejected':
				$icon = '<i class="fas fa-thumbs-down"></i>';
				break;

			case 'member_promoted_to_admin':
				$icon = '<i class="fas fa-user-secret"></i>';
				break;

			case 'member_promoted_to_mod':
				$icon = '<i class="fas fa-shield-alt"></i>';
				break;

			case 'bbp_new_reply':
			$icon = '<i class="fas fa-reply"></i>';
				break;				
			case 'update_reply':
				$icon = '<i class="fas fa-reply-all"></i>';
				break;

			case 'new_message':
				$icon = '<i class="fas fa-envelope"></i>';
				break;

			case 'friendship_request':
				$icon = '<i class="fas fa-handshake"></i>';
				break;

			case 'friendship_accepted':
				$icon = '<i class="fas fa-hand-peace"></i>';
				break;

			case 'new_membership_request':
				$icon = '<i class="fas fa-sign-in-alt"></i>';
				break;

			case 'group_invite':
				$icon = '<i class="fas fa-user-plus"></i>';
				break;

			case 'new_follow':
				$icon = '<i class="fas fa-share-alt"></i>';
				break;

			default:
				$icon = '<i class="fas fa-bell"></i>';
				break;
		}

		return $icon;
	}

}