<?php
/**
 * Provides functions for quick calling the field helpers.
 *
 * @since 1.1.0
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes
 */

defined( 'ABSPATH' ) || die();

/**
 * Outputs a text field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_text( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Text( $name, $label, $args, $value );
}

/**
 * Outputs a textarea field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_textarea( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_TextArea( $name, $label, $args, $value );
}

/**
 * Outputs a checkbox field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_checkbox( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Checkbox( $name, $label, $args, $value );
}

/**
 * Outputs a radio field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_radio( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Radio( $name, $label, $args, $value );
}

/**
 * Outputs a select field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_select( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Select( $name, $label, $args, $value );
}

/**
 * Outputs an image field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_media( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Media( $name, $label, $args, $value );
}

/**
 * Outputs an image field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_image( $name, $label = false, $value = false, $args = array() ) {

	$args['type']               = 'image';
	$args['button_text']        = 'Upload / Choose Image';
	$args['button_remove_text'] = 'Remove Image';
	$args['window_title']       = 'Choose Image';
	$args['window_button_text'] = 'Use Image';

	rbm_do_field_media( $name, $label, $value, $args );
}

/**
 * Outputs a datepicker field.
 *
 * @since 1.1.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_datepicker( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_DatePicker( $name, $label, $args, $value );
}

/**
 * Outputs a colorpicker field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_colorpicker( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_ColorPicker( $name, $label, $args, $value );
}

/**
 * Outputs a repeater field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param array $fields
 * @param mixed $values
 */
function rbm_do_field_repeater( $name, $label = false, $fields, $values = false ) {

	$args = array( 'fields' => $fields );

	new RBM_FH_Field_Repeater( $name, $label, $args, $values );
}

/**
 * Outputs a table field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_table( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_Table( $name, $label, $args, $value );
}

/**
 * Outputs a WYSIWYG field.
 *
 * @since 1.2.0
 *
 * @param string $name
 * @param string|bool $label
 * @param string|bool $value
 * @param array $args
 */
function rbm_do_field_wysiwyg( $name, $label = false, $value = false, $args = array() ) {

	new RBM_FH_Field_WYSIWYG( $name, $label, $args, $value );
}

/**
 * User Keys field.
 *
 * @since 1.1.0
 *
 * @param bool $label
 * @param array $args
 * @param bool $value
 */
function rbm_do_field_user_keys( $label = false, $args = array(), $value = false ) {
	new RBM_FH_Field_UserKeys( $label, $args, $value );
}