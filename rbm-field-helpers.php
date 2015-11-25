<?php
/**
 * Provides helper functions shared among all DZS plugins.
 *
 * @version 1.3.2
 *
 * @package    CPTAnimal
 * @subpackage CPTAnimal/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Only load once
if ( ! defined( 'DZOO_HELPER_FUNCTIONS' ) ) {

	define( 'DZOO_HELPER_FUNCTIONS', true );
	define( 'DZS_FIELD_HELPERS_VER', '1.3.2' );

	require_once __DIR__ . '/vendor/select2/select2-load.php';

	add_action( 'init', '_rbm_shared_fields_register_scripts' );
	add_action( 'admin_enqueue_scripts', '_rbm_shared_fields_enqueue_admin_scripts' );
	add_action( 'save_post', '_rbm_save_meta' );

	function _rbm_shared_fields_register_scripts() {

		// Admin styles
		wp_register_style(
			'DZS-field-helpers-admin',
			plugins_url( '/rbm-field-helpers-admin.css', __FILE__ ),
			array(),
			DZS_FIELD_HELPERS_VER
		);

		// Admin script
		wp_register_script(
			'DZS-field-helpers-admin',
			plugins_url( '/rbm-field-helpers-admin.js', __FILE__ ),
			array( 'jquery', 'DZS-jquery-repeater', 'wp-color-picker', 'jquery-ui-sortable' ),
			DZS_FIELD_HELPERS_VER,
			true
		);

		// Repeater
		wp_register_script(
			'DZS-jquery-repeater',
			plugins_url( '/vendor/jquery-repeater/jquery.repeater.min.js', __FILE__ ),
			array( 'jquery' ),
			'0.1.4',
			true
		);
	}

	function _rbm_shared_fields_enqueue_admin_scripts() {

		wp_enqueue_script( 'DZS-jquery-repeater' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'DZS-field-helpers-admin' );
		wp_enqueue_style( 'DZS-field-helpers-admin' );
	}

	function _rbm_save_meta( $post_ID ) {

		// Make sure we should be here!
		if ( ! isset( $_POST['_rbm_fields'] ) ||
		     ! wp_verify_nonce( $_POST['rbm-meta'], 'rbm-save-meta' ) ||
		     ! current_user_can( 'edit_posts' )
		) {
			return;
		}

		foreach ( $_POST['_rbm_fields'] as $field ) {
			update_post_meta( $post_ID, $field, $_POST[ $field ] );
		}
	}

	function _rbm_field_init( $name ) {

		global $rbm_fields;

		static $i;
		$i = $i === null ? 0 : $i + 1;

		if ( empty( $rbm_fields ) ) {
			wp_nonce_field( 'rbm-save-meta', 'rbm-meta' );
		}

		$rbm_fields[] = $name;
		?>
		<input type="hidden" name="_rbm_fields[<?php echo $i; ?>]" value="<?php echo $name; ?>"/>
		<?php
	}

	/**
	 * Outputs a text field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_text( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'       => '',
			'description'   => false,
			'sanitization'  => false,
			'input_class'   => 'widefat',
			'input_atts'    => array(),
			'wrapper_class' => '',
		) );

		$input_atts = array();
		foreach ( $args['input_atts'] as $attr_name => $attr_value ) {
			$input_atts[] = "$attr_name=\"$attr_value\"";
		}
		$input_atts = implode( ' ', $input_atts );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}

		if ( $args['sanitization'] && is_callable( $args['sanitization'] ) ) {

			$value = call_user_func( $args['sanitization'], $value );
			update_post_meta( get_the_ID(), $name, $value );
		}
		?>
		<p class="rbm-field-text <?php echo $args['wrapper_class']; ?>">
			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
				<input type="text" name="<?php echo $name; ?>" value="<?php echo $value ? $value : $args['default']; ?>"
				       class="<?php echo isset( $args['input_class'] ) ? $args['input_class'] : 'regular-text'; ?>"
					<?php echo $input_atts; ?> />
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}

	/**
	 * Outputs a number field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_number( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
				'default'       => '',
				'description'   => false,
				'sanitization'  => false,
				'input_class'   => '',
				'input_atts'    => array( 'style' => 'width: 3em' ),
				'wrapper_class' => '',
		) );

		$input_atts = array();
		foreach ( $args['input_atts'] as $attr_name => $attr_value ) {
			$input_atts[] = "$attr_name=\"$attr_value\"";
		}
		$input_atts = implode( ' ', $input_atts );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}

		if ( $args['sanitization'] && is_callable( $args['sanitization'] ) ) {

			$value = call_user_func( $args['sanitization'], $value );
			update_post_meta( get_the_ID(), $name, $value );
		}
		?>
		<p class="rbm-field-number <?php echo $args['wrapper_class']; ?>">
			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
				<input type="number" name="<?php echo $name; ?>" value="<?php echo $value ? $value : $args['default']; ?>"
				       class="<?php echo $args['input_class']; ?>"
						<?php echo $input_atts; ?> />
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}

	/**
	 * Outputs a textarea field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_textarea( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'       => '',
			'rows'          => 4,
			'sanitization'  => 'esc_attr',
			'description'   => false,
			'input_class'   => 'widefat',
			'wrapper_class' => '',
		) );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}

		if ( $args['sanitization'] && is_callable( $args['sanitization'] ) ) {
			$value = call_user_func( $args['sanitization'], $value );
		}
		?>
		<p class="rbm-field-textarea <?php echo $args['wrapper_class']; ?>">
			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
				<textarea name="<?php echo $name; ?>"
				          class="<?php echo $args['input_class']; ?>"
				          rows="<?php echo $args['rows']; ?>"
				><?php echo $value; ?></textarea>
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}

	/**
	 * Outputs a checkbox field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_checkbox( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'              => 0,
			'check_value'          => 1,
			'check_disabled_value' => 0,
			'check_label'          => '',
			'description'          => false,
			'input_class'          => '',
			'wrapper_class'        => '',
		) );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}

		if ( is_array( $value ) ) {
			$value = array_shift( $value );
		}
		?>
		<p class="rbm-field-checkbox <?php echo $args['wrapper_class']; ?>">
			<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $args['check_disabled_value']; ?>"/>

			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>

				<input type="checkbox" name="<?php echo $name; ?>"
				       value="<?php echo $args['check_value']; ?>"
				       class="<?php echo $args['input_class']; ?>"
					<?php checked( $args['check_value'], $value ); ?> />

				<?php echo $args['check_label']; ?>
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}

	/**
	 * Outputs a radio field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_radio( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'       => false,
			'radio_value'   => 1,
			'radio_label'   => '',
			'description'   => false,
			'input_class'   => '',
			'wrapper_class' => '',
		) );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}
		?>
		<p class="rbm-field-radio <?php echo $args['wrapper_class']; ?>">

			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>

				<input type="radio" name="<?php echo $name; ?>"
				       value="<?php echo $args['radio_value']; ?>"
				       class="<?php echo $args['input_class']; ?>"
					<?php checked( $args['radio_value'], $value ); ?> />

				<?php echo $args['radio_label']; ?>
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}

	/**
	 * Outputs a select field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_select( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'       => false,
			'options'       => array(),
			'opt_groups'    => false,
			'multiple'      => false,
			'description'   => false,
			'no_options'    => 'No options available',
			'input_class'   => 'widefat',
			'wrapper_class' => '',
		) );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}
		?>
		<p class="rbm-field-select <?php echo $args['wrapper_class']; ?>">
			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>

				<?php if ( ! empty( $args['options'] ) ) : ?>
					<select name="<?php echo $name . ( $args['multiple'] ? '[]' : '' ); ?>"
					        class="<?php echo $args['input_class']; ?>"
						<?php echo $args['multiple'] ? 'multiple' : ''; ?>>

						<?php if ( $args['opt_groups'] ) : ?>

							<?php foreach ( $args['options'] as $opt_group_label => $options ) : ?>
								<optgroup label="<?php echo $opt_group_label; ?>">

									<?php
									foreach ( $options as $option_value => $option_label ) :

										if ( $args['multiple'] ) {
											$selected = in_array( $option_value, (array) $value ) ? 'selected' : '';
										} else {
											$selected = selected( $option_value, $value, false ) ? 'selected' : '';
										}
										?>
										<option value="<?php echo $option_value; ?>" <?php echo $selected; ?>>
											<?php echo $option_label; ?>
										</option>
									<?php endforeach; ?>

								</optgroup>
								<?php
							endforeach;

						else:

							foreach ( $args['options'] as $option_value => $option_label ) :

								if ( $args['multiple'] ) {
									$selected = in_array( $option_value, (array) $value ) ? 'selected' : '';
								} else {
									$selected = selected( $option_value, $value, false ) ? 'selected' : '';
								}
								?>
								<option value="<?php echo $option_value; ?>" <?php echo $selected; ?>>
									<?php echo $option_label; ?>
								</option>

								<?php
							endforeach;
						endif;
						?>

					</select>
				<?php else: ?>
					<?php echo $args['no_options']; ?>
				<?php endif; ?>
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}

	/**
	 * Outputs an image field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_image( $name, $label = false, $value = false, $args = array() ) {

		$args['type']               = 'image';
		$args['button_text']        = 'Upload / Choose Image';
		$args['button_remove_text'] = 'Remove Image';
		$args['window_title']       = 'Choose Image';
		$args['window_button_text'] = 'Use Image';

		rbm_do_field_media( $name, $label, $value, $args );
	}

	/**
	 * Outputs an image field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_media( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'            => '',
			'preview_size'       => 'medium',
			'button_text'        => 'Upload / Choose Media',
			'button_remove_text' => 'Remove Media',
			'type'               => 'any',
			'window_title'       => 'Choose Media',
			'window_button_text' => 'Use Media',
			'placeholder'        => '&nbsp;',
			'description'        => false,
			'input_class'        => 'widefat',
			'wrapper_class'      => '',
		) );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}
		$media_item_src = wp_get_attachment_image_src( $value, $args['preview_size'] );
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
						<img src="<?php echo $value ? $media_item_src[0] : $args['placeholder']; ?>"
						     class="image-preview"
						     data-placeholder="<?php echo $args['placeholder']; ?>"/>
						<?php
						break;

					default:
						?>
						<code class="media-url" data-placeholder="<?php echo $args['placeholder']; ?>">
							<?php echo $value ? $media_item_src[0] : $args['placeholder']; ?>
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

	/**
	 * Outputs a colorpicker field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_colorpicker( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'       => '#fff',
			'description'   => false,
			'input_class'   => 'widefat',
			'input_atts'    => array(),
			'wrapper_class' => '',
		) );

		$input_atts = array();
		foreach ( $args['input_atts'] as $attr_name => $attr_value ) {
			$input_atts[] = "$attr_name=\"$attr_value\"";
		}
		$input_atts = implode( ' ', $input_atts );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}
		?>
		<p class="rbm-field-colorpicker <?php echo $args['wrapper_class']; ?>">
			<label>
				<?php echo $label ? "<strong>$label</strong><br/>" : ''; ?>
				<input type="text" name="<?php echo $name; ?>" value="<?php echo $value ? $value : $args['default']; ?>"
				       class="<?php echo $args['input_class']; ?>"
				       data-default-color="<?php echo $args['default']; ?>"
					<?php echo $input_atts; ?> />
			</label>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</p>
		<?php
	}

	/**
	 * Outputs a repeater field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param array $fields
	 */
	function rbm_do_field_repeater( $name, $label = false, $fields, $values = false ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		_rbm_field_init( $name );

		if ( $values === false ) {
			$values = get_post_meta( $post->ID, $name, true );
		}

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

	function rbm_do_field_taxonomy( $post, $taxonomy, $type = 'checkbox' ) {

		//Set up the taxonomy object and get terms
		$taxonomy_object = get_taxonomy( $taxonomy );
		$terms           = get_terms( $taxonomy, array( 'hide_empty' => 0 ) );

		//Name of the form
		$name = 'tax_input[' . $taxonomy . ']' . ( $type == 'checkbox' ? '[]' : '' );

		if ( $postterms = get_the_terms( $post->ID, $taxonomy ) ) {
			$postterms = wp_list_pluck( $postterms, 'term_id' );
		}

		if ( ! empty( $terms ) ) : ?>

			<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
				<ul id="<?php echo $taxonomy; ?>checklist"
				    class="list:<?php echo $taxonomy ?> categorychecklist form-no-clear">
					<?php foreach ( $terms as $term ) {

						$id      = $taxonomy . '-' . $term->term_id;
						$value   = ( is_taxonomy_hierarchical( $taxonomy ) ? "value='{$term->term_id}'" : "value='{$term->name}'" );
						$checked = in_array( $term->term_id, (array) $postterms ) ? 'checked' : '';

						echo "<li id='$id'><label class='selectit'>";
						echo "<input type='$type' id='in-$id' name='{$name}'" . $checked . " {$value} />$term->name<br />";
						echo "</label></li>";
					} ?>
				</ul>
			</div>

		<?php else : ?>
			<p>
				No <?php echo $taxonomy_object->labels->name; ?> yet! Add some <a
					href="<?php echo admin_url( "edit-tags.php?taxonomy=$taxonomy&post_type=$post->post_type" ); ?>">here</a>.
			</p>
		<?php endif;
	}

	/**
	 * Outputs a table field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_table( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'       => array(
				'head' => array(
					'',
					'',
					'',
				),
				'body' => array(
					array(
						'',
						'',
						'',
					),
				),
			),
			'description'   => false,
			'wrapper_class' => '',
		) );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}

		if ( $label ) : ?>
			<p class="rbm-field-repeater-label">
				<?php echo $label; ?>
			</p>
		<?php endif; ?>
		<div class="rbm-field-table <?php echo $args['wrapper_class']; ?>">
			<table>
				<thead>
				<tr>
					<?php
					$tab_index = 1;
					$i         = 0;
					foreach ( $value['head'] as $cell_value ) :
						?>
						<th>
							<p class="rbm-field-table-delete">
								<input type="button" data-table-delete-column class="button" value="Delete"/>
							</p>

							<input type="text" name="<?php echo $name; ?>[head][<?php echo $i; ?>]"
							       value="<?php echo esc_attr( $cell_value ); ?>"
							       tabindex="<?php echo $tab_index; ?>"/>
						</th>
						<?php
						$i ++;
						$tab_index ++;
					endforeach;
					?>
					<th class="actions">
						<!-- Actions -->
					</th>
				</tr>
				</thead>

				<tbody>
				<?php
				$row_i = 0;
				foreach ( $value['body'] as $row => $cells ) :
					?>
					<tr>
						<?php
						$cell_i = 0;
						foreach ( $cells as $cell_value ) :
							?>
							<td>
								<input type="text"
								       name="<?php echo $name; ?>[body][<?php echo $row_i; ?>][<?php echo $cell_i; ?>]"
								       value="<?php echo esc_attr( $cell_value ); ?>"
								       tabindex="<?php echo $tab_index; ?>"/>
							</td>
							<?php
							$cell_i ++;
							$tab_index ++;
						endforeach;
						?>
						<td class="actions">
							<input data-table-delete-row type="button" class="button" value="Delete"/>
							<span class="rbm-field-table-sort dashicons dashicons-sort"></span>
						</td>
					</tr>
					<?php
					$row_i ++;
				endforeach;
				?>
				</tbody>
			</table>

			<input data-table-create-row type="button" class="button" value="Add Row"/>
			<input data-table-create-column type="button" class="button" value="Add Column"/>

		</div>
		<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		<?php
	}

	function rbm_do_field_wysiwyg( $name, $label = false, $value = false, $args = array() ) {

		global $post;

		$name = isset( $args['no_init'] ) && $args['no_init'] ? $name : "_rbm_$name";

		if ( ! isset( $args['no_init'] ) || $args['no_init'] === false ) {
			_rbm_field_init( $name );
		}

		$args = wp_parse_args( $args, array(
			'default'       => '',
			'wysiwyg_args'  => array(),
			'wysiwyg_id'    => $name,
			'description'   => false,
			'wrapper_class' => '',
		) );

		if ( $value === false ) {
			$value = get_post_meta( $post->ID, $name, true );
			$value = $value ? $value : $args['default'];
		}

		if ( $label ) : ?>
			<p class="rbm-field-wysiwyg-label">
				<?php echo $label; ?>
			</p>
		<?php endif; ?>

		<div class="rbm-field-wysiwyg <?php echo $args['wrapper_class']; ?>"
		     data-id="<?php echo $args['wysiwyg_id']; ?>">

			<?php wp_editor( $value, $args['wysiwyg_id'], $args['wysiwyg_args'] ); ?>

			<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		</div>
		<?php
	}

	function rbm_do_helper_field_button( $name, $label = false, $args = array() ) {

		echo '<fieldset class="rbm-fieldset rbm-field-button">';
		echo "<legend>{$label} Button</legend>";

		$pages = get_posts( wp_parse_args( array(
			'post_type'   => 'page',
			'numberposts' => - 1,
		), $args ) );

		$select_options = array();

		if ( ! empty( $pages ) ) {

			/** @var WP_Post $post */
			foreach ( $pages as $page ) {
				$select_options[ $page->ID ] = $page->post_title;
			}
		}

		rbm_do_field_text( "${name}_text", 'Button Text' );
		rbm_do_field_select( "{$name}_post_link", 'Post Link', false, array(
			'options'     => $select_options,
			'input_class' => 'rbm-select2',
		) );
		rbm_do_field_text( "${name}_external_link", 'Link (external)', false, array(
			'description'  => 'Overrides the Post Link',
			'sanitization' => 'esc_url_raw',
		) );
		rbm_do_field_checkbox( "${name}_new_tab", false, false, array(
			'check_label' => 'Open in New Tab',
		) );

		echo '</fieldset>';
	}

	/**
	 * Retrieves and echos a rbm post field value from the DB.
	 *
	 * @since 1.2.0
	 *
	 * @param string $field The fieldname to get.
	 * @param bool|false $post_ID Supply post ID to get field from different post.
	 *
	 * @return bool|mixed Post meta or false if can't get post.
	 */
	function rbm_field( $field, $post_ID = false ) {

		echo rbm_get_field( $field, $post_ID );
	}

	/**
	 * Retrieves a rbm post field value from the DB.
	 *
	 * @since 1.2.0
	 *
	 * @param string $field The fieldname to get.
	 * @param bool|false $post_ID Supply post ID to get field from different post.
	 * @param bool|string $sanitization Sanitization method.
	 *
	 * @return bool|mixed Post meta or false if can't get post.
	 */
	function rbm_get_field( $field, $post_ID = false, $sanitization = false ) {

		global $post;

		if ( $post_ID === false && ( $post instanceof WP_Post ) ) {
			$post_ID = $post->ID;
		} elseif ( $post_ID === false ) {
			return false;
		}

		if ( $sanitization && is_callable( $sanitization ) ) {
			return call_user_func( $sanitization, get_post_meta( $post_ID, "_rbm_$field", true ) );
		} else {
			return get_post_meta( $post_ID, "_rbm_$field", true );
		}
	}

	function rbm_replace_taxonomy_mb( $taxonomy, $post_type = 'post', $input_type = 'checkbox' ) {

		$taxonomy_obj = get_taxonomy( $taxonomy );

		// Remove default tax mb
		remove_meta_box( "tagsdiv-$taxonomy", $post_type, 'side' );

		// Add new tax mb
		add_meta_box(
			"$taxonomy-type_id",
			$taxonomy_obj->labels->name,
			'rbm_mb_taxonomy',
			$post_type,
			'side',
			'core',
			array(
				'taxonomy'   => $taxonomy,
				'input_type' => $input_type,
			)
		);
	}

	function rbm_mb_taxonomy( $post, $args ) {

		rbm_do_field_taxonomy( $post, $args['args']['taxonomy'], $args['args']['input_type'] );
	}
}