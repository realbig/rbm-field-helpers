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

        this.getUI();

        // Initial change trigger to help other plugins
        setTimeout(() => {
            this.$field.trigger('change', [this.$ui.input.val()]);
        }, 1);

        this.setupHandlers();
    }

    /**
     * Retrieves the UI.
     *
     * @since {{VERSION}}
     */
    getUI() {

        this.$ui = {
            slider: this.$field.find('.fieldhelpers-field-toggle-slider'),
            input: this.$field.find('input[type="hidden"]'),
        }
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
     * Return if field is checked or not.
     *
     * @since {{VERSION}}
     *
     * @returns {*}
     */
    isChecked() {

        return this.$field.hasClass('checked');
    }

    /**
     * Fires on toggle change.
     *
     * @since {{VERSION}}
     */
    handleClick() {

        if ( this.isChecked() ) {

            this.$ui.input.val(this.options.uncheckedValue);
            this.$field.removeClass('checked');

        } else {

            this.$ui.input.val(this.options.checkedValue);
            this.$field.addClass('checked');
        }

        this.$field.trigger('change', [this.$ui.input.val()]);
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