<?php
/**
 * Handles all fields.
 *
 * @since {{VERSION}}
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Fields
 *
 * Handles all fields.
 *
 * @since {{VERSION}}
 */
class RBM_FH_Fields {

	/**
	 * Instance properties.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $instance = array();

	/**
	 * Handles field saving.
	 *
	 * @since {{VERSION}}
	 *
	 * @var RBM_FH_FieldsSave
	 */
	public $save = array();

	/**
	 * RBM_FH_Fields constructor.
	 *
	 * @since {{VERSION}}
	 */
	function __construct( $instance = array() ) {

		$this->instance = $instance;

		// Load files
		require_once __DIR__ . '/class-rbm-fh-field.php';
		require_once __DIR__ . '/class-rbm-fh-fields-save.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-checkbox.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-colorpicker.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-datepicker.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-timepicker.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-datetimepicker.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-list.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-media.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-radio.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-number.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-repeater.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-select.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-table.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-text.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-textarea.php';
		require_once __DIR__ . '/fields/class-rbm-fh-field-wysiwyg.php';

		$this->save = new RBM_FH_FieldsSave( $instance['ID'] );
	}

	/**
	 * Alias for get_meta_field().
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $field The fieldname to get.
	 * @param bool|false $post_ID Supply post ID to get field from different post.
	 * @param array $args Arguments.
	 *
	 * @return bool|mixed|void
	 */
	public function get_field( $field, $post_ID = false, $args = array() ) {

		return $this->get_meta_field( $field, $post_ID, $args );
	}

	/**
	 * Gets a meta field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $field The fieldname to get.
	 * @param bool|false $post_ID Supply post ID to get field from different post.
	 * @param array $args Arguments.
	 *
	 * @return bool|mixed|void
	 */
	public function get_meta_field( $field, $post_ID = false, $args = array() ) {

		global $post;

		if ( $post_ID === false && ( $post instanceof WP_Post ) ) {
			$post_ID = $post->ID;
		} elseif ( $post_ID === false ) {
			return false;
		}

		$args = wp_parse_args( array(
			'sanitization' => false,
			'single'       => true,
		) );

		$value = get_post_meta( $post_ID, "{$this->instance['prefix']}_{$field}", $args['single'] );

		if ( $args['sanitization'] && is_callable( $args['sanitization'] ) ) {
			$value = call_user_func( $args['sanitization'], $value );
		}

		/**
		 * Allows filtering of the meta field value.
		 *
		 * @since {{VERSION}}
		 */
		$value = apply_filters( "{$this->instance['prefix']}_rbm_fh_get_meta_field", $value, $field, $post_ID );

		/**
		 * Allows filtering of the specific meta field value.
		 *
		 * @since {{VERSION}}
		 */
		$value = apply_filters( "{$this->instance['prefix']}_rbm_fh_get_meta_field_{$field}", $value, $post_ID );

		return $value;
	}

	/**
	 * Gets an option field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $field The fieldname to get.
	 * @param array $args Arguments.
	 *
	 * @return bool|mixed|void
	 */
	public function get_option_field( $field, $args = array() ) {

		$args = wp_parse_args( array(
			'sanitization' => false,
			'single'       => true,
		) );

		$value = get_option( "{$this->instance['prefix']}_{$field}", $args['single'] );

		if ( $args['sanitization'] && is_callable( $args['sanitization'] ) ) {
			$value = call_user_func( $args['sanitization'], $value );
		}

		/**
		 * Allows filtering of the option field value.
		 *
		 * @since {{VERSION}}
		 */
		$value = apply_filters( "{$this->instance['prefix']}_rbm_fh_get_option_field", $value, $field );

		/**
		 * Allows filtering of the specific option field value.
		 *
		 * @since {{VERSION}}
		 */
		$value = apply_filters( "{$this->instance['prefix']}_rbm_fh_get_option_field_{$field}", $value );

		return $value;
	}

	/**
	 * Outputs a text field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_text( $name, $args = array() ) {

		$this->save->field_init( $name, 'text', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_Text( $name, $args );
	}

	/**
	 * Outputs a textarea field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_textarea( $name, $args = array() ) {

		$this->save->field_init( $name, 'textarea', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_TextArea( $name, $args );
	}

	/**
	 * Outputs a checkbox field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_checkbox( $name, $args = array() ) {

		$this->save->field_init( $name, 'checkbox', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_Checkbox( $name, $args );
	}

	/**
	 * Outputs a radio field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_radio( $name, $args = array() ) {

		$this->save->field_init( $name, 'radio', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_Radio( $name, $args );
	}

	/**
	 * Outputs a select field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_select( $name, $args = array() ) {

		$this->save->field_init( $name, 'select', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_Select( $name, $args );
	}

	/**
	 * Outputs a number field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_number( $name, $args = array() ) {

		$this->save->field_init( $name, 'number', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_Number( $name, $args );
	}

	/**
	 * Outputs an image field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_media( $name, $args = array() ) {

		$this->save->field_init( $name, 'media', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_Media( $name, $args );
	}

	/**
	 * Outputs a datepicker field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_datepicker( $name, $args = array() ) {

		$this->save->field_init( $name, 'datepicker', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_DatePicker( $name, $args );
	}

	/**
	 * Outputs a timepicker field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_timepicker( $name, $args = array() ) {

		$this->save->field_init( $name, 'timepicker', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_TimePicker( $name, $args );
	}

	/**
	 * Outputs a datetimepicker field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_datetimepicker( $name, $args = array() ) {

		$this->save->field_init( $name, 'datetimepicker', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_DateTimePicker( $name, $args );
	}

	/**
	 * Outputs a colorpicker field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_colorpicker( $name, $args = array() ) {

		$this->save->field_init( $name, 'colorpicker', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_ColorPicker( $name, $args );
	}

	/**
	 * Outputs a list field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_list( $name, $args = array() ) {

		$this->save->field_init( $name, 'list', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_List( $name, $args );
	}

	/**
	 * Outputs a repeater field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param mixed $values
	 */
	public function do_field_repeater( $name, $args = array() ) {

		$this->save->field_init( $name, 'repeater', $args );

		$args['prefix']          = $this->instance['ID'];
		$args['fields_instance'] = $this;

		new RBM_FH_Field_Repeater( $name, $args );
	}

	/**
	 * Outputs a table field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_table( $name, $args = array() ) {

		$this->save->field_init( $name, 'table', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_Table( $name, $args );
	}

	/**
	 * Outputs a WYSIWYG field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function do_field_wysiwyg( $name, $args = array() ) {

		$this->save->field_init( $name, 'wysiwyg', $args );

		$args['prefix'] = $this->instance['ID'];

		new RBM_FH_Field_WYSIWYG( $name, $args );
	}
}