<?php

/**
 * Repair Logy Pages
 */
function logy_repair_pages() {

    // Plugin Pages
    $pages = array();

    $pages[] = array(
        'title' => __( 'Login', 'youzer' ),
        'slug'  => 'login',
        'meta'  => '_logy_core',
        'pages' => 'logy_pages'
    );

    $pages[] = array(
        'title' => __( 'Password Reset', 'youzer' ),
        'slug'  => 'lost-password',
        'meta'  => '_logy_core',
        'pages' => 'logy_pages'
    );

    $pages[] = array(
        'title' => __( 'Complete Registration', 'youzer' ),
        'slug'  => 'complete-registration',
        'meta'  => '_logy_core',
        'pages' => 'logy_pages'
    );

    // Get Logy Pages
    $logy_pages = get_option( 'logy_pages' );

    foreach ( $pages as $page ) {
        $slug = $page['slug'];
        if ( ! isset( $logy_pages[ $slug ] ) ) {
            logy_add_new_plugin_page( $page );
        }
    }

}

add_action( 'init', 'logy_repair_pages' );

/**
 * Create New Plugin Page.
 */
function logy_add_new_plugin_page( $args ) {

    // Get Page Slug
    $slug = $args['slug'];

    // Check that the page doesn't exist already
    $is_page_exists = yz_get_post_id( 'page', $args['meta'], $slug );

    if ( $is_page_exists ) {

        if ( ! isset( $pages[ $slug ] ) ) {

            // init Array.
            $pages = get_option( $args['pages'] );

            // Get Page ID
            $page_id = yz_get_post_id( 'page', $args['meta'], $slug );

            // Add New Page Data.
            $pages[ $slug ] = $page_id;
            
            update_option( $args['pages'], $pages );
        }

        return false;
    }

    $user_page = array(
        'post_title'     => $args['title'],
        'post_name'      => $slug,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'post_author'    =>  1,
        'comment_status' => 'closed'
    );

    $post_id = wp_insert_post( $user_page );

    wp_update_post( array('ID' => $post_id, 'post_type' => 'page' ) );

    update_post_meta( $post_id, $args['meta'], $slug );

    // init Array.
    $pages = get_option( $args['pages'] );

    // Add New Page Data.
    $pages[ $slug ] = $post_id;

    if ( isset( $pages ) ) {
        update_option( $args['pages'], $pages );
    }

}

/**
 * Logy Options
 */
function logy_options( $option_id ) {

    $option_value = get_option( $option_id );

    // Filter Options.
    if ( has_filter( 'logy_edit_options' ) ) {
        $option_value = apply_filters( 'logy_edit_options', $option_id );
    }

    if ( ! isset( $option_value ) || empty( $option_value ) ) {
        $Yz_default_options = yz_standard_options();
        if ( isset( $Yz_default_options[ $option_id ] ) ) {
            $option_value = $Yz_default_options[ $option_id ];
        }
    }

    return $option_value;
}

/**
 * # Get Plugin Pages
 */
function logy_pages( $request_type = null, $id = null ) {

    // Get pages.
    $logy_pages = logy_options( 'logy_pages' );

    // Switch Key <=> Values
    if ( 'ids' == $request_type ) {
        $pages_ids = array_flip( $logy_pages );
        return $pages_ids;
    }

    return $logy_pages;
}

/**
 * # Check if it's a Logy Plugin Page
 */
function is_logy_page() {
    // Get Pages By ID's
    $pages = logy_pages( 'ids' );
    // check if its our plugin page.
    if ( is_page() && isset( $pages[ get_the_ID() ] ) ) {
        return true;
    }
    return false;
}

/**
 * # Check for current page.
 */
function logy_is_page( $page ) {
   
    if ( 'register' == $page || 'activate' == $page ) {
        // Get Buddypress Pages.
        $bp_pages = get_option( 'bp-pages' );
        // Get Page ID.
        if ( ! isset( $bp_pages[ $page ] ) ) {
            return false;
        }
    }

    if ( is_page( logy_page_id( $page ) ) ) {
        return true;
    }

    return false;
}

/**
 * # Get Page Template.
 */
function logy_template( $page_template ) {
    // check if its logy plugin page
    if ( is_logy_page() ) {
		// Print Template
		return LOGY_PATH . 'includes/public/templates/logy-template.php';
    }
    return $page_template;
}

