<?php
/**
 * Field Label.
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

<?php if ( $args['label']) : ?>
	<label for="<?php echo $name; ?>" class="fieldhelpers-field-label">
		<?php echo $args['label']; ?>
	</label>
<?php endif; ?>