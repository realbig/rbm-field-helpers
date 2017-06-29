(function ($) {
    'use strict';
	
	var majorVersion = RBM_FieldHelpers.wp_version.split( '.' )[0].toString(),
		minorVersion = RBM_FieldHelpers.wp_version.split( '.' )[1].toString();

	var legacy = true;
	if ( majorVersion == '4' && minorVersion >= '8' ||
	   majorVersion.toString() > '4' ) {
		legacy = false;
	}

    $(function () {

        var $repeaters = $('.rbm-field-repeater');

        if (!$repeaters.length ) {
            return;
        }

        $repeaters.on('add-item list-update delete-item', repeater_change);

    });

    function replace_wysiwygs($wysiwygs) {

        // Get Name set in WYSIWYG-Repeater Instance
        // Only have to check for the first WYSIWYG in the Repeater since repeater-list will be the same for all of them
        var name = $( $wysiwygs[0] ).closest( '.rbm-field-repeater-list' ).data( 'repeater-list' );

        var re = new RegExp('(' + name + '_)\\d(_.*?)', 'i');

        // For deletion of rows, we need to ensure that the name is properly indexed as well.
        var nameRe = new RegExp('(' + name + '\\[)\\d(\\]\\[.*?\\])', 'i');

        // Destroy ALL tinyMCE instances for our repeater
        // Otherwise when we delete and re-index, we will have an extra textarea that appears in some cases
        var activeEditors = tinymce.editors.slice(0);
        for ( var index = 0; index < activeEditors.length; index++ ) {
            if ( activeEditors[index].id.indexOf( name ) > -1 ) {
                tinymce.execCommand( 'mceRemoveEditor', true, activeEditors[index].id );
            }
        }
        
        $wysiwygs.each( function( index, element ) {

            var editorID = $( element ).attr( 'data-id' ).replace( re, "$1" + index + "$2" );

            $( element ).attr( 'data-id', editorID );

            $( element ).find( '.button' ).each( function() {

                if ( $( this ).data( 'editor' ) ) {

                    $( this ).attr( 'data-editor', '_rbm_' + editorID ); // This is due to the wp_editor being instantiated via PHP as it is
                    $( this ).data( 'editor', '_rbm_' + editorID ); // This element actually checks against data rather than the attr, but lets change both

                }

            } );

            $( element ).find( '[id*=' + name + ']' ).each( function() {

                $( this ).attr( 'id', $( this ).attr( 'id' ).replace( re, "$1" + index + "$2" ) );

                if ( $( this ).data( 'wp-editor-id' ) ) {

                    $( this ).attr( 'data-wp-editor-id', '_rbm_' + editorID );

                }


            } );

            var $textarea = $( element ).find('textarea.wp-editor-area');

            $textarea.attr( 'name', $textarea.attr( 'name' ).replace( nameRe, "$1" + index + "$2" ) );

            var tinymceArgs = {
                selector: $textarea.attr( 'id' ),  // change this value according to your HTML
                plugins: 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview,wpembed',
                content_css: '/wp-includes/css/dashicons.css,/wp-includes/js/tinymce/skins/wordpress/wp-content.css',
                menubar: false,
                toolbar1: 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,dfw,wp_adv,currentprojects,singleproject',
                toolbar2: 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                toolbar3: '',
                toolbar4: '',
				skin: false,
            };
			
			// The wpembed plugin was removed in WP v4.8
			if ( ! legacy ) {
				
				tinymceArgs.plugins = tinymceArgs.plugins.replace( /wpembed,?/gi, '' );
				
			}

            // Load Teeny toolbar for the WYSIWYG if the "teeny" wysiwyg_arg is set to TRUE
            if ( $( element ).hasClass( 'teeny' ) ) {

                tinymceArgs.toolbar1 = 'bold,italic,underline,blockquote,strikethrough,bullist,numlist,alignleft,aligncenter,alignright,undo,redo,link,unlink,fullscreen';
                tinymceArgs.toolbar2 = '';

            }

            // We need to store our settings in this global object. Just the way TinyMCE operates apparently.
            tinyMCEPreInit.mceInit['_rbm_' + $textarea.attr( 'id' )] = tinymceArgs;

            tinymce.init( tinymceArgs );

            tinymce.execCommand( 'mceAddEditor', false, $textarea.attr( 'id' ) );

            // Add our WYSIWYG to the Quicktags Instances
            quicktags( {
                id: $textarea.attr( 'id' ),
                buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
            } );

            // Add Quicktags buttons to our new editor instance
            QTags._buttonsInit();

            // Switch to the appropriate tab per-editor and prevent weird graphical bugs if window.getUserSetting( 'editor' ) is set to 'html'
            var mode = $( element ).find( '.wp-editor-wrap' ).hasClass( 'html-active' ) ? 'html' : 'tmce';
			
            switchEditors.go( $textarea.attr( 'id' ), mode );
			
			if ( ! legacy && 
				mode == 'html' ) {
				
				// Wait a sec for some DOM to load, then force "Click" the tab to ensure things /fully/ switch over
				// This is only a problem with the HTML/Text tab. The new Editor JS API additions really only made using these functions more hacky
				setTimeout( function() {
					$( element ).find( '.wp-switch-editor.switch-html' ).click();
				}, 100 );
				
			}

        });

    }

    function repeater_change(e, $repeaterRow ) {

        var $wysiwygs = $repeaterRow.parent().find('.rbm-field-wysiwyg');

        if ($wysiwygs.length) {
            replace_wysiwygs($wysiwygs);
        }
    }

})(jQuery);