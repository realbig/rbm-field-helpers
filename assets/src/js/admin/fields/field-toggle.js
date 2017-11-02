import Field from './field.js';

/**
 * Toggle Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldToggle extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field, 'toggle');

        this.initField();
    }

    /**
     * Initializes the select.
     *
     * @since {{VERSION}}
     */
    initField() {

        this.$ui = {
            slider: this.$field.find('.fieldhelpers-field-toggle-slider'),
            input: this.$field.find('input[type="checkbox"]'),
        }

        this.setupHandlers();
    }

    /**
     * Sets up class handlers.
     *
     * @since {{VERSION}}
     */
    setupHandlers() {

        const api = this;

        this.$ui.slider.click(() => {
            api.handleClick();
        });
    }

    /**
     * Fires on toggle change.
     *
     * @since {{VERSION}}
     */
    handleClick() {

        this.$ui.input.prop('checked', !this.$ui.input.prop('checked'));
    }
}

/**
 * Finds and initializes all Toggle fields.
 *
 * @since {{VERSION}}
 */
class FieldToggleInitialize {

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

        let $fields = $root.find('[data-fieldhelpers-field-toggle]');

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
            api: new FieldToggle($field),
        });
    }
}

export default FieldToggleInitialize;