add_filter( 'page_template', 'logy_template' );

/**
 * # Get Page Shortcode.
 */
function logy_get_page_shortcode( $page_id = null ) {

    // Get Plugin Pages.
    $pages = array_flip( logy_options( 'logy_pages' ) );

    // Get Page Name.
    $page = $pages[ $page_id ];

    // Set Shortcode.
    $shortcode = '[logy_' . str_replace( '-', '_', $page ) . '_page]';

    return $shortcode;
}

/**
 * # Get Page URL.
 */
function logy_page_url( $page_name, $user_id = null ) {

	// Get Page Data
    $page_id = logy_page_id( $page_name );

    // Get Page Url.
    $page_url = trailingslashit( get_permalink( $page_id ) );

	// Return Page Url.
    return $page_url;

}

/**
 * # Get Page ID.
 */
function logy_page_id( $page ) {

    // Get Logy Pages.
    $pages = get_option( 'logy_pages' );

    $page_id = isset( $pages[ $page ] ) ? $pages[ $page ] : null;

    if ( 'register' == $page || 'activate' == $page ) {
        // Get Buddypress Pages.
        $bp_pages = get_option( 'bp-pages' );
        // Get Page ID.
        $page_id = isset( $bp_pages[ $page ] ) ? $bp_pages[ $page ] : false;
    }
    
    return $page_id;
}

/**
 * Get Wordpress Pages
 */
function logy_get_pages() {

    // Set Up Variables
    $pages    = array();
    $wp_pages = get_pages();

    foreach ( $wp_pages as $page ) {
        $pages[ $page->ID ] = sprintf( __( '%1s ( ID : %2d )','youzer' ), $page->post_title, $page->ID );
    }

    return $pages;
}

/**
 * # Class Generator.
 */
function logy_generate_class( $classes ) {
    // Convert Array to String.
    return implode( ' ' , array_filter( $classes ) );
}

/**
 * # Form Messages.
 */
add_action( 'logy_panel_after_form', 'logy_form_messages' );

function logy_form_messages() {

    ?>

    <!-- Dialog -->
    <div class="quicket-dialog"></div>
    <div class="klabs-form-msg">
        <div id="klabs-action-message"></div>
        <div id="klabs-wait-message">
            <div class="klabs_msg wait_msg">
                <div class="klabs-msg-icon">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <span><?php _e( 'Please wait ...', 'youzer' ); ?></span>
            </div>
        </div>
    </div>

    <?php

}

/**
 * Popup Dialog Message
 */
function logy_popup_dialog( $type = null ) {

    // Init Alert Types.
    $alert_types = array( 'reset_tab', 'reset_all' );

    // Get Dialog Class.
    $form_class = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? 'alert' : 'error';
    
    // Get Dialog Name.
    $form_type  = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? $type : 'error';

    ?>

    <div id="klabs_popup_<?php echo $form_type; ?>" class="klabs-popup klabs-<?php echo $form_class; ?>-popup">
        <div class="klabs-popup-container">
            <div class="klabs-popup-msg"><?php logy_get_dialog_msg( $form_type ); ?></div>
            <ul class="klabs-buttons"><?php logy_get_dialog_buttons( $form_type ); ?></ul>
            <i class="fas fa-times klabs-popup-close"></i>
        </div>
    </div>

    <?php
}

/**
 * Get Pop Up Dialog Buttons
 */
function logy_get_dialog_buttons( $type = null ) {

    // Get Cancel Button title.
    $confirm = __( 'confirm', 'youzer' );
    $cancel  = ( 'error' == $type ) ? __( 'Got it!', 'youzer' ) : __( 'cancel', 'youzer' );

    if ( 'reset_all' == $type ) : ?>
        <li>
            <a class="klabs-confirm-popup klabs-confirm-reset" data-reset="all"><?php echo $confirm; ?></a>
        </li>
    <?php elseif ( 'reset_tab' == $type ) : ?>
        <li>
            <a class="klabs-confirm-popup klabs-confirm-reset" data-reset="tab"><?php echo $confirm; ?></a>
        </li>
    <?php endif; ?>

    <li><a class="klabs-close-popup"><?php echo $cancel; ?></a></li>

    <?php
}

/**
 * Get Pop Up Dialog Message
 */
