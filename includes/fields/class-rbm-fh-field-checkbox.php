<?php
/**
 * Field: Checkbox
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Checkbox
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_Checkbox extends RBM_FH_Field {

	/**
	 * RBM_FH_Field_Checkbox constructor.
	 *
	 * @since {{VERSION}}
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
	 * @since {{VERSION}}
	 *
	 * @param string $name Name of the field.
	 * @param mixed $value Value of the field.
	 * @param string $label Field label.
	 * @param array $args Field arguments.
	 */
	public static function field( $name, $value, $label = '', $args = array() ) {

		$args = wp_parse_args( $args, array(
			'check_value'          => 1,
			'check_disabled_value' => 0,
			'check_label'          => '',
			'input_class'          => '',
		) );

		if ( is_array( $value ) ) {
			$value = array_shift( $value );
		}
		?>
		<p class="rbm-field-checkbox <?php echo $args['wrapper_class']; ?>">
			<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $args['check_disabled_value']; ?>"/>

			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>

				<input type="checkbox" name="<?php echo $name; ?>"
				       value="<?php echo $args['check_value']; ?>"
				       class="<?php echo $args['input_class']; ?>"
					<?php checked( $args['check_value'], $value ); ?> />

				<?php echo $args['check_label']; ?>
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}
}