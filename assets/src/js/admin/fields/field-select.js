import Field from './field.js';

/**
 * Select Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldSelect extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field, 'select');

        this.initField();
    }

    /**
     * Initializes the select.
     *
     * @since {{VERSION}}
     */
    initField() {

        if ( !this.options.select2Disabled ) {

            if ( !jQuery.isFunction(jQuery.fn.select2) ) {

                console.error('Field Helpers Error: Trying to initialize Select field but "select2" ' +
                    'is not enqueued.');
                return;
            }

            this.setupL10n();

            this.$field.select2(this.options.select2Options);
        }
    }

    /**
     * Sets up languages.
     *
     * @since {{VERSION}}
     */
    setupL10n() {

        if ( Object.keys(this.options.select2Options.language).length > 0 ) {

            Object.keys(this.options.select2Options.language).map((id) => {

                let text = this.options.select2Options.language[id];

                // All languages must be functions. Turn all into functions.
                this.options.select2Options.language[id] = (args) => text;
            });
        }
    }

    /**
     * Resets the field.
     *
     * @since {{VERSION}}
     */
    fieldCleanup() {

        if ( this.options.select2Disabled ) {

            return;
        }

        let $oldSelect = this.$field.next('.select2');

        if ( $oldSelect.length ) {

            $oldSelect.remove();
        }

        this.$field
            .removeClass('select2-hidden-accessible')
            .removeAttr('tablindex aria-hidden');
    }

    /**
     * Sets the field to default. Override in child class if need different method.
     *
     * @since {{VERSION}}
     */
    setDefault() {

        this.$field.find('option:selected').prop('selected', false);
        this.$field.trigger('change');
    }
}

/**
 * Finds and initializes all Select fields.
 *
 * @since {{VERSION}}
 */
class FieldSelectInitialize {

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

        let $fields = $root.find('[data-fieldhelpers-field-select]');

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
            api: new FieldSelect($field),
        });
    }
}

export default FieldSelectInitialize;