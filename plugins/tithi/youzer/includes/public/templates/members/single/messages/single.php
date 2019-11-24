<?php
/**
 * BuddyPress - Members Single Message
 */

?>
<div id="message-thread">

	<?php

	/**
	 * Fires before the display of a single member message thread content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_message_thread_content' ); ?>

	<?php if ( bp_thread_has_messages() ) : ?>

		<div id="message-recipients">

			<div class="highlight-icon"><i class="far fa-comments"></i></div>
			
			<div class="highlight">

				<h2 id="message-subject"><?php bp_the_thread_subject(); ?></h2>
				
				<span class="highlight-meta">
					
				<?php if ( bp_get_thread_recipients_count() <= 1 ) : ?>

					<?php _e( 'You are alone in this conversation.', 'youzer' ); ?>

				<?php elseif ( bp_get_max_thread_recipients_to_list() <= bp_get_thread_recipients_count() ) : ?>

					<?php printf( __( 'Conversation between %s recipients.', 'youzer' ), number_format_i18n( bp_get_thread_recipients_count() ) ); ?>

				<?php else : ?>

					<?php printf( __( 'Conversation between %s.', 'youzer' ), bp_get_thread_recipients_list() ); ?>

				<?php endif; ?>

				</span>

			</div>
			<div class="highlight-button">
				
			<a class="button confirm" href="<?php bp_the_thread_delete_link(); ?>"><i class="fas fa-trash-alt"></i><?php _e( 'Delete', 'youzer' ); ?></a>

			</div>
			<?php

			/**
			 * Fires after the action links in the header of a single message thread.
			 *
			 * @since 2.5.0
			 */
			do_action( 'bp_after_message_thread_recipients' ); ?>
		</div>

		<?php

		/**
		 * Fires before the display of the message thread list.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_message_thread_list' ); ?>

		<?php while ( bp_thread_messages() ) : bp_thread_the_message(); ?>
			<?php bp_get_template_part( 'members/single/messages/message' ); ?>
		<?php endwhile; ?>

		<?php

		/**
		 * Fires after the display of the message thread list.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_message_thread_list' ); ?>

		<?php

		/**
		 * Fires before the display of the message thread reply form.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_message_thread_reply' ); ?>

		<form id="send-reply" action="<?php bp_messages_form_action(); ?>" method="post" class="standard-form">

			<div class="message-reply-content">
				
				<?php bp_loggedin_user_avatar( 'type=thumb&height=50&width=50' ); ?>

				<?php

				/**
				 * Fires before the display of the message reply box.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_before_message_reply_box' ); ?>

				<textarea name="content" id="message_content" rows="15" cols="40" placeholder="<?php _e( 'Write a reply ...', 'youzer' ); ?>"></textarea>

				<?php

				/**
				 * Fires after the display of the message reply box.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_message_reply_box' ); ?>
			
			</div>

			<div class="submit">
				<button type="submit" name="send" id="send_reply_button"><i class="fas fa-paper-plane"></i><?php esc_attr_e( 'Send', 'youzer' ); ?></button>
			</div>

			<input type="hidden" id="thread_id" name="thread_id" value="<?php bp_the_thread_id(); ?>" />
			<input type="hidden" id="messages_order" name="messages_order" value="<?php bp_thread_messages_order(); ?>" />
			<?php wp_nonce_field( 'messages_send_message', 'send_message_nonce' ); ?>

		</form><!-- #send-reply -->

		<?php

		/**
		 * Fires after the display of the message thread reply form.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_message_thread_reply' ); ?>

	<?php endif; ?>

	<?php

	/**
	 * Fires after the display of a single member message thread content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_message_thread_content' ); ?>

</div>
