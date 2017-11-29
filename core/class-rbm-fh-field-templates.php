<?php
/**
 * Applies templates to fields.
 *
 * @since 1.4.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_FieldTemplates
 *
 * Applies templates to fields.
 *
 * @since 1.4.0
 */
class RBM_FH_FieldTemplates {

	/**
	 * Instance properties.
	 *
	 * @since 1.4.0
	 *
	 * @var array
	 */
	public $instance = array();

	/**
	 * RBM_FH_FieldTemplates constructor.
	 *
	 * @since 1.4.0
	 *
	 * @param array $instance Instance properties.
	 */
	function __construct( $instance = array() ) {

		$this->instance = $instance;

		$prefix = $this->instance['ID'];

		add_action( "{$prefix}_fieldhelpers_do_field", array( $this, 'do_field' ), 10, 4 );

		add_action( "{$prefix}_fieldhelpers_field_template_header", array( $this, 'template_label' ), 10, 4 );
		add_action( "{$prefix}_fieldhelpers_field_template_header", array(
			$this,
			'template_description_after_label'
		), 15, 4 );
		add_action( "{$prefix}_fieldhelpers_field_template_content", array( $this, 'template_field' ), 10, 4 );
		add_action( "{$prefix}_fieldhelpers_field_template_footer", array(
			$this,
			'template_description_beneath'
		), 10, 4 );
	}

	/**
	 * Outputs a field.
	 *
	 * @since 1.4.0
	 * @access private
	 *
	 * @param string $type Field type.
	 * @param array $args Field arguments.
	 * @param string $name Field name.
	 * @param mixed $value Field value.
	 */
	function do_field( $type, $args, $name, $value ) {

		include __DIR__ . '/fields/views/field.php';
	}

	/**
	 * Outputs the field content.
	 *
	 * @since 1.4.0
	 * @access private
	 *
	 * @param string $type Field type.
	 * @param array $args Field arguments.
	 * @param string $name Field name.
	 * @param mixed $value Field value.
	 */
	function template_field( $type, $args, $name, $value ) {

		include __DIR__ . "/fields/views/fields/field-{$type}.php";
	}

	/**
	 * Outputs the field label.
	 *
	 * @since 1.4.0
	 * @access private
	 *
	 * @param string $type Field type.
	 * @param array $args Field arguments.
	 * @param string $name Field name.
	 * @param mixed $value Field value.
	 */
	function template_label( $type, $args, $name, $value ) {

		include __DIR__ . '/fields/views/field-label.php';
	}

	/**
	 * Outputs the field description.
	 *
	 * @since 1.4.0
	 * @access private
	 *
	 * @param string $type Field type.
	 * @param array $args Field arguments.
	 * @param string $name Field name.
	 * @param mixed $value Field value.
	 */
	function template_description_after_label( $type, $args, $name, $value ) {

		if ( $args['description_placement'] === 'after_label' ) {

			$this->template_description( $type, $args, $name, $value );
		}
	}

	/**
	 * Outputs the field description.
	 *
	 * @since 1.4.0
	 * @access private
	 *
	 * @param string $type Field type.
	 * @param array $args Field arguments.
	 * @param string $name Field name.
	 * @param mixed $value Field value.
	 */
	function template_description_beneath( $type, $args, $name, $value ) {

		if ( $args['description_placement'] === 'beneath' ) {

			$this->template_description( $type, $args, $name, $value );
		}
	}

	/**
	 * Outputs the field description.
	 *
	 * @since 1.4.0
	 * @access private
	 *
	 * @param string $type Field type.
	 * @param array $args Field arguments.
	 * @param string $name Field name.
	 * @param mixed $value Field value.
	 */
	function template_description( $type, $args, $name, $value ) {

		if ( $args['description_tip'] === true ) {

			include __DIR__ . '/fields/views/field-description-tip.php';

		} else {

			include __DIR__ . '/fields/views/field-description.php';
		}
	}
}