<?php
/**
 * Handles all automatic field saving.
 *
 * @since {{VERSION}}
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_FieldsSave
 *
 * Handles all automatic field saving.
 *
 * @since {{VERSION}}
 */
class RBM_FH_FieldsSave {

	/**
	 * Prefix for saving to the database.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string
	 */
	public $prefix = '_rbm';

	/**
	 * All used fields on the current page divided by sections.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $fields = array();

	/**
	 * RBM_FH_FieldsSave constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $prefix Prefix for saving to the database.
	 */
	function __construct( $prefix = '' ) {

		if ( $prefix ) {

			$this->prefix = $prefix;
		}

		add_action( 'save_post', array( $this, 'save_meta_fields_hook' ) );

		// TODO See if better hook for specifically options.php page saving.
		add_action( 'admin_init', array( $this, 'save_option_fields_hook' ) );
	}

	/**
	 * Initializes a field.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param string $name Field name.
	 * @param string $type Field type.
	 * @param array $args Field arguments.
	 */
	public function field_init( $name, $type, $args ) {

		$group = $args['group'] !== false ? $args['group'] : 'default';

		$this->fields[ $group ][] = array(
			'name' => $name,
			'type' => $type,
			'args' => $args,
		);
	}

	/**
	 * Initializes all used fields on this page for automatic saving.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $group Field group.
	 */
	public function initialize_fields( $group ) {

		if ( ! isset( $this->fields[ $group ] ) ) {

			return;
		}

		wp_nonce_field(
			"{$this->prefix}_fieldhelpers_{$group}_save_fields",
			"{$this->prefix}_fieldhelpers_{$group}_nonce"
		);

		foreach ( $this->fields[ $group ] as $i => $field ) {

			$option = $field['option_field'] === true ? '_option' : '';
			?>
            <input type="hidden"
                   name="<?php echo "{$this->prefix}_fieldhelpers{$option}_fields[{$group}][{$i}]"; ?>"
                   value="<?php echo $field['name']; ?>"/>
			<?php

			if ( $field['args']['multi_field'] ) {
				?>
                <input type="hidden"
                       name="<?php echo "{$this->prefix}_fieldhelpers_{$group}{$option}_field_{$field['name']}_multi_field"; ?>"
                       value="1"/>
				<?php
			}
		}
	}

	/**
	 * Hook for saving the post meta fields.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param int $post_ID Current post ID being saved.
	 */
	function save_meta_fields_hook( $post_ID ) {

		if ( ! isset( $_POST["{$this->prefix}_fieldhelpers_fields"] ) ) {

			return;
		}

		$groups = array_keys( (array) $_POST["{$this->prefix}_fieldhelpers_fields"] );

		foreach ( $groups as $group ) {

			$this->save_meta_fields( $group, $post_ID );
		}
	}

