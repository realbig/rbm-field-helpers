import FieldNumberInitialize from "./fields/field-number";
import FieldColorPickerInitialize from "./fields/field-colorpicker";
import FieldDatePickerInitialize from "./fields/field-datepicker";
import FieldTableInitialize from "./fields/field-table";
import FieldMediaInitialize from "./fields/field-media";

/**
 * Handles all field initializations.
 *
 * @since {{VERSION}}
 */
class FieldsInitialize {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor() {

        this.fields = {};

        this.fields.number      = new FieldNumberInitialize();
        this.fields.colorpicker = new FieldColorPickerInitialize();
        this.fields.datepicker  = new FieldDatePickerInitialize();
        this.fields.table       = new FieldTableInitialize();
        this.fields.media       = new FieldMediaInitialize();
    }
}

export default FieldsInitialize;