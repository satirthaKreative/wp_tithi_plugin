<?php

/**
 * Group Suggestions Widget
 */

class YZ_Group_Suggestions_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_group_suggestions_widget',
			__( 'Youzer - Group Suggestions', 'youzer' ),
			array( 'description' => __( 'Group Suggestions Widget', 'youzer' ) )
		);

		$this->default_options = array(
	    	'title' => __( 'Group Suggestions', 'youzer' ),
	        'avatar_border_style' => 'circle',
	        'show_buttons' => 'on',
	        'limit' => '5',
	    );

		// Save User Removed Suggestions.
		add_action( 'wp_ajax_yz_groups_refused_suggestion', array( $this, 'hide_suggestion' ) );

	}
	
	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

		global $Youzer;

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance, $this->default_options );

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

		<!-- Display Buttons -->
		<p>
	        <input class="checkbox" type="checkbox" <?php checked( $instance['show_buttons'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_buttons' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_buttons' ) ); ?>" /> 
	        <label for="<?php echo $this->get_field_id( 'show_buttons' ); ?>"><?php _e( 'Show Buttons', 'youzer' ); ?></label>
    	</p>

		<!-- Avatar Border Style -->
	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'avatar_border_style' ) ); ?>"><?php esc_attr_e( 'Avatar Border Style', 'youzer' ); ?></label> 
	        <select id="<?php echo $this->get_field_id( 'avatar_border_style' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'avatar_border_style' ) ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( $avatar_border_styles as $style_id => $style_name ) { ?>
	            	<option <?php selected( $instance['avatar_border_style'], $style_id ); ?> value="<?php echo $style_id; ?>"><?php echo $style_name; ?></option>
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

		// Update Fields.
		$instance = $old_instance;
		$instance['limit'] = absint( $new_instance['limit'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show_buttons'] = $new_instance['show_buttons'];
		$instance['avatar_border_style'] = strip_tags( $new_instance['avatar_border_style'] );

		return $instance;
	}

	/**
	 * Login Widget Content
	 */
	public function widget( $args, $instance ) {

		// If user not logged-in or groups feature not active Don't show anything.
		if ( ! is_user_logged_in() || ! bp_is_active( 'groups' ) ) {
			return false;
		}
		
		// Get User ID.
		$user_id = bp_loggedin_user_id();

		// Get Group Suggestions.
		$group_suggestions = $this->get_group_suggestions( $user_id );

		// Hide Widget IF There's No suggestions.
		if ( empty( $group_suggestions ) ) {
			return false;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'] );
			echo $args['after_title'];
		}

		$this->get_suggestions_list( $instance );

		echo $args['after_widget'];

	}

	/**
	 * Get Suggestions Groups.
	 */
	function get_group_suggestions( $user_id ) {

		// Get User ID.
		$user_id = ( $user_id ) ? $user_id : bp_loggedin_user_id();
		
		// Get List Of excluded Id's.
		$excluded_ids = (array) $this->get_excluded_groups_ids( $user_id );

		// Get Friends Groups.
		$friends_groups = (array) $this->get_user_friends_groups( $user_id );

		// Get Suggestion Groups.
		$group_suggestions = array_diff( $friends_groups, $excluded_ids );

		// Randomize Order.
		shuffle( $group_suggestions );

		// Return Group ID's.
		return $group_suggestions;
	}

	/**
	 * Get Suggestions List.
	 */
	function get_suggestions_list( $args ) {

		global $Youzer;

		// Get User ID.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_loggedin_user_id();

		// Get Suggestion Groups.
		$group_suggestions = $this->get_group_suggestions( $user_id );

		// Limit Groups Number
		$group_suggestions = array_slice( $group_suggestions, 0, $args['limit'] );
		
		// Get Data.
		$show_buttons = $args['show_buttons'] ? 'on' : 'off';

		// Get Widget Class
		$list_class = array( 
			'yz-items-list-widget',
			'yz-suggested-groups-widget',
			'yz-list-avatar-' . $args['avatar_border_style']
		);

		?>

		<div class="<?php echo yz_generate_class( $list_class ); ?>">

			<?php foreach ( $group_suggestions as $group_id ) : ?>

			<?php $group = groups_get_group( array( 'group_id' => $group_id ) ); ?>
			<?php $group_url = bp_get_group_permalink( $group ); ?>

			<div class="yz-list-item">
				<a href="<?php echo $group_url; ?>" class="yz-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $group_id, 'object' => 'group') ); ?></a>
				<div class="yz-item-data">
					<a href="<?php echo $group_url; ?>" class="yz-item-name"><?php echo $group->name; ?></a>
					<div class="yz-item-meta">
						<div class="yz-meta-item"><?php $Youzer->group->status( $group ); ?></div>
					</div>
				</div>
				<?php if ( 'on' == $show_buttons ) : ?>
				<div class="yz-item-action">
					
					<?php

						// Get Join Group Url.
						if ( 'public' == $group->status ) {
							$add_url = wp_nonce_url( trailingslashit( bp_get_group_permalink( $group ) . 'join' ), 'groups_join_group' );
						} else if ( 'private' == $group->status ) {
							$add_url = wp_nonce_url( trailingslashit( bp_get_group_permalink( $group ) . 'request-membership' ), 'groups_request_membership' );
						}

						// Get Remove Group Suggestion Url.
						$refused_url = bp_get_root_domain() . "/refuse-group-suggestion/?suggestion_id=" . $group_id . "&_wpnonce=" . wp_create_nonce( 'group-suggestion-refused-' . $group_id );
		
					?>
					
					<a href="<?php echo $add_url; ?>" class="yz-list-button yz-icon-button yz-add-button">
						<i class="fas fa-sign-in-alt"></i>
					</a>					
					<a href="<?php echo $refused_url;?>" class="yz-list-button yz-icon-button yz-close-button">
						<i class="fas fa-trash-alt"></i>
					</a>
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>

		<?php

	}
	/**
	 * Get User Friends Groups
	 */
	function get_user_friends_groups( $user_id = null ) {

		global $bp, $wpdb;

		// Get User ID.
		$user_id = ( $user_id ) ? $user_id : bp_loggedin_user_id();
		
		// Get All User Friends List.
		$user_friends = (array) friends_get_friend_user_ids( $user_id );
		
		// Check If User have friends.
		if ( empty( $user_friends ) ) {
			return;
		}

		// Convert Friends List into string an separate user ids by commas.
		$friends_ids = '(' . join( ',', $user_friends ) . ')';

		// Prepare Friends SQL.
		$friends_groups_sql = "SELECT DISTINCT group_id FROM {$bp->groups->table_name} g, {$bp->groups->table_name_members} m WHERE g.id=m.group_id AND ( g.status='public' OR g.status='private' ) AND m.user_id in {$friends_ids} AND is_confirmed= 1";

		// Get Friend Groups ID's.
		$friends_groups_result = $wpdb->get_col( $friends_groups_sql );

		return $friends_groups_result;

	}

	/**
	 * Get User Excluded Groups
	 */
	function get_excluded_groups_ids( $user_id = null ) {

		global $bp, $wpdb;

		// Get User ID.
		$user_id = ( $user_id ) ? $user_id : bp_loggedin_user_id();
		
		// Get All User Groups With All types.
		$groups_sql = $wpdb->prepare( "SELECT DISTINCT group_id FROM {$bp->groups->table_name_members} WHERE user_id = %d ", $user_id );

		// Get Sql Result.
		$groups_ids = $wpdb->get_col( $groups_sql );

		// List of Refused Suggestions
		$refused_groups = (array) self::get_refused_group_suggestions( $user_id );

		// make an array of users group+groups hidden by user
		$excluded_ids = array_merge( $groups_ids, $refused_groups );

		// Remove Repeated ID's.
		$excluded_ids = array_unique( $excluded_ids );

		return $excluded_ids;
	}

	/**
	 * Save New Refused Suggestions.
	 */
	public function hide_suggestion() {

		// Get Suggested Group ID.
		$suggestion_id = isset( $_POST['suggestion_id'] ) ? $_POST['suggestion_id'] : 0;
		
		check_ajax_referer( 'group-suggestion-refused-' . $suggestion_id );

		if ( empty( $suggestion_id ) || ! is_user_logged_in() ) {
			return;
		}
		
		// Get Current User ID.
		$user_id = bp_loggedin_user_id();

		// Get Old Refused Suggestions.
		$refused_suggestions = get_user_meta( $user_id, 'yz_refused_group_suggestions', true );

		// Add The new Refused Suggestion to the old refused suggetions list.
		if ( ! in_array( $suggestion_id, $refused_suggestions ) ) {
			$refused_suggestions[] = $suggestion_id;
		}

		// Save New Refused Suggestion
		update_user_meta( $user_id, 'yz_refused_group_suggestions', $refused_suggestions );

		die();

	}

	/**
	 * Get Refused Suggestions.
	 */
	public static function get_refused_group_suggestions( $user_id = null ) {

		// Get User ID.
		$user_id = ( $user_id ) ? $user_id : bp_loggedin_user_id();
		
		// Get Refused Groups.		
		return get_user_meta( $user_id, 'yz_refused_group_suggestions', true );

	}

}