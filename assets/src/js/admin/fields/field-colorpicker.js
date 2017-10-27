/**
 * Color Picker Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldColorPicker {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        if (!jQuery.isFunction(jQuery.fn.wpColorPicker)) {

            console.error('Field Helpers Error: Trying to initialize Color Picker field but "wp-color-picker" is ' +
                'not enqueued.');
            return;
        }

        $field.wpColorPicker();
    }
}

/**
 * Finds and initializes all Color Picker fields.
 *
 * @since {{VERSION}}
 */
class FieldColorPickerInitialize {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor() {

        const api = this;

        this.fields = [];

        let $fields = jQuery('[data-fieldhelpers-field-colorpicker]');

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
            api: new FieldColorPicker($field),
        });
    }
}

export default FieldColorPickerInitialize;