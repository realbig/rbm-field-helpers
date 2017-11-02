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

<div class="fieldhelpers-field-checkbox-container"
     data-fieldhelpers-field-checkbox
>
	<?php $i = 0; ?>
	<?php foreach ( $args['options'] as $check_value => $check_label ) : ?>
		<?php $i ++; ?>

        <div class="fieldhelpers-field-checkbox-row">

            <div class="fieldhelpers-field-radio-input-container">
                <input type="checkbox"
                       name="<?php echo $name . ( $args['repeater'] ? '' : '[]' ); ?>"
                       id="<?php echo "{$args['id']}_{$i}"; ?>"
                       value="<?php echo $check_value; ?>"
                       class="<?php echo $args['input_class']; ?>"
					<?php echo in_array( $check_value, $value ) ? 'checked' : ''; ?>
					<?php RBM_FH_Field::input_atts( $args ); ?>
                />
            </div>

            <label for="<?php echo "{$args['id']}_{$i}"; ?>"
                   class="fieldhelpers-field-checkbox-label"
            ><?php echo $check_label; ?></label>

        </div>

	<?php endforeach; ?>
</div>
