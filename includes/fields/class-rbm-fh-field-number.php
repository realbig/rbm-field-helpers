<?php
/**
 * Field: Number
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Number
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_Number extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $defaults = array(
		'increase_interval'     => 1,
		'decrease_interval'     => 1,
		'alt_increase_interval' => 10,
		'alt_decrease_interval' => 10,
		'max'                   => 'none',
		'min'                   => 'none',
		'postfix'               => false,
	);

	/**
	 * RBM_FH_Field_Number constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $label = '', $args = array(), $value = false ) {

		parent::__construct( $name, $label, $args, $value );
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

		?>
        <div class="rbm-field-number <?php echo esc_attr( $args['wrapper_class'] ); ?>">
            <label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
            </label>

            <div class="rbm-field-number-container"
				<?php echo $args['postfix'] ? 'data-postfix="' . esc_attr( $args['postfix'] ) . '"' : ''; ?>>
                <input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>"
                       class="rbm-field-input" value="<?php echo esc_attr( $value ); ?>"
					<?php self::input_atts( $args ); ?> />

                <button type="button" class="rbm-field-number-increase rbm-button" data-number-increase
                        data-number-interval="<?php echo esc_attr( $args['increase_interval'] ); ?>"
                        data-number-alt-interval="<?php echo esc_attr( $args['alt_increase_interval'] ); ?>"
                        data-number-max="<?php echo esc_attr( $args['max'] ); ?>"
                        title="increase number"
                        aria-label="increase number">
                    <span class="dashicons dashicons-plus"></span>
                </button>

                <button type="button" class="rbm-field-number-decrease rbm-button" data-number-decrease
                        data-number-interval="<?php echo esc_attr( $args['decrease_interval'] ); ?>"
                        data-number-alt-interval="<?php echo esc_attr( $args['alt_decrease_interval'] ); ?>"
                        data-number-min="<?php echo esc_attr( $args['min'] ); ?>"
                        title="decrease number"
                        aria-label="decrease number">
                    <span class="dashicons dashicons-minus"></span>
                </button>
            </div>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
        </div>
		<?php
	}
}