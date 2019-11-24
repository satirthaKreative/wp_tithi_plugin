<?php

class Youzer_User {

	/**
	 * # Cover.
	 */
	function cover( $query = null, $user_id = null ) {

		// Get User ID.
		$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		// Get Cover Photo Path.
	    $cover_path = bp_attachments_get_attachment( 'url', array(
	        'object_dir' => 'members', 'item_id' => $user_id
	        )
	    );

	    // Get Default Cover.
		if ( empty( $cover_path ) ) {
	    	$cover_path = yz_options( 'yz_default_profiles_cover' );
		}
	    
		// Get 404 Profile Picture
		$cover_404 = yz_options( 'yz_profile_404_cover' );
		if ( yz_is_404_profile() && ! empty( $cover_404 ) && $user_id == 0 ) {
			$cover_path = $cover_404;
		}

		// Get Cover Style.
		$cover_style = 'background-size: cover;';

		// If Cover not exist use .
		if ( empty( $cover_path ) ) {

			// Get Data.
			$default_avatar = bp_core_avatar_default();
			$photo_as_cover = yz_options( 'yz_header_use_photo_as_cover' );
			$profile_layout = yz_get_profile_layout();
			$profile_photo 	= bp_core_fetch_avatar( 
				array(
					'item_id' => $user_id,
					'type'	  => 'full',
					'html' 	  => false,
				)
			);

			// The profile photo as cover ( works only with Vertical Layouts ).
			if ( 'on' == $photo_as_cover && bp_get_user_has_avatar( $user_id ) && 'yz-vertical-layout' == $profile_layout ) {
				$cover_path = $profile_photo;
			} else {
				// If cover photo not exist use pattern.
				$cover_path = YZ_PA . 'images/geopattern.png';				
				// Get Cover Style.
				$cover_style = 'background-size: auto;';

			}

		}

		if ( 'url' == $query ) {
			return $cover_path;
		}

		// Get Cover
		$cover = "style='background-image:url( $cover_path ); $cover_style'";

		// return Cover Style
		if ( 'style' == $query ) {
			return $cover;
		}

		// Print Cover.
		echo $cover;
	}

	/**
	 * Photo
	 */
	function photo( $args = null ) {

		// Set Up Variable.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : 0;
		$target  = isset( $args['target'] ) ? $args['target'] : 'header';

		// Get Photo Border Style
		$border_style = yz_options( 'yz_' . $target . '_photo_border_style' );
		$show_border  = yz_options( 'yz_enable_' . $target . '_photo_border' );

		// Get Photo Data
		$photo_effect = yz_options( 'yz_profile_photo_effect' );

		$img_path = bp_core_fetch_avatar( 
			array(
				'item_id' => $user_id,
				'type'	  => 'full',
				'html' 	  => false,
			)
		);

		// Set Default avatar if avatar url is empty
		$img_path = ! empty( $img_path ) ? $img_path : bp_core_avatar_default();

		// Get 404 Profile Picture
		$avatar_404 = yz_options( 'yz_profile_404_photo' );
		if ( yz_is_404_profile() && ! empty( $avatar_404 ) ) {
			$img_path = $avatar_404;
		}

		// Prepare Photo Class
		$photo_class 	= array();
		$photo_class[] 	= 'yz-profile-photo';
		$photo_class[] 	= "yz-photo-$border_style";

		if ( 'on' == $show_border ) {
			$photo_class[] = 'yz-photo-border';
		}

		if ( 'on' == $photo_effect && 'circle' == $border_style ) {
			$photo_class[] = 'yz-profile-photo-effect';
		}

		// Generate Photo Class
		$photo_class = yz_generate_class( $photo_class );

		// Get Profile Url
		$profile_url = bp_core_get_user_domain( $user_id );
		
		echo "<div class='$photo_class'>";
		echo "<a href='$profile_url' class='yz-profile-img' style='background-image: url( $img_path );'></a>";
		echo "</div>";
	}

	/**
	 * # Username.
	 */
	function username() {

		// Get Data.
		$last_name 	= yz_data( 'last_name' );
		$first_name = yz_data( 'first_name' );
		$username 	= "$first_name $last_name";

		// Get Username
		if ( ! $first_name && ! $last_name ) {
			$username = yz_data( 'user_login' );
		}

		// Filter Username
		$username = apply_filters( 'yz_user_profile_username', $username );

		echo "<div class='yz-name'>";
		echo "<h2>". $username . "</h2>";
		echo "</div>";

	}