function logy_get_dialog_msg( $type = null ) {

    if ( 'reset_all' == $type ) : ?>

    <span class="dashicons dashicons-warning"></span>
    <h3><?php _e( 'Are you sure you want to reset all the settings?', 'youzer' ); ?></h3>
    <p><?php _e( 'Be careful! this will reset all the plugin settings.', 'youzer' ); ?></p>

    <?php elseif ( 'reset_tab' == $type ) : ?>

    <span class="dashicons dashicons-warning"></span>
    <h3><?php _e( 'Are you sure you want to do this ?', 'youzer' ); ?></h3>
    <p><?php _e( 'Be careful! this will reset all the current tab settings.', 'youzer' ); ?></p>

    <?php elseif ( 'error' == $type ) : ?>

    <i class="fas fa-exclamation-triangle"></i>
    <h3><?php _e( 'Oops!', 'youzer' ); ?></h3>
    <div class="klabs-msg-content"></div>

    <?php endif;

}  

/**
 * Edit Navigation Menu
 */
function logy_edit_nav_menu( $items, $args ) {

    // Set up Array's.
    $forms_pages = array( logy_page_id( 'register' ), logy_page_id( 'lost-password' ) );

    foreach( $items as $key => $item ) {

        // if user logged-in change the Login Page title to Logout.
        if ( $item->object_id == logy_page_id( 'login' ) && is_user_logged_in() ) {
            $item->url   = wp_logout_url();
            $item->title = __( 'Logout', 'youzer' );
        }

        // if user is logged-in remove the register page from menu.
        if ( in_array( $item->object_id, $forms_pages ) && is_user_logged_in() ) {
            unset( $items[ $key ] );
        }

    }

    return $items;
}

add_filter( 'wp_nav_menu_objects', 'logy_edit_nav_menu', 10, 2 );

/**
 * Fix Url Path.
 */
function logy_fix_path( $url ) {
    $url = str_replace( '\\', '/', trim( $url ) );
    return ( substr( $url,-1 ) != '/' ) ? $url .= '/' : $url;
}

/**
 * # Get Post ID .
 */
function logy_get_post_id( $post_type, $key_meta , $meta_value ) {

    // Get Posts
    $posts = get_posts(
        array(
            'post_type'  => $post_type,
            'meta_key'   => $key_meta,
            'meta_value' => $meta_value
        )
    );

    if ( isset( $posts[0] ) && ! empty( $posts ) ) {
        return $posts[0]->ID;
    }

    return false;
}

/**
 * Get Arguments consedering default values.
 */
function logy_get_args( $pairs, $atts, $prefix = null ) {

    // Set Up Arrays
    $out  = array();
    $atts = (array) $atts;

    // Get Prefix Value.
    $prefix = $prefix ? $prefix . '_' : null;

    // Get Values.
    foreach ( $pairs as $name => $default ) {
        if ( array_key_exists(  $prefix . $name, $atts ) ) {
            $out[ $name ] = $atts[ $prefix . $name ];
        } else {
            $out[ $name ] = $default;
        }
    }

    return $out;
}

/**
 * # Get Image Url.
 */
function logy_get_img_url( $path = null ) {

    if ( ! empty( $path ) ) {
        $img_path = $path;
    } else {
        $img_path = LOGY_PA . 'images/default-img.png';
    }

    return $img_path;
}

/**
 * Check If Registration is Incomplete
 */
function is_registration_incomplete() {
    
    // Get User Session Data.
    $user_session_data = logy_user_session_data( 'get' );

    if ( ! empty( $user_session_data ) ) {
        return true;
    }

    return false;
}

/**
 * Check If Limit login is enabled.
 */
function is_limit_login_enabled() {

    // Get Limit Login Option
    $enabled = logy_options( 'logy_enable_limit_login' );

    // Check ?
    if ( 'on' == $enabled ) {
        return true;
    }

    return false;
}

/**
 * Hide Dashboard Admin Bar For Non Admins.
 */
function logy_hide_dashboard() {

    if ( current_user_can( 'administrator' ) ) {
        return false;
    }

    // Setup Variables.
    $hide_dashboard = logy_options( 'logy_hide_subscribers_dash' );

    if ( 'on' != $hide_dashboard ) {
        return false;
    }

    // Hide Admin Bar.
    if ( ! is_admin() && current_user_can( 'subscriber' ) ) {
        show_admin_bar( false );
    }

    // Hide Admin Dashboard.
    if ( is_admin() && current_user_can( 'subscriber' ) &&
        ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url() );
        exit;
    }

}

