<?php

class Youzer_Author {

	function __construct() {
		// Add Shortcode.
		add_shortcode( 'youzer_author_box', array( &$this, 'youzer_author_box' ) );
	}

	/**
	 * Author Box Shortcode
	 */
	function youzer_author_box( $atts ) {
	
		ob_start();

		$box_args = shortcode_atts(
			array(
				'user_id' 			=> false,
				'layout'  			=> yz_options( 'yz_author_layout' ),
				'meta_icon'  		=> yz_options( 'yz_author_meta_icon' ),
				'meta_type'  		=> yz_options( 'yz_author_meta_type' ),
				'networks_type'	  	=> yz_options( 'yz_author_sn_bg_type' ),
				'networks_format' 	=> yz_options( 'yz_author_sn_bg_style' ),
				'cover_overlay'		=> yz_options( 'yz_enable_author_overlay' ),
				'cover_pattern'		=> yz_options( 'yz_enable_author_pattern' ),
				'statistics_bg' 	=> yz_options( 'yz_author_use_statistics_bg' ),
				'statistics_border' => yz_options( 'yz_author_use_statistics_borders' ),
		), $atts );

		// Don't Show Author box if the admin didn't set the user id.
		if ( empty( $box_args['user_id'] ) || 0 == $box_args['user_id'] ) {
			return false;
		}

		// Set Settings Target.
		$box_args['target'] = 'author';

		// Display Box.
		$this->get_author_box( $box_args );
    	
		$content = ob_get_contents();

		ob_end_clean();

    	return $content;

	}

	/**
	 * Author Box
	 */
	function get_author_box( $args ) {

		// Get User Id.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_displayed_user_id();

		?>

		<div class="yzb-author <?php echo $this->get_cover_class( $args ); ?>">

			<?php yz_get_user_tools( $user_id ) ?>

			<!-- Box Content -->
			<?php $this->get_elements( $args ); ?>

		</div>

		<?php

	}

	/**
	 * Author Box Structure
	 */
	function get_box_structure( $layout = null ) {

		// Set Up New Array.
		$structure = array();

		$structure['yzb-author-v1'] = array(
			'cover'	  => array( 'photo' ),
			'content' => array( 'box_head', 'badges', 'ratings', 'buttons', 'networks', 'statistics' )
		);

		$structure['yzb-author-v2'] = array(
			'cover'	  => array( 'photo' ),
			'content' => array( 'box_head', 'badges', 'ratings', 'buttons', 'statistics', 'networks' )
		);

		$structure['yzb-author-v3'] = array(
			'cover'	  => array( 'photo', 'box_head' ),
			'content' => array( 'ratings', 'badges', 'statistics' , 'buttons', 'networks' )
		);

		$structure['yzb-author-v4'] = array(
			'cover'	  => array( 'photo', 'box_head' ),
			'content' => array( 'ratings', 'badges', 'buttons', 'networks', 'statistics' )
		);

		$structure['yzb-author-v5'] = array(
			'content' => array( 'photo', 'box_head', 'badges', 'ratings', 'buttons', 'statistics', 'networks' )
		);

		$structure['yzb-author-v6'] = array(
			'cover'	=> array( 'photo', 'box_head', 'ratings', 'badges', 'buttons', 'networks', 'statistics' )
		);

		$structure = apply_filters( 'yz_get_author_box_structure', $structure, $layout );
		
		return $structure[ $layout ];

	}

	/**
	 * Author Box Elements Generator
	 */
	function get_elements( $args = null ) {

		global $Youzer;

		$elements = array( 'cover', 'content' );

		// Get Header Structure
		$header_args = $this->get_box_structure( $args['layout'] );

		foreach ( $elements as $element ) :

			if ( isset( $header_args[ $element ] ) ) :

				if ( 'cover' == $element ) {
					$cover = $Youzer->user->cover( 'style', $args['user_id'] );
					echo "<div class='yz-header-cover' $cover>";
				} elseif ( 'content' == $element ) {
					echo "<div class='yzb-author-content'>";
				}

				echo '<div class="yz-inner-content">';
				foreach ( $header_args[ $element ] as $element ) {
					do_action( 'yz_before_author_box_' . $element, $args );
					$function = "get_box_$element";
					$Youzer->user->$element( $args, $args['user_id'] );
					do_action( 'yz_after_author_box_' . $element, $args );
				}
				echo '</div>';

				echo '</div>';

			endif;

		endforeach;
	}

	/**
	 * Cover Class
	 */
	function get_cover_class( $args ) {

		// Create Empty Array.
		$cover_class = array();

		// Get Box Layout.
		$cover_class[] = $args['layout'];

		// Add header cover overlay.
		if ( 'on' == $args['cover_overlay'] ) {
			$cover_class[] = 'yz-header-overlay';
		}

		// Add header cover pattern.
		if ( 'on' == $args['cover_pattern'] ) {
			$cover_class[] = 'yz-header-pattern';
		}

	 	// Return Class Name.
		return yz_generate_class( $cover_class );
	}

}