<?php
/**
 * Field: Date Picker
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_DatePicker
 *
 * @since 1.1.0
 */
class RBM_FH_Field_DatePicker extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $defaults = array(
		'default'         => '',
		'format'		  => '',
		'datepicker_args' => array(),
	);

	/**
	 * RBM_FH_Field_DatePicker constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $label = '', $args = array(), $value = false ) {

		// Cannot use function in property declaration
		$this->defaults['format'] = get_option( 'date_format', 'F j, Y' );
		
		$args['default'] = current_time( $this->defaults['format'] );

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

		// Get preview format
		$preview = date( $args['format'], strtotime( $value ? $value : $args['default'] ) );

		// Datepicker args
		if ( $args['datepicker_args'] ) {
			add_filter( 'rbm_field_helpers_admin_data', function ( $data ) use ( $args, $name ) {

				$data["datepicker_args_$name"] = $args['datepicker_args'];

				return $data;
			} );
		}
		?>
		<p class="rbm-field-datepicker <?php echo $args['wrapper_class']; ?>">

			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>

				<input type="text" class="rbm-field-datepicker-preview"
				       value="<?php echo $preview; ?>"/>

				<input type="hidden" name="<?php echo $name; ?>"
				       value="<?php echo $value ? $value : $args['default']; ?>"
				       class="<?php echo $args['input_class']; ?>"
					<?php self::input_atts( $args ); ?> />
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}
}