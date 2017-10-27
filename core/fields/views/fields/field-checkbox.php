<?php
/**
 * Field Template: Checkbox
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();
?>

<?php $i = 0; ?>
<?php foreach ( $args['options'] as $check_value => $check_label ) : ?>
	<?php $i ++; ?>
    <input type="checkbox"
           name="<?php echo $name; ?>[]"
           id="<?php echo "{$name}_{$i}"; ?>"
           value="<?php echo $check_value; ?>"
           class="<?php echo $args['input_class']; ?>"
		<?php echo in_array( $check_value, $value ) ? 'checked' : ''; ?>
		<?php RBM_FH_Field::input_atts( $args ); ?>
    />

    <label for="<?php echo "{$name}_{$i}"; ?>"
           class="fieldhelpers-field-checkbox-label"
    ><?php echo $check_label; ?></label>

	<?php echo $i !== count( $args['options'] ) ? '<br/>' : ''; ?>
<?php endforeach; ?>