	/**
	 * # Meta.
	 */
	function meta() {

		// Show/Hide Elements
		$display_website  = yz_options( 'yz_display_header_site' );
		$display_location = yz_options( 'yz_display_header_location' );

		if ( 'on' == $display_location || 'on' == $display_website ) :

			echo '<div class="yz-usermeta"><ul>';
				if ( 'on' == $display_location ) {
					$this->location();
				}
				if ( 'on' == $display_website ) {
					$this->website();
				}
			echo '</ul></div>';

		endif;

	}

	/**
	 * # Location.
	 */
	function location( $only_data = false, $user_id = null ) {

		// Get user city & country.
		$user_city    = yz_data( 'user_city', $user_id );
		$user_country = yz_data( 'user_country', $user_id );

		if ( empty( $user_country ) && empty( $user_city ) ) {
			return false;
		}

		// Get Location
		if ( ! empty( $user_country ) &&  empty( $user_city ) ) {
			$user_location = $user_country;
		} elseif (  empty( $user_country ) && ! empty( $user_city ) ) {
			$user_location = $user_city;
		} elseif ( ! empty( $user_country ) && ! empty( $user_city ) ) {
			$user_location = "$user_country, $user_city";
		}

		if ( $only_data ) {
			return sanitize_text_field( $user_location );
		}

		// Get Location HTML.
		$location = '<li><i class="fas fa-map-marker-alt"></i><span>' . sanitize_text_field( $user_location ) . '</span></li>';

		echo apply_filters( 'yz_get_profile_header_meta_user_location', $location );

	}

	/**
	 * Badges
	 */
	function badges( $args = null, $user_id = null ) {
		do_action( 'yz_author_box_badges_content', $args );
	}

	/**
	 * Rating.
	 */
	function ratings( $args = null, $user_id = null ) {
		do_action( 'yz_author_box_ratings_content', $args );
	}
	
	/**
	 * # Address.
	 */
	function website() {

		// Get User Website
		$user_website = yz_esc_url( yz_data( 'user_url' ) );

		if ( empty( $user_website ) ) {
			return false;
		}

		// Get Website HTML.
		$website = '<li><a href="' . esc_url( $user_website ) . '" target="_blank" rel="nofollow noopener"><i class="fas fa-link"></i><span>' . $user_website . '</span></a></li>';

		echo apply_filters( 'yz_get_profile_header_meta_user_website', $website );

	}

	/**
	 * # Social Networks.
	 */
	function networks( $args = null ) {

		// Set Up Variable.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : null;
		$element = isset( $args['target'] ) ? $args['target'] : 'header';

		if ( ! is_user_have_networks( $user_id ) ) {
			return false;
		}

		// Get Social Networks
		$social_networks = yz_options( 'yz_social_networks' );

		// Display Networks Icons
		$display_networks = yz_options( 'yz_display_' . $element . '_networks' );

		// if Element is Widget Make it Networks Visible.
		if ( 'widget' == $element ) {
			$element = 'wg';
			$display_networks = 'on';
		}

		// Check Networks Visibility.
		if ( 'on' != $display_networks || empty( $social_networks ) ) {
			return false;
		}

		// Get networks Data.
		$data = yz_get_args(
			array(
				'networks_type'   => yz_options( 'yz_' . $element . '_sn_bg_type' ),
				'networks_format' => yz_options( 'yz_' . $element . '_sn_bg_style' ),
		), $args );

		// Get Networks Size
		$networks_size = yz_options( 'yz_wg_sn_icons_size' );
		if ( 'wg' == $element ) {
			$networks_class[] = "yz-icons-$networks_size";
		}

		// Prepare Networks Class .
		$networks_class[] = "yz-$element-networks";
		$networks_class[] = "yz-icons-{$data['networks_type']}";
		$networks_class[] = "yz-icons-{$data['networks_format']}";
		$networks_class[] = "yz-networks-$user_id";

		// Networks Action
		do_action( 'youzer_before_networks', $args );

		// Get Networks Type
		$networks_class = yz_generate_class( $networks_class );

		echo "<ul class='$networks_class'>";

		foreach ( $social_networks as $network => $data ) {

			// Get Widget Data
			$icon = apply_filters( 'yz_user_social_networks_icon', $data['icon'] );
			$name = sanitize_text_field( $data['name'] );
			$link = esc_url( yz_data( $network, $user_id ) );

			if ( $link && $icon ) {
				echo "<li class='$network'><a href='$link' target='_blank' rel='nofollow noopener'>";
				echo "<i class='$icon'></i>";
				if ( 'wg' == $element && 'full-width' == $networks_size ) {
					echo $name;
				}
				echo '</a></li>';
			}

		}

		echo '</ul>';
	}

