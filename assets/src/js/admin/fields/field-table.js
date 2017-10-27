/**
 * Table Field functionality.
 *
 * @since {{VERSION}}
 */
class FieldTable {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor($field) {

        this.$ui = {
            actions: $field.find('.fieldhelpers-field-table-actions'),
            loading: $field.find('.fieldhelpers-field-table-loading'),
            table: $field.find('table'),
            thead: $field.find('thead'),
            tbody: $field.find('tbody'),
            addRow: $field.find('[data-table-create-row]'),
            addColumn: $field.find('[data-table-create-column]'),
        }

        this.l10n = RBM_FieldHelpers.l10n['field_table'] || {};

        this.name = $field.attr('data-table-name');

        this.data = JSON.parse(this.$ui.table.attr('data-table-data'));

        this.setupHandlers();

        // Initial build
        this.buildTable();

        // Show initially
        this.$ui.table.show();
        this.$ui.actions.show();
        this.$ui.loading.hide();
    }

    /**
     * Sets up the class handlers.
     *
     * @since {{VERSION}}
     */
    setupHandlers() {

        const api = this;

        this.$ui.addRow.click((e) => {

            e.preventDefault();
            this.addRow();
        });

        this.$ui.addColumn.click((e) => {

            e.preventDefault();
            this.addColumn();
        });

        this.$ui.table.on('click', '[data-delete-row]', function (e) {

            let index = jQuery(this).closest('tr').index();

            api.deleteRow(index);
        });

        this.$ui.table.on('click', '[data-delete-column]', function (e) {

            let index = jQuery(this).closest('td').index();

            api.deleteColumn(index);
        });

        this.$ui.table.on('change', 'input[type="text"]', (e) => {

            this.updateTableData();
        });
    }

    /**
     * Gathers all data from the table.
     */
    updateTableData() {

        const api      = this;
        let $rows      = this.$ui.table.find('tbody tr');
        let data       = [];
        let currentRow = 0;

        $rows.each(function () {

            // Skip delete row
            if ( jQuery(this).hasClass('fieldhelpers-field-table-delete-columns') ) {

                return true;
            }

            let rowData     = [];
            let $cells      = jQuery(this).find('td');
            let currentCell = 0;

            $cells.each(function () {

                // Skip delete cell
                if ( jQuery(this).hasClass('fieldhelpers-field-table-delete-row') ) {

                    return true;
                }

                let $input = jQuery(this).find(`input[name="${api.name}[body][${currentRow}][${currentCell}]"]`);

                if ( !$input.length ) {

                    console.error('Field Helpers Error: Table data corrupted.');
                    return false;
                }

                rowData.push($input.val());

                currentCell++;
            });

            data.push(rowData);

            currentRow++;
        });

        this.data = data;
    }

    /**
     * Adds a row to the table.
     *
     * @since {{VERSION}}
     */
    addRow() {

        if ( !this.data.length ) {

            // Push 1 empty row with 1 empty cell
            this.data.push(['']);

        } else {

            let columns = this.data[0].length;
            let row     = [];

            for ( let i = 0; i < columns; i++ ) {
                row.push('');
            }

            this.data.push(row);
        }

        this.buildTable();
    }

    /**
     * Adds a column to the table.
     *
     * @since {{VERSION}}
     */
    addColumn() {

        if ( !this.data.length ) {

            // Push 1 empty row with 1 empty cell
            this.data.push(['']);

        } else {

            this.data.map((row) => {

                row.push('');
            });
        }

        this.buildTable();
    }

    /**
     * Deletes a row from the table.
     *
     * @since {{VERSION}}
     *
     * @param {int} index Index of row to delete.
     */
    deleteRow(index) {

        if ( this.data.length === 1 ) {

            this.data = [];

        } else {

            this.data.splice(index, 1);
        }


        this.buildTable();
    }

    /**
     * Deletes a column from the table.
     *
     * @since {{VERSION}}
     *
     * @param {int} index Index of column to delete.
     */
    deleteColumn(index) {

        if (this.data[0].length === 1 ) {

            this.data = [];

        } else {

            this.data.map((row) => {

                row.splice(index, 1);
                return row;
            });
        }

        this.buildTable();
    }

    /**
     * Builds the table based on the table data.
     *
     * @since {{VERSION}}
     */
    buildTable() {

        this.$ui.tbody.html('');

        if ( !this.data.length ) {

            this.$ui.tbody.html('');

        } else {

            this.data.map((row, row_i) => {

                let $row = jQuery('<tr/>');

                row.map((cell, cell_i) => {

                    let $cell = jQuery('<td/>');

                    $cell.append(`<input type="text" name="${this.name}[body][${row_i}][${cell_i}]" />`);

                    $cell.find('input[type="text"]').val(cell);

                    $row.append($cell);
                });

                $row.append(
                    '<td class="fieldhelpers-field-table-delete-row">' +
                    `<button type="button" data-delete-row aria-label="${this.l10n['delete_row']}">` +
                    '<span class="dashicons dashicons-no" />' +
                    '</button>' +
                    '</td>'
                );

                this.$ui.tbody.append($row);
            });


            let $deleteRow = jQuery('<tr class="fieldhelpers-field-table-delete-columns"></tr>');

            for ( let i = 0; i < this.data[0].length; i++ ) {

                $deleteRow.append(
                    '<td>' +
                    `<button type="button" data-delete-column aria-label="${this.l10n['delete_column']}">` +
                    '<span class="dashicons dashicons-no" />' +
                    '</button>' +
                    '</td>'
                );
            }

            this.$ui.tbody.append($deleteRow);
        }
    }
}

/**
 * Finds and initializes all Table fields.
 *
 * @since {{VERSION}}
 */
class FieldTableInitialize {

    /**
     * Class constructor.
     *
     * @since {{VERSION}}
     */
    constructor() {

        const api = this;

        this.fields = [];

        let $fields = jQuery('[data-fieldhelpers-field-table]');

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
            api: new FieldTable($field),
        });
    }
}

export default FieldTableInitialize;