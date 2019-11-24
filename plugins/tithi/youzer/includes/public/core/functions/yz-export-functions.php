<?php

/**
 * Get All User Youzer Fields
 */
function yz_user_youzer_fields() {

    $fields = array(
        'profile' => array(
            'title' => __( 'Profile', 'youzer' ),
            'fields' => array(
                'user_firstname' => array(
                    'title' => __( 'First Name', 'youzer' ),
                ),
                'user_lastname' => array(
                    'title' => __( 'Last Name', 'youzer' ),
                ),
                'user_nicename' => array(
                    'title' => __( 'Nice Name', 'youzer' ),
                ),
                'user_login' => array(
                    'title' => __( 'User Login', 'youzer' ),
                ),
                'user_email' => array(
                    'title' => __( 'Email', 'youzer' ),
                ),
                'user_url' => array(
                    'title' => __( 'Website', 'youzer' ),
                ),
                'user_city' => array(
                    'title' => __( 'City', 'youzer' ),
                ),
                'user_country' => array(
                    'title' => __( 'Country', 'youzer' ),
                ),
                'phone_nbr' => array(
                    'title' => __( 'Phone Number', 'youzer' ),
                ),
                'user_address' => array(
                    'title' => __( 'Address', 'youzer' ),
                ),
                'user_registered' => array(
                    'title' => __( 'User Registration Date', 'youzer' ),
                ),
                'user_description' => array(
                    'title' => __( 'Description', 'youzer' ),
                ),
            )
        ),
        'instagram' => array(
            'id' => 'wg_instagram_account_token',
            'title' => __( 'User Instagram', 'youzer' )
        ),
        'social_networks' => array(
            'id' => 'yz_networks',
            'title' => __( 'Social Networks', 'youzer' )
        ),
        'flickr' => array(
            'id' => 'wg_flickr_account_id',
            'title' => __( 'User Flickr', 'youzer' )
        ),
        'skills' => array(
            'id' => 'youzer_skills',
            'title' => __( 'User Skills', 'youzer' )
        ),
        'services' => array(
            'id' => 'youzer_services',
            'title' => __( 'User Services', 'youzer' )
        ),
        'slideshow' => array(
            'id' => 'youzer_slideshow',
            'title' => __( 'User Slideshow', 'youzer' )
        ),
        'portfolio' => array(
            'id' => 'youzer_portfolio',
            'title' => __( 'User Portfolio', 'youzer' )
        ),
        'post' => array(
            'title' => __( 'User Post Widget', 'youzer' ),
            'fields' => array(
                'yz_profile_wg_post_id' => array(
                    'title' => __( 'Post ID', 'youzer' ),
                ),
                'wg_post_type' => array(
                    'title' => __( 'Post Type', 'youzer' ),
                )
            )
        ),
        'video' => array(
            'title' => __( 'User Video Widget', 'youzer' ),
            'fields' => array(
                'wg_video_title' => array(
                    'title' => __( 'title', 'youzer' ),
                ),
                'wg_video_desc' => array(
                    'title' => __( 'Description', 'youzer' ),
                ),
                'wg_video_url' => array(
                    'title' => __( 'Url', 'youzer' ),
                )
            )
        ),
        'about_me' => array(
            'title' => __( 'User About me Widget', 'youzer' ),
            'fields' => array(
                'wg_about_me_photo' => array(
                    'title' => __( 'Photo', 'youzer' ),
                ),
                'wg_about_me_title' => array(
                    'title' => __( 'Title', 'youzer' ),
                ),
                'wg_about_me_desc' => array(
                    'title' => __( 'Description', 'youzer' ),
                ),
                'wg_about_me_bio' => array(
                    'title' => __( 'Biography', 'youzer' ),
                )
            )
        ),
        'quote' => array(
            'title' => __( 'User Quote Widget', 'youzer' ),
            'fields' => array(
                'wg_quote_owner' => array(
                    'title' => __( 'Owner', 'youzer' ),
                ),
                'wg_quote_txt' => array(
                    'title' => __( 'Text', 'youzer' ),
                ),
                'wg_quote_img' => array(
                    'title' => __( 'Cover', 'youzer' ),
                ),
                'wg_quote_use_bg' => array(
                    'title' => __( 'Use Quote Cover?', 'youzer' ),
                )
            )
        ),
        'link' => array(
            'title' => __( 'User Link Widget', 'youzer' ),
            'fields' => array(
                'wg_link_url' => array(
                    'title' => __( 'URL', 'youzer' ),
                ),
                'wg_link_txt' => array(
                    'title' => __( 'Text', 'youzer' ),
                ),
                'wg_link_img' => array(
                    'title' => __( 'Cover', 'youzer' ),
                ),
                'wg_link_use_bg' => array(
                    'title' => __( 'Use Link Cover?', 'youzer' ),
                )
            )
        ),
        'project' => array(
            'title' => __( 'User Project Widget', 'youzer' ),
            'fields' => array(
                'wg_project_title' => array(
                    'title' => __( 'Title', 'youzer' ),
                ),
                'wg_project_desc' => array(
                    'title' => __( 'Description', 'youzer' ),
                ),
                'wg_project_type' => array(
                    'title' => __( 'Type', 'youzer' ),
                ),
                'wg_project_thumbnail' => array(
                    'title' => __( 'Thumbnail', 'youzer' ),
                ),
                'wg_project_link' => array(
                    'title' => __( 'Link', 'youzer' ),
                ),
                'wg_project_categories' => array(
                    'title' => __( 'Categories', 'youzer'),
                ),
                'wg_project_tags' => array(
                    'title' => __( 'Tags', 'youzer'),
                ),
            )
        ),
        
    );
    return apply_filters( 'yz_export_fields', $fields );
}

