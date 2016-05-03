<?php
/**
 * Field: Text Area
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_TextArea
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_TextArea extends RBM_FH_Field {

	/**
	 * RBM_FH_Field_TextArea constructor.
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
			'rows'          => 4,
			'input_class'   => 'widefat',
		) );
		?>
		<p class="rbm-field-textarea <?php echo $args['wrapper_class']; ?>">
			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
				<textarea name="<?php echo $name; ?>"
				          class="<?php echo $args['input_class']; ?>"
				          rows="<?php echo $args['rows']; ?>"
				><?php echo $value; ?></textarea>
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}
}