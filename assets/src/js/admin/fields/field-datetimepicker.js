import Field from './field.js';

/**
 * Date Time Picker Field functionality.
 *
 * Also includes Date/Time Picker and Time Picker.
 *
 * @since {{VERSION}}
 */
class FieldDateTimePicker extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field, 'datetimepicker');

        this.initField();
    }

    /**
     * Initializes the Date Time Picker.
     *
     * @since {{VERSION}}
     */
    initField() {

        this.$hiddenField = this.$field.next('input[type="hidden"]');

        let option_functions = ['beforeShow', 'beforeShowDay', 'calculateWeek', 'onChangeMonthYear', 'onClose', 'onSelect'];

        // Function support
        jQuery.each(this.options.datetimepickerOptions, (name, value) => {

            if ( option_functions.indexOf(name) !== -1 &&
                !jQuery.isFunction(this.options.datetimepickerOptions[name]) &&
                jQuery.isFunction(window[value]) ) {

                this.options.datetimepickerOptions[name] = window[value];
            }
        });

        this.options.datetimepickerOptions.altField = this.$hiddenField;

        this.$field.datetimepicker(this.options);
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
 * Finds and initializes all Date Time Picker fields.
 *
 * @since {{VERSION}}
 */
class FieldDateTimePickerInitialize {

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

        let $fields = $root.find('[data-fieldhelpers-field-datetimepicker]');

        if ( $fields.length ) {

            if ( !jQuery.isFunction(jQuery.fn.datetimepicker) ) {

                console.error('Field Helpers Error: Trying to initialize Date Time Picker field but ' +
                    '"rbm-fh-jquery-ui-datetimepicker" is not enqueued.');
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
            api: new FieldDateTimePicker($field),
        });
    }
}

export default FieldDateTimePickerInitialize;