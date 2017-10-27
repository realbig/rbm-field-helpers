<?php
/**
 * Field: HTML
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_HTML
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_HTML extends RBM_FH_Field {

	/**
	 * RBM_FH_Field_HTML constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 */
	function __construct( $name, $label = '', $args = array() ) {

		parent::__construct( $name, $label, $args );
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
        <div class="rbm-field-html <?php echo esc_attr( $args['wrapper_class'] ); ?>">
			<?php if ( $label ) : ?>
                <label>
                    <strong>
						<?php echo $label; ?>
                    </strong>
                </label>
			<?php endif; ?>

			<?php echo $args['html']; ?>
        </div>
		<?php
	}
}