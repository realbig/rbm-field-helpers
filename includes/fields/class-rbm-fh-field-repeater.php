<?php
/**
 * Field: Repeater
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Repeater
 *
 * @since 1.1.0
 */
class RBM_FH_Field_Repeater extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $defaults = array(
		'collapsable'         => false,
		'collapsable_title'   => 'New Row',
		'sortable'            => true,
		'confirm_delete_text' => '',
	);

	/**
	 * RBM_FH_Field_Repeater constructor.
	 *
	 * @since 1.1.0
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $values
	 */
	function __construct( $name, $label = '', $args = array(), $values = false ) {

		wp_parse_args( $args, array(
			'confirm_delete_text' => __( 'Are you sure you want to delete this element?', 'rbm-field-helpers' ),
		) );

		parent::__construct( $name, $label, $args, $values );
	}

	/**
	 * Outputs the field.
	 *
	 * @since 1.1.0
	 *
	 * @param string $name Name of the field.
	 * @param mixed $values Value of the field.
	 * @param string $label Field label.
	 * @param array $args Args.
	 */
	public static function field( $name, $values, $label = '', $args = array() ) {

		$fields = $args['fields'];

		$empty = ! $values;

		$row_count = count( $values ) >= 1 ? count( $values ) : 1;

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

		if ( $label ) : ?>
            <p class="rbm-field-repeater-label">
				<?php echo $label; ?>
            </p>
		<?php endif; ?>

        <div class="rbm-field-repeater repeater <?php echo implode( ' ', $classes ); ?>"
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
											<?php echo apply_filters( 'rbm_fh_repeater_collapsable_title', $args['collapsable_title'], $name, $values[ $i ], $fields ); ?>
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

							<?php foreach ( $fields as $field_name => $field ) : ?>
								<?php

								if ( in_array( $field['type'], array( 'table', 'user_keys' ) ) ) {
									echo "<strong>ERROR:</strong> Field <strong>$field[type]</strong> not supported for repeaters.";
									continue;
								}

								if ( is_callable( "rbm_do_field_$field[type]" ) ) {

									$field = wp_parse_args( $field, array(
										'label' => false,
										'value' => isset( $values[ $i ][ $field_name ] ) ? $values[ $i ][ $field_name ] : false,
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

									call_user_func( "rbm_do_field_$field[type]", $field_name, $field['label'], $field['value'], $field['args'] );
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
		<?php
	}
}