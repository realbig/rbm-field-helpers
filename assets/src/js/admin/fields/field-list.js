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

        super($field, 'list');

        this.initField();
    }

    /**
     * Initializes the list.
     *
     * @since {{VERSION}}
     */
    initField() {

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
     *
     * @param {jQuery} $root Root element to initialize fields inside.
     */
    constructor($root) {

        const api = this;

        this.fields = [];

        let $fields = $root.find('[data-fieldhelpers-field-list]');

        if ( $fields.length ) {

            if ( !jQuery.isFunction(jQuery.fn.sortable) ) {

                console.error('Field Helpers Error: Trying to initialize List field but "jquery-ui-sortable" ' +
                    'is not enqueued.');
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
            api: new FieldList($field),
        });
    }
}

export default FieldListInitialize;