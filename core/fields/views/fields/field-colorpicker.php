<?php
/**
 * Field Template: Colorpicker
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
       name="<?php echo $name; ?>"
       value="<?php echo $value ? $value : $args['default']; ?>"
       class="<?php echo $args['input_class']; ?>"
       data-fieldhelpers-field-colorpicker
       data-default-color="<?php echo $args['default']; ?>"
	<?php RBM_FH_Field::input_atts( $args ); ?> />
