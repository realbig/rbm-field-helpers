<?php
/**
 * Provides helper functions shared among all RBM plugins.
 *
 * @version 1.0.0
 *
 * @package    CPTAnimal
 * @subpackage CPTAnimal/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// REMOVE
add_action( 'add_meta_boxes', function () {

	add_meta_box(
		'test',
		'Test',
		function () {

			rbm_do_field_text( 'text_test', 'Text Test' );
			rbm_do_field_textarea( 'textarea_test', 'Textarea Test' );
			rbm_do_field_checkbox( 'checkbox_test', 'Checkbox Test' );
			rbm_do_field_radio( 'radio_test', 'Radio Test', false, array(
				'options' => array(
					1 => 'One',
					2 => 'Two',
				),
			) );
			rbm_do_field_select( 'select_test', 'Select TEst', false, array(
				'options' => array(
					1 => 'One',
					2 => 'Two',
				),
			) );
			rbm_do_field_image( 'media_test', 'Media Test', false, array() );
			rbm_do_field_datepicker( 'datepicker_test', 'Date Picker Test' );
			rbm_do_field_colorpicker( 'colorpicker_test', 'Colorpicker Test' );
			rbm_do_field_repeater( 'repeater_test', 'Repeater Test', array(
				'one' => array(
					'type' => 'text',
					'label' => 'Text',
				),
				'two' => array(
					'type' => 'textarea',
					'label' => 'Textarea',
				),
				'three' => array(
					'type' => 'checkbox',
					'label' => 'Check Box',
				),
				'four' => array(
					'type' => 'radio',
					'label' => 'Radio',
					'args' => array(
						'options' => array(
							1 => 'One',
							2 => 'Two',
						),
					),
				),
				'five' => array(
					'type' => 'select',
					'label' => 'Select',
					'args' => array(
						'options' => array(
							1 => 'One',
							2 => 'Two',
						),
					),
				),
				'six' => array(
					'type' => 'image',
					'label' => 'Image',
				),
				'seven' => array(
					'type' => 'datepicker',
					'label' => 'Date Picker',
				),
				'eight' => array(
					'type' => 'colorpicker',
					'label' => 'Colorpicker',
				),
			) );
		},
		'post'
	);
} );

// Only load once
if ( ! defined( 'RBM_HELPER_FUNCTIONS' ) ) {

	define( 'RBM_HELPER_FUNCTIONS', true );
	define( 'RBM_FIELD_HELPERS_VER', '1.0.0' );

	require_once __DIR__ . '/vendor/select2/select2-load.php';

	require_once __DIR__ . '/includes/class-rbm-fh-field.php';

	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-checkbox.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-colorpicker.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-datepicker.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-media.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-radio.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-repeater.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-select.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-text.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-textarea.php';
	require_once __DIR__ . '/includes/fields/class-rbm-fh-field-userkeys.php';

	add_action( 'save_post', '_rbm_save_meta' );

	final class RBM_FieldHelpers {

		/**
		 * All registered fields.
		 *
		 * @since 1.6.0
		 *
		 * @var array
		 */
		public $fields = array();

		/**
		 * Loaded scripts.
		 *
		 * @since {{VERSION}}
		 *
		 * @var array
		 */
		public $scripts = array();

		/**
		 * Loaded styles.
		 *
		 * @since {{VERSION}}
		 *
		 * @var array
		 */
		public $styles = array();

		private function __clone() {
		}

		private function __wakeup() {
		}

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @since {{VERSION}}
		 *
		 * @staticvar Singleton $instance The *Singleton* instances of this class.
		 *
		 * @return RBM_FieldHelpers The *Singleton* instance.
		 */
		public static function getInstance() {

			static $instance = null;

			if ( null === $instance ) {
				$instance = new static();
			}

			return $instance;
		}

		/**
		 * RBM_FieldHelpers constructor.
		 *
		 * @since {{VERSION}}
		 */
		function __construct() {

			add_action( 'admin_init', array( $this, 'register_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Registers all scripts.
		 *
		 * @since {{VERSION}}
		 * @access private
		 */
		function register_scripts() {

			global $wp_scripts;

			// Admin styles
			wp_register_style(
				'RBM-field-helpers-admin',
				plugins_url( '/rbm-field-helpers-admin.css', __FILE__ ),
				array(),
				RBM_FIELD_HELPERS_VER
			);

			// Admin script
			wp_register_script(
				'RBM-field-helpers-admin',
				plugins_url( '/rbm-field-helpers-admin.js', __FILE__ ),
				array( 'jquery', 'RBM-jquery-repeater', 'wp-color-picker', 'jquery-ui-sortable' ),
				RBM_FIELD_HELPERS_VER,
				true
			);

			// Repeater
			wp_register_script(
				'RBM-jquery-repeater',
				plugins_url( '/vendor/jquery-repeater/jquery.repeater.min.js', __FILE__ ),
				array( 'jquery' ),
				'0.1.4',
				true
			);


			// get registered script object for jquery-ui
			$ui = $wp_scripts->query( 'jquery-ui-core' );

			// tell WordPress to load the Smoothness theme from Google CDN
			$url = "http://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";
			wp_register_style(
				'jquery-ui-smoothness',
				$url,
				false,
				null
			);

			// Localize data
			$data = apply_filters( 'rbm_field_helpers_admin_data', array(
				'nonce' => wp_create_nonce( 'rbm-field-helpers' ),
			) );

			wp_localize_script( 'RBM-field-helpers-admin', 'RBM_FieldHelpers', $data );
		}

		/**
		 * Enqueues common scripts.
		 *
		 * @since {{VERSION}}
		 * @access private
		 */
		function enqueue_scripts() {

			wp_enqueue_script( 'RBM-field-helpers-admin' );
			wp_enqueue_script( 'RBM-jquery-repeater' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-datepicker');

			wp_enqueue_style( 'jquery-ui-smoothness' );
			wp_enqueue_style( 'RBM-field-helpers-admin' );
			wp_enqueue_style( 'wp-color-picker' );
		}
	}

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

	new RBM_FieldHelpers();

	// TODO get these wehre they need to go
	function _rbm_shared_fields_enqueue_admin_scripts() {
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

		new RBM_FH_Field_Text( $name, $label, $args, $value );
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

		new RBM_FH_Field_TextArea( $name, $label, $args, $value );
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

		new RBM_FH_Field_Checkbox( $name, $label, $args, $value );
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

		new RBM_FH_Field_Radio( $name, $label, $args, $value );
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

		new RBM_FH_Field_Select( $name, $label, $args, $value );
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

		new RBM_FH_Field_Media( $name, $label, $args, $value );
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
	 * Outputs a datepicker field.
	 *
	 * @since 1.6.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param string|bool $value
	 * @param array $args
	 */
	function rbm_do_field_datepicker( $name, $label = false, $value = false, $args = array() ) {

		new RBM_FH_Field_DatePicker( $name, $label, $args, $value );
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

		new RBM_FH_Field_ColorPicker( $name, $label, $args, $value );
	}

	/**
	 * Outputs a repeater field.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name
	 * @param string|bool $label
	 * @param array $fields
	 * @param mixed $values
	 */
	function rbm_do_field_repeater( $name, $label = false, $fields, $values = false ) {

		$args = array( 'fields' => $fields );

		new RBM_FH_Field_Repeater( $name, $label, $args, $values );
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

	/**
	 * User Keys field.
	 *
	 * @since 1.6.0
	 *
	 * @param bool $label
	 * @param array $args
	 * @param bool $value
	 */
	function rbm_do_field_user_keys( $label = false, $args = array(), $value = false ) {
		new RBM_FH_Field_UserKeys( $label, $args, $value );
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