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

		if ( $label ) : ?>
			<p class="rbm-field-repeater-label">
				<?php echo $label; ?>
			</p>
		<?php endif; ?>

		<div class="rbm-field-repeater repeater">
			<div class="rbm-field-repeater-list" data-repeater-list="<?php echo $name; ?>">

				<?php for ( $i = 0; $i < $row_count; $i ++ ) : ?>

					<div class="rbm-field-repeater-row <?php echo $empty ? 'dummy' : ''; ?>" data-repeater-item>

						<div class="rbm-field-repeater-handle"></div>

						<?php foreach ( $fields as $field_name => $field ) : ?>
							<?php

							if ( in_array( $field['type'], array( 'table', 'user_keys' ) ) ){
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

									$field['args']['wysiwyg_id']   = "{$name}_{$i}_{$field_name}";
									$field['args']['wysiwyg_args'] = array(
										'textarea_name' => $field_name,
										'teeny'         => true,
										'textarea_rows' => 6,
									);
								}

								call_user_func( "rbm_do_field_$field[type]", $field_name, $field['label'], $field['value'], $field['args'] );
							}
							?>
						<?php endforeach; ?>

						<div class="clearfix"></div>
						<hr/>
						<input data-repeater-delete type="button" class="button" value="Delete"/>
					</div>

				<?php endfor; ?>

			</div>

			<input data-repeater-create type="button" class="button button-primary" value="Add"/>
		</div>
		<?php
	}
}