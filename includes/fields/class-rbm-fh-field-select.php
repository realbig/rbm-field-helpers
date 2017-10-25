<?php
/**
 * Field: Select
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Select
 *
 * @since 1.1.0
 */
class RBM_FH_Field_Select extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $defaults = array(
		'options'           => array(),
		'opt_groups'        => false,
		'multiple'          => false,
		'option_none'       => false,
		'option_none_value' => '',
		'multi_field'       => false,
		'no_options_text'   => '',
	);

	/**
	 * RBM_FH_Field_Select constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $label = '', $args = array(), $value = false ) {

		$args['no_options_text'] = $args['no_options_text'] ?
			$args['no_options_text'] : __( 'No select options.', 'rbm-field-helpers' );

		parent::__construct( $name, $label, $args, $value );
	}

	/**
	 * Outputs the field.
	 *
	 * @since 1.1.0
	 *
	 * @param string $name Name of the field.
	 * @param mixed $value Value of the field.
	 * @param string $label Field label.
	 * @param array $args Field arguments.
	 */
	public static function field( $name, $value, $label = '', $args = array() ) {
		?>
        <p class="rbm-field-select <?php echo esc_attr( $args['wrapper_class'] ); ?>">
            <label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>

				<?php if ( ! empty( $args['options'] ) ) : ?>
                    <select name="<?php echo esc_attr( $name . ( $args['multiple'] ? '[]' : '' ) ); ?>"
                            class="<?php echo esc_attr( $args['input_class'] ); ?>"
						<?php self::input_atts( $args ); ?>
						<?php echo $args['multiple'] ? 'multiple' : ''; ?>>

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
            </label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
        </p>
		<?php
	}
}