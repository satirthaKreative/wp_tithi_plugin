<?php

class YZ_Custom_Tabs {

	protected $youzer;

    public function __construct() {

		global $Youzer;

    	$this->youzer = &$Youzer;

		add_action( 'bp_init', array( &$this, 'hide_custom_tab_sidebar' ) );
    }

    /**
     * Custom Tab Hide Sidebar
     */
    function hide_custom_tab_sidebar() {
        
        if ( ! bp_is_user() ) {
            return false;
        }

    	// Get Curren Tab Name.
    	$current_slug = $this->get_current_tab_name();

    	if ( ! yz_is_custom_tab( $current_slug ) ) {
    		return false;
    	}

		// Get Header Layout.
		$header_layout = yz_get_profile_layout();

        // Get Custom Tabs
        $data = $this->get_all_data( $current_slug );

		if ( 'yz-horizontal-layout' == $header_layout && 'false' == $data['display_sidebar'] ) {

			// Remove Old Structure.
	    	remove_action(
	    		'yz_profile_main_content',
	    		array( &$this->youzer->profile, 'profile_main_content' )
	    	);

	    	// Add Wild Content.
			add_action( 'yz_profile_main_content', array( &$this, 'tab' ) );

		}

    }

	/**
	 * Tab Core
	 */
	function tab() {

        // Hide sidebar if profile is private.
        if ( ! yz_display_profile() ) {
            yz_private_account_message();
            return false;
        }

		$this->youzer->tabs->core( $this->get_args() );
	}

	/**
	 * # Tab Content
	 */
	function tab_content() {

        // Get Custom Tabs
        $data = $this->get_all_data( $this->get_current_tab_name() );

        // Display Widget.
        echo "<div class='yz-custom-tab'>";
        echo apply_filters( 'the_content', urldecode( $data['content'] ) );
        echo "</div>";

	}

    /**
     * # Custom Tabs Settings.
     */
    function settings() {

        global $Youzer_Admin, $Yz_Settings;

        $modal_args = array(
            'id'        => 'yz-custom-tabs-form',
            'title'     => __( 'create new tab', 'youzer' ),
            'button_id' => 'yz-add-custom-tab'
        );

        // Get New Custom Tabs Form.
        $Youzer_Admin->panel->modal( $modal_args, array( &$this, 'form' ) );

        // Get Custom Tabs List.
        $this->get_tabs_list();
        
    }

