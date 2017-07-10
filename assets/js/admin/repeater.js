(function ($) {
    'use strict';

    $(function () {

        $('.rbm-field-repeater').each(function () {

            var $list = $(this).find('.rbm-field-repeater-list'),
                name = $list.data('repeater-list');

            $(this).repeater({
                show: function () {

                    $(this).slideDown();
                    refresh_selects($(this));
					
					var $repeater = $( this ).closest( '.rbm-field-repeater' );
					
					if ( $repeater.hasClass( 'collapsable' ) ) {
						
						$( this ).addClass( 'opened' ).removeClass( 'closed' );
						
						// Hide current title for new item and show default title
						$( this ).find( '.rbm-field-repeater-header span.collapsable-title' ).html( $( this ).find( '.rbm-field-repeater-header span.collapsable-title' ).data( 'collapsable-title-default' ) );
						
						$( this ).find( '.collapse-icon' ).css({'transform' : 'rotate(-180deg)'});
						
					}

                    $(this).trigger('add-item', [$(this)]);
                },
                hide: function (deleteElement) {

                    if (confirm('Are you sure you want to delete this element?')) {

                        $(this).slideUp(400, deleteElement);
                        $(this).trigger('delete-item', [$(this)]);
                    }
                },
                ready: function (setIndexes) {

                    $list.on('sortupdate', setIndexes);
                },
                isFirstItemUndeletable: false
            });

            $list.sortable({
                axis: 'y',
                handle: '.rbm-field-repeater-handle',
                forcePlaceholderSize: true,
                placeholder: 'rbm-sortable-placeholder',
                update: function (e, ui) {
                    $list.trigger('list-update', [$(this).closest('.rbm-field-repeater')]);
                }
            });

            $(this).find('.rbm-field-repeater-row.dummy').remove();
			
			// Collapsable
			if ( $( this ).hasClass( 'collapsable' ) ) {
				$( this ).find( '.rbm-field-repeater-content' ).hide();
			}

            $(this).trigger('repeater-init', [$(this)]);
        });
		
		$( document ).on( 'click touchend', '.rbm-field-repeater.collapsable [data-repeater-collapsable-handle]', function () {

			var $repeater_field = $( this ).closest( '.rbm-field-repeater-row' ),
				$content = $repeater_field.find( '.rbm-field-repeater-content' ).first(),
				status = $repeater_field.hasClass( 'opened' ) ? 'closing' : 'opening',
				$collapse_icon = $( this ).find( '.collapse-icon' );

			if ( status == 'opening' ) {

				$content.stop().slideDown();
				$collapse_icon.css({'transform' : 'rotate(-180deg)'});
				$repeater_field.addClass( 'opened' );
				$repeater_field.removeClass( 'closed' );

			}
			else {

				$content.stop().slideUp();
				$collapse_icon.css({'transform' : 'rotate(0deg)'});
				$repeater_field.addClass( 'closed' );
				$repeater_field.removeClass( 'opened' );
			}

		} );

        function refresh_selects($e) {

            var $select2s = $e.find('select.rbm-select2');

            $select2s.each(function () {

                var $vestige = $(this).siblings('.select2-container');

                if ($vestige.length) {
                    $vestige.remove();
                }

                $(this).show().select2();
            });
        }
    });
})(jQuery);