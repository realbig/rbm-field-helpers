<?php
/**
 * Field: WYSIWYG
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_WYSIWYG
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_WYSIWYG extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $defaults = array(
		'wysiwyg_args'  => array(),
		'wysiwyg_id'    => '',
	);

	/**
	 * RBM_FH_Field_WYSIWYG constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $label = '', $args = array(), $value = false ) {

		// Can't do this on property declaration
		$this->defaults['wysiwyg_id'] = $name;

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

		if ( $label ) : ?>
			<p class="rbm-field-wysiwyg-label">
				<?php echo $label; ?>
			</p>
		<?php endif; ?>

		<div class="rbm-field-wysiwyg <?php echo $args['wrapper_class']; ?>"
		     data-id="<?php echo $args['wysiwyg_id']; ?>">

			<?php wp_editor( $value, "_rbm_$args[wysiwyg_id]", $args['wysiwyg_args'] ); ?>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</div>
		<?php
	}
}