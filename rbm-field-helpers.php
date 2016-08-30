<?php
/**
 * Plugin Name: RBM Field Helpers
 * Description: Provides helper functions shared among all RBM plugins.
 * Version: 1.2.2
 * Author: Real Big Marketing
 * Author URI: http://realbigmarketing.com
 */

/**
 * Provides helper functions shared among all RBM plugins.
 *
 * @version 1.2.1
 *
 * @package RBMFieldHelpers
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Only load once
if ( ! defined( 'RBM_HELPER_FUNCTIONS' ) ) {

	define( 'RBM_HELPER_FUNCTIONS', true );
	define( 'RBM_FIELD_HELPERS_VER', '1.2.2' );

	final class RBM_FieldHelpers {

		/**
		 * All registered fields.
		 *
		 * @since 1.1.0
		 *
		 * @var array
		 */
		public $fields = array();

		/**
		 * Loaded scripts.
		 *
		 * @since 1.1.0
		 *
		 * @var array
		 */
		public $scripts = array();

		/**
		 * Loaded styles.
		 *
		 * @since 1.1.0
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
		 * @since 1.1.0
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
		 * @since 1.1.0
		 */
		function __construct() {

			$this->includes();

			add_action( 'admin_init', array( $this, 'register_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_footer', array( $this, 'localize_data'));

			add_action( 'save_post', array( $this, 'save_meta' ) );
		}

		/**
		 * Loads all plugin required files.
		 *
		 * @since 1.1.0
		 */
		private function includes() {

			// Load dependencies
			require_once __DIR__ . '/vendor/select2/select2-load.php';

			// Load base files
			require_once __DIR__ . '/includes/rbm-fh-field-functions.php';

			// Load fields
			require_once __DIR__ . '/includes/class-rbm-fh-field.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-checkbox.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-colorpicker.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-datepicker.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-media.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-radio.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-repeater.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-select.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-table.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-text.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-textarea.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-userkeys.php';
			require_once __DIR__ . '/includes/fields/class-rbm-fh-field-wysiwyg.php';
		}

		/**
		 * Registers all scripts.
		 *
		 * @since 1.1.0
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
		}

		/**
		 * Enqueues common scripts.
		 *
		 * @since 1.1.0
		 * @access private
		 */
		function enqueue_scripts() {

			wp_enqueue_script( 'RBM-field-helpers-admin' );
			wp_enqueue_script( 'RBM-jquery-repeater' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-datepicker' );

			wp_enqueue_style( 'jquery-ui-smoothness' );
			wp_enqueue_style( 'RBM-field-helpers-admin' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		/**
		 * Localizes data.
		 *
		 * Fired in the footer so that fields can add data to this dynamically.
		 *
		 * @since 1.1.2
		 * @access private
		 */
		function localize_data() {

			// Localize data
			$data = apply_filters( 'rbm_field_helpers_admin_data', array(
				'nonce' => wp_create_nonce( 'rbm-field-helpers' ),
			) );

			wp_localize_script( 'RBM-field-helpers-admin', 'RBM_FieldHelpers', $data );
		}

		/**
		 * Saves the field helpers post meta.
		 *
		 * @since 1.1.0
		 * @access private
		 *
		 * @param $post_ID
		 */
		function save_meta( $post_ID ) {

			// Make sure we should be here!
			if ( ! isset( $_POST['_rbm_fields'] ) ||
			     ! wp_verify_nonce( $_POST['rbm-meta'], 'rbm-save-meta' ) ||
			     ! current_user_can( 'edit_posts' )
			) {
				return;
			}

			foreach ( $_POST['_rbm_fields'] as $field ) {

				$value = $_POST[ $field ];

				// If array, and told to do so, store in DB as a broken apart, non-unique meta field.
				// Someday I'd like to remove the 3rd conditional and simply assume this, but for now, to be safe,
				// it is manual.
				if ( is_array( $value ) && isset( $value[0] ) && isset( $_POST[ "_rbm_field_{$field}_multi_field"] ) ) {

					// Delete all instances of meta field first, as add_post_meta will simply continuously add, forever,
					// even if the value already exists (like an indexed array)
					delete_post_meta( $post_ID, $field );

					foreach ( $value as $_value ) {
						add_post_meta( $post_ID, $field, $_value );
					}
				} else {

					update_post_meta( $post_ID, $field, $_POST[ $field ] );
				}
			}
		}
	}

	// Primary instantiation
	require_once __DIR__ . '/includes/rbm-fh-functions.php';
	RBMFH();
}