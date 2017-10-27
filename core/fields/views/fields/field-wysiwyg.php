<?php
/**
 * Field Template: WYSIWYG
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();
?>

<?php wp_editor( $value, "_fieldhelpers_$args[wysiwyg_id]", $args['wysiwyg_args'] ); ?>