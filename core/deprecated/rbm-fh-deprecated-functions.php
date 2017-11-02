<?php
/**
 * Deprecated functionality.
 * 
 * @since {{VERSION}}
 */

defined( 'ABSPATH' ) || die();

/**
 * Gets the main plugin instance.
 *
 * @since 1.1.0
 * 
 * @deprecated {{VERSION}}
 *
 * @return RBM_FieldHelpers
 */
function RBMFH() {
	return RBM_FieldHelpers::getInstance();
}

/**
 * Retrieves a rbm post field value from the DB.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $field The fieldname to get.
 * @param bool|false $post_ID Supply post ID to get field from different post.
 * @param array $args Arguments.
 *
 * @return bool|mixed Post meta or false if can't get post.
 */
function rbm_get_field( $field, $post_ID = false, $args = array() ) {

	global $post;

	if ( $post_ID === false && ( $post instanceof WP_Post ) ) {
		$post_ID = $post->ID;
	} elseif ( $post_ID === false ) {
		return false;
	}

	$args = wp_parse_args( array(
		'sanitization' => false,
		'single'       => true,
	) );

	$value = get_post_meta( $post_ID, "_rbm_$field", $args['single'] );

	if ( $args['sanitization'] && is_callable( $args['sanitization'] ) ) {
		$value = call_user_func( $args['sanitization'], $value );
	}

	/**
	 * Allows filtering of the field value.
	 *
	 * @since 1.1.0
     *
     * @deprecated {{VERSION}}
	 */
	$value = apply_filters( 'rbm_get_field', $value, $field, $post_ID );

	/**
	 * Allows filtering of the specific field value.
	 *
	 * @since 1.1.0
     *
     * @deprecated {{VERSION}}
	 */
	$value = apply_filters( "rbm_get_field_$field", $value, $post_ID );

	return $value;
}

/**
 * Retrieves and echos a rbm post field value from the DB.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
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
 * This one is used for replacing the taxonomy MB content.
 *
 * @since 1.1.0
 * 
 * @deprecated {{VERSION}}
 *
 * @param $post
 * @param $taxonomy
 * @param string $type
 */
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
 * Legacy.
 *
 * @since 1.1.0
 *
 * @deprecated {{VERSION}}
 *
 * @param $name
 * @param bool $label
 * @param array $args
 */
function rbm_do_helper_field_button( $name, $label = false, $args = array() ) {
	rbm_helper_field_link( $name, $label, $args );
}

/**
 * Provides an easy to use link.
 *
 * @since 1.1.0
 * 
 * @deprecated {{VERSION}}
 *
 * @param $name
 * @param bool $label
 * @param array $args
 */
function rbm_helper_field_link( $name, $label = false, $args = array() ) {

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
 * Adds metabox for taxonomy.
 * 
 * @since 1.1.0
 * 
 * @deprecated {{VERSION}}
 * 
 * @param $taxonomy
 * @param string $post_type
 * @param string $input_type
 */
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

/**
 * Metabox output for taxonomy.
 * 
 * @since 1.1.0
 *
 * @deprecated {{VERSION}}
 * 
 * @param $post
 * @param $args
 */
function rbm_mb_taxonomy( $post, $args ) {

	rbm_do_field_taxonomy( $post, $args['args']['taxonomy'], $args['args']['input_type'] );
}

/**
 * Outputs a text field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_text( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Text( $name, $label, $args, $value );
}

/**
 * Outputs a textarea field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_textarea( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_TextArea( $name, $label, $args, $value );
}

/**
 * Outputs a checkbox field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_checkbox( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Checkbox( $name, $args );
}

/**
 * Outputs a radio field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_radio( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Radio( $name, $label, $args, $value );
}

/**
 * Outputs a select field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_select( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Select( $name, $label, $args, $value );
}

/**
 * Outputs a number field.
 *
 * @since 1.3.2
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_number( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Number( $name, $args );
}

/**
 * Outputs an image field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_media( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Media( $name, $label, $args, $value );
}

/**
 * Outputs an image field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
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
 * Outputs a datepicker field.
 *
 * @since 1.1.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_datepicker( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_DatePicker( $name, $args );
}

/**
 * Outputs a timepicker field.
 *
 * @since {{VERSION}}
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_timepicker( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_TimePicker( $name, $label, $args, $value );
}

/**
 * Outputs a datetimepicker field.
 *
 * @since {{VERSION}}
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_datetimepicker( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_DateTimePicker( $name, $label, $args, $value );
}

/**
 * Outputs a colorpicker field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_colorpicker( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_ColorPicker( $name, $label, $args, $value );
}

/**
 * Outputs a list field.
 *
 * @since 1.3.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_list( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_List( $name, $label, $args, $value );
}

/**
 * Outputs a repeater field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param array $fields
 * @param mixed $values
 */
function rbm_do_field_repeater( $name, $label = false, $fields, $values = false ) {

	$args = array( 'fields' => $fields );

	new RBM_FH_Field_Repeater( $name, $args, $values );
}

/**
 * Outputs a table field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_table( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Table( $name, $label, $args, $value );
}

/**
 * Outputs a WYSIWYG field.
 *
 * @since 1.2.0
 *
 * @deprecated {{VERSION}}
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_wysiwyg( $name, $label = false, $value = false, $args = array() ) {

    $args['wysiwyg'] = true;

	new RBM_FH_Field_TextArea( $name, $label, $args, $value );
}