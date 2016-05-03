(function ($) {
    'use strict';

    $(function () {

        var $datepickers = $('.rbm-field-datepicker'),
            $repeaters = $('.rbm-field-repeater');

        if ($datepickers.length) {
            datepickers_init($datepickers);
        }

        if ($repeaters.length) {
            $repeaters.on('add-item', datepicker_repeater_add);
        }

        function datepicker_repeater_add(e, $item) {

            var $datepickers = $('.rbm-field-datepicker');

            // Reset them
            $datepickers.find('input[type="text"]').removeClass('hasDatepicker').removeAttr('id');
            datepickers_init($datepickers);
        }

        function datepickers_init($datepickers) {

            $datepickers.each(function (){
                $(this).find('input[type="text"]').datepicker($(this).data());
            });
        }
    });
})(jQuery);