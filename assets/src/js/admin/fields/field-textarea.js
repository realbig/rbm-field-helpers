import Field from './field.js';

/**
 * TextArea Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldTextArea extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field, 'textarea');

        if ( this.options.richEditor ) {

            this.richEditorInit();
        }
    }

    /**
     * Initializes the select.
     *
     * @since {{VERSION}}
     */
    richEditorInit() {

        tinymce.EditorManager.execCommand('mceAddEditor', true, this.name);
// this.$field
    }

    /**
     * Resets the field.
     *
     * @since {{VERSION}}
     */
    fieldCleanup() {
    }
}

/**
 * Finds and initializes all TextArea fields.
 *
 * @since {{VERSION}}
 */
class FieldTextAreaInitialize {

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

        let $fields = $root.find('[data-fieldhelpers-field-textarea]');

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
            api: new FieldTextArea($field),
        });
    }
}

export default FieldTextAreaInitialize;