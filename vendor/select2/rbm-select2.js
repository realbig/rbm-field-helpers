(function ($) {
    'use strict';

    $(function () {

        $('select.rbm-select2:visible').each(function () {
            $(this).select2();
        });
    });
})(jQuery);