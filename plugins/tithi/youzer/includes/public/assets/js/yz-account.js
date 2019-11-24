( function( $ ) {

    'use strict';

	$( document ).ready( function() {
	
		if ( jQuery().niceSelect ) {
			$( '.youzer select' ).not( '[multiple="multiple"]' ).niceSelect();
		}

	    // Make items Removable
		$( document ).on( 'click', '.yz-delete-item', function( e ) {
	    	// Get Widget Name.
	    	var widget    = $( this ).parent().data( 'wg' ),
	    		form_id   = $( this ).closest( 'form' ).attr( 'id' ),
	    		img_url   = $( this ).parent().find( '.yz-photo-url' ).val(),
	    		img_thumb = $( this ).parent().find( '.yz-photo-thumbnail' ).val();

	        $( this ).parent().addClass( 'removered' ).fadeOut( function() {
	        	// Remove Item
	            $( this ).remove();
		    	// Delete Photo.
	           	if ( ( 'slideshow' == widget || 'portfolio' == widget ) && img_url ) {
	           		// Delete Photo
		    		$.yz_DeletePhoto( img_url );
		    		$.yz_DeletePhoto( img_thumb );
	    		}
	    		// Check Widget Items
            	$.yz_CheckItemsList();
		    });

	    });

	    /**
	     * Show Files Browser
	     */
		$( document ).on( 'click', '.yz-upload-photo', function( e ) {
			e.preventDefault();
			var elem = document.getElementById( $( this ).attr( 'for' ) );
			if ( elem && document.createEvent ) {
				var evt = document.createEvent( 'MouseEvents' );
				evt.initEvent( 'click', true, false );
				elem.dispatchEvent( evt );
			}
		});

	    /*
	     * Images Uploader
	     */
		$( document ).on( 'change', '.yz_upload_file', function( e ) {

	        e.stopPropagation();

	        var formData = new FormData(),
		  		file 	  = $( this ),
		  		img_file  = $( this )[0].files[0],
		  		field 	  = $( this ).closest( '.yz-uploader-item' ),
		  		preview   = field.find( '.yz-photo-preview' ),
		  		old_photo = field.find( '.yz-photo-url' ).val(),
		  		old_photo_thumbnail = field.find( '.yz-photo-thumbnail' ).val(),
		  		nonce 	  = $( this ).closest( 'form' ).find( "input[name='security']" ).val();

		  	// Append Data.
		  	formData.append( 'nonce', nonce );
	       	formData.append( 'file', img_file );
		  	formData.append( 'action', 'upload_files' );

	        $.ajax( {
	            url         : yz.ajax_url,
	            type        : "POST",
	            data        : formData,
	            contentType : false,
	            cache       : false,
	            processData : false,
	            beforeSend  : function() {
	            	// Display Loader.
	            	var loader = '<div class="yz-load-photo"><i class="fas fa-spinner fa-spin"></i></div>';
	            	$( loader ).hide().appendTo( preview ).fadeIn( 800 );
	            },
	            success : function( data ) {

	            	// Remove File From Input.
	            	file.val( '' );

	            	// Get Response Data.
	            	var res = $.parseJSON( data );

		            if ( res.error ) {
	            		// Hide Loader.
	            		preview.find( '.yz-load-photo' ).fadeOut( 300 ).remove();
		            	// Show Error Message
						$.ShowPanelMessage( {
							msg  : res.error,
							type : 'error'
						} );
	            		return;
		            }

				  	// Delete The Old Photo.
				  	if ( old_photo ) {
				  		// Delete Original/Thumbnail Photos.
				  		$.yz_DeletePhoto( old_photo );
				  		$.yz_DeletePhoto( old_photo_thumbnail );
				  	}

		   			// Save Photo.
	            	preview.find( '.yz-load-photo' ).fadeOut( 300, function() {
	            		// Hide Loader.
	            		$( this ).remove();
	            		// Display Photo Preview.
	            		preview.fadeOut( 100, function() {
		            		$( this ).css( 'background-image', 'url(' + yz.upload_url + res.thumbnail + ')' ).fadeIn( 400 );
		            		// Update Photo Url
		            		field.find( '.yz-photo-url' ).val( res.original ).change();
		            		field.find( '.yz-photo-thumbnail' ).val( res.thumbnail ).change();
		            		// Activate Trash Icon.
		            		field.find( '.yz-delete-photo' ).addClass( 'yz-show-trash' );
					        // Get Form Data
					        var data = field.closest( 'form' ).serialize() + '&die=true';
			        		// Save Form Data
			        		$.post( yz.ajax_url, data );
	            		});
	            	});
	            }
	        });
	    });

	    /**
	     * # Toggle Menu
	     */
        $( document ).on( 'click', '.yz-menu-head i' , function( e ) {

        	e.preventDefault();

        	// Get Data.
        	var elt = $( this ).closest( '.account-menus' ).find( 'ul' ),
        		elt_class =  elt.css( 'display' );

        	// Hide/Show Menus
        	$( this ).closest( '.account-menus' ).find( 'ul' ).slideToggle( 400 );

        	// Change Icon Direction
        	if ( elt_class == 'block' ) {
        		$( this ).attr( 'class', 'fas fa-caret-down' );
        	} else if ( elt_class == 'none' ) {
        		$( this ).attr( 'class', 'fas fa-caret-up' );
        	}

        });

	    /**
	     * # Remove Image
	     */
		$( document ).on( 'click', '.yz-delete-photo', function( e ) {

			// Set up Variables.
			var form_id   = $( this ).closest( 'form' ).attr( 'id' ),
				img_field = $( this ).closest( '.yz-uploader-item' ),
				img_url   = img_field.find( '.yz-photo-url' ).val(),
				thumb_url = img_field.find( '.yz-photo-thumbnail' ).val();

			// Remove Image Url
			img_field.find( '.yz-photo-url' ).val( '' ).trigger( 'change' );
			img_field.find( '.yz-photo-thumbnail' ).val( '' ).trigger( 'change' );

			// Remove Image from Directory.
			$.yz_DeletePhoto( img_url );
			$.yz_DeletePhoto( thumb_url );

			// Reset Preview Image.
		    img_field.find( '.yz-photo-preview' ).css( 'background-image', 'url(' + yz.default_img + ')' );

		    // Hide Trash Icon.
		    $( this ).removeClass( 'yz-show-trash' );

	        // Get Form Data
	        var data = img_field.closest( 'form' ).serialize() + '&die=true';

    		// Save Form Data
    		$.post( yz.ajax_url, data );

		});

	    /**
	     * # Check Account Items
	     */
		$.yz_CheckItemsList = function() {

			// Check Skills List.
			if ( $( '.yz-wg-skills-options li' )[0] ) {
				$( '.yz-no-skills' ).remove();
			} else if ( ! $( '.yz-no-skills' )[0] ) {
				$( '.yz-wg-skills-options' ).append(
					'<p class="yz-no-content yz-no-skills">' + yz.no_items + '</p>'
				);
			}

			// Check Services List.
			if ( $( '.yz-wg-services-options li' )[0] ) {
				$( '.yz-no-services' ).remove();
			} else if ( ! $( '.yz-no-services' )[0] ) {
				$( '.yz-wg-services-options' ).append(
					'<p class="yz-no-content yz-no-services">' + yz.no_items + '</p>'
				);
			}

			// Check Portfolio List.
			if ( $( '.yz-wg-portfolio-options li' )[0] ) {
				$( '.yz-no-portfolio' ).remove();
			} else if ( ! $( '.yz-no-portfolio' )[0] ) {
				$( '.yz-wg-portfolio-options' ).append(
					'<p class="yz-no-content yz-no-portfolio">' + yz.no_items + '</p>'
				);
			}

			// Check Slideshow List.
			if ( $( '.yz-wg-slideshow-options li' )[0] ) {
				$( '.yz-no-slideshow' ).remove();
			} else if ( ! $( '.yz-no-slideshow' )[0] ) {
				$( '.yz-wg-slideshow-options' ).append(
					'<p class="yz-no-content yz-no-slideshow">' + yz.no_items + '</p>'
				);
			}

		}

		$.yz_CheckItemsList();

	    /*
	     * Delete Photo
	     */
		$.yz_DeletePhoto = function( file ) {

			// Create New Form Data.
		    var formData = new FormData();

		    // Fill Form with Data.
		    formData.append( 'attachment', file );
		    formData.append( 'action', 'yz_delete_account_attachment' );

			$.ajax({
                type: "POST",
                data: formData,      
                url: Youzer.ajax_url,
		        contentType: false,
		        processData: false
			});
	    }

	    // Update Account Photo with the new uploaded photo.
	    $( '.yz-account-photo .yz-photo-url' ).on( 'change' , function( e ) {
			e.preventDefault();
			// Get Account Photo url.
			var account_photo = $( this ).val();
			// If Input Value Empty Use Default Image
			if ( ! account_photo ) {
				account_photo = yz.default_img;
			}
			// Change Account Photo.
		    $( '.yz-account-img' ).fadeOut( 200, function() {
		    	$( this ).css( 'background-image', 'url(' + account_photo + ')' ).fadeIn( 200 );
		    });
		});

    	$( '.yz-user-provider-unlink' ).on( 'click', function( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Unlinking. 
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars.
    		var yz_provider_parent = $( this ).closest( '.yz-user-provider-connected' ),
    			yz_curent_unlink_btn = $( this );

    		// Add Loading Class.
    		yz_curent_unlink_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				action: 'yz_unlink_provider_account',
				provider: $( this ).data( 'provider' ),
				security: $( this ).data( 'nonce')
			};

			// Process Ajax Request.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		yz_curent_unlink_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		yz_curent_unlink_btn.removeClass( 'loading' );

		    		// Clear Token input.
		    		yz_provider_parent.find( '.yz-user-provider-token' ).val( '' );

					// Remove Provider.
					yz_provider_parent.find( '.yz-user-provider-box' ).remove();
					yz_provider_parent.removeClass().addClass( 'yz-user-provider-unconnected' );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		yz_curent_unlink_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.yz_DialogMsg( 'error', Youzer.unknown_error );

				return false;

    		});

		});
		
	});

})( jQuery );