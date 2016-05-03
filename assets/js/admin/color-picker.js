(function ($) {
    'use strict';

    $(function () {

        var $colorpickers = $('.rbm-field-colorpicker'),
            $repeaters = $('.rbm-field-repeater');

        if ($colorpickers.length) {
            colorpickers_init($colorpickers);
        }

        if ($repeaters.length) {
            $repeaters.on('add-item', colorpicker_repeater_add);
        }

        function colorpicker_repeater_add(e, $item) {

            var $colorpickers = $item.find('.rbm-field-colorpicker');

            // Reset them
            $colorpickers.find('input[name^="_rbm_repeater"]').appendTo($item.find('label'));
            $colorpickers.find('.wp-picker-container').remove();
            colorpickers_init($colorpickers);
        }

        function colorpickers_init($colorpickers) {

            $colorpickers.each(function () {
                $(this).find('input[type="text"]').wpColorPicker($(this).data());
            });
        }
    });
})(jQuery);