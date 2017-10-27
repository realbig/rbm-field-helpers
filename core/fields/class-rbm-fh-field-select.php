<?php
/**
 * Field: Select
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Select
 *
 * @since 1.1.0
 */
class RBM_FH_Field_Select extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $defaults = array(
		'options'           => array(),
		'opt_groups'        => false,
		'multiple'          => false,
		'option_none'       => false,
		'option_none_value' => '',
		'multi_field'       => false,
		'no_options_text'   => '',
	);

	/**
	 * RBM_FH_Field_Select constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var array $args
	 */
	function __construct( $name, $args = array() ) {

		$args['no_options_text'] = $args['no_options_text'] ?
			$args['no_options_text'] : __( 'No select options.', 'rbm-field-helpers' );

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

	    do_action( "{$args['prefix']}_fieldhelpers_do_field", 'select', $args, $name, $value );
	}
}