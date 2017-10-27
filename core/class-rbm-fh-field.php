<?php
/**
 * Field framework.
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field
 *
 * @since 1.1.0
 */
abstract class RBM_FH_Field {

	/**
	 * Field name.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Field arguments.
	 *
	 * @since 1.1.0
	 *
	 * @var array|null
	 */
	public $args;

	/**
	 * Field value.
	 *
	 * @since 1.1.0
	 *
	 * @var mixed
	 */
	public $value;

	/**
	 * RBM_FH_Field constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name Field name.
	 * @var array $args Field arguments.
	 */
	function __construct( $name, $args = array() ) {

		// Unique args
		if ( isset( $this->defaults ) ) {

			$args = wp_parse_args( $args, $this->defaults );
		}

		$this->args = wp_parse_args( $args, array(
			'id'            => $name,
			'value'         => false,
			'prefix'        => '_rbm',
			'label'         => '',
			'default'       => '',
			'description'   => false,
			'wrapper_class' => '',
			'no_init'       => false,
			'sanitization'  => false,
			'input_class'   => 'widefat',
			'input_atts'    => array(),
			'option_field'  => false,
		) );

		$this->name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "{$this->args['prefix']}_{$name}";

		if ( $this->args['value'] === false ) {

			$this->get_value();

		} else {

			$this->value = $this->args['value'];
		}

		static::field( $this->name, $this->value, $this->args );
	}


	/**
	 * Gets the field value.
	 *
	 * @since 1.1.0
	 */
	function get_value() {

		if ( $this->args['option_field'] ) {

			$value = $this->get_option_value();

		} else {

			$value = $this->get_meta_value();
		}

		// Sanitize
		if ( $this->args['sanitization'] && is_callable( $this->args['sanitization'] ) ) {

			$value = call_user_func( $this->args['sanitization'], $value );
		}

		/**
		 * Filter the set value.
		 *
		 * @since 1.1.0
		 */
		$this->value = apply_filters( "rbm_field_{$this->name}_value", $value, $this );
	}

	/**
	 * Gets post meta field value.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @return mixed
	 */
	private function get_meta_value() {

		global $post;

		// Make sure something exists in post
		if ( ! $post ) {

			return false;
		}

		// If not a post object, try to get it
		if ( ! $post instanceof WP_Post ) {

			if ( ! ( $post = get_post( $post ) ) ) {

				return false;
			}
		}

		$value = get_post_meta( $post->ID, $this->name, ! $this->args['multi_field'] );

		$value = $value !== '' && $value !== false && $value !== null ? $value : $this->args['default'];

		return $value;
	}

	/**
	 * Gets option field value.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @return mixed
	 */
	private function get_option_value() {

		$value = get_option( $this->name, ! $this->args['multi_field'] );

		$value = $value !== '' && $value !== false && $value !== null ? $value : $this->args['default'];

		return $value;
	}

	/**
	 * Returns the input atts, if set.
	 *
	 * @since 1.3.2
	 *
	 * @param array $args Field args
	 */
	public static function get_input_atts( $args ) {

		if ( ! isset( $args['input_atts'] ) || ! $args['input_atts'] ) {

			return '';
		}

		$input_atts = array();
		foreach ( $args['input_atts'] as $attr_name => $attr_value ) {

			$input_atts[] = $attr_name . '="' . esc_attr( $attr_value ) . '"';
		}

		$input_atts = implode( ' ', $input_atts );

		return $input_atts;
	}

	/**
	 * Echoes the input atts, if set.
	 *
	 * @since 1.3.2
	 *
	 * @param array $args Field args
	 */
	public static function input_atts( $args ) {

		echo self::get_input_atts( $args );
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
	}
}