( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Check if Div is Empty
		function isEmpty( el ) {
		    return ! $.trim( el.html() );
		}

		if ( jQuery().niceSelect ) {
			$( '.youzer select:not([multiple="multiple"])' ).niceSelect();
		}
		
		// Textarea Auto Height.
		if ( jQuery().autosize ) {
			autosize( $( '.youzer textarea' ) );
		}

		if ( jQuery().niceSelect ) {
			$( '.logy select' ).not( '[multiple="multiple"]' ).niceSelect();
		}

    	$( '.yzw-form-show-all' ).on( 'click', function( e ) {
    		$( '.yzw-form-show-all' ).fadeOut( function() {
    			$( '.yz-wall-opts-item' ).fadeIn();
    			$( this ).remove();
    		});
		});
		
		// Delete Empty Notices.
		$( '.widget_bp_core_sitewide_messages' ).each( function() {
	        if ( isEmpty( $( this ).find( '.bp-site-wide-message' ) ) ) {
	          $( this ).remove();
	        }
	    });

		// Delete Empty Actions.
		$( '.youzer .group-members-list .action' ).each( function() {
	        if ( isEmpty( $( this ) ) ) {
	          $( this ).remove();
	        }
	    });

		// Delete Empty Sub Navigations.
		$( '#subnav ul' ).each( function() {
	        if ( isEmpty( $( this ) ) ) {
	          $( this ).parent().remove();
	        }
	    });

		// Delete Empty Search
        if ( isEmpty( $( '.yz-group-manage-members-search' ) ) ) {
          $( '.yz-group-manage-members-search' ).remove();
        }
        
		// Close SiteWide Notice.
		$( '#close-notice' ).on( 'click', function( e ) {
			$( this ).closest( '#sitewide-notice' ).fadeOut();
		});

		/**
		 * Display Activity tools.
		 */
		$( document ).on( 'click',  '.yz-show-item-tools', function ( e ) {

			// Switch Current Icon.
			$( this ).toggleClass( 'yz-close-item-tools' );

			// Show / Hide Tools.
			$( this ).closest( '.yz-item, .activity-item' ).find( '.yz-item-tools' ).fadeToggle();

		});

		/**
		 * Save New Removed Group Suggestions.
		 */
		$( document ).on( 'click',  ".yz-suggested-groups-widget .yz-close-button", function ( e ) {
			
			e.preventDefault();
			
			//hide the suggestion
			var item = $( this ).closest( '.yz-list-item' );
			
			$( item ).fadeOut( 400, function() {
				$( this ).remove();
			});

			var url = $( this ).attr( 'href' ),
				nonce = $.yz_get_var_in_url( url, '_wpnonce' ),
				suggested_group_id = $.yz_get_var_in_url( url, 'suggestion_id' );
			
			$.post( Youzer.ajax_url, {
				action: 'yz_groups_refused_suggestion',
				suggestion_id: suggested_group_id,
				_wpnonce: nonce
			});

			return false;

		});

		/**
		 * Save New Removed Friend Suggestions.
		 */
		$( document ).on( 'click', ".yz-suggested-friends-widget .yz-close-button", function ( e ) {
			
			e.preventDefault();
			
			//hide the suggestion
			var item = $( this ).closest( '.yz-list-item' );
			
			$( item ).fadeOut( 400, function() {
				$( this ).remove();
			});

			var url = $( this ).attr( 'href' ),
				nonce = $.yz_get_var_in_url( url, '_wpnonce' ),
				suggested_friend_id = $.yz_get_var_in_url( url, 'suggestion_id' );
			
			$.post( Youzer.ajax_url, {
				action: 'yz_friends_refused_suggestion',
				suggestion_id: suggested_friend_id,
				_wpnonce: nonce
			});

			return false;

		});

		/**
		 * Get Url Variable.
		 */
		$.yz_get_var_in_url = function( url, name ) {
			var urla = url.split( "?" );
			var qvars = urla[1].split( "&" );//so we hav an arry of name=val,name=val
			for ( var i = 0; i < qvars.length; i++ ) {
				var qv = qvars[i].split( "=" );
				if ( qv[0] == name )
					return qv[1];
			}
			return '';
		}

		// Change Fields Privacy.
	    $( '.field-visibility-settings .radio input[type=radio]' ).change( function() {
	    	var new_privacy = $( this ).parent().find( '.field-visibility-text' ).text();
	    	$( this ).closest('.field-visibility-settings')
	    	.prev( '.field-visibility-settings-toggle' )
	    	.find( '.current-visibility-level' )
	    	.text( new_privacy );
	    });
		
		// Append Dialog.
		$( 'body' ).append( '<div class="youzer-dialog"></div>' );

	    /**
	     * Dialog Message.
	     */
	    $.yz_DialogMsg = function ( type, msg ) {

	     	var dialogHeader, dialogTitle, dialogButton, confirmation_btn;

	     	// Get Dialog Title.
			if ( type == 'error' ) {
	     		dialogTitle = '<div class="youzer-dialog-title">' + Youzer.ops + '</div>';
	     	} else if ( type == 'success' ) {
	     		dialogTitle = '<div class="youzer-dialog-title">' + Youzer.done + '</div>';
	     	} else if ( type == 'info' ) {
	     		dialogTitle = '';
	     	}

	     	// Get Dialog Button.
			if ( type == 'error' ) {
	     		dialogButton = Youzer.gotit;
	     	} else if ( type == 'success' ) {
	     		dialogButton = Youzer.thanks;
	     	} else if ( type == 'info' ) {
	     		dialogButton = Youzer.cancel;
	     	}

	     	// Get Header Icon.
	     	if ( type == 'error' ) {
	     		dialogHeader = '<i class="fas fa-exclamation-triangle"></i>';
	     	} else if ( type == 'info' ) {
	     		dialogHeader = '<i class="fas fa-info-circle"></i>';
	     	} else if ( type == 'success' ) {
	     		dialogHeader = '<i class="fas fa-check"></i>';
	     	}

	     	// Get Confirmation Button
	     	if ( type == 'info' ) {
	     		confirmation_btn = '<li><a class="yz-confirm-dialog">' + Youzer.confirm + '</a></li>';
	     	} else {
	     		confirmation_btn = '';
	     	}

	     	var dialog =
	     	'<div class="yz-' + type + '-dialog">' +
	            '<div class="youzer-dialog-container">' +
	                '<div class="yz-dialog-header">' + dialogHeader + '</div>' +
	                '<div class="youzer-dialog-msg">' +
	                    '<div class="youzer-dialog-desc">' + dialogTitle + '<div class="yz-dialog-msg-content">' + msg + '</div>' + '</div>' +
	               	'</div>' +
	                '<ul class="yz-dialog-buttons">' +
	                	confirmation_btn +
	                	'<li><a class="yz-close-dialog">' + dialogButton + '</a></li>' +
	                '</ul>'+
	            '</div>' +
	        '</div>';

	     	$( '.youzer-dialog' ).empty().append( dialog );
	        $( '.youzer-dialog' ).addClass( 'yz-is-visible' );

	    }

	    // Close Dialog
	    $( '.youzer-dialog' ).on( 'click', function( e ) {
	        if ( $( e.target ).is( '.yz-close-dialog' ) || $( e.target ).is( '.youzer-dialog' ) ) {
	            e.preventDefault();
	            $( this ).removeClass( 'yz-is-visible' );
	        }
	    });

	    // Close Modal
	    $( '.youzer-modal' ).on( 'click', function( e ) {
	        if ( $( e.target ).is( '.yz-close-dialog' ) || $( e.target ).is( '.youzer-modal' ) ) {
	            e.preventDefault();
	            $( this ).removeClass( 'yz-is-visible' );
	        }
	    });

	    // Close Dialog if you user Clicked Cancel
	    $( 'body' ).on( 'click', '.yz-close-dialog', function( e ) {
	        e.preventDefault();
	        $( '.youzer-dialog,.youzer-modal' ).removeClass( 'yz-is-visible' );
	    });

	    // Add Close Button to Login Popup.
	    $( '.yz-popup-login .logy-form-header' )
	    .append( '<i class="fas fa-times yz-close-login"></i>' );

	    // Display Login Popup.
	    $( 'a[data-show-youzer-login="true"]' ).on( 'click', function( e ) {

	    	if ( Youzer.login_popup == 'off' ) {
	    		return;
	    	}

	        e.preventDefault();
	        $( '.yz-popup-login' ).addClass( 'yz-is-visible' );
	    });

	    // Close Login Popup.
	    $( '.yz-popup-login' ).on( 'click', function( e ) {
	        if ( $( e.target ).is( '.yz-close-login' ) || $( e.target ).is( '.yz-popup-login' ) ) {
	            e.preventDefault();
	            $( this ).removeClass( 'yz-is-visible' );
	        }
	    });

	    // Close Dialog if you user Clicked Cancel
	    $( '.yz-close-login' ).on( 'click', function( e ) {
	        e.preventDefault();
	        $( '.yz-popup-login' ).removeClass( 'yz-is-visible' );
	    });

		// Show/Hide Primary Nav Message
		$( '.yz-primary-nav-settings' ).click( function( e ) {
	        e.preventDefault();
	        // Get Parent Box.
			var settings_box = $( this ).closest( '.yz-primary-nav-area' );
			// Toggle Menu.
			settings_box.toggleClass( 'open-settings-menu' );
			// Display or Hide Box.
	        settings_box.find( '.yz-settings-menu' ).fadeToggle( 400 );
		});

	    // Ajax Login.
	    $( '.logy-login-form' ).on( 'submit', function( e ) {

	    	if ( Youzer.ajax_enabled == 'off' ) {
	    		return;
	    	}

	    	// Add Authenticating Class.
	    	$( this ).addClass( 'yz-authenticating' );

	    	// Init Vars.
	    	var yz_login_form = $( this ), yz_btn_txt, yz_btn_icon, yz_submit_btn;

	    	// Get Current Button Text & Icon.
	    	yz_submit_btn = $( this ).find( 'button[type="submit"]' );
	    	yz_btn_txt  = yz_submit_btn.find( '.logy-button-title' ).text();
	    	yz_btn_icon = yz_submit_btn.find( '.logy-button-icon i' ).attr( 'class' );

	    	// Display "Authenticating..." Messages.
	    	yz_submit_btn.find( '.logy-button-title' ).text( Youzer.authenticating );
	    	yz_submit_btn.find( '.logy-button-icon i' ).attr( 'class', 'fas fa-spinner fa-spin' );

	    	// Get Current Button Icon
	    	var yz_login_data = { 
                'action': 'yz_ajax_login',
                'username': $( this ).find( 'input[name="log"]' ).val(), 
                'password': $( this ).find( 'input[name="pwd"]' ).val(),
                'remember': $( this ).find( 'input[name="rememberme"]' ).val(), 
                'security': $( this ).find( 'input[name="yz_ajax_login_nonce"]' ).val(), 
	        };

	        $.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: Youzer.ajax_url,
	            data: yz_login_data,
	            success: function( response ) {

	                if ( response.loggedin == true ) {
	                	// Change Login Button Title.
	    				yz_submit_btn.find( '.logy-button-title' ).text( response.message );
	    				yz_submit_btn.find( '.logy-button-icon i' ).attr( 'class', 'fas fa-check' );
		         		// Redirect.
	                    document.location.href = response.redirect_url;
	                } else {

		            	// Add Authenticating Class.
		    			yz_login_form.removeClass( 'yz-authenticating' );
		    	
	                	// Clear Inputs Depending on the errors ..
	                	if ( response.error_code && 'incorrect_password' == response.error_code ) {
	                		// Clear Password Field.
	                		yz_login_form.find( 'input[name="pwd"]' ).val( '' );
	                	} else {
	                		// If Username invalid Clear Inputs.
	                		yz_login_form.find( 'input[name="log"],input[name="pwd"]' ).val( '' );
	                	}
	                	// Change Login Button Title & Icon.
	    				yz_submit_btn.find( '.logy-button-title' ).text( yz_btn_txt );
	    				yz_submit_btn.find( '.logy-button-icon i' ).attr( 'class', yz_btn_icon );
		            	// Show Error Message.
		            	$.yz_DialogMsg( 'error', response.message );
	                }
	            }
        	});

	        e.preventDefault();

	    });
	    
		// Responsive Navbar Menu
		$( '.yz-responsive-menu' ).click( function( e ) {
	        e.preventDefault();
			// Hide Account Settings Menu to avoid any Conflect.
			if (  $( '.yz-settings-area' ).hasClass( 'open-settings-menu' ) ) {
				$( '.yz-settings-area' ).toggleClass( 'open-settings-menu'  );
				$( '.yz-settings-area .yz-settings-menu' ).fadeOut();
			}
			// Show / Hide Navigation Menu
			$( this ).toggleClass( 'is-active' );
	        $( '.yz-profile-navmenu' ).fadeToggle( 600 );
		});
		
		/**
		 * # Hide Modal if user clicked Close Button or Icon
		 */
		$( document ).on( 'click', '.yz-wall-modal-close' , function( e ) {

			e.preventDefault();

			// Hide Form.
			$( '.yz-wall-modal' ).removeClass( 'yz-wall-modal-show' );
	        $( '.yz-wall-modal-overlay' ).fadeOut( 600 );

			setTimeout(function(){
			   // wait for card1 flip to finish and then flip 2
			   $( '.yz-wall-modal' ).remove();
			}, 500);

		});
		
		/**
		 * # Check is String is Json Code.
		 */
		$.yz_isJSON = function ( str ) {

		    if ( typeof( str ) !== 'string' ) { 
		        return false;
		    }

		    try {
		        JSON.parse( str );
		        return true;
		    } catch ( e ) {
		        return false;
		    }
		}
		
		/**
		 * # Hide Modal if user clicked Close Button or Icon
		 */
		$( document ).on( 'click', '.yz-modal-close, .yz-modal-close-icon' , function( e ) {

			e.preventDefault();

			// Hide Black Overlay
			$( '.yz-modal-overlay' ).fadeOut( 500 );

			// Get Data.
			var modal = $( this ).closest( '.yz-modal' ).fadeOut( 300, function() {
				$( this ).remove();
			});

		});

		// Hide Modal If User Clicked Escape Button
		$( document ).keyup( function( e ) {
			if ( $( '.yz-modal-show' )[0] ) {
			    if ( e.keyCode === 27 ) {
				    $( '.yz-modal-close' ).trigger( 'click' );
			    }
			}
			return false;
		});

		// # Hide Modal if User Clicked Outside
		$( document ).mouseup( function( e ) {
		    if ( $( '.yz-modal-overlay' ).is( e.target ) && $( '.yz-modal-show' )[0] ) {
				$( '.yz-modal-close' ).trigger( 'click' );
		    }
		    return false;
		});
	});

})( jQuery );