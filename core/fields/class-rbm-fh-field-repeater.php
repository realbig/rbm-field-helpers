<?php
/**
 * Field: Repeater
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Repeater
 *
 * @since 1.1.0
 */
class RBM_FH_Field_Repeater extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $defaults = array(
		'collapsable'         => false,
		'collapsable_title'   => 'New Row',
		'sortable'            => true,
		'confirm_delete_text' => '',
	);

	/**
	 * RBM_FH_Field_Repeater constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var array $args
	 */
	function __construct( $name, $args = array(), $values = false ) {

		wp_parse_args( $args, array(
			'confirm_delete_text' => __( 'Are you sure you want to delete this element?', 'rbm-field-helpers' ),
		) );

		parent::__construct( $name, $args, $values );
	}

	/**
	 * Outputs the field.
	 *
	 * @since 1.1.0
	 *
	 * @param string $name Name of the field.
	 * @param mixed $values Value of the field.
	 * @param array $args Args.
	 */
	public static function field( $name, $values, $args = array() ) {


		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'repeater', $args, $name, $values );
	}
}