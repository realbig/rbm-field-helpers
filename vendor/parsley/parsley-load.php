<?php
/**
 * Loads in Parsley, if not yet loaded from RBM.
 *
 * @version {{VERSION}}
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'RBM_Parsley_Loader' ) ) {

	class RBM_Parsley_Loader {

		function __construct() {

			add_action( 'init', array( $this, '_register_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, '_admin_enqueue_scripts' ) );
		}

		function _register_scripts() {

			wp_register_script(
				'parsley',
				plugins_url( '/parsley.min.js', __FILE__ ),
				array( 'jquery' ),
				'2.2.0-rc4'
			);
		}

		function _admin_enqueue_scripts() {

			if ( apply_filters( 'rbm_load_parsley', false ) ) {
				wp_enqueue_script( 'parsley' );
			}
		}
	}

	$DZS_Select2 = new RBM_Parsley_Loader();
}