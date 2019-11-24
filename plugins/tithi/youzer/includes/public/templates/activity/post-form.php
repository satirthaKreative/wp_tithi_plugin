<?php
/**
 * BuddyPress - Activity Post Form
 */

?>

<?php $security_nonce = wp_create_nonce( 'yz_wall_nonce_security' ); ?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="yz-wall-form" name="whats-new-form" enctype="multipart/form-data">
	
	<div class="yz-wall-options">

		<?php yz_wall_form_post_types_buttons(); ?>

	</div>

	<div class="yz-wall-content" ng-app="YouzerWallApp" ng-controller="YouzerWallController">

		<div class="yz-wall-author" href="<?php echo bp_loggedin_user_domain(); ?>" style="background-image: url(<?php echo bp_loggedin_user_avatar( 'html=false' ); ?>)">
		</div>

			<textarea name="status" class="yz-wall-textarea bp-suggestions" id="whats-new" placeholder="<?php if ( bp_is_group() )
		printf( __( "What's new in %s, %s?", 'youzer' ), bp_get_group_name(), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
	else
		printf( __( "What's new, %s?", 'youzer' ), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
	?>" <?php if ( bp_is_group() ) : ?> data-suggestions-group-id="<?php echo esc_attr( (int) bp_get_current_group_id() ); ?>" <?php endif; ?>
			><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_textarea( $_GET['r'] ); ?> <?php endif; ?></textarea>

		<?php if ( 'on' == yz_options( 'yz_enable_wall_link' ) ) : ?>
		<div class="yz-wall-custom-form yz-wall-link-form">

			<div class="yz-wall-cf-item">
				<input type="text" class="yz-wall-cf-input" name="link_url" placeholder="<?php _e( 'Add Link Url', 'youzer' ); ?>" />
			</div>
			
			<div class="yz-wall-cf-item">
				<input type="text" class="yz-wall-cf-input" name="link_title" placeholder="<?php _e( 'Add Link Title', 'youzer' ); ?>" />
			</div>
			
			<div class="yz-wall-cf-item">
				<textarea name="link_desc" class="yz-wall-cf-input" placeholder="<?php _e( 'Brief Link Description', 'youzer' ); ?>"></textarea>
			</div>

		</div>
		<?php endif; ?>
	
		<?php if ( 'on' == yz_options( 'yz_enable_wall_url_preview' ) ) : ?>
    		<link-preview tpage="%N ➜ %N" iamount="10"/></link-preview>
    	<?php endif; ?>
            
		<?php if ( 'on' == yz_options( 'yz_enable_wall_quote' ) ) : ?>
		<div class="yz-wall-custom-form yz-wall-quote-form">

			<div class="yz-wall-cf-item">
				<input type="text" class="yz-wall-cf-input" name="quote_owner" placeholder="<?php _e( 'Add Quote Owner', 'youzer' ); ?>" />
			</div>

			<div class="yz-wall-cf-item">
				<textarea name="quote_text" class="yz-wall-cf-input" placeholder="<?php _e( 'Add Quote Text', 'youzer' ); ?>"></textarea>
			</div>

		</div>
		<?php endif; ?>

	</div>
	<div class="yz-wall-actions">
		
		<?php if ( bp_is_active( 'groups' ) && !bp_is_my_profile() && !bp_is_group() ) : ?>

			<div id="whats-new-post-in-box">
				
				<label for="yz-whats-new-post-in" ><?php _e( 'Post in:', 'youzer' ); ?></label>
				<select id="yz-whats-new-post-in" name="yz-whats-new-post-in">
					<option selected="selected" value="0"><?php _e( 'My Profile', 'youzer' ); ?></option>

					<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
						while ( bp_groups() ) : bp_the_group(); ?>

							<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

						<?php endwhile;
					endif; ?>

				</select>
			</div>
			<input type="hidden" id="yz-whats-new-post-object" name="yz-whats-new-post-object" value="groups" />

		<?php elseif ( bp_is_group_activity() ) : ?>

			<input type="hidden" id="yz-whats-new-post-object" name="yz-whats-new-post-object" value="groups" />
			<input type="hidden" id="yz-whats-new-post-in" name="yz-whats-new-post-in" value="<?php bp_group_id(); ?>" />

		<?php endif; ?>

		<a class="yz-wall-upload-btn">
			<i class="fas fa-paperclip"></i><?php _e( 'upload attachment', 'youzer' ); ?>
		</a>

		<input type="submit" name="aw-whats-new-submit" class="yz-wall-post" value="<?php esc_attr_e( 'Post', 'youzer' ); ?>" />
		

			<?php

			/**
			 * Fires at the end of the activity post form markup.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_activity_post_form_options' ); ?>

	</div>
	
	<div class="yz-wall-attchments">
		<input hidden="true" id="yz-upload-attachments" type="file" name="attachments[]" multiple>
		<div class="yz-form-attachments"></div>
	</div>

	<input type="hidden" name="security" value="<?php echo $security_nonce; ?>">

</form>