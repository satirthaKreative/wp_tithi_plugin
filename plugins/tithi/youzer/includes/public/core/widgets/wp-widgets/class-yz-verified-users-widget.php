<?php

/**
 * Verified Users Widget
 */

class YZ_Verified_Users_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_verified_users_widget',
			__( 'Youzer - Verified Users', 'youzer' ),
			array( 'description' => __( 'Verified Users Widget', 'youzer' ) )
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
		    	'title' => __( 'Verified Users', 'youzer' ),
		        'limit' => '5',
	    	)
	    );

	    // Get Input's Data.
		$limit = absint( $instance['limit'] );
		$title = strip_tags( $instance['title'] );
		$avatar_border_styles = $Youzer->fields->get_field_options( 'border_styles' );

		?>

		<!-- Title. -->   
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'bp-group-suggest' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<!-- Suggestions Number. -->   
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Suggestions Number:', 'bp-group-suggest' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" style="width: 30%" />
			</label>
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

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {
		
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'] );
			echo $args['after_title'];
		}

		do_shortcode( '[youzer_verified_users limit="' . $instance['limit'] .'"]' );

		echo $args['after_widget'];

	}

}