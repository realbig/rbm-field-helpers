<?php
/**
 * Field: Table
 *
 * @since {{VERSION}}
 *
 * @package RBMFieldHelpers
 * @subpackage RBMFieldHelpers/includes/fields
 */

defined( 'ABSPATH' ) || die();

/**
 * Class RBM_FH_Field_Table
 *
 * @since {{VERSION}}
 */
class RBM_FH_Field_Table extends RBM_FH_Field {

	/**
	 * Field defaults.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $defaults = array(
		'default'       => array(
			'head' => array(
				'',
				'',
				'',
			),
			'body' => array(
				array(
					'',
					'',
					'',
				),
			),
		),
	);

	/**
	 * RBM_FH_Field_Table constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @var string $name
	 * @var string $label
	 * @var array $args
	 * @var mixed $value
	 */
	function __construct( $name, $label = '', $args = array(), $value = false ) {

		parent::__construct( $name, $label, $args, $value );
	}

	/**
	 * Outputs the field.
	 *
	 * @since {{VERSION}}
	 *
	 * @param string $name Name of the field.
	 * @param mixed $value Value of the field.
	 * @param string $label Field label.
	 * @param array $args Field arguments.
	 */
	public static function field( $name, $value, $label = '', $args = array() ) {

		if ( $label ) : ?>
			<p class="rbm-field-repeater-label">
				<?php echo $label; ?>
			</p>
		<?php endif; ?>
		<div class="rbm-field-table <?php echo $args['wrapper_class']; ?>">
			<table>
				<thead>
				<tr>
					<?php
					$tab_index = 1;
					$i         = 0;
					foreach ( $value['head'] as $cell_value ) :
						?>
						<th>
							<p class="rbm-field-table-delete">
								<input type="button" data-table-delete-column class="button" value="Delete"/>
							</p>

							<input type="text" name="<?php echo $name; ?>[head][<?php echo $i; ?>]"
							       value="<?php echo esc_attr( $cell_value ); ?>"
							       tabindex="<?php echo $tab_index; ?>"/>
						</th>
						<?php
						$i ++;
						$tab_index ++;
					endforeach;
					?>
					<th class="actions">
						<!-- Actions -->
					</th>
				</tr>
				</thead>

				<tbody>
				<?php
				$row_i = 0;
				foreach ( $value['body'] as $row => $cells ) :
					?>
					<tr>
						<?php
						$cell_i = 0;
						foreach ( $cells as $cell_value ) :
							?>
							<td>
								<input type="text"
								       name="<?php echo $name; ?>[body][<?php echo $row_i; ?>][<?php echo $cell_i; ?>]"
								       value="<?php echo esc_attr( $cell_value ); ?>"
								       tabindex="<?php echo $tab_index; ?>"/>
							</td>
							<?php
							$cell_i ++;
							$tab_index ++;
						endforeach;
						?>
						<td class="actions">
							<input data-table-delete-row type="button" class="button" value="Delete"/>
							<span class="rbm-field-table-sort dashicons dashicons-sort"></span>
						</td>
					</tr>
					<?php
					$row_i ++;
				endforeach;
				?>
				</tbody>
			</table>

			<input data-table-create-row type="button" class="button" value="Add Row"/>
			<input data-table-create-column type="button" class="button" value="Add Column"/>

		</div>
		<?php echo $args['description'] ? "<br/><span class=\"description\">$args[description]</span>" : ''; ?>
		<?php
	}
}