import Field from './field.js';
import FieldsInitialize from '../fields-init';

/**
 * Repeater Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldRepeater extends Field {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        super($field, 'repeater');

        this.initField();
    }

    /**
     * Initializes the Repeater.
     *
     * @since {{VERSION}}
     */
    initField() {

        this.$repeaterList = this.$field.find('.fieldhelpers-field-repeater-list');

        const api = this;

        this.$field.repeater({
            show: function () {
                api.repeaterShow(jQuery(this));
            },
            hide: function (deleteItem) {
                api.repeaterHide(jQuery(this), deleteItem)
            },
            ready: function (setIndexes) {
                api.$repeaterList.on('sortupdate', setIndexes);
            },
            isFirstItemUndeletable: api.options.isFirstItemUndeletable,
            defaultValues: api.options.values,
        });

        // Delete first item if allowed and empty
        if ( !this.options.isFirstItemUndeletable && !this.options.values ) {

            this.$repeaterList.find('.fieldhelpers-field-repeater-row').remove();
        }

        if ( this.options.collapsable ) {

            this.initCollapsable();
        }

        if ( this.options.sortable ) {

            if ( !jQuery.isFunction(jQuery.fn.sortable) ) {

                console.error('Field Helpers Error: Trying to initialize sortable Repeater field but "jquery-ui-sortable" ' +
                    'is not enqueued.');
                return;

            } else {

                this.initSortable();
            }
        }

        this.$field.trigger('repeater-init', [this.$field]);
    }

    /**
     * Initializes the Collapsable feature, if enabled.
     *
     * @since {{VERSION}}
     */
    initCollapsable() {

        const api = this;

        this.$field.on('click touchend', '[data-repeater-collapsable-handle]', function () {
            console.log('click');
            api.toggleCollapse(jQuery(this).closest('.fieldhelpers-field-repeater-row'));
        });
    }

    /**
     * Initializes the Sortable feature, if enabled.
     *
     * @since {{VERSION}}
     */
    initSortable() {

        const api = this;

        this.$repeaterList.sortable({
            axis: 'y',
            handle: '.fieldhelpers-field-repeater-handle',
            forcePlaceholderSize: true,
            placeholder: 'fieldhelpers-sortable-placeholder',
            stop: function (e, ui) {

                api.$repeaterList.trigger(
                    'list-update',
                    [api.$repeaterList]
                );
            }
        });
    }

    /**
     * Toggles a repeater item collapse.
     *
     * @since {{VERSION}}
     *
     * @param {jQuery} $item
     */
    toggleCollapse($item) {

        let $content = $item.find('.fieldhelpers-field-repeater-content').first();
        let status   = $item.hasClass('opened') ? 'closing' : 'opening';

        if ( status === 'opening' ) {

            $content.stop().slideDown();
            $item.addClass('opened');
            $item.removeClass('closed');

        } else {

            $content.stop().slideUp();
            $item.addClass('closed');
            $item.removeClass('opened');
        }
    }

    /**
     * Shows a new repeater item.
     *
     * @since {{VERSION}}
     *
     * @param {jQuery} $item Repeater item row.
     */
    repeaterShow($item) {

        this.$field.trigger('repeater-before-add-item', [$item]);

        $item.slideDown();

        if ( this.$repeaterList.hasClass('collapsable') ) {

            $item.addClass('opened').removeClass('closed');

            // Hide current title for new item and show default title
            $item.find('.fieldhelpers-field-repeater-header span.collapsable-title').html($item.find('.fieldhelpers-field-repeater-header span.collapsable-title').data('collapsable-title-default'));

            $item.find('.collapse-icon').css({'transform': 'rotate(-180deg)'});

        }

        // Re-initialize fields in new row
        new FieldsInitialize($item);

        this.$field.trigger('repeater-add-item', [$item]);
    }

    /**
     * Removes a repeater item.
     *
     * @since {{VERSION}}
     *
     * @param {jQuery} $item Repeater item row.
     * @param {function} deleteItem Callback for deleting the item.
     */
    repeaterHide($item, deleteItem) {

        if ( confirm(this.options.l10n['confirm_delete_text']) ) {

            $item.trigger('repeater-before-delete-item');
            this.$field.trigger('repeater-before-delete-item', [$item]);

            $item.slideUp(400, () => {

                deleteItem();
                this.$field.trigger('repeater-delete-item', [$item]);
            });
        }
    }
}

/**
 * Finds and initializes all Repeater fields.
 *
 * @since {{VERSION}}
 */
class FieldRepeaterInitialize {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     *
     * @param {jQuery} $root Root element to initialize fields inside.
     */
    constructor($root) {

        const api = this;

        this.fields = [];

        let $fields = $root.find('[data-fieldhelpers-field-repeater]');

        if ( $fields.length ) {

            $fields.each(function () {

                api.initializeField(jQuery(this));
            });
        }
    }

    /**
     * Initializes the field.
     *
     * @since {{VERSION}}
     *
     * @param {jQuery} $field
     */
    initializeField($field) {

        this.fields.push({
            $field,
            api: new FieldRepeater($field),
        });
    }
}

export default FieldRepeaterInitialize;