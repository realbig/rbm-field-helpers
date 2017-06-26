(function ($) {
    'use strict';

    $(function () {

        var $datepickers = $('.rbm-field-datepicker'),
            $repeaters = $('.rbm-field-repeater');

        if ($repeaters.length) {

            $repeaters.on('add-item', datepicker_repeater_reinitialize);

            // Reinitializes on init so that datepicker initializes with proper name attribute that the repeater sets.
            $repeaters.on('repeater-init', datepicker_repeater_reinitialize);
        }

        if ($datepickers.length) {

            datepickers_init($datepickers);
        }

        /**
         * Takes the datepickers, "un-initializes" them, then initializes
         * them again.
         *
         * @since {{VERSION}}
         *
         * @param e
         * @param $item
         */
        function datepicker_repeater_reinitialize(e, $item) {

            var $datepickers = $('.rbm-field-datepicker');

            // Reset them
            $datepickers.find('input[type="text"]').removeClass('hasDatepicker').removeAttr('id');
            datepickers_init($datepickers);
        }

        /**
         * Initializes datepickers.
         *
         * @since {{VERSION}}
         *
         * @param $datepickers
         */
        function datepickers_init($datepickers) {

            $datepickers.each(function (){

                var name = $(this).find('input[type="hidden"]').attr('name'),
                    option_functions = ['beforeShow', 'beforeShowDay', 'calculateWeek', 'onChangeMonthYear', 'onClose', 'onSelect'],
                    options = {};

                if (RBM_FieldHelpers['datepicker_args_' + name]) {
                    options = RBM_FieldHelpers['datepicker_args_' + name];
                }

                // Function support
                $.each(options, function (name, value ) {
                    if (option_functions.indexOf(name) !== -1) {
                        options[name] = window[value];
                    }
                });

                options['altField'] = '[name="' + name + '"]';
                options['altFormat'] = 'yymmdd';

                $(this).find('.rbm-field-datepicker-preview').datepicker(options);
            });
        }
    });
})(jQuery);