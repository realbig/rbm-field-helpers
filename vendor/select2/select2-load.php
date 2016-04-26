<?php
/**
 * Loads in Select2, if not yet loaded from RBM.
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'RBM_Select2_Loader' ) ) {

	class RBM_Select2_Loader {

		function __construct() {

			add_action( 'init', array( $this, '_register_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, '_admin_enqueue_scripts' ) );
		}

		function _register_scripts() {

			wp_register_script(
				'RBM-select2',
				plugins_url( '/rbm-select2.js', __FILE__ ),
				array( 'jquery', 'select2' ),
				'1.0.0'
			);

			wp_register_style(
				'RBM-select2',
				plugins_url( '/rbm-select2.css', __FILE__ ),
				array( 'select2' ),
				'1.0.0'
			);

			wp_register_script(
				'select2',
				plugins_url( '/select2.js', __FILE__ ),
				array( 'jquery' ),
				'4.0.0'
			);

			wp_register_style(
				'select2',
				plugins_url( '/select2.css', __FILE__ ),
				array(),
				'4.0.0'
			);
		}

		function _admin_enqueue_scripts() {

			if ( apply_filters( 'rbm_load_select2', false ) ) {

				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'select2' );
				wp_enqueue_style( 'select2' );
				wp_enqueue_script( 'RBM-select2' );
				wp_enqueue_style( 'RBM-select2' );
			}
		}
	}

	$RBM_Select2 = new RBM_Select2_Loader();
}