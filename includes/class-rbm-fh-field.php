<?php
/**
 * Field framework.
 *
 * @since 1.6.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field
 *
 * @since 1.6.0
 */
abstract class RBM_FH_Field {

	/**
	 * Field name.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Field label.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	public $label;

	/**
	 * Field arguments.
	 *
	 * @since 1.6.0
	 *
	 * @var array|null
	 */
	public $args;

	/**
	 * Field value.
	 *
	 * @since 1.6.0
	 *
	 * @var string|bool|null
	 */
	public $value;

	/**
	 * RBM_FH_Field constructor.
	 *
	 * @since 1.6.0
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
			'default'       => '',
			'description'   => false,
			'wrapper_class' => '',
			'no_init'       => false,
			'sanitization'  => false,
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
	 * @since 1.6.0
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
	}

	/**
	 * Gets the field value.
	 *
	 * @since 1.6.0
	 */
	function get_value() {

		global $post;

		// Get value
		$value = $this->value;
		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $this->name, true );
			$value = $value ? $value : $this->args['default'];
		}

		// Sanitize
		if ( $this->args['sanitization'] && is_callable( $this->args['sanitization'] ) ) {
			$value = call_user_func( $this->args['sanitization'], $value );
		}

		/**
		 * Filter the set value.
		 *
		 * @since {{VERSION}}
		 */
		$this->value = apply_filters( "rbm_field_{$this->name}_value", $value, $this );
	}

	/**
	 * Outputs the field.
	 *
	 * @since 1.6.0
	 *
	 * @param string $name Name of the field.
	 * @param mixed $value Value of the field.
	 * @param string $label Field label.
	 * @param array $args Field arguments.
	 */
	public static function field( $name, $value, $label = '', $args = array() ) {
	}
}