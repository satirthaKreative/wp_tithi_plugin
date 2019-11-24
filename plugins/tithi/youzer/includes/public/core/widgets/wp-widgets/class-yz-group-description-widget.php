<?php
/**
 * Group Description Widget
 */

class YZ_Group_Description_Widget extends WP_Widget {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		parent::__construct(
			'yz_group_description_widget',
			__( 'Youzer - Group Description', 'youzer' ),
			array( 'description' => __( 'Youzer Group Description Widget', 'youzer' ) )
		);

	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		// Check if widget should be visible or not.
		if ( ! bp_is_active( 'groups' ) || ! bp_is_groups_component() || ! bp_group_is_visible() ) {
			return false;
		}

		global $bp;

		// Get Group Data
		$group = $bp->groups->current_group;

		// Get Group Description.
		$group_description = esc_textarea( $group->description );
		
		if ( empty( $group_description ) ) {
			return false;
		}

		?>

		<div class="yz-group-infos-widget">
			<div class="yz-group-widget-title">
				<i class="fas fa-file-alt"></i>
				<?php echo _e( 'description', 'youzer' ); ?>
			</div>
			<div class="yz-group-widget-content"><?php echo $group_description; ?></div>
		</div>

		<?php

	}

	/**
	 * Login Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( 'This widget will show the opened group description', 'youzer' ) . '</p>';
	}

}