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