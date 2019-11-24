<?php do_action( 'youzer_account_before_content' ); ?>

<div class="youzer yz-page yz-account-page">

	<main class="yz-page-main-content">

		<aside class="youzer-sidebar settings-sidebar">

			<?php do_action( 'youzer_settings_menus' ); ?>

		</aside>

		<div class="youzer-main-content settings-main-content">

			<div id="template-notices" role="alert" aria-atomic="true">
				<?php

				/**
				 * Fires towards the top of template pages for notice display.
				 *
				 * @since 1.0.0
				 */
				do_action( 'template_notices' ); ?>

			</div>
			
			<div class="youzer-inner-content settings-inner-content">

                <?php do_action( 'youzer_account_before_form'); ?>

                <?php do_action( 'youzer_profile_settings' ); ?>

                <?php do_action( 'youzer_account_after_form' ); ?>

			</div>

		</div>

	</main>

	<?php do_action( 'youzer_account_footer'); ?>

</div>