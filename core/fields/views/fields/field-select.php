<?php
/**
 * Field Template: Select
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();
?>

<?php if ( ! empty( $args['options'] ) ) : ?>
    <select name="<?php echo esc_attr( $name . ( $args['multiple'] ? '[]' : '' ) ); ?>"
            class="<?php echo esc_attr( $args['input_class'] ); ?>"
            data-fieldhelpers-field-select="<?php echo esc_attr( $name ); ?>"
		<?php RBM_FH_Field::input_atts( $args ); ?>
		<?php echo $args['multiple'] ? 'multiple' : ''; ?>
    >

		<?php if ( $args['option_none'] ) : ?>
            <option value="<?php echo esc_attr( $args['option_none_value'] ); ?>">
				<?php echo esc_attr( $args['option_none'] ); ?>
            </option>
		<?php endif; ?>

		<?php if ( $args['opt_groups'] ) : ?>

			<?php foreach ( $args['options'] as $opt_group_label => $options ) : ?>
                <optgroup label="<?php echo esc_attr( $opt_group_label ); ?>">

					<?php
					foreach ( $options as $option_value => $option_label ) :

						if ( $args['multiple'] ) {
							$selected = in_array( $option_value, (array) $value ) ? 'selected' : '';
						} else {
							$selected = selected( $option_value, $value, false ) ? 'selected' : '';
						}
						?>
                        <option value="<?php echo esc_attr( $option_value ); ?>" <?php echo $selected; ?>>
							<?php echo esc_attr( $option_label ); ?>
                        </option>
					<?php endforeach; ?>

                </optgroup>
				<?php
			endforeach;

		else:

			foreach ( $args['options'] as $option_value => $option_label ) :

				if ( $args['multiple'] ) {
					$selected = in_array( $option_value, (array) $value ) ? 'selected' : '';
				} else {
					$selected = selected( $option_value, $value, false ) ? 'selected' : '';
				}
				?>
                <option value="<?php echo esc_attr( $option_value ); ?>" <?php echo $selected; ?>>
					<?php echo esc_attr( $option_label ); ?>
                </option>

				<?php
			endforeach;
		endif;
		?>

    </select>
<?php else: ?>
	<?php echo $args['no_options_text']; ?>
<?php endif; ?>