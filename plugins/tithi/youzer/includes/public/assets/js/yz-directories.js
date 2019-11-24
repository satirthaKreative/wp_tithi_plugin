( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		
		$('#members_search,#groups_search').on('click', function(){
		    $(window).off('resize');
		});

		// Add Loading Button
        $( '#yz-groups-list,#yz-members-list' ).on( 'click', 'a.group-button:not(.membership-requested),.friendship-button a', function() {
    		$( this ).addClass( 'yz-btn-loading' );
		});

		// Display Search Box.
    	$( '#directory-show-search a' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '#yz-directory-search-box' ).fadeToggle();
		});

		var resizeTimer;

		$( window ).on( 'resize', function ( e ) {

			// Init Vars.
			var window_changed;

		    clearTimeout( resizeTimer );

		    resizeTimer = setTimeout( function () {
		    	
		    	if ( $.browser.mobile ) {
			    	window_changed = $( window ).width() != app.size.window_width;
				} else {
					window_changed = true;
				}

	    		if ( window_changed ) {
			        if ( $( window ).width() > 768 ) {
			        	$( '#directory-show-search' ).fadeOut( 200 );
			        	$( '#yz-directory-search-box' ).fadeIn( 200 );
			        } else {
			        	$( '#directory-show-search' ).fadeIn( 200 );
			        	$( '#yz-directory-search-box' ).fadeOut( 200 );
			        }
		 		}
			}, 250 );
		});

		// Activate Members Masonry Layout.
		if ( $( '#yz-members-list' )[0] ) {

			// Set the container that Masonry will be inside of in a var
		    var members_container = document.querySelector( '#yz-members-list' );
		    
		    // Create empty var msnry
		    var members_msnry;
		    
		    // Initialize Masonry after all images have loaded
		    imagesLoaded( members_container, function() {
		        members_msnry = new Masonry( members_container, {
		            itemSelector: '#yz-members-list li'
		        });
		    });

		}

		// Activate Groups Masonry Layout.
		if ( $( '#yz-groups-list' )[0] ) {

			// Set the container that Masonry will be inside of in a var
		    var groups_container = document.querySelector( '#yz-groups-list');
		   
		    // Create empty var msnry
		    var groups_msnry;

		    // Initialize Masonry after all images have loaded
		    imagesLoaded( groups_container, function() {
		        groups_msnry = new Masonry( groups_container, {
		            itemSelector: '#yz-groups-list li'
		        });
		    });

		}
	});

})( jQuery );