(function ($) {
    'use strict';

    function color_picker_init() {

        var $fields = $('.rbm-field-colorpicker');

        if (!$fields.length) {
            return;
        }

        $fields.each(function () {

            if ($(this).data('rbm-colorpicker-init')) {
                return true;
            }

            $(this).data('rbm-colorpicker-init', 1);
            $(this).find('input[type="text"]').wpColorPicker();
        });
    }

    $(color_picker_init);
    $(document).on('rbm-repeater-add', color_picker_init);
})(jQuery);