<?php

class YZ_Basic_Infos {

    function __construct() {
    }

    /**
     * # Basic Infos Widget Arguments.
     */
    function args() {

        // Get Widget Args
        $args = array(
            'display_title' => 'on',
            'widget_icon'   => 'fas fa-info',
            'widget_name'   => 'basic_infos',
            'widget_title'  => __( 'basic info', 'youzer' )
        );

        // Filter
        $args = apply_filters( 'yz_basic_infos_widget_args', $args );

        return $args;
    }
    
    /**
     * # Content.
     */
    function widget() {

        // Get User Data
        $basic_infos = yz_get_wp_profile_fields();

        ?>

        <div class="yz-infos-content">
            <?php

            foreach ( $basic_infos as $infos  ) {
                $value = yz_data( $infos['id'] );
                if ( $value ) {
                    echo "<div class='yz-info-item'>";
                    echo '<div class="yz-info-label">' . $infos['name'] . '</div>';
                    echo '<div class="yz-info-data">' . $value . '</div>';
                    echo '</div>';
                }
            }

            ?>
        </div>

        <?php
    }

    /**
     * # Main Infos Settings .
     */
    function profile_infos() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'profile information', 'youzer' ),
                'id'    => 'profile-infos',
                'icon'  => 'fas fa-cogs',
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'first name', 'youzer' ),
                'desc'  => __( 'type your first name', 'youzer' ),
                'id'    => 'first_name',
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'last name', 'youzer' ),
                'id'    => 'last_name',
                'desc'  => __( 'type your last name', 'youzer' ),
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'country', 'youzer' ),
                'id'    => 'user_country',
                'desc'  => __( 'type your country', 'youzer' ),
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'city', 'youzer' ),
                'desc'  => __( 'type your city', 'youzer' ),
                'id'    => 'user_city',
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Biographical Info', 'youzer' ),
                'desc'  => __( 'add your biography', 'youzer' ),
                'id'    => 'description',
                'type'  => 'textarea'
            ), true
        );

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Contact Infos Settings.
     */
    function contact_infos() {

      global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'contact information', 'youzer' ),
                'id'    => 'contact-infos',
                'icon'  => 'fas fa-envelope',
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'E-mail', 'youzer' ),
                'desc'  => __( 'add your email address', 'youzer' ),
                'id'    => 'email_address',
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Phone', 'youzer' ),
                'desc'  => __( 'add your phone number', 'youzer' ),
                'id'    => 'phone_nbr',
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Address', 'youzer' ),
                'desc'  => __( 'add your address', 'youzer' ),
                'id'    => 'user_address',
                'type'  => 'text'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'website', 'youzer' ),
                'desc'  => __( 'add your website url', 'youzer' ),
                'id'    => 'user_url',
                'type'  => 'text'
            ), true
        );

      $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Profile Picture Settings.
     */
    function profile_picture() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Profile Picture', 'youzer' ),
                'id'    => 'profile-picture',
                'icon'  => 'fas fa-user-circle',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="yz-uploader-change-item yz-change-avatar-item">';
        bp_get_template_part( 'members/single/profile/change-avatar' );
        echo '</div>';

        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * # User Capabilities Settings.
     */
    function user_capabilities() {

        global $Yz_Settings;
        
        do_action( 'bp_before_member_settings_template' );

        $Yz_Settings->get_field(
            array(
                'form_action'   => bp_displayed_user_domain() . bp_get_settings_slug() . '/capabilities/',
                'title'         => __( 'User Capabilities Settings', 'youzer' ),
                'form_name'     => 'account-capabilities-form',
                'submit_id'     => 'capabilities-submit',
                'button_name'   => 'capabilities-submit',
                'id'            => 'capabilities-settings',
                'icon'          => 'fas fa-wrench',
                'type'          => 'open',
            )
        );

        bp_get_template_part( 'members/single/settings/capabilities' );

        $Yz_Settings->get_field(
            array(
                'type' => 'close',
                'hide_action' => true,
                'submit_id'     => 'capabilities-submit',
                'button_name'   => 'capabilities-submit'
            )
        );
        
        do_action( 'bp_after_member_settings_template' );

    }

    /**
     * # Profile Fields Group Settings.
     */
    function group_fields() {

        global $Yz_Settings, $group;

        $group_data = BP_XProfile_Group::get(
            array( 'profile_group_id' => bp_get_current_profile_group_id() )
        );

        $Yz_Settings->get_field(
            array(
                'icon'  => yz_get_xprofile_group_icon( $group_data[0]->id ),
                'title' => $group_data[0]->name,
                'id'    => 'profile-picture',
                'type'  => 'open'
            )
        );

        bp_get_template_part( 'members/single/profile/edit' );

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Account Privacy Settings.
     */
    function account_privacy() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Account Privacy', 'youzer' ),
                'id'    => 'account-privacy',
                'icon'  => 'fas fa-user-secret',
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Private Account', 'youzer' ),
                'desc'  => __( "make you profile private, only friends can access.", 'youzer' ),
                'id'    => 'yz_enable_private_account',
                'type'  => 'checkbox',
                'std'   => 'off',
            ), true
        );

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Delete Account Settings.
     */
    function delete_account() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Delete Account', 'youzer' ),
                'id'    => 'delete-account',
                'icon'  => 'fas fa-trash-alt',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="yz-delete-account-item">';
        bp_get_template_part( 'members/single/settings/delete-account' );
        echo '</div>';

        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }    

    /**
     * # Export Account Data.
     */
    function export_data() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Export Data', 'youzer' ),
                'id'    => 'export-data',
                'icon'  => 'fas fa-download',
                'type'  => 'bpDiv'
            )
        );

        $export_data = __( 'Export Personal Data', 'youzer' );

        ?>

        <div class="uk-option-item yz-export-item">
            <div class="option-infos">
                <label class="option-title"><?php echo $export_data; ?></label>
                <p class="option-desc"><?php _e( 'export your profile information, profile widgets information.' ); ?></p>              </div>
            <div class="option-content">
                <div class="ukai-button-item">
                    <a href="<?php echo yz_get_current_page_url() . '?yz-export-data=true'; ?>"><i class="fas fa-download"></i><?php echo $export_data; ?></a>
                </div>
            </div>
        </div>

        <?php

        echo '</div>';

        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }


    /**
     * # Profile Notifications Settings.
     */
    function notifications_settings() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Notifications Settings', 'youzer' ),
                'id'    => 'notifications-settings',
                'icon'  => 'fas fa-bell',
                'type'  => 'open'
            )
        );

        // # Activity Notifications.

        if ( bp_is_active( 'activity' ) ) :
                
            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Mentions Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a member mentions me in a post', 'youzer' ),
                    'id'    => 'yz_notification_activity_new_mention',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Replies Notifications', 'youzer' ),
                    'desc'  => __( "Email me when a member replies to a post or comment i have posted", 'youzer' ),
                    'id'    => 'yz_notification_activity_new_reply',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

        endif;

        // # Messages Notifications.

        if ( bp_is_active( 'messages' ) ) :
            
            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Messages Notifications', 'youzer' ),
                    'desc'  => __( "Email me when a member sends me a new message", 'youzer' ),
                    'id'    => 'yz_notification_messages_new_message',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

        endif;

        // # Friends Notifications.

        if ( bp_is_active( 'friends' ) ) :

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Friendship Requested Notifications', 'youzer' ),
                    'desc'  => __( "Email me when a member sends me a friendship request", 'youzer' ),
                    'id'    => 'yz_notification_friends_friendship_request',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Friendship Accepted Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a member accepts my friendship request', 'youzer' ),
                    'id'    => 'yz_notification_friends_friendship_accepted',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

        endif;

        // # Groups Notifications.

        if ( bp_is_active( 'groups' ) ) :

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Group Invitations Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a member invites me to join a group', 'youzer' ),
                    'id'    => 'yz_notification_groups_invite',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Group information Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when A Group information is updated', 'youzer' ),
                    'id'    => 'yz_notification_groups_group_updated',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Group Admin Promotion Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when i promoted to a group administrator or moderator', 'youzer' ),
                    'id'    => 'yz_notification_groups_admin_promotion',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Join Group Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a member requests to join a private group for which i am an admin', 'youzer' ),
                    'id'    => 'yz_notification_groups_membership_request',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Group Membership Request Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when my request to join a group has been approved or denied', 'youzer' ),
                    'id'    => 'yz_notification_membership_request_completed',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

        endif;

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Profile Cover Settings.
     */
    function profile_cover() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Profile Cover', 'youzer' ),
                'id'    => 'profile-cover',
                'icon'  => 'fas fa-camera-retro',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="yz-uploader-change-item yz-change-cover-item">';
        bp_get_template_part( 'members/single/profile/change-cover-image' );
        echo '</div>';
        
        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * # Password Settings.
     */
    function change_password() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'change password', 'youzer' ),
                'id'    => 'change-password',
                'icon'  => 'fas fa-lock',
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'New Password', 'youzer' ),
                'desc'  => __( 'type your new password', 'youzer' ),
                'id'    => 'new_password',
                'type'  => 'password' ), true
            );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Confirm Password', 'youzer' ),
                'desc'  => __( 'confirm your new password', 'youzer' ),
                'id'    => 'confirm_password',
                'type'  => 'password'
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Current Password', 'youzer' ),
                'desc'  => __( 'enter your current password', 'youzer' ),
                'id'    => 'current_password',
                'type'  => 'password'
            ), true
        );

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }
}
