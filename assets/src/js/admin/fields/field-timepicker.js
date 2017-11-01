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

        if ( !jQuery.isFunction(jQuery.fn.datepicker) ) {

            console.error('Field Helpers Error: Trying to initialize Date Picker field but "jquery-ui-datepicker" ' +
                'is not enqueued.');
            return;
        }

        this.timePicker     = typeof this.$field.attr('data-timepicker') !== 'undefined';
        this.dateTimePicker = typeof this.$field.attr('data-datetimepicker') !== 'undefined';

        if ( this.dateTimePicker && !jQuery.isFunction(jQuery.fn.datetimepicker) ) {

            console.error('Field Helpers Error: Trying to initialize Date Picker field but ' +
                '"rbm-fh-jquery-ui-datetimepicker" is not enqueued.');
            return;
        }

        if ( this.timePicker && !jQuery.isFunction(jQuery.fn.timepicker) ) {

            console.error('Field Helpers Error: Trying to initialize Date Picker field but ' +
                '"jquery-ui-datetimepicker" is not enqueued.');
            return;
        }

        this.initializeDatepicker();
    }

    /**
     * Initializes the Date Picker.
     *
     * @since {{VERSION}}
     */
    initializeDatepicker() {

        let name             = this.$field.find('input[type="hidden"]').attr('name');
        let option_functions = ['beforeShow', 'beforeShowDay', 'calculateWeek', 'onChangeMonthYear', 'onClose', 'onSelect'];
        let options          = {};

        if ( RBM_FieldHelpers['datepicker_args_' + name] ) {

            options = RBM_FieldHelpers['datepicker_args_' + name];
        }

        // Function support
        jQuery.each(options, (name, value) => {

            if ( option_functions.indexOf(name) !== -1 && typeof options[name] !== 'function' ) {

                options[name] = window[value];
            }
        });

        options.altField  = '[name="' + name + '"]';
        options.altFormat = 'yymmdd';

        if ( this.dateTimePicker ) {

            options.altTimeFormat    = 'HH:mm';
            options.altFieldTimeOnly = false;
            options.timeFormat       = 'hh:mm tt';
            options.controlType      = 'select';

            this.$field.datetimepicker(options);

        } else if ( this.timePicker ) {

            options.altTimeFormat = 'HH:mm';
            options.altFieldTimeOnly = false;
            options.timeFormat = 'hh:mm tt';
            options.controlType = 'select';

            this.$field.timepicker(options);

        } else {

            this.$field.datepicker(options);
        }
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
     */
    constructor() {

        const api = this;

        this.fields = [];

        let $fields = jQuery('[data-fieldhelpers-field-datepicker]');

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
            api: new FieldDatePicker($field),
        });
    }
}

export default FieldDatePickerInitialize;