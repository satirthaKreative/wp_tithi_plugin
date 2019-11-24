( function( $ ) {
  'use strict';
  $( document ).ready( function() {

    if ( jQuery().emojioneArea ) {

        // Activate Emojis in Posts.
      if ( Yz_Emoji.posts_visibility == 'on' ) {
          var el = $( ".yz-wall-textarea" ).emojioneArea( { pickerPosition: 'bottom' } );
        }
            
        // Activate Emojis in Posts Comments.
      if ( Yz_Emoji.comments_visibility == 'on' ) {

        // Init Comments Emoji Function
        $.yz_init_comments_emoji = function() {
          var yz_emoji_textarea = $( '.youzer .ac-form textarea' ).emojioneArea( { pickerPosition: 'bottom' } );
          return yz_emoji_textarea;
        }

        // Init Vars.
        var origAppend = $.fn.append,
          comment_el = $.yz_init_comments_emoji();

        // Overrding Append Function.
          $.fn.append = function () {
              return origAppend.apply( this, arguments ).trigger( 'append' );
          };

          // Reset Reply Form after submit.
          $( 'body' ).on( 'append','.activity-comments ul', function(){
            // Clean Textarea.
            if ( $( this ).parent().find( '.ac-form textarea' ).get(0) ) {
              $( this ).parent().find( '.ac-form textarea' ).get(0).emojioneArea.setText( '' );
            }
          });

          // Reload Emoji Comments After Loading More Posts.
          $( document ).ajaxComplete(function() {
            $.yz_init_comments_emoji();
        });

      }

      // Activate Emojis in Messages.
      if ( Yz_Emoji.messages_visibility == 'on' ) {
        // Enable Emoji.
        var message_el = $( '#send-reply textarea,.yzmsg-form-item #message_content' )
        .emojioneArea( { pickerPosition: 'bottom' } );
        // Override Val Function.
        var originalVal = this.originalVal = $.fn.val;
        $.fn.val = function(value) {
            if ( typeof value == 'undefined' ) {
                return originalVal.call( this );
            } else {
                if ( $( this ).attr( 'id' ) == 'message_content' && value == '' ) {
                  $( '#send-reply .emojionearea-editor' ).text( '' );
                }
                return originalVal.call( this, value );
            }
        };
      }

    }

  });

})( jQuery );