/**
 * Get User Personal Data.
 */
function yz_get_user_exported_data() {
    
    ob_start();

    // Get User Data.
    $user_data = yz_user_youzer_fields();
    $page_title = __( 'Personal Data Export', 'youzer' );
    ?>
    <!doctype html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title><?php echo $page_title; ?></title>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans%3A400%2C600" type="text/css" media="all" />

    <style type="text/css">
        
        body {
            padding: 20px 100px;
            font-family: Open sans, sans-serif;
            background-color: #eaeaea;
        }

        .yz-export-table {
            background-color: #fff; 
            color: #8b8b8b;
            font-size: 13px;
            margin-bottom: 35px;
            width: 80%;
        }

        .yz-export-table td {
            line-height: 24px;
        }

        .yz-export-table td,
        .yz-export-table th {
            padding: 15px;
        }
        
        .yz-export-table th {
            width: 20%;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .yz-export-table a {
            color: #43a3d0;
            font-weight: 600;
        }
        
        .yz-export-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .yz-export h2 {
            color: #898989;
            font-size: 18px;
            margin-bottom: 25px;
            text-transform: capitalize;
        }
        
        .yz-export .yz-table-trtd {
            color: #fff;
            padding: 15px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            background-color: #44b9f1;
        }

        .yz-export .no-data-found {
            color: #898989;
            font-size: 13px;
            font-weight: 600;
        }

    </style>
        </head>
        <body>

    <?php
    
    global $YZ_upload_url;

    // Get No Daata HTML.
    $no_data_found = '<span class="no-data-found">' . __( 'No Data Found.', 'youzer' ) . '</span>';

    echo '<h1>' . $page_title . '</h1>';
    echo '<div class="yz-export">'; 

    foreach ( $user_data as $option_id => $data ) { 

        echo '<table class="yz-export-table"><tbody>';

        ?>

        <h2><?php echo $data['title']; ?></h2>

        <?php

        switch ( $option_id ) {

            case 'instagram':

            $instagram_data = yz_data( 'wg_instagram_account_user_data' );
            
            if ( empty( $instagram_data ) ) {
                echo $no_data_found;
                break;
            }

            foreach ( $instagram_data as $key => $value ) {

                if ( empty( $value ) || $key == '__PHP_Incomplete_Class_Name' ) {
                    continue;
                }

                echo '<tr>';
                echo '<th>' . $key . '</th>';
                echo '<td>' . $value . '</td>';
                echo '</tr>';
            }

                break;
            
            case 'flickr':

                // Get Flickr
                $flickr = yz_data( 'wg_flickr_account_id' );

                if ( empty( $flickr ) ) {
                    echo $no_data_found;
                    break;
                }

                echo '<tr>';
                echo '<th>' . __( 'Account ID', 'youzer' ) . '</th>';
                echo '<td>' . $flickr . '</td>';
                echo '</tr>';

                break;

            case 'skills':
            case 'services':

                // Get Data.
                $widgets = yz_data( $data['id'] );

                if ( empty( $widgets ) ) {
                    echo $no_data_found;
                    break;
                }

                $i = 1;

                foreach ( $widgets as $key => $wg_data ) {
                    echo '<tr><td  colspan="3" class="yz-table-trtd">#'. $i . '</td></tr>';
                    foreach ( $wg_data as $key => $val ) {
                        echo '<tr>';
                        echo '<th>' . $key . '</th>';
                        echo '<td>' . $val . '</td>';
                        echo '</tr>';
                    }

                    $i++;

                }

                break;

            case 'portfolio':
            case 'slideshow':

                // Get Data.
                $widgets = yz_data( $data['id'] );
                
                if ( empty( $widgets ) ) {
                    echo $no_data_found;
                    break;
                }

                $i = 1;

                foreach ( $widgets as $key => $wg_data ) {

                    echo '<tr><td  colspan="3" class="yz-table-trtd">#'. $i . '</td></tr>';
                    
                    foreach ( $wg_data as $img_key => $img_value) {
                        
                        if ( empty( $img_value ) ) {
                            continue;
                        }

                        $val = ( $img_key == 'original' || $img_key == "thumbnail" ) ? $YZ_upload_url. $img_value: $img_value;

                        echo '<tr>';
                        echo '<th>' . $img_key . '</th>';
                        echo '<td><a href=" ' . $val . '" >' . $val . '</a></td>';
                        echo '</tr>';
                    }
                    
                    $i++;

                }

                break;

            case 'social_networks':
                
                if ( ! is_user_have_networks( bp_displayed_user_id() ) ) {
                    echo $no_data_found;
                    break;
                }

                // Get Networks !
                $social_networks = yz_options( 'yz_social_networks' );

                foreach ( $social_networks as $network => $net_data ) {

                    // Get Widget Data
                    $name = sanitize_text_field( $net_data['name'] );
                    $link = esc_url( yz_data( $network ) );

                    if ( $link ) {
                        
                        echo '<tr>';
                        echo '<th>' . $name . '</th>';
                        echo '<td><a href=" ' . $link . '" >' . $link . '</a></td>';
                        echo '</tr>';
                    }

                }


                break;
            
            default:
                if ( isset( $data['fields'] ) ) {
                    yz_export_user_data( $data['fields'] );
                }
                break;
        }

        echo '</tbody></table>';
    }

    echo '</div></body></html>';

    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode( 'yz_user_export', 'yz_get_user_exported_data' );

/**
 * Get User Export Data.
 */
function yz_export_user_data( $ids ) {

    global $YZ_upload_url;
    
    ?>

    <tbody>

        <?php foreach ( $ids as $option_id => $data ) : ?>

            <?php $value = yz_data( $option_id ); ?>
            
            <?php if ( ! is_numeric( $option_id ) && empty( $value ) ) continue; ?>
            
            <tr>
                <th><?php echo $data['title']; ?></th>
                <td><?php
                    if ( is_numeric( $option_id ) ) {
                        echo xprofile_get_field_data( $option_id, bp_displayed_user_id(), 'comma' );
                    } elseif ( is_array( $value ) ) {

                        switch ( $option_id ) {

                            case 'wg_project_categories':
                            case 'wg_project_tags':
                                echo implode( $value, ', ' ); 
                                break;

                            case 'wg_link_img':
                            case 'wg_quote_img':
                            case 'wg_about_me_photo':
                            case 'wg_project_thumbnail':
                                foreach ( $value as $key => $val ) {

                                    if ( empty( $val ) ) continue;
                                    if ( $key == 'original' ) {
                                        $key = __( 'Original', 'youzer' );
                                    } elseif ( $key == 'thumbnail' ) {
                                        $key = __( 'Thumbnail', 'youzer' );
                                    }
                                    $url = $YZ_upload_url . $val;
                                    echo'<strong>' . $key . '</strong> : <a href="' . $url . '">' . $url . '</a><br>';
                                }

                                break;
                            
                            default:
                                foreach ( $value as $key => $val ) {
                                    if ( empty( $val ) ) continue;
                                    echo $key . ' : ' . $val . '<br>'; 
                                }
                                break;
                        }

                    } else {
                        echo $value;
                    }

                ; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    
    <?php
}

/**
 * Export Xprofile Data
 */
function yz_export_xprofile_data( $fields ) {

    $options = array(
        'user_id' => bp_displayed_user_id(),
        'profile_group_id' => false,
    );

    if ( ! bp_is_active( 'xprofile' ) ) {
        return $fields;
    }
    
    if ( bp_has_profile( $options ) ) :

        while ( bp_profile_groups() ) : bp_the_profile_group();
            
        if ( bp_profile_group_has_fields() ) :

            $group_id = 'group_' . bp_get_the_profile_group_id();
            $xprofile_fields = array(
            $group_id => array(
                'title' => bp_get_the_profile_group_name()
            )
        );

        while ( bp_profile_fields() ) {

            bp_the_profile_field(); 

            if ( bp_field_has_data() ) {

                $xprofile_fields[ $group_id ]['fields'][ bp_get_the_profile_field_id() ] = array( 'title' =>bp_get_the_profile_field_name() );
            }

        }
        
        // Add Xprofile fields.
        $fields = yz_array_insert_after( $fields, 'profile', $xprofile_fields );

        endif; endwhile;
    
    endif;

    return $fields;
}

add_filter( 'yz_export_fields', 'yz_export_xprofile_data' );

/**
 * Generate personal data file.
 */
function yz_generate_personal_data_file() {

    if ( isset( $_GET['yz-export-data'] ) ) {

        // Get Filename.
        $filename = 'personal-data.html';

        // Get File Content.
        $somecontent = yz_get_user_exported_data();

        !$handle = fopen( $filename, 'w' );
        
        // Add Content to the created file.
        fwrite( $handle, $somecontent );
        
        // Close file.
        fclose( $handle );

        // Set File Header Data.
        header( "Cache-Control: public" );
        header( "Content-Description: File Transfer" );
        header( "Content-Length: " . filesize( "$filename" ) . ";" );
        header( "Content-Disposition: attachment; filename=$filename" );
        header( "Content-Type: application/octet-stream; " ); 
        header( "Content-Transfer-Encoding: binary" );

        // Open File.
        echo $somecontent;

    }

}

add_action( 'init', 'yz_generate_personal_data_file' );