	/**
	 * # Profile Statistics.
	 */
	function statistics( $args = null ) {

		// global $Youzer;
		$statistics_details = yz_get_user_statistics_details();

		// Set Up Variable.
		$target = isset( $args['target'] ) ? $args['target'] : 'header';

		// Get User Statistics.
		if ( yz_is_404_profile() ) {

			// If its 404 Profile Page.
			$views_number 	 = 4;
			$posts_number 	 = 4;
			$comments_number = 0;

			// Display All Elements
			$display_posts 	  = 'on';
			$display_comments = 'on';
			$display_views 	  = 'on';

		} else {

			// Get User ID.
			$user_id = isset( $args['user_id'] ) ? $args['user_id'] : yz_profileUserID();

			// Get Types.
			$first_statistic_type = yz_options( 'yz_' . $target . '_first_statistic' );
			$second_statistic_type = yz_options( 'yz_' . $target . '_second_statistic' );
			$third_statistic_type = yz_options( 'yz_' . $target . '_third_statistic' );
			
			// Show/Hide Elements.
			$display_first_statistic  = yz_options( 'yz_display_' . $target . '_first_statistic' );
			$display_third_statistic  = yz_options( 'yz_display_' . $target . '_third_statistic' );
			$display_second_statistic = yz_options( 'yz_display_' . $target . '_second_statistic' );
		}
		
		if ( 'on' != $display_first_statistic && 'on' != $display_third_statistic && 'on' != $display_second_statistic ) {
			return false;
		}

		// Get Statistics Data.
		$data = yz_get_args(
			array(
				'statistics_bg' 	=> yz_options( 'yz_' . $target . '_use_statistics_bg' ),
				'statistics_border' => yz_options( 'yz_' . $target . '_use_statistics_borders' ),
		), $args );

		// Get Statistics Class Name.
		$statistics_class[] = "yz-user-statistics";
		$statistics_class[] = ( 'on' == $data['statistics_bg'] ) ? 'yz-statistics-bg' : null;
		$statistics_class[] = ( 'on' == $data['statistics_border'] ) ? 'yz-use-borders' : null;

		?>
			<div class="<?php echo yz_generate_class( $statistics_class ); ?>">
				<ul>
					<?php if ( 'on' == $display_first_statistic && isset( $statistics_details[ $first_statistic_type ] ) ) : ?>

						<?php 
							if ( ! isset( $statistics_details[ $first_statistic_type ] ) ) {
								return;
							}

							$first_nbr = yz_get_user_statistic_number( $user_id, $first_statistic_type );
						 ?>

						<li>
							<div class="yz-snumber" title="<?php echo $first_nbr; ?>"><?php echo $this->get_statistic_number( $first_nbr ); ?></div>
							<h3 class="yz-sdescription"><?php echo $statistics_details[ $first_statistic_type ]; ?></h3>
						</li>

					<?php endif; ?>

					<?php if ( 'on' == $display_second_statistic && isset( $statistics_details[ $second_statistic_type ] )) : ?>

						<?php $second_nbr = yz_get_user_statistic_number( $user_id, $second_statistic_type ); ?>

						<li>
							<div class="yz-snumber" title="<?php echo $second_nbr; ?>"><?php echo $this->get_statistic_number( $second_nbr ); ?></div>
							<h3 class="yz-sdescription"><?php echo $statistics_details[ $second_statistic_type ]; ?></h3>
						</li>
					<?php endif; ?>

					<?php if ( 'on' == $display_third_statistic && isset( $statistics_details[ $third_statistic_type ] ) ) : ?>
						
						<?php 
							$third_nbr = yz_get_user_statistic_number( $user_id, $third_statistic_type );
						?>

						<li>
							<div class="yz-snumber" title="<?php echo $third_nbr; ?>"><?php echo $this->get_statistic_number( $third_nbr); ?></div>
							<h3 class="yz-sdescription"><?php echo $statistics_details[ $third_statistic_type ]; ?></h3>
						</li>
					<?php endif; ?>

				</ul>
			</div>
		<?php
	}

