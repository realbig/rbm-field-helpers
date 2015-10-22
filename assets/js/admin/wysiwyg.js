(function ($) {
    'use strict';

    function replace_wysiwygs(e, $field) {

        var $wysiwygs = $field.find('.rbm-field-wysiwyg');

        if (!$wysiwygs.length) {
            return;
        }

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

    $(document).on('rbm-repeater-list-update', replace_wysiwygs);
    $(document).on('rbm-repeater-add', replace_wysiwygs);
})(jQuery);