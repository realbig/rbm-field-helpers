<?php
/**
 * Sets up deprecated support.
 *
 * @since {{VERSION}}
 */

defined( 'ABSPATH' ) || die();

add_action( 'plugins_loaded', 'rbm_fh_deprecated_support' );

function rbm_fh_deprecated_support() {

	if ( ! defined( 'RBM_FH_DEPRECATED_SUPPORT' ) || RBM_FH_DEPRECATED_SUPPORT !== true ) {

		return;
	}

	global $rbm_fh_deprecated_support;

	$rbm_fh_deprecated_support = new RBM_FieldHelpers();
}