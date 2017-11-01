import Field from './field.js';

/**
 * Checkbox Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldCheckbox extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field, 'checkbox');
    }

    /**
     * Initializes the select.
     *
     * @since {{VERSION}}
     */
    initField() {
    }


    /**
     * Sets the ID to be unique, based off the repeater item index.
     *
     * For checkboxes, there will be multiple IDs in each, and need to be set accordingly.
     *
     * @since {{VERSION}}
     */
    repeaterSetID() {

        let ID    = this.options.id;
        let $rows = this.$field.find('.fieldhelpers-field-checkbox-row');
        let index = this.$field.closest('[data-repeater-item]').index();

        $rows.each(function () {

            let $field     = jQuery(this).find('input[type="checkbox"]');
            let $label     = $field.next('label');
            let fieldIndex = jQuery(this).index();
            let newID      = `${ID}_${index}_${fieldIndex}`;

            $field.attr('id', newID);
            $label.attr('for', newID);
        });
    }
}

/**
 * Finds and initializes all Checkbox fields.
 *
 * @since {{VERSION}}
 */
class FieldCheckboxInitialize {

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

        let $fields = $root.find('[data-fieldhelpers-field-checkbox]');

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
            api: new FieldCheckbox($field),
        });
    }
}

export default FieldCheckboxInitialize;