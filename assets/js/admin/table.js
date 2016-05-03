(function ($) {
    'use strict';

    $(function () {

        var $tables = $('.rbm-field-table');

        if ($tables.length) {
            tables_init($tables);
        }

        function tables_init($tables) {

            $tables.each(function () {

                var $this = $(this),
                    $table = $(this).find('table');

                $table.find('thead th').first().find('[data-table-delete-column]').prop('disabled', true);
                $table.find('tbody tr').first().find('[data-table-delete-row]').prop('disabled', true);

                $table.find('tbody').sortable({
                    axis: 'y',
                    handle: '.rbm-field-table-sort',
                    update: table_sortable_update
                });

                $(this).find('[data-table-create-row]').click(function () {

                    var $table = $(this).closest('.rbm-field-table').find('table'),
                        $row = $table.find('tbody tr').first().clone(true, true);

                    $row.find('[data-table-delete-row]').prop('disabled', false);
                    $row.find('input[type="text"]').val('');

                    $table.find('tbody').append($row);

                    table_reset($this);
                });

                $(this).find('[data-table-create-column]').click(function () {

                    var $table = $(this).closest('.rbm-field-table').find('table'),
                        $th = $table.find('thead th').first().clone(true, true),
                        $td = $table.find('tbody td').first().clone(true, true);

                    $th.find('input[type="text"]').val('');
                    $th.find('[data-table-delete-column]').prop('disabled', false);

                    $td.find('input[type="text"]').val('');

                    $table.find('thead tr th:last-of-type').before($th);
                    $table.find('tbody tr td:last-of-type').before($td);

                    table_reset($this);
                });

                $(this).find('[data-table-delete-row]').click(function () {

                    $(this).closest('tr').remove();

                    table_reset($this);
                });

                $(this).find('[data-table-delete-column]').click(function () {

                    var $table = $(this).closest('table'),
                        index = $(this).closest('th').index();

                    $table.find('th:nth-child(' + (index + 1) + ')').remove();
                    $table.find('td:nth-child(' + (index + 1) + ')').remove();

                    table_reset($this);
                });
            });
        }

        function table_reset($tables) {

            $tables.each(function () {

                $(this).find('thead th').each(function () {

                    if ($(this).hasClass('actions')) {
                        return true;
                    }

                    var index = $(this).index(),
                        name = $(this).find('input[type="text"]').attr('name'),
                        id = '[' + index + ']';

                    $(this).find('input[type="text"]').attr('name', name.replace(/\[\d]/, id));
                });

                $(this).find('tbody td').each(function () {

                    if ($(this).hasClass('actions')) {

                        if ($(this).closest('tr').index() === 0) {
                            $(this).find('[data-table-delete-row]').prop('disabled', true);
                        } else {
                            $(this).find('[data-table-delete-row]').prop('disabled', false);
                        }

                        return true;
                    }

                    var row_index = $(this).closest('tr').index(),
                        cell_index = $(this).index(),
                        name = $(this).find('input[type="text"]').attr('name'),
                        id = '[' + row_index + '][' + cell_index + ']';

                    $(this).find('input[type="text"]').attr('name', name.replace(/\[\d]\[\d]/, id));
                });
            });
        }

        function table_sortable_update(e, ui, a) {

            table_reset(ui.item);
        }
    });
})(jQuery);