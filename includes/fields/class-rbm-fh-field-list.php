<?php
/**
 * Field: List
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_List
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_List extends RBM_FH_Field {

	/**
	 * Data to localize for the list fields.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	static $data = array();

	/**
	 * Field defaults.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $defaults = array(
		'items'         => array(),
		'sortable_args' => array(
			'axis' => 'y',
		),
	);

	/**
	 * RBM_FH_Field_List constructor.
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

		if ( empty( self::$data ) ) {

			add_filter( 'rbm_field_helpers_admin_data', array( __CLASS__, 'localize_data' ) );
		}

		self::$data[ $this->name ] = $this->args['sortable_args'];
	}

	/**
	 * Localizes data for the fields on the page.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param array $data Data to be localized.
	 *
	 * @return array
	 */
	static function localize_data( $data ) {

		$data['list_fields'] = self::$data;

		return $data;
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
		<div class="rbm-field-list <?php echo $args['wrapper_class']; ?>" data-name="<?php echo esc_attr( $name ); ?>">
			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
			</label>

			<ul class="rbm-field-list-items" data-rbm-list>
				<?php foreach ( $args['items'] as $value => $label ) : ?>
					<li class="rbm-field-list-item">
						<span class="rbm-field-list-item-handle dashicons dashicons-menu"></span>

						<?php echo esc_attr( $label ); ?>

						<input type="hidden" name="<?php echo esc_attr( $name ); ?>[]"
						       value="<?php echo esc_attr( $value ); ?>"/>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</div>
		<?php
	}
}