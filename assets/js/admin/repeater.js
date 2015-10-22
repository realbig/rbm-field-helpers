(function ($) {
    'use strict';

    $(function () {

        $('.rbm-field-repeater').each(function () {

            var $list = $(this).find('.rbm-field-repeater-list'),
                name = $list.data('repeater-list');

            $(this).repeater({
                show: function () {

                    $(this).slideDown();
                    $(document).trigger('rbm-repeater-add', [$(this)]);
                },
                hide: function () {

                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(400, function () {
                            $(this).remove();
                        });
                        $(document).trigger('rbm-repeater-delete');
                    }
                },
                isFirstItemUndeletable: true
            });

            $list.sortable({
                axis: 'y',
                handle: '.rbm-field-repeater-handle',
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

                    $(document).trigger('rbm-repeater-list-update', [ui.item]);
                }
            });

            $(this).find('.rbm-field-repeater-row.dummy').remove();
        });
    });
})(jQuery);