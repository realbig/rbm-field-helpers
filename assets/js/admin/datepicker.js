(function ($) {
    'use strict';

    $(function () {

        var $datepickers = $('.rbm-field-datepicker');

        if (!$datepickers.length) {
            return;
        }

        datepickers_init($datepickers);

        $('.rbm-field-repeater').on('add-item', datepicker_repeater_add);

        function datepicker_repeater_add(e, $item) {

            var $datepickers = $item.find('.rbm-field-datepicker');

            // Reset them
            $datepickers.find('input[type="text"]').removeClass('hasDatepicker');
            datepickers_init($datepickers);
        }

        // TODO Get this working... also make sure all other repeater items are firing on the new "add-item" trigger

        function datepickers_init($datepickers) {

            $datepickers.each(function (){
                console.log($(this));
                $(this).find('input[type="text"]').datepicker($(this).data());
            });
        }
    });
})(jQuery);