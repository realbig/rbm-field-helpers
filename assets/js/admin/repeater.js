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

                    $(this).trigger('add-item', [$(this)]);
                },
                hide: function () {

                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp( {
                            duration: 400,
                            complete: function () {
                                // We can't call events on the removed item, so we will call it on the main Repeater
                                var $repeater = $(this).closest('.rbm-field-repeater');
                                $(this).remove();
                                $repeater.trigger('delete-item', [$repeater]);
                            },
                        } );
                    }
                },
                isFirstItemUndeletable: true
            });

            $list.sortable({
                axis: 'y',
                handle: '.rbm-field-repeater-handle',
                forcePlaceholderSize: true,
                placeholder: 'rbm-sortable-placeholder',
                update: function (e, ui) {

                    // Make sure the int in each attr name is the same as the row index
                    $list.find('.rbm-field-repeater-row').each(function () {

                        $(this).find('input, select, textarea').filter('[name^="' + name + '"]').each(function () {

                            var index = $(this).closest('.rbm-field-repeater-row').index(),
                                attr_name = $(this).attr('name'),
                                re = new RegExp('(' + name + '\\[)\\d(\\]\\[.*?\\])', 'i');

                            $(this).attr('name', attr_name.replace(re, "$1" + index + "$2"));
                        });
                    });

                    $list.trigger('list-update', [$(this).closest('.rbm-field-repeater')]);
                }
            });

            $(this).find('.rbm-field-repeater-row.dummy').remove();
        });

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