<?php
/**
 * Field: Time Picker
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_TimePicker
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_TimePicker extends RBM_FH_Field {

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
		'timepicker_args' => array(),
	);

	/**
	 * RBM_FH_Field_TimePicker constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var array $args
	 */
	function __construct( $name, $args = array() ) {

		// Cannot use function in property declaration
		$this->defaults['format'] = get_option( 'time_format', 'g:i a' );

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

		// Timepicker args
		if ( $args['timepicker_args'] ) {
			add_filter( 'rbm_field_helpers_admin_data', function ( $data ) use ( $args, $name ) {

				$data["timepicker_args_$name"] = $args['timepicker_args'];

				return $data;
			} );
		}

		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'timepicker', $args, $name, $value );
	}
}