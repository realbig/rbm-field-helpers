/**
 * Media Field functionality.
 *
 * Also includes Date/Time Picker and Time Picker.
 *
 * @since {{VERSION}}
 */
class FieldMedia {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        if ( !wp.media ) {

            console.error('Field Helpers Error: Trying to initialize Date Picker field but media is not enqueued.');
            return;
        }

        this.$ui = {
            input: $field.find('[data-media-input]'),
            addButton: $field.find('[data-add-media]'),
            imagePreview: $field.find('[data-image-preview]'),
            mediaPreview: $field.find('[data-media-preview]'),
            removeButton: $field.find('[data-remove-media]'),
        }

        this.mediaFrame = wp.media.frames.meta_image_frame = wp.media({
            title: 'Choose Media'
        });

        this.type = $field.attr('data-type');

        this.imageProperties = {
            previewSize: $field.attr('data-preview-size'),
        };

        this.setupHandlers();
    }

    /**
     * Sets up the class handlers.
     *
     * @since {{VERSION}}
     */
    setupHandlers() {

        this.$ui.addButton.click((e) => {

            e.preventDefault();
            this.addMedia();
        });

        this.$ui.removeButton.click((e) => {

            e.preventDefault();
            this.removeMedia();
        });

        this.mediaFrame.on('select', (e) => {

            this.selectMedia();
        });
    }

    /**
     * Opens the media frame to add media.
     *
     * @since {{VERSION}}
     */
    addMedia() {

        this.mediaFrame.open();
    }

    /**
     * Removes the currently selected media.
     *
     * @since {{VERSION}}
     */
    removeMedia() {

        this.$ui.addButton.show();
        this.$ui.removeButton.hide();
        this.$ui.input.val('');

        // Reset preview
        switch ( this.type ) {

            case 'image':

                this.$ui.imagePreview.attr('src', this.$ui.imagePreview.data('placeholder') || '');

                break;

            default:

                this.$ui.mediaPreview.html(this.$ui.mediaPreview.data('placeholder') || '&nbsp;');
        }
    }

    /**
     * Fires on selecting a piece of media.
     *
     * @since {{VERSION}}
     */
    selectMedia() {

        let mediaAttachment = this.mediaFrame.state().get('selection').first().toJSON();

        this.$ui.input.val(mediaAttachment.id);

        this.$ui.addButton.hide();
        this.$ui.removeButton.show();

        // Preview
        switch ( this.type ) {

            case 'image':

                let previewUrl = mediaAttachment.url;

                if ( mediaAttachment.sizes[this.imageProperties.previewSize] ) {

                    previewUrl = mediaAttachment.sizes[this.imageProperties.previewSize].url;
                }

                this.$ui.imagePreview.attr('src', previewUrl);
                break;

            default:

                this.$ui.mediaPreview.html(mediaAttachment.url);
        }
    }
}

/**
 * Finds and initializes all Media fields.
 *
 * @since {{VERSION}}
 */
class FieldMediaInitialize {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor() {

        const api = this;

        this.fields = [];

        let $fields = jQuery('[data-fieldhelpers-field-media]');

        if ( $fields.length ) {

            $fields.each(function () {

                api.initializeField(jQuery(this));
            });
        }
    }

    /**
     * Initializes the field.
     *
     * @since {{VERSION}}
     *
     * @param {jQuery} $field
     */
    initializeField($field) {

        this.fields.push({
            $field,
            api: new FieldMedia($field),
        });
    }
}

export default FieldMediaInitialize;