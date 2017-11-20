<?php
/**
 * Field Description.
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

<?php if ( $args['description'] ) : ?>

    <div class="fieldhelpers-field-description">
		<?php echo $args['description']; ?>
    </div>

<?php endif; ?>
