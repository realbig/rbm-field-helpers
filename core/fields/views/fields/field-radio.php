<?php
/**
 * Field Template: Radio
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
<?php foreach ( $args['options'] as $radio_value => $radio_label ) : ?>
	<?php $i ++; ?>
    <input type="radio"
           name="<?php echo $name; ?>"
           id="<?php echo "{$name}_{$i}"; ?>"
           value="<?php echo $radio_value; ?>"
           class="<?php echo $args['input_class']; ?>"
		<?php checked( $radio_value, $value ); ?>
		<?php RBM_FH_Field::input_atts( $args ); ?>
    />

    <label for="<?php echo "{$name}_{$i}"; ?>"
           class="fieldhelpers-field-radio-label"
    ><?php echo $radio_label; ?></label>

	<?php echo $i !== count( $args['options'] ) ? '<br/>' : ''; ?>
<?php endforeach; ?>

