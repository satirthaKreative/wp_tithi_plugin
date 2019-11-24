<?php

class Youzer_Group {

	function __construct() {

		// Group Navbar Content
		add_action( 'youzer_group_navbar', array( &$this, 'navbar' ) );

		// Group Main Content
		add_action( 'yz_group_main_content', array( &$this, 'main_content' ) );

		// Group Body
		add_action( 'yz_group_main_column', array( &$this, 'body' ) );

		// Load Groups Scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ) );


	}

	/**
	 * # Get Profile Layout
	 */
	function layout() {

	    // Set Up Variable
	    $group_layout = yz_options( 'yz_group_header_layout' );

	    if ( false !== strpos( $group_layout, 'yz-card' ) ) {
	        $layout = 'yz-vertical-layout';
	    } else {
	        $layout = 'yz-horizontal-layout';
	    }

	    return $layout;
	}

	/**
	 * # Navbar Menu.
	 */
	function navbar() { ?>

		<nav id="yz-profile-navmenu" class="<?php echo $this->get_navbar_class(); ?>">
			<div class="yz-inner-content">
				<div class="yz-open-nav">
					<button class="yz-responsive-menu"><span>toggle menu</span></button>
				</div>
				<ul class="item-list-tabs no-ajax yz-profile-navmenu" id="object-nav" aria-label="Group primary navigation" role="navigation">

					<?php bp_get_options_nav(); ?>

					<?php

					/**
					 * Fires after the display of group options navigation.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_group_options_nav' ); ?>

				</ul>

				<div id="yz-group-buttons"><?php

					/**
					 * Fires in the group header actions section.
					 *
					 * @since 1.2.6
					 */
					do_action( 'bp_group_header_actions' ); ?>
					
				</div><!-- #item-buttons -->

			</div>
		
		</nav>

		<?php
	}

	/**
	 * # Navbar Class.
	 */
	function get_navbar_class() {

		// Create Empty Array.
		$navbar_class = array();

		// Main Class.
		$navbar_class[] = 'yz-group-navmenu';

		// Get Options.
		$header_layout = yz_options( 'yz_group_header_layout' );

		// Add a class depending on another one.
		if ( 'hdr-v2' == $header_layout || 'hdr-v7' == $header_layout ) {
			$navbar_class[] = "yz-boxed-navbar";
		}

	 	// Return Class Name.
		return yz_generate_class( $navbar_class );
	}

	/**
	 * # Group Main Content.
	 */
	function main_content() {

		?>

		<div class="yz-main-column">
			<div class="yz-column-content">
				<?php do_action( 'yz_group_main_column' ); ?>
			</div>
		</div>

		<?php if ( yz_show_group_sidebar() ) : ?>
		<div class="yz-sidebar-column yz-group-sidebar youzer-sidebar">
			<div class="yz-column-content">
				<?php do_action( 'yz_group_sidebar' ); ?>
			</div>
		</div>
		<?php endif; ?>

		<!-- Scroll to top -->
		<?php if ( 'on' == yz_options( 'yz_display_group_scrolltotop' ) ) : ?>
			<?php yz_scroll_to_top(); ?>
		<?php endif; ?>

		<?php do_action( 'yz_group_content' ); ?>

		<?php
	}

