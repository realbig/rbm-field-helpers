(function ($) {
    'use strict';

    $(function () {

        $('select.rbm-select2:visible').each(function () {
            $(this).select2();
        });
    });

    $(document).on('rbm-repeater-add', function (e, $e) {
        replace_select_multiple_brackets($e);
        reset_select2($e);
    });

    $(document).on('rbm-repeater-delete', function (e, $e) {
        replace_select_multiple_brackets($e);
    });

    $(document).on('rbm-repeater-init', function (e, $e) {
        replace_select_multiple_brackets($e);
    });

    function reset_select2($e) {

        $e.find('select.rbm-select2').each(function () {
            $(this).siblings('.select2-container').remove();
            $(this).show().select2();
        });
    }

    function replace_select_multiple_brackets($e) {

        $e.closest('.rbm-field-repeater').find('select[multiple]').each(function () {

            var name = $(this).attr('name');

            // Replace messed up brackets
            name = name.replace('[]]', '][]');
            $(this).attr('name', name);

            // Replace non-multiple brackets
            if (name.indexOf('][]') === -1) {
                $(this).attr('name', name + '[]');
            }
        });
    }
})(jQuery);