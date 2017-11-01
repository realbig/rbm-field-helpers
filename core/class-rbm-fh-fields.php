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
	 * Data to localize.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $data = array();

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

		$this->save = new RBM_FH_FieldsSave( $instance['ID'] );

		add_filter( 'rbm_field_helpers_admin_data', array( $this, 'localize_data' ) );
	}

	/**
	 * Localizes data for the fields on the page.
	 *
	 * @since 1.3.0
	 * @access private
	 *
	 * @param array $data Data to be localized.
	 *
	 * @return array
	 */
	public function localize_data( $data ) {

		$data[ $this->instance['ID'] ] = $this->data;

		return $data;
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
	 * Sets up field data to be localized.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @param string $name Field name.
	 * @param string $type Field type.
	 * @param array $field_args All field args (not all will be localized).
	 * @param array $args Field args to be localized.
	 */
	private function setup_data( $name, $type, $field_args, $args ) {

		// Always add some standard args
		$args['id']      = $field_args['id'];
		$args['default'] = $field_args['default'];

		if ( $field_args['repeater'] ) {

			$this->data['repeaterFields'][ $field_args['repeater'] ][ $name ] = $args;

		} else {

			$this->data[ $type ]["{$this->instance['ID']}_{$name}"] = $args;
		}
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_Text( $name, $args );

		$this->save->field_init( $name, 'text', $field->args );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_TextArea( $name, $args );

		$this->save->field_init( $name, 'textarea', $field->args );

		$this->setup_data( $name, 'textarea', $field->args, array(
			'wysiwyg'        => $field->args['wysiwyg'],
			'wysiwygOptions' => $field->args['wysiwyg_options'],
		) );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_Checkbox( $name, $args );

		$this->save->field_init( $name, 'checkbox', $field->args );

		$this->setup_data( $name, 'checkbox', $field->args );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_Radio( $name, $args );

		$this->save->field_init( $name, 'radio', $field->args );

		$this->setup_data( $name, 'radio', $field->args );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_Select( $name, $args );

		$this->save->field_init( $name, 'select', $field->args );

		$this->setup_data( $name, 'select', $field->args, array(
			'select2Options' => $field->args['select2'],
		) );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_Number( $name, $args );

		$this->save->field_init( $name, 'number', $field->args );

		$this->setup_data( $name, 'number', $field->args, array(
			'increaseInterval'    => $field->args['increase_interval'],
			'decreaseInterval'    => $field->args['decrease_interval'],
			'altIncreaseInterval' => $field->args['alt_increase_interval'],
			'altDecreaseInterval' => $field->args['alt_decrease_interval'],
			'max'                 => $field->args['max'],
			'min'                 => $field->args['min'],
			'postfix'             => $field->args['postfix'],
		) );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_Media( $name, $args );

		$this->save->field_init( $name, 'media', $field->args );

		$this->setup_data( $name, 'media', $field->args, array(
			'placeholder' => $field->args['placeholder'],
			'type'        => $field->args['type'],
			'previewSize' => $field->args['preview_size'],
			'l10n'        => array(
				'window_title' => $field->args['window_title'],
			),
		) );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_DatePicker( $name, $args );

		$this->save->field_init( $name, 'datepicker', $field->args );

		$this->setup_data( $name, 'datepicker', $field->args, array(
			'datepickerOptions' => $field->args['datepicker_args'],
		) );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_TimePicker( $name, $args );

		$this->save->field_init( $name, 'timepicker', $field->args );

		$this->setup_data( $name, 'timepicker', $field->args, array(
			'timepickerOptions' => $field->args['timepicker_args'],
		) );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_DateTimePicker( $name, $args );

		$this->save->field_init( $name, 'datetimepicker', $field->args );

		$this->setup_data( $name, 'datetimepicker', $field->args, array(
			'datetimepickerOptions' => $field->args['datetimepicker_args'],
		) );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_ColorPicker( $name, $args );

		$this->save->field_init( $name, 'colorpicker', $field->args );

		$this->setup_data( $name, 'colorpicker', $field->args );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_List( $name, $args );

		$this->save->field_init( $name, 'list', $field->args );

		$this->setup_data( $name, 'list', $field->args );
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

		$args['prefix'] = $this->instance['ID'];

		$field = new RBM_FH_Field_Table( $name, $args );

		$this->save->field_init( $name, 'table', $field->args );

		$this->setup_data( $name, 'table', $field->args );
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

		$args['prefix']          = $this->instance['ID'];
		$args['fields_instance'] = $this;

		$field = new RBM_FH_Field_Repeater( $name, $args );

		$this->save->field_init( $name, 'repeater', $field->args );

		$values = $field->value;

		// Make sure saved values line up with fields (if repeater fields change, this will break)
		if ( $values ) {

			foreach ( array_keys( $values[0] ) as $field_name ) {

				if ( ! isset( $args['fields'][ $field_name ] ) ) {

					$values = array();
					break;
				}
			}
		}

		$this->setup_data( $name, 'repeater', $field->args, array(
			'values'      => $values,
			'collapsable' => $field->args['collapsable'],
			'sortable'    => $field->args['sortable'],
			'l10n'        => array(
				'firstItemUndeletable' => $field->args['first_item_undeletable'],
				'collapsableTitle'     => $field->args['collapsable_title'],
				'confirmDeleteText'    => $field->args['confirm_delete_text'],
				'deleteItemText'       => $field->args['delete_item_text'],
				'addItemText'          => $field->args['add_item_text'],
			),
		) );
	}
}