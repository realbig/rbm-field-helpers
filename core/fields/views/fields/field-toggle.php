<?php
/**
 * Field Template: Toggle
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();
?>

<div class="fieldhelpers-field-toggle-container"
     data-fieldhelpers-field-toggle
>
    <input type="hidden"
           name="<?php echo esc_attr( $name ); ?>"
           value="<?php echo esc_attr( $args['unchecked_value'] ); ?>"
    />
    <input type="checkbox"
           name="<?php echo esc_attr( $name ); ?>"
           id="<?php echo esc_attr( $args['id'] ); ?>"
           class="fieldhelpers-field-input"
           value="<?php echo esc_attr( $args['checked_value'] ); ?>"
		<?php checked( $value, $args['checked_value'] ); ?>
    />
    <span class="fieldhelpers-field-toggle-slider"></span>
</div>
