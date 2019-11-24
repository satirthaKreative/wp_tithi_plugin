<?php

class Logy {

    protected $plugin_slug;

    protected $version;

    public function __construct() {

        // Init Data.
        $this->version = '1.0.0';
        $this->plugin_slug = 'logy-slug';

        // Load Functions.
        $this->init_logy();
        
        // Load Global Variables.
        $this->logy_globals();

    }

    /**
     * # Init Logy Files
     */
    private function init_logy() {

        require_once LOGY_CORE . 'functions/logy-general-functions.php';
        require_once LOGY_CORE . 'functions/logy-social-functions.php';
    
        // General Functions
        require_once LOGY_CORE . 'functions/logy-admin-functions.php';
        require_once LOGY_CORE . 'functions/logy-bp-functions.php';

        // Classes
        require_once LOGY_CORE . 'class-logy-widgets.php';
        require_once LOGY_CORE . 'class-logy-form.php';
        require_once LOGY_CORE . 'class-logy-query.php';
        require_once LOGY_CORE . 'class-logy-social.php';
        require_once LOGY_CORE . 'class-logy-rewrite.php';
        require_once LOGY_CORE . 'class-logy-styling.php';
        require_once LOGY_CORE . 'class-logy-widgets.php';
        require_once LOGY_CORE . 'class-logy-limit.php';

        // Include Main Pages
        require_once LOGY_CORE . 'pages/logy-login.php';
        require_once LOGY_CORE . 'pages/logy-register.php';
        require_once LOGY_CORE . 'pages/logy-lost-password.php';
        require_once LOGY_CORE . 'pages/logy-complete-registration.php';

        // Init Classes
        $this->login          = new Logy_Login();
        $this->form           = new Logy_Form();
        $this->social         = new Logy_Social();
        $this->query          = new Logy_Query();
        $this->limit          = new Logy_Limit();
        $this->rewrite        = new Logy_Rewrite();
        $this->styling        = new Logy_Styling();
        $this->register       = new Logy_Register();
        $this->lost_password  = new Logy_Lost_Password();
        $this->complete_registration  = new Logy_Complete_Registration();

    }
    
    /**
     * # Logy Global Variables .
     */
    private function logy_globals() {

        global $wpdb, $Logy_Translation, $Logy_Settings;
        
        // Translation Data.
        $Logy_Translation = array(
            'try_later'             => __( 'Something went wrong, please try again later.', 'youzer' ),
            'reset_error'           => __( 'An Error Occurred While Resetting The Options !!', 'youzer' ),
            'reset_dialog_title'    => __( 'Resetting Options Confirmation', 'youzer' ),
            'required_fields'       => __( 'all fields are required !', 'youzer' ),
            'empty_field'           => __( 'Field Name Is Empty !', 'youzer' ),
            'empty_options'         => __( 'Options are empty !', 'youzer' ),
            'processing'            => __( 'processing... !', 'youzer' ),
            'save_changes'          => __( 'save changes', 'youzer' ),
            'error_msg'             => __( 'Ops, Error !', 'youzer' ),
            'photo_link'            => __( 'photo link', 'youzer' ),
            'success_msg'           => __( "Success !", 'youzer' ),
            'edit_item'             => __( 'edit item', 'youzer' ),
            'got_it'                => __( 'got it!', 'youzer' ),
            'done'                  => __( 'save', 'youzer' ),

            // Passing Data.
            'default_img' => LOGY_PA . 'images/default-img.png',
            'ajax_url'    => admin_url( 'admin-ajax.php' )

        );

    }

}