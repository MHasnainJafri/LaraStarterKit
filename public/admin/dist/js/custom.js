function initDataTable(selector, ajaxUrl, columns, options = {}) {
    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().destroy();
    }

    // Add checkbox column at the beginning
    columns.unshift({
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            return `<input type="checkbox" class="row-checkbox" value="${row.id}">`;
        }
    });

    // Default settings
    const defaults = {
        processing: true,
        serverSide: true,
        ajax: {
            url: ajaxUrl,
            type: 'GET',
            data: function(d) {
                d.customFilters = $(selector).data('customFilters') || {};
            }
        },
        columns: columns,
        order: options.initialOrder || [[1, 'asc']],
        searching: true,
        paging: true,
        pageLength: options.pageLength || 10,
        dom: 'Bfrtip',
        buttons: options.buttons || [],
        language: {
            emptyTable: options.emptyMessage || 'No data available'
        }
    };

    const table = $(selector).DataTable($.extend(true, {}, defaults, options));

    // Handle Select All checkbox
    $(selector).on('click', '.select-all', function () {
        const checked = $(this).prop('checked');
        $(selector).find('.row-checkbox').prop('checked', checked);
    });

    return table;
}