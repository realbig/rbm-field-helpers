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
		'show_empty_select' => false,
		'singular_name'     => '',
		'plural_name'       => '',
		'select2_disable'   => false,
		'select2_options'   => array(
			'placeholder'       => '',
			'containerCssClass' => 'fieldhelpers-select2',
			'dropdownCssClass'  => 'fieldhelpers-select2',
			'language'          => array(),
		),
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

		// Legacy
		$args['l10n']['no_options'] = isset( $args['no_options_text'] ) ?
			$args['no_options_text'] : $args['l10n']['no_options'];

		// Select2 Options defaults
		if ( ! isset( $args['select2_disable'] ) || $args['select2_disable'] !== true ) {

			$args['select2_options'] = wp_parse_args(
				isset( $args['select2_options'] ) ? $args['select2_options'] : array(),
				$this->defaults['select2_options']
			);

			if ( isset( $args['placeholder'] ) ) {

				$args['select2_options']['placeholder'] = $args['placeholder'];
			}

			// Languages
			$args['select2_options']['language'] = array(
				'errorLoading'    => $args['l10n']['error_loading'],
				'inputTooLong'    => $args['l10n']['input_too_long'],
				'inputTooShort'   => $args['l10n']['input_too_short'],
				'loadingMore'     => $args['l10n']['loading_more'],
				'maximumSelected' => $args['l10n']['maximum_selected'],
				'noResults'       => $args['l10n']['no_results'],
				'searching'       => $args['l10n']['searching'],
			);
		}

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