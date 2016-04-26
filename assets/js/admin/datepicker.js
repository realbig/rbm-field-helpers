(function ($) {
    'use strict';

    $(function () {

        datepickers_init();
        $(document).on('rbm-repeater-add', datepickers_init);

        function datepickers_init() {

            $('.rbm-field-datepicker').each(function () {

                if ($(this).data('rbm_datepicker_init')) {
                    return;
                }

                $(this).find('input[type="text"]').datepicker($(this).data()).data('rbm_datepicker_init', 1);
            });
        }
    });
})(jQuery);