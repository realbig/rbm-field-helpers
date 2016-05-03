/**
 Allows media uploader to be used in the backend.

 @since 1.0.0
 @package KidNiche
 */
(function ($) {
    'use strict';

    window['rbm_instantiate_media_uploaders'] = function () {

        var media_frame, $current_uploader;

        if (!wp.media) {
            return;
        }

        media_frame = wp.media.frames.meta_image_frame = wp.media({
            title: 'Choose Media'
        });

        media_frame.on('select', function () {

            var media_attachment = media_frame.state().get('selection').first().toJSON();

            $current_uploader.find('.media-id').val(media_attachment.id);

            $current_uploader.find('.upload-media').hide();
            $current_uploader.find('.remove-media').show();

            // Preview
            switch ($current_uploader.data('type')) {
                case 'image':
                    $current_uploader.find('.image-preview').attr('src', media_attachment.url);
                    break;

                default:
                    $current_uploader.find('.media-url').html(media_attachment.url);
            }
        });

        $(document).on('click', '.rbm-media-uploader .upload-media', function (e) {

            e.preventDefault();

            $current_uploader = $(this).closest('.rbm-media-uploader');

            var type = $current_uploader.data('type');

            media_frame.open();

            // TODO Not currently working
            //media_frame.title = $current_uploader.data('title');
            //media_frame.button = {text: $current_uploader.data('button-text')};
            //
            //if (type && type !== 'any') {
            //    media_frame.library = {type: type};
            //}
        });

        $(document).on('click', '.rbm-media-uploader .remove-media', function (e) {

            $(this).siblings('.upload-media').show();
            $(this).hide();
            $(this).siblings('.media-id').val('');

            $current_uploader = $(this).closest('.rbm-media-uploader');

            // Reset preview
            switch ($current_uploader.data('type')) {
                case 'image':

                    var $image_preview = $(this).siblings('.image-preview');
                    $image_preview.attr('src', $image_preview.data('placeholder') || '');

                    break;

                default:

                    var $media_url = $(this).siblings('.media-url');
                    $media_url.html($media_url.data('placeholder') || '&nbsp;');
            }

            $current_uploader = false;
        });
    };

    function repeater_reset_uploader(e, $field) {

        var $media_fields = $field.find('.rbm-field-media');

        if (!$media_fields.length) {
            return;
        }

        $media_fields.find('.remove-media').click();
    }

    $(rbm_instantiate_media_uploaders);
    //$(document).on('rbm-repeater-add', rbm_instantiate_media_uploaders);
    //$(document).on('rbm-repeater-add', repeater_reset_uploader);
})(jQuery);