<?php

class YZ_Info_Box {

    /**
     * Address Box Args
     */
    function address() {

        // Args
        $box_args = array(
            'box_icon'  => 'fas fa-home',
            'box_class' => 'address',
            'box_id'    => 'user_address',
            'box_title' => __( 'Address', 'youzer' ),
        );

        return $box_args;
    }
        
    /**
     * Address Args
     */
    function address_args() {

        // Arguments
        $args = array(
            'display_title'     => false,
            'widget_title'      => 'address',
            'widget_name'       => 'address',
            'main_data'         => 'user_address',
            'function_options'  => $this->address(),
            'load_effect'       => yz_options( 'yz_address_load_effect' )
        );

        // Filter
        $args = apply_filters( 'yz_address_box_widget_args', $args );

        return $args;
    }

    /**
     * Website Box Args
     */
    function website() {

        // Args.
        $box_args = array(
            'box_icon'  => 'fas fa-link',
            'box_class' => 'website',
            'box_id'    => 'user_url',
            'box_title' => __( 'Website', 'youzer' )
        );

        return $box_args;
    }

    /**
     * Website Args
     */
    function website_args() {

        // Arguments
        $args = array(
            'display_title'     => false,
            'widget_title'      => 'website',
            'widget_name'       => 'website',
            'main_data'         => 'user_url',
            'function_options'  => $this->website(),
            'load_effect'       => yz_options( 'yz_website_load_effect' )
        );

        // Filter
        $args = apply_filters( 'yz_website_box_widget_args', $args );

        return $args;
    }

    /**
     * E-mail Box Args
     */
    function email() {

        // Args
        $box_args = array(
            'box_icon'  => 'far fa-envelope',
            'box_class' => 'email',
            'box_id'    => 'email_address',
            'box_title' => __( 'E-mail Address', 'youzer' )
        );

        return $box_args;
    }

    /**
     * E-mail Args
     */
    function email_args() {

        //  Arguments
        $args = array(
            'display_title'     => false,
            'widget_title'      => 'email',
            'widget_name'       => 'email',
            'function_options'  => $this->email(),
            'main_data'         => 'email_address',
            'load_effect'       => yz_options( 'yz_email_load_effect' )
        );

        // Filter
        $args = apply_filters( 'yz_email_box_widget_args', $args );

        return $args;
    }

    /**
     * Phone Box Args
     */
    function phone() {

        // Get Box Args
        $box_args = array(
            'box_class' => 'phone',
            'box_id'    => 'phone_nbr',
            'box_icon'  => 'fas fa-phone-volume',
            'box_title' => __( 'Phone Number', 'youzer' )
        );
    
        return $box_args;

    }

    /**
     * Phone Args
     */
    function phone_args() {

        // Arguments
        $args =  array(
            'display_title'     => false,
            'widget_title'      => 'phone',
            'widget_name'       => 'phone',
            'main_data'         => 'phone_nbr',
            'function_options'  => $this->phone(),
            'load_effect'       => yz_options( 'yz_phone_load_effect' )
        );

        // Filter
        $args = apply_filters( 'yz_phone_box_widget_args', $args );

        return $args;
    }


    /**
     * # Content.
     */
    function widget( $args ) {

		// Get Box Content.
		$box_content = sanitize_text_field( yz_data( $args['box_id'] ) );

        // Check User Website Url.
        if ( 'user_url' == $args['box_id'] ) {
            $box_content = '<a href="' . esc_url( $box_content ). '">' . yz_esc_url( $box_content ) . '</a>';
        }

        // Hide Box if there's no content.
		if ( empty( $box_content ) ) {
            return false;
        }

		?>

		<div class="yz-infobox-content <?php echo "yz-box-" . $args['box_class']; ?>">
			<div class="yz-box-head">
				<div class="yz-box-icon">
					<i class="<?php echo $args['box_icon']; ?>"></i>
				</div>
				<h2 class="yz-box-title"><?php echo sanitize_text_field( $args['box_title'] ); ?></h2>
			</div>
			<div class="yz-box-content">
				<p><?php echo $box_content; ?></p>
			</div>
		</div>

		<?php

	}

	/**
     * # Admin Settings.
     */
    function admin_settings() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'boxes effect settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'address loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'address loading effect', 'youzer' ),
                'id'    => 'yz_address_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'email loading effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'email loading effect', 'youzer' ),
                'id'    => 'yz_email_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'phone loading effect', 'youzer' ),
                'desc'  => __( 'phone number loading effect?', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'id'    => 'yz_phone_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'website loading effect', 'youzer' ),
                'desc'  => __( 'website loading effect?', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'id'    => 'yz_website_load_effect',
                'type'  => 'select'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Email Styling settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'background left', 'youzer' ),
                'desc'  => __( 'gradient background color', 'youzer' ),
                'id'    => 'yz_ibox_email_bg_left',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'background right', 'youzer' ),
                'desc'  => __( 'gradient background color', 'youzer' ),
                'id'    => 'yz_ibox_email_bg_right',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'address Styling settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'background left', 'youzer' ),
                'desc'  => __( 'gradient background color', 'youzer' ),
                'id'    => 'yz_ibox_address_bg_left',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'background right', 'youzer' ),
                'desc'  => __( 'gradient background color', 'youzer' ),
                'id'    => 'yz_ibox_address_bg_right',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'website Styling settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'background left', 'youzer' ),
                'desc'  => __( 'gradient background color', 'youzer' ),
                'id'    => 'yz_ibox_website_bg_left',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'background right', 'youzer' ),
                'desc'  => __( 'gradient background color', 'youzer' ),
                'id'    => 'yz_ibox_website_bg_right',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'phone number Styling settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'background left', 'youzer' ),
                'desc'  => __( 'gradient background color', 'youzer' ),
                'id'    => 'yz_ibox_phone_bg_left',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'background right', 'youzer' ),
                'desc'  => __( 'gradient background color', 'youzer' ),
                'id'    => 'yz_ibox_phone_bg_right',
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    }

}