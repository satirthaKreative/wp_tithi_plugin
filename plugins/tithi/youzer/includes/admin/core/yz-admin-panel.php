<?php

class Youzer_Panel {

	public function __construct() {
		// Account Page Dialog Form.
		add_action( 'youzer_account_footer', array( &$this, 'account_dialog' ) );

        if ( ! yz_is_mycred_installed() ) {
			add_action( 'yz_user_balance_widget_settings', array( &$this, 'show_user_balance_widget_notice' ) );
		}
			add_action( 'yz_user_badges_widget_settings', array( &$this, 'show_user_bagdes_widget_notice' ) );

	}

	/**
	 * # Show User Balance Widget Notice.
	 */
	function show_user_balance_widget_notice() {

		global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'msg_type'  => 'info',
                'type'      => 'msgBox',
                'id'        => 'yz_msgbox_user_balance_widget_notice',
                'title'     => __( 'How to activate user balance widget?', 'youzer' ),
                'msg'       => sprintf( __( 'Please install the <a href="%1s"> MyCRED Plugin</a> to activate the user balance widget.'), 'https://wordpress.org/plugins/mycred/' )
            )
        );

	}
	
	/**
	 * # Show User Badges Widget Notice.
	 */
	function show_user_bagdes_widget_notice() {

		if ( defined( 'myCRED_BADGE_VERSION' ) ) {
			return false;
		}
		
		global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'msg_type'  => 'info',
                'type'      => 'msgBox',
                'id'        => 'yz_msgbox_user_badges_widget_notice',
                'title'     => __( 'How to activate user badges widget?', 'youzer' ),
                'msg'       => sprintf( __( 'Please install the <a href="%1s"> MyCRED Plugin</a> and <strong>MyCRED Badges Extension</strong> to activate the user badges widget.'), 'https://wordpress.org/plugins/mycred/' )
            )
        );

	}

	/**
	 * # Youzer Panel Form.
	 */
	function admin_panel( $menu = null, $settings = null ) {

	?>

	<?php do_action( 'youzer_admin_before_form' ); ?>

	<div id="ukai-panel" class="<?php echo yz_options( 'yz_panel_scheme' ); ?>">

	    <div class="uk-sidebar">
	        <div class="ukai-logo">
	        	<img src="<?php echo YZ_AA . 'images/logo.png'; ?>" alt="">
	        </div>
	        <a class="yz-tab-extensions" href="<?php echo apply_filters( 'youzer_panel_extensions_page_link', menu_page_url( 'yz-extensions', false ) ); ?>"><i class="fas fa-plug"></i><?php _e( 'Extensions <span class="new">New</span>') ?></a>
			<div class="kl-responsive-menu">
				<?php _e( 'menu', 'youzer' ); ?>
				<input class="kl-toggle-btn" type="checkbox" id="kl-toggle-btn" />
	  			<label class="kl-toggle-icon" for="kl-toggle-btn"></i><span class="kl-icon-bars"></span></label>
			</div>

			<!-- Panel Menu. -->
	        <?php $this->get_menu( $menu ); ?>
	    </div>

	    <div id="ukai-panel-content" class="ukai-panel">
	        <div class="youzer-main-content">
	            <?php
	            	// Get Panel Settings
	            	echo $settings;
	            ?>
			</div>
	    </div>

	</div>

	<div class="yz-md-overlay"></div>

	<!-- Reset Dialog -->
	<?php yz_popup_dialog( 'reset_tab' ); ?>

	<!-- Errors Dialog -->
	<?php yz_popup_dialog( 'error' ); ?>

	<?php do_action( 'youzer_admin_after_form' ); ?>
	
	<?php if ( 'on' == yz_options( 'yz_enable_panel_fixed_save_btn' ) ) : ?>
		<div class="yz-fixed-save-btn"><i class="fas fa-save"></i></div>
	<?php endif; ?>

	<?php

	}

	/**
	 *	Modal
	 */
	function modal( $args, $modal_function ) {

		$title 		  = $args['title'];
		$button_id	  = $args['button_id'];
		$button_title = isset( $args['button_title'] ) ? $args['button_title'] : __( 'save', 'youzer' );
		?>

		<div class="yz-md-modal yz-md-effect-1" id="<?php echo $args['id'] ;?>">
			<h3 class="yz-md-title" data-title="<?php echo $title; ?>">
				<?php echo $title; ?>
                <i class="fas fa-times yz-md-close-icon"></i>
			</h3>
			<div class="yz-md-content">
				<?php $modal_function(); ?>
			</div>
			<div class="yz-md-actions">
				<button id="<?php echo $button_id; ?>" data-add="<?php echo $button_id; ?>" class="yz-md-button yz-md-save">
					<?php echo $button_title ?>
				</button>
				<button class="yz-md-button yz-md-close">
					<?php _e( 'close', 'youzer' ); ?>
				</button>
			</div>
		</div>

		<?php
	}

	/**
	 * # Get Menu Content.
	 */
	function get_menu( $tabs_list ) {

		// Get Current Page Url.
		$current_url = yz_get_current_page_url();

		echo '<ul class="yz-panel-menu youzer-form-menu">';

		foreach ( $tabs_list as $key => $tab ) {

			// Add Tab ID to url.
			$tab_url = add_query_arg( 'tab', $key, $current_url );

			// Get Tab Class Name.
			$class = isset( $tab['class'] ) ? 'class="yz-active-tab"' : null; ?>

			<li>
				<a href="<?php echo $tab_url; ?>" <?php echo $class; ?>><i class="<?php echo $tab['icon']; ?>"></i><?php echo $tab['title']; ?></a>
			</li>

			<?php

		}

	    echo '</ul>';
	}

}