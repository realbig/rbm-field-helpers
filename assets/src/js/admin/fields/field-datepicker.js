import Field from './field.js';

/**
 * Date Picker Field functionality.
 *
 * Also includes Date/Time Picker and Time Picker.
 *
 * @since {{VERSION}}
 */
class FieldDatePicker extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field, 'datepicker');

        this.initField();
    }

    /**
     * Initializes the Date Picker.
     *
     * @since {{VERSION}}
     */
    initField() {

        this.$hiddenField = this.$field.next('input[type="hidden"]');

        let option_functions = ['beforeShow', 'beforeShowDay', 'calculateWeek', 'onChangeMonthYear', 'onClose', 'onSelect'];

        // Function support
        jQuery.each(this.options.datepickerOptions, (name, value) => {

            if ( option_functions.indexOf(name) !== -1 &&
                !jQuery.isFunction(this.options.datepickerOptions[name]) &&
                jQuery.isFunction(window[value]) ) {

                this.options.datepickerOptions[name] = window[value];
            }
        });

        this.options.datepickerOptions.altField  = this.$hiddenField;

        this.$field.datepicker(this.options.datepickerOptions);
    }

    /**
     * Cleans up after a repeater add/init.
     *
     * @since {{VERSION}}
     */
    fieldCleanup() {

        this.$field
            .removeClass('hasDatepicker')
            .removeAttr('id');
    }
}

/**
 * Finds and initializes all Date Picker fields.
 *
 * @since {{VERSION}}
 */
class FieldDatePickerInitialize {

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

        let $fields = $root.find('[data-fieldhelpers-field-datepicker]');

        if ( $fields.length ) {

            if ( !jQuery.isFunction(jQuery.fn.datepicker) ) {

                console.error('Field Helpers Error: Trying to initialize Date Picker field but "jquery-ui-datepicker" ' +
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
            api: new FieldDatePicker($field),
        });
    }
}

export default FieldDatePickerInitialize;