<?php
/**
 * Field Template: Date/Time Picker
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();
?>

<input type="text"
       class="fieldhelpers-field-datetimepicker-preview"
       value="<?php echo $args['preview']; ?>"
       data-fieldhelpers-field-datepicker
       data-datetimepicker
/>

<input type="hidden"
       name="<?php echo $name; ?>"
       value="<?php echo $value ? $value : $args['default']; ?>"
       id="fieldhelpers-field-datetimepicker-input-<?php echo $name; ?>"
       class="<?php echo $args['input_class']; ?>"
	<?php RBM_FH_Field::input_atts( $args ); ?>
/>