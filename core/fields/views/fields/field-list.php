<?php
/**
 * Field Template: Table
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();

$value = array(
	'head' => array( 'HOne', 'HTwo', 'HThree' ),
	'body' => array(
		array( 'One', 'Two', 'Three' ),
		array( 'One2', 'Two2', 'Three2' ),
	),
);
?>

<div class="fieldhelpers-field-table" data-fieldhelpers-field-table data-table-name="<?php echo $name; ?>">
    <table data-table-data="<?php echo esc_attr( json_encode( $value ) ); ?>" style="display: none;">
        <thead></thead>
        <tbody></tbody>
    </table>

    <div class="fieldhelpers-field-table-loading">
        <span class="spinner is-active"></span>
    </div>

    <div class="fieldhelpers-field-table-actions" style="display: none;">
        <input data-table-create-row type="button" class="button" value="Add Row"/>
        <input data-table-create-column type="button" class="button" value="Add Column"/>
    </div>
</div>