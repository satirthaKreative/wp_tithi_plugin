( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        $( document ).on( 'click', '#yz-service-button' , function( e ) {

            var current_wg_nbr = $( '.yz-wg-item[data-wg=services]' ).length + 1;

            if ( current_wg_nbr > yz_max_services_nbr )  {
                // Show Error Message
                $.ShowPanelMessage( {
                    msg  : yz.items_nbr + yz_max_services_nbr,
                    type : 'error'
                });
                return false;
            }

            e.preventDefault();

            var service_icon = $.ukai_form_input( {
                    option_item     : 'icon',
                    cell            : yz_service_nextCell,
                    options_name    : 'youzer_services',
                    input_desc      : yz.serv_desc_icon,
                    label_title     : yz.service_icon,
                    input_type      : 'icon',
                    inner_option    : true
                }),

                service_title = $.ukai_form_input( {
                    option_item     : 'title',
                    cell            : yz_service_nextCell,
                    input_desc      : yz.serv_desc_title,
                    options_name    : 'youzer_services',
                    label_title     : yz.service_title,
                    input_type      : 'text',
                    inner_option    : true
                }),

                service_desc = $.ukai_form_input( {
                    option_item     : 'description',
                    cell            : yz_service_nextCell,
                    options_name    : 'youzer_services',
                    input_desc      : yz.serv_desc_desc,
                    label_title     : yz.service_desc,
                    input_type      : 'textarea',
                    inner_option    : true
                });

            // Add Service
            $( '<li class="yz-wg-item" data-wg="services"><div class="yz-wg-container">' +
                service_icon + service_title + service_desc +
                '</div><a class="yz-delete-item"></a></li>'
            ).hide().prependTo( '.yz-wg-services-options' ).fadeIn( 400 );

            // increase ID number.
            yz_service_nextCell++;

            // Check Account Items List
            $.yz_CheckItemsList();

        });

    });

})( jQuery );