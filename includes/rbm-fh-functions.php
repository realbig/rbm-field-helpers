<?php
/**
 * Provides global functions for the plugin.
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes
 */

defined( 'ABSPATH' ) || die();

/**
 * Gets the main plugin instance.
 *
 * @since {{VERSION}}
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
		$value = call_user_func( $sanitization, get_post_meta( $post_ID, "_rbm_$field", true ) );
	} else {
		$value = get_post_meta( $post_ID, "_rbm_$field", true );
	}

	/**
	 * Allows filtering of the field value.
	 *
	 * @since 1.6.0
	 */
	$value = apply_filters( 'rbm_get_field', $value, $field, $post_ID );

	/**
	 * Allows filtering of the specific field value.
	 *
	 * @since 1.6.0
	 */
	$value = apply_filters( "rbm_get_field_$field", $value, $post_ID );

	return $value;
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
 * This one is used for replacing the taxonomy MB content.
 *
 * @since {{VERSION}}
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
 * @since {{VERSION}}
 *
 * @deprecated
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
 * @since {{VERSION}}
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