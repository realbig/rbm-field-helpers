<?php
/**
 * Main field view.
 *
 * @since {{VERSION}}
 *
 * @var string $type
 * @var array $args
 * @var string $name
 * @var mixed $value
 */

defined( 'ABSPATH' ) || die();
?>

<div class="<?php echo ( implode( ' ', $args['wrapper_classes'] ) ) . " {$args['prefix']}-fieldhelpers-field fieldhelpers-field-{$type}"; ?>">
    <header class="fieldhelpers-field-header">
		<?php
		/**
		 * Outputs the field header.
		 *
		 * @since {{VERSION}}
		 */
		do_action( "{$args['prefix']}_fieldhelpers_field_template_header", $type, $args, $name, $value );
		?>
    </header>

    <div class="fieldhelpers-field-content">
		<?php
		/**
		 * Outputs the field content.
		 *
		 * @since {{VERSION}}
		 */
		do_action( "{$args['prefix']}_fieldhelpers_field_template_content", $type, $args, $name, $value );
		?>
    </div>

    <footer class="fieldhelpers-field-footer">
		<?php
		/**
		 * Outputs the field footer.
		 */
		do_action( "{$args['prefix']}_fieldhelpers_field_template_footer", $type, $args, $name, $value );
		?>
    </footer>
</div>