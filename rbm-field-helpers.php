<?php
/**
 * Provides helper functions shared among all RBM plugins.
 *
 * @version 1.3.9
 *
 * @package RBMFieldHelpers
 */

defined( 'ABSPATH' ) || die();

if ( ! class_exists( 'RBM_FieldHelpers' ) ) {

	define( 'RBM_FIELD_HELPERS_VER', '1.3.9' );
	define( 'RBM_FIELD_HELPERS_URI', plugins_url( '', __FILE__ ) );
	define( 'RBM_FIELD_HELPERS_DIR', plugin_dir_path( __FILE__ ) );

	final class RBM_FieldHelpers {

		/**
		 * Instance properties.
		 *
		 * @since {{VERSION}}
		 *
		 * @var array
		 */
		public $instance = array();

		/**
		 * Fields instance.
		 *
		 * @since {{VERSION}}
		 *
		 * @var RBM_FH_Fields
		 */
		public $fields;

		/**
		 * Field Templates instance.
		 *
		 * @since {{VERSION}}
		 *
		 * @var RBM_FH_FieldTemplates
		 */
		public $templates;

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
		 *
		 * @param array $instance Instance arugments.
		 */
		function __construct( $instance = array() ) {

			$this->instance = wp_parse_args( $instance, array(
				'ID'   => 'default',
				'l10n' => array(
					'field_table' => array(
						'delete_row'    => __( 'Delete Row', 'learndash-gradebook' ),
						'delete_column' => __( 'Delete Column', 'learndash-gradebook' ),
					),
				),
			) );

			$this->includes();

			add_action( 'admin_init', array( $this, 'register_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_footer', array( $this, 'localize_data' ) );
		}

		/**
		 * Loads all plugin required files.
		 *
		 * @since 1.1.0
		 */
		private function includes() {

			require_once __DIR__ . '/core/deprecated/rbm-fh-deprecated-functions.php';
			require_once __DIR__ . '/core/class-rbm-fh-fields.php';
			require_once __DIR__ . '/core/class-rbm-fh-field-templates.php';

			$this->fields    = new RBM_FH_Fields( $this->instance );
			$this->templates = new RBM_FH_FieldTemplates( $this->instance );
		}

		/**
		 * Registers all scripts.
		 *
		 * @since 1.1.0
		 * @access private
		 */
		function register_scripts() {

			global $wp_scripts;

			// Core Admin
			wp_register_style(
				'rbm-fh-admin',
				RBM_FIELD_HELPERS_URI . '/assets/dist/css/rbm-field-helpers-admin.min.css',
				array(),
				RBM_FIELD_HELPERS_VER
			);

			wp_register_script(
				'rbm-fh-admin',
				RBM_FIELD_HELPERS_URI . '/assets/dist/js/rbm-field-helpers-admin.min.js',
				array(
					'jquery',
					'jquery-ui-sortable',
					'rbm-fh-jquery-repeater',
				),
				RBM_FIELD_HELPERS_VER,
				true
			);

			// jQuery Repeater
			wp_register_script(
				'rbm-fh-jquery-repeater',
				RBM_FIELD_HELPERS_URI . '/vendor/jquery-repeater/jquery.repeater.min.js',
				array( 'jquery' ),
				'0.1.4',
				true
			);

			// jQuery UI Datetimepicker
			wp_register_script(
				'rbm-fh-jquery-ui-datetimepicker',
				RBM_FIELD_HELPERS_URI . '/vendor/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.js',
				array( 'jquery', 'jquery-ui-datepicker' ),
				'0.1.4',
				true
			);

			wp_register_style(
				'rbm-fh-jquery-ui-datetimepicker',
				RBM_FIELD_HELPERS_URI . '/vendor/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.css',
				array(),
				RBM_FIELD_HELPERS_VER
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

			wp_enqueue_script( 'rbm-fh-admin' );
			wp_enqueue_style( 'rbm-fh-admin' );

			wp_enqueue_script( 'rbm-fh-jquery-repeater' );

			/**
			 * Load or don't load the Date/Time Picker scripts.
			 *
			 * @since {{VERSION}}
			 */
			$load_datetimepicker = apply_filters( 'rbm_fieldhelpers_load_datetimepicker', false );

			if ( $load_datetimepicker ) {

				// TODO Styles not working for this?
				wp_enqueue_script( 'rbm-fh-jquery-ui-datetimepicker' );
				wp_enqueue_style( 'rbm-fh-jquery-ui-datetimepicker' );
			}
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

			global $wp_version;

			// Localize data
			$data = apply_filters( "rbm_field_helpers_admin_data", array(
				'nonce'       => wp_create_nonce( 'rbm-field-helpers' ),
				'wp_version'  => $wp_version,
				'instance_id' => $this->instance['ID'],
				'l10n'        => $this->instance['l10n'],
			) );

			wp_localize_script( 'rbm-fh-admin', 'RBM_FieldHelpers', $data );
		}
	}
}