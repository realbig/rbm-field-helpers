<?php
/**
 * Field: DateTime Picker
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_DateTimePicker
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_DateTimePicker extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $defaults = array(
		'default'         => '',
		'format'		  => '',
		'datetimepicker_args' => array(),
	);

	/**
	 * RBM_FH_Field_DateTimePicker constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $label = '', $args = array(), $value = false ) {

		// Cannot use function in property declaration
		$this->defaults['format'] = get_option( 'date_format', 'F j, Y' ) . ' ' . get_option( 'time_format', 'g:i a' );
		
		$args['default'] = current_time( $this->defaults['format'] );

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
		
		$format = ( isset( $args['format'] ) ) ? $args['format'] : $this->defaults['format'];

		// Get preview format
		$preview = date( $format, strtotime( $value ? $value : $args['default'] ) );

		// DateTimepicker args
		if ( $args['datetimepicker_args'] ) {
			add_filter( 'rbm_field_helpers_admin_data', function ( $data ) use ( $args, $name ) {

				$data["datetimepicker_args_$name"] = $args['datetimepicker_args'];

				return $data;
			} );
		}
		?>
		<p class="rbm-field-datetimepicker <?php echo $args['wrapper_class']; ?>">

			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
				
				<input type="text" class="rbm-field-datetimepicker-preview"
				       value="<?php echo $preview; ?>"/>

				<input type="hidden" name="<?php echo $name; ?>"
				       value="<?php echo $value ? $value : $args['default']; ?>"
				       id="rbm-field-datetimepicker-input-<?php echo $name; ?>"
				       class="<?php echo $args['input_class']; ?>"
					<?php self::input_atts( $args ); ?> />
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}
}