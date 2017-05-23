(function ($) {
    'use strict';

    $(function () {

        var $timepickers = $('.rbm-field-timepicker'),
            $repeaters = $('.rbm-field-repeater');

        if ($timepickers.length) {
            timepickers_init($timepickers);
        }

        if ($repeaters.length) {
            $repeaters.on('add-item', timepicker_repeater_add);
        }

        function timepicker_repeater_add(e, $item) {

            var $timepickers = $('.rbm-field-timepicker');

            // Reset them
            $timepickers.find('input[type="text"]').removeClass('hasDatepicker').removeAttr('id');
            timepickers_init($timepickers);
        }

        function timepickers_init($timepickers) {

            $timepickers.each(function (){

                var name = $(this).find('input[type="hidden"]').attr('name'),
                    input_ID = '#rbm-field-timepicker-input-' + name,
                    option_functions = ['beforeShow', 'beforeShowDay', 'calculateWeek', 'onChangeMonthYear', 'onClose', 'onSelect'],
                    options = {};

                if (RBM_FieldHelpers['timepicker_args_' + name]) {
                    options = RBM_FieldHelpers['timepicker_args_' + name];
                }

                // Function support
                $.each(options, function (name, value ) {
                    if (option_functions.indexOf(name) !== -1) {
                        options[name] = window[value];
                    }
                });
				
				options.altField = input_ID;
				options.altTimeFormat = 'HH:mm';
				options.altFieldTimeOnly = false;
				options.timeFormat = 'hh:mm tt';
				options.controlType = 'select';

                $(this).find('.rbm-field-timepicker-preview').timepicker(options);
            });
        }
    });
})(jQuery);