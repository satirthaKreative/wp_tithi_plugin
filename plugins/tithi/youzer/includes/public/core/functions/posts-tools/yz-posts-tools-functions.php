<?php

/**
 * Posts Tools Function
 */
function yz_get_activity_tools() {

	// Get Activity ID.
	$activity_id = bp_get_activity_id();

	// Get Tools Data.
	$tools = array();

	// Filter.
	$tools = apply_filters( 'yz_activity_tools', $tools, $activity_id );

	if ( empty( $tools ) ) {
		return false;
	}

	// Display "Show Activity Tools Button". 
	add_action( 'bp_before_activity_entry_header', 'yz_add_activity_thumbtack_icon' );


	?>
	
	<div class="yz-item-tools yz-activity-tools" data-activity-id="<?php echo $activity_id; ?>">
		<?php foreach ( $tools as $tool ) : ?>
			<?php $attributes = isset( $tool['attributes'] ) ? $tool['attributes'] : null; ?>
			<div class="yz-item-tool <?php echo yz_generate_class( $tool['class'] ); ?>" <?php yz_get_item_attributes( $attributes ); ?> <?php if ( isset( $tool['action'] ) ) { echo 'data-action="' . $tool['action'] .'"'; } ?>>
				<div class="yz-tool-icon"><i class="<?php echo $tool['icon'] ?>"></i></div>
				<div class="yz-tool-name"><?php echo $tool['title']; ?></div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php
}

add_action( 'bp_before_activity_entry_content', 'yz_get_activity_tools' );


/**
 * Get Attributes
 */
function yz_get_item_attributes( $attributes = null ) {

	if ( empty( $attributes ) ) {
		return;
	}

	foreach ( $attributes as $attribute => $value ) {
		echo 'data-' . $attribute . '="' . $value . '"';
	}

}

/**
 * Add Pinned Icon
 */
function yz_add_activity_thumbtack_icon() {

	// Get Icon.
	$icon = '<i class="fas fa-angle-down"></i>';

	// Filter
	$icon = apply_filters( 'yz_display_activity_tools_icon', $icon );
	
	?>

	<div class="yz-show-item-tools">
		<?php echo $icon; ?>
	</div>
	
	<?php

}