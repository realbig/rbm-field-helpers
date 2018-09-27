import 'jquery.repeater';
import 'jquery-ui-timepicker-addon';
import FieldsInitialize from "./fields-init";

// Initialize app on jQuery Ready.
jQuery(() => {

    const Fields = new FieldsInitialize(jQuery(document));
});