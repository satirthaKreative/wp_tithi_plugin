<?php

class Yz_Profile_Structure {

    /**
     * # Admin Settings.
     */
	function settings() {

	    global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'msg_type' => 'info',
                'type'     => 'msgBox',
                'title'    => __( 'info', 'youzer' ),
                'id'       => 'yz_msgbox_profile_structure',
                'msg'      => __( 'You have to know that theses widgets <strong>( Email, Website, Address, Phone Number, Recent Posts , Keep In Touch )</strong> can\'t be moved to the <strong>"Main Widgets"</strong> column.', 'youzer' )
            )
        );

	    // Profile Widgets
		echo '<div class="yz-profile-structure yz-cs-content">';

		// Get Main Widegts
		$main_widgets = $this->main_widgets_area();

		// Get Sidebar Widegts
		$this->sidebar_widgets_area( $main_widgets );

		echo '<input type="hidden" name="yz_profile_stucture" value="true">';
		echo '</div>';
	}

	/**
	 * # Main Widgets Area.
	 */
	function main_widgets_area() {

		global $Yz_Translation;
		
		$Yz_Widgets = youzer()->widgets;
		
		// Create New Empty List
		$main_widgets_list = array();

		// Get Current Main Widgets
		$profile_main_widgets = yz_options( 'yz_profile_main_widgets' );

		?>

		<div class="yz-profile-wg yz-main-wgs">
			<div class="yz-wgs-inner-content">
				<h2 class="yz-profile-wg-title"><?php _e( 'Main Widgets', 'youzer' ); ?></h2>
				<ul id="yz-profile-main-widgets" data-widgets-type="main_widgets">

				<?php

				foreach ( $profile_main_widgets as $widget ) {

					// Get Widget Name
					$widget_name = key( $widget );

					// Get Widget Data
					if ( false !== strpos( $widget_name, 'yz_ad_' ) ) {
						$widget_icon  = 'bullhorn';
						$ad_flag = sprintf( '<span class="yz-ad-flag">%s</span>', __( 'ad', 'youzer' ) );
						$widget_title = $Yz_Widgets->ad->get_ads_data( $widget_name, 'title' ) . $ad_flag ;
					} elseif ( false !== strpos( $widget_name, 'yz_custom_widget_' ) ) {
						$widget_args  = $Yz_Widgets->custom_widgets->get_all_data( $widget_name );
						$widget_title = $widget_args['name'];
						$widget_icon  = $widget_args['icon'];
					} else {
						$widget_args  = $Yz_Widgets->$widget_name->args();
						$widget_title = $widget_args['widget_title'];
						$widget_icon  = $widget_args['widget_icon'];
					}

					$widget_status = $widget[ $widget_name ];
					$widget_class  = ( 'invisible' == $widget_status ) ? 'yz-hidden-wg' : null;
					$icon_title    = ( 'visible' == $widget_status ) ? $Yz_Translation['hide_wg'] : $Yz_Translation['show_wg'];

					// Get Template Args
					$template_args = array(
						'icon_title'	=> $icon_title,
						'widget_name'	=> $widget_name,
						'widget_icon'	=> $widget_icon,
						'widget_title'	=> $widget_title,
						'widget_class'	=> $widget_class,
						'widget_status'	=> $widget_status,
						'input_name'	=> "yz_profile_main_widgets[][$widget_name]",
					);

					// Print Widget
					$this->template( $template_args );

					// Fill "$main_widgets_list" Variable with the current list of widgets
					array_push( $main_widgets_list, $widget_name );

				}

				?>

				</ul>
			</div>
		</div>

		<?php

		return  $main_widgets_list;

	}

	/**
	 * # Sidebar Widgets Area.
	 */
	function sidebar_widgets_area( $main_widgets_list ) {

		global $Yz_Translation;
		
		$Yz_Widgets = youzer()->widgets;
		
		// Get Current Sidebar Widgets
		$profile_sidebar_widgets = yz_options( 'yz_profile_sidebar_widgets' );

		// List of Unsortable Widgets
		$unsortable_widgets = array(
			'recent_posts', 'social_networks', 'address', 'email', 'phone', 'website', 'friends_suggestions'
		);

		?>

		<div class="yz-profile-wg yz-sidebar-wgs">
			<h2 class="yz-profile-wg-title"><?php _e( 'Sidebar Widgets', 'youzer' ); ?></h2>
			<ul id="yz-profile-sidebar-widgets" data-widgets-type="sidebar_widgets">

			<?php

			foreach ( $profile_sidebar_widgets as $widget ) {

				// Get Widget Name.
				$widget_name = key( $widget );

				// if Widget Not already exist in the main area.
				if ( in_array( $widget_name, $main_widgets_list ) ) {
					continue;
				}

				// Get Widget Status
				$widget_status = $widget[ $widget_name ];

				// If widget is an info box -> change the widget name to it.
			 	$info_boxes = array( 'address', 'phone', 'email', 'website' );

			 	if ( in_array( $widget_name, $info_boxes ) ) {
			 		// Widget Icon
					$widget_args  = $Yz_Widgets->info_box->$widget_name();
					$widget_icon  = $widget_args['box_icon'];
					$widget_title = $widget_args['box_class'];
			 	} else {
					 if ( $widget_name == 'friends_suggestions' ) {
						$widget_args  = $Yz_Widgets->friends_suggestions;
						$widget_title = $widget_args['widget_title'];
						$widget_icon  = $widget_args['widget_icon'];
					} elseif ( false !== strpos( $widget_name, 'yz_ad_' ) ) {
						$widget_icon  = 'bullhorn';
						$ad_flag = sprintf( '<span class="yz-ad-flag">%s</span>', __( 'ad', 'youzer' ) );
						$widget_title = $Yz_Widgets->ad->get_ads_data( $widget_name, 'title' ) . $ad_flag ;
					} elseif ( false !== strpos( $widget_name, 'yz_custom_widget_' ) ) {
						$widget_args  = $Yz_Widgets->custom_widgets->get_all_data( $widget_name );
						$widget_title = $widget_args['name'];
						$widget_icon  = $widget_args['icon'];
					} else {
						$widget_args  = $Yz_Widgets->$widget_name->args();
						$widget_title = $widget_args['widget_title'];
						$widget_icon  = $widget_args['widget_icon'];
					}
			 	}

				$widget_class  = $widget_status == 'invisible' ? 'yz-hidden-wg' : null;
				$widget_class .= in_array( $widget_name, $unsortable_widgets ) ? ' yz_unsortable' : null;
				$icon_title    = $widget_status == 'visible' ? $Yz_Translation['hide_wg'] : $Yz_Translation['show_wg'];

				// Get Template Args
				$template_args = array(
					'icon_title'	=> $icon_title,
					'widget_name'	=> $widget_name,
					'widget_icon'	=> $widget_icon,
					'widget_title'	=> $widget_title,
					'widget_class'	=> $widget_class,
					'widget_status'	=> $widget_status,
					'input_name'	=> "yz_profile_sidebar_widgets[][$widget_name]",
				);

				// Print Widget
				$this->template( $template_args );

			}

			?>

			</ul>
		</div>

		<?php

	}

	/**
	 * Get Widget Status.
	 */
	function yz_get_widgets_status( $widget_list ) {
		$widgets = array();
		foreach ( $widget_list as $key => $value ) {
			$widgets[] = array( $value => 'visible' );
		}
		return $widgets;
	}

	/**
	 * Profile Structure Template.
	 */
	function template( $args ) {

		?>

		<li class="<?php echo $args['widget_class']; ?>" data-widget-name="<?php echo $args['widget_name']; ?>">
			<h3 data-hidden="<?php _e( 'hidden', 'youzer' ); ?>">
				<i class="<?php echo $args['widget_icon']; ?>"></i>
				<?php echo $args['widget_title']; ?>
			</h3>
			<a class="yz-hide-wg" title="<?php echo $args['icon_title']; ?>"></a>
			<input class="yz_profile_widget" type="hidden" name="<?php echo $args['input_name']; ?>" value="<?php echo $args['widget_status']; ?>">
		</li>

		<?php
	}

}