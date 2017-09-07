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
	 * Field label.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $label;

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
	 * @var string|bool|null
	 */
	public $value;

	/**
	 * RBM_FH_Field constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $label = '', $args = array(), $value = false ) {

		// Unique args
		if ( isset( $this->defaults ) ) {
			$args = wp_parse_args( $args, $this->defaults );
		}

		// Global args
		$args = wp_parse_args( $args, array(
			'id'            => $name,
			'default'       => '',
			'description'   => false,
			'wrapper_class' => '',
			'no_init'       => false,
			'sanitization'  => false,
			'multi_field'   => false,
			'input_class'   => 'widefat',
			'input_atts'    => array(),
		) );

		$this->name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";;
		$this->label = $label;
		$this->args  = $args;
		$this->value = $value;

		// Init unless told otherwise
		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			$this->field_init();
		}

		$this->get_value();

		static::field( $this->name, $this->value, $this->label, $this->args );
	}

	/**
	 * Initializes a field.
	 *
	 * @since 1.1.0
	 */
	private function field_init() {

		static $i;
		$i = $i === null ? 0 : $i + 1;

		if ( empty( RBMFH()->fields ) ) {
			wp_nonce_field( 'rbm-save-meta', 'rbm-meta' );
		}

		RBMFH()->fields[] = $this->name;
		?>
        <input type="hidden" name="_rbm_fields[<?php echo $i; ?>]" value="<?php echo $this->name; ?>"/>
		<?php

		if ( $this->args['multi_field'] ) {
			?>
            <input type="hidden" name="_rbm_field_<?php echo $this->name; ?>_multi_field" value="1"/>
			<?php
		}
	}

	/**
	 * Gets the field value.
	 *
	 * @since 1.1.0
	 */
	function get_value() {

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

		// Get value
		$value = $this->value;
		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $this->name, ! $this->args['multi_field'] );
			$value = $value !== '' ? $value : $this->args['default'];
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
	 * @param string $label Field label.
	 * @param array $args Field arguments.
	 */
	public static function field( $name, $value, $label = '', $args = array() ) {
	}
}