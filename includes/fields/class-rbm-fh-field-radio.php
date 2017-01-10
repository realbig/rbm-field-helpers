<?php
/**
 * Field: Radio
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Radio
 *
 * @since 1.1.0
 */
class RBM_FH_Field_Radio extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $defaults = array(
		'options' => false,
	);

	/**
	 * RBM_FH_Field_Radio constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $label = '', $args = array(), $value = false ) {

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

		// Legacy
		if ( ! $args['options'] ) {

			$args = wp_parse_args( $args, array(
				'radio_value' => 1,
				'radio_label' => $label,
			) );

			$args['options'] = array(
				$args['radio_value'] => $args['radio_label'],
			);

		} else {

			if ( $args['options'] === false ) {

				echo 'No radio options';

				return;
			}
		}
		?>
		<p class="rbm-field-radio <?php echo $args['wrapper_class']; ?>">
			<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>

			<?php
			$i = 0;
			foreach ( $args['options'] as $radio_value => $radio_label ) :
				$i ++;
				?>
				<label>
					<input type="radio" name="<?php echo $name; ?>"
					       value="<?php echo $radio_value; ?>"
					       class="<?php echo $args['input_class']; ?>"
						<?php checked( $radio_value, $value ); ?>
						<?php self::input_atts( $args ); ?> />

					<span class="rbm-field-radio-label"><?php echo $radio_label; ?></span>

				</label>
				<?php echo $i !== count( $args['options'] ) ? '<br/>' : ''; ?>
			<?php endforeach; ?>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}
}