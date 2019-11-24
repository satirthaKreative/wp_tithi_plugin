( function( $ ) {

    'use strict';

    $( document ).ready( function () {

        $( document ).on( 'click', '#yz-slideshow-button' , function( e ) {

            var current_wg_nbr = $( '.yz-wg-item[data-wg=slideshow]' ).length + 1;

            if ( current_wg_nbr > yz_max_slideshow_img  )  {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : yz.items_nbr + yz_max_slideshow_img,
                    type : 'error'
                });
                return false;
            }

            e.preventDefault();

            var slideshow_button = $.ukai_form_input( {
                    label_title : yz.upload_photo,
                    options_name : 'youzer_slideshow',
                    input_id    : 'yz_slideshow_' + yz_ss_nextCell,
                    cell         : yz_ss_nextCell,
                    class        : 'yz-photo-url',
                    input_type  : 'image',
                    option_item  : 'original',
                    option_only : true
                });

            // Add Slideshow Item.
            $(  '<li class="yz-wg-item" data-wg="slideshow">' +
                    '<div class="yz-wg-container">' +
                        '<div class="yz-cphoto-content">' + slideshow_button +
                    '</div></div><a class="yz-delete-item"></a>' +
                '</li>'
            ).hide().prependTo( '.yz-wg-slideshow-options' ).fadeIn( 400 );

            // Increase ID Number.
            yz_ss_nextCell++;

            // Check Account Items List
            $.yz_CheckItemsList();

        });

    });

})( jQuery );