<?php
/**
 * Field: Media
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
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
		'button_text'        => 'Upload / Choose Media',
		'button_remove_text' => 'Remove Media',
		'type'               => 'any',
		'window_title'       => 'Choose Media',
		'window_button_text' => 'Use Media',
		'placeholder'        => '&nbsp;',
	);

	/**
	 * RBM_FH_Field_Media constructor.
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

		if ( $media_item_src = wp_get_attachment_image_src( $value, $args['preview_size'] ) ) {
			$media_preview_url = $media_item_src[0];
		} else {
			$media_preview_url = wp_get_attachment_url( $value );
		}
		?>
		<div class="rbm-field-media <?php echo $args['wrapper_class']; ?>">
			<div class="rbm-media-uploader" data-type="<?php echo $args['type']; ?>"
			     data-title="<?php echo $args['window_title']; ?>"
			     data-button-text="<?php echo $args['window_button_text']; ?>">

				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>

				<?php
				switch ( $args['type'] ) :

					case 'image':

						$args['placeholder'] = $args['placeholder'] != '&nbsp;' ? $args['placeholder'] : '';
						?>
						<img src="<?php echo $value ? $media_preview_url : $args['placeholder']; ?>"
						     class="image-preview"
						     data-placeholder="<?php echo $args['placeholder']; ?>"/>
						<?php
						break;

					default:
						?>
						<code class="media-url" data-placeholder="<?php echo $args['placeholder']; ?>">
							<?php echo $value ? $media_preview_url : $args['placeholder']; ?>
						</code>
						<?php
				endswitch;
				?>

				<br/>

				<input type="button" class="button upload-media"
				       value="<?php echo $args['button_text']; ?>" <?php echo $value ? 'style="display: none;"' : ''; ?> />
				<input type="button" class="button remove-media"
				       value="<?php echo $args['button_remove_text']; ?>" <?php echo ! $value ? 'style="display: none;"' : ''; ?> />

				<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>"
				       class="media-id <?php echo isset( $args['input_class'] ) ? $args['input_class'] : ''; ?>"/>
			</div>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</div>
		<?php
	}
}