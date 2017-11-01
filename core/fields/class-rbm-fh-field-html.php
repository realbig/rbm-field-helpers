<?php
/**
 * Field: HTML
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_HTML
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_HTML extends RBM_FH_Field {

	/**
	 * RBM_FH_Field_HTML constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 */
	function __construct( $name, $label = '', $args = array() ) {

		parent::__construct( $name, $label, $args );
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

		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'html', $args, $name, $value );
	}
}