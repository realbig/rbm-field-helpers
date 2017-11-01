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

        this.$field.select2(this.select2Options);
    }

    /**
     * Resets the field.
     *
     * @since {{VERSION}}
     */
    fieldCleanup() {

        let $oldSelect = this.$field.next('.select2');

        if ( $oldSelect.length ) {

            $oldSelect.remove();
        }

        this.$field
            .removeClass('select2-hidden-accessible')
            .removeAttr('tablindex aria-hidden')
            .find('option:selected').prop('selected', false);
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

            if ( !jQuery.isFunction(jQuery.fn.select2) ) {

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
            api: new FieldSelect($field),
        });
    }
}

export default FieldSelectInitialize;