	/**
	 * Convert Statistics Number
	 */
	function get_statistic_number( $number ) {

		// if Number equal 0 return it.
		if ( 0 == $number ) {
			return 0;
		}

		// Define Number Letters.
		$abbrevs = array(
			12 	=> __( 'T', 'youzer' ),
			9 	=> __( 'B', 'youzer' ),
			6 	=> __( 'M', 'youzer' ),
			3 	=> __( 'K', 'youzer' ),
			0 	=> ''
		);

		// Get Number Letter
		foreach( $abbrevs as $exponent => $abbrev ) {
			if( $number >= pow( 10, $exponent ) ) {
				$display_num = $number / pow( 10, $exponent );
				$decimals = ( $exponent >= 3 && round( $display_num ) < 100 ) ? 1 : 0;
				$number_format = number_format( $display_num, $decimals );
				return $number_format . $abbrev;
			}
		}

	}

	/**
	 * # Settings Buttons.
	 */
	function buttons( $args = null ) {

		// Set Up Variable.
		$target  = isset( $args['target'] ) ? $args['target'] : 'header';
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : yz_profileUserID();

		// Check if current Box Belong to the logged-in user.
		$is_current_user_widget = ( get_current_user_id() == $user_id ) ? true : false;

		?>

		<div class="yzb-account-menu">

		<?php if ( ! is_user_logged_in() ) : ?>
			<?php if ( bp_is_user() || yz_is_404_profile() ) : ?>
			<a class="yzb-button yzb-login" data-show-youzer-login="true" href="<?php echo yz_get_login_page_url(); ?>">
				<i class="fas fa-user"></i>
				<span class="yzb-button-title"><?php _e( 'login', 'youzer' ); ?></span>
			</a>
			<?php endif; ?>

		<?php elseif ( is_user_logged_in() && $is_current_user_widget ) : ?>

			<?php if ( ! bp_is_user() || ! yz_is_wild_navbar_active() ) :?>
				<?php yz_user_quick_buttons(); ?>
			<?php endif; ?>

		<?php else : ?>
			<?php yz_get_social_buttons( $user_id ); ?>
		<?php endif; ?>

		</div>

		<?php
	}

	/**
	 * # Settings.
	 */
	function settings( $user_id = null ) {

	    // Get User ID.
	    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		// New Array
		$links = array();

		// Profile Settings
		$links['profile'] = array(
			'icon'	=> 'fas fa-user',
			'href'	=> yz_get_profile_settings_url( false, $user_id ),
			'title'	=> __( 'profile Settings', 'youzer' )
		);

    	if ( bp_is_active( 'settings' ) ) {
			// Account Settings
			$links['account'] = array(
				'icon'	=> 'fas fa-cogs',
				'href'	=> yz_get_settings_url( false, $user_id ),
				'title'	=> __( 'Account Settings', 'youzer' )
			);
    	}

		// Widgets Settings
		$links['widgets'] = array(
			'icon'	=> 'fas fa-sliders-h',
			'href'	=> yz_get_widgets_settings_url( false, $user_id ),
			'title'	=> __( 'Widgets Settings', 'youzer' )
		);

		// Change Photo Link
		$links['change-photo'] = array(
			'icon'	=> 'fas fa-camera-retro',
			'href'	=> yz_get_profile_settings_url( 'change-avatar', $user_id ),
			'title'	=> __( 'change avatar', 'youzer' )
		);

		// Change Password Link
		$links['change-password'] = array(
			'icon'	=> 'fas fa-lock',
			'href'	=> yz_get_settings_url( 'change-password', $user_id ),
			'title'	=> __( 'change password', 'youzer' )
		);

		// Logout Link
		$links['logout'] = array(
			'icon'	=> 'fas fa-power-off',
			'href'	=> wp_logout_url(),
			'title'	=> __( 'logout', 'youzer' )
		);

		// Filter.
		$links = apply_filters( 'yz_get_profile_account_menu', $links, $user_id );

		?>

		<div class="yz-settings-menu">
			<?php foreach ( $links as $link ) : ?>
				<a href="<?php echo esc_url( $link['href'] ); ?>">
					<div class="yz-icon"><i class="<?php echo $link['icon'];?>"></i></div>
					<span class="yzb-button-title"><?php echo $link['title']; ?></span>
				</a>
			<?php endforeach; ?>
		</div>

		<?php
	}

