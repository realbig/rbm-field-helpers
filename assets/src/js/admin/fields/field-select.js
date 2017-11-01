import Field from './field.js';

/**
 * List Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldList extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field);

        let name = this.$field.attr('data-fieldhelpers-field-list');

        if ( typeof RBM_FieldHelpers[this.instance]['listFields'] === 'undefined' ) {

            console.error('Field Helpers Error: Data for List fields cannot be found.');
            return;
        }

        if ( typeof RBM_FieldHelpers[this.instance]['listFields'][name] === 'undefined' ) {

            console.error(`Field Helpers Error: Cannot find field options for list field with name: ${name}.`);
            return;
        }

        this.options = RBM_FieldHelpers[this.instance]['listFields'][name];

        this.listInit();
    }

    /**
     * Initializes the list.
     *
     * @since {{VERSION}}
     */
    listInit() {

        this.$field.sortable(this.options);
    }
}

/**
 * Finds and initializes all List fields.
 *
 * @since {{VERSION}}
 */
class FieldListInitialize {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor() {

        const api = this;

        this.fields = [];

        let $fields = jQuery('[data-fieldhelpers-field-list]');

        if ( !jQuery.isFunction(jQuery.fn.sortable) ) {

            console.error('Field Helpers Error: Trying to initialize List field but "jquery-ui-sortable" ' +
                'is not enqueued.');
            return;
        }

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
            api: new FieldList($field),
        });
    }
}

export default FieldListInitialize;