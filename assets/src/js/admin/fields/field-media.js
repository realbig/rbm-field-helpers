import Field from './field.js';

/**
 * Media Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldMedia extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field, 'media');

        this.initField();
    }

    /**
     * Initializes the Media field.
     *
     * @since {{VERSION}}
     */
    initField() {

        this.$ui = {
            input: this.$field.find('[data-media-input]'),
            addButton: this.$field.find('[data-add-media]'),
            imagePreview: this.$field.find('[data-image-preview]'),
            mediaPreview: this.$field.find('[data-media-preview]'),
            removeButton: this.$field.find('[data-remove-media]'),
        }

        this.mediaFrame = wp.media.frames.meta_image_frame = wp.media({
            title: this.options.l10n['window_title'],
        });

        this.placeholder = this.options.placeholder;
        this.type        = this.options.type

        this.imageProperties = {
            previewSize: this.options.previewSize,
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

                this.$ui.imagePreview.attr('src', this.placeholder || '');

                break;

            default:

                this.$ui.mediaPreview.html(this.placeholder || '&nbsp;');
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
     *
     * @param {jQuery} $root Root element to initialize fields inside.
     */
    constructor($root) {

        const api = this;

        this.fields = [];

        let $fields = $root.find('[data-fieldhelpers-field-media]');

        if ( $fields.length ) {

            if ( !wp.media ) {

                console.error('Field Helpers Error: Trying to initialize Media field but media is not enqueued.');
                return;
            }

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