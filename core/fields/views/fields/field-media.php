<?php
/**
 * Field Template: Media
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();
?>

<div class="fieldhelpers-media-uploader"
     data-type="<?php echo esc_attr( $args['type'] ); ?>"
     data-title="<?php echo esc_attr( $args['window_title'] ); ?>"
     data-button-text="<?php echo esc_attr( $args['window_button_text'] ); ?>"
     data-preview-size="<?php echo esc_attr( $args['preview_size'] ); ?>"
     data-fieldhelpers-field-media
>

	<?php echo $args['label'] ? "<strong>{$args['label']}</strong><br/>" : ''; ?>

	<?php
	switch ( $args['type'] ) :

		case 'image':

			$args['placeholder'] = $args['placeholder'] != '&nbsp;' ? $args['placeholder'] : '';
			?>
            <img src="<?php echo $value ? esc_attr( $args['media_preview_url'] ) : esc_attr( $args['placeholder'] ); ?>"
                 data-image-preview
                 data-placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
            />
			<?php
			break;

		default:
			?>
            <code data-media-preview
                  data-placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
            >
				<?php echo $value ? $args['media_preview_url'] : $args['placeholder']; ?>
            </code>
			<?php
	endswitch;
	?>

    <br/>

    <input type="button"
           class="button"
           value="<?php echo esc_attr( $args['button_text'] ); ?>"
           data-add-media
		<?php echo $value ? 'style="display: none;"' : ''; ?>
    />
    <input type="button"
           class="button"
           value="<?php echo esc_attr( $args['button_remove_text'] ); ?>"
           data-remove-media
		<?php echo ! $value ? 'style="display: none;"' : ''; ?>
    />

    <input type="hidden"
           name="<?php echo esc_attr( $name ); ?>"
           value="<?php echo esc_attr( $value ); ?>"
           class="<?php echo isset( $args['input_class'] ) ? esc_attr( $args['input_class'] ) : ''; ?>"
           data-media-input
		<?php RBM_FH_Field::input_atts( $args ); ?>
    />
</div>
