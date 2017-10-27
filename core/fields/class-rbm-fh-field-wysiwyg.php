<?php
/**
 * Field: WYSIWYG
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_WYSIWYG
 *
 * @since 1.1.0
 */
class RBM_FH_Field_WYSIWYG extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $defaults = array(
		'wysiwyg_args' => array(),
		'wysiwyg_id'   => '',
	);

	/**
	 * RBM_FH_Field_WYSIWYG constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var array $args
	 */
	function __construct( $name, $args = array() ) {

		// Can't do this on property declaration
		$this->defaults['wysiwyg_id'] = $name;

		parent::__construct( $name, $args );
	}

	/**
	 * Outputs the field.
	 *
	 * @since 1.1.0
	 *
	 * @param string $name Name of the field.
	 * @param mixed $value Value of the field.
	 * @param array $args Field arguments.
	 */
	public static function field( $name, $value, $args = array() ) {

		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'wysiwyg', $args, $name, $value );
	}
}