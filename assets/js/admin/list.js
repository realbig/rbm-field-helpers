(function ($) {
    'use strict';

    $(function () {

        var $lists = $('.rbm-field-list'),
            $repeaters = $('.rbm-field-repeater');

        if ($lists.length) {

            lists_init($lists);
        }

        if ($repeaters.length) {

            $repeaters.on('add-item', list_repeater_add);
        }

        function list_repeater_add(e, $item) {

            var $lists = $item.find('.rbm-field-list');

            // Reset them


            lists_init($lists);
        }

        function lists_init($lists) {

            $lists.each(function () {

                var name = $(this).attr('data-name'),
                    sortable_args = {};

                // Make sure we can access field data
                if (!name ||
                    typeof RBM_FieldHelpers == 'undefined' ||
                    typeof RBM_FieldHelpers['list_fields'] == 'undefined' ||
                    typeof RBM_FieldHelpers['list_fields'][name] == 'undefined'
                ) {

                    return;
                }

                sortable_args = RBM_FieldHelpers['list_fields'][name];

                $(this).find('ul[data-rbm-list]').sortable(sortable_args);
            });
        }
    });
})(jQuery);