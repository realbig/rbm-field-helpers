<?php
defined( 'ABSPATH' ) || die();

/**
 * Class Tests_ExternalUsage
 *
 * Tests expected usage from external sources (plugins, themes).
 *
 * @since 1.1.0
 */
class Tests_ExternalUsage extends WP_UnitTestCase {

	/**
	 * Store created post ID for usage in tests.
	 *
	 * @since 1.1.0
	 *
	 * @var int
	 */
	private $test_post_ID;

	/**
	 * @since 1.1.0
	 */
	function setUp() {

		global $post;

		parent::setUP();

		$this->test_post_ID = $post_ID = $this->factory()->post->create();
		add_post_meta( $post_ID, '_rbm_test_field', '1' );

		// Globalize post
		$post = get_post( $post_ID );
		setup_postdata( $post );
	}

	/**
	 * Test retrieval of a field.
	 *
	 * @since 1.1.0
	 */
	function test_get_field() {

		// Test against global post
		$test_field = rbm_get_field( 'test_field' );
		$this->assertEquals( '1', $test_field );

		// Test with manual post ID
		$test_field = rbm_get_field( 'test_field', $this->test_post_ID );
		$this->assertEquals( '1', $test_field );

		// Test direct echoing
		// Test against global post
		ob_start();
		rbm_field( 'test_field' );
		$test_field = ob_get_clean();
		$this->assertEquals( '1', $test_field );

		// Test with manual post ID
		ob_start();
		rbm_field( 'test_field', $this->test_post_ID );
		$test_field = ob_get_clean();
		$this->assertEquals( '1', $test_field );
	}

	/**
	 * Test retrievel of a field value.
	 *
	 * @since {{VERSION}}
	 */
	function test_field_value() {

		global $post, $rbm_fh_factory;

		update_post_meta( $this->test_post_ID, '_rbm_test_text', '1' );

		// Setup field
		ob_start();
		$field = new RBM_FH_Field_Text( 'test_text' );
		ob_get_clean();

		// Test normal usage
		$this->assertEquals( '1', $field->value );

		// Unset post to test failure
		$_post = $post;
		$post = null;

		// Setup field again
		ob_start();
		$field = new RBM_FH_Field_Text( 'test_text' );
		ob_get_clean();

		// Test failure
		$this->assertEquals( false, $field->value );
	}
}

