<?php
/**
 * Field Template: Repeater
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();

$empty = ! $value;

$row_count = count( $value ) >= 1 ? count( $value ) : 1;

// Classes
$classes = array(
	'rbm-field-repeater',
	'repeater',
);

if ( $args['collapsable'] ) {

	$classes[] = 'collapsable';
}

if ( $args['sortable'] ) {

	$classes[] = 'sortable';
}
?>

<div class="repeater <?php echo implode( ' ', $classes ); ?>"
     data-is-sortable="<?php echo $args['sortable'] ? 'yes' : 'no'; ?>"
     data-confirm-delete-text="<?php echo esc_attr( $args['confirm_delete_text'] ); ?>">
    <div class="rbm-field-repeater-list" data-repeater-list="<?php echo esc_attr( $name ); ?>">

		<?php for ( $i = 0; $i < $row_count; $i ++ ) : ?>

            <div class="rbm-field-repeater-row <?php echo $empty ? 'dummy' : ''; ?>" data-repeater-item>

				<?php if ( $args['collapsable'] ) : ?>

                    <div class="rbm-field-repeater-header">

                        <div class="rbm-field-repeater-handle"></div>

                        <div class="rbm-field-repeater-header-interior">

                            <h2 data-repeater-collapsable-handle>
										<span class="collapsable-title"
                                              data-collapsable-title-default="<?php echo $args['collapsable_title']; ?>">
											<?php echo apply_filters( 'rbm_fh_repeater_collapsable_title', $args['collapsable_title'], $name, $value[ $i ], $args['fields'] ); ?>
										</span>
                                <span class="collapse-icon dashicons dashicons-arrow-down-alt2">
										</span>
                                <input data-repeater-delete type="button" class="button" value="Delete"
                                       style="float: right;"/>
                            </h2>

                        </div>

                    </div>


				<?php else : ?>

                    <div class="rbm-field-repeater-handle"></div>

				<?php endif; ?>

                <div class="rbm-field-repeater-content">

					<?php foreach ( $args['fields'] as $field_name => $field ) : ?>
						<?php

						if ( in_array( $field['type'], array( 'table', 'user_keys' ) ) ) {
							echo "<strong>ERROR:</strong> Field <strong>$field[type]</strong> not supported for repeaters.";
							continue;
						}

						if ( is_callable( "rbm_do_field_$field[type]" ) ) {

							$field = wp_parse_args( $field, array(
								'value' => isset( $value[ $i ][ $field_name ] ) ? $value[ $i ][ $field_name ] : false,
								'args'  => array(),
							) );

							$field['args']['no_init'] = true;

							if ( $field['type'] == 'wysiwyg' ) {

								$wysiwyg_args = isset( $field['args']['wysiwyg_args'] ) ?
									$field['args']['wysiwyg_args'] : array();

								$field['args']['wysiwyg_id']   = "{$name}_{$i}_{$field_name}";
								$field['args']['wysiwyg_args'] = wp_parse_args( $wysiwyg_args, array(
									'textarea_name' => $field_name,
									'teeny'         => true,
									'textarea_rows' => 6,
								) );
							}

							// Legacy
							call_user_func(
								array(
									$args['fields_instance'],
									"do_field_$field[type]",
								),
								$field_name,
								$field['args'],
								$field['value'] );
						}
						?>
					<?php endforeach; ?>
                </div>

				<?php if ( $args['collapsable'] ) : ?>

                    <div class="clearfix"></div>

				<?php else : ?>

                    <div class="clearfix"></div>

                    <hr/>
                    <input data-repeater-delete type="button" class="button" value="Delete"/>

				<?php endif; ?>

            </div>

		<?php endfor; ?>

    </div>

    <input data-repeater-create type="button" class="button button-primary" value="Add"/>
</div>