    /**
     * # Create New Custom Widgets Form.
     */
    function form() {

        // Get Data.
        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'type'  => 'openDiv',
                'class' => 'yz-custom-tabs-form'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'show for non logged-in', 'youzer' ),
                'desc'       => __( 'display tab for non logged-in users', 'youzer' ),
                'id'         => 'yz_tab_display_nonloggedin',
                'type'       => 'checkbox',
                'std'        => 'on',
                'no_options' => true
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'        => __( 'tab title', 'youzer' ),
                'desc'         => __( 'add tab title', 'youzer' ),
                'id'           => 'yz_tab_title',
                'type'         => 'text',
                'no_options'   => true
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'Tab type', 'youzer' ),
                'id'         => 'yz_tab_type',
                'desc'       => __( 'choose the tab type', 'youzer' ),
                'std'        => 'link',
                'no_options' => true,
                'type'       => 'radio',
                'opts'       => array(
                    'link'    => __( 'link', 'youzer' ),
                    'shortcode' => __( 'shortcode', 'youzer' )
                ),
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'tab Link', 'youzer' ),
                'id'         => 'yz_tab_link',
                'desc'       => __( 'You can use the tag {username} in the link and it will be replaced by the displayed profile user id.', 'youzer' ),
                'class'		 => 'yz-custom-tabs-link-item',
                'type'       => 'text',
                'no_options' => true
            )
        );


        // Tabs ShortCode Options
        $Yz_Settings->get_field(
            array(
                'type'  => 'openDiv',
                'class' => 'yz-custom-tabs-shortcode-items'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'display page sidebar', 'youzer' ),
                'desc'       => __( 'show page sidebar works only on horizontal layout', 'youzer' ),
                'id'         => 'yz_tab_display_sidebar',
                'type'       => 'checkbox',
                'std'        => 'on',
                'no_options' => true
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'tab content', 'youzer' ),
                'id'         => 'yz_tab_content',
                'desc'       => __( 'paste your shortcode or any html code', 'youzer' ),
                'type'       => 'textarea',
                'no_options' => true
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

        // Add Hidden Input
        $Yz_Settings->get_field(
            array(
                'id'         => 'yz_custom_tabs_form',
                'type'       => 'hidden',
                'class'      => 'yz-keys-name',
                'std'        => 'yz_custom_tabs',
                'no_options' => true
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

    }

    /**
     * Get Custom Tabs List
     */
    function get_tabs_list() {

        global $Yz_Settings;

        // Get Custom Tabs Items
        $yz_custom_tabs = yz_options( 'yz_custom_tabs' );

        // Next Custom Tab ID
        $next_id = yz_options( 'yz_next_custom_tab_nbr' );
        $yz_nextTab = ! empty( $next_id ) ? $next_id : '1';
        ?>

        <script> var yz_nextTab = <?php echo $yz_nextTab; ?>; </script>

        <div class="yz-custom-section">
            <div class="yz-cs-head">
                <div class="yz-cs-buttons">
                    <button class="yz-md-trigger yz-custom-tabs-button" data-modal="yz-custom-tabs-form">
                        <i class="fas fa-plus-circle"></i>
                        <?php _e( 'add new tab', 'youzer' ); ?>
                    </button>
                </div>
            </div>
        </div>

        <ul id="yz_custom_tabs" class="yz-cs-content">

        <?php

            // Show No Tabs Found .
            if ( empty( $yz_custom_tabs ) ) {
                global $Yz_Translation;
                $msg = $Yz_Translation['no_custom_tabs'];
                echo "<p class='yz-no-content yz-no-custom-tabs'>$msg</p></ul>";
                return false;
            }

            foreach ( $yz_custom_tabs as $tab => $data ) :

                // Get Tab Slug 
                $slug = yz_get_custom_tab_slug( $data['title'] );

                // Get Widget Data.
                $link = $data['link'];
                $type = $data['type'];
                $title = $data['title'];
                $content = $data['content'];
                $display_sidebar  = $data['display_sidebar'];
                $icon = yz_get_profile_nav_menu_icon( $slug );
                $display_nonloggedin  = $data['display_nonloggedin'];

                // Get Field Name.
                $name = "yz_custom_tabs[$tab]";

                ?>

                <!-- Tab Item -->
                <li class="yz-custom-tab-item" data-tab-name="<?php echo $tab; ?>">
                    <h2 class="yz-custom-tab-name">
                        <i class="yz-custom-tab-icon <?php echo $icon; ?>"></i>
                        <span><?php echo $title; ?></span>
                    </h2>
                    <input type="hidden" name="<?php echo $name; ?>[link]" value="<?php echo $link; ?>">
                    <input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $type; ?>">
                    <input type="hidden" name="<?php echo $name; ?>[title]" value="<?php echo $title; ?>">
                    <input type="hidden" name="<?php echo $name; ?>[content]" value="<?php echo $content; ?>">
                    <input type="hidden" name="<?php echo $name; ?>[display_sidebar]" value="<?php echo $display_sidebar; ?>">
                    <input type="hidden" name="<?php echo $name; ?>[display_nonloggedin]" value="<?php echo $display_nonloggedin; ?>">
                    <a class="yz-edit-item yz-edit-custom-tab"></a>
                    <a class="yz-delete-item yz-delete-custom-tab"></a>
                </li>

            <?php endforeach; ?>

        </ul>

        <?php
    }

    /**
     * Get Custom Widget data.
     */
    function get_all_data( $tab_name ) {
        $tabs = yz_options( 'yz_custom_tabs' );
        return $tabs[ $tab_name ];
    }

    /**
     * Get Args.
     */
    function get_args() {

        // Get Custom Tabs
        $data = $this->get_all_data( $this->get_current_tab_name() );

		// Get Custom Tab Args.
		$args = array(
			'tab_name'    => 'custom',
			'tab_title'   => $data['title'],
            'tab_slug'	  => yz_get_custom_tab_slug( $data['title'] )
		);

		return $args;
    }

    /**
     * Get Current Tab name.
     */
    function get_current_tab_name() {

    	// Get Current Slug.
    	$current_slug = bp_current_action();

    	// Get Tab Name.
    	$tab_name = yz_get_tab_name_by_slug( $current_slug );

    	return $tab_name;

    }

    /**
     * Get Custom Tab data.
     */
    function get_tab_data( $tab_name, $data_type ) {
        $tabs = yz_options( 'yz_custom_tabs' );
        return $tabs[ $tab_name ][ $data_type ];
    }
}