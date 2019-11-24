<?php
/**
 * BuddyPress - Members
 *
 */
/**
 * Fires at the top of the members directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_before_directory_members_page' ); ?>

<div class="youzer <?php echo yz_members_directory_class() ?>">

	<main class="yz-page-main-content">

		<div id="yz-members-directory">

			<?php

			/**
			 * Fires before the display of the members.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_before_directory_members' ); ?>

			<?php

			/**
			 * Fires before the display of the members content.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_before_directory_members_content' ); ?>

			<?php
			/**
			 * Fires before the display of the members list tabs.
			 *
			 * @since 1.8.0
			 */
			do_action( 'bp_before_directory_members_tabs' ); ?>

			<div class="yz-directory-filter">

				<div class="item-list-tabs" aria-label="<?php esc_attr_e( 'Members directory main navigation', 'youzer' ); ?>" role="navigation">
					<ul>
						<li class="selected" id="members-all"><a href="<?php bp_members_directory_permalink(); ?>"><?php printf( __( 'All Members %s', 'youzer' ), '<span>' . bp_get_total_member_count() . '</span>' ); ?></a></li>

						<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
							<li id="members-personal"><a href="<?php echo esc_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ); ?>"><?php printf( __( 'My Friends %s', 'youzer' ), '<span>' . bp_get_total_friend_count( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
						<?php endif; ?>

						<?php

						/**
						 * Fires inside the members directory member types.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_members_directory_member_types' ); ?>

					</ul>
				</div><!-- .item-list-tabs -->

				<div id="directory-show-search">
					<a><?php _e( 'Search', 'youzer' ); ?></a>
				</div>

				<div class="item-list-tabs" id="subnav" aria-label="<?php esc_attr_e( 'Members directory secondary navigation', 'youzer' ); ?>" role="navigation">
					<ul>
						<?php

						/**
						 * Fires inside the members directory member sub-types.
						 *
						 * @since 1.5.0
						 */
						do_action( 'bp_members_directory_member_sub_types' ); ?>

						<li id="members-order-select" class="last filter">
							<label for="members-order-by"><?php _e( 'Order By:', 'youzer' ); ?></label>
							<select id="members-order-by">
								<option value="active"><?php _e( 'Last Active', 'youzer' ); ?></option>
								<option value="newest"><?php _e( 'Newest Registered', 'youzer' ); ?></option>

								<?php if ( bp_is_active( 'xprofile' ) ) : ?>
									<option value="alphabetical"><?php _e( 'Alphabetical', 'youzer' ); ?></option>
								<?php endif; ?>

								<?php

								/**
								 * Fires inside the members directory member order options.
								 *
								 * @since 1.2.0
								 */
								do_action( 'bp_members_directory_order_options' ); ?>
							</select>
						</li>
						<li id="yz-directory-search-box">
							<div id="members-dir-search" class="dir-search" role="search">
								<?php bp_directory_members_search_form(); ?>
							</div><!-- #members-dir-search -->
						</li>
					</ul>
				</div>
			</div>
			
			<form action="" method="post" id="members-directory-form" class="dir-form">

				<div id="members-dir-list" class="members dir-list">
					<?php bp_get_template_part( 'members/members-loop' ); ?>
				</div><!-- #members-dir-list -->

				<?php

				/**
				 * Fires and displays the members content.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_directory_members_content' ); ?>

				<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

				<?php

				/**
				 * Fires after the display of the members content.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_directory_members_content' ); ?>

			</form><!-- #members-directory-form -->

			<?php

			/**
			 * Fires after the display of the members.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_after_directory_members' ); ?>

		</div><!-- #buddypress -->

	</main>

</div>

<?php

/**
 * Fires at the bottom of the members directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_directory_members_page' );