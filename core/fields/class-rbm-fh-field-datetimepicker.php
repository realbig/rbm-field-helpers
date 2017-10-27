<?php
/**
 * Field: DateTime Picker
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
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
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $args = array() ) {

		// Cannot use function in property declaration
		$this->defaults['format'] = get_option( 'date_format', 'F j, Y' ) . ' ' . get_option( 'time_format', 'g:i a' );
		
		$args['default'] = current_time( $this->defaults['format'] );

		parent::__construct( $name, $args );
	}

	/**
	 * Outputs the field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name Name of the field.
	 * @param mixed $value Value of the field.
	 * @param array $args Field arguments.
	 */
	public static function field( $name, $value, $args = array() ) {

		// Get preview format
		$args['preview'] = date( $args['format'], strtotime( $value ? $value : $args['default'] ) );

		// DateTimepicker args
		if ( $args['datetimepicker_args'] ) {

		    add_filter( 'rbm_field_helpers_admin_data', function ( $data ) use ( $args, $name ) {

				$data["datetimepicker_args_$name"] = $args['datetimepicker_args'];

				return $data;
			} );
		}

		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'datetimepicker', $args, $name, $value );
	}
}