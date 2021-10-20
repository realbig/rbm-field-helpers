import Field from './field.js';

/**
 * Radio Field functionality.
 *
 * @since 1.4.0
 */
class FieldRadio extends Field {

    /**
     * Class constructor.
     *
     * @since 1.4.0
     */
    constructor($field) {

        super($field, 'radio');

        this.initField();
    }

    /**
     * Initializes the select.
     *
     * @since 1.4.0
     */
    initField() {

        this.$ui = {
            radios: this.$field.find('input[type="radio"]'),
        }

        this.setupHandlers();

        this.$field.find('input:checked').trigger( 'change' );
    }

    /**
     * Sets up class handlers.
     *
     * @since 1.4.0
     */
    setupHandlers() {

        const api = this;

        this.$ui.radios.change(function () {
            api.handleChange(jQuery(this));
        });
    }

    /**
     * Fires on radio change.
     *
     * @since 1.4.0
     *
     * @param {jQuery} $input Checkbox input.
     */
    handleChange($input) {

        this.setInactive(this.$ui.radios.closest('.fieldhelpers-field-radio-row'));
        this.setActive($input.closest('.fieldhelpers-field-radio-row'));
    }

    /**
     * Sets the radio row as active.
     *
     * @since 1.4.0
     *
     * @param {jQuery} $row
     */
    setActive($row) {

        $row.addClass('fieldhelpers-field-radio-row-active');
    }

    /**
     * Sets the radio row as inactive.
     *
     * @since 1.4.0
     *
     * @param {jQuery} $row
     */
    setInactive($row) {

        $row.removeClass('fieldhelpers-field-radio-row-active');
    }


    /**
     * Sets the ID to be unique, based off the repeater item index.
     *
     * For radios, there will be multiple IDs in each, and need to be set accordingly.
     *
     * @since 1.4.0
     */
    repeaterSetID() {

        let ID    = this.options.id;
        let $rows = this.$field.find('.fieldhelpers-field-radio-row');
        let index = this.$field.closest('[data-repeater-item]').index();

        $rows.each(function () {

            let $field     = jQuery(this).find('input[type="radio"]');
            let $label     = $field.next('label');
            let fieldIndex = jQuery(this).index();
            let newID      = `${ID}_${index}_${fieldIndex}`;

            $field.attr('id', newID);
            $label.attr('for', newID);
        });
    }

    /**
     * Cleans up after a repeater add/init.
     *
     * @since {{VERSION}}
     */
    fieldCleanup() {

        let api = this;

        this.$field.find( 'input' ).each( function( index, input ) {
            api.setInactive( jQuery( input ).closest( '.fieldhelpers-field-radio-row' ) );
        } );

    }

    /**
     * Ensure that our styling is reapplied
     *
     * @param   {object}  $repeater  jQuery DOM Object
     * @param   {array}  options     Array of Field Options
     *
     * @since   {{VERSION}}
     * @return  void
     */
     repeaterOnInit( $repeater, options ) {

        this.initField();

    }
    
    /**
     * Runs cleanup before the Repeater creates a dummy row to clear out selected items
     *
     * @param   {object}  $repeater  jQuery DOM Object
     * @param   {array}  options     Array of Field Options
     *
     * @since   {{VERSION}}
     * @return  void
     */
    repeaterBeforeInit( $repeater, options ) {

        this.fieldCleanup();

    }

    /**
     * Sets the field to default.
     *
     * @since 1.4.0
     */
    setDefault() {

        if ( this.options.default ) {

            this.$field.find(`[value="${this.options.default}"]`).prop('checked', true).change();
        }
    }
}

/**
 * Finds and initializes all Radio fields.
 *
 * @since 1.4.0
 */
class FieldRadioInitialize {

    /**
     * Class constructor.
     *
     * @since 1.4.0
     *
     * @param {jQuery} $root Root element to initialize fields inside.
     */
    constructor($root) {

        const api = this;

        this.fields = [];

        let $fields = $root.find('[data-fieldhelpers-field-radio]');

        if ( $fields.length ) {

            $fields.each(function () {

                api.initializeField(jQuery(this));
            });
        }
    }

    /**
     * Initializes the field.
     *
     * @since 1.4.0
     *
     * @param {jQuery} $field
     */
    initializeField($field) {

        this.fields.push({
            $field,
            api: new FieldRadio($field),
        });
    }
}

export default FieldRadioInitialize;