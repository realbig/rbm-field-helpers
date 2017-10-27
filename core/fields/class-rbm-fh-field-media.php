<?php
/**
 * Field: Media
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Media
 *
 * @since 1.1.0
 */
class RBM_FH_Field_Media extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $defaults = array(
		'preview_size'       => 'medium',
		'button_text'        => '',
		'button_remove_text' => '',
		'type'               => 'image',
		'window_title'       => '',
		'window_button_text' => '',
		'placeholder'        => '&nbsp;',
	);

	/**
	 * RBM_FH_Field_Media constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var array $args
	 */
	function __construct( $name, $args = array() ) {

		$this->defaults['button_text']        = __( 'Upload / Choose Media', 'rbm-field-helpers' );
		$this->defaults['button_remove_text'] = __( 'Remove Media', 'rbm-field-helpers' );
		$this->defaults['window_title']       = __( 'Choose Media', 'rbm-field-helpers' );
		$this->defaults['window_button_text'] = __( 'Use Media', 'rbm-field-helpers' );

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

		if ( $media_item_src = wp_get_attachment_image_src( $value, $args['preview_size'] ) ) {

			$args['media_preview_url'] = $media_item_src[0];

		} else {

			$args['media_preview_url'] = wp_get_attachment_url( $value );
		}

		do_action( "{$args['prefix']}_fieldhelpers_do_field", 'media', $args, $name, $value );
	}
}