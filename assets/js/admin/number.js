(function ($) {
    'use strict';

    $(document).on('click', '[data-number-increase], [data-number-decrease]', function () {

        var $input = $(this).siblings('.rbm-field-input'),
            interval_normal = parseInt($(this).attr('data-number-interval')),
            interval_alt = parseInt($(this).attr('data-number-alt-interval')),
            interval = window['ld_gb_shift_key_down'] === true ? interval_alt : interval_normal,
            current_value = $input.val(),
            new_value;

        if (current_value === '') {

            current_value = 0;

        } else {

            current_value = parseInt(current_value);
        }

        if (typeof $(this).data('number-increase') != 'undefined') {

            new_value = current_value + interval;

        } else {

            new_value = current_value - interval;
        }

        $input.val(new_value).change();
    });

    $(document).on('keydown', '.rbm-field-number input[type="text"]', function (e) {

        var $button;

        if (e.which === 38) {

            $button = $(this).siblings( '[data-number-increase]');

        } else if (e.which == 40) {

            $button = $(this).siblings( '[data-number-decrease]');
        }

        if ( $button ) {

            $button.click();

            e.preventDefault();
        }
    });

    $(document).on('change', '.rbm-field-number input[type="text"]', function () {

        var max = $(this).siblings('[data-number-increase]').attr('data-number-max'),
            min = $(this).siblings('[data-number-decrease]').attr('data-number-min'),
            value = $(this).val();

        if (max != 'none' && value > parseInt(max)) {

            $(this).val(max);

        } else if (min != 'none' && value < parseInt(min)) {

            $(this).val(min);

        } else {

            // Only allow numbers
            $(this).val(value.match(/\d*\.?\d+/));
        }
    });

})(jQuery);