	/**
	 * # Author Box Head.
	 */
	function box_head( $target, $user_id = null ) {

		// Get Data
		$last_name 	= yz_data( 'last_name', $user_id );
		$first_name	= yz_data( 'first_name', $user_id );
		$username 	= sanitize_user( yz_data( 'user_login', $user_id ) );
		$full_name 	= sanitize_text_field( "$first_name $last_name" );
		$profile_url = bp_core_get_user_domain( $user_id );

		?>

		<div class="yzb-head-content">
			<div class="yzb-user-status"><?php echo yz_add_user_online_status_icon( null, $user_id ); ?></div>
			<a href="<?php echo $profile_url; ?>" class="yzb-head-username"><?php echo $full_name; ?><?php yz_the_user_verification_icon( $user_id, 'medium' ); ?></a>
			<?php $this->box_meta( $target, $user_id ); ?>
		</div>

		<?php

	}

	/**
	 * # Author Box Meta.
	 */
	function box_meta( $args = null ) {

		// Set Up Variables.
		$meta 	 = null;
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_displayed_user_id();

	    // Get Custom Meta Data
		$field_id  = $args['meta_type'];
	    $meta_icon = isset( $args['meta_icon'] ) ? $args['meta_icon'] : 'fas fa-globe';

	    $meta_value = yz_get_user_field_data( $field_id, $user_id );

	    if ( empty( $meta_value ) ) {
	        // Set Default Meta.
	        $meta_html = '@ ' . bp_core_get_username( $user_id );
	    } else {
	        // Create Custom Meta HTML Code.
	        $meta_html = '<i class="' . $meta_icon .'"></i>' . $meta_value;
	    }

	    // Filter
	    $meta_html = apply_filters( 'yz_get_header_meta_html', $meta_html, $meta_icon, $field_id, $meta_value );

		?>

		<span class="yzb-head-meta yzb-meta-<?php echo $field_id; ?>">
			<?php echo $meta_html; ?>
		</span>

		<?php

	}

	/**
	 * # Profile Views Number.
	 */
	function views( $user_id = null ) {
		$user_id = ! empty( $user_id ) ? $user_id : yz_profileUserID();
		$this->set_profile_views( $user_id );
		return $this->get_profile_views( $user_id );
	}

	/**
	 * # Get Profile Views Number.
	 */
	function get_profile_views( $profile_ID ) {

		// Set Up Variables.
		$count_key = 'profile_views_count';
		$count = get_post_meta( $profile_ID, $count_key, true );

		// Get Views Number
		if ( $count == '' ) {
			delete_post_meta( $profile_ID, $count_key );
			add_post_meta( $profile_ID, $count_key, '0' );
			return '0';
		}

		return $count;
	}

	/**
	 * # Set Profile Views Number .
	 */
	function set_profile_views( $profile_ID ) {

	 	// The user's IP address
	    $user_ip = $_SERVER['REMOTE_ADDR'];

	    $ip_key = 'profile_views_ip'; // The IP Address post meta key
	    $views_key = 'profile_views_count'; // The views post meta key

	    // The current post views count
	    $count = get_post_meta( $profile_ID, $views_key, true ); 

		if ( '' == $count ) {
			$count = 0;
			delete_post_meta( $profile_ID, $views_key );
			add_post_meta( $profile_ID, $views_key, '0' );
		} elseif ( is_page() ) {

			// Array of IP addresses that have already visited the post.
			if ( '' != get_post_meta( $profile_ID, $ip_key, true ) ) {
			    $ip = json_decode( get_post_meta( $profile_ID, $ip_key, true ), true );
			} else {
			    $ip = array(); 
			}

			// The following checks if the user's IP already exists
			for ( $i = 0; $i < count( $ip ); $i++ ) {
			    if ( $ip[ $i ] == $user_ip ) {
			        return false;
			    }
			}

			// Update and encode the $ip array into a JSON string
			$ip[ count( $ip ) ] = $user_ip;
			$json_ip = json_encode( $ip );

			// Update the post's metadata 
			$count++;
			update_post_meta( $profile_ID, $views_key, $count ); // Update the count
			update_post_meta( $profile_ID, $ip_key, $json_ip ); // Update the user IP JSON obect

		}
	}
}