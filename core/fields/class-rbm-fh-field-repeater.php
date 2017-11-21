<?php
/**
 * Field: Repeater
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

// TODO Output values properly. Apparently "Default Values" doesn't work.

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
		'collapsable'            => false,
		'sortable'               => true,
		'first_item_undeletable' => false,
		'collapsable_title'      => '',
		'confirm_delete_text'    => '',
		'delete_item_text'       => '',
		'add_item_text'          => '',
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

		// Legacy
		$args['l10n']['collapsable_title'] = isset( $args['collapsable_title'] ) ?
			$args['collapsable_title'] : $args['l10n']['collapsable_title'];

		$args['l10n']['confirm_delete'] = isset( $args['confirm_delete_text'] ) ?
			$args['confirm_delete_text'] : $args['l10n']['confirm_delete'];

		$args['l10n']['delete_item'] = isset( $args['delete_item_text'] ) ?
			$args['delete_item_text'] : $args['l10n']['delete_item'];

		$args['l10n']['add_item'] = isset( $args['add_item_text'] ) ?
			$args['add_item_text'] : $args['l10n']['add_item'];

		parent::__construct( $name, $args, $values );
	}

	/**
	 * Outputs the field.
	 *
	 * @since 1.1.0
	 *
	 * @param string $name Name of the field.
	 * @param mixed $value Value of the field.
	 * @param array $args Args.
	 */
	public static function field( $name, $value, $args = array() ) {

		if ( $args['collapsable'] ) {

			$args['wrapper_classes'][] = 'fieldhelpers-field-repeater-collapsable';
		}

		if ( $args['sortable'] ) {

			$args['wrapper_classes'][] = 'fieldhelpers-field-repeater-sortable';
		}

		if ( ! $value ) {

			// Default empty row
			$value = array(
				array_fill_keys( array_keys( $args['fields'] ), '' ),
			);
		}

		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'repeater', $args, $name, $value );
	}

	/**
	 * Loops through and executes each field in the repeater.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name Field name.
	 * @param array $value Field value.
	 * @param array $args Field arguments.
	 */
	public static function do_fields( $name, $value, $args ) {

		foreach ( $args['fields'] as $field_name => $field ) {

			if ( in_array( $field['type'], array(
				'list',
				'repeater',
				'table',
			) ) ) {

				printf(
				/* translators: %s is field type */
					__( "Field type %s not supported in Repeater fields.", 'rbm-field-helpers' ),
					$field['type']
				);
				continue;
			}

			if ( is_callable( array( $args['fields_instance'], "do_field_$field[type]" ) ) ) {

				$field = wp_parse_args( $field, array(
					'args' => array(),
				) );

				$field['args']['repeater'] = $name;
				$field['args']['no_init']  = true;
				$field['args']['id']       = "{$name}_{$field_name}";
				$field['args']['value']    = isset( $value[ $field_name ] ) ? $value[ $field_name ] : '';

				call_user_func(
					array(
						$args['fields_instance'],
						"do_field_$field[type]",
					),
					$field_name,
					$field['args']
				);
			}
		}
	}
}