(function ($) {
    'use strict';

    $(function () {

        var $datetimepickers = $('.rbm-field-datetimepicker'),
            $repeaters = $('.rbm-field-repeater');

        if ($datetimepickers.length) {
            datetimepickers_init($datetimepickers);
        }

        if ($repeaters.length) {
            $repeaters.on('add-item', datetimepicker_repeater_add);
        }

        function datetimepicker_repeater_add(e, $item) {

            var $datetimepickers = $('.rbm-field-datetimepicker');

            // Reset them
            $datetimepickers.find('input[type="text"]').removeClass('hasDatepicker').removeAttr('id');
            datetimepickers_init($datetimepickers);
        }

        function datetimepickers_init($datetimepickers) {

            $datetimepickers.each(function (){

                var name = $(this).find('input[type="hidden"]').attr('name'),
                    input_ID = '#rbm-field-datetimepicker-input-' + name,
                    option_functions = ['beforeShow', 'beforeShowDay', 'calculateWeek', 'onChangeMonthYear', 'onClose', 'onSelect'],
                    options = {};

                if (RBM_FieldHelpers['datetimepicker_args_' + name]) {
                    options = RBM_FieldHelpers['datetimepicker_args_' + name];
                }

                // Function support
                $.each(options, function (name, value ) {
                    if (option_functions.indexOf(name) !== -1) {
                        options[name] = window[value];
                    }
                });
				
				options.altField = input_ID;
                options.altFormat = 'yymmdd';
				options.altTimeFormat = 'HH:mm';
				options.altFieldTimeOnly = false;
				options.timeFormat = 'hh:mm tt';
				options.controlType = 'select';

                $(this).find('.rbm-field-datetimepicker-preview').datetimepicker(options);
            });
        }
    });
})(jQuery);