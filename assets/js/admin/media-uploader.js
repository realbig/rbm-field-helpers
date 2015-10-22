/**
 Allows media uploader to be used in the backend.

 @since 1.0.0
 @package KidNiche
 */
(function ($) {
    'use strict';

    window['rbm_instantiate_media_uploaders'] = function () {

        var $uploaders = $('.rbm-media-uploader');

        if (!$uploaders.length) {
            return;
        }

        $uploaders.each(function () {

            // Skip if already instantiated
            if ($(this).data('rbm_media_frame')) {
                return true;
            }

            // Sets up the image library frame
            var $uploader = $(this),
                args = {
                    title: $uploader.data('title'),
                    button: {text: $uploader.data('button-text')}
                },
                type = $uploader.data('type');

            if (type && type !== 'any') {
                args.library = {type: type};
            }

            $uploader.data('rbm_media_frame', wp.media.frames.meta_image_frame = wp.media(args));
            $uploader.find('.remove-media').off();
            $uploader.find('.upload-media').off();

            switch (type) {

                case 'image':
                    $uploader.data('rbm_media_frame').on('select', function () {

                        var media_attachment = $uploader.data('rbm_media_frame').state().get('selection').first().toJSON();

                        // Sends the attachment URL to our custom image input field.
                        $uploader.find('.media-id').val(media_attachment.id);
                        $uploader.find('.image-preview').attr('src', media_attachment.url);

                        $uploader.find('.upload-media').hide();
                        $uploader.find('.remove-media').show();
                    });

                    $uploader.find('.remove-media').click(function (e) {

                        var $image_preview = $(this).siblings('.image-preview');
                        $image_preview.attr('src', $image_preview.data('placeholder') || '');
                    });
                    break;

                default:
                    $uploader.data('rbm_media_frame').on('select', function () {

                        var media_attachment = $uploader.data('rbm_media_frame').state().get('selection').first().toJSON();

                        $uploader.find('.media-id').val(media_attachment.id);
                        $uploader.find('.media-url').html(media_attachment.url);

                        $uploader.find('.upload-media').hide();
                        $uploader.find('.remove-media').show();
                    });

                    $uploader.find('.remove-media').click(function (e) {

                        var $media_url = $(this).siblings('.media-url');
                        $media_url.html($media_url.data('placeholder') || '&nbsp;');
                    });
            }

            // Runs when the image button is clicked.
            $uploader.find('.upload-media').click(function (e) {

                e.preventDefault();

                $uploader.data('rbm_media_frame').open();
            });

            $uploader.find('.remove-media').click(function (e) {

                $(this).siblings('.upload-media').show();
                $(this).hide();
                $(this).siblings('.media-id').val('');
            });
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
    $(document).on('rbm-repeater-add', rbm_instantiate_media_uploaders);
    $(document).on('rbm-repeater-add', repeater_reset_uploader);
})(jQuery);