	/**
	 * Body
	 */
	function body() { ?>

		<div id="yz-group-body">

			<?php

			/**
			 * Fires before the display of the group home body.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_group_body' );

			/**
			 * Does this next bit look familiar? If not, go check out WordPress's
			 * /wp-includes/template-loader.php file.
			 *
			 * @todo A real template hierarchy? Gasp!
			 */

				// Looking at home location
				if ( bp_is_group_home() ) :

					if ( bp_group_is_visible() ) {

						// Load appropriate front template
						bp_groups_front_template_part();

					} else {

						/**
						 * Fires before the display of the group status message.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_before_group_status_message' ); ?>

						<div id="message" class="info">
							<p><?php bp_group_status_message(); ?></p>
						</div>

						<?php

						/**
						 * Fires after the display of the group status message.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_after_group_status_message' );

					}

				// Not looking at home
				else :

					// Group Admin
					if ( bp_is_group_admin_page() ) :
						bp_get_template_part( 'groups/single/admin' );

					// Group Activity
					elseif ( bp_is_group_activity()   ) :
						bp_get_template_part( 'groups/single/activity' );

					// Group Members
					elseif ( bp_is_group_members()    ) :
						bp_groups_members_template_part();

					// Group Invitations
					elseif ( bp_is_group_invites()    ) :
						bp_get_template_part( 'groups/single/send-invites' );

					// Membership request
					elseif ( bp_is_group_membership_request() ) :
						bp_get_template_part( 'groups/single/request-membership' );

					// Anything else (plugins mostly)
					else :
						bp_get_template_part( 'groups/single/plugins'      );

					endif;

				endif;

			/**
			 * Fires after the display of the group home body.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_after_group_body' ); ?>

		</div><!-- #yz-group-body -->
		
		<?php

	}

	/**
	 * # Cover.
	 */
	function cover( $query = null, $group_id = null ) {

		$group_id = ! empty( $group_id ) ? $group_id : bp_get_group_id();

		// Get Cover Photo Path.
		$cover_path = bp_attachments_get_attachment(
			'url',
			array(
	          'item_id' 	=> $group_id,
	          'object_dir'  => 'groups',
	        )
		);

	    // Get Default Cover.
		if ( empty( $cover_path ) ) {
	    	$cover_path = yz_options( 'yz_default_groups_cover' );
		}
		
		// Get Cover Style.
		$cover_style = 'background-size: cover;';

		// If Cover not exist use .
		if ( empty( $cover_path ) ) {

			// Get Data.
			$avatar = bp_core_fetch_avatar( array(
				'avatar_dir' => 'group-avatars',
				'item_id'    => $group_id,
				'object' 	 => 'group',
				'type'	  	 => 'full',
				'html' 	  	 => false
				)
			);

			$avatar_as_cover = yz_options( 'yz_group_header_use_avatar_as_cover' );

			// The group avatar as cover ( works only with Vertical Layouts ).
			if ( 'on' == $avatar_as_cover && ! empty( $avatar ) && 'yz-vertical-layout' == $this->layout() ) {
				$cover_path = $avatar;
			} else {
				// If cover image not exist use pattern.
				$cover_path = YZ_PA . 'images/geopattern.png';				
				// Get Cover Style.
				$cover_style = 'background-size: auto;';
			}

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
		$target = isset( $args['target'] ) ? $args['target'] : 'header';
		$group_id = isset( $args['group_id'] ) ? $args['group_id'] : bp_get_group_id();

		// Get Avatar Border Style
		$border_style = yz_options( 'yz_group_' . $target . '_avatar_border_style' );
		$show_border  = yz_options( 'yz_enable_group_' . $target . '_avatar_border' );

		// Get Data
		$photo_effect = yz_options( 'yz_group_avatar_effect' );
		
		$img_path = bp_core_fetch_avatar( array(
			'avatar_dir' => 'group-avatars',
			'object'	 => 'group',
			'item_id'    => $group_id,
			'type'	  	 => 'full',
			'html' 	  	 => false
			)
		);

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

		echo "<div class='$photo_class'>";
		echo "<div class='yz-profile-img' style='background-image: url( $img_path );'></div>";
		echo "</div>";
	}

	/**
	 * # Name.
	 */
	function name() {
		echo "<div class='yz-name'>";
		echo "<h2>". sanitize_text_field( bp_get_current_group_name() ) . "</h2>";
		echo "</div>";
	}

	/**
	 * # Meta.
	 */
	function meta() {

		// Show / Hide Elements
		$display_privacy  = yz_options( 'yz_display_group_header_privacy' );
		$display_activity = yz_options( 'yz_display_group_header_activity' );

		if ( 'on' == $display_privacy || 'on' == $display_activity ) :

			echo '<div class="yz-usermeta"><ul>';
				if ( 'on' == $display_privacy ) {
					echo '<li>';
					$this->status();
					echo '</li>';
				}
				if ( 'on' == $display_activity ) {
					echo '<li>';
					echo '<i class="far fa-clock"></i>';
					echo "<span>" . bp_get_group_last_active() ."</span>";
					echo '</li>';
				}
			echo '</ul></div>';

		endif;

	}

	/**
	 * # Group Status.
	 */
	function status( $group = null ) {

		// Get Group Type.
		$group_type = bp_get_group_status( $group );

		// Get Group Status Data
		if ( 'public' == $group_type ) {
			$icon = 'fas fa-globe-asia';
			$type = __( "Public Group", 'youzer' );
		} elseif ( 'hidden' == $group_type ) {
			$icon = 'fas fa-user-secret';
			$type = __( "Hidden Group", 'youzer' );
		} elseif ( 'private' == $group_type ) {
			$icon = 'fas fa-lock';
			$type = __( "Private Group", 'youzer' );
		} else {
			$icon = 'fas fa-users';
			$type = ucwords( $group_type ) . ' ' . __( 'Group', 'youzer' );
		}

		// Print Location
		echo '<i class="' . $icon . '"></i>';
		echo '<span>' . $type . '</span>';
	}

	/**
	 * # Group Statistics.
	 */
	function statistics( $args = null ) {

		global $Youzer;

		// Set Up Variable.
		$target = isset( $args['target'] ) ? $args['target'] : 'header';

		// Get Group ID.
		$group_id = isset( $args['group_id'] ) ? $args['group_id'] : bp_get_group_id();

		// Get Data.
		$members_number = bp_get_group_total_members();
		$posts_number 	= yz_get_group_total_posts_count( $group_id );

		// Show / Hide Elements.
		$display_posts 	 = yz_options( 'yz_display_group_' . $target . '_posts' );
		$display_members = yz_options( 'yz_display_group_' . $target . '_members' );

		if ( 'on' != $display_posts && 'on' != $display_members ) {
			return false;
		}

		// Get Statistics Data.
		$data = yz_get_args(
			array(
				'statistics_bg' 	=> yz_options( 'yz_group_' . $target . '_use_statistics_bg' ),
				'statistics_border' => yz_options( 'yz_group_' . $target . '_use_statistics_borders' ),
		), $args );

		// Get Statistics Class Name.
		$statistics_class[] = "yz-user-statistics";
		$statistics_class[] = ( 'on' == $data['statistics_bg'] ) ? 'yz-statistics-bg' : null;
		$statistics_class[] = ( 'on' == $data['statistics_border'] ) ? 'yz-use-borders' : null;

		?>

		<div class="<?php echo yz_generate_class( $statistics_class ); ?>">
			<ul>
				<?php if ( 'on' == $display_posts && bp_is_active( 'activity' ) ) : ?>
					<li>
						<div class="yz-snumber" title="<?php echo $posts_number; ?>"><?php echo $this->get_statistic_number( $posts_number ); ?></div>
						<h3 class="yz-sdescription"><?php _e( 'posts', 'youzer' ); ?></h3>
					</li>
				<?php endif; ?>

				<?php if ( 'on' == $display_members ) : ?>
					<?php $members_number = str_replace( array( ' ', '&nbsp;' ),'',  $members_number ); ?>
					<li>
						<div class="yz-snumber" title="<?php echo $members_number; ?>"><?php echo $this->get_statistic_number( $members_number ); ?></div>
						<h3 class="yz-sdescription"><?php _e( 'members', 'youzer' ); ?></h3>
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
	 * # Author Box Head.
	 */
	function box_head( $target, $user_id = null ) {

		// Get Data
		$last_name 	= yz_data( 'last_name', $user_id );
		$first_name	= yz_data( 'first_name', $user_id );
		$username 	= sanitize_user( yz_data( 'user_login', $user_id ) );
		$full_name 	= sanitize_text_field( "$first_name $last_name" );

		?>
			<div class="yzb-head-content">
				<h2><?php echo $full_name; ?></h2>
				<?php $this->box_meta( $target, $user_id ); ?>
			</div>
		<?php

	}

	/**
	 * # Ratings.
	 */
	function ratings() {
		// Soon.
	}

	/**
	 * # Social Networks.
	 */
	function networks( $args = null ) {

		// Prevent This Function for Now i will add it in coming updates.
		return false;

		// Set Up Variable.
		$group_id = isset( $args['group_id'] ) ? $args['group_id'] : null;
		$element = isset( $args['target'] ) ? $args['target'] : 'header';

		if ( ! is_group_have_networks( $group_id ) ) {
			return false;
		}

		// Get Social Networks
		$social_networks = yz_options( 'yz_group_social_networks' );

		// Display Networks Icons
		$display_networks = yz_options( 'yz_display_group_' . $element . '_networks' );

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
				'networks_type'   => yz_options( 'yz_group_' . $element . '_sn_bg_type' ),
				'networks_format' => yz_options( 'yz_group_' . $element . '_sn_bg_style' ),
		), $args );

		// Get Networks Size
		$networks_size = yz_options( 'yz_group_wg_sn_icons_size' );
		if ( 'wg' == $element ) {
			$networks_class[] = "yz-icons-$networks_size";
		}

		// Prepare Networks Class .
		$networks_class[] = "yz-$element-networks";
		$networks_class[] = "yz-icons-{$data['networks_type']}";
		$networks_class[] = "yz-icons-{$data['networks_format']}";
		$networks_class[] = "yz-networks-$user_id";

		// Networks Action
		do_action( 'youzer_before_group_networks', $args );

		// Get Networks Type
		$networks_class = yz_generate_class( $networks_class );

		echo "<ul class='$networks_class'>";

		foreach ( $social_networks as $network => $data ) {

			// Get Widget Data
			$icon = $data['icon'];
			$name = sanitize_text_field( $data['name'] );
			$link = esc_url( yz_data( $network, $user_id ) );

			if ( $link && $icon ) {
				echo "<li class='$network'><a href='$link'>";
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
	 * Scripts
	 */
	function scripts() {

		if ( ! bp_is_groups_component() ) {
			return;
		}

		global $Youzer;

		// Init Vars
		$jquery = array( 'jquery' );
		
	    // Load Carousel CSS and JS.
        wp_enqueue_style( 'yz-carousel-css', YZ_PA . 'css/owl.carousel.min.css', $Youzer->version );
        wp_enqueue_script( 'yz-carousel-js', YZ_PA . 'js/owl.carousel.min.js', $jquery, $Youzer->version, true );
        wp_enqueue_script( 'yz-slider', YZ_PA . 'js/yz-slider.min.js', $jquery, $Youzer->version, true );
    
	}
}