add_action( 'init', 'logy_hide_dashboard' );

/**
 * Login Form Short Code "[youzer_login]"; 
 */
function logy_login_shortcode( $attributes = null ) {

    if ( is_user_logged_in() ) {
        return false;
    }

    global $Logy;
    
    // Print Form
    echo '<div class="logy-login-shortcode">';
    $Logy->form->get_form( 'login', $attributes );
    echo '</div>';

}

add_shortcode( 'youzer_login', 'logy_login_shortcode' );

/**
 * Register Form ShortCode "[youzer_register]"; 
 */
function logy_register_shortcode( $attributes = null ) {

    if ( is_user_logged_in() ) {
        return false;
    }
        
    
    $bp = buddypress();

    // Init Step. 
    if ( empty( $bp->signup->step ) ) {
        $bp->signup->step = 'request-details';
    }   

    // Print Form
    echo '<div class="logy-register-shortcode">';
        require_once LOGY_TEMPLATE . 'members/register.php';
    echo '</div>';

}

add_shortcode( 'youzer_register', 'logy_register_shortcode' );

/**
 * Lost Password Form Short Code "[logy_lost_password]"; 
 */
function logy_lost_password_shortcode( $attributes = null ) {

    if ( is_user_logged_in() ) {
        return false;
    }
        
    global $Logy;

    // Print Form
    echo '<div class="logy-lost-password-shortcode">';
    $Logy->form->get_form( 'lost_password', $attributes );
    echo '</div>';

}

add_shortcode( 'youzer_lost_password', 'logy_lost_password_shortcode' );

/**
 * Get Wordpress Error Messages.
 */
function logy_get_error_messages( $messages ) {

    // Init Array.
    $errors = array();
    
    // Get Errors
    foreach ( $messages as $message ) {
        // code...
        $errors[] = logy_get_message( $message );
    }

    return $errors;

}

/**
 * Add Form Error Message.
 */
function logy_get_message( $content, $type = null ) {

    // Get Message Type.
    $type = ! empty( $type ) ? $type : 'error';
    
    // Get Message data.
    $error = array(
        'type'    => $type,
        'content' => $content
    );

    return $error;
}

/**
 * Cookie Name.
 */
function logy_message_cookie_name() {
    return apply_filters( 'logy_message_cookie_name', 'logy-message' );
}

/**
 * Cookie Name Type.
 */
function logy_message_type_cookie_name() {
    return apply_filters( 'logy_message_type_cookie_name', 'logy-message-type' );
}

/** Messages ******************************************************************/

/**
 * Add a feedback (error/success) message to the WP cookie so it can be displayed after the page reloads.
 *
 * @since 1.0.0
 *
 * @param string $message Feedback message to be displayed.
 * @param string $type    Message type. 'updated', 'success', 'error', 'warning'.
 *                        Default: 'success'.
 */
