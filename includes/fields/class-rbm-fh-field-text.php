<?php
/**
 * Field: Text
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Text
 *
 * @since 1.1.0
 */
class RBM_FH_Field_Text extends RBM_FH_Field {

	/**
	 * RBM_FH_Field_Text constructor.
	 *
	 * @since 1.1.0
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
	 * @since 1.1.0
	 *
	 * @param string $name Name of the field.
	 * @param mixed $value Value of the field.
	 * @param string $label Field label.
	 * @param array $args Field arguments.
	 */
	public static function field( $name, $value, $label = '', $args = array() ) {

		?>
        <p class="rbm-field-text <?php echo esc_attr( $args['wrapper_class'] ); ?>">
            <label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
                <input type="text" name="<?php echo esc_attr( $name ); ?>"
                       value="<?php echo $value ? esc_attr( $value ) : esc_attr( $args['default'] ); ?>"
                       class="<?php echo isset( $args['input_class'] ) ? esc_attr( $args['input_class'] ) : 'regular-text'; ?>"
					<?php self::input_atts( $args ); ?> />
            </label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
        </p>
		<?php
	}
}