<?php

/**
 * # Wall Settings.
 */

function yz_wall_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_url_preview',
            'title' => __( 'enable url live preview', 'youzer' ),
            'desc'  => __( 'display url preview in the wall form', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Sticky Posts Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_sticky_posts',
            'title' => __( 'Enable Sticky Posts', 'youzer' ),
            'desc'  => __( 'allow admins to pin/unpin posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_groups_sticky_posts',
            'title' => __( 'Enable Groups Sticky Posts', 'youzer' ),
            'desc'  => __( 'allow admins to pin/unpin posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_activity_sticky_posts',
            'title' => __( 'Enable Activity Sticky Posts', 'youzer' ),
            'desc'  => __( 'allow admins to pin/unpin posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Filters Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_youzer_activity_filter',
            'title' => __( 'Enable Youzer Activity Filter', 'youzer' ),
            'desc'  => __( 'use youzer activity filter', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_filter_bar',
            'title' => __( 'Display Wall Filter', 'youzer' ),
            'desc'  => __( 'show wall filter bar', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_activity_directory_filter_bar',
            'title' => __( 'Display Activity Filter', 'youzer' ),
            'desc'  => __( 'show global activity page filter bar', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Posts Embeds Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_posts_embeds',
            'title' => __( 'Enable Posts Embeds', 'youzer' ),
            'desc'  => __( 'activate Embeds inside posts', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_comments_embeds',
            'title' => __( 'Enable Comments Embeds', 'youzer' ),
            'desc'  => __( 'activate Embeds inside comments', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Posts Buttons Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_posts_likes',
            'title' => __( 'Enable Likes', 'youzer' ),
            'desc'  => __( 'allow users to like posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_posts_deletion',
            'title' => __( 'Enable Deletion', 'youzer' ),
            'desc'  => __( 'enable posts delete button', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_posts_comments',
            'title' => __( 'Enable Comments', 'youzer' ),
            'desc'  => __( 'allow posts comments', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_posts_reply',
            'title' => __( 'Enable Comments Replies', 'youzer' ),
            'desc'  => __( 'allow posts comments replies', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Attachments Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'yz_attachments_max_nbr',
            'title' => __( 'Max Attachments Number', 'youzer' ),
            'desc'  => __( 'Slideshow and photos max number per post', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'yz_attachments_max_size',
            'title' => __( 'Max File Size', 'youzer' ),
            'desc'  => __( 'attachment max size by megabytes', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'yz_atts_allowed_images_exts',
            'title' => __( 'Image Extentions', 'youzer' ),
            'desc'  => __( 'allowed image extentions', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'yz_atts_allowed_videos_exts',
            'title' => __( 'Video Extentions', 'youzer' ),
            'desc'  => __( 'allowed video extentions', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'yz_atts_allowed_audios_exts',
            'title' => __( 'Audio Extentions', 'youzer' ),
            'desc'  => __( 'allowed audio extentions', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'yz_atts_allowed_files_exts',
            'title' => __( 'Files Extentions', 'youzer' ),
            'desc'  => __( 'allowed files extentions', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Posts Per Page Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'yz_profile_wall_posts_per_page',
            'title' => __( 'Profile - Posts Per Page', 'youzer' ),
            'desc'  => __( 'profile wall posts per page', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'yz_groups_wall_posts_per_page',
            'title' => __( 'Groups - Posts Per Page', 'youzer' ),
            'desc'  => __( 'groups wall posts per page', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'yz_activity_wall_posts_per_page',
            'title' => __( 'Activity - Posts Per Page', 'youzer' ),
            'desc'  => __( 'global activity wall posts per page', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Control Wall Posts Visibility', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_status',
            'title' => __( 'Status', 'youzer' ),
            'desc'  => __( 'enable status posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_photo',
            'title' => __( 'Photo', 'youzer' ),
            'desc'  => __( 'enable photo posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_slideshow',
            'title' => __( 'Slideshow', 'youzer' ),
            'desc'  => __( 'enable slideshow posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_link',
            'title' => __( 'Link', 'youzer' ),
            'desc'  => __( 'enable link posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_quote',
            'title' => __( 'Quote', 'youzer' ),
            'desc'  => __( 'enable quote posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_video',
            'title' => __( 'Video', 'youzer' ),
            'desc'  => __( 'enable video posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_audio',
            'title' => __( 'Audio', 'youzer' ),
            'desc'  => __( 'enable audio posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_file',
            'title' => __( 'File', 'youzer' ),
            'desc'  => __( 'enable file posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_new_cover',
            'title' => __( 'New Cover', 'youzer' ),
            'desc'  => __( 'enable new cover posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_new_avatar',
            'title' => __( 'new avatar', 'youzer' ),
            'desc'  => __( 'enable new avatar posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_new_member',
            'title' => __( 'new member', 'youzer' ),
            'desc'  => __( 'enable new registered member posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_friendship_created',
            'title' => __( 'Friendship Created', 'youzer' ),
            'desc'  => __( 'enable friendship created posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_friendship_accepted',
            'title' => __( 'Friendship Accepted', 'youzer' ),
            'desc'  => __( 'enable friendship accepted posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_created_group',
            'title' => __( 'Group Created', 'youzer' ),
            'desc'  => __( 'enable groupd created posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_joined_group',
            'title' => __( 'Group Joined', 'youzer' ),
            'desc'  => __( 'enable groupd joined posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_new_blog_post',
            'title' => __( 'New Blog Post', 'youzer' ),
            'desc'  => __( 'enable new blog posts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_new_blog_comment',
            'title' => __( 'New Blog Comment', 'youzer' ),
            'desc'  => __( 'enable new blog Comments', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_comments',
            'title' => __( 'Comments Post', 'youzer' ),
            'desc'  => __( 'enable post comments posts', 'youzer' ),
        )
    );
    
    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_wall_updated_profile',
            'title' => __( 'Updated Profile Post', 'youzer' ),
            'desc'  => __( 'enable updated profile posts', 'youzer' ),
        )
    );

    do_action( 'yz_wall_posts_visibility_settings' );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}