	/**
	 * Hook for saving the option fields.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function save_option_fields_hook() {

		if ( ! isset( $_POST["{$this->prefix}_fieldhelpers_fields"] ) ) {

			return;
		}

		$groups = array_keys( (array) $_POST["{$this->prefix}_fieldhelpers_fields"] );

		foreach ( $groups as $group ) {

			$this->save_option_fields( $group );
		}
	}

	/**
	 * Saves the meta fields.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param string $group
	 * @param int $post_ID
	 */
	private function save_meta_fields( $group, $post_ID ) {

		// Make sure we should be here!
		if ( ! wp_verify_nonce(
				$_POST["{$this->prefix}_fieldhelpers_{$group}_nonce"],
				"{$this->prefix}_fieldhelpers_{$group}_save_fields"
			) ||
		     ! current_user_can( 'edit_posts' )
		) {
			return;
		}

		// Ensure it only fires ONCE
		// TODO See where this is necessary? This should only hook/fire one time anyways.
		unset( $_POST["{$this->prefix}_fieldhelpers_{$group}_nonce"] );

		/**
		 * Filters the fields to be saved
		 *
		 * Passing a value to this will cancel saving entirely.
		 *
		 * @since 1.3.0
		 */
		$fields = apply_filters(
			"{$this->prefix}_fieldhelpers_{$group}_fields_save",
			$_POST["{$this->prefix}_fieldhelpers_fields"][ $group ],
			$post_ID
		);

		foreach ( $fields as $field ) {

			$field_db_ID = "{$this->prefix}_{$field}";

			$value = $_POST[ $field_db_ID ];

			/**
			 * Filters the value to save to the field.
			 *
			 * @since 1.3.0
			 */
			$value = apply_filters( "{$this->prefix}_fieldhelpers_{$group}_save_field_$field", $value, $post_ID );

			// If array, and told to do so, store in DB as a broken apart, non-unique meta field.
			// Someday I'd like to remove the 3rd conditional and simply assume this, but for now, to be safe,
			// it is manual.
			if ( is_array( $value ) &&
			     isset( $value[0] ) &&
			     isset( $_POST["{$this->prefix}_fieldhelpers_{$group}_field_{$field}_multi_field"] )
			) {

				// Delete all instances of meta field first, as add_post_meta will simply continuously add, forever,
				// even if the value already exists (like an indexed array)
				delete_post_meta( $post_ID, $field_db_ID );

				foreach ( $value as $_value ) {
					add_post_meta( $post_ID, $field_db_ID, $_value );
				}
			} else {

				update_post_meta( $post_ID, $field_db_ID, $_POST[ $field_db_ID ] );
			}
		}

		/**
		 * Fires after fields have been saved.
		 *
		 * @since 1.3.0
		 */
		do_action( "{$this->prefix}_fieldhelpers_{$group}_fields_saved", $post_ID, $fields );
	}

	/**
	 * Saves all option fields.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param string $group Field group.
	 */
	private function save_option_fields( $group ) {

		// Make sure we should be here!
		if ( ! wp_verify_nonce(
				$_POST["{$this->prefix}_fieldhelpers_{$group}_nonce"],
				"{$this->prefix}_fieldhelpers_{$group}_save_fields"
			) ||
		     ! current_user_can( 'manage_options' )
		) {
			return;
		}

		// Ensure it only fires ONCE
		// TODO See where this is necessary? This should only hook/fire one time anyways.
		unset( $_POST["{$this->prefix}_fieldhelpers_{$group}_options_nonce"] );

		/**
		 * Filters the option fields to be saved
		 *
		 * Passing a value to this will cancel saving entirely.
		 *
		 * @since {{VERSION}}
		 */
		$fields = apply_filters(
			"{$this->prefix}_fieldhelpers_{$group}_option_fields_save",
			$_POST["{$this->prefix}_fieldhelpers_{$group}_option_fields"]
		);

		foreach ( $fields as $field ) {

			$field_db_ID = "{$this->prefix}_{$field}";

			$value = $_POST[ $field_db_ID ];

			/**
			 * Filters the value to save to the field.
			 *
			 * @since {{VERSION}}
			 */
			$value = apply_filters( "{$this->prefix}_fieldhelpers_{$group}_save_option_field_$field", $value );

			// If array, and told to do so, store in DB as a broken apart, non-unique meta field.
			// Someday I'd like to remove the 3rd conditional and simply assume this, but for now, to be safe,
			// it is manual.
			if ( is_array( $value ) &&
			     isset( $value[0] ) &&
			     isset( $_POST["{$this->prefix}_fieldhelpers_{$group}_option_field_{$field}_multi_field"] )
			) {

				// Delete all instances of meta field first, as add_post_meta will simply continuously add, forever,
				// even if the value already exists (like an indexed array)
				delete_option( $field_db_ID );

				foreach ( $value as $_value ) {
					add_option( $field_db_ID, $_value );
				}
			} else {

				update_option( $field_db_ID, $_POST[ $field_db_ID ] );
			}
		}

		/**
		 * Fires after fields have been saved.
		 *
		 * @since {{VERSION}}
		 */
		do_action( "{$this->prefix}_fieldhelpers_{$group}_option_fields_saved", $fields );
	}
}