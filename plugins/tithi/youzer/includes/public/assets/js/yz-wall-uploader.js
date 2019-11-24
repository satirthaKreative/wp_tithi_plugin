( function( $ ) {

	'use strict';
	
	$( document ).ready( function() {
		
		// Init Vars.
		var yz_atts_count = 0, yz_nxt_atts_id = 0, yz_atts_files = null,
			yz_wall_form = $( '#yz-wall-form' );

		/**
		 * Open Files Uploader
		 */
		$( '.yz-wall-upload-btn' ).click( function( e ) {

			// Check Files Number.
			if ( ! $.yz_CheckFilesNumber() || yz_wall_form.find( '.yz-file-progress' )[0] ) {
				return false;
			}

			// Trigger Click
		    $( '#yz-upload-attachments' ).click();

		    e.preventDefault();
		});

		/**
		 * Submit form to Upload Files.
		 */
		$( '#yz-upload-attachments' ).change( function ( e ) {

		    e.preventDefault();

			// Get form Files.
    		var files = $( '#yz-upload-attachments' ).get( 0 );

    		// Get Files.
    		yz_atts_files = files;

    		// Upload Files.
			$.yz_UploadFiles({
				'attachments': files,
				'max_size': Yz_Wall.max_size,
				'max_number': 20,
				'allowed_extensions': ['png', 'jpg', 'jpeg', 'gif', 'mp4', 'ogv', 'ogg', 'mp3', 'wav', 'webm' ]
			});

		});

	    /**
	     * Validate Uploader Attachments.
	     */
	    $.yz_validate_attachment = function ( file, options ) {

			// Get Options.
        	var yz_options = $.extend( {
        		allowed_extensions : [ "jpg", "jpeg", "gif", "png" ],
        		max_number : 3,
        		max_size : 3
        	}, options ), dialog;

			// Check File Size.
			if ( file.size > yz_options.max_size * 1048576 ) {
				$.yz_DialogMsg( 'error', Yz_Wall.invalid_file_size );
				return false;
			}

			// Check Files Number.
			if ( ! $.yz_CheckFilesNumber() ) {
				return false;
			}

			// Get Current Attachment Number.
			var attachments_nbr = yz_wall_form.find( '.yz-attachment-item' ).length + 1;

	    	// Check Files number.
			if ( attachments_nbr > yz_options.max_number ) {
				$.yz_DialogMsg( 'error', Yz_Wall.invalid_files_number );
				return false;
			}

			// Check File Extension.
			var ext = file.name.split( '.' ).pop().toLowerCase();

			// Check If File Is Video .
			if ( $.yz_isPostType( 'video' ) ) {
				var allowed_video_extentions = Yz_Wall.video_extentions;
				if ( $.inArray( ext, allowed_video_extentions ) == -1 ) {
					$.yz_DialogMsg( 'error', Yz_Wall.invalid_video_ext );
					return false;
				}
			}

			// Check If File Is Image .
			if ( $.yz_isPostType( 'photo' ) || $.yz_isPostType( 'slideshow' ) ) {
				var allowed_image_extentions = Yz_Wall.image_extentions;
				if ( $.inArray( ext, allowed_image_extentions ) == -1 ) {
					$.yz_DialogMsg( 'error', Yz_Wall.invalid_image_ext );
					return false;
				}
			}

			// Check If File extention allowed .
			if ( $.yz_isPostType( 'file' ) ) {
				var allowed_file_extentions = Yz_Wall.file_extentions;
				if ( $.inArray( ext, allowed_file_extentions ) == -1 ) {
					$.yz_DialogMsg( 'error', Yz_Wall.invalid_file_ext );
					return false;
				}
			}

			// Check If File Is Image .
			if ( $.yz_isPostType( 'audio' ) ) {
				var allowed_audio_extentions = Yz_Wall.audio_extentions;
				if ( $.inArray( ext, allowed_audio_extentions ) == -1 ) {
					$.yz_DialogMsg( 'error', Yz_Wall.invalid_audio_ext );
					return false;
				}
			}

			return true;
	    }

		/**
		 * Upload Files.
		 */
		$.yz_UploadFiles = function ( options ) {

			// Get Options.
        	var qto = $.extend({
        		allowed_extensions : Yz_Wall.default_extentions,
        		max_number : 3,
        		max_size : 3
        	}, options ), dialog;

        	// Get Files.
        	var files = qto.attachments.files;

    		for ( var i = 0; i < files.length ; i++ ) {

				// Get File.
    			var file = files[i];

    			if ( ! $.yz_validate_attachment( file, qto ) ) {
    				return false;
    			}

        		// Get Attachment Item Html Code.
        		var qt_AttachmentItem = $.youzerAttachmentItem({
        			'file' : file,
        			'file_name': file.name
        		});

        		// Append Item To the Attachments List.
        		$( '.yz-form-attachments' ).append( qt_AttachmentItem );

        		// Upload File. 
        		if ( i == 0 ) {
        			$.yz_UploadFile( file );
        		}

			}

		}

		/**
		 * Get Attachment Item HTML Code.
		 */
		$.youzerAttachmentItem = function ( options ) {

			// Get Option.
			var qto = $.extend( {}, options ), file_code, image_code, file_name;

			// Get File Name.
			file_name = $.yz_GetNameExcerpt( qto.file_name );
	
			// Get Files HTML Code.
			file_code =  '<div class="yz-attachment-item yz-file-preview">' +
							'<div class="yz-attachment-details">' +
								'<i class="fas fa-hourglass-half yz-file-icon"></i>' +
								'<span class="yz-file-name">' + file_name + '</span>' +
							'</div>' +
							'<div class="yz-file-progress">' +
								'<span class="yz-file-upload"></span>' +
							'</div>' +
							'<input type="hidden" class="yz-attachment-data" name="attachments_files[]" />' +
						'</div>';
	
			// Get Image Preview HTML Code.
			image_code =  '<div class="yz-attachment-item yz-image-preview">' +
							'<div class="yz-attachment-details">' +
								'<i class="fas fa-hourglass-half yz-file-icon"></i>' +
							'</div>' +
							'<div class="yz-file-progress">' +
								'<span class="yz-file-upload"></span>' +
							'</div>' +
							'<input type="hidden" class="yz-attachment-data" name="attachments_files[]" />' +
						'</div>';

			// Return Item Code.
			if ( $.yz_CheckIsFileImage( qto.file ) ) {
				return image_code;
			} else {
				return file_code;
			}

		}

		/**
		 * Upload Attachments.
		 */
		$.yz_UploadFile = function ( file ) {

			// Get Attachment Item.
			var item = yz_wall_form.find( '.yz-file-progress:first' ).parent( '.yz-attachment-item' );

			// Create New Form Data.
		    var formData = new FormData();

		    // Fill Form with Data.
		    formData.append( 'image', file );
		    formData.append( 'action', 'yz_upload_wall_attachments' );
		    formData.append( 'security', yz_wall_form.find( 'input[name="security"]' ).val() );

		    // Upload File.
		    $.ajax({
		        type  : 'POST',
		        url   : Youzer.ajax_url,
		        data  : formData,
		        cache : false,
		        contentType: false,
		        processData: false,
		        xhr: function() {
	                var YouzerXhr = $.ajaxSettings.xhr();
	                if ( YouzerXhr.upload ) {

	                	// Disable submit button.
						$( '.yz-wall-actions .yz-wall-post' ).attr( 'disabled', true );

	                    YouzerXhr.upload.addEventListener( 'progress', function( e ) {
						    if ( e.lengthComputable ) {

						   		// Set up Variables.
						        var max = e.total,
						        	current = e.loaded,
						        	Percentage = ( current * 100 ) / max;

						        // Get Progress Bar
						       	var progress_bar = item.find( '.yz-file-upload' );
						       	
						       	// Upload Started Class.
						       	var yz_loading_icon = 'fas fa-spinner fa-spin yz-file-icon';

						       	// Add loader icon
		        				item.find( '.yz-file-icon' ).attr( 'class', yz_loading_icon );

						       	// Update Upload status.
						        progress_bar.css( 'width', Percentage  + '%' );

						        if ( Percentage >= 100 ) {
						        	// Change Progress Bar Class .
						        	progress_bar.addClass( 'yz-file-uploaded' );
						        }

				    		}  

	                    });
	                }
	                return YouzerXhr;
		        },

		        success: function( result ) {

	            	// Get Response Data.
	            	var res = $.parseJSON( result );

		            if ( res.error ) {

		            	// Show Error Message
		            	$.yz_DialogMsg( 'error', res.error );

		            	// Remove Item.
		            	item.remove();

						// Check Upload Progress to Enable Submit Field.
						$.yz_CheckUploadProgress();

	            		return false;
		            }
			        
			        // Prepare Trash Icon
		        	var trash_icon = '<i class="fas fa-trash-alt yz-delete-attachment"></i>',
		        		paperclip_icon = 'fas fa-paperclip yz-file-icon';
		        	
		        	// Remove Progress Bar.
		        	item.find( '.yz-file-progress' ).fadeOut( 400, function() {
		        		
		        		// Remove Progress Div.
		        		$( this ).remove();
		        		
		        		// Let's Upload Next File.
		        		$.yz_upload_next_file();

						// Check Upload Progress to Enable Submit Field.
						$.yz_CheckUploadProgress();

		        	});
		        	
		        	// Delete Loader Icon.
					if ( $.yz_CheckIsFileImage( file ) ) {
			        	item.find( '.yz-file-icon' ).remove();
			        }

			   		// Change Loader Icon with paperclip icon.
		        	item.find( '.yz-file-icon' ).attr( 'class', paperclip_icon );

		        	// Add Trash Icon to the attachment item.
		        	item.find( '.yz-attachment-details' ).append( trash_icon );

		        	// Update Item Attachments Data.
					item.find( '.yz-attachment-data' ).val( result );

					// Get File Data.
					var file_data = $.parseJSON( result );

					// Get Temporary File Name
					var filename = $.map( file_data, function( file ) { return file.original; });
					
					var img_preview =  Yz_Wall.base_url + '/temp/' + filename[0];
					
					item.css( 'background-image', 'url(' + img_preview + ')' );

		        },
		        
		        error : function( XMLHttpRequest, textStatus, errorThrown ) {

	            	// Remove Item.
	            	item.remove();

					$.yz_DialogMsg( 'error', textStatus );
	            	
	            	// Check Upload Progress to Enable Submit Field.
					$.yz_CheckUploadProgress();

	            	$.yz_upload_next_file();

		        }

		    });

		}

		/**
		 * Upload Next File
		 */
		 $.yz_upload_next_file = function( ) {

    		// Let's Upload Next File.
    		yz_atts_count++;

        	if ( typeof yz_atts_files.files[ yz_atts_count ] !== 'undefined' ) {
        		$.yz_UploadFile( yz_atts_files.files[ yz_atts_count ] );
        	}

		}

		/**
		 * Get File Name Excerpt.
		 */
		$.yz_GetNameExcerpt = function ( name ) {

		    // Set up Variables.
			var strLen = 25,
		    	separator = '...';

		    // If file name not too long keep it.
		    if ( name.length <= strLen ) {
		    	return name;
		    }

		    // Set up Variables.
		    var sepLen = separator.length,
		        charsToShow = strLen - sepLen,
		        frontChars = Math.ceil(charsToShow/2),
		        backChars = Math.floor(charsToShow/2);

		    // Shorten File Name.
		    return name.substr( 0, frontChars ) + separator + name.substr(name.length - backChars);
		};

		/**
		 * Delete Attachment .
		 */
        $( document ).on( 'click', '.yz-delete-attachment' , function( e ) {

        	// Get Attachment item.
        	var attachment = $( this ).closest( '.yz-attachment-item' );

        	// Get File Data.
			var file_data = $.parseJSON( attachment.find( '.yz-attachment-data' ).val() );

			// Get Temporary File Name
			var filename = $.map( file_data, function( file ) { return file.file_name; });

			// Remove Attachment from Form.
			attachment.remove();

			// Remove Attachment from Directory.
			$.yz_DeleteAttachment( filename[0] );

        });

		/**
		 * Delete Attachment File.
		 */
		$.yz_DeleteAttachment = function( file ) {

			// Create New Form Data.
		    var formData = new FormData();

		    // Fill Form with Data.
		    formData.append( 'attachment', file );
		    formData.append( 'action', 'yz_delete_wall_attachment' );
			formData.append( 'security', yz_wall_form.find( 'input[name="security"]' ).val() );

			$.ajax({
                type: "POST",
                data: formData,      
                url: Youzer.ajax_url,
		        contentType: false,
		        processData: false
			});

		}

		/*
		 * Check If Uploaded File Is Image.
		 **/
		$.yz_CheckIsFileImage = function( file ) {
			var fileType = file['type'];
			var ValidImageTypes = [ "image/gif", "image/jpeg", "image/png" ];
			if ( $.inArray( fileType, ValidImageTypes ) < 0 ) {
			    return false;
			}
			return true;
		}

		/*
		 * Check Upload Progress !!??
		 **/
		$.yz_CheckUploadProgress = function( ) {
			if ( ! $( '.youzer .yz-file-progress' )[0] ) {
				$( '#yz-upload-attachments' ).val( '' );
				yz_wall_form.find( '.yz-wall-post' ).attr( 'disabled' , false );
				// Reset Vars.
				yz_atts_count = 0;
				yz_atts_files = null;
			}
		}

		/**
		 * Check to show Upload Button.
		 */
		$.yz_check_for_uploader_button = function() {
			if ( 'status' != $( 'input:radio[name="post_type"]' ).val() ) {
	            $( '.yz-wall-actions .yz-wall-upload-btn' ).fadeIn();
			}
		}
		$.yz_check_for_uploader_button();

		/*
		 * Show/Hide Link Form.
		 **/
		$( 'input:radio[name="post_type"]' ).change( function() {
			// Show/Hide Link Form.
	        if ( $( this ).is( ':checked' ) && $( this ).val() == 'link' ) {
	            $( '.yz-wall-link-form' ).fadeIn();
	        } else {
	            $( '.yz-wall-link-form' ).fadeOut();
	        }

			// Show/Hide Quote Form.
	        if ( $( this ).is( ':checked' ) && $( this ).val() == 'quote' ) {
	            $( '.yz-wall-quote-form' ).fadeIn();
	        } else {
	            $( '.yz-wall-quote-form' ).fadeOut();
	        }

	        // Show/Hide Upload Button
	        if ( $( this ).is( ':checked' ) && $( this ).val() != 'status' ) {
	            $( '.yz-wall-actions .yz-wall-upload-btn' ).fadeIn();
	        } else {
	            $( '.yz-wall-actions .yz-wall-upload-btn' ).fadeOut();
	        }

	        // Remove Old Attachments
	       	$( '.yz-attachment-item' ).remove();

	    });

		/**
		 * Check Post Type.
		 **/
	    $.yz_isPostType = function( post_type ) {
	    	if ( post_type == $( 'input:radio[name="post_type"]:checked' ).val() ) {
	    		return true;
	    	}
	    	return false;
	    }

		/*
		 * Check Files Number.
		 **/
		$.yz_CheckFilesNumber = function() {
			if ( 'photo' != $( 'input:radio[name="post_type"]:checked' ).val() && 'slideshow' != $( 'input:radio[name="post_type"]:checked' ).val() && $( '.yz-attachment-item' )[0] ) {
				$.yz_DialogMsg( 'error', Yz_Wall.max_one_file );
				return false;
			}
			return true;
		}

	});

	/**
	 * # Modal.
	 */
	$( document ).on( 'click', '.yz-trigger-who-modal' , function( e ) {
		
		$( '.yz-wall-modal-overlay' ).fadeIn( 500, function() {
			$( this ).find( '.yz-modal-loader' ).fadeIn( 400 );
		});

		e.preventDefault();

		var post_id = $( this ).data( 'who-liked' );
		var data = {
			'action': 'yz_get_who_liked_post',
			'post_id': post_id
		};

		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post( Youzer.ajax_url, data, function( response ) {
			var $new_modal = $( '#yz-wall-modal' ).append( response );		
		    // Display Modal
			$new_modal.find( '.yz-wall-modal' ).addClass( 'yz-wall-modal-show' );
			// Hide Loader
			$( '.yz-wall-modal-overlay' ).find( '.yz-modal-loader' ).hide();
		});
		

	});

	// Hide Modal If User Clicked Escape Button
	$( document ).keyup( function( e ) {
		if ( $( '.yz-wall-modal-show' )[0] ) {
		    if ( e.keyCode === 27 ) {
			    $( '.yz-wall-modal-close' ).trigger( 'click' );
		    }
		}
		return false;
	});

	// # Hide Modal if User Clicked Outside
	$( document ).mouseup( function( e ) {
	    if ( $( '.yz-wall-modal-overlay' ).is( e.target ) && $( '.yz-wall-modal-show' )[0] ) {
			$( '.yz-wall-modal-close' ).trigger( 'click' );
	    }
	    return false;
	});

})( jQuery );