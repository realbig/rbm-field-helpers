<?php
/**
 * Field Template: Repeater
 *
 * @since {{VERSION}}
 *
 * @var array $args Field arguments.
 * @var string $name Field name.
 * @var mixed $value Field value.
 */

defined( 'ABSPATH' ) || die();

$empty     = ! $value;
$row_count = count( $value ) >= 1 ? count( $value ) : 1;
?>

<div class="fielhelpers-field-repeater-container"
     data-fieldhelpers-field-repeater="<?php echo esc_attr( $name ); ?>"
>

    <div class="fieldhelpers-field-repeater-list"
         data-repeater-list="<?php echo esc_attr( $name ); ?>"
    >

        <div class="fieldhelpers-field-repeater-row" data-repeater-item>

			<?php if ( $args['collapsable'] ) : ?>

                <div class="fieldhelpers-field-repeater-header">

					<?php if ( $args['sortable'] ) : ?>
                        <div class="fieldhelpers-field-repeater-handle"></div>
					<?php endif; ?>

                    <div class="fieldhelpers-field-repeater-header-interior">

                        <h2 class="fieldhelpers-field-repeater-collapsable-handle"
                            data-repeater-collapsable-handle
                        >
                            <span class="collapsable-title"
                                  data-collapsable-title-default="<?php echo $args['collapsable_title']; ?>"
                            >
                                <?php echo $args['collapsable_title']; ?>
                            </span>
                            <span class="fieldhelpers-field-repeater-collapsable-collapse-icon dashicons dashicons-arrow-down-alt2"></span>
                            <input data-repeater-delete
                                   type="button"
                                   class="fieldhelpers-field-repeater-delete-button button"
                                   value="<?php echo $args['delete_item_text']; ?>"
                            />
                        </h2>

                    </div>

                </div>


			<?php elseif ( $args['sortable'] ) : ?>

                <div class="fieldhelpers-field-repeater-handle"></div>

			<?php endif; ?>

            <div class="fieldhelpers-field-repeater-content">

				<?php RBM_FH_Field_Repeater::do_fields( $name, $args ); ?>

            </div>

			<?php if ( $args['collapsable'] ) : ?>

                <div class="clearfix"></div>

			<?php else : ?>

                <div class="clearfix"></div>

                <hr/>
                <input data-repeater-delete
                       type="button"
                       class="fieldhelpers-field-repeater-delete-button button"
                       value="<?php echo $args['delete_item_text']; ?>"
                />

			<?php endif; ?>

        </div>

    </div>

    <input data-repeater-create
           type="button"
           class="fieldhelpers-field-repeater-add-button button button-primary"
           value="<?php echo $args['add_item_text']; ?>"
    />
</div>