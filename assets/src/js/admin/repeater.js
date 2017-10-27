(function ($) {
    'use strict';

    $(function () {

        $('.fieldhelpers-field-repeater').each(function () {

            var $list      = $(this).find('.fieldhelpers-field-repeater-list'),
                name       = $list.data('repeater-list'),
                isSortable = $(this).attr('data-is-sortable') === 'yes';

            $(this).repeater({
                show: function () {

                    $(this).slideDown();
                    refresh_selects($(this));

                    var $repeater = $(this).closest('.fieldhelpers-field-repeater');

                    if ( $repeater.hasClass('collapsable') ) {

                        $(this).addClass('opened').removeClass('closed');

                        // Hide current title for new item and show default title
                        $(this).find('.fieldhelpers-field-repeater-header span.collapsable-title').html($(this).find('.fieldhelpers-field-repeater-header span.collapsable-title').data('collapsable-title-default'));

                        $(this).find('.collapse-icon').css({'transform': 'rotate(-180deg)'});

                    }

                    $(this).trigger('add-item', [$(this)]);
                },
                hide: function (deleteElement) {

                    var deleteText = $(this).closest('.fieldhelpers-field-repeater').attr('data-confirm-delete-text');

                    if ( confirm(deleteText) ) {

                        $(this).slideUp(400, deleteElement);
                        $(this).trigger('delete-item', [$(this)]);
                    }
                },
                ready: function (setIndexes) {

                    $list.on('sortupdate', setIndexes);
                },
                isFirstItemUndeletable: false
            });

            if ( isSortable ) {

                $list.sortable({
                    axis: 'y',
                    handle: '.fieldhelpers-field-repeater-handle',
                    forcePlaceholderSize: true,
                    placeholder: 'fieldhelpers-sortable-placeholder',
                    update: function (e, ui) {
                        $list.trigger('list-update', [$(this).closest('.fieldhelpers-field-repeater')]);
                    }
                });
            }

            $(this).find('.fieldhelpers-field-repeater-row.dummy').remove();

            // Collapsable
            if ( $(this).hasClass('collapsable') ) {
                $(this).find('.fieldhelpers-field-repeater-content').hide();
            }

            $(this).trigger('repeater-init', [$(this)]);
        });

        $(document).on('click touchend', '.fieldhelpers-field-repeater.collapsable [data-repeater-collapsable-handle]', function () {

            var $repeater_field = $(this).closest('.fieldhelpers-field-repeater-row'),
                $content        = $repeater_field.find('.fieldhelpers-field-repeater-content').first(),
                status          = $repeater_field.hasClass('opened') ? 'closing' : 'opening',
                $collapse_icon  = $(this).find('.collapse-icon');

            if ( status == 'opening' ) {

                $content.stop().slideDown();
                $collapse_icon.css({'transform': 'rotate(-180deg)'});
                $repeater_field.addClass('opened');
                $repeater_field.removeClass('closed');

            }
            else {

                $content.stop().slideUp();
                $collapse_icon.css({'transform': 'rotate(0deg)'});
                $repeater_field.addClass('closed');
                $repeater_field.removeClass('opened');
            }

        });

        function refresh_selects($e) {

            var $select2s = $e.find('select.fieldhelpers-select2');

            $select2s.each(function () {

                var $vestige = $(this).siblings('.select2-container');

                if ( $vestige.length ) {
                    $vestige.remove();
                }

                $(this).show().select2();
            });
        }
    });
})(jQuery);