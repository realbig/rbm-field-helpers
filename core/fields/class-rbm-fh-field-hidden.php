<?php
/**
 * Field: Hidden
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Hidden
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_Hidden extends RBM_FH_Field {

	/**
	 * RBM_FH_Field_Hidden constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var array $args
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

		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'hidden', $args, $name, $value );
	}
}