function logy_add_message( $message, $type = null ) {

    // Get Message Error.
    $type = ! empty( $type ) ? $type: 'error';

    // Get Message Content and serialize it.
    $message = serialize( $message );

    // Send the values to the cookie for page reload display.
    @setcookie( logy_message_cookie_name(), $message, time() + 60 * 60 * 24, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
    @setcookie( logy_message_type_cookie_name(), $type, time() + 60 * 60 * 24, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );

    // Get BuddyPress.

    global $Logy;
    /**
     * Send the values to the $bp global so we can still output messages
     * without a page reload
     */
    $Logy->template_message = $message;
    $Logy->template_message_type = $type;
}

/**
 * Set up the display of the 'template_notices' feedback message.
 *
 * Checks whether there is a feedback message in the WP cookie and, if so, adds
 * a "template_notices" action so that the message can be parsed into the
 * template and displayed to the user.
 *
 * After the message is displayed, it removes the message vars from the cookie
 * so that the message is not shown to the user multiple times.
 *
 * @since 1.1.0
 *
 */
function logy_core_setup_message() {

    // Get BuddyPress.
    global $Logy;

    $cookie_msg_name = logy_message_cookie_name();
    $cookie_msg_type = logy_message_type_cookie_name();

    if ( empty( $Logy->template_message ) && isset( $_COOKIE[ $cookie_msg_name ] ) ) {
        $Logy->template_message = stripslashes( $_COOKIE[ $cookie_msg_name ] );
    }

    if ( empty( $Logy->template_message_type ) && isset( $_COOKIE[ $cookie_msg_type ] ) ) {
        $Logy->template_message_type = stripslashes( $_COOKIE[ $cookie_msg_type ] );
    }

    add_action( 'logy_form_notices', 'logy_core_render_message' );

    if ( isset( $_COOKIE[ $cookie_msg_name ] ) ) {
        @setcookie( $cookie_msg_name, false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
    }

    if ( isset( $_COOKIE[ $cookie_msg_type ] ) ) {
        @setcookie( $cookie_msg_type, false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
    }
}

add_action( 'init', 'logy_core_setup_message', 0 );

/**
 * Render the 'template_notices' feedback message.
 *
 * The hook action 'template_notices' is used to call this function, it is not
 * called directly.
 *
 * @since 1.1.0
 */
function logy_core_render_message() {

    global $Logy;

    if ( ! isset( $Logy->template_message_type ) ) {
        return false;
    }

    $messages = unserialize( $Logy->template_message );

    if ( ! empty( $messages ) ) :

        $type = ! empty( $Logy->template_message_type ) ? $Logy->template_message_type : 'error';

        /**
         * Filters the 'template_notices' feedback message content.
         *
         * @since 1.5.5
         *
         * @param string $template_message Feedback message content.
         * @param string $type             The type of message being displayed.
         *                                 Either 'updated' or 'error'.
         */

        // Get Messages
        $messages = apply_filters( 'logy_core_render_messages', $messages, $type ); ?>

        <div class="logy-form-message logy-<?php echo esc_attr( $type ); ?>-msg">
            <?php foreach( $messages as $message ) : ?>
                <p><?php echo $message['content']; ?></p>
            <?php endforeach; ?>
            <?php do_action( 'logy_form_messages_list' );?>
        </div>

    <?php

        /**
         * Fires after the display of any template_notices feedback messages.
         *
         * @since 1.1.0
         */
        do_action( 'logy_core_render_message' );

    endif;
}

/**
 * is Ajax Login Enabled
 */
function logy_is_ajax_login_active() {

    // Check if Ajax Login is enabled.
    $ajax_login = yz_options( 'yz_enable_ajax_login' );

    if ( $ajax_login == 'on' ) {
        return true;
    }
    
    return false;
}

/**
 * Check if Login Popup is Enabled.
 */
function logy_is_login_popup_active() {

    // Check if Login Popup is enabled.
    $login_popup = yz_options( 'yz_enable_login_popup' );

    if ( $login_popup == 'on' ) {
        return true;
    }
    
    return false;
}

/**
 * is Ajax Login Enabled
 */
function logy_get_popop_login_form() {
   
    if ( is_user_logged_in() || ! logy_is_login_popup_active() ) {
        return false;
    }

    ?>

    <div class="yz-popup-login">
        <?php echo do_shortcode( '[youzer_login]' ); ?>
    </div>

    <?php
}

add_action( 'wp_footer', 'logy_get_popop_login_form' );

/**
 * is Ajax Login Enabled
 */
function yz_add_login_page_attribute( $atts, $item, $args ) {

    if ( is_user_logged_in() || ! logy_is_login_popup_active() ) {
        return $atts;
    }

    // Get Login Page ID.
    $login_page_id = logy_page_id( 'login' );

    if ( empty( $login_page_id ) ) {
        return $atts;
    }

    // Add Attribute.
    if ( $item->object_id == $login_page_id ) {
        $atts['data-show-youzer-login'] = 'true';
    }

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'yz_add_login_page_attribute', 10, 3 );

/**
 * # Register Public Scripts .
 */
function logy_public_scripts() {
    
    if ( is_user_logged_in() ){
        return false;
    }

    global $Youzer;

    // Main Css.
    wp_register_style( 'logy-style', LOGY_PA . 'css/logy.min.css', array( 'yz-opensans', 'yz-icons' ), $Youzer->version );

    // Call Style
    wp_enqueue_style( 'logy-style' );
}

add_action( 'wp_enqueue_scripts', 'logy_public_scripts' );