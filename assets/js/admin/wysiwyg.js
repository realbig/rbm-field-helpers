(function ($) {
    'use strict';

    $(function () {

        var $repeaters = $('.rbm-field-repeater');

        if (!$repeaters.length ) {
            return;
        }

        $repeaters.on('add-item list-update', repeater_change);
    });

    function replace_wysiwygs($wysiwygs) {

        $wysiwygs.each(function () {

            var $textarea = $(this).find('textarea.wp-editor-area'),
                editor = tinymce.editors[$(this).data('id')],
                $template = $('<textarea type="text" name="' + $textarea.attr('name') + '"></textarea>'),
                $wysiwyg = $(this).find('.wp-editor-wrap'),
                content;

            if (editor) {
                content = editor.getContent();
            } else {
                content = $textarea.val();
            }

            $template.val(content).hide();
            $wysiwyg.before('<p class="description">Please Update or Save Draft to edit this content.</p>');
            $wysiwyg.replaceWith($template)
        });
    }

    function repeater_change(e, $repeater ) {

        var $wysiwygs = $repeater.find('.rbm-field-wysiwyg');

        if ($wysiwygs.length) {
            replace_wysiwygs($wysiwygs);
        }
    }
})(jQuery);