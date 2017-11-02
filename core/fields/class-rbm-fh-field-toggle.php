<?php
/**
 * Field: Toggle
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Toggle
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_Toggle extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $defaults = array(
		'checked_value'   => 1,
		'unchecked_value' => 0,
	);

	/**
	 * RBM_FH_Field_Toggle constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $args = array() ) {

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

		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'toggle', $args, $name, $value );
	}
}