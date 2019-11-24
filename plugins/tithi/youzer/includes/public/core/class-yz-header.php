<?php

class Youzer_Header {

	function __construct() {

		// Add Profile Header
		add_action( 'youzer_profile_header', array( &$this, 'profile_header' ) );

		// Add Group Header
		add_action( 'youzer_group_header', array( &$this, 'group_header' ) );

		// Remove Issues With Prefetching Adding Extra Views
		remove_action( 'wp_head', array( &$this, 'adjacent_posts_rel_link_wp_head' ) , 10, 0 );

	}

	/**
	 * # Profile Header.
	 */
	function profile_header() {

		global $Youzer;

		// Get Header Layout.
		$header_layout = yz_options( 'yz_header_layout' );

		if ( false !== strpos( $header_layout, 'yzb-author' ) ) {
			// Get Auhtor Box Arguments.
			$args = array( 
				'target'  	=> 'header',
				'layout'  	=> $header_layout,
				'user_id' 	=> yz_profileUserID(),
				'meta_icon' => yz_options( 'yz_header_meta_icon' ),
				'meta_type' => yz_options( 'yz_header_meta_type' ),
				'cover_overlay'	=> yz_options( 'yz_enable_header_overlay' ),
				'cover_pattern'	=> yz_options( 'yz_enable_header_pattern' )
			);
			// Get Author Box Header.
	 		$Youzer->author->get_author_box( $args );
		} else {
			// Get Standard Header.
			$this->get_header( 'user' );
		}

	}

	/**
	 * # Group Header.
	 */
	function group_header() {

		global $Youzer;

		// Get Header Layout.
		$header_layout = yz_options( 'yz_group_header_layout' );

		// Get Standard Header.
		$this->get_header( 'group' );

	}

	/**
	 * Get Profile Header.
	 */
	function get_header( $component ) {

		global $Youzer;

		?>

		<div class="yz-header-cover" <?php $Youzer->$component->cover(); ?>>
			<?php do_action( 'yz_before_header_cover' ); ?>
			<div class="yz-cover-content">
				<?php do_action( 'yz_before_header_cover_content' ); ?>
				<?php $this->get_elements( $component, 'firstRow', 'outer' ); ?>
				<div class="yz-inner-content">
					<?php do_action( 'yz_before_header_cover_inner_content' ); ?>
					<?php $this->get_elements( $component, 'firstRow', 'photo' ); ?>
					<div class="yz-head-content">
						<?php do_action( 'yz_before_header_cover_head_content' ); ?>
						<?php $this->get_elements( $component, 'firstRow', 'head' ); ?>
						<?php do_action( 'yz_after_header_cover_head_content' ); ?>
					</div>
					<?php $this->get_elements( $component, 'firstRow', 'inner' ); ?>
					<?php do_action( 'yz_after_header_cover_inner_content' ); ?>
				</div>
				<?php do_action( 'yz_after_header_cover_content' ); ?>
			</div>
			<?php do_action( 'yz_after_header_cover' ); ?>
		</div>

		<div class="yz-header-content">
			<div class="yz-header-head">
				<?php $this->get_elements( $component, 'secondRow', 'inner' ); ?>
			</div>
			<?php $this->get_elements( $component, 'secondRow', 'outer' ); ?>
		</div>

		<?php
	}

	/**
	 * # Header Class.
	 */
	function get_class( $component ) {

		// Create Empty Array.
		$header_class = array();

		// Add Header Main Class
		$header_class[] = 'yz-profile-header';

		// Get Options.

		if ( 'user' == $component ) {
			$header_type 	= yz_options( 'yz_header_type' );
			$header_layout 	= yz_options( 'yz_header_layout' );
			$header_effect 	= yz_options( 'yz_hdr_load_effect' );
			$header_overlay	= yz_options( 'yz_enable_header_overlay' );
			$header_pattern	= yz_options( 'yz_enable_header_pattern' );
		} elseif ( 'group' == $component ) {
			$header_type 	= yz_options( 'yz_group_header_type' );
			$header_layout 	= yz_options( 'yz_group_header_layout' );
			$header_overlay	= yz_options( 'yz_enable_group_header_overlay' );
			$header_pattern	= yz_options( 'yz_enable_group_header_pattern' );
		}

		// Add a class depending on another one.
		if ( 'hdr-v4' == $header_layout || 'hdr-v5' == $header_layout ) {
			$header_class[] = "yz-hdr-v3";
		} elseif ( 'hdr-v8' == $header_layout ) {
			$header_class[] = "yz-hdr-v1";
		}

		// Add header layout.
		$header_class[] = "yz-$header_layout";

		// Add header cover overlay.
		if ( 'on' == $header_overlay ) {
			$header_class[] = 'yz-header-overlay';
		}

		// Add header cover pattern.
		if ( 'on' == $header_pattern ) {
			$header_class[] = 'yz-header-pattern';
		}

		if ( 'user' == $component ) {
			// Add effect class.
		 	$header_class[] = youzer()->widgets->get_loading_effect( $header_effect, 'class' );
		}

	 	// Return Class Name.
		return yz_generate_class( $header_class );
	}

	/**
	 * Header Structure
	 */
	function get_header_structure( $component ) {

		$args = array();

		// Get Name.
		$name = ( 'user' == $component ) ? 'username' : 'name';

		$args['hdr-v1'] = array(
			'firstRow' 	=> array(
				'photo'	=> array( 'photo' ),
				'head' 	=> array( $name, 'ratings', 'meta' ),
				'inner' => array( 'statistics' ),
			)
		);

		$args['hdr-v2'] = array(
			'firstRow' 	=> array(
				'outer' => array( 'photo' ),
				'inner' => array( $name, 'ratings', 'meta' )
			),
			'secondRow' => array(
				'outer' => array( 'networks', 'statistics' )
			)
		);

		$args['hdr-v3'] = array(
			'firstRow' 	=> array(
				'inner' => array( 'photo', $name, 'ratings', 'meta', 'networks'  )
			)
		);

		$args['hdr-v7'] = array(
			'firstRow' 	=> array(
				'outer' => array( 'photo' ),
				'inner' => array( 'ratings', 'networks' )
			),
			'secondRow' => array(
				'inner' => array( $name, 'meta' ),
				'outer' => array( 'statistics' )
			)
		);

		$args['hdr-v8'] = array(
			'firstRow' 	=> array(
				'photo'	=> array( 'photo' ),
				'head' 	=> array( $name, 'ratings', 'meta', 'networks' ),
				'inner' => array( 'statistics' ),
			)
		);

		// Get Header Layout.
		if ( 'user' == $component ) {
			$header_layout = yz_options( 'yz_header_layout' );
		} elseif ( 'group' == $component ) {
			$header_layout = yz_options( 'yz_group_header_layout' );
		}

		// Mutual Structure
		$common_structure = array( 'hdr-v4', 'hdr-v5', 'hdr-v6' );

		// Return Structure
		if ( in_array( $header_layout, $common_structure ) ) {
			return $args['hdr-v3'];
		} else {
			return $args[ $header_layout ];
		}

	}

	/**
	 * Header Elements Generator
	 */
	function get_elements( $component, $row, $target ) {

		// Get Header Structure
		$header_args = $this->get_header_structure( $component );

		if ( isset( $header_args[ $row ][ $target ] ) ) :
			global $Youzer;
			foreach ( $header_args[ $row ][ $target ] as $element ) {
				do_action( 'yz_before_header_' . $element .'_' . $target );
				$Youzer->$component->$element();
				do_action( 'yz_after_header_' . $element .'_' . $target );
			}
		endif;

	}
}